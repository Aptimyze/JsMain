<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

/****************************
Created for Trac Tkt 1023
This script will send out photo requests to all recipients of response booster Auto EOI's sent out one day before
*****************************/
$flag_using_php5=1;
$fromCrontab=1;
include("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");

$dbslave=connect_slave();

$photoDate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$sql="SELECT SENDER,RECEIVER FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING LEFT JOIN newjs.JPROFILE ON RECEIVER=PROFILEID WHERE DATE LIKE '$photoDate%' AND HAVEPHOTO IN ('','N')";
$res=mysql_query_decide($sql,$dbslave) or die("Error while executing photo sql  ".$sql."   ".mysql_error($dbslave));
while($row=mysql_fetch_assoc($res))
	$arr[]=array("SENDER"=>$row["SENDER"],"RECEIVER"=>$row["RECEIVER"]);
if(is_array($arr))
{
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/photorequest.php");
	foreach($arr as $senderReceiverArr)
	{
		$contactResult=getResultSet("FILTERED","$senderReceiverArr[SENDER]",'',"$senderReceiverArr[RECEIVER]");
		if($contactResult[0]["FILTERED"]!='Y')
			photo_req_common($senderReceiverArr["SENDER"],$senderReceiverArr["RECEIVER"]);
	}
}
?>
