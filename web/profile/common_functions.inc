<?php

/*		Function created by NIKHIL on jan 18 2010
		This function will created the contact address gadget of each evalue users
		Exception: Returns template with proper error messge(Gender, filter, Deleted, Login)
		$evalue_users --> Contains the list of users who have taken evalue membership
		$data --> Contains information about the login user.
		$other_gender --> Gender of search user.
*/

function check_for_all_condition($evalue_users,$data,$other_gender)
{
	global $SITE_URL;
	$prof=implode(",",$evalue_users);
	$prof_filter=array();
	if(!$data['PROFILEID'])
	{
		$reason="<span style='font-weight:normal'>To view contact details of this users <a href='$SITE_URL/profile/login.php?SHOW_LOGIN_WINDOW=1' class='thickbox'>Login here</a>.</span>";
	}
	else 
	{
		$profilechecksum=createChecksumForSearch($data['PROFILEID']);
		$activated=$data['ACTIVATED'];
		$incomplete=$data['INCOMPLETE'];

		if($incomplete == "Y")
                	$reason="<span style='font-weight:normal'>You need to complete your profile before viewing the user's contact details. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile.</span>";
		elseif($activated == "N" || $activated == "U" || $activated == "P")
                	$reason="<span style='font-weight:normal'>You can view the contact details of the user only after your profile is screened.</span>";
		elseif($data['GENDER']==$other_gender)
		{
			$reason="<span style='font-weight:normal'>You cannot see the contact details of this profile as the profile is of the same gender.</span>";
		}
		else
		{
			include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
			$is_spam=is_spam($data['PROFILEID']);
		        $my_parameters=get_my_parameters_for_dpp_match($data['PROFILEID']);
			for($i=0;$i<count($evalue_users);$i++)
			{
					if($evalue_users[$i])
						$prof_filter[$evalue_users[$i]]=getFilteredContact($data['PROFILEID'],$evalue_users[$i],$is_spam,$my_parameters,'viewContactDetails');
			}
		}
	}
	if($reason)
		return $reason;
	else
		return $prof_filter;

}
/* 	Function is created by NIKHIL on jan 18 2010
	Fill all the data that are required for contact gadget
	$exception_err --> Contains the error that need to be shown if present.
	$myrow --> Contains information about particular profile.
*/
function set_contact_gadget($exception_err,$myrow)
{
	global $smarty;
	global $contact_locked,$from_similar;
	$contact_locked=0;
	$jprofile_result["viewed"]=$myrow;
	$reason="";
	$smarty->assign("CONTACT_USERNAME",$myrow['USERNAME']);
	$smarty->assign("FROM_SEARCH_GADGET",1);
	if(is_array($exception_err))
	{
		if($exception_err[$myrow['PROFILEID']]=='Y')
		{
			$reason="<span style='font-weight:normal'>You cannot see the contact details of this profile as the profile has filtered you.</span>";	
		}
		else
		{
			$contact_locked=1;
	                $reason="";
		}
	}
	else if($exception_err!="")
	{
		$reason=$exception_err;
	}
	else
	{
		$contact_locked=1;
		$reason="";
	}
	if($from_similar && $contact_locked==1 && $reason=="")
		$contactsGadget=1;
	else
	{ 
		if($contact_locked)
			set_address($jprofile_result);
		else
			$smarty->assign("CONTACT_LOCKED",$contact_locked);
		$smarty->assign("reason",$reason);
		$contactsGadget= $smarty->fetch("contactAddressGadget.htm");
	}
	
	return $contactsGadget;
	
}
function off_call_history()
{
	global $smarty,$data;
	global $jprofile_result;
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/cmr.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
	$membershipObj = new Membership;
	$memHandlerObj = new MembershipHandler;
	$offlineCallCountArr = $memHandlerObj->getAllCount($data["PROFILEID"]);
	$introCall = getIntroCallHistory($data["PROFILEID"],$offlineCallCountArr["EXPIRED"]);
	$introCallDetail["PURCHASED"] = $offlineCallCountArr["TOTAL"];
	$introCallDetail["TO_BE_CALLED"] = $introCall["TOTAL"]+1;
	$introCallDetail["CALLED"] = $introCall["calledCount"];
	$introCallDetail["TO_BE_ADDED"] = $offlineCallCountArr["TOTAL"]-($introCall["TOTAL"]+1);
	$introCallDetail["TO_BE_ADDED_CNT"] = ($offlineCallCountArr["TOTAL"]+$offlineCallCountArr["EXPIRED"]) - $introCall["TOTAL_COUNT"];
	if(in_array($jprofile_result['viewed']['PROFILEID'],$introCall['profile']))
	{
		//If not called only commented firtst time
		if($introCall[CS][$jprofile_result['viewed']['PROFILEID']]=="N" && TeleCommentsinProgress($data[PROFILEID], $jprofile_result['viewed']['PROFILEID']))
			$smarty->assign("OFFLINE_CALL_PROGRESS",1);
		
		$smarty->assign("OFFLINE_ASSISTANT_REM",1);
	}
	else
	{
		if($introCallDetail["TO_BE_ADDED_CNT"]>0)
		{
			$smarty->assign("OFFLINE_ASSISTANT_ADD",1);
		}
	}
	
	$smarty->assign("introCallDetail",$introCallDetail);
}
function TeleCommentsinProgress($first,$second)
{
	if($first && $second)
	{
	$sql        = "SELECT PROFILEID FROM Assisted_Product.AP_MATCH_COMMENTS WHERE PROFILEID='$first' AND MATCH_ID='$second'";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes", $sql, "ShowErrTemplate");
	if($row=mysql_fetch_assoc($res))
	{
		return true;
	}
	}
	return false;
}
//Getting call history records.
function getIntroCallHistory($self_profileid)
{
	return;//issue with the ap function, so removing temporarily
	$introCall = array();
        $sql = "SELECT DISTINCT(MATCH_ID),CALL_STATUS FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID='$self_profileid' ORDER BY REQUEST_DATE";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
        $calledY = 0;
        $calledC = 0;
        $notCalled = 0;
        $introCall["profile"] = array();
	$i=0;
        while($row=mysql_fetch_array($res))
        {
			$introCall["profile"][] = $row["MATCH_ID"];
			if($row["CALL_STATUS"] == "Y")
				$calledY++;
			if($row["CALL_STATUS"] == "C")
				$calledC++;
			if($row["CALL_STATUS"] == "N")
				$notCalled++;
			$introCall[CS][$row["MATCH_ID"]]=$row["CALL_STATUS"];
        }
        $introCall["calledCount"] = $calledY;
        $introCall["notCalledCount"] = $notCalled;
        $introCall["TOTAL"] = $calledY+$notCalled;
        $introCall["TOTAL_COUNT"] = $calledY+$notCalled+$calledC;
        return $introCall;
}

