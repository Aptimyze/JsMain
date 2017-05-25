<?php
$fromCrontab = true;
include_once(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/ivr/knowlarityFunctions.php");
$db_master = connect_db();
$sql= "SELECT * FROM  test.unverified_profiles_test_list WHERE 1";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$profileid = $row["PROFILEID"];
	echo $r = getVirtualNumber($row['PROFILEID'],$row['PHONE_MOB']);
	echo "\n".$sql = "UPDATE test.unverified_profiles_test_list set VIRTUAL_NUMBER='$r' where PROFILEID = '$profileid'";
	mysql_query($sql) or die(mysql_error());
	die;
}
?>
