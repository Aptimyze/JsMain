<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");
	
	$db=connect_db();
	
	$sql="select PROFILEID from JPROFILE where ACTIVATED='D'";
	$result=mysql_query($sql) or die($sql);
	
	while($myrow=mysql_fetch_array($result))
	{
		$sql="insert ignore into DELETED_PROFILE_CONTACTS select * from CONTACTS where SENDER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="delete from CONTACTS where SENDER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="insert ignore into DELETED_PROFILE_CONTACTS select * from CONTACTS where RECEIVER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="delete from CONTACTS where RECEIVER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);

		/*$sql="insert ignore into MESSAGE_LOG_EXPIRE select * from MESSAGE_LOG where SENDER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="delete from MESSAGE_LOG where SENDER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="insert ignore into MESSAGE_LOG_EXPIRE select * from MESSAGE_LOG where RECEIVER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);
		
		$sql="delete from MESSAGE_LOG where RECEIVER='" . $myrow["PROFILEID"] . "'";
		mysql_query($sql) or die($sql);*/

		$sql="INSERT INTO DEL_MES_PID VALUES ('" . $myrow["PROFILEID"] . "')";
		mysql_query($sql);
	}
	
	mysql_free_result($result);
	
?>
