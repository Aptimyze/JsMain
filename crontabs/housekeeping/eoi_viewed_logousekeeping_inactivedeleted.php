<?php
include_once("commonHousekeeping.php");
$counter=0;

$joinTable="test.EOI_VIEWED_LOG_ACTIVE";
$table="test.EOI_VIEWED_LOG_INACTIVE";
$sourceTable="newjs.EOI_VIEWED_LOG";

$time_ini = microtime_float();
$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT A . * FROM $sourceTable A LEFT JOIN $joinTable B ON A.VIEWER=B.VIEWER AND A.VIEWED=B.VIEWED WHERE B.VIEWER IS NULL AND B.VIEWED IS NULL";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$to = "nitesh.s@jeevansathi.com";
$from = "info@jeevansathi.com";
$subject = "Alter table";
$msgBody = "Alter table in crontabs/housekeeping/eoi_viewed_logousekeeping_inactivedeleted.php";
send_email($to,$msgBody,$subject,$from);

$sql="ALTER TABLE $table ENABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();
?>
