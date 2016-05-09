<?php
include("connect.inc");
include("lib/SendMessage.class.php");
$sendMessageObj = new SendMessage();
//$db=connect_db();
$smarty->assign("cid",$cid);
if(!getAuthenticationRoutine($cid))
	$smarty->display("msm_relogin.htm");
$smarty->assign("msmjsHeader",$smarty->fetch("msmjsHeader.htm"));
if($useFile)
{
	if($useFile == 'upload')
		$fileName = $_FILES['uploadedFile'];
	$sendMessageObj->wrapperSendSms($useFile, $fileId, $fileName, $message, $fromMobile);
        /*if(!$_FILE)
        {
                $messageId = $sendMessageObj->insertMessageDetail($fileId, $message, $fromMobile);
		$source = $fileName;
		$destination = 'sms_'.$messageId.'.csv';
		$sendMessageObj->copyFileToFinalCsvPath($source, $destination);
        }
        else
        {
                $messageId = $sendMessageObj->insertMessageDetail($fileId, $message, $fromMobile);
                $_FILE['fileName']['name'] = 'sms_'.$messageId.'.csv';
		$destination = $_FILE['fileName']['name'];
                $sendMessageObj->uploadFile($_FILE['uploadedFile']);
        }
        $mobile = $sendMessageObj->getCSV($destination);
        $to = $sendMessageObj->checkDuplicateMobileNumbers($mobile);
	$sendMessageObj->updateUploadedFileDetail($messageId, $destination, count($to));
        //$to[] = '919911566742';

        //$sendMessageObj->sendSMS($message, $fromMobile, $to);*/
	$smarty->assign("status", 'sentSms');
}
$smarty->assign('setMessageWidget',$smarty->fetch('setMessageWidget.htm'));
$file = $sendMessageObj->getFileDetail($fileId);
$smarty->assign('fileId',$fileId);
$smarty->assign("sql",$file[0]['sql']);
$smarty->assign("count",$file[0]['count']);
$smarty->assign("fileName",$file[0]['fileName']);
$sms = $sendMessageObj->getSmsDetail($fileId);
$smarty->assign('sms', $sms);
$smarty->assign('uploadedFileWidget', $smarty->fetch('uploadedFileWidget.htm'));
$smarty->display("setMessage.htm");
?>
