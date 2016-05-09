<?php
$to	="manoj.rana@naukri.com";
$from	="From:vibhor.garg@jeevansathi.com";
/*********************************************************************************************
* FILE NAME   	: opt_in_scrubbingDaily.php
* DESCRIPTION 	: Re-start profiles who were marked in DNC but opt-in for calls
*********************************************************************************************/
include("DialerDncScrubing.class.php");

//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to vario server");
$db_js_157 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to local server");
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");
$dialerDncScrubingObj =new DialerDncScrubing($db_js, $db_js_157, $db_dialer);

// Failed Payment,Upsell campaign OPT-IN Check
$campaignArr =array('FP_JS','UPSELL_JS');
foreach($campaignArr as $key=>$campaignName)
{
	if($campaignName=='FP_JS')
		$dateTime =date("Y-m-d H:i:s",time()-12*60*60);
	elseif($campaignName=='UPSELL_JS')
		$dateTime =date("Y-m-d H:i:s",time()-24*60*60);

	$msg    	="Start time:".@date('H:i:s');
	$dnc_array      =$dialerDncScrubingObj->compute_dnc_array($campaignName, $dateTime);
	$opt_in_array   =$dialerDncScrubingObj->compute_opt_in_array($dnc_array);

	for($i=0;$i<count($opt_in_array);$i++){
	        $profileid =$opt_in_array[$i];
	        $dialerDncScrubingObj->start_opt_in_profiles($campaignName,$profileid,$dateTime);
	}
	unset($dnc_array);
	unset($opt_in_array);
	$sub="Dialer updates for $campaignName-Campaign opt-in done";
	$msg.="End time:".@date('H:i:s');
	mail($to,$sub,$msg,$from);
}

// OB_Sales OPT-IN Check
$msg    	="Start time:".@date('H:i:s');
$campaignName 	='OB_Sales';
$leadId 	=date("Y-m-d"); //'2016-02-18';
$dnc_phoneArray =$dialerDncScrubingObj->compute_dnc_array_forSalesCampaign($campaignName, $leadId);
foreach($dnc_phoneArray as $phoneNo=>$profileArr){
	foreach($profileArr as $key=>$profileid){
		$profileidArr[] =$profileid;
		$phoneArr[$profileid] =$phoneNo;
	}	
}		
unset($dnc_phoneArray);
$opt_in_array 	=$dialerDncScrubingObj->compute_opt_in_array($profileidArr);
for($i=0;$i<count($opt_in_array);$i++){
	$profileid =$opt_in_array[$i];
	$phoneNo =$phoneArr[$profileid];
        $dialerDncScrubingObj->start_opt_in_profiles_forSalesCampaign($campaignName,$phoneNo,$leadId);
}	
unset($opt_in_array);
unset($profileidArr);
unset($phoneArr);
$sub='Dialer updates for OB_Sales-Campaign opt-in done.';
$msg.="End time:".@date('H:i:s');
mail($to,$sub,$msg,$from);

?>
