<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once("housekeepingConfig.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");


checkForEndTime();
if(CommonUtility::hideFeaturesForUptime() && JsConstants::$whichMachine == 'prod')
        successfullDie();


$monthDiff = 12;
$timeDiff = $monthDiff + 6 ;
$timestamp=mktime(0, 0, 0, date("m") - $monthDiff , date("d"), date("Y"));
$inactivityDate=date("Y-m-d",$timestamp);

$timestamp=mktime(0, 0, 0, date("m")- $timeDiff  , date("d"), date("Y"));
$inactivityDate_plus_onemonth=date("Y-m-d",$timestamp);

$timestamp=mktime(0, 0, 0, date("m") - $timeDiff , date("d"), date("Y"));
$oldActivityOneYear=date("Y-m-d",$timestamp);

$delArchivePrefix = HouseKeepingEnum::DELETE_ARCHIVE_TABLE_PREFIX;
$delArchiveSuffix = HouseKeepingEnum::DELETE_ARCHIVE_TABLE_SUFFIX;

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);
mysql_select_db("test",$dbSlave);
echo "Start\n\n";
$inactiveRecordTable="INACTIVE_RECORDS_6_MONTHS_FOR_CRON";
$mysqlObj=new Mysql;

//$inactivityDate='2011-10-02';
//Store list of profile which are not logged in b/w 6-7 months
$sql="TRUNCATE TABLE test.$inactiveRecordTable";
echo $sql."\n\n";
mysql_query($sql,$dbSlave) or die(mysql_error($dbSlave).$sql);

$sql="INSERT INTO test.$inactiveRecordTable(PROFILEID) SELECT PROFILEID FROM newjs.JPROFILE WHERE DATE(LAST_LOGIN_DT) BETWEEN '$inactivityDate_plus_onemonth' AND '$inactivityDate' ";
//$sql="INSERT INTO test.$inactiveRecordTable(PROFILEID) SELECT PROFILEID FROM newjs.JPROFILE WHERE LAST_LOGIN_DT < '$inactivityDate'";
echo $sql."\n\n";
mysql_query($sql,$dbSlave) or die(mysql_error($dbSlave).$sql);
//Store list of profile which are not logged in b/w 6-7 months
$orgNoOfActiveServers = $noOfActiveServers ;
//$noOfActiveServers =2;

//dumping INACTIVE_RECORDS_6_MONTHS_FOR_CRON to all slaves as they are needed for joins
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $dbNameS=getActiveServerName($activeServerId,"slave");
        $dbS=$mysqlObj->connect($dbNameS);
        mysql_select_db("test",$dbS);

	$sql="TRUNCATE TABLE test.$inactiveRecordTable";
echo "Query on slave",$activeServerId,": ",$sql."\n\n";
	mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
}
$s1=getDumpCommandConnectionDetails('S',"$inactiveRecordTable",'test','source');//slave

$t1=getDumpCommandConnectionDetails('SS1','','test','target');//shardslave1
$p=$s1." | ".$t1;
passthru($p);

echo "Dump1","\n\n";

