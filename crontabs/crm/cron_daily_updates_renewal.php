<?php

echo $msg = "Start time #".@date('H:i:s');echo "\n";
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_renewal.php
* DESCRIPTION 	: Change the dialer priority based in eligibility criteria on daily basis
*********************************************************************************************/
include("DialerHandler.class.php");

// Live Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to vario server");
$db_js_157 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to local server");
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js_157);
//Live Connection at DialerDB
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");

$campaign_name = 'JS_RENEWAL';
$start_from=0;
$dialerHandlerObj =new DialerHandler($db_js, $db_js_157, $db_dialer); 
$campaign_nameArr =array("JS_RENEWAL","OB_RENEWAL_MAH");

foreach($campaign_nameArr as $key=>$campaign_name)
{
	/*Stop non-eligible profiles*/
	echo "/Part-1/"."\n";
	for($i=$start_from;$i<10;$i++)
	{
		$ignore_array = $dialerHandlerObj->getRenewalInEligibleProfiles($i,$campaign_name);
		$rd_array = $dialerHandlerObj->getRenewalDiscountArray($ignore_array);
	        $dialerHandlerObj->stop_non_eligible_profiles($campaign_name,$i,$ignore_array,$rd_array);
		echo "DONE$i"."\n";
	}

	/*Update data of eligible profiles*/
	echo "/Part-2/"."\n";
	for($i=$start_from;$i<10;$i++)
	{
		$eligible_array = $dialerHandlerObj->getRenewalEligibleProfiles($i,$campaign_name);
		$rd_array = $dialerHandlerObj->getRenewalDiscountArray($eligible_array);
		$allotedArray =$dialerHandlerObj->getAllotedProfiles($eligible_array);
		$scoreArray = $dialerHandlerObj->getScoreArray($eligible_array);
		$paidProfiles =$dialerHandlerObj->getPaidProfilesArray($eligible_array);
	        $dialerHandlerObj->update_data_of_eligible_profiles($campaign_name,$i,$eligible_array,$rd_array,$allotedArray,$scoreArray, $paidProfiles);
		echo "DONE$i"."\n";
	}
}

echo "\n";
echo $msg.="End time :".@date('H:i:s');
$to="manoj.rana@naukri.com";
$sub="Dialer updates of Renewal done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
?>
