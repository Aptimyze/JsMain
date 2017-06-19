<?php
/*********************************************************************************************
Script name     :       deleteTestContacts.php
Script Type     :       Cron
Created On      :       15 Sep 09
Created By      :       Tanu Gupta
Description     :       Deletes contacts made by some test users
**********************************************************************************************/

include_once("connect.inc");
// connect to database
$db=connect_db();

/*$usernameArr = array(
		"'TAA0886'",
		"'TAA0913'",
		"'TAA0936'",
		"'TAA1083'",
		"'TAA1097'",
		"'TAA1269'",
		"'TAA1329'",
		"'TAA1253'",
		"'TAA1271'",
		"'TAA1420'"
		);

$usernames = implode(",",$usernameArr);
$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME IN($usernames)";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res))
{
	$profileIdArr[] = $row["PROFILEID"];
}
$profileIds = implode(",", $profileIdArr);*/

$profileIds = "4676516,4676543,4676566,4676712,4676726,4676882,4676898,4676900,4676958,4677049";
$profileIdArr = explode(",",$profileIds);
$mysqlObj=new Mysql;
foreach($profileIdArr as $key=>$val)
{
	$profileId = $val;
	$myDbName=getProfileDatabaseConnectionName($profileId,'',$mysqlObj);
	$myDb=$mysqlObj->connect("$myDbName");
	$sql = "DELETE FROM CONTACTS WHERE SENDER = '$profileId' AND RECEIVER IN($profileIds)";
	$mysqlObj->executeQuery($sql, $myDb);
	$sql = "SELECT ID FROM MESSAGE_LOG WHERE SENDER = '$profileId' AND RECEIVER IN($profileIds)";
	$res = $mysqlObj->executeQuery($sql,$myDb);
	while($row = mysql_fetch_array($res))
	{
		$id = $row["ID"];
		$sql = "DELETE FROM MESSAGE_LOG WHERE ID = '$id'";
		$mysqlObj->executeQuery($sql,$myDb);
		$sql = "DELETE FROM MESSAGES WHERE ID = '$id'";
		$mysqlObj->executeQuery($sql,$myDb);
	}	
	//$sql="DELETE FROM MIS.SEARCH_CONTACT_FLOW_TRACKING WHERE PROFILEID = '$profileId'";
	//$mysqlObj->executeQuery($sql,$myDb);
	//$sql="DELETE FROM MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW WHERE PROFILEID = '$profileId'";
	//$mysqlObj->executeQuery($sql,$myDb);
	$sql = "DELETE FROM PHOTO_REQUEST WHERE PROFILEID='$profileId' OR PROFILEID_REQ_BY='$profileId'"; 
	$mysqlObj->executeQuery($sql,$myDb);
	$sql = "DELETE FROM HOROSCOPE_REQUEST WHERE PROFILEID='$profileId' OR PROFILEID_REQUEST_BY='$profileId'";
	$mysqlObj->executeQuery($sql,$myDb);
	/******Commented as there is no use of the table anywhere
	$sql = "DELETE FROM MIS.SIMILLAR_CONTACT_COUNT WHERE PROFILEID = '$profileId'";
	mysql_query_decide($sql);
	*****************/
}

echo "done";
?>
