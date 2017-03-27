<?php
include("connect.inc");

$db=connect_db();
$data=authenticated($checksum);
if($hideDelete)
	$pswrd=rawurldecode($pswrd);
if($data)
{
	$sql="SELECT PASSWORD FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$data[PROFILEID]'";
	$res=mysql_query_decide($sql) or logError($sql);
	$row=mysql_fetch_assoc($res);
	include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
	if(PasswordHashFunctions::validatePassword($pswrd, $row['PASSWORD']))
		echo "true";
	else
		echo "false";
	mysql_free_result($res);
}
else
	echo "Login";
?>
