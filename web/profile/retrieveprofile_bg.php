<?php
/** 
* @author Lavesh Rawat 
* @copyright Copyright 2010, Infoedge India Ltd.
*/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
$dirname=dirname(__FILE__);
chdir($dirname);
include('connect.inc');
include($_SERVER["DOCUMENT_ROOT"].'/../crontabs/housekeeping/housekeepingConfig.php');
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

/** invalid or no profileid is passed **/
if(!$argv[1])
{
        $logError=1;
}
else
{
$profileid = $argv[1];
if(!$profileid)
        $logError=2;
if(!is_numeric($profileid))
        $logError=3;
}
$mainDb = connect_db();
if($logError)
{
        $date=date("Y-m-d");
        $sql="Update MIS.DELETE_RETRIEVE_INVALID_PROFILEID set COUNT=COUNT+1 where Date='$date' AND ERROR='$logError' AND  DELETE_RETRIEVE='R'";
        mysql_query($sql,$mainDb);
        if(mysql_affected_rows()==0)
        {
                $sql="Insert into MIS.DELETE_RETRIEVE_INVALID_PROFILEID(DATE,COUNT,ERROR,DELETE_RETRIEVE) values ('$date','1','$logError','R')";
                mysql_query($sql,$mainDb);
        }
        exit;

}
/** invalid or no profileid is passed **/

//Check If Profile was deleted within last 3 months, if yes then profile is eligible for retrieval process.
$eligibleDate=date("Y-m-d",mktime(0, 0, 0, date("m") - 3  , date("d"), date("Y")));
$sql="SELECT * newjs.NEW_DELETED_PROFILE_LOG where PROFILEID = '$profileid' and DATE >= '$eligibleDate' ORDER BY DATE DESC";
$result=mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);


$bIsEligible = mysql_num_rows($result) ? true : false;

if(false === $bIsEligible) {
    //TODO : Add a debug msg or mail
    exit;
}
$row = mysql_fetch_array($result);
//Check for Tables
//If Housekeeping is not executed for this profile
$liveDate = "2017-03-20 00:00:00";
$bInHouseKeeping = false;
if( $liveDate < $row["DATE"] || $row["HOUSEKEEPING_DONE"] == 'Y') {
  $bInHouseKeeping = true;
}
/* duplication_fields_insertion() call inserted by Reshu Rajput, here "invalid_dup_fields" is any dummy string
* to be passed to use the older version of the function with least modifications
* This call is made to do the insertion in duplicates_check_fields table when retrieve account is there
*/
duplication_fields_insertion("invalid_dup_fields",$profileid);


/* Deleting from DELETED_PROFILE_LOG and adding entry into RETRIVE_PROFILE_LOG(to check if cron is executing properly)*/ 
//$mainDb = connect_db();
//$sql="DELETE FROM newjs.NEW_DELETED_PROFILE_LOG WHERE PROFILEID=$profileid";
//mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);

$today = date('Y-m-d');
$sql="INSERT IGNORE INTO newjs.RETRIEVE_PROFILE_LOG(PROFILEID,DATE) VALUES('$profileid','$today')";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);

//if(mysql_affected_rows() == 0) {//if row already exist then replace data
//  $sql="REPLACE INTO newjs.RETRIEVE_PROFILE_LOG(PROFILEID,DATE,SHARD1,SHARD2,SHARD3,MAINDB) VALUES('$profileid',NOW(),'0','0','0','0')";  
//  mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
//}

/* Deleting from DELETED_PROFILE_LOG and adding entry into RETRIVE_PROFILE_LOG(to check if cron is executing properly)*/ 


$mysqlObj=new Mysql;
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbarr[$myDbName]);
}


$messageShardCount=0;
$dbMessageLogObj1=new NEWJS_MESSAGE_LOG("shard1_master");
$dbMessageLogObj2=new NEWJS_MESSAGE_LOG("shard2_master");
$dbMessageLogObj3=new NEWJS_MESSAGE_LOG("shard3_master");

if($bInHouseKeeping) {
  $dbDeletedMessagesObj1=new NEWJS_DELETED_MESSAGES("shard1_master");
  $dbDeletedMessagesObj2=new NEWJS_DELETED_MESSAGES("shard2_master");
  $dbDeletedMessagesObj3=new NEWJS_DELETED_MESSAGES("shard3_master");
} else {
  $dbDeletedMessagesObj1=new NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET("shard1_master");
  $dbDeletedMessagesObj2=new NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET("shard2_master");
  $dbDeletedMessagesObj3=new NEWJS_DELETED_MESSAGES_ELIGIBLE_FOR_RET("shard3_master");
}

