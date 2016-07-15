<?php
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql = "SELECT MODULE_ID,IMAGE_TYPE FROM IMAGE_SERVER.LOG WHERE MODULE_NAME = 'PICTURE' AND STATUS = 'I'";
$result = $mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
while($row = $mysqlObj->fetchArray($result))
{
	if($row["IMAGE_TYPE"]=='P_M')
		$pic = "MainPicUrl";
	elseif($row["IMAGE_TYPE"]=='P_P')
		$pic = "ProfilePicUrl";
	elseif($row["IMAGE_TYPE"]=='P_S')
		$pic = "SearchPicUrl";
	elseif($row["IMAGE_TYPE"]=='P_T')
		$pic = "ThumbailUrl";
	elseif($row["IMAGE_TYPE"]=='P_T96')
		$pic = "Thumbail96Url";

	$sql1  = "SELECT ".$pic." FROM newjs.PICTURE_NEW WHERE PICTUREID = ".$row["MODULE_ID"];
	$result1 = $mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
	$row1 = $mysqlObj->fetchArray($result1);
	
	if(strstr($row1[$pic],"JS"))
	{
		$savePath = "/var/www/html/web/".strstr($row1[$pic],"uploads");
		$url = str_replace("JS","www.jeevansathi.com",$row1[$pic]);
		$filename = ltrim(strrchr($url,"/"),"/");
		exec("wget ".$url);
		rename($filename,$savePath);
	}
	unset($pic);
}

mysql_close($dbM);
?>
