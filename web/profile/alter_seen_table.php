<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$mysqlObj=new Mysql;

if($fromSym==1)
{
 global $CALL_NOW,$force_query;

}
$message_upd=1;
$updateDb=$mysqlObj->connect("master");


$myDbName=getProfileDatabaseConnectionName($mypid,'',$mysqlObj);

$connection1=JsDbSharding::getShardNo($mypid);
$dbMessageLogObj=new NEWJS_MESSAGE_LOG($connection1);

$updateDb=$mysqlObj->connect("$myDbName");
if($updatecontact || $force_query==1)
	$contact_result_seen=update_table_master($mypid,$profileid,'newjs.CONTACTS','SENDER','RECEIVER',$updateDb);
$photo_result_seen=update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID','PROFILEID_REQ_BY',$updateDb);
//$horoscope_result_seen=update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID','PROFILEID_REQUEST_BY',$updateDb);
$photo_request_upload_seen=update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID_REQ_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
//$horoscope_result_upload_seen=update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID_REQUEST_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
if(!$message_upd)
	global $message_upd;
if($message_upd || $force_query==1){	//$message_upload_seen=update_table_master($mypid,$profileid,'newjs.MESSAGE_LOG','SENDER','RECEIVER',$dbMessageLogObj);
	$message_upload_seen=$dbMessageLogObj->alterMessageSeen($mypid,$profileid );	
}
	

if($photo_result_seen||$horoscope_result_seen||$message_upload_seen)
{
	$profileMemcacheServiceObj = new ProfileMemcacheService($mypid);
	if($photo_result_seen>0)
		$profileMemcacheServiceObj->update("PHOTO_REQUEST_NEW",-1);
	if($horoscope_result_seen>0)
		$profileMemcacheServiceObj->update("HOROSCOPE_NEW",-1);
	if($message_upload_seen>0)
		$profileMemcacheServiceObj->update("MESSAGE_NEW",-1);
	$profileMemcacheServiceObj->updateMemcache();
}

$myDbName2=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
$updateDb=$mysqlObj->connect("$myDbName2");
//echo $myDbName2."--".$myDbName;
if($myDbName2!=$myDbName)
{
		$connection2=JsDbSharding::getShardNo($profileid);
		$dbMessageLogObj2=new NEWJS_MESSAGE_LOG($connection2);

//echo "contact--$contact_result_seen--photo--$photo_result_seen--horo--$horoscope_result_seen---mesage---message_upload_seen";
	if($updatecontact || $force_query==1)
		if($contact_result_seen)
			update_table_master($mypid,$profileid,'newjs.CONTACTS','SENDER','RECEIVER',$updateDb);
	if($photo_result_seen || $force_query==1)
		update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID','PROFILEID_REQ_BY',$updateDb);
//	if($horoscope_result_seen || $force_query==1)
//		update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID','PROFILEID_REQUEST_BY',$updateDb);
	if($photo_request_upload_seen || $force_query==1)
		update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID_REQ_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
//	if($horoscope_result_upload_seen || $force_query==1 )
//		update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID_REQUEST_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
	if($message_upd || $force_query==1)
		if($message_upload_seen || $force_query==1){
			//update_table_master($mypid,$profileid,'newjs.MESSAGE_LOG','SENDER','RECEIVER',$updateDb);
			$dbMessageLogObj2->alterMessageSeen($mypid,$profileid );
		}
}

//$updateDb = connect_211();
$updateDb=$mysqlObj->connect("viewLogRep");
//$SUFFIX=getsuffix($mypid);
//$SUFFIX=getsuffix($profileid);
$view_log_trigger_seen=update_table_master($mypid,$profileid,'newjs.VIEW_LOG_TRIGGER','VIEWER','VIEWED',$updateDb);
//$view_log_trigger_seen=update_table_master($mypid,$profileid,'newjs.VIEW_LOG_TRIGGER_TEMP','VIEWER','VIEWED',$updateDb);
mysql_query($sql,$updateDb);


function update_table_master($mypid,$profileid,$table_name,$FIELD1,$FIELD2,$updateDb,$updateY='')
{
	if($table_name=="newjs.CONTACTS")
	{
		global $run_on;
		if($mypid && $profileid)
		{	
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD2 in($mypid,$profileid) AND $FIELD1 in($profileid,$mypid)";
			mysql_query($sql,$updateDb);
			return mysql_affected_rows($updateDb);
		}		
	}
	elseif($table_name=="userplane.CHAT_REQUESTS" || $table_name=="jsadmin.OFFLINE_MATCHES" || $table_name=="jsadmin.VIEW_CONTACTS_LOG")
	{
		if($table_name=="jsadmin.VIEW_CONTACTS_LOG")
			$sql="select count(*) from $table_name WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND SOURCE='".CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING."'";
		else
			$sql="select count(*) from $table_name WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
		$res=mysql_query($sql,$updateDb);

		$countrow=mysql_fetch_row($res);
		if($countrow[0]>0)
		{
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
			mysql_query($sql,$updateDb);
	                return mysql_affected_rows($updateDb);
		}
		else
			return 0;
	}
	else
	{	
		if($updateY)
			$sql="UPDATE $table_name SET $updateY='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND $updateY='U'";
		else
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
		
		// IVR-Callnow table update call status
		if($table_name=='newjs.CALLNOW')			 
			$sql .=" AND (CALL_STATUS='R' OR CALL_STATUS='M')";

//		echo $sql."<BR>";
		mysql_query($sql,$updateDb);
		return mysql_affected_rows($updateDb);
		//echo "<br>";
		//echo "<br>";
		//echo "\n".$sql;
	}
}
?>
