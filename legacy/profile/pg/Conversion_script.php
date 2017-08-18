<?php
die("Can be used for starting the orders");
include ("../connect.inc");
include ("functions.php");
connect_db();
$Order_Id = $argv[1];
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$membershipObj = new Membership;
$id_arr = explode("-", $Order_Id);
print_r($id_arr);
$id = $id_arr[1];
echo $sql = " select count(*) as cnt from  billing.ORDERS where ID=$id";
$result = mysql_query_decide($sql) or die('failure');
$myrow_1 = mysql_fetch_array($result);
if ($myrow_1['cnt'] > 0) {
    $dup = false;
    $AuthDesc = 'Y';
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
    
    //ret should work for voucher code working
    echo "Ret-$ret";
    if ($ret) {
        $membershipObj->startServiceOrder($Order_Id);
        echo $sql_tmp = "INSERT INTO billing.ORDERS_STARTED(ORDERID, ENTRY_DT) VALUES('$Order_Id',now())";
        $res_tmp = mysql_query_decide($sql_tmp) or die(mysql_error_js() . $sql_tmp);
        
        echo "Service Started";
    } 
    else echo "Not again\n";
} 
else echo " orderid not available";
?>
