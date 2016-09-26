<?php

/** 
* @author Lavesh Rawat 
* @copyright Copyright 2010, Infoedge India Ltd.
*/

$dirname=dirname(__FILE__);
chdir($dirname);
include('connect.inc');
include("../sugarcrm/custom/crons/housekeepingConfig.php");
include("../sugarcrm/include/utils/systemProcessUsersConfig.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
global $partitionsArray;
global $process_user_mapping;

$processUserId=$process_user_mapping["delete_profile"];
if(!$processUserId)
        $processUserId=1;

$updateTime=date("Y-m-d H:i:s");

/** invalid or no profileid is passed **/
if(!$argv[1])
{

	$logError=1;
}
else
{
	$profileid = $argv[1];
	$isarchive=$argv[2];
	if(!$profileid)
		$logError=2;
	if(!is_numeric($profileid))
		$logError=3;
}

$mainDb = connect_db();
$slaveDb = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$mainDb);
if($logError)
{
	$date=date("Y-m-d");
        $sql="Update MIS.DELETE_RETRIEVE_INVALID_PROFILEID set COUNT=COUNT+1 where Date='$date' AND ERROR='$logError' AND  DELETE_RETRIEVE='D'";
        mysql_query($sql,$mainDb);
        if(mysql_affected_rows()==0)
        {
                $sql="Insert into MIS.DELETE_RETRIEVE_INVALID_PROFILEID(DATE,COUNT,ERROR,DELETE_RETRIEVE) values ('$date','1','$logError','D')";
        	mysql_query($sql,$mainDb);
        }
	exit;

}
/** invalid or no profileid is passed **/


callDeleteCronBasedOnId($profileid);

/*** Logging deleted profiles to run check in the night to delete any left over contacts **/
$sql="INSERT IGNORE INTO newjs.NEW_DELETED_PROFILE_LOG(PROFILEID,DATE) VALUES('$profileid',NOW())";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
/*** Logging deleted profiles to run check in the night to delete any left over contacts **/


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
 $dbDeletedMessagesObj1=new NEWJS_DELETED_MESSAGES("shard1_master");
 $dbDeletedMessagesObj2=new NEWJS_DELETED_MESSAGES("shard2_master");
 $dbDeletedMessagesObj3=new NEWJS_DELETED_MESSAGES("shard3_master");
 $dbMessageObj1=new NEWJS_MESSAGES("shard1_master");
 $dbMessageObj2=new NEWJS_MESSAGES("shard2_master");
 $dbMessageObj3=new NEWJS_MESSAGES("shard3_master");
 $dbDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG("shard1_master");
 $dbDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG("shard2_master");
 $dbDeletedMessageLogObj3=new NEWJS_DELETED_MESSAGE_LOG("shard3_master");





/****  Transaction for all 3 shards started here. We will commit all three shards together. ****/
if(count($myDbarr))
{
	$i=1;
	foreach($myDbarr as $key=>$value)
	{
		$myDb=$myDbarr[$key];
		
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
		
		$dbMessageLogObj->startTransaction();
		$sql="BEGIN"; 
		mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);

		delFromTables('DELETED_HOROSCOPE_REQUEST','HOROSCOPE_REQUEST',$myDb,$profileid,"PROFILEID");
		delFromTables('DELETED_HOROSCOPE_REQUEST','HOROSCOPE_REQUEST',$myDb,$profileid,"PROFILEID_REQUEST_BY");

		delFromTables('DELETED_PHOTO_REQUEST','PHOTO_REQUEST',$myDb,$profileid,"PROFILEID");
		delFromTables('DELETED_PHOTO_REQUEST','PHOTO_REQUEST',$myDb,$profileid,"PROFILEID_REQ_BY");

		delFromTables('DELETED_MESSAGE_LOG','MESSAGE_LOG',$myDb,$profileid,"RECEIVER",'',$dbMessageLogObj,$dbDeletedMessagesObj,$dbMessageObj,$dbDeletedMessageLogObj);
		delFromTables('DELETED_MESSAGE_LOG','MESSAGE_LOG',$myDb,$profileid,"SENDER",'',$dbMessageLogObj,$dbDeletedMessagesObj,$dbMessageObj,$dbDeletedMessageLogObj);

		delFromTables('DELETED_EOI_VIEWED_LOG','EOI_VIEWED_LOG',$myDb,$profileid,"VIEWER");
		delFromTables('DELETED_EOI_VIEWED_LOG','EOI_VIEWED_LOG',$myDb,$profileid,"VIEWED");

		delFromTables('DELETED_PROFILE_CONTACTS','CONTACTS',$myDb,$profileid,"SENDER");
		delFromTables('DELETED_PROFILE_CONTACTS','CONTACTS',$myDb,$profileid,"RECEIVER");
		$i++;
	}
}
/****  Transaction for all 3 shards started here.We will commit all three shards together. ****/


