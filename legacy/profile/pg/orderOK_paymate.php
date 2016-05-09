<?php

include ("../connect.inc");
include ("functions.php");
connect_db();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$serObj = new Services;

$data = authenticated($checksum);

$sql_orderid = "select ORDERID from billing.PAYMATE_ORDERID where ID='$orderid'";
$res_orderid = mysql_query_decide($sql_orderid) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_orderid, "ShowErrTemplate");
if (mysql_num_rows($res_orderid) >= 0) {
    $myrow_orderid = mysql_fetch_array($res_orderid);
    $Order_Id = $myrow_orderid['ORDERID'];
    $sql = "insert into billing.PAYMATE (ORDERID,RESPONSECODE,PRODUCTCOST,TRANSACTIONID) values ('$Order_Id','$STATUS','$txn_amount','$paymate_trxid')";
    mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    if ($STATUS == 400) {
        $dup = false;
        $status = "Y";
        $ret = updtOrder($Order_Id, $dup, $status);
        
        if (!$dup && $ret) start_service($Order_Id);
        
        list($part1, $part2) = explode("-", $Order_Id);
        $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
        $res = mysql_query_decide($sql);
        
        if (mysql_num_rows($res) > 0) {
            $myrow = mysql_fetch_array($res);
            $Amount = $myrow["AMOUNT"];
            list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
            $orderdate = my_format_date($day, $month, $year);
            
            list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
            $expirydate = my_format_date($day, $month, $year);
            $paytype = "RS.";
            $ser_name = $serObj->getServiceName($myrow["SERVICEMAIN"]);
            $QueryString = '';
            foreach ($ser_name as $Key => $Value) {
                $QueryString.= ',' . $Value[NAME];
            }
            $smarty->assign("MEMTYPE", substr($QueryString, 1));
            $service_main_details = getServiceDetails($myrow["SERVICEMAIN"]);
            $smarty->assign("PERIOD", $service_main_details["DURATION"]);
            $smarty->assign("AMOUNT", $Amount);
            $smarty->assign("ORDERID", $Order_Id);
            $smarty->assign("ORDERDATE", $orderdate);
            $smarty->assign("EXPIRYDATE", $expirydate);
            $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
            $smarty->assign("PAYTYPE", $paytype);
            
            $smarty->assign("CHECKSUM", $Merchant_Param);
            $smarty->assign("USERNAME", $data[USERNAME]);
            $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
            $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
            set_subscription_cookie($myrow['PROFILEID']);
            payment_thanks_things_to_do($myrow['PROFILEID'], $myrow['SET_ACTIVATE']);
            
            $smarty->display("pg/orderreceipt.htm");
        } 
        else {
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            $smarty->display("pg/ordererror.htm");
        }
    } 
    else
    {
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        $smarty->display("pg/ordererror.htm");
    }
} 
else
{
    $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
    $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
    
    $smarty->display("pg/ordererror.htm");
}
?>
