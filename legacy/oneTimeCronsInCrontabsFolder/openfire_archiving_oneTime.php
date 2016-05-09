<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include_once("openfire_archiving.inc");
include_once("../profile/connect.inc");
$db=connect_openfire();
//TODO change it to actual log file name
$logName="/tmp/openfire_archiving.log";
$logHandle=fopen($logName,"a");
one_time();
fclose($logHandle);
?>
