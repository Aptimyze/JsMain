<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : svlForRelatedTablesAlltime.php
Description : Track the data(inserted,updated,deleted) on every shard,check weather if there is any dicrepancy and log the 
	      errors on the daily basis.
Created By  : Vibhor Garg
Created On  : 28 Apr 2008
****************************************************************************************************************************/

$path = $_SERVER[DOCUMENT_ROOT];
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


//Clear the previous dump
$sql_select="TRUNCATE TABLE SVL.MAY_WRONG_SHARD_DOUBLE_ALL";
$res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error());

$date=date("Y-m-d");
//Store the new dump
if(count($myDbarr))
	foreach($myDbarr as $key=>$val)
        {
		$myDb=$myDbarr[$key];
		$sql_select="SELECT * FROM SVL.MAY_WRONG_SHARD_DOUBLE WHERE DATE='$date'";
		$res_select=mysql_query($sql_select,$myDb) or die(mysql_error());
		while($row_select=mysql_fetch_array($res_select))
		{
			$sender=$row_select[0]."\n";
			$receiver=$row_select[1]."\n";
			$sid=$row_select[2];
			$rid=$row_select[3];
			$shard=$row_select[4]."\n";
			$tablename=$row_select[5];
			$mod_type=$row_select[6];
			$sql_insert="INSERT IGNORE INTO SVL.MAY_WRONG_SHARD_DOUBLE_ALL VALUES($sender,$receiver,$sid,$rid,$shard,'$tablename','$mod_type')";
			mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
		}
		$sql_delete="DELETE FROM SVL.MAY_WRONG_SHARD_DOUBLE WHERE DATE='$date'";
                mysql_query($sql_delete,$myDb_dump) or die(mysql_error());
	}