/*This function helps in removing those profiles from visitors who don't satify dpp and income parameter.
Paramters defination
first  --> contain profiles information that required for calculation
second --> jparter obj
third --> income of login user
fourth --> profileid of login user
fifth --> page from which it called, output depends on page.
sixth --> used for visitors function
seventh --> used for visitors function.
*/
function pass_in_visitors($all_data,$jpartnerObj,$income,$profileid,$from_where=0,$time_array=array(),$seen_array=array())
{
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");	

	$jpartnerObj=new Jpartner;
	$mysqlObj=new Mysql;
	if($from_where==1)
		$from_cmr=1;
	if($profileid)//profileid is viewed profileid
        {
                $viewedDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
                $viewedDb=$mysqlObj->connect("$viewedDbName");
        }

	$jpartnerObj->setPartnerDetails($profileid,$viewedDb,$mysqlObj);

	$lage=$jpartnerObj->getLAGE();
	 $hage=$jpartnerObj->getHAGE();
	if(!$lage || !$hage)
		$no_age=1;
	$lheight=$jpartnerObj->getLHEIGHT();
	$hheight=$jpartnerObj->getHHEIGHT();
	if(!$lheight || !$hheight)
		$no_height=1;

	$manglik=explode(",",remove_quotes($jpartnerObj->getPARTNER_MANGLIK()));
	$mstatus=explode(",",remove_quotes($jpartnerObj->getPARTNER_MSTATUS()));
	$caste=display_format($jpartnerObj->getPARTNER_CASTE());
        if($caste)
                $all_caste=get_all_caste($caste);
	$religion=explode(",",remove_quotes($jpartnerObj->getPARTNER_RELIGION()));
	$community=explode(",",remove_quotes($jpartnerObj->getPARTNER_MTONGUE()));
	$country=explode(",",remove_quotes($jpartnerObj->getPARTNER_COUNTRYRES()));
	$par_income=explode(",",remove_quotes($jpartnerObj->getPARTNER_INCOME()));

	//If income is not defined in dpp , then use actual income of profile.
	if(count($par_income)>0 && $par_income[0]!="")
	{
		foreach($par_income as $key=>$val)
			$act_income[]=get_income_sortby_new($val,'','F');
			//$act_income[]=$income_arr[$val];
	}
	else
		$act_income[]=get_income_sortby_new($income,'','F');
		//$act_income[]=$income_arr[$income];


	if(is_array($all_data))
	foreach($all_data as $key=>$val)
	{
		$row=$val;
		$allow=1;
		$oth_age=$row['AGE'];
		$oth_height=$row['HEIGHT'];
		$oth_manglik=$row['MANGLIK'];
		$oth_mstatus=$row['MSTATUS'];
		$oth_caste=$row['CASTE'];
		$oth_religion=$row['RELIGION'];
		$oth_community=$row['MTONGUE'];
		$oth_country=$row["COUNTRY_RES"];
		$oth_income=$row['INCOME'];
		$oth_prof=$row['PROFILEID'];
		if(!($oth_age>=$lage && $oth_age<=$hage))
		{
		 	$allow=0;
		}
		if($allow==1)
			if(!($oth_height>=$lheight && $oth_height<=$hheight))
				$allow=0;
//echo $oth_height."---".$lheight."---".$hheight;
		if($allow==1)
			$allow=check_in_array($mstatus,$oth_mstatus);
		if($allow==1)
			$allow=check_in_array($country,$oth_country);
		if($allow==1)
			$allow=check_in_array($all_caste,$oth_caste);
		if($allow==1)
			$allow=check_in_array($religion,$oth_religion);
		if($allow==1)
			$allow=check_in_array($community,$oth_community);
		if($allow==1)
			$allow=check_in_array($manglik,$oth_manglik);

		if($allow==1)
		if(is_array($act_income))
		{
			foreach($act_income as $key=>$val)
			{
				$allow=0;
				if(check_in($val,$oth_income))
				{
					$allow=1;
					break;
				}
				
			}
		}
		if($allow==1)
		{
			$return_prof[]=$oth_prof;
			if($from_cmr)
			{
				$data_final_profile[]=$row['PROFILEID'].",".$time_array[$row['PROFILEID']].",".$seen_array[$row['PROFILEID']];
			}
		}
		
		
	}
	if($from_cmr)
		return $data_final_profile;
	else
		return $return_prof;

	
}

function check_in($val,$oth_income)
{
	$arr1=explode(",",$val);
        for($i=0;$i<count($arr1);$i++)
        {
                if($arr1[$i]==$oth_income)
                        return 1;
        }
        return 0;
}

function check_in_array($array,$value)
{
	if(count($array)>0 )
		if(!(count($array)==1 && $array[0]=="" && $array[0]!='DM'))
		if(!in_array($value,$array))
			return 0;
	return 1;
}
function remove_quotes($value)
{
        return str_replace("'","",$value);
}

