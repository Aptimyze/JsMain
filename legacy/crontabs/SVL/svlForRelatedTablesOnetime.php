<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : svlForRelatedTablesOnetime.php
Description : Synchronize the data on every shard ,check weather if there is any dicrepancy and log the errors.
Created By  : Vibhor Garg
Created On  : 26 Apr 2008
****************************************************************************************************************************/

$path =$_SERVER[DOCUMENT_ROOT];
chdir($path);
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

//Take the connection on shard on which data is stored for verification and final output available. 
$myDbName_dump=getActiveServerName(2);
$myDbarr[$myDbName_dump]=$mysqlObj->connect("$myDbName","slave");
$myDb_dump=$myDbarr[$myDbName_dump];

//Verfication of sharding for each table(one time)
//echo "HOROSCOPE_REQUEST:\n";
//verify_shard_twoway("HOROSCOPE_REQUEST","PROFILEID","PROFILEID_REQUEST_BY",$myDbarr,$myDb_dump);
echo "PHOTO_REQUEST:\n";
verify_shard_twoway("PHOTO_REQUEST","PROFILEID","PROFILEID_REQ_BY",$myDbarr,$myDb_dump);
echo "DONE";die;
echo "DELETED_MESSAGE_LOG:\n";
verify_shard_twoway("DELETED_MESSAGE_LOG","SENDER","RECEIVER",$myDbarr,$myDb_dump);
echo "MESSAGE_LOG:\n";
verify_shard_twoway("MESSAGE_LOG","SENDER","RECEIVER",$myDbarr,$myDb_dump);

