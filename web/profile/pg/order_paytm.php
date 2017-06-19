<?php

include_once ("../connect.inc");
include_once ("functions.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
connect_db();

$ip = FetchClientIP();

//Gets ipaddress of user
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
        if (!$paymode) $paymode = "ncard";
        
        if (JsConstants::$whichMachine == 'test') {
            $Merchant_Id = gatewayConstants::$PayTmTestRsMerchantId;
            $WorkingKey = gatewayConstants::$PayTmTestRsSalt;
            $redirectURL = gatewayConstants::$PayTmTestURL;
            $industryType = gatewayConstants::$PayTmTestIndustryType;
            $channelId = gatewayConstants::$PayTmTestChannelId;
            $website = gatewayConstants::$PayTmTestWebsite;
        } 
        else {
            $Merchant_Id = gatewayConstants::$PayTmLiveRsMerchantId;
            $WorkingKey = gatewayConstants::$PayTmLiveRsSalt;
            $redirectURL = gatewayConstants::$PayTmLiveURL;
            $industryType = gatewayConstants::$PayTmLiveIndustryType;
            $channelId = gatewayConstants::$PayTmLiveChannelId;
            $website = gatewayConstants::$PayTmLiveWebsite;
        }
        
        $paramList = array();
        
        $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYTM', $discount_type, $device, $couponCodeVal);
        
        if (!$ORDER) {
            $smarty->assign("CHECKSUM", $checksum);
            $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            
            //$smarty->display("pg/ordererror.htm");
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
            die;
        }
        
        $paramList["MID"] = $Merchant_Id;
        $paramList["ORDER_ID"] = $ORDER["ORDERID"];
        $paramList["CUST_ID"] = $profileid;
        $paramList["INDUSTRY_TYPE_ID"] = $industryType;
        $paramList["CHANNEL_ID"] = $channelId;
        $paramList["TXN_AMOUNT"] = floor($ORDER["AMOUNT"]);
        $paramList["WEBSITE"] = $website;
        $paramList["MOBILE_NO"] = $ORDER["PHONE"];
        $paramList["MERC_UNQ_REF"] = $checksum;
        $paramList["CALLBACK_URL"] = JsConstants::$siteUrl."/profile/pg/paytm_return.php";
        $paramList["EMAIL"] = $ORDER["EMAIL"];
        
        $smarty->assign("MID", $Merchant_Id);
        $smarty->assign("ORDER_ID", $ORDER["ORDERID"]);
        $smarty->assign("CUST_ID", $profileid);
        $smarty->assign("INDUSTRY_TYPE_ID", $industryType);
        $smarty->assign("CHANNEL_ID", $channelId);
        $smarty->assign("TXN_AMOUNT", floor($ORDER["AMOUNT"]));
        $smarty->assign("WEBSITE", $website);
        $smarty->assign("MERC_UNQ_REF", $checksum);
        $smarty->assign("MOBILE_NO", $ORDER["PHONE"]);
        $smarty->assign("EMAIL", $ORDER["EMAIL"]);
        $smarty->assign("CALLBACK_URL", JsConstants::$siteUrl."/profile/pg/paytm_return.php");
        
        $paytmCheckSum = PayTmManager::getChecksumFromArray($paramList, $WorkingKey);
        $smarty->assign("CHECKSUMHASH", $paytmCheckSum);
        $smarty->assign("redirectURL", $redirectURL);

        //print_r(array($paramList, $paytmCheckSum));

        $smarty->display("pg/paytm_redirect.htm");
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

?>
