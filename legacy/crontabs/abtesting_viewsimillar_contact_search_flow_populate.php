<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


$flag_using_php5=1;
include_once("connect.inc");
$mysqlObj=new Mysql;

$backtime=mktime(0,0,0,date("m"),date("d")-1,date("Y")); // To get the time for previous days
$backdate=date("Y-m-d",$backtime);

$dbSlave=$mysqlObj->connect('303');
$k=0;
$sql="SELECT PROFILEID,SEARCH_TYPE,CONTACTID,DATE from MIS.SIMILLAR_CONTACT_COUNT WHERE DATE='$backdate' AND SEARCH_TYPE IN ('CN2','CN')";
$result=$mysqlObj->executeQuery($sql,$dbSlave);
while($row=$mysqlObj->fetchArray($result))
{
	$pid=$row["PROFILEID"];
	$stype=$row["SEARCH_TYPE"];
	$cid=$row["CONTACTID"];
	$dt=$row["DATE"];
	$resArr[$pid][$k][0]=$stype;
	$resArr[$pid][$k][1]=$cid;
	$resArr[$pid][$k][2]=$dt;
	$allPidsArr[]=$pid;
	$k++;
}
$allPidsArr=array_unique($allPidsArr);
$allPidsStr=implode("','",$allPidsArr);
unset($allPidsArr);

$dbSlave=$mysqlObj->connect('303');
$sql="SELECT PROFILEID,SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID IN ('$allPidsStr')";
$result=$mysqlObj->executeQuery($sql,$dbSlave);
while($row=$mysqlObj->fetchArray($result))
{
	$pid=$row["PROFILEID"];
	$sid=$row["SERVERID"];
	$pids[$sid][]=$pid;
}	
unset($allPidsStr);

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
	$MasterShard=$mysqlObj->connect("$myDbName");

	foreach($pids[$activeServerId] as $key=>$value)
	{
		foreach($resArr[$value] as $key1=>$value1)
		{
			$sql="INSERT IGNORE INTO MIS.SIMILLAR_CONTACT_COUNT_FOR_ABTESTING VALUES($value,'$value1[0]',$value1[1],'$value1[2]')";
			$mysqlObj->executeQuery($sql,$MasterShard);
		}
	}
}
unset($pids);
unset($resArr);
?>
