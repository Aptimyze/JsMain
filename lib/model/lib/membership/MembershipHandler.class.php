<?php
if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = sfConfig::get("sf_web_dir");
}

if (JsConstants::$whichMachine != 'matchAlert') {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/connect_functions.inc";
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/globalVariables.Class.php";

class MembershipHandler
{
    public $memObj;
    public $serviceObj;
    public $jprofileObj;

    public function __construct($setStoreObj = true)
    {
        $this->memObj      = new JMembership();
        $this->serviceObj  = new JServices();
        
        if($setStoreObj != false){
            $this->jprofileObj = new JPROFILE();
        }
    }

    public function getOnlineActiveMainMemDurationsWrapper($mtongue=""){
        if(empty($mtongue)){
            return 0;
        }
        else{
            $runSqlQuery = true;
            
            $memCacheObject = JsMemcache::getInstance();
            $noFilterMontongueList = $memCacheObject->get("NO_MAIN_MEM_FILTER_MTONGUE");
            if (!empty($noFilterMontongueList) && $noFilterMontongueList !== false) {
                if(strpos($noFilterMontongueList,",".$mtongue.",") !== false){
                    $count = 0;
                    $runSqlQuery = false;
                }
                else{
                    //$count = 1;
                    $runSqlQuery = true;
                }
            }
            else{
                //$noFilterMontongueList = "";
                $runSqlQuery = true;
            }
            if($runSqlQuery == true){
                $serviceObj = new billing_SERVICES("newjs_masterRep");
                $activeOnlineServices = $serviceObj->getOnlineActiveDurations($mtongue);
                unset($serviceObj);
                if(is_array($activeOnlineServices)){
                    $count = count($activeOnlineServices);
                }
                else{
                    $count = 0;
                }
                if($count == 0){
                    if(empty($noFilterMontongueList)){
                        $noFilterMontongueList = ",";
                    }
                    $noFilterMontongueList .= $mtongue.",";
                    $memCacheObject->set("NO_MAIN_MEM_FILTER_MTONGUE",$noFilterMontongueList,3600);
                }
                else{
                    if(empty($noFilterMontongueList)){
                        $noFilterMontongueList = "";
                    }
                    $memCacheObject->set("NO_MAIN_MEM_FILTER_MTONGUE",$noFilterMontongueList,3600);
                }
            }

            return $count;
        }
    }

     public function getOnlineActiveAddonDurationsWrapper($mtongue=""){
        if(empty($mtongue)){
            return 0;
        }
        else{
            $runSqlQuery = true;
            $memCacheObject = JsMemcache::getInstance();
            $noFilterMontongueList = $memCacheObject->get("NO_ADDON_MEM_FILTER_MTONGUE");
            if (!empty($noFilterMontongueList) && $noFilterMontongueList !== false) {
                if(strpos($noFilterMontongueList,",".$mtongue.",") !== false){
                    $count = 0;
                    $runSqlQuery = false;
                }
                else{
                    //$count = 1;
                    $runSqlQuery = true;
                }
            }
            else{
                //$noFilterMontongueList = ",";
                $runSqlQuery = true;
            }
            
            if($runSqlQuery == true){
                $serviceObj = new billing_SERVICES("newjs_masterRep");
                $activeOnlineServices = $serviceObj->getOnlineActiveDurations($mtongue,"Y");

                unset($serviceObj);
                if(is_array($activeOnlineServices)){
                    $count = count($activeOnlineServices);
                }
                else{
                    $count = 0;
                }
                if($count == 0){
                    if(empty($noFilterMontongueList)){
                        $noFilterMontongueList = ",";
                    }
                    $noFilterMontongueList .= $mtongue.",";
                    $memCacheObject->set("NO_ADDON_MEM_FILTER_MTONGUE",$noFilterMontongueList,3600);
                }
                else{
                    if(empty($noFilterMontongueList)){
                        $noFilterMontongueList = "";
                    }
                    $memCacheObject->set("NO_ADDON_MEM_FILTER_MTONGUE",$noFilterMontongueList,3600);
                }
            }
            return $count;
        }
    }
    public function fetchMembershipDetails($membership, $userObj, $device = 'desktop',$ignoreShowOnlineCheck= false)
    {
        if(empty($device)){
            $device = "desktop";
        }
        $memCacheObject = JsMemcache::getInstance();

        $servicesObj = new billing_SERVICES('newjs_master');

        if ($membership == "MAIN") {
            $mtongue = "-1";
            if(!empty($userObj)){
                $mtongue = $userObj->mtongue;
            }
            
            $key_main = $device . "_".$membership."_MEMBERSHIP";
            $key_main .= "_" . $userObj->getCurrency()."_".$mtongue;
            $key_main_hidden = $device . "_".$membership."_HIDDEN_MEMBERSHIP";
            $key_main_hidden .= "_" . $userObj->getCurrency()."_".$mtongue;
            $fetchOnline = true;
            $fetchOffline = $ignoreShowOnlineCheck;
            $allMainMemHidden = array();
            $allMainMem = array();

            if ($fetchOffline == true && $memCacheObject->get($key_main_hidden)) {
                $allMainMemHidden = unserialize($memCacheObject->get($key_main_hidden));
                $fetchOffline = false;
            } 
            if ($fetchOnline == true && $memCacheObject->get($key_main)) {
                $allMainMem = unserialize($memCacheObject->get($key_main));
                $fetchOnline = false;
            }

            if($fetchOnline == true || $fetchOffline == true){
                $serviceArr = VariableParams::$mainMembershipsArr;
                if ($userObj) {
                    $currencyType = $userObj->getCurrency();
                }
                $serviceInfoAggregateData = $this->serviceObj->getServiceInfo($serviceArr, $currencyType, "", $renew, $userObj->getProfileid(), $device, $userObj,$fetchOnline,$fetchOffline);
                foreach ($serviceArr as $key => $value) {
                    foreach ($serviceInfoAggregateData as $kk => $vv) {
                        if ($value == substr($kk, 0, strlen($value))) {
                            if($fetchOffline == true && $vv['SHOW_ONLINE'] == 'N'){
                                $allMainMemHidden[$value][$kk] = $vv;
                            }
                            if($fetchOnline == true && ($vv['SHOW_ONLINE'] == 'Y' || $vv['SHOW_ONLINE'] == 'S')){
                                $allMainMem[$value][$kk] = $vv;
                            }
                        }
                        
                    }
                }
                if($fetchOffline == true){
                   $memCacheObject->set($key_main_hidden, serialize($allMainMemHidden), 3600); 
                }
                if($fetchOnline == true){
                    $memCacheObject->set($key_main, serialize($allMainMem), 3600);
                }
            }
            if(is_array($allMainMem) && is_array($allMainMemHidden)){
                foreach ($allMainMem as $key => $value) {
                    $allMainMemCombined[$key] = $value;
                }
                foreach ($allMainMemHidden as $key => $value) {
                    if($allMainMemCombined[$key]){
                        foreach ($value as $durationId => $durationWiseServices) {
                            $allMainMemCombined[$key][$durationId] = $durationWiseServices;
                        }
                    }
                    else{
                        $allMainMemCombined[$key] = $value;
                    }
                }
            }
            else if(is_array($allMainMem)){
                $allMainMemCombined = $allMainMem;
            }
            else if(is_array($allMainMemHidden)){
                $allMainMemCombined = $allMainMemHidden;
            }
            else{
                $allMainMemCombined = array();
            }
            return $allMainMemCombined;
        } elseif ($membership == "ADDON") {
            $mtongue = "-1";
            if(!empty($userObj)){
                $addonMtongue = $userObj->addonMtongue;
            }
            $key = $device . "_ADDON_MEMBERSHIP";
            $key .= "_" . $userObj->getCurrency()."_".$addonMtongue;

            if ($memCacheObject->get($key)) {
                $addonMem = unserialize($memCacheObject->get($key));
            } else {
                if ($userObj) {
                    $currencyType = $userObj->getCurrency();
                }
                $addonMem = $this->serviceObj->getAddOnInfo($currencyType, 0, $device,$addonMtongue);
                $memCacheObject->set($key, serialize($addonMem), 3600);
            }
            return $addonMem;
        }

    }

    public function fetchPaymentOptions($ipAddress)
    {
        $options = $this->getEligiblePaymentMethods($ipAddress);
        return $options;
    }

    public function getEligiblePaymentMethods($ipAddress)
    {
        $options['DebitCard']  = "";
        $options['CreditCard'] = "";
        $options['NetBanking'] = "";
        return $options;
    }

    public function getDiscountInfo($user,$upgradeMem="NA",$device="desktop")
    {
        $userType = $user->userType;
        $fest     = $this->getFestiveFlag();
        $user->setFestInfo($fest);
        
        if($userType == memUserType::UPGRADE_ELIGIBLE && in_array($upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
            $discountInfo["TYPE"] = discountType::UPGRADE_DISCOUNT;
        } else{
            if($userType == memUserType::PAID_WITHIN_RENEW || $userType == memUserType::EXPIRED_WITHIN_LIMIT) {
                $discountInfo["TYPE"] = discountType::RENEWAL_DISCOUNT;
            } else {
                if ($user->getProfileid() != '') {
                    if($userType == memUserType::FREE || $userType == memUserType::EXPIRED_BEYOND_LIMIT){
                        $this->lightningDealDiscount = $this->memObj->getLightningDealDiscount($user->getProfileid(),$device);
                    }
                    if(!$this->lightningDealDiscount){
                        $varDiscount       = $this->memObj->getSpecialDiscount($user->getProfileid());
                        $this->varDiscount = $varDiscount;
                    }
                }
                if ($this->lightningDealDiscount) {
                    $discountInfo["TYPE"] = discountType::LIGHTNING_DEAL_DISCOUNT;
                } 
                else if ($varDiscount) {
                    $discountInfo["TYPE"] = discountType::SPECIAL_DISCOUNT;
                } else {
                    if ($fest) {
                        $discountInfo["TYPE"] = discountType::FESTIVE_DISCOUNT;
                    } elseif ($this->isDiscountOfferActive()) {
                        $discountInfo["TYPE"] = discountType::OFFER_DISCOUNT;
                    }
                }
            }
        }
        return $discountInfo;
    }

    public function trackMembership($userObj, $source, $navigationString = '', $serviceSelected = '', $vasImpression = '', $discount = '', $total = '', $paymentTab = '', $trackType = '', $device = '')
    {
        $profileid = $userObj->getProfileid();
        $userType  = $userObj->getUserType();
	if($userType==8)
		$this->memUpgrade=true;
	else
		$this->memUpgrade=false;
	
        if ($source == 1) {
            $serviceSelected           = $userObj->getMemStatus();
            $navigationSuggestedString = $serviceSelected;
        } else {
            $navigationSuggestedString = $navigationString;
        }

        $serviceSelectedArr = explode(',', $serviceSelected);
        $serviceSelectedArr = array_filter($serviceSelectedArr);
        $serviceSelected    = implode(',', $serviceSelectedArr);

        if(($trackType == 'F' || $source == 100) && $this->memUpgrade==false) {
            $trackFailedPaymentObj    = new billing_TRACKING_FAILED_PAYMENT();
            $trackFailedPaymentLogObj = new billing_TRACKING_FAILED_PAYMENT_LOG();
            if ($paymentTab) {
                $paymentOptionsArr = VariableParams::$paymentOptions;
                $paymentTab        = $paymentOptionsArr[$paymentTab];
            }
            $currency = $userObj->getCurrency();

            if ($source == 100) {
                $device = 'Android_app';
            }

            $trackFailedPaymentObj->trackingPaymentPage($profileid, $serviceSelected, $total, $discount, $currency, $paymentTab, $device);
            $trackFailedPaymentLogObj->trackingPaymentPage($profileid, $serviceSelected, $total, $discount, $currency, $paymentTab, $device);
        } else {
            $trackingObj = new billing_TRACKING_BILLING_REVAMP();
            $trackingObj->trackingMain($profileid, $userType, $serviceSelected, $source, $navigationSuggestedString, $vasImpression, $discount, $total);
        }
    }

    public function getOfferPrice($allMainMem, $user, $discountType = "", $device = 'desktop',$apiObj = "",$upgradeMem="NA")
    {
        if (!$discountType) {

            if($apiObj != "" && is_array($apiObj->discountTypeInfo)){
                $discountTypeArr = $apiObj->discountTypeInfo;
            }
            else{

                $discountTypeArr = $this->getDiscountInfo($user,$upgradeMem,$device);
            }
            $discountType    = $discountTypeArr['TYPE'];
        }
        if($apiObj!="" && $apiObj->userRenewalPercent){
            $renewalPercent = $apiObj->userRenewalPercent;
        }
        else{
            $renewalPercent = $this->getVariableRenewalDiscount($user->getProfileid());
        }
        //get upgrade discount for this user
        if(in_array($upgradeMem,VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
            $upgradePercentArr = $this->getUpgradeMembershipDiscount($user, $upgradeMem,$apiObj);
        }
        else{
            $upgradePercentArr = array();
        }

        foreach ($allMainMem as $mainMem => $subMem) {
            foreach ($subMem as $key => $value) {
                $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round($allMainMem[$mainMem][$key]['PRICE'], 2);
                if (strpos(discountType::UPGRADE_DISCOUNT, $discountType) !== false && $upgradePercentArr[$key]) { 
                    $allMainMem[$mainMem][$key]['OFFER_PRICE'] = $upgradePercentArr[$key]["discountedUpsellMRP"];
                } else{
                    if (strpos(discountType::LIGHTNING_DEAL_DISCOUNT, $discountType) !== false && strpos(",", $discountType) === false) {
                            $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round($allMainMem[$mainMem][$key]['LIGHTNING_DEAL_DISCOUNT_PRICE'], 2);
                    } 
                    else if (strpos(discountType::RENEWAL_DISCOUNT, $discountType) !== false) {
                        $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round(($allMainMem[$mainMem][$key]['PRICE'] - ceil($allMainMem[$mainMem][$key]['PRICE'] * $renewalPercent) / 100), 2);
                    } else {
                        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false && strpos(",", $discountType) === false) {
                            $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round($allMainMem[$mainMem][$key]['SPECIAL_DISCOUNT_PRICE'], 2);
                        } else {
                            if (strpos(discountType::FESTIVE_DISCOUNT, $discountType) !== false && !strpos(",", $discountType)) {
                                $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round($allMainMem[$mainMem][$key]['FESTIVE_PRICE'], 2);
                            } else if (strpos(discountType::OFFER_DISCOUNT, $discountType) !== false && strpos(",", $discountType) === false) {
                                $allMainMem[$mainMem][$key]['OFFER_PRICE'] = round($allMainMem[$mainMem][$key]['DISCOUNT_PRICE'], 2);
                            }
                        }
                    }
                }
            }
        }
        //print_r($allMainMem);die;
        return $allMainMem;
    }

    public function isDiscountOfferActive()
    {
        $discountOfferLogObj   = new billing_DISCOUNT_OFFER_LOG('newjs_masterRep');
        $active                = $discountOfferLogObj->checkDiscountOffer();
        $this->discountOfferID = $active;
        return $active;
    }

    public function getDiscountUpto()
    {
        $discountOfferObj = new billing_DISCOUNT_OFFER();
        $discountUpto     = $discountOfferObj->getDiscountUpto();
        return $discountUpto;
    }

    public function getDiscountExpiry($discntId)
    {
        $discountOfferLogObj = new billing_DISCOUNT_OFFER_LOG();
        $discount_expiry     = $discountOfferLogObj->getExpiryDate($discntId);
        list($yy, $mm, $dd)  = explode('-', $discount_expiry);
        $ts                  = mktime(0, 0, 0, $mm, $dd, $yy);
        $discount_expiry     = date("j-M-Y", $ts);
        $discount_expiry     = date("jS F", strtotime($discount_expiry));
        return $discount_expiry;
    }

    public function fetchLowestActivePrices($userObj, $allMainMem, $device = 'desktop')
    {
        $memArray = VariableParams::$mainMembershipsArr;
        $userType = $userObj->userType;

        if(!empty($userObj) && $userObj!=""){
            $mtongue = $userObj->mtongue;
        }
        else{
            $mtongue = "-1";
        }

        $minPriceInfoAggregateData = $this->serviceObj->getLowestActiveMainMembership($memArray, $device,$mtongue);
        //print_r($minPriceInfoAggregateData);die;
        foreach ($memArray as $key => $value) {
            foreach ($minPriceInfoAggregateData as $kk => $vv) {
                if ($value == substr($kk, 0, strlen($value))) {
                    $minPriceArr[$value][$kk] = $vv;
                }
            }
        }

        foreach ($minPriceArr as $key => $val) {
            foreach ($val as $k => $v) {
                if (!isset($allMainMem[$key][$k])) {
                    unset($minPriceArr[$key][$k]);
                }
            }
        }

        foreach ($minPriceArr as $key => $val) {
            $i = 0;
            foreach ($val as $k => $v) {
                if ($i == 1) {
                    unset($minPriceArr[$key][$k]);
                } else {
                    $newMinPriceArr[$key] = array(
                        'NAME'         => $minPriceArr[$key][$k]['NAME'],
                        'PRICE_RS_TAX' => $minPriceArr[$key][$k][$device . "_RS"],
                        'PRICE_INR'    => $minPriceArr[$key][$k]['PRICE_INR'],
                        'PRICE_USD'    => $minPriceArr[$key][$k]['PRICE_USD'],
                    );
                }
                $i = 1;
            }
        }

        $minPriceArr = $newMinPriceArr;

        foreach ($allMainMem as $mainMem => $subMem) {
            $offerPrice = array();
            $i          = 0;
            foreach ($subMem as $key => $value) {
                $actPrice[$key]   = $allMainMem[$mainMem][$key]['PRICE'];
                $offerPrice[$key] = $allMainMem[$mainMem][$key]['OFFER_PRICE'];
            }
            foreach ($offerPrice as $key => $value) {
                if ($value == min($offerPrice)) {
                    $keyAct = $key;
                }

            }

            $minPriceArr[$mainMem]['OFFER_PRICE'] = min($offerPrice);
        }

        return $minPriceArr;
    }

    public function getSubStatus($profileid, $module = null)
    {
        $subStatus = $this->memObj->getSubscriptionStatus($profileid, $module);
        return $subStatus;
    }

    public function getMostPopular()
    {
        $most_popular = $this->serviceObj->getMostPopular();
        return $most_popular;
    }

    public function getActiveServices()
    {
        $activeServcs   = $this->serviceObj->getActiveServices();
        $tempArr        = VariableParams::$mainMembershipsArr;
        $sortedServices = array();
        if ($activeServcs) {
            $servIds = array_values($activeServcs);
        }

        if (!empty($servIds) && is_array($servIds)) {
            foreach ($tempArr as $key => $val) {
                if (in_array($val, $servIds)) {
                    $sortedServices[] = $val;
                }
            }
        }
        return $sortedServices;
    }

    public function getServicePrice($serviceId, $currency)
    {
        $servicePrice = $this->serviceObj->getServicesAmount($serviceId, $currency);
        return $servicePrice;
    }

    public function addCallBack($phoneNo, $email, $jsSelectd, $profileid = '', $device = null, $channel = null, $callbackSource = null, $date, $startTime, $endTime)
    {
        $billingExcCallbackObj = new billing_EXC_CALLBACK();
        $added                 = $billingExcCallbackObj->insertCallbackWithSelectedService($phoneNo, $email, $jsSelectd, $profileid, $device, $channel, $callbackSource, $date, $startTime, $endTime);
        return $added;
    }

    public function deActivateDiscountOffer($discountType)
    {
        $todaysDt = date("Y-m-d");
        if ($discountType == discountType::FESTIVE_DISCOUNT) {
            $festiveLogRevampObj = new billing_FESTIVE_LOG_REVAMP();
            $offerDetails        = $festiveLogRevampObj->getActiveOfferDetails();
            $offerEndDt          = $offerDetails['END_DT'];
            $activestatus        = $offerDetails['STATUS'];

            if ($activestatus && (JSstrToTime($todaysDt) > JSstrToTime($offerEndDt))) {
                $festiveLogRevampObj->deActivateOffer($offerDetails['ID']);
                $this->serviceObj->activateLoggedServicesForOnline($offerDetails['LAST_ACTIVE_SERVICES']);
            }
        }
        $this->flushMemcacheForMembership();
    }

    public function flushMemcacheForMembership()
    {
        $memCacheObject     = JsMemcache::getInstance();
        $membershipKeyArray = VariableParams::$membershipKeyArray;
        $mtongueArr = FieldMap::getFieldLabel("community_small",null,"1");
        foreach ($membershipKeyArray as $key => $keyVal) {
            $memCacheObject->remove($keyVal."_-1");
            foreach ($mtongueArr as $k => $v) {
                $memCacheObject->remove($keyVal."_".$k);
            }
        }
        $memCacheObject->remove('NO_MAIN_MEM_FILTER_MTONGUE');
        $memCacheObject->remove('NO_ADDON_MEM_FILTER_MTONGUE');
    }

    public function memCallbackTracking($profileid, $phoneNo, $email, $device = null, $channel = null, $callbackSource = null, $date, $startTime, $endTime)
    {
        $excCallbackObj = new billing_EXC_CALLBACK();
        $excCallbackObj->addRecord($profileid, $phoneNo, $email, $device, $channel, $callbackSource, $date, $startTime, $endTime);
    }

    public function checkEmailSendForDay($profileid, $email)
    {
        $time     = date("Y-m-d H:i:s", time());
        $todaysDt = date("Y-m-d", JSstrToTime("$time + 09 hours 30 minutes"));

        $excCallbackObj = new billing_EXC_CALLBACK();
        $entryDt        = $excCallbackObj->getLatestEntryDate($profileid, $email);
        if ($entryDt) {
            $entryDt = date("Y-m-d", JSstrToTime("$entryDt + 09 hours 30 minutes"));
        }

        if ($todaysDt == $entryDt) {
            return 1;
        }

        return;
    }

    public function getAllotedExecEmail($profileid)
    {
        $mainAdminObj     = new incentive_MAIN_ADMIN();
        $jsadminPswrdsObj = new jsadmin_PSWRDS();
        $execName         = $mainAdminObj->getAllotedExecForProfile($profileid);
        $execDetails      = $jsadminPswrdsObj->getExecutiveDetails($execName);
        $execEmail        = $execDetails['EMAIL'];
        return $execEmail;
    }

    public function getAllotedExecSupervisor($profileid)
    {
        $mainAdminObj     = new incentive_MAIN_ADMIN('newjs_slave');
        $jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
        $execName         = $mainAdminObj->getAllotedExecForProfile($profileid);
        $execSup          = $jsadminPswrdsObj->fetchAgentSupervisor($execName);
        return array($execName, $execSup);
    }

    public function getDiscountOffer($subMem)
    {
        $discountOfferObj = new billing_DISCOUNT_OFFER();
        $discount         = $discountOfferObj->getDiscountOffer($subMem);
        if ($discount > 0) {
            return $discount;
        }

        return 0;
    }

    public function getSpecialDiscount($profileId)
    {
        $discount = $this->memObj->getSpecialDiscount($profileId);
        if ($discount != 0) {
            list($yy, $mm, $dd) = explode('-', $discount["EDATE"]);
            $ts                 = mktime(0, 0, 0, $mm, $dd, $yy);
            $discount["EDATE"]  = date("j-M-Y", $ts);
            $discount["EDATE"]  = date("jS F", strtotime($discount["EDATE"]));
        }
        return $discount;
    }

    public function getLightningDealDiscount($profileId,$device="desktop")
    {
        $discount = $this->memObj->getLightningDealDiscount($profileId,$device);
        if ($discount != 0) {
            list($yy, $mm, $dd) = explode('-', $discount["EDATE"]);
            $ts                 = mktime(0, 0, 0, $mm, $dd, $yy);
            $discount["EDATE"]  = date("j-M-Y", $ts);
            $discount["EDATE"]  = date("jS F", strtotime($discount["EDATE"]));
        }
        return $discount;
    }

    public function getSpecialDiscountForAllDurations($profileId)
    {
        $vdodObj  = new VariableDiscount();
        $discount = $vdodObj->getAllDiscountWithService($profileId);
        return $discount;
    }

    public function getSpecialDiscountForAllDurationsPreviously($profileid)
    {
        $vdodObj = new VariableDiscount();
        $discount = $vdodObj->getPreviousVdLogDetails($profileid, true);
        return $discount;
    }

    public function horoscopeMatch($user, $maxActivity, $regisDur, $order, $freeLatest)
    {

        $obj        = new newjs_HOROSCOPE();
        $descFields = $this->jprofileObj->get($user->profileid, 'PROFILEID', "HOROSCOPE_MATCH,CITY_BIRTH,BTIME");
        if (!is_array($freeLatest)) {
            $freeLatest = array();
        }
        if (($obj->getIfHoroscopePresent($user->profileid) || ($descFields['HOROSCOPE_MATCH'] && $descFields['CITY_BIRTH'] && $descFields['BTIME'])) && !in_array('A', $freeLatest)) {
            array_push($order, 'A');
            if (count($order) < $maxActivity) {
                return $this->uniqueSearches($user->profileid, $maxActivity, $regisDur, $order, $freeLatest);
            } else {
                return $order;
            }

        } else {
            return $this->uniqueSearches($user->profileid, $maxActivity, $regisDur, $order, $freeLatest);
        }
    }

    public function uniqueSearches($profileid, $maxActivity, $regisDur, $order, $freeLatest)
    {

        $misObj = new MIS_SEARCHQUERY();
        if ((pow(($misObj->getUniqueSearchDays($profileid) / $regisDur), .75) < .25) && !in_array('T', $freeLatest)) {
            array_push($order, 'T');
            if (count($order) < $maxActivity) {
                return $this->profileViews($profileid, $maxActivity, 0, $regisDur, $order, $freeLatest);
            } else {
                return $order;
            }
        } else {
            return $this->profileViews($profileid, $maxActivity, 0, $regisDur, $order, $freeLatest);
        }
    }

    public function profileViews($profileid, $maxActivity, $fromRelation, $regisDur, $order, $freeLatest)
    {

        include_once $_SERVER['DOCUMENT_ROOT'] . "/profile/ntimes_function.php";
        if ((pow((ntimes_count($profileid, "SELECT") / $regisDur), 0.75) < 5.0) && !in_array('R', $freeLatest)) {
            if ($fromRelation != 1) {
                array_push($order, 'R');
                if (count($order) < $maxActivity) {
                    return $this->profileSuggestedBy($profileid, $maxActivity, $order, $freeLatest, $regisDur);
                } else {
                    return $order;
                }

            } else {
                if ($fromRelation != 1) {
                    return $this->profileSuggestedBy($profileid, $maxActivity, $order, $freeLatest, $regisDur);
                } else {
                    return $order;
                }
            }
        } else {
            if ($fromRelation != 1) {
                return $this->profileSuggestedBy($profileid, $maxActivity, $order, $freeLatest, $regisDur);
            } else {
                return $order;
            }
        }
    }

    public function profileSuggestedBy($profileid, $maxActivity, $order, $freeLatest, $regisDur)
    {

        $fromRelation = 1;
        $relationVal  = $this->jprofileObj->get($profileid, 'PROFILEID', 'RELATION');
        $relationVal  = $relationVal['RELATION'];
        if (($relationVal == '1' || $relationVal == '2' || $relationVal == '2D') && !in_array('I', $freeLatest)) {
            array_push($order, 'I');
            if (count($order < $maxActivity)) {
                return $this->profileViews($profileid, $maxActivity, 1, $regisDur, $order, $freeLatest);
            } else {
                return $order;
            }

        } else {
            return $this->profileViews($profileid, $maxActivity, 1, $regisDur, $order, $freeLatest, $regisDur);
        }
    }

    public function array_merge_full($array1, $array2)
    {

        if (count($array1) == 0 || count($array2) == 0) {
            if (count($array1) == 0) {
                $mergedArray = $array2;
            } else {
                $mergedArray = $array1;
            }

        } else {
            $mergedArray = array_merge($array1, $array2);
        }
        return $mergedArray;
    }

    public function getFestiveFlag()
    {
        $festFlag = 0;
        $festFlag = $this->serviceObj->getFestive();
        return $festFlag;
    }

    public function getFestiveBanner()
    {
        $festData = $this->serviceObj->getFestivalBanner();
        return $festData;
    }
    public function handleBackendCase($id, $profileid)
    {
        $paymentCollectObj = new incentive_PAYMENT_COLLECT();
        $details           = $paymentCollectObj->getMembershipDetails($id, $profileid);
        $landingVAS        = "main" . $details["MAIN_SERVICE"] . ",";
        $landingVAS .= $details["ADDON_SERVICE"];
        $discount  = $details['DISCOUNT'];
        $profileid = $details['PROFILEID'];
        return array(
            $landingVAS,
            $discount,
            $profileid,
        );
    }

    public function getUserData($profileid)
    {
        $profileObj        = LoggedInProfile::getInstance();
        $loggedinProfileID = $profileObj->getPROFILEID();
        if ($profileid == $loggedinProfileID) {
            $userData['PROFILEID'] = $loggedinProfileID;
            $userData['USERNAME']  = $profileObj->getUSERNAME();
            $userData['EMAIL']     = $profileObj->getEMAIL();
            $userData['PHONE_MOB'] = $profileObj->getPHONE_MOB();
            $userData['PHONE_RES'] = $profileObj->getPHONE_RES();
            $userData['CONTACT']   = $profileObj->getCONTACT();
            $userData['PINCODE']   = $profileObj->getPINCODE();
        } else {
            $userData = $this->jprofileObj->get($profileid, 'PROFILEID', "USERNAME,EMAIL,PHONE_MOB,PHONE_RES,CONTACT,PINCODE");
        }
        return $userData;
    }

    public function addHitsTracking($profileid, $pgNo, $tabNo, $fromSource = '', $user_agent = 'UNKNOWN')
    {
        $billingPaymentHitsObj = new BILLING_PAYMENT_HITS();
        $billingPaymentHitsObj->addPaymentHits($profileid, $pgNo, $tabNo, $user_agent);
        if ($fromSource) {
            $billingPaySrcObj = new billing_PAYMENT_SOURCE_TRACKING();
            $billingPaySrcObj->addSourceTracking($profileid, $pgNo, $fromSource);
            $billingDropSrcObj = new billing_DROPOFF_SOURCE_TRACKING();
            $geoIpCountry      = $_SERVER['GEOIP_COUNTRY_CODE'];
            $billingDropSrcObj->addSourceTracking($profileid, $pgNo, $fromSource, $geoIpCountry);
        }
        unset($billingPaySrcObj);
        unset($billingPaymentHitsObj);
    }

    public function serviceIdWhitelistCheck($value = '')
    {
        if (!$value) {
            return true;
        }

        $servicesArr = $this->serviceObj->getAllServices();
        foreach ($servicesArr as $key => $val) {
            $serviceId = $val['SERVICEID'];
            if (strstr($serviceId, trim($value))) {
                return true;
            }

        }
        $validationHandObj = new ValidationHandler();
        $error             = "Membership-MainServiceId set:$value";
        $validationHandObj->getValidationHandler('', $error, 1);
        return;
    }

    public function getServiceNames($services)
    {
        $serviceName = array();
        foreach ($services as $key => $val) {
            foreach (VariableParams::$mainMembershipNamesArr as $id => $name) {
                if ($val == $id) {
                    $serviceName[$key] = $name;
                }
            }
        }
        return $serviceName;
    }

    public function getServiceMessages($services)
    {
        $message         = array();
        $serviceFeatures = VariableParams::$apiPageOnePerMembershipBenefitsVisibility;
        $serviceMessages = VariableParams::$apiPageOnePerMembershipBenefits;
        foreach ($services as $key => $val) {
            if ($val == 'P') {
                foreach (array_intersect_key($serviceMessages[$val], $serviceFeatures) as $k => $v) {
                    $message[$key][] = $serviceFeatures[$k];
                }
            } elseif ($val == 'C') {
                foreach (array_intersect_key($serviceMessages[$val], $serviceFeatures) as $k => $v) {
                    $message[$key][] = $serviceFeatures[$k];
                }
            } elseif ($val == 'ESP') {
                foreach (array_intersect_key($serviceMessages[$val], $serviceFeatures) as $k => $v) {
                    $message[$key][] = $serviceFeatures[$k];
                };
            } elseif ($val == "X") {
                foreach (array_intersect_key($serviceMessages[$val], $serviceFeatures) as $k => $v) {
                    $message[$key][] = $serviceFeatures[$k];
                }
            }
        }
        return $message;
    }

    public function getUserServiceName($memID)
    {
        $memID     = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);
        $realMemID = $memID[0];
        foreach (VariableParams::$mainMembershipNamesArr as $id => $name) {
            if ($realMemID == $id) {
                return $name;
            }

        }
    }

    public function getMobMembershipTabs($allMainMem, $userMemID)
    {
        $tabs = array();
        foreach ($allMainMem as $key => $memType) {
            if ($userMemID != $key) {
                unset($allMainMem[$key]);
            } else {
                foreach ($memType as $k => $v) {
                    $duration           = explode($userMemID, $k);
                    $tabs[$duration[1]] = $v;
                }
            }
        }
        return $tabs;
    }

    public function getMobSuggestedService($id, $tabs)
    {
        $tabs = array_keys($tabs);
        foreach ($tabs as $key => $value) {
            $tabs[$key] = $id . $value;
        }
        $tabs = implode(',', $tabs);
        return $tabs;
    }

    public function bmsCheckRenewalDiscountGiven($profileid)
    {
        $purchaseObj = new BILLING_PURCHASES();
        return $purchaseObj->bmsCheckRenewalDiscountGiven($profileid);
    }

    public function updateScheduleVisitEntry($profileid)
    {
        $fieldSalesWidgetObj = new incentive_FIELD_SALES_WIDGET();
        $fieldSalesWidgetObj->insertEntry($profileid);
        //execute post submit actions(mail and sms)
        $fieldSalesObj = new FieldSales();
        $fieldSalesObj->postFieldVisitRequestSubmit($profileid, true, true);
        unset($fieldSalesObj);
    }

    public function retrieveCorrectMemID($memID)
    {
        if ($memID != "FREE") {
            $memID = @explode(",", $memID);
            $memID = $memID[0];
            $memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);
            $memID = $memID[0];
            if (strpos($memID, "L")) {
                $this->unlimited = true;
                $memID           = substr($memID, 0, -1);
            }
            return $memID;
        } else {
            return $memID;
        }
    }

    public function checkForESathiService($profileid, $activationDt, $expiryDt, $serviceid)
    {
        $servStatusObj = new BILLING_SERVICE_STATUS();
        $purchaseObj   = new BILLING_PURCHASES();
        $expiryDt      = date("Y-m-d", strtotime($expiryDt));
        $valueArray    = array(
            "ACTIVATED_ON" => "'$activationDt'",
            "EXPIRY_DT"    => "'$expiryDt'",
            "SERVICEID"    => "'$serviceid'",
            "PROFILEID"    => $profileid,
        );
        $billid         = $servStatusObj->getArray($valueArray, '', '', '', 'BILLID');
        $purchaseDetail = $purchaseObj->getPurchaseDetails($billid[0]['BILLID']);
        $actualService  = $purchaseDetail['SERVICEID'];
        return $actualService;
    }

    public function getUserIPandCurrency($profileid = null)
    {
        $geoIpCountry = $_SERVER['GEOIP_COUNTRY_CODE'];
        if (!empty($geoIpCountry)) {
            if ($geoIpCountry == 'IN') {
                $currency     = 'RS';
                $currencyType = VariableParams::$indianCurrency;
            } else {
                $currency     = 'DOL';
                $currencyType = VariableParams::$otherCurrency;
            }

        } else {
            $JMembershipObj = new JMembership();
            $ipAddress      = CommonFunction::getClientIP();
            if (strstr($ipAddress, ",")) {
                $ip_new    = explode(",", $ipAddress);
                $ipAddress = $ip_new[0];
            }
            $isNRIUser = $JMembershipObj->fetchNRIStatus($ipAddress);
            if ($isNRIUser) {
                $currency     = 'DOL';
                $currencyType = VariableParams::$otherCurrency;
            } else {
                $currency     = 'RS';
                $currencyType = VariableParams::$indianCurrency;
            }
        }
        
        $testDol = false;
        if (JsConstants::$whichMachine == 'test') {
            $dolBillingForTest = new billing_DOL_BILLING_USERS_FOR_TEST();
            $testDol = $dolBillingForTest->checkUserForDol($profileid);
        }
        if ($profileid == 12970375 || $testDol == true) {
            $currency = 'DOL';
        }
        if($_COOKIE['jeevansathi_hindi_site_new'] == 'Y'){ 
            $currency = 'RS';
        }
        return array(
            $ipAddress,
            $currency
        );
    }

    public function getUserDiscountDetailsArray($userObj, $type = "1188", $apiVersion = 3,$apiObj="",$upgardeMem="NA")
    {
        if($apiObj!="" && $apiObj->device){
            $device = $apiObj->device;
        }
        else{
            $device = "desktop";
        }
        if ($userObj->getProfileid()) {
            $profileObj = LoggedInProfile::getInstance('newjs_slave', $userObj->getProfileid());
            $profileObj->getDetail();
            if ($profileObj->getPROFILEID()) {
                $activatedStatus = $profileObj->getACTIVATED();
                $screeningStatus = $activatedStatus;
            }
            if ($screeningStatus == "Y") 
            {
                if($apiObj!="" && is_array($apiObj->discountTypeInfo)){
                    $discountTypeArr = $apiObj->discountTypeInfo;
                }
                else{
                    $discountTypeArr = $this->getDiscountInfo($userObj,$upgardeMem,$device);
                }
                $discountType    = $discountTypeArr['TYPE'];
            }
        }
        if (strpos(discountType::UPGRADE_DISCOUNT, $discountType) !== false) {
            $upgradeActive  = '1';

            //get upgrade discount for this user
            if(in_array($upgardeMem,VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){

                $upgradePercentArr = $this->getUpgradeMembershipDiscount($userObj, $upgardeMem,$apiObj);
            }
            else{
                $upgradePercentArr = array();
            }
        }

        if (strpos(discountType::LIGHTNING_DEAL_DISCOUNT, $discountType) !== false) {
            $lightningDealActive = '1';

            list($yy, $mm, $dd)       = explode('-', $this->lightningDealDiscount["EDATE"]);
            $ts                       = mktime(0, 0, 0, $mm, $dd, $yy);
            $lightning_deal_discount_expiry = date("Y-m-d", $ts);
            $lightningDealDiscountPercent          = $this->lightningDealDiscount['DISCOUNT'];

        }
        if (strpos(discountType::OFFER_DISCOUNT, $discountType) !== false) {
            $discountActive  = '1';
            $discntId        = $this->discountOfferID;
            $discount_expiry = $this->getDiscountExpiry($discntId);
            $discountPercent = $this->getDiscountUpto();
        }
        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false) {
            $specialActive = '1';

            list($yy, $mm, $dd)       = explode('-', $this->varDiscount["EDATE"]);
            $ts                       = mktime(0, 0, 0, $mm, $dd, $yy);
            $variable_discount_expiry = date("Y-m-d", $ts);
            $discountSpecial          = $this->varDiscount['DISCOUNT'];
        }

        $fest = $userObj->getFestInfo();

        if ($fest == "1") {
            $festiveLogRevampObj = new billing_FESTIVE_LOG_REVAMP();
            $offerDetails        = $festiveLogRevampObj->getActiveOfferDetails();
            $festEndDt           = date('d M Y', strtotime($offerDetails['END_DT']));
            $memActFunc          = new MembershipActionFunctions();
            $festDurBanner       = $memActFunc->getFestDurBanner($type, $discountType, $userObj->getProfileid(), $apiVersion);
            unset($festiveLogRevampObj);
        }
        if($apiObj!="" && $apiObj->userRenewalPercent){
            $renewalPercent = $apiObj->userRenewalPercent;
        }
        else{
            $renewalPercent = $this->getVariableRenewalDiscount($userObj->getProfileid());
        }
        if ($userObj->userType == 4 || $userObj->userType == 6 || $specialActive == 1 || $discountActive == 1 || $fest == 1 || $lightningDealActive == 1) {
            if ($lightningDealActive == 1) {
                $expiry_date = $lightning_deal_discount_expiry;
                $discPerc    = $lightningDealDiscountPercent;
                if ($fest == 1) {
                    $code = 9;
                } else {
                    $code = 10;
                }
            }
            else if ($userObj->userType == 4 || $userObj->userType == 6) {
                $expiry_date   = $userObj->expiryDate;
                $discPerc      = $renewalPercent;
                $renewalActive = 1;
                if ($fest == 1) {
                    $code = 1;
                } else {
                    $code = 2;
                }
            } elseif ($specialActive == 1) {
                $expiry_date = $variable_discount_expiry;
                $discPerc    = $discountSpecial;
                if ($fest == 1) {
                    $code = 3;
                } else {
                    $code = 4;
                }
            } elseif ($discountActive == 1) {
                $expiry_date = $discount_expiry;
                $discPerc    = $discountPercent;
                if ($fest == 1) {
                    $code = 5;
                } else {
                    $code = 6;
                }
            } elseif ($fest == 1) {
                $expiry_date       = $festEndDt;
                $festOffrLookupObj = new billing_FESTIVE_OFFER_LOOKUP();
                $discPerc          = $festOffrLookupObj->getMaxFestiveDiscountPercentage();
                unset($festOffrLookupObj);
                $code = 7;
            }
        } elseif ($userObj->userType == 5 || $userObj->userType == memUserType::UPGRADE_ELIGIBLE) {

            $expiry_date = null;
            $discPerc    = null;
            $code        = 8;
        } else {

            // No discounts active
            $expiry_date = null;
            $discPerc    = null;
            $code        = 0;
        }

        return array(
            $discountType,
            $discountActive,
            $discount_expiry,
            $discountPercent,
            $specialActive,
            $variable_discount_expiry,
            $discountSpecial,
            $fest,
            $festEndDt,
            $festDurBanner,
            $renewalPercent,
            $renewalActive,
            $expiry_date,
            $discPerc,
            $code,
            $upgradePercentArr,
            $upgradeActive,
            $lightningDealActive,
            $lightning_deal_discount_expiry,
            $lightningDealDiscountPercent
        );
    }

    public function getCrmSmsDiscountText($profileid) {
        $userObj = new memUser($profileid);
        list($ipAddress, $currency) = $this->getUserIPandCurrency($profileid);
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($currency);
        $userObj->setMemStatus();
        $userType = $userObj->userType;
        $validityCheck = $this->checkIfUserIsPaidAndNotWithinRenew($profileid, $userType);
        if ($userType == 4 || $userType == 6) {
            $renewCheckFlag = 1;
        }
        list($discountType,$discountActive,$discount_expiry,$discountPercent,$specialActive,$variable_discount_expiry,$discountSpecial,$fest,$festEndDt,$festDurBanner,$renewalPercent,$renewalActive,$expiry_date,$discPerc,$code,$upgradePercentArr,$upgradeActive,$lightningDealActive,$lightning_deal_discount_expiry,$lightningDealDiscountPercent) = $this->getUserDiscountDetailsArray($userObj);
        if ($validityCheck && ($renewCheckFlag || $specialActive == 1 || $discountActive == 1 || $fest == 1 || $lightningDealActive == 1)) {
            if ($lightningDealActive == 1) {
                //ankita change discounttype to add switch case in getOCB text
                $discountType = 'LIGHTNING_DEAL';
                $messageArr   = $this->getOCBTextMessage($profileid, $discountType, $discPerc, $expiry_date, $fest);
                $text         = "discount of " . str_replace("OFF", "", str_replace("Get ", "", $messageArr['top']));
            }
            else if ($renewCheckFlag) {
                if ($fest == 1) {
                    $text    = "discount of upto " . $renewalPercent . "%";
                } else {
                    $text    = "discount of flat" . $renewalPercent . "%";
                }
            } else if ($specialActive == 1) {
                $discountType = 'VD';
                $messageArr   = $this->getOCBTextMessage($profileid, $discountType, $discPerc, $expiry_date, $fest);
                $text         = "discount of " . str_replace("OFF", "", str_replace("Get ", "", $messageArr['top']));
            } else if ($discountActive == 1) {
                $discountType = 'CASH';
                $messageArr   = $this->getOCBTextMessage($profileid, $discountType, $discPerc, $expiry_date, $fest);
                $text         = "discount of " . str_replace("OFF", "", str_replace("Get ", "", $messageArr['top']));
            } elseif ($fest == 1) {
                $text    = "discount of extra months";
            }
        } 
        if (!empty($text)) {
            return $text;
        } else {
            return null;
        }
    }

    public function getSubscriptionStatusArray($userObj, $subStatus = null, $module = null,$actualService="")
    {
        $memCacheObject = JsMemcache::getInstance();
        $profileid      = $userObj->getProfileid();
        $output         = null;
        if ($memCacheObject->get($profileid . "_MEM_SUBSTATUS_ARRAY")) {
            $subStatus = unserialize($memCacheObject->get($profileid . "_MEM_SUBSTATUS_ARRAY"));
        }
        if (!$output) {
            if (empty($subStatus)) {
                $subStatus = $this->getSubStatus($userObj->getProfileid(), $module);
            }
            if ($subStatus && is_array($subStatus)) {
                foreach ($subStatus as $key => &$value) {
                    $vasCheck = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $value['SERVICEID']);
                    if (!in_array($vasCheck[0], VariableParams::$mainMembershipsArr) && !strpos($vasCheck[0], 'L')) {
                        unset($subStatus[$key]);
                        continue;
                    }
                    $memID = $this->retrieveCorrectMemID($value['SERVICEID'], $userObj);
                    if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                        unset($subStatus[$key]);
                    } else {
                        if (empty($module)) {
                            $esathiCheck = $this->checkForESathiService($userObj->getProfileid(), $value['ACTIVATED_ON'], $value['EXPIRY_DT'], $value['SERVICEID']);
                            if (substr($esathiCheck, 0, 3) == "ESP") {
                                $value['SERVICEID'] = $esathiCheck;
                                $memID              = "ESP";
                            }
                            if (substr($esathiCheck, 0, 3) == "NCP") {
                                $value['SERVICEID'] = $esathiCheck;
                                $memID              = "NCP";
                            }
                            if($actualService != "" && strpos($value['SERVICEID'],'NCP') !== false){
                                $serviceIdArr = explode(",",$value['SERVICEID']);
                                $value['SERVICEID'] = $serviceIdArr[0];
                            }
                            $value['SERVICE_NAME'] = $this->getUserServiceName($memID);
                            $value['SERVICEID_WITHOUT_DURATION'] = $memID;
                            if (filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT)) {
                                $value['SERVICE_DURATION'] = filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT);
                            } else {
                                $value['SERVICE_DURATION'] = 'Unlimited';
                            }
                            $value['ORIG_SERVICEID'] = $value['SERVICEID'];
                        }
                    }
                    $value['EXPIRY_DT'] = date("d M Y", strtotime($value['EXPIRY_DT']));
                }
            }
            if (is_array($subStatus)) {
                usort($subStatus, function ($a, $b) {
                    return strtotime($a['EXPIRY_DT']) - strtotime($b['EXPIRY_DT']);
                });
            }
            $toDeleteKeys = array();
            if (is_array($subStatus)) {
                foreach ($subStatus as $key => $val) {
                    if ($val['SERVICE_NAME'] == 'eSathi') {
                        if ($subStatus[$key + 1]['EXPIRY_DT'] == $val['EXPIRY_DT'] && $subStatus[$key + 1]['SERVICE_NAME'] == 'e-Classifieds') {
                            $toDeleteKeys[] = $key + 1;
                        }
                    }
                    if ($val['SERVICE_NAME'] == 'e-Classifieds') {
                        $toDeleteKeys[] = $key;
                    }
                }

                foreach ($toDeleteKeys as $key => $val) {
                    if (isset($subStatus[$val])) {
                        unset($subStatus[$val]);
                    }
                }
            }
            if (is_array($subStatus)) {
                $subStatus = array_values($subStatus);
                if($actualService != "" && count($subStatus) > 0){
                    $actualServiceArr = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $actualService);
                    if(strpos($actualServiceArr[0], 'L') != false){
                        $actualServiceArr[0] = substr($actualServiceArr[0],0,-1);
                        $actualServiceArr[1] = 'L';
                    }
                    if($actualServiceArr[0]!= "" && $subStatus[0]['SERVICEID_WITHOUT_DURATION'] != $actualServiceArr[0]){
                        $subStatus[0]['SERVICEID_WITHOUT_DURATION'] = $actualServiceArr[0];
                        if($subStatus[0]['SERVICE_DURATION'] == "Unlimited"){
                            $subStatus[0]['ORIG_SERVICEID'] = $subStatus[0]['SERVICEID_WITHOUT_DURATION'].'L';
                        }
                        else{
                            $subStatus[0]['ORIG_SERVICEID'] = $subStatus[0]['SERVICEID_WITHOUT_DURATION'].$subStatus[0]['SERVICE_DURATION'];
                        }
                    }
                }
            }
            $memCacheObject->set($profileid . '_MEM_SUBSTATUS_ARRAY', serialize($subStatus), 1800);
        }
        
        return $subStatus;
    }

    public function getMembershipDurationsAndPrices($userObj, $discountType = "", $displayPage = null, $device = 'desktop',$ignoreShowOnlineCheck = false,$apiObj="",$upgradeMem="NA")
    {
        $allMainMem = $this->fetchMembershipDetails("MAIN", $userObj, $device,$ignoreShowOnlineCheck);
        
        //ankita: code removed to hide P1
        /*if ($displayPage == 1) {
            if (isset($allMainMem['P']['P1'])) {
                unset($allMainMem['P']['P1']);
            }
        }*/
        if (strpos(discountType::LIGHTNING_DEAL_DISCOUNT, $discountType) !== false && strpos(",", $discountType) === false) {
            if(is_array($this->lightningDealDiscount) && $this->lightningDealDiscount["DISCOUNT"]){
                $lightningDealDiscount = $this->lightningDealDiscount["DISCOUNT"];
            }
            else{
                $lightningDealDiscount = $this->memObj->getLightningDealDiscount($user->getProfileid(),$device);
            }
            if(empty($lightningDealDiscount)){
                $lightningDealDiscount = 0;
            }
        }
        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false && strpos(",", $discountType) === false) {
            $discountArr = $this->getSpecialDiscountForAllDurations($userObj->getProfileid());
        }
        
        foreach ($allMainMem as $mainMem => $subMem) {
            $discount = $discountArr[$mainMem];
            if ($userObj->profileid != '') {
                foreach ($subMem as $key => $value) {
                    $mem_duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
                    if (strpos($mem_duration[0], "L")) {
                        $mem_duration = "L";
                    } else {
                        $mem_duration = $mem_duration[1];
                    }
                    if(strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false){
                        $allMainMem[$mainMem][$key]['SPECIAL_DISCOUNT_PRICE'] = round(($allMainMem[$mainMem][$key]['PRICE'] - ceil($allMainMem[$mainMem][$key]['PRICE'] * $discount[$mem_duration]) / 100), 2);
                    }
                    if(strpos(discountType::LIGHTNING_DEAL_DISCOUNT, $discountType) !== false){
                        $allMainMem[$mainMem][$key]['LIGHTNING_DEAL_DISCOUNT_PRICE'] = round(($allMainMem[$mainMem][$key]['PRICE'] - ceil($allMainMem[$mainMem][$key]['PRICE'] * $lightningDealDiscount) / 100), 2);
                    }
                }
            }
        }
        
        $allMainMem  = $this->getOfferPrice($allMainMem, $userObj, $discountType, $device,$apiObj,$upgradeMem);
        $minPriceArr = $this->fetchLowestActivePrices($userObj, $allMainMem, $device);

        return array(
            $allMainMem,
            $minPriceArr
        );
    }

    public function setUpgradableMemberships($currentServiceId=""){
        $memID     = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $currentServiceId);
        
        if(strpos($memID[0], 'L')!=false){
            $memID[0] = substr($memID[0],0,-1);
            $memID[1] = 'L';
        }
        if($memID != "" && is_array($memID) && in_array($memID[0], VariableParams::$memUpgradeConfig["excludeMainMembershipUpgrade"]) == false){
            switch($memID[0]){
                case "P":
                        $upgradeMem = "C";
                        break;
                case "C":
                        $upgradeMem = "NCP";
                        break;
                case "NCP":
                        $upgradeMem = "X";
                        break;
            }
        }
        if($upgradeMem){
            return array("upgradeMem"=>$upgradeMem,"upgradeMemDur"=>$memID[1]);
        }
        else{
            return array();
        }
    }

    /*function - getUpgradeMembershipDiscount
     * get discount details for upgrade membership based on user and membership plan
     * @params: $userObj,$upgradeMem="NA",$apiObj=""
     * @return : $discountArr
     */
    public function getUpgradeMembershipDiscount($userObj,$upgradeMem="NA",$apiObj=""){
        $discountArr = array();
        if($upgradeMem == "MAIN" && $userObj->userType == memUserType::UPGRADE_ELIGIBLE){
            if($apiObj != ""){
                $upgradableMemArr = $this->setUpgradableMemberships($apiObj->subStatus[0]['ORIG_SERVICEID']);
            }
            $discountArr = array();
            if(is_array($upgradableMemArr) && count($upgradableMemArr) > 0){
                //fetch current membership id and duration and set discount accordingly 
               
                $lastDiscountPercent = ($apiObj != "" && $apiObj->lastPurchaseDiscount ? $apiObj->lastPurchaseDiscount:0);
                
                if($apiObj!=""){
                    if(!is_array($apiObj->allMainMem)){
                        $allMainMem = $this->fetchMembershipDetails("MAIN", $userObj, $apiObj->device,false);
                    }
                    else{
                        $allMainMem = $apiObj->allMainMem;
                    }
                    $currentMemMRP = $allMainMem[$apiObj->subStatus[0]['SERVICEID_WITHOUT_DURATION']][$apiObj->subStatus[0]['ORIG_SERVICEID']]['PRICE'];
                    $upgradeMemMRP = $allMainMem[$upgradableMemArr['upgradeMem']][$upgradableMemArr['upgradeMem'].$upgradableMemArr['upgradeMemDur']]['PRICE'];
                }
                
                if($currentMemMRP && $currentMemMRP > 0 && $upgradeMemMRP && $upgradeMemMRP > 0 && $upgradeMemMRP >= $currentMemMRP){
                    //var_dump($currentMemMRP."----".$upgradeMemMRP);
                    //var_dump($lastDiscountPercent);
                    $upsellMRP = round((((1-VariableParams::$memUpgradeConfig["upgradeMainMemAdditionalPercent"]) * (100 - $lastDiscountPercent) * ($upgradeMemMRP - $currentMemMRP))/100));
                    //$upgradeTotalDiscount = round((($upgradeMemMRP-$upsellMRP) * 100 /$upgradeMemMRP),2);
                }
                //$upgradeTotalDiscount = round(100 - ((100 - VariableParams::$memUpgradeConfig["upgradeMainMemAdditionalPercent"])*(100-$lastDiscountPercent))/100,2);
               
                if($upgradeMemMRP > 0 && $upsellMRP <= 0 && $upsellMRP >= $upgradeMemMRP && JsConstants::$whichMachine == 'prod'){
                    CRMAlertManager::sendMailAlert("Wrong upsellMRP calculated=".$upsellMRP." for profileid=".$userObj->getProfileid()." at machine: ".JsConstants::$whichMachine." with url-".JsConstants::$siteUrl);
                }
                if($upsellMRP > 0){

                    $upsellMRP = ($upsellMRP < (0.3*($upgradeMemMRP - $currentMemMRP)))?(0.3*($upgradeMemMRP - $currentMemMRP)):$upsellMRP;
                    $discountArr[$upgradableMemArr["upgradeMem"].$upgradableMemArr["upgradeMemDur"]] = array("discountedUpsellMRP"=>$upsellMRP,"actualUpsellMRP"=>$upgradeMemMRP-$currentMemMRP);
                }
            }
        }
        return $discountArr;
    }
    
    public function getAllVASData($userObj, $device = 'desktop')
    {
        $addonInfo = $this->fetchMembershipDetails("ADDON", $userObj, $device);
        return $addonInfo;
    }

    public function getServiceName($serviceid)
    {
        $serviceid     = str_replace('P1188', 'PL', $serviceid);
        $serviceid     = str_replace('C1188', 'CL', $serviceid);
        $serviceid     = str_replace('A0.5', 'A2W', $serviceid);
        $serviceid     = str_replace('B0.5', 'B2W', $serviceid);
        $serviceid     = str_replace('P0.5', 'P2W', $serviceid);
        $serviceid     = str_replace('C0.5', 'C2W', $serviceid);
        $serviceid     = str_replace('P1.5', 'P6W', $serviceid);
        $serviceid     = str_replace('C1.5', 'C6W', $serviceid);
        $serviceid     = str_replace('P0.07', 'P1W', $serviceid);
        $serviceid     = str_replace('C0.07', 'C1W', $serviceid);
        $serviceid_arr = @explode(",", $serviceid);
        $serviceid_str = @implode("','", $serviceid_arr);

        $serviceObj    = new billing_SERVICES();
        $serviceid_str = "'" . $serviceid_str . "'";
        $servicesArr   = $serviceObj->fetchAllServiceDetails($serviceid_str);
        $service_name  = array();
        if (is_array($servicesArr)) {
            foreach ($servicesArr as $key => $val) {
                $id                        = $val["SERVICEID"];
                $service_name[$id]["NAME"] = $val["NAME"];
            }
        }
        unset($serviceObj);
        return $service_name;
    }

    public function OfferReverseMapping($offered_serviceid)
    {
        if ($offered_serviceid) {
            $lookupObj = new billing_FESTIVE_OFFER_LOOKUP();
            $serviceid = $lookupObj->fetchServiceId($offered_serviceid);
            if (!$serviceid) {
                $serviceid = $offered_serviceid;
            }

        }
        return $serviceid;
    }

    public function getMobileDisplayServiceArray($arr, $id, $orderID, $profileid, $entryDt, $expiryDt)
    {

        foreach ($arr as $key => $val) {
            $temp   = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
            $memID  = $temp[0];
            $memDur = $temp[1];

            if (strpos($memID, "L")) {
                $memID  = substr($memID, 0, -1);
                $memDur = 'Unlimited';
            } else {

                if ($this->getFestiveFlag()) {
                    $temp2 = $this->OfferReverseMapping($key);
                } else {
                    $temp2 = $key;
                }
                $temp2   = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $temp2);
                $memDur2 = $temp2[1];
                if ($memDur2 != $memDur) {
                    $extra_months = $memDur - $memDur2;
                    $memDur       = $memDur2;
                }
            }

            if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                $serviceObj  = new billing_SERVICES();
                $servicesArr = $serviceObj->fetchAllServiceDetails($memID . $memDur);
                foreach ($servicesArr as $key => $row_services) {
                    $name                                   = explode("-", $row_services["NAME"]);
                    $vas[$row_services["SERVICEID"]]['KEY'] = $memID;
                    if ($name[0] == "Matri") {
                        $vas[$row_services["SERVICEID"]]['NAME'] = "Matri-Profile";
                    } else {
                        $vas[$row_services["SERVICEID"]]['NAME'] = $name[0];
                    }
                    $vas[$row_services["SERVICEID"]]['DURATION'] = $memDur;
                }
                unset($serviceObj);
            } else {
                $serviceObj = new billing_SERVICES();
                if ($memDur == "Unlimited") {
                    $keyDur = "L";
                } else {
                    $keyDur = $memDur;
                }
                $servicesArr = $serviceObj->fetchAllServiceDetails($memID . $keyDur);
                foreach ($servicesArr as $key => $row_services) {
                    $entryDt     = date("Y-m-d", strtotime($entryDt));
                    $esathiCheck = $this->checkForESathiService($profileid, $entryDt, $expiryDt, $row_services['SERVICEID']);
                    if (substr($esathiCheck, 0, 3) == "ESP" || substr($esathiCheck, 0, 3) == "NCP") {
                        if (strpos($esathiCheck, "L")) {
                            $memID  = substr($esathiCheck, 0, -1);
                            $memDur = 'Unlimited';
                        } else {
                            $tp     = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $esathiCheck);
                            $memID  = $tp[0];
                            $memDur = $tp[1];
                        }
                    }
                    $name                                         = $this->getUserServiceName($memID);
                    $main[$row_services["SERVICEID"]]['KEY']      = $memID;
                    $main[$row_services["SERVICEID"]]['NAME']     = $name;
                    $main[$row_services["SERVICEID"]]['DURATION'] = $memDur;
                    if ($extra_months) {
                        $main[$row_services["SERVICEID"]]['EXTRA'] = $extra_months;
                    }
                }
                unset($serviceObj);
            }
        }
        return array(
            $vas,
            $main,
        );
    }

    public function trackMembershipProgress($userObj, $source = '', $tab = '', $pgNo = '', $device = '', $userAgent = '', $allMemberships = '', $mainMembership = '', $vasImpression = '', $discount = 0, $total = 0, $paymentTab = '', $trackType = '', $specialActive = '', $discPerc = '', $discountActive = '',$upgradeMem="NA",$apiObj="")
    {
        $profileid = $userObj->getProfileid();
        $this->addHitsTracking($profileid, $pgNo, $tab, $device, $userAgent);
        if ($source == '103' || $source == '203' || $source == '303' || $source == '503' || $source == '504' || $source == '603' || $source == '604' || $source == '3') {
            $profileid = $userObj->getProfileid();
            $currency  = $userObj->getCurrency();
            $trackType = "F";
            if ($device != 'discount_link') {
                list($totalN, $discN) = $this->setTrackingPriceAndDiscount($userObj, $profileid, $mainMembership, $allMemberships, $currency, $device,null,null,null,null,false,$upgradeMem,$apiObj);
                //error_log("tracking totalN =".$totalN." ,discN=".$discN);
                $totalN               = round($totalN, 2);
                $discN                = round($discN, 2);
            } else {
                $totalN = round($total, 2);
                $discN  = round($discount, 2);
            }
            $this->trackMembership($userObj, $source, $mainMembership, $allMemberships, $vasImpression, $discN, $totalN, $paymentTab, $trackType, $device);
        } else {
            $this->trackMembership($userObj, $source, $mainMembership, $allMemberships, $vasImpression, $discount, $total, $paymentTab, $trackType, $device);
        }
    }

    public function setTrackingPriceAndDiscount($userObj, $profileid, $mainMembership, $allMemberships, $currency, $device = 'desktop', $couponCode, $backendRedirect = null, $profileCheckSum = null, $reqid = null, $previousCheck = false,$upgradeMem="NA",$apiObj="")
    {
        $servObj = new billing_SERVICES();
        // Get vasImpression from diff of allMemberships and mainMembership
        if (empty($mainMembership)) {
            $vasImpression = implode(",", array_diff(explode(",", $allMemberships), array()));
        } else {
            $vasImpression = implode(",", array_diff(explode(",", $allMemberships), explode(",", $mainMembership)));
        }
        if (isset($mainMembership) && !empty($mainMembership)) {
            $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $mainMembership);
            $mainMem = $tempMem[0];
            if (strpos($mainMem, "L")) {
                $mainMemDur = "L";
                $mainMem    = substr($mainMem, 0, -1);
            } else {
                $mainMemDur = $tempMem[1];
            }
            list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code,$upgradePercentArr,$upgradeActive,$lightningDealActive,$lightning_deal_discount_expiry,$lightningDealDiscountPercent) = $this->getUserDiscountDetailsArray($userObj, "L",3,$apiObj,$upgradeMem);
           
            $expThreshold = (strtotime(date("Y-m-d", time())) - 86400); // Previous Day
            if ($specialActive == 1 || $discountActive == 1 || $renewalActive == 1 || $fest == 1 || $lightningDealActive == 1) {
                if($lightningDealActive == 1){
                    if(!empty($lightningDealDiscountPercent)){
                        $discPerc = $lightningDealDiscountPercent;
                    }
                    else{
                        $discPercArr = $this->memObj->getLightningDealDiscount($profileid,$device);
                        if(is_array($discPercArr)) 
                            $discPerc = $discPercArr["DISCOUNT"];
                        else
                            $discPerc = 0;
                    }

                }
                else if ($userObj->userType == 4 || $userObj->userType == 6) {
                    $discPerc = $renewalPercent;
                } else if ($specialActive == 1) {
                    $vdDiscArr = $this->getSpecialDiscountForAllDurations($profileid);
                    $vdDisc    = $vdDiscArr[$mainMem];
                    if (in_array($mainMemDur, array_keys($vdDisc))) {
                        $discPerc = $vdDisc[$mainMemDur];
                    } else if ($fest == 1) {
                        $discPerc = $vdDisc[$mainMemDur];
                    } else {
                        $discPerc = 0;
                    }
                } else if ($discountActive == 1) {
                    $discPerc = $this->getDiscountOffer($mainMembership);
                } else if ($fest == 1 && $mainMem != "X") {
                    $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
                    $perc           = $festOffrLookup->getPercDiscountOnService($mainMembership);
                    unset($festOffrLookup);
                    if ($renewalActive == 1) {
                        $discPerc = $renewalPercent;
                    } else {
                        $discPerc = $perc;
                    }
                }
                if ($fest == 1 && $mainMem == "X" && $specialActive != 1 && $renewalActive != 1 && $lightningDealActive != 1) {
                    $discPerc = 0;
                }
            }
            $renPrevFlag = false;
            // Check for previous Day discount, if yes, get max of discount
            if ($previousCheck == true) {
                $renDiscLog = new billing_RENEWAL_DISCOUNT_LOG();
                $renDisc = $renDiscLog->fetchRenewalDiscountForProfileAndDate($profileid, date("Y-m-d", $expThreshold));
                $discPerc = max($discPerc, $renDisc);
                if ($renDisc) {
                    $renPrevFlag = true;
                }
            }
            // Force VD Check  
            if ($previousCheck == true && !$renPrevFlag) {
                $vdDiscArr = $this->getSpecialDiscountForAllDurationsPreviously($profileid);
                $lastExp = $vdDiscArr['EDATE'];
                if (strtotime($lastExp) >= $expThreshold) {
                    $vdDisc = $vdDiscArr['DISCOUNT'][$mainMem];
                    if (in_array($mainMemDur, array_keys($vdDisc))) {
                        $discPerc1 = $vdDisc[$mainMemDur];
                    } else if ($fest == 1) {
                        $discPerc1 = $vdDisc[$mainMemDur];
                    } else {
                        $discPerc1 = 0;
                    }
                }
                $discPerc = max($discPerc, $discPerc1);
            }
            $mems = explode(",", $allMemberships);
        } else if (isset($vasImpression) && !empty($vasImpression)) {
            $allMemberships = $vasImpression;
            $mems           = explode(",", $allMemberships);
        }

        if (!empty($backendRedirect) && $backendRedirect == 1) {
            $profileCheckSumArray = explode("i", $profileCheckSum);
            $profileid            = $profileCheckSumArray[1];
            $idCheckSum           = $reqid;
            $idCheckSumArray      = explode("i", $idCheckSum);
            $idBackend            = $idCheckSumArray[1];
            if (md5($idBackend) == $idCheckSumArray[0]) {
                list($allMemberships, $discountBackend, $profileid) = $this->handleBackendCase($idBackend, $profileid);
            }
            $discPerc = $discountBackend;
            if ($allMemberships) {
                $memArray = explode(",", $allMemberships);
                for ($p = 0; $p < count($memArray); $p++) {
                    if (strpos($memArray[$p], "main") !== false) {
                        $subMem  = substr($memArray[$p], 4);
                        $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $subMem);
                        $mainMem = $tempMem[0];
                        if (strpos($mainMem, "L")) {
                            $mainMemDur = "L";
                            $mainMem    = substr($mainMem, 0, -1);
                        } else {
                            $mainMemDur = $tempMem[1];
                        }
                    }
                }
            }
            if ($mainMem == "E") {
                $mainMem = "ESP";
            }
            $remainingServices = array_splice($memArray, 1);
            $mainMembership    = $mainMem . $mainMemDur;
            $vasImpression     = implode(",", $remainingServices);
            foreach ($remainingServices as $key => $val) {
                if (empty($val) || $val == null) {
                    $emptyRemainingServicesFlag = 1;
                }
            }
            if (!$emptyRemainingServicesFlag) {
                $allMemberships = $mainMem . $mainMemDur . "," . implode(",", $remainingServices);
            } else {
                $allMemberships = $mainMem . $mainMemDur;
            }
            $mems = explode(',', $allMemberships);
        }
        $allMemberships = $allMemberships;

        if ($currency == 'RS') {
            $price             = 0;
            $totalCartPrice    = 0;
            $discountCartPrice = 0;
            if (strpos($mainMembership, 'ESP') === false) {
                if (is_array($mems)) {
                    $servDetails = $servObj->fetchServiceDetailForRupeesTrxn($mems, $device);
                    foreach ($mems as $key => $val) {
                        $price = $servDetails[$val]['PRICE'];
                        if ($discountActive == 1 && $backendRedirect != 1) {
                            if ($this->getDiscountOffer($mainMembership)) {
                                $discPerc = $this->getDiscountOffer($val);
                            }
                        }
                        if (!empty($discPerc) && $discPerc != 0) {
                            $discountCartPrice += round($price * ($discPerc / 100), 2);
                        } else {
                            $discountCartPrice += 0;
                        }
                        $totalCartPrice += $price - round($price * ($discPerc / 100), 2);
                    }
                }
            } else {
                $servDetails = $servObj->fetchServiceDetailForRupeesTrxn($mainMembership, $device);
                $price       = $servDetails['PRICE'];
                if ($discountActive == 1 && $backendRedirect != 1) {
                    if ($this->getDiscountOffer($mainMembership)) {
                        $discPerc = $this->getDiscountOffer($mainMembership);
                    }
                }
                if (!empty($discPerc) && $discPerc != 0) {
                    $discountCartPrice += round($price * ($discPerc / 100), 2);
                } else {
                    $discountCartPrice += 0;
                }
                $totalCartPrice += $price - round($price * ($discPerc / 100), 2);
            }
        } else {
            $price             = 0;
            $totalCartPrice    = 0;
            $discountCartPrice = 0;
            if (strpos($mainMembership, 'ESP') === false) {
                $servDetails = $servObj->fetchServiceDetailForDollarTrxn($mems, $device);
                foreach ($mems as $key => $val) {
                    $price = $servDetails[$val]['PRICE'];
                    if ($discountActive == 1 && $backendRedirect != 1) {
                        if ($this->getDiscountOffer($mainMembership)) {
                            $discPerc = $this->getDiscountOffer($val);
                        }
                    }
                    if (!empty($discPerc) && $discPerc != 0) {
                        $discountCartPrice += round($price * ($discPerc / 100), 2);
                    } else {
                        $discountCartPrice += 0;
                    }
                    $totalCartPrice += $price - round($price * ($discPerc / 100), 2);
                }
            } else {
                $servDetails = $servObj->fetchServiceDetailForDollarTrxn($mainMembership, $device);
                $price       = $servDetails['PRICE'];
                if ($discountActive == 1 && $backendRedirect != 1) {
                    if ($this->getDiscountOffer($mainMembership)) {
                        $discPerc = $this->getDiscountOffer($mainMembership);
                    }
                }
                if (!empty($discPerc) && $discPerc != 0) {
                    $discountCartPrice += round($price * ($discPerc / 100), 2);
                } else {
                    $discountCartPrice += 0;
                }
                $totalCartPrice += $price - round($price * ($discPerc / 100), 2);
            }
        }
        //add additional discount for upgrade membership if applicable
        if((empty($backendRedirect) || $backendRedirect != 1) && $upgradeActive == '1' && count($upgradePercentArr) > 0 && $upgradePercentArr[$mainMembership]){
            //$additionalUpgradeDiscount = round($totalCartPrice * ($upgradePercentArr[$mainMembership] / 100) , 2);
            //$temp = $totalCartPrice;
            $totalCartPrice = $upgradePercentArr[$mainMembership]["discountedUpsellMRP"];
            $discountCartPrice+= $upgradePercentArr[$mainMembership]["actualUpsellMRP"] - $upgradePercentArr[$mainMembership]["discountedUpsellMRP"];
            /*if(is_array($apiObj->purchaseDetArr) && $apiObj->purchaseDetArr['NET_AMOUNT']){
                $discountCartPrice = $discountCartPrice - $apiObj->purchaseDetArr['NET_AMOUNT'];
                error_log("purchaseDetArr in setTrackingPriceAndDiscount-".$apiObj->purchaseDetArr['NET_AMOUNT']);
            }*/
        }

        if (!empty($couponCode)) {
            $validation = $this->validateCouponCode($mainMembership, $couponCode);
            if (is_numeric($validation) && !empty($validation) && $validation > 0) {
                $additionalDiscount = round($totalCartPrice * ($validation / 100), 2);
                $totalCartPrice -= $additionalDiscount;
                $discountCartPrice += $additionalDiscount;
            }
        }
       
        return array(
            $totalCartPrice,
            $discountCartPrice,
        );
    }

    // Get OCB Message
    public function getOCBTextMessage($profileid, $discountType, $discPerc = '', $expiry_date = '', $festiveActive = '')
    {
        $vdodObj = new VariableDiscount();
        switch ($discountType) {
            case 'VD':
                $discountVD = $vdodObj->getDiscountDetails($profileid);
                $maxVDDisc  = $discountVD['MAX_DISCOUNT'];
                $flat       = $discountVD['FLAT_DISCOUNT'];
                $discPerc   = $maxVDDisc;
                if ($flat) {
                    $discountDisplayText = 'flat';
                } else {
                    $discountDisplayText = 'upto';
                }
                $top = "Get " . $discountDisplayText . " " . $discPerc . "% OFF";
                if ($festiveActive) {
                    $bottom = "or extra months if you upgrade before <strong>" . date("d M", strtotime($expiry_date)) . "</strong> !";
                } else {
                    $bottom = "if you upgrade your membership before <strong>" . date("d M", strtotime($expiry_date)) . "</strong> !";
                }
                break;
            case 'CASH':
                $discountDisplayText = $vdodObj->getCashDiscountDispText($profileid, 'small');
                $top                 = "Get " . $discountDisplayText . " " . $discPerc . "% OFF";
                $bottom              = "if you upgrade your membership before <strong>" . date("d M", strtotime($expiry_date)) . "</strong> !";
                break;
            default:
                break;
        }
        $messageArr = array('top' => $top, 'bottom' => $bottom, 'discountText' => $discountDisplayText);
        return $messageArr;
    }

    public function fetchMembershipMessage($request, $apiAppVersion = 17)
    {
        $request->setParameter('getMembershipMessage', 1);
        $request->setParameter('INTERNAL', 1);
        $request->setParameter('API_APP_VERSION', $apiAppVersion);
        $memCacheObject = JsMemcache::getInstance();
        $loginData      = $request->getAttribute("loginData");
        if ($loginData['PROFILEID']) {
            $profileid = $loginData['PROFILEID'];
            if ($memCacheObject->get($profileid . "_MEM_OCB_MESSAGE_API" . $apiAppVersion)) {
                $output = unserialize($memCacheObject->get($profileid . "_MEM_OCB_MESSAGE_API" . $apiAppVersion));
                $output = json_encode($output);
            }
        }
        if (!$output) {
            ob_start();
            $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');
            $output = ob_get_contents();
            ob_end_clean();
        }
        $data = json_decode($output, true);
        $data = $this->modifyResponseForLightningDeal($data);
        return $data;
    }

    public function fetchHamburgerMessage($request)
    {
        $request->setParameter('getMembershipMessage', 0);
        $request->setParameter('getHamburgerMessage', 1);
        $request->setParameter('INTERNAL', 1);
        $memCacheObject = JsMemcache::getInstance();
        $loginData      = $request->getAttribute("loginData");
        if ($loginData['PROFILEID']) {
            $profileid = $loginData['PROFILEID'];
            if ($memCacheObject->get($profileid . "_MEM_HAMB_MESSAGE")) {
                $output = unserialize($memCacheObject->get($profileid . "_MEM_HAMB_MESSAGE"));
                $output = json_encode($output);
            }
        }
        if (!$output) 
        {
            ob_start();
            $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');
            $output = ob_get_contents();
            ob_end_clean();
        }
        $data = json_decode($output, true);
        return $data;
    }

    public function generateCouponCode()
    {
        $character_set_array   = array();
        $character_set_array[] = array(
            'count'      => 3,
            'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        );
        $character_set_array[] = array(
            'count'      => 3,
            'characters' => '0123456789',
        );
        $temp_array = array();
        foreach ($character_set_array as $character_set) {
            for ($i = 0; $i < $character_set['count']; $i++) {
                $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
            }
        }
        shuffle($temp_array);
        return "JS" . implode('', $temp_array);
    }

    public function validateCouponCode($serviceid, $couponCode)
    {
        $dt         = date("Y-m-d");
        $coupOffObj = new billing_COUPON_OFFER();
        $couponRow  = $coupOffObj->validateCoupon($couponCode);
        unset($coupOffObj);
        if (is_array($couponRow) && !empty($couponRow)) {
            if (strtotime($couponRow['START_DT']) > time() || strtotime($couponRow['END_DT']) < time()) {
                $output = "INVDUR";
            } else if ($couponRow['USER_LIMIT'] == $couponRow['USED_COUNT']) {
                $output = "LIMEXP";
            } else {
                $coupDiscLookupObj = new billing_COUPON_DISCOUNT_LOOKUP();
                $discVal           = $coupDiscLookupObj->getDiscount($couponRow['ID'], $serviceid);
                if ($discVal) {
                    $output = $discVal;
                } else {
                    $output = 0;
                }
                unset($coupDiscLookupObj);
            }
        } else {
            $output = 0;
        }
        return $output;
    }

    public function checkIfUserIsPaidAndNotWithinRenew($profileid, $userType = "",$source="")
    {
        if (empty($userType) && !empty($profileid)) {
            $userObj = new memUser($profileid);
            $userObj->setMemStatus();
            $userType = $userObj->userType;
        }
        if (!empty($profileid)) {
            $jprofileObj     = new JPROFILE('newjs_slave');
            $profileDetails  = $jprofileObj->get($profileid, "PROFILEID", "ACTIVATED");
            $activatedStatus = $profileDetails['ACTIVATED'];
        } else {
            $profileObj      = LoggedInProfile::getInstance();
            $activatedStatus = $profileObj->getACTIVATED();
        }
        if ($userType != 5 && (($source=="hamburger" && $activatedStatus != 'D') || $activatedStatus == 'Y')){
            return 1;
        } else {
            return 0;
        }
    }

    public function getMaxVdDiscount($discount)
    {
        $discountNew = $this->memObj->getMaxVdDiscount($discount);
        return $discountNew;
    }

    public function isRenewable($profileid)
    {
        return $this->memObj->isRenewable($profileid);
    }

    public function addVariableDiscountProfiles()
    {
        $vdDurationObj         = new billing_VARIABLE_DISCOUNT_DURATION("newjs_masterRep");
        $vdPoolTechObj         = new billing_VARIABLE_DISCOUNT_POOL_TECH("newjs_masterRep");
        $vdObj                 = new billing_VARIABLE_DISCOUNT();
        $vdOfferDurationObj    = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
        $vdDurationPoolTechObj = new billing_VARIABLE_DISCOUNT_DURATION_POOL_TECH("newjs_masterRep");
        $jprofileObj           = new JPROFILE('newjs_slave');
        $vdProfilesArr         = array();

        $vdDatesArr   = $vdDurationObj->getVdOfferDates();
        $startDate    = $vdDatesArr['SDATE'];
        $endDate      = $vdDatesArr['EDATE'];
        $activationDt = $vdDatesArr['ENTRY_DT'];
        $todayDate    = date("Y-m-d");

        //if(strtotime($endDate) >= strtotime($todayDate)){
        if (strtotime($startDate) == strtotime($todayDate)) {
            $vdProfilesArr = $vdPoolTechObj->fetchVdPoolTechProfiles();
            foreach ($vdProfilesArr as $key => $profileid) {

                // paid condition check
                $subscription = $jprofileObj->getProfileSubscription($profileid);
                if ((strstr($subscription, "F") != "") || (strstr($subscription, "D") != "")) {
                    continue;
                }

                // get discount details
                $discountArr = $vdDurationPoolTechObj->getDiscountArr($profileid);
                if (is_array($discountArr)) {
                    $discount = max($discountArr);
                }

                unset($discountArr);
                // add discount
                if ($discount) {
                    $vdObj->addVDProfile($profileid, $discount, $startDate, $endDate, $activationDt);
                    $vdOfferDurationObj->addVdOfferDuration($profileid);
                }
            }
        }
    }

    public function calculateVariableRenewalDiscount($profileid)
    {

        // New Scoring logic for Renewal discount Start
        $score    = 0;
        $discount = 0;
        $score    = new incentive_MAIN_ADMIN_POOL('newjs_slave');
        $score    = $score->getAnalyticScore($profileid);

        $renewalDiscountLookup = new billing_RENEWAL_DISCOUNT_LOOKUP('newjs_slave');
        $discount              = $renewalDiscountLookup->getDiscountForScore($score);
        if (!$discount) {
            $discount = userDiscounts::RENEWAL;
        }

        return $discount;
        // New Scoring logic End
    }

    public function getVariableRenewalDiscount($profileid, $notApplicable = '')
    {
        $rdObj = new billing_RENEWAL_DISCOUNT();
        $res   = $rdObj->getDiscount($profileid);
        
        if ($res['DISCOUNT']) {
            return $res['DISCOUNT'];
        } else {
            if ($notApplicable) {
                return;
            } else {
                return userDiscounts::RENEWAL;
            }

        }
    }

    public function sendEmailForCallback($subject, $msgBody, $to = '')
    {
        if (!$to) {
            $to = "premium.js@jeevansathi.com,kanika.tanwar@jeevansathi.com,princy.gulati@jeevansathi.com";
        }

        $from = "js-sums@jeevansathi.com";
        if (JsConstants::$whichMachine == 'prod') {
            SendMail::send_email($to, $msgBody, $subject, $from);
        }
    }

    public function oldJSMSsetSubcriptionExp($userObj, $memHandlerObj, $obj)
    {
        $subStatus = $memHandlerObj->getSubStatus($userObj->getProfileid());
        if ($userObj->userType != memUserType::FREE) {
            $userObj->subStatus = $subStatus;
        }
        if (is_array($subStatus)) {
            foreach ($subStatus as $key => $value) {
                if ($value['LINK'] != 'N') {
                    $latestMainExp = $key;
                    break;
                }
            }
            foreach ($subStatus as $key => $value) {
                if ($value['EXPIRY_DT'] == '') {
                    $subStatus[$key]['EXPIRY_DT'] = $subStatus[$latestMainExp]['EXPIRY_DT'];
                }
            }
        }
        $obj->subStatus    = $subStatus;
        $obj->subStatusNew = $memHandlerObj->getSubscriptionStatusArray($userObj);
    }

    public function getAllCount($profileid)
    {
        $main               = 0;
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $profilesDetail     = $billingServStatObj->getAllCountForProfileid($profileid);
        foreach ($profilesDetail as $key => $row) {
            $serve = $row['SERVEFOR'];
            if ($row["ACTIVE"] == "Y") {
                $avail = $row['TOTAL_COUNT'] - $row['USED_COUNT'];
                $arr[$serve]['AVAIL'] += $avail;
                $arr[$serve]['TOTAL'] += $row['TOTAL_COUNT'];
                $arr[$serve]['USED'] += $row['USED_COUNT'];
            } elseif ($row["ACTIVE"] == "E") {
                $arr[$serve]['EXPIRED'] += $row['TOTAL_COUNT'];
            }
        }
        if ($arr) {
            return ($arr['I']);
        } else {
            return 0;
        }

    }

    /*fetch data for membership panel section
     * @param : $profileid
     * @return : $output-array
     */
    public function getMembershipPanelContent($profileid)
    {
        //get currently active service
        $purchasesObj = new BILLING_PURCHASES();
        $memID        = @strtoupper($purchasesObj->getCurrentlyActiveService($profileid));
        unset($purchasesObj);
        $memIDString = preg_replace('/[0-9].*/', '', $memID);

        $benefitVisibilityArr = VariableParams::$newApiPageOneBenefitsVisibility[$memIDString];
        $index                = 0;
        //get benefits
        foreach ($benefitVisibilityArr as $key => $value) {
            if ($value == 1) {
                if ($memIDString == "X") {
                    $benefitMsg[$index] = VariableParams::$newApiPageOneBenefitsJSX[$key];
                } else {
                    $benefitMsg[$index] = VariableParams::$newApiPageOneBenefits[$key];
                }

            }
            ++$index;
        }
        //get heading and subheading
        if ($memIDString == "FREE") {
            $membershipHeading    = "You only have a Free Account.";
            $membershipSubHeading = "Get access to the following benefits of a Premium account";
            $expiryTitle          = "";
        } else {
            $membershipName = VariableParams::$mainMembershipNamesArr[$memIDString];
            if (in_array($membershipName[0], array('e', 'i', 'o', 'a', 'u'))) {
                $membershipHeading = 'You are currently an ' . $membershipName . ' member.';
            } else {
                $membershipHeading = 'You are currently a ' . $membershipName . ' member.';
            }
            $membershipSubHeading = "Benefits of your membership:";

        }
        //get expiry information
        $userObj   = new memUser($profileid);
        $subStatus = $this->getSubscriptionStatusArray($userObj);
        if ($subStatus[0]['EXPIRY_DT'] && $memID != "ESJA") {
            $subscriptionExp = date("Y-m-d", strtotime($subStatus[0]['EXPIRY_DT']));
            $datetime1       = new DateTime($subscriptionExp);
            $datetime2       = new DateTime(date("Y-m-d"));
            $difference      = $datetime1->diff($datetime2);
            if ($difference->y < 1) {
                $expiryTitle = "Your {$membershipName} plan expires on <b>{$subStatus[0]['EXPIRY_DT']}</b>.";
            } else {
                $expiryTitle = "";
            }
            //unlimited membership
        } elseif ($userObj->userType == 4 && $memID != "ESJA") {
            $expiryTitle = "Your {$membershipName} membership has expired."; //expired case
        } else {
            $expiryTitle = "";
        }
        unset($userObj);
        $output = array("heading" => $membershipHeading, "subHeading" => $membershipSubHeading, "benefits" => $benefitMsg, "expiryInfo" => $expiryTitle, "memIDString" => $memIDString);
        return $output;
    }

    public function getRealMembershipName($profileid)
    {
        $memCacheObject = JsMemcache::getInstance();
        if ($memCacheObject->get($profileid . "_MEM_NAME")) {
            $output = unserialize($memCacheObject->get($profileid . "_MEM_NAME"));
            $output = json_encode($output);
            $output = str_replace('"', '', $output);
        } else {
            $purchasesObj = new BILLING_PURCHASES();
            $memID        = @strtoupper($purchasesObj->getCurrentlyActiveService($profileid));
            if (!empty($memID) && $memID != "FREE" && $memID != "ESJA") {
                $activeServiceName = $this->getUserServiceName($memID);
                if (stristr($activeServiceName, "advantage")) {
                    $membershipStatus = 'eAdvantage';
                } elseif (stristr($activeServiceName, "value")) {
                    $membershipStatus = 'eValue';
                } elseif (stristr($activeServiceName, "rishta")) {
                    $membershipStatus = 'eRishta';
                } elseif (stristr($activeServiceName, "sathi")) {
                    $membershipStatus = 'eSathi';
                } elseif (stristr($activeServiceName, "exclusive")) {
                    $membershipStatus = 'JS Exclusive';
                } else {
                    $membershipStatus = 'Free';
                }

            } else {
                $membershipStatus = 'Free';
            }
            $output = $membershipStatus;
            $memCacheObject->set($profileid . '_MEM_NAME', serialize($membershipStatus), 1800);
        }
        return $output;
    }

    /*function to get allocation details of exclusive members
     *@param : assigned flag
     * @return : $allocationDetails
     */
    public function getExclusiveAllocationDetails($assigned = false, $orderBy = "")
    {
        $exclusiveObj      = new billing_EXCLUSIVE_MEMBERS();
        $allocationDetails = $exclusiveObj->getExclusiveMembers("PROFILEID,DATE_FORMAT(BILLING_DT, '%d/%m/%Y %H:%i:%s') AS BILLING_DT,ASSIGNED_TO,BILL_ID", $assigned, $orderBy);
        if (is_array($allocationDetails) && $allocationDetails) {
            $profileIDArr = array_keys($allocationDetails);
            if (is_array($profileIDArr) && $profileIDArr) {
                $whereCondition = array("SUBSCRIPTION" => '%X%', "ACTIVATED" => 'Y');
                //get jprofile details
                $jprofleSlaveObj = new JPROFILE("crm_slave");
                $profileDetails  = $jprofleSlaveObj->getProfileSelectedDetails($profileIDArr, "PROFILEID,USERNAME,EMAIL,PHONE_MOB,AGE,MSTATUS,RELIGION,CASTE,INCOME,GENDER,HEIGHT", $whereCondition);
                unset($jprofleSlaveObj);

                //get names of profiles
                $incentiveObj    = new incentive_NAME_OF_USER("crm_slave");
                $profileNamesArr = $incentiveObj->getName($profileIDArr);
                unset($incentiveObj);

                //get names of agents to whom profiles are allotted
                $mainAdminObj   = new incentive_MAIN_ADMIN("crm_slave");
                $jsadminDetails = $mainAdminObj->getArray(array("PROFILEID" => implode(",", $profileIDArr)), "", "", "ALLOTED_TO AS SALES_PERSON,PROFILEID", "", "PROFILEID");
                unset($mainAdminObj);

                //get billing details of profiles via billid's
                $billIdArr = array_map(function ($arr) {return $arr['BILL_ID'];}, $allocationDetails);
                if (is_array($billIdArr) && $billIdArr) {
                    $billingObj     = new BILLING_SERVICE_STATUS("crm_slave");
                    $billingDetails = $billingObj->fetchServiceDetailsByBillId(array_filter($billIdArr), "PROFILEID,SERVICEID,DATE_FORMAT(EXPIRY_DT, '%d/%m/%Y') AS EXPIRY_DT", "%X%");
                    unset($billingObj);
                }
            }
            foreach ($allocationDetails as $profileid => $value) {
                if ($profileDetails[$profileid]) {
                    $allocationDetails[$profileid] = $this->modifyExclusiveMembersDetails($profileid, $profileDetails[$profileid], $allocationDetails[$profileid], $jsadminDetails[$profileid], $billingDetails[$profileid], $profileNamesArr[$profileid]);
                } else {
                    unset($allocationDetails[$profileid]);
                }

            }
            unset($billingDetails);
            unset($jsadminDetails);
            unset($profileNamesArr);
        }
        return $allocationDetails;
    }

    /*function to reform and merge details of exclusive members
     *@param : $profileid,$profileDetails,$allocationDetails,$jsadminDetails,$billingDetails,$profileName
     * @return : $allocationDetails
     */
    private function modifyExclusiveMembersDetails($profileid, $profileDetails, $allocationDetails, $jsadminDetails = "", $billingDetails = "", $profileName = "")
    {
        //reform profile details format
        $columnsToBeMapped = array("INCOME", "RELIGION", "CASTE", "HEIGHT");
        $profileDetails    = exclusiveMemberList::mapColumnsToActualValues($profileDetails, $columnsToBeMapped);
        unset($columnsToBeMapped);

        //get name of profile
        $profileDetails['PROFILE_NAME'] = $profileName;

        //get dpp matches count for profile
        $loggedInProfileObj = Operator::getInstance();
        $loggedInProfileObj->getDetail($profileid, 'PROFILEID', '*');
        // $dppDetails = SearchCommonFunctions::getMyDppMatches("",$loggedInProfileObj,"","","","","","","","onlyCount");
        // $profileDetails['MATCHES'] = $dppDetails['CNT'];
        unset($dppDetails);
        unset($loggedInProfileObj);

        //reformat billing details(serviceid to service duration)
        if ($billingDetails) {
            $billingDetails = exclusiveMemberList::mapColumnsToActualValues($billingDetails, array("SERVICEID"));
        }

        //merge all details
        if (is_array($billingDetails) && is_array($jsadminDetails)) {
            $allocationDetails = array_merge($allocationDetails, $profileDetails, $billingDetails, $jsadminDetails);
        } else if (is_array($billingDetails)) {
            $allocationDetails = array_merge($allocationDetails, $profileDetails, $billingDetails);
        } else if (is_array($jsadminDetails[$profileid])) {
            $allocationDetails = array_merge($allocationDetails, $profileDetails, $jsadminDetails);
        } else {
            $allocationDetails = array_merge($allocationDetails, $profileDetails);
        }

        return $allocationDetails;

    }
    public function showVerificationWidgetOrNot()
    {
        $loginProfile = LoggedInProfile::getInstance();
        if ($loginProfile) {
            $profileid            = $loginProfile->getPROFILEID();
            $cityRes              = $loginProfile->getCITY_RES();
            $incHistObj           = new incentive_HISTORY('newjs_slave');
            $purchasesObj         = new BILLING_PURCHASES('newjs_slave');
            $incFieldSalesCityObj = new incentive_FIELD_SALES_CITY('newjs_slave');
            $dispositionDone      = $incHistObj->get($profileid, 'PROFILEID', "DISPOSITION = 'FVD' AND PROFILEID=$profileid");
            $activeServices       = $purchasesObj->getCurrentlyActiveService($profileid);
            $checkFieldSalesCity  = $incFieldSalesCityObj->checkFieldSalesCityCodeExists($cityRes);
            $scheduleVisitCount   = 0;
            if (!$dispositionDone && $activeServices == "FREE" && $checkFieldSalesCity) {
                $fieldSalesWidgetObj   = new incentive_FIELD_SALES_WIDGET();
                $scheduleVisitCount    = $fieldSalesWidgetObj->checkIfProfileidExists($profileid);
                $schedule_visit_widget = 1;
            } else {
                $schedule_visit_widget = 0;
            }

            return ($schedule_visit_widget && !($scheduleVisitCount) ? '1' : '0');
        } else {
            return false;
        }
    }

    public function sendInstantSMS($profileid, $phNo, $smsMessage)
    {
        include_once JsConstants::$docRoot . "/classes/SmsVendorFactory.class.php";
        $smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
        if ($profileid && $phNo && $smsMessage) {
            $xmlData1 = $xmlData1 . $smsVendorObj->generateXml($profileid, $phNo, $smsMessage);
            if ($xmlData1) {
                $smsVendorObj->send($xmlData1, "transaction");
            }
            unset($xmlData1);
        }
        unset($smsVendorObj);
    }

    /*@desc:Get detail regarding currently active service
     * @input: profileid, serviceid
     * @output: PROFILEID, SERVICEID, ACTIVATED_ON, EXPIRY_DT, DURATION_MONTHS
     */
    public function getActiveSubscriptionDetail($profileid, $serviceid)
    {
        $serviceObj                 = new BILLING_SERVICE_STATUS("newjs_slave");
        $details                    = $serviceObj->getActiveSubscriptionDetail($profileid, $serviceid);
        $duration                   = substr($details["SERVICEID"], 1);
        $details["DURATION_MONTHS"] = $duration;
        return $details;
    }

    public function trackAndGetOpenedCount($profileid)
    {
        if ($profileid && is_numeric($profileid)) {
            $memCacheObject = JsMemcache::getInstance();
            if ($memCacheObject->get($profileid . "_MEM_PAGE_VISITS") >= 5) {
                return $memCacheObject->get($profileid . "_MEM_PAGE_VISITS");
            } else {
                $trackMemVisitObj = new billing_TRACK_MEMBERSHIP_VISITS();
                $currentCount     = $trackMemVisitObj->getCount($profileid);
                if (empty($currentCount)) {
                    $trackMemVisitObj->insertDetails($profileid, 1);
                    return 1;
                } else if ($currentCount < 5) {
                    $trackMemVisitObj->updateCount($profileid);
                    $memCacheObject->set($profileid . '_MEM_PAGE_VISITS', ($currentCount + 1), 86400);
                    return $currentCount + 1;
                } else if ($currentCount >= 5) {
                    return $currentCount;
                }
            }
        } else {
            return 0;
        }
    }

    public function getRenewCronSMSServiceName($profileid) {
        $billServStatObj = new BILLING_SERVICE_STATUS();
        $billServObj = new billing_SERVICES('newjs_slave');
        $servAct = $billServStatObj->getLastActiveServiceDetails($profileid);
        $serviceID = $servAct['SERVICEID'];
        $servName = $billServObj->getServiceName($serviceID);
        if (strstr($servAct['SERVEFOR'], 'N') !== false) {
            $servName = str_replace('e-Value', 'eAdvantage', $servName);
        }
        return $servName;
    }

    public function calculateNewRenewalDiscountBasedOnPreviousTransaction($profileid, $discount_calc, $purDet) {
        $billServObj    = new billing_SERVICES('newjs_slave');
        $servDetailsArr = $billServObj->getServiceDetailsArr();
        // Start - Logic to change renewal based on previous discount
        $prevServPur = explode(",", $purDet['SERVICEID']);
        $prevDiscAmt = $purDet['DISCOUNT'];
        if ($prevDiscAmt != 0) {
            $currency    = $purDet['CUR_TYPE'];
            foreach ($prevServPur as $val) {
                if ($currency == "RS") {
                    $prevTotAmt += $servDetailsArr[$val]['desktop_RS'];
                } else {
                    $prevTotAmt += $servDetailsArr[$val]['desktop_DOL'];
                }
            }
            $prevDisc = round(($prevDiscAmt/$prevTotAmt)*100, 2);
	    if($prevDisc>=100){
		$prevDisc =0;
	    }	
        } else {
            $prevDisc = 0;
        }
        if ($prevDisc > $discount_calc) {
            $discount = (0.6 * ($prevDisc- $discount_calc)) + $discount_calc;
            // rounding to nearest 5
            $discount = round($discount/5) * 5; 
        } else {
            $discount = $discount_calc;
        }
        // print_r(array('profileid' => $profileid, 'currency' => $currency, 'last_main_transaction_services' => implode(",", $prevServPur), 'previous_discount_amount' => $prevDiscAmt, 'previous_final_amount' => $prevTotAmt, 'previous_discount_perc' => $prevDisc, 'rd_algo_calculated_discount_prec' => $discount_calc, 'rohan_algo_calculated_discount_prec' => $discount));
        // End - Logic to change renewal based on previous discount
        unset($discount_calc, $currency, $prevServPur, $prevDiscAmt, $prevTotAmt, $prevDisc);
        return $discount;
    }
    
    public function handleNegativeTransaction($receiptidArr,$source=''){
        if(is_array($receiptidArr)){
            $payDetObj = new BILLING_PAYMENT_DETAIL();
            foreach($receiptidArr['RECEIPTIDS'] as $key => $receiptid){
                $payDetData = $payDetObj->fetchAllDataForReceiptId($receiptid);
                $this->deleteRedisForFreeToPaid($source, $payDetData["PROFILEID"]);
                $payDetData['ENTRY_DT'] = date('Y-m-d H:i:s');
                if(!($source == 'CANCEL' && in_array($receiptid, $receiptidArr['REFUND']))){
                    $payDetData['AMOUNT'] = $payDetData['AMOUNT']*(-1);
                    $payDetData['APPLE_COMMISSION'] = $payDetData['APPLE_COMMISSION']*(-1);
                    $payDetData['FRANCHISEE_COMMISSION'] = $payDetData['FRANCHISEE_COMMISSION']*(-1);
                }
                $this->negativeTransaction($payDetData);
                unset($payDetData);
            }
            unset($payDetObj);
        }
    }
    

    public function negativeTransaction($params){
        foreach($params as $key => $val){
            $paramsStr.= "$key, ";
            $valuesStr.= "'$val', ";
        }
        $paramsStr = rtrim($paramsStr,", ");
        $valuesStr = rtrim($valuesStr,", ");
        $negTransactionObj = new billing_PAYMENT_DETAIL_NEW();
        $negTransactionObj->insertRecord($paramsStr,$valuesStr);
    }

    public function addUserForDollarPayment($profileid){
        $dolBillingUsersObj = new billing_DOL_BILLING_USERS_FOR_TEST();
        $dolBillingUsersObj->addUserForDol($profileid);
    }
    
    public function removeUserForDollarPayment($profileid){
        $dolBillingUsersObj = new billing_DOL_BILLING_USERS_FOR_TEST();
        $dolBillingUsersObj->removeUserForDol($profileid);

    }
    
    public function getReceiptids($billid){
        $payDetObj = new BILLING_PAYMENT_DETAIL();
        $data = $payDetObj->getStatusTransactions($billid,array("'DONE'","'REFUND'"));
        foreach($data as $key => $val){
            if($val['STATUS'] == 'REFUND'){
                $result['REFUND'][] = $val['RECEIPTID'];
            }
            $result['RECEIPTIDS'][] = $val['RECEIPTID'];
        }
        unset($payDetObj,$data);
        return $result;
    }
    
    public function getCancelledDate($billid){
        $negTransactionObj = new billing_PAYMENT_DETAIL_NEW();
        $row = $negTransactionObj->getCancelledBillIdDetails($billid);
        $dt = $row["ENTRY_DT"];
        return $dt;
    }

    /*function - deactivateCurrentMembership
    * deactivates currently active membership of user
    * @inputs: $params
    * @outputs: true/false
    */
    public function deactivateCurrentMembership($params){
        try{
            if(is_array($params) && $params["PROFILEID"] && $params["USERNAME"] && in_array($params["MEMBERSHIP"], VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                if($params["MEMBERSHIP"] == "MAIN"){
                    $billingServStatObj = new BILLING_SERVICE_STATUS();

                    //get info of active main membership of profile
                    $serStatDet  = $billingServStatObj->getLatestActiveMemInfoForProfiles(array($params["PROFILEID"]),"BILLID,SERVICEID");
                    //if any main membership is active,then deactivate it
                    if(!empty($serStatDet[$params["PROFILEID"]])){

                        //get payment details of this billid
                        $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL();
                        $paymentDet = $billingPaymentDetObj->getAllDetailsForBillidArr(array($serStatDet[$params["PROFILEID"]]["BILLID"]),"RECEIPTID","1");
                        unset($billingPaymentDetObj);

                        //insert entry in EDIT_DETAILS_LOG for changes
                        $billingEditLogObj = new billing_EDIT_DETAILS_LOG();
                        $billingEditLogObj->logEntryInsert(array("PROFILEID"=>$params["PROFILEID"],"BILLID"=>$serStatDet[$params["PROFILEID"]]["BILLID"],"RECEIPTID"=>$paymentDet[$serStatDet[$params["PROFILEID"]]["BILLID"]]["RECEIPTID"],"CHANGES"=>"TRANSACTION DEACTIVATED FOR UPGRADE","ENTRYBY"=>$params["USERNAME"],"ENTRY_DT"=>date("Y-m-d H:i:s")));
                        unset($billingEditLogObj);

                        //update status of main membership
                        $billingServStatObj->updateActiveStatusForBillidAndServiceid($serStatDet[$params["PROFILEID"]]["BILLID"], $serStatDet[$params["PROFILEID"]]["SERVICEID"],'N');

                        //update user's subscription
                        $subscription    = $billingServStatObj->getActiveServeFor($params["PROFILEID"]);
                        if (empty($subscription)){
                            $subscription = '';
                        }
                        $this->jprofileObj->edit(array('SUBSCRIPTION' => $subscription), $params["PROFILEID"], 'PROFILEID');

                        //clear the user membership memcache
                        $memCacheObject = JsMemcache::getInstance();
                        if ($memCacheObject) {
                            $memCacheObject->remove($params["PROFILEID"] . '_MEM_NAME');
                            $memCacheObject->remove($params["PROFILEID"] . "_MEM_OCB_MESSAGE_API17");
                            $memCacheObject->remove($params["PROFILEID"] . "_MEM_HAMB_MESSAGE");
                            $memCacheObject->remove($params["PROFILEID"] . "_MEM_SUBSTATUS_ARRAY");
                        }
                        //update the success deactivate entry
                        if($params["NEW_ORDERID"] && $params["NEW_ORDERID"]!=""){
                            $upgradeOrdersObj = new billing_UPGRADE_ORDERS();
                            $upgradeOrdersObj->updateOrderUpgradeEntry($params["NEW_ORDERID"],array("OLD_BILLID"=>$serStatDet[$params["PROFILEID"]]["BILLID"],"DEACTIVATED_STATUS"=>"DONE"));
                            unset($upgradeOrdersObj);
                        }
                    }
                    unset($billingServStatObj);
                }
                return true;
            }
            else{
                //log the failed deactivate entry
                $upgradeOrdersObj = new billing_UPGRADE_ORDERS();
                $upgradeOrdersObj->updateOrderUpgradeEntry($params["NEW_ORDERID"],array("DEACTIVATED_STATUS"=>"FAILED","REASON"=>"Invalid inputs to deactivateCurrentMembership api"));
                unset($upgradeOrdersObj);
                return false;
            }
        }
        catch(Exception $e){
            //log the failed deactivate entry
            $upgradeOrdersObj = new billing_UPGRADE_ORDERS();
            $upgradeOrdersObj->updateOrderUpgradeEntry($params["NEW_ORDERID"],array("DEACTIVATED_STATUS"=>"FAILED","REASON"=>"exception in deactivateCurrentMembership-".$e));
            unset($upgradeOrdersObj);
            return false;
        }
    }
    
    /*function - updateMemUpgradeStatus
    * update success upgrade status
    * @inputs: $orderid
    * @outputs: none
    */
    function updateMemUpgradeStatus($orderid,$profileid,$updateArr=array(),$flushCache=true){
        $upgradeOrdersObj = new billing_UPGRADE_ORDERS();
        $upgradeOrdersObj->updateOrderUpgradeEntry($orderid,$updateArr);
        unset($upgradeOrdersObj);
        if($flushCache == true){
            $memCacheObject = JsMemcache::getInstance();
            $memCacheObject->remove($profileid.'_MEM_UPGRADE_'.$orderid);
        }
    }

    function checkMemUpgrade($orderid,$profileid,$flushCache=true){
        //check whether user is eligible for membership upgrade or not
        $memCacheObject = JsMemcache::getInstance();
        $checkForMemUpgrade = $memCacheObject->get($profileid.'_MEM_UPGRADE_'.$orderid);
        $memUpgrade = "NA";

        if($checkForMemUpgrade == null || $checkForMemUpgrade == false){
            $upgradeOrderObj = new billing_UPGRADE_ORDERS();
            $isUpgradeCaseEntry = $upgradeOrderObj->isUpgradeEntryExists($orderid,$profileid);
            if(is_array($isUpgradeCaseEntry)){
                $memUpgrade = $isUpgradeCaseEntry["MEMBERSHIP"];
            }
            else{
                $memUpgrade = "NA";
            }
        }
        else{
            if(in_array($checkForMemUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $memUpgrade = $checkForMemUpgrade;
            }
            else{
                $memUpgrade = "NA";
            }
        }
        
        if($flushCache == true){
            $memCacheObject->remove($profileid.'_MEM_UPGRADE_'.$orderid);
        }
        return $memUpgrade;
    }
    
    public function computeMaximumDiscount($memPriceArr){
        if(is_array($memPriceArr)){
            $nonZero = false;
            foreach($memPriceArr as $service => $val){
                $servDisc[$service] = 0;
                foreach($val as $servDur => $details){
                    $disc = $details["PRICE"] - $details["OFFER_PRICE"];
                    if($disc > 0){
                        $nonZero = true;
                        $per = ($disc/$details["PRICE"])*100;
                        if($per>$servDisc[$service]){
                            $servDisc[$service] = intval($per);
                        }
                    }
                }
            }
        }
        $servDisc["PROFILEID"] = $memPriceArr["PROFILEID"];
        if($nonZero == false){
            $servDisc['P'] = 0;
            $servDisc['C'] = 0;
            $servDisc['NCP'] = 0;
            $servDisc['X'] = 0;
            $maxDiscount = 0;
        }
        else{
            $maxDiscount = 0;
            $maxDiscount = max($servDisc['P'],$servDisc['NCP'],$servDisc['X'],$servDisc['C']);
        }
        $disHistObj = new billing_DISCOUNT_HISTORY();
        $disHistObj->insertDiscountHistory($servDisc);
        unset($disHistObj);
        
        /*$discMaxObj = new billing_DISCOUNT_HISTORY_MAX();
        $discMaxObj->updateDiscountHistoryMax(array("MAX_DISCOUNT"=>$maxDiscount,"PROFILEID"=>$servDisc["PROFILEID"],"LAST_LOGIN_DATE"=>date("Y-m-d"),"MAX_DISCOUNT_DATE"=>date("Y-m-d")));
        unset($discMaxObj);*/
        unset($nonZero);
    }

    /*function to compute starting price of membership plans with and without discount
    * @inputs:$request
    * @return:$output
    */
    public function fetchMembershipPlansStartingRange($request){
        $request->setParameter('getMembershipPlansStartingRange', 1);
        $request->setParameter('INTERNAL', 1);
        ob_start();
        $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');
        $output = ob_get_contents();
        ob_end_clean();
        $data = json_decode($output, true);
        return $data;
    }
    
    public function deleteRedisForFreeToPaid($source,$profileid){
        if($source == 'CANCEL' || $source == 'CHARGE_BACK' || $source == 'BOUNCE'){
            JsMemcache::getInstance()->remove("FreeToP_$profileid");
        }
    }
    
    public function clearMembershipCacheForProfile($profileid){
        $memCacheObject = JsMemcache::getInstance();
        if ($memCacheObject) {
            $memCacheObject->remove($profileid . '_MEM_NAME');
            $memCacheObject->remove($profileid . "_MEM_OCB_MESSAGE_API17");
            $memCacheObject->remove($profileid . "_MEM_HAMB_MESSAGE");
            $memCacheObject->remove($profileid . "_MEM_SUBSTATUS_ARRAY");
        }
    }
    
    public function modifyResponseForLightningDeal($data,$source=''){
        if (strpos($data["membership_message"]["top"], 'FLASH DEAL') !== false) {
            $data["membership_message"]["endTimeInSec"] = strtotime($data["membership_message"]["expiryDate"]) - strtotime(date('Y-m-d H:i:s'));
        }
        return $data;
    }
    
    public function modifiedMessage($data){
        $msg = $data['hamburger_message']['top'];
        if( (strpos($data['hamburger_message']['top'], 'FLASH DEAL') !== false) ){
            $msgArr = explode(",",$data['hamburger_message']['bottom']);
            $msg = $msgArr[0].".";
        }
        return $msg;
    }

    // Automated Outbound Call for Membership
    public function checkEligibleForMemCall($profileid){
	$minScore 	=91;
	$maxScore 	=100;
        $displayPage 	=1;
        $device 	="desktop";
        $ignoreShowOnlineCheck =false;
	$result 	=array();	

    	$mainAdminPoolObj = new incentive_MAIN_ADMIN_POOL('newjs_masterRep');	
       	$eligible =$mainAdminPoolObj->getEligibileProfile($profileid, $minScore, $maxScore);     		
	if($eligible){
		$userObj = new memUser($profileid);
		$userObj->setMemStatus();
		$type =$userObj->getUserType();	
		if($type==2){
			list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code) = $this->getUserDiscountDetailsArray($userObj, "L");
			list($allMainMem, $minPriceArr) = $this->getMembershipDurationsAndPrices($userObj, $discountType, $displayPage, $device, $ignoreShowOnlineCheck);
			$result =$minPriceArr['P'];
			unset($result['PRICE_RS_TAX']);
			return $result;
		}
	}
	return false;
    }
    
    public function getMembershipAutoLoginLink($profileid,$source){
        if($profileid){
            include(JsConstants::$docRoot."/classes/authentication.class.php");
            $protect_obj = new protect;
            $profilechecksum = md5($profileid)."i".$profileid;
            $profileObj = LoggedInProfile::getInstance('newjs_slave',$profileid);
            $echecksum = $protect_obj->js_encrypt($profilechecksum,$profileObj->getEMAIL());
            $autoLoginLink = JsConstants::$siteUrl."/membership/jspc?CMGFRMMMMJS=1&checksum=$profilechecksum&profilechecksum=$profilechecksum&echecksum=$echecksum&enable_auto_loggedin=1&from_source=$source";
            return $autoLoginLink;
        }
    }

}
