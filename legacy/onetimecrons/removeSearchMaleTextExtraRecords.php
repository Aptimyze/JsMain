<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
require_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");

$dbS = connect_slave();
$dbM = connect_db();

$sql = "SELECT A.PROFILEID FROM SEARCH_FEMALE_TEXT A LEFT JOIN SEARCH_FEMALE B ON A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IS NULL";
$res = mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
while($row = mysql_fetch_assoc($res))
{
	$pid = $row["PROFILEID"];
	$sql1 = "DELETE FROM SEARCH_FEMALE_TEXT WHERE PROFILEID='$pid'";	
	mysql_query($sql1,$dbM) or die(mysql_error($dbM).$sql1);
}

$sql = "SELECT A.PROFILEID FROM SEARCH_MALE_TEXT A LEFT JOIN SEARCH_MALE B ON A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IS NULL";
$res = mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
while($row = mysql_fetch_assoc($res))
{
	$pid = $row["PROFILEID"];
	$sql1 = "DELETE FROM SEARCH_MALE_TEXT WHERE PROFILEID='$pid'";	
	mysql_query($sql1,$dbM) or die(mysql_error($dbM).$sql1);
}
