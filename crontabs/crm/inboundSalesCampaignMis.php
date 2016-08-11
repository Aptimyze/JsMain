<?php
/*********************************************************************************************
* FILE NAME   	: inboundSalesCampaignMis.php 
* DESCRIPTION 	: Capture inbound sales campaign details for call connecting 
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/

//Open connection at JSDB
$db_master 	= mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server at ".$start);
//Connection at DialerDB
$db_dialer 	= mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");
mysql_query("set session wait_timeout=600",$db_master);

$campaignArr	=array('IB_Sales'=>'ct_IB_Sales','IB_SupSale'=>'ct_IB_SupSales','IB_Service'=>'ct_IB_Service','IB_SupService'=>'ct_IB_SupService','IB_PaidService'=>'ct_IB_PaidService','IB_SupPaidservice'=>'ct_IB_SupPaidS');
$entryDt	=date("Y-m-d",time()-24*60*60);

foreach($campaignArr as $campaignName=>$tableName){	

	$squery1 = "select count(lead_id) as cnt from ".$tableName." where lead_id='$entryDt' and PHONE_NO1 is not null";
	$sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
	if($srow1 = mssql_fetch_array($sresult1))
	{
		$count =$srow1["cnt"];
		addCampaignDetails($campaignName, $count,$entryDt, $db_master);
	}
}
// Mail added
$to="manoj.rana@naukri.com";
$sub="Inbound Sales Campaign Mis Details";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$profileStr,$from);

// Add Campaign Details
function addCampaignDetails($campaignName, $count, $entryDt,$db_master)
{
        $sql= "INSERT INTO incentive.INBOUND_SALES_LOG(`CAMPAIGN_NAME`,`CALL_COUNT`,`ENTRY_DT`) VALUES($campaignName,$count,$entryDt)";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
}
?>
