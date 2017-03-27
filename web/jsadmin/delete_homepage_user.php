<?php 
/***************************************************************************************************************
* FILE NAME     : delete_homepage_user.php
* DESCRIPTION   : Delete users from Home Page according to preset criteria
* CREATION DATE : 12 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
include(JsConstants::$docRoot."/commonFiles/flag.php");
include("connect.inc");

if($cid)
	$data=$cid;
else if($checksum)
	$data=$checksum;

$smarty->assign("cid",$data);

if(authenticated($data))
{
	if($Continue)
	{
		mysql_select_db_js("newjs",$db);
		$sql="DELETE HP.* FROM newjs.HOMEPAGE_PROFILES AS HP,newjs.JPROFILE AS JP WHERE HP.PROFILEID=JP.PROFILEID AND DATE(JP.LAST_LOGIN_DT)<=DATE_SUB(CURDATE(),INTERVAL 45 DAY)";
		mysql_query_decide($sql) or logError(mysql_error_js(),$sql);

		$sql="DELETE HP.* FROM newjs.HOMEPAGE_PROFILES AS HP,newjs.JPROFILE AS JP WHERE HP.PROFILEID=JP.PROFILEID AND (JP.PRIVACY!='A' OR JP.PHOTO_DISPLAY!='A' OR JP.ACTIVATED <> 'Y')";
		mysql_query_decide($sql) or logError(mysql_error_js(),$sql);


		$msg="The users have been deleted<br>";
                $msg .="<a href=\"manage_homepage.php?cid=$cid\">";
                $msg .="Go To MainPage </a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");

	}
	else if($Cancel)
	{
		$msg="You have chosen not to delete the users<br>";
		$msg .="<a href=\"manage_homepage.php?cid=$cid\">";
	   	$msg .="Go To MainPage </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$smarty->display("delete_homepage_user.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
