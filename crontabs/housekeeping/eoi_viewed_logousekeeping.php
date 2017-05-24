<?php
include_once("commonHousekeeping.php");
$counter=0;


$table="test.EOI_VIEWED_LOG_ACTIVE";
$sourceTable="newjs.EOI_VIEWED_LOG";
$joinTable="test.CONTACTS_ACTIVE";

$time_ini = microtime_float();

$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$sql="INSERT INTO $table  SELECT A.* FROM $sourceTable A , $joinTable B where A.VIEWER=B.SENDER AND A.VIEWED=B.RECEIVER";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT IGNORE INTO $table  SELECT A.* FROM $sourceTable A , $joinTable B where A.VIEWER=B.RECEIVER AND A.VIEWED=B.SENDER ";//ignore plzz chk why needed
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
