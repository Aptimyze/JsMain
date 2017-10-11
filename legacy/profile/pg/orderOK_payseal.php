<?php
include ("../connect.inc");
require ("libfuncs.php");
include ("functions.php");
connect_db();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$serObj = new Services;
$membershipObj = new Membership;

$data = authenticated($checksum);

if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
}

$ID = $id;
unset($trans_status);
$sql = "Select * from billing.PAYSEAL where ID='$ID'";
$result = mysql_query_decide($sql) or die("no select" . $sql);
while ($myrow = mysql_fetch_array($result)) {
    $orderid = $myrow["ORDERID"];
    $trans_status = $myrow["STATUS"];
    $merchantid = $myrow["MERCHANTID"];
}

if ($trans_status == 'D' || $trans_status == '') {
    $ip = FetchClientIP();
    if (strstr($ip, ",")) {
        $ip_new = explode(",", $ip);
        $ip = $ip_new[1];
    }
    $membershipObj->log_payment_status($orderid, 'U', 'PAYSEAL', $ip);
    $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
    $smarty->assign("ORDERID", $orderid);
    $smarty->assign("PROFILEID", $profileid);
    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
    $smarty->display("pg/ordererror.htm");
} 
else {
    $sql_up = "UPDATE billing.PAYSEAL set STATUS='D' where ID='$ID'";
    mysql_query_decide($sql_up) or die("no dup updated" . $sql_up);
    
    $message_str = "Order ID : $orderid\n Status : $trans_status\n Merchant Id : $merchantid";
    
    if ($trans_status == "0") {
        $membershipObj->log_payment_status($orderid, 'S', 'PAYSEAL', $message_str);
        
        $Order_Id = $orderid;
        $dup = false;
        $status = "Y";
        $ret = $membershipObj->updtOrder($orderid, $dup, $status);
        
        if (!$dup && $ret) $membershipObj->startServiceOrder($orderid);
        
        list($part1, $part2) = explode("-", $Order_Id);
        $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
        $res = mysql_query_decide($sql);
        $ordrDeviceObj = new billing_ORDERS_DEVICE();
        $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
        
        if (mysql_num_rows($res)) {
            $myrow = mysql_fetch_array($res);
            $Amount = $myrow["AMOUNT"];
            list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
            $orderdate = my_format_date($day, $month, $year);
            
            list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
            $expirydate = my_format_date($day, $month, $year);
            
            if ($myrow["CURTYPE"] == 'DOL') {
                $paytype = "US $";
            } 
            else {
                $paytype = "RS.";
            }
            $ser_name = $serObj->getServiceName($myrow["SERVICEMAIN"]);
            $memHandlerObj = new MembershipHandler();
            list($vas, $main) = $memHandlerObj->getMobileDisplayServiceArray($ser_name, $part2, $part1, $myrow['PROFILEID'], $myrow['ENTRY_DT'], $myrow['EXPIRY_DT']);
            $smarty->assign("vasServices", $vas);
            $smarty->assign("mainServices", $main);
            
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
            $smarty->assign("PROFILEID", $profileid);
            $smarty->assign("USERNAME", $data[USERNAME]);
            $smarty->assign("CHECKSUM", $Merchant_Param);
            $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
            $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
            
            set_subscription_cookie($myrow['PROFILEID']);
            if (MobileCommon::isMobile()) {
                if (strpos($device, 'mobile_website') === FALSE) {
                    $smarty->display("pg/mob_redirect_orderreceipt.htm");
                } 
                else {
                    if (BrowserCheck::IsHtml5Browser()) {
                        $smarty->display("pg/rev_redirect_orderreceipt.htm");
                    } 
                    else {
                        $smarty->display("pg/mob_orderreceipt.htm");
                    }
                }
            } 
            else {
                $smarty->display("pg/orderreceipt.htm");
            }
        }
    } 
    else {
        $membershipObj->log_payment_status($orderid, 'F', 'PAYSEAL', $message_str);
        $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
        $smarty->assign("PROFILEID", $profileid);
        $smarty->assign("ORDERID", $orderid);
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        $Order_Id = $orderid;
        list($part1, $part2) = explode("-", $Order_Id);
        $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
        $res = mysql_query_decide($sql);
        $ordrDeviceObj = new billing_ORDERS_DEVICE();
        $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
        if (MobileCommon::isMobile()) {
            if (strpos($device, 'mobile_website') === FALSE) {
                $smarty->display("pg/mob_redirect_ordererror.htm");
            } 
            else {
                if (BrowserCheck::IsHtml5Browser()) {
                    $smarty->display("pg/rev_redirect_ordererror.htm");
                } 
                else {
                    $smarty->display("pg/mob_ordererror.htm");
                }
            }
        } 
        else {
            $smarty->display("pg/ordererror.htm");
        }
    }
}
?>
