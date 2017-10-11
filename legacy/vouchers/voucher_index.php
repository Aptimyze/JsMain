<?php
/******************************************************************************************************
Filename    : voucher_index.php 
Description : Display microsite
Created On  : 10 September 2007
Created By  : Sadaf Alam
*******************************************************************************************************/
header("Cache-Control: public");
include("connect.inc");

$db=connect_db();

$sql="SELECT CLIENTID,CLIENT_NAME,VSUMMARY,HEADLINE FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND CLIENTID!='VLCC01'";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
if(mysql_num_rows($result))
{
	$i=mysql_num_rows($result);
	$j=$i;
	if($i%3!=0)
	$j=$j-($i%3)+3;
	while($j>0)
	{
		$row=mysql_fetch_assoc($result);
		$client1=$row["CLIENTID"];
		$vsummary=html_entity_decode($row["VSUMMARY"],ENT_QUOTES);
		$vsummary=nl2br($vsummary);
		$logo1="<img src=\"$SITE_URL/jsadmin/voucher_backend_display.php?clientid=$row[CLIENTID]&file=VLOGO\" width=\"108\" height=\"39\" border=\"0\"></img>";
		$client[]=array("CLIENT_NAME"=>$row["CLIENT_NAME"],
				"CLIENTID"=>$row["CLIENTID"],
				"VSUMMARY"=>$vsummary,
				"HEADLINE"=>$row["HEADLINE"]);
		$row=mysql_fetch_assoc($result);
		$client2=$row["CLIENTID"];
		$vsummary=html_entity_decode($row["VSUMMARY"],ENT_QUOTES);
                $vsummary=nl2br($vsummary);
		$logo2="<img src=\"$SITE_URL/jsadmin/voucher_backend_display.php?clientid=$row[CLIENTID]&file=VLOGO\" width=\"108\" height=\"39\" border=\"0\"></img>";
		 $client[]=array("CLIENT_NAME"=>$row["CLIENT_NAME"],
                                "CLIENTID"=>$row["CLIENTID"],
                                "VSUMMARY"=>$vsummary,
                                "HEADLINE"=>$row["HEADLINE"]);

		$row=mysql_fetch_assoc($result);
		$client3=$row["CLIENTID"];
		$vsummary=html_entity_decode($row["VSUMMARY"],ENT_QUOTES);
                $vsummary=nl2br($vsummary);
                $logo3="<img src=\"$SITE_URL/jsadmin/voucher_backend_display.php?clientid=$row[CLIENTID]&file=VLOGO\" width=\"108\" height=\"39\" border=\"0\"></img>";
		 $client[]=array("CLIENT_NAME"=>$row["CLIENT_NAME"],
                                "CLIENTID"=>$row["CLIENTID"],
                                "VSUMMARY"=>$vsummary,
                                "HEADLINE"=>$row["HEADLINE"]);

		$display[]=array("client1"=>$client1,"logo1"=>$logo1,"client2"=>$client2,"logo2"=>$logo2,"client3"=>$client3,"logo3"=>$logo3);
		$j-=3;
	}
	$smarty->assign("display",$display);
	$smarty->assign("client",$client);
	$smarty->display("voucher_index.htm");
}
?>
