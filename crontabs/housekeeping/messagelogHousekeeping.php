<?php
include_once("commonHousekeeping.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$counter=0;


$table="test.MESSAGE_LOG_ACTIVE";
$sourceTable="newjs.MESSAGE_LOG";
$joinTable="test.CONTACTS_ACTIVE";

$time_ini = microtime_float();

$sql="INSERT IGNORE INTO $table  SELECT A.* FROM $sourceTable A , $joinTable B where A.SENDER=B.RECEIVER AND A.RECEIVER=B.SENDER ";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$to = "nitesh.s@jeevansathi.com";
$from = "info@jeevansathi.com";
$subject = "Alter table";
$msgBody = "Alter table in crontabs/housekeeping/messagelogHousekeeping.php";
send_email($to,$msgBody,$subject,$from);

$sql="ALTER TABLE $table ENABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();
?>