//This function will set the contact details if required.
function call_directly($jprofile_result,$sender_sub,$reciever_sub,$contact_type,$who)
{
	//This is get variable called from search and view similar.
	global $from_search;
	global $from_symfony;
	//print_r($jprofile_result);
	$sender_prof=$jprofile_result['viewer']['PROFILEID'];
	$reciever_prof=$jprofile_result['viewed']['PROFILEID'];
	$filter=$jprofile_result['IS_FILTER'];
	$same_gender=$jprofile_result['SAME_GENDER'];
	
	$type=$contact_type;
	$return_mes=ret_filter_gender_pri_ph($filter,$same_gender,$type,$who,$jprofile_result);	
	$allow=0;
	$direct_show=0;

	//Don't show filter message for contact in[I,RA,A] state.
	$ctype=$type;
	if($who=="SENDER")
		$ctype="R".$type;
	if(in_array($ctype,array('I','A','RA')))	
		$filter='';

	if(!$return_mes)
		$alreadyViewed = alreadyViewed($sender_prof,$reciever_prof,$sender_sub);
	if($alreadyViewed == "S")
	{
		$allow=1;
		$direct_show=1;
	}
	elseif($filter && $alreadyViewed != "R")
		$return_mes="You cannot see the contact details of this profile as the profile has filtered you.";
	if(!$return_mes && $alreadyViewed)
	{
		if($alreadyViewed == "S")
		{
			$allow=1;
			$direct_show=1;
		}
		else
		{
			//$verify_ph=isValidPhone($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewer"]["PHONE_MOB"]);
			$verify_ph = getPhoneStatus($jprofile_result["viewer"]);
			if($verify_ph!='Y')
			{
				if($jprofile_result["viewer"]["PHONE_RES"])
					$LANDLINE = $jprofile_result["viewer"]["STD"]."-".$jprofile_result["viewer"]["PHONE_RES"];
				if($jprofile_result["viewer"]["PHONE_MOB"])
					$MYMOBILE = $jprofile_result["viewer"]["PHONE_MOB"];                    
				$con_det_message="Please <a href=\"/profile/myjs_verify_phoneno.php?width=700\" class=\"thickbox\" onclick=\"javascript:{close_all_con_layer();$.colorbox({href:'/profile/myjs_verify_phoneno.php'});return false;}\">Verify your number</a> before using this feature.";
			}
			else
			{
				$allow=1;
				$direct_show=1;
			}
		}
	}
	else
	{
		if($sender_sub && !$filter && !$same_gender && !$jprofile_result["INCOMPLETE_USER"] && !$jprofile_result["UNDERSCREEN_USER"])
		{
			$result=contacts_left_to_view($sender_prof);
			$viewed=$result['VIEWED'];
			$alloted=$result['ALLOTED'];
			if($viewed>=$alloted)
				$comm_mes="You have <span class='dark_orange b t14'>0</span> contacts left to view.<BR><div style='height:3px'>&nbsp;</div>";
			else
			{
				//$verify_ph=isValidPhone($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewer"]["PHONE_MOB"]);
				$verify_ph = getPhoneStatus($jprofile_result["viewer"]);
				if($verify_ph!='Y')
				{
					if($jprofile_result["viewer"]["PHONE_RES"])
						$LANDLINE = $jprofile_result["viewer"]["STD"]."-".$jprofile_result["viewer"]["PHONE_RES"];
					if($jprofile_result["viewer"]["PHONE_MOB"])
						$MYMOBILE = $jprofile_result["viewer"]["PHONE_MOB"];				
					$return_mes="Please <a href=\"/profile/myjs_verify_phoneno.php?width=700\" class=\"thickbox\" onclick=\"javascript:{close_all_con_layer();$.colorbox({href:'/profile/myjs_verify_phoneno.php'});return false;}\">Verify your number</a> before using this feature.";
				}
				if($return_mes)
					$con_det_message=$return_mes;
				else
					$allow=1;
			}
		}
		else
		{
			//$return_mes=ret_filter_gender_pri_ph($filter,$same_gender,$type,$who,$jprofile_result);
			if($return_mes)
				$con_det_message=$return_mes;
			else
				$comm_mes="Only paid members can view contact details directly.<BR><!--div style='height:3px'>&nbsp;</div-->";
		}
		//When to put br in message.[because size is different in layer and page.
		$to_br="<BR>";
		if($from_symfony)
			$to_br="";
		if($con_det_message)
		{
		}
		elseif($type=="")
		{
			$con_det_message="$comm_mes Please <a class='blink b' href='$SITE_URL/profile/mem_comparison.php?from_source=Express_interest_tab'>Upgrade your membership</a>";
			
			if($from_search)
			{
				$profile_url=$_GET['profile_url'];
                                //$con_det_message=$con_det_message." or <BR> <a style=\"pointer:cursor\" class=\"blink b\" onclick=\"javascript:{show_exp_layer('PROFILE_".$profile_url."','".$profile_url."',event);}\">Express Interest</a>";		
				$con_det_message=$con_det_message;
			}
			else
			{
				$con_det_message=$con_det_message." or $to_br <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Express Interest</a>";
			}
		}
		elseif($type=='I')
		{
			if($who=='SENDER')
			{
				$con_det_message="$comm_mes Please <a class='blink b' href='$SITE_URL/profile/mem_comparison.php?from_source=Express_interest_tab'>Upgrade your membership</a>";
				if(!$from_search)
					$con_det_message=$con_det_message." or <BR> <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Send Reminder</a>";
			}
			else
			{
				$con_det_message="$comm_mes Please <a class='blink b' href='$SITE_URL/profile/mem_comparison.php?from_source=Express_interest_tab'>Upgrade your membership</a>";
				if(!$from_search)
                                        $con_det_message=$con_det_message." or $to_br <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Accept</a>";
			}
		}
		elseif($type=='A')
		{
			$con_det_message="$comm_mes Please <a class='blink b' href='$SITE_URL/profile/mem_comparison.php?from_source=Express_interest_tab'>Upgrade your membership</a>";

		}
		elseif($type=='D')
		{
			if($who=="")
				$con_det_message="Please <a class='blink b' href='$SITE_URL/profile/mem_comparison.php?from_source=Express_interest_tab'>Upgrade your membership</a>";
			if(!$from_search)
                                        $con_det_message=$con_det_message." or $to_br <a href=\"#\" class=\"blink b\" onclick=\"javascript:{show_layer('show_express','show_contact','expr_layer','con_layer');return false;}\">Accept</a>";
		}
			
	}
	$return_arr['ALLOW']=$allow;
	$return_arr['DIRECT_SHOW']=$direct_show;
	$return_arr["VIEWED"]=$viewed;
	$return_arr['ALLOTED']=$alloted;
	$return_arr['con_det_message']=$con_det_message;
	if($alreadyViewed == "S" || $alreadyViewed == "R")
		$return_arr['ALREADY_VIEWED']=1;
	return $return_arr;
}
function call_directly_mobile($jprofile_result,$sender_sub,$reciever_sub,$contact_type,$who)
{
	//This is get variable called from search and view similar.
	global $from_search,$profilechecksum,$nav_type;

	//print_r($jprofile_result);
	$sender_prof=$jprofile_result['viewer']['PROFILEID'];
	$reciever_prof=$jprofile_result['viewed']['PROFILEID'];
	$filter=$jprofile_result['IS_FILTER'];
	$same_gender=$jprofile_result['SAME_GENDER'];
	
	$type=$contact_type;
	$return_mes=ret_filter_gender_pri_ph($filter,$same_gender,$type,$who,$jprofile_result);	
	$allow=0;
	$direct_show=0;
	if(!$return_mes)
		$alreadyViewed = alreadyViewed($sender_prof,$reciever_prof,$sender_sub);
	if($alreadyViewed == "S")
	{
		$allow=1;
		$direct_show=1;
	}
	elseif($filter && $alreadyViewed != "R")
		$return_mes="You cannot see the contact details of this profile as the profile has filtered you.";
	if(!$return_mes && $alreadyViewed)
	{
		if($alreadyViewed == "S")
		{
			$allow=1;
			$direct_show=1;
		}
		else
		{
			//$verify_ph=isValidPhone($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewer"]["PHONE_MOB"]);
			$verify_ph = getPhoneStatus($jprofile_result["viewer"]);
			if($verify_ph!='Y')
			{
				if($jprofile_result["viewer"]["PHONE_RES"])
					$LANDLINE = $jprofile_result["viewer"]["STD"]."-".$jprofile_result["viewer"]["PHONE_RES"];
				if($jprofile_result["viewer"]["PHONE_MOB"])
					$MYMOBILE = $jprofile_result["viewer"]["PHONE_MOB"];                    
					$con_det_message="Please visit www.Jeevansathi.com from your personal computer and verify your mobile number.";
			}
			else
			{
				$allow=1;
				$direct_show=1;
			}
		}
	}
	else
	{
		if($sender_sub && !$filter && !$same_gender && !$jprofile_result["INCOMPLETE_USER"] && !$jprofile_result["UNDERSCREEN_USER"])
		{
			$result=contacts_left_to_view($sender_prof);
			$viewed=$result['VIEWED'];
			$alloted=$result['ALLOTED'];
			if($viewed>=$alloted)
				$comm_mes="You have <span class='dark_orange b t14'>0</span> contacts left to view.<BR><div style='height:3px'>&nbsp;</div>";
			else
			{
				//$verify_ph=isValidPhone($jprofile_result["viewer"]["PROFILEID"], $jprofile_result["viewer"]["PHONE_MOB"]);
				$verify_ph = getPhoneStatus($jprofile_result["viewer"]);
				if($verify_ph!='Y')
				{
					if($jprofile_result["viewer"]["PHONE_RES"])
						$LANDLINE = $jprofile_result["viewer"]["STD"]."-".$jprofile_result["viewer"]["PHONE_RES"];
					if($jprofile_result["viewer"]["PHONE_MOB"])
						$MYMOBILE = $jprofile_result["viewer"]["PHONE_MOB"];				
					$return_mes="Please visit www.Jeevansathi.com from your personal computer and verify your mobile number.";
				}
				if($return_mes)
					$con_det_message=$return_mes;
				else
					$allow=1;
			}
		}
		else
		{
			//$return_mes=ret_filter_gender_pri_ph($filter,$same_gender,$type,$who,$jprofile_result);
			if($return_mes)
				$con_det_message=$return_mes;
			else
				$comm_mes="Only paid members can view contact details directly.<BR><!--div style='height:7px'>&nbsp;</div-->";
		}
		if($con_det_message)
		{
		}
		elseif($type=="")
		{
				$con_det_message=$comm_mes;
			
			if($from_search)
			{
				$profile_url=$_GET['profile_url'];
                                //$con_det_message=$con_det_message." or <BR> <a style=\"pointer:cursor\" class=\"blink b\" onclick=\"javascript:{show_exp_layer('PROFILE_".$profile_url."','".$profile_url."',event);}\">Express Interest</a>";		
				$con_det_message=$con_det_message;
			}
			else
				$con_det_message=$con_det_message." or <BR><a href='invoke_contact_engine.php?checksum=$checksum&profilechecksum=$profilechecksum&to_do=eoi&$cc_navigator&nav_type=".$nav_type."'><input type=\"button\" value=\"Express Interest\" class=\"normalbtn fbld\" style=\" width:110px;\" /></a>"; 
		}
		elseif($type=='I')
		{
			if($who=='SENDER')
			{
					$con_det_message=$comm_mes;
				if(!$from_search)
					$con_det_message=$con_det_message." or <BR><a href='invoke_contact_engine.php?checksum=$checksum&profilechecksum=$profilechecksum&to_do=reminder&$cc_navigator&nav_type=".$nav_type."'><input type=\"button\" value=\"Send Reminder\"        class=\"normalbtn fbld\" style=\" width:110px;\" /></a>";
			}
			else
			{
					$con_det_message=$comm_mes;
				if(!$from_search)
					$con_det_message=$con_det_message." or <BR><a href='invoke_contact_engine.php?checksum=$checksum&profilechecksum=$profilechecksum&to_do=accept&$cc_navigator&nav_type=".$nav_type."'><input type=\"button\" value=\"Accept\"  class=\"normalbtn fbld\" style=\" width:110px;\" /></a>";
			}
		}
		elseif($type=='A')
		{
					$con_det_message=$comm_mes;

		}
		elseif($type=='D')
		{
			if($who=="")
					$con_det_message=$comm_mes;
			if(!$from_search)
					$con_det_message=$con_det_message." or <BR><a href='invoke_contact_engine.php?checksum=$checksum&profilechecksum=$profilechecksum&to_do=accept&$cc_navigator&nav_type=".$nav_type."'><input type=\"button\" value=\"Accept\"  class=\"normalbtn fbld\" style=\" width:110px;\" /></a>";
		}
			
	}
	$return_arr['ALLOW']=$allow;
	$return_arr['DIRECT_SHOW']=$direct_show;
	$return_arr["VIEWED"]=$viewed;
	$return_arr['ALLOTED']=$alloted;
	$return_arr['con_det_message']=$con_det_message;
	if($alreadyViewed == "S" || $alreadyViewed == "R")
		$return_arr['ALREADY_VIEWED']=1;
	return $return_arr;
}
//function to total contact left to view and total conctact alloted.
function contacts_left_to_view($viewer)
{
	$sql="select ALLOTED,VIEWED from jsadmin.CONTACTS_ALLOTED where PROFILEID='$viewer'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($row=mysql_fetch_assoc($res))
	{
		return $row;
	}
	else
	{
		return array("ALLOTED"=>0,"VIEWED"=>0);
	}
}