$t2=getDumpCommandConnectionDetails('SS2','','test','target');//shardslave2
echo $p=$s1." | ".$t2;
passthru($p);
echo "Dump2","\n\n";
$t3=getDumpCommandConnectionDetails('SS3','','test','target');//shardslave3
$p=$s1." | ".$t3;
passthru($p);
echo "Dump3","\n\n";
//dumping INACTIVE_RECORDS_6_MONTHS_FOR_CRON to all slaves as they are needed for joins
//BOOKMARKS
for($i=0;$i<2;$i++)
{
	if($i==0)
		$sql="SELECT DISTINCT(A.BOOKMARKER) AS PID FROM newjs.BOOKMARKS A , test.$inactiveRecordTable B WHERE BKDATE<'$oldActivityOneYear' AND A.BOOKMARKER=B.PROFILEID";
	else
		$sql="SELECT DISTINCT(A.BOOKMARKEE) AS PID FROM newjs.BOOKMARKS A , test.$inactiveRecordTable B WHERE BKDATE<'$oldActivityOneYear' AND A.BOOKMARKEE=B.PROFILEID";
echo "(".$i.")";
echo $sql."\n\n";
	$res=mysql_query($sql,$dbSlave) or die(mysql_error($dbSlave).$sql);
	while($row=mysql_fetch_array($res))
	{
	checkForEndTime();
if($laveshBookmark++%1000==0)
	echo $laveshBookmark." - ";

		$col1=$row["PID"];
		if($i==0)
			$whereCondition=" AND BOOKMARKER='$col1'";	
		else
			$whereCondition=" AND BOOKMARKEE='$col1'";

		$sql_1="BEGIN";
		mysql_query($sql_1,$db) or die(mysql_error($db).$sql_1);
		
		$sql_i="REPLACE INTO newjs.{$delArchivePrefix}DELETED_BOOKMARKS{$delArchiveSuffix} SELECT * FROM newjs.BOOKMARKS WHERE BKDATE<'$oldActivityOneYear'";
		$sql_i.=$whereCondition;
		//echo $sql_i,"\n\n";
		mysql_query($sql_i,$db) or die(mysql_error($db).$sql_i);

		$sql_d="DELETE FROM newjs.BOOKMARKS WHERE BKDATE<'$oldActivityOneYear'";
		$sql_d.=$whereCondition;
		mysql_query($sql_d,$db) or die(mysql_error($db).$sql_d);

		$sql_1="COMMIT";
		mysql_query($sql_1,$db) or die(mysql_error($db).$sql_1);
		
		unset($whereCondition);
	}
}
//BOOKMARKS

echo "\n\n\n";
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");
        mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbArr[$myDbName]);
}

$dbMessageLogObj1=new NEWJS_MESSAGE_LOG("shard1_slave");
$dbMessageLogObj2=new NEWJS_MESSAGE_LOG("shard2_slave");
$dbMessageLogObj3=new NEWJS_MESSAGE_LOG("shard3_slave");

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	$k=$activeServerId+1;
	if($k==1)
		$dbMessageLogObj=$dbMessageLogObj1;
	if($k==2)
		$dbMessageLogObj=$dbMessageLogObj2;
	if($k==3)
		$dbMessageLogObj=$dbMessageLogObj3;
		
        $dbNameS=getActiveServerName($activeServerId,"slave");
        $dbS=$mysqlObj->connect($dbNameS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

	//PHOTO REQUEST
	for($i=0;$i<2;$i++)
	{
		if($i==0)
			$sql="SELECT A.PROFILEID AS PID,A.PROFILEID_REQ_BY AS PID2 FROM newjs.PHOTO_REQUEST A , test.$inactiveRecordTable B WHERE DATE<'$oldActivityOneYear' AND A.PROFILEID=B.PROFILEID";
		else
			$sql="SELECT A.PROFILEID_REQ_BY AS PID,A.PROFILEID AS PID2 FROM newjs.PHOTO_REQUEST A , test.$inactiveRecordTable B WHERE DATE<'$oldActivityOneYear' AND A.PROFILEID_REQ_BY=B.PROFILEID";
echo $sql."\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
		while($row=mysql_fetch_array($res))
		{
	checkForEndTime();
if($laveshPhoto++%1000==0)
	echo $laveshPhoto." - ";
			$col1=$row["PID"];
			$col2=$row["PID2"];

			if($i==0)
				$whereCondition=" AND PROFILEID='$col1' AND PROFILEID_REQ_BY='$col2' ";
			else
				$whereCondition=" AND PROFILEID='$col2'  AND PROFILEID_REQ_BY='$col1' ";

			unset($affectedDb);
			$myDbName=getProfileDatabaseConnectionName($col1,'',$mysqlObj);
			$myDb=$myDbArr[$myDbName];
			$affectedDb[0]=$myDb;

			$myDbName=getProfileDatabaseConnectionName($col2,'',$mysqlObj);
			$viewedDb=$myDbArr[$myDbName];
			if(!in_array($viewedDb,$affectedDb))
				$affectedDb[1]=$viewedDb;

			for($ll=0;$ll<count($affectedDb);$ll++)
                        {
				$dbM=$affectedDb[$ll];

				$sql_1="BEGIN";
				mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);

				$sql_i="REPLACE INTO newjs.{$delArchivePrefix}DELETED_PHOTO_REQUEST{$delArchiveSuffix} SELECT * FROM newjs.PHOTO_REQUEST WHERE DATE<'$oldActivityOneYear'";
				$sql_i.=$whereCondition;
				//echo $sql_i,"\n\n";
				mysql_query($sql_i,$dbM) or die(mysql_error($dbM).$sql_i);
		
				$sql_d="DELETE FROM newjs.PHOTO_REQUEST WHERE DATE<'$oldActivityOneYear'";
				$sql_d.=$whereCondition;
				mysql_query($sql_d,$dbM) or die(mysql_error($dbM).$sql_d);
			}
			for($ll=0;$ll<count($affectedDb);$ll++)
			{
				$dbM=$affectedDb[$ll];
				$sql_1="COMMIT";
				mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
			}
		}
	}
	//PHOTO REQUEST
