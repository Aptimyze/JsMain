<?php
if($_GET["jsm"]=='l@veshResh!')
        ;
else
        die("not allowed");
$SERVER_ARR[]=array("master",MysqlDbConstants::$master["HOST"],MysqlDbConstants::$master["USER"],MysqlDbConstants::$master["PASS"],MysqlDbConstants::$master["PORT"]);
$SERVER_ARR[]=array("shard1",MysqlDbConstants::$shard1["HOST"],MysqlDbConstants::$shard1["USER"],MysqlDbConstants::$shard1["PASS"],MysqlDbConstants::$shard1["PORT"]);
$SERVER_ARR[]=array("shard2",MysqlDbConstants::$shard2["HOST"],MysqlDbConstants::$shard2["USER"],MysqlDbConstants::$shard2["PASS"],MysqlDbConstants::$shard2["PORT"]);
$SERVER_ARR[]=array("shard3",MysqlDbConstants::$shard3["HOST"],MysqlDbConstants::$shard3["USER"],MysqlDbConstants::$shard3["PASS"],MysqlDbConstants::$shard3["PORT"]);
$SERVER_ARR[]=array("shard1Slave",MysqlDbConstants::$shard1Slave["HOST"],MysqlDbConstants::$shard1Slave["USER"],MysqlDbConstants::$shard1Slave["PASS"],MysqlDbConstants::$shard1Slave["PORT"]);
$SERVER_ARR[]=array("shard2Slave",MysqlDbConstants::$shard2Slave["HOST"],MysqlDbConstants::$shard2Slave["USER"],MysqlDbConstants::$shard2Slave["PASS"],MysqlDbConstants::$shard2Slave["PORT"]);
$SERVER_ARR[]=array("shard3Slave",MysqlDbConstants::$shard3Slave["HOST"],MysqlDbConstants::$shard3Slave["USER"],MysqlDbConstants::$shard3Slave["PASS"],MysqlDbConstants::$shard3Slave["PORT"]);
$SERVER_ARR[]=array("viewSimilar",MysqlDbConstants::$viewSimilar["HOST"],MysqlDbConstants::$viewSimilar["USER"],MysqlDbConstants::$viewSimilar["PASS"],MysqlDbConstants::$viewSimilar["PORT"]);
$SERVER_ARR[]=array("bmsSlave",MysqlDbConstants::$bmsSlave["HOST"],MysqlDbConstants::$bmsSlave["USER"],MysqlDbConstants::$bmsSlave["PASS"],MysqlDbConstants::$bmsSlave["PORT"]);
$SERVER_ARR[]=array("alertsSlave",MysqlDbConstants::$alertsSlave["HOST"],MysqlDbConstants::$alertsSlave["USER"],MysqlDbConstants::$alertsSlave["PASS"],MysqlDbConstants::$alertsSlave["PORT"]);

for($i=0;$i<count($SERVER_ARR);$i++)
{
	if(!$db = mysql_connect($SERVER_ARR[$i][1] . ":" . $SERVER_ARR[$i][4],$SERVER_ARR[$i][2],$SERVER_ARR[$i][3]))
		$str.="cannot connect to " . $SERVER_ARR[$i][0] . "\n";
	else
	{
		$res=mysql_query("show status like 'Threads_connected'",$db);
		if(!$res)
		{
			$str.="cannot get thread information from " . $SERVER_ARR[$i][0] . "\n";
			if($_SERVER["DOCUMENT_ROOT"])
				$str.="<br>";
		}
		else
		{
			$row=mysql_fetch_row($res);
			$str.="Threads connected on " . $SERVER_ARR[$i][0] . " = " . $row[1] . "\n";
			if($_SERVER["DOCUMENT_ROOT"])
				$str.="<br>";
		}
	}
}
echo $str;
?>
