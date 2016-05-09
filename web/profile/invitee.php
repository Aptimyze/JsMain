<?php
//invitee.php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");

$db=connect_db();

$smarty->assign("head_tab","my jeevansathi");   //flag for headnew.htm tab

$data=authenticated($checksum);

if($data)
                login_relogin_auth($data);
/*****************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/***********************End of Portion of Code*****************************************/

//$db=connect_db();
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

/**************************Added By Shakti for link tracking**********************/
link_track("invitee.php");
/*********************************************************************************/


if($invitation)
{
	unset($iserror);
	$error=0;
	unset($valid);
	//unset($friend);
	//unset($email);
	$INVALID_EMAIL = "N";
	$INVALID_NAME  = "N";

	if(trim($referer)=="")
	{
		$NOREFERER="Y";
		$error=1;
	}
	if (!checkemail1($referer_email))
	{
		$error=1;
		$NOEMAIL="Y";
	}
	else
	{
		if($referer_email == '')
		{
			$error=1;
                	$NOEMAIL="Y";
		}
	}
	if(is_array($friend))
	{
		for($i=0;$i<count($friend);$i++)
		{
			if(trim($friend[$i])=="")
			{
				if(trim($email[$i])!="")
				{
					if(!checkemail1($email[$i]))
					{
						$error=1;
						$cnt+=1;

						$INVALID_EMAIL = "Y";
						$INVALID_NAME  = "Y";

						$valid[$i]["check_name"]="Y";	
						$valid[$i]["check_email"]="Y";
					}
					else
					{
						$error=1;

						$INVALID_NAME  = "Y";
						$valid[$i]["check_name"]="Y";
						$valid[$i]["check_email"]="N";
					}
				}
				else
				{
					$check_all+=1;
				}
			}
			else
			{
				$valid[$i]["check_name"]="N";
				$cnt+=1;
				if($email[$i]=="")
				{
					$INVALID_EMAIL = "Y";
					$valid[$i]["check_email"]="Y";
					$error=1;
					//break;
				}
				else
				{
					if(!checkemail1($email[$i]))
					{
						$INVALID_EMAIL = "Y";

						$error=1;
						$valid[$i]["check_email"]="Y";
					}
					else
						$valid[$i]["check_email"]="N";
				}
			}
		}
		if($check_all==5)
		{
			for($i=0;$i<count($friend);$i++)
			{
				$INVALID_EMAIL = "Y";
                                $INVALID_NAME  = "Y";

				$valid[$i]["check_name"]="Y";
				$valid[$i]["check_email"]="Y";
			}
			$error=1;
			
		}	
	}
	if($error)
	{
		//for($i=0;$i<$cnt;$i++)
		for($i=0;$i<count($friend);$i++)
                {
                	$valid[$i]["name"]=$friend[$i];
	                $valid[$i]["email"]=$email[$i];
		}
		$smarty->assign("friend_name",$friend);
		$smarty->assign("friend_email",$email);
		$smarty->assign("referer",$referer);
		$smarty->assign("referer_email","$referer_email");
		$smarty->assign("valid",$valid);
		$smarty->assign("error","$error");
		$smarty->assign("NOREFERER",$NOREFERER);
		$smarty->assign("NOFRIEND",$NOFRIEND);
		$smarty->assign("NOEMAIL",$NOEMAIL);	
		$smarty->assign("INVALID_EMAIL",$INVALID_EMAIL);	
		$smarty->assign("INVALID_NAME",$INVALID_NAME);	
		$smarty->display("invite_friends.htm");
	}
	else
	{
		for($i=0;$i<count($friend);$i++)
		{
			$valid[$i]["name"]=$friend[$i];
			$valid[$i]["email"]=$email[$i];
		}
		//print_r($valid);
		//$name=$valid[0]["name"];
		//$email=$valid[0]["email"];
		
		//$sql="";

		//$sql="INSERT IGNORE INTO newjs.INVITEE (PROFILEID, NAME,REFREE_NAME,REFREE_EMAIL,DATE) VALUES ('$data[PROFILEID]','$referer','$name','$email',now())";
		for($i=0;$i<count($valid);$i++)
		{
			$sql="";
			$name=$valid[$i]["name"];
                	$email=$valid[$i]["email"];
			/* added on feb 09 2006 by Puneet Makkar so that profileid  is not 0 when somebody invites friends from logout page*/	
			if($data)
				$profileid=$data['PROFILEID'];
			else
			{
				$sql_profile="SELECT PROFILEID FROM JPROFILE WHERE  activatedKey=1 and EMAIL='$referer_email'"; 
				$res_profile=mysql_query_decide($sql_profile) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_profile,"ShowErrTemplate");
				$myrow=mysql_fetch_array($res_profile);
				$profileid=$myrow['PROFILEID'];
			}	
			/* added on feb 09 by Puneet Makkar so that profileid  is not 0 when somebody invites friends from logout page*/	
			
			if($name!="" && $email!="")
			{	
				$sql="INSERT IGNORE INTO newjs.INVITEE (PROFILEID, NAME,REFREE_NAME,REFREE_EMAIL,DATE) VALUES ('$profileid','$referer','$name','$email',now())";
				$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");//die(mysql_error_js());
				//$sql.=",('$data[PROFILEID]','$referer','$name','$email',now())";
			}
		}
		//echo $sql;
		//$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");//or die(mysql_error_js());
		$smarty->assign("referer",$referer);
                $smarty->assign("referer_email","$referer_email");
		$smarty->assign("error","$error");
		//$smarty->display("../../shobha/profile/invitee.htm");	


		//$msg = "<strong>We have sent your Username and Password<br><br>at the Email ID registered in your profile.</strong>";
		$msg = "<b>Thank you for referring your friend(s) on JeevanSathi.com.</b><br><br> We hope that you and your friend(s) find your perfect life partner on our website.<br>To refer more people on JeevanSathi.com,";

		$link="<a href=\"invitee.php?checksum=$checksum\">Click Here</a>";
		//$link = "Click Here";
                //$url = $SITE_URL."/P/invitee.php?checksum=$checksum";

		$smarty->assign("msg",$msg);
                $smarty->assign("url",1);
                $smarty->assign("link",$link);

		$smarty->display("confirmation.htm");
	}
}
else
{
	if($data)
	{
		$sql = "SELECT EMAIL FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
		$res = mysql_query_decide($sql);
		$row = mysql_fetch_array($res);
		$smarty->assign("referer_email","$row[EMAIL]");
	}
	//else
	//	TimedOut();
	$smarty->assign("initial","1");
	$smarty->assign("profileid","$data[PROFILEID]");
	$smarty->display("invite_friends.htm");
}

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
