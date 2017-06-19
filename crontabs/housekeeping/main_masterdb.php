<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once("../../P/connect.inc");
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
include_once("commonHousekeeping.php");
$counter=0;

$scriptTimeIni = microtime_float();
include("bookmarksHousekeeping.php");
laveshEcho1($scriptTimeIni);

$scriptTimeIni = microtime_float();
include("bookmarksHousekeeping_inactivedeleted.php");//-1-
laveshEcho1($scriptTimeIni);

?>

