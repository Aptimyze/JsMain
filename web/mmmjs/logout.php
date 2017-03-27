<?
include "connect.inc";
$cid = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENTID"]);
logout($cid);
//unsetLoginCookies();
$smarty->display("logout.htm");
?>
