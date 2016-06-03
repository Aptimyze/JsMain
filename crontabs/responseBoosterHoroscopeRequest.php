<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

/****************************
Created for Trac Tkt 1023
This script will send out horoscope requests to recipients of Auto EOI's sent out two days before.
*****************************/
$flag_using_php5=1;
$fromCrontab=1;
global $RBCron,$receiverProfile;
$RBCron=1;
include("$_SERVER[DOCUMENT_ROOT]/profile/connect.inc");

$dbslave=connect_slave();

$horDate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));

$sql="SELECT SENDER,RECEIVER FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING LEFT JOIN newjs.JPROFILE ON SENDER=JPROFILE.PROFILEID LEFT JOIN newjs.ASTRO_DETAILS ON JPROFILE.PROFILEID=ASTRO_DETAILS.PROFILEID WHERE AUTOMATED_CONTACTS_TRACKING.DATE LIKE '$horDate%' AND (ASTRO_DETAILS.PROFILEID IS NOT NULL OR (RELIGION IN ('1','9','4','7') AND HOROSCOPE_MATCH IN ('Y','')) OR (RELIGION IN ('1','9','4','7') AND MANGLIK IN ('M','A')))";
$res=mysql_query_decide($sql,$dbslave) or die("Error while executing horoscope sql  ".$sql."   ".mysql_error($dbslave));
while($row=mysql_fetch_assoc($res))
        $arr[]=array("SENDER"=>$row["SENDER"],"RECEIVER"=>$row["RECEIVER"]);
if(is_array($arr))
{
        include_once($_SERVER['DOCUMENT_ROOT']."/profile/horoscoperequest.php");
        foreach($arr as $senderReceiverArr)
	{
		$contactResult=getResultSet("FILTERED","$senderReceiverArr[SENDER]",'',"$senderReceiverArr[RECEIVER]");
		if($contactResult[0]["FILTERED"]!='Y')
		{
			$sql="SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID=\"$senderReceiverArr[RECEIVER]\"";
			$res=mysql_query_decide($sql,$dbslave) or die("Error while checking receiver for horoscope  ".$sql."   ".mysql_error_js($dbslave));
			$row=mysql_fetch_assoc($res);
			if(!$row["COUNT"])
			{
				$receiverProfile=$senderReceiverArr["RECEIVER"];
				photo_req_common($senderReceiverArr["SENDER"],$senderReceiverArr["RECEIVER"]);
			}
		}
	}
}
?>
