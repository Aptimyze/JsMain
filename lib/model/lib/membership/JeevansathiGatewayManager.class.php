<?php

class JeevansathiGatewayManager
{
    public static function setPayUParams($apiObj, $apiParams)
    {
        include_once JsConstants::$docRoot . "/commonFiles/connect_dd.inc";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";

        if ($apiParams->currency == "RS") {
            if (JsConstants::$whichMachine == 'test') {
                $apiObj->key        = gatewayConstants::$PayUTestRsMerchantId;
                $apiObj->salt       = gatewayConstants::$PayUTestRsSalt;
                $apiObj->gatewayURL = gatewayConstants::$PayUTestGatewayURL;
            } else {
                $apiObj->key        = gatewayConstants::$PayULiveRsMerchantId;
                $apiObj->salt       = gatewayConstants::$PayULiveRsSalt;
                $apiObj->gatewayURL = gatewayConstants::$PayULiveGatewayURL;
            }
        } elseif ($apiParams->currency == "DOL") {
            if (JsConstants::$whichMachine == 'test') {
                $apiObj->key        = gatewayConstants::$PayUTestDolMerchantId;
                $apiObj->salt       = gatewayConstants::$PayUTestDolSalt;
                $apiObj->gatewayURL = gatewayConstants::$PayULiveGatewayURL;
            } else {
                $apiObj->key        = gatewayConstants::$PayULiveDolMerchantId;
                $apiObj->salt       = gatewayConstants::$PayULiveDolSalt;
                $apiObj->gatewayURL = gatewayConstants::$PayULiveGatewayURL;
            }
        }

        $memObj        = new Membership;
        $memHandlerObj = new MembershipHandler();
        $memObj->setProfileid($apiParams->profileid);
        $apiParams->track_memberships = trim($apiParams->track_memberships, ",");
        $payment                      = $memObj->forOnline($apiParams->track_memberships, $apiParams->type, $apiParams->service, $apiParams->discSel, $apiParams->paymode, $apiParams->device, $apiParams->couponCode);
        $total                        = $payment['total'];
        $service_main                 = $payment['service_str'];
        $discount                     = $payment['discount'];
        $discount_type                = $payment['discount_type'];
        $ORDER                        = newOrder($apiParams->profileid, $apiParams->paymode, $apiParams->type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYU', $discount_type, $apiParams->device, $apiParams->couponCode);
        if ($service_main != $apiParams->track_memberships && JsConstants::$whichMachine == 'prod') {
            $msg = "Mismatch in services sent to forOnline '{$apiParams->track_memberships}' vs newOrder '{$service_main}'<br>Profileid : '{$apiParams->profileid}', Gateway : PAYU, Device : '{$apiParams->device}'<br>OrderID : {$ORDER['ORDERID']}";
            SendMail::send_email('avneet.bindra@jeevansathi.com', $msg, 'Mismatch in Order Generation', $from = "js-sums@jeevansathi.com", $cc = "vibhor.garg@jeevansathi.com,vidushi@naukri.com");
        }
        $nameOfUserObj  = new incentive_NAME_OF_USER();
        $userName       = $nameOfUserObj->getName($apiParams->profileid);
        $apiObj->txnid  = $ORDER["ORDERID"];
        $apiObj->amount = (float) round($ORDER["AMOUNT"], 2);

        $apiObj->productinfo = "UsrID: {$apiParams->profileid}, Mem: {$apiParams->track_memberships}, Amt: {$apiObj->amount}, Discnt: {$discount}, DisntTyp: {$discount_type}";
        $userData            = $memHandlerObj->getUserData($apiParams->profileid);
        if ($userName) {
            $apiObj->firstname = $userName;
        } else {
            $apiObj->firstname = "";
        }
        $apiObj->email    = $ORDER["EMAIL"];
        $apiObj->phone    = $userData['PHONE_MOB'];
        $apiObj->lastname = "";
        $apiObj->address1 = $ORDER["CONTACT"];
        $city_country     = explode(",", $ORDER["COUNTRY"]);
        $city_order       = $city_country[0];
        $country_order    = $city_country[1];
        $apiObj->city     = $city_order;
        $apiObj->state    = $ORDER["STATE"];
        $apiObj->country  = $country_order;
        $apiObj->zipcode  = $ORDER["PINCODE"];
        $apiObj->surl     = JsConstants::$siteUrl . "/profile/pg/payU_return.php";
        $apiObj->furl     = JsConstants::$siteUrl . "/profile/pg/payU_return.php";
        $apiObj->curl     = JsConstants::$siteUrl . "/profile/pg/payU_return.php";
        $checksumSplit    = str_split($apiParams->checksum, 20);
        $apiObj->udf1     = $checksumSplit[0];
        $apiObj->udf2     = $checksumSplit[1];
        $apiObj->udf3     = $checksumSplit[2];
        $apiObj->udf4     = $checksumSplit[3];
        $apiObj->udf5     = $apiParams->currency;
        $apiObj->device   = $apiParams->device;

        $hashText     = "{$apiObj->key}|{$apiObj->txnid}|{$apiObj->amount}|{$apiObj->productinfo}|{$apiObj->firstname}|{$apiObj->email}|{$checksumSplit[0]}|{$checksumSplit[1]}|{$checksumSplit[2]}|{$checksumSplit[3]}|{$apiParams->currency}||||||{$apiObj->salt}";
        $apiObj->hash = hash("sha512", $hashText);
        $paymentArray = paymentOption::$paymentMode;
        if ($apiParams->paymentMode == "CR") {
            $apiObj->drop_category = "EMI,COD,DC,NB";
            $apiObj->pg            = 'CC';
        } else if ($apiParams->paymentMode == "DR") {
            $apiObj->drop_category = "EMI,COD,CC,NB";
            $apiObj->pg            = 'DC';
        } else if ($apiParams->paymentMode == "NB") {
            $apiObj->drop_category = "EMI,COD,CC,DC";
            $apiObj->pg            = 'NB';
        } else {
            $apiObj->drop_category = "EMI,COD";
        }

        $apiObj->custom_note    = "Jeevansathi Matrimony Payments";
        $apiObj->pageRedirectTo = $apiParams->pageRedirectTo;
        $apiObj->setTemplate("paymentRedirect");
    }

    public static function setCCAVENUEParams($apiObj, $apiParams)
    {
        include_once JsConstants::$docRoot . "/commonFiles/connect_dd.inc";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";

        if ($apiParams->currency == "DOL" && $apiParams->card_option != 'netBanking') {
            if (JsConstants::$whichMachine == 'test') {
                $apiObj->mid        = gatewayConstants::$CCAvenueTestDolMerchantId;
                $apiObj->key        = gatewayConstants::$CCAvenueTestDolSalt;
                $apiObj->returnURL  = JsConstants::$siteUrl . "/profile/pg/orderonlineOK_dol.php";
                $apiObj->gatewayURL = gatewayConstants::$CCAvenueTestDolURL;
                $apiObj->accessCode = gatewayConstants::$CCAvenueTestDolAccessCode;
            } else {
                $apiObj->mid        = gatewayConstants::$CCAvenueLiveDolMerchantId;
                $apiObj->key        = gatewayConstants::$CCAvenueLiveDolSalt;
                $apiObj->returnURL  = JsConstants::$siteUrl . "/profile/pg/orderonlineOK_dol.php";
                $apiObj->gatewayURL = gatewayConstants::$CCAvenueLiveDolURL;
                $apiObj->accessCode = gatewayConstants::$CCAvenueLiveDolAccessCode;
            }
        } else if ($apiParams->currency == "RS") {
            if (JsConstants::$whichMachine == 'test') {
                $apiObj->mid        = gatewayConstants::$CCAvenueTestRsMerchantId;
                $apiObj->key        = gatewayConstants::$CCAvenueTestRsSalt;
                $apiObj->returnURL  = JsConstants::$siteUrl . "/profile/pg/orderonlineOK.php";
                $apiObj->gatewayURL = gatewayConstants::$CCAvenueTestRsURL;
            } else {
                $apiObj->mid        = gatewayConstants::$CCAvenueLiveRsMerchantId;
                $apiObj->key        = gatewayConstants::$CCAvenueLiveRsSalt;
                $apiObj->returnURL  = JsConstants::$siteUrl . "/profile/pg/orderonlineOK.php";
                $apiObj->gatewayURL = gatewayConstants::$CCAvenueLiveRsURL;
            }
        }

        if (!$apiParams->paymode) {
            $apiObj->paymode = 'ncard';
        } else {
            $apiObj->paymode = $apiParams->paymode;
        }

        if ($apiParams->paymentMode == 'NB' || $apiParams->paymentMode == 'CSH') {
            $apiObj->gatewayURL = gatewayConstants::$CCAvenueSeamlessRedirectURL;
        }

        $memObj        = new Membership;
        $memHandlerObj = new MembershipHandler();
        $memObj->setProfileid($apiParams->profileid);
        $payment       = $memObj->forOnline($apiParams->track_memberships, $apiParams->type, $apiParams->service, $apiParams->discSel, $apiObj->paymode, $apiParams->device, $apiParams->couponCode);
        $total         = $payment['total'];
        $service_main  = $payment['service_str'];
        $discount      = $payment['discount'];
        $discount_type = $payment['discount_type'];
        $ORDER         = newOrder($apiParams->profileid, $apiParams->paymode, $apiParams->type, $total, $service_str, $service_main, $discount, $setactivate, 'CCAVENUE', $discount_type, $apiParams->device, $apiParams->couponCode);
        if ($service_main != $apiParams->track_memberships && JsConstants::$whichMachine == 'prod') {
            $msg = "Mismatch in services sent to forOnline '{$apiParams->track_memberships}' vs newOrder '{$service_main}'<br>Profileid : '{$apiParams->profileid}', Gateway : CCAVENUE, Device : '{$apiParams->device}'<br>OrderID : {$ORDER['ORDERID']}";
            SendMail::send_email('avneet.bindra@jeevansathi.com', $msg, 'Mismatch in Order Generation', $from = "js-sums@jeevansathi.com", $cc = "vibhor.garg@jeevansathi.com,vidushi@naukri.com");
        }
        $nameOfUserObj = new incentive_NAME_OF_USER();
        $userName      = $nameOfUserObj->getName($apiParams->profileid);
        if ($apiParams->currency == 'DOL') {
            $merchantDataArr = array('currency' => 'USD', 'merchant_id' => $apiObj->mid, 'amount' => $ORDER["AMOUNT"], 'order_id' => $ORDER["ORDERID"], 'merchant_param5' => $apiParams->checksum, 'redirect_url' => $apiObj->returnURL, 'cancel_url' => $apiObj->returnURL, 'language' => 'EN');
            foreach ($merchantDataArr as $key => $val) {
                $merchantDataString .= $key . '=' . $val . '&';
            }
            $apiObj->encRequest = CCAvenueDolManager::encrypt($merchantDataString, $apiObj->key);
        } else {
            $apiObj->ccavenueChecksum = CCAvenueRsManager::getCheckSum($apiObj->mid, $ORDER["AMOUNT"], $ORDER["ORDERID"], $apiObj->returnURL, $apiObj->key);
        }

        $apiObj->txnid  = $ORDER["ORDERID"];
        $apiObj->amount = $ORDER["AMOUNT"];
        $userData       = $memHandlerObj->getUserData($apiParams->profileid);
        if ($userName) {
            $apiObj->firstname = $userName;
        } else {
            $apiObj->firstname = "";
        }
        if ($apiParams->currency == "DOL") {
            $apiObj->address       = "";
            $apiObj->city_country  = "";
            $apiObj->city_order    = "";
            $apiObj->country_order = "";
            $apiObj->BILL_STATE    = "";
            $apiObj->BILL_PINCODE  = "";
            $apiObj->BILL_PHONE    = "";
            $apiObj->BILL_EMAIL    = "";
        } else {
            $apiObj->address       = str_replace("-", " ", $ORDER["CONTACT"]);
            $apiObj->city_country  = explode(",", $ORDER["COUNTRY"]);
            $apiObj->city_order    = $apiObj->city_country[0];
            $apiObj->country_order = $apiObj->city_country[1];
            $apiObj->BILL_STATE    = $ORDER["STATE"];
            $apiObj->BILL_PINCODE  = $ORDER["PINCODE"];
            $apiObj->BILL_PHONE    = $ORDER["PHONE"];
            $apiObj->BILL_EMAIL    = $ORDER["EMAIL"];
        }
        $apiObj->pageRedirectTo    = $apiParams->pageRedirectTo;
        $apiObj->device            = $apiParams->device;
        $apiObj->currency          = $apiParams->currency;
        $apiObj->checksum          = $apiParams->checksum;
        $apiObj->paymentMode       = $apiParams->paymentMode;
        $apiObj->card_option       = $apiParams->card_option;
        $apiObj->net_banking_cards = $apiParams->net_banking_cards;
        $apiObj->CCRDType          = $apiParams->CCRDType;
        $apiObj->setTemplate("paymentRedirect");

    }

    public static function setPayTMParams($apiObj, $apiParams)
    {
        include_once JsConstants::$docRoot . "/commonFiles/connect_dd.inc";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";

        if (JsConstants::$whichMachine == 'test') {
            $apiObj->mid          = gatewayConstants::$PayTmTestRsMerchantId;
            $apiObj->key          = gatewayConstants::$PayTmTestRsSalt;
            $apiObj->returnURL    = JsConstants::$siteUrl . "/profile/pg/paytm_return.php";
            $apiObj->gatewayURL   = gatewayConstants::$PayTmTestURL;
            $apiObj->industryType = gatewayConstants::$PayTmTestIndustryType;
            $apiObj->channelId    = gatewayConstants::$PayTmTestChannelId;
            $apiObj->website      = gatewayConstants::$PayTmTestWebsite;
        } else {
            $apiObj->mid          = gatewayConstants::$PayTmLiveRsMerchantId;
            $apiObj->key          = gatewayConstants::$PayTmLiveRsSalt;
            $apiObj->returnURL    = JsConstants::$siteUrl . "/profile/pg/paytm_return.php";
            $apiObj->gatewayURL   = gatewayConstants::$PayTmLiveURL;
            $apiObj->industryType = gatewayConstants::$PayTmLiveIndustryType;
            $apiObj->channelId    = gatewayConstants::$PayTmLiveChannelId;
            $apiObj->website      = gatewayConstants::$PayTmLiveWebsite;
        }

        $memObj = new Membership;
        $memObj->setProfileid($apiParams->profileid);
        $payment       = $memObj->forOnline($apiParams->track_memberships, $apiParams->type, $apiParams->service, $apiParams->discSel, $apiParams->paymode, $apiParams->device, $apiParams->couponCode);
        $total         = $payment['total'];
        $service_main  = $payment['service_str'];
        $discount      = $payment['discount'];
        $discount_type = $payment['discount_type'];
        $ORDER         = newOrder($apiParams->profileid, $apiParams->paymode, $apiParams->type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYTM', $discount_type, $apiParams->device, $apiParams->couponCode);
        if ($service_main != $apiParams->track_memberships && JsConstants::$whichMachine == 'prod') {
            $msg = "Mismatch in services sent to forOnline '{$apiParams->track_memberships}' vs newOrder '{$service_main}'<br>Profileid : '{$apiParams->profileid}', Gateway : PAYTM, Device : '{$apiParams->device}'<br>OrderID : {$ORDER['ORDERID']}";
            SendMail::send_email('avneet.bindra@jeevansathi.com', $msg, 'Mismatch in Order Generation', $from = "js-sums@jeevansathi.com", $cc = "vibhor.garg@jeevansathi.com,vidushi@naukri.com");
        }
        $apiObj->txnid            = $ORDER["ORDERID"];
        $apiObj->INDUSTRY_TYPE_ID = $apiObj->industryType;
        $apiObj->CHANNEL_ID       = $apiObj->channelId;
        $apiObj->TXN_AMOUNT       = $ORDER["AMOUNT"];
        $apiObj->WEBSITE          = $apiObj->website;
        $apiObj->MOBILE_NO        = $ORDER["PHONE"];
        $apiObj->MERC_UNQ_REF     = $apiParams->checksum;
        $apiObj->EMAIL            = $ORDER["EMAIL"];
        $apiObj->CALLBACK_URL     = $apiObj->returnURL;

        $paramList                     = array();
        $paramList["MID"]              = $apiObj->mid;
        $paramList["ORDER_ID"]         = $ORDER["ORDERID"];
        $paramList["CUST_ID"]          = $apiParams->profileid;
        $paramList["INDUSTRY_TYPE_ID"] = $apiObj->industryType;
        $paramList["CHANNEL_ID"]       = $apiObj->channelId;
        $paramList["TXN_AMOUNT"]       = $ORDER["AMOUNT"];
        $paramList["WEBSITE"]          = $apiObj->website;
        $paramList["MOBILE_NO"]        = $ORDER["PHONE"];
        $paramList["MERC_UNQ_REF"]     = $apiParams->checksum;
        $paramList["CALLBACK_URL"]     = $apiObj->returnURL;
        $paramList["EMAIL"]            = $ORDER["EMAIL"];

        $apiObj->CHECKSUMHASH   = PayTmManager::getChecksumFromArray($paramList, $apiObj->key);
        $apiObj->pageRedirectTo = $apiParams->pageRedirectTo;
        $apiObj->device         = $apiParams->device;
        $apiObj->profileid      = $apiParams->profileid;
        $apiObj->setTemplate("paymentRedirect");
    }

    public static function setPaypalParams($apiObj, $apiParams)
    {
        include_once JsConstants::$docRoot . "/commonFiles/connect_dd.inc";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php";
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";

        if (JsConstants::$whichMachine == 'test') {
            $apiObj->mid        = gatewayConstants::$PaypalTestMerchantId;
            $apiObj->gatewayURL = gatewayConstants::$PaypalGatewayTestURL;
        } else {
            $apiObj->mid        = gatewayConstants::$PaypalLiveMerchantId;
            $apiObj->gatewayURL = gatewayConstants::$PaypalGatewayLiveURL;
        }

        $apiObj->returnURL    = JsConstants::$siteUrl . gatewayConstants::$PaypalReturnURL;
        $apiObj->cancelURL    = JsConstants::$siteUrl . gatewayConstants::$PaypalCancelReturnURL;
        $apiObj->noShipping   = gatewayConstants::$PaypalNoShipping;
        $apiObj->noNote       = gatewayConstants::$PaypalNoNote;
        $apiObj->cmd          = gatewayConstants::$PaypalCmd;
        $apiObj->returnMethod = gatewayConstants::$PaypalReturnMethod;

        $memObj = new Membership;
        $memObj->setProfileid($apiParams->profileid);
        $payment       = $memObj->forOnline($apiParams->track_memberships, $apiParams->type, $apiParams->service, $apiParams->discSel, $apiParams->paymode, $apiParams->device, $apiParams->couponCode);
        $total         = $payment['total'];
        $service_main  = $payment['service_str'];
        $discount      = $payment['discount'];
        $discount_type = $payment['discount_type'];
        $ORDER         = newOrder($apiParams->profileid, $apiParams->paymode, $apiParams->type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYPAL', $discount_type, $apiParams->device, $apiParams->couponCode);
        if ($service_main != $apiParams->track_memberships && JsConstants::$whichMachine == 'prod') {
            $msg = "Mismatch in services sent to forOnline '{$apiParams->track_memberships}' vs newOrder '{$service_main}'<br>Profileid : '{$apiParams->profileid}', Gateway : PAYPAL, Device : '{$apiParams->device}'<br>OrderID : {$ORDER['ORDERID']}";
            SendMail::send_email('avneet.bindra@jeevansathi.com', $msg, 'Mismatch in Order Generation', $from = "js-sums@jeevansathi.com", $cc = "vibhor.garg@jeevansathi.com,vidushi@naukri.com");
        }
        $apiObj->PAYPALAMOUNT  = $ORDER["AMOUNT"];
        $apiObj->PAYPALORDERID = $ORDER["ORDERID"];
        $billServObj           = new billing_SERVICES();
        $servArr               = explode(',', $apiParams->track_memberships);
        foreach ($servArr as $key => $val) {
            $names[] = $billServObj->getServiceName($val);
        }
        $apiObj->PAYPALSERVICE  = implode(",", $names);
        $apiObj->PAYPALCHECKSUM = $apiParams->checksum;
        $apiObj->pageRedirectTo = $apiParams->pageRedirectTo;
        $apiObj->setTemplate("paymentRedirect");
    }

    public static function reAuthenticateUser($apiObj)
    {
        $authenticationLoginObj = AuthenticationFactory::getAuthenicationObj(null);
        $data                   = $authenticationLoginObj->setPaymentGatewayAuthchecksum($apiObj->apiParams->profileid);
        // $jprofileObj    = new JPROFILE();
        // $fields         = "PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,EMAIL";
        // $valueArray     = array("PROFILEID" => $apiObj->apiParams->profileid, "activatedKey" => 1);
        // $profileDetails = $jprofileObj->getArray($valueArray, '', '', $fields, '', '', '', '', '', '', '', '');
        // unset($jprofileObj);
        // $protectObj = new protect();
        // $protectObj->logout();
        // $protectObj->postLogin($profileDetails[0]);
        // unset($protectObj);
    }
}
