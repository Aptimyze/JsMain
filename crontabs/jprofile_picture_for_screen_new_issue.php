<?php
/*
This cron is created to resolve have photo and photoscreen discrepancies in newjs.JPROFILE,newjs.PICTURE_FOR_SCREEN_NEW,newjs.PICTURE_NEW
Author :Reshu
Created : 27 March 2014
*/

  
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

// newjs.JPROFILE and newjs.PICTURE_FOR_SCREEN_NEW 
$sql = "SELECT DISTINCT(J.PROFILEID) AS PROFILEID FROM newjs.JPROFILE AS J LEFT JOIN newjs.PICTURE_FOR_SCREEN_NEW as P ON J.PROFILEID=P.PROFILEID WHERE J.HAVEPHOTO='Y' AND J.PHOTOSCREEN=0 AND P.PROFILEID IS NULL;";
$result = $mysqlObj->executeQuery($sql,$dbS,'',1) or $mysqlObj->logError($sql,1);
while($row = $mysqlObj->fetchArray($result))
{
	$sql1 = "SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = ".$row["PROFILEID"];
        $result1 = $mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
        $row1 = $mysqlObj->fetchArray($result1);
	if(!$mysqlObj->numRows($result1))
		$profileIds[]=$row["PROFILEID"];
}
if(is_array($profileIds))
{
	$params["PHOTOSCREEN"]='1';
	$JProfileUpdateLibObj = JProfileUpdateLib::getInstance();
	$JProfileUpdateLibObj->updateForMutipleProfiles($params,$profileIds);
	unset($params);
}

// newjs.JPROFILE,newjs.PICTURE_FOR_SCREEN_NEW and newjs.PICTURE_NEW 

$todays_date =date("Y-m-d H:i:s");
$fiveDaysbefore =date("Y-m-d H:i:s",strtotime("$todays_date -5 days"));

$sql3 = "SELECT DISTINCT(J.PROFILEID) AS PROFILEID FROM newjs.JPROFILE AS J INNER JOIN newjs.PICTURE_NEW as P ON J.PROFILEID=P.PROFILEID WHERE J.HAVEPHOTO='U' AND J.PHOTOSCREEN=0 AND PHOTODATE <='". $fiveDaysbefore."';";
$result2 = $mysqlObj->executeQuery($sql3,$dbS,'',1) or $mysqlObj->logError($sql3,1);
while($row = $mysqlObj->fetchArray($result2))
{
        $sql4 = "SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = ".$row["PROFILEID"];
        $result3 = $mysqlObj->executeQuery($sql4,$dbM,'',1) or $mysqlObj->logError($sql4,1);
        $row2 = $mysqlObj->fetchArray($result3);
        if(!$mysqlObj->numRows($result3))
        {
		$sql6 = "SELECT * FROM newjs.PICTURE_NEW WHERE PROFILEID = ".$row["PROFILEID"];
        	$result4 = $mysqlObj->executeQuery($sql6,$dbM,'',1) or $mysqlObj->logError($sql6,1);
        	$row3 = $mysqlObj->fetchArray($result4);
		if($mysqlObj->numRows($result4))
	                $profileArray[]=$row3["PROFILEID"];
        }
}
if(is_array($profileArray))
{
	$params["PHOTOSCREEN"]='1';
	$params["HAVEPHOTO"]='Y';
	$JProfileUpdateLibObj = JProfileUpdateLib::getInstance();
	$JProfileUpdateLibObj->updateForMutipleProfiles($params,$profileArray);
}
$sql10="update PICTURE_FOR_SCREEN_NEW set SCREEN_BIT='1' where ORDERING >0 and OriginalPicURl!= '' and SCREEN_BIT = '0000000'";
$res3 = $mysqlObj->executeQuery($sql10,$dbM,'',1) or $mysqlObj->logError($sql10,1);

mysql_close($dbM);
mysql_close($dbS);
?>
       
