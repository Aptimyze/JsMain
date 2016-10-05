<?php
/*********************************************************************************************
* FILE NAME   	: cron_daily_updates_mah.php
* DESCRIPTION 	: Change the data of the online dialing daily
* MADE DATE 	: 20 Mar, 2014
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
include("cron_daily_updates_functions.php");
include("DialerHandler.class.php");

//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to vario server");
$db_js_111 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to local server");
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);
//Connection at DialerDB
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer);
$campaign_name = 'MAH_JSNEW';
$eligibleType ='N';
$limit =10;

// get Status
$statusArr =getCampaignEligibilityStatus($campaign_name);
$status =$statusArr[$campaign_name][$eligibleType];
if(!$status)
        $status=0;

/*Stop non-eligible profiles*/
for($i=$status;$i<$limit;$i++)
{
	$ignore_array 	= compute_ignore_array($i,$db_js);
	$vd_array 	= getVDdiscount($ignore_array,$db_js);
        stop_non_eligible_profiles($campaign_name,$i,$ignore_array,$db_dialer,$db_js_157,$vd_array);
	updateCampaignEligibilityStatus($campaign_name,$eligibleType, $i);
	echo "DONE$i"."\n";
}

/*Update data of eligible profiles*/
$eligibleType ='Y';
$status =$statusArr[$campaign_name][$eligibleType];
if(!$status)
        $status=0;
for($i=$status;$i<$limit;$i++)
{
	$eligible_array 	= compute_eligible_array($i,$db_js);
	$vd_array 		= getVDdiscount($eligible_array,$db_js);
	$loggedinWithin15days 	= loginWithin15Days($eligible_array,$db_js);
	$allotedArray 		= allotedArray($eligible_array,$db_js);
	$scoreArray 		= scoreArray($eligible_array,$db_js);
        update_data_of_eligible_profiles($campaign_name,$i,$eligible_array,$db_dialer,$vd_array,$loggedinWithin15days,$allotedArray,$scoreArray,$db_js_157);
	updateCampaignEligibilityStatus($campaign_name,$eligibleType, $i);
	echo "DONE$i"."\n";
}

$to="manoj.rana@naukri.com";
$sub="Dialer updates of maharashtra done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,'',$from);
?>