$dbMessageObj1=new NEWJS_MESSAGES("shard1_master");
$dbMessageObj2=new NEWJS_MESSAGES("shard2_master");
$dbMessageObj3=new NEWJS_MESSAGES("shard3_master");

if($bInHouseKeeping) {
  $dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG("shard1_master");
  $dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG("shard2_master");
  $dbDeletedMessageLogObj3=new NEWJS_DELETED_MESSAGE_LOG("shard3_master");
} else {
  $dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET("shard1_master");
  $dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET("shard2_master");
  $dbDeletedMessageLogObj3=new NEWJS_DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET("shard3_master");
}


/*** executing quries on associated shards of user to make sure only active profile is retreived ***/
$myDbname=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
$horoscopeStr=retreiveOnlyActiveProfiles('DELETED_HOROSCOPE_REQUEST_ELIGIBLE_FOR_RET',"PROFILEID","PROFILEID_REQUEST_BY",$myDbarr[$myDbname],$mainDb,$profileid,"newjs",$bInHouseKeeping);
$photoStr=retreiveOnlyActiveProfiles('DELETED_PHOTO_REQUEST_ELIGIBLE_FOR_RET',"PROFILEID","PROFILEID_REQ_BY",$myDbarr[$myDbname],$mainDb,$profileid,"newjs",$bInHouseKeeping);
$contactsStr=retreiveOnlyActiveProfiles('DELETED_PROFILE_CONTACTS_ELIGIBLE_FOR_RET',"SENDER","RECEIVER",$myDbarr[$myDbname],$mainDb,$profileid,"newjs",$bInHouseKeeping);
//$messagelogStr=retreiveOnlyActiveProfiles('DELETED_MESSAGE_LOG',"SENDER","RECEIVER",$myDbarr[$myDbname],$mainDb,$profileid);
$messagelogStr=$contactsStr;
$eoiviewlogStr=$contactsStr;
/*** executing quries on associated shards of user to make sure only active profile is retreived ***/

/****  Transaction for all 3 shards started here.We will commit all three shards together. ****/
$i=1;
if(count($myDbarr))
{
        foreach($myDbarr as $key=>$value)
        {
			if($i==1)
		{
			$dbMessageLogObj=$dbMessageLogObj1;
			$dbDeletedMessagesObj=$dbDeletedMessagesObj1;
			$dbMessageObj=$dbMessageObj1;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj1;
		}
		if($i==2)
		{
			$dbMessageLogObj=$dbMessageLogObj2;
			$dbDeletedMessagesObj=$dbDeletedMessagesObj2;
			$dbMessageObj=$dbMessageObj2;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj2;
		}
		if($i==3)
		{
			$dbMessageLogObj=$dbMessageLogObj3;
			$dbDeletedMessagesObj=$dbDeletedMessagesObj3;
			$dbMessageObj=$dbMessageObj3;
			$dbDeletedMessageLogObj=$dbDeletedMessageLogObj3;
		}
			
                $myDb=$myDbarr[$key];
				$dbMessageLogObj->startTransaction();
				$i++;
                $sql="BEGIN";
                mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
    
		retreiveFromTables('DELETED_HOROSCOPE_REQUEST_ELIGIBLE_FOR_RET','HOROSCOPE_REQUEST',"PROFILEID","PROFILEID_REQUEST_BY",$myDb,$profileid,$horoscopeStr,"newjs","","","","",$bInHouseKeeping);
		retreiveFromTables('DELETED_PHOTO_REQUEST_ELIGIBLE_FOR_RET','PHOTO_REQUEST',"PROFILEID","PROFILEID_REQ_BY",$myDb,$profileid,$photoStr,"newjs","","","","",$bInHouseKeeping);
		retreiveFromTables('DELETED_MESSAGE_LOG_ELIGIBLE_FOR_RET','MESSAGE_LOG',"RECEIVER","SENDER",$myDb,$profileid,$messagelogStr,'',$dbMessageLogObj,$dbDeletedMessagesObj,$dbMessageObj,$dbDeletedMessageLogObj,$bInHouseKeeping);
		retreiveFromTables('DELETED_PROFILE_CONTACTS_ELIGIBLE_FOR_RET','CONTACTS',"SENDER","RECEIVER",$myDb,$profileid,$contactsStr,"newjs","","","","",$bInHouseKeeping);
		retreiveFromTables('DELETED_EOI_VIEWED_LOG_ELIGIBLE_FOR_RET','EOI_VIEWED_LOG',"VIEWER","VIEWED",$myDb,$profileid,$eoiviewlogStr,"newjs","","","","",$bInHouseKeeping);
    }
    }
