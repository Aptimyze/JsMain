<?
$dirname=dirname(__FILE__);
chdir($dirname);
include('connect.inc');
//mysql_close();

$profileid = $argv[1];

$mysqlObj=new Mysql;
//$db2 = connect_slave();
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId);
        $myDbarr[$myDbName]=$mysqlObj->connect("$myDbName");

}
$db = connect_db();

//Logging deleted profiles to run check in the night to delete any left over contacts
$sql="INSERT INTO newjs.DELETED_PROFILE_LOG(PROFILEID,DATE) VALUES('$profileid',NOW())";
mysql_query($sql,$db);

$affectedId=array();

$myDbname=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);

//Sharding of CONTACTS and DELETED_PROFILE_CONTACTS done by Sadaf
//finding the contactid(s) where sender is the profile being deleted.
if(count($myDbarr))
{
foreach($myDbarr as $key=>$value)
{
	//added BY Lavesh
	//FOR HOROSCOPE REQUEST
	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];
	if($myDbname==$key)
	{
		$sql="SELECT PROFILEID_REQUEST_BY  FROM HOROSCOPE_REQUEST WHERE PROFILEID=$profileid";
		$result=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
		while($myrow=$mysqlObj->fetchAssoc($result))
		{
        		$pidArr[]=$myrow['PROFILEID_REQUEST_BY'];
		}
	}
	$sql="INSERT IGNORE INTO DELETED_HOROSCOPE_REQUEST SELECT * FROM HOROSCOPE_REQUEST WHERE PROFILEID=$profileid"; 
	$mysqlObj->executeQuery($sql,$myDb);

	$sql="DELETE FROM HOROSCOPE_REQUEST WHERE PROFILEID=$profileid";
	$mysqlObj->executeQuery($sql,$myDb);

	$sql="INSERT IGNORE INTO DELETED_HOROSCOPE_REQUEST SELECT * FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY=$profileid";
	$mysqlObj->executeQuery($sql,$myDb);

	$sql="DELETE FROM HOROSCOPE_REQUEST WHERE PROFILEID_REQUEST_BY=$profileid";
	$mysqlObj->executeQuery($sql,$myDb);

	if($myDbname==$key && is_array($pidArr))
	{
		$affected_pid_str=implode(",",$pidArr);
		unset($pidArr);
		unset($affected_pid_str);
	}
	//FOR HOROSCOPE REQUEST
		
	//FOR PHOTO REQUEST
	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];
        if($myDbname==$key)
        {
                $sql="SELECT PROFILEID_REQ_BY  FROM PHOTO_REQUEST WHERE PROFILEID=$profileid";
                $result=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
                while($myrow=$mysqlObj->fetchAssoc($result))
                {
                        $pidArr[]=$myrow['PROFILEID_REQ_BY'];
                }
        }
        $sql="INSERT IGNORE INTO DELETED_PHOTO_REQUEST SELECT * FROM PHOTO_REQUEST WHERE PROFILEID=$profileid";
        $mysqlObj->executeQuery($sql,$myDb);

        $sql="DELETE FROM PHOTO_REQUEST WHERE PROFILEID=$profileid";
        $mysqlObj->executeQuery($sql,$myDb);

        $sql="INSERT IGNORE INTO DELETED_PHOTO_REQUEST SELECT * FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY=$profileid";
        $mysqlObj->executeQuery($sql,$myDb);

        $sql="DELETE FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY=$profileid";
        $mysqlObj->executeQuery($sql,$myDb);

        if($myDbname==$key && is_array($pidArr))
        {
                $affected_pid_str=implode(",",$pidArr);
                unset($pidArr);
                unset($affected_pid_str);
        }
	//FOR PHOTO REQUEST

	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];
	delete_and_move_records_on_deleteProfile("BOOKMARKS",$profileid,$db,array('BOOKMARKER','BOOKMARKEE'),$mysqlObj);

	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];
	delete_and_move_records_on_deleteProfile("IGNORE_PROFILE",$profileid,$db,array('PROFILEID','IGNORED_PROFILEID'),$mysqlObj);
	//added by Lavesh

	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];

	$sql="select CONTACTID, TYPE, RECEIVER from newjs.CONTACTS where SENDER='$profileid'";
	$result=$mysqlObj->executeQuery($sql,$myDb);
	while($myrow=$mysqlObj->fetchArray($result))
	{
		$contactid=$myrow["CONTACTID"];
		//inserting into DELETED_PROFILE_CONTACTS.
		$sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
		$res=$mysqlObj->executeQuery($sql,$myDb);
		if($res)
		{
			//deleting the records from CONTACTS table.
			$sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
			$mysqlObj->executeQuery($sql,$myDb);

			//updating the counts in leftpanel.
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
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='A')
					$CONTACT_STATUS_FIELD['ACC_BY_ME']=-1; 
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='D')
					$CONTACT_STATUS_FIELD['DEC_BY_ME']=-1;
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";

				if(!in_array($myrow["RECEIVER"],$affectedId))
				{
					updatememcache($CONTACT_STATUS_FIELD,$myrow["RECEIVER"],1);
		                        unset($CONTACT_STATUS_FIELD);
					//mysql_query_decide($sql_upd,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
					$affectedId[]=$myrow["RECEIVER"];
				}
			}
		}
	}
	mysql_free_result($result);

	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];

	//finding the contactid(s) where receiver is the profile being deleted.
	$sql="select CONTACTID, TYPE, SENDER from newjs.CONTACTS where RECEIVER='$profileid'";
	$result=$mysqlObj->executeQuery($sql,$myDb);
	while($myrow=$mysqlObj->fetchArray($result))
	{
		$contactid=$myrow["CONTACTID"];
		//inserting into DELETED_PROFILE_CONTACTS.
		$sql="insert ignore into newjs.DELETED_PROFILE_CONTACTS select * from newjs.CONTACTS where CONTACTID='$contactid'";
		$res=$mysqlObj->executeQuery($sql,$myDb);
		if($res)
		{
			//deleting the records from CONTACTS table.
			$sql="delete from newjs.CONTACTS where CONTACTID='$contactid'";
			$mysqlObj->executeQuery($sql,$myDb);

			//updating the counts in leftpanel.
			if($myrow['TYPE']!='C')
			{
				unset($CONTACT_STATUS_FIELD);
				if($myrow['TYPE']=='I')
				{
					$CONTACT_STATUS_FIELD['NOT_REP']=-1;
					$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$myrow[SENDER]'";
				}
				elseif($myrow['TYPE']=='A')
				{
					$CONTACT_STATUS_FIELD['ACC_ME']=-1;
					$CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
					if($myrow["SEEN"]!='Y')
						$CONTACT_STATUS_FIELD['ACC_ME_NEW']=-1;
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				}
				elseif($myrow['TYPE']=='D')
				{
					 $CONTACT_STATUS_FIELD['DEC_ME']=-1;
					 $CONTACT_STATUS_FIELD['TOTAL_CONTACTS_MADE']=-1;
					 if($myrow["SEEN"]!='Y')
						$CONTACT_STATUS_FIELD['ACC_ME_NEW']=-1;
					//$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				}
				if(!in_array($myrow["SENDER"],$affectedId))
				{	
					updatememcache($CONTACT_STATUS_FIELD,$myrow["SENDER"],1);
                                        unset($CONTACT_STATUS_FIELD);
					//mysql_query_decide($sql_upd,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
					$affectedId[]=$myrow["SENDER"];
				}
			}
		}
	}
	mysql_free_result($result);
	
	$mysqlObj->ping($myDbarr[$key]);
	$myDb=$myDbarr[$key];
	//finding the id(s) from MESSAGE_LOG where sender is the profile being deleted.
	$sql="select ID,RECEIVER,RECEIVER_STATUS from newjs.MESSAGE_LOG where SENDER='$profileid'";
	$result=$mysqlObj->executeQuery($sql,$myDb) or die($sql);
	while($myrow=mysql_fetch_array($result))
	{
		$contactid=$myrow["ID"];
		//inserting into DELETED_MESSAGE_LOG.
		$sql="insert ignore into newjs.DELETED_MESSAGE_LOG select * from newjs.MESSAGE_LOG where ID='$contactid'";
		$res=$mysqlObj->executeQuery($sql,$myDb) or die($sql);
		if($res)
		{
			//deleting from MESSAGE_LOG table.
			$sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
			$mysqlObj->executeQuery($sql,$myDb) or die($sql);
		}
	
	}
	
	mysql_free_result($result);
	//finding the id(s) from MESSAGE_LOG where receiver is the profile being deleted.
	$sql="select ID from newjs.MESSAGE_LOG where RECEIVER='$profileid'";
	$result=$mysqlObj->executeQuery($sql,$myDb) or die($sql);;
	while($myrow=$mysqlObj->fetchArray($result))
	{
		$contactid=$myrow["ID"];
		//inserting into DELETED_MESSAGE_LOG.
		$sql="insert ignore into newjs.DELETED_MESSAGE_LOG select * from newjs.MESSAGE_LOG where ID='$contactid'";
		$res=$mysqlObj->executeQuery($sql,$myDb) or die($sql);
		if($res)
		{
			//deleting from MESSAGE_LOG table.
			$sql="delete from newjs.MESSAGE_LOG where ID='$contactid'";
			$mysqlObj->executeQuery($sql,$myDb) or die($sql);
		}
	}
	mysql_free_result($result);
}
}

//deleting the record from CONTACTS_STATUS table.
$sql_del = "DELETE FROM newjs.CONTACTS_STATUS WHERE PROFILEID='$profileid'";
mysql_query_decide($sql_del,$db);

$db_211 = connect_211();
//Deleting records from VIEW_LOG_TRIGGER table.
//$SUFFIX=getsuffix($profileid);
$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER WHERE VIEWED='$profileid'";
mysql_query_decide($sql_delete,$db_211);

$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER  WHERE VIEWER='$profileid'";
mysql_query_decide($sql_delete,$db_211);
//@mysql_close($db_211);

//Added By Lavesh
function delete_and_move_records_on_deleteProfile($table,$pid,$myDb,$whereArr,$mysqlObj)
{
	$deltable="DELETED_".$table;

	foreach($whereArr as $key=>$value)
	{
		$sql="INSERT IGNORE INTO $deltable SELECT * FROM $table WHERE $value=$pid"; 
		$mysqlObj->executeQuery($sql,$myDb);

		$sql="DELETE FROM $table WHERE $value=$pid";
		$result=$mysqlObj->executeQuery($sql,$myDb);
	}
}

?>
