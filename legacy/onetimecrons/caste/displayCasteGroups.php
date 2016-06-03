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

$statement = "SELECT GROUP_VALUE,CASTE_VALUE FROM newjs.CASTE_GROUP_MAPPING";
$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
$casteGroupArr = array();
while($row = $mysqlObjM->fetchArray($result))
{
	$casteGroupArr[$row["GROUP_VALUE"]] = $casteGroupArr[$row["GROUP_VALUE"]].$row["CASTE_VALUE"].",";
}

$file = fopen("caste_group_mapping.php", "w") or exit("Unable to open file!");
fwrite($file,"<?php\r\n");
foreach ($casteGroupArr as $k=>$v)
{
	fwrite($file,"\$CASTE_GROUP_ARRAY[".$k."] = \"".rtrim($v,",")."\";\r\n");	
}
fwrite($file,"?>");

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
