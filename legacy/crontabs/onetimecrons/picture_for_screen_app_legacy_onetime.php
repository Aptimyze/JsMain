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

$live_dt = "2014-01-30 00:00:00";
$tableArr = array("SEARCH_MALE","SEARCH_FEMALE");

foreach($tableArr as $k=>$v)
{
	$sql = "SELECT S.PROFILEID AS PROFILEID,S.LAST_LOGIN_DT AS LAST_LOGIN_DT FROM newjs.".$v." S INNER JOIN newjs.PICTURE_NEW P ON S.PROFILEID = P.PROFILEID WHERE P.ORDERING = 0 AND S.ENTRY_DT <=  '".$live_dt."' AND S.LAST_LOGIN_DT >= DATE_SUB( NOW( ) , INTERVAL 2 MONTH ) AND (P.MobileAppPicUrl IS NULL OR P.MobileAppPicUrl =  '')";
	$result = $mysqlObj->executeQuery($sql,$dbS,'',1) or $mysqlObj->logError($sql,1);
	$i=1;
	$insertStatement = "";
	while($row = $mysqlObj->fetchArray($result))
	{
		$insertStatement = $insertStatement."(".$row["PROFILEID"].",'".$row["LAST_LOGIN_DT"]."','N'),";
		if($i==1000)
		{
			$sql1 = "INSERT INTO newjs.PICTURE_FOR_SCREEN_APP_LEGACY(PROFILEID,LAST_LOGIN_DT,STATUS) VALUES ".rtrim($insertStatement,",");
			$mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
			unset($sql1);
			$insertStatement="";
			$i=0;
		}
		$i++;	
	}
}
mysql_close($dbM);
mysql_close($dbS);
?>
