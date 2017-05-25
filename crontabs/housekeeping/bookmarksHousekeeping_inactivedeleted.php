<?php
include_once("commonHousekeeping.php");
$counter=0;

$joinTable="newjs.BOOKMARKS_ACTIVE";
$table="newjs.BOOKMARKS_INACTIVE";
$sourceTable="newjs.BOOKMARKS";

$time_ini = microtime_float();
$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT A . * FROM $sourceTable A LEFT JOIN $joinTable B ON A.BOOKMARKER=B.BOOKMARKER AND A.BOOKMARKEE=B.BOOKMARKEE WHERE B.BOOKMARKER IS NULL AND B.BOOKMARKEE IS NULL";
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
