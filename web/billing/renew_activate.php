<?php
chdir(dirname(__FILE__));
include_once "../jsadmin/connect.inc";
include_once "../jsadmin/ap_common.php";
$tt      = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
$curdate = date("Y-m-d", $tt);
include_once JsConstants::$docRoot . "/classes/JProfileUpdateLib.php";

$db = connect_db();
mysql_query("set session wait_timeout=600", $db);
//$db_slave = connect_slave();

$sql = "SELECT s.BILLID, s.PROFILEID, s.ID FROM billing.SERVICE_STATUS as s, billing.PURCHASES as p WHERE p.BILLID=s.BILLID and s.ACTIVATE_ON = '$curdate' AND p.STATUS='DONE' AND s.ACTIVE='Y'";
$res = mysql_query_decide($sql, $db) or logError("Error2:", $sql);

while ($row = mysql_fetch_array($res)) {
    $id                = $row['ID'];
    $pid               = $row['PROFILEID'];
    $profileids_arr1[] = $pid;

    $sql1 = "UPDATE billing.SERVICE_STATUS SET ACTIVATED='Y', ACTIVATED_ON='$curdate', ACTIVATE_ON='0000-00-00' WHERE ID=$id";
    $res1 = mysql_query_decide($sql1, $db) or die($sql1 . mysql_error_js());
}

$profileids_arr_n = array_unique($profileids_arr1);
$profileids_arr   = array_values($profileids_arr_n);

if ($profileids_arr) {
    for ($i = 0; $i < count($profileids_arr); $i++) {
        $profile = $profileids_arr[$i];
        unset($servefor1);
        unset($servefor);
        unset($servefor_str);
        unset($servefor_arr);
        unset($servefor_arr1);

        $sql = "SELECT SERVEFOR FROM billing.SERVICE_STATUS WHERE PROFILEID=$profile AND ACTIVE='Y' AND ACTIVATED='Y' AND EXPIRY_DT>'$curdate'";
        $res = mysql_query_decide($sql, $db) or die($sql . mysql_error_js());
        while ($row = mysql_fetch_array($res)) {
            $servefor1 .= "," . $row['SERVEFOR'];
        }

        $servefor      = ltrim($servefor1, ',');
        $servefor_arr  = explode(",", $servefor);
        $servefor_arr1 = array_unique($servefor_arr);
        $servefor_str  = implode(",", $servefor_arr1);

        $sql_offline = "SELECT PROFILEID,BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID=$profile ORDER BY BILLID DESC LIMIT 1";
        $res_offline = mysql_query_decide($sql_offline, $db) or die($sql . mysql_error_js());
        while ($row_offline = mysql_fetch_array($res_offline)) {
            $offline_bill = $row_offline["BILLID"];
        }

        $jprofileObj = JProfileUpdateLib::getInstance('newjs_master');
        if ($offline_bill) {
            $sql_off = "UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE='Y' WHERE PROFILEID=$profile AND BILLID=$offline_bill";
            mysql_query_decide($sql_off, $db) or die($sql_off . mysql_error_js());

            $paramArr = array("SUBSCRIPTION" => $servefor_str);
            $extraStr = "PREACTIVATED=IF(PREACTIVATED <> ACTIVATED, ACTIVATED,PREACTIVATED),ACTIVATED='Y',activatedKey=1";
            $jprofileObj->updateJProfileForBilling($paramArr, $profile, 'PROFILEID', $extraStr);
        } else {
            $paramArr = array("SUBSCRIPTION" => $servefor_str);
            $jprofileObj->editJPROFILE($paramArr, $profile, 'PROFILEID');

        }
        
        if(strpos($servefor_str, "X")!==false){
            startAutoApply($profile, "");
            addAutoApplyLog($profile,'MEMBERSHIP',"X");
        }

        // CLEAR MEMCACHE FOR CURRENT USER
        $memCacheObject = JsMemcache::getInstance();
        if ($memCacheObject) {
            $memCacheObject->remove($profile . '_MEM_NAME');
            $memCacheObject->remove($profile . "_MEM_OCB_MESSAGE_API17");
            $memCacheObject->remove($profile . "_MEM_HAMB_MESSAGE");
            $memCacheObject->remove($profile . "_MEM_SUBSTATUS_ARRAY");
        }

        // Code to sent service renewal SMS
        if (strstr($servefor_str, 'F') !== false) { // Main Membership got activated
            CommonUtility::sendPlusTrackInstantSMS('MEM_REN_ACT_CRON', $profile);
        }
        unset($offline_bill);

    }
}
