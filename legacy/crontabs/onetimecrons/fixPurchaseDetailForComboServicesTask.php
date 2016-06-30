<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include('../connect.inc');
$db = connect_db();
$db_slave = connect_slave();

// BACKUP EXISTING DATA BEFORE MODIFICATION
//$replicateTablePur = "CREATE TABLE billing.PURCHASES_BACKUP_NOV_26 LIKE billing.PURCHASES";
//mysql_query($replicateTablePur,$db);
//$populateTablePur = "INSERT INTO billing.PURCHASES_BACKUP_NOV_26 SELECT * FROM billing.PURCHASES";
//mysql_query($populateTablePur,$db);
//$replicateTablePurDet = "CREATE TABLE billing.PURCHASE_DETAIL_BACKUP_NOV_26 LIKE billing.PURCHASE_DETAIL";
//mysql_query($replicateTablePurDet,$db);
//$populateTablePurDet = "INSERT INTO billing.PURCHASE_DETAIL_BACKUP_NOV_26 SELECT * FROM billing.PURCHASE_DETAIL";
//mysql_query($populateTablePurDet,$db);
// END BACKUP

// FETCH ALL PURCHASES SINCE 1ST APRIL 2015
$sql="SELECT BILLID, SERVICEID, PROFILEID FROM billing.PURCHASES WHERE ENTRY_DT >= '2015-04-01 00:00:00'"; 
$res=mysql_query($sql,$db_slave) or die(mysql_error($db_slave)); 
while($row = mysql_fetch_array($res)){
	$billid = $row['BILLID'];
	$profileid = $row['PROFILEID'];
	//print_r(array($billid, $profileid));
	// FETCH ACTUAL TRANSACTION AMOUNT PAID BY USER PER PURCHASE
	$sql2="SELECT AMOUNT FROM billing.PAYMENT_DETAIL WHERE BILLID = {$billid}";
	$res2=mysql_query($sql2,$db_slave) or die(mysql_error($db_slave));
	while($row2 = mysql_fetch_array($res2)){
		$purchasedAmount = $row2['AMOUNT'];

		// FETCH STORED DETAILS IN PURCHASE_DETAIL FOR CORRESPONDING BILLID
		$sql3="SELECT * FROM billing.PURCHASE_DETAIL WHERE BILLID = {$billid}";
		$res3=mysql_query($sql3,$db_slave) or die(mysql_error($db_slave));
		while($row3 = mysql_fetch_array($res3)){
			// CALCULATE THE ACTUAL SERVICE PRICE INSTEAD OF DISCOUNTED PRICE
			$fullServicePrice += $row3['PRICE'];
		}
		$totalDiscount = $fullServicePrice - $purchasedAmount;
		$actualPercentageDiscount = round(($totalDiscount/$fullServicePrice),5);
		//print_r(array($purchasedAmount, $fullServicePrice, $totalDiscount, $actualPercentageDiscount));

		// UPDATE EACH TRANSACTION AS PER NEWLY CALCULATED DISCOUNTED PRICE
		$sql4="SELECT * FROM billing.PURCHASE_DETAIL WHERE BILLID = {$billid}";
		$res4=mysql_query($sql4,$db_slave) or die(mysql_error($db_slave));
		while($row4 = mysql_fetch_array($res4)){ 
			$newDiscount = round($row4['PRICE']*$actualPercentageDiscount,0);
			$newNetAmount = $row4['PRICE'] - $newDiscount;
			$serviceid = $row4['SERVICEID'];
			$sql5="UPDATE billing.PURCHASE_DETAIL SET DISCOUNT = {$newDiscount}, NET_AMOUNT = {$newNetAmount} WHERE BILLID= {$billid} AND SERVICEID = '$serviceid' AND PROFILEID = {$profileid}";
			//print_r(array($newDiscount, $newNetAmount, $serviceid));
			$res5=mysql_query($sql5,$db) or die(mysql_error($db));
			$newTotalDiscount += $newDiscount; 
		}

	}

	if($newTotalDiscount){
		// FINALLY UPDATE THE PURCHASES DISCOUNT FOR THE SAME PROFILEID
		$sql6="UPDATE billing.PURCHASES SET DISCOUNT = {$newTotalDiscount} WHERE BILLID = {$billid} AND PROFILEID = {$profileid}";
		$res6=mysql_query($sql6,$db) or die(mysql_error($db));
	}

	// CLEAR ALL VARIABLES
	unset($billid);
	unset($profileid);
	unset($purchasedAmount);
	unset($fullServicePrice);
	unset($totalDiscount);
	unset($actualPercentageDiscount);
	unset($newDiscount);
	unset($newNetAmount);
	unset($newTotalDiscount);
	unset($serviceid);
}

?>
