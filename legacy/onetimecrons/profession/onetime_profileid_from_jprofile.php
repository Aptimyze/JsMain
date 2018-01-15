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

$labelchangeArr = array(1,4,5,7,8,18,16,17,19,38,32,22,23,25,28);
$splitArr = array(20,2,11,6,13,15,9,39,29,24,27,30,31,40);
$othersArr = array(43);

$occupationStr = implode(",",$labelchangeArr).",".implode(",",$splitArr).",".implode(",",$othersArr);

$select_statement = "SELECT PROFILEID,OCCUPATION FROM newjs.JPROFILE WHERE OCCUPATION IN (".$occupationStr.")";
$result = $mysqlObjS->executeQuery($select_statement,$dbS) or die($select_statement);
while($row = $mysqlObjS->fetchArray($result))
{
	if(in_array($row["OCCUPATION"],$labelchangeArr))
	{
		$revamp_value = 3;
	}
	elseif(in_array($row["OCCUPATION"],$splitArr))
	{
		$revamp_value = 4;
	}
	elseif(in_array($row["OCCUPATION"],$othersArr))
	{
		$revamp_value = 5;
	}

	$update_statement = "UPDATE MIS.REVAMP_LAYER_CHECK SET OLD_PROFESSION = ".$row["OCCUPATION"].", REVAMP_VALUE = CONCAT(REVAMP_VALUE,\",".$revamp_value."\"), CASTE_REVAMP_FLAG = 2 WHERE PROFILEID = ".$row["PROFILEID"];
	$mysqlObjM->executeQuery($update_statement,$dbM) or die($update_statement);
	if($mysqlObjM->affectedRows()==0)
	{
		$insert_statement = "REPLACE INTO MIS.REVAMP_LAYER_CHECK(PROFILEID,CASTE_REVAMP_FLAG,OLD_PROFESSION,REVAMP_VALUE) VALUES (".$row["PROFILEID"].",2,".$row["OCCUPATION"].",".$revamp_value.")";
		$mysqlObjM->executeQuery($insert_statement,$dbM) or die($insert_statement);
	}
	
}

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS
?>
