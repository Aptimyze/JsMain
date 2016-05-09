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
//Sharding of CONTACTS and DELETED_PROFILE_CONTACTS done by Sadaf
//finding the contactid(s) where sender is the profile being deleted.
if(count($myDbarr))
{
foreach($myDbarr as $key=>$value)
{
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
				if($myrow['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET OPEN_CONTACTS=OPEN_CONTACTS-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_BY_ME=ACC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_BY_ME=DEC_BY_ME-1 WHERE PROFILEID='$myrow[RECEIVER]'";
				if(!in_array($myrow["RECEIVER"],$affectedId))
				{
					mysql_query_decide($sql_upd,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
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
				if($myrow['TYPE']=='I')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET NOT_REP=NOT_REP-1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='A')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET ACC_ME=ACC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				elseif($myrow['TYPE']=='D')
					$sql_upd = "UPDATE newjs.CONTACTS_STATUS SET DEC_ME=DEC_ME-1 WHERE PROFILEID='$myrow[SENDER]'";
				if(!in_array($myrow["SENDER"],$affectedId))
				{	
					mysql_query_decide($sql_upd,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_upd,"ShowErrTemplate");
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
	
		//added by Nikhil--> Update contact status new message field value, if sender delete it's profile
		if($myrow['RECEIVER_STATUS']=='U')
		{		
			$sql="update newjs.CONTACTS_STATUS set NEW_MES=NEW_MES-1 where PROFILEID='".$myrow['RECEIVER']."'";
			mysql_query_decide($sql,$db);
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
$SUFFIX=getsuffix($profileid);
$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER_$SUFFIX WHERE VIEWED='$profileid'";
mysql_query_decide($sql_delete,$db_211);
for($SUFFIX=1;$SUFFIX<16;$SUFFIX++)
{
	$sql_delete="DELETE FROM newjs.VIEW_LOG_TRIGGER_$SUFFIX  WHERE VIEWER='$profileid'";
	mysql_query_decide($sql_delete,$db_211);
}
//@mysql_close($db_211);
?>
