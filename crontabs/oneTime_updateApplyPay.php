<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();

$type 		='CL';
$start_dt 	='2017-04-01 00:00:00';
$end_dt		='2017-12-31 23:59:59';

echo $sql ="SELECT pd.ENTRY_DT,pd.BILLID,pd.RECEIPTID,pd.PROFILEID,pur_d.SERVICEID,pur_d.CUR_TYPE,pd.AMOUNT,pd.APPLE_COMMISSION,pur_d.SID,pur_d.PRICE,pur_d.NET_AMOUNT FROM billing.PAYMENT_DETAIL pd, billing.PURCHASE_DETAIL pur_d,billing.PURCHASES p,billing.ORDERS o WHERE p.BILLID=pd.BILLID AND p.PROFILEID=pd.PROFILEID AND pd.PROFILEID=pur_d.PROFILEID  AND pd.BILLID=pur_d.BILLID  AND o.ID=p.ORDERID  AND o.GATEWAY='APPLEPAY' AND pd.ENTRY_DT>='$start_dt'  AND pd.ENTRY_DT<='$end_dt' AND pur_d.SERVICEID='$type' ORDER BY pd.ENTRY_DT ASC";
$res = mysql_query($sql) or logError($sql);
while($row = mysql_fetch_array($res))
{
	$sid		=$row['SID'];
	$billid       	=$row['BILLID'];
	$profileid      =$row['PROFILEID'];
	$amount       	=$row['AMOUNT'];
	$apple_com      =$row['APPLE_COMMISSION'];
	
	$tot		=$amount+$apple_com;

	$sql2 ="update billing.PURCHASE_DETAIL SET AMOUNT='$tot',NET_AMOUNT='$tot' WHERE SID='$sid' AND BILLID='$billid' AND PROFILEID='$profileid' LIMIT 1";
	echo "\n$sql2";
	mysql_query($sql2) or logError($sql2);
}

?>
