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

$db = connect_db();
$curdate = date("Y-m-d");


$sql = "SELECT GROUP_CONCAT(ID) AS ID,PROFILEID,GROUP_CONCAT(SERVEFOR) AS SERVEFOR FROM billing.SERVICE_STATUS WHERE EXPIRY_DT = '$curdate' AND ACTIVE='E' GROUP BY PROFILEID";
$res = mysql_query_decide($sql, $db) or die($sql . mysql_error_js());
$count_rollback = 0;
$jprofileObj = JProfileUpdateLib::getInstance('newjs_master');
$memCacheObject = JsMemcache::getInstance();
while ($row = mysql_fetch_array($res)) {
    
    $id               = $row['ID'];
    $pid               = $row['PROFILEID'];
    $servefor_str      = $row['SERVEFOR'];
    if($servefor_str != ''){
        $sql1 = "UPDATE billing.SERVICE_STATUS SET ACTIVE='Y' WHERE ID IN($id)";
        echo "$sql1"."\n";
        $res1 = mysql_query_decide($sql1, $db) or die($sql . mysql_error_js());
        $paramArr    = array("SUBSCRIPTION" => $servefor_str);
        $jprofileObj->editJPROFILE($paramArr, $profile, 'PROFILEID');
        ++$count_rollback;

        if ($memCacheObject) {
            $memCacheObject->remove($pid . '_MEM_NAME');
            $memCacheObject->remove($pid . "_MEM_OCB_MESSAGE_API17");
            $memCacheObject->remove($pid . "_MEM_HAMB_MESSAGE");
            $memCacheObject->remove($pid . "_MEM_SUBSTATUS_ARRAY");
        }
    }
}
unset($jprofileObj);
var_dump($count_rollback);