<?php
$fromCrontab = 1;
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/ScheduleSms.class.php");
$vd_type = $argv[1];
$sms1 = new ScheduleSms;
$sms1->processData($vd_type,'');
?>