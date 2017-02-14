<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once("../../P/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
include_once("commonHousekeeping.php");
$counter=0;

$table="test.DELETED_PROFILES";
$table2="test.INACTIVE_RECORDS_6MONTHS";
;
$time_ini = microtime_float();

$to = "nitesh.s@jeevansathi.com";
$from = "info@jeevansathi.com";
$subject = "Alter table";
$msgBody = "Alter table in crontabs/housekeeping/main_createTablesInactive_and_deleted.php";
send_email($to,$msgBody,$subject,$from);

$sql="ALTER TABLE $table DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="INSERT INTO $table SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED='D'";
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


$sql="ALTER TABLE $table2 DISABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

//$sql="INSERT INTO $table2 SELECT PROFILEID FROM newjs.JPROFILE WHERE DATE_SUB(NOW(),INTERVAL 6 MONTH)>LAST_LOGIN_DT AND ACTIVATED<>'D'";
$sql="INSERT INTO $table2 SELECT PROFILEID FROM newjs.JPROFILE WHERE '$time6Months'>LAST_LOGIN_DT AND ACTIVATED<>'D'";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();

$sql="ALTER TABLE $table2 ENABLE KEYS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$time_end = microtime_float();
laveshEcho($db,$time_end,$time_ini);
$time_ini = microtime_float();
?>
