<?
include "connect.inc";
$cid = $_COOKIE["CRM_NOTIFICATION_AGENTID"];
logout($cid);
unsetLoginCookies();
$smarty->display("logout.htm");
?>