/****  Transaction for master tables started here . ****/
$mainDb = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$mainDb);
$sql="BEGIN"; 
$dupLogObj=new DUPLICATE_PROFILE_LOG();
$dupLogObj->startTransaction();
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
delFromTables('DELETED_BOOKMARKS','BOOKMARKS',$mainDb,$profileid,"BOOKMARKER");
delFromTables('DELETED_BOOKMARKS','BOOKMARKS',$mainDb,$profileid,"BOOKMARKEE");

delFromTables('DELETED_IGNORE_PROFILE','IGNORE_PROFILE',$mainDb,$profileid,"PROFILEID");
delFromTables('DELETED_IGNORE_PROFILE','IGNORE_PROFILE',$mainDb,$profileid,"IGNORED_PROFILEID");

delFromTables('DELETED_OFFLINE_MATCHES','OFFLINE_MATCHES',$mainDb,$profileid,"MATCH_ID","jsadmin");
delFromTables('DELETED_OFFLINE_MATCHES','OFFLINE_MATCHES',$mainDb,$profileid,"PROFILEID","jsadmin");

delFromTables('DELETED_OFFLINE_NUDGE_LOG','OFFLINE_NUDGE_LOG',$mainDb,$profileid,"SENDER",'jsadmin');
delFromTables('DELETED_OFFLINE_NUDGE_LOG','OFFLINE_NUDGE_LOG',$mainDb,$profileid,"RECEIVER",'jsadmin');

delFromTables('DELETED_VIEW_CONTACTS_LOG','VIEW_CONTACTS_LOG',$mainDb,$profileid,"VIEWER",'jsadmin');
delFromTables('DELETED_VIEW_CONTACTS_LOG','VIEW_CONTACTS_LOG',$mainDb,$profileid,"VIEWED",'jsadmin');

markProfilesAsNonDuplicate($profileid,$dupLogObj);

/****  Transaction for master tables started here . ****/



/****** Commit Starts here ******/

/** Shards Committed **/
$iii=1;
foreach($myDbarr as $key=>$value)
{
	$myDb=$myDbarr[$key];
	if($iii==1)
		$dbMessageLogObj=$dbMessageLogObj1;
	if($iii==2)
		$dbMessageLogObj=$dbMessageLogObj2;
	if($iii==3)
		$dbMessageLogObj=$dbMessageLogObj3;
		
	$dbMessageLogObj->commitTransaction();
	$sql="COMMIT";
	mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);

	$sql="UPDATE newjs.NEW_DELETED_PROFILE_LOG SET SHARD$iii=1 WHERE PROFILEID='$profileid'";
	mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
	$iii++;
}
/** Shards Committed **/

/** mainDb committed **/
$sql="COMMIT";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
$dupLogObj->commitTransaction();

$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$profileid'";
mysql_query($sql_del,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql_del);

$sql="UPDATE newjs.NEW_DELETED_PROFILE_LOG SET MAINDB=1 WHERE PROFILEID='$profileid'";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);


