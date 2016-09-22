<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("connect.inc");

$db=connect_ddl();

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
logTime();

$tm=date("Y-m-d H:i:s");

$sql="truncate table MIS.SEARCHQUERY_TEMP";
mysql_query($sql) or die(mysql_error());

$sql="insert into MIS.SEARCHQUERY_TEMP select * from newjs.SEARCHQUERY where DATE < DATE_SUB('$tm', interval 1 day)";
$res=mysql_query($sql) or die(mysql_error().$sql);

if($res)
{
	$sql="insert into MIS.SEARCHQUERY select * from MIS.SEARCHQUERY_TEMP";
	$res1=mysql_query($sql) or die(mysql_error().$sql);

	if($res1)
	{
		$sql="truncate table MIS.SEARCHQUERY_TEMP";
		mysql_query($sql) or die(mysql_error());

		$sql="DELETE FROM newjs.SEARCHQUERY WHERE DATE < DATE_SUB('$tm', interval 1 day)";
		mysql_query($sql) or die(mysql_error());
	}
}

$sql="DELETE FROM newjs.CONTACTED_PROFILES WHERE DATE < DATE_SUB('$tm', interval 1 day)";
mysql_query($sql) or die(mysql_error());

//$sql="optimize table newjs.SEARCHQUERY";
//mysql_query($sql) or die(mysql_error());

$ts=time();
$ts30=$ts-30*24*60*60;
$date30=date("Y-m-d",$ts30);

$sql="DELETE FROM newjs.FEATURED_PROFILE_LOG WHERE DATE<'$date30'";
mysql_query($sql) or die(mysql_error());

logTime();
?>
