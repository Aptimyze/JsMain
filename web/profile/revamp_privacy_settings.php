<?php
/*********************************************************************************************
* FILE NAME     : revamp_privacy_settings.php
* DESCRIPTION   : Changes privacy settings
* CREATION DATE : 25 Septmber, 2008
* CREATEDED BY  : Ankit Aggarwal
*********************************************************************************************/
//print_r($_POST);
//to zip the file before sending it
//header("HTTP/1.1 500");
//sleep(2);
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it
include_once("connect.inc");
$db=connect_db();
$data=authenticated($checksum);
//Added By lavesh.
if($data)
        login_relogin_auth($data);
//ends Here.

//print_r($data);
/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/************************************************End of Portion of Code*****************************************/
if(!isset($Submit))
        $Submit=0;
if(isset($data))
{
	$smarty->assign("privacy_settings",1);
	$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("leftpanel_settings.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	include_once("sphinx_search_function.php");//to be tested later
	savesearch_onsubheader($data["PROFILEID"]);//to be tested later
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	$smarty->assign("CHECKSUM",$checksum);
        //Added By lavesh
       // login_relogin_auth($data);
        $PROFILEID=$data["PROFILEID"];
       // $MYPROFILECHECKSUM=md5($PROFILEID) . "i" . $PROFILEID;
	
        if($Submit)
        {
			$today = CommonUtility::makeTime(date("Y-m-d"));

			include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
			$objUpdate = JProfileUpdateLib::getInstance();
			$nowDate = date('Y-m-d H:i:s');
			$result = $objUpdate->editJPROFILE(array('PRIVACY'=>$new_value,'MOD_DT'=>$nowDate,'LAST_LOGIN_DT'=>$today), $PROFILEID, 'PROFILEID', 'activatedKey=1');
			if (false === $result) {
				$sql = "UPDATE newjs.JPROFILE SET PRIVACY='$new_value' , MOD_DT=now(),LAST_LOGIN_DT='$today' WHERE PROFILEID='$PROFILEID' and activatedKey=1";
				logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
//	        $sql = "UPDATE newjs.JPROFILE SET PRIVACY='$new_value' , MOD_DT=now(),LAST_LOGIN_DT='$today' WHERE PROFILEID='$PROFILEID' and activatedKey=1";
//                mysql_query_decide($sql) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		//$msg=$new_value;
		die('Profile visibility settings saved');
	}
	else
	{
		$sql = "Select PRIVACY from newjs.JPROFILE where  activatedKey=1 and PROFILEID ='$PROFILEID'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$myrow=mysql_fetch_array($result);
		$privacy=$myrow[PRIVACY];
		if($privacy=="")
                	$privacy="A";
//		echo $privacy;
		$smarty->assign("PRIVACY",$privacy);
		$smarty->display("revamp_privacy_settings.html");
	}
}
else
{
	if($Submit)
	{
		die('You have logged out or Your Session has expired');
	}
        TimedOut();
}

// flush the buffer
if($zipIt)
        ob_end_flush();

?>

