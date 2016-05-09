<?php
include("connect.inc");
include("lib/SendMessage.class.php");
$sendMessageObj = new SendMessage();
//$db=connect_db();
$smarty->assign("cid",$cid);
if(!getAuthenticationRoutine($cid))
	$smarty->display("msm_relogin.htm");
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
$smarty->assign('setMessageWidget',$smarty->fetch('setMessageWidget.htm'));
$smarty->assign('fileId',$fileId);
$smarty->display("uploadFile.htm");
?>
