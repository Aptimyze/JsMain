<?php

$_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;
$path=$_SERVER['DOCUMENT_ROOT'];
include_once($path."/jsadmin/connect.inc");
//include("connect.inc");

$db_slave = connect_slave();
$db_master = connect_db();

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

$sql="SELECT PROFILEID FROM jsadmin.DELETE_PROFILE_DUPLICATE WHERE DELETED='N'";
$res= mysql_query($sql,$db_slave) or die(mysql_error($db_slave));

while($row=mysql_fetch_array($res))
{
	$profileid=$row['PROFILEID'];
	
	$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null";
	//$cmd = "php -q ".$path;
	$cmd = "/usr/bin/php -q ".$path;
	shell_exec($cmd);

	$sql_1="UPDATE jsadmin.DELETE_PROFILE_DUPLICATE SET DELETED ='Y' WHERE PROFILEID='$profileid'";
	mysql_query($sql_1,$db_master) or die(mysql_error($db_master));
}

?>
