<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");
	
	// take mysql connection on slave
	$db=connect_slave();
	
	$sql="select min(MAILID) as MINMAIL from SENDMAIL where STATUS in ('N','S')";
	$result=mysql_query($sql);
	
	if(mysql_num_rows($result)>0)
	{
		$myrow=mysql_fetch_array($result);
		
		$cnt=$myrow["MINMAIL"];

		// close mysql connection on slave
		mysql_close($db);
		
		// take mysql connection on master
		$db=connect_db();
		
		$sql="delete from SENDMAIL where MAILID < $cnt";
		mysql_query($sql);

		//$sql="optimize table SENDMAIL";
		//mysql_query($sql);
	}
?>
