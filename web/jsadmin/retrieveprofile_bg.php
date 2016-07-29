<?
$dirname=dirname(__FILE__);
chdir($dirname);
include('connect.inc');
//mysql_close();
$path_class=JsConstants::$docRoot;
$_SERVER['DOCUMENT_ROOT']=$path_class;
include_once("$path_class/classes/globalVariables.Class.php");
include_once("$path_class/classes/Mysql.class.php");
include_once("$path_class/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$mysql=new Mysql;
$sql_timeout='set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000';
//$db2 = connect_slave();
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysql->connect("$myDbName");
        $mysql->executeQuery($sql_timeout,$myDbarr[$myDbName]) or die($sql_timeout);

}
$db = connect_db();
mysql_query_decide($sql_timeout,$db);
$db2=connect_737();
mysql_query_decide($sql_timeout,$db2);
$profileid = $argv[1];

$sql="DELETE FROM newjs.DELETED_PROFILE_LOG WHERE PROFILEID='$profileid'";
mysql_query_decide($sql,$db) or die(mysql_error_js($db));
$i=0;
$myProfileIdShard=JsDbSharding::getShardNo($profileid);
$myDbname=getProfileDatabaseConnectionName($profileid,'',$mysql);
$myDb=$myDbarr[$myDbname];

delete_and_move_records_on_retreiveProfile($profileid,"PHOTO_REQUEST","PROFILEID_REQ_BY","PROFILEID",$mysql,'Y');
delete_and_move_records_on_retreiveProfile($profileid,"PHOTO_REQUEST","PROFILEID","PROFILEID_REQ_BY",$mysql,'Y');
delete_and_move_records_on_retreiveProfile($profileid,"HOROSCOPE_REQUEST","PROFILEID_REQUEST_BY","PROFILEID",$mysql,'Y');
delete_and_move_records_on_retreiveProfile($profileid,"HOROSCOPE_REQUEST","PROFILEID","PROFILEID_REQUEST_BY",$mysql,'Y');
delete_and_move_records_on_retreiveProfile($profileid,"BOOKMARKS","BOOKMARKER","BOOKMARKEE",$mysql);
delete_and_move_records_on_retreiveProfile($profileid,"BOOKMARKS","BOOKMARKEE","BOOKMARKER",$mysql);
delete_and_move_records_on_retreiveProfile($profileid,"IGNORE_PROFILE","IGNORED_PROFILEID","PROFILEID",$mysql);
delete_and_move_records_on_retreiveProfile($profileid,"IGNORE_PROFILE","PROFILEID","IGNORED_PROFILEID",$mysql);

delete_and_move_records_on_retreiveProfile($profileid,"VIEW_CONTACTS_LOG","VIEWER","VIEWED",$mysql,'','jsadmin');
delete_and_move_records_on_retreiveProfile($profileid,"VIEW_CONTACTS_LOG","VIEWED","VIEWER",$mysql,'','jsadmin');

$sql="select * from newjs.DELETED_PROFILE_CONTACTS where SENDER='$profileid'";
$result=$mysql->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));

while($myrow=$mysql->fetchAssoc($result))
{
	$PROFILEID=$myrow['RECEIVER'];
	$DATA[$PROFILEID]=$myrow;
	if($i==0)
		$profile_str="$PROFILEID";
	else
		$profile_str.="','$PROFILEID";

	$i++;
	
}
$sql="select * from newjs.DELETED_PROFILE_CONTACTS where RECEIVER='$profileid'";
$result=$mysql->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
while($myrow=$mysql->fetchAssoc($result))
{
	$PROFILEID=$myrow['SENDER'];
	$DATA[$PROFILEID]=$myrow;
	if($i==0)
		$profile_str="$PROFILEID";
	else
		$profile_str.="','$PROFILEID";

	$i++;


}
$sql="select PROFILEID from newjs.JPROFILE where PROFILEID IN('$profile_str') and ACTIVATED='D'";
$result=mysql_query_decide($sql) or die(mysql_error_js());
while($myrow=mysql_fetch_array($result))
{
	$profileidTemp=$myrow['PROFILEID'];
	unset($DATA[$profileidTemp]);
}
	
$rec_str['I']="OPEN_CONTACTS=OPEN_CONTACTS+1";
$rec_str['A']="ACC_BY_ME=ACC_BY_ME+1";
$rec_str['D']="DEC_BY_ME=DEC_BY_ME+1";
$sen_str['I']="NOT_REP=NOT_REP+1";
$sen_str['A']="ACC_ME=ACC_ME+1";
$sen_str['D']="DEC_ME=DEC_ME+1";

