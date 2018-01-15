<?php
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_renewal.php
* DESCRIPTION 	: Change the dialer priority based in eligibility criteria on daily basis
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");
include('PriorityHandler.class.php');

// Live Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer);
$priorityHandlerObj =new PriorityHandler($db_js, $db_js_111, $db_dialer);
$campaign_nameArr =array("JS_RENEWAL","OB_RENEWAL_MAH");
$limit =10;
$npriority =5;
$todayDate =date("Y-m-d",time()-10.5*60*60);
$todayDate1 =strtotime($todayDate);

foreach($campaign_nameArr as $key=>$campaignName)
{
	// get Status
	$statusArr =$dialerHandlerObj->getCampaignEligibilityStatus($campaignName);
	$eligibleType ='P';
	$status =$statusArr[$campaignName][$eligibleType];
	if(!$status)
        	$status=0;	
	
	for($i=$status;$i<$limit;$i++){
		$dialerData =$priorityHandlerObj->getDialerProfileForPriority($campaignName,'',$i);
		if(!$dialerData)
			continue;
		foreach($dialerData as $profileid=>$dataArr){
			$expiryDate 		=$dataArr['EXPIRY_DT'];	
			$expiryDate1 		=strtotime($expiryDate);
			$expiryDatePrev5Days 	=date('Y-m-d', strtotime('-4 days',strtotime($expiryDate)));
			$expiryDatePrev5Days1 	=strtotime($expiryDatePrev5Days);

			if($todayDate1>=$expiryDatePrev5Days1 && $todayDate1<=$expiryDate1){
				// Prioritize - with new priority
				$priorityHandlerObj->prioritizeProfile($profileid,$campaignName,$dialerData,$npriority);
			}
			else{
				// De-prioritize - with old priority
				$priorityHandlerObj->dePrioritizeProfile($profileid,$campaignName,$dialerData);
			}
		}
		$dialerHandlerObj->updateCampaignEligibilityStatus($campaignName,$eligibleType, $i);
		echo "DONE$i"."\n";
	}
}
$to="manoj.rana@naukri.com";
$sub="Renewal Prioritization Done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,'',$from);
?>
