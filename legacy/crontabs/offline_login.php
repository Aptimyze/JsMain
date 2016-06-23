<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**************************************************************************************************************************
Filename    : offline_login.php
Description : To update the sort date and login date of offline customer to maintain 7 day interval [2586]
Created On  : 31 January 2008
Created By  : Nikhil Dhiman
***************************************************************************************************************************/
chdir(dirname(__FILE__));
include("connect.inc");
ini_set('max_execution_time',0);
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

$mysql=new Mysql;
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
        $myDb[$myDbName]=$mysql->connect("$myDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDb[$myDbName]);
}

$sql="select DISTINCT(PROFILEID) from jsadmin.OFFLINE_BILLING WHERE ACTIVE='Y'";
$res=mysql_query($sql,$db) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
       $profileid=$row['PROFILEID'];
       
	$sql="update newjs.JPROFILE set SORT_DT=if(DATE_SUB(NOW(),INTERVAL 7 DAY)>=SORT_DT,DATE_ADD(SORT_DT,INTERVAL 7 DAY),SORT_DT) where PROFILEID='$profileid'";
	mysql_query($sql,$db) or logError($sql);
		
	if(mysql_affected_rows())
	{
		$sqlup="SELECT SORT_DT FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $resup=mysql_query($sqlup,$db) or logError($sqlup);
                $rowup=mysql_fetch_assoc($resup);
		$sqlup="update newjs.JPROFILE set LAST_LOGIN_DT='$rowup[SORT_DT]' where PROFILEID='$profileid'";
		mysql_query($sqlup,$db) or logError($sqlup);
		$myDbName=getProfileDatabaseConnectionName($profileid);

		if(!$myDb[$myDbName])
                                $myDb[$myDbName]=$mysql->connect("$myDbName");

		$sqlup="INSERT IGNORE INTO newjs.LOGIN_HISTORY(PROFILEID,LOGIN_DT) VALUES('$profileid','$rowup[SORT_DT]')";
		$mysql->executeQuery($sqlup,$myDb[$myDbName]) or logError($sqlup);
		if($mysql->affectedRows()>0)
		{
			$sql="update newjs.LOGIN_HISTORY_COUNT  set TOTAL_COUNT=TOTAL_COUNT+1 where PROFILEID=".$profileid;
                        $mysql->executeQuery($sql,$myDb[$myDbName]) or logError($sql);

                        if($mysql->affectedRows()<=0)
                        {
                                $sql="replace into newjs.LOGIN_HISTORY_COUNT(PROFILEID,TOTAL_COUNT) values(".$profileid.",1)";
                                mysql_query($sql,$db) or logError($sql);

                        }
		}
	}
	
}
?>
