<?php
chdir(dirname(__FILE__));
include_once "../jsadmin/connect.inc";
if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;
}

include_once "../jsadmin/connect.inc";
include_once "functions.php";
include_once "comfunc_sums.php";
include_once "../jsadmin/ap_common.php";
include_once JsConstants::$docRoot . "/classes/JProileUpdateLib.php";
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$db = connect_db();
$ts = time();
//current date
$curdate = date("Y-m-d", $ts);
//10 days before
$ts -= (24 * 60 * 60) * 10;
$before_ten_days = date("Y-m-d", $ts);

$sql = "SELECT ID,PROFILEID, EXPIRY_DT, SERVEFOR, BILLID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT BETWEEN '$before_ten_days' AND '$curdate' AND ACTIVE='Y'";
$res = mysql_query_decide($sql, $db) or die($sql . mysql_error_js());
while ($row = mysql_fetch_array($res)) {
    $id                = $row['ID'];
    $pid               = $row['PROFILEID'];
    $profileids_arr1[] = $pid;
    $billid            = $row['BILLID'];
    //if(strstr($row['SERVEFOR'],'O'))
    //    $offline_arr[$pid]=$pid;
    if (($row['SERVEFOR'] == 'L') || ($row['SERVEFOR'] == 'T') || (strpos($row['SERVEFOR'],'X')!==false)) {
        if(strpos($row['SERVEFOR'],'X')!==false){
            $assisted_arr[$pid][] = 'X';
        }
        else{
            $assisted_arr[$pid][] = $row['SERVEFOR'];
        }
    }

    $sql1 = "UPDATE billing.SERVICE_STATUS SET ACTIVE='E' WHERE ID=$id";
    $res1 = mysql_query_decide($sql1, $db) or die($sql . mysql_error_js());
    if ($row['SERVEFOR'] == 'I') {
        endIntroCalls($pid);
    }

    //Reactivate previous old unlimited membership after expiration of new service
    $membershipHandler = new MembershipHandler();
    $membershipHandler->changeUnlimitedServiceStatusForNewService($billid,false);

    // Deleting entry from billing.EXCLUSIVE_SERVICING as soon as subs expire
    if (strpos($row['SERVEFOR'],'X')){
        $exclusiveFunctionsObj = new ExclusiveFunctions();
        $exclusiveFunctionsObj->deleteEntryFromExclusiveServicing($pid,'X',$billid);
    }
}
$profileids_arr_n = array_unique($profileids_arr1);
$profileids_arr   = array_values($profileids_arr_n);
if (count($profileids_arr) > 0) {
    @mysql_ping_js($db);
    for ($i = 0; $i < count($profileids_arr); $i++) {
        unset($servefor1);
        unset($servefor);
        unset($servefor_str);
        unset($servefor_arr);
        unset($servefor_arr1);
        unset($expire_assist);

        $check_main = array();
        $profile    = $profileids_arr[$i];
        $sql        = "SELECT SERVEFOR,ACTIVATED FROM billing.SERVICE_STATUS WHERE PROFILEID=$profile AND ACTIVE='Y' AND EXPIRY_DT>'$curdate'";
        $res        = mysql_query_decide($sql, $db) or die($sql . mysql_error_js());
        if (mysql_num_rows($res)) {
            while ($row = mysql_fetch_array($res)) {
                if ($row['ACTIVATED'] == 'Y') {
                    $servefor1 .= "," . $row['SERVEFOR'];
                }

                $check_main[] = $row['SERVEFOR'];
            }
            $servefor      = ltrim($servefor1, ',');
            $servefor_arr  = explode(",", $servefor);
            $servefor_arr1 = array_unique($servefor_arr);
            $servefor_str  = implode(",", $servefor_arr1);
        }
        if (is_array($assisted_arr[$profile])) {
            $expire_assist = array_diff($assisted_arr[$profile], $check_main);
            
            if (in_array("X", $expire_assist)) {
                endAutoApply($profile);
            }

            if (in_array("L", $expire_assist)) {
                endHomeDelivery($profile);
            }

        }
        /*if(@in_array("I",$servefor_arr1) && (!@in_array("F",$check_main) && !@in_array("F,D",$check_main) && !@in_array("D,F",$check_main)))
        {

        $sql1="UPDATE billing.SERVICE_STATUS SET ACTIVE='E' WHERE PROFILEID=$profile AND ACTIVE='Y' AND SERVEFOR='I'";
        $res1=mysql_query_decide($sql1) or die($sql.mysql_error_js());
        endIntroCalls($profile);
        }*/
        /*$sql1="UPDATE newjs.JPROFILE set SUBSCRIPTION='$servefor_str' where PROFILEID='$profile' ";
        mysql_query_decide($sql1) or die($sql1.mysql_error_js());*/
        $jprofileObj = JProfileUpdateLib::getInstance('newjs_master');
        $paramArr    = array("SUBSCRIPTION" => $servefor_str);
        $jprofileObj->editJPROFILE($paramArr, $profile, 'PROFILEID');

        $count_expire_today++;

        // CLEAR MEMCACHE FOR CURRENT USER
        $memCacheObject = JsMemcache::getInstance();
        if ($memCacheObject) {
            $memCacheObject->remove($profile . '_MEM_NAME');
            $memCacheObject->remove($profile . "_MEM_OCB_MESSAGE_API17");
            $memCacheObject->remove($profile . "_MEM_HAMB_MESSAGE");
            $memCacheObject->remove($profile . "_MEM_SUBSTATUS_ARRAY");
        }
    }
    if (count($profileids_arr) > 0) {
        $proid_str = implode("','", $profileids_arr);
    }

    if (count($offline_arr) > 0) {
        $offline_str = implode("','", $offline_arr);
        stop_offline_service($offline_str);
    }
    unset($profileids_arr);
    unset($offline_arr);
}