//Verify the sharding for the given table and display the error if any[for both parameters (id & id request by)]
function verify_shard_twoway($tablename,$paramname1,$paramname2,$myDbarr,$myDb_dump)
{	

	$dbid=0;
        if(count($myDbarr))
                foreach($myDbarr as $key=>$val)
                {
                        echo "SHARD".$dbid.":\n";
			$myDb=$myDbarr[$key];
			$date1='2005-01-01';
			$date2='2005-07-01';
			$flag=0;
			while($date2!='2008-06-01')		
			{
				echo $date1.$date2."\n";
				$sql1="SELECT ".$tablename.".".$paramname1." AS PARAMID,".$paramname2." AS PARAM2ID,SERVERID FROM ".$tablename." LEFT JOIN PROFILEID_SERVER_MAPPING ON ".$tablename.".".$paramname1." = PROFILEID_SERVER_MAPPING.PROFILEID AND ".$tablename.".DATE BETWEEN '".$date1."' AND '".$date2."' WHERE SERVERID !=".$dbid."";
				$res1=mysql_query($sql1,$myDb) or die(mysql_error($myDb));
				while($row1=mysql_fetch_assoc($res1))
				{
					$sender=$row1['PARAMID'];
                                        $receiver=$row1['PARAM2ID'];
                                        $sid=$row1['SERVERID'];
					if($sid=='')
					{
						$sql_insert="INSERT IGNORE SVL.NO_SHARD VALUES ($sender,$dbid)";
                                                mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
					}
					else
					{
						$sql_insert="INSERT INTO SVL.MAY_CORRECT_SHARD_FOR_RECEIVER VALUES ($sender,$receiver,$sid,$dbid)";
                                              	mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
						
                                        }					
				}
				$flag++;
                                $date1=assign_date($flag,1);
                  	        $date2=assign_date($flag,2);
			}
			$date1='2005-01-01';
                        $date2='2005-07-01';
                        $flag=0;
                        while($date2!='2008-06-01')
                        {
				echo $date1.$date2."\n";
				$sql2="SELECT ".$paramname2." AS PARAM2ID,".$tablename.".".$paramname1." AS PARAM1ID,SERVERID FROM ".$tablename." LEFT JOIN PROFILEID_SERVER_MAPPING ON ".$paramname2." = PROFILEID_SERVER_MAPPING.PROFILEID AND ".$tablename.".DATE BETWEEN '".$date1."' AND '".$date2."' WHERE SERVERID !=".$dbid."";

				$res2=mysql_query($sql2,$myDb) or die(mysql_error($myDb));
				while($row2=mysql_fetch_assoc($res2))
				{
                                        $receiver=$row2['PARAM2ID'];
                                        $sender=$row2['PARAM1ID'];
					$sid=$row2['SERVERID'];
					if($sid=='')
                                        {
                                                $sql_insert="INSERT IGNORE SVL.NO_SHARD VALUES ($sender,$dbid)";
                                                mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
                                        }
                                        else
                                        {	
						$sql_insert="INSERT INTO SVL.MAY_CORRECT_SHARD_FOR_SENDER VALUES ($receiver,$sender,$sid,$dbid)";
						mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
						
					}
				}
				$flag++;
				$date1=assign_date($flag,1);
				$date2=assign_date($flag,2);
			}		
                        $dbid++;
                }
	
		$shard=0;
		while($shard<3)
		{
			echo $shard;
			$sql_select="SELECT SENDER,RECEIVER,SERVERID_OF_SENDER FROM SVL.MAY_CORRECT_SHARD_FOR_RECEIVER WHERE SHARD=$shard";
                	$res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error($myDb_dump));
                	while($row_select=mysql_fetch_assoc($res_select))
			{
                		$sender=$row_select['SENDER'];
				$receiver=$row_select['RECEIVER'];
				$sid_of_sender=$row_select['SERVERID_OF_SENDER'];
					
				$sql_sel="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID=$receiver";
				$res_sel=mysql_query($sql_sel,$myDb_dump) or die(mysql_error($myDb_dump));
        	                $row_sel=mysql_fetch_assoc($res_sel);
                	        $sid_of_receiver=$row_sel['SERVERID'];
				if($sid_of_receiver != '')
				{		
					if($sid_of_receiver != $shard)
					{
						$sql_insert="INSERT IGNORE INTO SVL.WRONG_SHARD_DOUBLE VALUES ($sender,$receiver,$sid_of_sender,$sid_of_receiver,$shard,'$tablename')";
                                		$res=mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
					}
					else
					{
			                        $shard1=$sid_of_sender;
        			                $myDbName_check1=getActiveServerName($shard1);
                		        	$myDb_check1=$myDbarr[$myDbName_check1];
                        			$sql1="SELECT * FROM $tablename WHERE ".$paramname1."=$sender AND ".$paramname2."=$receiver";
						$res1=mysql_query($sql1,$myDb_check1) or die(mysql_error($myDb_check1));
        			                $row1=mysql_fetch_row($res1);
						$shard2=$sid_of_receiver;
                        			$myDbName_check2=getActiveServerName($shard2);
	                        		$myDb_check2=$myDbarr[$myDbName_check2];
						@mysql_ping($myDb_check2);
						$sql2="SELECT * FROM $tablename WHERE ".$paramname1."=$sender AND ".$paramname2."=$receiver";
						$res2=mysql_query($sql2,$myDb_check2) or die(mysql_error($myDb_check2));
						$row2=mysql_fetch_row($res2);
	        	        	        $flag=0;
        	        	        	for($i=0;$i<count($row1);$i++)
	                		        {
        	                		        if($row1[$i]!=$row2[$i])
                	                        		$flag=1;
		                	        }
						unset($row1);
						unset($row2);
        		                	if($flag==1)
	        	        	        {
        	        	        	        $sql_us="INSERT INTO SVL.UNMATCH_SHARDING (SENDER,RECEIVER,TABLENAME,SHARD1,SHARD2) VALUES($sender,$receiver,'$tablename',$shard1,$shard2)";
                	        	        	mysql_query($sql_us,$myDb_dump) or die(mysql_error($myDb_dump));
	                        		}
					}
				}
				else
				{
					$sql_in="INSERT IGNORE INTO SVL.NO_SHARD VALUES ($receiver,$shard)";
                                        mysql_query($sql_in,$myDb_dump) or die(mysql_error($myDb_dump));
				}	
				$sql_delete="DELETE FROM SVL.MAY_CORRECT_SHARD_FOR_RECEIVER WHERE SENDER=$sender AND RECEIVER=$receiver";
                                mysql_query($sql_delete,$myDb_dump) or die(mysql_error($myDb_dump));

                                $sql_del="DELETE FROM SVL.MAY_CORRECT_SHARD_FOR_SENDER WHERE RECEIVER=$receiver AND SENDER=$sender";
                                mysql_query($sql_del,$myDb_dump) or die(mysql_error($myDb_dump));
			}	
			$shard++;
		}
		
		$shard=0;
                while($shard<3)
                {
                        echo $shard;
			$sql_select="SELECT RECEIVER,SENDER,SERVERID_OF_RECEIVER FROM SVL.MAY_CORRECT_SHARD_FOR_SENDER WHERE SHARD=$shard";
                        $res_select=mysql_query($sql_select,$myDb_dump) or die(mysql_error($myDb_dump));
                        while($row_select=mysql_fetch_assoc($res_select))
                        {
                                $receiver=$row_select['RECEIVER'];
				$sender=$row_select['SENDER'];
				$sid_of_receiver=$row_select['SERVERID_OF_RECEIVER'];

                                $sql_sel="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID=$sender";
                                $res_sel=mysql_query($sql_sel,$myDb_dump) or die(mysql_error($myDb_dump));
                                $row_sel=mysql_fetch_assoc($res_sel);
                                $sid_of_sender=$row_sel['SERVERID'];

                                if($sid_of_sender != '')
				{
					if($sid_of_sender != $shard)
					{
						$sql_insert="INSERT INTO SVL.WRONG_SHARD_DOUBLE VALUES ($sender,$receiver,$sid_of_sender,$sid_of_receiver,$shard,'$tablename')";
						mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
					}
					else
					{
						$shard1=$sid_of_sender;
						$myDbName_check1=getActiveServerName($shard1);
						$myDb_check1=$myDbarr[$myDbName_check1];
						$sql1="SELECT * FROM $tablename WHERE ".$paramname1."='$sender' AND ".$paramname2."='$receiver'";
						$res1=mysql_query($sql1,$myDb_check1) or die(mysql_error($myDb_check1));
					        $row1=mysql_fetch_row($res1);
						$shard2=$sid_of_receiver;
						$myDbName_check2=getActiveServerName($shard2);
						$myDb_check2=$myDbarr[$myDbName_check2];
						@mysql_ping($myDb_check2);
						$sql2="SELECT * FROM $tablename WHERE ".$paramname1."='$sender' AND ".$paramname2."='$receiver'";
						$res2=mysql_query($sql2,$myDb_check2) or die(mysql_error($myDb_check2));
						$row2=mysql_fetch_row($res2);
						$flag=0;
						for($i=0;$i<count($row1);$i++)
						{
							if($row1[$i]!=$row2[$i])
							$flag=1;
						}
						if($flag==1)
						{
                                                $sql_us="INSERT INTO SVL.UNMATCH_SHARDING (SENDER,RECEIVER,TABLENAME,SHARD1,SHARD2) VALUES($sender,$receiver,'$tablename',$shard1,$shard2)";
                                                mysql_query($sql_us,$myDb_dump) or die(mysql_error($myDb_dump));
                                        	}
                                	}
				}
				else
				{
					$sql_insert="INSERT IGNORE SVL.NO_SHARD VALUES ($sender,$shard)";
                                        mysql_query($sql_insert,$myDb_dump) or die(mysql_error($myDb_dump));
				}

                                $sql_delete="DELETE FROM SVL.MAY_CORRECT_SHARD_FOR_SENDER WHERE SENDER=$sender AND RECEIVER=$receiver";
                                mysql_query($sql_delete,$myDb_dump) or die(mysql_error($myDb_dump));				
                        }
                        $shard++;
		}
}

function assign_date($flag,$date_num)
{
	Switch ($flag)
        {
		Case 1:
		{
			if($date_num == 1)
				$date='2005-07-01';
			else
				$date='2006-01-01';			
			break;
		}
		Case 2:
                {
		        if($date_num == 1)
				$date='2006-01-01';
                        else
				$date='2006-07-01';
			break;
		}
		Case 3:
                {
		        if($date_num == 1)
				$date='2006-07-01';
                        else
				$date='2007-01-01';
			break;
		}
		Case 4:
                {
		        if($date_num == 1)
				$date='2007-01-01';
                        else
				$date='2007-07-01';
			break;
		}
		Case 5:
		{
                        if($date_num == 1)
                                $date='2007-07-01';
                        else
                                $date='2008-01-01';
                        break;
                }
		Case 6:
                {
                        if($date_num == 1)
                                $date='2008-01-01';
                        else
                                $date='2008-06-01';
                        break;
                }
		Case 7: 
		{
			if($date_num == 1)
				$date='2008-07-01';
			else
				$date='2008-07-01';
			break;
		}
	}
	return $date;
}
?>
