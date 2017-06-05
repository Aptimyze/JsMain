<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj = new Mysql;
$db81=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db81);
mysql_select_db("kundli_alert",$db81);

$sql = "DELETE FROM kundli_alert.API_OUTPUT WHERE STATUS = 'N'";
$mysqlObj->executeQuery($sql,$db81) or die($sql);

$sql = "SELECT NO,DATE FROM kundli_alert.LAST_ACTIVE_LOG WHERE TYPE = \"P\"";
$result = $mysqlObj->executeQuery($sql,$db81) or die($sql);
$row = $mysqlObj->fetchArray($result);

if($row["DATE"])
	$days = (JSstrToTime(date("Y-m-d"))-JSstrToTime($row["DATE"]))/(24*60*60);
else
	$days = 1;

if($row["NO"])
{
	if($days>=0 && $days<14)
	{
	}
	else
	{
		$a=$row["NO"]+1;
		if($row["NO"]==1)
		{
			$x=$row["NO"];	
			$logArr[] = "LOG".$x;
			$logArr[] = "LOG".$a;
		}
		else
		{
			$x=$row["NO"];
			$y=$x-1;
			$logArr[] = "LOG".$y;
			$logArr[] = "LOG".$x;
			$logArr[] = "LOG".$a;
		}
		$logStr = implode(",",$logArr);

		$sql = "CREATE TABLE kundli_alert.LOG".$a." LIKE kundli_alert.LOG".$x;
		$mysqlObj->executeQuery($sql,$db81) or die($sql);

		$sql = "UPDATE kundli_alert.LAST_ACTIVE_LOG SET NO = ".$a.",DATE = NOW() WHERE TYPE = \"P\"";
		$mysqlObj->executeQuery($sql,$db81) or die($sql);
		
		$sql = "DROP TABLE kundli_alert.KUNDLI_CONTACT_CENTER";
		$mysqlObj->executeQuery($sql,$db81) or die($sql);

		$sql = "CREATE TABLE kundli_alert.KUNDLI_CONTACT_CENTER (PROFILEID int(11) unsigned NOT NULL,MATCHID int(11) unsigned NOT NULL,GUNA float NOT NULL,LAGNA tinyint(2) NOT NULL,SUN tinyint(2) NOT NULL,MERCURY tinyint(2) NOT NULL,JUPITER tinyint(2) NOT NULL,SATURN tinyint(2) NOT NULL,MARS tinyint(2) NOT NULL,VENUS tinyint(2) NOT NULL,ENTRY_DT datetime DEFAULT NULL,MAIL_DT date NOT NULL,PRIMARY KEY (PROFILEID,MATCHID)) ENGINE=MRG_MyISAM DEFAULT CHARSET=latin1 INSERT_METHOD=LAST UNION=(".$logStr.")";
		$mysqlObj->executeQuery($sql,$db81) or die($sql);
		
	}
}
else
{
	$sql = "CREATE TABLE kundli_alert.LOG1 (PROFILEID int(11) unsigned NOT NULL,MATCHID int(11) unsigned NOT NULL,GUNA float NOT NULL,LAGNA tinyint(2) NOT NULL,SUN tinyint(2) NOT NULL,MERCURY tinyint(2) NOT NULL,JUPITER tinyint(2) NOT NULL,SATURN tinyint(2) NOT NULL,MARS tinyint(2) NOT NULL,VENUS tinyint(2) NOT NULL,ENTRY_DT datetime DEFAULT NULL,MAIL_DT date NOT NULL,PRIMARY KEY (PROFILEID,MATCHID)) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$mysqlObj->executeQuery($sql,$db81) or die($sql);

	$sql = "CREATE TABLE kundli_alert.KUNDLI_CONTACT_CENTER (PROFILEID int(11) unsigned NOT NULL,MATCHID int(11) unsigned NOT NULL,GUNA float NOT NULL,LAGNA tinyint(2) NOT NULL,SUN tinyint(2) NOT NULL,MERCURY tinyint(2) NOT NULL,JUPITER tinyint(2) NOT NULL,SATURN tinyint(2) NOT NULL,MARS tinyint(2) NOT NULL,VENUS tinyint(2) NOT NULL,ENTRY_DT datetime DEFAULT NULL,MAIL_DT date NOT NULL,PRIMARY KEY (PROFILEID,MATCHID)) ENGINE=MRG_MyISAM DEFAULT CHARSET=latin1 INSERT_METHOD=LAST UNION=(LOG1)";
	$mysqlObj->executeQuery($sql,$db81) or die($sql);

	$sql = "INSERT INTO kundli_alert.LAST_ACTIVE_LOG(NO,DATE,TYPE) VALUES (\"1\",NOW(),\"P\")";
	$mysqlObj->executeQuery($sql,$db81) or die($sql);
}

mysql_close($db81);
?>
