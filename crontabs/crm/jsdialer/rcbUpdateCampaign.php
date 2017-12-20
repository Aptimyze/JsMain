<?php
/*********************************************************************************************
 * FILE NAME       : paidCampaignProcess.php
 * DESCRIPTION     : Change the Dialer Dial status to 0
 *********************************************************************************************/
include "MysqlDbConstants.class.php";
include_once "DialerLog.class.php";
include("DialerApplication.class.php");
$dialerLogObj = new DialerLog();

//Open connection at JSDB
$db_js     = mysql_connect(MysqlDbConstants::$misSlave['HOST'], MysqlDbConstants::$misSlave['USER'], MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'], MysqlDbConstants::$master['USER'], MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'], MysqlDbConstants::$slave111['USER'], MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'], MysqlDbConstants::$dialer['USER'], MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");
mysql_query("set session wait_timeout=2000",$db_master);

$dialerApplicationObj = new DialerApplication();
$campaignName   = 'OB_JS_RCB';
$action         = 'STOP';
$action1	= 'STOP-D';	
$str            = 'Dial_Status=0';
$date2DayBefore = date("Y-m-d H:i:s", time() - 48 * 60 * 60);
$scbValue 	='Schedule Call Back';	
$profilesArr   = fetchProfiles($db_master);

$eligibleArr   = $profilesArr['ELIGIBLE'];
$inEligibleArr = $profilesArr['IN_ELIGIBLE'];
$allPids        =array_merge($eligibleArr, $inEligibleArr);

$deletedArr     = $dialerApplicationObj->getDeletedProfiles($allPids,$db_js);
$paidArr      	= getPaidProfiles($allPids, $db_master, $date2DayBefore);
$paidDeletedArrNew = array_merge($deletedArr, $paidArr);
$paidDeletedArrNew = array_unique($paidDeletedArrNew);
$paidDeletedArrNew = array_values($paidDeletedArrNew);
if(is_array($inEligibleArr)){
	$inEligibleArrNew =array_diff($inEligibleArr, $paidDeletedArrNew);
	$inEligibleArrNew =array_values($inEligibleArrNew);
}


// Stop profiles which are 2 days old
    $query0 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE CSV_ENTRY_DATE<'$date2DayBefore' AND Last_disposition IS NULL";
    mssql_query($query0, $db_dialer) or $dialerLogObj->logError($query0, $campaignName, $db_dialer, 1);

    $query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE CSV_ENTRY_DATE<'$date2DayBefore' AND Last_disposition IS NOT NULL AND Last_disposition!='$scbValue'";
    mssql_query($query1, $db_dialer) or $dialerLogObj->logError($query1, $campaignName, $db_dialer, 1);

if(is_array($inEligibleArrNew)) {
    foreach($inEligibleArrNew as $key=>$pid) {
	$getLatDispositionArr 	=checkProfileDisposition($pid, $campaignName,$scbValue,$db_dialer,$dialerLogObj);
        $getLatDisposition      =$getLatDispositionArr['Last_disposition'];
        $easyCode               =$getLatDispositionArr['easycode'];
        if($getLatDisposition==$scbValue){
        	$query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=3 WHERE PROFILEID='$pid' AND Dial_Status!='3' AND easycode='$easyCode'";
                mssql_query($query1,$db_dialer)  or $dialerLogObj->logError($query1,$campaignName,$db_dialer,'1',$campaignName);
        }
	else{
		$pidArr[] =$pid;
	}
    }
  if(is_array($pidArr)){
    $profileStr     =implode(",",$pidArr);
    if($profileStr)
	deleteProfiles($db_master,$profileStr);
    
    foreach ($pidArr as $key => $profileid) {
	addLog($profileid, $campaignName, $str, $action1, $db_js_111);	
    }
  }
}

// Stop profiles which are paid 
if (count($paidDeletedArrNew > 0)) {
    foreach ($paidDeletedArrNew as $key => $profileid) {
            $query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=0 WHERE PROFILEID='$profileid'";
            mssql_query($query1, $db_dialer) or $dialerLogObj->logError($query1, $campaignName, $db_dialer, 1);
	    deleteProfiles($db_master,$profileid);	
            addLog($profileid, $campaignName, $str, $action, $db_js_111);
    }
}

// Fetch profile with dial status 0
function fetchProfiles($db_js)
{
    $eligibleArr =array();
    $inEligibleArr =array();	
    $sql        = "SELECT PROFILEID, DIAL_STATUS, CSV_ENTRY_DATE FROM incentive.SALES_CSV_DATA_RCB";
    $res        = mysql_query($sql, $db_js) or die($sql . mysql_error($db_js));
    while ($myrow = mysql_fetch_array($res)) {
        $dialStatus = $myrow["DIAL_STATUS"];
        $pid        = $myrow['PROFILEID'];
        if ($dialStatus) {
            $eligibleArr[] = $pid;
        } else {
            $inEligibleArr[] = $pid;
        }
    }
    return array("ELIGIBLE" => $eligibleArr, "IN_ELIGIBLE" => $inEligibleArr);
}

function updateDialStatus($profileid,$dialStatus,$db_master)
{
        $sql= "update incentive.SALES_CSV_DATA_RCB SET DIAL_STATUS='$dialStatus' WHERE PROFILEID='$profileid'";
        $res=mysql_query($sql,$db_master) or die($sql.mysql_error($db_master));

}
function checkProfileDisposition($pid, $campaignName,$scbValue,$db_dialer,$dialerLogObj)
{
        $lastDisp =array();
        $squery1 = "SELECT top 1 Last_disposition,easycode FROM easy.dbo.ct_$campaignName JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$pid' order by CSV_ENTRY_DATE DESC";
        $sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaignName,$db_dialer,'1',$campaignName);
        if($srow1 = mssql_fetch_array($sresult1)){
                $val =$srow1['Last_disposition'];
		$val =trim($val);
                if($val==$scbValue)
                        $lastDisp =$srow1;
        }
        return $lastDisp;
}

function deleteProfiles($db_master, $profiles)
{
    $sql = "delete FROM incentive.SALES_CSV_DATA_RCB WHERE PROFILEID IN ($profiles)";
    $res = mysql_query($sql, $db_master) or die($sql . mysql_error($db_master));
}

// Add logging
function addLog($profileid, $campaignName, $str = '', $action, $db_js_111)
{
    $log_query = "INSERT into js_crm.DIALER_UPDATE_LOG (PROFILEID,CAMPAIGN,UPDATE_STRING,TIME,ACTION) VALUES ('$profileid','$campaignName','$str',now(),'$action')";
    mysql_query($log_query, $db_js_111) or die($log_query . mysql_error($db_js_111));
}

// Fetch Paid profiles
function getPaidProfiles($profileArr, $db_master, $date2DayBefore)
{
    $dataArr    = array();
    $profileStr = implode(",", $profileArr);
    $sql= "SELECT distinct PROFILEID FROM billing.PURCHASES WHERE PROFILEID IN($profileStr) AND STATUS='DONE' AND ENTRY_DT>='$date2DayBefore' AND MEMBERSHIP='Y'";	
    $res        = mysql_query($sql, $db_master) or die($sql . mysql_error($db_master));
    while ($myrow = mysql_fetch_array($res)) {
        $dataArr[] = $myrow["PROFILEID"];
    }
    return $dataArr;
}
