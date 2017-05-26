<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");

$mysqlObj = new Mysql;
$dbM = $mysqlObj->connect("master") or die("Unable to connect to master");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$table = array('SEARCH_MALE','SEARCH_FEMALE');

foreach($table as $k=>$v)
{
	$sql = "SELECT F.PROFILEID AS PROFILEID,F.SCORE AS SCORE FROM newjs.FEATURED_PROFILE_LIST F INNER JOIN newjs.".$v." S ON F.PROFILEID = S.PROFILEID WHERE F.IS_MODIFIED = 1 AND S.FEATURE_PROFILE = 1";
	$result = $mysqlObj->executeQuery($sql,$dbM) or $mysqlObj->logError($sql);

	if($mysqlObj->numRows($result))
	{
		$update_statement = "UPDATE newjs.".$v." SET FEATURE_PROFILE_SCORE = CASE PROFILEID ";
		while($row = $mysqlObj->fetchArray($result))
		{
			$profileidArr[] = $row["PROFILEID"];
			$update_statement = $update_statement."WHEN ".$row["PROFILEID"]." THEN ".$row["SCORE"]." ";
		}
		$update_statement = $update_statement."END WHERE PROFILEID IN (".implode(",",$profileidArr).")";
		$mysqlObj->executeQuery($update_statement,$dbM) or $mysqlObj->logError($update_statement);
		
		$update_statement = "UPDATE newjs.FEATURED_PROFILE_LIST SET IS_MODIFIED = 0 WHERE PROFILEID IN (".implode(",",$profileidArr).")";
		$mysqlObj->executeQuery($update_statement,$dbM) or $mysqlObj->logError($update_statement);

		unset($update_statement);
		unset($profileidArr);
	}
}

mysql_close($dbM);
?>
