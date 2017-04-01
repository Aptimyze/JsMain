<?php
include_once("commonHousekeeping.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$counter=0;

$table="newjs.BOOKMARKS_ACTIVE";
$sourceTable="newjs.BOOKMARKS";
$inactiveRecordTable="newjs.INACTIVE_RECORDS_6MONTHS";//make this as inactive records without delete
$deletedRecordTable="newjs.DELETED_PROFILES";

$time_ini = microtime_float();


$to = "nitesh.s@jeevansathi.com";
$from = "info@jeevansathi.com";
$subject = "Alter table";
$msgBody = "Alter table in crontabs/housekeeping/bookmarksHousekeeping.php";
send_email($to,$msgBody,$subject,$from);

$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT * FROM $sourceTable WHERE BKDATE<'$bookmarkHouseKeepingTime'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$sql="DELETE $table.* FROM $table JOIN $inactiveRecordTable  WHERE $table.BOOKMARKER=$inactiveRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="DELETE $table.* FROM $table JOIN $inactiveRecordTable  WHERE $table.BOOKMARKEE=$inactiveRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT * FROM $sourceTable WHERE BKDATE>='$bookmarkHouseKeepingTime'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();


$sql="DELETE $table.* FROM $table JOIN $deletedRecordTable  WHERE $table.BOOKMARKER=$deletedRecordTable.PROFILEID";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="DELETE $table.* FROM $table JOIN $deletedRecordTable  WHERE $table.BOOKMARKEE=$deletedRecordTable.PROFILEID";
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
