<?php
/*
include("connect.inc");
*/
/*
$mypid=$_SERVER['argv'][1];
$profileid=$_SERVER['argv'][2];
$mypid=156193;	
$profileid=786528;
*/
$mysqlObj=new Mysql;
/*
if(!$mypid || !$profileid)
	exit();
*/
//$updateDb=connect_db();
$updateDb=$mysqlObj->connect("master");
//update_table_master($mypid,$profileid,'newjs.BOOKMARKS','BOOKMARKER','BOOKMARKEE',$updateDb);
update_table_master($mypid,$profileid,'userplane.CHAT_REQUESTS','SENDER','RECEIVER',$updateDb);
//update_table_master($mypid,$profileid,'newjs.IGNORE_PROFILE','SENDER','RECEIVER',$updateDb);
update_table_master($mypid,$profileid,'jsadmin.OFFLINE_MATCHES','PROFILEID','MATCH_ID',$updateDb);

$myDbName=getProfileDatabaseConnectionName($mypid,'',$mysqlObj);
$updateDb=$mysqlObj->connect("$myDbName");
if($updatecontact)
	update_table_master($mypid,$profileid,'newjs.CONTACTS','SENDER','RECEIVER',$updateDb);
update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID','PROFILEID_REQ_BY',$updateDb);
update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID','PROFILEID_REQUEST_BY',$updateDb);
update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID_REQ_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID_REQUEST_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
if($updatecontact)
	update_table_master($mypid,$profileid,'newjs.MESSAGE_LOG','SENDER','RECEIVER',$updateDb);

$myDbName2=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
$updateDb=$mysqlObj->connect("$myDbName2");
if($myDbName2!=$myDbName)
{
	if($updatecontact)
		update_table_master($mypid,$profileid,'newjs.CONTACTS','SENDER','RECEIVER',$updateDb);
	update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID','PROFILEID_REQ_BY',$updateDb);
	update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID','PROFILEID_REQUEST_BY',$updateDb);
	update_table_master($mypid,$profileid,'newjs.PHOTO_REQUEST','PROFILEID_REQ_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
	update_table_master($mypid,$profileid,'newjs.HOROSCOPE_REQUEST','PROFILEID_REQUEST_BY','PROFILEID',$updateDb,'UPLOAD_SEEN');
	if($updatecontact)
		update_table_master($mypid,$profileid,'newjs.MESSAGE_LOG','SENDER','RECEIVER',$updateDb);
}

//$updateDb = connect_211();
$updateDb=$mysqlObj->connect("211");
$SUFFIX=getsuffix($mypid);
//$SUFFIX=getsuffix($profileid);
update_table_master($mypid,$profileid,'newjs.VIEW_LOG_TRIGGER_'.$SUFFIX,'VIEWER','VIEWED',$updateDb);
//mysql_query($sql,$updateDb);


function update_table_master($mypid,$profileid,$table_name,$FIELD1,$FIELD2,$updateDb,$updateY='')
{
	if($table_name=="newjs.CONTACTS")
	{
		$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND TYPE='I'";
		mysql_query($sql,$updateDb);
		//echo "<br>".$sql;
		$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$mypid AND $FIELD2=$profileid AND TYPE IN('A','D')";
                mysql_query($sql,$updateDb);
		//echo "<br>".$sql;
		//$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$mypid AND $FIELD2=$profileid AND TYPE='D'";
                //mysql_query($sql,$updateDb);
		//echo "<br>".$sql;
	}
	else
	{	
		if($updateY)
			$sql="UPDATE $table_name SET $updateY='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid AND $updateY='U'";
		else
			$sql="UPDATE $table_name SET SEEN='Y' WHERE $FIELD1=$profileid AND $FIELD2=$mypid";
		mysql_query($sql,$updateDb);
		//echo "<br>";
		//echo "<br>";
		//echo "\n".$sql;
	}
}
?>
