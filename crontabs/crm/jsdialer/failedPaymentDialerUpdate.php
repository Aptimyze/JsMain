<?php
/*********************************************************************************************
* FILE NAME   	: failedPaymentDialerUpdate.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/
include_once("MysqlDbConstants.class.php");
include("DialerLog.class.php");
include('PriorityHandler.class.php');
$dialerLogObj =new DialerLog();
//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_master);
mysql_query('set session wait_timeout=10000,net_read_timeout=10000',$db_js_111);

$priorityHandlerObj =new PriorityHandler($db_js, $db_js_111, $db_dialer,$db_master);

$dateTime       =date("Y-m-d H:i:s",time()-22.5*60*60);
$campaignName	='FP_JS';
$action		='STOP';
$str		='Dial_Status=0';
$npriority	=5;
$last20MinTime	=date("Y-m-d H:i:s",time()-10.5*60*60-25*60);
$last20MinTime	=strtotime($last20MinTime);
$scbValue 	='Schedule Call Back';	

$profilesArr 	=fetchProfiles($db_master);
$eligibleArr	=$profilesArr['ELIGIBLE'];
$inEligibleArr	=$profilesArr['IN_ELIGIBLE'];
$allDataArr	=$profilesArr['ALL_DATA'];

$allocatedArr	=getAllocatedProfiles($eligibleArr,$db_master);
$paidArr	=getPaidProfiles($eligibleArr,$db_master,$dateTime);
$eligibleArrNew	=array_merge($allocatedArr,$paidArr);
$eligibleArrNew	=array_unique($eligibleArrNew);
$eligibleArrNew =array_values($eligibleArrNew);

// Prioritization logic

if(count($allDataArr)>0){
	foreach($allDataArr as $profileid=>$csvEntryDate){

		$dialerData =$priorityHandlerObj->getDialerProfileForPriority($campaignName,array($profileid));
		if(!$dialerData)
			continue;

		if(strtotime($csvEntryDate)>=$last20MinTime){
			// Prioritize - with new priority
			$priorityHandlerObj->prioritizeProfile($profileid,$campaignName,$dialerData,$npriority);			
		}
		else{
			// De-prioritize - with old priority
			$priorityHandlerObj->dePrioritizeProfile($profileid,$campaignName,$dialerData);
		}		
	}
}

// Stop profiles which are paid and allocated
if(count($eligibleArrNew>0)) {
    foreach($eligibleArrNew as $key=>$profileid){
			$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=0 WHERE PROFILEID='$profileid'";
			mssql_query($query1,$db_dialer)  or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);
                        $dialStatus =0;
			updateDialStatus($profileid,$dialStatus,$db_master);
			addLog($profileid,$campaignName,$str,$action,$db_js_111);
	}
}

// Stop profiles which are 12 hours old
if(is_array($inEligibleArr)){
	//$profileStr     =implode(",",$inEligibleArr);
	$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE Dial_Status=1 AND Login_Timestamp<'$dateTime' and Last_disposition!='$scbValue'";
	mssql_query($query1,$db_dialer) or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);
        foreach($inEligibleArr as $key=>$pid){
		$getLatDisposition =checkProfileDisposition($pid, $campaignName,$scbValue,$db_dialer,$dialerLogObj);
            if($getLatDisposition!=$scbValue)
			$pidArr[] =$pid;
	}
	$profileStr     =implode(",",$pidArr);
	if($profileStr)
		deleteProfiles($db_master,$profileStr);
	foreach($pidArr as $key=>$profileid){
		addLog($profileid,$campaignName,$str,$action,$db_js_111);
	}
}


/* Functions added */
function updateDialStatus($profileid,$dialStatus,$db_master)
{
        $sql= "update incentive.SALES_CSV_DATA_FAILED_PAYMENT SET DIAL_STATUS='$dialStatus' WHERE PROFILEID='$profileid'";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));

}
function checkProfileDisposition($pid, $campaignName,$scbValue,$db_dialer,$dialerLogObj)
{
	$squery1 = "SELECT Last_disposition FROM easy.dbo.ct_$campaignName JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$pid' AND Last_disposition='$scbValue'";
	$sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaignName,$db_dialer,1);
	if($srow1 = mssql_fetch_array($sresult1))
		$lastDisp =trim($srow1['Last_disposition']);
	return $lastDisp;
}
// Add logging
function addLog($profileid,$campaignName,$str='',$action,$db_js_111)
{
	$log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaignName','$str',now(),'$action')";
        mysql_query($log_query,$db_js_111) or die($log_query.mysql_error($db_js_111));
}

// fetch FP profiles
function fetchProfiles($db_js)
{
        $profileArr =array();
        $sql= "SELECT PROFILEID,DIAL_STATUS,CSV_ENTRY_DATE FROM incentive.SALES_CSV_DATA_FAILED_PAYMENT";
        $res=mysql_query($sql,$db_js) or die($sql.mysql_error($db_js));
        while($myrow = mysql_fetch_array($res)){
		$dialStatus =$myrow["DIAL_STATUS"];
		$pid	    =$myrow['PROFILEID'];	   	
		if($dialStatus)
			$eligibleArr[] =$pid;
		else
			$inEligibleArr[] =$pid;

		$allDataArr[$pid] =$myrow['CSV_ENTRY_DATE'];
	}	
	return array("ELIGIBLE"=>$eligibleArr,"IN_ELIGIBLE"=>$inEligibleArr,"ALL_DATA"=>$allDataArr);
}

// Delete ineligible profiles from FP table
function deleteProfiles($db_master,$profiles)
{
	$sql= "delete FROM incentive.SALES_CSV_DATA_FAILED_PAYMENT WHERE PROFILEID IN ($profiles)";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));
}

// Fetch allocated profiles
function getAllocatedProfiles($profileArr,$db_js)
{
	$dataArr	=array();
	$profileStr     =implode(",",$profileArr);	
        $sql= "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID IN($profileStr)";
        $res=mysql_query($sql,$db_js) or die($sql.mysql_error($db_js));
	while($myrow = mysql_fetch_array($res)){
		$dataArr[] =$myrow["PROFILEID"];
	}
	return $dataArr;
}

// Fetch Paid profiles
function getPaidProfiles($profileArr,$db_js,$dateTime)
{
	$dataArr	=array();
        $profileStr     =implode(",",$profileArr);
        $sql= "SELECT distinct PROFILEID FROM billing.PURCHASES WHERE PROFILEID IN($profileStr) AND STATUS='DONE' AND ENTRY_DT>='$dateTime' AND MEMBERSHIP='Y'";
        $res=mysql_query($sql,$db_js) or die($sql.mysql_error($db_js));
        while($myrow = mysql_fetch_array($res)){
                $dataArr[] =$myrow["PROFILEID"];
        }
        return $dataArr;
}

?>