//die(X);
	//HOROSCOPE REQUEST
	for($i=0;$i<2;$i++)
	{
		if($i==0)
			$sql="SELECT A.PROFILEID AS PID,A.PROFILEID_REQUEST_BY AS PID2 FROM newjs.HOROSCOPE_REQUEST A , test.$inactiveRecordTable B WHERE DATE<'$oldActivityOneYear' AND A.PROFILEID=B.PROFILEID";
		else
			$sql="SELECT A.PROFILEID_REQUEST_BY AS PID,A.PROFILEID AS PID2 FROM newjs.HOROSCOPE_REQUEST A , test.$inactiveRecordTable B WHERE DATE<'$oldActivityOneYear' AND A.PROFILEID_REQUEST_BY=B.PROFILEID";
echo $sql."\n\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
		while($row=mysql_fetch_array($res))
		{
	checkForEndTime();
if($laveshHoro++%1000==0)
	echo $laveshHoro." - ";
			$col1=$row["PID"];
			$col2=$row["PID2"];

                        if($i==0)
                                $whereCondition=" AND PROFILEID='$col1' AND PROFILEID_REQUEST_BY='$col2' ";
                        else
                                $whereCondition=" AND PROFILEID='$col2' AND PROFILEID_REQUEST_BY='$col1' ";

                        unset($affectedDb);
                        $myDbName=getProfileDatabaseConnectionName($col1,'',$mysqlObj);
			$myDb=$myDbArr[$myDbName];
                        $affectedDb[0]=$myDb;

                        $myDbName=getProfileDatabaseConnectionName($col2,'',$mysqlObj);
			$viewedDb=$myDbArr[$myDbName];
                        if(!in_array($viewedDb,$affectedDb))
                                $affectedDb[1]=$viewedDb;

                        for($ll=0;$ll<count($affectedDb);$ll++)
                        {
                                $dbM=$affectedDb[$ll];

                        	$sql_1="BEGIN";
	                        mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);

				$sql_i="REPLACE INTO newjs.{$delArchivePrefix}DELETED_HOROSCOPE_REQUEST{$delArchiveSuffix} SELECT * FROM newjs.HOROSCOPE_REQUEST WHERE DATE<'$oldActivityOneYear'";
				$sql_i.=$whereCondition;
				//echo $sql_i,"\n\n";
				mysql_query($sql_i,$dbM) or die(mysql_error($dbM).$sql_i);
	
				$sql_d="DELETE FROM newjs.HOROSCOPE_REQUEST WHERE DATE<'$oldActivityOneYear'";
				$sql_d.=$whereCondition;
				mysql_query($sql_d,$dbM) or die(mysql_error($dbM).$sql_d);
			}

                        for($ll=0;$ll<count($affectedDb);$ll++)
                        {
                                $dbM=$affectedDb[$ll];
                                $sql_1="COMMIT";
                                mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
                        }
		}
	}
	//HOROSCOPE REQUEST
echo "\n\n\n";
	//CONTACTS -- EOI -- MESSAGE_LOG -- MESSAGES
	for($i=0;$i<1;$i++)
	{
		$dbNameS=getActiveServerName($activeServerId,"slave");
		$dbS=$mysqlObj->connect($dbNameS);
		mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

                $dbNameM=getActiveServerName($activeServerId,"master");
				$dbmaster=$myDbArr[$dbNameM];
                mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbmaster);
