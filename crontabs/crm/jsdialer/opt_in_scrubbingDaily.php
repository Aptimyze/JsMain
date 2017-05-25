<?php
$to	="manoj.rana@naukri.com";
$from	="From:vibhor.garg@jeevansathi.com";
/*********************************************************************************************
* FILE NAME   	: opt_in_scrubbingDaily.php
* DESCRIPTION 	: Re-start profiles who were marked in DNC but opt-in for calls
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerDncScrubing.class.php");

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$dialerDncScrubingObj =new DialerDncScrubing($db_js, $db_js_111, $db_dialer);

// campaign OPT-IN Check
$campaignArr =array('FP_JS','UPSELL_JS','JS_RENEWAL','OB_RENEWAL_MAH','MAH_JSNEW','JS_NCRNEW');
$eligibleCampaignArr =array('JS_RENEWAL','OB_RENEWAL_MAH','MAH_JSNEW','JS_NCRNEW');
$renewalCampaignArr =array('JS_RENEWAL','OB_RENEWAL_MAH');

foreach($campaignArr as $key=>$campaignName)
{
	$dateTime ='';
	if($campaignName=='FP_JS')
		$dateTime =date("Y-m-d H:i:s",time()-22.5*60*60);
	elseif($campaignName=='UPSELL_JS')
		$dateTime =date("Y-m-d H:i:s",time()-48*60*60);

	$dnc_array      =$dialerDncScrubingObj->compute_dnc_array($campaignName, $dateTime);
	$opt_in_array   =$dialerDncScrubingObj->compute_opt_in_array($dnc_array);

	if(in_array("$campaignName", $eligibleCampaignArr)){
		if(in_array("$campaignName",$renewalCampaignArr))
			$renewal =1;
		else
			$renewal ='';
		$opt_in_array1	=$dialerDncScrubingObj->compute_eligible_in_array($opt_in_array, $renewal);
		unset($opt_in_array);
		$opt_in_array =$opt_in_array1;
	}
	// common computation
	for($i=0;$i<count($opt_in_array);$i++){
	        $profileid =$opt_in_array[$i];
	        $dialerDncScrubingObj->start_opt_in_profiles($campaignName,$profileid,$dateTime);
	}
	unset($dnc_array);
	unset($opt_in_array);
	$msg[] =$campaignName;
}

// OB_Sales OPT-IN Check
$campaignName 	='OB_Sales';
$leadId 	=date("Y-m-d",time()-24*60*60);  
$dnc_phoneArray =$dialerDncScrubingObj->compute_dnc_array_forSalesCampaign($campaignName, $leadId);
if(count($dnc_phoneArray)){
	foreach($dnc_phoneArray as $phoneNo=>$profileArr){
		foreach($profileArr as $key=>$profileid){
			$profileidArr[] =$profileid;
			$phoneArr[$profileid] =$phoneNo;
		}	
	}
}		
unset($dnc_phoneArray);
if(is_array($profileidArr))
	$opt_in_array 	=$dialerDncScrubingObj->compute_opt_in_array($profileidArr);
for($i=0;$i<count($opt_in_array);$i++){
	$profileid =$opt_in_array[$i];
	$phoneNo =$phoneArr[$profileid];
        $dialerDncScrubingObj->start_opt_in_profiles_forSalesCampaign($campaignName,$phoneNo,$leadId);
}	
unset($opt_in_array);
unset($profileidArr);
unset($phoneArr);
$msg[]=$campaignName;

$sub='Dialer updates for OPT-IN done';
$msg =implode(" | ",$msg);
mail($to,$sub,$msg,$from);

?>
