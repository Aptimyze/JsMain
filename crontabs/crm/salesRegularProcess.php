<?php
/*********************************************************************************************
* FILE NAME   	: salesRegularProcess.php 
* DESCRIPTION 	: 
*********************************************************************************************/
ini_set('max_execution_time',0);
$to     ="manoj.rana@naukri.com";
$from   ="From:JeevansathiCrm@jeevansathi.com";

//Connection at JSDB
$db_js = mysql_connect("ser2.jeevansathi.jsb9.net","user_dialer","DIALlerr") or die("Unable to connect to vario server");
$db_master = mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server at ".$start);
$db_js_111 = mysql_connect("localhost:/tmp/mysql_06.sock","user_sel","CLDLRTa9") or die("Unable to connect to local server");
$db_dialer = mssql_connect("dialer.infoedge.com","online","jeev@nsathi@123") or die("Unable to connect to dialer server");
mysql_query("set session wait_timeout=600",$db_master);

include("DialerHandler.class.php");
$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);
$csvEntryDate =date("Y-m-d",time()-9.5*60*60);

/* Config array Start */
$campaignArr 		=array('JS_NCRNEW','MAH_JSNEW','JS_RENEWAL','OB_JS_PAID','OB_RENEWAL_MAH');
$campaignTableArr       =array('JS_NCRNEW'=>array('SALES_CSV_DATA_NOIDA','SALES_CSV_DATA_DELHI'),'MAH_JSNEW'=>array('SALES_CSV_DATA_MUMBAI','SALES_CSV_DATA_PUNE'),'JS_RENEWAL'=>array('SALES_CSV_DATA_RENEWAL'),'OB_JS_PAID'=>array('SALES_CSV_DATA_PAID_CAMPAIGN'),'OB_RENEWAL_MAH'=>array('SALES_CSV_DATA_RENEWAL'));

/* Config array End */

/* Test Configuration */
//$csvEntryDate ='2016-03-30';
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
		if($campaignRecord == $dialerCampaignReords){
			$status=1;
			$statusMsg ='Success';
		}
		else{
			$status=0;
			$statusMsg='Failure';
		}
		$dialerHandlerObj->setCampaignStatus($campaignName,$csvEntryDate,$status);

		// Mailer	
		$sub	="$statusMsg: Dialer insert for $campaignName-Campaign Done";
		$msg	="Campaign Records:".$campaignRecord."# Dialer Records:".$dialerCampaignReords;	
		mail($to,$sub,$msg,$from);

		unset($campaignRecord);
		unset($dialerCampaignReords);
	}
}

?>
