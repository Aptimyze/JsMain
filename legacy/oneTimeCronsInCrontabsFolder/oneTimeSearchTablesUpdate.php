<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/**
This is a one time cron used to update the value of column SEARCH_MALE.NTIMES AND SEARCH_FEMALE.NTIMES from the table JP_NTIMES.
Author: prinka
**/

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days

include_once("../profile/config.php");
include("../classes/Mysql.class.php");
include("connect.inc");
$db = connect_db();
$db_737 = connect_737();

$genderArr[]='MALE';
$genderArr[]='FEMALE';

foreach($genderArr as $gender)
{
	$sql = "SELECT PROFILEID FROM SEARCH_$gender ";
	$res = mysql_query($sql,$db_737) or die(mysql_error().$sql);
	
	while($row = mysql_fetch_array($res))
	{
		$profileid = $row['PROFILEID'];
		$update = "UPDATE SEARCH_$gender S,JP_NTIMES JP SET S.NTIMES=JP.NTIMES WHERE JP.PROFILEID=$profileid AND S.PROFILEID=JP.PROFILEID";
		mysql_query($update,$db) or die(mysql_error().$update);
	}
}
?>
