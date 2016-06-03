<?php
	include ("connect.inc");

	$sql="show table status from mmmjs like '____mailer\_s%'";
	$result=mysql_query($sql) or die(mysql_error());

	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["Update_time"] <= '2010-10-31')
		{
			$sql="drop table " . $myrow["Name"];
			mysql_query($sql);
		}
	}
?>
