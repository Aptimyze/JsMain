<?php
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_renewal.php
* DESCRIPTION 	: Change the dialer priority based in eligibility criteria on daily basis
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");

// Live Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer); 
$campaign_nameArr =array("JS_RENEWAL","OB_RENEWAL_MAH");
$limit =10;
$todayDate =$dialerHandlerObj->getEST();

foreach($campaign_nameArr as $key=>$campaign_name)
{
	// get Status
	$statusArr =$dialerHandlerObj->getCampaignEligibilityStatus($campaign_name);

	/*Stop non-eligible profiles*/
	$eligibleType ='N';
	$status =$statusArr[$campaign_name][$eligibleType];
	if(!$status)
        	$status=0;		
	for($i=$status;$i<$limit;$i++)
	{
		$ignore_array = $dialerHandlerObj->getRenewalInEligibleProfiles($i,$campaign_name);
		$rd_array = $dialerHandlerObj->getRenewalDiscountArray($ignore_array);
	        $dialerHandlerObj->stop_non_eligible_profiles($campaign_name,$i,$ignore_array,$rd_array);
		$dialerHandlerObj->updateCampaignEligibilityStatus($campaign_name,$eligibleType, $i, $todayDate);
		echo "DONE$i"."\n";
	}

	/*Update data of eligible profiles*/
        $eligibleType ='Y';
        $status =$statusArr[$campaign_name][$eligibleType];
        if(!$status)
                $status=0;
	for($i=$status;$i<$limit;$i++)
	{
		$eligible_array = $dialerHandlerObj->getRenewalEligibleProfiles($i,$campaign_name);
		$rd_array = $dialerHandlerObj->getRenewalDiscountArray($eligible_array);
		$allotedArray =$dialerHandlerObj->getAllotedProfiles($eligible_array);
		$scoreArray = $dialerHandlerObj->getScoreArray($eligible_array);
		$paidProfiles =$dialerHandlerObj->getPaidProfilesArray($eligible_array);
	        $dialerHandlerObj->update_data_of_eligible_profiles($campaign_name,$i,$eligible_array,$rd_array,$allotedArray,$scoreArray, $paidProfiles);
		$dialerHandlerObj->updateCampaignEligibilityStatus($campaign_name,$eligibleType, $i, $todayDate);
		echo "DONE$i"."\n";
	}
}

$to="manoj.rana@naukri.com";
$sub="Dialer updates of Renewal done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,'',$from);
?>
