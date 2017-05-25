<?php
include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
$rand = rand ( 1 , 100000 );
$msg = urldecode($_GET['message']);
$xmlData1 = $smsVendorObj->generateXml($rand,$_GET['number'],$msg);
$smsVendorObj->send($xmlData1,"transaction");
?>
