<?php
                                                                                                 
include ("connect.inc");
$cid = $_COOKIE["CRM_NOTIFICATION_AGENTID"];
if($cid)
	$lout=logout($cid);
setcookie("OPERATOR",'',0,"/",$domain);
unsetLoginCookies();

if($lout || !$cid)
{
	$msg="You have successfully logged out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

?>
