<?php
ini_set("memory_limit","512M");
ini_set("max_execution_time","0");
include("connect.inc");
include("lib/SendMessage.class.php");
//$db=connect_db();
$smarty->assign("cid",$cid);
if(!getAuthenticationRoutine($cid))
	$smarty->display("msm_relogin.htm");
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
if($sendMessage)
{
	$sendMessageObj = new SendMessage;
	$fileName = $_FILES['uploadedFile'];
	$sendMessageObj->wrapperSendSms($fileId, $fileName, $messageType);
	$smarty->assign('success',true);
	$smarty->display("optForm.htm");
}
?>
