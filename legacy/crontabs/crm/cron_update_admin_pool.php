<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include('../connect.inc');
connect_db();

$ts             = time();
$ts            = $ts - 90*24*60*60;
$end_date       = date("Y-m-d",$ts);

$sql1 = "UPDATE incentive.MAIN_ADMIN_POOL m , incentive.DO_NOT_CALL d SET m.ALLOTMENT_AVAIL='Y' , d.REMOVED='Y' WHERE d.PROFILEID=m.PROFILEID AND d.ENTRY_DT BETWEEN '$end_date 00:00:00' AND '$end_date 23:59:59'";
mysql_query($sql1) or die("$sql1".mysql_error());

?>
