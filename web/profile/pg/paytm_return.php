<?php
include ("../connect.inc");
include ("functions.php");
connect_db();

include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");

$serObj = new Services;
$membershipObj = new Membership;

$checksum = $MERC_UNQ_REF;
$paramList = array();
$isValidChecksum = FALSE;
$paramList = $_POST;

if (MobileCommon::isMobile()) {
    include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/common_functions.inc");
    assignHamburgerSmartyVariables($profileid);
}

if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
}

$gatewayRespObj = new billing_GATEWAY_RESPONSE_LOG();

if ($profileid) {
    list($order_str, $order_num) = explode("-", $ORDERID);
    $responseMsg = serialize($_REQUEST);
    $gatewayRespObj->insertResponseMessage($profileid, $order_num, $order_str, 'PAYTM', $responseMsg);
}

if (JsConstants::$whichMachine == 'test') {
    $merchantID = gatewayConstants::$PayTmTestRsMerchantId;
    $salt = gatewayConstants::$PayTmTestRsSalt;
} 
else {
    $merchantID = gatewayConstants::$PayTmLiveRsMerchantId;
    $salt = gatewayConstants::$PayTmLiveRsSalt;
}

$Order_Id = $ORDERID;

if ($STATUS == "TXN_SUCCESS") {
    $AuthDesc = "Y";
    $ret_status = "S";
} 
elseif ($STATUS == "TXN_FAILURE") {
    $AuthDesc = "N";
    $ret_status = "F";
} 
else {
    $STATUS = "unknown";
    $ret_status = "U";
    $AuthDesc = "U";
}

$memHandlerObj = new MembershipHandler();
$billingPaymentStatusLogObj = new billing_PAYMENT_STATUS_LOG();
$membershipObj->log_payment_status($Order_Id, $ret_status, 'PAYTM', $STATUS);
$paytmChecksum = isset($CHECKSUMHASH) ? $CHECKSUMHASH : "";
$isValidChecksum = PayTmManager::verifychecksum_e($paramList, $salt, $paytmChecksum);

$dup = false;

if ($isValidChecksum && $AuthDesc == "Y") {
    $dup = false;
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
    $gatewayRespObj->updateDupRetStatus($profileid, $order_num, var_export($dup, 1), var_export($ret, 1));
    if (!$dup && $ret) $membershipObj->startServiceOrder($Order_Id);
    
    list($part1, $part2) = explode("-", $Order_Id);
    $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
    $res = mysql_query_decide($sql);
    $ordrDeviceObj = new billing_ORDERS_DEVICE();
    $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
    
    if (mysql_num_rows($res)) {
        $myrow = mysql_fetch_array($res);
        $Amount = $myrow["AMOUNT"];
        list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
        $order_date_mobile = date("d M Y", strtotime($myrow["ENTRY_DT"]));
        $orderdate = my_format_date($day, $month, $year);
        
        list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
        $expirydate = my_format_date($day, $month, $year);
        
        if ($myrow["CURTYPE"] == 'DOL') {
            $paytype = "US $";
            $smarty->assign("SHOWCONVERSION", "Y");
            $smarty->assign("CONVERSIONVALUE", ($myrow["AMOUNT"]));
            $smarty->assign("DOL_CONV_RATE", $DOL_CONV_RATE);
            $Amount = $Amount / $DOL_CONV_RATE;
        } 
        else {
            $paytype = "Rs.";
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
        
        if (isset($_COOKIE['JSLOGIN'])) {
            $checksum = $_COOKIE['JSLOGIN'];
            list($val, $id) = explode("i", $checksum);
            $sql = "UPDATE newjs.CONNECT SET SUBSCRIPTION='" . $myrow['SERVEFOR'] . "' WHERE ID='$id'";
            mysql_query_decide($sql);
        }
        
        $service_main_details = getServiceDetails($myrow["SERVICEMAIN"]);
        $smarty->assign("PERIOD", $service_main_details["DURATION"]);
        $smarty->assign("AMOUNT", $Amount);
        $smarty->assign("ORDERID", $Order_Id);
        $smarty->assign("PROFILEID", $profileid);
        $smarty->assign("ORDERDATE", $orderdate);
        $smarty->assign("ORDERDATEMOB", $order_date_mobile);
        $smarty->assign("EXPIRYDATE", $expirydate);
        $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
        $smarty->assign("PAYTYPE", $paytype);
        
        $smarty->assign("CHECKSUM", $Merchant_Param);
        $data = authenticated();
        $smarty->assign("USERNAME", $data[USERNAME]);
        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
        $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
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
else if ($isValidChecksum && $AuthDesc == "N") {
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
    $gatewayRespObj->updateDupRetStatus($profileid, $order_num, var_export($dup, 1), var_export($ret, 1));
    list($part1, $part2) = explode("-", $Order_Id);
    $ordrDeviceObj = new billing_ORDERS_DEVICE();
    $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
    
    $smarty->assign("CHECKSUM", $Merchant_Param);
    $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
    $smarty->assign("ORDERID", $Order_Id);
    $smarty->assign("PROFILEID", $profileid);
    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
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
else {
    echo "<br>Security Error. Illegal access detected";
}
?>
