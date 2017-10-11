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

$statement = "SELECT p1.PROFILEID AS PROFILEID1, p2.PROFILEID AS PROFILEID2, IF(p1.MAINPHOTO!='','Y','N') AS MAINPHOTO, IF(p1.ALBUMPHOTO1!='','Y','N') AS ALBUMPHOTO1, IF(p1.ALBUMPHOTO2!='','Y','N') AS ALBUMPHOTO2, IF(p1.PROFILEPHOTO!='','Y','N') AS PROFILEPHOTO, IF(p1.THUMBNAIL!='','Y','N') AS THUMBNAIL FROM newjs.PICTURE_FOR_SCREEN p1 LEFT JOIN newjs.PICTURE p2 ON p1.PROFILEID = p2.PROFILEID AND p1.UPLOADED NOT IN ('Y','D')";

$result = $mysqlObjS->executeQuery($statement,$dbS) or die(mysql_error($statement));

while($row = $mysqlObjS->fetchArray($result))
{
	$profileId = $row["PROFILEID1"];
	$photoscreen = calculatePhotoScreen($row["MAINPHOTO"],$row["ALBUMPHOTO1"],$row["ALBUMPHOTO2"],$row["PROFILEPHOTO"],$row["THUMBNAIL"]);
	if (!$row["PROFILEID2"])
	{
		$sql1 = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"U\", PHOTOSCREEN=".$photoscreen." WHERE PROFILEID = ".$profileId;			
	}
	else
	{
		$sql1 = "UPDATE newjs.JPROFILE SET HAVEPHOTO=\"Y\", PHOTOSCREEN=".$photoscreen." WHERE PROFILEID = ".$profileId;
	}
	$output = $mysqlObjM->executeQuery($sql1,$dbM);
	if (!$output)
	{
		$sql = "INSERT INTO MIS.PHOTO_CRON_ERROR (PROFILEID,TYPE) VALUES (\"".$profileId."\",\"PIC_SCREEN_UPDATE\")";
                $mysqlObjM->executeQuery($sql,$dbM) or die(mysql_error($dbM).$sql);//logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql1,"ShowErrTemplate");
	}
	echo $profileId.",";
}

echo "********UPDATE ON PICTURE_FOR_SCREEN TABLE ENDS*********";

function calculatePhotoScreen($mainphoto,$albumphoto1,$albumphoto2,$profilephoto,$thumbnail)
{
	$outputVal = 0;
	if ($mainphoto == "N")
		$outputVal = $outputVal+1;
	if ($albumphoto1 == "N")
		$outputVal = $outputVal+2;
	if ($albumphoto2 == "N")
		$outputVal = $outputVal+4;
	if ($thumbnail == "N")
		$outputVal = $outputVal+8;
	if ($profilephoto == "N")
		$outputVal = $outputVal+16;
	return $outputVal;
}
?>

