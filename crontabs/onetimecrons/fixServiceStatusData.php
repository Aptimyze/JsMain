<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
connect_db();
$todaysDt = '2015-09-09';

$sql_crm ="SELECT * FROM billing.PURCHASE_DETAIL WHERE END_DATE<'$todaysDt'";
$res_crm = mysql_query($sql_crm) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{

	$profileid =$row_crm['PROFILEID'];
	$serviceid =$row_crm['SERVICEID'];
	$billid =$row_crm['BILLID'];
	$startDt =$row_crm['START_DATE'];
	$endDt =$row_crm['END_DATE'];
	if(strtotime($endDt)<strtotime($todaysDt)){
		$active = 'N';
	}
	$sql1 ="UPDATE billing.SERVICE_STATUS SET ACTIVATED='$active', ACTIVATED_ON='$startDt', ACTIVATE_ON='0000-00-00', EXPIRY_DT='$endDt' WHERE PROFILEID=$profileid AND BILLID=$billid AND SERVICEID='$serviceid'";
	$res1 = mysql_query($sql1) or logError($sql1);
	echo $sql1."\n";
}

$sql_crm ="SELECT * FROM billing.PURCHASE_DETAIL WHERE END_DATE>='$todaysDt'";
$res_crm = mysql_query($sql_crm) or logError($sql_crm);
while($row_crm = mysql_fetch_array($res_crm))
{

	$profileid =$row_crm['PROFILEID'];
	$serviceid =$row_crm['SERVICEID'];
	$billid =$row_crm['BILLID'];
	$startDt =$row_crm['START_DATE'];
	$endDt =$row_crm['END_DATE'];
	if(strtotime($endDt)>=strtotime($todaysDt) && strtotime($startDt)<=strtotime($todaysDt)){
		$active = 'Y';
		$sql1 ="UPDATE billing.SERVICE_STATUS SET ACTIVATED='$active', ACTIVATED_ON='$startDt', ACTIVATE_ON='0000-00-00', EXPIRY_DT='$endDt' WHERE PROFILEID=$profileid AND BILLID=$billid AND SERVICEID='$serviceid'";
	} else if(strtotime($startDt)>strtotime($todaysDt)){
		$active = 'N';
		$sql1 ="UPDATE billing.SERVICE_STATUS SET ACTIVATED='$active', ACTIVATED_ON='0000-00-00', ACTIVATE_ON='$startDt', EXPIRY_DT='$endDt' WHERE PROFILEID=$profileid AND BILLID=$billid AND SERVICEID='$serviceid'";
	}
	$res1 = mysql_query($sql1) or logError($sql1);
	echo $sql1."\n";
}
?>
