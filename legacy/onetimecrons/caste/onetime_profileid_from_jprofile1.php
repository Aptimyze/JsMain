<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//INCLUDE FILES HERE
include_once("$_SERVER[DOCUMENT_ROOT]/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once("update_functions.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$caste_val = "130,200,210,51,305,325,216,228,348,113,359,363,233,203,253,368,353,400,215,140,401,402,146,147,287,336,169,189,186";

$select_statement = "SELECT PROFILEID,CASTE FROM newjs.JPROFILE WHERE CASTE IN (".$caste_val.")";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG,OLD_CASTE) VALUES (".$row["PROFILEID"].",1,".$row["CASTE"].")";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
