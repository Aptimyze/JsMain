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

$db = connect_db();

$sql = "select PROFILEID,BILL_ID from billing.EXCLUSIVE_MEMBERS WHERE ASSIGNED_DT>='2017-08-01' AND BILLING_DT>='2017-08-01 00:00:00'";
$res = mysql_query_decide($sql, $db) or die($sql . mysql_error_js());

$count_autoApply = 0;
$profileStr="";
while ($row = mysql_fetch_array($res)) {
    if(!empty($row['PROFILEID']) && !empty($row['BILL_ID'])){
        $sql1 = "select PROFILEID from billing.SERVICE_STATUS WHERE BILLID=".$row['BILL_ID']." AND SERVICEID LIKE '%X%' AND ACTIVE='Y' AND ACTIVATED='Y'";

        $res1 = mysql_query_decide($sql1, $db) or die($sql1 . mysql_error_js());

        $row1 = mysql_fetch_array($res1);

        if(!empty($row1) && !empty($row1['PROFILEID'])){
            $profileStr .= $row1['PROFILEID'].",";
            startAutoApply($row1['PROFILEID'], '');
            addAutoApplyLog($row1['PROFILEID'],'MEMBERSHIP',"X");
            ++$count_autoApply;
        }
        unset($row1); 
    }
}
echo "count_autoApply"."\n";
var_dump($count_autoApply);

if(!empty($profileStr)){
    $profileStr = substr($profileStr,0,-1);
}
echo "profileStr"."\n";
var_dump($profileStr);