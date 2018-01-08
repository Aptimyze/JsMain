<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************************************************
Filename     :  calculate_contacts_for_trends.php
Description  :  To calculate initiated, accepted, declined and cancelled contacts for all profiles in TRENDS table[2840]
Created On   :  22 September 2008
Author       :  Sadaf Alam
***********************************************************************************************************************/
ini_set("max_execution_time","0");

$flag_using_php5=1;
include("connect.inc");

$mysqlObj=new Mysql;

for($i=0;$i<$noOfActiveServers;$i++)
{
	$slaveDbName=$slave_activeServers[$i];
	$slaveDb=$mysqlObj->connect("$slaveDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$slaveDb);
	$slaveDbArray[$slaveDbName]=$slaveDb;
}

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);

$sql="SELECT PROFILEID FROM twowaymatch.TRENDS";
$res=mysql_query($sql,$dbSlave) or logError($sql);
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		unset($pid);
		unset($initiated);
		unset($accepted);
		unset($declined);
		$pid=$row["PROFILEID"];
		$myDbName=getProfileDatabaseConnectionName($pid,'slave',$mysqlObj);
		mysql_ping($slaveDbArray[$myDbName]);
		$myDb=$slaveDbArray[$myDbName];
		$sqlcon="SELECT TYPE FROM newjs.CONTACTS WHERE SENDER='$pid'";
		$myRes=$mysqlObj->executeQuery($sqlcon,$myDb);
		while($myRow=$mysqlObj->fetchAssoc($myRes))
		{
			$initiated++;
			if($myRow["TYPE"]=="A")
				$accepted++;
			if($myRow["TYPE"]=="D")
				$declined++;
			
		}
		mysql_free_result($myRes);
		$sqlupdate="UPDATE twowaymatch.TRENDS SET INITIATED='$initiated',ACCEPTED='$accepted',DECLINED='$declined' WHERE PROFILEID='$pid'";
		mysql_query($sqlupdate,$db) or logError($sqlupdate);
		
	}
	mysql_free_result($res);
}

?>
