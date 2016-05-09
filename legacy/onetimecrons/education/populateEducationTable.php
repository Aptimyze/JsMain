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

$statement = "SELECT VALUE FROM newjs.EDUCATION_LEVEL_NEW WHERE VALUE!=22 ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$updateStr = "UPDATE newjs.EDUCATION_LEVEL_NEW SET SORTBY = CASE VALUE ";
$valueStr = "";
$x=1;
while($row = $mysqlObjM->fetchArray($result))
{
        $updateStr = $updateStr."WHEN ".$row["VALUE"]." THEN ".$x." ";
        $valueStr = $valueStr.$row["VALUE"].",";
        $x++;
}
$valueStr = $valueStr."22";
$updateStr = $updateStr."WHEN 22 THEN ".$x." ";
$updateStr = $updateStr."END WHERE VALUE IN (".$valueStr.")";

$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
