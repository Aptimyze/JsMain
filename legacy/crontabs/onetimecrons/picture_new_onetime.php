<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$last_id = "39428";
$limit = 100;
$module_name = "PICTURE";
$status = "N";

for($i=0;$i<$last_id;$i+=$limit)
{
	$sql = "SELECT PICTUREID,MainPicUrl,ProfilePicUrl,ThumbailUrl,Thumbail96Url,SearchPicUrl FROM test.PICTURE_NEW_DUMP WHERE PICTUREID<=".$last_id." AND STATUS = 'N' LIMIT ".$limit;
	$result = $mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
	if($mysqlObj->numRows($result))
	{
		while($row = $mysqlObj->fetchArray($result))
		{
			if($row["MainPicUrl"])
				$insertArr[] = "(\"\",\"".$module_name."\",\"".$row["PICTUREID"]."\",\"P_M\",\"".$status."\",\"".date("Y-m-d")."\")";
			if($row["ProfilePicUrl"])
				$insertArr[] = "(\"\",\"".$module_name."\",\"".$row["PICTUREID"]."\",\"P_P\",\"".$status."\",\"".date("Y-m-d")."\")";
			if($row["ThumbailUrl"])
				$insertArr[] = "(\"\",\"".$module_name."\",\"".$row["PICTUREID"]."\",\"P_T\",\"".$status."\",\"".date("Y-m-d")."\")";
			if($row["Thumbail96Url"])
				$insertArr[] = "(\"\",\"".$module_name."\",\"".$row["PICTUREID"]."\",\"P_T96\",\"".$status."\",\"".date("Y-m-d")."\")";
			if($row["SearchPicUrl"])
				$insertArr[] = "(\"\",\"".$module_name."\",\"".$row["PICTUREID"]."\",\"P_S\",\"".$status."\",\"".date("Y-m-d")."\")";
			$sql1 = "INSERT INTO IMAGE_SERVER.LOG(AUTOID,MODULE_NAME,MODULE_ID,IMAGE_TYPE,STATUS,DATE) VALUES ".implode(",",$insertArr);
			$mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
			unset($insertArr);
			$sql2 = "UPDATE test.PICTURE_NEW_DUMP SET STATUS = 'Y' WHERE PICTUREID = ".$row["PICTUREID"];
			$mysqlObj->executeQuery($sql2,$dbM,'',1) or $mysqlObj->logError($sql1,1);
		}
	}
	else
	{
		break;
	}
}

mysql_close($dbM);
?>
