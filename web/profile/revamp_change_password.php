<?php
/*********************************************************************************************
* FILE NAME     : revamp_change_password.php
* DESCRIPTION   : Changes password for the user
* CREATION DATE : 25 Septmber, 2008
* CREATEDED BY  : Ankit Aggarwal
*********************************************************************************************/
//print_r($_POST);
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
	
include_once("connect.inc");
include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");

$db=connect_db();
$data=authenticated($checksum);
//Added By lavesh.
if($data)
        login_relogin_auth($data);
//ends Here.
/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/************************************************End of Portion of Code*****************************************/

if(!isset($Submit))
	$Submit=0;
if(isset($new_password))
{
	$newPwd=$new_password;
	$currPwd=$current_password;
}
if(isset($data))
{
	$smarty->assign("CHECKSUM",$checksum);
	//added by lavesh for revamp.
	include_once("sphinx_search_function.php");//to be tested later
	savesearch_onsubheader($data["PROFILEID"]);//to be tested later
	$smarty->assign("change_password",1);
	$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("leftpanel_settings.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	$PROFILEID=$data["PROFILEID"];
	if($Submit)
	{
		$isError=0;
		$msg="";
			$currPwd=trim($currPwd);
			$newPwd=trim($newPwd);
		include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$sql_pwd="SELECT PASSWORD,SCREENING FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID=$PROFILEID";
		$res_pwd=mysql_query_decide($sql_pwd) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_pwd,"ShowErrTemplate");
		$row_pwd=mysql_fetch_array($res_pwd);
		$pwd=$row_pwd['PASSWORD'];
		$screening=$row_pwd[SCREENING];
		if(!PasswordHashFunctions::validatePassword($currPwd,$row_pwd['PASSWORD']))
		{
			//echo $pwd."-".stripslashes($currPwd);
			$isError++;
			$msg="The current password is incorrect.";
			die($msg);
		}
		mysql_select_db_js('newjs');

		//Insert into autoexpiry table, to expire all autologin url coming before date
		PasswordUpdate::change($PROFILEID,$newPwd);
		$expireDt=date("Y-m-d H:i:s");
        $bRes = ProfileReplaceLib::getInstance()->replaceAUTOEXPIRY($PROFILEID, 'P', $expireDt);
        if(false === $bRes) {
            $sqlExpire="replace into jsadmin.AUTO_EXPIRY set PROFILEID='$PROFILEID',TYPE='P',DATE='$expireDt'";
            logError($errorMsg,"$sqlExpire","ShowErrTemplate");
        }
		//end
		$msg="New Password saved";
		die($msg);
	}
	else
	{
		$smarty->display("revamp_change_password.html");
	}
}
else
{
        if($Submit)
        {
		die("You have logged out or Your Session has expired");
	}
	TimedOut();
}

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
