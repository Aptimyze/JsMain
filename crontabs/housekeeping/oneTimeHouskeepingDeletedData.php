<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once("housekeepingConfig.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);
$mysqlObj=new Mysql;

$todayTimestamp=mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
$todayDate=date("Y-m-d",$todayTimestamp);
if(date('N', strtotime($todayDate)) >= 6)
{
	successfullDie("weekend");die;
}

$totalScript=4;
$currentScript=$argv[1];

//Conditonal Variables
$photoRequestArchive=0;
$horoscopeArchive=0;
$contactsMessageArchive=1;
$viewLogArchive=0;
$bookmarksArchive=0;
$archiveYears=6;

//Archive Date Logic
$timestamp=mktime(0, 0, 0, date("m")  , date("d"), date("Y")-$archiveYears);
$archiveDate=date("Y-m-d",$timestamp);

date_default_timezone_set('Asia/Calcutta');
		

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{       $myDbName=getActiveServerName($activeServerId);       $myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");       mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbArr[$myDbName]);
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
	$dbNameS=getActiveServerName($activeServerId,"master");
	$dbM=$mysqlObj->connect($dbNameS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);
	
	$dbNameS=getActiveServerName($activeServerId,"slave");
	$dbS=$mysqlObj->connect($dbNameS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

	
	//PHOTO REQUEST
	if($photoRequestArchive)
	{
		
		echo "\n\n";
		echo "PHOTO REQUEST";
		echo "\n\n";
		echo $sql="SELECT A.PROFILEID_REQ_BY AS PID,A.PROFILEID AS PID2 FROM newjs.DELETED_PHOTO_REQUEST A  WHERE DATE<'$archiveDate'";
		echo $sql."\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
		while($row=mysql_fetch_array($res))
		{
			if($NiteshPhotoRequest++%1000==0)
				echo $NiteshPhotoRequest." - ";
			$col1=$row["PID"];
			$col2=$row["PID2"];

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
				$dbMShard=$affectedDb[$ll];

				echo $sql_1="BEGIN";
				mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
				echo "\n\n";
				$sql_i="INSERT INTO newjs.PHOTO_REQUEST_ARCHIVE SELECT * FROM newjs.DELETED_PHOTO_REQUEST WHERE DATE<'$archiveDate'";
				echo $sql_i.=$whereCondition;
				mysql_query($sql_i,$dbMShard) or die(mysql_error($dbMShard).$sql_i);
				echo "\n\n";
				$sql_d="DELETE FROM newjs.DELETED_PHOTO_REQUEST WHERE DATE<'$archiveDate'";
				echo $sql_d.=$whereCondition;
				mysql_query($sql_d,$dbMShard) or die(mysql_error($dbMShard).$sql_d);
			}
			for($ll=0;$ll<count($affectedDb);$ll++)
			{
				echo "\n\n";
				$dbMShard=$affectedDb[$ll];
				echo $sql_1="COMMIT";
				echo "\n\n";
				mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
			}
		}
	}
	
	//HOROSCOPE REQUEST
	if($horoscopeArchive){
		
		echo "\n\n";
		echo "HOROSCOPE REQUEST";
		echo "\n\n";
		echo $sql="SELECT A.PROFILEID_REQUEST_BY AS PID,A.PROFILEID AS PID2 FROM newjs.DELETED_HOROSCOPE_REQUEST A  WHERE DATE<'$archiveDate'";
		echo $sql."\n\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
		while($row=mysql_fetch_array($res))
		{
           if($NiteshPhotoRequest++%1000==0)
				echo $NiteshPhotoRequest." - ";
			$col1=$row["PID"];
			$col2=$row["PID2"];

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
				$dbMShard=$affectedDb[$ll];
				echo "\n\n";
				echo $sql_1="BEGIN";
				mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
				echo "\n\n";
				$sql_i="INSERT INTO newjs.HOROSCOPE_REQUEST_ARCHIVE SELECT * FROM newjs.DELETED_HOROSCOPE_REQUEST WHERE DATE<'$archiveDate'";
				echo $sql_i.=$whereCondition;
				mysql_query($sql_i,$dbMShard) or die(mysql_error($dbMShard).$sql_i);
				echo "\n\n";
				$sql_d="DELETE FROM newjs.DELETED_HOROSCOPE_REQUEST WHERE DATE<'$archiveDate'";
				echo $sql_d.=$whereCondition;
				mysql_query($sql_d,$dbMShard) or die(mysql_error($dbMShard).$sql_d);
			}
			
			for($ll=0;$ll<count($affectedDb);$ll++)
			{
				echo "\n\n";
				$dbMShard=$affectedDb[$ll];
				echo $sql_1="COMMIT";
				echo "\n\n";
				mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
			}
		}
		
	}
	
	
	//CONTACTS ARCHIVING 
	//CONTACTS -- EOI -- MESSAGE_LOG -- MESSAGES
	if($contactsMessageArchive){
		echo "\n\n";
		echo "CONTACTS ARCHIVING";
		echo "\n\n";
		echo $sql="SELECT A.SENDER,A.RECEIVER FROM newjs.DELETED_PROFILE_CONTACTS A where TIME<'$archiveDate' AND A.SENDER%$totalScript=$currentScript";
		echo $sql."\n\n";
		$res=mysql_query($sql,$dbS) or die(mysql_error($dbS).$sql);
		while($row=mysql_fetch_array($res))
		{
			if($NiteshContacts++%1000==0)
				echo $NiteshContacts." - ";
			if( date('H') >=19 || date('H') <=6)
			{
				successfullDie("Stop On Peak Time");die;
			}
			$col1=$row["SENDER"];
			$col2=$row["RECEIVER"];
			unset($col_id);
			unset($col_str);
			//echo $sql."\n\n";
			$sqlAdded="SELECT COUNT(*) as CNT FROM newjs.DELETED_PROFILE_CONTACTS  WHERE SENDER='$col1' and RECEIVER='$col2' AND TIME<'$archiveDate'";
			$resAdded=mysql_query($sqlAdded,$dbM) or die(mysql_error($dbM).$sqlAdded);
			$rowAdded=mysql_fetch_array($resAdded);
			$cntAdded=$rowAdded["CNT"];
			if($cntAdded>0)
			{
				//echo $sql."\n\n";
				$res=$dbMessageLogObj->getMessageLogHousekeeping($col1,$col2);
				//$sql_1="SELECT ID FROM newjs.DELETED_MESSAGE_LOG WHERE SENDER IN ('$col1','$col2') AND RECEIVER IN ('$col1','$col2')";
				//$res_1=mysql_query($sql_1,$dbS) or die(mysql_error($dbS).$sql_1);
				//while($row_1=mysql_fetch_array($res_1))
				foreach($res as $k=>$row_1)
				{
					$col_id[]=$row_1["ID"];
				}
				unset($affectedDb);
				$myDbName=getProfileDatabaseConnectionName($col1,'',$mysqlObj);
				$myDb=$myDbArr[$myDbName];
				$affectedDb[0]=$myDb;

				$myDbName=getProfileDatabaseConnectionName($col2,'',$mysqlObj);
				$viewedDb=$myDbArr[$myDbName];
				if(!in_array($viewedDb,$affectedDb))
				$affectedDb[1]=$viewedDb;
$ProfileId1shard=JsDbSharding::getShardNo($col1);
$ProfileId2shard=JsDbSharding::getShardNo($col2);
$dbMessageLogObj1=new NEWJS_MESSAGE_LOG_ARCHIVE($ProfileId1shard);
$dbMessageLogObj2=new NEWJS_MESSAGE_LOG_ARCHIVE($ProfileId2shard);
$dbDeletedMessagesObj1=new NEWJS_DELETED_MESSAGES($ProfileId1shard);
$dbDeletedMessagesObj2=new NEWJS_DELETED_MESSAGES($ProfileId2shard);
$dbDeletedMessagesObj1=new NEWJS_MESSAGES_ARCHIVE($ProfileId1shard);
$dbMessageObj2=new NEWJS_MESSAGES_ARCHIVE($ProfileId2shard);
$dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG($ProfileId1shard);
$dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG($ProfileId2shard);
				for($ll=0;$ll<count($affectedDb);$ll++)
				{
					$shard=$ll+1;
					if($shard==1)
					{
						$dbMessageLogObj=$dbMessageLogObj1;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj1;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj1;
						$dbDeletedMessageLogObj=$dbDeletedMessageLogObj1;
					}
					if($shard==2)
					{
						$dbMessageLogObj=$dbMessageLogObj2;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj2;
						$dbDeletedMessagesObj=$dbDeletedMessagesObj2;
						$dbDeletedMessageLogObj=$dbDeletedMessageLogObj2;
					}
					
					$dbMShard=$affectedDb[$ll];	
					//echo $sql."\n\n";
					$sql_1="BEGIN";
					$dbMessageLogObj->startTransaction();
					mysql_query($sql_1,$dbM) or die(mysql_error($dbM).$sql_1);
	
					if(is_array($col_id))
					{
						$col_str=implode("','",$col_id);
						//echo $sql."\n\n";
						$dbMessageLogObj->insertMessageLogHousekeeping($col_id);
						//$sql_1="INSERT INTO newjs.MESSAGE_LOG_ARCHIVE SELECT * FROM newjs.DELETED_MESSAGE_LOG WHERE ID IN ('$col_str')";
						//mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
						//echo $sql."\n\n";
						$dbDeletedMessageLogObj->deleteMultipleLogForSingleProfile($col_id);
						//$sql_1="DELETE FROM newjs.DELETED_MESSAGE_LOG WHERE ID IN ('$col_str')"; 
						//mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);

						//echo $sql."\n\n";
						$dbMessageObj->insertMessageLogHousekeeping($col_id);
						//$sql_1="INSERT INTO newjs.MESSAGES_ARCHIVE SELECT * FROM newjs.DELETED_MESSAGES WHERE ID IN ('$col_str')";
						//mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
						//echo $sql."\n\n";
						$dbDeletedMessagesObj->deleteMessages($col_id);
						//$sql_1="DELETE FROM newjs.DELETED_MESSAGES WHERE ID IN ('$col_str')"; 
						//mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
					}
					//echo $sql."\n\n";
					$sql_1="INSERT INTO newjs.PROFILE_CONTACTS_ARCHIVE SELECT * FROM newjs.DELETED_PROFILE_CONTACTS WHERE SENDER='$col1' and RECEIVER='$col2'";
					mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
					//echo $sql."\n\n";
					$sql_1="DELETE FROM newjs.DELETED_PROFILE_CONTACTS WHERE SENDER='$col1' and RECEIVER='$col2'";
					mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
					//echo $sql."\n\n";
					$sql_1="INSERT INTO newjs.EOI_VIEWED_LOG_ARCHIVE SELECT * FROM newjs.DELETED_EOI_VIEWED_LOG WHERE VIEWER IN ('$col1','$col2') AND VIEWED IN  ('$col1','$col2')";
					mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
					//echo $sql."\n\n";
					$sql_1="DELETE FROM newjs.DELETED_EOI_VIEWED_LOG WHERE VIEWER IN ('$col1','$col2') AND VIEWED IN  ('$col1','$col2')";
					mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
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
						
						$dbMShard=$affectedDb[$ll];
						//echo $sql."\n\n";
						$sql_1="COMMIT";
						$dbMessageLogObj->commitTransaction();
						mysql_query($sql_1,$dbMShard) or die(mysql_error($dbMShard).$sql_1);
				}
			}
		}	
		send_email("nitesh.s@jeevansathi.com","","completed Contacts Cron for specific date","noreply@jeevansathi.com","","","","","");
		successfullDie("completed");
	}
	
}


