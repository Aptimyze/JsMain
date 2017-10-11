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

$file = fopen("delete.csv", "r") or exit("Unable to open file!");
$deleteStr = "DELETE FROM newjs.CASTE WHERE VALUE IN (";

while(!feof($file))
{
     	$data = trim(fgets($file));
     	if ($data)
     	{
            	$deleteStr = $deleteStr.trim($data).",";
      	}
}
fclose($file);
$deleteStr = rtrim($deleteStr,",");
$deleteStr = $deleteStr.")";
$mysqlObjM->executeQuery($deleteStr,$dbM) or die($deleteStr);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
