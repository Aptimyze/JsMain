<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/commonFiles/SymfonyPictureFunctions.class.php");
//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$dbS = $mysqlObj->connect("slave") or die("Unable to connect to slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);
$pictureId = "";
$sqlMain = "SELECT max(PICTUREID) as PID from test.APP_PIC_SIZE";
$resultMain = $mysqlObj->executeQuery($sqlMain,$dbM,'',1) or $mysqlObj->logError($sqlMain,1);
$rowMain = $mysqlObj->fetchArray($resultMain);
if(!is_null($rowMain["PID"]))
	$pictureId = $rowMain["PID"];
$sql = "SELECT PICTUREID,MobileAppPicUrl FROM newjs.PICTURE_NEW WHERE COALESCE(MobileAppPicUrl, '') != ''";
if($pictureId!="")
	$sql.=" AND PICTUREID > $pictureId";
$sql.=" ORDER BY PICTUREID ASC ";
$result = $mysqlObj->executeQuery($sql,$dbS,'',1) or $mysqlObj->logError($sql,1);
while($row = $mysqlObj->fetchArray($result))
{
	$pid = $row["PICTUREID"];
	$imgUrl = $row["MobileAppPicUrl"];
	$mainImageInfo = getimagesize(PictureFunctions::getCloudOrApplicationCompleteUrl($imgUrl));
	if(is_array($mainImageInfo))
	{
		$width =$mainImageInfo[0];
		$height = $mainImageInfo[1];
		$sql1 = "INSERT INTO test.APP_PIC_SIZE(PICTUREID,MobileAppPicUrl,Width,Height) VALUES ($pid,'$imgUrl',$width,$height)";
		$mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
		unset($sql1);
	}
}
mysql_close($dbM);
mysql_close($dbS);
?>
