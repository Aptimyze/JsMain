<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");


	$dbSlave=connect_slave();

	$sql="select PROFILEID from JPROFILE WHERE ((YEAR(CURDATE())-YEAR(DTOFBIRTH)) - (RIGHT(CURDATE(),5)<RIGHT(DTOFBIRTH,5)))<>AGE AND RIGHT( DTOFBIRTH, 5 ) <> RIGHT(CURDATE(),5)";
	$result=mysql_query($sql,$dbSlave) or die(mysql_error($dbSlave));

	$db=connect_db();

	while($myrow=mysql_fetch_array($result))
	{
		$sql="update JPROFILE SET AGE=((YEAR(CURDATE())-YEAR(DTOFBIRTH)) - (RIGHT(CURDATE(),5)<RIGHT(DTOFBIRTH,5))) WHERE PROFILEID=". $myrow['PROFILEID'];
		mysql_query($sql,$db) or die(mysql_error($db));
	}
?>
