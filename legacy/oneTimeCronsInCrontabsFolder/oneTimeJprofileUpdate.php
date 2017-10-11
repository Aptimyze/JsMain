<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**
 * This cron is used to change the values of column newjs.JPROFILE.PHOTOSCREEN according to the new photo module.
 * Earlier this column had values ranging from 0 to 31, not it would have a valu either 0 or 1.
 * author prinka / lavesh
**/

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days

include_once("../profile/config.php");
include("../classes/Mysql.class.php");
include("connect.inc");
$db = connect_db();

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$db) or die(mysql_error().$sql);

$sql = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = 0 WHERE PHOTOSCREEN < 31";
mysql_query($sql,$db) or die(mysql_error().$sql);

$sql = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = 1 WHERE PHOTOSCREEN = 31";
mysql_query($sql,$db) or die(mysql_error().$sql);

$sql="UPDATE JPROFILE SET PHOTO_DISPLAY='C' WHERE PHOTO_DISPLAY='H'";
mysql_query($sql,$db) or die(mysql_error().$sql);

$sql="UPDATE SEARCH_MALE SET PHOTO_DISPLAY='C' WHERE PHOTO_DISPLAY='H'";
mysql_query($sql,$db) or die(mysql_error().$sql);

$sql="UPDATE SEARCH_FEMALE SET PHOTO_DISPLAY='C' WHERE PHOTO_DISPLAY='H'";
mysql_query($sql,$db) or die(mysql_error().$sql);

?>
