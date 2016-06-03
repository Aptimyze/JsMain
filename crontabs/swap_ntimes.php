<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
connect_db();

$sql="UPDATE SEARCH_MALE a, JP_NTIMES b SET a.NTIMES=b.NTIMES WHERE a.PROFILEID=b.PROFILEID";
mysql_query($sql) or die(mysql_error1());

$sql="UPDATE SEARCH_FEMALE a, JP_NTIMES b SET a.NTIMES=b.NTIMES WHERE a.PROFILEID=b.PROFILEID";
mysql_query($sql) or die(mysql_error1());

function mysql_error1()
{
	mail("vikas@jeevansathi.com,alok@jeevansathi.com,puneet.makkar@jeevansathi.com","Jeevansathi Error in swapping",mysql_error());
}

?>
