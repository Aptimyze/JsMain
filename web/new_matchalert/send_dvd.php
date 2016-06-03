<?php
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

chdir(dirname(__FILE__));
include_once("connect.inc");
include_once(JsConstants::$alertDocRoot."/new_matchalert/configVariables.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/Receiver.class.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/StrategyDvD.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/SendDvDAlert.php");
include_once(JsConstants::$alertDocRoot."/classes/shardingRelated.php");

$mysqlObj=new Mysql;

$localdb=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$localdb);
mysql_select_db("matchalerts",$localdb) or die(mysql_error());

$db_211=$mysqlObj->connect("viewLogSlave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);
mysql_select_db("newjs",$db_211) or die(mysql_error());

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId,'slave',$mysqlObj);

	$myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$myDbArr[$myDbName]);
}

$db_fast=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_fast);
mysql_select_db("matchalerts",$db_fast) or die(mysql_error());

$sql_loop="SELECT PROFILEID FROM matchalerts.DVD_PROFILES WHERE SENT='T'";
$result_loop=mysql_query($sql_loop,$localdb) or logerror1("In check_trends.php",$sql_loop);
while($row_loop=mysql_fetch_array($result_loop))
{
	$profileId=$row_loop["PROFILEID"];
echo $profileId.",";
	$sendAlertObject = new SendMatchAlert($profileId , $myDbArr , $localdb , $mysqlObj);
	$sendAlertObject->send($idForTarcking);

	$sql="UPDATE matchalerts.DVD_PROFILES SET SENT='N' WHERE PROFILEID='$profileId'";
	mysql_query($sql,$localdb) or die(mysql_error($localdb).$sql);	
}
?>
