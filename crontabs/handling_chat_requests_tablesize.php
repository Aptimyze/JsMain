<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
ini_set("max_execution_time","0");
ini_set("memory_limit",-1);

include("connect.inc");
$dbM=connect_ddl(); //Master connection.
$dbname="userplane";
$curtable="CHAT_REQUESTS";
$newtable="$curtable"."_NEW";
$deltable="DELETED_$curtable";
$temptable="$curtable"."_TEMP";

$to = "nitesh.s@jeevansathi.com";
$from = "info@jeevansathi.com";
$subject = "Drop table";
$msgBody = "Drop table in crontabs/handling_chat_requests_tablesize.php";
send_email($to,$msgBody,$subject,$from);

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM) or die(mysql_error($dbM));
mysql_query("Drop table  IF EXISTS $dbname.$newtable",$dbM) or die(mysql_error($dbM));
mysql_query("create table $dbname.$newtable like $dbname.$curtable",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$curtable to $dbname.$temptable",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$newtable to $dbname.$curtable",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$temptable to $dbname.$newtable",$dbM) or die(mysql_error($dbM));
mysql_query("create table IF NOT EXISTS $dbname.$deltable like $dbname.$newtable",$dbM) or die(mysql_error($dbM));
mysql_query("insert into $dbname.$deltable select * from $dbname.$newtable WHERE TIMEOFINSERTION < DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH )",$dbM) or die(mysql_error($dbM));
mysql_query("DELETE FROM $dbname.$newtable WHERE TIMEOFINSERTION < DATE_SUB( CURDATE( ) , INTERVAL 6 MONTH )",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$newtable to $dbname.$temptable",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$curtable TO $dbname.$newtable",$dbM) or die(mysql_error($dbM));
mysql_query("rename table $dbname.$temptable to $dbname.$curtable",$dbM) or die(mysql_error($dbM));
mysql_query("insert ignore into $dbname.$curtable select * from $dbname.$newtable",$dbM) or die(mysql_error($dbM));
?>
