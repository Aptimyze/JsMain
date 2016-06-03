<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : svlTestScript.php
Description : Insert,Update and Delete the data from sharded tables on all the shards for testing.
Created By  : Vibhor Garg
Created On  : 02 May 2008
****************************************************************************************************************************/

$path =$_SERVER[DOCUMENT_ROOT];
include_once($path."/classes/Mysql.class.php");
include_once($path."/profile/connect_db.php");
include_once($path."/classes/shardingRelated.php");

$mysqlObj=new Mysql;

//Take the connection on all shards
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
	$myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
}

//Take the connection on shard on which dump is stored. 
$myDbName_dump=getActiveServerName(2);
$myDbarr[$myDbName_dump]=$mysqlObj->connect("$myDbName");
$myDb_dump=$myDbarr[$myDbName_dump];

$date=date("Y-m-d");
/*
if(count($myDbarr))
	foreach($myDbarr as $key=>$val)
        {
		$myDb=$myDbarr[$key];
		$pid=420;
		$prid=420;
		$prid_by_r=420;
		$did=71706;
		while($pid<840)
		{
			$sql_insert="INSERT IGNORE INTO PHOTO_REQUEST VALUES ('$prid','$prid_by_r',$date,1,'N')";
			mysql_query($sql_insert,$myDb) or die(mysql_error());
			$sql_insert="UPDATE PHOTO_REQUEST SET DATE='0000-00-00' WHERE PROFILEID='$pid'";
                	mysql_query($sql_insert,$myDb) or die(mysql_error());
			$sql_insert="DELETE FROM PHOTO_REQUEST WHERE PROFILEID='$did'";
        	        mysql_query($sql_insert,$myDb) or die(mysql_error());
			$pid=$pid+2;
			$prid=$prid+2;
			$prid_by_r=$prid_by_r+3;
			$did=$did+400;
		}
	}

if(count($myDbarr))
        foreach($myDbarr as $key=>$val)
        {
                $myDb=$myDbarr[$key];
                $pid=420;
                $prid=420;
                $prid_by_r=420;
                $did=71706;
                while($pid<840)
                {
                        $sql_insert="INSERT IGNORE INTO HOROSCOPE_REQUEST VALUES ('$prid','$prid_by_r',$date,'N',1)";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $sql_insert="UPDATE HOROSCOPE_REQUEST SET DATE='0000-00-00' WHERE PROFILEID='$pid'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $sql_insert="DELETE FROM HOROSCOPE_REQUEST WHERE PROFILEID='$did'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $pid=$pid+2;
                        $prid=$prid+2;
                        $prid_by_r=$prid_by_r+3;
                        $did=$did+400;
                }
        }

if(count($myDbarr))
        foreach($myDbarr as $key=>$val)
        {
                $myDb=$myDbarr[$key];
                $pid=420;
                $prid=420;
                $did=71706;
                while($pid<840)
                {
                        $sql_insert="UPDATE MESSAGE_LOG SET DATE='0000-00-00' WHERE SENDER='$pid' AND RECEIVER='$prid'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $sql_insert="DELETE FROM MESSAGE_LOG WHERE RECEIVER='$did'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $pid=$pid+2;
                        $prid=$prid+3;
                        $did=$did+400;
                }
        }
*/
if(count($myDbarr))
        foreach($myDbarr as $key=>$val)
        {
                $myDb=$myDbarr[$key];
                $pid=420;
                $prid=420;
                $did=71706;
                while($pid<840)
                {
                        $sql_insert="UPDATE DELETED_MESSAGE_LOG SET DATE='0000-00-00' WHERE SENDER='$pid' AND RECEIVER='$prid'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $sql_insert="DELETE FROM DELETED_MESSAGE_LOG WHERE RECEIVER='$did'";
                        mysql_query($sql_insert,$myDb) or die(mysql_error());
                        $pid=$pid+2;
                        $prid=$prid+3;
                        $did=$did+400;
                }
        }

