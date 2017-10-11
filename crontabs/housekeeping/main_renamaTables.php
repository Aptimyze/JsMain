<?php

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once("../../P/connect.inc");
/*
$db=connect_db();
$sql="RENAME TABLE BOOKMARKS TO BOOKMARKS_BEFOREHOUSEKEEPING , BOOKMARKS_ACTIVE TO BOOKMARKS";
echo $sql."\n";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
mysql_close($db);
*/

include_once("../../classes/Mysql.class.php");
$mysqlObj=new Mysql;

for($activeServerId=0;$activeServerId<3;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
	$db=$mysqlObj->connect("$myDbName");
echo $activeServerId."--".$myDbName."==".$db."\n";

	$sql="RENAME TABLE CONTACTS TO CONTACTS_BEFOREHOUSEKEEPING , CONTACTS_ACTIVE TO CONTACTS";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="RENAME TABLE MESSAGE_LOG TO MESSAGE_LOG_BEFOREHOUSEKEEPING , MESSAGE_LOG_ACTIVE TO MESSAGE_LOG";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="RENAME TABLE MESSAGES TO MESSAGES_BEFOREHOUSEKEEPING , MESSAGES_ACTIVE TO MESSAGES";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="RENAME TABLE EOI_VIEWED_LOG TO EOI_VIEWED_LOG_BEFOREHOUSEKEEPING , EOI_VIEWED_LOG_ACTIVE TO EOI_VIEWED_LOG";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	
	if($i==1)
	{
		$sql="RENAME TABLE VIEW_LOG TO VIEW_LOG_BEFOREHOUSEKEEPING , VIEW_LOG_ACTIVE TO VIEW_LOG";
echo $sql."\n";
		mysql_query($sql,$db) or die(mysql_error($db).$sql);
	}

	$sql="RENAME TABLE PHOTO_REQUEST TO PHOTO_REQUEST_BEFOREHOUSEKEEPING , PHOTO_REQUEST_ACTIVE TO PHOTO_REQUEST";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="RENAME TABLE HOROSCOPE_REQUEST TO HOROSCOPE_REQUEST_BEFOREHOUSEKEEPING , HOROSCOPE_REQUEST_ACTIVE TO HOROSCOPE_REQUEST";
echo $sql."\n";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
}
?>

