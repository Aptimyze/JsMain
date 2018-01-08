<?php
include_once("/usr/local/scripts/DocRoot.php");
ini_set('memory_limit',-1);
$fromCrontab = 1;
include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
$smsObj = new SMSLib;
if($argv[1]) echo $smsObj->getShortURL($argv[1],"","",true);
?>
