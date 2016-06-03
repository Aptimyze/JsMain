<?php
include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("slave");

$sql = "SELECT DISTINCT RECEIVER FROM matchalerts.LOG110 WHERE DATE>=3215";
$result = $mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchArray($result))
{
	$sql1 = "SELECT GENDER FROM newjs.JPROFILE WHERE PROFILEID = ".$row["RECEIVER"];
	$result1 = $mysqlObj->executeQuery($sql1,$db);
	$row1=$mysqlObj->fetchArray($result1);
	if($row1["GENDER"]=="F")
	{
		$sql2 = "DELETE FROM matchalerts.LOG110 WHERE RECEIVER = ".$row["RECEIVER"];
		$mysqlObj->executeQuery($sql2,$db);
		$sql2 = "DELETE FROM matchalerts.LOG_COPY WHERE RECEIVER = ".$row["RECEIVER"];
		$mysqlObj->executeQuery($sql2,$db);
		$sql2 = "DELETE FROM matchalerts.LOG_TEMP WHERE RECEIVER = ".$row["RECEIVER"];
		$mysqlObj->executeQuery($sql2,$db);
		$sql2 = "DELETE FROM matchalerts.MAILER WHERE RECEIVER = ".$row["RECEIVER"];
		$mysqlObj->executeQuery($sql2,$db);
		$sql2 = "DELETE FROM matchalerts.PROFILE_LOGS WHERE PROFILEID = ".$row["RECEIVER"];
		$mysqlObj->executeQuery($sql2,$db);
	}
}

echo "DONE\n";
mysql_close($db);
?>
