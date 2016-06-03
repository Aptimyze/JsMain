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

$file = fopen("merges.csv", "r") or exit("Unable to open file!");
$x=1;
$deleteStr = "DELETE FROM newjs.CASTE WHERE VALUE IN (";
$updateStr = "UPDATE newjs.CASTE SET LABEL = CASE VALUE ";
while(!feof($file))
{
        if($x!=1)
        {
                $data = explode("|",trim(fgets($file)));
                if ($data[0] && $data[1])
             	{
			$deleteStr = $deleteStr.trim($data[1]).",";
			$newLabelArr[] = trim($data[5]);
			$updateIdArr[] = trim($data[0]);
		}
        }
        else
        {
                fgets($file);
                $x++;
        }
}
fclose($file);

$deleteStr = rtrim($deleteStr,",");
$deleteStr = $deleteStr.")";

foreach ($updateIdArr as $k=>$v)
{
	$updateStr = $updateStr."WHEN ".$v." THEN \"Hindu: ".$newLabelArr[$k]."\" ";
}
$updateStr = $updateStr."END, SMALL_LABEL = CASE VALUE ";
foreach ($updateIdArr as $k=>$v)
{
        $updateStr = $updateStr."WHEN ".$v." THEN \"-".$newLabelArr[$k]."\" ";
}
$updateStr = $updateStr."END WHERE VALUE IN (".implode(",",$updateIdArr).")";

$mysqlObjM->executeQuery($updateStr,$dbM) or die($updateStr);
$mysqlObjM->executeQuery($deleteStr,$dbM) or die($deleteStr);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>

