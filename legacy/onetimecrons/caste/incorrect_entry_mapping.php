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

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$CASTE[473] = 36;
$CASTE[474] = 33;
$CASTE[475] = 46;
//$CASTE[476] = 
$CASTE[477] = 293;
/*
$CASTE[478] = 
$CASTE[479] = 
$CASTE[480] = 
$CASTE[481] = 
$CASTE[482] = 
$CASTE[483] = 
*/
$CASTE[484] = 78;
$CASTE[485] = 17;
$CASTE[486] = 95;
$CASTE[487] = 19;
$CASTE[488] = 54;
$CASTE[489] = 121;
$CASTE[490] = 403;
$CASTE[491] = 284;
$CASTE[492] = 123;
$CASTE[493] = 129;
$CASTE[494] = 94;
//$CASTE[495] = 

$select_statement = "SELECT PROFILEID,CASTE FROM newjs.JPROFILE WHERE CASTE IN (473,474,475,477,484,485,486,487,488,489,490,491,492,493,494)";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$update_statement = "UPDATE newjs.JPROFILE SET CASTE = ".$CASTE[$row["CASTE"]]." WHERE CASTE = ".$row["CASTE"]." AND PROFILEID = ".$row["PROFILEID"];
	$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);
}

$update_statement = "UPDATE newjs.JPROFILE SET CASTE = CASE PROFILEID WHEN 7505667 THEN 125 WHEN 6302280 THEN 25 WHEN 6881139 THEN 25 WHEN 6915388 THEN 25 WHEN 7207179 THEN 252 WHEN 7787462 THEN 36 END WHERE PROFILEID IN (7505667,6302280,6881139,6915388,7207179,7787462)";
$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
