<?php
include_once("/usr/local/scripts/DocRoot.php");
include("connect.inc");
$db = connect_db();
$db_slave = connect_db();
$entryDate = "2017-04-01 00:00:00";

echo $sql = "SELECT RECEIPTID,BILLID,PROFILEID,APPLE_COMMISSION,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>='$entryDate' and APPLE_COMMISSION>0 AND APPLE_COMMISSION IS NOT NULL";
$res = mysql_query_decide($sql, $db_slave) or die("$sql" . mysql_error_js($db_slave));

    while ($row = mysql_fetch_array($res)) {
	$receiptid	=$row["RECEIPTID"];
	$billid		=$row["BILLID"];
	$profileid      =$row["PROFILEID"];
	$amount		=$row["AMOUNT"];
	$appleComm	=$row["APPLE_COMMISSION"];
	
	echo "\n".$sql1 = "UPDATE incentive.MONTHLY_INCENTIVE_ELIGIBILITY SET AMOUNT ='$amount', APPLE_COMMISSION ='$appleComm' where PROFILEID='$profileid' and RECEIPTID ='$receiptid' AND BILLID ='$billid'";

	//$res1=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js($db));
    }
?>
