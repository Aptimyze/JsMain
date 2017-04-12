<?php

// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
include_once(JsConstants::$docRoot."/commonFiles/crmLoginFunctions.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
if(!function_exists("filter_page_redirection"))
{
	function filter_page_redirection($profileid)
	{	
		$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID=$profileid AND ((HARDSOFT='Y') OR (HARDSOFT='N' AND COUNT>2))";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
	    if(!mysql_num_rows($res))
	    {
			return "Y";
		}
		else 
			return "N";
					
	}
}

if(!function_exists("filter_page_redirection_decline"))
{
	function filter_page_redirection_decline($profileid)
	{	
		$sql="SELECT * FROM newjs.FILTERS WHERE PROFILEID=$profileid AND HARDSOFT='Y'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
	    if(!mysql_num_rows($res))
	    {
			return "Y";
		}
		else 
			return "N";
					
	}
}

/**
	This function is used to check if the photo privacy layer is to be shown to a user on logging in.
	This layer is not shown to
	1. incomplete users
	2. un-activated users.
	3. users who have seen this layer 3 times
	4. users who have selected option 3 (to remove photos) from this layer before
	5. paid members
	This layer is shown to users who
	1. have JPROFILE.PHOTO_DISPLAY=C
	2. have JPROFILE.ENTRY_DT older than 1 month
**/
function checkPhotoPrivacy($profileid,$row1)
{
	$sql = "SELECT ID,OPTION_SELECTED FROM MIS.PHOTO_PRIVACY_LAYER_LOG WHERE PROFILEID=$profileid ";
	$res=mysql_query_decide($sql);// or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
	while($row=mysql_fetch_assoc($res))
	{
		$counter++;
		if($counter >= 3)
			return 'N';
		if($row['OPTION_SELECTED'] == 3 )
			return 'N';
	}
	return checkIfPhotoPrivacyLayerToBeShown($profileid,$row1);
//	if($counter == 0)
//		return 'Y';
}
		
/**
	This function is used to check if the photo privacy layer is to be shown to a user on logging in based on JPROFILE fields.
	This function is called from the function checkPhotoPrivacy() in this file.
**/
function checkIfPhotoPrivacyLayerToBeShown($profileid,$row)
{

/*
	$sql = "SELECT ENTRY_DT, PHOTO_DISPLAY, SUBSCRIPTION, ACTIVATED, INCOMPLETE FROM newjs.JPROFILE WHERE PROFILEID = $profileid";
	$res=mysql_query_decide($sql);// or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_assoc($res))
*/
	if($row["HAVEPHOTO"]=='Y' || $row["HAVEPHOTO"]=='U')
	{
		if($row['PHOTO_DISPLAY'] == 'C')
		{
			if(strstr($row['SUBSCRIPTION'],'F') || strstr($row['SUBSCRIPTION'],'X'))
				;
			elseif($row['ACTIVATED'] == 'Y' && $row['INCOMPLETE'] != 'Y')
			{
				if($row['ENTRY_DT'] < date("Y-m-d",JSstrToTime("-1 month"))." 00:00:00")
					return 'Y';
			}
		}
	}
	return 'N';
}

function duplication_fields_insertion($dup_fields,$profileid)
{
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions_edit_profile.php");
	$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
	include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
	include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
	if($dup_fields == "invalid_dup_fields")
	{
		$val = FieldMap::getFieldLabel("flagval","sum","0");
		insert_in_duplication_check_fields($profileid,'new',$val);
	}
	else
	{
		$res=get_from_duplication_check_fields($profileid);
		if($res[TYPE]=='NEW')
			return;
		if($res)
			$val=$res[FIELDS_TO_BE_CHECKED];
		else
			$val=0;
		foreach($dup_fields as $field){
			$val=Flag::setFlag($field,$val,'duplicationFieldsVal');
		}
		insert_in_duplication_check_fields($profileid,'edit',$val);
	}

}
function checkPhoneVerificationLayerCondition($profileid)
{
	global $lay_status_profile;
	if($lay_status_profile[$profileid])
		return $lay_status_profile[$profileid];
	$lay_status_profile[$profileid]=false;

        $sql="select PROFILEID,STD,PHONE_RES,PHONE_MOB,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,ISD from newjs.JPROFILE WHERE PROFILEID='".$profileid."'";
	$res=mysql_query_decide($sql);
	if($row=mysql_fetch_array($res))
	{
		$ph_layer_status =get_phoneVerifyLayer($row);
		if($ph_layer_status=='')
			$lay_status_profile[$profileid]=false;
		else
			$lay_status_profile[$profileid]=true;
	}
	return $lay_status_profile[$profileid];
}
function get_limit_message($profileid,$subscription)
{
	$message="";

	//Getting limit for particular user.
	$limits=set_contact_limit($subscription);
	$day_limit=$limits[0];
	$week_limit=$limits[1];
	$month_limit=$limits[2];
	$overall_limit=$limits[3];
	$notvalidnumber_limit=$limits[4];
	//Getting how many contacts user made.
	$contact_status=unserialize(memcache_call($profileid));
	if(!is_array($contact_status))
		return "";	

	$day_cont=$contact_status[TODAY_INI_BY_ME];
	$week_cont=$contact_status[WEEK_INI_BY_ME];
	$month_cont=$contact_status[MONTH_INI_BY_ME];
	$overall_cont=$contact_status[TOTAL_CONTACTS_MADE];
	
	//By default day is assumed to be the threshold crosser.
	$highest='day';
	$day=($day_cont*100)/$day_limit;
	$week=($week_cont*100)/$week_limit;
	if($week>$$highest)
		$highest='week';
	$month=($month_cont*100)/$month_limit;
	if($month>$$highest)
		$highest='month';
	$overall=($overall_cont*100)/$overall_limit;
	if($overall>$$highest)
		$highest='overall';
	/*if(checkPhoneVerificationLayerCondition($profileid))
	{
		$overall_cont=get_dup_overall_cnt($profileid);
		$notvalidnumber=($overall_cont*100)/$notvalidnumber_limit;
		if($notvalidnumber>$$highest)
		{
			$overall=$notvalidnumber;
			$highest='overall';
			$overall_limit=$notvalidnumber_limit;
		}
	}
	*/
	if($$highest>=60 && $$highest<=100)
	{
		if($highest=='day')
		{
			$message="You can only send ".($day_limit-$day_cont)." more Expressions of interest today";
			if(($day_limit-$day_cont)<=0)
				$message="You cannot send any more Expressions of interest today";
		}
		if($highest=='month')
		{
			$date=date("l, jS F",mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));
			$message="You can only send ".($month_limit-$month_cont)." more Expressions of interest till this month ending $date";
			if(($month_limit-$month_cont)<=0)
				$message="You cannot send any more Expressions of interest till this month ending $date";
		}
		if($highest=='week')
                {       
			$cur_date=6-date("w");
                        $date=date("l, jS F",mktime(0, 0, 0, (date('m')), date("d")+$cur_date, date('Y')));
                        $message="You can only send ".($week_limit-$week_cont)." more Expressions of interest till this week ending $date";
			if(($week_limit-$week_cont)<=0)
				$message="You cannot send any more Expressions of interest till this week ending $date";

                }
		if($highest=='overall')
		{
			$message="You can only send ".($overall_limit-$overall_cont)." more Expressions of interest";
			if(($overall_limit-$overall_cont)<=0)
				$message="You cannot send any more Expressions of interest";
		}
	}
	return $message;
	
}
function get_dup_overall_cnt($profileid)
{
	//When gone live with duplication.
        $dupLiveDate="2012-10-10";
        $dup_live_diff=floor((JSstrToTime(date("Y-m-d")) - JSstrToTime($dupLiveDate))/86400);
	$profArray=array();
	if($profileid)
	{
//		$mysqlObj=new Mysql;
		$senderShard=JsDbSharding::getShardNo($profileid);
		$dbMessageLogObj=new NEWJS_MESSAGE_LOG($senderShard);
		//$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		//$myDb=$mysqlObj->connect("$myDbName");
		$res=$dbMessageLogObj->getMessagesBasedOnType($profileid,"I");
		//$sql="select RECEIVER, DATEDIFF(now(),`DATE`) as `TIME` from newjs.MESSAGE_LOG where SENDER='$profileid' and `TYPE`='I' order by `DATE` DESC";
		//$res=$mysqlObj->executeQuery($sql,$myDb);
			
		foreach($res as $key=>$row)
		{
			if($row['TIME']<=$dup_live_diff)
				$profArray[$row[RECEIVER]]=1;
			else
				unset($profArray[$row[RECEIVER]]);
		}
	}	
		return count($profArray);
}

function set_contact_limit($subscription)
{
	$day_limit=100;
	$weekly_limit=100;
	$month_limit=400;
	$overall_limit=400;
	$notValidNumber_limit=10;
	if(isPaid($subscription))
	{
		$day_limit=200;
                $weekly_limit=400;
                $month_limit=800;
                $overall_limit=800000;
		$notValidNumber_limit=100;		
		
	}
	if(isOfflineMember($subscription))
	{
		$day_limit=225;
		$weekly_limit=575;
		$month_limit=1400;
		$overall_limit=800000;
		$notValidNumber_limit=100000;
	}
	//$overall_limit=1;
	$limit[0]=$day_limit;
	$limit[1]=$weekly_limit;
	$limit[2]=$month_limit;
	$limit[3]=$overall_limit;
	$limit[4]=$notValidNumber_limit;
	return $limit;
	
}
function check_profile_percent()
{
}
function get_contact_status_dp($sender_profileid,$receiver_profileid)
{
        global $NUDGES;
        global $n_source;
        $yday=mktime(0,0,0,date("m"),date("d")-30,date("Y")); // To get the time for previous day
        $back_30_days=date("Y-m-d",$yday);

        //Used to check whether to shift the awaiting response person to archive when he viewed by the login user
        global $allow_shift_archive;

        $response = 0;
	if($sender_profileid && $receiver_profileid)
        {
	        $sendersIn=$sender_profileid.",".$receiver_profileid;
        	$receiversIn=$sender_profileid.",".$receiver_profileid;
		$contactResult=getResultSet("SENDER,TYPE,MSG_DEL,SEEN,DATEDIFF(now(),`TIME`)",$sendersIn,'',$receiversIn,'','','','','','',"Y",'');
	}
	else
                return 0;

        if(is_array($contactResult))
        {
                $may_present=1;
                foreach($contactResult as $key=>$value)
                {
                        $last_data=$contactResult[$key];
                        if($contactResult[$key]['SENDER'] == $receiver_profileid)
                        {
                                $response = 1;
                                break;
                        }
                }
                if($last_data['TYPE']=='I')
                {
                        if($last_data['WAITING']>30)
                                $allow_shift_archive=0;
                }
		if($response)
                {
                        $new_myrow['R_TYPE'] = "R".$contactResult[$key]['TYPE'];
                        $new_myrow['TYPE'] = $contactResult[$key]['TYPE'];
                        $new_myrow['MSG_DEL'] = $contactResult[$key]['MSG_DEL'];
			$new_myrow['SEEN']=$contactResult[$key]['SEEN'];
                        $return= $new_myrow;
                }
                else
                {
                        $myrow=$contactResult[0];
                        return $myrow;
                }
        }

        return $return;

}
function common_assign($data)
{
        global $smarty;
	global $CALL_NOW;
	global $CALL_DIRECT;
	$CALL_NOW=0;
	$CALL_DIRECT=1;
	if($CALL_DIRECT)
		$smarty->assign("CALL_DIRECT",1);
	if($CALL_NOW)
		$smarty->assign("CALL_NOW",1);
	$kundli_link=3;
        if($data['PROFILEID'])
        {
			
			$smarty->assign("LOGIN",1);
			$smarty->assign("USERNAME",$data['USERNAME']);
			$smarty->assign("LOGGED_PERSON_USERNAME",$data['USERNAME']);
			$smarty->assign("LOGGED_PERSON_PROFILEID",$data['PROFILEID']);
			//Paid memeber or free member for header link name changes
			if($data[SUBSCRIPTION]!=""){
				 $smarty->assign("subscriptionHeader",0);
			}
			else
				 $smarty->assign("subscriptionHeader",1);
			$key = $data["PROFILEID"]."_KUNDLI_LINK";
			if(JsMemcache::getInstance()->get($key))
			{
					$kundli_link = JsMemcache::getInstance()->get($key);
					$smarty->assign("kundli_link",$kundli_link);
			}
			else
			{
				$naObj = ProfileAstro::getInstance();
				if($naObj->getIfAstroDetailsPresent($data["PROFILEID"]))
				{
					if(JsConstants::$alertServerEnable) {
						$kakcc = new KUNDLI_ALERT_KUNDLI_CONTACT_CENTER("newjs_slave");
						if($kakcc->ifKundliMatchesExist($data["PROFILEID"]))
							$kundli_link = 2;
						else
							$kundli_link = 3;
						unset($kakcc);
					}
					else
					{
						$kundli_link = 3;
					}
				}
				else{
						$kundli_link = 1; 
					}
				unset($naObj);
				JsMemcache::getInstance()->set($key,$kundli_link,1800);
				$smarty->assign("kundli_link",$kundli_link);
				
			}
			savesearch_onsubheader($data['PROFILEID']);
        }
        else
			$smarty->assign("kundli_link",$kundli_link);
		
		
	$smarty->assign("TRACK_HEAD",BrijjTrackingHelper::getHeadTrackJs());
	$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs(0,true,2,"https://track.99acres.com/images/zero.gif"));
	$smarty->assign("TRACK_JSFLAG",BrijjTrackingHelper::setJsLoadFlag(0));
	$smarty->assign("TRACK_JSFLAGTRACK",BrijjTrackingHelper::setJsLoadFlag(1));
        //Assigning variable that will be called by thickbox class when window is reloaded by login layer
	$smarty->assign("CALL_THICKBOX",urlencode($_GET['CALL_ME']));
        $smarty->assign("ID_CHECKED",urlencode($_GET['ID_CHECKED']));
	$smarty->assign("AFTER_LOGIN_CALL",stripslashes($_GET['after_login_call']));
}
function set_call_help($data)
{ 
	if(JsConstants::$AndroidPromotion)
	{
		if($_COOKIE['show_app_prom']!=1)
		{
			global $smarty;
		
		
			$showPromo['/index.php']=1;
			$showPromo['/profile/mainmenu.php']=1;
			$scriptname=$_SERVER['PHP_SELF'];
			$scriptname=str_replace("/P/","/profile/",$scriptname);
			if($scriptname=="/profile/mainmenu.php")
			{
				if($data['PROFILEID'] && LoggedInAppPromo($data['PROFILEID']))
				{
					$smarty->assign("SHOW_HELP",$smarty->fetch("show_app_promo.tpl"));
					return;
				}
			}
			elseif($showPromo[$scriptname])
			{
				$smarty->assign("SHOW_APP_URL","https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Ddesktop%26utm_content%3DBottom_Right_popup_D%26utm_campaign%3DJSAA");

				$smarty->assign("SHOW_HELP",$smarty->fetch("show_app_promo.tpl"));
				return;
			}
		}
	}
	if($_COOKIE['close_help']!=1)
	{
		global $smarty;
		if($data['PROFILEID'])
			$login=1;
		else
			$login=0;
		if(isPaid($data['SUBSCRIPTION']))
			$paid=1;
		else
			$paid=0;

		if($_SERVER["DOCUMENT_ROOT"])
		{
			$roll=0;
			if(strstr($_GET['profilechecksum'],$data['PROFILEID'])  || $_GET['ownview']==1)
				$editPage=1;
			else
				$editPage=0;
			if($_COOKIE['IS_NRI'])
			{
				$smarty->assign("IS_NRI",1);
			}
			$u_agent = $_SERVER['HTTP_USER_AGENT']; 
			if(preg_match('/MSIE\ 6\.0/i',$u_agent))
			{
				$ub = True;
			} 
			$ie_any=false;
			if(preg_match('/MSIE/i',$u_agent))
			{
				$ie_any = true;
			}

			$MESSAGE['/index.php'][0][0]="Need Help to register ?";

			$MESSAGE['/profile/mainmenu.php'][1][0]="Need Help to<br> contact members ?";
			$MESSAGE['/profile/mainmenu.php'][1][1]="Need Help to<br> contact members ?";

			//$MESSAGE['HOME'][1][0]="Find the perfect Jeevansathi. Need Help to register ?";
			
			$MESSAGE['/search/perform'][0][0]="Find the perfect Jeevansathi.<BR>Need Help to register ?";
			$MESSAGE['/search/perform'][1][0]="Find the perfect Jeevansathi.<br>For Search Tips,";
			$MESSAGE['/search/perform'][1][1]="Find the perfect Jeevansathi.<br>For Search Tips,";

			//Detailed profile page.
			if($editPage==0)
			{
				$MESSAGE['/profile/viewprofile.php'][0][0]="To contact this member";
		
				$MESSAGE['/profile/viewprofile.php'][1][0]="To contact this member,";
			}
			else
				$MESSAGE['/profile/viewprofile.php'][1][0]="For tips to get more <br>responses on your profile";

			
			$MESSAGE['/profile/contacts_made_received.php'][1][0]="Need Help to<br>contact members ?";


			$MESSAGE['/profile/mem_comparison.php'][0][0]="Need help on<br>membership options ?";

			$MESSAGE['/profile/mem_comparison.php'][1][0]="Need help on<br>membership options ?";

			$MESSAGE['/profile/payment.php'][0][0]="Need help on <br>payment options ?";
			$MESSAGE['/profile/payment.php'][1][0]="Need help on <br>payment options ?";

			$MESSAGE["OTHER"][0][0]="Need Help ?";
			$MESSAGE["OTHER"][1][0]="Need Help ?";
			$MESSAGE["OTHER"][1][1]="Need Help ?";
			
			$scriptname=$_SERVER['PHP_SELF'];
			$scriptname=str_replace("/P/","/profile/",$scriptname);
			if($MESSAGE[$scriptname])
			{
				$mes=$MESSAGE[$scriptname][$login][$paid];
			}
			else
				$mes=$MESSAGE["OTHER"][0][0];

			if($mes)
			{
				$smarty->assign("CALL_HELP_MES",$mes);
				$smarty->assign("is_ie",$ub);	
				$smarty->assign("ie_any",$ie_any);
				$smarty->assign("SHOW_HELP",$smarty->fetch("call_help.htm"));
			}
			else
			{
				$smarty->assign("CALL_HELP_MES","");
                                $smarty->assign("is_ie",0);
                                $smarty->assign("ie_any",0);
                                $smarty->assign("SHOW_HELP","");
			}
			
		}
	}	
}
//Function that run every time user is login to knew about his/her save searches , that to be shown in sub head
function savesearch_onsubheader($profileid)
{
        global $smarty;
        if($profileid)
        {
                $smarty->assign("profileid",$profileid);

	/*
		//Key for storing save search data in memcache.
		
		$key=$profileid."SAVESEARCH";
		$value=unserialize(memcache_call($key));
		if(!$value)
		{
			$sql="SELECT SQL_CACHE SEARCH_NAME,ID FROM SEARCH_AGENT WHERE PROFILEID='$profileid'";
	                $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
        	        while($res=mysql_fetch_array($result))
                	{
                        	$search_agent_name[]=addslashes(stripslashes($res['SEARCH_NAME']));
	                        $search_agent_id[]=$res['ID'];
        	        }
			if($search_agent_name)
			{
				$memcache_data[0]=$search_agent_name;
				$memcache_data[1]=$search_agent_id;
				memcache_call($key,serialize($memcache_data),3600);
			}
		}
		else
		{
			if(is_array($value))
			{
				$search_agent_name=$value[0];
				$search_agent_id=$value[1];
			}
		}
	*/
        }
	/*
        $smarty->assign("SEARCH_AGENT_NAME",$search_agent_name);;
        $smarty->assign("SEARCH_AGENT_ID",$search_agent_id);
        $smarty->assign("SEARCHESCOUNT",count($search_agent_id));
	//Added for symfony 
	if(strstr($_SERVER['PHP_SELF'],'symfony_index.php') || strstr($_SERVER['PHP_SELF'],'jeevansathi_dev.php'))
		sfContext::getInstance()->getRequest()->setAttribute("searchesCount",count($search_agent_id));
	*/
}

function getCallNowSetting($receiverProfileId){
	global $CALL_NOW;
	if($receiverProfileId){
		if((is_string($receiverProfileId) && strstr($receiverProfileId,"'")) || is_array($receiverProfileId)){
			if(is_array($receiverProfileId)) $receiverProfileIds = "'".implode("','",$receiverProfileId)."'";
			else $receiverProfileIds = $receiverProfileId;
			$callnowSetting = array();
			$sql = "SELECT CALL_ANONYMOUS,PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID IN ($receiverProfileIds)";
			//$sql = "SELECT 'Y' as CALL_ANONYMOUS,PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN ($receiverProfileIds)";
			$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row=mysql_fetch_array($res)){
				if($row["CALL_ANONYMOUS"] == "Y" && $CALL_NOW)
					$callnowSetting[$row["PROFILEID"]] = $row["CALL_ANONYMOUS"];
				else $callnowSetting[$row["PROFILEID"]] = "";
			}
		}else{
			if(!$CALL_NOW) return false;
			$callnowSetting = false;
			$sql = "SELECT CALL_ANONYMOUS,PROFILEID FROM newjs.JPROFILE_CONTACT WHERE PROFILEID = $receiverProfileId";
			$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($row=mysql_fetch_array($res)){
				if($row["CALL_ANONYMOUS"] == "Y")
					$callnowSetting = true;
			}
//return true;
		}
	}
	return $callnowSetting;
}

function isPaid($subscription)
{
	$paid = 0;
        if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D") || strstr($subscription,"F"))
                $paid=1;
	return $paid;
}

function isEvalueMember($subscription)
{
	$eValue = false;
        if(strstr($subscription,"F,D") || strstr($subscription,"D,F") || strstr($subscription,"D"))
                $eValue=true;
	return $eValue;
}

function isErishtaMember($subscription)
{
	$eRishta = false;
	if(strstr($subscription,"F"))
		$eRishta = true;
	return $eRishta;
}

function isOfflineMember($subscription)
{
        $offline = false;
        if(strstr($subscription,"T"))
                $offline = true;
        return $offline;
}
/*Function called by symfony profile module
	This is required since we have to use global variables, that should be declared in symfony module.
*/
function symCalling($jprofile_results,$datas,$contact_status_new,$NUDGES,$spammer='',$filter='',$contact_limit_reach='',$samegender,$contact_limit_messages,$smartys)
{
	global $jprofile_result;
	global $data;
	global $contact_limit_message;
	global $contact_limit_reached;
	global $smarty;
	global $fromViewprofile;
	$fromViewprofile=1;	
	$jprofile_result=$jprofile_results;
	$data=$datas;
	$contact_limit_message=$contact_limit_messages;
	$contact_limit_reached=$contact_limit_reach;
	$smarty=$smartys;
	if(checkPhoneVerificationLayerCondition($data[PROFILEID]))
        {
                $smarty->assign('PH_UNVERIFIED_STATUS',1);
        }
	express_page($jprofile_result,$data,$contact_status_new,"",$spammer,$filter,$contact_limit_reached,$samegender);
}
function express_page($jprofile_result,$data,$contact_status_new,$NUDGES,$spammer='',$filter='',$contact_limit_reached='',$samegender,$fixed='',$action='')
{
	
        global $smarty;        
	global $member_101,$member_101_details;
	global $contact_limit_message;
	//Setting filter paramter to jprofile_result array, so that can be used easily.
	$filterout=0;
	if($spammer)
		$filterout=1;
	if($filter)
		$filterout=1;
	$jprofile_result['IS_FILTER']=$filterout;
	if($samegender)
		$jprofile_result['SAME_GENDER']=1;

	global $contact_limit_message;	
	$error_message = "";
	if($samegender==1)
                $error_message="You cannot initiate contact with profile(s) of the same gender.";
        if($contact_limit_reached)
		if($contact_limit_message)
                        $error_message=str_replace("'","&#039;",$contact_limit_message);
                else
                        $error_message="You cannot contact this profile as you have reached your contact limit";

        //Now spammer will be treated as filtered out profiles.
        //if($spammer)
        //      $error_message="You Cannot contact this Profile as you are marked spammer by Jeevansathi.com";  
	
	if($action=='view_contact')
		$error_message="";
        if($error_message && !isset($_GET['ONLY_LAYER']))
        {
                $smarty->assign("ERROR_MESSAGE",$error_message);
                return false;
        }
	$smarty->assign("ERROR_MESSAGE",$error_message);
        $sender_id=$jprofile_result['viewer']['PROFILEID'];
        $receiver_id=$jprofile_result['viewed']['PROFILEID'];
        $sender_sub=$jprofile_result['viewer']['SUBSCRIPTION'];
        $receiver_sub=$jprofile_result['viewed']['SUBSCRIPTION'];

        if(strstr($sender_sub,"F,D") || strstr($sender_sub,"D,F") || strstr($sender_sub,"D"))
                $sen_sub="EV";
        elseif(strstr($sender_sub,"F"))
                $sen_sub="ER";
        if(strstr($receiver_sub,"F,D") || strstr($receiver_sub,"D,F") || strstr($receiver_sub,"D"))
               $rec_sub="EV";
        elseif(strstr($receiver_sub,"F"))
               $rec_sub="ER";
	$seen=$contact_status_new['SEEN'];
        $type=$contact_status_new['R_TYPE'];
        if($type=="")
                $type=$contact_status_new['TYPE'];
	//Load contact history tab when user's have some contact.
	if(!strstr($_GET['after_login_call'],"contact_history"))
	{
		if(function_exists("check_any_contact"))
			if(check_any_contact($sender_id,$receiver_id,$type))
			{
				$smarty->assign("LOAD_CONTACT_HISTORY_TAB",1);
			}
	}
	else
	{
		if($_GET['CAME_FROM_CONTACT_MAIL'])
			$smarty->assign("AFTER_LOGIN_CALL",stripslashes("change_tab(dID('contact_history'),'Contact_History',0,1)"));
	}
	
	
	if(($data && $rec_sub=="EV" && $type=="") || $_GET["SHOW_CONTACT_TAB_EV"])
		$smarty->assign("SHOW_CONTACT_TAB_EV",1);

	if($member_101 && $member_101_details)
	{
		$rec_sub="EV";
	}
	//Setting the temporary values for checking
/*      $type="I";
        $sen_sub="";
        $rec_sub="ER";
        $type=$_GET['type'];
        $sen_sub=$_GET['sen_sub'];
        $rec_sub=$_GET['rec_sub'];
*/
        if($type=="")
        {
                $who="";
                $type="";
        }
        elseif($type)
        {
                if(strstr($type,"R"))
                {
                        $who='SENDER';
                        $type=substr($type,1,1);
                }

        }
	if(($who=='SENDER' && ($type=='A' OR $type=='D')) || ($who!='SENDER' && ($type=='I' )))
        {
		//This variable is required to update message log table.
		global $message_upd;
		if($seen!='Y')
                {
                        global $updatecontact,$run_on;

                        if($who=='SENDER')
                                $run_on='SENDER';
                        else
                                $run_on='RECEIVER';
                        $updatecontact=1;
                }
		$message_upd=1;
                
        }

	if(($who!='SENDER' && $type=='C') || ($who=='SENDER' && $type=='D'))
	{
		$smarty->assign("CANNOTCONTACT",1);
	}
        if($type=='C' && $who!='SENDER')
        {
                $who='SENDER';
                $type='D';
        }
	global $fromViewprofile;
	if($fromViewprofile)
		if($type=='I' || $type=='A')
			off_call_history();


	//Setting type ,who and subscription of reciever in Jprofile_result array , since it will be used in set_address function 
	$jprofile_result['type']=$type;
	$jprofile_result['who']=$who;
	$jprofile_result['rec_sub']=$rec_sub;
	
	if($type=='I' && $who!='SENDER' && $fromViewprofile==1)
	{
		$eoi_viewer = $jprofile_result['viewer']['PROFILEID'];
		$eoi_viewed = $jprofile_result['viewed']['PROFILEID'];
		if($eoi_viewer && $eoi_viewed)
		{
			$mysqlObj=new Mysql;
			$myDbName_viewed=getProfileDatabaseConnectionName($eoi_viewed,'',$mysqlObj);
			$myDb_viewed=$mysqlObj->connect("$myDbName_viewed");
			$myDbName_viewer=getProfileDatabaseConnectionName($eoi_viewer,'',$mysqlObj);
			$myDb_viewer=$mysqlObj->connect("$myDbName_viewer");
			$sql="INSERT IGNORE INTO newjs.EOI_VIEWED_LOG(VIEWER, VIEWED, `DATE`) VALUES ($eoi_viewer, $eoi_viewed, now())";
			mysql_query($sql,$myDb_viewed) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if($myDbName_viewed!=$myDbName_viewer)
				mysql_query($sql,$myDb_viewer) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

		}
	}

/*      if($_GET['ONLY_LAYER'])
        {
                //$rec_sub='ER';
                $sen_sub="ER";
                $type='A';
        }
*/

        global $smarty;
	if($action=='callnow_layer'){
		ivrCallNow($jprofile_result['viewer']['PROFILEID'],$jprofile_result['viewed']['PROFILEID'],$type,$who);
		$smarty->display("callnow_layer.htm");die;
	}
	else{
		if($fixed)
			generate_express_form_fixed($who,$type,$sen_sub,$rec_sub,$jprofile_result,$NUDGES);
		else
			generate_express_form($who,$type,$sen_sub,$rec_sub,$jprofile_result,$NUDGES);
	}
}
function generate_express_form_fixed($who,$type,$sen_sub,$rec_sub,$jprofile_result,$NUDGES)
{
        global $smarty;
        global $db;
        global $data;
	global $index;
	global $to_do;
	global $show_contacts;
	global $ajax_error;
	global $CALL_DIRECT;
	global $contact_locked;
	global $_COOKIE;
	global $isMobile,$cc_navigator,$nav_type;
        $pid=$data['PROFILEID'];
        $username=$data['USERNAME'];

	$his_her='his';
	$him_her="him";
        if($data['GENDER']=='M')
	{
                $his_her='her';
                $him_her='her';
	}

        /* EXP is an array that in which 
                FIRST is the SENDER or RECEIVER
                SECOND is type of contact between them(N,I,A,C,D) where 'N' is no contact
                Third is Type of subscription they have(N->no subscription,ER->Eristha,EV->Evalue)
                Fourth is message or which button to display
        */
        //When free member is trying to make contact

        $contact_headline=0;
        $contact_message="";
        $allow_accept_decline=0;
        $paid=0;
        $contact_locked=0;
        $button=0;
        $allow_mes_write=0;
        $button_enable=0;
        $prompt_to_pay=0;
	if($show_contacts)
		$contact_locked=1;
        //When no contact is made
//      $smarty->assign("DEFAULT_MESSAGE","Hi,\nI liked your profile. My user id on Jeevansathi.com is $username. Incase, you like my profile, please accept this initial contact message and contact me to proceed further. \n Regards\n $username");
        $smarty->assign("DEFAULT_MESSAGE","");
	if($sen_sub!="")
        {
                        $DEFAULT_MESSAGE="";
                        $sql="select MESSAGE,DRAFTID,DRAFTNAME from newjs.DRAFTS where PROFILEID=$pid ORDER BY CREATE_TIME DESC";
                        $res=mysql_query_decide($sql) or logError("",$sql);

                        while($row=@mysql_fetch_array($res))
                        {
                                $DRAFT[$row[1]]=htmlspecialchars($row[2],ENT_QUOTES);
                                //Replace new line with sequence of characters, required in javascript.
                                $DRA_MES[$row[1]]=ereg_replace("\r\n|\n|\r","#n#",htmlspecialchars($row[0],ENT_QUOTES));
                        }
                        $smarty->assign("DRAFT",$DRAFT);
                        $smarty->assign("DRA_MES",$DRA_MES);
                        $smarty->assign("DEFAULT_MESSAGE","");
        }

	if($NUDGES)
        {
                $smarty->assign("NUDGE_STATUS",$NUDGES['STATUS']);
                setNudgeDetails($NUDGES['STATUS'],$jprofile_result["viewed"]["USERNAME"]);
                if($NUDGES['STATUS']=='ACC')
                {
                        $contact_locked=1;
			set_address($jprofile_result,$rec_sub);
                }
                $op_profileid=$jprofile_result["viewer"]['PROFILEID'];
                $op_matchid=$jprofile_result["viewed"]['PROFILEID'];
                $op_msg_sql="SELECT MESSAGE FROM jsadmin.OFFLINE_OPERATOR_MESSAGES WHERE MATCH_ID='$op_profileid' AND PROFILEID='$op_matchid'";
                $op_msg_res=@mysql_query_decide($op_msg_sql) or logError("",$sql);
                if(@mysql_num_rows($op_msg_res))
                {
                        $op_msg_row=@mysql_fetch_assoc($op_msg_res);
                        $smarty->assign("N_MESSAGE",html_entity_decode($op_msg_row["MESSAGE"]));
                }
                //Putting checks for the temporary contact.
                $tempParam = temporaryInterestSuccess($jprofile_result["viewer"]["INCOMPLETE"], $jprofile_result["viewer"]["ACTIVATED"]);
                if($tempParam)
                {

                        $tempCnt = ifTempContactExists($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"]);
                        if($tempCnt[$jprofile_result["viewed"]["PROFILEID"]])
                        {
                                $smarty->assign("TEMP_CONTACT",1);
                                $smarty->assign("TAB_NAME","Interest Expressed");
                                $smarty->assign("CONTACT_HEADLINE","Interest Expressed");
                                $smarty->assign("CONTACT_MESSAGE","You have already contacted ".$jprofile_result['viewed']['USERNAME'].". We'll deliver the interest once your profile goes live");
                                $smarty->assign("CON_DET_MES","Your interest will be delivered once your profile goes live. Acceptance from the other member is needed to view her contact details.");
                                if($tempParam=='incomplete')
                                {
                                        $profilechecksum=md5($pid) ."i". $pid;
                                        $smarty->assign("CONTACT_MESSAGE","Your interest will be delivered once you complete the profile. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile");
                                        $smarty->assign("CON_DET_MES","You need to complete your profile before viewing the user's contact details. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile.");
                                }
                        }

                }

                global $CALL_DIRECT;
                if(!$CALL_DIRECT || $contact_locked==1)
                        return false;
        }
	elseif($type=='')
        {
                        $contact_headline="Express Interest";
                        if($sen_sub!="")
                        {
                                $paid=1;
                                $allow_mes_write=1;
                                $prompt_to_pay=1;
                        }
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                //$prompt_to_pay=1;
                        }
                        $button="Express Interest";
			$allow_accept_decline=0;
			$tempParam = temporaryInterestSuccess($jprofile_result["viewer"]["INCOMPLETE"], $jprofile_result["viewer"]["ACTIVATED"]);
			$contact_message="Expressing interest in this profile will send the following message. You will be notified by Email when member accepts or declines your interest.";
			if($tempParam=="underScreened")
			{
				$tempCnt = ifTempContactExists($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"]);
				if($tempCnt[$jprofile_result["viewed"]["PROFILEID"]])
					$con_det_message = "Your interest will be delivered once your profile goes live. Acceptance from the other member is needed to view ".$his_her." contact details.";
				else
				{
					if($rec_sub=='EV')
						$con_det_message = "You can view the contact details of the user only after your profile is screened";
					else
						$con_det_message = "To View Contact Details <a class=\"blink b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi_contact');}\">Express Interest</a> first";
				}
				if($CALL_DIRECT)
					$jprofile_result['UNDERSCREEN_USER']="You can view the contact details of the user only after your profile is screened";
				$contact_locked=0;
			}
			elseif($tempParam == "incomplete")
			{
				$profilechecksum=md5($pid) ."i". $pid;
				$con_det_message = "Your interest will be delivered once you complete the profile. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile";
			
				if($CALL_DIRECT)
                                        $jprofile_result['INCOMPLETE_USER']="You need to complete your profile before viewing the user's contact details. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile";
				$contact_locked=0;
			}
			else
				$con_det_message="To View Contact Details <a class=\"blink b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'eoi_contact');}\">Express Interest</a> first";
                        if($sen_sub=="")
                        {
                                $BUY_MESSAGE_EOI="Please note that your message does not include your contact details. To include contact details and edit message.";
                                $smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
                        }
        }
	elseif($type=='I')
        {
                if($who=='SENDER')
                {
			$updatedStatus = "I";
                        $contact_headline="Send Reminder";
                        $contact_message="You have already contacted this member. ";
                        if($sen_sub)
                        {
                                $contact_message.="You can send a customised message as a reminder.";
                                $paid=1;
                                $prompt_to_pay=1;
				//$allow_mes_write=1;
                        }
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                //$allow_mes_write=1;
                                //$prompt_to_pay=1;
                        }

                        $button="Send Reminder";
                        $con_det_message="Interest expressed. Acceptance from the other member is needed to view $his_her contact details. <a class=\"blink b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'reminder_contact');}\">Send reminder</a>";
                        if($sen_sub == "")
                                $smarty->assign("BUY_MESSAGE_EOI","To write message");
                }
                else
                {
			//$contact_headline="Respond to Interest sent by ".$jprofile_result[viewed][USERNAME].".";
                        $contact_message="A Jeevansathi member has expressed interest in you. Please respond.";
			if($to_do=='accept')
			{
				$updatedStatus = "A";
	                        $button="Accept Interest";
				$contact_headline="Accept Expression of Interest";
				if($sen_sub)
				{
					$paid=1;
					$prompt_to_pay=1;
					if($_REQUEST["multiple"])
						$contact_message="Accepting these members will send the following message and show their contact details to you ";
					else
						$contact_message="Accepting this member will send the following message and show $his_her contact details to you ";
				}
				else
				{
					if($_REQUEST["multiple"])
					{
						$contact_message="Accepting these members will send the following message";
						$BUY_MESSAGE_EOI="If you want to view other member contact details, share your contact details and edit this message,";
					}
					else
					{
						if($rec_sub=="EV")
						{
							$contact_message="Accepting this member will send the following message";
							$BUY_MESSAGE_EOI="If you want to share your contact details with other members and edit this message,";
						}
						else
						{
							$contact_message="Your acceptance will be sent with the following message";
							$BUY_MESSAGE_EOI="If you want to share your contact details with $him_her and edit this message,";
						}
					}
					$smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
				}
			}
			else
			{
				$updatedStatus = "D";
				if($_REQUEST["multiple"])
				{
					$contact_headline="Not Interested in these members";
					$contact_message="Declining these members will send the following message";
				}
				else
				{
					$contact_headline="Not Interested in this member";
					$contact_message="Declining this member will send the following message";
				}
				$button = "Not Interested";
				$bottom_line = "When you decline a member your contact information will not be shown";
				$smarty->assign("bottom_line",$bottom_line);
				if($sen_sub)
				{
                                        $paid=1;
                                        $prompt_to_pay=1;
				}
				//$allow_accept_decline=1;
			}
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                $prompt_to_pay=1;
				$allow_mes_write=1;
                        }
		
			if($to_do=="view_contact_message")
				$con_det_message="To view contact details, <a class=\"blink b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept_contact_message');}\">Accept</a> this member";
			else
 	                       $con_det_message="To view contact details, <a class=\"blink b\" onclick=\"javascript:{check_window('hide_exp_layer()');check_checkbox('PROFILE_$index',$index,'accept_contact');}\">Accept</a> this member";

                }

		$smarty->assign("updatedStatus",$updatedStatus);
        }
	 elseif($type=='A')
        {
                if($who=='SENDER')
                {
                        $contact_headline="Send Message";
                        $contact_message="Jeevansathi Member has Accepted you.";
                        if($sen_sub=="" && $rec_sub!="EV")
                                $button_enable=1;

                        if($sen_sub)
                        {
                                $contact_message.="You can send a customised message as a reminder.";
                                $paid=1;
                                $prompt_to_pay=1;
                                $contact_locked=1;
			}
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                $prompt_to_pay=1;
				$allow_mes_write=1;
			}
                        $button="Send Message";
                        //$con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_layer'  style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";
                        $con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab' style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";
                }
		else
                {
                        if($sen_sub=="" && $rec_sub=="")
                                $button_enable=1;
                        $contact_headline="Send Message";
                        $contact_message="You have accepted a Jeevansathi member.";
                        //$allow_accept_decline=1;
                        if($rec_sub)
                        {
                                $contact_locked=1;
                                $allow_mes_write=1;
                                $prompt_to_pay=1;
                        }
                        if($sen_sub)
                        {
                                $allow_mes_write=1;
                                $paid=1;
                                $prompt_to_pay=1;
                                $contact_locked=1;
                        }
                        $button="Send Message";
                        $con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab'  style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";

                }
		if($button_enable)
			$smarty->assign("BUY_MESSAGE_EOI","To write message");
        }
	elseif($type=='D')
        {
                $prompt_to_pay=1;
                if($who=='SENDER')
                {
                        $contact_headline="This member is not interested in further communication with you.";
                        if($rec_sub=="EV" && false)
                        {
                                $contact_locked=1;

                        }
                        if($sen_sub)
                        {
                                $paid=1;
                                $allow_mes_write=1;
                        }
                        $all_disable=1;
                        $con_det_message="Contact details are locked.";
			$show_only_cont=1;
                }
		else
                {
			$button="Accept";
                        $contact_headline="You have declined further communication with this member.";
                        if($rec_sub=="EV")
                        {
                                $contact_locked=1;
                        }
                        if($rec_sub=="EV")
                        {
                                $allow_mes_write=1;
                        }
                        if($sen_sub)
                        {
                                $allow_mes_write=1;
                                $paid=1;
                        }
                        if($sen_sub=="" && $rec_sub=="")
                                $REMOVE_TEXTAREA=1;

                        $con_det_message="To view contact details, <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Accept</a> this member";
                }
        }
	 if($type=='C')
        {
                $contact_headline="You have cancelled any further communication with Jeevansathi member.";
                if($sen_sub!='' || $rec_sub=="EV")
                {
                        $allow_mes_write=1;
                        $paid=1;
			$contact_locked=1;
                        $prompt_to_pay=1;
                }
                $button="Accept";
		$con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab'  style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";
        }

        if(($type=="" || $type=='I' && $who=='SENDER' ) &&  ($jprofile_result['IS_FILTER'] || $jprofile_result['VIEWER']['INCOMPLETE']=='Y'))
        {
                        if($jprofile_result['IS_FILTER'])
                        {
                                $con_det_message="You have been filtered by the member.";
                                $contact_locked=0;
                       }
                        elseif($jprofile_result['VIEWER']['INCOMPLETE']=='Y')
                        {
                                $con_det_message="You need to complete your profile before viewing your contact details. Please <a href='/profile/viewprofile.php?ownview=1' class='blink'>click here</a> to complete your profile";
                                $contact_locked=0;
                        }

        }
        if($jprofile_result['REDIRECT_TO_CONTACT'])
        {
		$contact_message="This member has moved to <a href='/profile/contacts_made_received.php?page=accept&filter=A' class='blink'>Accepted member</a> folder in 'My Contacts'";
		$contact_headline="You have accepted interest sent by ".$jprofile_result['viewed']['USERNAME'];
		$smarty->assign("SHOW_SHORT_CONTACT",true);
		if($sen_sub=="")
		{
			if($rec_sub=="")
			{
				$BUY_MESSAGE_EOI="<ul style='margin:0 0 0 -12px;padding:0 0 0 30px; float:left;'><li class='acpt'>View contact information of this member</li><li class='acpt'>Include your contact information in messages</li></ul>";
				$contact_message="This is a free member so contact details cannot be viewed";
			}
			else
			{
				$BUY_MESSAGE_EOI="Want to share your contact details with other members";
				$contact_message="This is a Paid member so contact details are shared with you";
			}
			$smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
		}
		else
			$contact_message="Contact details of this member are";
        }

	$textarea=40;
        if(!$allow_accept_decline)
                $textarea+=4;
        if($type=="A" && $allow_mes_write)
                $textarea+=40;
        if($type=='I' && $who=="")
	{
		if($prompt_to_pay)
			$textarea+=15;
		else
	                $textarea=30;
	}
	if($type=="I" && $who=="SENDER")
		$textarea = 93;
        if($type=="" && $sen_sub!="")
                $textarea=85;
        if($type=="" && $sen_sub=="")
                $textarea=30;
        $textarea=$textarea;
        $textarea="$textarea"."px";
	if(!$_REQUEST["multiple"])
	{
		if($jprofile_result["viewed"]["CALLED_CHECKED"])
		{
			if($jprofile_result["viewed"]["VIEW_CONTACT_DETAILS"])
				$contact_locked = 1;
		}
		/*else
		{
			global $CALL_NOW;
			$called = getCallerProfiles($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"],$CALL_NOW);
			if($called)
				$contact_locked = 1;
		}*/
	}
	
	if($contact_locked)
        {
		if($jprofile_result['SAME_GENDER']==1)
		{
			$contact_locked=0;
			$con_det_message="You cannot see the contact details of this profile as the profile is of the same gender.";
		}
		if($contact_locked)
			set_address($jprofile_result,$rec_sub);
        }
	else
	{
                global $CALL_DIRECT;
                if($CALL_DIRECT && !$jprofile_result['REDIRECT_TO_CONTACT'])
                {
			if($to_do=="view_contact" || $to_do=="view_contact_message")
			{
				global $from_search;
				$from_search=1;
				$con_det_message = "";
				if($isMobile)
				$all_data=call_directly_mobile($jprofile_result,$sen_sub,$rec_sub,$type,$who);
				else
				$all_data=call_directly($jprofile_result,$sen_sub,$rec_sub,$type,$who);
				$jprofile_result["ALREADY_VIEWED"] = $all_data["ALREADY_VIEWED"];
				$smarty->assign("directCallData",$all_data);
				if($all_data["con_det_message"])
					$bottom_line=$all_data["con_det_message"];
				if($all_data['ALLOW'])
				{
					if($all_data['DIRECT_SHOW'])
						set_address($jprofile_result,$rec_sub,"direct_call");
					else
					{
						global $RELATIONSHIP;
						$left_con=$all_data['ALLOTED']-$all_data['VIEWED'];
						//Create encrypted string.
						$start_cd="FIRST TEST";
						$middle_cd="MIDDLE TEST";
						$end_cd="END TEST";
						$profileid=$jprofile_result['viewed']['PROFILEID'];
						//Check encrypt value is same as provided
						$both_users=md5($start_cd.$profileid.$middle_cd.$data['PROFILEID'].$end_cd);
						$c_prof=createchecksumforsearch($profileid);
						global $NUMBER_OWNER;
						$phone_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["PHONE_NUMBER_OWNER"]];
						$phone_owner_name=$jprofile_result['viewed']["PHONE_OWNER_NAME"];
						$mobile_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["MOBILE_NUMBER_OWNER"]];
						$mobile_owner_name=$jprofile_result['viewed']["MOBILE_OWNER_NAME"];
						if($phone_owner_name=="")
							$owner_name=$phone_owner_name."<span class='no_b'>($phone_owner_number)</span>";
						else
							$owner_name=$mobile_owner_name."<span class='no_b'>($mobile_owner_number)</span>";
						global $fromNewSearch;
						if(!$fromNewSearch)
						{
							$show_cross="<div style='float:right'><a onclick='$.colorbox.close();close_all_con_layer();return false;' class='blink' href='#'><B>[ x ]</B></a></div>";
							$jsFnc="show_contact(\"$both_users\",\"$c_prof\",\"1\")";
						}	
						else
						{
							$jsFnc="CallDirectOnSearch(postData,\"$c_prof\",\"$both_users\")";
						}
						if($isMobile){
							if(checkSpamForReceiver($jprofile_result['viewer']['PROFILEID'], $jprofile_result['viewed']['PROFILEID'])){
								$shift_mes="<div class=\"confirm_msg\">Dear User,<BR/>You have  <span class=\"fcMrgnt\">$left_con</span> contacts left to view out of $all_data[ALLOTED] available.<BR><BR>
									<a href=\"call_directly.php?both_users=$both_users&show_con=1&profilechecksum=$c_prof&nav_type=$nav_type&$cc_navigator\" style='pointer:cursor'>Click here</a> to view contact details</div><p style=\"height:60px;\"></p>";
							}
							else
								$shift_mes="The profile has been viewed more than the allotted number of times for the day. Please try again tomorrow";
						}
						else{
							if(checkSpamForReceiver($jprofile_result['viewer']['PROFILEID'], $jprofile_result['viewed']['PROFILEID']))
								$shift_mes="<div style='width:255px;float:left'>$show_cross Dear User,<BR>&nbsp;You have <span class='dark_orange b t14'>$left_con</span> contacts left to view out of $all_data[ALLOTED] available.<BR><BR><BR><span class='b'><a  onclick='return $jsFnc;' class='blink' style='pointer:cursor'>Click here</a> to view contact details</span><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>";
							else
								$shift_mes="<div style='width:255px;float:left'>$show_cross <BR>The profile has been viewed more than the allotted number of times for the day. Please try again tomorrow <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>";
							$shift_mes.="</div>";
						}
						$smarty->assign("shift_mes",$shift_mes);
					}
				}
			}
		}
                $smarty->assign("show_line",1);
                if($NUDGES)
                {
                        if($all_data['DIRECT_SHOW'])
                                $smarty->assign("show_line",0);
                        if(!$contact_locked)
                        {
				$bottom_line = $con_det_message;
                        }
                        if($_GET['ONLY_LAYER'])
                        {
                                $smarty->display("dp_express_interest_layer_fixed.htm");
                                die;
                        }
                        return false;
                }
	}
	set_address_details($jprofile_result['viewer']['PHONE_MOB'],$jprofile_result['viewer']['PHONE_RES'],$jprofile_result['viewer']['STD'],$jprofile_result['viewer']['ISD'],$jprofile_result['viewer']['EMAIL']);
        set_drafts($paid,$type,$who,$rec_sub,$jprofile_result["viewer"]['RELATION'],1);
	$smarty->assign("BOTTOM_LINE",$bottom_line);
        $smarty->assign("CONTACT_HEADLINE",$contact_headline);
        $smarty->assign("CONTACT_MESSAGE",$contact_message);
        $smarty->assign("ALLOW_ACCEPT_DECLINE",$allow_accept_decline);
        $smarty->assign("PAID",$paid);
        $smarty->assign("TYPE",$type);
        $smarty->assign("WHO",$who);
        $smarty->assign("DISABLE_ALL",$all_disable);
        $smarty->assign("REMOVE_TEXTAREA",$REMOVE_TEXTAREA);
        if(!$contact_locked)
        {
		if($to_do=="view_contact_ignore")
			$CON_DET_MES = "";
		else
			$CON_DET_MES = $con_det_message;
        }
	$PROFILE_CHECKSUM=createChecksumForSearch($jprofile_result['viewed']["PROFILEID"]);
	$smarty->assign("viewed_profile",$PROFILE_CHECKSUM);
	$smarty->assign("CON_DET_MES",$CON_DET_MES);
        $smarty->assign("BUTTON_NAME",$button);
        $smarty->assign("ALLOW_MES_WRITE",$allow_mes_write);
        $smarty->assign("STATUS",$type);
        $smarty->assign("WHO",$who);
        $smarty->assign("ENABLE_BUTTON",$button_enable);
        $smarty->assign("TEXTAREA_SIZE",$textarea);
        $smarty->assign("PROMPT_TO_PAY",$prompt_to_pay);
	$smarty->assign("SHOW_ONLY_CONT",$show_only_cont);
        if($_GET['ONLY_LAYER'])
        {
                $smarty->display("dp_express_interest_layer.htm");
                die;
        }

}
/*This function returns all the parameters required by express layer
 Who is set to SENDER is contact is made by login user
 Sen_sub is the login person subscription
 Rec_sub is the other user subscription
*/
function generate_express_form($who,$type,$sen_sub,$rec_sub,$jprofile_result,$NUDGES)
{
        global $smarty;
        global $db;
        global $data;
	global $RELATIONSHIP;
	global $CALL_DIRECT;
	global $contact_locked;
	global $isMobile,$cc_navigator,$nav_type;
        $pid=$data['PROFILEID'];
        $username=$data['USERNAME'];

        /* EXP is an array that in which 
                FIRST is the SENDER or RECEIVER
                SECOND is type of contact between them(N,I,A,C,D) where 'N' is no contact
                Third is Type of subscription they have(N->no subscription,ER->Eristha,EV->Evalue)
                Fourth is message or which button to display
        */
        //When free member is trying to make contact

        $contact_headline=0;
        $contact_message="";
        $allow_accept_decline=0;
        $paid=0;
        $contact_locked=0;
        $button=0;
        $allow_mes_write=0;
        $button_enable=0;
        $prompt_to_pay=0;

	$his_her='his';
        if($data['GENDER']=='M')
                $his_her='her';

	//When no contact is made
//      $smarty->assign("DEFAULT_MESSAGE","Hi,\nI liked your profile. My user id on Jeevansathi.com is $username. Incase, you like my profile, please accept this initial contact message and contact me to proceed further. \n Regards\n $username");
        $smarty->assign("DEFAULT_MESSAGE","");
        if($sen_sub!="" && !$jprofile_result["viewer"]["NO_DRAFTS"])
        {
                        $DEFAULT_MESSAGE="";
                        $sql="select MESSAGE,DRAFTID,DRAFTNAME from newjs.DRAFTS where PROFILEID=$pid ORDER BY CREATE_TIME DESC";
                        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

                        while($row=mysql_fetch_array($res))
                        {
                                $DRAFT[$row[1]]=htmlspecialchars($row[2],ENT_QUOTES);
                                //Replace new line with sequence of characters, required in javascript.
                                $DRA_MES[$row[1]]=preg_replace("/\\r\\n|\\n|\\r/","#n#",htmlspecialchars($row[0],ENT_QUOTES));
                        }
                        $smarty->assign("DRAFT",$DRAFT);
                        $smarty->assign("DRA_MES",$DRA_MES);
                        $smarty->assign("DEFAULT_MESSAGE","");

        }
	
	if($NUDGES)
        {
                $smarty->assign("NUDGE_STATUS",$NUDGES['STATUS']);
                setNudgeDetails($NUDGES['STATUS'],$jprofile_result["viewed"]["USERNAME"]);
		$tab_name="Express Interest";
                if($NUDGES['STATUS']=='ACC')
                {
                        $contact_locked=1;
			set_address($jprofile_result,$rec_sub);
                }
                $op_profileid=$jprofile_result["viewer"]['PROFILEID'];
                $op_matchid=$jprofile_result["viewed"]['PROFILEID'];
                $op_msg_sql="SELECT MESSAGE FROM jsadmin.OFFLINE_OPERATOR_MESSAGES WHERE MATCH_ID='$op_profileid' AND PROFILEID='$op_matchid'";
                $op_msg_res=mysql_query_decide($op_msg_sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$op_msg_sql,"ShowErrTemplate");
                if(mysql_num_rows($op_msg_res))
                {
                        $op_msg_row=mysql_fetch_assoc($op_msg_res);
                        $smarty->assign("N_MESSAGE",html_entity_decode($op_msg_row["MESSAGE"]));
                }

		//Putting checks for the temporary contact.
		$tempParam = temporaryInterestSuccess($jprofile_result["viewer"]["INCOMPLETE"], $jprofile_result["viewer"]["ACTIVATED"]);
		if($tempParam)
		{
			
			$tempCnt = ifTempContactExists($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"]);
			if($tempCnt[$jprofile_result["viewed"]["PROFILEID"]])
			{		
				$smarty->assign("TEMP_CONTACT",1);
				$smarty->assign("TAB_NAME","Interest Expressed");
				$smarty->assign("CONTACT_HEADLINE","Interest Expressed");
        	                $smarty->assign("CONTACT_MESSAGE","You have already contacted ".$jprofile_result['viewed']['USERNAME'].". We'll deliver the interest once your profile goes live");
				$smarty->assign("CON_DET_MES","Your interest will be delivered once your profile goes live. Acceptance from the other member is needed to view her contact details.");
				if($tempParam=='incomplete')
				{
					$profilechecksum=md5($pid) ."i". $pid;
					$smarty->assign("CONTACT_MESSAGE","Your interest will be delivered once you complete the profile. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile");
					$smarty->assign("CON_DET_MES","You need to complete your profile before viewing the user's contact details. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile.");
				}
			}

		}

		global $CALL_DIRECT;
		if(!$CALL_DIRECT || $contact_locked==1)
                	return false;
        }
	elseif($type=='')
        {
			$tab_name="Express Interest";
                        $contact_headline="Express Interest";
                        $contact_message="Expressing interest in this profile will send the following message. You will be notified by Email when member accepts or declines your interest.";
                        $allow_accept_decline=0;
                        if($sen_sub!="")
                        {
                                $paid=1;
                                $allow_mes_write=1;
                                $prompt_to_pay=1;
                        }
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                //$prompt_to_pay=1;
                        }
                        $button="Express Interest";
			
			
                        $con_det_message="To View Contact Details <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Express Interest</a> first";
			$tempParam = temporaryInterestSuccess($jprofile_result["viewer"]["INCOMPLETE"], $jprofile_result["viewer"]["ACTIVATED"]);
			if($tempParam)
			{
				$temp_cont=0;

				$temp_message['underScreened'][1]="You have already contacted ".$jprofile_result['viewed']['USERNAME'].". We'll deliver the interest once your profile goes live";
				$temp_con_mes['underScreened'][0]="To View Contact Details Express Interest first";
				$temp_con_mes['underScreened'][1]="You can view the contact details of the user only after your profile is screened";
				$profilechecksum=md5($pid) ."i". $pid;
				$temp_message['incomplete'][1]="Your interest will be delivered once you complete the profile. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile";
				$temp_con_mes['incomplete'][0]=$temp_con_mes['incomplete'][1]="You need to complete your profile before viewing the user's contact details. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile";

				$tempCnt = ifTempContactExists($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"]);
                                if($tempCnt[$jprofile_result["viewed"]["PROFILEID"]])
				{
					$tab_name="Interest Expressed";
					$contact_headline=$tab_name;
					$button="Interest Expressed";
					$temp_cont=1;
					$smarty->assign("TEMP_CONTACT",1);
				}
				if($temp_message[$tempParam][$temp_cont])
					$contact_message=$temp_message[$tempParam][$temp_cont];
				if($temp_con_mes[$tempParam][$temp_cont])
				{
					$contact_locked=0;
					$con_det_message=$temp_con_mes[$tempParam][$temp_cont];
					global $CALL_DIRECT;
					$CALL_DIRECT=0;
				}
			}
			if($sen_sub=="")
			{
				$BUY_MESSAGE_EOI="Please note that your message does not include your contact details. To include contact details and edit message.";
				$smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
			}
			
			
			
        }
	elseif($type=='I')
        {
                if($who=='SENDER')
                {
                        $contact_headline="Send Reminder";
			$tab_name="Send Reminder";
                        $contact_message="You have already contacted this member. ";
                        if($sen_sub)
                        {
                                $contact_message.="You can send a customised message as a reminder.";
                                $paid=1;
                                $prompt_to_pay=1;

                        }
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                //$allow_mes_write=1;
                                //$prompt_to_pay=1;
                        }

                        $button="Send Reminder";
                        $con_det_message="Interest expressed. Acceptance from the other member is needed to view $his_her contact details. <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Send reminder</a>";
                }
		else
                {
			$tab_name="Respond";
                        $contact_headline="Respond to Interest sent by ".$jprofile_result[viewed][USERNAME].".";
                        $contact_message="A Jeevansathi member has expressed interest in you. Please respond.";
                        $allow_accept_decline=1;
                        $button="Accept Interest";
                        if($sen_sub)
                        {
                                $paid=1;
                                $prompt_to_pay=1;
                        }
                        if($rec_sub=='EV')
                        {
                                $contact_locked=1;
                                $prompt_to_pay=1;
                                 $allow_mes_write=1;
                        }
		
			if($sen_sub=="")
			{
				if($rec_sub=="")
				{
					$BUY_MESSAGE_EOI="<ul style='margin:0 0 0 0;padding:0 0 0 0;margin-left:-12px;float:left;padding-left:30px'><li class='acpt'>View contact information of this member</li><li class='acpt'>Include your contact information in messages</li></ul>";
				}
				else
					$BUY_MESSAGE_EOI="If you want to share your contact details with other members and edit this message,";
				$smarty->assign("BUY_MESSAGE_EOI",$BUY_MESSAGE_EOI);
			
			}
                        $con_det_message="To view contact details, <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Accept</a> this member";

                }

        }
	elseif($type=='A')
        {
                if($who=='SENDER')
                {
                        $contact_headline="Send Message";

			$tab_name="Write Message";
                        $contact_message="Jeevansathi Member has Accepted you.";
                        if($sen_sub=="" && $rec_sub!="EV")
                                $button_enable=1;

                        if($sen_sub)
                        {
                                $contact_message.="You can send a customised message as a reminder.";
                                $paid=1;
                                $prompt_to_pay=1;
                                $contact_locked=1;
                        }
                        if($rec_sub=='EV')
                        {
				$allow_mes_write=1;
                                $contact_locked=1;
                                $prompt_to_pay=1;
                        }


                        $button="Send Message";

                        $con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab' style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";
                }
		else
                {
                        if($sen_sub=="" && $rec_sub=="")
                                $button_enable=1;
                        $contact_headline="Send Message";
			$tab_name="Write Message";
                        $contact_message="You have accepted a Jeevansathi member.";
                        //$allow_accept_decline=1;
                        if($rec_sub)
                        {
                                $contact_locked=1;
                                $allow_mes_write=1;
                                $prompt_to_pay=1;
                        }
                        if($sen_sub)
                        {
                                $allow_mes_write=1;
                                $paid=1;
                                $prompt_to_pay=1;
                                $contact_locked=1;
                        }
                        $button="Send Message";
                        $con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab' style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";

                }

        }
	elseif($type=='D')
        {
                $prompt_to_pay=1;
                if($who=='SENDER')
                {
                        $contact_headline="This member is not interested in further communication with you.";
                        if($rec_sub=="EV" && false)
                        {
                                $contact_locked=1;

                        }
                        if($sen_sub)
                        {
                                $paid=1;
                                $allow_mes_write=1;
                        }
                        $all_disable=1;
                        $con_det_message="Contact details are locked.";
			$show_only_cont=1;
                }
		else
                {
                        $contact_headline="You have declined further communication with this member.";
			$tab_name="Respond";
                        if($rec_sub=="EV")
                        {
                                $contact_locked=1;
                        }
                        if($rec_sub=="EV")
                        {
                                $allow_mes_write=1;
                        }
                        if($sen_sub)
                        {
                                $allow_mes_write=1;
                                $paid=1;
                        }
                        if($sen_sub=="" && $rec_sub=="")
                                $REMOVE_TEXTAREA=1;

                        $button="Accept";
                        $con_det_message="To view contact details, <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Accept</a> this member";
                }
        }
	 if($type=='C')
        {
                $contact_headline="You have cancelled any further communication with Jeevansathi member.";
                $who='SENDER';
		$tab_name="Respond";

                if($sen_sub!="" || $rec_sub=="EV")
                {
                        $allow_mes_write=1;
                        $paid=1;
                        $contact_locked=1;
                        $prompt_to_pay=1;
                }
                $button="Accept";
                if($sen_sub=="")
			$con_det_message="View contact information of this member <a href='$SITE_URL/profile/mem_comparison.php?from_source=View_Contact_details_tab'  style='font-size:16px;font-weight:bold'>Become a Paid Member Now</a>";
        }
	if(($type=="" || $type=='I' && $who=='SENDER' ) &&  ($jprofile_result['IS_FILTER'] || $jprofile_result['VIEWER']['INCOMPLETE']=='Y'))
	{
			if($temp_cont && $con_det_message)
			{
				//Bypass below message..
			}
			elseif($jprofile_result['IS_FILTER'])
                        {
                                $con_det_message="You have been filtered by the member.";
                                $contact_locked=0;
                        }
                        elseif($jprofile_result['VIEWER']['INCOMPLETE']=='Y')
                        {
                                $con_det_message="You need to complete your profile before viewing your contact details. Please <a href='/profile/viewprofile.php?ownview=1' class='blink'>click here</a> to complete your profile";
                                $contact_locked=0;
                        }
		
	}
	if($jprofile_result['CON_DET_MESSAGE'])
	{
		$smarty->assign("LATEST_ACC_DEC",$jprofile_result['CON_DET_MESSAGE']);
		if($jprofile_result['CON_HEADLINE'])
			$contact_headline=$jprofile_result['CON_HEADLINE'];
		$contact_message=$jprofile_result['CON_DET_MESSAGE'];
	}
	
	$textarea=40;
        if(!$allow_accept_decline)
                $textarea+=19;
        if($allow_mes_write)
                $textarea+=14;
        if($type!='A')
                $textarea+=19;

	if($type=='I' && $who=="" && !$prompt_to_pay)
		$textarea=20;
	if($type=="" && $sen_sub!="")
		$textarea=90;
	if($type=="" && $sen_sub=="")
		$textarea=30;
	if($type=="I" && $who=="" && $prompt_to_pay)
		$textarea=69;
        $textarea=$textarea;
	if(!$_REQUEST["multiple"])
	{
		if($jprofile_result["viewed"]["CALLED_CHECKED"])
		{
			if($jprofile_result["viewed"]["VIEW_CONTACT_DETAILS"])
				$contact_locked = 1;
		}
		/*else
		{
			global $CALL_NOW;
			$called = getCallerProfiles($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewed"]["PROFILEID"],$CALL_NOW);
			if($called)
				$contact_locked = 1;
		}*/
	}
	if($contact_locked)
	{
		set_address($jprofile_result,$rec_sub);
        }
	else
	{
		global $CALL_DIRECT;
		if($CALL_DIRECT)
		{
			global $from_search;
			if($isMobile)
			$all_data=call_directly_mobile($jprofile_result,$sen_sub,$rec_sub,$type,$who);
			else
			$all_data=call_directly($jprofile_result,$sen_sub,$rec_sub,$type,$who);
			$jprofile_result["ALREADY_VIEWED"] = $all_data["ALREADY_VIEWED"];
			$smarty->assign("directCallData", $all_data);
			if($all_data["con_det_message"])
				$con_det_message=$all_data["con_det_message"];
			if($all_data['ALLOW'])
			{
				if($all_data['DIRECT_SHOW'])
					set_address($jprofile_result,$rec_sub,"direct_call");
				else
				{
					global $RELATIONSHIP;
					$left_con=$all_data['ALLOTED']-$all_data['VIEWED'];
					//Create encrypted string.
					$start_cd="FIRST TEST";
					$middle_cd="MIDDLE TEST";
					$end_cd="END TEST";
					$profileid=$jprofile_result['viewed']['PROFILEID'];
					//Check encrypt value is same as provided
					$both_users=md5($start_cd.$profileid.$middle_cd.$data['PROFILEID'].$end_cd);
					$c_prof=createchecksumforsearch($profileid);
					global $NUMBER_OWNER;
					$phone_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["PHONE_NUMBER_OWNER"]];
					$phone_owner_name=$jprofile_result['viewed']["PHONE_OWNER_NAME"];
					$mobile_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["MOBILE_NUMBER_OWNER"]];
					$mobile_owner_name=$jprofile_result['viewed']["MOBILE_OWNER_NAME"];
					if($phone_owner_name=="")
						$owner_name=$phone_owner_name."<span class='no_b'>($phone_owner_number)</span>";
					else
						$owner_name=$mobile_owner_name."<span class='no_b'>($mobile_owner_number)</span>";
					//This variable is passed whenever script is called from search.

					if($from_search)
						$show_cross="<div style='float:right'><a onclick='return close_all_con_layer()' class='blink' href='#'><B>[ x ]</B></a></div>";

					if($isMobile){
						if(checkSpamForReceiver($jprofile_result['viewer']['PROFILEID'], $jprofile_result['viewed']['PROFILEID'])){
							$shift_mes="<div class=\"confirm_msg\">Dear User,<BR/>You have <span class=\"fcMrgnt\">$left_con</span> contacts left to view our of $all_data[ALLOTED] available.<BR><BR>
								<a href=\"call_directly.php?both_users=$both_users&show_con=1&profilechecksum=$c_prof&nav_type=$nav_type&$cc_navigator\" style='pointer:cursor'>Click here</a> to view contact details</div><p style=\"height:60px;\"";
						}
						else
							$shift_mes="The profile has been viewed more than the allotted number of times for the day. Please try again tomorrow";
					}
					else{
						if(checkSpamForReceiver($jprofile_result['viewer']['PROFILEID'], $jprofile_result['viewed']['PROFILEID']))
							$shift_mes="<div style='width:255px;float:left'>$show_cross Dear User,<BR>&nbsp;You have <span class='dark_orange b t14'>$left_con</span> contacts left to view out of $all_data[ALLOTED] available.<BR><BR><BR><span class='b'><a  onclick='return show_contact(\"$both_users\",\"$c_prof\",\"$from_search\");' class='blink' style='pointer:cursor'>Click here</a> to view contact details</span><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>";
						else
							$shift_mes="<div style='width:255px;float:left'>$show_cross <BR>The profile has been viewed more than the allotted number of times for the day. Please try again tomorrow <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>";
						$shift_mes.="</div>";
					}
					$smarty->assign("shift_mes",$shift_mes);
				}
			}
		}
		
		$smarty->assign("show_line",1);
		if($NUDGES)
		{
			if($all_data['DIRECT_SHOW'])
				$smarty->assign("show_line",0);
			if(!$contact_locked)
			{
				$smarty->assign("CON_DET_MES",$con_det_message);
			}
			if($_GET['ONLY_LAYER'])
			{
				$smarty->display("dp_express_interest_layer.htm");
				die;
			}	
			return false;
		}
	}
	set_address_details($jprofile_result['viewer']['PHONE_MOB'],$jprofile_result['viewer']['PHONE_RES'],$jprofile_result['viewer']['STD'],$jprofile_result['viewer']['ISD'],$jprofile_result['viewer']['EMAIL']);
	if(!$jprofile_result["viewer"]["NO_DRAFTS"])
	        set_drafts($paid,$type,$who,$rec_sub,$jprofile_result["viewer"]['RELATION']);
	if($jprofile_result["viewed"]["COUNTRY_RES"]!=51)
                $smarty->assign("NRI",1);
	$smarty->assign("CONTACT_HEADLINE",$contact_headline);
        $smarty->assign("CONTACT_MESSAGE",$contact_message);
        $smarty->assign("ALLOW_ACCEPT_DECLINE",$allow_accept_decline);
        $smarty->assign("PAID",$paid);
        $smarty->assign("TYPE",$type);
        $smarty->assign("WHO",$who);
        $smarty->assign("DISABLE_ALL",$all_disable);
        $smarty->assign("REMOVE_TEXTAREA",$REMOVE_TEXTAREA);
        if(!$contact_locked)
        {
		$CON_DET_MES = $con_det_message;
        }
        //Used for search , in order to provide security.
        $PROFILE_CHECKSUM=createChecksumForSearch($jprofile_result['viewed']["PROFILEID"]);
	if($CON_DET_MES)
		$smarty->assign("CON_DET_MES",$CON_DET_MES);
        $smarty->assign("viewed_profile",$PROFILE_CHECKSUM);
        $smarty->assign("BUTTON_NAME",$button);
        $smarty->assign("ALLOW_MES_WRITE",$allow_mes_write);
        $smarty->assign("STATUS",$type);
        $smarty->assign("WHO",$who);
        $smarty->assign("ENABLE_BUTTON",$button_enable);
        $smarty->assign("TEXTAREA_SIZE",$textarea);
        $smarty->assign("PROMPT_TO_PAY",$prompt_to_pay);
	$smarty->assign("TAB_NAME",$tab_name);
        $smarty->assign("SHOW_ONLY_CONT",$show_only_cont);
	if($_GET['ONLY_LAYER'])
        {
		
        	if(!$_GET[from_album])
		{ 
			$smarty->template_dir=$_SERVER[DOCUMENT_ROOT]."/../apps/jeevansathi/modules/profile/templates/";
			$smarty->relative_dir="";
			//$smarty->fetch("_express_page.tpl");
			$smarty->display("_express_page.tpl");
	                //    die;
		}
        }
}
function set_address($jprofile_result,$viewed_subs='',$direct_call="")
{
        global $smarty;
	global $RELATIONSHIP;
        global $NUMBER_OWNER;
	global $data;
	global $contact_locked;
	global $MESSENGER_CHANNEL;
	if($direct_call)
	{
		if(!isset($jprofile_result["viewed"]["INVALID_PHONE"]))
		{
			$jprofile_result["viewed"]["INVALID_PHONE"]=bounced_phone($jprofile_result["viewed"]['PROFILEID']);
		}
		if($jprofile_result["viewed"]["INVALID_PHONE"])
		{
			$jprofile_result["viewed"]["PHONE_MOB"] = "";
			$jprofile_result["viewed"]["PHONE_RES"] = "";
		}
		
		if(!$jprofile_result["viewed"]["VALID_PHONE_CHECKED"])
		{
			//$phoneVerified = isValidPhone($jprofile_result["viewed"]["PROFILEID"], $jprofile_result["viewed"]["PHONE_MOB"]);

			$chk_mobStatus 	=getPhoneStatus($jprofile_result["viewed"],'','M');
			$chk_landlStatus=getPhoneStatus($jprofile_result["viewed"],'','L');
			if($chk_mobStatus =='Y' || $chk_landlStatus =='Y')
			{
				if($chk_mobStatus =="Y" && $chk_landlStatus =='Y')
				{
					$jprofile_result["viewed"]["VALID_PHONE"]["MOB"] = 1;
					$jprofile_result["viewed"]["VALID_PHONE"]["LANDLINE"]=1;
				}
				else if($chk_landlStatus =="Y")
					$jprofile_result["viewed"]["VALID_PHONE"]["LANDLINE"]=1;
				else if($chk_mobStatus =="Y")
					$jprofile_result["viewed"]["VALID_PHONE"]["MOB"] = 1;
			}
		}
	}
	$isd=$jprofile_result['viewed']['ISD'];
        if($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $jprofile_result["viewed"]["PHONE_RES"]!="")
	{
                $phone_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["PHONE_NUMBER_OWNER"]];
                $phone_owner_name=$jprofile_result['viewed']["PHONE_OWNER_NAME"];
                $res_phone=$jprofile_result["viewed"]["STD"]."-".$jprofile_result["viewed"]["PHONE_RES"];
		if($isd)
			$res_phone="$isd-$res_phone";
        }

        if($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $jprofile_result["viewed"]["PHONE_MOB"]!="")
        {
		$mobile_owner_number=$NUMBER_OWNER[$jprofile_result['viewed']["MOBILE_NUMBER_OWNER"]];
                $mobile_owner_name=$jprofile_result['viewed']["MOBILE_OWNER_NAME"];
                $mob_phone=$jprofile_result["viewed"]["PHONE_MOB"];
		if($isd)
			$mob_phone="$isd-$mob_phone";
        }

        if($jprofile_result["viewed"]["CONTACT"]!="" && $jprofile_result["viewed"]["SHOWADDRESS"]=="Y")
		$SHOW_ADDRESS = nl2br($jprofile_result["viewed"]["CONTACT"]);
        if($jprofile_result["viewed"]["SHOW_PARENTS_CONTACT"]=="Y" && $jprofile_result["viewed"]["PARENTS_CONTACT"]!="")
		$SHOW_PARENTS_ADDRESS = nl2br($jprofile_result["viewed"]["PARENTS_CONTACT"]);

        if($res_phone || $mob_phone)
                if($jprofile_result["viewed"]['TIME_TO_CALL_START'] && $jprofile_result["viewed"]['TIME_TO_CALL_END'])
                {
			$TIME_TO_CALL_START = $jprofile_result["viewed"]['TIME_TO_CALL_START'];
			$TIME_TO_CALL_END = $jprofile_result["viewed"]['TIME_TO_CALL_END'];
                }
        if($jprofile_result['viewed']['RELATION'])
		$RELATION_NAME = $RELATIONSHIP[$jprofile_result['viewed']['RELATION']];
	if($jprofile_result["viewed"]["SHOWMESSENGER"]=='Y' && $jprofile_result["viewed"]["MESSENGER_CHANNEL"] && $jprofile_result["viewed"]["MESSENGER_ID"])
        {
                $messenger=$jprofile_result["viewed"]["MESSENGER_ID"];
                if(!strstr($messenger,"@"))
                        $messenger=$messenger."@".$MESSENGER_CHANNEL[$jprofile_result["viewed"]["MESSENGER_CHANNEL"]];
		$SHOW_MESSENGER = $messenger;
        }
        $contact_locked=1;
	if($jprofile_result["viewer"]["INVALID_PHONE_CHECKED"])
		$contact_locked=$jprofile_result["viewer"]["CONTACT_LOCKED"];
	else
	        $contact_locked=check_invalid_ph_landline($jprofile_result);

	//Checking for filter condition for evalue members.
	$type=$jprofile_result['type'];
	$who=$jprofile_result['who'];
	if($jprofile_result['IS_FILTER'] && $contact_locked==1 && !$jprofile_result['ALREADY_VIEWED'])
	{
		if(!($type=="A" || ($type=='I' && $who=="") || ($type=='D' && $who=="")))
		{
			$contact_locked=0;
			$con_det_mes="You have been filtered by the member.";
			$smarty->assign("CON_DET_MES",$con_det_mes);
			
		}
		
	}

	//If profile is incomplete.
	if($jprofile_result['viewer']['INCOMPLETE']=='Y' && $contact_locked==1)
        {
		$contact_locked=0;
		$profilechecksum=createchecksumforsearch($jprofile_result['viewer']['PROFILEID']);
		$con_det_mes="You need to complete your profile before viewing the user's contact details. Please <a href='/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile' class='blink'>click here</a> to complete your profile.";
		$smarty->assign("CON_DET_MES",$con_det_mes);


        }

	//If profile is under screening.
	$activated=$jprofile_result['viewer']['ACTIVATED'];
	if($contact_locked==1)
		if($activated=='U' || $activated=='N' || $activated=='P' )
		{
			$contact_locked=0;
			$con_det_mes="You can view the contact details of the user only after your profile is screened.";
			$smarty->assign("CON_DET_MES",$con_det_mes);
		}

	if(!$data)
                $contact_locked=0;
        $email = "";
        if($jprofile_result["viewed"]["EMAIL"])
                $email = $jprofile_result["viewed"]["EMAIL"];

	/*********Alternative number********/
	$altMob = "";
	$altMobDetail = "";
	$sql = "select ALT_MOBILE, ALT_MOBILE_ISD, SHOWALT_MOBILE, ALT_MOBILE_OWNER_NAME, ALT_MOBILE_NUMBER_OWNER from newjs.JPROFILE_CONTACT where profileid=".$jprofile_result["viewed"]["PROFILEID"];
	$res = mysql_query_decide($sql);
	while($row=mysql_fetch_array($res)){
		if($row["SHOWALT_MOBILE"]=='Y' && $row["ALT_MOBILE"]){
			if($row["ALT_MOBILE_ISD"]) $altIsd = $row["ALT_MOBILE_ISD"]."-";
			$altMob = $altIsd.$row["ALT_MOBILE"];
			if($row["ALT_MOBILE_OWNER_NAME"]){ 
				$altMobDetail.=" of ".$row["ALT_MOBILE_OWNER_NAME"];
				if($row["ALT_MOBILE_NUMBER_OWNER"]) $altMobDetail.=" (".$NUMBER_OWNER[$row["ALT_MOBILE_NUMBER_OWNER"]].")";
			}
		}
	}
	/*********Ends here******************/
	/***********Call now********/
	if($jprofile_result["viewed"]["CALLNOW_CHECKED"])
		$callnowSetting = $jprofile_result["viewed"]["CALL_ANONYMOUS"];
	else
		$callnowSetting = getCallNowSetting($jprofile_result['viewed']["PROFILEID"]);
	if($callnowSetting)
	{
		global $index;
		$contact_accept_layer = $jprofile_result['REDIRECT_TO_CONTACT']?1:0;
		$mob_phone = "<img src=\"$IMG_URL/images/lock_call.gif\" style=\"vertical-align: middle;\"/>";
		$res_phone = "<img src=\"$IMG_URL/images/lock_call.gif\" style=\"vertical-align: middle;\"/>";
		if($altMob) $altMob = "<img src=\"$IMG_URL/images/lock_call.gif\" style=\"vertical-align: middle;\"/>";
		//$altMob = "";
		$profilechecksum=md5($jprofile_result["viewed"]["PROFILEID"]) ."i". $jprofile_result["viewed"]["PROFILEID"];
		$callnow_link="<div class=\"fl f11 wtbox\">This user prefers to recieve call through our system<br><strong><a class=\"blink\" style=\"text-align:center; display:block; font-size:18px; text-decoration:none\" onClick=\"return show_callnow_layer('$profilechecksum','$index','$contact_accept_layer');\">Call NOW</a></strong></div><div class=\"sp16\"></div>";
		$smarty->assign("SHOW_CALLNOW_LINK",$callnow_link);
	}
	/************Ends here********/
	$smarty->assign("ALT_MOBILE_LABEL",$altMobDetail);
	$smarty->assign("ALT_MOBILE",$altMob);
	$smarty->assign("SHOW_ADDRESS",$SHOW_ADDRESS);
	$smarty->assign("SHOW_PARENTS_ADDRESS",$SHOW_PARENTS_ADDRESS);
	$smarty->assign("TIME_TO_CALL_START",$TIME_TO_CALL_START);
	$smarty->assign("TIME_TO_CALL_END",$TIME_TO_CALL_END);
	$smarty->assign("RELATION_NAME",$RELATION_NAME);
	$smarty->assign("SHOW_MESSENGER",$SHOW_MESSENGER);
	//$smarty->assign("MYMOBILE",$MYMOBILE);
        $smarty->assign("PHONE_NO",$res_phone);
        $smarty->assign("SHOW_MOBILE",$mob_phone);
        $smarty->assign("PHONE_PROFILENAME",$phone_owner_name);
        $smarty->assign("PHONE_RELATION_NAME",$phone_owner_number);
        $smarty->assign("MOB_PROFILENAME",$mobile_owner_name);
        $smarty->assign("MOB_RELATION_NAME",$mobile_owner_number);
        $smarty->assign("EMAIL_ID",$email);
        $smarty->assign("CONTACT_LOCKED",$contact_locked);
        $smarty->assign("VERIFIED_LANDLINE",$jprofile_result["viewed"]["VALID_PHONE"]["LANDLINE"]);
        $smarty->assign("VERIFIED_MOB",$jprofile_result["viewed"]["VALID_PHONE"]["MOB"]);
}
function set_drafts($paid,$type,$who,$other_paid,$relation,$fixed='')
{
        global $data,$smarty,$RELATIONSHIP,$to_do,$ajax_error,$isMobile;
        $pid=$data['PROFILEID'];
        $username=$data['USERNAME'];
        if($type!="" && $type!='I')
        {
                $smarty->assign("DEFAULT_MESSAGE","");
        }
	$user_accept_drafts=0;
	if($other_paid && $type=="I" && $who=="")
	{
		if($paid=='')
			$smarty->assign("UPDATE_DECLINE",1);
	}

        if($paid)
        {
                $smarty->assign("DEFAULT_MESSAGE","");

                $sql="select MESSAGE,DRAFTID,DRAFTNAME,DECLINE_MES from newjs.DRAFTS where PROFILEID='$pid' ORDER BY CREATE_TIME DESC";
                $res=@mysql_query_decide($sql) or logError("",$sql);

/*		$ACCEPT['WNM']="Write New Message";
		$DECLINE['WNM']="Write New Message";
		$DRA_MES['WNM']="";*/
                while($row=@mysql_fetch_array($res))
                {
                        if($row['DECLINE_MES']=='N')
                        {
				$user_accept_drafts=1;
                                $ACCEPT[$row[1]]=stripslashes(htmlspecialchars($row[2],ENT_QUOTES));
                        }
                        else
			{
                                $DECLINE[$row[1]]=stripslashes(htmlspecialchars($row[2],ENT_QUOTES));
			}

			$DRA_MES[$row[1]]=preg_replace("/\\r\\n|\\n|\\r/","#n#",htmlspecialchars($row[0],ENT_QUOTES));
                }
		

        }
	if($type=="")
        {
		$ACCEPT['PRE_1']="Preset Message";
		$show_what='EOI';
		$add_slash='yes';
		include_once("preset_message.php");
		if($paid){
			$ACCEPT['WNM']="Write New Message";
			$DECLINE['WNM']="Write New Message";
			$DRA_MES['WNM']="";
		}
	 }
        elseif($type=='I' && $who=='')
        {
		$ACCEPT['PRE_2']="Preset Message";
                $DECLINE['D1']="Preset Message";
                $show_what='DECLINE';
                $add_slash='yes';
                include_once("preset_message.php");
		if($paid){
			$ACCEPT['WNM']="Write New Message";
			$DECLINE['WNM']="Write New Message";
			$DRA_MES['WNM']="";
		}
        }
	if($type=="" || ($type=='I' && $who==""))
	{
	}
	elseif($type=='C')
        {
                if($other_paid!="")
                {
                        unset($ACCEPT);
                }
                $DECLINE="";
        }
	else
	{
		if($DRA_MES)
			unset($DRA_MES);
		if($DECLINE)
	               unset($DECLINE);
                if($ACCEPT)
			unset($ACCEPT);
	}
	if($fixed)
	{
		if($type=='')
		{
			if($isMobile)
			{
				$smarty->assign("DEF_MES",$DRA_MES["PRE_1"]);
				$smarty->assign("DEF_ID","PRE_1");
			}
			$DRA_MES["PRE_1"] = ereg_replace("#n#","\n",stripslashes($DRA_MES["PRE_1"]));
			$smarty->assign("DRAFTS",$ACCEPT);
			$smarty->assign("cntDrafts",count($ACCEPT));
			//if(@!in_array("Write New Message",$ACCEPT))
			$smarty->assign("DEFAULT_MESSAGE",$DRA_MES["PRE_1"]);
		}
		else
		{
			if($isMobile)
			{
				$smarty->assign("DEF_MES",$DRA_MES["D1"]);
				$smarty->assign("DEF_ID","D1");
			}
			$DRA_MES["D1"] = ereg_replace("#n#","\n",stripslashes($DRA_MES["D1"]));
			if($to_do=='decline')
			{
                                if(!$paid && !$allow_mes_write)
                                        $DECLINE = "";
				$smarty->assign("DRAFTS",$DECLINE);
				$smarty->assign("cntDrafts",count($DECLINE));
				//if(@!in_array("Write New Message",$DECLINE))
				$smarty->assign("DEFAULT_MESSAGE",$DRA_MES["D1"]);
			}
			elseif(($to_do=="accept" || $to_do=="accept_contact" || $to_do=="accept_contact_message") && $type=='I')
			{
				if($isMobile)
				{
					$smarty->assign("DEF_MES",$DRA_MES["PRE_2"]);
					$smarty->assign("DEF_ID","PRE_2");
				}
				$DRA_MES["PRE_2"] = ereg_replace("#n#","\n",stripslashes($DRA_MES["PRE_2"]));
                                //if(@!in_array("Write New Message",$DECLINE))
                                $smarty->assign("DEFAULT_MESSAGE",$DRA_MES["PRE_2"]);
				$smarty->assign("DRAFTS",$ACCEPT);
				$smarty->assign("cntDrafts",count($ACCEPT));
			}
			else
			{
				$ACCEPT['PRE_1']="Write New message";
				$smarty->assign("cntDrafts",1);
				$smarty->assign("DRAFTS",$ACCEPT);
			}
		}
	}
	else
	{
		$smarty->assign("DRAFT",$DRAFT);
		$smarty->assign("DRA_MES",$DRA_MES);
		$smarty->assign("DEFAULT_MESSAGE","");
		$smarty->assign("DECLINE_OPTION",$DECLINE);
		$smarty->assign("ACCEPT_OPTION",$ACCEPT);
	}
}
function get_user_drafts($message,$status)
{
        global $smarty,$data;
         //Replaces the message with particular draft.
        $overwrite=1;
        $pid=$data['PROFILEID'];
        if($pid)
        {
                $sql="select MESSAGE,DRAFTID,DRAFTNAME from newjs.DRAFTS where PROFILEID=$pid";
                if($status=="D")
                        $sql.=" AND DECLINE_MES!='N' ORDER BY CREATE_TIME DESC ";
                else
                        $sql.=" AND DECLINE_MES='N' ORDER BY CREATE_TIME DESC ";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                $start=0;
                while($row=mysql_fetch_array($res))
                {
                        $DRAFT[$row[1]]=htmlspecialchars($row[2],ENT_QUOTES);
                        //No need to show replace option , since the message is already from draft.
                        if($row[0]==$message)
                                $overwrite=0;
                        $DRA_MES[$row[1]]=htmlspecialchars($row[0],ENT_QUOTES);
                        $start++;


                }
                if($start>=5 && $overwrite==1)
                {
                        $smarty->assign("DRA_MES_OPTION",$DRAFT);
                        $smarty->assign("OVERFLOW",1);
                }
        }
}
function get_all_drafts($paid,$relation)
{
	global $data,$smarty,$isMobile;
	$pid=$data['PROFILEID'];
        $username=$data['USERNAME'];
	$user_accept_drafts=0;

        if($paid)
        {
//              $DECLINE="<option value=''>Write New message</option>";
		//$DECLINE['und']="Write New message";
                //$ACCEPT=$DECLINE;
                /*$ACCEPT['WNM']="Write New Message";
                $DECLINE['WNM']="Write New Message";
                $DRA_MES['WNM']="";*/

                $sql="select MESSAGE,DRAFTID,DRAFTNAME,DECLINE_MES from newjs.DRAFTS where PROFILEID=$pid ORDER BY CREATE_TIME DESC";
                $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
                {
                        if($row['DECLINE_MES']=='N')
                        {
				$user_accept_drafts=1;
                                $ACCEPT[$row[1]]=stripslashes(htmlspecialchars($row[2],ENT_QUOTES));
                        }
                        else
			{
                                $DECLINE[$row[1]]=stripslashes(htmlspecialchars($row[2],ENT_QUOTES));
			}

			//Replace new line with sequence of characters, required in javascript.
			$DRA_MES[$row[1]]=ereg_replace("\r\n|\n|\r","#n#",stripslashes(htmlspecialchars($row[0],ENT_QUOTES)));
                }
        }
		$ACCEPT['PRE_1']="Preset Message";
		$ACCEPT['PRE_2']="Preset Message";
		$DECLINE['D1']="Preset Message";
		if($paid){
			$ACCEPT['WNM']="Write New Message";
			$DECLINE['WNM']="Write New Message";
			$DRA_MES['WNM']="";
		}
			if($user_accept_drafts)
				$ONLY_ACCEPT=$ACCEPT;
                $show_what='ALL';
                $add_slash='yes';
                include_once("preset_message.php");
                $smarty->assign("DRAFT",$DRAFT);
                $smarty->assign("DRA_MES_ALL",$DRA_MES);
				if(!$isMobile)
               	$smarty->assign("DEFAULT_MESSAGE","");
                $smarty->assign("DECLINE_OPTION_ALL",$DECLINE);
                $smarty->assign("ACCEPT_OPTION_ALL",$ACCEPT);
		$smarty->assign("ONLY_ACCEPT_OPTION_ALL",$ONLY_ACCEPT);
}
function member_101_details_show($viewer_data,$jpartnerObj)
{
	mail('niteshsethi1987@gmail.com','checking wheter this function is in used or not','member_101_details_Show:partner_elevel_coloumn'.$HTTP_SERVER_VARS);
      /*         $show=1;
        $LAGE=$jpartnerObj->getLAGE();
        $HAGE=$jpartnerObj->getHAGE();
        if($LAGE && $HAGE)
        {
                if($viewer_data["AGE"]>=$LAGE && $viewer_data["AGE"]<=$HAGE)
                        ;
                else
                        return false;        
	}        
	$lheight=$jpartnerObj->getLHEIGHT();        
	$hheight=$jpartnerObj->getHHEIGHT();
        if($lheight && $hheight)
        {
                if($viewer_data["HEIGHT"]>=$lheight && $viewer_data["HEIGHT"]<=$hheight)
                        ;
                else
                        return false;
        }
        $handicapped=$jpartnerObj->getHANDICAPPED();
        if($handicapped)
        {
                if($viewer_data["HANDICAPPED"]==$handicapped)
                        ;
                else
                        return false;
        }
        unset($tempstr);
        unset($temp);
	$tempstr=$jpartnerObj->getPARTNER_BTYPE();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["BTYPE"],$temp))
                        ;
                else
                        return false;
        }

        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_CASTE();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                $temp=get_all_caste($temp);
                if(in_array($viewer_data["CASTE"],$temp))
                        ;
                else
                        $show=1;
        }
	unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_CITYRES();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                for($i=0;$i<count($temp);$i++)
                {
                        if(is_numeric($temp[$i]))
                        {
                                $country_usa=1;
                                $city_usa[]=$temp[$i];
                        }
                        elseif(strlen($temp[$i])==2)
                        {
                                $country_india=1;
                                $citysql="select SQL_CACHE VALUE from newjs.CITY_NEW where VALUE like '$temp[$i]%'";
                                $cityresult=mysql_query_decide($citysql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$citysql,"ShowErrTemplate");

                                while($cityrow=mysql_fetch_array($cityresult))
                                {
                                        $city_india[]=$cityrow["VALUE"];
                                }

                                mysql_free_result($cityresult);
                         }
                        else
                        {
                                $country_india=1;
                                $city_india[]=$temp[$i];
                        }

                }
		if($country_india)
                {
                        if($viewer_data["COUNTRY_RES"]=="51" && in_array($viewer_data["CITY_RES"],$city_india))
                                $city_match=1;
                        else
                                ;
                }
                if($country_usa)
                {
                        if(in_array($viewer_data["COUNTRY_RES"]=="128" && $viewer_data["viewer"]["CITY_RES"],$city_usa))
                                $city_match=1;
                        else
                                ;
                }
                if(!$country_india && !$country_usa)
                {
                        unset($tempstr);
                        unset($temp);
                        $tempstr=$jpartnerObj->getPARTNER_COUNTRYRES();
                        if($tempstr)
                        {
                                $temp=display_format($tempstr);
                                if(in_array($viewer_data["COUNTRY_RES"],$temp))
                                        $city_match=1;
                                else
                                        ;
                        }
                }
                if($city_match)
                        $show=1;
                else
                        return false;
        }
	unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_COUNTRYRES();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["COUNTRY_RES"],$temp))
                        ;
                else
                        return false;
        }

        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_DIET();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["DIET"],$temp))
                        ;
                else
                        return false;
        }
        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_DRINK();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["DRINK"],$temp))
                        ;
                else
                        return false;
        }
	 unset($tempstr);
        unset($temp);
        
	$tempstr=$jpartnerObj->getPARTNER_ELEVEL_NEW();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["EDU_LEVEL_NEW"],$temp))
                        ;
                else
                        return false;
        }

        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_INCOME();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["INCOME"],$temp))
                        ;
                else
                        return false;
        }
	unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_MANGLIK();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["MANGLIK"],$temp))
                        ;
                else
                        return false;
        }

        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_MSTATUS();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["MSTATUS"],$temp))
                        ;
                else
                        return false;
        }
        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_MTONGUE();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["MTONGUE"],$temp))
                        ;
                else
                        return false;
        }
	unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_OCC();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["OCCUPATION"],$temp))
                        ;
                else
                        return false;
        }
        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_SMOKE();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["SMOKE"],$temp))
                        ;
                else
                        return false;
        }

        unset($tempstr);
        unset($temp);
        $tempstr=$jpartnerObj->getPARTNER_COMP();
        if($tempstr)
        {
                $temp=display_format($tempstr);
                if(in_array($viewer_data["COMPLEXION"],$temp))
                        ;
                else
                        return false;
	}
        return $show;*/
}
function get_navigation_link($navi_id)
{
	if($navi_id)
	{
		global $memcacheObj;
		if(!$memcacheObj && class_exists(UserMemcache))
			$memcacheObj=new UserMemcache;
		if($memcacheObj)
		{
			return $memcacheObj->getDataFromMem($navi_id);
		}
	}
}
function navigation_inserted_id($navigation)
{
	global $memcacheObj;
	if(!$memcacheObj && class_exists(UserMemcache))
		$memcacheObj=new UserMemcache;
	if($memcacheObj )
	{
		return $memcacheObj->setDataToMem($navigation);
	}

}
function develop_url()
{
	foreach($_GET as $key=>$val)
                {
                        if( $key!='overwrite' && $key!='MESSAGE' && $key!='CALL_ME' && $key!='ID_CHECKED' && $key!='google_kwd' && $key!='after_login_call' && $key!='ajax_error' && $key!='from_viewprofile' && $key!='nextViewSim' && $key!='draft_id' && $key!='type_of_con' && $key!="MESSAGE" && $key!='draft_name' && $key!='from_search' && $key!='AllPhotos')
                                $link.=$key."__".$val."@";
                        if($key=='nextViewSim')
                                $link.='nextViewSim1__1@';
                }
               
	return $link;
}
function navigation($type,$param,$username='',$symfony='')
{
        global $smarty;
	global $cc_navigator;
        $navi_id=$_GET["NAVIGATOR"];
	
	if(!$_GET['NAVIGATOR'] && ($type=="CVS"|| $type=='VS' || $type == 'CVS_NEW')  && $param=="")
		$param=develop_url();

	$pass_navig=0;
	//Checking if NAVIGATOR value contains combination of navigation id and table part id
	if($_GET['NAVIGATOR'])
	{
		list($naviid,$part)=explode("_",$_GET["NAVIGATOR"]);
		if(intval($part) && intval($naviid) && $part<=10)
			$pass_navig=1;
	}

	//Generating navi_id if NAVIGATOR is string , usually string value comes from mailer or my jeevansathi page
	if(strlen($_GET["NAVIGATOR"])>0 && $pass_navig==0 && strlen($_GET['NAVIGATOR'])!=32)
	{
		$navi_id=navigation_inserted_id($_GET["NAVIGATOR"]);
		$_GET["NAVIGATOR"]=$navi_id;
	}
	if($navi_id)
	{
		$navigator=get_navigation_link($navi_id);
	}
	if(!$navigator)
		$navigator="";

        //If script is called by open_tab layer from viewprofile.
        if(strstr($param,"SIM_USERNAME"))
                $coming_from_dp=1;
	
		if($navigator=="" && $param=="")
			return 0;
		
		$arrAllowedSearchPage_NavType = array('SR','MA','KM');
        //$navigator="SR:searchid_6402/Back to Search Results;VS:PQ6500(686500)/View Similar results to PQ6500(686500);";
        $max_allowed=3;
        //Type is SR , DP, VS, CVS
        if(!in_array($type,$arrAllowedSearchPage_NavType) && $param!="" && $navigator=="")
        {
		if($type=="ACC")
			$to_show="All Acceptances";
		elseif($type=="ACC_M")
			$to_show="People I Accepted";
		elseif($type=="ACC_R")
			$to_show="People who Accepted me";
		elseif($type=="CVSM")
			$to_show="Contact Viewers Mobile";
		else
		if($type=="FAV")
                        $to_show="People I have Shortlisted";
		else if($type=="PHO_R")
			$to_show="Photo Requests Received";
		else if($type=="PHO_M")
			$to_show="Photo Requests Sent";
		else if($type=="HOR_M")
			$to_show="Horoscope Requests Sent";
		else if($type=="HOR_R")
			$to_show="Horoscope Requests Received";
                else
		if($type=="CHAT")
                        $to_show="Chat Requests Received";
                else
		if($type=="IGN")
                        $to_show="Blocked Members";
                else
		if($type=="DEC_R")
                        $to_show="People Not Interested";
                else
		if($type=="DEC_S")
                        $to_show="People I Declined";
                else
		if($type=="MAT")
                        $to_show="Match Alerts";
		elseif($type=="KUN")
			$to_show="Kundli Matches";
                else
		if($type=="VIS")
                        $to_show="People who Visited my Profile";
                else
		if($type=="EOI")
			$to_show="People I Have to Respond to";
                else
                if($type=="REM")
                        $to_show="People yet to Respond";
		else
		if($type=="ARC")
                        $to_show="Archived Interests";
                else
		if($type=="MES_R")
                        $to_show="Messages Received";
                else
		if($type=="MES_M")
                        $to_show="Messages Sent";
                else
        if($type=="MES_A")
                        $to_show="All Messages";
                else
		if($type=="FIL")
                        $to_show="Filtered Interests";
                else
		if($type=="INB")
                        $to_show="All New Items";
		else
		if($type=="CALL")
			$to_show="Call history";
		else
		if($type=="IC")
			$to_show="People to be Called";
		else
		if($type=="VC")
			$to_show='People Whose Phone&#47Emails I Viewed';
		else
		if($type=="VCB")
			$to_show="People who Viewed my Phone&#47Email";
		elseif($type=='PCV')
		        $to_show="Phonebook";
		elseif($type=='CVS_NEW')
		        $to_show="Profile of $username";
        else if($type=='JVS')//Jsms View Similar
		        $to_show="Profile similar to $username";
		else
		        $to_show="Profiles similar to $username";
	
                $link=$param;
                $actual=$link."/".$to_show;
                $link_developed="$type:$actual;;";
        }
        elseif(in_array($type,$arrAllowedSearchPage_NavType) && $param!="")
        {
			if($type=='SR')
			{
				$to_show="Search Results";
			}
			else if($type=='MA')
			{
				$to_show="Match Alerts";
			}
			else if($type=='KM')
			{
				$to_show="Kundli Alerts";
			}
		$param1=explode(":",$param);
		$param=$param1[0];
                $link="searchId__$param";
                $actual=$link."/".$to_show;
                $link_developed=$type.":$actual;;";
		$sr_navig_id=navigation_inserted_id($link_developed);
		if($param1[1])
			$actual=$link."@currentPage__".$param1[1]."@NAVIGATOR__$sr_navig_id/$to_show";
		else
			$actual=$link."@currentPage__1@NAVIGATOR__$sr_navig_id/$to_show";
/*
		if($param1[1])
			$actual=$link."@offset__".$param1[1]."@NAVIGATOR__$sr_navig_id@j__1/$to_show";
		else
			$actual=$link."@NAVIGATOR__$sr_navig_id@j__1/$to_show";
*/
                $link_developed=$type.":$actual;;";
		
		
                //return $link_developed;
        }
	elseif($navigator)
        {
                $link_arr=explode(";",$navigator);
                $total=count($link_arr);
                $first=$link_arr[0];
                $second=$link_arr[1];
                $third=$link_arr[2];
                $link='';
                if(isset($_GET['overwrite']))
                        $not_allow=1;
		$link=develop_url();

                //Overwrite the link that to be developed ..
                if($coming_from_dp==1)	
                        $link=$param;

                if($link)
                {
                        $link=substr($link,0,strlen($link)-1);
                }

                $actual='';
                $link_developed='';
                if($type=='SR')
                {
                        $to_show="Search Results";
                }
				if($type=='MA')
				{
					$to_show="Match Alerts";
				}
				if($type=='KM')
				{
					$to_show="Kundli Alerts";
				}
                if($type=='DP')
                {
                        $to_show="Detailed profile of $username";
                }
                if($type=='DP_NEW')
                {
                        $to_show="Profile of $username";
                }
		if($type=='VS')
                {
                        $to_show="Profiles similar to $username";
                }
                if($type=='CVS_NEW')
                {
                        $to_show="Profile of $username";
                }
                if($type=='CVS')
                {
                        $to_show="Profiles similar to $username";
                }
		if($type=="ACC")
		{
                        $to_show="Accepted Members";
		}
                if($type=="FAV")
		{
                        $to_show="Shortlisted Members";
		}
                if($type=="PHO")
		{
                        $to_show="Photo Requests";
		}
                if($type=="HOR")
		{
                        $to_show="Horoscope Requests";
		}
                if($type=="CHAT")
		{
                        $to_show="Chat Requests";
		}
                if($type=="IGN")
		{
                        $to_show="Blocked Members";
		}
                if($type=="DEC_R")
		{
                        $to_show="People Not Interested";
		}
                if($type=="DEC_S")
		{
                        $to_show="People I Declined";
		}
                if($type=="MAT")
		{
                        $to_show="Recommended Matches";
		}
		if($type=="KUN")
		{
			$to_show="Kundli Matches";
		}
                if($type=="VIS")
		{
                        $to_show="Profile Visitors";
		}
                if($type=="EOI")
		{
                        $to_show="People I Have to Respond to";
		}
                if($type=="REM")
		{
                        $to_show="People yet to Respond";
		}
                if($type=="ARC")
		{
                        $to_show="Archived Interests";
		}
                if($type=="MES")
		{
                        $to_show="Messages";
		}
                if($type=="FIL")
		{
                        $to_show="Filtered Interests";
		}
                if($type=="INB")
		{
                        $to_show="All New Items";
		}
		if($type=='CALL')
		{
			$to_show="Call history";
		}
		if($type=='IC')
		{
			$to_show="People to be Called";
		}
                if($type=='VC')
                {
                        $to_show='Phone&#47Emails I Viewed';
                }
                if($type=='VCB')
                {
                        $to_show="People who Viewed my Phone&#47Email";
                }
        if($type=="MVS")
        {
			$to_show="People who viewed my contacts";
		}
                if($type=="MMH")//Mobile Message Handler
                {
                        $to_show="Mobile message handler";
                }
                if($type=="RVS")//Mobile Message Handler
                {
                        $to_show="Mobile send reminder";
                }
                if($type=="PCV")//Mobile Message Handler
                {
                        $to_show="Phonebook";
                }

                if($type=="JVS")//Jsms View Similar
                {
                        $to_show="Jsms View Similar";
                }

                $actual=$link."/".$to_show;
                $link_developed="$type:$actual";
                if($_GET['overwrite'])
                {
                        if($third!="")
                                $third=$link_developed;
                        elseif($second!="")
                                $second=$link_developed;
                        elseif($first!="")
                                $first=$link_developed;
                }
                else
                {
                        if($second=="")
                        {
                                $second=$link_developed;
                                $total--;
                        }
                        elseif($third=="")
                        {

				if(substr($second,0,2)=='DP' && $type=='DP_NEW')
                                {
                                        $third=$link_developed;

                                        $total--;
                                }
				elseif(substr($second,0,2)=='DP' && $type=='DP')
                                {
                                        $second=$link_developed;

                                        $total--;
                                }
                                else
                                {
                                        $total--;
                                        $third=$link_developed;
                                }
                        }
                        //JSMS Ecp Page, Coming from Level 2 Ecp page to DP
                        if ($type=="DP"                     && 
                            stripos($third,'DP')!==false    && 
                            stripos($second,'JVS')!==false)
                        {
                            $third = $link_developed;
                        }
                        else if($type=="JVS"                && 
                            stripos($third,'JVS')!==false   && 
                            stripos($second,'DP')!==false)
                        {//Case in which hard refresh happening on JSMS ECP Page
                            $third = $link_developed;
                        }                        
                        else if($total>=3)
                        {
                                $second=$third;
                                $third=$link_developed;
                          	$show_all=1;
                        }
                        //$link_developed="$first;$second;$third";
                }
                $link_developed="$first;$second;$third";
			
        }
//echo $link_developed;
	
	$ins_row=navigation_inserted_id($link_developed);
	$cc_navigator="NAVIGATOR=$ins_row";	
	$smarty->assign("NAVIGATOR",$cc_navigator);
	$smarty->assign("NAVIGATOR_LINK","$ins_row");
	$showNavigator=show_navigation($link_developed,$show_all,$ins_row);
	if($symfony==2)
		return $showNavigator;
	if($symfony)
		return $cc_navigator;
        return $link_developed;
}
function show_navigation($navigator,$show_all,$navig_id)
{
	global $smarty,$SITE_URL;
        $isMobile = MobileCommon::isMobile();
        $isPc = MobileCommon::isDesktop();
	$type_arr=explode(";",$navigator);
	
	$TYPE['SR']="/search/perform";
	$TYPE['VS']="/profile/simprofile_search.php";
	$TYPE['DP']="/profile/viewprofile.php";
	$TYPE['DP_NEW']="/profile/viewprofile.php";
	$TYPE["CVS_NEW"]="/profile/viewprofile.php";
	$TYPE["CVS"]="/search/viewSimilarProfile";
	$TYPE["ACC"]="/profile/contacts_made_received.php";
	$TYPE["CVSM"]="/profile/contacts_made_received.php";	
	if($isPc)
	{
		$TYPE["PCV"]="/inbox/index";
		$TYPE["MES_A"]="/inbox/index";
		$TYPE["ACC_R"]="/inbox/index";
		$TYPE["ACC_M"] = "/inbox/index";
		$TYPE["PHO_R"]="/inbox/index";
		$TYPE["PHO_M"]="/inbox/index";
		$TYPE["HOR_R"]="/inbox/index";
		$TYPE["IGN"]="/inbox/index";
		$TYPE["DEC_R"]="/inbox/index";
		$TYPE["DEC_S"]="/inbox/index";
		$TYPE["EOI"]= "/inbox/index";
		$TYPE["REM"]= "/inbox/index";
		$TYPE["MES"]="/inbox/index";
		$TYPE["FIL"]="/inbox/index";
		$TYPE["VCB"]="/inbox/index";
		$TYPE["HOR_M"]="/inbox/index";
	}
	else
	{
		$TYPE["PCV"]="/profile/contacts_made_received.php";
		$TYPE["ACC_R"]="/profile/contacts_made_received.php";
		$TYPE["ACC_M"]="/profile/contacts_made_received.php";
		$TYPE["PHO_R"]="/profile/contacts_made_received.php";
		$TYPE["PHO_M"]="/profile/contacts_made_received.php";
		$TYPE["HOR_R"]="/profile/contacts_made_received.php";
		$TYPE["IGN"]="/profile/contacts_made_received.php";
		$TYPE["DEC_R"]="/profile/contacts_made_received.php";
		$TYPE["DEC_S"]="/profile/contacts_made_received.php";
		$TYPE["EEOI"]="/profile/contacts_made_received.php";
		$TYPE["EOI"]="/profile/contacts_made_received.php";
		$TYPE["REM"]="/profile/contacts_made_received.php";
		$TYPE["MES"]="/profile/contacts_made_received.php";
		$TYPE["FIL"]="/profile/contacts_made_received.php";
		$TYPE["VCB"]="/profile/contacts_made_received.php";
		$TYPE["HOR_M"]="/profile/contacts_made_received.php";
		$TYPE["AEOI"]="/profile/contacts_made_received.php";

	}
	$TYPE["FAV"]="/profile/contacts_made_received.php";
	$TYPE["PHO"]="/profile/contacts_made_received.php";
	$TYPE["HOR"]="/profile/contacts_made_received.php";
	$TYPE["CHAT"]="/profile/contacts_made_received.php";
	$TYPE["MAT"]="/profile/contacts_made_received.php";
	$TYPE["KUN"]="/profile/contacts_made_received.php";
	$TYPE["VIS"]="/profile/contacts_made_received.php";
	$TYPE["ARC"]="/profile/contacts_made_received.php";
	$TYPE["MES_R"]="/profile/contacts_made_received.php";
	$TYPE["MES_M"]="/profile/contacts_made_received.php";
	$TYPE["INB"]="/profile/contacts_made_received.php";
	$TYPE["CALL"]="/profile/contacts_made_received.php";
	$TYPE["IC"]="/profile/contacts_made_received.php";
	$TYPE["VC"]="/profile/contacts_made_received.php";
	$TYPE["FP"]="/search/perform";
	$TYPE["MVS"] = "/contacts/PostEOI";//Mobile View Similar
    $TYPE["MMH"] = "/contacts/MessageHandle";//Mobile Message Handler
    $TYPE["RVS"] = "/contacts/PostSendReminder";//Mobile Send Reminder View Similar
    $TYPE["MA"] = "/search/matchalerts";//Match Alerts
    $TYPE["KM"] = "/search/kundlialerts";//Kundli Alerts
    $TYPE["JVS"] = "/search/MobSimilarProfiles";//Jsms View Similar
    
     if($isMobile)
    {
		
		foreach($TYPE as $k=>$v)
		{
			if($v=="/profile/contacts_made_received.php")
			{
				$TYPE[$k]="/inbox/jsmsPerform";
				
			}
		}
	}
    
	$minus=0;
	if($type_arr[1]=="")
		$minus=+2;
	elseif($type_arr[2]=="")
		$minus=+1;
	if($show_all)
		$upto=count($type_arr)-$minus;
	else
		$upto=count($type_arr)-$minus;
	$last=$upto-1;
	$random = rand(1,10);
	for($i=0;$i<$upto;$i++)
	{
		if($type_arr[$i]=="")
			break;
		$sep_arr=explode(":",$type_arr[$i]);
		$type=$sep_arr[0];
		$type_text=$sep_arr[1];
		
		$sep_arr=explode("/",$type_text);
		$link=$sep_arr[0];
		$text=$sep_arr[1];
		$posted_arr=explode("@",$link);
		$j=0;
		for($j=0;$j<count($posted_arr);$j++)
		{
			$key_val=explode("__",$posted_arr[$j]);
			if($j==0)
				$params="?$key_val[0]=$key_val[1]";
			else
				$params.="&$key_val[0]=$key_val[1]";
		}
		
		if($i==$last)
		{
			//$navigation.=" <span class=b>&gt; $text</span> ";
		}
		elseif($i==0)
		{
			if(!$isMobile)
				$navigation="<a  class=\"blink \" href=\"$TYPE[$type]$params&overwrite=1\">Back to $text</a>";
			else
				$navigation="<a href=\"$TYPE[$type]$params&overwrite=1\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}
		elseif(!$isMobile)
		{
			if($i==1)
			{
				$navigation.=" | <a href=\"$TYPE[$type]$params\" class=\"blink \">Back to $text</a>";
			}
			else
				$navigation.=" | <a href=\"$TYPE[$type]$params\"  class=\"blink \">Back to $text</a>";
		}
		
		if($isMobile  && $_GET['fmConfirm'] && $i==1 && !MobileCommon::isNewMobileSite())
		{
			$navigation="<a href=\"$TYPE[$type]$params&fmBack=1\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}
        //JSMS ECP View 
		if ($isMobile                               && 
            $type =='JVS'                           && 
            MobileCommon::isNewMobileSite()         && 
            stripos($type_arr[2],'DP')!==false //Current Page is Detailed Page and Previous Page was ECP/DP 
        ) {
			$navigation="<a href=\"$TYPE[$type]$params\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}

        //JSMS ECP View 
        if ($isMobile                               && 
            $type =='DP'                            && 
            MobileCommon::isNewMobileSite()         && 
            stripos($type_arr[2],'JVS')!==false//Current Page is ECP and previous page was DP
        ) {
			$navigation="<a href=\"$TYPE[$type]$params\"  class=\"pull-right btn pre-next-btn \">Go back</a>";
		}    
        
		if($i!=$last)
		{
			$smarty->assign("BACK_TO_SEARCH","<a href=\"$TYPE[$type]$params\" class=\"blink rf\">&lt;&lt; Go back to $text</a>");
		}
		
	}
	if($i>1)
	{
		if(strstr($_SERVER['PHP_SELF'],'view_similar_profile.php'))
			$smarty->assign("BREADCRUMB","<span class='b blink'> &lt;&lt; $navigation</span>");
		else
	    {
			if(!$isMobile)
				$navigation="<span class='b blink'>&lt;&lt; $navigation</span>";
				
			if(strstr($_SERVER['PHP_SELF'],'symfony_index.php'))
				sfContext::getInstance()->getRequest()->setAttribute("BREADCRUMB",$navigation);
			$smarty->assign("BREADCRUMB",$navigation);
		}

	}
	
	return $navigation;
}

if (!function_exists('createChecksumForSearch'))
{
	function createChecksumForSearch($profileid)
	{
		$checksum='';
		if($profileid)
		{
			$start_tag="start";
			$end_tag="end";
			$checksum=md5($profileid)."i".$profileid;
			//$checksum=md5($start_tag.$profileid.$end_tag).$profileid;
			
		}
		return $checksum;
	}
}
function getProfileidFromChecksum($checksum)
{
	if($checksum)
	{
		/*$start_tag="start";
                $end_tag="end";
		$profileid=substr($checksum,32,strlen($checksum));
		$temp_check=substr($checksum,0,32);
		$real_check=md5($start_tag.$profileid.$end_tag);
		if($temp_check==$real_check)
			return $profileid;
		*/
		$profileid=substr($checksum,33,strlen($checksum));
                $temp_check=substr($checksum,0,32);
                $real_check=md5($profileid);
                if($temp_check==$real_check)
                        return $profileid;

	}
	return 0;
}
function is_spam($profileid,$action='')
{
	if($profileid)
	{
		$key=$profileid."SPAMMER";
		$spam=memcache_call($key);
		if($spam)
		{
			if($spam==1)
				return true;
		}
		if(!$spam)
		{
			include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
	                $NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST();
			if($action=='sendContact')
				$spamParamaters['FLAG_INBOX_EOI']=1;
			elseif($action=='viewContactDetails')
				$spamParamaters['FLAG_CONTACT_DETAIL']=1;
        	        if($NEGATIVE_TREATMENT_LIST->isNegativeTreatmentRequired($profileid,$spamParamaters))
	                {
				//is spammer
				memcache_call($key,1,3600);
				return true;
	                }
			//not a spammer.
			memcache_call($key,2,3600);
		}
	
	}
	return false;
}
function get_operator_email($profileid)
{
	if($profileid)
	{
		 $sql_get="select OPERATOR from jsadmin.OFFLINE_ASSIGNED where PROFILEID='$profileid'";
		$res_get=mysql_query_decide($sql_get)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_get,"ShowErrTemplate");;

		if($row_get=mysql_fetch_array($res_get))
		{
			$sql_email="select EMAIL from jsadmin.PSWRDS where USERNAME='$row_get[OPERATOR]'";
			$res_email=mysql_query_decide($sql_email)  or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_email,"ShowErrTemplate");;

			if($row_email=mysql_fetch_array($res_email))
				$op_email=$row_email[0];
			else
				$op_email='no email';
		}
		else
			$op_email='';
	}
	
	return $op_email;
	
}
function getNudgeDetails($type,$PROFILENAME)
{
		global $index,$invoke_layer;
		if($invoke_layer==1)
		{
			$NUDGE['N']=array(
                                                "HEADLINE"=>"Contact Match Point member",
                                                "MESSAGE"=>"Jeevansathi has found a potential match for you in $PROFILENAME. Your desired partner profiles matched each other's profile and hence we are recommending this profile to you. In case you find the profile to your liking, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer. Otherwise, please click on \"Not Interested\"",
                                                "BUTTON"=>' <input type="button" title="Click here to accept this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px " onclick="javascript:submit_form(\'A_NUDGE\','.$index.')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" title="Click here to reject this profile" name="D_NUDGE" value="Not Interested" class="green_btn" style="width:110px "  onclick="javascript:submit_form(\'D_NUDGE\','.$index.')">');

                        $NUDGE['NACC']=array(
                                                "HEADLINE" => "Contact Match Point member",
                                                "MESSAGE" => "You have contacted this member earlier. Send a reminder.",
                                                "BUTTON" => ' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="green_btn" style="width:110px ">');

                         $NUDGE['SL']=array(
                                                "HEADLINE" => "Contact Match Point member",
                                        "MESSAGE" => "You have contacted this member earlier. ",
                                        "BUTTON" => '');

                        $NUDGE['NREJ']=array(
                                                "HEADLINE" => "You have declined further communication with this member",
                                                "MESSAGE"=>" If for any reason you wish to contact the member again, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer.",
                                                "BUTTON" => '<input type="button"  onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">');
			$NUDGE['NI']=array( "HEADLINE"=>"Contact Match Point member",
                                            "MESSAGE" =>" Express your Interest in this profile and an email message will be sent to this member about your interest. You can't send personalised message to Match Point member",
                                            "BUTTON" =>'<input type="button"   onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">'     );
                        $NUDGE['NIA']=array("HEADLINE" => "Contact Match Point member",
                                                "MESSAGE"=>"You have contacted this member earlier. Send a reminder.",
                                                "BUTTON"=>' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="green_btn" style="width:110px ">');
                        $NUDGE['NNOW']=array(
                                                "HEADLINE"=>"Contact Match Point member",
                                                "MESSAGE"=>"Jeevansathi has found a potential match for you in $PROFILENAME. Your desired partner profiles matched each other's profile and hence we are recommending this profile to you. In case you find the profile to your liking, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer. Otherwise, please click on \"Not Interested\"",
                                                "BUTTON"=>' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Click here to accept this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="javascript:submit_form(\'D_NUDGE\','.$index.')" title="Click here to reject this profile" name="D_NUDGE" value="Not Interested" class="green_btn" style="width:110px">');

                        $NUDGE['ACC']=array(
                                                "HEADLINE" => "Jeevansathi Member has Accepted you",
                                                "MESSAGE" => "Messages can't be written to Match Point profiles.",
                                                //"BUTTON" => ' <input type="submit" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="buttonor" style="width:180px ">'
                                        );
                        $NUDGE['REJ']=array("HEADLINE" =>"A Jeevansathi Member has declined any further communication");

                        $NUDGE['NNREJ']=array(
                                                "HEADLINE" => "You have declined further communication with this member",
                                                "MESSAGE"=>" If for any reason you wish to contact the member again, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer.",
                                                "BUTTON" => '<input type="button"  onclick="javascript:submit_form(\'A_NUDGE\','.$index.')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">');
		}
		else
		{
			$NUDGE['N']=array(
						"HEADLINE"=>"Contact Match Point member",
						"MESSAGE"=>"Jeevansathi has found a potential match for you in $PROFILENAME. Your desired partner profiles matched each other's profile and hence we are recommending this profile to you. In case you find the profile to your liking, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer. Otherwise, please click on \"Not Interested\"",
						"BUTTON"=>' <input type="button" title="Click here to accept this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px " onclick="javascript:submit_form(\'A_NUDGE\')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" title="Click here to reject this profile" name="D_NUDGE" value="Not Interested" class="green_btn" style="width:110px "  onclick="javascript:submit_form(\'D_NUDGE\')">');

			$NUDGE['NACC']=array(
						"HEADLINE" => "Contact Match Point member",
						"MESSAGE" => "You have contacted this member earlier. Send a reminder.",
						"BUTTON" => ' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\')" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="green_btn" style="width:110px ">');
			
			 $NUDGE['SL']=array(
						"HEADLINE" => "Contact Match Point member",
                                        "MESSAGE" => "You have contacted this member earlier. ",
                                        "BUTTON" => '');

			$NUDGE['NREJ']=array(   
						"HEADLINE" => "You have declined further communication with this member",
						"MESSAGE"=>" If for any reason you wish to contact the member again, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer.",
						"BUTTON" => '<input type="button"  onclick="javascript:submit_form(\'A_NUDGE\')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">');

			$NUDGE['NI']=array( "HEADLINE"=>"Contact Match Point member",
					    "MESSAGE" =>" Express your Interest in this profile and an email message will be sent to this member about your interest. You can't send personalised message to Match Point member",
					    "BUTTON" =>'<input type="button"   onclick="javascript:submit_form(\'A_NUDGE\')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">'     );
			$NUDGE['NIA']=array("HEADLINE" => "Contact Match Point member",
						"MESSAGE"=>"You have contacted this member earlier. Send a reminder.",
						"BUTTON"=>' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\')" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="green_btn" style="width:110px ">');
			$NUDGE['NNOW']=array(
						"HEADLINE"=>"Contact Match Point member",
						"MESSAGE"=>"Jeevansathi has found a potential match for you in $PROFILENAME. Your desired partner profiles matched each other's profile and hence we are recommending this profile to you. In case you find the profile to your liking, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer. Otherwise, please click on \"Not Interested\"",
						"BUTTON"=>' <input type="button"  onclick="javascript:submit_form(\'A_NUDGE\')" title="Click here to accept this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="javascript:submit_form(\'D_NUDGE\')" title="Click here to reject this profile" name="D_NUDGE" value="Not Interested" class="green_btn" style="width:110px">');
			
			$NUDGE['ACC']=array(
						"HEADLINE" => "Jeevansathi Member has Accepted you",
						"MESSAGE" => "Messages can't be written to Match Point profiles.",
						//"BUTTON" => ' <input type="submit" title="Send Reminder" name="A_NUDGE" value="Send Reminder" class="buttonor" style="width:180px ">'
					);
			$NUDGE['REJ']=array("HEADLINE" =>"A Jeevansathi Member has declined any further communication");
						
			$NUDGE['NNREJ']=array(
						"HEADLINE" => "You have declined further communication with this member",
						"MESSAGE"=>" If for any reason you wish to contact the member again, please respond by clicking the \"Express Interest\" button below. We'd convey your interest to our Match Point customer.",
						"BUTTON" => '<input type="button"  onclick="javascript:submit_form(\'A_NUDGE\')" title="Click here to Express Interest in this profile" name="A_NUDGE" value="Express Interest" class="green_btn" style="width:110px ">');
		}

                return $NUDGE[$type];
}
function setNudgeDetails($type,$PROFILENAME)
{
	global $smarty;
	$data=getNudgeDetails($type,$PROFILENAME);
	/*$smarty->assign("N_HEADLINE",$data['HEADLINE']);
	$smarty->assign("N_MESSAGE",$data['MESSAGE']);
	$smarty->assign("N_BUTTON",$data['BUTTON']);*/
	$smarty->assign("CONTACT_HEADLINE",$data['HEADLINE']);
        $smarty->assign("CONTACT_MESSAGE",$data['MESSAGE']);
        $smarty->assign("N_BUTTON",$data['BUTTON']);
}


function edu_occ()
{
        global $data;
        global $smarty;
	global $Gender;
	$Gend_stat=$Gender;
	if($Gend_stat=="")
		$Gend_stat=$data['GENDER'];
	if($data['GENDER'])
		$Gend_stat=$data['GENDER'];
	 $sql="select RELATION from newjs.JPROFILE where  activatedKey=1 and PROFILEID='".$data['PROFILEID']."'";
          $res=mysql_query_decide($sql);
        $row=mysql_fetch_row($res);
                                                                                                                             
        $gender=$Gend_stat;
        $relation=$row[0];
        if($relation!=1)
        {
                if($gender=="F")
                {
                                                                                                                             
                        $HE_SHE="she";
                        $HIS_HER="Her";
                	$his_her="her";
		}
                else
                {
                                                                                                                             
                        $HE_SHE="he";
                        $HIS_HER="His";
                	$his_her="his";
		}
                $First_Your=$HIS_HER;
		$Your=$his_her;
                $your="";
                $you=$HE_SHE;
                                                                                                                             
                                                                                                                             
        }
        else
        {
                $First_Your="Your";
		$Your='your';
                $you='you';
                $your='your';
        }
                                                                                                                             
        $smarty->assign("Your",$Your);
       $smarty->assign("your",$your);
        $smarty->assign("you",$you);
        $smarty->assign("First_Your",$First_Your);
	if($your!="")
        {
		 $smarty->assign("DO_YOU","Do you");
		$smarty->assign("Do","Do");
	}
	else
		$smarty->assign("Do","Does");
}

function first_time_registration_mail($id)
{
	global $smarty;
	 $sql="select EMAIL,USERNAME,PASSWORD,CITY_RES from JPROFILE where  activatedKey=1 and PROFILEID=$id";
         $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
        $myrow1=mysql_fetch_array($result);
        $my_city=$myrow1['CITY_RES'];
        $smarty->assign("EMAIL",$myrow1["EMAIL"]);
        $smarty->assign("USERNAME",$myrow1["USERNAME"]);
        $smarty->assign("PASSWORD",$myrow1["PASSWORD"]);
        //added by nikhil on June 01:upgradation of register mail //
                                                                          
                                                                                                                             
  	 //branch code
                                                                                                                             
	$sql="SELECT SQL_CACHE NEAR_BRANCH FROM incentive.BRANCH_CITY WHERE VALUE='$my_city'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        if(mysql_num_rows($result)>0)
        {
                $myrow=mysql_fetch_array($result);
                $sql="SELECT SQL_CACHE NAME,ADDRESS,CONTACT_PERSON,PHONE,ID FROM BRANCHES WHERE VALUE='$myrow[NEAR_BRANCH]'";
        }
        else
                $sql="SELECT SQL_CACHE NAME,ADDRESS,CONTACT_PERSON,PHONE,ID FROM BRANCHES WHERE VALUE='UP25'";
        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $myrow=mysql_fetch_array($result);
	$PROFILE_CHECKSUM=createChecksumForSearch($id);	
        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
        $forgotPasswordStr = ResetPasswordAuthentication::getResetLoginStr($id);
        $forgotPasswordUrl = JsConstants::$siteUrl."/common/resetPassword?".$forgotPasswordStr;
	$smarty->assign("forgotPasswordUrl",$forgotPasswordUrl);
        $smarty->assign("BRANCH_NAME",substr_replace(strtolower($myrow['NAME']),strtoupper(substr( strtolower($myrow['NAME']), 0, 1)), 0, 1));
	$smarty->assign("PROFILE_CHECKSUM",$PROFILE_CHECKSUM);
       $smarty->assign("BRANCH_PHONE",$myrow['PHONE']);
       $smarty->assign("BRANCH_ADDRESS",$myrow['ADDRESS']);
       $smarty->assign("MAILTO","mailto:anamika.singh@jeevansathi.com");
       mysql_free_result($result);
                                                               
      // branch code ends
      $msg=$smarty->fetch("registration_mailer.htm");

	 send_email($myrow1["EMAIL"],$msg,"Thank you for registering with jeevansathi.com ","register@jeevansathi.com","","","","","","Y");



}
function getsuffix($profileid)
{
	$value=intval($profileid/300000)+1;
	return $value;
}
function isvalid_foldername($string) 
{
	$invalidchar = array('#','\'','"','\\','/',' ','!','@','$','%','^','&','*','?','<','>','+','[',']','{','}','|',':',' ','~','`','_','-','(',')',',',';','=','.');
	for($i=0;$i<strlen($string);$i++)
	if(in_array($string[$i],$invalidchar))
	{
		$username_flag=1;
		return $username_flag;
	}
											 
        return 0;
}
if(!function_exists('logError'))
{
        function logError($message,$query="",$critical="exit", $sendmailto="NO",$server="",$database="")
        {
                global $smarty, $checksum,$sql,$ajax_error,$isMobile;

                //if(mysql_error_js() && $sendmailto!="YES")
                //      $sendmailto="vikas@jeevansathi.com";

                // Too many connections error
                if(mysql_errno_js()==1040)
                {
                        $sendmailto="NO";
                }

                ob_start();
                var_dump($_SERVER);
                $ret_val = ob_get_contents();
                ob_end_clean();
                if($database)
                         $errorstring = "\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js($database)) ."\nMysql Error Number:". mysql_errno_js($database)."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n$sql\n";
                else
                         $errorstring = "\n" . date("Y-m-d G:i:s",time() + 37800) . "\nErrorMsg: $message\nMysql Error: " . addslashes(mysql_error_js()) ."\nMysql Error Number:". mysql_errno_js()."\nSQL: $query\n#User Agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n #Referer : " . $_SERVER['HTTP_REFERER'] . " \n #Self :  ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n #Method : ".$_SERVER['REQUEST_METHOD']."\n$sql\n";

		error_log($errorstring,3,JsConstants::$docRoot . "/profile/logerror.txt");
		error_log($errorstring,3,JsConstants::$docRoot . "/profile/logerror_temp.txt");
		
		$errorstring.="\n#Details : $ret_val";
        LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($errorstring));
                if($sendmailto!="NO" && $sendmailto!="YES")
                        $b=mail($sendmailto,"ERROR in jeevansathi", $errorstring);

                if($_SERVER['ajax_error'] OR $ajax_error)
                {
			if($ajax_error==3 || $ajax_error==4)
			{
				$smarty->assign("ERROR_MESSAGE","An error has occured! We will be correcting this problem at the earliest. Kindly check back later.");
				if($ajax_error==3)
					$smarty->display("dp_express_interest_layer_fixed.htm");
				elseif($ajax_error==4)
					$smarty->display("congrats_contact.htm");
			}
			else
			{
				if($_SERVER['ajax_error']==2 OR $ajax_error==2)
				{
					echo 'A_E';
					exit;
				}
				$smarty->assign("ajax_error",1);
				$smarty->display("search_cluser_layer2.htm");
			}
                        die;
                        return ;
                }

                if($critical=="exit")
                {
                        echo $message;
                        exit;
                }
		elseif($critical=="ShowErrTemplate")
                {
                        if(strstr($_SERVER['PHP_SELF'],'symfony_index.php')){
								sfContext::getInstance()->getRequest()->setParameter("blockOldConnection500",1);
                              sfContext::getInstance()->getController()->forward("static","page500");
                              throw new sfStopException ( );
                        }
                        if($_SERVER['REQUEST_METHOD']=="POST")
                        {
                                $j=0;
                                foreach($_POST as $key => $value)
                                {
                                        if($value != "")
                                        {
                                                $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                                if(is_array($value))
                                                {
	                                                $data[$j]["VALUE"]="ARRAY";
                                                        $i=0;
                                        		foreach($value as $val)
        	                                        	if($val != "")
                                                                {
		                                                        $data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
                		                                }
                                                }
                                                else
                                                        $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
                                                $j++;
                                        }//if
                                }//foreach
				if(count($_GET)>0)
					$action_url=$_SERVER['REQUEST_URI'];
                        }
			else
                        {
                                $j=0;
                                foreach($_GET as $key => $value)
                                {
                                        if($value != "")
                                        {
                                                $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                                if(is_array($value))
                                                {
                        	                        $data[$j]["VALUE"]="ARRAY";
                                                        $i=0;
                                		        foreach($value as $val)
                                                	    if($val != "")
                                                                {
                                                        		$data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
                                                    		}
                                                }
                                                else
                                                        $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
                                                $j++;
                                        }//if
                                }//foreach
                        }

			
                        $smarty->assign("DATA",$data);
			if(!$action_url)
				$action_url=$_SERVER['PHP_SELF'];
				
                        $smarty->assign("ACTION",$action_url);
                        $smarty->assign("CHECKSUM",$checksum);
                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
			$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
                        //$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
                        //$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                        //$smarty->assign("msg_error", $message);
						if($isMobile)
							$smarty->display("mobilejs/jsmb_err_template.html");
						else
                        $smarty->display("error_template.htm");
			//$smarty->display("site_down.htm");
                        exit;
                }
                elseif($critical!="continue")
                {
                        echo $message;
                }

        }
}
function get_contact_id_inbox($sender_profileid,$receiver_profileid)
{
	//Sharding of CONTACTS done by Sadaf
        $sendersIn=$sender_profileid;
        $receiversIn=$receiver_profileid;
        $contactResult=getResultSet("CONTACTID",$sendersIn,'',$receiversIn);
        if(is_array($contactResult))
                return $contactResult[0]["CONTACTID"];
        else
        {
                unset($contactResult);
                $sendersIn=$receiver_profileid;
                $receiversIn=$sender_profileid;
                $contactResult=getResultSet("CONTACTID",$sendersIn,'',$receiversIn);
                if(is_array($contactResult))
                        return $contactResult[0]["CONTACTID"];
                else
                        return null;
        }
}

function getTimeDiff1($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= $timestamp_diff / (24*60*60);
                return $days_diff;
        }
        elseif($date2 == $date1)
                return 0;
        else
                return -1;
}

function leftpanel_membership($renew_status='')
{
        global $data,$smarty;
        $profileid=$data["PROFILEID"];
                                                                                                                             
        $subscription=$data["SUBSCRIPTION"];
                                                                                                                             
        if($data["SUBSCRIPTION"])
        {
                if(strstr($data['SUBSCRIPTION'],'F'))
                {
                        if(strstr($data['SUBSCRIPTION'],'D'))
                        {
                                $smarty->assign("my_membership","eValuePack Member");
                        }
                        else
                        {
                                $smarty->assign("my_membership","eRishta Member");
                        }
                }                 else
                        $smarty->assign("my_membership","eClassifieds Member");
                                                                                                                             
                if($renew_status=='')
                {
                        $sql="SELECT EXPIRY_DT from billing.SERVICE_STATUS where PROFILEID='$profileid' AND ACTIVATED <> 'N' order by ID desc";
                        $result=mysql_query_optimizer($sql) or logError($error_msg,$sql,"ShowErrTemplate",$announce_to_email);
                        if(mysql_num_rows($result)>0)
                        {
                                $myrow=mysql_fetch_array($result);
                        }
                        $renew_status=$myrow["EXPIRY_DT"];
                }
                                                                                                                             
                if($renew_status)
                {
                        $curdate=date('Y-m-d');
                        $days_left_expire= getTimeDiff1($curdate,$renew_status);
                        if($days_left_expire >= 0)
                        {
                                list($year,$month,$day)= explode("-",$renew_status);
                                $exp_dt=my_format_date($day,$month,$year,1);
                                $smarty->assign("expiry_dt",$exp_dt);
                                                                                                                             
                                                                                                                             
                                if($days_left_expire >10)
                                        $smarty->assign("mem_case",3);
                                else
                                {
                                        $smarty->assign("mem_case",1);
                                }
                        }
                        else
                                $smarty->assign("mem_case",2);
                }
                else
                {
                        $smarty->assign("mem_case",2);
                }
        }
        else
                $smarty->assign("mem_case",2);
	$smarty->assign("days_left_expire",$days_left_expire);
}
					
if(!function_exists('maStripVARS'))
{
	function maStripVARS($action)
	{ 
		global $_GET, $_POST;
		
		if (get_magic_quotes_gpc() == 0)
		{
			if($action=="stripslashes")
			{
				if (is_array($_GET))
				{ 
					while(list($k,$v) = each($_GET))
				{ 
					if(!is_array($v))
					{
						$_GET[$k] = strip_tags(str_replace("\"","'",$v));
						$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
					}
				}
				reset($_GET);
				}
				if (is_array($_POST))
				{
					while(list($k,$v) = each($_POST))
				{ 
					if(!is_array($v))
					{
						$_POST[$k] = strip_tags(str_replace("\"","'",$v));
						$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
					}
				}
				reset($_POST);
				}
				return;
			}
		}
		else
		{
			if($action=="addslashes")
			{
				if (is_array($_GET))
				{ 
					while(list($k,$v) = each($_GET))
				{ 
					if(!is_array($v))
					{
						$_GET[$k] = strip_tags(str_replace("\"","'",$v));
						$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
					}
				}
				reset($_GET);
				}
				if (is_array($_POST))
				{
					while(list($k,$v) = each($_POST))
				{ 
					if(!is_array($v))
					{
						$_POST[$k] = strip_tags(str_replace("\"","'",$v));
						$GLOBALS[$k] = strip_tags(str_replace("\"","'",$GLOBALS[$k]));
					}
				}
				reset($_POST);
				}
				return;
			}
		}
			
		if (is_array($_GET))
		{ 
			while(list($k,$v) = each($_GET))
		{ 
			if(!is_array($v))
			{
				if($action=="stripslashes")
				{
					$_GET[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
					$GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
				}
				
				if($action=="addslashes")
				{
					$_GET[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
					$GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
				}
			}
		}
		reset($_GET);
		}
		if (is_array($_POST))
		{
			while(list($k,$v) = each($_POST))
		{ 
			if(!is_array($v))
			{
				if($action=="stripslashes")
				{
					$_POST[$k] = strip_tags(str_replace("\"","'",stripslashes($v)));
					$GLOBALS[$k] = strip_tags(str_replace("\"","'",stripslashes($GLOBALS[$k])));
				}
				
				if($action=="addslashes")
				{
					$_POST[$k] = strip_tags(str_replace("\"","'",addslashes($v)));
					$GLOBALS[$k] = strip_tags(str_replace("\"","'",addslashes($GLOBALS[$k])));
				}
			}
		}
		reset($_POST);
		}
	}
}
if(!function_exists('my_format_date'))
{
function my_format_date($day,$month,$year,$mem_case='',$hour='',$min='')
{
	if($mem_case == 2)
        {
                if($month=="01" || $month=="1")
                        $month="jan";
                elseif($month=="02" || $month=="2")
                        $month="feb";
                elseif($month=="03" || $month=="3")
                        $month="mar";
                elseif($month=="04" || $month=="4")
                        $month="apr";
                elseif($month=="05" || $month=="5")
                        $month="may";
                elseif($month=="06" || $month=="6")
                        $month="jun";
                elseif($month=="07" || $month=="7")
                        $month="july";
                elseif($month=="08" || $month=="8")
                        $month="aug";
                elseif($month=="09" || $month=="9")
                        $month="sep";
                elseif($month=="10")
                        $month="oct";
                elseif($month=="11")
                        $month="nov";
                else
                $month="dec";
        }
	elseif($mem_case==4)
	{
		if($month=="01" || $month=="1")
                        $month="January";
                elseif($month=="02" || $month=="2")
                        $month="February";
                elseif($month=="03" || $month=="3")
                        $month="March";
                elseif($month=="04" || $month=="4")
                        $month="April";
                elseif($month=="05" || $month=="5")
                        $month="May";
                elseif($month=="06" || $month=="6")
                        $month="June";
                elseif($month=="07" || $month=="7")
                        $month="July";
                elseif($month=="08" || $month=="8")
                        $month="August";
                elseif($month=="09" || $month=="9")
                        $month="September";
                elseif($month=="10")
                        $month="October";
                elseif($month=="11")
                        $month="November";
                else
                        $month="December";
	}
	else
        {
                if($month=="01" || $month=="1")
                        $month="Jan";
                elseif($month=="02" || $month=="2")
                        $month="Feb";
                elseif($month=="03" || $month=="3")
                        $month="Mar";
                elseif($month=="04" || $month=="4")
                        $month="Apr";
                elseif($month=="05" || $month=="5")
                        $month="May";
                elseif($month=="06" || $month=="6")
                        $month="Jun";
                elseif($month=="07" || $month=="7")
                        $month="Jul";
                elseif($month=="08" || $month=="8")
                        $month="Aug";
                elseif($month=="09" || $month=="9")
                        $month="Sep";
                elseif($month=="10")
                        $month="Oct";
                elseif($month=="11")
                        $month="Nov";
                else
                        $month="Dec";
        }
        if(strlen($day)==1)
                $day= "0" . $day;
	if($hour!='')
        {
                if($hour>12)
                {
                        $hour=$hour-12;
                        if($hour<10)
                                $hour="0".$hour;
                        $clock='PM';

                }
                elseif($hour==12)
                        $clock='PM';
                elseif($hour==0)
                {
                        $hour=12;
                        $clock='AM';
                }
                else
                        $clock='AM';
                return $day." ".$month." ".$year." ".$hour.".".$min." ". $clock;
        }
        //added by lavesh
        if($mem_case==1)
        {
                $suffix = get_suffix($day);
                return $day.$suffix." ".$month.", ".$year;
        }
        elseif($mem_case==2)
        {
                $yr = substr($year,2,3);
                return $day.$month."'".$yr;
        }
	elseif($mem_case==3)
        {
                return $month;
        }
	elseif($mem_case==4)
	{
		$suffix = get_suffix($day);
                return $day.$suffix." ".$month.", ".$year;
	}
        //ends here.

        return $month . " " . $day . ", " . $year;
}
}
function getid_from_checksum($checksum='')
{
	if(substr($checksum,0,32)!=md5(substr($checksum,33,strlen($checksum))))
		return 0;
        else
	{
		$id=substr($checksum,33,strlen($checksum));
		return $id;
	}
}
function get_suffix($day)
{
        $l=0;
        $l = strlen($day);
        if($l==1)
        {
                if($day == '1')
                        $suffix="st";
                elseif($day == '2')
                        $suffix="nd";
                elseif($day == '3')
                        $suffix="rd";
                else
                        $suffix="th";
        }
        else
        {
                $last_digit = substr($day,1,2);
                if(($day == '11')||($day == '12')||($day == '13'))
                        $suffix="th";
                elseif($last_digit == '1')
                        $suffix="st";
                elseif($last_digit == '2')
                        $suffix="nd";
                elseif($last_digit == '3')
                        $suffix="rd";
                else
                        $suffix="th";
        }
        return $suffix;
}
function check_livestatus()
{
	global $smarty;

	if($smarty)
        	$smarty->assign("STATUS","ON");
	return;

	//check for live chat status
	$sql="SELECT SQL_CACHE STATUS from newjs.LIVECHAT";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	$myrow1=mysql_fetch_array($result);
	if($smarty)
	$smarty->assign("STATUS",$myrow1["STATUS"]);

}

function get_rights($profileid)
{
	global $data;

        if(!$profileid)
                return array();

	if($data['PROFILEID']==$profileid)
	{
		if($data['SUBSCRIPTION']=="")
			return array();
		else
		{
			$rights=explode(",",$data['SUBSCRIPTION']);
			return $rights;
		}
	}

	$sql="select SUBSCRIPTION from JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		$rights=explode(",",$myrow["SUBSCRIPTION"]);
		
		return $rights;
	}
	else 
		return array();
}

function customisedusername($profileid)
{
	global $smarty,$domain;
	
	if($_COOKIE["CUST_USER"]=="N")
	{
		$show1=0;
		$new4username=0;
		$smarty->assign('show4newusername',$show1);
		
		return $new4username;
	}
	
	$sql="SELECT PROFILEID FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid' AND DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= ENTRY_DT";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if(mysql_num_rows($result) < 1)
	{
		$show1=0;
		$new4username=0;
		$smarty->assign('show4newusername',$show1);//dont show link4newusername
		
		if(!headers_sent())
			setcookie("CUST_USER","N",0,"/",$domain);
	}
	else
	{
		$sql="SELECT PROFILEID FROM CUSTOMISED_USERNAME WHERE PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) > 0)
		{
			$show2=0;//dont show link4newusername	
			$new4username=0;
			
			if(!headers_sent())
				setcookie("CUST_USER","N",0,"/",$domain);
		}
		else
		{
			//Sharding of CONTACTS done by Sadaf
                        $sendersIn=$profileid;
                        $contactResult=getResultSet("COUNT(*) AS CNT",$sendersIn);
         
                        if($contactResult[0]["CNT"]>0)
                        {
                                $show2=0;//dont show link4newusername
                                $new4username=0;                                
                                if(!headers_sent())
                                        setcookie("CUST_USER","N",0,"/",$domain);
                        }
                        else
                        {
                                $show2=1;//show link4newusername        
                                $new4username=1;
                        }
                        unset($contactResult);
		}
		$smarty->assign('show4newusername',$show2);
	}
	return $new4username;
}

function right_panel($subscription)
{
	global $smarty;
	                                                                                                             
	if(strstr($subscription,"F"))
		$smarty->assign("HAS_ERISHTA","Y");
		
	if(strstr($subscription,"D"))
		$smarty->assign("HAS_ECLASSIFIED","Y");
}


function bounced_emailID($profileid,$email="",$verify_email="")//added by lavesh for optimization for jprofile
{
	$email_verify = 0;

/*
	if(!$email && !$verify_email)
        {
		$sql = "SELECT EMAIL , VERIFY_EMAIL FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID = '$profileid'";
                $res = mysql_query_decide($sql) or logError("Errorr in retrieving email Id",$sql , "continue","YES");
                $myrow = mysql_fetch_array($res);
                $email = $myrow["EMAIL"];
                $verify_email=$myrow["VERIFY_EMAIL"];
	}
*/                                                                                                                            
        $sql = "SELECT COUNT(EMAIL) AS CNT , EMAIL FROM bounces.BOUNCED_MAILS WHERE EMAIL = '$email' GROUP BY EMAIL";
        if($res = mysql_query_decide($sql))
        {
                $row = mysql_fetch_array($res);

                if ($row["CNT"] >= 3 )
                        $email_verify = 1;
/*                                                                                                                            
                if (($row["CNT"] >= 3 ) && $verify_email == 'Y')
                {
                        $email_verify = 1;
                }
                elseif ($row["CNT"] >= 3 )
                {
                        $email_verify = 2;
                }
                elseif ($verify_email == 'Y')
                {
                        $email_verify = 3;
                }
*/
        }
	
        return $email_verify;
}

function get_other_relevant_pro($contactedby,$contacted,$from="",$scriptname,$flag_first6_simprofile="")
{
	global $smarty,$_SERVER;
	include_once("search.inc");	
	//added by lavesh for new simillar profile logic
        global $remove_viewed_profile;
        $remove_viewed_profile=1;
        if($_SERVER['LIMIT10']==1)
                $mylimit=10;
        else
                $mylimit=9;

        if($contactedby)
        {
                if($from=="single_contact_aj")
                {
			if($scriptname=='AjaxContact.php')
				$profileid_receiver=getProfileidFromChecksum($contacted);//added by lavesh bec. of nikhil new checksum-logic
			else
			{
	                        $shvar=$contacted;
        	                list($hashval_receiver,$profileid_receiver)=explode("i",$contacted);
			}
                	$smarty->assign("contact",$profileid_receiver);
                        if(!$flag_first6_simprofile)
                                $j=1;

                        //if(select_random_simlogics($j,$contactedby,$profileid_receiver))
                        {
                                $new_logic_flag=1;
                                include_once("simprofile_search_new.php");
				//mysql_close($db);
				//mysql_close($db1);
				$db=connect_db();

                        }
                        //else
                                //$new_logic_flag=0;
                }
        }
	//Ends Here
	unset($senders);
	if(!$new_logic_flag)
	{
		if($from=="single_contact_aj")
		{
			if($scriptname=='AjaxContact.php')
				$profileid_receiver=getProfileidFromChecksum($contacted);//added by lavesh bec. of nikhil new checksum-logic
			else
			{
				$shvar=$contacted;
				list($hashval_receiver,$profileid_receiver)=explode("i",$contacted);	
				$smarty->assign("contact",$profileid_receiver);
			}
		}
		else if($from=="searchaction")
		{
			if(count($contacted)>1)
			{
				$profileid_receiver=implode("','",$contacted);
			}
			else
			{
				$profileid_receiver=$contacted[0];
			}
			$smarty->assign("contact",$contacted[0]);
		}

		if($from=="single_contact_aj")
		{
			//Sharding of CONTACTS done by Sadaf
                        $receiversIn=$profileid_receiver;
                        $sendersNotIn=$contactedby;
                        $contactResult=getResultSet("SENDER",'',$sendersNotIn,$receiversIn);
                        if(is_array($contactResult))
                        {
                                foreach($contactResult as $key=>$value)
                                        $senders[]=$contactResult[$key]["SENDER"];
                        }
                        unset($contactResult);
		}
		elseif($from=="searchaction")
		{
			// this query is being run on CONTACTS_SEARCH as it can result in thousands or even lacs of records if run on CONTACTS
			//mysql_close($db);
			$sql="SELECT SENDER FROM newjs.CONTACTS_SEARCH WHERE RECEIVER IN ('".$profileid_receiver."') AND SENDER <> '".$contactedby."'";
			$db2=connect_db4();
			$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$sql,"ShowErrTemplate"); 
			//mysql_close($db2);
			$db=connect_db();
			
			while($row=mysql_fetch_array($res))
			{
				$senders[]="'".$row['SENDER']."'";
			}
		}
		if(count($senders)>1)
		{
			$sender_list=implode(",",$senders);
		}
		else if(count($senders)==1)
		{
			$sender_list=$senders[0];
		}
		else
		{
			$sender_list="''";
			if($from=="searchaction")
			{
				//CONTACTS_SEARCH2 has the receivers which have more than 100 contacts in case of males and more than 200 contacts in case of females which CONTACTS_SEARCH doesnot have
				//mysql_close($db);
				$sql="SELECT SENDER FROM newjs.CONTACTS_SEARCH2 WHERE RECEIVER IN ('".$profileid_receiver."') AND SENDER <> '".$contactedby."'";
				$db2=connect_db4();
				$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH2",$sql,"ShowErrTemplate"); 
				//mysql_close($db2);
				$db=connect_db();
				
				while($row=mysql_fetch_array($res))
				{
					$senders[]="'".$row['SENDER']."'";
				}
				if(count($senders)>1)
				{
					$sender_list=implode(",",$senders);
				}
				else if(count($senders)==1)
				{
					$sender_list=$senders[0];
				}
				else
				{
					$sender_list="''";
				}
			}	
		}

		unset($senders);

		//Sharding of CONTACTS done by Sadaf
                $sql="SELECT VIEWED_PROFILE FROM newjs.SIM_PROFILE_LOG WHERE PROFILEID='$contactedby' union all select IGNORED_PROFILEID from IGNORE_PROFILE WHERE PROFILEID='$contactedby' AND UPDATED='Y' union all select PROFILEID from IGNORE_PROFILE WHERE IGNORED_PROFILEID='$contactedby' AND UPDATED='Y'";

                $res=mysql_query_decide($sql) or logError("Error while retrieving data from newjs.CONTACTS",$sql,"ShowErrTemplate");

                while($row=mysql_fetch_row($res))                
		{
                        $previous_rec.=$row[0] . ",";
                }
		$contactedby=1;
                $sendersIn=$contactedby;
                $contactResult=getResultSet("RECEIVER",$sendersIn);
                if(is_array($contactResult))
                {
                        foreach($contactResult as $key=>$value)
                                $previous_rec.=$contactResult[$key]["RECEIVER"].",";
                }
                unset($contactResult);

                $receiversIn=$contactedby;
                $contactResult=getResultSet("SENDER",'','',$receiversIn);
                if(is_array($contactResult))
                {
                        foreach($contactResult as $key=>$value)
                                $previous_rec.=$contactResult[$key]["SENDER"].",";
                }
                unset($contactResult);


		//added by sriram, to prevent the contacted member to be shown again.
		$previous_rec .= "'".$profileid_receiver."'";
		
		$previous_rec=rtrim($previous_rec,',');
		//end of section added by Gaurav on 18 Sep 2006 for ignore user

		if($sender_list!="''")
		{
			//mysql_close($db); 
			$db2=connect_db4();
			
			$sql="SELECT RECEIVER,COUNT(*) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN (".$sender_list.")";
			if(trim($previous_rec)!="")
				$sql.=" AND RECEIVER NOT IN (".$previous_rec.")";
				
			$sql.=" GROUP BY RECEIVER ORDER BY CNT DESC LIMIT $mylimit";
			
			$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$sql,"ShowErrTemplate");
			//mysql_close($db2);
			$db=connect_db();
			
			/*
			if(!function_exists(displayresults))
			{
				include("search.inc");
			}
			*/

			if(mysql_num_rows($res)==0)
			{
				//mysql_close($db); 
				$db2=connect_db4();
				
				$sql="SELECT RECEIVER,COUNT(*) AS CNT FROM newjs.CONTACTS_SEARCH2 WHERE SENDER IN (".$sender_list.")";
				if(trim($previous_rec)!="")
					$sql.=" AND RECEIVER NOT IN (".$previous_rec.")";
					
				$sql.=" GROUP BY RECEIVER ORDER BY CNT DESC LIMIT $mylimit";
				
				$res=mysql_query_decide($sql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH2",$sql,"ShowErrTemplate");
				//mysql_close($db2);
				$db=connect_db();
			}
			if(mysql_num_rows($res)>0)
			{
				$smarty->assign("STYPE","CO");
                                //if($flag_first6_simprofile)
                                        //update_random_simlogics("OLD_CNT",$contactedby,$profileid_receiver,'OLD');
                                updateSimTempLog($res,$db1,$contactedby);
                                /*
                                while($myviews=mysql_fetch_array($res))
                                {
                                        $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[RECEIVER]',now())";
                                        mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
                                }
                                */

				mysql_data_seek($res,0);
				//displayresults($res,"",$scriptname,"","","1","","","","");
				if($mylimit==9)
					set_results($res,"single_contact",9);
				else
					new_displayresults($res,$start_from,10,10,"view_similar_profile.php");
				//set_results($res,"single_contact",9);
			}
			else if($from=="single_contact_aj" || ($from=="searchaction" && count($contacted)==1))
			{	
				if($from=="single_contact_aj")
				{
					$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid_receiver."'";
				}
				else if($from=="searchaction" && count($contacted)==1)
				{
					$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$contacted[0]."'";
				}

				$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
				$row=mysql_fetch_array($res);
				$age=$row['AGE'];
				$caste=$row['CASTE'];
				$gender=$row['GENDER'];
				$mtongue=$row['MTONGUE'];
																	    
				$lage=$age-2;
				$hage=$age+2;

				if(!is_array($caste) && $caste!="" && $caste!="All")
				{
					$tempcaste=$caste;
					unset($caste);
					$caste[0]=$tempcaste;
				}

				if(is_array($caste) && !in_array("All",$caste))
				{
					$seCaste=get_all_caste($caste);
					if(is_array($seCaste))
						$searchCaste="'" . implode($seCaste,"','") . "'";
				}
	

				$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM ";

				if($gender=='M')
					$sql.=" newjs.SEARCH_MALE ";
				else if($gender=='F')
					$sql.=" newjs.SEARCH_FEMALE ";

				$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
				if(trim($previous_rec)!="")
					$sql.=" AND PROFILEID NOT IN (".$previous_rec.")";
				$sql.=" LIMIT $mylimit";
				$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
						
				/*											    
				if(!function_exists(displayresults))
				{
					include("search.inc");
				}
				*/
													    
				if(mysql_num_rows($res)>0)
				{
                                        updateSimTempLog($res,$db1,$contactedby);
                                        /*
                                        while($myviews=mysql_fetch_array($res))
                                        {
                                                $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
                                                mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
                                        }
                                        */
					mysql_data_seek($res,0);
					//displayresults($res,"",$scriptname,"","","1","","","","");
					if($mylimit==9)
						set_results($res,"single_contact",9);
					else
						new_displayresults($res,$start_from,10,10,"view_similar_profile.php");
					//set_results($res,"single_contact",9);
				}
				else 
				{
					log_no_similar_profiles($contactedby,$profileid_receiver);
				}
			}
		}
		else if($from=="single_contact_aj" || ($from=="searchaction" && count($contacted)==1))
		{
			if($from=="single_contact_aj")
			{
				$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$profileid_receiver."'";
			}
			else if($from=="searchaction" && count($contacted)==1)
			{
				$sql="SELECT AGE,CASTE,GENDER,MTONGUE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='".$contacted[0]."'";
			}

			$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			$age=$row['AGE'];
			$caste=$row['CASTE'];
			$gender=$row['GENDER'];
			$mtongue=$row['MTONGUE'];
																    
			$lage=$age;
			$hage=$age;

			if(!$age)
			{
				mail("shiv.narayan@jeevansathi.com","no data","query : $sql\nfrom : $from\nsqlshiv : $sqlshiv\nscript : $scriptname\ncontacted : $shvar");
			}

			if(!is_array($caste) && $caste!="" && $caste!="All")
			{
				$tempcaste=$caste;
				unset($caste);
				$caste[0]=$tempcaste;
			}

			if(is_array($caste) && !in_array("All",$caste))
			{
				$seCaste=get_all_caste($caste);
				if(is_array($seCaste))
					$searchCaste="'" . implode($seCaste,"','") . "'";
			}
																    
																    
			$sql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID FROM ";
																    
			if($gender=='M')
				$sql.=" newjs.SEARCH_MALE ";
			else// if($gender=='F')
				$sql.=" newjs.SEARCH_FEMALE ";
																    
			$sql.=" WHERE AGE BETWEEN '".$lage."' AND '".$hage."' AND CASTE IN (".$searchCaste.") AND MTONGUE='$mtongue'";
			if(trim($previous_rec)!="")
				$sql.=" AND PROFILEID NOT IN (".$previous_rec.")";
			$sql.=" LIMIT $mylimit";

			$res=mysql_query_decide($sql) or logError("Error while retrieving data",$sql,"ShowErrTemplate");
																    
			if(!function_exists(displayresults))
			{
				include("search.inc");
			}
			
			if(mysql_num_rows($res)>0)
			{
                                updateSimTempLog($res,$db1,$contactedby);
                                /*
                                while($myviews=mysql_fetch_array($res))
                                {
                                        $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$myviews[PROFILEID]',now())";
                                        mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
                                }
                                */
				mysql_data_seek($res,0);
				//displayresults($res,"",$scriptname,"","","1","","","","");
				if($mylimit==9)
					set_results($res,"single_contact",9);
				else
					new_displayresults($res,$start_from,10,10,"view_similar_profile.php");
			}
			else 
				log_no_similar_profiles($contactedby,$profileid_receiver);
		}
		else 
			log_no_similar_profiles($contactedby,$profileid_receiver);
	}
}
/*if(!function_exists('getname'))
{
function getname($cid)
{
        $temp=explode("i",$cid);
        $userid=$temp[1];
        $sql="SELECT USER FROM jsadmin.CONNECT WHERE ID='$userid'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $id=$row['USER'];

        $sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE RESID='$id'";
        $res=mysql_query_decide($sql) or die(mysql_error_js());
        $row=mysql_fetch_array($res);
        $username=$row['USERNAME'];
        return $username;
}
}*/
function get_contact_id_inbox_count($sender_profileid,$receiver_profileid)
{
	//Sharding of CONTACTS done by Sadaf

        $sendersIn=$sender_profileid;
        $receiversIn=$receiver_profileid;
        $contactResult=getResultSet("COUNT(*) AS CNT",$sendersIn,'',$receiversIn);
        if($contactResult[0]["CNT"]==0)
        {
                unset($contactResult);

                $sendersIn=$receiver_profileid;
                $receiversIn=$sender_profileid;
                $contactResult=getResultSet("COUNT(*) AS CNT",$sendersIn,'',$receiversIn);
                if($contactResult[0]["CNT"]==0)
                {
                        unset($contactResult);
                        return false;
                }
                else
                {
                        unset($contactResult);
                        return true;
                }
        }
        else
        {
                unset($contactResult);
                return true;
        }
}

function create_code($tablename)
{
	if($tablename=="CITY_INDIA")
      	{
       		$tablename="CITY_NEW";
               	$attribute = "STD_CODE";
      	}
      	else
     	{
          	$attribute = "CODE";
    	}
        $sql = "select SQL_CACHE VALUE,".$attribute." from newjs.$tablename order by SORTBY";
        $res = mysql_query_decide($sql) or logError("error",$sql);
        $ret = "";
        while($myrow = mysql_fetch_array($res))
        {
                //$k[]=$myrow['CODE'];
                $k[]=$myrow['VALUE']."|".$myrow[$attribute];
        }
        $code=implode(",",$k);
        //the code is in the format:
        //MH04|24,UP24|0120,....
        //city code, followed by its std code
        return $code;
}

function link_track($script_name)
{
	return;
	
    $sql="SELECT * FROM MIS.LINK_TRACKER WHERE SCRIPT_NAME='".$script_name."' AND ENTRY_DATE=CURDATE()";
    $res=mysql_query_decide($sql);
                                                                                                                        
    if(mysql_num_rows($res)>0)
    {
        $sql="UPDATE MIS.LINK_TRACKER SET COUNT=COUNT+1 WHERE SCRIPT_NAME='".$script_name."' AND ENTRY_DATE=CURDATE()";
        mysqL_query($sql);
    }
    else if(mysql_num_rows($res)==0)
    {
        $sql="INSERT INTO MIS.LINK_TRACKER VALUES('',CURDATE(),'$script_name','1')";
        mysql_query_decide($sql);
    }
}
function vpin_gen()
{
	mt_srand((double) microtime() * 10000000);

	$random =(((1*rand(0,9))+(10*rand(0,9))+(100*rand(0,9))+(1000*rand(1,9))));
	return $random;
}

function infovision_auth($checksum="")
{
	global $smarty;
	$arr=explode("i",$checksum);

	if($arr[0]!=md5($arr[1]))
		return NULL;

	$sql="select USERNAME , PROFILEID , GENDER , ACTIVATED , RELIGION ,COUNTRY_BIRTH , SUBSCRIPTION from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$arr[1]'";
	$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	$myrow=mysql_fetch_array($result);
	
	//If record not found , then search in entire JPROFILE table.
	if(!$myrow)
	{
		$sql="select USERNAME , PROFILEID , GENDER , ACTIVATED , RELIGION ,COUNTRY_BIRTH , SUBSCRIPTION from newjs.JPROFILE where PROFILEID='$arr[1]' and activatedKey=0";
        	$result=@mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
	}
	$data["USERNAME"]=$myrow["USERNAME"];
	$data["GENDER"]=$myrow["GENDER"];
	$data["ACTIVATED"]=$myrow["ACTIVATED"];
	$data["RELIGION"]=$myrow["RELIGION"];
	$data["COUNTRY_BIRTH"]=$myrow["COUNTRY_BIRTH"];
	$data["SUBSCRIPTION"]=trim($myrow["SUBSCRIPTION"]);
	$data["PROFILEID"]=$arr[1];
	$smarty->assign("CURRENTUSERNAME",$myrow["USERNAME"]);
	return $data;
}

function getfilename($cm)
{
	$l=strlen($cm);
	for($i=$l;$i>0;$i--)
	{
		if($cm[$i]=="/")
		{
			$pos1=$i;
			break;
		}
	}
	$filename="";
	for($i=$pos1+1;$i<$l;$i++)
		$filename=$filename.$cm[$i];
	return $filename;
}

function redo_gothra($gothra)
{
	$sql="select FINAL from GOTHRA WHERE ORIGINAL='$gothra'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($myrow=mysql_fetch_array($result))
		$newgothra=$myrow['FINAL'];
	else
		$newgothra="";
	return $newgothra;
}
function loadnakshatra($isitkannad,$current_nak)
{
	$current_nak=addslashes(stripslashes($current_nak));
	if($isitkannad=="16")
		$FIELD="KANNAD";
	else
		$FIELD="OTHERS";
	$ret="";$ret1="";$flag=0;
	$sql="SELECT SQL_CACHE $FIELD from newjs.NAKSHATRA";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow = mysql_fetch_array($result))
	{
		if(addslashes(stripslashes($myrow[$FIELD]))==$current_nak)
		{
		$ret1.= "<option value=\"$myrow[$FIELD]\" selected>$myrow[$FIELD]</option>\n";
		$flag=1;
		}
		else
		$ret1.= "<option value=\"$myrow[$FIELD]\">$myrow[$FIELD]</option>\n";
	}
	/*	if($flag!=1)
		$ret .= "<option value=\"\" disabled></option>\n";
		else
			$ret.="<option value=\"\" disabled></option>\n";*/
	$ret.=$ret1;
	return $ret;
}

function log_no_similar_profiles($sender,$receiver)
{
	$receiver=addslashes($receiver);
	$sql="insert into NO_SIMILAR_PROFILES(SENDER,RECEIVER,DATE) values ('$sender','$receiver',now())";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

//Created By lavesh rawat on 30 aug 2006
//Check if subcaste is compulsory for profileid when registering or editing its profile.
function validate_combination($Caste,$Subcaste)
{
	//These Values are for Others as small_label in newjs.CASTE
	$compulsory_subcaste=array(242,243,244,245,246);

	if(in_array($Caste,$compulsory_subcaste))
		if(trim($Subcaste)=='')
			return(1);
	return(0);
}

//added By lavesh.
function get_username($pid)
{
	$sql="SELECT USERNAME FROM JPROFILE WHERE  PROFILEID='$pid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
	$row=mysql_fetch_array($result);
	return($row['USERNAME']);
}

//Added by lavesh.
//Curremtly using to optimize inbox 1) fn will be called only once 2)CONTACTS query will be saved when PHOTO_DISPALY='C'
function get_user_photo_details2($profileid_array2,$contact_status,$msg_type)
{
	global $data;
	//$profileid_array2=array('100105','136580','100163','2','6','742','62','100105','100982');
	$profileid_array=array_unique($profileid_array2);
	$profileid_array=implode("','",$profileid_array);
															     
	$sql="select PROFILEID,HAVEPHOTO,PRIVACY,PHOTO_DISPLAY from JPROFILE WHERE  activatedKey=1 and PROFILEID IN ('$profileid_array')";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	while($myrow=mysql_fetch_array($result))
	{
		$profileid=$myrow["PROFILEID"];
		unset($have_photo);
															     
		if($myrow["HAVEPHOTO"]=="U")
		{
			$have_photo="1";
			$havephoto='U';
		}
		elseif($myrow["HAVEPHOTO"]!="Y")
		{
			$have_photo="1";
			$havephoto='N';
		}
		else
			$havephoto='Y';
															     
		if($myrow["PHOTO_DISPLAY"]=="H")
		{
			$have_photo="1";
			//$havephoto='P';
			$havephoto='H';
		}
		elseif(!$data && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F" || $myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C"))
		{
			$have_photo="1";
			$havephoto='L';
		}
		elseif($myrow["PRIVACY"]=="F")
		{
			if(check_privacy_filtered1($data["PROFILEID"],$profileid))
			{
				$notfiltered=1;
				$have_photo="1";
				//$havephoto='P';
				$havephoto='F';
			}
		}
															     
		if($have_photo=='')
		{
			if($myrow["PHOTO_DISPLAY"]=="C")
			{
				$k=array_search($profileid,$profileid_array2);
				$my_contact=$contact_status[$k];
															     
				if($msg_type=='s')
				//sent items
				{
					if($my_contact=='I' || $my_contact=='D' || $my_contact=='RC')
						//$havephoto="P";
						$havephoto="C";
				}
				//inbox
				elseif($msg_type=='i')
				{
					if($my_contact=='RD' || $my_contact=='C' || $my_contact=='RI')
					{
						//$havephoto="P";
						$havephoto="C";
					}
				}
			}
			elseif($myrow["PHOTO_DISPLAY"]=="F")
			{
				if(!$notfiltered && check_privacy_filtered1($data["PROFILEID"],$profileid))
					$havephoto='P';
			}
		unset($contacted1);
		unset($contacted2);
		}
		$havephoto_str1[$profileid]=$havephoto;
	}
	//lines for sorting in order it was given.
	for($i=0;$i<count($profileid_array2);$i++)
	{
		$havephoto_str[]=$havephoto_str1[$profileid_array2[$i]];
	}
	//print_r($havephoto_str);
	return $havephoto_str;
}

function check_privacy_filtered1($myprofileid,$hisprofileid)
{
	//Sharding Concept added by Lavesh Rawat on table JPARTNER
	$sql="select * from FILTERS where PROFILEID='$hisprofileid'";
	$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
														     
	if(mysql_num_rows($resultfilter)>0)
	{
		$filterrow=mysql_fetch_array($resultfilter);

		if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y"||$filterrow["CASTE"]=="Y" || $filterrow["CITY_RES"]=="Y" || $filterrow["INCOME"]=="Y")
		{
			$sqlfilter="select count(*) from JPROFILE where  activatedKey=1 and PROFILEID='$myprofileid'";
			$sqlfilter_temp=$sqlfilter;
			include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
			$jpartnerObj= new Jpartner;
			$mysqlObj=new Mysql;

			$myDbName=getProfileDatabaseConnectionName($hisprofileid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName");
			$jpartnerObj->setPartnerDetails($hisprofileid,$myDb,$mysqlObj,"LAGE,HAGE,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_INCOME,PARTNER_CITYRES,PARTNER_RELIGION");
			if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$hisprofileid))
			{
				if($filterrow["AGE"]=="Y" && $jpartnerObj->getLAGE() && $jpartnerObj->getHAGE() )
					$sqlfilter.= " AND AGE between ".$jpartnerObj->getLAGE()." AND ".$jpartnerObj->getHAGE();

				if($filterrow["CASTE"]=="Y" && $jpartnerObj->getPARTNER_CASTE())
				{
					$caste_arr=explode("','",trim($jpartnerObj->getPARTNER_CASTE(),"'"));
					$sqlfilter.=" and CASTE in ('" . implode("','",get_all_caste($caste_arr)) . "')";
				}

				if($filterrow["COUNTRY_RES"]=="Y" && $jpartnerObj->getPARTNER_COUNTRYRES())
					$sqlfilter.=" AND COUNTRY_RES in (".$jpartnerObj->getPARTNER_COUNTRYRES().")";

				if($filterrow["MSTATUS"]=="Y" && $jpartnerObj->getPARTNER_MSTATUS())
					$sqlfilter.=" and MSTATUS in (".$jpartnerObj->getPARTNER_MSTATUS().")";

				if($filterrow["MTONGUE"]=="Y" && $jpartnerObj->getPARTNER_MTONGUE())
					$sqlfilter.=" and MTONGUE in (".$jpartnerObj->getPARTNER_MTONGUE().")";

				if($filterrow["CITY_RES"]=="Y" && $jpartnerObj->getPARTNER_CITYRES())
					$sqlfilter.=" and CITY_RES in (".$jpartnerObj->getPARTNER_CITYRES().")";

				if($filterrow["INCOME"]=="Y" && $jpartnerObj->getPARTNER_INCOME())
					$sqlfilter.=" and INCOME in (".$jpartnerObj->getPARTNER_INCOME().")";

				if($filterrow["RELIGION"]=="Y" && $jpartnerObj->getPARTNER_RELIGION())
					$sqlfilter.=" and RELIGION in (".$jpartnerObj->getPARTNER_RELIGION().")";
			}
			//If no filter set
			if($sqlfilter==$sqlfilter_temp)
				return true;

			$resfil=mysql_query_decide($sqlfilter) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlfilter,"ShowErrTemplate");
			$finalfilterrow=mysql_fetch_row($resfil);

			if($finalfilterrow[0] <= 0)
				return true;
		}
	}
	return false;
}

//added by lavesh to check photo status of a user.
function get_user_photo_details($profileid,$checksum="",$havephoto="",$privacy="",$photo_display="",$contact_status="")
{
	global $data,$sender_details;
	if(!$data)
		$data=$sender_details;
	if(!$data)
		$data=authenticated($checksum);
 
        if(!$havephoto || !$privacy || !$photo_display)
        {
		$sql="select HAVEPHOTO,PRIVACY,PHOTO_DISPLAY from JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
	}
	else
	{
                $myrow["HAVEPHOTO"]=$havephoto;
                $myrow["PRIVACY"]=$privacy;
                $myrow["PHOTO_DISPLAY"]=$photo_display;
	}	
	
	if($myrow["HAVEPHOTO"]=="U")
		return "U";
															     
	if($myrow["HAVEPHOTO"]!="Y")
		return "N";
															     
	if($myrow["PHOTO_DISPLAY"]=="H")
		//return "P";
		return "H";
															     
	if(!$data && ($myrow["PRIVACY"]=="R" || $myrow["PRIVACY"]=="F" || $myrow["PHOTO_DISPLAY"]=="F" || $myrow["PHOTO_DISPLAY"]=="C"))
		//return "P";
		return "L";
															     
	if($myrow["PRIVACY"]=="F")
	{
		if(check_privacy_filtered1($data["PROFILEID"],$profileid) && $contact_status=="RI")
		{
			$notfiltered=1;
			//return "P";
			return "F";
		}
	}
															     
	if($myrow["PHOTO_DISPLAY"]=="C")
	{
		if($_GET['type'])
        {
                if($_GET['type']=='R' || $_GET['type']=='RC')
                        $set_type='S';
                elseif($_GET['type']=='M')
                        $set_type='R';
        }
		if(strpos($_SERVER['PHP_SELF'],'contacts_made_received.php') === false || !isset($_GET['flag']))
			$allow_query=1;
		else
			$allow_query=0;

		if($allow_query)
                {
			//Sharding of CONTACTS done by Sadaf
                        $sendersIn=$data["PROFILEID"];
                        $receiversIn=$profileid;
                        $contactResult=getResultSet("TYPE",$sendersIn,'',$receiversIn);           
               		 
		        if(is_array($contactResult))
                        {
                                foreach($contactResult as $key=>$value)
                                {
                                        $contacted1[$profileid]=$contactResult[$key]["TYPE"];
                                        $contacted2[$profileid]="R";
                                }
                        }
		}
		else
                {
                        $contacted1[$profileid]=$_GET['flag'];
                        $contacted2[$profileid]=$set_type;
                }
		
		if($allow_query)	
			 if(!is_array($contactResult))
                        {
                                $sendersIn=$profileid;
                                $receiversIn=$data["PROFILEID"];
                                $contactResult=getResultSet("TYPE",$sendersIn,'',$receiversIn);

                                if(is_array($contactResult))
                                {
                                        foreach($contactResult as $key=>$value)
                                        {
                                                $contacted1[$profileid]=$contactResult[$key]["TYPE"];
                                                $contacted2[$profileid]="S";
                                        }
                                }
                        }

                if(is_array($contactResult))
                        unset($contactResult);
															     
	    if(is_array($contacted1) && array_key_exists($profileid,$contacted1) && (($contacted2[$profileid]=="S" && ($contacted1[$profileid]=="I" || $contacted1[$profileid]=="A" || $contacted1[$profileid]=="D")) || ($contacted2[$profileid]=="R" && ($contacted1[$profileid]=="A" || $contacted1[$profileid]=="C"))))
		    ;
	    else
	    {
		    //return "P";
		    return "C";
	    }
	}
	if($myrow["PHOTO_DISPLAY"]=="F")
	{
		//if(is_array($contacted1) && array_key_exists($profileid,$contacted1) && (($contacted2[$profileid]=="S" && ($contacted1[$profileid]=="I" || $contacted1[$profileid]=="A")) || ($contacted2[$profileid]=="R" && $contacted1[$profileid]=="A")))
		//        ;
		
		if(!$notfiltered && check_privacy_filtered1($data["PROFILEID"],$profileid))
			return "P";
	}
	
	return "Y";
}

function contacted_profiles($profileid,$include_awaiting_contacts='')
{
	//Sharding of CONTACTS done by Sadaf
	global $contactResultRec,$contactResultSen;

	include_once("$_SERVER[DOCUMENT_ROOT]/profile/contacts_functions.php");
	$receiversIn=$profileid;

	if($contactResultSen)
		$contactResult=$contactResultSen;
	else
	{
		if($include_awaiting_contacts == 1) //condition added for suggested profiles algo on detailed profile page
			$contactResult=getResultSet("SENDER",'','',$receiversIn,'','','\'I\''); //ignore profiles that have sent him an EOI which has been either accepted/declined
		else
	        	$contactResult=getResultSet("SENDER",'','',$receiversIn);
	}

        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $sender[]=$contactResult[$key]["SENDER"];
        }
        unset($contactResult);

        $sendersIn=$profileid;
	if($contactResultRec)
		$contactResult=$contactResultRec;
	else
	{
//		if($include_awaiting_contacts == 1)
//			$contactResult=getResultSet("RECEIVER",$sendersIn,'','','','','\'I\'');
//		else
			$contactResult=getResultSet("RECEIVER",$sendersIn);
	}
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $sender[]=$contactResult[$key]["RECEIVER"];
        }
        unset($contactResult);

	//Nudge contact not to be shown in search
	$sender=get_nudge_profiles($profileid,$sender);

	global $sphinx_205;
        if(is_array($sender))
                $sphinx_205=count($sender);
        else
                $sphinx_205=0;

        if(is_array($sender))
        {       $sen_arr=implode($sender,"+");
                return $sen_arr;
        }
}
function get_nudge_profiles($profileid,$sender='')
{
	if($profileid)
	{
		$sql="select PROFILEID as RECEIVER from jsadmin.OFFLINE_MATCHES where MATCH_ID='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	        while($myrow=mysql_fetch_array($result))
        	        $sender[]=$myrow['RECEIVER'];
	}
	return $sender;
}
function incorrect_count_track($data)
{
	//added by sriram for tracking of incorrect counts in view all stats page.
	$profileid=$data['PROFILEID'];
	
	global $mysqlObj;
	
	$memcache_cont_stat=unserialize(memcache_call($profileid));
	if($memcache_cont_stat)
        {
                return 1;       

        }
	$sql_check = "SELECT * FROM CONTACTS_STATUS WHERE PROFILEID = '$profileid'";
	$res_check = mysql_query_decide($sql_check) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_check,"ShowErrTemplate");
	if(mysql_num_rows($res_check) > 0)
	{
		$today=date("Y-m-d");
		$row_check=mysql_fetch_assoc($res_check);
		//The array used to store data into memcache.
		$original_arr=array('TOTAL_CONTACTS_MADE','TODAY_INI_BY_ME','MONTH_INI_BY_ME','OPEN_CONTACTS','ACC_BY_ME','ACC_ME','NOT_REP','DEC_ME','DEC_BY_ME','EXPIRY_DT','SAVE_SEARCH');

		foreach($original_arr as $key=>$val)
		{
			$correct_arr[$val]=$row_check[$val];
		}
		
		//This variable is checked in jsChat.php script, to know if user is setting memcache value first time.
		//$correct_arr['FIRST_TIME']=1;
		//Saving data into memcache.
        memcache_call($profileid,serialize($correct_arr));
	}
	//added by sriram for tracking of incorrect counts in view all stats page.
}
/*Function that will save data into memcache and return data from memcache
	$key-> is the key on which memcache will save or return data
	$to_save->data to save, if value is not equal to not_up  then retrieval of data from memcache.
	$time_to_live-> time to which data to retain in memcache.
*/
function memcache_call($key,$to_save='not_up',$time_to_live=3600)
{
	if(!$key)
		return '';

	global $memcacheObj;

	//Giving lock to only those keys that have profileid associated with them.
	$temp=intval($key);
	if($temp>0)
		$allow_lock=1;

	//Temporary stopping the locking.
	$allow_lock=0;

	if(!$memcacheObj && class_exists(UserMemcache))
		$memcacheObj=new UserMemcache;
	if($memcacheObj)
	{
		//$t1=time();
		if($to_save!="not_up")
		{
			
			if($allow_lock)
				$lock=$memcacheObj->setlock($key);
			//Put data into memcache
			$memcacheObj->setDataToMem($to_save,$key,$time_to_live);
			//release lock
			if($allow_lock)
				$memcacheObj->releaselock($lock);
		}
		else
		{
			if($allow_lock)
                                $lock=$memcacheObj->setlock($key);
			$data=$memcacheObj->getDataFromMem($key);
			if($allow_lock)
                                $memcacheObj->releaselock($lock);
			//$t2=time()-$t1;
	                //echo shell_exec($query);
			return $data;
		}
		//$t2=time()-$t1;
		//echo shell_exec($query);
	}
}

//added by Gaurav on 28 March to track save search run
function track_save_search_run()
{
	$tablename='MIS.SAVE_SEARCH_RUN';
	$sql_mis="select ID from $tablename where DATE=now()";
	$result_mis=mysql_query_decide($sql_mis) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
	$row_mis=mysql_fetch_array($result_mis);
	if($row_mis['ID'])
		$sql_mis="update $tablename set COUNT=COUNT+1 where DATE=now()";
	else
		$sql_mis="INSERT INTO $tablename(ID,DATE,COUNT) values ('',now(),'1')";
															     
	mysql_query_decide($sql_mis) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
}

function get_income_sortby_new($my_income='',$pid='',$gender='',$plus_minus_value='')
{
	$rupeeArr=array(2,3,4,5,6,7,16,17,18,19,20,22,23,24,25,26,27);
	$dollarArr=array(8,9,10,11,12,13,14,21);
	$exceptionArr=array(15,14,21);
	$dollar_equi_to_rupee[8]='4';$dollar_equi_to_rupee[9]='6';$dollar_equi_to_rupee[10]='16';$dollar_equi_to_rupee[11]='17';$dollar_equi_to_rupee[12]='17';$dollar_equi_to_rupee[13]='18';$dollar_equi_to_rupee[21]='22';$dollar_equi_to_rupee[14]='23';

        if(!$my_income && $pid && ($plus_minus_value && !$gender) )
        {
                $sql="SELECT INCOME,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$pid";
                $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
                $my_income=$row["INCOME"];
                $gender=$row["GENDER"];
        }
	if($gender=='M')
		$complete_income[]=15;//No income
	if(!$my_income)
	{
		for($i=2;$i<28;$i++)
		$complete_income[]=$i;
		$income_sortby=implode(",",$complete_income);
        	return $income_sortby;
	}
	if($plus_minus_value)
	{
		global $INCOME_VALUE_TO_SORT,$INCOME_SORT_TO_VALUE;

		if($gender=='F')
		{
			$myIncSort=$INCOME_VALUE_TO_SORT[$my_income];	
			$myIncSort-=$plus_minus_value;
			$tempInc=$INCOME_SORT_TO_VALUE[$myIncSort];
			if($tempInc)
			$complete_income[]=$tempInc;
			if(array_key_exists($tempInc,$dollar_equi_to_rupee))
				$complete_income[]=$dollar_equi_to_rupee[$tempInc];
				
		}
		else
		{
			$myIncSort=$INCOME_VALUE_TO_SORT[$my_income];	
			$myIncSort+=$plus_minus_value;
			$tempInc=$INCOME_SORT_TO_VALUE[$myIncSort];
			if($tempInc)
			$complete_income[]=$tempInc;
			if(array_key_exists($tempInc,$dollar_equi_to_rupee))
				$complete_income[]=$dollar_equi_to_rupee[$tempInc];
		}
	}


	if($gender=='F')
	{
		if(in_array($my_income,$exceptionArr))
		{
			if($my_income==15)
			{
				for($i=2;$i<28;$i++)
					$complete_income[]=$i;
			}
			if($my_income==14)
			{
				$complete_income[]=14;
				$my_income=$dollar_equi_to_rupee[$my_income];
				$k=array_search($my_income,$rupeeArr);
				for($i=$k;$i<count($rupeeArr);$i++)
					$complete_income[]=$rupeeArr[$i];
			}
			if($my_income==21)
			{
				$complete_income[]=21;
				$complete_income[]=14;
				$my_income=$dollar_equi_to_rupee[$my_income];
				$k=array_search($my_income,$rupeeArr);
				for($i=$k;$i<count($rupeeArr);$i++)
					$complete_income[]=$rupeeArr[$i];
			}
		}
		elseif(in_array($my_income,$rupeeArr))
		{
			foreach($dollarArr as $k=>$v)
				$complete_income[]=$v;
			$k=array_search($my_income,$rupeeArr);
			for($i=$k;$i<count($rupeeArr);$i++)
				$complete_income[]=$rupeeArr[$i];
		}
		else
		{
			$k=array_search($my_income,$dollarArr);
			for($i=$k;$i<count($dollarArr);$i++)
				$complete_income[]=$dollarArr[$i];

			$my_income=$dollar_equi_to_rupee[$my_income];
			$k=array_search($my_income,$rupeeArr);
			for($i=$k;$i<count($rupeeArr);$i++)
				$complete_income[]=$rupeeArr[$i];
		}
	}
	else
	{
		if(in_array($my_income,$exceptionArr))
		{
			if($my_income==15)
				$complete_income[]=15;
			if($my_income==14)
			{
				foreach($dollarArr as $k=>$v)
					$complete_income[]=$v;
				for($i=0;$i<count($rupeeArr);$i++)
					$complete_income[]=$rupeeArr[$i];
			}
			if($my_income==21)
			{
				foreach($dollarArr as $k=>$v)
				{
					if($v!=14)
					$complete_income[]=$v;
				}
				for($i=0;$i<count($rupeeArr);$i++)
					$complete_income[]=$rupeeArr[$i];
			}
		} 
		elseif(in_array($my_income,$rupeeArr))
		{
			$k=array_search($my_income,$rupeeArr);
			for($i=$k;$i>=0;$i--)
				$complete_income[]=$rupeeArr[$i];
		}
		else
		{
			$k=array_search($my_income,$dollarArr);
			for($i=$k;$i>=0;$i--)
				$complete_income[]=$dollarArr[$i];

			for($i=0;$i<count($rupeeArr);$i++)
				$complete_income[]=$rupeeArr[$i];
		}
	}
	sort($complete_income);
	array_unique($complete_income);
	$income_sortby=implode(",",$complete_income);
        return $income_sortby;
}
function get_income_sortby_ankit($my_income='',$pid='',$gender='',$plus_minus_value='')
{
        if(!$my_income && $pid && ($plus_minus_value && !$gender) )
        {
                $sql="SELECT INCOME,GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$pid";
                $res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_mis,"ShowErrTemplate");
                $row=mysql_fetch_array($res);
                $my_income=$row["INCOME"];
                $gender=$row["GENDER"];
        }
        if($my_income==15)
                $income_sortby=0;
        elseif($my_income==21)
                $income_sortby=19;
        elseif($my_income==14)
                $income_sortby=20;
        elseif($my_income==23)
                $income_sortby=12;
        elseif($my_income==20 || $my_income==22)
                $income_sortby=$my_income/2;
        elseif($my_income<7)
                $income_sortby=$my_income;
        elseif(in_array($my_income,array(16,17,18)))
                $income_sortby=$my_income-9;
        else
                $income_sortby=$my_income+5;

        if($plus_minus_value)
        {
                global $loggedInGender;
                $loggedInGender=$gender;

                if($gender=='F')
                        $income_sortby-=$plus_minus_value;
                else
                        $income_sortby+=$plus_minus_value;
        }
        return $income_sortby;
}
/*
function get_income_sortby($my_income)
{
	if($my_income==1 || !$my_income)
	{
		$income_range[0]=7;
		$income_range[1]=6;
		$income_range[2]=4;
	}
	elseif($my_income==2)
	{
		$income_range[0]=7;
		$income_range[1]=6;
		$income_range[2]=5;
	}
	elseif($my_income==3)
	{
		$income_range[0]=9;
		$income_range[1]=7;
		$income_range[2]=5;
	}
	elseif($my_income==4)
	{
		$income_range[0]=9;
		$income_range[1]=9;
		$income_range[2]=6;
	}
	elseif($my_income==5)
	{
		$income_range[0]=10;
		$income_range[1]=9;
		$income_range[2]=7;
	}
	elseif($my_income==6)
	{
		$income_range[0]=10;
		$income_range[1]=10;
		$income_range[2]=9;
	}
	elseif($my_income==7)
	{
		$income_range[0]=10;
		$income_range[1]=10;
		$income_range[2]=9;
	}
	elseif($my_income==8)
	{
		$income_range[0]=13;
		$income_range[1]=13;
		$income_range[2]=10;
	}
	elseif($my_income==9)
	{
		$income_range[0]=13;
		$income_range[1]=13;
		$income_range[2]=10;
	}
	elseif($my_income==10)
	{
		$income_range[0]=13;
		$income_range[1]=13;
		$income_range[2]=13;
	}
	elseif($my_income==11)
	{
		$income_range[0]=13;
		$income_range[1]=13;
		$income_range[2]=13;
	}
	elseif($my_income==12)
	{
		$income_range[0]=13;
		$income_range[1]=13;
		$income_range[2]=13;
	}
	elseif($my_income==13)
	{
		$income_range[0]=14;
		$income_range[1]=14;
		$income_range[2]=14;
	}
	elseif($my_income==14)
	{
		$income_range[0]=15;
		$income_range[1]=15;
		$income_range[2]=15;
	}
	elseif($my_income==15)
	{
		$income_range[0]=16;
		$income_range[1]=16;
		$income_range[2]=16;
	}
	elseif($my_income==16)
	{
		$income_range[0]=17;
		$income_range[1]=17;
		$income_range[2]=17;
	}
	elseif($my_income==17)
	{
		$income_range[0]=18;
		$income_range[1]=18;
		$income_range[2]=18;
	}
	elseif($my_income==18)
	{
		$income_range[0]=18;
		$income_range[1]=18;
		$income_range[2]=18;
	}
	else
	{
		$income_range[0]=7;
		$income_range[1]=6;
		$income_range[2]=4;
	}
	return ($income_range);
}
*/
function get_income_sortby($my_income)
{
        if($my_income==1 || !$my_income)
        {
                $income_range[0]=16;
                $income_range[1]=6;
                $income_range[2]=4;
        }
        elseif($my_income==2)
        {
                $income_range[0]=16;
                $income_range[1]=6;
                $income_range[2]=5;
        }
        elseif($my_income==3)
        {
                $income_range[0]=16;
                $income_range[1]=16;
                $income_range[2]=5;
        }
        elseif($my_income==4)
        {
                $income_range[0]=17;
                $income_range[1]=16;
                $income_range[2]=6;
        }
        elseif($my_income==5)
        {
                $income_range[0]=17;
                $income_range[1]=17;
                $income_range[2]=16;
        }
        elseif($my_income==6)
        {
                $income_range[0]=18;
                $income_range[1]=17;
                $income_range[2]=16;
        }
        elseif($my_income==16)
        {
                $income_range[0]=18;
		$income_range[1]=18;
                $income_range[2]=17;
        }
        elseif($my_income==17)
        {
                $income_range[0]=20;
                $income_range[1]=18;
                $income_range[2]=18;
        }
        elseif($my_income==18)
        {
                $income_range[0]=20;
                $income_range[1]=20;
                $income_range[2]=20;
        }
        elseif($my_income==20)
        {
                $income_range[0]=22;
                $income_range[1]=22;
                $income_range[2]=22;
        }
        elseif($my_income==22)
        {
                $income_range[0]=23;
                $income_range[1]=23;
                $income_range[2]=23;
        }
        elseif($my_income==23)
        {
                $income_range[0]=23;
                $income_range[1]=23;
                $income_range[2]=23;
        }
        elseif($my_income==8)
        {
                $income_range[0]=13;
                $income_range[1]=13;
                $income_range[2]=10;
        }
        elseif($my_income==9)
	{
                $income_range[0]=13;
                $income_range[1]=13;
                $income_range[2]=10;
        }
        elseif($my_income==10)
        {
                $income_range[0]=13;
                $income_range[1]=13;
                $income_range[2]=13;
        }
        elseif($my_income==11)
        {
                $income_range[0]=13;
                $income_range[1]=13;
                $income_range[2]=13;
        }
        elseif($my_income==12)
        {
                $income_range[0]=13;
                $income_range[1]=13;
                $income_range[2]=13;
        }
        elseif($my_income==13)
        {
                $income_range[0]=14;
                $income_range[1]=14;
                $income_range[2]=14;
        }
        elseif($my_income==21)
        {
                $income_range[0]=14;
                $income_range[1]=14;
                $income_range[2]=14;
        }
        elseif($my_income==14)
        {
                $income_range[0]=14;
                $income_range[1]=14;
                $income_range[2]=14;
        }
	else
        {
                $income_range[0]=16;
                $income_range[1]=6;
                $income_range[2]=4;
        }
        return ($income_range);
}


function get_variables_for_3d($profileid,$neglect_cookies='')
{
	global $PROFILE_3D;	//Declared in mainmenu.php , used to prevent same query to run again.
	
	if(is_array($PROFILE_3D))
		$fields=$PROFILE_3D['FIELDS'];

	if($fields=="")
		$fields=" CASTE,MTONGUE,INCOME,RELIGION,AGE ";

	if($PROFILE_3D['AGE']=="")
	{
		$sql="SELECT  $fields  FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";

		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($res)>= 0)
		{
			$pass=1;
			$myrow_get_value=mysql_fetch_array($res);
			$PROFILE_3D=$myrow_get_value;
		}
		
	}
	else
	{
		$myrow_get_value=$PROFILE_3D;
		$pass=1;
	}	
	
	if($pass)
	{
		$arr_3d['CASTE']=$myrow_get_value['CASTE'];
		$arr_3d['MTONGUE']=$myrow_get_value['MTONGUE'];
		if($myrow_get_value['INCOME']==15)
			$myrow_get_value['INCOME']=1;
		elseif($myrow_get_value['INCOME']<=7)
			$myrow_get_value['INCOME']++;
		elseif($myrow_get_value['INCOME']>=8 && $myrow_get_value['INCOME']<=14)
			$myrow_get_value['INCOME']+=4;
		elseif($myrow_get_value['INCOME']>=16 && $myrow_get_value['INCOME']<=18)
			$myrow_get_value['INCOME']-=7;
		$arr_3d['INCOME']=$myrow_get_value['INCOME'];
		$arr_3d['RELIGION']=$myrow_get_value['RELIGION'];
		$arr_3d['AGE']=$myrow_get_value['AGE'];
		if(!$arr_3d['CASTE'])
			$arr_3d['CASTE']=0;
		if(!$arr_3d['MTONGUE'])
			$arr_3d['MTONGUE']=0;
		if(!$arr_3d['INCOME'])
			$arr_3d['INCOME']=0;
		if(!$arr_3d['RELIGION'])
			$arr_3d['RELIGION']=0;
		if(!$arr_3d['AGE'])
			$arr_3d['AGE']=0;
	}
	else
	{
		$arr_3d['CASTE']=0;
		$arr_3d['MTONGUE']=0;
		$arr_3d['INCOME']=0;
		$arr_3d['RELIGION']=0;
		$arr_3d['AGE']=0;
	}
	
	return($arr_3d);
}


function get_variables_for_3d_old($profileid,$neglect_cookies='')
{
	global $domain;
	if(!isset($_COOKIE["JS_CASTE"]) || $neglect_cookies)
		$get_values[]="CASTE";
	if(!isset($_COOKIE["JS_MTONGUE"]) || $neglect_cookies)
		$get_values[]="MTONGUE";
	if(!isset($_COOKIE["JS_INCOME"]) || $neglect_cookies)
		$get_values[]="INCOME";
	if(!isset($_COOKIE["JS_RELIGION"]) || $neglect_cookies)
		$get_values[]="RELIGION";
	if(!isset($_COOKIE["JS_AGE"]) || $neglect_cookies)
		$get_values[]="AGE";
	if(is_array($get_values))
	{
		$sql_get_values=implode(",",$get_values);
		$sql="SELECT ".$sql_get_values." FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($res)>= 0)
		{
			$myrow_get_value=mysql_fetch_array($res);
			$count_get_values=count($get_values);
			for($k=0;$k<$count_get_values;$k++)
			{
				if($get_values[$k]=='CASTE')
					$my_caste=$myrow_get_value[$get_values[$k]];
				elseif($get_values[$k]=='MTONGUE')
					$my_mtongue=$myrow_get_value[$get_values[$k]];
				elseif($get_values[$k]=='INCOME')
				{	
				        if($myrow_get_value[$get_values[$k]]==0)
						$myrow_get_value[$get_values[$k]]=15;
					if($myrow_get_value[$get_values[$k]]==15)
						$myrow_get_value[$get_values[$k]]=1;
					elseif($myrow_get_value[$get_values[$k]]<=7)
						$myrow_get_value[$get_values[$k]]++;
					elseif($myrow_get_value[$get_values[$k]]>=8 && $myrow_get_value[$get_values[$k]]<=14)
						$myrow_get_value[$get_values[$k]]+=4;
					elseif($myrow_get_value[$get_values[$k]]>=16 && $myrow_get_value[$get_values[$k]]<=18)
						$myrow_get_value[$get_values[$k]]-=7;
					$my_income=$myrow_get_value[$get_values[$k]];
				}
				elseif($get_values[$k]=='RELIGION')
					$my_religion=$myrow_get_value[$get_values[$k]];
				elseif($get_values[$k]=='AGE')
					$my_age=$myrow_get_value[$get_values[$k]];
				if(!headers_sent() && !$neglect_cookies)
					setcookie('JS_'.$get_values[$k],$myrow_get_value[$get_values[$k]],0,"/",$domain);
			}
			if(!(in_array('CASTE',$get_values)) && !$neglect_cookies)
				$my_caste=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_CASTE"]);
			if(!(in_array('MTONGUE',$get_values)) && !$neglect_cookies)
				$my_mtongue=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_MTONGUE"]);
			if(!(in_array('INCOME',$get_values)) && !$neglect_cookies)
				$my_income=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_INCOME"]);
			if(!(in_array('RELIGION',$get_values)) && !$neglect_cookies)
				$my_religion=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_RELIGION"]);;
			if(!(in_array('AGE',$get_values)) && !$neglect_cookies)
				$my_age=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_AGE"]);
		}
	}
	else
	{
		$my_caste=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_CASTE"]);
		$my_mtongue=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_MTONGUE"]);
		$my_income=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_INCOME"]);
		$my_religion=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_RELIGION"]);
		$my_age=preg_replace('/[^A-Za-z0-9\. -]/', '', $_COOKIE["JS_AGE"]);
	}
	$arr_3d['CASTE']=$my_caste;
	$arr_3d['MTONGUE']=$my_mtongue;
	$arr_3d['INCOME']=$my_income;
	$arr_3d['RELIGION']=$my_religion;
	$arr_3d['AGE']=$my_age;
	return($arr_3d);
}

function create_castemapping_query($Gender,$Mtongue1,$RELATED_CHECK_C,$RELATED_CHECK_CM,$searchCaste,$searchCaste2,$castemapping_query,$castemapping_clustering_query,$cosmo,$sortorder,$logic_3d,$keyword,$fromfile)
{
	if($Mtongue1=='')
	{
		$castemapping_sql1="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS PROFILEID ";
		
		if(count($RELATED_CHECK_CM)>=1)
		{
			if($logic_3d)
				$castemapping_sql1.=" ,SORT_DT,PROFILE_SCORE,FRESHNESS_POINTS,CASTE,INCOME_SORTBY,MTONGUE,RELIGION ";
			elseif($sortorder==1)
				$castemapping_sql1.=" , TOTAL_POINTS as X , SORT_DT as Y ";
			else
				$castemapping_sql1.=" , $sortorder as X ";
		}
		
		$castemapping_sql1.=" FROM ";
		
		if($Gender=="M")
		{
			if($Keyword=="")
				$castemapping_sql1.="SEARCH_MALE WHERE ";
			else
				$castemapping_sql1.="SEARCH_MALE_FULL1 WHERE ";
		}
		else
		{
			if($Keyword=="")
				$castemapping_sql1.="SEARCH_FEMALE WHERE ";
			else
				$castemapping_sql1.="SEARCH_FEMALE_FULL1 WHERE ";
		}
		
		$castemapping_sql1 .= $castemapping_query;
		
		if($searchCaste2!='')
		{
			$searchCaste2=$searchCaste2.",$searchCaste";
			if($Keyword && $Gender=='F')
				$castemapping_sql1.=" SEARCH_FEMALE_FULL1.CASTE IN ($searchCaste2) ";
			elseif($Keyword && $Gender=='M')
				$castemapping_sql1.=" SEARCH_MALE_FULL1.CASTE IN ($searchCaste2) ";
			elseif($Gender=="M")
				$castemapping_sql1.=" SEARCH_MALE.CASTE IN ($searchCaste2) ";
			else
				$castemapping_sql1.=" SEARCH_FEMALE.CASTE IN ($searchCaste2) ";
		}
		elseif(strstr($searchCaste,','))
		{
			if($Keyword && $Gender=='F')
				$castemapping_sql1.=" SEARCH_FEMALE_FULL1.CASTE IN ( $searchCaste) ";
			elseif($Keyword && $Gender=='M')
				$castemapping_sql1.=" SEARCH_MALE_FULL1.CASTE IN ( $searchCaste) ";
			elseif($Gender=="M")
				$castemapping_sql1.=" SEARCH_MALE.CASTE IN  ($searchCaste) ";
			else
				$castemapping_sql1.=" SEARCH_FEMALE.CASTE IN  ( $searchCaste) ";
		}
		elseif($searchCaste!='')
		{
			if($Keyword && $Gender=='F')
				$castemapping_sql1.=" SEARCH_FEMALE_FULL1.CASTE =$searchCaste ";
			elseif($Keyword && $Gender=='M')
				$castemapping_sql1.=" SEARCH_MALE_FULL1.CASTE =$searchCaste ";
			elseif($Gender=="M")
				$castemapping_sql1.=" SEARCH_MALE.CASTE =$searchCaste ";
			else
				$castemapping_sql1.=" SEARCH_FEMALE.CASTE  =$searchCaste ";
		}
		else
			$castemapping_sql1.=" 1 ";	
		//echo "castemapping_sql1 is ".$castemapping_sql1;
		if(count($RELATED_CHECK_CM)>=1)
		{
			$castemapping_sql2="SELECT SQL_CACHE PROFILEID ";
			if($logic_3d)
				$castemapping_sql2.=",SORT_DT,PROFILE_SCORE,FRESHNESS_POINTS,CASTE,INCOME_SORTBY,MTONGUE,RELIGION ";
			elseif($sortorder==1)
				$castemapping_sql2.=",TOTAL_POINTS as X,SORT_DT as Y ";
			else
				$castemapping_sql2.=",$sortorder as X ";
			
			$castemapping_sql2.=" FROM ";
														     
			if($Gender=="M")
			{
				if($Keyword=="")
					$castemapping_sql2.="SEARCH_MALE WHERE ";
				else
					$castemapping_sql2.="SEARCH_MALE_FULL1 WHERE ";
			}
			else
			{
				if($Keyword=="")
					$castemapping_sql2.="SEARCH_FEMALE WHERE ";
				else
					$castemapping_sql2.="SEARCH_FEMALE_FULL1 WHERE ";
			}
			$castemapping_sql2.=$castemapping_query;
														     
			$string_cm="'" . implode($RELATED_CHECK_CM,"','") . "'";
			if(strstr($string_cm,","))
			{
				if($Keyword && $Gender=='F')
					$castemapping_sql2.=" SEARCH_FEMALE_FULL1.CASTE_MTONGUE IN ($string_cm) ";
				elseif($Keyword && $Gender=='M')
					$castemapping_sql2.=" SEARCH_MALE_FULL1.CASTE_MTONGUE IN ($string_cm) ";
				elseif($Gender=="M")
					$castemapping_sql2.=" SEARCH_MALE.CASTE_MTONGUE IN ($string_cm) ";
				else
					$castemapping_sql2.=" SEARCH_FEMALE.CASTE_MTONGUE IN ($string_cm) ";
			}
			elseif($string_cm!='')
			{
				if($Keyword && $Gender=='F')
					$castemapping_sql2.=" SEARCH_FEMALE_FULL1.CASTE_MTONGUE= $string_cm ";
				elseif($Keyword && $Gender=='M')
					$castemapping_sql2.=" SEARCH_MALE_FULL1.CASTE_MTONGUE = $string_cm ";
				elseif($Gender=="M")
					$castemapping_sql2.=" SEARCH_MALE.CASTE_MTONGUE=$string_cm ";
				else
					$castemapping_sql2.=" SEARCH_FEMALE.CASTE_MTONGUE=$string_cm ";
			}
			else
				$castemapping_sql2.=" 1 ";
			//echo "castemapping_sql2 is ".$castemapping_sql2;
		}
	}
	else
	{
		$castemapping_sql3="SELECT SQL_CACHE  SQL_CALC_FOUND_ROWS PROFILEID FROM ";
		if($Gender=="M")
		{
			if($Keyword=="")
				$castemapping_sql3.="SEARCH_MALE WHERE ";
			else
				$castemapping_sql3.="SEARCH_MALE_FULL1 WHERE ";
		}
		else
		{
			if($Keyword=="")
				$castemapping_sql3.="SEARCH_FEMALE WHERE ";
			else
				$castemapping_sql3.="SEARCH_FEMALE_FULL1 WHERE ";
		}
		$castemapping_sql3 .= $castemapping_query;
		
		//echo $castemapping_sql3;
		$Mtongue1str=str_replace("'",'',$Mtongue1);
		$Mtongue1_arr=explode(",",$Mtongue1str);
														     
		if(strstr($Mtongue1,","))
		{
			$searchCaste_str=str_replace("'",'',$searchCaste);
			if(count($RELATED_CHECK_CM)>=1)
			{
				$string_cm.="'" . implode($RELATED_CHECK_CM,"','") . "'";
			}
			if(!strstr($searchCaste,','))
				for($i=0;$i<count($Mtongue1_arr);$i++)
					$string2.="'".$searchCaste_str."-".$Mtongue1_arr[$i]."',";
			else
			{
				 $searchCaste_arr=explode(',',$searchCaste_str);
				 for($i=0;$i<count($searchCaste_arr);$i++)
					for($x=0;$x<count($Mtongue1_arr);$x++)
						 $string2.="'".$searchCaste_arr[$i]."-".$Mtongue1_arr[$x]."',";
			}                                                                                                                              
			$string2 = substr($string2, 0, strlen($string2)-1);
			if($string_cm!='')
				$string_cm.=",".$string2;
			else
				$string_cm=$string2;
			for($i=0;$i<count($RELATED_CHECK_C);$i++)
				for($x=0;$x<count($Mtongue1_arr);$x++)
					$string3.="'".$RELATED_CHECK_C[$i]."-".$Mtongue1_arr[$x]."',";
			if($string3!='')
				$string3 = substr($string3, 0, strlen($string3)-1);
			if($string_cm!='' && $string3!='')
				$string_cm.=",".$string3;
		}
		else
		{
			$searchCaste_str=str_replace("'",'',$searchCaste);
			if(!strstr($searchCaste,','))
				$string_cm="'".$searchCaste_str."-".$Mtongue1str."'";
			else
			{
				$searchCaste_arr=explode(',',$searchCaste_str);
				for($i=0;$i<count($searchCaste_arr);$i++)
					$string_cm.="'".$searchCaste_arr[$i]."-".$Mtongue1str."',";
				if($string_cm!='')
					$string_cm = substr($string_cm, 0, strlen($string_cm)-1);
			}
			if(count($RELATED_CHECK_CM)>=1)
			{
				$string1.="'" . implode($RELATED_CHECK_CM,"','") . "'";
				$string_cm.=",".$string1;
			}
			for($i=0;$i<count($RELATED_CHECK_C);$i++)
				$string2.="'".$RELATED_CHECK_C[$i]."-".$Mtongue1str."',";
			if($string2!='')
			{       $string2 = substr($string2, 0, strlen($string2)-1);
				$string_cm.=",".$string2;
			}
		}
		//echo $string_cm;
		if($string_cm!='')
		{	if($Keyword && $Gender=='F')
				$castemapping_sql3.=" SEARCH_FEMALE_FULL1.CASTE_MTONGUE IN ($string_cm) ";
			elseif($Keyword && $Gender=='M')
				$castemapping_sql3.=" SEARCH_MALE_FULL1.CASTE_MTONGUE IN ($string_cm) ";
			elseif($Gender=="M")
				$castemapping_sql3.=" SEARCH_MALE.CASTE_MTONGUE IN ($string_cm) ";
			else
				$castemapping_sql3.=" SEARCH_FEMALE.CASTE_MTONGUE IN ($string_cm) ";
		}
		else
			$castemapping_sql3.=" 1 ";
	}
	
	if($cosmo)
	{
		if($Gender=='M')
		{
			if($Keyword)
				$cosmo_table="SEARCH_MALE_FULL1";
			else
				$cosmo_table="SEARCH_MALE";
			$search_gender='F';
		}
		else
		{
			if($Keyword)
				$cosmo_table="SEARCH_FEMALE_FULL1";
			else
				$cosmo_table="SEARCH_FEMALE";
			$search_gender='M';
		}

		if($logic_3d)
			$sql_q_castemap=", SORT_DT,$cosmo_table.CASTE,PROFILE_SCORE,FRESHNESS_POINTS,INCOME_SORTBY,MTONGUE,RELIGION ";
		elseif($sortorder==1)
			$sql_q_castemap=", TOTAL_POINTS AS X , SORT_DT AS Y ";
		else
			$sql_q_castemap=", $sortorder as X ";
		
		//$sql_cosmo_clustering_castemapping= " FROM JPARTNER AS J LEFT JOIN PARTNER_CASTE AS P on P.PARTNERID=J.PARTNERID JOIN $cosmo_table ON J.PROFILEID=$cosmo_table.PROFILEID  WHERE P.CASTE IS NULL AND J.GENDER='$search_gender'";
		$sql_cosmo_clustering_castemapping= " FROM $cosmo_table, newjs.COSMO AS C WHERE $cosmo_table.PROFILEID = C.PROFILEID";

		if($castemapping_sql1!='')
		{
			$q=strstr($castemapping_sql1,"WHERE");
			$q=substr($q,6);
			if($castemapping_sql2!='')
				$castemapping_sql1="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS C.PROFILEID AS PROFILEID ".$sql_q_castemap." ".$sql_cosmo_clustering_castemapping." AND ".$q;
			else
				$castemapping_sql1="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS C.PROFILEID AS PROFILEID ".$sql_cosmo_clustering_castemapping." AND ".$q;
														     
		}
		if($castemapping_sql2!='')
		{
			$q=strstr($castemapping_sql2,"WHERE");
			$q=substr($q,6);
			$castemapping_sql2="SELECT C.PROFILEID AS PROFILEID ".$sql_q_castemap." ".$sql_cosmo_clustering_castemapping." AND ".$q;
		}
		if($castemapping_sql3!='')
		{
			$q=strstr($castemapping_sql3,"WHERE");
			$q=substr($q,6);
			$castemapping_sql3="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS  C.PROFILEID AS PROFILEID ".$sql_cosmo_clustering_castemapping." AND ".$q; 
		}
	}
	$castemapping_arr[0]=$castemapping_sql1;
	$castemapping_arr[1]=$castemapping_sql2;
	$castemapping_arr[2]=$castemapping_sql3;
	return $castemapping_arr;
}

function create_3d_orderby($Profileid,$Gender,$Mtongue1,$RELATED_CHECK_C,$RELATED_CHECK_CM,$seCaste,$income,$union_query)
{
	//$Gender is the searched Gender.
	//$Mtongue1 is the searched Mtongue in this format ex- '1','2','3','4'
	//$RELATED_CHECK_C is the array that contains caste searched from castemapping.
	//$RELATED_CHECK_CM is the arrat caste-mtongue pair searched from castemapping.
	//$income denotes searched income in this format ex- 1,2,3,4
	//$union_query denotes whether union query is created in castemapping or not.
	$arr_3d=get_variables_for_3d($Profileid);
	if($arr_3d['CASTE'])
		$my_caste=$arr_3d['CASTE'];
        else
		$my_caste=0;
	if($arr_3d['MTONGUE'])
		$my_mtongue=$arr_3d['MTONGUE'];
	else
        	$my_mtongue=0;
	if($arr_3d['INCOME'])
		$my_income=$arr_3d['INCOME'];
	else
		$my_income=0;
	if($arr_3d['RELIGION'])
		$my_religion=$arr_3d['RELIGION'];
	else
		$my_religion=0;

	$my_mtongue=7;
	$my_caste=17;

	//my_caste my_mtongue my_income my_religion are respective values of person logged in.

	//echo "my caste is ".$my_caste." and mtongue is ".$my_mtongue." and religion is ".$my_religion;
	if($Gender=="M")
	{
		if($Keyword=="")
			"SEARCH_MALE";
		else
			"SEARCH_MALE_FULL1";
	}
	else
	{
		if($Keyword=="")
			"SEARCH_FEMALE";
		else
			"SEARCH_FEMALE_FULL1";
	}
	if($Mtongue1!='')
	{
		$Mtongue1str=str_replace("'",'',$Mtongue1);
		$seMtongue=explode(",",$Mtongue1str);
	}
	$rel_caste_temp=get_all_caste($my_caste);
	$sql_rel_caste="select REL_CASTE,PARENT from CASTE_COMMUNITY,CASTE where PARENT_CASTE ='$my_caste' AND VALUE = REL_CASTE";
	$sql_rel_caste_result=mysql_query_decide($sql_rel_caste);
	while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
	{
		if($myrow_sql_rel_caste_result["PARENT"]==$my_religion)
			$rel_caste_temp[]=$myrow_sql_rel_caste_result["REL_CASTE"];
		else
			$rel_caste_diff_religion[]=$myrow_sql_rel_caste_result["REL_CASTE"];
	}
	if(is_array($rel_caste_temp))
	{       foreach ($rel_caste_temp as $rel_caste_value)
		{
			if($rel_caste_value!=$my_caste)
			{
				$rel_caste[]=$rel_caste_value;
			}
		}
		unset($rel_caste_temp);
	}
	if(!is_array($rel_caste))
	{
		$rel_caste=Array();
	}
	if(!is_array($rel_caste_diff_religion))
	{
		$rel_caste_diff_religion=Array();
	}
													     
	/*echo "<br>";
	echo "seCaste before is ";
	print_r($seCaste);
	echo "<br>se mtongue before is ";
	print_r($seMtongue);*/
	
	$RELATED_CHECK_C_COUNT=count($RELATED_CHECK_C);
	$RELATED_CHECK_CM_COUNT=count($RELATED_CHECK_CM);
	for($p=0;$p<$RELATED_CHECK_C_COUNT;$p++)
		$seCaste[]=$RELATED_CHECK_C[$p];
	for($p=0;$p<$RELATED_CHECK_CM_COUNT;$p++)
	{
		$exarr=explode("-",$RELATED_CHECK_CM[$p]);
		$seCaste[]=$exarr[0];
		$seMtongue[]=$exarr[1];
	}
	if(!is_array($seMtongue))
		$seMtongue=Array();
	$seMtongue = array_unique($seMtongue);
													     
	/*echo "<br>se mtongue after is ";
	print_r($seMtongue);
	echo "<br>related check c is ";
	print_r($RELATED_CHECK_C);
	echo "<br>related check cm is ";
	print_r($RELATED_CHECK_CM);
	echo "<br>";*/
													     
	if(!is_array($seCaste))
		$seCaste=Array();
	$seCaste = array_unique($seCaste);
													     
	if(count($seCaste)>0)
	{
		$seCaste_str=implode("','",$seCaste);
		$sql_religion="select DISTINCT PARENT from CASTE where VALUE IN ('$seCaste_str')";
		$sql_religion_result=mysql_query_decide($sql_religion);
		while($myrow_sql_religion_result=mysql_fetch_array($sql_religion_result))
			$search_religion[]=$myrow_sql_religion_result["PARENT"];
	}
	if(!is_array($search_religion))
		$search_religion=Array();
													     
	/*echo "seCaste after is ";
	print_r($seCaste);
	echo "<br>seCaset count is ".count($seCaste);
	echo "<br>";
	echo "search religion is ";
	print_r($search_religion);*/

	if(is_array($rel_caste))
		$rel_caste = array_unique($rel_caste);
	if(is_array($rel_caste_diff_religion))                                 
		$rel_caste_diff_religion = array_unique($rel_caste_diff_religion);
													     
	/*echo "<br>";
	echo "related caste is ";
	print_r($rel_caste);
	echo "<br>";
	echo "related caste diff religion is ";
	print_r($rel_caste_diff_religion);
	echo "<br>";*/
	$seCaste[0]=1;
	if(count($seCaste)==0)
	{
		if(count($rel_caste_diff_religion)==0)
		{
			if(count($rel_caste)>0)
			{
				$rel_caste_str=implode("','",$rel_caste);
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_str'),125,if(RELIGION=$my_religion,25,-100))) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_str'),125,if(RELIGION=$my_religion,25,-100))) ";
			}
			else
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(RELIGION=$my_religion,25,-100)) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(RELIGION=$my_religion,25,-100)) ";
			}
		}
		else
		{
			$rel_caste_diff_religion_str=implode("','",$rel_caste_diff_religion);
			if(count($rel_caste)>0)
			{
				$rel_caste_str=implode("','",$rel_caste);
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_str'),125,if(CASTE IN('$rel_caste_diff_religion_str'),100,if(RELIGION=$my_religion,25,-100)))) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_str'),125,if(CASTE IN('$rel_caste_diff_religion_str'),100,if(RELIGION=$my_religion,25,-100)))) ";
			}
			else
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_diff_religion_str'),100,if(RELIGION=$my_religion,25,-100))) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$rel_caste_diff_religion_str'),100,if(RELIGION=$my_religion,25,-100))) ";
			}
		}
	}
	else
	{
		if(count($seCaste)==1 && in_array($my_caste,$seCaste))
			$sql_if[]=" 200 ";
		else
		{
			$flag_my_caste=0;
			if(in_array($my_caste,$seCaste))
			{
				$flag_my_caste=1;
			}
													     
			$flag_caste=0;//all castes in search caste expect my own caste are in my related caste and my religion(125)
			foreach ($seCaste as $value_caste)
			{
				if(!in_array($value_caste,$rel_caste) && $value_caste!=$my_caste)
				{
					$flag_caste=1;
					break;
				}
			}
			if($flag_caste==1)
			{
				foreach ($seCaste as $value_caste)
				{
					if(in_array($value_caste,$rel_caste))
					{
						$caste_arr_3d[]=$value_caste;
					}
				}
			}
			if(count($caste_arr_3d)>0)
				$caste_3d_str=implode("','",$caste_arr_3d);
													     
			$flag_diff_religion=1;          //nobody in my religion
			if(count($search_religion)==1 && $search_religion[0]==$my_religion)
				$flag_diff_religion=0;  //everybody in my religion
			elseif(in_array($my_religion,$search_religion))
				$flag_diff_religion=2;  //somebody in my religion
													     
			if($flag_diff_religion==0)
				$if_religion="25";
			elseif($flag_diff_religion==1)
				$if_religion="-100";
			else
			{
				if(!$union_query)
					$if_religion="if(RELIGION=$my_religion,25,-100)";
				else
					$if_religion="if(RELIGION=$my_religion,25,-100)";
			}
													     
			$flag_caste_diff_religion=0;    //all caste in search caste expect my own caste are in my (related caste which are not in my religion).
			foreach ($seCaste as $value_caste)
			{
				if(!in_array($value_caste,$rel_caste_diff_religion) && $value_caste!=$my_caste)
				{
					$flag_caste_diff_religion=1;
					break;
				}
			}
			if($flag_caste_diff_religion==1)
			{
				foreach ($seCaste as $value_caste)
				{
					if(in_array($value_caste,$rel_caste_diff_religion))
					{
						$caste_arr_3d_diff_religion[]=$value_caste;
					}
				}
			}
			if(count($caste_arr_3d_diff_religion)>0)
				$caste_3d_diff_religion_str=implode("','",$caste_arr_3d_diff_religion);
													     
			/*echo "<br>caste arr 3d is ";
			print_r($caste_arr_3d);
			echo "<br>";*/
													     
			//nobody of my caste but some people in (my related castes and religion(125)) and some people only in (my related caste(100))
			if($flag_my_caste==0 && count($caste_arr_3d)>0 && count($caste_arr_3d_diff_religion)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE IN('$caste_3d_str'),125,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion)) ";
				else
					$sql_if[]=" if(CASTE IN('$caste_3d_str'),125,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion)) ";
			}
													     
			//some people of my caste(200) and some people in (my related castes and religion(125)) and some people only in (my related caste(100))
			elseif($flag_my_caste==1 && count($caste_arr_3d)>0 && count($caste_arr_3d_diff_religion)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_str'),125,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion))) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_str'),125,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion))) ";
			}
													     
			//nobody of my caste but all in my related castes(100) and my religion(25)
			elseif($flag_my_caste==0 && $flag_caste==0 && $flag_diff_religion==0)
				$sql_if[]=" 125 ";
													     
			//nobody of my caste but all in my related castes(100)
			elseif($flag_my_caste==0 && $flag_caste_diff_religion==0 && $flag_diff_religion==1)
				$sql_if[]=" 100 ";
													     
			//somepeople of my caste(200) and rest all in my related caste(100) and my religion(25)
			elseif($flag_my_caste==1 && $flag_caste==0 && $flag_diff_religion==0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,125) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,125) ";
			}
													     
			//somepeople of my caste(200) and rest all in my related caste(100)
			elseif($flag_my_caste==1 && $flag_caste_diff_religion==0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,100) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,100) ";
			}
													     
			//nobody of my caste and some people in my related caste and religion(125)
			elseif($flag_my_caste==0 && count($caste_arr_3d)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE IN('$caste_3d_str'),125,$if_religion) ";
				else
					$sql_if[]=" if(CASTE IN('$caste_3d_str'),125,$if_religion) ";
			}
													     
			//some people of my caste and some in my related castes and my religion 125
			elseif($flag_my_caste==1 && count($caste_arr_3d)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_str'),125,$if_religion)) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_str'),125,$if_religion)) ";
			}
													     
			//nobody of my caste and some in my related castes 100
		       	elseif($flag_my_caste==0 && count($caste_arr_3d_diff_religion)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion) ";
				else
					$sql_if[]=" if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion) ";
			}
													     
			//some people of my caste and some in my related castes 100
			elseif($flag_my_caste==1 && count($caste_arr_3d_diff_religion)>0)
			{
				if(!$union_query)
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion)) ";
				else
					$sql_if[]=" if(CASTE=$my_caste,200,if(CASTE IN('$caste_3d_diff_religion_str'),100,$if_religion)) ";
			}
			else
				$sql_if[]=" $if_religion ";
		}
	}
	unset($rel_caste);
	
	if(count($seMtongue)==0 || ($Mtongue1=='' && count($RELATED_CHECK_CM)>0))
	{
		if($my_mtongue==10 || $my_mtongue==19 || $my_mtongue==33)
		{
			if(!$union_query)
				$sql_if[]=" if(MTONGUE IN (10,19,33),100,25) ";
			else
				$sql_if[]=" if(MTONGUE IN (10,19,33),100,25) ";
		}
		else
		{
			if(!$union_query)
				$sql_if[]=" if(MTONGUE=$my_mtongue,100,25) ";
			else
				$sql_if[]=" if(MTONGUE=$my_mtongue,100,25) ";
		}
	}
	else
	{
		if($my_mtongue==10 || $my_mtongue==19 || $my_mtongue==33)
		{
			if(in_array(10,$seMtongue) || in_array(19,$seMtongue) || in_array(33,$seMtongue))
			{
				$flag_mtongue=0;
				foreach ($seMtongue as $value_mtongue)
				{
					if($value_mtongue!=10 && $value_mtongue!=19 && $value_mtongue!=33)
					{
						$flag_mtongue=1;
						break;
					}
				}
				/*some people of my mtongue either (10,19 or 33)*/
				if($flag_mtongue==1)
				{
					if(in_array(10,$seMtongue))
						$mtongue_arr_3d[]=10;
					if(in_array(19,$seMtongue))
						$mtongue_arr_3d[]=19;
					if(in_array(33,$seMtongue))
						$mtongue_arr_3d[]=33;
					if(count($mtongue_arr_3d)>=1)
					{
      //  $smarty->assign("bms_left",24);
       // $smarty->assign("bms_right",28);
//	$smarty->assign("bms_new_win",32);
	/*****************************************************************************************/
						$mtongue_3d_str=implode("','",$mtongue_arr_3d);
						if(!$union_query)
							$sql_if[]=" if(MTONGUE IN('$mtongue_3d_str'),100,25) ";
						else
							$sql_if[]=" if(MTONGUE IN('$mtongue_3d_str'),100,25) ";
					}
				}
				/*all people of my mtonuge (either 10 ,19 or 33)*/
				else
					$sql_if[]=" 100 ";
			}
			else
				$sql_if[]=" 25 ";
		}
		else
		{
			/*all people of my mtonuge*/
			if(in_array($my_mtongue,$seMtongue) && count($seMtongue)==1)
				$sql_if[]=" 100 ";
			/*some people of my mtongue*/
			elseif(in_array($my_mtongue,$seMtongue))
			{
				if(!$union_query)
					$sql_if[]=" if(MTONGUE=$my_mtongue,100,25) ";
				else
					$sql_if[]=" if(MTONGUE=$my_mtongue,100,25) ";
			}
			else
				$sql_if[]=" 25 ";
		}
		unset($seMtongue);
	}
	
	/*if($income_clustering)
		$income=$income_clustering;*/
													     
	/*echo "<br>";
	echo "search income before is ";
	echo $income;
	echo "<br>";*/
	
	/*if($my_income==0)
		$my_income=15;
	if($my_income==15)
		$my_income=1;
	elseif($my_income<=7)
		$my_income++;
	elseif($my_income>=8 && $my_income<=14)
		$my_income+=4;
	elseif($my_income>=16 && $my_income<=18)
		$my_income-=7;*/
	
	if($Gender=='M')
		$income_range=get_income_sortby($my_income);
	
	/*echo "my new income is ".$my_income."<br>";
	echo "<br> my income range is <br>";
	print_r($income_range);*/
	
	if($income=='')
	{
		/*girl's income is less than mine income then give girl 100 points else 50 points*/
		if($Gender=='F')
		{
			$sql_if[]=" if(INCOME_SORTBY=$my_income,100,if(INCOME_SORTBY<$my_income,90,-100)) ";
		}
		else
		{
			if($my_income<=9)
				$sql_if[]=" if(INCOME_SORTBY>=$income_range[0],100,if(INCOME_SORTBY>=$income_range[1],80,if(INCOME_SORTBY>=$income_range[2],65,if(INCOME_SORTBY>=$my_income,50,-100)))) ";
			else
				$sql_if[]=" if(INCOME_SORTBY>=$income_range[0],100,if(INCOME_SORTBY>=$my_income,50,-100)) ";
		}
	}
	elseif(strstr($income,','))
	{
		$income_arr=explode(",",$income);
		$income_arr_count=count($income_arr);
		for($p=0;$p<$income_arr_count;$p++)
		{
			//$income_arr[$p]=get_income_sortby_ankit($income_arr[$p]);
			$income_arr[$p]=get_income_sortby_new($income_arr[$p],'',$Gender);
/*			if($income_arr[$p]==15)
				$income_arr[$p]=1;
			elseif($income_arr[$p]<=7)
				$income_arr[$p]++;
			elseif($income_arr[$p]>=8 && $income_arr[$p]<=14)
				$income_arr[$p]+=4;
			elseif($income_arr[$p]>=16 && $income_arr[$p]<=18)
				$income_arr[$p]-=7;
*/		}
		/*echo "search income new is ";
		print_r($income_arr);*/
		/*
		for($p=0;$p<$income_arr_count;$p++)
		{
			if($Gender=='M')
			{
				 if($my_income<=$income_arr[$p])
					$income_arr_3d[]=$income_arr[$p];
			}
			else
			{
				if($my_income>=$income_arr[$p])
					$income_arr_3d[]=$income_arr[$p];
			}
		}
		*/
		for($p=0;$p<$income_arr_count;$p++)
		{
			 if(strpos($income_arr[$p],$my_income))
				$income_arr_3d[]=$income_arr[$p];
		}
		if(count($income_arr_3d)>=1)
		{
			if($Gender=='M')
			{
				if($my_income<=9)
					$sql_if[]=" if(INCOME_SORTBY>=$income_range[0],100,if(INCOME_SORTBY>=$income_range[1],80,if(INCOME_SORTBY>=$income_range[2],65,if(INCOME_SORTBY>=$my_income,50,-100)))) ";
				else
					$sql_if[]=" if(INCOME_SORTBY>=$income_range[0],100,if(INCOME_SORTBY>=$my_income,50,-100)) ";
			}
			else
			{
				$flag_same_income=1;
				$flag_diff_income=1;
				foreach ($income_arr_3d as $others_income)
				{
					if($others_income!=$my_income)
						$income_arr_3d_backup[]=$others_income;
					if($others_income<$my_income)
						$flag_same_income=0;
					elseif($others_income==$my_income)
						$flag_diff_income=0;
				}
				/*echo '<br>income arr 3d backus is';
				print_r($income_arr_3d_backup);
				echo '<br>';*/
				if(is_array($income_arr_3d_backup))
					$income_str=implode("','",$income_arr_3d_backup);
				if(count($income_arr_3d)==count($income_arr) && $flag_same_income && $flag_diff_income==0)
					$sql_if[]=" 100 ";
				elseif(count($income_arr_3d)==count($income_arr) && $flag_same_income==0 && $flag_diff_income)
					$sql_if[]=" 90 ";
				elseif(count($income_arr_3d)==count($income_arr) && $flag_same_income==0 && $flag_diff_income==0)
					$sql_if[]=" if(INCOME_SORTBY=$my_income,100,90) ";
				elseif(in_array($my_income,$income_arr_3d) && $income_str)
					$sql_if[]=" if(INCOME_SORTBY=$my_income,100,if(INCOME_SORTBY IN('$income_str'),90,-100)) ";
				elseif(in_array($my_income,$income_arr_3d))
					$sql_if[]=" if(INCOME_SORTBY=$my_income,100,-100) ";
				elseif($income_str)
					$sql_if[]=" if(INCOME_SORTBY IN('$income_str'),90,-100)";
				else
					$sql_if[]=" -100 ";
				/*if(count($income_arr_3d)==count($income_arr))
					$sql_if[]=" 100 ";
				else
				{
					$income_str=implode("','",$income_arr_3d);
					$sql_if[]=" if(INCOME_SORTBY IN('$income_str'),100,50) ";
				}*/
			}
		}
		else
		{
			$sql_if[]=" -100 ";
		}
	}
	elseif($income!='')
	{       
                include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
	       	$income=getSortByIncome($income);
/*		if($income==15)
			$income=1;
		elseif($income<=7)
			$income++;
		elseif($income>=8 && $income<=14)
			$income+=4;
		elseif($income>=16 && $income<=18)
			$income-=7;
*/		//echo "search income is ".$income;
		if($Gender=='F')
		{
			if($my_income==$income)
				$sql_if[]=" 100 ";
			elseif($my_income>$income)
				$sql_if[]=" 90 ";
			else
				$sql_if[]=" -100 ";
		}
		else
		{
			if($income>=$income_range[0])
				$sql_if[]=" 100 ";
			elseif($income>=$income_range[1])
				$sql_if[]=" 80 ";
			elseif($income>=$income_range[2])
				$sql_if[]=" 65 ";
			elseif($income>=$my_income)
				$sql_if[]=" 50 ";
			else
				$sql_if[]=" -100 ";
		}
	}
	unset($income_range);

	$sql_if2[]=" if(PROFILE_SCORE>325,96,if(PROFILE_SCORE>150,41,27)) ";
	$sql_if2[]=" if(FRESHNESS_POINTS=300,85,25) ";
	$sql_3d="(".implode("+",$sql_if).")";
	/*echo "<br> sql 3d is <br>".$sql_3d."<br>";
	echo "****************************************************************";*/
	$sql_3d_score="(".implode("+",$sql_if2).")";
	$final_sql_3d="(  $sql_3d_score + if($sql_3d>=280,55,if($sql_3d>=175,40,-20)) + ($sql_3d/(if($sql_3d>=280,400,if($sql_3d>=175,275,150))))  ) ";
	return  $final_sql_3d;
}

function calculate_3d_points($caste,$religion,$mtongue,$income,$my_gender='',$profileid,$neglect_cookies='',$arr_3d='',$rel_caste='')
{
	//$caste $religion $mtongue $income are respective values of person whose points are to be calculated.
	//$my_gender and $profileid are respective values of person logged in.
        if(!is_array($arr_3d) || !is_array($rel_caste))
        {
		$arr_3d=get_variables_for_3d($profileid,$neglect_cookies);
		$rel_caste=get_all_caste($arr_3d['CASTE']);
		$sql_rel_caste="select SQL_CACHE REL_CASTE from CASTE_COMMUNITY where PARENT_CASTE ='$arr_3d[CASTE]'";
		$sql_rel_caste_result=mysql_query_decide($sql_rel_caste);
		while($myrow_sql_rel_caste_result=mysql_fetch_array($sql_rel_caste_result))
		{
			$rel_caste[]=$myrow_sql_rel_caste_result["REL_CASTE"];
		}
		if(!is_array($rel_caste))
			$rel_caste=Array();
	}	
	/*$caste_points=0;
	$mtongue_points=0;
	$income_points=0;
	echo '<br>';	
	echo " other caste is ".$caste." other mtongue ".$mtongue." other income is ".$income." other religion is ".$religion;
	echo '<br>';*/
	
	if($caste==$arr_3d['CASTE'])
		$points=200;
	elseif(in_array($caste,$rel_caste) && $religion==$arr_3d['RELIGION'])
		$points=125;
	elseif(in_array($caste,$rel_caste))
		$points=100;
	elseif($religion==$arr_3d['RELIGION'])
		$points=25;
	else
		$points=-100;
	
	/*$caste_points=$points;
	echo 'caste points is '.$points;*/
	
	if($arr_3d['MTONGUE']==10 || $arr_3d['MTONGUE']==19 || $arr_3d['MTONGUE']==33)
	{
		if($mtongue==10 || $mtongue==19 || $mtongue==33)
			$points+=100;
		else
			$points+=25;
	}
	elseif($arr_3d['MTONGUE']==$mtongue)
		$points+=100;
	else
		$points+=25;
	
	/*$mtongue_points=$points-$caste_points;
	echo 'mtongue points is '.$mtongue_points;*/
	
	$others_income=$income;
	/*if($others_income==15)
		$others_income=1;
	elseif($others_income<=7)
		$others_income++;
	elseif($others_income>=8 && $others_income<=14)
		$others_income+=4;
	elseif($others_income>=16 && $others_income<=18)
		$others_income-=7;*/
	if($my_gender=='M')
	{
		if($others_income==$arr_3d['INCOME'])
			$points+=100;
		elseif($others_income<$arr_3d['INCOME'])
			$points+=90;
		else
			$points+=-100;
	}
	else
	{
		$income_range=get_income_sortby($arr_3d['INCOME']);
		if($arr_3d['INCOME']<=9)
		{
			if($others_income>=$income_range[0])
				$points+=100;
			elseif($others_income>=$income_range[1])
				$points+=80;
			elseif($others_income>=$income_range[2])
				$points+=65;
			elseif($others_income>=$arr_3d['INCOME'])
				$points+=50;
			else
				$points+=-100;
		}
		else
		{
			if($others_income>=$income_range[0])
				$points+=100;
			elseif($others_income>=$arr_3d['INCOME'])
				$points+=50;
			else
				$points+=-100;
		}
	}
	
	/*$income_points=$points-$caste_points-$mtongue_points;
	echo 'income points is '.$income_points;
	echo "total points is ".$points."<br>";*/
	return $points;
}

function visitors($profileid,$gender,$showall='',$getcount='')
{
	global $smarty,$VISITORS,$db;
	global $contactResultRec,$contactResultSen;
	//include_once(JsConstants::$docRoot."/profile/connect_db.php");
	//include_once(JsConstants::$docRoot."/profile/connect.inc");
	$db=connect_db();
	if(!$gender)
	{
		global $data;
		if($data['GENDER'])
			$gender = $data['GENDER'];
		else
		{
			$sql = "SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID=".$profileid;
			$res=mysql_query($sql,$db) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_viewer,"ShowErrTemplate");
			if($row=mysql_fetch_row($res))
				$gender = $row['GENDER'];
		}
	}

	//Sharding of CONTACTS done by Sadaf
        $receiversIn=$profileid;
	if($contactResultSen)
		$contactResult=$contactResultSen;
	else
	        $contactResult=getResultSet("SENDER",'','',$receiversIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $ING_PRO[]=$contactResult[$key]["SENDER"];
                unset($contactResult);
        }
        $sendersIn=$profileid;

	if($contactResultRec)
		$contactResult=$contactResultRec;
	else
	        $contactResult=getResultSet("RECEIVER",$sendersIn);
        if(is_array($contactResult))
        {
                foreach($contactResult as $key=>$value)
                        $ING_PRO[]=$contactResult[$key]["RECEIVER"];
                unset($contactResult);
        }
	$sql_viewer="SELECT IGNORED_PROFILEID FROM IGNORE_PROFILE WHERE PROFILEID='$profileid'";
	$result=mysql_query_decide($sql_viewer) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_viewer,"ShowErrTemplate");

        while($row=mysql_fetch_row($result))
        {
                $ING_PRO[]=$row[0];
        }
        
	if(is_array($ING_PRO))
                $ing_pro=implode("','",$ING_PRO);
        else
                $ing_pro='';

        $yday=mktime(0,0,0,date("m"),date("d")-10,date("Y"));    // To get the time for before 10 days
        $back_10_days=date("Y-m-d",$yday);

	$db_211 = connect_211();
        $sql_visit="SELECT VIEWER,DATE,SEEN FROM VIEW_LOG_TRIGGER WHERE VIEWED='$profileid' AND VIEWER NOT IN('$ing_pro') AND VIEWER!='$profileid' AND DATE>='$back_10_days 00:00:00'";

	$res_visit=mysql_query_decide($sql_visit,$db_211,1) or  logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_visit,"ShowErrTemplate");
	
	$db=connect_db();
	if(mysql_num_rows($res_visit)==0)
		return null;
	else
	{
		$profileid_arr=array();
		while($row_visit=mysql_fetch_array($res_visit))
		{	
			if(!in_array($row_visit['VIEWER'],$profileid_arr))
			{
				$profileid_arr[]=$row_visit['VIEWER'];
				$time_array[$row_visit['VIEWER']]=$row_visit['DATE'];
				$seen_array[$row_visit['VIEWER']]=$row_visit['SEEN'];
			}
		}

                include_once($_SERVER['DOCUMENT_ROOT']."/classes/NEGATIVE_TREATMENT_LIST.class.php");
                $NEGATIVE_TREATMENT_LIST=new NEGATIVE_TREATMENT_LIST($db);
                $parameters['FLAG_VIEWABLE']='N';
                $profileid_arr = $NEGATIVE_TREATMENT_LIST->removeNegativeIdsFromList($parameters,$profileid_arr);
		$profileid_str="'".implode($profileid_arr,"','")."'";	

		//$mysqlObj=new Mysql;
                $mydb_slave=connect_737();//$mysqlObj->connect("slave");
		$sql="SELECT AGE,HEIGHT,MANGLIK,MSTATUS,CASTE,RELIGION,MTONGUE,COUNTRY_RES,INCOME,PROFILEID,GENDER,HAVEPHOTO,PHOTO_DISPLAY,if(PRIVACY='' or PRIVACY is null,'A',PRIVACY) as PRIVACY FROM newjs.JPROFILE where  activatedKey=1 and PROFILEID IN ($profileid_str) AND (ACTIVATED='Y' OR (ACTIVATED='N' AND INCOMPLETE='Y'))";
		$result=mysql_query_decide($sql,$mydb_slave) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=mysql_fetch_array($result))
		{
			if($myrow['GENDER']!=$gender)
			{
				//That array will be used in pass_in_visitors
				if($gender=='F')
					$data_for_dpp[]=$myrow;	
				$data_final_profile[]=$myrow['PROFILEID'].",".$time_array[$myrow['PROFILEID']].",".$seen_array[$myrow['PROFILEID']];
				/*This array will be required in mainmenu.php for passing values in get_user_details function */
				if(is_array($VISITORS))
				{
				    $VISITORS[$myrow['PROFILEID']]["HAVEPHOTO"]=$myrow['HAVEPHOTO'];
				    $VISITORS[$myrow['PROFILEID']]["PHOTO_DISPLAY"]=$myrow['PHOTO_DISPLAY'];
				    $VISITORS[$myrow['PROFILEID']]["PRIVACY"]=$myrow['PRIVACY'];
				}				
			}	
		}
		
		$sql_privacy = "SELECT PRIVACY,INCOME FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID = '$profileid'";
                $result_privacy = mysql_query_decide($sql_privacy) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_privacy,"ShowErrTemplate");

	        $myrow_privacy=mysql_fetch_row($result_privacy);
              	$privacy = $myrow_privacy[0];
		$income=$myrow_privacy[1];

		//Remove all the profiles that donot pass female dpp and income criteria..
		if($gender=='F')
			$data_final_profile=pass_in_visitors($data_for_dpp,'',$income,$profileid,1,$time_array,$seen_array);

		$total=count($data_final_profile);
		if(is_array($data_final_profile))
		{
			for($i=0;$i<$total;$i++)
			{
				$data_final = explode(",",$data_final_profile[$i]);
				if($privacy != 'C')
				{
					if($privacy == 'F')
					{	
						if(filterPass($profileid,$data_final[0],1))
						{
							$t=JSstrToTime($data_final[1]);
							$viewer[$t]=$data_final_profile[$i];
						}
					}
					else			
					{
						$t=JSstrToTime($data_final[1]);			 
						$viewer[$t]=$data_final_profile[$i];
					}
				}
			}
			$smarty->assign("visitor_cnt",count($viewer));
			if(is_array($viewer))
				krsort($viewer);
			if($getcount)
				return count($viewer);
			else
				return $viewer;
		}
		else
			return null;
	}
}

//Code for filtering the profile if user has choosen to the option in privacy settings
function filterPass($profileid,$value,$option="")
{
	connect_db();
	$sql="select * from FILTERS where PROFILEID='$profileid'";
       	$resultfilter=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");                                                         
        if(mysql_num_rows($resultfilter) > 0)
        {
        	connect_737_ro();
		$filterrow=mysql_fetch_array($resultfilter);
               	if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"]=="Y")
               	{
                      	////////////
			if(!$option)
			{
				$sqlv="select PROFILEID from JPROFILE where  activatedKey=1 and USERNAME='$value'";
				$resultv=mysql_query_decide($sqlv) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlv,"ShowErrTemplate");
        	                $myrowv=mysql_fetch_array($resultv);
                	        $valueid=$myrowv["PROFILEID"];
			}
			else
				$valueid=$value;
			/////////////
			$sqlfilter="select count(*) from JPROFILE where  activatedKey=1 and PROFILEID='$valueid'";
			$sqlfilter_temp=$sqlfilter;
                       	$mysqlObj=new Mysql;
                        $jpartnerObj=new Jpartner;
                        $myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                        $myDb=$mysqlObj->connect("$myDbName");
                        $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
                        //Get the filter values
                        if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj,$profileid))
                        {
                                //first filter : age
                                if($filterrow["AGE"]=="Y")
                                {
                                        if($jpartnerObj->getLAGE()!="" && $jpartnerObj->getHAGE()!="")
                                        {
                                                $sqlfilter.= " and AGE between '" . $jpartnerObj->getLAGE() . "' and '" . $jpartnerObj->getHAGE() ."' ";                                                                                                   
                                        }
                                }
                                 //second filter : religion
                                if($filterrow["RELIGION"]=="Y")
                                {
                                        $PARTNER_CASTE=$jpartnerObj->getPARTNER_CASTE();
                                        if($PARTNER_CASTE != "" )
                                        {
                                                $sqlfilter.=" and CASTE in ('" . implode("','",get_all_caste($PARTNER_CASTE,1)) . "')";
                                        }
                                }

                        	//third filter : country of residence
                                if($filterrow["COUNTRY_RES"]=="Y")
                                {
                                        $PARTNER_COUNTRYRES=$jpartnerObj->getPARTNER_COUNTRYRES();
                                        if($PARTNER_COUNTRYRES != "")
                                        {
                                                $sqlfilter.=" and COUNTRY_RES in (" . $PARTNER_COUNTRYRES . ")";
                                        }
                                }
                                 //fourth filter : marital status
                                if($filterrow["MSTATUS"]=="Y")
                                {
                                        $FILTER_MSTATUS=$jpartnerObj->getPARTNER_MSTATUS();
                                        if($FILTER_MSTATUS != "")
                                        {
                                                $sqlfilter.=" and MSTATUS in (" . $FILTER_MSTATUS . ")";
                                        }
                                }
                                //fifth filter : community
                                if($filterrow["MTONGUE"]=="Y")
                                {
                                       $PARTNER_MTONGUE=$jpartnerObj->getPARTNER_MTONGUE();
                                       if($PARTNER_MTONGUE != "")
                                       {
                                                $sqlfilter.=" and MTONGUE in (" . $PARTNER_MTONGUE . ")";
                                       }
                                }
	                        //If no filter set
	                        if($sqlfilter==$sqlfilter_temp)
        	                        return true;

				$resfil=mysql_query_decide($sqlfilter) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sqlfilter,"ShowErrTemplate");

                                $finalfilterrow=mysql_fetch_row($resfil);
                                mysql_free_result($resfil);
	                        if($finalfilterrow[0] <= 0)
        	                {
                	                $error_msg="Person has set filters ";
					return false;
			 	}
				else
					return true;
                	}
        	}
		else
			return true;
	}
	else
		return true;
}
//Code ends	
function recent_match_alerts($profileid)
{
        global $smarty;
        global $CONTACTS;   //Variable declared in mainmenu.php .
	connect_slave81();
        /*$sql="SELECT GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
        $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        $row=mysql_fetch_assoc($res);
        if($row["GENDER"]=='M')
                $gender = 'F';
        else
                $gender = 'M';
        mysql_free_result($res);
        $sql="SELECT USER AS RMA FROM matchalerts.LOG A,newjs.JPROFILE B WHERE B.GENDER = '$gender' AND A.USER = B.PROFILEID AND A.RECEIVER ='$profileid' GROUP BY USER ORDER BY DATE DESC";
        */
	$sql="SELECT USER AS RMA FROM matchalerts.LOG USE INDEX(RECEIVER) WHERE RECEIVER ='$profileid' GROUP BY USER ORDER BY DATE DESC";
	$res_con=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        if(mysql_num_rows($res_con)==0)
	{
        	$db=connect_db();	
		return null;
	}
	else
        {
                while($row_con=mysql_fetch_array($res_con))
                        $profileid_arr[]=$row_con['RMA'];
                if(count($profileid_arr)>5)
                {
                        $smarty->assign("cmore",1);
                        for($j=0;$j<5;$j++)
                                $profileid_str.="'".$profileid_arr[$j]."',";
                        $profileid_str=substr($profileid_str, 0, strlen($profileid_str)-1);
                }
                else
                        $profileid_str="'".implode($profileid_arr,"','")."'";
                $db=connect_db();
		if($profileid_str)
                {
   $sql="SELECT USERNAME,PROFILEID,HAVEPHOTO,PHOTO_DISPLAY,if(PRIVACY='' or PRIVACY is null,'A',PRIVACY) as PRIVACY FROM JPROFILE where  activatedKey=1 and PROFILEID IN ($profileid_str)";
                        $result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        while($myrow=mysql_fetch_array($result))
                        {
                                $data_3d[$myrow['USERNAME']]=$myrow['PROFILEID'];
                                //This is required since we have to prevent queries that will run for getting details of the                                    below users     
                                if(is_array($CONTACTS))
                                {
                                        $CONTACTS[$myrow['PROFILEID']]["HAVEPHOTO"]=$myrow['HAVEPHOTO'];
                                        $CONTACTS[$myrow['PROFILEID']]["PHOTO_DISPLAY"]=$myrow['PHOTO_DISPLAY'];
                                        $CONTACTS[$myrow['PROFILEID']]["PRIVACY"]=$myrow['PRIVACY'];
                                }
                        }
                }
                if(is_array($data_3d))
                {
                        foreach($profileid_arr as $profileid)
                        {
                                if(in_array($profileid,$data_3d))
                                {
                                        $key = array_search($profileid,$data_3d);
                                        $contact_rec[$data_3d[$key]]=$key;
                                }
                        }
                        $smarty->assign("contact_rec_cnt",count($contact_rec));
                        return $contact_rec;
                }
                else
                        return null;
        }
}

/*function new_contacts_received($profileid)
{
	global $smarty;
	global $CONTACTS;   //Variable declared in mainmenu.php .

///code MODIFIED  By Tapan Arora for Members Waiting for My Reply and Awaiting Response Archive
                                 $yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));    // To get the time for previous day
                                $back_30_days=date("Y-m-d",$yday);
	$sql_con="SELECT SENDER FROM CONTACTS WHERE RECEIVER='$profileid' AND TYPE='I' AND TIME BETWEEN '$back_30_days 00:00:00' AND NOW() ORDER BY TIME DESC ";
	$res_con=mysql_query_decide($sql_con);
	if(mysql_num_rows($res_con)==0)
		return null;
	else
	{
                while($row_con=mysql_fetch_array($res_con))
                        $profileid_arr[]=$row_con['SENDER'];
                //print_r($profileid_arr);
		if(count($profileid_arr)>5)
		{	
			$smarty->assign("cmore",1);
			for($j=0;$j<5;$j++)
				$profileid_str.="'".$profileid_arr[$j]."',";
			$profileid_str=substr($profileid_str, 0, strlen($profileid_str)-1);
		}
		else
			$profileid_str="'".implode($profileid_arr,"','")."'";
                
		if($profileid_str)
		{	
			$sql="SELECT USERNAME,PROFILEID,HAVEPHOTO,PHOTO_DISPLAY,if(PRIVACY='' or PRIVACY is null,'A',PRIVACY) as PRIVACY FROM JPROFILE where  activatedKey=1 and PROFILEID IN ($profileid_str)";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			while($myrow=mysql_fetch_array($result))
			{
				$data_3d[$myrow['USERNAME']]=$myrow['PROFILEID'];
			
				//This is required since we have to prevent queries that will run for getting details of the 					below users	

				if(is_array($CONTACTS))
				{
					$CONTACTS[$myrow['PROFILEID']]["HAVEPHOTO"]=$myrow['HAVEPHOTO'];
					$CONTACTS[$myrow['PROFILEID']]["PHOTO_DISPLAY"]=$myrow['PHOTO_DISPLAY'];
					$CONTACTS[$myrow['PROFILEID']]["PRIVACY"]=$myrow['PRIVACY'];
 				}				
			}
		}
		
		if(is_array($data_3d))
                {
                        foreach($profileid_arr as $profileid)
                        {
                                if(in_array($profileid,$data_3d))
                                {
                                        $key = array_search($profileid,$data_3d);
                                        $contact_rec[$data_3d[$key]]=$key;
                                }
                        }
                        $smarty->assign("contact_rec_cnt",count($contact_rec));
                        return $contact_rec;
                }
                else
                        return null;
	}
}*/

//Created by lavesh to check if contact no. is invalid.
function bounced_phone($pid)
{       
        $sql = "Select PHONE_FLAG from newjs.JPROFILE where  activatedKey=1 and PROFILEID = '$pid'";
        $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $myrow = mysql_fetch_array($result);
        if($myrow['PHONE_FLAG']=='I')
                return 1;
        return 0;
}
function viewlink($profile)
{
  $sql="Select SHOW_HOROSCOPE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profile'";
  $result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
  $myrow = mysql_fetch_array($result);
  if($myrow['SHOW_HOROSCOPE']=='Y')
  {
    $sql_n="Select count(*) as cnt from ASTRO_DETAILS where PROFILEID='$profile'";
    $result_n= mysql_query_decide($sql_n) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
    $myrow_n= mysql_fetch_array($result_n);
    if($myrow_n['cnt']>0)
         return 1;
    else
         return 0;
  }
  else
   return 0;
}
if(!function_exists('compatibility_link'))
{
function compatibility_link($my_profileid,$result_profileid)
{
	global $smarty;
		
	//to check if the other person has filled his ASTRO DETAILS in ASTRO_DETAILS table
        $sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$result_profileid'";
        $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row = mysql_fetch_array($result);
	
	//check if the other person has filled his ASTRO DETAILS
	//if Yes then show the link of Check Compatibility
	if($row['MOON_DEGREES_FULL']&&$row['MARS_DEGREES_FULL']&&$row['VENUS_DEGREES_FULL']&&$row['LAGNA_DEGREES_FULL'])
	{
		$compatibility_link="C";
	}
	//if NOT
	else
	{
		//check if the person has choosen or not to receive any more horoscope requests 
		//if not then show request horoscope link else dnt show any link
		//if(!viewlink($result_profileid))
		if(!check_astro_details($result_profileid))
		{
			$compatibility_link="R";
		}
	}
	return $compatibility_link;
}
}
function self_astro_details($profileid,$viewer_viewed="")
{
	global $smarty , $jprofile_result,$ASTRO_DETAILS;

	//If ASTRO details are already filled, then no need to run the query.
	if(!isset($ASTRO_DETAILS))
	{

	        $sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($result);
	}
	else
		$row=$ASTRO_DETAILS;

	//check if the person has filled his ASTRO DETAILS
	if($row['MOON_DEGREES_FULL']&&$row['MARS_DEGREES_FULL']&&$row['VENUS_DEGREES_FULL']&&$row['LAGNA_DEGREES_FULL'])
	{
		$astro_details=1;
	}
	else //if NO then show him to fill his a button Astro Details and a button which takes him to PAYMENT PAGE
	{
		$astro_details=0;
	}

	//check if the person has taken COMPATIBILITY SUBSCRIPTION or not
	//if NOT then show him the button which takes him to PAYMENT PAGE

	//added by lavesh on 9 aug. If jprofile_result global variable is prevent , query on JPROFILE is saved.
	if($viewer_viewed=='viewer')
	{
		if(strstr($jprofile_result["viewer"]["SUBSCRIPTION"],'A'))
			$compatibility_subscription=1;
		else
			$compatibility_subscription=0;
	}
	elseif(in_array('A',get_rights($profileid)))
		$compatibility_subscription=1;
	else
		$compatibility_subscription=0;
		
	$smarty->assign("ASTRO_DETAILS",$astro_details);
	$smarty->assign("COMPATIBILITY_SUBSCRIPTION",$compatibility_subscription);
}

//function to check weather a user has filled the astro details.
//this function returns true if user has filled his astro details i.e entry exists in ASTRO_DETAILS table.
if(!function_exists('check_astro_details'))
{
function check_astro_details($profileid,$show_horoscope="abc")
{
	global $SHOW_HOROSCOPE;  //variable declared in mainmenu.php, used to prevent the query to run unnecessary.
	global $HOROSCOPE_ARRAY;
	global $data;

	if(!isset($SHOW_HOROSCOPE))
	{
		if($show_horoscope=="abc")
		{
	                global $jprofile_result;
	                //added by lavesh on 9 aug. If jprofile_result global variable is prevent , query on JPROFILE is saved.
        	        if(is_array($jprofile_result))
                	        $show_horoscope=$jprofile_result["viewed"]["SHOW_HOROSCOPE"];
                	else
                	{

				$sql_jp = "SELECT SHOW_HOROSCOPE FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID = '$profileid'";
				$res_jp = mysql_query_optimizer($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
				$row_jp = mysql_fetch_array($res_jp);
				$show_horoscope=$row_jp['SHOW_HOROSCOPE'];
			}
		}
	}
	else
		$show_horoscope=$SHOW_HOROSCOPE;

	if(is_array($show_horoscope))
	{
		$profileid.=",".$data["PROFILEID"];
		$show_horoscope[$data["PROFILEID"]]=array("SHOW_HOROSCOPE"=>"Y","GENDER"=>$data["GENDER"]);
		$profileid_arr=explode(",",$profileid);
		for($i=0;$i<count($profileid_arr);$i++)
		{
			if($show_horoscope[$profileid_arr[$i]]["SHOW_HOROSCOPE"]=="Y")
				$profileid_str.="'".$profileid_arr[$i]."',";
		}
		$profileid_str=substr($profileid_str,0,strlen($profileid_str)-1);
		$profileid_str = implode(",",array_diff(explode(",",$profileid_str), [0]));
		if($profileid_str)
		{
			$sql_ast_det="SELECT PROFILEID,LAGNA_DEGREES_FULL,SUN_DEGREES_FULL,MOON_DEGREES_FULL,MARS_DEGREES_FULL,MERCURY_DEGREES_FULL,JUPITER_DEGREES_FULL,VENUS_DEGREES_FULL,SATURN_DEGREES_FULL FROM newjs.ASTRO_DETAILS WHERE PROFILEID IN ($profileid_str)";
			$res_ast_det=mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql_ast_det,"ShowErrTemplate");
			while($row_ast_det=mysql_fetch_array($res_ast_det))
			{
				$gender_value='';
				$lagna=$row_ast_det['LAGNA_DEGREES_FULL'];
				$sun=$row_ast_det['SUN_DEGREES_FULL'];
				$mo=$row_ast_det['MOON_DEGREES_FULL'];
				$ma=$row_ast_det['MARS_DEGREES_FULL'];
				$me=$row_ast_det['MERCURY_DEGREES_FULL'];
				$ju=$row_ast_det['JUPITER_DEGREES_FULL'];
				$ve=$row_ast_det['VENUS_DEGREES_FULL'];
				$sa=$row_ast_det['SATURN_DEGREES_FULL'];
				$gender=$show_horoscope[$row_ast_det["PROFILEID"]]["GENDER"];
				if($gender != "")
				{
					if($gender == 'M')
						$gender_value = 1;
					else
						$gender_value = 2;
				}
				else
					$gender_value = "2";

				$HOROSCOPE_ARRAY[$row_ast_det["PROFILEID"]]=array("HOROSCOPE"=>"Y","ASTRO_DETAILS"=>"$row_ast_det[PROFILEID]:$gender_value:$lagna:$sun:$mo:$ma:$me:$ju:$ve:$sa");
			}
		}
		/*$profileid_str='';
		foreach($profileid_arr as $key=>$value)
		{
			if(!array_key_exists($value,$HOROSCOPE_ARRAY) && $show_horoscope[$value]["SHOW_HOROSCOPE"]=="Y")
				$profileid_str.="'".$value."',";
		}
		$profileid_str=substr($profileid_str,0,strlen($profileid_str)-1);
		if($profileid_str)
		{
			$sql_ast_det="SELECT PROFILEID FROM newjs.HOROSCOPE WHERE PROFILEID IN($profileid_str)";
			$res_ast_det=mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ast_det,"ShowErrTemplate");
			while($row_ast_det=mysql_fetch_array($res_ast_det))
			{
				$HOROSCOPE_ARRAY[$row_ast_det["PROFILEID"]]=array("HOROSCOPE"=>"Y","ASTRO_DETAILS"=>'');
			}
		}*/
		foreach($profileid_arr as $key=>$value)
                {
                        if(!array_key_exists($value,$HOROSCOPE_ARRAY))
				$HOROSCOPE_ARRAY[$value]=array("HOROSCOPE"=>"N","ASTRO_DETAILS"=>'');
                }
	}
	else
	if($show_horoscope == 'Y')
	{
        $astroLib = ProfileAstro::getInstance();
        $result = $astroLib->getIfAstroDetailsPresent($profileid);
//		$sql_ast_det = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID = '$profileid'";
//		$res_ast_det = mysql_query_optimizer($sql_ast_det) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ast_det,"ShowErrTemplate");
//		$row_ast_det = mysql_fetch_array($res_ast_det);
		if(intval($result) > 0)
			return true;
		else
		{
			/*$sql_ast_det1 = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE WHERE PROFILEID = '$profileid'";
                        $res_ast_det1 = mysql_query_optimizer($sql_ast_det1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ast_det1,"ShowErrTemplate");
                        $row_ast_det1 = mysql_fetch_array($res_ast_det1);
                        if($row_ast_det1['COUNT'] > 0)
                                return true;
                        else*/
                                return false;
		}
		
	}
	else
		return false;
}
}
//added by sriram for contact search.
//this function is used to find the dropdown values fora particular user depending on his/her awaiting/decline profiles.
//this function does not return anything.
//function search_pending_records($self_profileid,$flag,$type="R",$searched_caste,$searched_religion,$searched_lage,$searched_hage,$searched_mstatus,$searched_mtongue,$searched_city,$searched_havephoto)
function search_pending_records($pageDetail)
{
	global $sql_in;
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
	global $smarty;
	global $MSTATUS;
	global $profileTypeArray;
	global $useSlave;
	$count = 0;
	$photo_count = 0;
	$self_profileid = $pageDetail["self_profileid"];	
	$flag = $pageDetail["flag"];
	$type = $pageDetail["type"];
	$searched_caste = $pageDetail["caste"];
	$searched_religion = $pageDetail["religion"];
	$searched_lage = $pageDetail["lage"];
	$searched_hage = $pageDetail["hage"];
	$searched_mstatus = $pageDetail["mstatus"];
	$searched_mtongue = $pageDetail["mtongue"];
	$searched_city = $pageDetail["city_Res"];
	$searched_havephoto = $pageDetail["havephoto"];
	$ALLOW_PROFILES = $pageDetail["ALLOW_PROFILES"];
	$city = array();
	$city_arr = array();
	/********Not needed
	if($flag=="IB")
	{
		foreach($ALLOW_PROFILES as $key=>$value)
		{
			$profileid_str.="'".$value["PROFILEID"]."',";
		}
		$profileid_str=trim($profileid_str,",");
		$profileid_str=trim($profileid_str,"'");
	}
	else
	{
	****/

		if(is_array($ALLOW_PROFILES))	
			$profileid_str=implode("','",$ALLOW_PROFILES);	
//	}
	if($profileid_str)
	{
		$sql_jp = "SELECT AGE, HEIGHT, MTONGUE,CASTE, CITY_RES, MSTATUS, HAVEPHOTO, RELIGION FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID IN ('$profileid_str')";
		if($useSlave)
		{
			$dbslave=connect_slave();
			$res_jp=mysql_query($sql_jp,$dbslave) or die("Error while fetching profile details  ".mysql_error($dbslave));
			$db=connect_db();
		}
		else
			$res_jp = mysql_query_decide($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
			$i=0;
		while($row_jp = mysql_fetch_array($res_jp))
		{
			$count++;
			if(!@in_array($row_jp['AGE'],$age))
				$age[] = $row_jp['AGE'];

			if(!@in_array($row_jp['HEIGHT'],$height))
				$height[] = $row_jp['HEIGHT'];

			if(!@in_array($row_jp['MTONGUE'],$mtongue))
				$mtongue[] = $row_jp['MTONGUE'];

			if(!@in_array($row_jp['CASTE'],$caste))
				$caste[] = $row_jp['CASTE'];

			if(!@in_array($row_jp['CITY_RES'],$city))
				$city[] = $row_jp['CITY_RES'];

			if(!@in_array($row_jp['MSTATUS'],$mstatus))
				$mstatus[] = $row_jp['MSTATUS'];
		
			 if(!@in_array($row_jp['RELIGION'],$religion))
				$religion[] = $row_jp['RELIGION'];

			if($row_jp['HAVEPHOTO'] == "Y")
				$photo_count++;
		}
	}
	/*if($photo_count == $count)
		$smarty->assign("DISABLE_PHOTO_CHECKED","Y");
	elseif($photo_count == 0)
		$smarty->assign("DISABLE_PHOTO_UNCHECKED","Y");*/
	if(is_array($pageDetail["LEADS"]) && count($pageDetail["LEADS"]))
        {
                include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/display_common.php");
                $resultSet=array();
                $db=connect_db();
                $resultSet=get_lead_details_all($resultSet,$pageDetail["LEADS"]);
                foreach($resultSet as $key=>$value)
                {
                        if($value['AGE'] && !@in_array($value['AGE'],$age))
                                $age[] = $value['AGE'];

                        if($value['MTONGUE_VAL'] && !@in_array($value['MTONGUE_VAL'],$mtongue))
                                $mtongue[] = $value['MTONGUE_VAL'];

                        if($value['CASTE_VAL'] && !@in_array($value['CASTE_VAL'],$caste))
                                $caste[] = $value['CASTE_VAL'];

                        if($value['CITY_VAL'] && !@in_array($value['CITY_VAL'],$city))
                                $city[] = $value['CITY_VAL'];

                        if($value['MSTATUS_VAL'] && !@in_array($value['MSTATUS_VAL'],$mstatus))
                                $mstatus[] = $value['MSTATUS_VAL'];
                }
        }
	if(is_array($age))
	{
		$lage = min($age);
		$hage = max($age);
	}
	unset($age);
	for($i=$lage;$i<=$hage;$i++)
		$age_arr[]=$i;

	/*if($lage==$hage)
		$smarty->assign("DISABLE_AGE","Y");*/
	if(is_array($height))
	{
		$lheight = min($height);
		$hheight = max($height);
	}
	unset($height);
	$i=0;
	$sql_ht = "SELECT VALUE, LABEL FROM newjs.HEIGHT WHERE VALUE BETWEEN '$lheight' AND '$hheight'";
	$res_ht = mysql_query_decide($sql_ht) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ht,"ShowErrTemplate");
	while($row_ht = mysql_fetch_array($res_ht))
	{
		$height_arr[$i]['VALUE'] = $row_ht['VALUE'];
		$pos = strpos($row_ht['LABEL'],"(");
		$height_arr[$i]['LABEL'] = substr($row_ht['LABEL'],0,$pos);
		$i++;
	}

	/*if($lheight==$hheight)
		$smarty->assign("DISABLE_HEIGHT","Y");

	if(count($mtongue)==1)
		$smarty->assign("DISABLE_MTONGUE","Y");*/

	if(is_array($mtongue))
	{
		$mtongue_str = "'".@implode("','",$mtongue)."'";
		unset($mtongue);
		$i=0;
		$sql_mt = "SELECT DISTINCT(VALUE),SMALL_LABEL AS LABEL FROM newjs.MTONGUE WHERE VALUE IN ($mtongue_str) ORDER BY SORTBY";
		$res_mt = mysql_query_decide($sql_mt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mt,"ShowErrTemplate");
		while($row_mt = mysql_fetch_array($res_mt))
		{
			if($searched_mtongue == $row_mt['VALUE'])
				$searched_mtongue_label = $row_mt['LABEL'];
			$mtongue_arr[$i]['VALUE'] = $row_mt['VALUE'];
			$mtongue_arr[$i]['LABEL'] = $row_mt['LABEL'];
			$i++;
		}
	}

	/*if(count($religion)==1)
		$smarty->assign("DISABLE_RELIGION","Y");*/

	if(is_array($religion))
	{
		$religion_str = "'".@implode("','",$religion)."'";
		unset($religion);
		$i=0;
		$sql_mt = "SELECT DISTINCT(VALUE),LABEL FROM newjs.RELIGION WHERE VALUE IN ($religion_str) ORDER BY SORTBY";
		$res_mt = mysql_query_decide($sql_mt) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mt,"ShowErrTemplate");
		while($row_mt = mysql_fetch_array($res_mt))
		{
			if($searched_religion == $row_mt['VALUE'])
				$searched_religion_label = $row_mt['LABEL'];
			$religion_arr[$i]['VALUE'] = $row_mt['VALUE'];
			$religion_arr[$i]['LABEL'] = $row_mt['LABEL'];
			$i++;
		}
	}

	/*if(count($caste)==1)
		$smarty->assign("DISABLE_CASTE","Y");*/

	//$caste_str = "'".@implode("','",$caste)."'";
	if(is_array($caste))
	{
		$caste_str = implode(",",$caste);
		$i=0;
		$sql_cs = "SELECT DISTINCT(VALUE), LABEL FROM newjs.CASTE WHERE VALUE IN ($caste_str) ORDER BY SORTBY";
		$res_cs = mysql_query_decide($sql_cs) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cs,"ShowErrTemplate");
		while($row_cs = mysql_fetch_array($res_cs))
		{
			if($searched_caste == $row_cs["VALUE"])
				$searched_caste_label = $row_cs["LABEL"];
			$caste_label = explode(": ",$row_cs['LABEL']);
			if($caste_label[1] != "")
				$option_str .= "<option value=\"$row_cs[VALUE]\">-$caste_label[1]</option>";
			else
				$option_str .= "<option value=\"$row_cs[VALUE]\" style=\"font-weight: bold; color: rgb(0, 0, 0);\">$row_cs[LABEL]</option>";

			$caste_arr[$i]['VALUE'] = $row_cs['VALUE'];
			$caste_arr[$i]['LABEL'] = $row_cs['LABEL'];
			$i++;
		}
		$smarty->assign("CASTE_ARR",$option_str);
		$smarty->assign("CASTE_STR",$caste_str);
		unset($caste);
	}

	global $CITY_DROP;
	$i = 0;
	if(is_array($city))
	{
		foreach($city as $key=>$val)
		{
			$city_label = $CITY_DROP[$val];
			if($city_label)
			{
				if($searched_city == $val)
					$searched_city_label = $city_label;
				$city_arr[$i]['LABEL'] = $city_label;		
				$city_arr[$i]['VALUE'] = $val;
				$i++;
			}
		}
		unset($city);
		sort($city_arr);
	}
	/*if(count($city)==1)
		$smarty->assign("DISABLE_CITY","Y");*/

	/*$city_str = "'".@implode("','",$city)."'";
	unset($city);
	$i=0;
	$sql_cti = "SELECT DISTINCT(VALUE), LABEL FROM newjs.CITY_NEW WHERE VALUE IN ($city_str) ORDER BY SORTBY";
	$res_cti = mysql_query_decide($sql_cti) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cti,"ShowErrTemplate");
	while($row_cti = mysql_fetch_array($res_cti))
	{
		if($searched_city == $row_cti['VALUE'])
			$searched_city_label = $row_cti['LABEL'];
		$city_i_arr[$i]['VALUE'] = $row_cti['VALUE'];
		$city_i_arr[$i]['LABEL'] = $row_cti['LABEL'];
		$i++;
	}

	$i=0;
	$sql_ctu = "SELECT DISTINCT(VALUE), LABEL FROM newjs.CITY_NEW WHERE VALUE IN ($city_str) ORDER BY SORTBY";
	$res_ctu = mysql_query_decide($sql_ctu) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ctu,"ShowErrTemplate");
	while($row_ctu = mysql_fetch_array($res_ctu))
	{
                if($searched_city == $row_ctu['VALUE'])
                        $searched_city_label = $row_ctu['LABEL'];
		$city_u_arr[$i]['VALUE'] = $row_ctu['VALUE'];
		$city_u_arr[$i]['LABEL'] = $row_ctu['LABEL'];
		$i++;
	}*/

	if(is_array($mstatus))
	{
		$mstatus_count = count($mstatus);
		/*if($mstatus_count == 1)
			$smarty->assign("DISABLE_MSTATUS","Y");*/

		for($i=0;$i<$mstatus_count;$i++)
		{
			if($searched_mstatus == $mstatus[$i])
				$searched_mstatus_label = $MSTATUS[$mstatus[$i]];
			$mstatus_arr[$i]['VALUE'] = $mstatus[$i];
			$mstatus_arr[$i]['LABEL'] = $MSTATUS[$mstatus[$i]];
		}
	}

	$search_string = array();
	if($searched_religion)
		$search_string[] = $searched_religion_label;
	if($searched_caste)
		$search_string[] = $searched_caste_label;
	if($searched_mtongue)
		$search_string[] = $searched_mtongue_label;
	if($searched_mstatus)
		$search_string[] = $searched_mstatus_label;
	if($searched_lage || $searched_hage)
		$search_string[] = $searched_lage."-".$searched_hage;
	if($searched_city)
		$search_string[] = $searched_city_label;
	if($searched_havephoto == "Y")
		$search_string[] = "with photo";
	if($pageDetail["match_type"])
                $search_string[]=$profileTypeArray[$pageDetail["match_type"]];
	if($search_string)
		$search_string_display = implode(", ",$search_string);
		
	$smarty->assign("search_string",$search_string_display);
	$smarty->assign("MIN_AGE",$lage);
	$smarty->assign("MAX_AGE",$hage);
	$smarty->assign("MIN_HEIGHT",$lheight);
	$smarty->assign("MAX_HEIGHT",$hheight);
	$smarty->assign("age_arr",$age_arr);
	$smarty->assign("height_arr",$height_arr);
	$smarty->assign("mtongue_arr",$mtongue_arr);
	$smarty->assign("mtongue_str",$mtongue_str);
	$smarty->assign("caste_arr",$caste_arr);
	$smarty->assign("city_arr",$city_arr);
	//$smarty->assign("city_u_arr",$city_u_arr);
	$smarty->assign("mstatus_arr",$mstatus_arr);
	$smarty->assign("religion_arr",$religion_arr);
	$smarty->assign("profileid_str",$profileid_str);
}

//added by sriram for contact search.
//this function is used to search for profiles depending on the search criteria selected.
//this function returns a result resource.
function search_pending_query($profileid_str,$lage,$hage,$lheight,$hheight,$mtongue,$City_Res,$caste,$mstatus, $havephoto,$income="",$occupation="",$education="",$relation="",$diet="",$manglik="",$special_search="",$from_cluster="",$j="",$PAGELEN="",$MIN_AGE="",$MAX_AGE="",$MIN_HEIGHT="",$MAX_HEIGHT="")
{
	global $smarty;

	if($from_cluster)
		$select_values = "DISTINCT(JPROFILE.PROFILEID), INCOME, EDU_LEVEL_NEW, OCCUPATION, RELATION, MANGLIK, DIET, COUNTRY_RES, COUNTRY_BIRTH, SUBSCRIPTION";
	else
		$select_values = "JPROFILE.PROFILEID ";

	$sql_drop = "DROP TABLE newjs.ORDERING_TEMP";
	@mysql_query_decide($sql_drop);

	$sql_temp = "CREATE TEMPORARY TABLE newjs.ORDERING_TEMP(`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `PROFILEID` INT(11))";
	mysql_query_decide($sql_temp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_temp,"ShowErrTemplate");

	$temp_profileid = explode("','",ltrim(rtrim($profileid_str,"'"),"'"));
	for($i=0;$i<count($temp_profileid);$i++)
		$ins_profileid_str .= "('".$temp_profileid[$i]."'),";

	//inserting into temp table to store the order of profileid's.
	$sql_ins = "INSERT INTO newjs.ORDERING_TEMP(PROFILEID) VALUES".rtrim($ins_profileid_str,",");
	mysql_query_decide($sql_ins) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins,"ShowErrTemplate");

	//building search query depending on search criteria selected from provided search band.
	$sql_search = "SELECT SQL_CALC_FOUND_ROWS $select_values FROM newjs.JPROFILE, newjs.ORDERING_TEMP WHERE JPROFILE.PROFILEID IN ($profileid_str) AND JPROFILE.PROFILEID=ORDERING_TEMP.PROFILEID  and JPROFILE.activatedKey=1";

	if($lage && $hage && ($lage != $MIN_AGE || $hage != $MAX_AGE))
		$sql_search .= " AND AGE BETWEEN '".addslashes(stripslashes($lage))."' AND '".addslashes(stripslashes($hage))."'";

        if($lheight && $hheight && ($lheight != $MIN_HEIGHT || $hheight != $MAX_HEIGHT))
                $sql_search .= " AND HEIGHT BETWEEN '".addslashes(stripslashes($lheight))."' AND '".addslashes(stripslashes($hheight))."'";

        if($mtongue)
                $sql_search .= " AND MTONGUE ='".addslashes(stripslashes($mtongue))."'";

        if($City_Res)
                $sql_search .= " AND CITY_RES='".addslashes(stripslashes($City_Res))."'";

        if($caste)
	{
		//REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		$sql_group = "SELECT VALUE FROM newjs.CASTE WHERE VALUE='".addslashes(stripslashes($caste))."' AND ISGROUP='Y'";
		//REVAMP JS_DB_CASTE
		$res_group = mysql_query_decide($sql_group) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_group,"ShowErrTemplate");
		if($row_group = mysql_fetch_array($res_group))
		{
			//REVAMP JS_DB_CASTE
			$caste_group_str = $CASTE_GROUP_ARRAY[$row_group["VALUE"]];
			//REVAMP JS_DB_CASTE

			$sql_search .= " AND CASTE IN ('$caste_group_str')";
		}
		else
			$sql_search .= " AND CASTE='".addslashes(stripslashes($caste))."'";
	}

        if($mstatus)
                $sql_search .= " AND MSTATUS='".addslashes(stripslashes($mstatus))."'";

        if($havephoto)
                $sql_search .= " AND HAVEPHOTO='Y'";

	if($income && $income != "All")
	{
		$sql_inc_map = "SELECT OLD_VALUE FROM newjs.INCOME_CLUSTER WHERE VALUE='$income'";
		$res_inc_map = mysql_query_decide($sql_inc_map) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_inc_map,"ShowErrTemplate");
		$row_inc_map = mysql_fetch_array($res_inc_map);
		$income_map = $row_inc_map['OLD_VALUE'];

		$income_arr = explode(",",addslashes(stripslashes($income_map)));
		if(is_array($income_arr))
		{
			$income_str = "'".implode("','",$income_arr)."'";
			$sql_search .= " AND INCOME IN ($income_str)";
		}
		else
			$sql_search .= " AND INCOME='".addslashes(stripslashes($income_map))."'";
	}

	if($occupation && $occupation != "All")
	{
		mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com","profile/connect_functions.inc called OCCUPATION_CLUSTER","profile/connect_functions.inc called OCCUPATION_CLUSTER");
	}

	if($education && $education != "All")
	{
		mail("lavesh.rawat@jeevansathi.com,kumar.anand@jeevansathi.com","profile/connect_functions.inc called EDUCATION_LEVEL_NEW_CLUSTER","profile/connect_functions.inc called EDUCATION_LEVEL_NEW_CLUSTER");
	}

        if($relation)
	{
		$rel_arr = explode(",",$relation);
		if(count($rel_arr) > 1)
		{
			$relation = implode("','",$rel_arr);
			$sql_search .= " AND RELATION IN ('$relation')";
		}
		else
			$sql_search .= " AND RELATION='".addslashes(stripslashes($relation))."'";
	}

        if($diet)
                $sql_search .= " AND DIET='".addslashes(stripslashes($diet))."'";

        if($manglik)
                $sql_search .= " AND MANGLIK='".addslashes(stripslashes($manglik))."'";

	if($special_search)
	{
		if($special_search=="nri")
			$sql_search .= " AND COUNTRY_BIRTH='51' AND COUNTRY_RES != '51'";
		elseif($special_search=="e-class")
			$sql_search .= " AND SUBSCRIPTION REGEXP 'D'";
		elseif($special_search=="cosmo")
		{
			//$sql_cosmo = "SELECT PROFILEID FROM newjs.JPARTNER AS j LEFT JOIN newjs.PARTNER_CASTE AS pc ON  j.PARTNERID=pc.PARTNERID WHERE j.PROFILEID IN ($profileid_str) AND pc.CASTE IS NULL";
			$sql_cosmo = "SELECT PROFILEID FROM newjs.COSMO WHERE PROFILEID IN ($profileid_str)";
			$res_cosmo = mysql_query_decide($sql_cosmo) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cosmo,"ShowErrTemplate");
			while($row_cosmo = mysql_fetch_array($res_cosmo))
				$cosmo_str .= "'".$row_cosmo['PROFILEID']."',";

			$cosmo_str = rtrim($cosmo_str,",");

			if($cosmo_str)
				$sql_search .= " AND JPROFILE.PROFILEID IN ($cosmo_str)";
		}
		elseif($special_search="online")
			$sql_search .= " AND JPROFILE.PROFILEID IN (SELECT DISTINCT(userID) FROM userplane.users WHERE 1)";
	}

	if($sql_search && $PAGELEN)
		$sql_search .= " ORDER BY ORDERING_TEMP.ID LIMIT $j, $PAGELEN";


//echo $sql_search;//die;
//echo $relation."--".$diet."---".$manglik."---".$special_search;

        $res_search = mysql_query_decide($sql_search) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_search,"ShowErrTemplate");

	$smarty->assign("INCOME_VAL",$income);
	$smarty->assign("OCCUPATION_VAL",$occupation);
	$smarty->assign("EDUCATION_VAL",$education);
	$smarty->assign("RELATION_VAL",$relation);
	$smarty->assign("DIET_VAL",$diet);
	$smarty->assign("MANGLIK_VAL",$manglik);
	$smarty->assign("SPECIAL_SEARCH_VAL",$special_search);

	return $res_search;
}

//added by sriram for contact search.
//this function is used to find the profiles who have initiated interest to the logged in user and the users who have been declined by the declined user.
//this function returns a profileid string.
function awaiting_decline_profiles($self_profileid,$include_declines,$with_without_message="",$flag="",$type="R")
{
	global $smarty,$arch,$contacted_str;

	$original_flag = $flag;

	if($include_declines)
		$flag = "'I','D'";
///code MODIFIED  By Tapan Arora for Members Waiting for My Reply and Awaiting Response Archive
	 $yday=mktime(0,0,0,date("m"),date("d")-30,date("Y"));    // To get the time for previous day
	$back_30_days=date("Y-m-d",$yday);
	$b31=mktime(0,0,0,date("m"),date("d")-31,date("Y"));
	$back_31_days=date("Y-m-d",$b31);
	$b90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
	$back_90_days=date("Y-m-d",$b90);

	//finding initated contacts with messages.
	if($with_without_message!="view_all" && $with_without_message != "")
	{
		if(($original_flag=="D" || ($original_flag=="I" && ($type=="R" || $type=="RC" ))) && $include_declines)
		{
			$sql_conts_d = "SELECT SENDER FROM newjs.CONTACTS WHERE TYPE='D' AND RECEIVER = '$self_profileid'";
			$res_conts = mysql_query_decide($sql_conts_d) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_conts_d,"ShowErrTemplate");
			while($row_conts_d = mysql_fetch_array($res_conts_d))
				$profileid_sen_arr[] = $row_conts_d['SENDER'];

			if(is_array($profileid_sen_arr))
				$profileid_sen_str = implode(",",$profileid_sen_arr);

			if($profileid_sen_str != "")
			{
				$sql_D = "SELECT DISTINCT(SENDER) FROM newjs.MESSAGE_LOG WHERE TYPE='D' AND RECEIVER='$self_profileid' AND IS_MSG='Y' AND SENDER IN ($profileid_sen_str)";
				$res_D = mysql_query_decide($sql_D,"",1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_D,"ShowErrTemplate");
				while($row_D = mysql_fetch_array($res_D))
					$profileid_arr[] = $row_D['SENDER'];
			}

			unset($profileid_sen_arr);
			unset($profileid_sen_str);

			$sql_conts_i = "SELECT SENDER FROM newjs.CONTACTS WHERE TYPE='I' AND RECEIVER = '$self_profileid'";
			if($type=="RC")
				$sql_conts_i .= " AND TIME BETWEEN '$back_90_days 00:00:00' AND '$back_31_days 23:59:59'";
			else
				$sql_conts_i .= " AND TIME BETWEEN '$back_30_days 00:00:00' AND NOW())";

			$res_conts_i = mysql_query_decide($sql_conts_i) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_conts_i,"ShowErrTemplate");
			while($row_conts_i = mysql_fetch_array($res_conts_i))
				$profileid_sen_arr[] = $row_conts_i['SENDER'];

			if(is_array($profileid_sen_arr))
				$profileid_sen_str = implode(",",$profileid_sen_arr);

			if($profileid_sen_str != "")
                        {
				$sql_I= "SELECT DISTINCT(SENDER) FROM newjs.MESSAGE_LOG WHERE TYPE='I' AND RECEIVER='$self_profileid' AND IS_MSG='Y' AND SENDER IN ($profileid_sen_arr)";
				$res_I = mysql_query_decide($sql_I,"",1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_I,"ShowErrTemplate");
				while($row_I = mysql_fetch_array($res_I))
					$profileid_arr[] = $row_I['SENDER'];
			}
		}
		else
		{
			$sql_con_split = "SELECT SENDER FROM newjs.CONTACTS WHERE";
			if($include_declines)
                                $sql_con_split .= " TYPE IN ($flag)";
                        else
                                $sql_con_split .= " TYPE='$flag'";

			if($type=="RC")
                                $sql_con_split .= " AND RECEIVER = '$self_profileid' AND TIME BETWEEN '$back_90_days 00:00:00' AND '$back_31_days 23:59:59'";
                        elseif($type=="R" && $flag=="I")
                                $sql_con_split .= " AND RECEIVER = '$self_profileid'  AND TIME BETWEEN '$back_30_days 00:00:00' AND NOW() ";
                        else
                                $sql_con_split .= " AND RECEIVER = '$self_profileid'";

			$res_con_split = mysql_query_decide($sql_con_split) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_con_split,"ShowErrTemplate");
			while($row_con_split = mysql_fetch_array($res_con_split))
				$profileid_sen_arr[] = $row_con_split['SENDER'];

			if(is_array($profileid_sen_arr))
				$profileid_sen_str = implode(",",$profileid_sen_arr);

			if($profileid_sen_str)
			{
				$sql = "SELECT DISTINCT(SENDER) FROM newjs.MESSAGE_LOG WHERE";
				if($include_declines)
					$sql .= " TYPE IN ($flag)";
				else
					$sql .= " TYPE='$flag'";

				$sql .= " AND RECEIVER='$self_profileid' AND IS_MSG='Y' AND SENDER IN ($profileid_sen_str)";

				$res = mysql_query_decide($sql,"",1) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				while($row = mysql_fetch_array($res))
					$profileid_arr[] = $row['SENDER'];
			}
//echo $sql;die;
		}
		$profileid_msg_str = "'".@implode("','",$profileid_arr)."'";
	}

	//finding who have initiated intrest or have been declined by logged in person.
	if(($original_flag=="D" || ($original_flag=="I" && ($type=="R" || $type=="RC" ))) && $include_declines)
	{	
		$sql_D = "SELECT SENDER,TIME FROM newjs.CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE ='D'";
		if($type=="RC")
			$sql_I = "SELECT SENDER,TIME FROM newjs.CONTACTS WHERE RECEIVER='$self_profileid' AND SENDER IN('$arch')";
		else
			$sql_I = "SELECT SENDER,TIME FROM newjs.CONTACTS WHERE RECEIVER='$self_profileid' AND SENDER IN('$contacted_str')";
		if($with_without_message=="with_message" && $profileid_msg_str)
		{
			$sql_D .= "  AND SENDER IN($profileid_msg_str)";
			$sql_I .= "  AND SENDER IN($profileid_msg_str)";
			$heading = "Members Who Have Sent Messages";
		}
		elseif($with_without_message=="without_message"  && $profileid_msg_str)
                {
			$sql_D .= "  AND SENDER NOT IN($profileid_msg_str)";
			$sql_I .=" AND SENDER NOT IN($profileid_msg_str)";

                        $heading = "Members Who Have Expressed Interest";
                }
		else
		{
			$heading = "Members Declined By You";
		}
		 $sql_D .= " ORDER BY TIME DESC";
//echo $sql;
                $res_D = mysql_query_decide($sql_D) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_D,"ShowErrTemplate");
                unset($profileid_arr);
                while($row_D = mysql_fetch_array($res_D))
                {
                        $profileid_arr[] = $row_D['SENDER'];

                        $TOI = $row_D['TIME'];
                        $year=substr($TOI,0,4);
                        $month=substr($TOI,5,2);
                        $day=substr($TOI,8,2);
                        $TOI=my_format_date($day,$month,$year,2);

                        $TIME_ARR[$row_D['SENDER']]['TIME'] = $TOI;
                }
		 $sql_I .= " ORDER BY TIME DESC";
//echo $sql;
                $res_I = mysql_query_decide($sql_I) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_I,"ShowErrTemplate");
                while($row_I = mysql_fetch_array($res_I))
                {
                        $profileid_arr[] = $row_I['SENDER'];

                        $TOI = $row_I['TIME'];
                        $year=substr($TOI,0,4);
                        $month=substr($TOI,5,2);
                        $day=substr($TOI,8,2);
                        $TOI=my_format_date($day,$month,$year,2);

                        $TIME_ARR[$row_I['SENDER']]['TIME'] = $TOI;
                }


			
	}
	else
	{	 
        	if($include_declines)
               		$sql = "SELECT SENDER,TIME FROM newjs.CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE IN ($flag)";
        	else
                	$sql = "SELECT SENDER,TIME FROM newjs.CONTACTS WHERE RECEIVER='$self_profileid' AND TYPE ='$flag'";

		if($with_without_message=="with_message" && $profileid_msg_str)
		{
			if($type=="RC")
				$sql .= "  SENDER IN('$arch') AND  SENDER IN($profileid_msg_str)";
			elseif($type=="R" && $flag=="I")
				$sql .= "  AND SENDER IN('$contacted_str') AND SENDER IN($profileid_msg_str)";
			else
				$sql .= "  AND SENDER IN($profileid_msg_str)";

			$heading = "Members Who Have Sent Messages";

		}
		elseif($with_without_message=="without_message"  && $profileid_msg_str)
		{
			if($type=="RC")
				$sql .= "  AND SENDER IN('$arch') AND SENDER NOT IN($profileid_msg_str)";
			elseif($type=="R" && $flag=="I")
			
				$sql .= "  AND SENDER IN('$contacted_str') AND SENDER NOT IN($profileid_msg_str)";
			else
				$sql .= "  AND SENDER NOT IN($profileid_msg_str)";

			$heading = "Members Who Have Expressed Interest";
		}
		else
		{
			if($type=="RC")
				$sql.="  AND SENDER IN('$arch') ";
			elseif($type=="R" && $flag=="I")
				$sql.="  AND SENDER IN('$contacted_str')";
		
			
			if($original_flag=="D")
				$heading = "Members Declined By You";
			else
			{
				if($type=="RC")
					$heading = "Awaiting Response Archive";
				else
					$heading = "Members Awaiting My Response";
			}
		}
	

	$sql .= " ORDER BY TIME DESC";
//echo $sql;
        	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		unset($profileid_arr);
        	while($row = mysql_fetch_array($res))
		{
                	$profileid_arr[] = $row['SENDER'];

			$TOI = $row['TIME'];
			$year=substr($TOI,0,4);
			$month=substr($TOI,5,2);
			$day=substr($TOI,8,2);
			$TOI=my_format_date($day,$month,$year,2);

			$TIME_ARR[$row['SENDER']]['TIME'] = $TOI;
		}
	}
        $profileid_str = "'".@implode("','",$profileid_arr)."'";

	$smarty->assign("HEADING",$heading);
	$smarty->assign("MESSAGE_VAL",$with_without_message);
	$smarty->assign("TIME_ARR",$TIME_ARR);

	return $profileid_str;
}

//added by sriram to shift some select queries to slave
function mysql_query_optimizer($sql,$db="",$divert="")
{
        global $db_master, $db_slave, $db;
        if($db_master && $db_slave)
        {
                if(strtolower(substr(trim($sql),0,6)) == "select")
                {
                        @mysql_ping_js($db_slave);
                        return mysql_query_decide($sql,$db_slave,$divert);
                }
                else
                {
                        @mysql_ping_js($db_master);
                        return mysql_query_decide($sql,$db_master,$divert);
                }
        }

        //@mysql_ping_js($db);

        return mysql_query_decide($sql,$db,$divert);
}

function contact_hit_limit($profileid,$type)
{

	global $data,$db;
	$username=$data['USERNAME'];
	if($profileid!="")
	{
			$day=date("d");
	
		$month=date("m");
		$year=date("Y");
		if($type=='T')
			$sql="select PROFILEID from MIS.CONTACTS_FAULT_MONITOR where PROFILEID=$profileid and TYPE='$type' and YEAR='$year' and MONTH='$month' and DAY='$day' ";
		if($type=='M')
			$sql="select PROFILEID from MIS.CONTACTS_FAULT_MONITOR where PROFILEID=$profileid and TYPE='$type' and YEAR='$year' and MONTH='$month' ";
		if($type=='O' || $type=='I')
			$sql="select PROFILEID from MIS.CONTACTS_FAULT_MONITOR where PROFILEID=$profileid and TYPE='$type'";
		if($type=='W')
			$sql="select PROFILEID from MIS.CONTACTS_FAULT_MONITOR where PROFILEID=$profileid and TYPE='$type' and YEAR='$year' and MONTH='$month' and DAY='$day'";
		if($sql)
		{
			$res=mysql_query_decide($sql)	or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(!($row=mysql_fetch_array($res)))
			{
				$sql="insert into MIS.CONTACTS_FAULT_MONITOR  (`PROFILEID`,`USERNAME`,`DATE`,`TYPE`,`YEAR`,`MONTH`,`DAY`,`SPAM_CALC`) values($profileid,'$username',now(),'$type','$year','$month','$day','N')";
				mysql_query_decide($sql) or 	logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
		}
	}	
	
}
function rightpanel()
{
	success_pool(1);
}
function success_pool($to_fetch)
{
	global $smarty;
	$mysqlObj=new Mysql;
        $mydb=$mysqlObj->connect("master");
	//Key for storing data related to small random success story..
	$key="SUCCESSPOOL";
	$val=unserialize(memcache_call($key));
	if(!$val)
	{
		$sql_story = "SELECT SID, NAME1, NAME2,HOME_PIC_URL FROM newjs.INDIVIDUAL_STORIES,SUCCESS_POOL WHERE STORYID=ID_POOL AND CURRENT_LIVE='Y' order by SID limit 4";
		$res_story = mysql_query($sql_story,$mydb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_story,"ShowErrTemplate");
		$i=0;
		while($row_story = mysql_fetch_array($res_story))
		{
			$val[$i]["SID"] = $row_story["SID"];
			$val[$i]["NAME1"] = ucwords($row_story["NAME1"]);
			$val[$i]["NAME2"] = ucwords($row_story["NAME2"]);
			$val[$i]["HOME_PIC_URL"] = getCloudOrApplicationCompleteUrl($row_story["HOME_PIC_URL"]);
			$i++;
		
		}
		memcache_call($key,serialize($val),345600);
	}
	/*if(count($val)<$to_fetch)
		$to_fetch=count($val);
	$fin_arr=array_rand($val,$to_fetch);
	if(is_array($fin_arr))
	{
		foreach($fin_arr as $key=>$vv)
		{
			$story_arr[$key]=$val[$vv];
		}
	}
	else
		$story_arr[0]=$val[$fin_arr];*/
	$story_arr = $val;

	$smarty->assign("story_arr",$story_arr);
	return $story_arr;
}
function getCloudOrApplicationCompleteUrl($value)
{
	$flag=substr($value,0,2);
	$remaining=substr($value,2);
	switch($flag)
	{
		case 'JS' : $setServer=JsConstants::$applicationPhotoUrl;
							 break;
		case 'CL' : $setServer=JsConstants::$cloudUrl;
							break;
		case 'AR' : $setServer=JsConstants::$cloudArchiveUrl;
                                                        break;
	}
	if($setServer)
		$setServer = $setServer.$remaining;
	else
		$setServer=$value;

	return $setServer;
}
function login_relogin_auth($data)
{
     //return;
	$check_data=$data;
        global $smarty,$data,$mysqlObj;
	global $domain,$_COOKIE;
        global $CONTACT_STATUS;		//This array is declared in mainmenu.php 
	global $ASTRO_DETAILS;		//This array is defined in mainmenu.php
	if(!$data)
                $data=$check_data;
	if($data['USERNAME'])
	{
		$smarty->assign("LOGIN",1);
		$smarty->assign("USERNAME",$data['USERNAME']);
	}
        if($data)
        {
                $profileid=$data["PROFILEID"];
		
		//added by lavesh to show photo rquest link on left panel only if user does-not have photo.
		if($_COOKIE['JS_HAVEPHOTO'])
		{
			$my_photo=$_COOKIE['JS_HAVEPHOTO'];
        	        settype($my_photo,"integer");
			$smarty->assign("my_photo",$my_photo);
		}
		//ends here

		if(is_spam($profileid))
		{		
			$data['SPAM']='Y';
			$smarty->assign("SPAM","Y");
		}
                //leftpanel_membership();//added by lavesh
                                                                                                                             
                //modified by sriram
		$memcache_cont_stat=unserialize(memcache_call($profileid));		
                if(!$memcache_cont_stat && $profileid)
		{
			$sql_cont_stat = "SELECT * FROM CONTACTS_STATUS WHERE PROFILEID='$profileid'";
	                $res_cont_stat = mysql_query_optimizer($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
			if($memcache_cont_stat=mysql_fetch_array($res_cont_stat))
				$passZE=1;
			
		}
                if($memcache_cont_stat)
                {
                        $row_cont_stat = $memcache_cont_stat;
			
                        $total_accepted = $row_cont_stat['ACC_BY_ME'] + $row_cont_stat['ACC_ME'];

			if(is_array($CONTACT_STATUS))
			{
				$CONTACT_STATUS["ACC_BY_ME"]=$row_cont_stat['ACC_BY_ME'];
				$CONTACT_STATUS['ACC_ME']=$row_cont_stat['ACC_ME'];
				$CONTACT_STATUS['EXPIRY_DT']=$row_cont_stat['EXPIRY_DT'];
				$CONTACT_STATUS['NOT_REP']=$row_cont_stat['NOT_REP'];
			}
                                                                                                                             

						//added by Nikhil--> Now we are showing new messages count on every page
						
			$data['MONTH_INI_TOTAL']=$row_cont_stat['MONTH_INI_BY_ME'];
			$data['TODAY_INI_TOTAL']=$row_cont_stat['TODAY_INI_BY_ME']; 
			$data['WEEK_INI_TOTAL']=$row_cont_stat['WEEK_INI_BY_ME'];
			$data['TOTAL_CONTACTS_MADE']=$row_cont_stat['TOTAL_CONTACTS_MADE'];
                       
                       
                        $smarty->assign("RECEIVED_I_ARCHIVE",$row_cont_stat['LAST_30_90_OPEN_CONTACTS']);
							
                        $smarty->assign("TOPLEFT_OPEN",$row_cont_stat['LAST_30_OPEN_CONTACTS']);
                        $smarty->assign("TOPLEFT_ACCEPTED",$total_accepted);
                        $smarty->assign("PHOTO_REQUEST_CNT",$row_cont_stat['PHOTO_REQUESTS']);
                        //added by lavesh.
                        $smarty->assign("RECEIVED_A",$row_cont_stat['ACC_BY_ME']);
                        $smarty->assign("MADE_A",$row_cont_stat['ACC_ME']);
                        $smarty->assign("RECEIVED_I",$row_cont_stat['LAST_30_OPEN_CONTACTS']);//(Duplicate)
                        $smarty->assign("MADE_I",$row_cont_stat['NOT_REP']);
                        $smarty->assign("MADE_D",$row_cont_stat['DEC_ME']);
                        $smarty->assign("RECEIVED_D",$row_cont_stat['DEC_BY_ME']);

			$smarty->assign("TODAY_INI_TOTAL",$row_cont_stat['TODAY_INI_BY_ME']);
			$smarty->assign("MONTH_INI_TOTAL",$row_cont_stat['MONTH_INI_BY_ME']);
			$smarty->assign("WEEK_INI_TOTAL",$row_cont_stat['WEEK_INI_BY_ME']);
			//$smarty->assign("ALLOW_GENDER",$data['GENDER']);
                        //Default settting for contact limit now applies for all.
                        $smarty->assign("ALLOW_GENDER",'M');

			$smarty->assign("TOTAL_CONTACTS_MADE",$row_cont_stat['TOTAL_CONTACTS_MADE']);

			$smarty->assign("RECEIVEDSUM",$row_cont_stat['ACC_BY_ME']+$row_cont_stat['LAST_30_90_OPEN_CONTACTS']+$row_cont_stat['LAST_30_OPEN_CONTACTS']+$row_cont_stat['DEC_BY_ME']);

			$smarty->assign("MADESUM",$row_cont_stat['ACC_ME']+$row_cont_stat['NOT_REP']+$row_cont_stat['DEC_ME']);
                        //ends here.
			//added by sriram
                        if($row_cont_stat['OPEN_CONTACTS'] > 20)
                                $smarty->assign("SHOW_SEARCH_CONTACTS","Y");
                        if($data["SUBSCRIPTION"])
                        {
                                if($row_cont_stat['EXPIRY_DT']!="0000-00-00")
				{
					$curdate=date('Y-m-d');
                                        $days_left_expire=getTime_Diff($curdate,$row_cont_stat['EXPIRY_DT']);
                                        $expiry_dt=$row_cont_stat['EXPIRY_DT'];
				}
                                leftpanel_membership($expiry_dt);
				$smarty->assign("DAYS_TO_EXPIRE_GLOBAL",$days_left_expire);
                        }
                        else
                                $smarty->assign("mem_case",2);
                        //added by lavesh
			$horoscope_requests=$row_cont_stat["HOROSCOPE_REQUESTS"];
                        $smarty->assign("HOROSCOPE_REQUESTS",$horoscope_requests);
                        //ends here
                }
                else
                {
			put_back_to_contact_status();	
		}
                /*$sql="SELECT count(*) as cnt FROM MESSAGE_LOG WHERE RECEIVER='$data[PROFILEID]' AND FOLDERID=0 AND RECEIVER_STATUS='U' AND OBSCENE='N'";
                $result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                                                                                                                             
                $countres=mysql_fetch_array($result);
                                                                                                                             
                $smarty->assign("INBOX_CNT",$countres["cnt"]);*/
		/*Added by sriram to toggle Create / View horoscope link on left panel*/
		$sql = "SELECT * FROM newjs.ASTRO_DETAILS WHERE PROFILEID='$profileid'";
		$result=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if(mysql_num_rows($result) > 0)
		{
			$row=mysql_fetch_array($result);
			
			if(is_array($ASTRO_DETAILS))
			{
				foreach($ASTRO_DETAILS as $key=>$val)
					$ASTRO_DETAILS[$key]=$row[$key];
			}
			
			$smarty->assign("HOROSCOPE_EXISTS","Y");
		}
                /*End - of Added by sriram to toggle Create / View horoscope link on left panel*/
        }
}

//Helps in updating memcache content for particular profileid.
//array_to_merge->values that needs to be updated
//$profileid --> whom array to be merged
//$not_mand --> need not to updated the array if value is 1
function logError2($sql)
{
	error_log($sql,3,JsConstants::$docRoot . "/profile/logerror.txt");
	logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
function updatememcache($array_to_merge,$profileid,$not_mand=0)
{
	 global $memcacheObj;
	if(!$memcacheObj && class_exists(UserMemcache))
		$memcacheObj=new UserMemcache;

	if($array_to_merge)
	{
		if($memcacheObj)
			$lock=$memcacheObj->setlock($profileid);

		//Getting data from memcache.
		 $memcache_cont_stat=unserialize(memcache_call($profileid));
		//If record is not updated in memcache.
        	//if($memcache_cont_stat['FIRST_TIME']==1)
	        {
                	put_back_to_contact_status();
        	}
	        $memcache_cont_stat=unserialize(memcache_call($profileid));
		
		
		if($not_mand && !$memcache_cont_stat)
		{
			//Putting entry into contacts_status table.
			foreach($array_to_merge as $key=>$val)
			{
				if(strstr($key,"NEW")===false && strstr($key,"AWAITING_RESPONSE")===false)
				{
					if(strstr($key,"REQUEST") == true)
						$key = $key."S";
					if(strstr($key,"HOROSCOPE") == true)
						$key = $key."_REQUESTS";
					if(strstr($val,"-")===true)
						$str_merge.="$key=$key$val,";
					else
						$str_merge.="$key=$key+$val,";
				}
			}
			$str_merge=rtrim($str_merge,",");
			if($memcacheObj)
				$memcacheObj->releaselock($lock);
			return 1;
		}
	
		$reset_memcache_data=0;
		if($memcache_cont_stat)
		{
			$save_search=$memcache_cont_stat['SAVE_SEARCH'];
			//This array is used to update the curent day record.
			$week_array=array("A"=>0,"B"=>1,"C"=>2,"D"=>3,"E"=>4,"F"=>5,"G"=>6);
			//Give me the previous update date .
			$cur_day=array_search(date("w"),$week_array);
			if($cur_day!=$save_search)
			{
				$reset_memcache_data=1;
			}
		}
		if(!$memcache_cont_stat || $reset_memcache_data)
			put_back_to_contact_status();
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$eventObj = new ProfileMemcacheService($profileid);
		$memcache_cont_stat=unserialize(memcache_call($profileid));
		foreach($array_to_merge as $key=>$val)
		{
			$eventObj->update($key, $val,$not_mand);
			$memcache_cont_stat[$key]=$memcache_cont_stat[$key]+$val;
			
		}
		//Update the data in contacts_status table.
		if($memcache_cont_stat)
		{
			
			foreach($memcache_cont_stat as $key=>$val)
			{
				$lowerkey=strtolower($key);
				$$lowerkey=$val;
				
			}
			
		}
			
		$eventObj->updateMemcache();	
		if($memcacheObj)
			$memcacheObj->releaselock($lock);
	}
}
function put_back_to_contact_status($data_check=0,$flushData=0)
{
	global $data,$autoContact,$RBCron,$autoProfile,$receiverProfile;
	$profileid=$data[PROFILEID];
	if($autoContact)
                $profileid=$autoProfile;
        if($RBCron)
                $profileid=$receiverProfile;
	
	if($profileid)
	{
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$contactsMemcacheObj = new ProfileMemcacheService($profileid);
		if($flushData)
	        	$contactsMemcacheObj->clearInstance();	
	}
	if($autoContact)
                unset($data);
	if($data_check==1 && $contactsMemcacheObj)
		return $contactsMemcacheObj->getMemcacheData();
}

function login_left_panel($profileid)
{
    return;
}

//function made By Tapan
function getTime_Diff($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= round($timestamp_diff / (24*60*60));
                return $days_diff;
        }
        elseif($date2 == $date1)
                return 0;
        else
                return -1;
}
/*
Function    : get_101_details
Description : To get the contact details of 101 members or operator who created them
@param      : $profileid (Profileid of 101 member
@param      : $detail (Contact details required)
@param      : $operator (Operator to whom 101 is assigned if operator's details required else blank)Author      : Sadaf Alam
Modified On : 02 Sep 2008 [Mantis ID 3271]
*/
function get_101_details($profileid,$detail,$operator='')
{
        if($detail)
        {
                $detailarr=explode(",",$detail);
                if($operator)
                {
                        $sql="SELECT ";
                        foreach($detailarr as $value)
                        {
                                $sql.=$value.",";
                        }
                        $sql=rtrim($sql,",");
                        $sql.=" FROM jsadmin.PSWRDS WHERE USERNAME='$operator'";
                        $res=mysql_query_decide($sql) or die("Wrong details provided");
                        if(mysql_num_rows($res))
                        {
                                $row=mysql_fetch_assoc($res);
                                return $row;
                        }
                }
                else
                {

                }
        }
	else
        {
                die("No detail provided");
        }

}

function delete_profile($profileid,$delete_reason='',$specify_reason='')
{
        global $db,$smarty;
        $sql_jp="SELECT USERNAME,EMAIL,GENDER,ACTIVATED,CONTACT,SUBSCRIPTION FROM newjs.JPROFILE WHERE  PROFILEID='$profileid'";
        $res_jp = mysql_query_decide($sql_jp) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
        $row_jp = mysql_fetch_array($res_jp);
	

        //insert delete reason
        $delete_reason = htmlspecialchars($delete_reason,ENT_QUOTES);
        $specify_reason = htmlspecialchars($specify_reason,ENT_QUOTES);
        $sql_del = "REPLACE INTO newjs.PROFILE_DEL_REASON(USERNAME,DEL_REASON, SPECIFIED_REASON,PROFILE_DEL_DATE,PROFILEID) VALUES('$row_jp[USERNAME]','$delete_reason', '$specify_reason',now(),'$profileid')";

        mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");

        $sql="update JPROFILE set PREACTIVATED=IF(ACTIVATED<>'H',if(ACTIVATED<>'D',ACTIVATED,PREACTIVATED),PREACTIVATED), ACTIVATED='D', MOD_DT=now(),activatedKey=0 where PROFILEID='$profileid'";
		JProfileUpdateLib::getInstance()->removeCache($profileid);
        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	
	if($delete_reason=="I found my match on Jeevansathi.com")
	{
		$sql="SELECT ID FROM newjs.SUCCESS_STORIES WHERE USERNAME='$row_jp[USERNAME]'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
		if(mysql_num_rows($res)==0)
		{
			$from='customerservice@jeevansathi.com';
			$sub='Congratulations from Jeevansathi !';
			if($row_jp["EMAIL"])
			{
				$msg=$smarty->fetch("../mailer/success_mailer/success_mailer_delete.htm");
				send_email($row_jp["EMAIL"],$msg,$sub,$from);
				$sql="INSERT IGNORE INTO mailer.SS_MAILER (USERNAME,SENT_MAIL,PROFILE_TYPE) VALUES('$row_jp[USERNAME]','Y','2')";
				$res= mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_jp,"ShowErrTemplate");
			}
		}
	}
        
	//added by neha
        $now= date("Y-m-d H:i:s");
        $sql= "UPDATE jsadmin.MARK_DELETE SET STATUS='D', DATE='$now' WHERE PROFILEID=$profileid ";
        mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	//delete offline contacts
        //added by Neha Verma

        $sql="SELECT BILLID,ENTRY_DATE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= '$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
        $res= mysql_query_decide($sql) or die(mysql_error_js());
        $row= mysql_fetch_array($res);
        $entry_date= $row['ENTRY_DATE'];
        $bid= $row['BILLID'];
        $sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N' WHERE PROFILEID= '$profileid' AND ENTRY_DATE= '$entry_date' AND BILLID= '$bid'";
        $res= mysql_query_decide($sql) or die(mysql_error_js());

        $SQL= "SELECT PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
        $RES= mysql_query_decide($SQL) or die (mysql_error_js());
        if(mysql_num_rows($RES)>0)
        {
                $sqldel1="INSERT ignore INTO jsadmin.DELETED_OFFLINE_MATCHES(ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE,SEEN) SELECT ID,PROFILEID,MATCH_ID,STATUS,CATEGORY,MATCH_DATE,NOTE,SHOW_ONLINE,SEEN FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
                mysql_query_decide($sqldel1) or logError($sqldel1);
                $sqldel2="DELETE FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('N','NACC','NNOW','NREJ','NNREJ')";
                mysql_query_decide($sqldel2) or logError($sqldel2);
        }
        $SQL1= "SELECT PROFILEID FROM jsadmin.OFFLINE_MATCHES WHERE MATCH_ID= '$profileid' OR PROFILEID= '$profileid' AND STATUS IN ('ACC','SL','REJ')";
        $RES1= mysql_query_decide($SQL1) or die (mysql_error_js());
        if(mysql_num_rows($RES1)>0)
        {
                $sql_d= "UPDATE jsadmin.OFFLINE_MATCHES SET SHOW_ONLINE= 'N' WHERE PROFILEID= '$profileid' OR MATCH_ID= '$profileid' ";
                $res_d= mysql_query_decide($sql_d) or die (mysql_error_js());

        }
	$SQL2= "SELECT COUNT(*) AS CNT FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid'";
        $RES2= mysql_query_decide($SQL2) or die(mysql_error_js());
        if(mysql_num_rows($RES2)>0)
        {
                $sqldel="INSERT IGNORE INTO jsadmin.DELETED_OFFLINE_NUDGE_LOG(ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON) SELECT ID,SENDER,RECEIVER,DATE,RECEIVER_STATUS,FOLDERID,SENDER_STATUS,TYPE,V_CON FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid' ";
                mysql_query_decide($sqldel) or logError($sqldel);
                $sqldel="DELETE FROM jsadmin.OFFLINE_NUDGE_LOG WHERE SENDER= '$profileid' OR RECEIVER= '$profileid'";
                mysql_query_decide($sqldel) or logError($sqldel);
        }

	if($row_jp["SUBSCRIPTION"])
	{
		$subArray=explode(",",$row_jp["SUBSCRIPTION"]);
		if(in_array("T",$subArray))
		{
			$sqlAP="DELETE FROM Assisted_Product.AP_PROFILE_INFO WHERE PROFILEID='$profileid'";
		        mysql_query_decide($sqlAP) or logError($sqlAP);
		}
		if(in_array("L",$subArray))
		{
			$sqlAP="DELETE FROM Assisted_Product.AP_SERVICE_TABLE WHERE PROFILEID='$profileid'";
			mysql_query_decide($sqlAP) or logError($sqlAP);

			$sqlAP="UPDATE Assisted_Product.AP_MISSED_SERVICE_LOG SET COMPLETED='N' WHERE COMPLETED='' AND PROFILEID='$profileid'";
			mysql_query_decide($sqlAP) or logError($sqlAP);

		}
		/*
		if(in_array("I",$subArray))
		{
			$sqlAP="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid')";
        		mysql_query_decide($sqlAP) or logError($sqlAP);
		        $sqlAP="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE PROFILEID='$profileid' AND CALL_STATUS='Y'";
		        mysql_query_decide($sqlAP) or logError($sqlAP);	
		}
		*/
	}

        //$sqlAP="INSERT INTO Assisted_Product.DELETED_AP_CALL_HISTORY SELECT * FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid') AND CALL_STATUS='N'";
        //$sqlAP="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid') AND CALL_STATUS='N'";
        //mysql_query_decide($sqlAP) or logError($sqlAP);
        $sqlAP="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE (PROFILEID='$profileid' OR MATCH_ID='$profileid')";
        mysql_query_decide($sqlAP) or logError($sqlAP);

        if($row_jp['ACTIVATED']!='D')
        {
                $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
                $cmd = JsConstants::$php5path." -q ".$path;
                passthru($cmd);
        }
        //end of - added by sriram to prevent the query on CONTACTS table being run several times on page reload.
}
function get_date_from_timestamp($date,$start_year)
{
	$year=$start_year;
	if($date>365)
	{
		while($date>365)
		{
			$year++;
			if(($year%4==0)&&($year%100!=0)||($year%400)==0)
			$date-=366;
			else
			$date-=365;
		}

	}
	if(($year%4==0)&&($year%100!=0)||($year%400==0))
	$leap=1;
	else
	$leap=0;
	$monthnum=array("Jan"=>"01",
			"Feb"=>"02",
			"Mar"=>"03",
			"Apr"=>"04",
			"May"=>"05",
			"Jun"=>"06",
			"Jul"=>"07",
			"Aug"=>"08",
			"Sep"=>"09",
			"Oct"=>"10",
			"Nov"=>"11",
			"Dec"=>"12");
	if($leap)
	$montharr=array("Jan"=>31,
		"Feb"=>28,
		"Mar"=>31,
		"Apr"=>30,
		"May"=>31,
		"Jun"=>30,
		"Jul"=>31,
		"Aug"=>31,
		"Sep"=>30,
		"Oct"=>31,
		"Nov"=>30,
		"Dec"=>31);
	else
	$montharr=array("Jan"=>31,
		"Feb"=>28,
		"Mar"=>31,
		"Apr"=>30,
		"May"=>31,
		"Jun"=>30,
		"Jul"=>31,
		"Aug"=>31,
		"Sep"=>30,
		"Oct"=>31,
		"Nov"=>30,
		"Dec"=>31);
	foreach($montharr as $key=>$value)
	{
		if($date<=$value)
		{
			$month=$key;
			break;
		}
		$date=$date-$value;

	}
	$day=$date;
	$date=$day."-".$month."-".$year;
	$month=$monthnum[$month];
	if($month && $day && $year)
	{
		$datestamp=mktime(0, 0, 0,$month,$day+1,$year);
		$formatted_date=date("Y-m-d",$datestamp);
	}
	return $formatted_date;
}
function updateSimProfileLog($contactedby,$db='')
{
	if($contactedby)
	{
		$sql="SELECT * FROM newjs.SIM_PROFILE_LOG_TEMP WHERE PROFILEID=$contactedby";
		if($db)
			$res=mysql_query_decide($sql,$db) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql,"ShowErrTemplate");
		else
			$res=mysql_query_decide($sql) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql,"ShowErrTemplate");
		while($myviews=mysql_fetch_array($res))
		{
			$viewed=$myviews["VIEWED_PROFILE"];
			$listArr[]=$viewed;
			$dt=$myviews["DATE"];
			/*
			$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$viewed','$dt')";
			if($db)
				mysql_query_decide($sql_insert_views,$db) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
			else
				mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
			*/
			$upstrArr[]="('".$contactedby."','".$viewed."','".$dt."')";
		}
		if(is_array($upstrArr))
		{
			$upstr=implode(",",$upstrArr);
			//$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG(PROFILEID,VIEWED_PROFILE,DATE) VALUES('$contactedby','$viewed','$dt')";
			$sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG(PROFILEID,VIEWED_PROFILE,DATE) VALUES $upstr";
			if($db)
				mysql_query_decide($sql_insert_views,$db) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
			else
				mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
		}

		if($listArr)
		{
			$list=implode(",",$listArr);
			$sql="DELETE FROM newjs.SIM_PROFILE_LOG_TEMP WHERE PROFILEID=$contactedby AND VIEWED_PROFILE IN ($list)";
			if($db)
				mysql_query_decide($sql,$db) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql,"ShowErrTemplate");
			else
				mysql_query_decide($sql) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql,"ShowErrTemplate");
		}

	}
}

function updateSimTempLog($res,$db1='',$contactedby)
{
        while($myviews=mysql_fetch_array($res))
        {
                $viewed=$myviews["PROFILEID"];
                if(!$viewed)
                        $viewed=$myviews["RECEIVER"];
                $dt=date("Y-m-d");
                $upstrArr[]="('".$contactedby."','".$viewed."','".$dt."')";
        }
        if(is_array($upstrArr))
        {
                $upstr=implode(",",$upstrArr);
                $sql_insert_views="INSERT IGNORE INTO newjs.SIM_PROFILE_LOG_TEMP(PROFILEID,VIEWED_PROFILE,DATE) VALUES $upstr";
                if($db1)
                        mysql_query_decide($sql_insert_views,$db1) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
                else
                        mysql_query_decide($sql_insert_views) or logError("Error while entring  data to newjs.SIM_PROFILE+LOG",$sql_insert_views,"ShowErrTemplate");
        }
}


function getcasteparent($caste){
        //REVAMP JS_DB_CASTE
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
        return getcasteparent_revamp_js_db($caste);
        //REVAMP JS_DB_CASTE
}

/* IVR - Callnow functionalty added in detailed profile to access Callnow feature 
 * function verifies the condition to make call by the loggedin User and acces the callnow feature in detailed profile page.
 * return: false  
 * file include: jsContactVerify.php (file contains the Callnow related functions)
*/
function ivrCallNow($caller_profileid,$receiver_profileid,$type,$who)
{
        global $smarty;
        include_once($_SERVER['DOCUMENT_ROOT']."/ivr/IVRContact.class.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/callnow_messages.php");
	$ivrContact = new IVRContact();
	$ivrContact->setCallerReceiverDetail($caller_profileid, $receiver_profileid);
	if($ivrContact->getCallNowSetting()){
		$result = $ivrContact->JeevansathiVerifyContact($caller_profileid, $receiver_profileid);
		if($result=='SUCCESS')
		{
			$lock = $ivrContact->getDialcodeLock();
			if($lock){
				$dialCode=$ivrContact->getDialCode();
				$ivrContact->releaseDialcodeLock();;
			}else $dialCode=0;
			if($dialCode){
				$callNowSuccess["DIALCODE"]=$dialCode;
				$callNowSuccess["MESSAGE"]=$ivrContact->getSuccessMessage();
				$callNowSuccess["PATCHED_CALLS"]=$ivrContact->getPatchedCalls();
				$callNowSuccess["SHORTCODE"]=$ivrContact->shortCode;
				$callNowSuccess["AVAILABLE_TIME"]=$ivrContact->getReceiverCallAvailability();
				$callNowSuccess["RELATIONSHIP"]=$ivrContact->showReceiverRelationship();;
			}
			else $smarty->assign("DIALCODE_ERROR",true);
		}else{
			if($result=='CALLER_ALREADY_CALLED'){
				$callNowError["PATCHED_CALLS"]=$ivrContact->getPatchedCalls();
			}
			global $index;
			$callNowError["MESSAGE"]=$ivrContact->getErrorMessage($result,$type,$who,$index);
			$callNowError["LINK"] = $ivrContact->showActionLink($result,$type,$who,$index);
		}
		$smarty->assign("CALLNOW_RESULT",$result);
		$smarty->assign("CALLNOW_SUCCESS",$callNowSuccess);
		$smarty->assign("CALLNOW_ERROR", $callNowError);
	}
}

/* function to get the Call setting set by the user for the callnow feature accessible 
 * return true/false, true: when callnow feature is accessible, false: callnow feature is not accessible 
 */
function callAccess($profileidStr="")
{
	$callnowSetting = getCallNowSetting($profileidStr);
        /*$sql ="SELECT `PROFILEID`,`SHOWPHONE_MOB`,`SHOWPHONE_RES`,PHONE_MOB,PHONE_RES from newjs.JPROFILE WHERE PROFILEID in($profileidStr)";
        $result =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($myrow = mysql_fetch_array($result))
        {
                $profileid = $myrow['PROFILEID'];
                $showphone_res = $myrow['SHOWPHONE_RES'];
                $showphone_mob = $myrow['SHOWPHONE_MOB'];
                if(($myrow['PHONE_RES'] || $myrow['PHONE_MOB']))
                        $callAccessArr[$profileid] ='Y';
                else
                        $callAccessArr[$profileid] ='';
        }*/
	return $callnowSetting;
}

// function to check the call receiver daily quota check 
function rec_callnowDailyQuotaCheck($profileIDStr)
{
        include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsContactVerify.php");
        $receiver_DailyQuota ='10';
        $todaysDateTime =getIST();
        $todaysDateArr = explode(" ",$todaysDateTime);
        $todaysDate = $todaysDateArr[0];

        $receiverQuotaStatusArr=array();
        $pidsArr =explode(",",$profileIDStr);
        $tot_pids =count($pidsArr);
        for($i=0;$i<$tot_pids;$i++)
        {
                $pid=$pidsArr[$i];
                $pid =str_replace("'","",$pid);
                $receiverQuotaStatusArr[$pid]='Y';
        }

        $sql ="SELECT COUNT(*) as CNT,RECEIVER_PID from newjs.CALLNOW where RECEIVER_PID in($profileIDStr) AND `CALL_STATUS`='R' AND `CALL_DT` like '$todaysDate%' GROUP BY RECEIVER_PID";
        $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row = mysql_fetch_array($res))
        {
                $count = $row['CNT'];
                $receiver_pid = $row['RECEIVER_PID'];
                if($count>=$receiver_DailyQuota)
                        $receiverQuotaStatusArr[$receiver_pid]='';
        }
        return $receiverQuotaStatusArr;
}

function getCallerProfiles($receiver,$caller,$CALL_NOW)
{
        global $data;
        //$paid=isPaid($data["SUBSCRIPTION"]);
        //if(!$paid)
        //        return false;
	if(!$CALL_NOW)
		return false;
	if(!$receiver && !$caller)
		return false;
	$callerProfilesArr=array();
	if(is_array($caller))
	{
		$tot =count($caller);
		for($i=0;$i<$tot;$i++)
		{
			$pid=$caller[$i];
			$callerProfilesArr[$pid]='';
		}
		$callerStr =implode(",",$caller);
	}
	else{
		$callerProfilesArr[$caller]='';
		$callerStr=$caller;
	}

        $sql ="SELECT `CALLER_PID` from newjs.CALLNOW where RECEIVER_PID='$receiver' AND CALLER_PID in($callerStr) AND (`CALL_STATUS`='R' OR `CALL_STATUS`='M')";
        $res =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row = mysql_fetch_array($res))
        {
                $callerpid =$row['CALLER_PID'];
                $callerProfilesArr[$callerpid] ='Y';                           
	}
	if(!in_array("Y",$callerProfilesArr))
		return false;
        return $callerProfilesArr; 
}

// function to record the callnow link hits (* user logged in case only)
function recordCallnowHits($field)
{
	$sql="UPDATE MIS.CALLNOW_MIS SET $field=$field+1 WHERE DATE=CURDATE()";
	if(mysql_query_decide($sql))
	{
		if(mysql_affected_rows_js()==0)
	        {
	        	$sql="INSERT INTO MIS.CALLNOW_MIS ($field,DATE) VALUES ('1',CURDATE())";
	                mysql_query_decide($sql);
	        }
	}
}
function stat_name($PROFILEID,$username)
{

/**********************************************************************************************
                        Changed By      :       Shakti Srivastava
                        Change Date     :       4 January, 2006
                        Reason          :       New element, STAT_UNAME has been added for static urls. 
                ***********************************************************************************************/
                        $sumProfileid=0;

                        for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
                        {
                                $sumProfileid=$sumProfileid+$PROFILEID{$tempcnt};      //sum of profileid digits
                        }
                        $rotator=$sumProfileid%(strlen($PROFILEID));           //sum mod length of profileid rotator

                        for($tempcnt=0;$tempcnt<strlen($PROFILEID);$tempcnt++)
                        {
                                $newpos=($tempcnt+$rotator)%strlen($PROFILEID);
                                $rotatedProfileidArr[$newpos]=$PROFILEID{$tempcnt};    //rotated profileid
                        }

                        ksort($rotatedProfileidArr);

                        if(count($rotatedProfileidArr)>1)
                                $rotatedProfileid=implode("",$rotatedProfileidArr);
                        else
                                $rotatedProfileid=$rotatedProfileidArr[0];

                        unset($rotatedProfileidArr);
                        unset($sumProfileid);
                        for($tempcnt=0;$tempcnt<strlen($username);$tempcnt++)
                        {
                                $asciiChr=ord($username{$tempcnt});

				if($asciiChr>=33 && $asciiChr<=126)
                                {
                                        $stat_uname=$rotatedProfileid.$username{$tempcnt}.$rotator;
                                        break;
                                }
                        }
                        unset($rotatedProfileid);
                        unset($rotator);
                        return $stat_uname;

}
/* Function that will send the intimation on email regarding the chat id of jeevansathi*/

function send_chat_request_email($profileid,$gmail,$username)
{
return;
		global $smarty;
        $mypid=$profileid;
        $email=$gmail;
		

			if($mypid && $email && $username)
			{
					$myprofilechecksum = md5($mypid)."i".($mypid);
					$smarty->assign("myprofilechecksum",$myprofilechecksum);
					$smarty->assign("USERNAME",$username);
					$msg=$smarty->fetch("proposals_gtalk.htm");
					$counter++;
					send_email($email,$msg,"Receive proposals on Gtalk","info@jeevansathi.com");
					
			}
		
}
/**
  * This function is used to find out profileids of all the profiles that have been ignored by a user.
  * @param - $viewer - profiles whose ignored profiles are to be found.
  * @param - $db - database connection
  * @param - $getValuesInKey - has a value '1' if the resulting profileids have to be returned in the key of the return array.
  * @return - a list of profiles ignored by the $viewer
**/
function getIgnoredProfiles($viewer,$db,$getValuesInKey='')
{
	global $ajax_error;
	$ajax_error=2;

	$sql = "SELECT IGNORED_PROFILEID FROM newjs.IGNORE_PROFILE WHERE PROFILEID = $viewer AND UPDATED='Y' ";
	$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate","","",$db);

	while($row=mysql_fetch_assoc($res))
	{
		if($getValuesInKey == 1)
			$ignoredList[$row['IGNORED_PROFILEID']]=1;
		else
			$ignoredList[]=$row['IGNORED_PROFILEID'];
	}
	return $ignoredList;
}

        /*
        This function is used to generate an array having mtongue regions with their value and label and mtongues belonging to each region with their values and labels
        @param - if small labels of mtongue are required then pass 1 in the optional parameter
        @return - required 2 dimensional array
        */
        function generateMtongueDropdownForTemplate($community_small='')
        {
	global $MTONGUE_DROP_SMALL,$MTONGUE_DROP,$MTONGUE_REGION_LABEL,$MTONGUE_REGION_REGISTRATION_DROP;
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");

                if($community_small)
                        $mtongue_drop = $MTONGUE_DROP_SMALL;
                else
                        $mtongue_drop = $MTONGUE_DROP;

                foreach($MTONGUE_REGION_LABEL as $k=>$v)
                {
                        $output[$k]["LABEL"] = $v;
                        $output[$k]["VALUES"] = explode(",",$MTONGUE_REGION_REGISTRATION_DROP[$k]);
                }

                foreach($output as $k=>$v)
                {
                        foreach($v["VALUES"] as $kk=>$vv)
                        {
                                $temp[$vv] = str_replace("&amp;","&",$mtongue_drop[$vv]);
                        }
                        $output[$k]["VALUES"] = $temp;
                        unset($temp);
                }

                return $output;
        }


	function logDeletedProfileViews($stype='',$activated='',$privacy='',$pid='',$viewer='')
	{
		$db = connect_db();
		$dt = date("Y-m-d");
		if(!$stype)
			$stype='0';
		if(!$activated)
			$activated='D';

		global $_SERVER;
		$uri       = mysql_real_escape_string($_SERVER["REQUEST_URI"]);
		$ref       = mysql_real_escape_string($_SERVER["HTTP_REFERER"]);
		$userAgent = mysql_real_escape_string($_SERVER["HTTP_USER_AGENT"]);

		$sql="INSERT INTO MIS.DELETED_PROFILE_PIDS(DATE,VIEWER,VIEWED,STYPE,ACTIVATED,PRIVACY,REQUEST_URI,HTTP_REFERER,HTTP_USER_AGENT) VALUES('$dt','$viewer','$pid','$stype','$activated','$privacy','$uri','$ref','$userAgent')";
		mysql_query($sql,$db);

		$sql = "UPDATE MIS.DELETED_PROFILE_VIEWS SET COUNT=COUNT+1 WHERE ACTIVATED='$activated' AND PRIVACY='$privacy' AND DATE='$dt' AND STYPE='$stype'";
		mysql_query($sql,$db);
		if(mysql_affected_rows()==0)
		{
			$sql = "INSERT IGNORE INTO MIS.DELETED_PROFILE_VIEWS(DATE,COUNT,STYPE,ACTIVATED,PRIVACY) VALUES('$dt','1','$stype','$activated','$privacy')";
			mysql_query($sql,$db);
		}
	}
	function callDeleteCronBasedOnId($profileid,$background='Y')
	{	
		if($profileid=='EXPORT')
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing EXPORT";
		elseif($profileid=='DELTA')
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing DELTA";
		else
			$command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing PROFILEID ".$profileid;
		//$command.= " >> /var/www/htmlrevamp/ser6/branches/milestoneConfig/cache/l.txt";
		if($background=='Y')
			$command.=" &";
		//echo  $command;echo "\n\n";
		exec($command);
		
	}
	function pixelcode_reg($VAR,$CITY_RES_pixel,$AGE_pixel,$GENDER_pixel,$PROFILEID_pixel,$USERNAME_pixel,$adnetwork1)
	{
		if($VAR)
		{
			$sql="SELECT SQL_CACHE PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
			$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($res);
			$pixelcode=$row[PIXELCODE];
			$pixelcode = str_replace('~$CITY`', $CITY_RES_pixel, $pixelcode);
			$pixelcode = str_replace('~$USERNAME`', $USERNAME_pixel, $pixelcode);
			$pixelcode = str_replace('~$AGE`', $AGE_pixel, $pixelcode);
			$pixelcode = str_replace('~$GENDER`', $GENDER_pixel, $pixelcode);
			$pixelcode = str_replace('~$PROFILEID`', $PROFILEID_pixel, $pixelcode);
			$pixelcode = str_replace('~$ADNETWORK1`', $adnetwork1, $pixelcode);
			return $pixelcode;
		}
	}

function helpWidget($loginData){ 
    global $smarty;
    
    if($_SERVER["DOCUMENT_ROOT"])
    {
        
        $login = isset($loginData['PROFILEID'])?1:0;
		$bIsNRI  = $_COOKIE['IS_NRI'] ?true:false;
        $defaultEmail = "";
        if($login)
        {
            $iProfileId = $loginData['PROFILEID'];
            $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master',$iProfileId);
            $loggedInProfileObj->getDetail($iProfileId, "PROFILEID", 'PROFILEID,EMAIL,PHONE_MOB,MOB_STATUS,LANDL_STATUS,PHONE_WITH_STD');
            $defaultEmail = $loggedInProfileObj->getEMAIL();
            $phoneNumber = "";
            if($loggedInProfileObj->getMOB_STATUS() == "Y"){
                $phoneNumber = $loggedInProfileObj->getPHONE_MOB();
            }
            else if($loggedInProfileObj->getLANDL_STATUS() == "Y"){
                $phoneNumber = $loggedInProfileObj->getPHONE_WITH_STD();
            }
            else{//Check Alternate Phone numbmer
                $objAlternate = new ProfileContact();
                $arrResult = $objAlternate->getProfileContacts($iProfileId);
                if($arrResult["ALT_MOB_STATUS"] == "Y"){
                    if(trim($arrResult["ALT_MOBILE_ISD"]))
                        $phoneNumber = "+".trim($arrResult["ALT_MOBILE_ISD"])."-";
                    $phoneNumber .= trim($arrResult["ALT_MOBILE"]);
                }
            }
        }        
        
        
        $scriptname=$_SERVER['PHP_SELF'];
        $scriptname=str_replace("/P/","/profile/",$scriptname);
        
        $showExpandMode = 0;
        if(stripos($scriptname,'mainmenu.php') || stripos($scriptname,'membership') || $scriptname==='/index.php')
        {
           $showExpandMode = 1; 
        }
        
        $mobileNumber = '1-800-419-6299';
        if($bIsNRI)
        {
            $mobileNumber = '0120-4393500';
        }
        
        $helpWidgetCSS = "helpWidget_css.css";
        $helpWidgetJS = "helpWidget_js.js";
        
        $smarty->assign("defaultEmail",$defaultEmail);
        $smarty->assign("defaultPhone",$phoneNumber);
        $smarty->assign("mobileNumber",$mobileNumber);
        $smarty->assign("showExpandMode",$showExpandMode);
        $smarty->assign("helpWidget_css",$helpWidgetCSS);
        $smarty->assign("helpWidget_js",$helpWidgetJS);
        
        $smarty->assign("HELP_WIDGET",$smarty->fetch("helpWidget.htm"));
        
    }
}
?>
