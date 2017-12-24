<?php
$to	="manoj.rana@naukri.com";
$from	="From:vibhor.garg@jeevansathi.com";
/*********************************************************************************************
* FILE NAME   	: removePaidDeletedProcess.php 
* DESCRIPTION 	: Remove Paid and Deleted Profiles
*********************************************************************************************/
include("MysqlDbConstants.class.php");
include("DialerHandler.class.php");
include("DialerApplication.class.php");

//Connection at JSDB
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");

$dialerHandlerObj =new DialerHandler($db_js, $db_js_111, $db_dialer,$db_master);
$dialerApplicationObj = new DialerApplication($db_js, $db_js_111, $db_dialer,$db_master);

$campaignArr =array('JS_RENEWAL','OB_RENEWAL_MAH','MAH_JSNEW','JS_NCRNEW','JS_NCRNEW_Auto');
$renewalCampArr =array('JS_RENEWAL','OB_RENEWAL_MAH');
$outboundCampArr =array('MAH_JSNEW','JS_NCRNEW');
$autoCampArr	=array('JS_NCRNEW_Auto');

$typeArray		=array('PAID','DELETED'); 	
$str                    ='Dial_Status=0';
$Remove_Paid_Deleted	='Remove_Paid_Deleted';
$checkTime		=date("Y-m-d H:i:s",time()-14.5*60*60);

foreach($typeArray as $key=>$type)
{
	if($type=='PAID'){
		$processId ='19';
		$action ='STOP_P';
	}
	elseif($type=='DELETED'){
		$processId ='20';
		$action ='STOP_D';
	}

        $startDate =$dialerHandlerObj->getLastHandledDate($processId);
	if(!$startDate || $startDate=='0000-00-00 00:00:00' || (strtotime($startDate)<=strtotime($checkTime))){
		break;	
	}

	if($type=='PAID')
		$profilesArr =$dialerApplicationObj->getPaidProfilesList($startDate);
	elseif($type=='DELETED')
		$profilesArr =$dialerApplicationObj->getDeletedProfileList($startDate);
	
	if(count($profilesArr)==0)
		continue;

	unset($dateTime);
	foreach($profilesArr as $profileid=>$dateTime){
		foreach($campaignArr as $key1=>$campaignName){
			$dialerHandlerObj->updateDialStatusInDialer($profileid, $campaignName,$processName);
			$dialerApplicationObj->updateIneligibleFlagInJS($profileid, $campaignName, $renewalCampArr, $outboundCampArr,$autoCampArr);
			$dialerHandlerObj->addLog($profileid,$campaignName,$str,$action);
		}
	}
	if($dateTime)
		$dialerHandlerObj->updateLastHandledDate($processId,$dateTime);
	unset($profilesArr);
}

?>