//if($i==0)
//			$sql="SELECT SENDER,RECEIVER FROM newjs.CONTACTS WHERE TIME<'$inactivityDate' ";
//		elseif($i==1)
//			$sql="SELECT A.SENDER,A.RECEIVER FROM newjs.CONTACTS A , test.$inactiveRecordTable B WHERE TIME<'$inactivityDate' AND A.SENDER=B.PROFILEID";
//		elseif($i==2)
//			$sql="SELECT A.SENDER,A.RECEIVER FROM newjs.CONTACTS A , test.$inactiveRecordTable B WHERE TIME<'$inactivityDate' AND A.RECEIVER=B.PROFILEID";
//    
echo "Contacts :\n";
			$sql="SELECT A.SENDER,A.RECEIVER FROM newjs.CONTACTS A , test.$inactiveRecordTable B WHERE A.SENDER=B.PROFILEID OR A.RECEIVER=B.PROFILEID";

			//$sql="SELECT A.SENDER,A.RECEIVER FROM newjs.CONTACTS A , test.$inactiveRecordTable B WHERE TIME<'$inactivityDate' AND A.RECEIVER=B.PROFILEID";
echo $sql."\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
    echo mysql_num_rows($res),"\n\n";
    $icount = 0;
		while($row=mysql_fetch_array($res))
		{
      ++$icount;  
      checkForEndTime();
      if($laveshC++%1000==0)
        echo $laveshC." - ";
      $col1=$row["SENDER"];
      $col2=$row["RECEIVER"];

			unset($col_id);
			unset($col_str);

//                        $sqlAdded="SELECT COUNT(*) as CNT FROM newjs.CONTACTS  WHERE SENDER='$col1' and RECEIVER='$col2' AND TIME<'$inactivityDate'";
//                        $resAdded=mysql_query($sqlAdded,$dbmaster) or die(mysql_error($dbmaster).$sqlAdded);
//                        $rowAdded=mysql_fetch_array($resAdded);
//                        $cntAdded=$rowAdded["CNT"];

      if(1 || $cntAdded>0)
			{
				
				$resMsgLog=$dbMessageLogObj->getMessageLogHousekeeping($col1,$col2);
				//$sql_1="SELECT ID FROM newjs.MESSAGE_LOG WHERE SENDER IN ('$col1','$col2') AND RECEIVER IN ('$col1','$col2')";
				//$res_1=mysql_query($sql_1,$dbS) or die(mysql_error($dbS).$sql_1);
				//while($row_1=mysql_fetch_array($res_1))
				foreach($resMsgLog as $k=>$row_1)
				{
					$col_id[]=$row_1["ID"];
				}

        unset($affectedDb);
        $myDbName = getProfileDatabaseConnectionName($col1, '', $mysqlObj);
        $myDb = $myDbArr[$myDbName];
        $affectedDb[0] = $myDb;

        $myDbName = getProfileDatabaseConnectionName($col2, '', $mysqlObj);
        $viewedDb = $myDbArr[$myDbName];
        if (!in_array($viewedDb, $affectedDb))
          $affectedDb[1] = $viewedDb;

        $ProfileId1shard=JsDbSharding::getShardNo($col1);
        $ProfileId2shard=JsDbSharding::getShardNo($col2);
        $dbMessageLogObj1=new NEWJS_MESSAGE_LOG($ProfileId1shard);
        $dbMessageLogObj2=new NEWJS_MESSAGE_LOG($ProfileId2shard);
        $dbDeletedMessagesObj1=new NEWJS_DELETED_MESSAGES($ProfileId1shard);
        $dbDeletedMessagesObj2=new NEWJS_DELETED_MESSAGES($ProfileId2shard);
        $dbMessageObj1=new NEWJS_MESSAGES($ProfileId1shard);
        $dbMessageObj2=new NEWJS_MESSAGES($ProfileId2shard);
        $dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG($ProfileId1shard);
        $dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG($ProfileId2shard);

				for($ll=0;$ll<count($affectedDb);$ll++)
				{$shard=$ll+1;
					if($shard==1)
					{
						$dbMessageLogObj=$dbMessageLogObj1;
						$dbMessageObj=$dbMessageObj1;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj1;
						$dbDeletedMessageLogObj=$dbDeletedMessageLogObj1;
						
					}
					if($shard==2)
					{
						$dbMessageLogObj=$dbMessageLogObj2;
						$dbMessageObj=$dbMessageObj2;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj2;
						$dbDeletedMessageLogObj=$dbDeletedMessageLogObj2;
					}
					$dbM=$affectedDb[$ll];	
					$dbMessageLogObj->startTransaction();
			                $sql_1="BEGIN";
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
	
					if(is_array($col_id))
					{
						$col_str=implode("','",$col_id);
						$dbDeletedMessageLogObj->insertMessageLogHousekeeping($col_id, $delArchivePrefix, $delArchiveSuffix);
						//$sql_1="REPLACE INTO newjs.DELETED_MESSAGE_LOG SELECT * FROM newjs.MESSAGE_LOG WHERE ID IN ('$col_str')";
						//mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
						$dbMessageLogObj->deleteMultipleLogForSingleProfile($col_id);
						//$sql_1="DELETE FROM newjs.MESSAGE_LOG WHERE ID IN ('$col_str')"; 
						//mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
						$dbDeletedMessagesObj->insertMessageLogHousekeeping($col_id, $delArchivePrefix, $delArchiveSuffix);
						//$sql_1="REPLACE INTO newjs.DELETED_MESSAGES SELECT * FROM newjs.MESSAGES WHERE ID IN ('$col_str')";
						//mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
						$dbMessageObj->deleteMessages($col_id);
						//$sql_1="DELETE FROM newjs.MESSAGES WHERE ID IN ('$col_str')"; 
						//mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
					}
					$sql_1="REPLACE INTO newjs.{$delArchivePrefix}DELETED_PROFILE_CONTACTS{$delArchiveSuffix} SELECT * FROM newjs.CONTACTS WHERE SENDER='$col1' and RECEIVER='$col2'";
					//echo $sql_1,"\n\n";
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);

					$sql_1="DELETE FROM newjs.CONTACTS WHERE SENDER='$col1' and RECEIVER='$col2'";
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);

					$sql_1="REPLACE INTO newjs.{$delArchivePrefix}DELETED_EOI_VIEWED_LOG{$delArchiveSuffix} SELECT * FROM newjs.EOI_VIEWED_LOG WHERE VIEWER IN ('$col1','$col2') AND VIEWED IN  ('$col1','$col2')";
					//echo $sql_1,"\n\n";
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
			
					$sql_1="DELETE FROM newjs.EOI_VIEWED_LOG WHERE VIEWER IN ('$col1','$col2') AND VIEWED IN  ('$col1','$col2')";
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
				}
                                for($ll=0;$ll<count($affectedDb);$ll++)
                                {
									$sharding=$ll+1;
									if($sharding==1)
										$dbMessageLogObj=$dbMessageLogObj1;
									if($sharding==2)
										$dbMessageLogObj=$dbMessageLogObj2;
									if($sharding==3)
										$dbMessageLogObj=$dbMessageLogObj3;
                                        $dbM=$affectedDb[$ll];
                                        $sql_1="COMMIT";
                                        $dbMessageLogObj->commitTransaction();
                                        mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
					checkForEndTime();
                                }
			}
		}
    echo "Total loop run is :",$icount,"\n\n\n";
	}
	//CONTACTS -- EOI -- MESSAGE_LOG -- MESSAGES
}
$timestamp=mktime(0, 0, 0, date("m")- $timeDiff  , date("d"), date("Y"));
$inactivityDateViewLog=date("Y-m-d",$timestamp);
//VIEW LOG
$db_211=$mysqlObj->connect("viewLogRep");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);

