<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db = connect_db();

$sql ="SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT WHERE TYPE IS NULL AND SDATE='2017-12-19' AND EDATE='2017-12-21'";
$res = mysql_query($sql,$db) or logError($sql);
while($row = mysql_fetch_array($res)){

	echo "\n".$profileid =$row['PROFILEID'];

	$sql1 ="DELETE FROM billing.VARIABLE_DISCOUNT where PROFILEID='$profileid'";
	mysql_query($sql1,$db) or logError($sql1);

        $sql2 ="DELETE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION where PROFILEID='$profileid'";
        mysql_query($sql2,$db) or logError($sql2);

}
?>
