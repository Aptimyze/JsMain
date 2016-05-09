<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


	include("connect.inc");

	connect_db();

	logTime();
	$today=date("Y-m-d");

	$sql="SELECT PROFILEID FROM JPROFILE WHERE ACTIVATED='H'";
	if($res=mysql_query($sql))
	{
		while($row=mysql_fetch_assoc($res))
		{
			$profileid=$row['PROFILEID'];

			$sql="UPDATE JPROFILE SET ACTIVATED=PREACTIVATED WHERE PROFILEID='$profileid' AND ACTIVATE_ON<='$today'";
			mysql_query($sql) or logError($sql);
		}
	}
	else
		logError($sql);
	logTime();
?>
