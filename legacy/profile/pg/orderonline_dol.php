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
    $disc = $memObj->isRenewable($profileid);
    if ($disc) {
        $disc = 'Y';
    } 
    else {
        $disc = 'N';
    }
    
    $serObj = new Services;
    $service_main = $serObj->getTrueService($service_main);
    $total = $serObj->getTotalPrice($service_main, $type);
    if (strstr($service, 'P')) {
        $main_service = $service;
    }
    if (strstr($service, 'C')) {
        $main_service = $service;
    }
    if ($main_service) {
        $serv_price = $serObj->getServicesAmount($main_service, $type);
        $serv_price1 = $serv_price[$main_service][PRICE];
        if ($disc == 'Y') {
            $serv_price1 = floor(($renew_discount_rate / 100) * $serv_price1);
            $discount = $serv_price1;
            $total = $total - $serv_price1;
        } 
        else if ($avail_discount == "Y") {
            $returned_val = check_voucher_discount_code($voucher_code, $profileid);
            if ($returned_val['CODE_EXISTS'] > 0 || "Y" == $rem) {
                $vdr = $returned_val['PERCENT'];
                if ($vdr) $voucher_discount_rate = $vdr;
                $subtotal2 = round((($voucher_discount_rate / 100) * $serv_price1), 2);
                if ($type == "DOL") $total1 = round($subtotal2);
                else $total1 = floor($subtotal2);
                $serv_price1 = $subtotal2;
                $discount = $serv_price1;
                $total = ceil($total - $serv_price1);
            }
        }
    }
    
    if ($checkout = 1) {
        if (strstr($paymode, "card")) {
            if ($type == "DOL") {
                require_once ("libfuncs_dol.php");
                $Merchant_Id = "jsdollar5615";
                $Redirect_Url = "http://www.jeevansathi.com/profile/pg/orderonlineOK_dol.php";
                $WorkingKey = "6cwghcvrmo2091w2uxnxeerde9xj7nle";
                $discount_type = $DISCOUNT_TYPE;
                $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'DOLLAR_CCAVENUE', $discount_type);
                $Checksum = getCheckSum($Merchant_Id, $ORDER["ORDERID"], ($ORDER["AMOUNT"]), $WorkingKey, 'USD', $Redirect_Url);
            } 
            else {
                require_once ("libfuncs.php");
                $Merchant_Id = "M_anyana_1395";
                $Redirect_Url = "http://www.jeevansathi.com/profile/pg/orderonlineOK.php";
                $WorkingKey = "a5qdxwe59g5af94qphru8hjubw1t9o6u";
                $discount_type = $DISCOUNT_TYPE;
                $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'CCAVENUE', $discount_type);
                $Checksum = getCheckSum($Merchant_Id, ($ORDER["AMOUNT"]), $ORDER["ORDERID"], $Redirect_Url, $WorkingKey);
            }
            if (!$ORDER) {
                $smarty->assign("CHECKSUM", $checksum);
                $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
                $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
                $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
                $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
                
                $smarty->display("pg/ordererror.htm");
                die;
            }
            
            $smarty->assign("MERCHANTID", $Merchant_Id);
            $smarty->assign("REDIRECTURL", $Redirect_Url);
            $smarty->assign("ACTIVE", $ORDER["ACTIVE"]);
            $smarty->assign("AMOUNT", ($ORDER["AMOUNT"]));
            $smarty->assign("ORDERID", $ORDER["ORDERID"]);
            $smarty->assign("CHECKSUM", $Checksum);
            $smarty->assign("BILL_NAME", $ORDER["USERNAME"]);
            $smarty->assign("BILL_ADD", $ORDER["CONTACT"]);
            $smarty->assign("BILL_COUNTRY", $ORDER["COUNTRY"]);
            $smarty->assign("BILL_PHONE", $ORDER["PHONE"]);
            $smarty->assign("BILL_EMAIL", $ORDER["EMAIL"]);
            $smarty->assign("DLVR_NAME", "");
            $smarty->assign("DLVR_ADD", "");
            $smarty->assign("DLVR_PHONE", "");
            $smarty->assign("DLVR_NOTES", "");
            $smarty->assign("MERCHANT_PARAM", $checksum);
            if ($type == "DOL") $smarty->display("pg/redirect_dol.htm");
            else $smarty->display("pg/redirect.htm");
        } 
        elseif (strstr($paymode, "cheque")) {
            $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, "", $discount_type);
            
            if (!$ORDER) {
                $smarty->assign("CHECKSUM", $checksum);
                $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
                $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
                $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
                $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
                $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
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
            $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            
            $smarty->display("pg/orderreceipt_cheque.htm");
        }
    } 
    else {
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        $smarty->display("pg/ordererror.htm");
    }
} 
else {
    TimedOut();
}
?>

