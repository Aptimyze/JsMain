<?php
include_once ("../connect.inc");
include_once ("functions.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
connect_db();

function get_currency($from_Currency, $to_Currency, $amount) {
    $amount = urlencode($amount);
    $from_Currency = urlencode($from_Currency);
    $to_Currency = urlencode($to_Currency);
    $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
    $ch = curl_init();
    $timeout = 0;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $rawdata = curl_exec($ch);
    curl_close($ch);
    $data = explode('bld>', $rawdata);
    $data = explode($to_Currency, $data[1]);
    return round($data[0], 2);
}

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
        if (!$paymode) $paymode = "ncard";
        
        if (strstr($paymode, "card")) {
            
            if ($type == "DOL" && !$cardOption == 'netBanking') {
                require_once ("libfuncs_dol.php");
                
                $Redirect_Url = JsConstants::$siteUrl . "/profile/pg/orderonlineOK_dol.php";
                
                if (JsConstants::$whichMachine == 'test') {
                    $Merchant_Id = gatewayConstants::$CCAvenueTestDolMerchantId;
                    $WorkingKey = gatewayConstants::$CCAvenueTestDolSalt;
                } 
                else {
                    $Merchant_Id = gatewayConstants::$CCAvenueLiveDolMerchantId;
                    $WorkingKey = gatewayConstants::$CCAvenueLiveDolSalt;
                }
                $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'CCAVENUE', $discount_type, $device, $couponCodeVal);
                $Checksum = getCheckSum($Merchant_Id, $ORDER["ORDERID"], ($ORDER["AMOUNT"]), $WorkingKey, "USD", $Redirect_Url);
            } 
            else {
                require_once ("libfuncs.php");
                
                $Redirect_Url = JsConstants::$siteUrl . "/profile/pg/orderonlineOK.php";
                
                if (JsConstants::$whichMachine == 'test') {
                    $Merchant_Id = gatewayConstants::$CCAvenueTestRsMerchantId;
                    $WorkingKey = gatewayConstants::$CCAvenueTestRsSalt;
                } 
                else {
                    $Merchant_Id = gatewayConstants::$CCAvenueLiveRsMerchantId;
                    $WorkingKey = gatewayConstants::$CCAvenueLiveRsSalt;
                }
                $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'CCAVENUE', $discount_type, $device, $couponCodeVal);
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
            
            $smarty->assign("MERCHANTID", $Merchant_Id);
            $smarty->assign("REDIRECTURL", $Redirect_Url);
            $smarty->assign("ACTIVE", $ORDER["ACTIVE"]);
            $smarty->assign("AMOUNT", ($ORDER["AMOUNT"]));
            $smarty->assign("ORDERID", $ORDER["ORDERID"]);
            $smarty->assign("CHECKSUM", $Checksum);
            $nameOfUserObj = new incentive_NAME_OF_USER();
            $userName = $nameOfUserObj->getName($profileid);
            if (!empty($userName)) {
                $smarty->assign("BILL_NAME", trim($userName));
            } 
            else {
                $smarty->assign("BILL_NAME", "");
            }
            $smarty->assign("BILL_ADD", str_replace("-", " ", $ORDER["CONTACT"]));
            $city_country = explode(",", $ORDER["COUNTRY"]);
            $city_order = $city_country[0];
            $country_order = $city_country[1];
            $smarty->assign("BILL_CITY", $city_order);
            $smarty->assign("BILL_COUNTRY", $country_order);
            $smarty->assign("BILL_STATE", $ORDER["STATE"]);
            $smarty->assign("BILL_PINCODE", $ORDER["PINCODE"]);
            $smarty->assign("BILL_PHONE", $ORDER["PHONE"]);
            $smarty->assign("BILL_EMAIL", $ORDER["EMAIL"]);
            $smarty->assign("DLVR_NAME", "");
            $smarty->assign("DLVR_ADD", "");
            $smarty->assign("DLVR_PHONE", "");
            $smarty->assign("DLVR_NOTES", "");
            $smarty->assign("MERCHANT_PARAM", $checksum);
            $smarty->assign("cardOption", $cardOption);
            $smarty->assign("netBankingCards", $netBankingCards);
            $smarty->assign("CCRDType", $CCRDType);
            
            if ($cardOption && ($netBankingCards || $CCRDType)) {
                $smarty->display("pg/redirect_seamless.htm");
            } 
            elseif ($type == "DOL") {
                $smarty->display("pg/redirect_dol.htm");
            } 
            else {
                $smarty->display("pg/redirect.htm");
            }
        } 
        elseif (strstr($paymode, "cheque")) {
            $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, "", $discount_type, $device, $couponCodeVal);
            
            if (!$ORDER) {
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
            $nameOfUserObj = new incentive_NAME_OF_USER();
            $userName = $nameOfUserObj->getName($profileid);
            if (!empty($userName)) {
                $smarty->assign("BILL_NAME", trim($userName));
            } 
            else {
                $smarty->assign("BILL_NAME", "");
            }
            $smarty->assign("BILL_ADD", str_replace("-", " ", $ORDER["CONTACT"]));
            $city_country = explode(",", $ORDER["COUNTRY"]);
            $city_order = $city_country[0];
            $country_order = $city_country[1];
            $smarty->assign("BILL_CITY", $city_order);
            $smarty->assign("BILL_COUNTRY", $country_order);
            $smarty->assign("BILL_STATE", $ORDER["STATE"]);
            $smarty->assign("BILL_PINCODE", $ORDER["PINCODE"]);
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
?>
