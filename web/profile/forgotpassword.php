<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include ("connect.inc");
connect_db();
$iserror = 0;
$page_track="PASS";
if ($submit_username || $submit_email)
{
	maStripVARS("addslashes");
	if ($submit_username)
	{
		if (trim($username)=="")
		{
			$smarty->assign ("Error_User","Error : Username Not Filled.");
			$username_err = 'Y';
			$iserror++;
		}
	}
	elseif ($submit_email)
	{
		if (trim($email)=="")
                {
                        $smarty->assign ("Error_Email","Error : Email Not Filled.");
			$email_err = 'Y';
                        $iserror++;
                }
	}
	if ($iserror > 0)
	{
		if($ajaxValidation)
		{
			echo "E1";
			exit;
		}
		maStripVARS("stripslashes");
		$smarty->assign("email_err","$email_err");
		$smarty->assign("username_err","$username_err");
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->display("forgot_password.htm");
	}
	else
	{
		if ($submit_username)
		{
			if (trim($username)!="")
				$sql = "select USERNAME,EMAIL,ACTIVATED,PROFILEID from JPROFILE where  USERNAME= binary '$username'";
		}
		elseif ($submit_email)
		{
			if (trim($email))
				$sql = "select USERNAME,EMAIL,ACTIVATED,PROFILEID from JPROFILE where  EMAIL='".trim($email)."'";
		}

	//	$sql .= " and ACTIVATED <> 'D'";
		$res = mysql_query ($sql) or die("Could not process request at this time.");
		$count = mysql_num_rows($res);
		if($submit_email && $count>0)
		{	$dup_email_flag='N';track_duplicate_email($email,$page_track,$dup_email_flag);}
		$myrow = mysql_fetch_array($res);
		$act = $myrow["ACTIVATED"];
		if($myrow['ACTIVATED']=='D')
		{
			$sqljs="select PROFILEID from newjs.JSARCHIVED where PROFILEID='$myrow[PROFILEID]' and STATUS ='Y'";
                        $resjs=mysql_query($sqljs);
                        if($myrowjs=mysql_fetch_array($resjs))
                        {
                                global $ajaxValidation;

                                if($ajaxValidation)
                                {
                                        echo "JA";
                                }
                                else
                                {
                                        HEADER("LOCATION:/profile/retrieve_archived.php?email=$email");
                                }

                                die;
                                //return $myrow['PROFILEID'];
                        }
		}
		if (($count > 0)&&($act!='D'))
		{
                        include_once(JsConstants::$docRoot."/profile/sendForgotPasswordLink.php");
                        sendForgotPasswordLink($myrow);

			if($ajaxValidation)
			{
				echo "D1";
				exit;
			}
			$msg = "<strong>We have sent your Username and Password<br><br>at the Email ID registered in your profile.</strong>";
			//$link = "Click Here to Login";
			//$url = $SITE_URL."/P/mainmenu.php";
			$link="<a href=\"mainmenu.php\">Click Here to Login</a>";
			
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
        		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
        		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
        		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

			//$smarty->display("forgot_pass_confirmation.htm");
			$smarty->assign("msg",$msg);
			$smarty->assign("url",1);
			$smarty->assign("link",$link);
		
			$smarty->display("confirmation.htm");
	
		}
		else if(($count > 0)&&($act=='D'))
		{
			if($ajaxValidation)
			{
				echo "E3";
				exit;
			}
		}
		else
		{
			if ($submit_username)
			{
				if($ajaxValidation)
				{
					echo "E2";
					exit;
				}
				$smarty->assign ("Error_User","Error : The username  you have entered does not exists in our records.<br> Please check the username you have entered and try again.");
				$smarty->assign("username_err","YES");	
			}
			elseif ($submit_email)
			{
				$dup_email_flag='Y';
				track_duplicate_email($email,$page_track,$dup_email_flag);
				 if($ajaxValidation)
                                {
                                        echo "E2";
                                        exit;
                                }

				$smarty->assign ("Error_Email","Error : The email you have entered does not exists in our records.<br> Please check the email you have entered and try again.");
				$smarty->assign("email_err","YES");
			}

			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			$smarty->display("forgot_password.htm");
		}

	}
}
else
{
//	echo 'hi how ar eou and i belive that no one in this world';
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	//$smarty->display("forgot_pass.htm");
	$smarty->display("forgot_password.htm");
}

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
