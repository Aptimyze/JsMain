<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
/*
$db2=connect_slave();

$today=date("Y-m-d");
list($yy,$mm,$dd)=explode("-",$today);
$ts=mktime(0,0,0,$mm,$dd,$yy);
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
$sql="SELECT COUNT(*) as cnt FROM newjs.JPROFILE WHERE LAST_LOGIN_DT='$today'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
$row=mysql_fetch_array($res);
$cnt=$row['cnt'];

mysql_close($db2);
*/

$db=connect_db();

$file="$docRoot/crontabs/login_count.txt";
if($fp=fopen($file,"r"))
{
	$sql=fgets($fp);
	fclose($fp);
}
else
{
	die("no fp");
}
//$sql="INSERT INTO MIS.DAY_LOGIN_COUNT(LAST_LOGIN_DT,COUNT) VALUES('$today','$cnt')";
mysql_query($sql,$db) or logError($sql,$db);


?>
