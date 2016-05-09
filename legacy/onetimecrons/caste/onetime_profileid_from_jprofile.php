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

$caste_val = "17,18,20,25,30,48,49,63,64,66,70,71,74,75,76,78,79,82,89,94,98,101,108,111,115,116,117,118,121,122,123,124,125,127,129,134,135,136,143,146,215,231,242,319,328,342,402";

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE IN (".$caste_val.")";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 130 AND MTONGUE = 31";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 19 AND MTONGUE = 25";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 16 AND MTONGUE IN (3,16,17,31)";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 61 AND MTONGUE = 31";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 99 AND MTONGUE = 31";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

$select_statement = "SELECT PROFILEID FROM newjs.JPROFILE WHERE CASTE = 119 AND MTONGUE = 3";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	$insert_statement = "REPLACE INTO MIS.CASTE_UPDATE_PROFILEID(PROFILEID,CASTE_REVAMP_FLAG) VALUES (".$row["PROFILEID"].",2)";
	$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
}

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
