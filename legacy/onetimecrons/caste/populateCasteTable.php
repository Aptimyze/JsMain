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

$statement = "INSERT INTO newjs.CASTE(ID,PARENT,LABEL,SMALL_LABEL,VALUE,ISALL,ISGROUP,TOP_SORTBY,REG_DISPLAY) SELECT ID,PARENT,LABEL,SMALL_LABEL,VALUE,ISALL,ISGROUP,TOP_SORTBY,\"\" FROM newjs.CASTE_OLD_ANAND";
$mysqlObjM->executeQuery($statement,$dbM) or die($statement);

$statement1 = "UPDATE newjs.CASTE SET TOP_SORTBY = \"\" WHERE ISALL!=\"Y\"";
$mysqlObjM->executeQuery($statement1,$dbM) or die($statement1);

$statement4 = "UPDATE newjs.CASTE SET REG_DISPLAY = \"N\" WHERE PARENT IN (151,152)";
$mysqlObjM->executeQuery($statement4,$dbM) or die($statement4);

$statement5 = "UPDATE newjs.CASTE SET TOP_SORTBY = \"6\" WHERE ISALL = \"Y\" AND PARENT = 5";
$mysqlObjM->executeQuery($statement5,$dbM) or die($statement5);

$file = fopen("hindu_castes.csv", "r") or exit("Unable to open file!");
$x=1;
while(!feof($file))
{
	if($x!=1)
	{
		$data = explode("|",trim(fgets($file)));
		if ($data[0])
			$newCastes[] = $data[0];
	}	
	else
	{
		fgets($file);
		$x++;
	}	
}
fclose($file);

$statement2 = "SELECT max(VALUE) AS VALUE FROM newjs.CASTE_OLD_ANAND";
$result2 = $mysqlObjM->executeQuery($statement2,$dbM) or die($statement2);
$row2 = $mysqlObjM->fetchArray($result2);
$maxValue = $row2["VALUE"];
$insertValues = "";

foreach ($newCastes as $k=>$v)
{
	$maxValue = $maxValue+1;
	$insertValues = $insertValues."(\"\",1,\"Hindu: ".$v."\",\"-".$v."\",".$maxValue.",\"\",\"\"),";
}

$maxValue = $maxValue+1;
$insertValues = $insertValues."(\"\",1,\"Hindu: OBC\",\"-OBC\",".$maxValue.",\"\",\"\"),";

$statement3 = "UPDATE newjs.CASTE SET LABEL = \"Hindu: Nayee (Barber)\", SMALL_LABEL = \"-Barber\" WHERE ID = 107";
$mysqlObjM->executeQuery($statement3,$dbM) or die($statement3);

$file2 = fopen("sikh_castes.txt", "r") or exit("Unable to open file!");
while(!feof($file2))
{
	$xx = trim(fgets($file2));
        if($xx)
        {
           	$newCastes1[] = $xx;
        }
}
fclose($file2);

foreach ($newCastes1 as $k=>$v)
{
        $maxValue = $maxValue+1;
        $insertValues = $insertValues."(\"\",4,\"Sikh: ".$v."\",\"-".$v."\",".$maxValue.",\"\",\"\"),";
}

$file1 = fopen("caste_group.csv", "r") or exit("Unable to open file!");
while(!feof($file1))
{
	$xx = trim(fgets($file1));
        if($xx)
        {
           	$newCasteGroup[] = $xx;
        }
}
fclose($file1);

foreach ($newCasteGroup as $k=>$v)
{
        $maxValue = $maxValue+1;
        $insertValues = $insertValues."(\"\",1,\"Hindu: ".$v."\",\"-".$v."\",".$maxValue.",\"Y\",\"N\"),";
}

$insertValues = rtrim($insertValues,",");
$insertValues = "INSERT INTO newjs.CASTE(ID,PARENT,LABEL,SMALL_LABEL,VALUE,ISGROUP,REG_DISPLAY) VALUES ".$insertValues;
$mysqlObjM->executeQuery($insertValues,$dbM) or die($insertValues);

echo "DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
