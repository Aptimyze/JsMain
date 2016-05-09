<?
include "connect.inc";
$cid = $_COOKIE["CRM_NOTIFICATION_AGENTID"];
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
logout($cid);
//unsetLoginCookies();
$smarty->display("logout.htm");
?>
