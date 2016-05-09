<?php

include('connect.inc');
$db_slave=connect_slave();
$db_master=connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',25920000);
ini_set('log_errors_max_len',0);

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

$count=0;
$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_RES=''";
$res=mysql_query($sql,$db_slave) or die($sql);
while($row=mysql_fetch_array($res))
{
	$pid=$row['PROFILEID'];
	$sql_1="UPDATE newjs.JPROFILE SET PHONE_WITH_STD='' AND STD='' WHERE PROFILEID='$pid'";
	mysql_query($sql_1,$db_master) or die(error($db_master));
	$count++;
}

$message='One time cron has been executed succesfully, Total affected records are ===>'.$count.'       '.'Date of Execution ==> '.date('Y-m-d');
send_email('anurag.gautam@jeevansathi.com',$message,'OneTimeCron PHONE_WTH_STD Blank Done');

function error($db)
{
	mail('anurag.gautam@jeevansathi.com','OneTimeCron PHONE_WTH_STD Blank Error Occured',mysql_error($db));
}

?>