if($DATA)
foreach($DATA as $key=>$val)
{
	
	$other_user=$key;
	$myDbname_other=getProfileDatabaseConnectionName($key,'',$mysql);
	$myDb_other=$myDbarr[$myDbname_other];
	
	$contactid=$val["CONTACTID"];
	$sender=$val['SENDER'];
	$receiver=$val['RECEIVER'];
	$type=$val['TYPE'];
	if($sen_str[$type])
	{
		$sql_cs="update newjs.CONTACTS_STATUS set $sen_str[$type],TOTAL_CONTACTS_MADE=TOTAL_CONTACTS_MADE+1  where PROFILEID=$sender";
		mysql_query_decide($sql_cs,$db) or die(mysql_error_js($db));
	}
	if($rec_str[$type])
	{
		$sql_cs="update newjs.CONTACTS_STATUS set $rec_str[$type] where PROFILEID=$receiver";
		mysql_query_decide($sql_cs,$db) or die(mysql_error_js($db));
	}
	$sql1="insert ignore into newjs.CONTACTS select * from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";
	$sql2="delete from newjs.DELETED_PROFILE_CONTACTS where CONTACTID='$contactid'";

	$mysql->executeQuery($sql1,$myDb) or die(mysql_error_js($myDb));
	$mysql->executeQuery($sql2,$myDb) or die(mysql_error_js($myDb));

	if($myDbname!=$myDbname_other)
	{
		$mysql->executeQuery($sql1,$myDb_other) or die(mysql_error_js($myDb_other));
		$mysql->executeQuery($sql2,$myDb_other) or die(mysql_error_js($myDb_other));
	}
}
mysql_free_result($result);

unset($DATA);
$profile_str='';

unset($CONTACT);
unset($PROFILEID);

//Getting the SENDER and RECEIVER from MESSAGE_LOG table corresponding to the retrieval user
	$connection=JsDbSharding::getShardNo($profileid);
	$dbDeletedMessageLogObj=new NEWJS_DELETED_MESSAGE_LOG($connection);
	$result=$dbDeletedMessageLogObj->getSenderIdMessageLog($profileid,'SENDER');
	//$myDb=$myDbarr[$myDbname];
	//$sql="select ID,RECEIVER from newjs.DELETED_MESSAGE_LOG where SENDER='$profileid'";
	//$result=$mysql->ExecuteQuery($sql,$myDb) or die($sql);
	//while($myrow=$mysql->fetchArray($result))
	foreach($result as $key=>$myrow)
	{
		$CONTACT[$myrow["RECEIVER"]][]=$myrow["ID"];
		$PROFILEID[$myrow["RECEIVER"]]=$myrow["RECEIVER"];
	}
	
	$res=$dbDeletedMessageLogObj->getSenderIdMessageLog($profileid,'RECEIVER');
//	$sql="select ID,SENDER from newjs.DELETED_MESSAGE_LOG where RECEIVER='$profileid'";
//	$result=$mysql->ExecuteQuery($sql,$myDb) or die($sql);
	//while($myrow=$mysql->fetchArray($result))
	foreach($res as $key=>$myrow)
	{
		$CONTACT[$myrow["SENDER"]][]=$myrow["ID"];
	        $PROFILEID[$myrow["SENDER"]]=$myrow["SENDER"];

	}
//If any contact was made by the user
if(is_array($CONTACT))
{
	$profilestr=implode("','",$PROFILEID);
}

//Getting the PROFILEID of the user's whoes PROFILEID are not deleted
$messageShardCount=0;
$dbMyMessageLog=new NEWJS_MESSAGE_LOG($myProfileIdShard);
$dbMyDeletedMessageLogObj=new NEWJS_DELETED_MESSAGE_LOG($myProfileIdShard);
$dbOtherMessageLogObj1=new NEWJS_MESSAGE_LOG("shard1_master");
$dbOtherMessageLogObj2=new NEWJS_MESSAGE_LOG("shard2_master");
$dbOtherMessageLogObj3=new NEWJS_MESSAGE_LOG("shard3_master");
$dbOtherDeletedMessageLogObj1=new NEWJS_DELETED_MESSAGE_LOG("shard1_master");
$dbOtherDeletedMessageLogObj2=new NEWJS_DELETED_MESSAGE_LOG("shard2_master");
$dbOtherDeletedMessageLogObj3=new NEWJS_DELETED_MESSAGE_LOG("shard3_master");