//VIEW_LOG
if($viewLogArchive){
	//VIEW LOG
	$db_211=connect_211();
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);

	//$db_211_slave=connect_211_slave();
	$db_211_slave = $mysqlObj->connect("211Slave");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211_slave);

	echo "\n\n";
	echo "VIEW LOG";
	echo "\n\n";
	echo $sql="SELECT VIEWER,VIEWED FROM newjs.DELETED_VIEW_LOG  WHERE DATE<'$archiveDate'";
	echo "\n\n";
	$res=mysql_query($sql,$db_211_slave) or die(mysql_error($db_211_slave).$sql);
	while($row=mysql_fetch_assoc($res))
	{
			$viewer=$row["VIEWER"];
			$viewed=$row["VIEWED"];

			$sqlI="INSERT INTO newjs.VIEW_LOG_ARCHIVE SELECT * FROM newjs.DELETED_VIEW_LOG WHERE VIEWER='$viewer' and VIEWED='$viewed'";
			mysql_query($sqlI,$db_211) or die(mysql_error($db_211).$sqlI);


			$sqlD="DELETE FROM newjs.DELETED_VIEW_LOG WHERE VIEWER='$viewer' and VIEWED='$viewed'";
			mysql_query($sqlD,$db_211) or die(mysql_error($db_211).$sqlD);

	}
	echo "\n\n";
	echo "done";
	//VIEW LOG
}
//VIEW_LOG

