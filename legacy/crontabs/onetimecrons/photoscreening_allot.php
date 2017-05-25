<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

//MAKE CONNECTION TO MASTER
$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
$sql = "SET SESSION group_concat_max_len = 100000;";
mysql_query($sql,$dbM) or mysql_error1(mysql_error($db).$sql);

$sql = "SELECT PROFILEID, GROUP_CONCAT(CASE WHEN (ORDERING =0 AND LENGTH( SCREEN_BIT )  =1  ) OR (ORDERING !=0 AND LENGTH( SCREEN_BIT )  >2 ) THEN 5 WHEN ORDERING =0 AND SCREEN_BIT LIKE  '0%' THEN 0 WHEN SCREEN_BIT LIKE  '0%' THEN 0 WHEN ORDERING =0 AND SCREEN_BIT LIKE  '1%1%' THEN 1 WHEN ORDERING =0 AND SCREEN_BIT LIKE  '1%4%' THEN 4 WHEN ORDERING =0 AND SCREEN_BIT LIKE  '1%2%' THEN 2 WHEN ORDERING =0 AND LENGTH( SCREEN_BIT )  =1 THEN 2 WHEN ORDERING !=0 AND LENGTH( SCREEN_BIT )  >2 THEN SUBSTRING( SCREEN_BIT, 1, 1  ) ELSE SCREEN_BIT END ) AS BIT FROM newjs.`PICTURE_FOR_SCREEN_NEW` GROUP  BY PROFILEID HAVING BIT LIKE  '%5%' ";
$result = $mysqlObj->executeQuery($sql,$dbM,'',1) or $mysqlObj->logError($sql,1);
$str1 = array();
while($row = $mysqlObj->fetchArray($result))
{
	$str1[] = $row["PROFILEID"];
}
$str = implode(",",$str1);
unset($str);

$sql1 = "SELECT substring_index(GROUP_CONCAT(DISTINCT P.PROFILEID ORDER BY UPDATED_TIMESTAMP ASC SEPARATOR ','), ',', 400) AS PROFILEID FROM newjs.PICTURE_FOR_SCREEN_NEW AS P LEFT JOIN jsadmin.MAIN_ADMIN AS M ON M.PROFILEID = P.PROFILEID WHERE M.PROFILEID IS NULL";
$result1 = $mysqlObj->executeQuery($sql1,$dbM,'',1) or $mysqlObj->logError($sql1,1);
while($row = $mysqlObj->fetchArray($result1))
{
	$str= $str.",".$row["PROFILEID"];
}
$str = trim($str,",");
echo $str."\n\n";
if($str!="")
{
	$sql3 = "SELECT GROUP_CONCAT(USERNAME SEPARATOR ',') AS PROFILES FROM newjs.JPROFILE  WHERE PROFILEID IN (".$str .")";
	$result3 = $mysqlObj->executeQuery($sql3,$dbM,'',1) or $mysqlObj->logError($sql3,1);
	while($row = $mysqlObj->fetchArray($result3))
	{
		$profiles =$row["PROFILES"];
	}
	
	//cid for jstech user
	$cid = "0c6eb925e8bc1a1ba66830a17a853485i1376554";
	$arr = explode(",",$profiles);
echo count($arr);
	foreach($arr as $k=>$v)
	{
echo "(".$k."))";
		$a = JsConstants::$siteUrl."/operations.php/photoScreening/masterPhotoEditSubmit?sender=&cid=".$cid."&user=&mailid=&message=&subject=&source=master&username=".$v."&profileid=&profileData=&profileDataKeys=&Submit=Mark+For+Screen";
	        sleep(6);
	        file_get_contents($a);
	}
	
}
mysql_close($dbM);
?>