/****  Transaction for all 3 shards started here.We will commit all three shards together. ****/

$mainDb = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$mainDb);

/*** executing quries on masterdb to make sure only active profile is retreived ***/
$bookmarkStr=retreiveOnlyActiveProfiles('DELETED_BOOKMARKS_ELIGIBLE_FOR_RET',"BOOKMARKER","BOOKMARKEE",$mainDb,$mainDb,$profileid,"",$bInHouseKeeping);
$ignoreStr=retreiveOnlyActiveProfiles('DELETED_IGNORE_PROFILE_ELIGIBLE_FOR_RET',"PROFILEID","IGNORED_PROFILEID",$mainDb,$mainDb,$profileid,"",$bInHouseKeeping);
$matcheStr=retreiveOnlyActiveProfiles('DELETED_OFFLINE_MATCHES_ELIGIBLE_FOR_RET',"MATCH_ID","PROFILEID",$mainDb,$mainDb,$profileid,'jsadmin',$bInHouseKeeping);
$nudgeStr=retreiveOnlyActiveProfiles('DELETED_OFFLINE_NUDGE_LOG_ELIGIBLE_FOR_RET',"SENDER","RECEIVER",$mainDb,$mainDb,$profileid,'jsadmin',$bInHouseKeeping);
$viewContactStr=retreiveOnlyActiveProfiles('DELETED_VIEW_CONTACTS_LOG_ELIGIBLE_FOR_RET',"VIEWER","VIEWED",$mainDb,$mainDb,$profileid,'jsadmin',$bInHouseKeeping);
/*** executing quries on masterdb to make sure only active profile is retreived ***/

/****  Transaction for master tables(innodb ones only) started here . ****/
$sql="BEGIN";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
retreiveFromTables('DELETED_BOOKMARKS_ELIGIBLE_FOR_RET','BOOKMARKS',"BOOKMARKER","BOOKMARKEE",$mainDb,$profileid,$bookmarkStr,"newjs","","","","",$bInHouseKeeping);
retreiveFromTables('DELETED_IGNORE_PROFILE_ELIGIBLE_FOR_RET','IGNORE_PROFILE',"PROFILEID","IGNORED_PROFILEID",$mainDb,$profileid,$ignoreStr,"newjs","","","","",$bInHouseKeeping);
retreiveFromTables('DELETED_OFFLINE_MATCHES_ELIGIBLE_FOR_RET','OFFLINE_MATCHES',"MATCH_ID","PROFILEID",$mainDb,$profileid,$matcheStr,'jsadmin',"","","","",$bInHouseKeeping);
retreiveFromTables('DELETED_OFFLINE_NUDGE_LOG_ELIGIBLE_FOR_RET','OFFLINE_NUDGE_LOG',"SENDER","RECEIVER",$mainDb,$profileid,$nudgeStr,'jsadmin',"","","","",$bInHouseKeeping);
retreiveFromTables('DELETED_VIEW_CONTACTS_LOG_ELIGIBLE_FOR_RET','VIEW_CONTACTS_LOG',"VIEWER","VIEWED",$mainDb,$profileid,$viewContactStr,'jsadmin',"","","","",$bInHouseKeeping);
/****  Transaction for master tables(innodb ones only) started here . ****/

/****** Commit Starts here ******/

/** Shards Committed **/
$iii=1;
foreach($myDbarr as $key=>$value)
{
	if($iii==1)
		$dbMessageLogObj=$dbMessageLogObj1;
	if($iii==2)
		$dbMessageLogObj=$dbMessageLogObj2;
	if($iii==3)
		$dbMessageLogObj=$dbMessageLogObj3;
	$dbMessageLogObj->commitTransaction();
        $myDb=$myDbarr[$key];
        $sql="COMMIT";
        mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);

        $sql="UPDATE newjs.RETRIEVE_PROFILE_LOG SET SHARD$iii=1 WHERE PROFILEID='$profileid' AND DATE='$today'";
        mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
        $iii++;
}
/** Shards Committed **/



