<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");

global $noOfActiveServers;
global $activeServers;

$mysqlObj=new Mysql;
$db=$mysqlObj->connect('master');
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
for($i=0;$i<$noOfActiveServers;$i++)
{
	$dbName=$noOfActiveServers[$i];
	$dbArr[$i]=$mysqlObj->connect($dbName);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbArr[$i]);
}
var_dump($dbArr);
$sql="SELECT a.PROFILEID,b.PARTNER_DIET FROM Assisted_Product.AP_PROFILE_INFO a JOIN Assisted_Product.AP_DPP_FILTER_ARCHIVE b ON a.PROFILEID=b.PROFILEID  WHERE a.STATUS='LIVE' AND b.STATUS='LIVE'";
$res=$mysqlObj->executeQuery($sql,$db);
while($row=$mysqlObj->fetchAssoc($res))
{
	$dbID=getProfileDatabaseId($row["PROFILEID"]);
	var_dump($dbArr[$dbID]);
	$sqlUpdate="UPDATE newjs.JPARTNER SET PARTNER_DIET=\"$row[PARTNER_DIET]\" WHERE PROFILEID='$row[PROFILEID]'";
	echo "\n".$sqlUpdate;
	$mysqlObj->executeQuery($sqlUpdate,$dbArr[$dbID]);
}
?>
