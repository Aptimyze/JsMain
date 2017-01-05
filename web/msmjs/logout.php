<?
include "connect.inc";
$cid = preg_replace('/[^A-Za-z0-9\. -_]/', '', $_COOKIE["CRM_NOTIFICATION_AGENTID"]);
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
logout($cid);
//unsetLoginCookies();
$smarty->display("logout.htm");
?>
