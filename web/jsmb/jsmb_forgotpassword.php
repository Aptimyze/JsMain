<?php
include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
connect_db();
assignHamburgerSmartyVariables($data[PROFILEID]);
$page_track="MOBPASS";    //esha
$data=authenticated();
if($data){
        header("Location:$SITE_URL/search/partnermatches");
die;
}
if($submit_ps){
	$is_err=0;
	if(trim($in_field)==""){
		$is_err++;
		$smarty->assign("Error_User","Error: Username/Email not filled.");
		$smarty->assign("username_err","YES");
	}
	if(!$is_err){
		if(preg_match("/.*@.*/",$in_field)){
			$submit_email=1;
			$email=$in_field;
		}
	else{
		$submit_username=1;
		$username=$in_field;
	}
		if ($submit_username)
		{
	        $username_esc=mysql_real_escape_string(trim($username));
			if ($username_esc!="")
				$sql = "select USERNAME,PHONE_MOB,ACTIVATED,EMAIL,PROFILEID from JPROFILE where USERNAME= binary '$username_esc'";
		}
		elseif ($submit_email)
		{
			$email_esc=mysql_real_escape_string(trim($email));
			if ($email_esc)
				$sql = "select USERNAME,EMAIL,PHONE_MOB,ACTIVATED,PROFILEID from JPROFILE where EMAIL='$email_esc'";
		}
	//	$sql .= " and ACTIVATED <> 'D'";
		$res = mysql_query ($sql) or die("Could not process request at this time.");
		$count = mysql_num_rows($res);
		$myrow = mysql_fetch_array($res);
		$act = $myrow["ACTIVATED"];
		$user_pid=$myrow["PROFILEID"];
		if (($count > 0)&&($act!='D'))
		{
			//$myrow = mysql_fetch_array($res);
			if($submit_email)      //esha
	                {       
				$dup_email_flag='N';
				track_duplicate_email($email_esc,$page_track,$dup_email_flag);
			}                                        //esha
			include_once(JsConstants::$docRoot."/profile/sendForgotPasswordLink.php");
			sendForgotPasswordLink($myrow);
			$msg="An email has been sent to your id. Please click on the link provided to reset your password";
			$smarty->assign("MESSAGE_SHOW",$msg);
			$smarty->display("mobilejs/forgotpwd_sent_success.html");
			die;
		}
		else
		{
			if($act=='D'){
				$arcsql="select PROFILEID from newjs.JSARCHIVED where PROFILEID='$user_pid' and STATUS ='Y'";

                	        $arcres=mysql_query($arcsql);

        	                $arcrow=mysql_fetch_array($arcres);
	                        if(mysql_num_rows($arcres) > 0)
				{
					HEADER("LOCATION:/profile/retrieve_archived.php");
					die;
				}
				$smarty->assign("Error_User","Error : The profile registered with this email has
				been deleted. To retrieve the username and password kindly mail to
				bug@jeevansathi.com.");
				$smarty->assign("username_err","YES");	
			}
			else{
				if ($submit_username)
				{
					$smarty->assign ("Error_User","Error : The username/Email Id you have entered does not exists in our records.");
					$smarty->assign("username_err","YES");	
				}
				elseif ($submit_email)
				{
			                $dup_email_flag='Y';   //esha
					track_duplicate_email($email_esc,$page_track,$dup_email_flag);  //esha
					$smarty->assign ("Error_Email","Error : The email you have entered does not exists in our records.");
					$smarty->assign("email_err","YES");
				}
			}
		}
	}
}
$expire = $_GET['expire'];
$smarty->assign("expire",$expire);
$smarty->assign("in_field",htmlspecialchars($in_field));
$smarty->display("mobilejs/forgot-password.html");
?>
