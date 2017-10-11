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
//include_once("../htdocs/profile/config.php");
include_once("config.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Mysql.class.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or die(mysql_error());//logError("Unable to connect to slave","ShowErrTemplate");
//$dbS= mysql_connect("10.208.67.196","user","CLDLRTa9") or die(mysql_error());
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

$statement = "SELECT j.PROFILEID as PROFILEID, j.HAVEPHOTO AS HAVEPHOTO, j.PHOTOSCREEN AS PHOTOSCREEN FROM newjs.JPROFILE j LEFT JOIN newjs.PICTURE p ON j.PROFILEID = p.PROFILEID LEFT JOIN newjs.PICTURE_FOR_SCREEN p1 ON j.PROFILEID = p1.PROFILEID WHERE p.PROFILEID IS NULL AND p1.PROFILEID IS NULL AND (j.PHOTOSCREEN!=31 OR j.HAVEPHOTO IN ('Y','U'))";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));

while($row = $mysqlObjS->fetchArray($result))
{
	if ($row["HAVEPHOTO"]=="N" && $row["PHOTOSCREEN"]!=31)
	{
		$sql = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = 31 WHERE PROFILEID = ".$row["PROFILEID"];
	}
	else if ($row["HAVEPHOTO"]=="Y" || $row["HAVEPHOTO"]=="U")
	{
		$sql = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"N\", PHOTOSCREEN=31 WHERE PROFILEID = ".$row["PROFILEID"];
	}
	else if ($row["HAVEPHOTO"]=="" && $row["PHOTOSCREEN"]!=31)
	{
		$sql = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = 31 WHERE PROFILEID = ".$row["PROFILEID"];
	}

	if ($sql)
	{
		$sql2 = "SELECT count(*) AS COUNT FROM PICTURE_FOR_SCREEN WHERE PROFILEID = ".$row["PROFILEID"];
		$result2 = $mysqlObjM->executeQuery($sql2,$dbM) or die(mysql_error($sql2));
		$row2 = $mysqlObjM->fetchArray($result2);
		if ($row2["COUNT"]==0)
		{
			$sql3 = "SELECT count(*) AS COUNT FROM PICTURE WHERE PROFILEID = ".$row["PROFILEID"];
                	$result3 = $mysqlObjM->executeQuery($sql3,$dbM) or die(mysql_error($sql3));
                	$row3 = $mysqlObjM->fetchArray($result3);
			if ($row3["COUNT"]==0)
			{
				$output = $mysqlObjM->executeQuery($sql,$dbM);
				if (!$output)
				{
					$sql1 = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$row["PROFILEID"]."\",\"UPDATE_JPROFILE\")";
                			$mysqlObjM->executeQuery($sql1,$dbM) or die(mysql_error($dbM).$sql1);
				}
				echo $row["PROFILEID"].",";
			}
		}
		unset($sql);
	}
}
echo "JPROFILE UPDATE ENDS **************************************";
?>

