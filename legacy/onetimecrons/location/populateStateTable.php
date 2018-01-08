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

$statement = "TRUNCATE TABLE newjs.STATE_NEW";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "INSERT INTO newjs.STATE_NEW(ID,LABEL,VALUE) SELECT '',LABEL,VALUE FROM newjs.CITY_INDIA WHERE TYPE = 'STATE'";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "INSERT INTO newjs.STATE_NEW(LABEL,VALUE) VALUES ('Delhi','DE'),('Goa','GO'),('Pondichery','PO'),('Punjab/Haryana','PH'),('Andaman & Nicobar Islands','AN')";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "UPDATE newjs.STATE_NEW SET LABEL = 'Odisha' WHERE VALUE = 'OR'";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "UPDATE newjs.STATE_NEW SET LABEL = 'Uttarakhand' WHERE VALUE = 'UT'";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "UPDATE newjs.STATE_NEW SET VALUE = 'UK' WHERE VALUE = 'UT'";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement = "SELECT VALUE FROM newjs.STATE_NEW ORDER BY LABEL";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$updateStr = "UPDATE newjs.STATE_NEW SET SORTBY = CASE VALUE ";
$valueStr = "";
$x = 1;
while($row = $mysqlObjM->fetchArray($result))
{
        $updateStr = $updateStr."WHEN '".$row["VALUE"]."' THEN ".$x." ";
        $valueStr = $valueStr."'".$row["VALUE"]."',";
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
