<?php
/*********************************************************************************************
* FILE NAME   	: rcbCampaignProcess.php 
* DESCRIPTION 	: RCM Campaign process
*********************************************************************************************/
include("MysqlDbConstants.class.php");
$to     ="manoj.rana@naukri.com";
$from   ="From:JeevansathiCrm@jeevansathi.com";

//Connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

include("DialerHandler.class.php");
$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);

/* Config array Start */
$campaignArr 		=array('OB_JS_RCB');
$campaignIdArr		=array('OB_JS_RCB'=>17);
$campaignTableArr       =array('OB_JS_RCB'=>array('SALES_CSV_DATA_RCB'));

foreach($campaignArr as $key=>$campaignName)
{
		$totalIns       =0;
		$processId	=$campaignIdArr[$campaignName];
		$startID 	=$dialerHandlerObj->getLastHandledID($processId);
                /*if($startDate=='0000-00-00 00:00:00')
                        break;*/
		if(!$startID)
			break;

		$tableArr	=$campaignTableArr[$campaignName];
		foreach($tableArr as $key=>$tableName){
			$profilesArr    =$dialerHandlerObj->getProfilesForCampaign($tableName,'',$campaignName, '','',$startID);
			$totalRecord	=count($profilesArr);
			if($totalRecord>0){
				foreach($profilesArr as $key=>$dataArr){
					if(!isset($startDate))
						$startDate =$dataArr['CSV_ENTRY_DATE'];
					$dataArr =$dialerHandlerObj->formatDataSet($campaignName,$dataArr,$startDate);		
					$endID =$dataArr['ID'];
					unset($dataArr['ID']);
					$dialerHandlerObj->addProfileinCampaign($dataArr, $campaignName);
					$totalIns++;
					if($endID>0)
						$dialerHandlerObj->updateLastHandledID($processId,$endID);
					unset($dataArr);
				}
			}
			unset($profilesArr);
		}
		if(isset($startDate))
			$dialerCampaignReords =$dialerHandlerObj->getCampaignRecordsForDuration($campaignName, $startDate );
		if($totalRecord !=$dialerCampaignReords){
			$sub	="FAILED: Dialer insert for $campaignName";
			$msg	="JS Campaign Records:".$totalRecord."# Dialer Insert:".$dialerCampaignReords."# Eligible Count:".$totalIns;	
			mail($to,$sub,$msg,$from);
		}
		unset($startDate);
		unset($campaignRecord);
		unset($dialerCampaignReords);
}

?>
