<?php
if($in==1)
	$doo="PROFILEID:".$profileid ."   LOGINUSERNAME:".$username1."   USERNAME:".$username2." pwd:".$pwd." CHECKSUM:".$checksum;
else if($in==2)
	$doo="PROFILEID:".$profileid ."   LOGINUSERNAME:".$username1."   USERNAME:".$username2." CHECKSUM:".$checksum;
else if($in==3)
	$doo="PROFILEID:".$profileid ."   USERNAME:".$username2." pwd:".$pwd." CHECKSUM:".$checksum;
else if($in==4)
	$doo="PROFILEID:".$profileid ."   LOGINUSERNAME:".$username1." pwd:".$pwd." CHECKSUM:".$checksum;
else if($in==5)	
	$doo="PROFILEID:".$profileid ."  CHECKSUM:".$checksum;
else if($in==6)
	$doo="PROFILEID:".$profileid ."USERNAME:".$username;
else if($in==7)
	$doo="PROFILEID:".$profileid ."USERNAME:".$username."--> Did not connect";
$doo.=" String : $errorstring";
//echo $doo;
$msg="echo \"$doo\n";
if($in==7)
{
	include('../profile/connect.inc');
	$db=@connect_db();
	@mysql_select_db("userplane",$db);
	$sql="INSERT IGNORE INTO userplane.USERS_CNC VALUES('$profileid',CURDATE())";
	$res=mysql_query_decide($sql,$db) or die();
}
else
{
	$msg.="\" >> loginerror0301";
	passthru($msg);
}
?>
