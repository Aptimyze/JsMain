<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

$matchalertServer = 1;

//INCLUDE FILES HERE
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/connect.inc");
include_once(JsConstants::$alertDocRoot."/classes/shardingRelated.php");
include_once("StrategyKundli.php");
include_once("PopulateTables.class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//INCLUDE FILE ENDS

$mysqlObj = new Mysql;

$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=100000,interactive_timeout=100000,net_read_timeout=100000',$localdb);

$db_fast = $localdb;

$db_211=$mysqlObj->connect("viewLogSlave");
mysql_query('set session wait_timeout=100000,interactive_timeout=100000,net_read_timeout=100000',$db_211);
mysql_select_db("newjs",$db_211) or die(mysql_error());

$api_output_failure = 0;

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObj);
        $myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$myDbArr[$myDbName]);
}
?>
