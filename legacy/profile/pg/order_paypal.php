<?php
include_once ("../connect.inc");
include_once ("functions.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");

connect_db();

$ip = FetchClientIP();
if (strstr($ip, ",")) {
    $ip_new = explode(",", $ip);
    $ip = $ip_new[1];
}
if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
    $memObj = new Membership;
    $memObj->setProfileid($profileid);
    if ($cardOption == 'netBanking') $cardOptionSel = 'netBanking';
    else $cardOptionSel = $paymode;
    $payment = $memObj->forOnline($service_main, $type, $service, $discSel, $cardOptionSel, $device, $couponCodeVal);
    $total = $payment['total'];
    $service_main = $payment['service_str'];
    $discount = $payment['discount'];
    $discount_type = $payment['discount_type'];
    if ($checkout = 1) {
        if (strstr($paymode, "card")) {
            $Merchant_Id = gatewayConstants::$PaypalLiveMerchantId;
            $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYPAL', $discount_type, $device, $couponCodeVal);
            if (!$ORDER) {
                $smarty->assign("CHECKSUM", $checksum);
                $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
                $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
                $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
                $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
                
                $smarty->display("pg/ordererror.htm");
                die;
            }
            
            $service = $ORDER["SERVICE_MAIN"] . "," . $ORDER["ADDON_SERVICE"];
            $service = getServiceName($service);
            
            $smarty->assign("MERCHANTID", $Merchant_Id);
            $smarty->assign("ACTIVE", $ORDER["ACTIVE"]);
            $smarty->assign("AMOUNT", ($ORDER["AMOUNT"]));
            $smarty->assign("ORDERID", $ORDER["ORDERID"]);
            $smarty->assign("SERVICE", $service);
            $smarty->assign("CHECKSUM", $Checksum);
            $smarty->assign("CURRENCY", "USD");
            $smarty->assign("NO_SHIPPING", "1");
            $smarty->assign("NO_NOTE", "1");
            $smarty->assign("PAYPAL_CMD", "_xclick");
            $smarty->assign("RETURN_METHOD", "POST");
            $smarty->assign("IMAGE_URL", "$SITE_URL/profile/imagesnew/Matrimonial.gif");
            $smarty->assign("RETURN", "$SITE_URL/profile/pg/orderOK_paypal.php");
            $smarty->assign("CANCEL_RETURN", "$SITE_URL/profile/pg/orderCancel_paypal.php");
            
            $smarty->display("pg/paypal_redirect.htm");
        } 
        elseif (strstr($paymode, "cheque")) {
            $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, "", $discount_type, $device, $couponCodeVal);
            
            if (!$ORDER) {
                $smarty->assign("CHECKSUM", $checksum);
                $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
                $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
                $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
                $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
                
                $smarty->display("pg/ordererror.htm");
                die;
            }
            
            $orderdate = date("Y-m-d", time());
            list($year, $month, $day) = explode("-", $orderdate);
            $orderdate = my_format_date($day, $month, $year);
            
            if ($type == "DOL") {
                $paytype = "US $";
                $smarty->assign("AMOUNT", $ORDER["AMOUNT"] / $DOL_CONV_RATE);
            } 
            else {
                $paytype = "RS.";
                $smarty->assign("AMOUNT", $ORDER["AMOUNT"]);
            }
            $service_main_details = getServiceDetails($service_main);
            $smarty->assign("PERIOD", $service_main_details["DURATION"]);
            
            $smarty->assign("ORDERID", $ORDER["ORDERID"]);
            $smarty->assign("ORDERDATE", $orderdate);
            $smarty->assign("BILL_NAME", $ORDER["USERNAME"]);
            $smarty->assign("BILL_ADD", $ORDER["CONTACT"]);
            $smarty->assign("BILL_COUNTRY", $ORDER["COUNTRY"]);
            $smarty->assign("BILL_PHONE", $ORDER["PHONE"]);
            $smarty->assign("BILL_EMAIL", $ORDER["EMAIL"]);
            $smarty->assign("PAYTYPE", $paytype);
            
            $smarty->assign("CHECKSUM", $checksum);
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            
            $smarty->display("pg/orderreceipt_cheque.htm");
        }
    } 
    else {
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        $smarty->display("pg/ordererror.htm");        
    }
} 
else {
    TimedOut();
}
?>
