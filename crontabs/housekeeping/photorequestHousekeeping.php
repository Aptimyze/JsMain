<?php
include_once("commonHousekeeping.php");
$counter=0;

$table="newjs.PHOTO_REQUEST_ACTIVE";
$sourceTable="newjs.PHOTO_REQUEST";
$inactiveRecordTable="newjs.INACTIVE_RECORDS_6MONTHS";//make this as inactive records without delete
$deletedRecordTable="newjs.DELETED_PROFILES";

$time_ini = microtime_float();
$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT * FROM $sourceTable WHERE DATE<'$photoHouseKeepingTime'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$sql="DELETE $table.* FROM $table JOIN $inactiveRecordTable  WHERE $table.PROFILEID=$inactiveRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="DELETE $table.* FROM $table JOIN $inactiveRecordTable  WHERE $table.PROFILEID_REQ_BY=$inactiveRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT * FROM $sourceTable WHERE DATE>='$photoHouseKeepingTime'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$sql="DELETE $table.* FROM $table JOIN $deletedRecordTable  WHERE $table.PROFILEID=$deletedRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="DELETE $table.* FROM $table JOIN $deletedRecordTable  WHERE $table.PROFILEID_REQ_BY=$deletedRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);

$sql="ALTER TABLE $table ENABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();
?>