//$db_211_slave=connect_211_slave();
$db_211_slave = $mysqlObj->connect("211Slave");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211_slave);

echo "\n\n";
echo $sql="SELECT VIEWER,VIEWED FROM newjs.VIEW_LOG  WHERE DATE<'$inactivityDateViewLog'";
echo "\n\n";
$res=mysql_query($sql,$db_211_slave) or die(mysql_error($db_211_slave).$sql);
while($row=mysql_fetch_assoc($res))
{
	checkForEndTime();
if($laveshrawat++%10000==0)
{
        echo $laveshrawat."-";
        if($laveshrawat%100000==1)
                echo "\n";
}

        $viewer=$row["VIEWER"];
        $viewed=$row["VIEWED"];
//TODO
        $sqlI="REPLACE INTO newjs.{$delArchivePrefix}DELETED_VIEW_LOG{$delArchiveSuffix} SELECT * FROM newjs.VIEW_LOG WHERE VIEWER='$viewer' and VIEWED='$viewed'";
	//echo $sqlI,"\n\n";
        mysql_query($sqlI,$db_211) or die(mysql_error($db_211).$sqlI);


        $sqlD="DELETE FROM newjs.VIEW_LOG WHERE VIEWER='$viewer' and VIEWED='$viewed'";
        mysql_query($sqlD,$db_211) or die(mysql_error($db_211).$sqlD);

}
echo "\n\n";
echo "done";
//VIEW LOG

