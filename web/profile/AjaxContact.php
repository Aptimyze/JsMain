<?
	/*This script is called in both cases, in multiple contact and single contact , since parameter pass will be different in different cases
	For Mulitple contact case
		SENDERS_DATA will contact profileidschecksum seprated by comman
		TYPE_OF will be M 
	For Single contact case
		SENDERS_DATA will contain only one profilechecksum
		TYPE_OF will be S
	*/

	//to zip the file before sending it
	include_once("search.inc");
        include_once("connect.inc");
        include_once("arrays.php");
        include_once("hin_arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        include_once("hits.php");
        include("manglik.php");
        include_once('functions.inc');
        include_once('ntimes_function.php');
	include_once("contact.inc");
	include_once("sphinx_search_function.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
	include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
	include_once("mobile_detect.php");
        $jpartnerObj=new Jpartner;
        $mysqlObj=new Mysql;
        // connect to database
	$db_slave=connect_slave();	
        $db=connect_db();

	$data=authenticated();
    if($data)
    {
        $smarty->assign("LOGGEDIN", 1);
    }
    else
    {
			if($fromNewSearch)
			die("Login");
		}
	//If posted data, then converting into GET
        if(count($_GET)<=0 && count($_POST)>0)
                $_GET=$_POST;
	$profileid=$data['PROFILEID'];
	$sen_gender=$data['GENDER'];
	$is_spam=0;
	$type_of_contact=$_GET['TYPE_OF'];
	$senders_data=$_GET['senders_data'];
	
	

	//Check if login user is declared as spammer or not.
	if(is_spam($profileid,'sendContact'))
		$is_spam=1;

	//This function will give you the actual overall limit;
	if(!stristr($data['SUBSCRIPTION'],'F'))
                check_profile_percent();

	if($profileid)
	{

		//Getting all the required information about the login user required while running spammer check.
		$sql="select MSTATUS,CASTE,MTONGUE,HEIGHT,AGE,COUNTRY_RES,RELATION,RELIGION,CITY_RES,INCOME,INCOMPLETE,ACTIVATED,SUBSCRIPTION from newjs.JPROFILE where  activatedKey=1 and PROFILEID=$profileid";
		$res=mysql_query_decide($sql) or die("ERROR#Unable to process your request,Please retry");
		$row=mysql_fetch_assoc($res);
		$LOGIN_USER=$row;
		//Getting the default message
		$relation=$row['RELATION'];
		$show_what="EOI";
		$username=$data["USERNAME"];
		$incomplete=$row['INCOMPLETE'];
		$activated=$row['ACTIVATED'];
		$senSub=$row['SUBSCRIPTION'];

		$show_what="EOI";
		include_once("preset_message.php");
		
		//End of getting the default message	

		//If someone is indirectly accessing the script
		if($type_of_contact!='M' && $type_of_contact!='S')
		{
			if($isMobile)
				displayForMobile("This operation is not allowed");
			else
				echo"ERROR#This operation is not allowed";
			die;
		}
		if($senders_data=="")
		{
			if($isMobile)
				displayForMobile("Please first select Users");
			else
			echo "ERROR#Please first select Users";
			die;
		}
		
		
		if($type_of_contact=='M' && $is_spam==1)
		{
			if($isMobile)
				displayForMobile("You are not allowed to do multiple contact");
			else
			echo "ERROR#You are not allowed to do multiple contact";
			die;
		}
		$rec_check=explode(",",$senders_data);
		$total_contact=count($rec_check);
		$tempParam = temporaryInterestSuccess($incomplete, $activated);
		
				
		//Checking if contact limit getting exceded.
		$err_msg=check_all_contact_limit($profileid,$total_contact,1);
		if($err_msg)
		{
			if($isMobile)
				displayForMobile($err_msg);
			else
			echo "ERROR#$err_msg";
			die;
		} 

		//Getting the connection parameter on sharded server.
		$sen_dbname=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
		$sen_db=$mysqlObj->connect($sen_dbname);
		if(!isPaid($data[SUBSCRIPTION]))
			$custmessage="";
		else
			$custmessage=$_REQUEST['MESSAGE'];
		if( $fromNewSearch && !isset($_POST['MESSAGE']))
			$custmessage=$DRA_MES[PRE_1];
		elseif(!$custmessage && !isPaid($data[SUBSCRIPTION]))
			$custmessage=$DRA_MES[PRE_1];	
		for($start=0;$start<count($rec_check);$start++)
		{
			
			$receiver_id=$rec_check[$start];
			$rec_profileid=getProfileidFromChecksum($receiver_id);
			if($rec_profileid==0)
			{
				if($isMobile)
					displayForMobile("Breaching of data is not allowed.");
				else
					echo "ERROR#Breaching of data is not allowed.";
				die;
			}
			$sender_profileid=$data['PROFILEID'];
			$sender_details=$data;
			$error_msg="";
			$sender_prob=0;
			$error_msg=can_contact_message($sender_profileid,$rec_profileid,$sender_details);
			if($error_msg)
			{
				if($sender_prob==1)
				{
					if($isMobile)
						displayForMobile($error_msg);
					else
						echo "ERROR#$error_msg";
					die;
				}
				else
					$ERR_MES[]=$error_msg;
				
			}
			//If login user and contacted profile have same gender.
			$sql_g="select USERNAME,GENDER from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$rec_profileid'";
			$res_g=mysql_query_decide($sql_g) or die("Please try after some time");
			$row_g=mysql_fetch_assoc($res_g);
			$rec_username=$row_g['USERNAME'];
			if($row_g['GENDER']==$sen_gender)
			{
				if($isMobile)
					displayForMobile("You cannot initiate contact with profile(s) of the same gender");
				else
					echo "ERROR#You cannot initiate contact with profile(s) of the same gender";
				die;
			}
			if($fromNewSearch!=1 && get_contact_status($profileid,$rec_profileid))
			{
				$ALREADY_CONTACTED[]=$rec_username;
			}
			else
			{
				//If user unable to satisfy filters.
				
				$filtered_contact='';
				if(!$tempParam)
				{
					$filtered_contact=getFilteredContact($profileid, $rec_profileid);
					if($filtered_contact)
						$FILTER[]=$rec_username;
				}

				$CONTACTED[]=$rec_username;			
				$contact_pro=$rec_profileid;
				$receiver_details=get_profile_details($rec_profileid);
				if($tempParam)	
				{
                	        	if($tempParam)
						$tempDetail = initiateTemporaryInterest($data, $receiver_details, $draft_name, $custmessage, $DRA_MES, "I", $paid, $tempParam, $stype);
				}
				else
				{
					if($fromNewSearch && $MESSAGE)
						$flag_again=1;
					make_initial_contact($profileid,$rec_profileid,$savedraft,$custmessage,$flag_again,$markcc,"",$stype,"",'',$filtered_contact,$receiver_details["SUBSCRIPTION"],$senSub);
				    $message=get_message_to_send_contact($data,$draft_name,$custmessage,$receiver_details,'I',$DRA_MES);
				}
				$threshold_message=get_limit_message($data[PROFILEID],$data[SUBSCRIPTION]);
				$smarty->assign("THRESHOLD_MESSAGE",$threshold_message);
			
			}
			$REMOVE_PROFILEID[]=$rec_profileid;
			
				
		}
		//Logging if multiple contact done.
		$stypeArr=array("C","VO","V","VC","CO","VN","CN","CN2","L");
		if($type_of_contact=='M' && count($rec_check)>1 && !in_array(strtoupper($stype),$stypeArr))
		{
			$cnt_dt=date("Y-m-d");
			$tot_eoi=count($rec_check);
			if(is_array($ALREADY_CONTACTED))
			{
				$tot_eoi=$tot_eoi-count($ALREADY_CONTACTED);	
			}
			if($tot_eoi>0)
			{
				$conSql="update MIS.MultiContactLog set CNT=CNT+1,TOTAL_EOI=TOTAL_EOI+$tot_eoi where DATE='$cnt_dt'";
				$conRes=mysql_query_decide($conSql);
				if(@mysql_affected_rows($conRes)<1)
				{
					$conSql="insert into MIS.MultiContactLog set CNT=1,TOTAL_EOI=$tot_eoi,DATE='$cnt_dt'";
	        	                $conRes=mysql_query_decide($conSql);
				}
			}
		}

		if(is_array($REMOVE_PROFILEID))
		{

			if(is_array($ALREADY_CONTACTED))
				if($message_show)
					$message_show.="<BR> You have already contacted ".implode(", ",$ALREADY_CONTACTED);
				else
					$message_show.=" You have already contacted ".implode(", ",$ALREADY_CONTACTED);
			
			//Profiles whose checkboxes are disabled.
			if(is_array($CONTACTED))
				$smarty->assign("CONTACTED_PROFILES",implode(", ",$CONTACTED));
			
			$smarty->assign("FILTER_ALREADY_CONTACT",$message_err);
			if(stristr($data['SUBSCRIPTION'],'F'))
			{
				$smarty->assign("PAID",1);
				set_draft($custmessage);	
			}
			if(count($rec_check)==1 && is_array($CONTACTED))
			{
				$scriptname="AjaxContact.php";
                                if($profileid && !in_array($stype,array('CN','CO','L','V','CN2','')))
				{
                                        updateSimProfileLog($profileid);
				}
				//if(!is_array($FILTER)&&!$isMobile)
				if(!$isMobile)
				{
					if($fromNewSearch)
						die("SUCCESS");
					else
						die("<script>red_view_similar('REDIRECT:$contact_pro:nikhil:I');</script>");
					//die("REDIRECT:$contact_pro:$rec_username:I");
					//$logic=revamp_get_other_relevant_pro($profileid,$senders_data,"single_contact_aj",$scriptname);
				}
				//get_similar_profile();
				$smarty->assign("profilechecksum",md5($contact_pro)."i".$contact_pro);
				$smarty->assign("RECEIVER_USERNAME",$rec_username);
				//navigation("CVS","searchid__-1@j__1@contact__$contact_pro@SIM_USERNAME__$rec_username@stype__$stypes@NAVIGATOR__$_GET[NAVIGATOR]@",$rec_username);
				//navigation("CVS","",$CONTACTED[0]);
				//$smarty->assign("NAVIGATION",
			if(!$isMobile)	
				$smarty->assign("SHOW_SIMILAR","YES");
				$smarty->assign("contact",$contact_pro);
				
			}
			if($CONTACTED)
			{
				if($isMobile)
				{
					if($message_show)
						$message_show="You have succesfully  Expressed  interest  in this profile <BR>".$message_show;
					else
						$message_show="You have succesfully  Expressed  interest  in this profile. ";
				}
				else
				{
					if($message_show)
						$message_show="<img src='/profile/images/grn_tck.gif' align='absmiddle' />&nbsp;Your expressions of interest has been sent to <span class=''>".implode(", ",$CONTACTED)."</span><BR>".$message_show;
					else
						$message_show="<img src='/profile/images/grn_tck.gif' align='absmiddle' />&nbsp;Your expressions of interest has been sent to  <span class=''>".implode(", ",$CONTACTED)."</span>";
				}
			}
			$smarty->assign("MESSAGE_SHOW",$message_show);
			
			$smarty->assign("BUY_MESSAGE_EOI_FROM_SEARCH","Please note that your message does not include your contact details. To include contact details and edit message. <a class=\"paid_mem f_16 b\" href=\"mem_comparison.php\">Become a Paid Member Now</a>");
			
			if($isMobile)
			{
				navigation("SR","","");
				$header=$smarty->fetch("mobilejs/jsmb_header.html");
				$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
				$smarty->assign("HEADER",$header);
				$smarty->assign("FOOTER",$footer);
				$smarty->display("mobilejs/jsmb_confirmation.html");
			}
			else
				$smarty->display("congrats_contact.htm");
die;
			
			
		}
		if($isMobile)
			displayForMobile("No contacts made");
		else
			echo "ERROR:No contacts made";
	die;
		//Getting similar profiles.
	}
	else
	{
		$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
		if($isMobile){
			include_once($_SERVER['DOCUMENT_ROOT']."/jsmb/login_home.php");
			die;
		}
		else
		{
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
        	$smarty->display("login_layer.htm");
	        die;
		}
	}
function get_similar_profile()
{
	$sql="select PROFILEID from newjs.SEARCH_FEMALE limit 0,9";
	$res=mysql_query_decide($sql) or die("ERROR#Please try after some time");
	set_results($res,"single_contact",9);
}
function check_filters($profileid)
{
	global $FILTER_LAGE,$PARTNER_CASTE,$FILTER_HAGE,$FILTER_MSTATUS,$PARTNER_COUNTRYRES,$PARTNER_MTONGUE,$FILTER_INCOME,$FILTER_CITY,$FILTER_RELIGION;
	global $LOGIN_USER;
	global $data;
	// check whether the person being viewed has set the filters
	$sql="select * from FILTERS where PROFILEID='$profileid'";
	$resultfilter=mysql_query_optimizer($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

	if(mysql_num_rows($resultfilter) > 0)
	{
		$filterrow=mysql_fetch_array($resultfilter);
		if($filterrow["AGE"]=="Y" || $filterrow["MSTATUS"]=="Y" || $filterrow["RELIGION"]=="Y" || $filterrow["COUNTRY_RES"]=="Y" || $filterrow["MTONGUE"] =="Y"||$filterrow["CASTE"]=="Y" || $filterrow["CITY_RES"]=="Y" || $filterrow["INCOME"]=="Y")
		{


			$PARTNER_CASTE_LIST=get_all_caste($PARTNER_CASTE);

			$temp_age=$LOGIN_USER["AGE"];
			//if($filterrow["AGE"]=="Y" && ($FILTER_LAGE>$temp_age || $temp_age>$FILTER_HAGE))
		//		$filter_flag=1;
			if($filterrow["MSTATUS"]=="Y" && is_array($FILTER_MSTATUS) && !in_array($LOGIN_USER["MSTATUS"],$FILTER_MSTATUS))
				$filter_flag=1;
			elseif($filterrow["RELIGION"]=="Y" && is_array($PARTNER_CASTE_LIST) && !in_array($LOGIN_USER["CASTE"],$PARTNER_CASTE_LIST))
				$filter_flag=1;
			elseif($filterrow["COUNTRY_RES"]=="Y" && is_array($PARTNER_COUNTRYRES) && !in_array($LOGIN_USER["COUNTRY_RES"],$PARTNER_COUNTRYRES))
				$filter_flag=1;
			elseif($filterrow["MTONGUE"]=="Y" && is_array($PARTNER_MTONGUE) && !in_array($LOGIN_USER["MTONGUE"],$PARTNER_MTONGUE))
				$filter_flag=1;
			elseif($filterrow["CASTE"]=="Y" && is_array($PARTNER_CASTE) && !in_array($LOGIN_USER["CASTE"],$PARTNER_CASTE))
				$filter_flag=1;
			elseif($filterrow["CITY_RES"]=="Y" && is_array($FILTER_CITY) && !in_array($LOGIN_USER["CITY_RES"],$FILTER_CITY))
				$filter_flag=1;
			elseif($filterrow["INCOME"]=="Y" && is_array($FILTER_INCOME) && !in_array($LOGIN_USER["INCOME"],$FILTER_INCOME))
				$filter_flag=1;
			//If filter is not satisfied..
		       if($filter_flag)
			{
				$sql="insert into FILTER_LOG(VIEWER,VIEWED,DATE) values ('" . $data["PROFILEID"] . "','$profileid',now())";
				mysql_query_optimizer($sql);
				return true;
			}
		}
	}
	return false;
}


function check_dpp_for_spam($profileid,$FILTER_HAGE,$FILTER_LAGE,$PARTNER_COUNTRYRES,$PARTNER_CASTE,$PARTNER_MTONGUE)
{

        
	//LOGIN_USER contains all the information required during spammer check.
       	global $LOGIN_USER; 

        $CASTE=$LOGIN_USER['CASTE'];
        $MTONGUE=$LOGIN_USER['MTONGUE'];
        $HEIGHT=$LOGIN_USER['HEIGHT'];
        $AGE=$LOGIN_USER["AGE"];
        $COUNTRY=$LOGIN_USER["COUNTRY_RES"];
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
function can_contact_message($sender_profileid,$receiver_profileid,$sender_details)
{
	//first check : male -> female or female -> male only           
	global $sender_prob;
	global $SITE_URL;
	$sender_gender=$sender_details["GENDER"];
	$sql="select USERNAME,GENDER,ACTIVATED from JPROFILE where  activatedKey=1 and PROFILEID='$receiver_profileid'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	$myrow=mysql_fetch_array($result);

	//If data is not present in active partition..
	if(!$myrow)
	{
		$sql="select USERNAME,GENDER,ACTIVATED from JPROFILE where  PROFILEID='$receiver_profileid'";
        	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	        $myrow=mysql_fetch_array($result);
	}

	$receiver_gender=$myrow["GENDER"];
	if($sender_gender==$receiver_gender)
	{
		$error_msg="You cannot initiate contact with profile(s) of the same gender.";
		$sender_prob=1;
		return $error_msg;
	}

	//second check : Is the profile to be contacted activated
	if($myrow["ACTIVATED"]!='Y')
	{
		$he_she="she";
		$his="her";
		if($myrow['GENDER']=='M')
		{
			$he_she="he";
			$his="his";
		}
		if($myrow["ACTIVATED"]=='H')
			$error_msg=" $myrow[USERNAME] is currently hidden. You can contact this person once $he_she activates $his profile. ";
		elseif($myrow["ACTIVATED"]=='D')
			$error_msg="$myrow[USERNAME] profile has been deleted ";
		elseif($myrow["ACTIVATED"]=='N'||$myrow["ACTIVATED"]=='U'||$myrow["ACTIVATED"]=='P')
			$error_msg="$myrow[USERNAME] profile is currently under screening. Please try again after 24 hours. ";
		elseif(!$myrow)
			$error_msg="Profile not found.";
		$sender_prob=1;

		return $error_msg;
	}

	mysql_free_result($result);
	if($sender_prob!=2)
	{
		$sql="select ACTIVATED,INCOMPLETE from JPROFILE where  activatedKey=1 and PROFILEID='$sender_profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		
		$s_checksum=createChecksumForSearch($sender_profileid);
		if($myrow["ACTIVATED"]!='Y')
		{
			if($myrow["ACTIVATED"]=='H')
			{
				$error_msg="Your profile is currenly hidden. You need to unhide your profile before contacting this user.";
			}
			elseif($myrow["ACTIVATED"]=='D')
			{
				$error_msg="Your profile has been deleted";
			}
			elseif(!$myrow["ACTIVATED"])
				$error_msg="Your profile has been deleted";

			if($error_msg)
				$sender_prob=1;
			return $error_msg;
		}
		if($myrow['INCOMPLETE']=='Y')
		{
			if($error_msg)
				$sender_prob=1;

                        return $error_msg;
		}
	}
	$sender_prob=2;

	mysql_free_result($result);

	return '';
}
function displayForMobile($err_msg)
{
	global $smarty;
	navigation("SR","","");
	$header=$smarty->fetch("mobilejs/jsmb_header.html");
	$footer=$smarty->fetch("mobilejs/jsmb_footer.html");
	$smarty->assign("HEADER",$header);
	$smarty->assign("FOOTER",$footer);
	$smarty->assign("ERROR_MESSAGE",$err_msg);
	$smarty->display("mobilejs/jsmb_confirmation.html");
}
	
?>
