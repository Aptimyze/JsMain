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
$dbS = $mysqlObjS->connect("slave") or die(mysql_error());//logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or die(mysql_error());//logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
//CONNECTION MAKING ENDS

$statement = "SELECT p1.PROFILEID as PROFILEID, p2.PROFILEID as ID FROM newjs.PICTURE p1 LEFT JOIN newjs.PICTURE_FOR_SCREEN p2 ON p1.PROFILEID = p2.PROFILEID WHERE p1.MAINPHOTO!=\"Y\" AND p1.ALBUMPHOTO1!=\"Y\" AND p1.ALBUMPHOTO2!=\"Y\"";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));

while($row = $mysqlObjS->fetchArray($result))
{
	$profileId = $row["PROFILEID"];
	$sql1 = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"N\", PHOTOSCREEN=31 WHERE PROFILEID = ".$profileId;
	$sql2 = "DELETE FROM newjs.PICTURE WHERE PROFILEID = ".$profileId." AND MAINPHOTO!=\"Y\" AND ALBUMPHOTO1!=\"Y\" AND ALBUMPHOTO2!=\"Y\"";
	if ($row["ID"])
		$output = true;
	else
		$output = $mysqlObjM->executeQuery($sql1,$dbM);
	$output1 = $mysqlObjM->executeQuery($sql2,$dbM);
	if (!$output || !$output1)
	{
		$sql = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$profileId."\",\"PICTURE_DEL\")";
                $mysqlObjM->executeQuery($sql,$dbM) or die(mysql_error($dbM).$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
	}
	echo $profileId.",";
}

echo "********DELETE FROM PICTURE TABLE ENDS*********";

$statement = "SELECT p1.PROFILEID as PROFILEID, p2.PROFILEID as ID FROM newjs.PICTURE_FOR_SCREEN p1 LEFT JOIN newjs.PICTURE p2 ON p1.PROFILEID = p2.PROFILEID WHERE p1.MAINPHOTO=\"\" AND p1.ALBUMPHOTO1=\"\" AND p1.ALBUMPHOTO2=\"\"";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));

while($row = $mysqlObjS->fetchArray($result))
{
	$profileId = $row["PROFILEID"];
        $sql1 = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"N\", PHOTOSCREEN=31 WHERE PROFILEID = ".$profileId;
        $sql2 = "DELETE FROM newjs.PICTURE_FOR_SCREEN WHERE PROFILEID = ".$profileId." AND MAINPHOTO=\"\" AND ALBUMPHOTO1=\"\" AND ALBUMPHOTO2=\"\"";
	if ($row["ID"])
		$output = true;
	else
        	$output = $mysqlObjM->executeQuery($sql1,$dbM);
        $output1 = $mysqlObjM->executeQuery($sql2,$dbM);
        if (!$output || !$output1)
        {
                $sql = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$profileId."\",\"PICTURE_SCREEN_DEL\")";
                $mysqlObjM->executeQuery($sql,$dbM) or die(mysql_error($dbM).$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
        }
	echo $profileId.",";
}

echo "********DELETE FROM PICTURE_FOR_SCREEN TABLE ENDS*********"

?>