$sql_select="SELECT DISTINCT(TABLENAME) FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL";
$res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error());
while($row_select=mysql_fetch_assoc($res_select))
{
	echo $table=$row_select['TABLENAME'];
	
	if($table == 'PHOTO_REQUEST')
	{
		$paramname1="PROFILEID_REQ_BY";
		$paramname2="PROFILEID";
	}
	elseif($table == 'HOHOSCOPE_REQUEST')
	{
		$paramname1="PROFILEID_REQUEST_BY";
                $paramname2="PROFILEID";
	}
	else
	{
		$paramname1="SENDER";
                $paramname2="RECEIVER";
	}

	$sql="SELECT SENDER,RECEIVER FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE MODIFICATION_TYPE='D' AND TABLENAME='$table'";
	$res=mysql_query($sql,$myDb_dump) or die(mysql_error());
	while($row=mysql_fetch_assoc($res))
	{
		$sender=$row['SENDER'];
        	$receiver=$row['RECEIVER'];
	 	$sql1="SELECT COUNT(*) AS CNT FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='D' AND TABLENAME='$table'";
                $res1=mysql_query($sql1,$myDb_dump) or die(mysql_error());
                $row1=mysql_fetch_assoc($res1);
                $count=$row1['CNT']; 
		if($count==1)
		{
			$sql_insert="INSERT IGNORE INTO SVL.INCOMPLETE_SHARDING (SENDER,RECEIVER,SHARD,TABLENAME,MODIFICATION_TYPE) VALUES($sender,$receiver,$shard,'$table','D')";
                	mysql_query($sql_insert,$myDb_dump) or die(mysql_error());
		}
		if($count>0)
		{
			$sql2="DELETE FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='D' AND TABLENAME='$table'";
        		mysql_query($sql2,$myDb_dump) or die(mysql_error());
		}
	}
	
	$sql="SELECT SENDER,RECEIVER,SHARD FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE MODIFICATION_TYPE='I' AND TABLENAME='$table'";
        $res=mysql_query($sql,$myDb_dump) or die(mysql_error());
        while($row=mysql_fetch_assoc($res))
        {
		$sender=$row['SENDER'];
        	$receiver=$row['RECEIVER'];
		$shard=$row['SHARD'];
        	$sql3="SELECT COUNT(*) AS CNT FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='I' AND TABLENAME='$table'";
        	$res3=mysql_query($sql3,$myDb_dump) or die(mysql_error());
        	$row3=mysql_fetch_assoc($res3);
                $count=$row3['CNT'];
        	if($count==1)
        	{
                	$sql_insert="INSERT IGNORE SVL.INCOMPLETE_SHARDING (SENDER,RECEIVER,SHARD,TABLENAME,MODIFICATION_TYPE) VALUES($sender,$receiver,$shard,'$table','I')";
                	mysql_query($sql_insert,$myDb_dump) or die(mysql_error());
		}
		elseif($count==2)
		{
			$sql4="SELECT SHARD FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='I' AND TABLENAME='$table'";
	                $res4=mysql_query($sql4,$myDb_dump) or die(mysql_error());
        	        while($row4=mysql_fetch_assoc($res4))
			{
	                	$check[]=$row4['SHARD'];
			}
			$shard1=$check[0];
			$myDbName_check=getActiveServerName($shard1);
			$myDb_check=$myDbarr[$myDbName_check];
			$sql1="SELECT * FROM $table WHERE $paramname1='$sender' AND $paramname2='$receiver'";
                        $res1=mysql_query($sql1,$myDb_check) or die(mysql_error());
                        $row1=mysql_fetch_row($res1);
			$shard2=$check[1];
                        $myDbName_check=getActiveServerName($shard2);
                        $myDb_check=$myDbarr[$myDbName_check];
                        $sql2="SELECT * FROM $table WHERE $paramname1='$sender' AND $paramname2='$receiver'";
                        $res2=mysql_query($sql2,$myDb_check) or die(mysql_error());
                        $row2=mysql_fetch_row($res2);
			for($i=0;$i<count($row1);$i++)
			{
				if($row1[$i]!=$row2[$i])
					$flag=1;
			}
			if($flag==1)
			{
				$sql_insert="INSERT INTO SVL.UNMATCH_SHARDING (SENDER,RECEIVER,TABLENAME,SHARD1,SHARD2) VALUES($sender,$receiver,'$table',$shard1,$shard2)";
                        	mysql_query($sql_insert,$myDb_dump) or die(mysql_error());
			}
		}
		if($count>0)
		{
			$sql5="DELETE FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='I' AND TABLENAME='$table'";
                	mysql_query($sql5,$myDb_dump) or die(mysql_error());
		}
	}
	
	$sql="SELECT SENDER,RECEIVER,SHARD FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE MODIFICATION_TYPE='U' AND TABLENAME='$table'";
        $res=mysql_query($sql,$myDb_dump) or die(mysql_error());
        while($row=mysql_fetch_array($res))
        {
                $sender=$row['SENDER'];
                $receiver=$row['RECEIVER'];
		$shard=$row['SHARD'];
                $sql3="SELECT COUNT(*) AS CNT FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='U' AND TABLENAME='$table'";
                $res3=mysql_query($sql3,$myDb_dump) or die(mysql_error());
                $row3=mysql_fetch_assoc($res3);
                $count=$row3['CNT'];
                if($count==1)
                {
                        $sql_is="INSERT IGNORE SVL.INCOMPLETE_SHARDING (SENDER,RECEIVER,SHARD,TABLENAME,MODIFICATION_TYPE) VALUES($sender,$receiver,$shard,'$table','U')";
                        mysql_query($sql_is,$myDb_dump) or die(mysql_error());
                }
                if($count==2)
                {
                      	$sql4="SELECT SHARD FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER='$sender' AND RECEIVER='$receiver' AND MODIFICATION_TYPE='U' AND TABLENAME='$table'";
                        $res4=mysql_query($sql4,$myDb_dump) or die(mysql_error());
                        while($row4=mysql_fetch_array($res4))
			{
				$check[]=$row4['SHARD'];
			}
                        $shard1=$check[0];
                        $myDbName_check1=getActiveServerName($shard1);
                        $myDb_check1=$myDbarr[$myDbName_check1];
                        $sql1="SELECT * FROM $table WHERE $paramname1='$sender' AND $paramname2='$receiver'";
                        $res1=mysql_query($sql1,$myDb_check1) or die(mysql_error());
                        $row1=mysql_fetch_row($res1);
                        $shard2=$check[1];
                        $myDbName_check2=getActiveServerName($shard2);
                        $myDb_check2=$myDbarr[$myDbName_check2];
                        $sql2="SELECT * FROM $table WHERE $paramname1='$sender' AND $paramname2='$receiver'";
                        $res2=mysql_query($sql2,$myDb_check2) or die(mysql_error());
                        $row2=mysql_fetch_row($res2);
			$flag=0;
			for($i=0;$i<count($row1);$i++)
                        {
                                if($row1[$i]!=$row2[$i])
                                        $flag=1;
                        }
			if($flag==1)
                        {
                                $sql_us="INSERT INTO SVL.UNMATCH_SHARDING (SENDER,RECEIVER,TABLENAME,SHARD1,SHARD2) VALUES($sender,$receiver,'$table',$shard1,$shard2)";
                                mysql_query($sql_us,$myDb_dump) or die(mysql_error());
                        }
			else
			{
        			$shards[0]=$shard1;	
				$shards[1]=$shard2;
				$sql4="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID='$sender'";
        			$res4=mysql_query($sql4,$myDb_dump) or die(mysql_error());
        			$row4=mysql_fetch_array($res4);
        			$sid=$row4['SERVERID'];
				$sql5="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID='$receiver'";
				$res5=mysql_query($sql5,$myDb_dump) or die(mysql_error());
				$row5=mysql_fetch_array($res5);
				$rid=$row5['SERVERID'];
				if((!in_array($sid,$shards)) && (!in_array($rid,$shards)))
				{
					if($sid!=$rid)
					{
						$sql_us="INSERT INTO SVL.WRONG_SHARD_DOUBLE (SENDER,RECEIVER,SERVERID_OF_SENDER,SERVERID_OF_RECEIVER,SHARD) VALUES($sender,$receiver,$sid,$rid,$shard1)";
					}
					else
					{
						$sql_us="INSERT INTO SVL.WRONG_SHARD_DOUBLE (SENDER,RECEIVER,SERVERID_OF_SENDER,SERVERID_OF_RECEIVER,SHARD) VALUES($sender,$receiver,$sid,$rid,$shard1)";
	                                	mysql_query($sql_us,$myDb_dump) or die(mysql_error());
						$sql_us="INSERT INTO SVL.WRONG_SHARD_DOUBLE (SENDER,RECEIVER,SERVERID_OF_SENDER,SERVERID_OF_RECEIVER,SHARD) VALUES($sender,$receiver,$sid,$rid,$shard2)";
                                        	mysql_query($sql_us,$myDb_dump) or die(mysql_error());
					}
					
				}
			}
        	}
		if($count>0)
		{
			$sql5="DELETE FROM SVL.MAY_WRONG_SHARD_DOUBLE_ALL WHERE SENDER=$sender AND RECEIVER=$receiver AND MODIFICATION_TYPE='U' AND TABLENAME='$table'";
                	mysql_query($sql5,$myDb_dump) or die(mysql_error());
		}		
	}
}
$sql="SELECT SENDER,RECEIVER,SHARD FROM SVL.INCOMPLETE_SHARDING";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$sender=$row['SENDER'];
	$receiver=$row['RECEIVER'];
	$shard=$row['SHARD'];
	$sql4="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID='$sender'";
	$res4=mysql_query($sql4,$myDb_dump) or die(mysql_error());
        $row4=mysql_fetch_array($res4);
	$sid=$row4['SERVERID'];
	if($shard==$sid)
	{
		$sql5="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID='$receiver'";
        	$res5=mysql_query($sql5,$myDb_dump) or die(mysql_error());
        	$row5=mysql_fetch_array($res5);
        	$rid=$row5['SERVERID'];	
        	if($sid==$rid)
		{
			$sql6="DELETE FROM SVL.INCOMPLETE_SHARDING WHERE SENDER=$sender AND RECEIVER=$receiver";
                	mysql_query($sql6,$myDb_dump) or die(mysql_error());
        	}
	}
}
?>
