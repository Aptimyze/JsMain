<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include_once(JsConstants::$cronDocRoot."/crontabs/openfire_archiving.inc");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db=connect_openfire();
//TODO change it to actual log file name
$logName="/tmp/openfire_archiving.log";
$logHandle=fopen($logName,"a");
openfire_housekeeping();
fclose($logHandle);
?>
