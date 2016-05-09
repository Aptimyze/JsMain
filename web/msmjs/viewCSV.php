<?php
include("connect.inc");
include("lib/SendMessage.class.php");
if(!getAuthenticationRoutine($cid))
	$smarty->display("msm_relogin.htm");
$sendMessageObj = new SendMessage;
if($uploaded)
	$filePath = JsConstants::$alertDocRoot."/msmjs/finalCSV";
else
	$filePath = JsConstants::$alertDocRoot."/msmjs/tempCSV";
$sendMessageObj->serveFile($filePath, $fileName);

?>
