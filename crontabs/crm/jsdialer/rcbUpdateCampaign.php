<?php
/*********************************************************************************************
 * FILE NAME       : paidCampaignProcess.php
 * DESCRIPTION     : Change the Dialer Dial status to 0
 *********************************************************************************************/
include "MysqlDbConstants.class.php";
include_once "DialerLog.class.php";
$dialerLogObj = new DialerLog();

//Open connection at JSDB
$db_js     = mysql_connect(MysqlDbConstants::$misSlave['HOST'], MysqlDbConstants::$misSlave['USER'], MysqlDbConstants::$misSlave['PASS']) or die("Unable to connect to nmit server");
$db_master = mysql_connect(MysqlDbConstants::$master['HOST'], MysqlDbConstants::$master['USER'], MysqlDbConstants::$master['PASS']) or die("Unable to connect to nmit server ");
$db_js_111 = mysql_connect(MysqlDbConstants::$slave111['HOST'], MysqlDbConstants::$slave111['USER'], MysqlDbConstants::$slave111['PASS']) or die("Unable to connect to local-111 server");
$db_dialer = mssql_connect(MysqlDbConstants::$dialer['HOST'], MysqlDbConstants::$dialer['USER'], MysqlDbConstants::$dialer['PASS']) or die("Unable to connect to dialer server");

$campaignName   = 'OB_JS_RCB';
$action         = 'STOP';
$str            = 'Dial_Status=0';
$date2DayBefore = date("Y-m-d H:i:s", time() - 58 * 60 * 60);
$scbValue 	='Schedule Call Back';	
$profilesArr   = fetchProfiles($db_master);

$eligibleArr   = $profilesArr['ELIGIBLE'];
$inEligibleArr = $profilesArr['IN_ELIGIBLE'];
if(is_array($inEligibleArr))
	$profileStrIneligible = implode(",", $inEligibleArr);

$allocatedArr = getAllocatedProfiles($eligibleArr, $db_js);
$paidArr      = getPaidProfiles($eligibleArr, $db_js);

if (!empty($allocatedArr) && !empty($paidArr)) {
    $eligibleArrNew = array_merge($allocatedArr, $paidArr);
} else if (empty($allocatedArr)) {
    $eligibleArrNew = $paidArr;
} else if (empty($paidArr)) {
    $eligibleArrNew = $allocatedArr;
} else {
    $eligibleArrNew = array();
}
$eligibleArrNew = array_unique($eligibleArrNew);
$eligibleArrNew = array_values($eligibleArrNew);

// Stop profiles which are 2 days old
if ($profileStrIneligible != '') {
    // Set dial status=0 for paid campaign

    $query0 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE CSV_ENTRY_DATE<'$date2DayBefore' AND Last_disposition IS NULL";
    mssql_query($query0, $db_dialer) or $dialerLogObj->logError($query0, $campaignName, $db_dialer, 1);

    $query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status='0' WHERE CSV_ENTRY_DATE<'$date2DayBefore' AND Last_disposition IS NOT NULL AND Last_disposition!='$scbValue'";
    mssql_query($query1, $db_dialer) or $dialerLogObj->logError($query1, $campaignName, $db_dialer, 1);

    foreach($inEligibleArr as $key=>$pid) {
	$getLatDisposition =checkProfileDisposition($pid, $campaignName,$scbValue,$db_dialer,$dialerLogObj);
	if($getLatDisposition!=$scbValue)
		$pidArr[] =$pid;
    }
  if(is_array($pidArr)){
    $profileStr     =implode(",",$pidArr);
    if($profileStr)
	deleteProfiles($db_master,$profileStr);
    
    foreach ($pidArr as $key => $profileid) {
	addLog($profileid, $campaignName, $str, $action, $db_js_111);	
    }
  }
}

// Stop profiles which are paid and allocated
if (count($eligibleArrNew > 0)) {
    foreach ($eligibleArrNew as $key => $profileid) {
            $query1 = "UPDATE easy.dbo.ct_$campaignName SET Dial_Status=0 WHERE PROFILEID='$profileid'";
            mssql_query($query1, $db_dialer) or $dialerLogObj->logError($query1, $campaignName, $db_dialer, 1);
	    deleteProfiles($db_master,$profileid);	
            addLog($profileid, $campaignName, $str, $action, $db_js_111);
    }
}

// mail added
$profilesStr =$profileStrIneligible."###".$profileStrEligible;
$to   = "manoj.rana@naukri.com";
$sub  = "Dialer updates of RCB Campaign Process.";
$from = "From:vibhor.garg@jeevansathi.com";
//mail($to, $sub, $profilesStr, $from);

// Fetch profile with dial status 0
function fetchProfiles($db_js)
{
    $profileArr = array();
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
	$squery1 = "SELECT Last_disposition FROM easy.dbo.ct_$campaignName JOIN easy.dbo.ph_contact ON easycode=code WHERE PROFILEID ='$pid' AND Last_disposition='$scbValue'";
	$sresult1 = mssql_query($squery1,$db_dialer) or $dialerLogObj->logError($squery1,$campaignName,$db_dialer,1);
	if($srow1 = mssql_fetch_array($sresult1))
		$lastDisp =trim($srow1['Last_disposition']);
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

// Fetch allocated profiles
function getAllocatedProfiles($profileArr, $db_js)
{
    $dataArr    = array();
    $profileStr = implode(",", $profileArr);
    $sql        = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID IN($profileStr)";
    $res        = mysql_query($sql, $db_js) or die($sql . mysql_error($db_js));
    while ($myrow = mysql_fetch_array($res)) {
        $dataArr[] = $myrow["PROFILEID"];
    }
    return $dataArr;
}

// Fetch Paid profiles
function getPaidProfiles($profileArr, $db_js)
{
    $dataArr    = array();
    $profileStr = implode(",", $profileArr);
    $sql        = "SELECT distinct PROFILEID FROM billing.SERVICE_STATUS WHERE PROFILEID IN($profileStr) AND SERVEFOR LIKE '%F%' AND ACTIVE='Y' AND ACTIVATED='Y'";
    $res        = mysql_query($sql, $db_js) or die($sql . mysql_error($db_js));
    while ($myrow = mysql_fetch_array($res)) {
        $dataArr[] = $myrow["PROFILEID"];
    }
    return $dataArr;
}
