<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "TRUNCATE TABLE RELIGION_ANAND";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "INSERT INTO RELIGION_ANAND SELECT * FROM RELIGION";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "SELECT MAX(VALUE) AS VALUE FROM newjs.RELIGION_ANAND";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$row = $mysqlObjM->fetchArray($result);
$maxValue = $row["VALUE"]+1;

$statement = "INSERT INTO RELIGION_ANAND(ID,LABEL,VALUE) VALUES (\"\",\"Bahai\",".$maxValue.")";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "SELECT VALUE FROM newjs.RELIGION_ANAND ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$update_statement = "UPDATE newjs.RELIGION_ANAND SET ALPHA_ORDER = CASE VALUE ";
$valueStr = "";
$x=1;
while($row = $mysqlObjM->fetchArray($result))
{
	if($row["VALUE"] == 8)
	{
	}
	else
	{
		$update_statement = $update_statement."WHEN ".$row["VALUE"]." THEN ".$x." ";
		$valueStr = $valueStr.$row["VALUE"].",";	
		$x++;
	}
}

$update_statement = $update_statement."WHEN 8 THEN ".$x." ";
$valueStr = $valueStr."8";

$update_statement = $update_statement."END WHERE VALUE IN (".$valueStr.")";
$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);

$statement = "SELECT SORTBY FROM newjs.RELIGION_ANAND WHERE VALUE = 8";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$row = $mysqlObjM->fetchArray($result);

$update_statement = "UPDATE newjs.RELIGION_ANAND SET SORTBY = CASE VALUE WHEN 8 THEN ".($row["SORTBY"]+1)." WHEN 10 THEN ".$row["SORTBY"]." END WHERE VALUE IN (8,10)";
$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

