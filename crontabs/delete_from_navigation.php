<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
$db2=connect_ddl();

$ts=time();
$ts-=24*60*60;
$today=date("nj",$ts);
$ts2=time()-2*24*60*60;
$today2=date("nj",$ts2);
for($i=1;$i<11;$i++)
{
//	$sql="delete from navig.NAVIGATION_$i where monthday<=$today ";
	$sql="truncate table navig.NAVIGATION_$i";
	mysql_query_decide($sql);
}
 mail("nikhil.dhiman@jeevansathi.com","NAVIGATION truncated on ".date('Y-m-d'),"NAVIGATION truncated on ".date('Y-m-d'));
?>
