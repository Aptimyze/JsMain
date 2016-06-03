<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

$activeServerId=$_SERVER['argv'][1];
if(!$activeServerId && $activeServerId!='0')
        exit("PLEASE ENTER AN ID BETWEEN 0 AND 2");
if(!in_array($activeServerId,array(0,1,2)))
        exit("PLEASE ENTER AN ID BETWEEN 0 AND 2.OUT OF RANGE");
include_once("../../P/connect.inc");
include_once("../../classes/Mysql.class.php");
$mysqlObj=new Mysql;
$myDbName=getActiveServerName($activeServerId);
$db=$mysqlObj->connect("$myDbName");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

if(!$db)
        exit("db not creating");

include_once("commonHousekeeping.php");
$counter=0;

$scriptTimeIni = microtime_float();
include("photorequestHousekeeping.php");//-1-
laveshEcho1($scriptTimeIni);

$scriptTimeIni = microtime_float();
include("horoscopeHousekeeping.php");//-1-
laveshEcho1($scriptTimeIni);

$scriptTimeIni = microtime_float();
include("photorequestHousekeeping_inactivedeleted.php");
laveshEcho1($scriptTimeIni);

$scriptTimeIni = microtime_float();
include("horoscopeHousekeeping_inactivedeleted.php");
laveshEcho1($scriptTimeIni);


?>