/*
function insertIntoViewLog($viewer,$viewed)
{
        $date=date("Y-m-d G:i:s");
        $sql="insert ignore into jsadmin.VIEW_CONTACTS_LOG (`VIEWER`,`VIEWED`,`DATE`,`CREDITED`) values('$viewer','$viewed','$date','N')";
        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
*/

/*Check if contact details could be viewed directly or not
function direct_show($sender_prof,$reciever_prof, $jprofile_result, $credited)
{
	$direct_show = "";
	if(!isPhonePresent($jprofile_result))
		$direct_show = "phoneNotPresent";
	if(!$direct_show && $credited=="N")
		$direct_show = "alreadyViewed";
	if($direct_show!='phoneNotPresent')
	{
		$phoneVerified = isValidPhone($jprofile_result["viewed"]["PROFILEID"], $jprofile_result["viewed"]["PHONE_MOB"]);
		if(!$phoneVerified)
			$direct_show = "phoneNotVerified";
		elseif($phoneVerified == "LANDLINE")
			$direct_show = "mobNotVerified";
		elseif($phoneVerified == "MOB")
			$direct_show = "landlineNotVerified";
	}
	if($direct_show && !$credited)
		insertIntoViewLog($sender_prof,$reciever_prof);
	$direct_show = "";
	return $direct_show;
}*/