//Added by Amit Jaiswal to Mark deleted in sugarcrm if a lead is there for current user mentioned in sugarcrm enhancement 2 PRD
$username_query="select USERNAME from newjs.JPROFILE where PROFILEID='".$profileid."'";
$username_result=mysql_query($username_query,$slaveDb) or mysql_error_with_mail(mysql_error($mainDb).$username_query);
$row=mysql_fetch_assoc($username_result);
$username=$row['USERNAME'];

if(0 === strlen($username)) {
        $username_result=mysql_query($username_query,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$username_query);
        $row=mysql_fetch_assoc($username_result);
        $username=$row['USERNAME'];
}

$sugar_sql="select id_c from sugarcrm.leads_cstm where jsprofileid_c='".$username."'";
$sugar_res=mysql_query($sugar_sql,$slaveDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_sql);
if(mysql_num_rows($sugar_res)>0)
{
	while($sugar_row=mysql_fetch_array($sugar_res))
	{
		$lead_id=$sugar_row['id_c'];
		$sugar_del_sql1="update sugarcrm.leads,sugarcrm.leads_cstm set deleted='1',status='32',disposition_c='26',modified_user_id='$processUserId',date_modified='$updateTime' where id=id_c and id='".$lead_id."'";
		$sugar_del_sql2="update sugarcrm.email_addr_bean_rel set deleted='1' where bean_id='".$lead_id."'";
		$sugar_del_result1=mysql_query($sugar_del_sql1,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_del_sql1);
		$sugar_del_result1=mysql_query($sugar_del_sql2,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_del_sql2);
	}
}

if(is_array($partitionsArray))
{
	foreach($partitionsArray as $partition=>$partitionArray)
	{
		$partitionLeadsCstm="sugarcrm_housekeeping.".$partition."_leads_cstm";
		$partitionLeads="sugarcrm_housekeeping.".$partition."_leads";
		$partitionEmail="sugarcrm_housekeeping.".$partition."_email_addr_bean_rel";
		$sugar_sql="select id_c from $partitionLeadsCstm where jsprofileid_c='".$username."'";
		$sugar_res=mysql_query($sugar_sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_sql);
		if(mysql_num_rows($sugar_res)>0)
		{
			while($sugar_row=mysql_fetch_array($sugar_res))
			{
				$lead_id=$sugar_row['id_c'];
				$sugar_del_sql1="update $partitionLeads,$partitionLeadsCstm set deleted='1',status='32',disposition_c='26',modified_user_id='$processUserId',date_modified='$updateTime' where id='".$lead_id."' and id=id_c";
				$sugar_del_sql2="update $partitionEmail set deleted='1' where bean_id='".$lead_id."'";
				$sugar_del_result1=mysql_query($sugar_del_sql1,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_del_sql1);
				$sugar_del_result1=mysql_query($sugar_del_sql2,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sugar_del_sql2);
			}
		}
	}
}
//End by Jaiswal

/** mainDb committed **/

/****** Commit ends here ******/



/*** 211 tables ***/
$db_211 = connect_211();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);

$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWED='$profileid'";
mysql_query($sql_delete,$db_211);

$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER  WHERE VIEWER='$profileid'";
mysql_query($sql_delete,$db_211);

$sql="UPDATE newjs.NEW_DELETED_PROFILE_LOG SET 211DB=1 WHERE PROFILEID='$profileid'";
mysql_query($sql,$mainDb) or mysql_error_with_mail(mysql_error($mainDb).$sql);
/*** 211 tables ***/

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

