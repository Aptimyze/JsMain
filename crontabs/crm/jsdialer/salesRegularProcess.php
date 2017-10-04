<?php
/*********************************************************************************************
* FILE NAME   	: salesRegularProcess.php 
* DESCRIPTION 	: 
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");

ini_set('max_execution_time',0);
ini_set('memory_limit',-1);

$to     ="manoj.rana@naukri.com";
$from   ="From:JeevansathiCrm@jeevansathi.com";

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_master);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);
$csvEntryDate =date("Y-m-d",time()-10.5*60*60);

/* Config array Start */
$campaignArr 		=array('JS_NCRNEW','MAH_JSNEW','JS_NCRNEW_Auto','JS_RENEWAL','OB_JS_PAID','OB_RENEWAL_MAH');
$campaignTableArr       =array('JS_NCRNEW'=>array('SALES_CSV_DATA_NOIDA','SALES_CSV_DATA_DELHI'),'JS_NCRNEW_Auto'=>array('SALES_CSV_DATA_NOIDA_NEW'),'MAH_JSNEW'=>array('SALES_CSV_DATA_MUMBAI','SALES_CSV_DATA_PUNE'),'JS_RENEWAL'=>array('SALES_CSV_DATA_RENEWAL'),'OB_JS_PAID'=>array('SALES_CSV_DATA_PAID_CAMPAIGN'),'OB_RENEWAL_MAH'=>array('SALES_CSV_DATA_RENEWAL'));

/* Config array End */

/* Test Configuration */
//$csvEntryDate ='2016-09-29';
foreach($campaignArr as $key=>$campaignName)
{
	$status =0;
	$status =$dialerHandlerObj->getCampaignStatus($campaignName,$csvEntryDate);
	if(!$status){

		@mysql_ping($db_master);
		$tableArr	=$campaignTableArr[$campaignName];
		foreach($tableArr as $key=>$tableName){
			$profilesArr    =$dialerHandlerObj->getProfilesForCampaign($tableName, $csvEntryDate,$campaignName);
			$totalRecord	=count($profilesArr);
			if($totalRecord>0){
				foreach($profilesArr as $key=>$dataArr){
					$dataArr =$dialerHandlerObj->formatDataSet($campaignName,$dataArr,$csvEntryDate);		
					$dialerHandlerObj->addProfileinCampaign($dataArr);
					unset($dataArr);
				}
			}
			unset($profilesArr);
			$campaignRecord +=$totalRecord;
		}
		// Update Status log - CAMPAIGN NAME:,DATE:,STATUS=1	
		$dialerCampaignReords =$dialerHandlerObj->getDialerCampaignRecords($campaignName, $csvEntryDate);
		if(($campaignRecord == $dialerCampaignReords) && $dialerCampaignReords>0){
			$status=1;
			$statusMsg ='Success';
			$dialerHandlerObj->setCampaignStatus($campaignName,$csvEntryDate,$status);
		}
		else{
			$statusMsg='Failure';
		}
		// Mailer system 	
		$sub	="$statusMsg: Dialer insert for $campaignName-Campaign Done";
		$msg	="Campaign Records:".$campaignRecord."# Dialer Records:".$dialerCampaignReords;	
		mail($to,$sub,$msg,$from);

		unset($campaignRecord);
		unset($dialerCampaignReords);
	}
}

?>