function sendViewContactMailer($viewed, $viewer, $jprofile_result)
{
	global $smarty,$CITY_DROP,$income_map,$HEIGHT_DROP,$OCCUPATION_DROP,$RELIGIONS,$CASTE_DROP,$FAMILY_BACK_DROP,$FAMILY_TYPE,$FAMILY_STATUS,$MOTHER_OCC_DROP,$FAMILY_VALUES,$MSTATUS,$COUNTRY_DROP;
	if(!$jprofile_result)
	{
		include_once("reduce_jprofilequery.php");
		limiting_jprofile_query($viewer,$viewed);
	}

	$viewerDetail = $jprofile_result["viewer"];
	$viewerDetail["MTONGUE"] = label_select("MTONGUE",$viewerDetail['MTONGUE']);
	$viewerDetail["HEIGHT"] = $HEIGHT_DROP["$viewerDetail[HEIGHT]"];
	$viewerDetail["CITY_RES"] = $CITY_DROP["$viewerDetail[CITY_RES]"];
	$viewerDetail["INCOME"] = $income_map["$viewerDetail[INCOME]"];
	$viewerDetail["OCCUPATION"] = $OCCUPATION_DROP["$viewerDetail[OCCUPATION]"];
	$educationLevel = label_select("EDUCATION_LEVEL_NEW",$viewerDetail["EDU_LEVEL_NEW"]);
	$viewerDetail["EDU_LEVEL_NEW"] = $educationLevel[0];
	$viewerDetail["MTONGUE"] = $viewerDetail["MTONGUE"][0];
	$viewerDetail["RELIGION_LABEL"] = $RELIGIONS["$viewerDetail[RELIGION]"];
	$viewerDetail["CASTE"] = $CASTE_DROP["$viewerDetail[CASTE]"];
	$viewerDetail["FAMILY_VALUES"] = $FAMILY_VALUES["$viewerDetail[FAMILY_VALUES]"];
	$viewerDetail["FAMILY_TYPE"] = $FAMILY_TYPE["$viewerDetail[FAMILY_TYPE]"];
	$viewerDetail["FAMILY_STATUS"] = $FAMILY_STATUS["$viewerDetail[FAMILY_STATUS]"];
	$viewerDetail["MOTHER_OCC"] = $MOTHER_OCC_DROP["$viewerDetail[MOTHER_OCC]"];
	$viewerDetail["FAMILY_BACK"] = $FAMILY_BACK_DROP["$viewerDetail[FAMILY_BACK]"];
	$smallTag = $viewerDetail["AGE"];
	if($viewerDetail["HEIGHT"]) $smallTag.=", ".$viewerDetail["HEIGHT"];
	if($viewerDetail["RELIGION_LABEL"]) $smallTag.=", ".$viewerDetail["RELIGION_LABEL"];
	if($viewerDetail["MTONGUE"]) $smallTag.=", ".$viewerDetail["MTONGUE"];
	if($viewerDetail["MSTATUS"]) $smallTag.=", ".$MSTATUS[$viewerDetail["MSTATUS"]];
	if($viewerDetail["CASTE"]) $smallTag.=", ".$viewerDetail["CASTE"];
	if($viewerDetail["EDU_LEVEL_NEW"]) $smallTag.=", ".$viewerDetail["EDU_LEVEL_NEW"];
	if($viewerDetail["INCOME"]) $smallTag.=", ".$viewerDetail["INCOME"];
	if($viewerDetail["OCCUPATION"]) $smallTag.=", ".$viewerDetail["OCCUPATION"];
	if($viewerDetail["OCCUPATION"] && $viewerDetail["CITY_RES"])
		$smallTag.=" in ";
	if($viewerDetail["CITY_RES"] && !$viewerDetail["OCCUPATION"])
		$smallTag.=", ";
	if($viewerDetail["CITY_RES"])  $smallTag.=$viewerDetail["CITY_RES"];
	if($viewerDetail["COUNTRY_RES"])  $smallTag.=", ".$COUNTRY_DROP[$viewerDetail["COUNTRY_RES"]];
	$viewerDetail["smallTag"] = $smallTag;
	$viewerDetail["PROFILECHECKSUM"] = md5($viewerDetail['PROFILEID'])."i".$viewerDetail['PROFILEID'];
	$checksum=md5($jprofile_result["viewed"]["PROFILEID"])."i".$jprofile_result["viewed"]["PROFILEID"];
	$protect_obj=new protect;
	$viewerDetail["echecksum"]=$protect_obj->js_encrypt($checksum,$jprofile_result["viewed"]["EMAIL"]);
	$viewerDetail["checksum"]=$checksum;
	
	if($viewerDetail["PARENT_CITY_SAME"]=="Y")
		$viewerDetail["LIVE_WITH_PARENTS"] = "Yes";
	elseif($viewerDetail["PARENT_CITY_SAME"]=="N")
		$viewerDetail["LIVE_WITH_PARENTS"] = "No";
	elseif($viewerDetail["PARENT_CITY_SAME"]=="D")
		$viewerDetail["LIVE_WITH_PARENTS"] = "Not applicable";
	$screening=$viewerDetail["SCREENING"];
	if(!isFlagSet("YOURINFO",$screening))
		$viewerDetail["YOURINFO"] = "";
	if(!isFlagSet("FAMILYINFO",$screening))
		$viewerDetail["FAMILYINFO"] = "";
	if(!isFlagSet("JOB_INFO",$screening))
		$viewerDetail["JOB_INFO"] = "";
	if(!isFlagSet("EDUCATION",$screening))
		$viewerDetail["EDUCATION"] = "";
	if($viewerDetail["RELIGION"]==1)
	{
		if(!isFlagSet("GOTHRA",$screening))
			$viewerDetail["GOTHRA"] = "";
	}
	else
	{
		$viewerDetail["NAKSHATRA"] = "";
		$viewerDetail["GOTHRA"] = "";
	}
	$displayPhoto = displayPhoto($viewerDetail["PROFILEID"], $viewerDetail["GENDER"], $viewerDetail['HAVEPHOTO'], $viewerDetail['PHOTO_DISPLAY'], $viewerDetail['PRIVACY']);
	$viewerDetail["DISPLAY_PHOTO"] = $displayPhoto;
	$smarty->assign("viewerDetail",$viewerDetail);
	$from = "info@jeevansathi.com";
	$to = $jprofile_result["viewed"]["EMAIL"];
	$subject = "A Jeevansathi member has shown interest in you by viewing your contact details";
	$mailer = $smarty->fetch("viewContactMailer.htm");
	if($to)
		send_email($to,$mailer,$subject,$from,"","","","","","",1);
}

//If receiver has been viewed more then 10 times in a day then restrict viewer to view his/her contact detail
function checkSpamForReceiver($viewer, $viewed)
{
	$today = date("Y-m-d");
	$sql = "SELECT COUNT(*) cnt FROM jsadmin.VIEW_CONTACTS_LOG WHERE VIEWED='$viewed' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."' AND DATE BETWEEN '$today 00:00:00' AND '$today 23:59:59'";
	$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	if($row[0] >= 10)
		return false;
	return true;
}

//Check if phone number of receiver is present or not
function isPhonePresent($jprofile_result)
{
	if(($jprofile_result["viewed"]["SHOWPHONE_RES"]=="Y" && $jprofile_result["viewed"]["PHONE_RES"]!="") || ($jprofile_result["viewed"]["SHOWPHONE_MOB"]=="Y" && $jprofile_result["viewed"]["PHONE_MOB"]!=""))
		return true;
	return false;
}

/* code removed, function is not in use
 * // IVR - Check for phone Verification
*/
/*
function isValidPhone($profileid, $mobile)
{
	$phoneVerified = false;
	include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
	$phoneVerified = getPhoneValidity($profileid);
	if($phoneVerified!="MOB" && $phoneVerified!="MOBLANDLINE")
	{
		$mobile=substr($mobile,-10,10);
		$sql_mob="SELECT count(*) as cnt  FROM newjs.MOBILE_VERIFICATION_SMS  WHERE MOBILE IN ('0$mobile','+91$mobile','91$mobile','$mobile')";
		$result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
		$row_mob=mysql_fetch_array($result_mob);
		if($row_mob["cnt"]>0)
		{
			if($phoneVerified=="LANDLINE")
				$phoneVerified = "MOBLANDLINE";
			else
				$phoneVerified = "MOB";
		}
	}
	return $phoneVerified;
}
*/

//Checks if contact is already viewed by sender through call directly feature.
function ifViewedBySender($viewer,$viewed,$sender_sub)
{
	$cnt = 0;
	if($sender_sub)
	{
		$sql="select count(*) cnt from jsadmin.VIEW_CONTACTS_LOG where VIEWER='$viewer' and VIEWED='$viewed' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row = mysql_fetch_array($res);
		$cnt = $row[0];
	}
	return $cnt;
}

