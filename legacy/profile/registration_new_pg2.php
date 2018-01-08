<?php
include_once("connect.inc");
include_once("registration_functions.inc");
$db=connect_db();

$smarty->assign("LEADID",$leadid);
if($page2submit_x || $page2submit || $about_yourself || $page_submit)
{
	$smarty->assign("gender",$gender);
	$smarty->assign("EMAIL",$email);
	$smarty->assign("yourHeading",$yourHeading);
	$smarty->assign("TIEUP_SOURCE",$tieup_source);
	$smarty->assign("TIEUP_SOURCE",$source);
	$smarty->assign("HITSOURCE",$hit_source);
	$smarty->assign("NEWIP",$newip);
	$smarty->assign("ADNETWORK",$adnetwork);
	$smarty->assign("ACCOUNT",$account);
	$smarty->assign("CAMPAIGN",$campaign);
	$smarty->assign("ADGROUP",$adgroup);
	$smarty->assign("KEYWORD",$keyword_tieup);
	$smarty->assign("MATCH",$match);
	$smarty->assign("LMD",$lmd);
	$smarty->assign("SHOWLOGIN",$showlogin);
	$smarty->assign("FROMMARRIAGEBUREAU",$fromprofilepage);
	$smarty->assign("GROUPNAME",$groupname);
	$smarty->assign("groupname",$groupname);
	$smarty->assign("CURRENT_DATE",date('Y-n-j'));

	$page2_error=0;
	//yourinfo
	$length=strlen($about_yourself);
	if($length>=100)
	{
		$smarty->assign("profileComplete",1);
	}
	//yourinfo

	//name
	if(($fname_user && !ereg("^[a-zA-Z\.\, ]+$",$fname_user)) || ($lname_user && !ereg("^[a-zA-Z\.\, ]+$",$lname_user)))
	{
		$smarty->assign("usernameError",1);
		$page2_error=1;
	}
	//name
	if($page2_error==0)
	{
		$checksum=$protect_obj->js_decrypt($checksum);
		$profileid=getProfileidFromChecksum($checksum);
		if($profileid)
		{
			$about_yourselfbout_yourself=mysql_real_escape_string(stripslashes($about_yourself));
			if($fname_user || $lname_user)
			{
	                	if($gender == "M")
					$name_of_user = "Mr.".$fname_user." ".$lname_user;
				elseif($gender == "F")
					$name_of_user = "Ms.".$fname_user." ".$lname_user;
				else	
					$name_of_user =$fname_user." ".$lname_user;
				$sql_name = "REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES('$profileid','".addslashes(stripslashes($name_of_user))."')";
				mysql_query_decide($sql_name) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_name,"ShowErrTemplate");
			}
			$length=strlen($about_yourself);
			if($length <= '99')
			{
				$sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='Y' WHERE PROFILEID=$profileid";
				mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
			}
			elseif($length >= '100')
			{
				$sql_page2="UPDATE newjs.JPROFILE SET YOURINFO='$about_yourself',INCOMPLETE='N' WHERE PROFILEID=$profileid";
				mysql_query_decide($sql_page2) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_page2,"ShowErrTemplate");
			}
		
			$sql_pg="UPDATE MIS.REG_LEAD SET INCOMPLETE='N' WHERE EMAIL='$email'";
			mysql_query_decide($sql_pg) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_pg,"ShowErrTemplate");

			// Under Screening Mailer attached to the first page
			$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row=mysql_fetch_array($result);
			$email=$row["EMAIL"];
			$username=$row["USERNAME"];
			$smarty->assign("username",$username);
			$msg =$smarty->fetch('Under_Screening.html');
			send_email($email,$msg,"Welcome to Jeevansathi.com","register@jeevansathi.com","","","","","","Y");
			
			$phone=explode('-',$phone);
			$phone=$phone[2];

		       /* As Requirment we have shifted IVR-  Phone No. Verification Code after profile completation in second page
			* function parameters: ivrPhoneVerification(profileid,mobile,landline,std)
			* return true/false
			*/
			
			include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
			if($mobile)
			      $ivr_status = ivrPhoneVerification($profileid,$mobile,'','register');
			else if($phone)
			      $ivr_status = ivrPhoneVerification($profileid,$phone,$state_code,'register');
			
			/* end of IVR code */



			//include tanu page.
			include("registration_dpp.php");
			exit;
		}
		else
		{
			//else mail
			//$http_msg = "User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
			//$http_msg .= implode(",",$_POST);
			$http_msg=print_r($_SERVER,true);
			//mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank 2',$http_msg);
		}
			
	}
	else
	{
		$about_yourself=htmlspecialchars(stripslashes($about_yourself),ENT_QUOTES);
		$smarty->assign("about_yourself",$about_yourself);
                $smarty->assign("fname_user",$fname_user);
                $smarty->assign("lname_user",$lname_user);
		$smarty->assign("checksum",$checksum);
	}
}
if(!$page2_error)
{
	if(!$profileid)
	{
		//$http_msg = "User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
		//$http_msg .= implode(",",$_POST);
		$http_msg=print_r($_SERVER,true);
		//mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank-----source1',$http_msg);
	}
	$checksum=md5($profileid)."i".$profileid;
	$checksum=$protect_obj->js_encrypt($checksum);
	$smarty->assign("checksum",$checksum);
}
$smarty->assign("gender",$gender);
$smarty->assign('p_percent',profile_percent_new($profileid));
$smarty->display("registration_new_pg2.htm");
?>
