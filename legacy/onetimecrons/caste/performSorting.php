<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "SELECT b.VALUE AS VALUE,b.LABEL AS LABEL,b.TOP_SORTBY AS TOP_SORTBY FROM newjs.CASTE a, newjs.CASTE b WHERE a.ISALL = \"Y\" AND a.PARENT = b.PARENT ORDER BY a.TOP_SORTBY,b.LABEL,b.VALUE DESC";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$updateStr = "UPDATE newjs.CASTE SET SORTBY = CASE VALUE ";
$valueStr = "";
$x = 1;
while($row = $mysqlObjM->fetchArray($result))
{
	if ($row["TOP_SORTBY"]>1 && $row["TOP_SORTBY"]<7)
	{
		$updateStr = $updateStr."WHEN ".$val." THEN ".$x." ";
                $valueStr = $valueStr.$val.",";
		$x++;	
	}
	if (strpos($row["LABEL"],"Other") === false)
	{
		$updateStr = $updateStr."WHEN ".$row["VALUE"]." THEN ".$x." ";
		$valueStr = $valueStr.$row["VALUE"].",";
		$x++;
	}
	else
	{
		$val = $row["VALUE"];
	}
}

$statement1 = "SELECT VALUE FROM newjs.CASTE WHERE PARENT IN (151,152)";
$result1 = $mysqlObjM->executeQuery($statement1,$dbM) or die($statement1);

while($row1 = $mysqlObjM->fetchArray($result1))
{
	$updateStr = $updateStr."WHEN ".$row1["VALUE"]." THEN ".$x." ";                         
        $valueStr = $valueStr.$row1["VALUE"].",";
        $x++;
}
$valueStr = rtrim($valueStr,",");
$updateStr = $updateStr."END WHERE VALUE IN (".$valueStr.")";
$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