//Checks if contact is already viewed by receiver through call directly feature.
function ifViewedByReceiver($viewer,$viewed)
{
	$cnt = 0;
        $sql="SELECT count(*) cnt FROM jsadmin.VIEW_CONTACTS_LOG WHERE `VIEWER` = '$viewed' AND `VIEWED` = '$viewer' AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$row = mysql_fetch_array($res);
	$cnt = $row[0];
	return $cnt;
}

function alreadyViewed($viewer,$viewed,$sender_sub)
{
	$isViewed = false;
	$viewedBySender = ifViewedBySender($viewer,$viewed,$sender_sub);
	$viewedByReceiver = ifViewedByReceiver($viewer,$viewed);
	if($viewedBySender)
		$isViewed = "S";
	elseif($viewedByReceiver)
		$isViewed = "R";
	return $isViewed;
}

//if user is spammer than extra checks for dpp check applied
function check_dpp($is_spam,$FILTER_HAGE,$FILTER_LAGE,$PARTNER_COUNTRYRES,$PARTNER_CASTE,$PARTNER_MTONGUE)
{

	global $jprofile_result;
	$profileid=$jprofile_result['viewer']['PROFILEID'];
	if(is_spam($profileid) || $is_spam)
	{
		$spam='yes';	
	}
	if(!$spam)
		return false;

	//print_r($jprofile_result);
	$CASTE=$jprofile_result['viewer']['CASTE'];
	$MTONGUE=$jprofile_result['viewer']['MTONGUE'];
	$HEIGHT=$jprofile_result['viewer']['HEIGHT'];
	$AGE=$jprofile_result["viewer"]["AGE"];
	$COUNTRY=$jprofile_result["viewer"]["COUNTRY_RES"];	
	if($AGE>=$FILTER_LAGE && $AGE<=$FILTER_HAGE)
	{}
	else
		return true;
	if(is_array($PARTNER_COUNTRYRES) && $COUNTRY)
	{
		if(in_array($COUNTRY,$PARTNER_COUNTRYRES))
		{}
	else
		return true;
	}
	if($PARTNER_CASTE)
	$PARTNER_CASTE_LIST=get_all_caste($PARTNER_CASTE);

	if(is_array($PARTNER_CASTE_LIST) && $CASTE)
	{
		if(in_array($CASTE,$PARTNER_CASTE_LIST))
		{}
		else
			return true;
	}
	if(is_array($PARTNER_MTONGUE) && $MTONGUE)
	{
		if(in_array($MTONGUE,$PARTNER_MTONGUE))
		{}
		else
			return true;
	}
	return false;
}

//This function will check for mobile and landline verification return 1 if any one is verified , return 0 is not single one is verified.	
function check_invalid_ph_landline($jprofile_result,$viewed_subs="")
{
	//Now showing the contact details even if login user ph. no is not verified.
	return 1;

	// Not required the below code
	global $smarty;
	$contact_locked=1;
	//Checking if phone number (mobile/landline) of the login user is not verified
        if($jprofile_result['viewer']['COUNTRY_RES']==51 && $viewed_subs!='EV')
        {
                $mobile=$jprofile_result['viewer']['PHONE_MOB'];
                //$landline=$jprofile_result['viewer']['PHONE_RES'];
		$pass_mobile='';
                if($mobile)
                {
                        $mobile=substr($mobile,-10,10);

                        $sql_mob="SELECT count(*) as cnt  FROM newjs.MOBILE_VERIFICATION_SMS  WHERE MOBILE IN ('0$mobile','+91$mobile','91$mobile','$mobile')";
                        $result_mob=mysql_query_decide($sql_mob) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_mob,"ShowErrTemplate");
                        $row_mob=mysql_fetch_array($result_mob);

                        if($row_mob["cnt"]==0)
                        {
                                $contact_locked=0;
                                $smarty->assign("MYMOBILE",$mobile);
                                $smarty->assign("MOB_NOT_VERI",1);
                        }
			else
				$pass_mobile=1;
                }
		if(!$pass_mobile)
		{
	                // IVR - Check for phone Verification
        	        include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsivrFunctions.php");
                	$phoneCheck = getPhoneValidity($jprofile_result['viewer']['PROFILEID']);
	                if(!$phoneCheck){
        	                $contact_locked=0;
                	        $phone_res=$jprofile_result['viewer']['PHONE_RES'];
                        	$std      =$jprofile_result['viewer']['STD'];
	                        $smarty->assign("LANDLINE",$phone_res);
        	                $smarty->assign("STD",$std);
                	        $smarty->assign("LANDLINE_NOT_VERI",1);
                                $smarty->assign("MOB_NOT_VERI",1);
                	}
			else
				$contact_locked=1;
		}
		
                // end IVR - Check
        }
	return $contact_locked;
}
function ret_filter_gender_pri_ph($filter,$same_gender,$type,$who,$jprofile_result)
{
	if($same_gender)
        {
                $con_det_message="You cannot see the contact details of this profile as the profile is of the same gender.";
        }
	elseif($jprofile_result["viewer"]["ACTIVATED"]!="Y" && $jprofile_result["viewer"])
		$con_det_message="You can view the contact details of the user only after your profile is screened";
	elseif($jprofile_result["viewer"]["INCOMPLETE"]=="Y" && $jprofile_result["viewer"])
		$con_det_message="You need to complete your profile before viewing the user's contact details.";
	elseif($type=='D' && $who=='SENDER')
	{
		$con_det_message="You cannot see the contact details of this profile as the profile has declined any further contacts with you.";
	}
	elseif($jprofile_result['viewed']['ACTIVATED'] == "D")
	{
		 $con_det_message="You cannot see the contact details of this profile as the profile is deleted";
	}
	elseif($jprofile_result['UNDERSCREEN_USER'])
	{
		$con_det_message=$jprofile_result['UNDERSCREEN_USER'];
	}
	elseif($jprofile_result['INCOMPLETE_USER'])
	{
		$con_det_message=$jprofile_result['INCOMPLETE_USER'];
	}
	return $con_det_message;
}

//This function will check whetner profile filter with other data.
function check_spammer_filter($jprofile_result,$action='')
{
	$myprofileid=$jprofile_result['viewer']['PROFILEID'];
	$hisprofileid=$jprofile_result['viewed']['PROFILEID'];
	$return=getFilteredContact($myprofileid, $hisprofileid,'','',$action);
	if($return=='Y')
		return 1;
	return 0;
}
function getIncompleteUnscreenedStatus($sender_profileid)
{
        $sql="select ACTIVATED,INCOMPLETE from JPROFILE where  activatedKey=1 and PROFILEID='$sender_profileid'";
        $res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        $row=mysql_fetch_array($res);

	//If record not found in active partition then search in Entire JPROFILE..
	if(!$row)
	{
		$sql="select ACTIVATED,INCOMPLETE from JPROFILE where  PROFILEID='$sender_profileid' and activatedKey=0";
        	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	        $row=mysql_fetch_array($res);
	}
        $tempstatus["ACTIVATED"] = $row["ACTIVATED"];
        $tempstatus["INCOMPLETE"] = $row["INCOMPLETE"];
        return $tempstatus;
}