/*** For CONATCT_STATUS TABLE numbers. ***/
$affectedId=array();
$k=1;
foreach($myDbarr as $key=>$value)
{
	$myDb=$myDbarr[$key];
	$sql="select TYPE, RECEIVER,SEEN from DELETED_PROFILE_CONTACTS where SENDER='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	
	while($myrow=mysql_fetch_assoc($result))
	{
		if($myrow['TYPE']!='C')
		{
			unset($CONTACT_STATUS_FIELD);
			if($myrow['TYPE']=='I')
			{
				$CONTACT_STATUS_FIELD['OPEN_CONTACTS']=-1;
				$CONTACT_STATUS_FIELD['AWAITING_RESPONSE']=-1;
				if($myrow["SEEN"]!='Y')
					$CONTACT_STATUS_FIELD['AWAITING_RESPONSE_NEW']=-1;
				
			}
			elseif($myrow['TYPE']=='A')
			{
				$CONTACT_STATUS_FIELD['ACC_BY_ME']=-1;				
			} 
			elseif($myrow['TYPE']=='D')
			{
				$CONTACT_STATUS_FIELD['DEC_BY_ME']=-1;				
			}
			if(!in_array($myrow["RECEIVER"],$affectedId))
			{
				if(!$isarchive)
				{
					updatememcache($CONTACT_STATUS_FIELD,$myrow["RECEIVER"],1);
					
				}
				unset($CONTACT_STATUS_FIELD);
				$affectedId[]=$myrow["RECEIVER"];
			}
		}
	}


	$sql="select TYPE, SENDER,SEEN from DELETED_PROFILE_CONTACTS where RECEIVER='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow['TYPE']!='C')
		{
			unset($CONTACT_STATUS_FIELD);
			if($myrow['TYPE']=='I')
			{
				$CONTACT_STATUS_FIELD['NOT_REP']=-1;
				$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
				
			
			}
			elseif($myrow['TYPE']=='A')
			{
				$CONTACT_STATUS_FIELD['ACC_ME']=-1;
				$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
				if($myrow["SEEN"]!='Y')
					$CONTACT_STATUS_FIELD['ACC_ME_NEW']=-1;
				
			}
			elseif($myrow['TYPE']=='D')
			{
				 $CONTACT_STATUS_FIELD['DEC_ME']=-1;
				 $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
				 if($myrow["SEEN"]!='Y')
					$CONTACT_STATUS_FIELD['DEC_ME_NEW']=-1;
				
			}
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
	$sql="select PROFILEID, SEEN from DELETED_HOROSCOPE_REQUEST where PROFILEID_REQUEST_BY='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SEEN"]!= 'Y')
			$CONTACT_STATUS_FIELD['HOROSCOPE_REQUEST_NEW']=-1;
		$CONTACT_STATUS_FIELD['HOROSCOPE_REQUEST']=-1;
		if(!in_array($myrow["PROFILEID"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["PROFILEID"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["PROFILEID"];
			
		}
	}
	$sql="select PROFILEID, SEEN from DELETED_PHOTO_REQUEST where PROFILEID_REQ_BY='$profileid'";
	$result=mysql_query($sql,$myDb) or mysql_error_with_mail(mysql_error($myDb).$sql);
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["SEEN"]!= 'Y')
			$CONTACT_STATUS_FIELD['PHOTO_REQUEST_NEW']=-1;
		$CONTACT_STATUS_FIELD['PHOTO_REQUEST']=-1;
		if(!in_array($myrow["PROFILEID"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["PROFILEID"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["PROFILEID"];
			
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
			$CONTACT_STATUS_FIELD['MESSAGE_NEW']=-1;
		$CONTACT_STATUS_FIELD['MESSAGE']=-1;
		if(!in_array($myrow["RECEIVER"],$affectedId))
		{	
			if(!$isarchive)
			{
				updatememcache($CONTACT_STATUS_FIELD,$myrow["RECEIVER"],1);
			}
			unset($CONTACT_STATUS_FIELD);
			$affectedId[]=$myrow["RECEIVER"];
			
		}
	}
}

if(JsConstants::$webServiceFlag == 1)
{
	$process = "INVALIDATE";
	$producerObj=new Producer();
	if($producerObj->getRabbitMQServerConnected())
	{
		$sendCacheData = array('process' =>$process,'data'=>$profileid, 'redeliveryCount'=>0 );
		$producerObj->sendMessage($sendCacheData);
	}
}

/*** For CONATCT_STATUS TABLE numbers. ***/

/**
* This function is used to move records from 'active user table' to 'deleted user table'.
* @param string $delTable is name of table used to keep deleted user records
* @param string $selTable is name of table used to keep active user records
* @param resource-id $db is database connection
* @param int $profileid is unique id of a user.Here its is identifying deleted record
* @param string whereStrLabel is where-condition on which profileid is checked.
* @param string $databaseName optinal field for specifying the database name. @default is 'newjs'
*/
function delFromTables($delTable,$selTable,$db,$profileid,$whereStrLabel,$databaseName='',$dbMessageLogObj='',$dbDeletedMessagesObj='',$dbMessageObj='',$dbDeletedMessageLogObj='')
{
	if(!$databaseName)
		$databaseName='newjs';

	if($selTable=='MESSAGE_LOG')
	{
		
		/*$sql="select ID FROM $databaseName.$selTable WHERE $whereStrLabel='$profileid'";
        	$result=mysql_query($sql,$db) or mysql_error_with_mail(mysql_error($db).$sql);
	        while($myrow=mysql_fetch_array($result))
		{
			$idsArr[]=$myrow["ID"];
		}*/
		$idsArr=$dbMessageLogObj->getAllMessageIdLog($profileid,$whereStrLabel);
		
		if(is_array($idsArr))
		{
			//$idStr=implode(",",$idsArr);
			$result=$dbDeletedMessagesObj->insertIntoDeletedMessages($idsArr);
		
			//$sql="INSERT IGNORE INTO $databaseName.DELETED_MESSAGES SELECT * FROM $databaseName.MESSAGES WHERE ID IN ($idStr)";
			//mysql_query($sql,$db) or ($skip=1);
			
			if($result)
			{
		
				$res=$dbMessageObj->deleteMessages($idsArr);
				
		
				if(!$res)
						mysql_error_with_mail(mysql_error($db)."deleteMessages");
				//$sql="DELETE FROM $databaseName.MESSAGES WHERE ID IN ($idStr)";
				//mysql_query($sql,$db) or ($skip=1);
			}
			else
			{
				mysql_error_with_mail(mysql_error($db)."insertIntoDeletedMessages");
				/* no need to rollback as it is defaulted*/
			}

		}
		
		$res=$dbDeletedMessageLogObj->insert($profileid,$whereStrLabel);
		
		//$sql="INSERT IGNORE INTO $databaseName.$delTable SELECT * FROM $databaseName.$selTable WHERE $whereStrLabel='$profileid'";
		//mysql_query($sql,$db) or ($skip=1);
		if ($res) {
			
		
				$response=$dbMessageLogObj->deleteMessageLog($profileid,$whereStrLabel);
		
				if(!$response)
					$skip=1;
				//$sql = "DELETE FROM $databaseName.$selTable WHERE $whereStrLabel='$profileid'";
				//mysql_query($sql, $db) or ($skip = 1);			
		}
	}
	else{
		$sql="INSERT IGNORE INTO $databaseName.$delTable SELECT * FROM $databaseName.$selTable WHERE $whereStrLabel='$profileid'";
		mysql_query($sql,$db) or ($skip=1);
		if (!$skip) {
			if ($selTable == "CONTACTS" && JsConstants::$webServiceFlag == 1) {
				$url = JsConstants::$contactUrl."/v1/contacts/".$profileid."?TYPE=".$whereStrLabel;
				sendCurlDeleteRequest($url);

			} 
				$sql = "DELETE FROM $databaseName.$selTable WHERE $whereStrLabel='$profileid'";
				mysql_query($sql, $db) or ($skip = 1);
		}
	}
	
	

	if($skip)
	{
		mysql_error_with_mail(mysql_error($db).$sql);
		/* no need to rollback as it is defaulted*/
	}
}

/**
* This function is used to send error message in a mail to concerned developer.
* @msg string messge which contains error and sql query which has caused error.
*/
function mysql_error_with_mail($msg)
{
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","deleteprofile_bg_autocommit_final.php",$msg);
	exit;
}


function sendCurlDeleteRequest($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);
	$result = json_decode($result);
	curl_close($ch);

	return $result;
}

function markProfilesAsNonDuplicate($profileid,$dupLogObj){
    
    $dupArray = $dupLogObj->fetchLogForAProfile($profileid);
    foreach ($dupArray as $key => $value) 
    {
        if($value['PROFILE1']==$profileid) $profile2=$value['PROFILE2'];
        else $profile2=$value['PROFILE1'];
        
        if(!$profileArray[$profile2]['ENTRY_DATE'])
        {
                $profileArray[$profile2]['ENTRY_DATE']=$value['ENTRY_DATE'];
                $profileArray[$profile2]['FLAG']=$value['IS_DUPLICATE'];
        }
            if(JSstrToTime($profileArray[$profile2]['ENTRY_DATE']) < JSstrToTime($value['ENTRY_DATE']))
        {
                $profileArray[$profile2]['ENTRY_DATE']=$value['ENTRY_DATE'];
                $profileArray[$profile2]['FLAG']=$value['IS_DUPLICATE'];
        }

        
    }
    
    $rawDuplicateObj=new RawDuplicate();
    $rawDuplicateObj->setReason(REASON::NONE); 
    $rawDuplicateObj->setIsDuplicate(IS_DUPLICATE::NO); 
    $rawDuplicateObj->addExtension('MARKED_BY','SYSTEM');
    $rawDuplicateObj->setScreenAction(SCREEN_ACTION::NONE);
    $rawDuplicateObj->addExtension('IDENTIFIED_ON',date('Y-m-d H:i:s'));
    $rawDuplicateObj->setComments("Profile Deleted");
    $rawDuplicateObj->setProfileid1($profileid);
    $negativeObj=new INCENTIVE_NEGATIVE_TREATMENT_LIST();
            
    foreach($profileArray as $key => $value)
    {
        
        if($value['FLAG']=='YES')
        {
              $rawDuplicateObj->setProfileid2($key);
              DuplicateHandler::DuplicateProfilelog($rawDuplicateObj); 
              
              $tempArray=$dupLogObj->fetchLogForAProfile($key);
              foreach($tempArray as $key1 => $value1)   
              {

              		  if($value1['PROFILE2']==$profileid || $value1['PROFILE1']==$profileid) continue;
                      	
                      if($value1['PROFILE1']==$key) $tempProfile2=$value1['PROFILE2'];
                      else $tempProfile2=$value1['PROFILE1'];

                      if(!$tempProfileArray[$tempProfile2]['ENTRY_DATE'])
                      {
                              $tempProfileArray[$tempProfile2]['ENTRY_DATE']=$value1['ENTRY_DATE'];
                              $tempProfileArray[$tempProfile2]['FLAG']=$value1['IS_DUPLICATE'];
                      }
                      if(JSstrToTime($tempProfileArray[$tempProfile2]['ENTRY_DATE']) < JSstrToTime($value1['ENTRY_DATE']))
                      {
                              $tempProfileArray[$tempProfile2]['ENTRY_DATE']=$value1['ENTRY_DATE'];
                              $tempProfileArray[$tempProfile2]['FLAG']=$value1['IS_DUPLICATE'];
                      }
                  
                      
                  
              }
              
              $tempFlag=0;
              foreach($tempProfileArray as $key3 => $value3)
              {
                  
                        if($value3['FLAG']=='YES')
                        {
                           $tempFlag=1;
                           break;
                        }  
              }    

              if($tempFlag==0)
              {
              
                  $negativeObj->deleteRecord($key);
                  (new NEWJS_SWAP_JPROFILE())->insert($key);
                  $dp=new DUPLICATES_PROFILES();
				  $dp->removeProfileAsDuplicate($key);
                  
              }
              unset($tempArray);
              unset($tempProfileArray);
              
              
              
            
        }
        
        
    }
    
    
    
}
