<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db =connect_db();

$sql ="SELECT distinct PROFILEID FROM incentive.MANUAL_ALLOT WHERE ENTRY_DT='2015-06-08' AND CALL_SOURCE='WL'";
$res_2 = mysql_query($sql,$db) or die(mysql_error($sql,$db));
while($row =mysql_fetch_array($res_2))
{
	$profileid =$row['PROFILEID'];

	$sql1 ="DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
        mysql_query($sql1) or logError($sql1);

	$sql2 ="DELETE FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
        mysql_query($sql2) or logError($sql2);
}

$sql3 ="DELETE FROM incentive.MANUAL_ALLOT WHERE ENTRY_DT='2015-06-08' AND CALL_SOURCE='WL'";
mysql_query($sql3) or logError($sql3);
?>