function temporaryInterestSuccess($incomplete, $activated)
{
	$success = false;
	if($incomplete == "Y")
		$success = "incomplete";
	elseif($activated == "N" || $activated == "U" || $activated == "P")
		$success = "underScreened";
	return $success;
}
function initiateTemporaryInterest($sender_details, $receiver_details, $draft_name, $message, $DRA_MES, $contact_status, $paid, $tempParam, $stype)
{
	$sender_profileid = $sender_details["PROFILEID"];
	$receiver_profileid = $receiver_details["PROFILEID"];
	$receiver_username = $receiver_details["NAME"];
	$error = "";
	/******If sender is offline member then disallow him for making contact****/
	$sender_source = $sender_details["SOURCE"];
	if($sender_source == "ofl_prof")
		$sender_offline = true;
	if(!$sender_source)
		$sender_offline=get_source($sender_profileid);
	if($sender_offline)
		$error=disable_offline_contacts($sender_profileid);
	/****Ends here******/

        if($contact_status == "I" && !$error)
        {
		$tempContactArr = ifTempContactExists($sender_profileid, $receiver_profileid);
		if($tempContactArr[$receiver_profileid])
			$error = "You have already contacted ".$receiver_username.". We will deliver the interest once your profile goes live";
		else
		{
			if($sender_profileid && $receiver_profileid)
			{
				//Update memcache for temporary contacts made
				updateMemcacheForContactsMade($sender_profileid, $receiver_profileid, true);
				$draft_message = $DRA_MES[$draft_name];
				$sql = "INSERT ignore INTO CONTACTS_TEMP(SENDER, RECEIVER, CUST_MESSAGE, DRAFT_NAME, DRAFT_MESSAGE, STYPE, TIME, DELIVERED) VALUES('$sender_profileid', '$receiver_profileid', '".addslashes($message)."', '".addslashes($draft_name)."', '".addslashes($draft_message)."','$stype', now(), 'N')";
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			if($tempParam == "underScreened")
				$layer_message = "Your interest will be delivered once your profile is screened and made live";
			elseif($tempParam == "incomplete")
			{
				global $SITE_URL;
				$profilechecksum=md5($sender_profileid) ."i". $sender_profileid;
				$layer_message = "Your interest will be delivered once you complete the profile. Please <a href=\"$SITE_URL/profile/viewprofile.php?checksum=&profilechecksum=$profilechecksum&EditWhatNew=incompletProfile\">click here</a> to complete your profile";
			}
		}
        }
	$tempContact["ERROR_MESSAGE"] = $error;
	$tempContact["LAYER_MESSAGE"] = $layer_message;
	return $tempContact;
}

function updateMemcacheForContactsMade($sender_profileid, $receiver_profileid, $temporaryContact=false)
{
	if(!$temporaryContact)
		$CONTACT_STATUS_FIELD['NOT_REP']=1;
	$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=1;
	$CONTACT_STATUS_FIELD['MONTH_INI_BY_ME']=1;
	$CONTACT_STATUS_FIELD['TODAY_INI_BY_ME']=1;
	$CONTACT_STATUS_FIELD['WEEK_INI_BY_ME']=1;
	updatememcache($CONTACT_STATUS_FIELD,$sender_profileid);
	unset($CONTACT_STATUS_FIELD);
}

function ifTempContactExists($sender_profileid, $receiver_profileid)
{
	if(is_array($receiver_profileid))
	{
		$receiver_profileids = implode(",", $receiver_profileid);
                $sql = "SELECT COUNT(1) cnt, RECEIVER FROM CONTACTS_TEMP WHERE SENDER = '$sender_profileid' AND RECEIVER IN ($receiver_profileids) AND DELIVERED='N' GROUP BY RECEIVER";
                $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                while($row=mysql_fetch_array($res))
		{
			$tempContact[$row["RECEIVER"]] = $row["cnt"];
		}
	}
	else
	{
		$sql = "SELECT COUNT(1) cnt FROM CONTACTS_TEMP WHERE SENDER = '$sender_profileid' AND RECEIVER = '$receiver_profileid' AND DELIVERED='N'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		$tempContact[$receiver_profileid] = $row["cnt"];
	}
	return $tempContact;
}

function getTemporaryContactsCount($profileid)
{
        $today = date("Y-m-d");
        $sql = "SELECT COUNT(1) cnt, DATE(TIME) date FROM newjs.CONTACTS_TEMP WHERE SENDER = '$profileid' AND DELIVERED='N' GROUP BY date";
        $res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
        while($row = mysql_fetch_array($res))
        {
                if($row["date"] == $today)
                        $tempDayContactCount = $row["cnt"];
                $tempOverAllContactCount = $row["cnt"] + $tempOverAllContactCount;
        }
        $temporaryContactCount = array("tempDayContactCount"=>$tempDayContactCount,"tempOverAllContactCount"=>$tempOverAllContactCount);
        return $temporaryContactCount;
}

//This variable will be used by preset_messages.php script to put address details in def message.
function set_address_details($phone_mob,$phone_res,$phone_std,$phone_isd,$myEmail)
{
	global $own_addr;
	$own_addr['EMAIL']=$myEmail;
	if($phone_std=="")
		$phone_std=$phone_isd;
	else
		$phone_std="$phone_isd-$phone_std";

//echo $phone_std."nikhil";

	if($phone_mob)
		$own_addr['PHONE']=$phone_mob;
	if($phone_res)
		if($own_addr['PHONE'])
			$own_addr['PHONE']=$phone_mob.", $phone_std-$phone_res";
		else
			$own_addr['PHONE']="$phone_std-$phone_res";
}

/*		Function created by NIKHIL on sep 23, 2010
		This function will check all type of contact limit (day,month,week, overall)
		Exception: If any limit crossed proper error message is returned.
		$profileid --> Profileid of the user
		$total_contact --> No. of contact user is trying to made at one go.
		$add_slashes --> Check if addslashes is required in error_message
*/
function check_all_contact_limit($profileid,$total_contact,$add_slashes=0)
{
	global $from_viewprofile;
	//data storing the relevant information of the user.
	global $data;
	global $from_search;
	global $fromNewSearch;
	global $searchId;
	//Fetching all the contacts made by user
	assign_contact_history($profileid);	
	
	if($profileid)
	{
		$total_ini_total=$data['TODAY_INI_TOTAL']+$total_contact;
		$week_ini_total=$data['WEEK_INI_TOTAL']+$total_contact;
		$month_ini_total=$data['MONTH_INI_TOTAL']+$total_contact;
		$total_contacts_made=$data['TOTAL_CONTACTS_MADE']+$total_contact;
		$day_limit=$data['DAY_LIMIT'];
		$weekly_limit=$data['WEEKLY_LIMIT'];
		$month_limit=$data['MONTH_LIMIT'];
		$overall_limit=$data['OVERALL_LIMIT'];
		$subscription=$data['SUBSCRIPTION'];
		$notValidNumber_limit=$data[NOT_VALID_NUMBER_LIMIT];
		
		if($total_ini_total>$day_limit )
		{
				$TYPE='T';
				$contact_limit_message="Contact cannot be initiated. You have reached your daily contact limit";
				if($from_search)
				$contact_limit_message="You have exceeded your daily limit of  Expression of interest";
				
		}
		else if($week_ini_total>$weekly_limit)
		{
			$TYPE='W';
			$contact_limit_message="Contact cannot be initiated. You have reached your weekly contact limit";
			if($from_search)
				$contact_limit_message="You have exceeded your weekly limit of  Expression of interest";

		}
		else if($month_ini_total>$month_limit)
		{
			$TYPE='M';
			$contact_limit_message="Contact cannot be initiated. You have reached your monthly contact limit";
			if($from_search)
				$contact_limit_message="You have exceeded your monthy limit of  Expression of interest";

		}
		else if($total_contacts_made>$overall_limit)
		{
				$TYPE='O';
				if(isPaid($subscription))
					$contact_limit_message="Contact cannot be initiated. Your contact limit as a paid member has been reached.";
				else
				{
					
					if(!$add_slashes)
						$contact_limit_message=addslashes("Contact cannot be initiated. Your contact limit as a free member has been reached. Please <a href='javascript:{\$.colorbox.close();document.location=\"mem_comparison.php\";}'>pay</a> to make more contacts");
					else
						$contact_limit_message="Contact cannot be initiated. Your contact limit as a free member has been reached. Please <a href='javascript:{\$.colorbox.close();document.location=\"mem_comparison.php\";}'>pay</a> to make more contacts";
					if($from_search)
				$contact_limit_message="You have exceeded your overall limit of  Expression of interest <BR><BR><b>To get  unlimited Expression of Interest<BR><a href='/profile/mem_comparison.php?from_source=seo'>Become a paid members now </a></b>";	
				}
		}
		else if(checkPhoneVerificationLayerCondition($profileid))
		{
			$total_contacts_made=get_dup_overall_cnt($profileid)+$total_contact;
			if($total_contacts_made>$notValidNumber_limit)
			{
				$TYPE='I';
				$verify_url="$.colorbox({href:'/profile/myjs_verify_phoneno.php?sourcePage=EOI&fromNewSearch=$fromNewSearch&searchId=$searchId'})";
				if(!$from_viewprofile)
					$verify_url=stripslashes($verify_url);
				$contact_limit_message="Your phone number is not verified. <a href=\"/profile/myjs_verify_phoneno.php?sourcePage=EOI&width=700\" class=\"thickbox\" onclick=\"javascript:{close_all_con_layer();$verify_url;return hide_exp_layer()}\">Click here</a> to Verify your phone number. Or call 18004196299 for help.";
				if($from_viewprofile)
					$contact_limit_message="<div style=\"width:350px;color:black;font-size:13px\">$contact_limit_message</div>";
			}
		}
		if($TYPE)
			contact_hit_limit($profileid,$TYPE);
			
		return $contact_limit_message;
	}
	
}

/*		Function created by NIKHIL on sep 23, 2010
		This function is used to assigns the limits of user.
		$profileid --> Is the profileid of user whom you want to set the contacts made by him/her.
*/

function assign_contact_history($profileid)
{
	//$data storing the relevant information of the user.
	global $data;

	 //Fetching all type of contacts initiated by this user, this is required this script won't call login_relogin function

	if($profileid)
	{
               
		//Getting data from memcache.
		$memcache_cont_stat=unserialize(memcache_call($profileid));
		if(!$memcache_cont_stat)
		{
				//If record not found, then fetch the records.
				put_back_to_contact_status();
				$memcache_cont_stat=unserialize(memcache_call($profileid));
		}
		
		//If still not found than search in contacts_status table.
		if(!$memcache_cont_stat)
		{	
			 $sql_cont_stat = "SELECT MONTH_INI_BY_ME,TODAY_INI_BY_ME,TOTAL_CONTACTS_MADE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$profileid'";
			$res_cont_stat = mysql_query_optimizer($sql_cont_stat) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cont_stat,"ShowErrTemplate");
			$row_cont_stat=mysql_fetch_array($res_cont_stat);
		}
		else
                        $row_cont_stat=$memcache_cont_stat;

		//Insert all type of contact to data variable.
		if($row_cont_stat)
		{
			 
			 $data['MONTH_INI_TOTAL']=$row_cont_stat['MONTH_INI_BY_ME'];
			 $data['TODAY_INI_TOTAL']=$row_cont_stat['TODAY_INI_BY_ME']; 
			 $data['WEEK_INI_TOTAL']=$row_cont_stat['WEEK_INI_BY_ME']; 
			 $data['TOTAL_CONTACTS_MADE']=$row_cont_stat['TOTAL_CONTACTS_MADE'];

		}
	}
}
/*		Function created by NITESH on sep 03 OCT, 2013
		This function is used to assigns the hamburger left slider , right slider, and header on the required page.
*/

