<?php
ini_set("memory_limit","512M");
ini_set("max_execution_time","0");
include("connect.inc");
include("lib/SendMessage.class.php");
$sendMessageObj = new SendMessage;
$fileName = $_FILES['uploadedFile'];
$sendMessageObj->wrapperSendSms($fileId, $fileName, $messageType);
?>
