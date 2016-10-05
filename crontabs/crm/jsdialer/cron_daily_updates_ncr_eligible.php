<?php
/*********************************************************************************************
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
$campaign_name = 'JS_NCRNEW';
$eligibleType ='Y';
$limit =10;

// get Status
$status =getCampaignEligibilityStatus($campaign_name,$eligibleType);
if(!$status)
        $status=0;

/*Update data of eligible profiles*/
$start_from =$status;
for($i=$start_from;$i<$limit;$i++)
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

$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Dialer updates of ncr-eligible done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,'',$from);
?>
