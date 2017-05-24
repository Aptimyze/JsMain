<?php
include("connect.inc");
include("lib/SendMessage.class.php");
//$db=connect_db();
$smarty->assign("cid",$cid);
if(!getAuthenticationRoutine($cid))
        $smarty->display("msm_relogin.htm");
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
$sendMessageObj = new SendMessage;
if($resend)
{
	//$mobile = $sendMessageObj->getCSV($fileName);
	$sms = $sendMessageObj->getSentSms($messageId);
	$smarty->assign('fileName',$sms[0]['fileName']);
	$smarty->assign('sql',stripslashes($sms[0]['sql']));
	$smarty->assign('count',$sms[0]['sentCount']);
	$smarty->assign('messageId', $messageId);
	$smarty->assign('resend', 1);
	$smarty->assign('message', $sms[0]['message']);
	$smarty->assign('title', $sms[0]['title']);
	$smarty->assign('fromMobile', $sms[0]['fromMobile']);
	$smarty->display('setMessage.htm');
}
else
{
	$sms = $sendMessageObj->getSentSms("");
	$smarty->assign('sms', $sms);
	$smarty->display('resentMessage.htm');
}
?>
