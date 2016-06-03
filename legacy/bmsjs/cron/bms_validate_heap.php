<?php

/*
	created by:lavesh
	purpose: To check if there is some problem in bannerheap
	date:31 jan 2006
*/
chdir(dirname(__FILE__));
include("../includes/bms_connect.php");
$dbbms = getConnectionBms();

$sql="SELECT COUNT(*) as cnt FROM bms2.BANNER WHERE BannerStatus = 'live'";
$result=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in validate head1",mysql_error($dbbms).$sql);
$myrow=mysql_fetch_array($result);
$cnt1=$myrow["cnt"];


$sql="SELECT COUNT(*) as cnt FROM bms2.BANNERHEAP";
$result=mysql_query($sql,$dbbms) or mail("lavesh.rawat@jeevansathi.com","error in validate head2",mysql_error($dbbms).$sql); $myrow=mysql_fetch_array($result);
$cnt2=$myrow["cnt"];

if($cnt1>$cnt2)
{
	$msg="BANNER count is-->".$cnt1."<br>"."BANNERHEAP count is-->".$cnt2;
	mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com",'URGENT-BANNER HEAP IS INVALID',$msg);
	//mail("shiv.narayan@jeevansathi.com","URGENT-BANNER HEAP IS INVALID",$msg);
}
?>
