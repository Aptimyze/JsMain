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
    
    if ($BcKlinK && (($DISCOUNT * 23) + 57) == $chk_ids) {
        $total = $total - $DISCOUNT;
        $discount = $DISCOUNT;
        $discount_type = $DISCOUNT_TYPE;
    }
    $checkout = 1;
    if ($checkout) {
        $notype = 0;
        if ($type == 'RS') {
            if (JsConstants::$whichMachine == 'test') {
                $Merchant_Id = gatewayConstants::$PaysealLiveRsMerchantId;
            } 
            else {
                $Merchant_Id = gatewayConstants::$PaysealLiveRsMerchantId;
            }
            
            $currency = "INR";
            
            $return_url = JsConstants::$siteUrl . "/jspellhtml2k4/SFAResponse.jsp";
        } 
        elseif ($type == 'DOL') {
            if (JsConstants::$whichMachine == 'test') {
                $Merchant_Id = gatewayConstants::$PaysealLiveDolMerchantId;
            } 
            else {
                $Merchant_Id = gatewayConstants::$PaysealLiveDolMerchantId;
            }
            
            $currency = "USD";
            
            $return_url = JsConstants::$siteUrl . "/jspellhtml2k4/SFAResponse_dol.jsp";
        } 
        else {
            $notype = 1;
        }
        $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYSEAL', $discount_type, $device, $couponCodeVal);
        
        if (!$ORDER || $notype == 1) {
            $smarty->assign("CHECKSUM", $checksum);
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
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
            die;
        }
        
        $service = $ORDER["SERVICE_MAIN"] . "," . $ORDER["ADDON_SERVICE"];
        $service = getServiceName($service);
        
        $smarty->assign("MERCHANTID", encrypted_key($Merchant_Id));
        $smarty->assign("ACTIVE", $ORDER["ACTIVE"]);
        if ($type == "RS") $smarty->assign("AMOUNT", ($ORDER["AMOUNT"]));
        else $smarty->assign("AMOUNT", $ORDER["AMOUNT"]);
        $smarty->assign("ORDERID", encrypted_key($ORDER["ORDERID"]));
        $smarty->assign("SERVICE", $service);
        $smarty->assign("CURRENCY", $currency);
        
        $smarty->assign("RETURN_METHOD", "POST");
        $smarty->assign("IMAGE_URL", "http://www.jeevansathi.com/profile/imagesnew/Matrimonial.gif");
        $smarty->assign("RETURN", $return_url);
        if ($type == 'DOL') {
            $display_amt = $currency . $ORDER["AMOUNT"];
            $purchase_amt = $ORDER["AMOUNT"] * 100;
            $num_currency_code = 840;
        }
        $smarty->assign("NUM_CURRENCY_CODE", encrypted_key($num_currency_code));
        $smarty->assign("PURCHASE_AMT", encrypted_key($purchase_amt));
        $smarty->assign("DISPLAY_AMT", encrypted_key($display_amt));
        if ($paymode == 'card9') $smarty->assign("pm_mod", 'db_card');
        $smarty->display("pg/payseal_redirect.htm");        
    } 
    else {
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
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
} 
else {
    TimedOut();
}

function encrypted_key($str) {
    $str = (string)$str;
    $var = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        if ($i % 2 == 0) $str{$i} = ((ord($str{$i}) + 17) > 127) ? chr(ord($str{$i}) + 17 - 127) : chr(ord($str{$i}) + 17);
        else $str{$i} = ((ord($str{$i}) + 7) > 127) ? chr(ord($str{$i}) + 7 - 127) : chr(ord($str{$i}) + 7);
        if ($str{$i} == '"') $var = 1;
    }
    if ($var == 1) $str = encrypted_key($str);
    return $str;
}
?>
