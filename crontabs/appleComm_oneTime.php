<?php

$curFilePath = dirname(__FILE__) . "/";
include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
$db = connect_db();
$db_slave = connect_db();
$entryDate = "2017-04-16 00:00:00";
$sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>=$entrydate and APPLE_COMMISSION>0 AND APPLE_COMMISSION IS NOT NULL";
$res = mysql_query_decide($sql, $db_slave) or die("$sql" . mysql_error_js($db_slave));

    while ($row = mysql_fetch_array($res)) {
	$recieptid	=$row["RECIEPTID"];
	$billid		=$row["BILLID"];
	$profileid      =$row["PROFILEID"];
	$amount		=$row["AMOUNT"];
	$appleComm	=$row["APPLE_COMMISSION"];
	$sql1 = "UPDATE incentive.MONTHLY_INCENTIVE_ELIGIBILITY SET AMOUNT = $amount, APPLE_COMMISSION = $appleComm where PROFILEID=$profileid and RECIEPTID = $recieptid AND BILLID = $billid";

	$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
    }
?>
