<?php

include("connect.inc");

$db=connect_db();
$sql="SELECT CLIENT_NAME,HEADLINE,VDETAILS,CDETAILS FROM billing.VOUCHER_CLIENTS WHERE CLIENTID='$clientid'";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
$row=mysql_fetch_assoc($result);
$vdetails=html_entity_decode($row["VDETAILS"],ENT_QUOTES);
$cdetails=html_entity_decode($row["CDETAILS"],ENT_QUOTES);
$vdetails=nl2br($vdetails);
$cdetails=nl2br($cdetails);

$deal=array("CLIENT_NAME"=>$row["CLIENT_NAME"],
	    "HEADLINE"=>$row["HEADLINE"],
	    "CLIENTID"=>$clientid,
	    "CDETAILS"=>$cdetails,
	    "VDETAILS"=>$vdetails);
$smarty->assign("deal",$deal);
$sql="SELECT CLIENT_NAME,CLIENTID FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01'";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try again after a couple of minutes",$sql,"ShowErrTemplate");
while($row=mysql_fetch_assoc($result))
{
	$client[]=array("CLIENTID"=>$row["CLIENTID"],
			"CLIENT_NAME"=>$row["CLIENT_NAME"]);
}
$smarty->assign("client",$client);
$smarty->display("voucher.htm");
?>
