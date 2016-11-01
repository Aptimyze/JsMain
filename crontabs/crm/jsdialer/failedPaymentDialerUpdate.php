<?php
/*********************************************************************************************
* FILE NAME   	: failedPaymentDialerUpdate.php 
* DESCRIPTION 	: Change the Dialer Dial status to 0
* MADE BY     	: MANOJ RANA 
*********************************************************************************************/
include_once("MysqlDbConstants.class.php");
include("DialerLog.class.php");
$dialerLogObj =new DialerLog();

//Open connection at JSDB
$db_js = mysql_connect(MysqlDbConstants::$misSlave['HOST'],MysqlDbConstants::$misSlave['USER'],MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'],MysqlDbConstants::$master['USER'],MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'],MysqlDbConstants::$slave111['USER'],MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'],MysqlDbConstants::$dialer['USER'],MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$dateTime       =date("Y-m-d H:i:s",time()-22.5*60*60);
$campaignName	='FP_JS';
$action		='STOP';
$str		='Dial_Status=0';
$profilesArr 	=fetchProfiles($db_js);
$eligibleArr	=$profilesArr['ELIGIBLE'];
$inEligibleArr	=$profilesArr['IN_ELIGIBLE'];

$allocatedArr	=getAllocatedProfiles($eligibleArr,$db_js);
$paidArr	=getPaidProfiles($eligibleArr,$db_js,$dateTime);
$eligibleArrNew	=array_merge($allocatedArr,$paidArr);
$eligibleArrNew	=array_unique($eligibleArrNew);
$eligibleArrNew =array_values($eligibleArrNew);

// Stop profiles which are paid and allocated
if(count($eligibleArrNew>0)){
	foreach($eligibleArrNew as $key=>$profileid){
		
		$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=0 WHERE PROFILEID='$profileid'";
		mssql_query($query1,$db_dialer)  or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);

		$deleteArr[] =$profileid;
		addLog($profileid,$campaignName,$str,$action,$db_js_111);
	}
	if(is_array($deleteArr)){
		$profileStr     =implode(",",$deleteArr);
		deleteProfiles($db_master,$profileStr);
		unset($deleteArr);	
	}
}

// Stop profiles which are 12 hours old
if(is_array($inEligibleArr)){
	$profileStr     =implode(",",$inEligibleArr);
	$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE Dial_Status=1 AND Login_Timestamp<'$dateTime'";
	mssql_query($query1,$db_dialer) or $dialerLogObj->logError($query1,$campaignName,$db_dialer,1);

	if($profileStr)
		deleteProfiles($db_master,$profileStr);
	foreach($inEligibleArr as $key=>$profileid){
		addLog($profileid,$campaignName,$str,$action,$db_js_111);
	}
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
        $sql= "SELECT PROFILEID,DIAL_STATUS FROM incentive.SALES_CSV_DATA_FAILED_PAYMENT";
        $res=mysql_query($sql,$db_js) or die($sql.mysql_error($db_js));
        while($myrow = mysql_fetch_array($res)){
		$dialStatus =$myrow["DIAL_STATUS"];
		if($dialStatus)
			$eligibleArr[] =$myrow["PROFILEID"];
		else
			$inEligibleArr[] =$myrow["PROFILEID"];
	}	
	return array("ELIGIBLE"=>$eligibleArr,"IN_ELIGIBLE"=>$inEligibleArr);
}

// Delete ineligible profiles from FP table
function deleteProfiles($db_master,$profiles)
{
	$sql= "delete FROM incentive.SALES_CSV_DATA_FAILED_PAYMENT WHERE PROFILEID IN ($profiles)";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_js));
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
