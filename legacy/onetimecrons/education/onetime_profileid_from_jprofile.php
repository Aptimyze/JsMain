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

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$eduArr = array(17,3,13,19,27);

$eduStr = implode(",",$eduArr);

$select_statement = "SELECT PROFILEID,EDU_LEVEL_NEW FROM newjs.JPROFILE WHERE EDU_LEVEL_NEW IN (".$eduStr.")";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
$revamp_value = 6;
while($row = $mysqlObjS->fetchArray($result))
{
	$update_statement = "UPDATE MIS.REVAMP_LAYER_CHECK SET OLD_EDUCATION = ".$row["EDU_LEVEL_NEW"].", REVAMP_VALUE = CONCAT(REVAMP_VALUE,\",".$revamp_value."\"), CASTE_REVAMP_FLAG = 2 WHERE PROFILEID = ".$row["PROFILEID"];
	$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);
	if($mysqlObjM->affectedRows()==0)
	{
		$insert_statement = "REPLACE INTO MIS.REVAMP_LAYER_CHECK(PROFILEID,CASTE_REVAMP_FLAG,OLD_EDUCATION,REVAMP_VALUE) VALUES (".$row["PROFILEID"].",2,".$row["EDU_LEVEL_NEW"].",".$revamp_value.")";
		$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
	}
	
}

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
