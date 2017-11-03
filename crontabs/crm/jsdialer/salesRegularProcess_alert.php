<?php
/*********************************************************************************************
* FILE NAME   	: salesRegularProcess.php 
* DESCRIPTION 	: 
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_master);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);

/* Config array Start */
$campaignArr 		=array('JS_NCRNEW','MAH_JSNEW','JS_RENEWAL','OB_JS_PAID','OB_RENEWAL_MAH','JS_NCRNEW_Auto');
$campaignTableArr       =array('JS_NCRNEW'=>array('SALES_CSV_DATA_NOIDA','SALES_CSV_DATA_DELHI'),'MAH_JSNEW'=>array('SALES_CSV_DATA_MUMBAI','SALES_CSV_DATA_PUNE'),'JS_RENEWAL'=>array('SALES_CSV_DATA_RENEWAL'),'OB_JS_PAID'=>array('SALES_CSV_DATA_PAID_CAMPAIGN'),'OB_RENEWAL_MAH'=>array('SALES_CSV_DATA_RENEWAL'),'JS_NCRNEW_Auto'=>array('SALES_CSV_DATA_NOIDA_NEW'));

/* Test Configuration */
$csvEntryDate =date("Y-m-d",time()-24*60*60);
$statusCheck =1;

$msg ="Campaign Name | Total Generated Records | Temp Uploaded Records | Campaign Uploaded Records\n";
foreach($campaignArr as $key=>$campaignName)
{
        $tableArr       =$campaignTableArr[$campaignName];
        foreach($tableArr as $key=>$tableName){
                $profilesArr    =$dialerHandlerObj->getProfilesForCampaign($tableName, $csvEntryDate,$campaignName);
                $totalRecord	+=count($profilesArr);
        }       
        // Update Status log - CAMPAIGN NAME:,DATE:,STATUS=1    
        $dialerCampaignReords 		=$dialerHandlerObj->getDialerCampaignRecords($campaignName, $csvEntryDate,$statusCheck);
	$dialerUploadedCampaignReords 	=$dialerHandlerObj->getUploadedDialerCampaignRecords($campaignName, $csvEntryDate);

        $msg .=$campaignName." | ".$totalRecord." | ".$dialerCampaignReords." | ".$dialerUploadedCampaignReords."\n";

        unset($totalRecord);
        unset($profilesArr);
}

$to     ="manoj.rana@naukri.com,dheeraj.negi@naukri.com,anamika.singh@jeevansathi.com,princy.gulati@jeevansathi.com";
$from   ="From:JeevansathiCrm@jeevansathi.com";
$sub    ="Dialer Status Report for Uploaded Reecords";
mail($to,$sub,$msg,$from);

?>
