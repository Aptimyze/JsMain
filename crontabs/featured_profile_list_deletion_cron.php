<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql = "SELECT F.PROFILEID AS PROFILEID FROM ((newjs.FEATURED_PROFILE_LIST F LEFT JOIN newjs.SEARCH_MALE SM ON F.PROFILEID = SM.PROFILEID) LEFT JOIN newjs.SEARCH_FEMALE SF ON F.PROFILEID = SF.PROFILEID) WHERE (SM.PROFILEID IS NULL AND SF.PROFILEID IS NULL) OR (SM.FEATURE_PROFILE = 0 AND SF.FEATURE_PROFILE IS NULL) OR (SM.FEATURE_PROFILE IS NULL AND SF.FEATURE_PROFILE=0)";
$result = $mysqlObj->executeQuery($sql,$dbM) or $mysqlObj->logError($sql);

if($mysqlObj->numRows($result))
{
	while($row = $mysqlObj->fetchArray($result))
	{
		$profileidArr[] = $row["PROFILEID"];	
	}

	$profileidStr = implode(",",$profileidArr);

	$sql = "DELETE FROM newjs.FEATURED_PROFILE_LIST WHERE PROFILEID IN (".$profileidStr.")";
	$mysqlObj->executeQuery($sql,$dbM) or $mysqlObj->logError($sql);
}
mysql_close($dbM);
?>
