<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");

$ts=time();
$ts-=24*60*60;
$date=date("Y-m-d",$ts);

$db_slave=connect_slave();
$sql="SELECT COUNT(DISTINCT VIEWED) AS CNT FROM newjs.FEATURED_PROFILE_LOG WHERE DATE='$date'";
$res=mysql_query($sql,$db_slave) or die(mysql_error1($sql,$db_slave));
$row=mysql_fetch_assoc($res);
$count=$row['CNT'];

$db=connect_db();
$sql="INSERT INTO MIS.FEATURED_PROFILE_COUNT (DATE,COUNT) VALUES ('$date',$count)";
$res=mysql_query($sql,$db) or die(mysql_error1($sql,$db));

function mysql_error1($sql,$db)
{
        mail("neha.verma@jeevansathi.com","error in fp_countMis.php",$sql.mysql_error($db));
}
?>
