<?php 

include "connect.inc";
$mysqlObj=new Mysql;

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $db=$mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=100000,interactive_timeout=100000,net_read_timeout=100000',$db);

	$sql="ALTER TABLE newjs.CONTACTS ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	$sql="ALTER TABLE newjs.DELETED_PROFILE_CONTACTS ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="ALTER TABLE newjs.HOROSCOPE_REQUEST ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	$sql="ALTER TABLE newjs.DELETED_HOROSCOPE_REQUEST ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);

	$sql="ALTER TABLE newjs.PHOTO_REQUEST ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	$sql="ALTER TABLE newjs.DELETED_PHOTO_REQUEST ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	
	$sql="ALTER TABLE newjs.MESSAGE_LOG ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
	$sql="ALTER TABLE newjs.DELETED_MESSAGE_LOG ENGINE=INNODB;";
	mysql_query($sql,$db) or die(mysql_error($db).$sql);
}

$db=connect_db();
mysql_query('set session wait_timeout=100000,interactive_timeout=100000,net_read_timeout=100000',$db);

$sql="ALTER TABLE newjs.BOOKMARKS ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$sql="ALTER TABLE newjs.DELETED_BOOKMARKS ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);


$sql="ALTER TABLE newjs.IGNORE_PROFILE ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$sql="ALTER TABLE newjs.DELETED_IGNORE_PROFILE ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);

$sql="ALTER TABLE jsadmin.OFFLINE_MATCHES ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$sql="ALTER TABLE jsadmin.DELETED_OFFLINE_MATCHES ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);


$sql="ALTER TABLE jsadmin.OFFLINE_NUDGE_LOG ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
$sql="ALTER TABLE jsadmin.DELETED_OFFLINE_NUDGE_LOG ENGINE=INNODB;";
mysql_query($sql,$db) or die(mysql_error($db).$sql);
?>

