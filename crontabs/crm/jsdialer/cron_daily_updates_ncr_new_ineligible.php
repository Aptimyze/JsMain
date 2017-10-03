<?php
/*********************************************************************************************
* DESCRIPTION 	: Change the data of the online dialing daily
* MADE DATE 	: 20 Mar, 2014
* MADE BY     	: VIBHOR GARG
*********************************************************************************************/
//include("cron_daily_updates_functions.php");
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer);
$campaign_name = 'OB_NCRNEW';
$eligibleType ='N';
$limit =10;
$todayDate =$dialerHandlerObj->getEST();

// get Status
$status =$dialerHandlerObj->getCampaignEligibilityStatus($campaign_name,$eligibleType);
if(!$status)
	$status=0;

/*Stop non-eligible profiles*/
$start_from =$status;
for($i=$start_from;$i<$limit;$i++)
{
	$ignore_array 	= $dialerHandlerObj->getInDialerNewInEligibleProfiles($i,$campaign_name);
	$vd_array 	= $dialerHandlerObj->getVDdiscount($ignore_array);
        $dialerHandlerObj->stop_non_eligible_profiles($campaign_name,$i,$ignore_array,$vd_array);
	$dialerHandlerObj->updateCampaignEligibilityStatus($campaign_name,$eligibleType, $i, $todayDate);
	echo "DONE$i"."\n";
}

$to="vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
$sub="Dialer updates of ncr-ineligible done.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,'',$from);
?>
