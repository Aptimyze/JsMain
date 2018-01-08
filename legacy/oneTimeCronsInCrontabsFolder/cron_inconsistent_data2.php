<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*
@author: Kumar Anand
*/

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

//INCLUDE FILES HERE
include_once("../htdocs/profile/config.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Mysql.class.php");
//INCLUDE FILE ENDS


//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjS = new Mysql;
//$dbS = $mysqlObjS->connect("slave") or die(mysql_error());//logError("Unable to connect to slave","ShowErrTemplate");
$dbS= mysql_connect("10.208.67.196","user","CLDLRTa9") or die(mysql_error());
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

//$statement = "SELECT p1.PROFILEID AS PROFILEID FROM (newjs.PICTURE p1 LEFT JOIN newjs.TEMP_PROFILEID p2 ON p1.PROFILEID = p2.PROFILEID WHERE p2.PROFILEID IS NULL) LEFT JOIN newjs.JPROFILE j ON p1.PROFILEID = j.PROFILEID AND (j.HAVEPHOTO!=\"Y\" OR j.PHOTOSCREEN!=31)";
$statement = "SELECT p1.PROFILEID AS PROFILEID FROM newjs.PICTURE p1 LEFT JOIN newjs.TEMP_PROFILEID p2 ON p1.PROFILEID = p2.PROFILEID LEFT JOIN newjs.JPROFILE j ON p1.PROFILEID = j.PROFILEID WHERE  p2.PROFILEID IS NULL AND(j.HAVEPHOTO!='Y' OR j.PHOTOSCREEN!=31)";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));

while($row = $mysqlObjS->fetchArray($result))
{
	$profileId = $row["PROFILEID"];
	$statement1 = "SELECT count(*) AS COUNT FROM newjs.PICTURE_FOR_SCREEN WHERE PROFILEID = ".$profileId;
	$result1 = $mysqlObjS->executeQuery($statement1,$dbS) or die(mysql_error($statement1));
	$row1 = $mysqlObjS->fetchArray($result1);

	if ($row1["COUNT"])
	{
		$output = true;
		$output3 = true;
	}
	else
	{
		$sql1 = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"Y\", PHOTOSCREEN=31 WHERE PROFILEID = ".$profileId;
		$output = $mysqlObjM->executeQuery($sql1,$dbM);
		$version = mktime();
		$sql3 = "UPDATE newjs.PICTURE SET VERSION = \"".$version."\" WHERE PROFILEID = ".$profileId;
		$output3 = $mysqlObjM->executeQuery($sql3,$dbM);
	}
	$sql2 = "REPLACE INTO newjs.TEMP_PROFILEID(PROFILEID) VALUES ($profileId)";
	$output2 = $mysqlObjM->executeQuery($sql2,$dbM);
	if (!$output || !$output2 || !$output3)
	{
		$sql = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$profileId."\",\"PICTURE_UPDATE\")";
                $mysqlObjM->executeQuery($sql,$dbM) or die(mysql_error($dbM).$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
	}
	echo $profileId.",";
}

echo "********UPDATE ON PICTURE TABLE ENDS*********";
?>

