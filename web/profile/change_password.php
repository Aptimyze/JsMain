<?php
/*********************************************************************************************
* FILE NAME     : change_password.php
* DESCRIPTION   : Changes password for the user
* CREATION DATE : 31 May, 2005
* CREATEDED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

//to zip the file before sending it
include_once("revamp_change_password.php");
die();
/*
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("connect.inc");
$db=connect_db();
$data=authenticated($checksum);
//Added By lavesh.
if($data)
        login_relogin_auth($data);
//ends Here.


$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);


$smarty->assign("CHECKSUM",$checksum);
//$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
//$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
//$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));

//added by lavesh for revamp.
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("head_tab",'my jeevansathi');
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
//Ends for revamp

        link_track("change_password.php");

if(isset($data))
{
	//Added By lavesh
	login_relogin_auth($data);
	$PROFILEID=$data["PROFILEID"];
	$MYPROFILECHECKSUM=md5($PROFILEID) . "i" . $PROFILEID;

	if($Submit=='Cancel')
	{
		$msg="Your Password is not changed";
		$lnk="<a href=\"viewprofile.php?checksum=$checksum&profilechecksum=$MYPROFILECHECKSUM\">Edit Profile</a>";
		$smarty->assign("msg",$msg);
		$smarty->assign("link",$lnk);
		$smarty->assign("url",1);
		$smarty->assign("no_icon",1);
		$smarty->display("confirmation.htm");	
	}
	//Ends Here
	
	elseif($Submit)
	{
		$isError=0;
		$msg="";

		$currPwd=trim($currPwd);
		$newPwd=trim($newPwd);
		$renewPwd=trim($renewPwd);
		$profileid=$data["PROFILEID"];

		$sql_pwd="SELECT PASSWORD FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$profileid";
		$res_pwd=mysql_query_decide($sql_pwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_pwd,"ShowErrTemplate");
		$row_pwd=mysql_fetch_array($res_pwd);
		$pwd=$row_pwd['PASSWORD'];

		if($pwd!=stripslashes($currPwd))
		{
			$isError++;
			$smarty->assign("CPWDMATCHERR","Y");
			$smarty->assign("CPWDERR","Y");
		}

                if(strlen($newPwd)>40 || strlen($newPwd)<5)
                {
                	$isError++;
                	$smarty->assign("password_length",1);
                }
		elseif ($newPwd!=$renewPwd)
		{
			$isError++;
			$smarty->assign("NEWPWDMATCHERR","Y");
			$smarty->assign("NPWDERR","Y");
			$smarty->assign("RPWDERR","Y");
		}

		if($isError==0)
		{
			$sql_newPwd="UPDATE newjs.JPROFILE SET PASSWORD='$newPwd' WHERE PROFILEID='$profileid'";
			$res_newPwd=mysql_query_decide($sql_newPwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_newPwd,"ShowErrTemplate");
			$msg="Your Password has been changed";
			//Modified By lavesh for Re-vamp.
			$lnk="<a href=\"viewprofile.php?checksum=$checksum&profilechecksum=$MYPROFILECHECKSUM\">Edit Profile</a>";
			$smarty->assign("msg",$msg);
			$smarty->assign("link",$lnk);
			$smarty->assign("url",1);
			$smarty->display("confirmation.htm");
			//Ends Here.
		}
		else
		{
			$smarty->assign("ERR",$isError);
			$smarty->assign("MSG",$msg);
			//Modified By lavesh for Re-vamp.
			//$smarty->display("reset_password.htm");
			$smarty->display("change_password.htm");
		}
	}
	else
	{
		//Template Changed by lavesh for Re-vamp.
		//$smarty->display("reset_password.htm");
		$smarty->display("change_password.htm");
	}
}
else
{
	TimedOut();
}

// flush the buffer
if($zipIt)
	ob_end_flush();
*/		
?>