function assignHamburgerSmartyVariables($profileid="")
{
	global $smarty;
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	
	if($profileid){
		$smarty->assign("LOGGEDOUT",0);
		$profileMemcacheObj=new ProfileMemcacheService($profileid);
		$memcacheCountNew=$profileMemcacheObj->get("AWAITING_RESPONSE_NEW")+ $profileMemcacheObj->get("ACC_ME_NEW")+ $profileMemcacheObj->get("MESSAGE_NEW")+$profileMemcacheObj->get("PHOTO_REQUEST_NEW")+ $profileMemcacheObj->get("HOROSCOPE_NEW") +$profileMemcacheObj->get("MATCHALERT");
		$memcacheCountTotal=$memcacheCountNew+$profileMemcacheObj->get("AWAITING_RESPONSE")+$profileMemcacheObj->get("ACC_ME")+$profileMemcacheObj->get("MESSAGE")+$profileMemcacheObj->get("PHOTO_REQUEST")+$profileMemcacheObj->get("HOROSCOPE");
		$smarty->assign("memcacheCountNew",$memcacheCountNew);
		$smarty->assign("memcacheCountTotal",$memcacheCountTotal);
		$smarty->assign("profileMemcacheObj",$profileMemcacheObj);
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
		$memObj=new Membership();
		$jmemObj=new JMembership();
		list($userType,$expiryDate,$memStatus)=$jmemObj->getMemUserType($profileid);
		$contactsLeft = $jmemObj->getRemainingContactsForUser($profileid);
		if($userType == '2' || $userType == '3' || $userType == '7')
			$memStat = 'F';
		else if($userType == '4' || (($userType == '6' || $userType == '5' || $userType == '8') && $contactsLeft == 0))
			$memStat = 'R';
		else
			$memStat = 'P';
		$smarty->assign("memStat",$memStat);

		if($memcacheCountTotal>0)
		{			
			$smarty->assign("profileMemcacheObj",$profileMemcacheObj);
			$rightSlider=$smarty->fetch("mobilejs/right_slider.html");
			$smarty->assign("RIGHT_SLIDER",$rightSlider);
		}
	}
	else
	{
		$smarty->assign("LOGGEDOUT",1);
	}
	//Left Slider and Header
	$leftSlider=$smarty->fetch("mobilejs/left_slider.html");
	$smarty->assign("LEFT_SLIDER",$leftSlider);
	$header=$smarty->fetch("mobilejs/hamburgerHeader.html");
	$smarty->assign("HEADER",$header);
	$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
	$smarty->assign("FOOTER",$footer);
		
}
