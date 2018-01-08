<?php
//$path=realpath(dirname(__FILE__)."/../../..");
//include_once("$path/profile/connect.inc");
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
$db=connect_ddl();
$sql1="TRUNCATE TABLE sugarcrm.auto_mailer";
$res=mysql_query_decide($sql1,$db) or die("Error while truncating sugarcr.auto_mailer".mysql_error_js());
$sql2="TRUNCATE TABLE sugarcrm.auto_sms";
$res=mysql_query_decide($sql2,$db) or die("Error while truncating sugarcr.auto_mailer".mysql_error_js());
//mysql_query_decide($sql1) or die(mysql_error());
//mysql_query_decide($sql2) or die(mysql_error());
?>
