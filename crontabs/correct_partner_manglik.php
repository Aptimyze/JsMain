<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	
	include("connect.inc");
	
	$db=connect_db();
	
	$sql="delete from PARTNER_MANGLIK where MANGLIK='D'";
	mysql_query($sql) or die(mysql_error() . $sql);
	
	$sql="select count(*) as cnt,PARTNERID from PARTNER_MANGLIK group by PARTNERID having cnt > 1";
	$res=mysql_query($sql) or die(mysql_error() . $sql);
	
	while($myrow=mysql_fetch_row($res))
	{
		$sql="delete from PARTNER_MANGLIK where PARTNERID=" . $myrow[1];
		mysql_query($sql) or die(mysql_error() . $sql);
	}
?>