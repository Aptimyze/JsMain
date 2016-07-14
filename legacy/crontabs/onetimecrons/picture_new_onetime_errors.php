<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);
$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$sql = "SELECT DISTINCT(PROFILEID) AS PROFILEID FROM test.PICTURE_NEW_DUMP WHERE UPDATED_TIMESTAMP>='2013-07-16 00:00:00' AND UPDATED_TIMESTAMP<='2013-07-17 23:59:59'";
$result = $mysqlObj->executeQuery($sql,$dbS,'',1) or $mysqlObj->logError($sql,1);
while($row = $mysqlObj->fetchArray($result))
{
	$sql1 = "SELECT HAVEPHOTO,PHOTOSCREEN FROM newjs.JPROFILE WHERE PROFILEID = ".$row["PROFILEID"];
	$result1 = $mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
	$row1 = $mysqlObj->fetchArray($result1);

	$sql2 = "SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = ".$row["PROFILEID"];
	$result2 = $mysqlObj->executeQuery($sql2,$dbM,'',1) or $mysqlObj->logError($sql2,1);

	if($mysqlObj->numRows($result2))
	{
		$sql4 = "SELECT * FROM newjs.PICTURE_FOR_SCREEN_NEW WHERE PROFILEID = ".$row["PROFILEID"]." AND ORDERING = 0";
		$result4 = $mysqlObj->executeQuery($sql4,$dbM,'',1) or $mysqlObj->logError($sql4,1);
		if($mysqlObj->numRows($result4))
		{
			if($row1["HAVEPHOTO"]!='U' || $row1["PHOTOSCREEN"]!=0)
			{
				$sql3 = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = '0',HAVEPHOTO = 'U' WHERE PROFILEID = ".$row["PROFILEID"];
				$mysqlObj->executeQuery($sql3,$dbM,'',1) or $mysqlObj->logError($sql3,1);
			}
		}
		else
		{
			if($row1["HAVEPHOTO"]!='Y' || $row1["PHOTOSCREEN"]!=0)
			{
				$sql3 = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = '0',HAVEPHOTO = 'Y' WHERE PROFILEID = ".$row["PROFILEID"];
				$mysqlObj->executeQuery($sql3,$dbM,'',1) or $mysqlObj->logError($sql3,1);
			}
		}
	}
	else
	{
		$sql4 = "SELECT PICTUREID,ORDERING,ProfilePicUrl FROM newjs.PICTURE_NEW WHERE PROFILEID = ".$row["PROFILEID"]." ORDER BY ORDERING ASC";
		$result4 = $mysqlObj->executeQuery($sql4,$dbM,'',1) or $mysqlObj->logError($sql4,1);
		while($row4 = $mysqlObj->fetchArray($result4))
		{
			$output[] = $row4;
		}

		$flag = 0;
		foreach($output as $k=>$v)
		{
			if($v["ORDERING"]==0 && !$v["ProfilePicUrl"])
				$currentProfPic = $k;
			if($v["ProfilePicUrl"] && $v["ORDERING"]!=0)
				$newProfPic = $k;
		}

		if($newProfPic || $newProfPic===0)
		{
			updateOrdering1($output,$currentProfPic,$newProfPic);
		}

			if($row1["HAVEPHOTO"]!='Y' || $row1["PHOTOSCREEN"]!=1)
			{
				$sql3 = "UPDATE newjs.JPROFILE SET PHOTOSCREEN = '1',HAVEPHOTO = 'Y' WHERE PROFILEID = ".$row["PROFILEID"];
				$mysqlObj->executeQuery($sql3,$dbM,'',1) or $mysqlObj->logError($sql3,1);
			}
	}
}

echo "DONE";

function updateOrdering1($output,$currentProfPic='',$newProfPic)
{
	global $mysqlObj,$dbM;
	if($currentProfPic || $currentProfPic===0)
	{
		$sql = "START TRANSACTION";
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
		$free_ordering = $output[$newProfPic]["ORDERING"];
		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING = '-1' WHERE PICTUREID = ".$output[$currentProfPic]["PICTUREID"];
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING = '0' WHERE PICTUREID = ".$output[$newProfPic]["PICTUREID"];
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING = $free_ordering WHERE PICTUREID = ".$output[$currentProfPic]["PICTUREID"];
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
		$sql = "COMMIT";
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
	}
	else
	{
		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING = '0' WHERE PICTUREID = ".$output[$newProfPic]["PICTUREID"];
                $mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
	}
}

function updateOrdering($result,$profileid)
{
	global $mysqlObj,$dbM;
	foreach($result as $k=>$row)
	{
		if($row["ProfilePicUrl"])
		{
			$profilePicId = $row["PICTUREID"];
			break;
		}
	}

	if($profilePicId)
	{
		$sql = "UPDATE newjs.PICTURE_NEW SET ORDERING = CASE PICTUREID";
		$i=1;
		$x = "";
		foreach($result as $k=>$row)
		{
			if($row["PICTUREID"] == $profilePicId)
			{
				$sql = $sql." WHEN ".$row["PICTUREID"]." THEN 0";
			}
			else
			{
				$sql = $sql." WHEN ".$row["PICTUREID"]." THEN ".$i;
				$i++;
			}
			$x = $x.$row["PICTUREID"].",";
		}
		$x = rtrim($x,",");
		$sql.=" END WHERE PICTUREID IN ($x) ORDER BY FIELD( PICTUREID, $x)";
		$mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
	}
	unset($profilePicId);
}

mysql_close($dbM);
mysql_close($dbS);
?>
