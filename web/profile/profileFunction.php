<?php
	global $CALL_NOW;
	global $CALL_DIRECT;
	$CALL_DIRECT=1;
	$CALL_NOW=0;
	if($CALL_DIRECT)
		$smarty->assign("CALL_DIRECT",1);
	if($CALL_NOW)
		$smarty->assign("CALL_NOW",1);
		
	//To be used in contact engine	
	global $FAMILY_VALUES,$GENDER,$RELATIONSHIP,$PHOTO_PRIVACY,$NUMBER_OWNER;
	global $SUBSCRIPTION,$ORIGINAL_SUBSCRIPTION;
	include("$_SERVER[DOCUMENT_ROOT]/profile/arrays.php");

	//To be used in setting contact engine
	global $from_symfony;
	$from_symfony=1;	
	function check_any_contact($logged_pid,$profileid,$type)
	{

		if(!($logged_pid && $profileid))
			return 0;
		if($type!="")
			return 1;
		
		$sql="select STATUS from jsadmin.OFFLINE_MATCHES where MATCH_ID='$logged_pid' and PROFILEID='$profileid'";
		$res=mysql_query_decide($sql)  or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_init,"ShowErrTemplate");
		if($row=mysql_fetch_array($res))
		return 1;
		

		global $db,$smarty,$myDb,$mysqlObj;
		if(!$myDb || !$mysqlObj)
		{
			if($logged_pid)
			{
				$mysqlObj=new Mysql;
				$myDbName=getProfileDatabaseConnectionName($logged_pid,'',$mysqlObj);
				$myDb=$mysqlObj->connect("$myDbName");
			}
		}
		$sql="select count(*) as cnt from newjs.BOOKMARKS where BOOKMARKER=$logged_pid and BOOKMARKEE='$profileid'";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($myrow=mysql_fetch_row($result))
		{
			if($myrow[0]>0)
			return 1;
		}
		
		$sql="select count(*) as cnt from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$logged_pid' and PROFILEID='$profileid' UNION select count(*) as cnt from `HOROSCOPE_REQUEST` where PROFILEID_REQUEST_BY='$profileid' and PROFILEID='$logged_pid'";

		$result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=$mysqlObj->fetchArray($result))
		{
			if($myrow['cnt']>0)
			return 1;
		}
		$sql="select count(*) as cnt from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$logged_pid' and PROFILEID='$profileid' UNION select count(*) as cnt from `PHOTO_REQUEST` where PROFILEID_REQ_BY='$profileid' and PROFILEID='$logged_pid'";

		$result=$mysqlObj->executeQuery($sql,$myDb) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=$mysqlObj->fetchArray($result))
		{
			if($myrow['cnt']>0)
			return 1;
		}
		$sql="select count(*) as cnt from userplane.CHAT_REQUESTS where SENDER='$logged_pid' and RECEIVER='$profileid' union select count(*) as cnt from userplane.CHAT_REQUESTS where SENDER='$profileid' and RECEIVER='$logged_pid' ";
		$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($myrow=mysql_fetch_array($result))
		{
			if($myrow['cnt']>0)
			return 1;
		}
	}
?>
