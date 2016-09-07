<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once("connect.inc");
$mysqlObj = new Mysql;

$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("matchalerts",$db) or mysql_error1($db);
/*
$sql="TRUNCATE TABLE matchalerts.LOG_TEMP";
mysql_query($sql,$db) or mysql_error1($db);
*/
passthru(JsConstants::$php5path." /var/www/html/symfony cron:matchAlertsReplacePartitions");

function mysql_error1($db)
{
        global $sql;
	$msg=$sql."-->>>".mysql_error($db);
	echo $msg;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","matchalertsMoveLogTables.php",$msg);
	die;

}


?>
