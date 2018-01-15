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

$file = fopen("Legacy_Layer.csv", "r") or exit("Unable to open file!");
$x=1;
while(!feof($file))
{
        if($x!=1)
        {
		if($aaa = trim(fgets($file)))
                	$data[] = $aaa;
        }
        else
        {
                fgets($file);
                $x++;
        }
}
fclose($file);

$caste_val = array(16,17,18,20,25,30,48,49,50,61,63,64,66,70,71,74,75,76,78,79,82,89,94,98,99,101,108,115,116,117,118,119,121,122,123,124,125,127,129,130,134,135,136,143,146,189,200,215,231,242,319,328,342,353);

$params = "INSERT INTO newjs.MAPPING_CASTE_CASTE_MTONGUE(OLD_CASTE,NEW_CASTE,NEW_CASTE_MTONGUE,NEW_CASTE_SORTBY) VALUES ";

foreach ($caste_val as $k=>$v)
{
	foreach ($data as $kk=>$vv)
	{
		$dataArr = explode("|",$vv);
		$dataArr1 = explode(",",$dataArr[1]);
		foreach ($dataArr1 as $kkk=>$vvv)
		{
			if (trim($vvv)==$v)
			{
				$statement = "SELECT SORTBY FROM newjs.CASTE_REVAMP WHERE VALUE = ".$dataArr[0];
				$result = $mysqlObjM->executeQuery($statement,$dbM) or die($statement);
				$row = $mysqlObjM->fetchArray($result);
				$params = $params."(".$v.",".$dataArr[0].",\"".$dataArr[2]."\",".$row["SORTBY"]."),";
				break;
			}
		} 
		unset($dataArr);
		unset($dataArr1);
	}
}

$params = rtrim($params,",");
$mysqlObjM->executeQuery($params,$dbM) or die($params);

echo "<br />DONE";

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
//CLOSING ENDS
?>