//BOOKMARKS
if($bookmarksArchive){
	for($i=0;$i<2;$i++)
	{
		echo "\n\n";
		echo "VIEW LOG";
		echo "\n\n";
		echo $sql="SELECT DISTINCT(A.BOOKMARKEE) AS PID FROM newjs.DELETED_BOOKMARKS A WHERE BKDATE<'$archiveDate'";
		echo "(".$i.")";
		echo $sql."\n\n";
		$res=mysql_query($sql,$dbSlave) or die(mysql_error($dbSlave).$sql);
		while($row=mysql_fetch_array($res))
		{
			$col1=$row["PID"];
			$whereCondition=" AND BOOKMARKEE='$col1'";

			$sql_1="BEGIN";
			mysql_query($sql_1,$db) or die(mysql_error($db).$sql_1);
		
			$sql_i="INSERT INTO newjs.BOOKMARKS_ARCHIVE SELECT * FROM newjs.DELETED_BOOKMARKS WHERE BKDATE<'$archiveDate'";
			$sql_i.=$whereCondition;
			mysql_query($sql_i,$db) or die(mysql_error($db).$sql_i);

			$sql_d="DELETE FROM newjs.DELETED_BOOKMARKS WHERE BKDATE<'$archiveDate'";
			$sql_d.=$whereCondition;
			mysql_query($sql_d,$db) or die(mysql_error($db).$sql_d);

			$sql_1="COMMIT";
			mysql_query($sql_1,$db) or die(mysql_error($db).$sql_1);
		
			unset($whereCondition);
		}
	}
}
//BOOKMARKS
