<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

die();
include "$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc";
//$today  =date ("Y-m-d",mktime(0, 0, 0, date("m", time())  , date("d", time())-1, date("Y", time())));

$sql_pc = "SELECT PROFILEID, REQ_DT, SERVICE FROM incentive.PAYMENT_COLLECT WHERE STATUS='S' AND BILLING=''";
$res_pc = mysql_query($sql_pc) or die();
while($row_pc = mysql_fetch_array($res_pc))
{
	$pid = $row_pc['PROFILEID'];
	$req_dt = $row_pc['REQ_DT'];
	$sid = $row_pc['SERVICE'];

	$sql_purch = "SELECT BILLID, ENTRYBY FROM billing.PURCHASES WHERE PROFILEID='$pid' AND ENTRY_DT >= '$req_dt' AND SERVICEID='$sid'";
	$res_purch = mysql_query($sql_purch) or die();
	if($row_purch = mysql_fetch_array($res_purch))
	{
		$entryby = $row_purch['ENTRYBY'];
		$sql_upd = "UPDATE incentive.PAYMENT_COLLECT SET BILLING='Y', ENTRYBY='$entryby', ENTRY_DT = now() WHERE PROFILEID = '$pid'";
		mysql_query($sql_upd) or die();
	}
}

/*
$sql = "SELECT PROFILEID, SERVICEID, ENTRYBY FROM billing.PURCHASES WHERE ENTRY_DT between  '$today 00:00:00' and '$today 23:59:59' and STATUS='DONE' ";


$res = mysql_query($sql) or die();

while($row = mysql_fetch_array($res))
{
	$pid = $row['PROFILEID'];
	$sid = $row['SERVICEID'];
	$eby = $row['ENTRYBY'];
	$sql_bill_y_update = "UPDATE incentive.PAYMENT_COLLECT set BILLING = 'Y',STATUS = 'S', ENTRYBY = '$eby', ENTRY_DT =now() where PROFILEID = '$pid' AND SERVICE = '$sid' and STATUS='S' and BILLING='' ";
	$res_bill_y_update = mysql_query($sql_bill_y_update) or die();
}
*/
?>