function getDumpCommandConnectionDetails($case,$table='',$dbname='',$dump)
{
	if($case=='S')
	{
		$h=MysqlDbConstants::$misSlave[HOST];
		$p=MysqlDbConstants::$misSlave[PASS];
		$u=MysqlDbConstants::$misSlave[USER];
		if(strstr($h,'sock'))
		{
			$x=explode(":",$h);
			$sipArr[0]=$x[0];
			$s=$x[1];
		}
		else
			$sipArr=explode(":",MysqlDbConstants::$misSlave[HOST]);
	}
	elseif($case=='SS1')
	{
		$h=MysqlDbConstants::$shard1Slave[HOST];
		$p=MysqlDbConstants::$shard1Slave[PASS];
		$u=MysqlDbConstants::$shard1Slave[USER];
		if(strstr($h,'sock'))
		{
			$x=explode(":",$h);
			$sipArr[0]=$x[0];
			$s=$x[1];
		}
		else{
			
			$sipArr[0]=MysqlDbConstants::$shard1Slave[HOST];
			$sipArr[1]=MysqlDbConstants::$shard1Slave[PORT];
			
		}
	}
        elseif($case=='SS2')
        {
                $h=MysqlDbConstants::$shard2Slave[HOST];
                $p=MysqlDbConstants::$shard2Slave[PASS];
                $u=MysqlDbConstants::$shard2Slave[USER];
		if(strstr($h,'sock'))
		{
			$x=explode(":",$h);
			$sipArr[0]=$x[0];
			$s=$x[1];
		}
		else
		{

                        $sipArr[0]=MysqlDbConstants::$shard2Slave[HOST];
                        $sipArr[1]=MysqlDbConstants::$shard2Slave[PORT];

                }
	}
        elseif($case=='SS3')
        {
                $h=MysqlDbConstants::$shard3Slave[HOST];
                $p=MysqlDbConstants::$shard3Slave[PASS];
                $u=MysqlDbConstants::$shard3Slave[USER];
		if(strstr($h,'sock'))
		{
			$x=explode(":",$h);
			$sipArr[0]=$x[0];
			$s=$x[1];
		}
		else{

                        $sipArr[0]=MysqlDbConstants::$shard3Slave[HOST];
                        $sipArr[1]=MysqlDbConstants::$shard3Slave[PORT];

                }

        }

	
	if($dump=='target')
		$dump=MysqlDbConstants::$mySqlPath;
	else
		$dump=MysqlDbConstants::$mySqlDumpPath;

	$str=$dump.' -t -u '.$u.' -p'.$p.' -h'.$sipArr[0];
	if($s)	
		$str.=' -S '.$s;
	if($sipArr[1])
		$str.=' -P '.$sipArr[1];
	if($dbname)
		$str.=' '.$dbname;
	if($table)
		$str.=' '.$table;
	return $str;
}

function checkForEndTime()
{
	$orgTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Calcutta");

	 if(in_array(date('H'),array("06","07","08","09")))
	 {
		date_default_timezone_set($orgTZ);
		successfullDie();		
            return 1;
         }
		

	date_default_timezone_set($orgTZ);
}

?>
