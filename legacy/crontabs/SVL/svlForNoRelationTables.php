<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : svlForNoRelationTable.php
Description : Synchronize the data on every shard for tables belongs to single shard,check weather if there is any dicrepancy		   and log the errors.
Created By  : Vibhor Garg
Created On  : 18 Apr 2008
****************************************************************************************************************************/
$path = $_SERVER[DOCUMENT_ROOT];
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

include_once($path."/classes/Mysql.class.php");
include_once($path."/profile/connect_db.php");
include_once($path."/classes/shardingRelated.php");

$mysqlObj=new Mysql;

//Take the connection on all shards(slaves)
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$myDbName=getActiveServerName($activeServerId);
	$myDbarr[$myDbName]=$mysqlObj->connect("$myDbName","slave");
	mysql_query("set session wait_timeout=10000",$myDbarr[$myDbName]);
}

//Take the connection on all shards(masters)
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName_master=getActiveServerName($activeServerId);
        $myDbarr_master[$myDbName_master]=$mysqlObj->connect("$myDbName_master");
	mysql_query("set session wait_timeout=10000",$myDbarr_master[$myDbName_master]);
}

//Take the connection on shard on which dump is stored. 
$myDbName_dump=getActiveServerName(2);
$myDbarr[$myDbName_dump]=$mysqlObj->connect("$myDbName","slave");
$myDb_dump=$myDbarr[$myDbName_dump];

//Take the connection on main database.
$myDb_main=$mysqlObj->connect("master");
mysql_query("set session wait_timeout=10000",$myDb_main);
$date = date("Y-m-d",time()-86400);

//Clear the previous dump
$sql_select="TRUNCATE TABLE PROFILEID_SERVER_MAPPING_DUMP";
$res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error());

//Store the new dump

if(count($myDbarr))
	foreach($myDbarr as $key=>$val)
        {
		$myDb=$myDbarr[$key];
		
		$sql_select="SELECT * FROM PROFILEID_SERVER_MAPPING WHERE ASSIGN_DATE='$date'";
		$res_select=mysql_query($sql_select,$myDb) or die(mysql_error($myDb));
		while($row_select=mysql_fetch_array($res_select))
		{
			$pid=$row_select[0];
			$sid=$row_select[1];
			$date=$row_select[2];
			$sql_insert="INSERT IGNORE INTO PROFILEID_SERVER_MAPPING_DUMP VALUES ('$pid','$sid','$date')";
			mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
		}
	}

$sql_select="SELECT * FROM `PROFILEID_SERVER_MAPPING` WHERE ASSIGN_DATE='$date'";
$res_select=mysql_query($sql_select,$myDb_main) or die(mysql_error($myDb_main));
while($row_select=mysql_fetch_array($res_select))
{
        $pid=$row_select[0];
        $sid=$row_select[1];
        $date_reg=$row_select[2];
        $sql_insert="INSERT IGNORE INTO PROFILEID_SERVER_MAPPING_DUMP VALUES ('$pid','$sid','$date_reg')";
        mysql_query($sql_insert,$myDb_dump) or die(mysql_error());
}

//Synchronize the data on all the shards
if(count($myDbarr))
{
	$server=0;
        foreach($myDbarr_master as $key=>$val)
        {
		$myDb_master=$myDbarr_master[$key];
                $sql_select="SELECT * FROM PROFILEID_SERVER_MAPPING_DUMP WHERE SERVERID='$server'";
                $res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error($myDb_dump));
                while($row_select=mysql_fetch_array($res_select))
                {
                        $pid=$row_select[0];
                        $sid=$row_select[1];
                        $date_reg=$row_select[2];
                        $sql_insert="INSERT IGNORE INTO PROFILEID_SERVER_MAPPING VALUES ('$pid','$sid','$date_reg')";
                        mysql_query($sql_insert,$myDb_master) or die(mysql_error($myDb_master));
                }
		$server++;
	}
}

//Transfer data from dump to main database
$sql_select="SELECT * FROM PROFILEID_SERVER_MAPPING_DUMP";
$res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error());
while($row_select=mysql_fetch_array($res_select))
{
        $pid=$row_select[0];
        $sid=$row_select[1];
        $date_reg=$row_select[2];
        $sql_insert="INSERT IGNORE INTO PROFILEID_SERVER_MAPPING VALUES ('$pid','$sid','$date_reg')";
        mysql_query($sql_insert,$myDb_main) or die(mysql_error());
}
/*
verify_shard("JPARTNER","PROFILEID",$myDbarr,$myDb_dump);
verify_shard("LOGIN_HISTORY","PROFILEID",$myDbarr,$myDb_dump);
verify_shard("LOGIN_HISTORY_COUNT","PROFILEID",$myDbarr,$myDb_dump);
//verify_shard("MESSAGES","ID",$myDbarr,$myDb_dump);
verify_shard("LOG_LOGIN_HISTORY","PROFILEID",$myDbarr,$myDb_dump);
*/

$date = date("Y-m-d",time()-86400);
$time = date("H-i-s");

//Verfication of sharding for each table(ALL TIME)

verify_shard("JPARTNER","PROFILEID",$myDbarr,$myDb_dump,"DATE",$date);
verify_shard("LOGIN_HISTORY","PROFILEID",$myDbarr,$myDb_dump,"LOGIN_DT",$date);
verify_shard("LOGIN_HISTORY_COUNT","PROFILEID",$myDbarr,$myDb_dump);
//verify_shard("MESSAGES","ID",$myDbarr,$myDb_dump);
verify_shard("LOG_LOGIN_HISTORY","PROFILEID",$myDbarr,$myDb_dump,"TIME",$time);

//Verify the sharding for given table and display the error if any
function verify_shard($tablename,$paramname,$myDbarr,$myDb_dump,$limitparam='',$limitval='')
{
	$dbid=0;
	if(count($myDbarr))
		foreach($myDbarr as $key=>$val)
		{
			$myDb=$myDbarr[$key];
			if($limitparam=='')
			{
				$sql_select="SELECT ".$tablename.".".$paramname.",SERVERID FROM ".$tablename." LEFT JOIN PROFILEID_SERVER_MAPPING ON ".$tablename.".".$paramname." = PROFILEID_SERVER_MAPPING.PROFILEID WHERE SERVERID !=".$dbid."";
			}
			else
			{
				$sql_select="SELECT ".$tablename.".".$paramname.",SERVERID FROM ".$tablename." LEFT JOIN PROFILEID_SERVER_MAPPING ON ".$tablename.".".$paramname." = PROFILEID_SERVER_MAPPING.PROFILEID AND ".$tablename.".".$limitparam."=".$limitval." WHERE SERVERID !=".$dbid."";
			}
			$res_select=mysql_query($sql_select,$myDb) or die(mysql_error($myDb));
                        while($row=mysql_fetch_row($res_select))
			{
                        	$pid=$row[0];
				$sid=$row[1];
				if($pid !="")
				{	
					if($sid=='')
					{
						$sql_insert="INSERT IGNORE SVL.NO_SHARD VALUES ($pid,$dbid)";
                	                        mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
					}
                        		else
					{
						$sql_insert="INSERT IGNORE SVL.WRONG_SHARD_SINGLE VALUES ($pid,$sid,$dbid,'$tablename')";
                	                        mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
					}
				}
			}
             		$dbid++;
		}
}
?>
