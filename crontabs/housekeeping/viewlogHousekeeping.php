<?php
include_once("commonHousekeeping.php");
$counter=0;

$table="test.VIEW_LOG_ACTIVE";
$sourceTable="newjs.VIEW_LOG";
$inactiveRecordTable="test.INACTIVE_RECORDS_6MONTHS";//make this as inactive records without delete
$deletedRecordTable="test.DELETED_PROFILES";

$time_ini = microtime_float();
$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT * FROM $sourceTable WHERE DATE>='$viewlogTime'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="ALTER TABLE $table ENABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();
?>