/** mainDb committed **/
$sql="COMMIT";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);

$sql="UPDATE newjs.RETRIEVE_PROFILE_LOG SET MAINDB=1 WHERE PROFILEID='$profileid' AND DATE='$today'";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
/** mainDb committed **/

/****** Commit ends here ******/

//For Assisteed Product//////
$billingObj = new BILLING_SERVICE_STATUS('newjs_slave');
$szSubscription = $billingObj->getActiveSuscriptionString($profileid);
if(stristr($szSubscription, 'T')) {//Its a active assisted product user 
   $sql = "INSERT IGNORE INTO Assisted_Product.AP_PROFILE_INFO(PROFILEID,SE,STATUS,ENTRY_DT) VALUES('$profileid', 'default.se', 'LIVE',now())";
   mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);

   // Logging added     
   $sqlLog = "INSERT INTO Assisted_Product.AP_PROFILE_INFO_DEBUG(PROFILEID,TYPE,ENTRY_DT) VALUES('$profileid','RETRIEVE',now())";
   mysql_query($sqlLog,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sqlLog);

}
//////////////////////////////
/*** Update contact status table ***/
$affectedId=array();
$k=1;
foreach($myDbarr as $key=>$value)
{
	$myDb=$myDbarr[$key];
        $sql="select TYPE, RECEIVER,SEEN from CONTACTS where SENDER='$profileid'";
        $result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
        while($myrow=mysql_fetch_array($result))
        {
                if($myrow['TYPE']!='C')
                {
                        unset($CONTACT_STATUS_FIELD);
                        if($myrow['TYPE']=='I')
                        {
                                $CONTACT_STATUS_FIELD['OPEN_CONTACTS']=+1;
                                $CONTACT_STATUS_FIELD['AWAITING_RESPONSE']=+1;
								if($myrow["SEEN"]!='Y')
									$CONTACT_STATUS_FIELD['AWAITING_RESPONSE_NEW']=+1;
						}
                        elseif($myrow['TYPE']=='A')
                                $CONTACT_STATUS_FIELD['ACC_BY_ME']=+1;
                        elseif($myrow['TYPE']=='D')
                                $CONTACT_STATUS_FIELD['DEC_BY_ME']=+1;


                        if(!in_array($myrow["RECEIVER"],$affectedId))
                        {
                                updatememcache($CONTACT_STATUS_FIELD,$myrow["RECEIVER"],1);
                                unset($CONTACT_STATUS_FIELD);
                                $affectedId[]=$myrow["RECEIVER"];
                        }
                }
        }
        $sql="select TYPE, SENDER,SEEN from CONTACTS where RECEIVER='$profileid'";
        $result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
        while($myrow=mysql_fetch_array($result))
        {
                if($myrow['TYPE']!='C')
                {
                        unset($CONTACT_STATUS_FIELD);
                        if($myrow['TYPE']=='I')
                        {
                                $CONTACT_STATUS_FIELD['NOT_REP']=+1;
                                $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=+1;
                        }
                        elseif($myrow['TYPE']=='A')
                        {
                                $CONTACT_STATUS_FIELD['ACC_ME']=+1;
                                $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=+1;
                                if($myrow["SEEN"]!='Y')
									$CONTACT_STATUS_FIELD['ACC_ME_NEW']=+1;
                        }
                        elseif($myrow['TYPE']=='D')
                        {
                                 $CONTACT_STATUS_FIELD['DEC_ME']=+1;
                                 $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=+1;
                                 if($myrow["SEEN"]!='Y')
									$CONTACT_STATUS_FIELD['ACC_ME_NEW']=+1;
                        }
                        if(!in_array($myrow["SENDER"],$affectedId))
                        {
                                updatememcache($CONTACT_STATUS_FIELD,$myrow["SENDER"],1);
                                unset($CONTACT_STATUS_FIELD);
                                        $affectedId[]=$myrow["SENDER"];
                        }
                }
        }
        $tableName = "DELETED_HOROSCOPE_REQUEST_ELIGIBLE_FOR_RET";
        if($bInHouseKeeping) {
          $tableName = "DELETED_HOROSCOPE_REQUEST";
        }
        $sql="select PROFILEID, SEEN from $tableName where PROFILEID_REQUEST_BY='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SEEN"]!= 'Y')
			$CONTACT_STATUS_FIELD['HOROSCOPE_NEW']=+1;
		$CONTACT_STATUS_FIELD['HOROSCOPE_REQUEST']=+1;
		if(!in_array($myrow["SENDER"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["SENDER"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["SENDER"];
			
		}
	}
  $tableName = "DELETED_PHOTO_REQUEST_ELIGIBLE_FOR_RET";
  if($bInHouseKeeping) {
    $tableName = "DELETED_PHOTO_REQUEST";
  }
	$sql="select PROFILEID, SEEN from $tableName where PROFILEID_REQ_BY='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SEEN"]!= 'Y')
			$CONTACT_STATUS_FIELD['PHOTO_REQUEST_NEW']=+1;
		$CONTACT_STATUS_FIELD['PHOTO_REQUEST']=+1;
		if(!in_array($myrow["SENDER"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["SENDER"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["SENDER"];
			
		}
	}
	
	if($k==1)
		$dbDeletedMessagesObj=$dbDeletedMessagesObj1;
	if($k==2)
		$dbDeletedMessagesObj=$dbDeletedMessagesObj2;
	if($k==3)
		$dbDeletedMessagesObj=$dbDeletedMessagesObj3;
	$result=$dbDeletedMessagesObj->getSenderMessages($profileid);
	$k++;
	//$sql = "SELECT RECEIVER, SEEN FROM DELETED_MESSAGE_LOG WHERE SENDER = '$profileid' AND TYPE = 'R' AND IS_MSG = 'Y'";
	//$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	foreach($result as $key=>$myrow)
	{
		if($myrow["SEEN"]!= 'Y')
			$CONTACT_STATUS_FIELD['MESSAGE_NEW']=+1;
		$CONTACT_STATUS_FIELD['MESSAGE']=+1;
		if(!in_array($myrow["SENDER"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["SENDER"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["SENDER"];
			
		}
	}
}
/*** Update contact status table ***/



/**
* This function is used to move records from 'deleted user table' to 'active user table'.
* @param string $delTable is name of table used to keep deleted user records
* @param string $selTable is name of table used to keep active user records
* @param string $whereStrLabel1 is where-condition1 on which profileid is checked.
* @param string $whereStrLabel2 is where-condition2 on which profileid is checked.
* @param resource $db is database connection
* @param int $profileid is unique id of a user.Here its is profileid of retreive record.
* @param string $listOfActiveProfile comma seperatad values of active profiles. 
* @param string $database optinal field for specifying the database name. @default is 'newjs
* @param object $dbMessageLogObj @default is ''
* @param object $dbDeletedMessagesObj @default is ''
* @param object $dbMessageObj @default is ''
* @param object $dbDeletedMessageLogObj @default is ''
* @param boolean $bInHouskeeping @default is false
*/

function retreiveFromTables($delTable,$selTable,$whereStrLabel1,$whereStrLabel2,$db,$profileid,$listOfActiveProfile,$database="",$dbMessageLogObj='',$dbDeletedMessagesObj='',$dbMessageObj='',$dbDeletedMessageLogObj='',$bInHouskeeping=false)
{
	if($listOfActiveProfile)
	{
	        if(!$database)
        	        $database='newjs';

		if($selTable=='MESSAGE_LOG')
		{
			//retreiveFromTables('DELETED_MESSAGE_LOG','MESSAGE_LOG',"RECEIVER","SENDER",$myDb,$profileid,$messagelogStr);
			$idsArr=$dbDeletedMessageLogObj->selectActiveDeletedData($profileid,$listOfActiveProfile,$whereStrLabel1,$whereStrLabel2);
			
			//$sql="select ID FROM $database.$delTable WHERE ($whereStrLabel1='$profileid' OR $whereStrLabel2='$profileid') AND ($whereStrLabel1 IN ($listOfActiveProfile) OR $whereStrLabel2 IN ($listOfActiveProfile))";
			//$result=mysql_query($sql,$db) or mysql_error_with_mail(mysql_error($db).$sql);
//echo $sql."\n";
			/*while($myrow=mysql_fetch_array($result))
			{
				$idsArr[]=$myrow["ID"];
			}*/
			if(is_array($idsArr))
			{
				//$idStr=implode(",",$idsArr);
				mysql_query($sql,$db);
        //If profile is in housekeeping then
        if($bInHouskeeping) {
          $result=$dbMessageObj->insertIntoMessages($idsArr);
        } else {
          $result=$dbMessageObj->insertIntoFromMessagesEligibleForRet($idsArr);
        }
				        
				//$sql="INSERT IGNORE INTO $database.MESSAGES SELECT * FROM $database.DELETED_MESSAGES WHERE ID IN ($idStr)";
				//mysql_query($sql,$db) or ($skip=1);
//echo $sql."\n";
				if($result)
				{
					$res=$dbDeletedMessagesObj->deleteMessages($idsArr);
					//$sql="DELETE FROM $database.DELETED_MESSAGES WHERE ID IN ($idStr)";
					//mysql_query($sql,$db) or ($skip=1);
					if(!$res)
						mysql_error_with_mail(mysql_error($db)."deleteMessages");
				}
				else
				{
					
					mysql_error_with_mail(mysql_error($db).$sql);
					/* no need to rollback as it is defaulted*/
				}

			}
      //If profile exist in housekeeping then
      if($bInHouskeeping) {
        $res=$dbMessageLogObj->insertMessageLogData($profileid,$listOfActiveProfile,$whereStrLabel1,$whereStrLabel2);
      } else {
        $res=$dbMessageLogObj->insertMessageLogDataFromEligibleForRet($profileid,$listOfActiveProfile,$whereStrLabel1,$whereStrLabel2);
      }
			
			//$sql="INSERT IGNORE INTO $database.$selTable SELECT * FROM $database.$delTable WHERE ($whereStrLabel1='$profileid' OR $whereStrLabel2='$profileid') AND ($whereStrLabel1 IN ($listOfActiveProfile) OR $whereStrLabel2 IN ($listOfActiveProfile))";
        	//mysql_query($sql,$db) or ($skip=1);
//echo $sql."\n";
	        if($res)
        	{
				$response=$dbDeletedMessageLogObj->deleteMessages($profileid,$listOfActiveProfile,$whereStrLabel1,$whereStrLabel2);
                //	$sql="DELETE FROM $database.$delTable WHERE ($whereStrLabel1='$profileid' OR $whereStrLabel2='$profileid') AND ($whereStrLabel1 IN ($listOfActiveProfile) OR $whereStrLabel2 IN ($listOfActiveProfile))";
	              //  mysql_query($sql,$db) or ($skip=1);
//echo $sql."\n";
				if(!$response){
					mysql_error_with_mail(mysql_error($db)."deleteMessages");
				}
        	}
        	else
				mysql_error_with_mail(mysql_error($db)."insert");	       
		}
		else
		{
      if($bInHouskeeping) {
        $delTable = substr($delTable, 0, stripos($delTable, "_ELIGIBLE_FOR_RET"));
      }
	        $sql="INSERT IGNORE INTO $database.$selTable SELECT * FROM $database.$delTable WHERE ($whereStrLabel1='$profileid' OR $whereStrLabel2='$profileid') AND ($whereStrLabel1 IN ($listOfActiveProfile) OR $whereStrLabel2 IN ($listOfActiveProfile))";
        	mysql_query($sql,$db) or ($skip=1);
//echo $sql."\n";
	        if(!$skip)
        	{
                	$sql="DELETE FROM $database.$delTable WHERE ($whereStrLabel1='$profileid' OR $whereStrLabel2='$profileid') AND ($whereStrLabel1 IN ($listOfActiveProfile) OR $whereStrLabel2 IN ($listOfActiveProfile))";
	                mysql_query($sql,$db) or ($skip=1);
//echo $sql."\n";
        	}
	        if($skip)
        	        mysql_error_with_mail(mysql_error($db).$sql);
		}
	}
}

/**
* This function is used to retreive active profiles from the tables.As we need to skip deleted profiles from movement from 'deleted tables' to 'active tables'
* @param string $table tables from which active profiles need to fetched
* @param string whereStrLabel1 is where-condition1 on which profileid is checked.
* @param string whereStrLabel2 is where-condition2 on which profileid is checked.
* @param resource-id $myDb associated shard connection of profile
* @param resource-id $mainDb is database connection of master database.
* @param int $profileid is unique id of a user.Here its is profileid of retreive record.
* @param string $database optinal field for specifying the database name. @default is 'newjs'
*/
function retreiveOnlyActiveProfiles($table,$whereStrLabel1,$whereStrLabel2,$myDb,$mainDb,$profileid,$database="",$bInHouseKeepingTable=false)
{
	if(!$database)
		$database='newjs';
	global $inactivityDate,$oldActivityOneYear,$oldActivitySixMonths;

        if($table=='DELETED_HOROSCOPE_REQUEST_ELIGIBLE_FOR_RET' || $table=="DELETED_PHOTO_REQUEST_ELIGIBLE_FOR_RET" || $table=="DELETED_BOOKMARKS_ELIGIBLE_FOR_RET")
        {
		if($table=="DELETED_BOOKMARKS_ELIGIBLE_FOR_RET")
			$dateTimeColumnName="BKDATE";
		else
			$dateTimeColumnName="DATE";
                $oldActivity=$oldActivityOneYear;
        }
	elseif($table=='DELETED_PROFILE_CONTACTS_ELIGIBLE_FOR_RET')
	{
		$dateTimeColumnName="TIME";
                $oldActivity=$oldActivitySixMonths;
		$specialWhereCondition=" AND ($dateTimeColumnName>='$inactivityDate' OR TYPE<>'I')";
	}
	
  if($bInHouseKeepingTable) {
    $table = substr($table, 0, stripos($table, "_ELIGIBLE_FOR_RET"));
  }
  
	//dateTimeColumnName if will be removed
	if($dateTimeColumnName)
		$sql="SELECT $whereStrLabel2 as PIDS , $dateTimeColumnName as DATE FROM $database.$table WHERE $whereStrLabel1='$profileid'";
	else
		$sql="SELECT $whereStrLabel2 as PIDS FROM $database.$table WHERE $whereStrLabel1='$profileid'";
	if($specialWhereCondition)
		$sql.=$specialWhereCondition;
	$res=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
//echo $sql."\n";
	while($row=mysql_fetch_array($res))
	{
		if($dateTimeColumnName)
		{
			if($row["DATE"]<$oldActivity)
				$arrForInactivityConsideation[]=$row["PIDS"];	
			else
				$arr[]=$row["PIDS"];
		}
		else
			$arr[]=$row["PIDS"];
	}
	if($dateTimeColumnName)
		$sql="SELECT $whereStrLabel1 as PIDS , $dateTimeColumnName as DATE  FROM $database.$table WHERE $whereStrLabel2='$profileid'";
	else
		$sql="SELECT $whereStrLabel1 as PIDS FROM $database.$table WHERE $whereStrLabel2='$profileid'";
	$res=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
//echo $sql."\n";
	while($row=mysql_fetch_array($res))
	{
                if($dateTimeColumnName)
                {
                        if($row["DATE"]<$oldActivity)
                                $arrForInactivityConsideation[]=$row["PIDS"];   
			else
				$arr[]=$row["PIDS"];
                }
                else
                        $arr[]=$row["PIDS"];
	}
	if($arr || $arrForInactivityConsideation)
	{
		if($arr)
		{
			$str="'".implode("','",$arr)."'";
			$whereArr[]="(PROFILEID IN ($str))";
		}
		if($arrForInactivityConsideation)
		{
			$strForInactivityConsideation="'".implode("','",$arrForInactivityConsideation)."'";
			$whereArr[]="(PROFILEID IN ($strForInactivityConsideation) AND DATE(LAST_LOGIN_DT)>'$inactivityDate')";
		}
		$whereStr="(".implode(" OR " ,$whereArr).")";

		unset($arr);
		unset($arrForInactivityConsideation);

		//$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN ($str) AND ACTIVATED<>'D'";
		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND $whereStr";
		$res=mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
//echo $sql."\n";
		while($row=mysql_fetch_array($res))
		{
			$arr[]=$row["PROFILEID"];
		}
	}
//echo "\n".$sql."\n";
	if($arr)
	{
		$str=implode("','",$arr);
		return "'".$str."'";
	}
	return;
}

/**
* This function is used to send error message in a mail to concerned developer.
* @msg string messge which contains error and sql query which has caused error.
*/
function mysql_error_with_mail($msg)
{
	//echo "-------->>".$msg;die;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com,kunal.test02@gmail.com","deleteprofile_bg_autocommit_final.php",$msg);
	exit;
}
?>
