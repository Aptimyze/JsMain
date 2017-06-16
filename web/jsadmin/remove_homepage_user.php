<?php
/***************************************************************************************************************
* FILE NAME     : remove_homepage_user.php 
* DESCRIPTION   : Removes a user from the Home Page
* CREATION DATE : 11 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

include("connect.inc");

if($cid)
	$data=$cid;
else if($checksum)
	$data=$checksum;

$smarty->assign("cid",$data);

if(authenticated($data))
{
	if($Yes)
	{
		$smarty->assign("asked","1");
		$sql="DELETE FROM newjs.HOMEPAGE_PROFILES WHERE PROFILEID='$ID'";
		$res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
		$smarty->display("remove_homepage_user.htm");

	}
	else if($No)
	{
		$smarty->assign("asked","2");
		$smarty->display("remove_homepage_user.htm");
	}
	else
	{
		$smarty->assign("asked","0");
		$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$ID'";
		$res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$uname=$row['USERNAME'];
		$smarty->assign("uname",$uname);
		$smarty->assign("ID",$ID);
		$smarty->display("remove_homepage_user.htm");
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
