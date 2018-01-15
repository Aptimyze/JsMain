<?php
//INCLUDE FILES HERE
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "SELECT n.VALUE,n.LABEL FROM newjs.CITY_NEW n LEFT JOIN incentive.LOCATION_CITY l ON n.VALUE=l.VALUE WHERE n.COUNTRY_VALUE='51' AND n.TYPE='CITY' AND l.VALUE IS NULL ORDER BY n.SORTBY";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
while($row = $mysqlObjM->fetchArray($result))
{

	$cityVal 	=$row["VALUE"];
	$stateVal	=substr($cityVal,0,2);
	$label 		=$row["LABEL"];	

	$sql ="INSERT IGNORE INTO incentive.LOCATION_CITY (`NAME`,`VALUE`,`STATE`) VALUES('$label','$cityVal','$stateVal')";
	$mysqlObjM->executeQuery($sql,$dbM) or die($sql);
}


//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