$sql_jp = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID IN('$profilestr') and ACTIVATED<>'D'";
$res_jp = mysql_query_decide($sql_jp) or die($sql_jp.mysql_error_js());
while($row_jp = mysql_fetch_array($res_jp))
{
	 	if(is_array($CONTACT[$row_jp['PROFILEID']]))
		{
			foreach($CONTACT[$row_jp['PROFILEID']] as $key=>$val)
			{
				$contactid=$val;
				$messageShardCount = ($row_jp['PROFILEID']%3) + 1;
				if($messageShardCount==1)
				{
					$dbOtherMessageLogObj=$dbOtherMessageLogObj1;
					$dbOtherDeletedMessageLogObj=$dbOtherDeletedMessageLogObj1;
				}
				if($messageShardCount==2)
				{
					$dbOtherMessageLogObj=$dbOtherMessageLogObj2;
					$dbOtherDeletedMessageLogObj=$dbOtherDeletedMessageLogObj2;
				}
				if($messageShardCount==3)
				{
					$dbOtherMessageLogObj=$dbOtherMessageLogObj3;
					$dbOtherDeletedMessageLogObj=$dbOtherDeletedMessageLogObj3;
				}
				$otherProfileIdShard=JsDbSharding::getShardNo($row_jp['PROFILEID']);
				//inserting the messages back to message_log from delete_message_log table
				$res=$dbOtherMessageLogObj->insertMessageLogFromDeletedLogContact($contactid);
				//$sql="insert ignore into newjs.MESSAGE_LOG select * from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
				//Query to run on both sharded servers
				//$myDbothername=getProfileDatabaseConnectionName($row_jp['PROFILEID'],'',$mysql);
				//$myDbother=$myDbarr[$myDbothername];
				//$myDb=$myDbarr[$myDbname];

				//$res=$mysql->ExecuteQuery($sql,$myDbother) or die(mysql_error_js());
				if($res)
				{    
					   $dbOtherDeletedMessageLogObj->deleteMessageLogById($contactid);
				//	$sql="delete from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
				//	$mysql->ExecuteQuery($sql,$myDbother) or die(mysql_error_js());
				}
				if($myProfileIdShard!=$otherProfileIdShard)
				{
					$response=$dbMyMessageLog->insertMessageLogFromDeletedLogContact($contactid);
					//$sql="insert ignore into newjs.MESSAGE_LOG select * from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
				       // $res=$mysql->ExecuteQuery($sql,$myDb) or die(mysql_error_js());
                			if($response)
	                		{	
								$dbMyDeletedMessageLogObj->deleteMessageLogById($contactid);
        	                		//$sql="delete from newjs.DELETED_MESSAGE_LOG where ID='$contactid'";
						//$mysql->ExecuteQuery($sql,$myDb) or die(mysql_error_js());
        			    	}
				}
			}
		}

}

//Genearlized function added by lavesh
function delete_and_move_records_on_retreiveProfile($profileid,$table,$selectColumn,$whereColumn,$mysql,$isTableSahrded="",$dbName="")
{
        global $myDb,$myDbarr,$myDbname,$db2,$db;

        if(!$dbName)
                $dbName='newjs';
        $delTable="$dbName.DELETED_".$table;
        $table=$dbName.'.'.$table;

        $sql="SELECT $selectColumn FROM $delTable WHERE $whereColumn=$profileid";
	if($isTableSahrded)
        	$result=$mysql->executeQuery($sql,$myDb) or die(mysql_error($myDb));
	else
		$result=$mysql->executeQuery($sql,$db) or die(mysql_error($myDb));

        while($myrow=$mysql->fetchrow($result))
        {
                $pidArr[]=$myrow[0];
        }
        
	if(is_array($pidArr))
        {
                $affected_pid_str=implode(",",$pidArr);

                $sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED<>'D' AND PROFILEID IN ($affected_pid_str)";
        	$result=$mysql->executeQuery($sql,$db2) or die(mysql_error($db2));
	        while($myrow=$mysql->fetchArray($result))
                {
                        $activatedPidArr[]=$myrow['PROFILEID'];
                }

                if(is_array($activatedPidArr))
                {
			if($isTableSahrded)
			{
                        	for($i=0;$i<count($activatedPidArr);$i++)
	                        {
        	                        $otherPid=$activatedPidArr[$i];
                	                $otherDbName=getProfileDatabaseConnectionName($otherPid,'',$mysql);
                        	        $otherDb=$myDbarr[$otherDbName];
	                                $sql1="INSERT IGNORE into $table SELECT * FROM $delTable WHERE $whereColumn=$profileid AND $selectColumn=$otherPid";
        	                        $sql2="delete from $delTable WHERE $whereColumn=$profileid AND $selectColumn=$otherPid";
	
					//mysql_query($sql1,$myDb) or die(mysql_error($myDb));
					//die;
        	                        $mysql->executeQuery($sql1,$myDb) or die(mysql_error($myDb));
                	                $mysql->executeQuery($sql2,$myDb) or die(mysql_error($myDb));

                        	        if($myDbname!=$otherDbName)
                                	{
                                        	$mysql->executeQuery($sql1,$otherDb) or die(mysql_error($otherDb));
	                                        $mysql->executeQuery($sql2,$otherDb) or die(mysql_error($otherDb));
        	                        }
                	        }
			}	
			else
			{
				$activatedPidstr=implode(",",$activatedPidArr);	
				$sql1="INSERT IGNORE into $table SELECT * FROM $delTable WHERE $whereColumn=$profileid AND $selectColumn in ($activatedPidstr)";
				$sql2="delete from $delTable where $whereColumn=$profileid AND $selectColumn in ($activatedPidstr)";

				$mysql->executeQuery($sql1,$db) or die(mysql_error($db));
                                $mysql->executeQuery($sql2,$db) or die(mysql_error($db));
			}
                }
        }
}
?>
