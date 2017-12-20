<?php

/*
 * This class handles responses thrown by ApiMembershipDetailsV3
 * Functions mentioned here are used to decide which response to throw based on incoming parameters for API
 * Please note: modifying properties here will result in change across all channels
 */

class MembershipAPIResponseHandler {
    
    public function initializeAPI($request) {

        $loginData = $request->getAttribute("loginData");
        if ($loginData['PROFILEID']) {
            $this->profileid = $loginData['PROFILEID'];
            $this->checksum = $loginData['CHECKSUM'];
        }

        $this->userProfile = $request->getParameter('userProfile');
        if (empty($this->profileid) && !empty($this->userProfile)) {
            $this->profileid = $this->userProfile;
        }
        
        //$this->mainMem = $request->getParameter("mainMem");
	$this->mainMem = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter("mainMem"));
        //$this->mainMemDur = $request->getParameter("mainMemDur");
	$this->mainMemDur = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter("mainMemDur"));
        //$this->selectedVas = $request->getParameter("selectedVas");
	$this->selectedVas = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter("selectedVas"));
        $this->displayPage = $request->getParameter("displayPage");
        $this->upgradeMem = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter("upgradeMem"));
        if(!$this->upgradeMem || !in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
           $this->upgradeMem = "NA"; 
        }

        if(empty($this->displayPage)) {
        	$this->displayPage = 1;
        }

        $this->getAppData = $request->getParameter("getAppData");
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $fromBackend = $request->getParameter("from_source");
        $this->backendRedirect = $request->getParameter('backendRedirect');
        $this->backendChecksum = $request->getParameter("checksum");
        $this->profilechecksum = $request->getParameter("profilechecksum");
        $this->echecksum = $request->getParameter("echecksum");
        $this->reqid = $request->getParameter("reqid");
        $this->couponCode = $request->getParameter('couponID');
        if (empty($this->couponCode) || $this->couponCode == 'null' || $this->couponCode == 'NULL') {
        	$this->couponCode = NULL;
        }
        
        $this->orderID = $request->getParameter("orderID");
        
        $this->trackAppData = $request->getParameter('trackAppData');
        $this->source = $request->getParameter('source');
        $this->tab = $request->getParameter('tab');
        $this->pgNo = $request->getParameter('pgNo');
        $this->device = $request->getParameter('device');
        
        if(empty($this->device)){
        	$this->device = 'desktop';
        }

        
        $this->allMemberships = $request->getParameter('allMemberships');
	$this->mainMembership = preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter('mainMembership'));
        //$this->mainMembership = $request->getParameter('mainMembership');
	$this->vasImpression =  preg_replace('/[^A-Za-z0-9\. -_,]/', '', $request->getParameter('vasImpression'));
        //$this->vasImpression = $request->getParameter('vasImpression');
        $this->backDisc = $request->getParameter('backDisc');
        $this->backTot = $request->getParameter('backTot');
        $this->processPayment = $request->getParameter('processPayment');
        $this->totalCartPrice = $request->getParameter('totalCartPrice');
        $this->paymentMode = $request->getParameter('paymentMode');
        $this->cardType = $request->getParameter('cardType');
        $this->usdTOinr = $request->getParameter('usdTOinr');

        $this->callType = $request->getParameter('execCallbackType');
        $this->callTab = $request->getParameter('tabVal');
        $this->callProfile = $request->getParameter('profileid');
        $this->processCallback = $request->getParameter('processCallback');
        
        $this->validateCoupon = $request->getParameter('validateCoupon');
        $this->selID = $request->getParameter('serviceID');
        
        $this->pickupRequest = $request->getParameter('pickupRequest');
        $this->city = $request->getParameter('city');
        $this->name = $request->getParameter('name');
        $this->landline = $request->getParameter('landline');
        $this->mobile = $request->getParameter('mobile');
        $this->address = $request->getParameter('address');
        $this->date = $request->getParameter('date');
        $this->comment = $request->getParameter('comment');
        $this->channel = $request->getParameter('channel');
        
        $this->internalParamCheck = $request->getParameter('INTERNAL');
        $this->getMembershipMessage = $request->getParameter('getMembershipMessage');
        $this->getHamburgerMessage = $request->getParameter('getHamburgerMessage');
        $this->getMembershipPlansStartingRange = $request->getParameter('getMembershipPlansStartingRange');
        $this->appVersion = $request->getParameter('API_APP_VERSION');

        $this->callbackSource = $request->getParameter('callbackSource');
        
        $this->rcbResponse = $request->getParameter('rcbResponse');//For Rcb tracking
        
        if (isset($this->appVersion) && is_numeric($this->appVersion) && $this->appVersion >= 18) {
            $this->lowPriorityBannerDisplayCheck = true;
        } 
        else if (!isset($this->appVersion) || empty($this->appVersion)) {
            $this->lowPriorityBannerDisplayCheck = true;
        } 
        else {
            $this->lowPriorityBannerDisplayCheck = false;
        }
        // $this->PayUOrderProcess = $request->getParameter('PayUOrderProcess');
        $this->generateNewIosOrder = $request->getParameter('generateNewIosOrder');
        $this->AppleOrderProcess = $request->getParameter('AppleOrderProcess');
        $this->testBilling = $request->getParameter('testBilling');
        $this->userForDolPayment = $request->getParameter('userForDolPayment');
        
        $this->memHandlerObj = new MembershipHandler();
        $this->userObj = new memUser($this->profileid);
        $purchasesObj = new BILLING_PURCHASES();
        $this->memApiFuncs = new MembershipApiFunctions();
        
        list($this->ipAddress, $this->currency) = $this->memHandlerObj->getUserIPandCurrency($this->profileid);
        $this->userObj->setIpAddress($this->ipAddress);
        $this->userObj->setCurrency($this->currency);
        if (!empty($this->profileid)) {
            $this->userObj->setMemStatus($fromBackend);
            $this->userType = $this->userObj->userType;
            $purchaseDetails = $purchasesObj->getCurrentlyActiveService($this->profileid,"PU.DISCOUNT_PERCENT");
            if(is_array($purchaseDetails) && $purchaseDetails['SERVICEID']){
                $this->memID = @strtoupper($purchaseDetails['SERVICEID']);
                $this->lastPurchaseDiscount = $purchaseDetails['DISCOUNT_PERCENT'];
                //$this->lastPurchaseBillid = $purchaseDetails['BILLID'];
            }
            else{
                $this->memID = @strtoupper($purchaseDetails);
                $this->lastPurchaseDiscount = 0;
                //$this->lastPurchaseBillid = null;
            }

            $this->subStatus = $this->memHandlerObj->getSubscriptionStatusArray($this->userObj,null,null,$this->memID);
            if (is_array($this->subStatus) && !empty($this->subStatus)) {
                $this->countActiveServices = count($this->subStatus);
            } 
            else {
                $this->countActiveServices = 0;
            }
            $contactsArr = $this->userObj->getRemainingContacts($this->profileid,"ALLOTED");
            $this->contactsRemaining = $contactsArr['REMAINING'];
            $this->totalContactsAllotted = $contactsArr['ALLOTED'];
            $this->userDetails = $this->memHandlerObj->getUserData($this->profileid);
        } 
        else {
            $this->memID = "FREE";
        }
        
        if ($this->userObj->userType == 4 || $this->userObj->userType == 6) {
            $this->renewCheckFlag = 1;
        }
       
        //set to fetch main membership offer prices for upgrade display section
        if($this->displayPage == '1' && $this->userObj->userType == memUserType::UPGRADE_ELIGIBLE && in_array($this->device, VariableParams::$memUpgradeConfig["channelsAllowed"]) && $this->fromBackend != 1 && $this->processPayment != 1){
            $this->upgradeMem = "MAIN";
        }
        //set discount info so that it can be used as common variable
        $this->discountTypeInfo = $this->memHandlerObj->getDiscountInfo($this->userObj,$this->upgradeMem,$this->device);
        if($this->discountTypeInfo == null){
            $this->discountTypeInfo = array();
        }
        //set renewal percent for common use
        if ($this->profileid){
            $this->userRenewalPercent = $this->memHandlerObj->getVariableRenewalDiscount($this->profileid);
            if($this->userRenewalPercent == null){
                $this->userRenewalPercent = "0";
            }
        }
        
        $this->memApiFuncs->setDiscountDetails($this,true);
        
        if ($this->memID != "FREE" && $this->memID != "ESJA") {
            $this->memID = $this->memApiFuncs->retrieveCorrectMemID($this->memID, $this);
            $this->activeServiceName = $this->memHandlerObj->getUserServiceName($this->memID);
        }
        //var_dump($this->backendRedirect);
        if($fromBackend == "discount_link"){
            $ignoreShowOnlineCheck = true;
        }
        else{
            $ignoreShowOnlineCheck = false;
        }
        list($this->allMainMem, $this->minPriceArr) = $this->memHandlerObj->getMembershipDurationsAndPrices($this->userObj, $this->discountType, $this->displayPage , $this->device,$ignoreShowOnlineCheck,$this,$this->upgradeMem);
       
        $this->curActServices = array_keys($this->allMainMem);
        
        if ($this->device == "iOS_app") {
            if (($key = array_search("ESP", $this->curActServices)) !== false) {
                unset($this->curActServices[$key]);
            }
            if (($key = array_search("X", $this->curActServices)) !== false) {
                unset($this->curActServices[$key]);
            }
            if (($key = array_search("D", $this->curActServices)) !== false) {
                unset($this->curActServices[$key]);
            }
            if (($key = array_search("P", $this->curActServices)) !== false) {
		        unset($this->allMainMem['P']['P2']);
            }
            if (($key = array_search("C", $this->curActServices)) !== false) {
		        unset($this->allMainMem['C']['C2']);
            }
        }

        if ($this->device == "mobile_website" || $this->device == "Android_app" || $this->device == "desktop") {
            if (($key = array_search("ESP", $this->curActServices)) !== false) {
                unset($this->curActServices[$key]);
            }
        }

        // Fixing Channel based on device
        if (empty($this->channel)) {
	        if ($this->device == 'JSAA_mobile_website' || $this->device == 'Android_app'){
	        	$this->channel = 'JSAA';
	        } else if ($this->device == 'iOS_app'){
	        	$this->channel = 'JSIA';
	        } else if ($this->device == 'mobile_website'){
	        	$this->channel = 'JSMS';
	        } else {
	        	$this->channel = 'JSPC';
	        }
	    }

	    // CallbackSource for recording inbound link
	    if (empty($this->callbackSource)) {
		    if($fromBackend == 'REQUEST_CALLBACK'){
		    	$this->callbackSource = "SMS";
		    } else if(!empty($fromBackend)){
		    	$this->callbackSource = $fromBackend;
		    } else {
		    	$this->callbackSource = "Membership_Page";
		    }
		}
        
        $this->service_data = $this->memApiFuncs->getMembershipData($this);
        $this->vas_data = $this->memHandlerObj->getAllVASData($this->userObj, $this->device);
        $this->vas_data = $this->memApiFuncs->resortVasData($this->vas_data, $this);
        
        if ($fromBackend == "discount_link") {
            $this->displayPage = 3;
            $this->fromBackend = 1;
        }
        
        $dataArr = $this->generateHamburgerMessageResponse();
        $this->topDiscountBanner = $dataArr['hamburger_message'];

        if(!empty($this->profileid) && $this->userObj->userType != 5 && $this->userObj->userType != memUserType::UPGRADE_ELIGIBLE && $this->userObj->userType != 6){
	        // VAS Logic data required
	        $profileObj = LoggedInProfile::getInstance('newjs_master');
	        $horoscopeObj = new Horoscope();
			$horoscopeSet = $profileObj->getHOROSCOPE_MATCH();
			$horoscopeFilled = $horoscopeObj->isHoroscopeExist($profileObj);
			if($horoscopeSet == "Y" || $horoscopeFilled == "Y"){
				$this->horoscopeSetting = "Y";
			} else {
				$this->horoscopeSetting = "N";
			}
			$profileMemcacheObj = new ProfileMemcacheService($profileObj);
			$this->acceptanceCount = $profileMemcacheObj->get('ACC_ME');
			$shardDb = JsDbSharding::getShardNo($this->profileid,'slave');
			//$newjsMessageLogObj = new NEWJS_MESSAGE_LOG($shardDb);
			//$this->interestRecCount = $newjsMessageLogObj->getInterestRecievedInLastWeek($this->profileid);
		}

        return $this;
    }
    
    public function generateResponseData($request) {
        $output = array();
        if ($this->trackAppData == 1 && !empty($this->device)) {
            if ($this->device == 'Android_app') {
                $pTab = 23;
            } 
            else {
                $pTab = 33;
            }
            if ($this->device == 'discount_link') {
                $this->memHandlerObj->trackMembershipProgress($this->userObj, $this->source, $this->tab, $this->pgNo, $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression, $this->backDisc, $this->backTot, $pTab, $this->trackType, $this->specialActive, $this->discPerc, $this->discountActive);
            } 
            else {
                $this->memHandlerObj->trackMembershipProgress($this->userObj, $this->source, $this->tab, $this->pgNo, $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression, 0, 0, $pTab, $this->trackType, $this->specialActive, $this->discPerc, $this->discountActive);
            }
        } 
        // elseif ($this->PayUOrderProcess == 1) {
        //     $output = $this->handlePayUOrderProcessing($request);
        // }
        else if($this->getMembershipPlansStartingRange == 1) {
            $output = $this->generateMembershipPlansStartingRange();
        }  
        elseif ($this->getMembershipMessage == 1) {
            $output = $this->generateOCBMessageResponse();
        } 
        elseif ($this->getHamburgerMessage == 1) {
            $output = $this->generateHamburgerMessageResponse();
        } 
        elseif ($this->validateCoupon == 1) {
            $outputArr = $this->validateCouponResponse($this->selID, $this->couponCode);
            if (is_array($outputArr)) $output = $outputArr['message'];
        } 
        elseif ($this->processCallback) {
            $output = $this->requestCallBackResponse($request);
        } 
        elseif ($this->pickupRequest == 1) {
            $output = $this->validatePickupRequestResponse($request);
            if ($output['status'] == 1) $output = $this->processPickupRequestResponse($request);
        } 
        elseif ($this->generateNewIosOrder == 1) {
            $output = $this->generateNewAppleOrderResponse($request);
        } 
        elseif ($this->AppleOrderProcess == 1) {
            $output = $this->generateAppleOrderProcessingResponse($request);
        } 
        elseif ($this->testBilling == 1) {
            $output = $this->doTestBilling($request);
        }
        elseif ($this->userForDolPayment == 1) {
            $output = $this->addRemoveUserForDolPayment($request);
        }
        else {
            if ($this->displayPage == 1) {
                $output = $this->generateLandingPageResponse($request);
            } 
            elseif (!$this->profileid) {
                $output = 'logout_case';
            } 
            elseif ($this->displayPage == 2 && !empty($this->mainMem) && !empty($this->mainMemDur)) {
                $output = $this->generateVasPageResponse($request);
            } 
            elseif ($this->displayPage == 3 && $this->fromBackend != 1) {
                $output = $this->generateCartPageResponse($request);
                //print_r($output);die;
            } 
            elseif ($this->displayPage == 3 && $this->fromBackend == 1) {
                $output = $this->generateBackendDiscountPageResponse($request);
            } 
            elseif ($this->displayPage == 4) {
                
                // TBD Coupon Code page
                
            } 
            else if ($this->displayPage == 5) {
                $output = $this->generatePaymentOptionsPageResponse($request);
                //print_r($output);die;
            } 
            else if ($this->displayPage == 6) {
                $output = $this->generateChequePickupResponse($request);
            } 
            else if ($this->displayPage == 7) {
                $output = $this->generateChequePickupSuccessResponse($request);
            } 
            elseif ($this->displayPage == 8 && !empty($this->orderID)) {
                $output = $this->generateSuccessPageResponse();
            } 
            elseif ($this->displayPage == 9) {
                $output = $this->generateFailurePageResponse();
            } 
            elseif ($this->displayPage == 10) {
                $output = $this->generatePayAtBranchesPageResponse();
            }
        }
        $output["appVersion"] = $this->appVersion;
        if (isset($output) && !empty($output)) {
            return $this->response = $output;
        } 
        else {
            return false;
        }
    }
    
    public function generateLandingPageResponse($request) {
        $this->memApiFuncs->getTopBlockContent($this);
        if ($this->currency == "RS") {
            $topHelp = array(
                "title" => "Help",
                "phone_number" => "1800-419-6299",
                "call_text" => "Call Us (Toll Free India)",
                "value" => "18004196299",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=" . $this->channel . "&callbackSource=" . $this->callbackSource
            );
        } 
        else {
            $topHelp = array(
                "title" => "Help",
                "phone_number" => "+911204393500",
                "call_text" => "Call Us (India)",
                "value" => "+911204393500",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=" . $this->channel . "&callbackSource=" . $this->callbackSource
            );
        }
        
        $tracking_params = array(
            "source" => "601",
            "tab" => "61",
            "pgNo" => "1",
            "device" => $this->device,
            "allMemberships" => implode(",", $this->curActServices) ,
            "mainMembership" => NULL,
            "vasImpression" => NULL
        );
        
        $bottomHelp = $topHelp;
        $bottomHelp['title'] = 'I need help in buying Membership';
        //tracking of mem page visits only for free user to autocollapse learning cartoon
        if(in_array($this->userObj->userType,array(2,4,6)))
		  $openedCount = $this->memHandlerObj->trackAndGetOpenedCount($this->profileid);
        
        if (($this->userObj->userType == 4 || $this->userObj->userType == 6) && $this->device != "iOS_app") {
            if ($this->contactsRemaining == 0) {
                $renewText = "Renew your membership to continue current benefits";
            } 
            else {
                $renewText = "Renew before {$this->expiry_date} & get " . substr($this->topDiscountBanner['top'], 0, strpos($this->topDiscountBanner['top'], "till", 0));;
            }
        } 
        else if ($this->userObj->userType == 5 || $this->userObj->userType == memUserType::UPGRADE_ELIGIBLE) {
            $renewText = "You may choose from Value Added Services below that best fit your needs";
        }
        
        if ($this->device != "iOS_app") {
            $dividerText = $this->topDiscountBanner['top'];
            $dividerExpiry = $this->topDiscountBanner['expiry'];
        } 
        else {
            $renewText = NULL;
        }
       
        if (($this->userObj->userType == 5 || $this->userObj->userType == memUserType::UPGRADE_ELIGIBLE) && $this->device != "iOS_app" && $this->contactsRemaining != 0) {
            $this->memApiFuncs->customizeVASDataForAPI(0, 0, $this);

            //filter out vas services from vas content based on main membership if vas content is there
            $this->memApiFuncs->filterMainMemBasedVASData($this->custVAS,$this,$this->memID);
            $this->memApiFuncs->removeExtraParamsFromVAS($this->custVAS, $this);
            $this->service_data = NULL;
        } 
        else {
            $this->custVAS = NULL;
        }
        
        if (isset($this->custVAS) && !empty($this->custVAS)) {
            $title = "Membership Status";
            $bottomHelp = NULL;
        } 
        else {
            $title = $this->topDiscountBanner['bottom'];
        }
        
        $allBenefits = VariableParams::$newApiPageOneBenefits;
        
        if (!$this->profileid) {
            $userId = 0;
        } 
        else {
            $userId = $this->profileid;
        }
        $vasFiltering = json_encode(VariableParams::$mainMemBasedVasFiltering);
        if($this->device == "desktop" && $this->custVAS == NULL){
            $this->memApiFuncs->customizeVASDataForAPI(0, 0, $this);
            //filter out vas services from vas content based on main membership
            $this->memApiFuncs->filterMainMemBasedVASData($this->custVAS,$this,"NCP");
            $this->memApiFuncs->removeExtraParamsFromVAS($this->custVAS, $this);
            $this->pageOneVas = $this->custVAS;
            $preSelectVasGlobal = array();
            if($this->horoscopeSetting == "Y"){
                $preSelectVasGlobal[] = "A";
            }
            $preSelectVasGlobal = implode(",",$preSelectVasGlobal);
            $this->custVAS = NULL;
            
        }
        if($this->discountTypeInfo["TYPE"] == discountType::OFFER_DISCOUNT)
            $disableVasDiscount = "1";
        $output = array(
            'title' => $title,
            'topBlockMessage' => $this->topBlockMessage,
            'currency' => $this->currency,
            'dividerText' => $dividerText,
            'dividerExpiry' => $dividerExpiry,
            'backgroundText' => $renewText,
            'serviceContent' => $this->service_data,
            'vasContent' => $this->custVAS,
            'topHelp' => $topHelp,
            'bottom_message' => "If you buy any plan, you also get unlimited views of Phone & Email for Accepted Members",
            'bottomHelp' => $bottomHelp,
            'continueText' => "Continue",
            'device' => $this->device,
            'userId' => $userId,
            'openedCount' => $openedCount,
            'allBenefits' => $allBenefits,
            'userDetails' => $this->userDetails,
            'taxRate' => billingVariables::TAX_RATE,
            'tracking_params' => $tracking_params,
            'filteredVasServices'=>$vasFiltering,
            'skipVasPageMembershipBased'=>json_encode(VariableParams::$skipVasPageMembershipBased),
            'pageOneVas'=>$this->pageOneVas,
            'preSelectLandingVas'=>$preSelectVasGlobal,
            'disableVasDiscount'=>$disableVasDiscount,
            'date'=>date('Y-m-d H:i:s')
        );
        
        //fetch the upgrade membership content based on eligibilty and channel
        if(in_array($this->device, VariableParams::$memUpgradeConfig["channelsAllowed"]) && $this->userObj->userType == memUserType::UPGRADE_ELIGIBLE){
            $output["upgradeMembershipContent"] = $this->generateUpgradeMemResponse($request,"",$this);
            if(is_array($output["upgradeMembershipContent"]) && is_array($output)){
                $output["title"] = "Upgrade to ".$output["upgradeMembershipContent"]["upgradeMainMemName"];
            }
        }
        
        if(in_array($this->device, VariableParams::$lightningDealOfferConfig["channelsAllowed"]) && $this->discountTypeInfo["TYPE"] == discountType::LIGHTNING_DEAL_DISCOUNT){
            $output["lightningDealContent"] = $this->generateLightningDealResponse($request);
            if(($this->userObj->userType == memUserType::PAID_WITHIN_RENEW || $this->userObj->userType == memUserType::EXPIRED_WITHIN_LIMIT) && is_array($output["lightningDealContent"])){
                $output["lightningDealContent"]["renewalLightning"] = "1";
                $output["backgroundText"] = NULL;
            }
            
            if(is_array($output["lightningDealContent"]) && is_array($output)){
                $output["title"] = "Upgrade Membership";
            }
        }
        
        //error_log("device in generateLandingPageResponse= ".$this->device);    
        if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == "Android_app") {
            if($this->upgradeMem || in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $this->memHandlerObj->trackMembershipProgress($this->userObj,'601','61','1',$this->device, $this->user_agent, implode(",", $this->curActServices), '', '',0, 0, 0, '', '', '', '',$this->upgradeMem);
            }
            else{
                $this->memHandlerObj->trackMembershipProgress($this->userObj, '601', '61', '1', $this->device, $this->user_agent, implode(",", $this->curActServices));
            }
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == "desktop") {
            //tracking for upgrade membership page
            if($this->upgradeMem || in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $this->memHandlerObj->trackMembershipProgress($this->userObj,'1','1','1',$this->device, $this->user_agent, implode(",", $this->curActServices), '', '',0, 0, 0, '', '', '', '',$this->upgradeMem);
            }
            else{
                $this->memHandlerObj->trackMembershipProgress($this->userObj, '1', '1', '1', $this->device, $this->user_agent, implode(",", $this->curActServices));
            }
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device != "Android_app") {
            if($this->device == "mobile_website"){
                if($this->upgradeMem || in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                    $this->memHandlerObj->trackMembershipProgress($this->userObj,'501','51','1',$this->device, $this->user_agent, implode(",", $this->curActServices), '', '',0, 0, 0, '', '', '', '',$this->upgradeMem);
                }
                else{
                    $this->memHandlerObj->trackMembershipProgress($this->userObj, '501', '51', '1', $this->device, $this->user_agent, implode(",", $this->curActServices));
                }
            }
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '501', '51', '1', $this->device, $this->user_agent, implode(",", $this->curActServices));
        }
        //print_r($output);die;
        return $output;
    }
    
    /*generateUpgradeMemDisplayPageResponse
    * sets the display content for upgrade membership section in apiObj
    * @inputs : $request
    */
    public function generateUpgradeMemResponse($request,$fromSource="",$thisObj){
        if($thisObj && $thisObj->userObj->userType == memUserType::UPGRADE_ELIGIBLE){
            $upgradableMemArr = $thisObj->memHandlerObj->setUpgradableMemberships($thisObj->subStatus[0]['ORIG_SERVICEID']);
            if(is_array($upgradableMemArr) && $upgradableMemArr["upgradeMem"] && $thisObj->allMainMem[$upgradableMemArr["upgradeMem"]] && $thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]] && $thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]]['SHOW_ONLINE'] == 'Y'){

                //type of upgrade
                $output["type"] = "MAIN";
                //serviceid of upgrade mem without duration
                $output["upgradeMainMem"] = $upgradableMemArr["upgradeMem"];
                //name of upgrade service
                $output["upgradeMainMemName"] = $thisObj->memHandlerObj->getUserServiceName($output["upgradeMainMem"]);
                if($thisObj->currency == "RS"){
                    $output["upgradeCurrency"] = "₹";
                }
                else{
                    $output["upgradeCurrency"] = "$";
                }
                if($fromSource != "cron"){
                    $output["upgradeOCBBenefits"] = $this->memApiFuncs->getOCBUpgradeBenefits($upgradableMemArr["upgradeMem"]);
                }
                //formatting output for ocb banner or hamburger text
                if($fromSource == "Hamburger" || $fromSource == "MyjsOCB"){
                    $output["upgradeOfferExpiry"] = date('Y-m-d',strtotime($thisObj->subStatus[0]['ACTIVATED_ON'] . VariableParams::$memUpgradeConfig["mainMemUpgradeLimit"]." day"));
                    //extra amount to be paid for upgrade

                    $output["upgradeExtraPay"] = number_format($thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]]["OFFER_PRICE"], 0, '.', ','); 


                }
                else{                           //response for upgrade page  
                    //duration of upgrade service
                    $output["upgradeMainMemDur"] = $upgradableMemArr["upgradeMemDur"];
                    //total contacts to view for upgarde mem
                    $output["upgradeTotalContacts"] = $thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]]['CALL'];
                    //additional benefits with upgrade compared to current mem
                    if($fromSource != "cron"){
                        $output["upgradeAdditionalBenefits"] = $thisObj->memApiFuncs->getAdditionalUpgradeBenefits($thisObj->subStatus[0]['SERVICEID_WITHOUT_DURATION'],$upgradableMemArr["upgradeMem"]);
                    }

                    $formattedUpgradeMemName = ($output["upgradeMainMem"] != "X" ? ucfirst(strtolower($output["upgradeMainMemName"])) : $output["upgradeMainMemName"]);
                    $formattedCurrentMemName = ($thisObj->subStatus[0]['SERVICEID_WITHOUT_DURATION'] != "X" ? ucfirst(strtolower($thisObj->activeServiceName)) : $thisObj->activeServiceName);
                    //extra compared facts for upgarde
                    $output["upgardeComparedFacts"] = array(
                                    $formattedUpgradeMemName." members are contacted more than ".$formattedCurrentMemName,
                                    $formattedUpgradeMemName." members get more screen views",
                                    );
                    //expiry date for upgarde discount
                    $output["upgradeOfferExpiry"] = date('M d Y',strtotime($thisObj->subStatus[0]['ACTIVATED_ON'] . VariableParams::$memUpgradeConfig["mainMemUpgradeLimit"]." day"));
                    $output["jsmsupgradeOfferExpiry"] = date('jS M, Y',strtotime($thisObj->subStatus[0]['ACTIVATED_ON'] . VariableParams::$memUpgradeConfig["mainMemUpgradeLimit"]." day"));
                    //extra amount to be paid for upgrade
                    $output["upgradeExtraPay"] = number_format($thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]]["OFFER_PRICE"], 2, '.', ','); 
                }
                $output["upgradeExtraPayUnformated"] = $thisObj->allMainMem[$upgradableMemArr["upgradeMem"]][$upgradableMemArr["upgradeMem"]."".$upgradableMemArr["upgradeMemDur"]]["OFFER_PRICE"]; 
            }
        }
        return $output;
    }
    
    public function generateLightningDealResponse($request,$source='',$startingPlanData=''){
        $memObj = new Membership();
        $lightningDealDiscount = $memObj->getLightningDealDiscount($this->profileid);
        if($startingPlanData == ''){
                $startingPlanData = $this->generateMembershipPlansStartingRange();
        }
        $output["discount"] = $lightningDealDiscount['DISCOUNT'];
        $output["origStartingPrice"] = $startingPlanData["startingPlan"]["origStartingPrice"];
        $output["discountedPrice"] = $output["origStartingPrice"]*((100-$output["discount"])/100);
        $output["expiryDate"] = $lightningDealDiscount['EDATE'];
        if($this->currency == "RS"){
            $output["currencySymbol"] = "₹";
        }
        else{
            $output["currencySymbol"] = "$";
        }
        $planText = "Plans starts @ <span class='strike color8 opa70'>".$output["currencySymbol"].$output["origStartingPrice"]."</span> ".$output["currencySymbol"].$output["discountedPrice"];
       if($source == "MyjsOCB" || $source == "Hamburger"){
            return $output;
        }
        else{
            $output["top"] = "FLASH DEAL";
            $output["discText"] = $output["discount"]."% OFF";
            $output["other"] = "on all memberships";
            $output["middle"] = $output["discount"]."% OFF <span class='fontlig f14'>on all memberships</span>";
            $output["bottom"] = $planText;
            $curTime = date('Y-m-d H:i:s');
            $output['priceStrike'] = $output["currencySymbol"].$output["origStartingPrice"];
            $output['discPrice']   = $output["currencySymbol"].$output["discountedPrice"];
            $output["diffSecond"] = strtotime($output["expiryDate"]) - strtotime($curTime);
        }
        return $output;        
    }

    public function generateVasPageResponse($request) {
        $this->memApiFuncs->getTopBlockContent($this);
        $this->memApiFuncs->customizeVASDataForAPI(0, 0, $this);
        //filter out vas services from vas content based on main membership
        $this->memApiFuncs->filterMainMemBasedVASData($this->custVAS,$this,$this->mainMem);
        $this->memApiFuncs->removeExtraParamsFromVAS($this->custVAS, $this);

        $preSelectVasGlobal = array();
        if($this->horoscopeSetting == "Y"){
        	$preSelectVasGlobal[] = "A";
        }
        if($this->acceptanceCount <= 3){
        	$preSelectVasGlobal[] = "T";	
        }
        if($this->interestRecCount < 3){
        	$preSelectVasGlobal[] = "R";
        }
        $preSelectVasGlobal = implode(",",$preSelectVasGlobal);

        $tracking_params = array(
            "source" => "602",
            "tab" => "62",
            "pgNo" => "2",
            "device" => $this->device,
            "allMemberships" => implode(",", $this->curActServices) ,
            "mainMembership" => $this->mainMem . $this->mainMemDur,
            "vasImpression" => NULL
        );
        
        if (($this->mainMem == 'ESP' || $this->mainMem == 'NCP') && !empty($this->mainMemDur)) {
            if ($this->mainMem == 'ESP') {
                $arr = VariableParams::$eSathiAddOns;
            }
            elseif($this->mainMem == 'NCP'){
                $arr = VariableParams::$jsExclusiveComboAddon;
            }
            else {
                $arr = VariableParams::$eValuePlusAddOns;
            }
            foreach ($arr as $key => $val) {
                if ($this->mainMemDur == '1188' || $this->mainMemDur == 'L') {
                    $dur = '12';
                } 
                else {
                    $dur = $this->mainMemDur;
                }
                if ($val == "I") {
                    $dur.= "0";
                }
                $arr[$key] = $val . $dur;
            }
            $preSelectedVas = implode(",", $arr);
            if ($this->mainMem == 'ESP') {
                $preSelectedESathiVas = $preSelectedVas;
            } 
            else {
                $preSelectedEValuePlusVas = $preSelectedVas;
            }
        }
        
        if ($this->device != 'desktop') {
            unset($this->service_data);
        }
        $vasFiltering = json_encode(VariableParams::$mainMemBasedVasFiltering);
        $output = array(
            'title' => 'Add Astro',
            'topBlockMessage' => NULL,
            'currency' => $this->currency,
            'dividerText' => NULL,
            'backgroundText' => NULL,
            'serviceContent' => $this->service_data,
            'selectedMainServKey' => $this->mainMem,
            'selectedMainServDur' => $this->mainMemDur,
            'vasContent' => $this->custVAS,
            'topButton' => 'Skip',
            'bottom_message' => NULL,
            'bottomHelp' => NULL,
            'preSelectedESathiVas' => $preSelectedESathiVas,
            'preSelectedEValuePlusVas' => $preSelectedEValuePlusVas,
            'continueText' => "Continue",
            'device' => $this->device,
            'taxRate' => billingVariables::TAX_RATE,
            'userDetails' => $this->userDetails,
            'tracking_params' => $tracking_params,
            'filteredVasServices'=>$vasFiltering,
            'skipVasPageMembershipBased'=>json_encode(VariableParams::$skipVasPageMembershipBased),
            'preSelectVasGlobal'=>$preSelectVasGlobal
        );
        if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == "Android_app") {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '602', '62', '2', $this->device, $this->user_agent, implode(",", $this->curActServices));
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == "desktop") {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '2', '2', '2', $this->device, $this->user_agent, implode(",", $this->curActServices));
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device != "Android_app") {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '502', '52', '2', $this->device, $this->user_agent, implode(",", $this->curActServices));
        }
        
        return $output;
    }
    
    public function generateCartPageResponse($request) {
        $id = $this->mainMem;
        $this->mainServices = array();
        $this->validation;
        if (isset($this->mainMem) && !empty($this->mainMem)){
            $this->memApiFuncs->getMainMembershipDetails($this, $id);
        }
        
        $this->memApiFuncs->customizeVASDataForAPI($this->validation, 0, $this);

        $vas_text = NULL;
        $skip_text = "Continue";
        
        if ($this->mainMem == "X")
            $this->selectedVas = NULL;
        
        if (isset($this->selectedVas) && !empty($this->selectedVas)) {
            $this->totalVASPrice;$this->totalVASOrigPrice;$this->totalVASCount;$this->price;$this->totalVASDiscount;$this->vasServices = array();
            $this->memApiFuncs->getVASDetails($this);
        } 
        else
            $this->vasServices = NULL;
        
        if (count($this->vasServices) == 1 && empty($this->mainMem))
            $this->vasServices[0]['remove_text'] = NULL;
      
        if (isset($this->mainServices) && !empty($this->mainServices)) {
            $finalCartPrice = $this->mainServices['price'] + $this->totalVASPrice;
            $finalCartDiscount = $this->mainServices['discount_given'] + $this->totalVASDiscount;
            $this->mainServices['price'] = $this->mainServices['display_price'];
            $cart_items['main_memberships'] = array(
                $this->mainServices
            );
        } 
        else {
            $finalCartPrice = $this->totalVASPrice;
            $finalCartDiscount = $this->totalVASDiscount;
            $cart_items['main_memberships'] = NULL;
        }
        unset($this->mainServices['display_price']);
        unset($this->mainServices['discount_given']);
        
        $cart_items['vas_memberships'] = $this->vasServices;
        $price = $finalCartPrice;
        
        if ($finalCartDiscount > 0) {
            if (isset($this->mainServices['orig_price'])) {
                $actualTotalPrice = "" . number_format($this->mainServices['orig_price'] + $this->totalVASOrigPrice, 2, '.', ',');
                $discount = '' . number_format((($this->mainServices['orig_price'] + $this->totalVASOrigPrice) - $finalCartPrice) , 2, '.', ',');
            } 
            else {
                $actualTotalPrice = '' . number_format($finalCartPrice + $finalCartDiscount, 2, '.', ',');
                $discount = '' . number_format($finalCartDiscount, 2, '.', ',');
            }
        } 
        else
            $discount = NULL;
        
        if ($this->mainMemDur == "L")
            $allMemberships = $this->mainMem . "L";
        else
            $allMemberships = $this->mainMem . $this->mainMemDur;
        
        $mainMembership = $allMemberships;
        
        if (isset($this->selectedVas) && !empty($this->selectedVas)) {
            $vasImpression = $this->selectedVas;
            $allMemberships.= "," . $vasImpression;
        }
        
        $this->memApiFuncs->removeExtraParamsFromVAS($this->custVAS, $this);
        
        if ($this->mainMemDur == 1188)
            $sub_dur = 'L';
        else
            $sub_dur = $this->mainMemDur;
        
       	$cart_tax_text = "PRICE INCLUDES " . billingVariables::TAX_RATE . "% ". billingVariables::TAX_TEXT . billingVariables::TAX_TEXT_SB;
        
        $this->couponParams = $this->memApiFuncs->getCouponCodeDetails($this);
        
        $tracking_params = array(
            "source" => "603",
            "tab" => "63",
            "pgNo" => "3",
            "device" => $this->device,
            "allMemberships" => $allMemberships,
            "mainMembership" => $mainMembership,
            "vasImpression" => $vasImpression
        );
        
        if ($this->currency == "DOL")
            $continueText = "You Pay USD " . number_format($price, 2, '.', ',') . "";
        else
            $continueText = "Continue";
        
        $couponDiscount = 0;
        if ($discount == 0){
            $discount = NULL;
        }
        else if($discount > 0 && is_array($this->mainServices) && $this->mainServices['actual_upgrade_price']){
            $couponDiscount = $this->mainServices['actual_upgrade_price']-$finalCartPrice;
        }
        
        $nameOfUserObj = new incentive_NAME_OF_USER();
        $userName = $nameOfUserObj->getName($apiObj->profileid);
        
        $output = array(
            'title' => 'Your Cart',
            'subscription_id' => $this->mainMem,
            'subscription_duration' => $sub_dur,
            'currency' => $this->currency,
            'cart_items' => $cart_items,
            'totalVasPrice' => '' . number_format($this->totalVASPrice, 2, '.', ',') ,
            'totalVasCount' => '' . $this->totalVASCount,
            'cart_tax_text' => $cart_tax_text,
            'cart_bottom_text' => 'And, yes we have a 100% safe & secure payment gateway because your worry is our concern too.',
            'cart_price_text' => "You Pay",
            'cart_price' => "" . number_format($price, 2, '.', ',') ,
            'apply_coupon_text' => $this->couponParams->apply_coupon_text,
            'coupon_discount_text' => $this->couponParams->discount_text,
            'coupon_success' => "" . $this->couponParams->coupon_success,
            'actual_total_text' => "Total",
            'actual_total_price' => $actualTotalPrice,
            'cart_discount' => $discount,
            'coupon_discount' => $couponDiscount,
            'preSelectedESathiVas' => $preSelectedESathiVas,
            'preSelectedEValuePlusVas' => $this->preSelectedEValuePlusVas,
            'continueText' => $continueText,
            'device' => $this->device,
            'taxRate' => billingVariables::TAX_RATE,
            'userDetails' => $this->userDetails,
            'tracking_params' => $tracking_params,
            'proceed_text' => $skip_text,
            'username' => $userName,
            'skipVasPageMembershipBased'=>json_encode(VariableParams::$skipVasPageMembershipBased),
            'upgradeMem'=>$this->upgradeMem
        );
        
        if ($this->device == 'desktop') {
            $chequeData = $this->generateChequePickupResponse($request);
            $request->setParameter('mainMembership', $this->mainMem . $this->mainMemDur);
            $request->setParameter('vasImpression', $this->selectedVas);
            $paymentOptionsData = $this->generatePaymentOptionsPageResponse($request, $chequeData);
            
            if(is_array($this->discountTypeInfo) && $this->discountTypeInfo["TYPE"]==discountType::LIGHTNING_DEAL_DISCOUNT){
                $output['payAtBranchesData'] = NULL;
            }
            else if ($this->currency == 'RS') {
                $branchData = $this->generatePayAtBranchesPageResponse();
                $output['payAtBranchesData'] = $branchData;
            }
            $output['chequeData'] = $chequeData;
            $output['paymentOptionsData'] = $paymentOptionsData;
        }
        
        //$this->memApiFuncs->setTrackingProgressParams($this,$allMemberships, $mainMembership, $vasImpression);
        
        
        if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == 'Android_app') {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '603', '63', '3', $this->device, $this->user_agent, $allMemberships, $mainMembership, $vasImpression);
        }
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == 'desktop') {
            //tracking for upgrade membership page
            if($this->upgradeMem || in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $this->memHandlerObj->trackMembershipProgress($this->userObj,'3','3','3',$this->device, $this->user_agent,$allMemberships, $mainMembership, $vasImpression,0, 0, 0, '', '', '', '',$this->upgradeMem,$this);
            }
            else{
                $this->memHandlerObj->trackMembershipProgress($this->userObj, '3', '3', '3', $this->device, $this->user_agent, $allMemberships, $mainMembership, $vasImpression);
            }
            //die(print_r(array($this->userObj, '3', '3', '3', $this->device, $this->user_agent, $allMemberships, $mainMembership, $vasImpression))); 
        }
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device != 'Android_app') {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '503', '53', '3', $this->device, $this->user_agent, $allMemberships, $mainMembership, $vasImpression);
        }
        //print_r($output);die;
        return $output;
    }
    
    public function generateBackendDiscountPageResponse($request) {
        $profileCheckSum = $this->profilechecksum;
        $profileCheckSumArray = explode("i", $profileCheckSum);
        $profileid = $profileCheckSumArray[1];
        $idCheckSum = $this->reqid;
        $idCheckSumArray = explode("i", $idCheckSum);
        $idBackend = $idCheckSumArray[1];
        if (md5($idBackend) == $idCheckSumArray[0]) {
            list($allMemberships, $discountBackend, $profileid) = $this->memHandlerObj->handleBackendCase($idBackend, $profileid);
        }
        $this->discountBackend = $discountBackend;
        $this->fromBackend = 1;
        $this->backendId = $idBackend;
        $this->backendCheckSum = $idCheckSum;
        if ($allMemberships) {
            $memArray = explode(",", $allMemberships);
            for ($p = 0; $p < count($memArray); $p++) {
                if (strpos($memArray[$p], "main") !== false) {
                    $subMem = substr($memArray[$p], 4);
                    $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $subMem);
                    $this->mainMem = $tempMem[0];
                    if (strpos($this->mainMem, "L")) {
                        $this->mainMemDur = "L";
                        $this->mainMem = substr($this->mainMem, 0, -1);
                    } 
                    else {
                        $this->mainMemDur = $tempMem[1];
                    }
                }
            }
        }
        if ($this->mainMem == "E") {
            $this->mainMem = "ESP";
        }
        
        $this->memID = $this->mainMem;
        $id = $this->mainMem;
        if (isset($this->mainMem) && !empty($this->mainMem)) {
            $mainServices['sideTitle'] = "Main Membership";
            $mainServices['service_name'] = $this->memHandlerObj->getUserServiceName($id);
            if ($this->mainMemDur == "L") {
                $subId = $this->mainMem . 'L';
                $mainServices['service_duration'] = 'Unlimited Months';
            }
            if ($this->fest == 1 && $this->mainMem != "X" && $this->device != "iOS_app") {
                $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
                $addedDur = $festOffrLookup->getDurationDiscountOnService($this->mainMem . $this->mainMemDur);
                $addedPerc = $festOffrLookup->getPercDiscountOnService($this->mainMem . $this->mainMemDur);
                unset($festOffrLookup);
                if (!empty($addedDur) && $addedDur > 0) {
                    $addonMonths = $this->festDurBanner[$this->mainMem][$this->mainMemDur];
                }
            }
            if ($this->mainMemDur == "L") {
                $monthsPrependVal = "Unlimited";
            } 
            else {
                $monthsPrependVal = $this->mainMemDur;
            }
            if (!isset($subId)) {
                $subId = $this->mainMem . $this->mainMemDur;
            }
            if ($addonMonths) {
                $mainServices['service_duration'] = $monthsPrependVal . " " . $addonMonths;
            } 
            else {
                if (!isset($mainServices['service_duration'])) {
                    $mainServices['service_duration'] = $monthsPrependVal . ' Months';
                }
            }
            $mainServices['service_contacts'] = $this->allMainMem[$id][$subId]['CALL'] . ' Contacts To View';
            $mainServices['standard_price'] = $this->allMainMem[$id][$subId]['PRICE'];
          
            $mainServices['orig_price'] = $mainServices['standard_price'];
            $mainServices['orig_price_formatted'] = number_format($mainServices['standard_price'], 2, '.', ',');
            
            $mainServices['discount_given'] = "" . round(($this->allMainMem[$id][$subId]['PRICE']) * ($this->discountBackend / 100) , 2);
            $mainServices['price'] = "" . ($mainServices['standard_price'] - $mainServices['discount_given']);
            
            if ($mainServices['standard_price'] != NULL) {
                $mainServices['price_strike'] = number_format($mainServices['standard_price'], 2, '.', ',');
            } 
            else {
                $mainServices['price_strike'] = NULL;
            }
            $mainServices['display_price'] = number_format($mainServices['price'], 2, '.', ',');
            if ($mainServices['service_name'] == "eSathi") {
                if ($this->mainMemDur == "L") {
                    $dur = '12';
                } 
                else {
                    $dur = $this->mainMemDur;
                }
            }
            $vas_text = NULL;
            $skip_text = "Continue";
        }
        
        $this->memApiFuncs->customizeVASDataForAPI(0, $this->discountBackend, $this);
        $this->backendVAS = array_slice($memArray, 1);
        foreach ($this->backendVAS as $kk => $vv) {
            if (!empty($vv)) {
                $actualVASId = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $vv);
                $actualVASId = $actualVASId[0];
                $temp[$actualVASId] = $vv;
            }
        }
        $this->backendVAS = $temp;
        if (!empty($this->backendVAS)) {
            if (!empty($this->selectedVas)) {
                $this->selectedVas.= "," . implode(',', array_values($this->backendVAS));
            } 
            else {
                $this->selectedVas = implode(',', array_values($this->backendVAS));
            }
        } else if (empty($this->backendVAS) && ($this->mainMem == "NCP" || $this->mainMem == "ESP")) {
            $this->selectedVas = array();
            if ($this->mainMemDur == "L") {
                $dur = '12';
            } 
            else {
                $dur = $this->mainMemDur;
            }
            if ($this->mainMem == "ESP") {
                foreach (VariableParams::$eSathiAddOns as $addons) {
                    $this->selectedVas[] = $addons.$dur;
                }
            }
            unset($addons);
            if ($this->mainMem == "NCP") {
                foreach (VariableParams::$jsExclusiveComboAddon as $addons) {
                    $this->selectedVas[] = $addons.$dur;
                }
            }
            unset($addons);
            $this->selectedVas = implode(',', array_values($this->selectedVas));
        }
        //Here refracting
        if (isset($this->selectedVas) && !empty($this->selectedVas)) {
            $totalVASPrice = 0;
            $totalVASOrigPrice = 0;
            $totalVASCount = 0;
            $vasArr = explode(",", $this->selectedVas);
            foreach ($vasArr as $key => $val) {
                $vasID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $val);
                $v['service_name'] = VariableParams::$newApiVasNamesAndDescription[$vasID[0]]['name'];
                if ($vasID[0] == "I") {
                    $v['service_duration'] = $vasID[1] . ' Profiles';
                } 
                else {
                    $v['service_duration'] = $vasID[1] . ' Months';
                }
                if (is_array($this->custVAS)) {
                    foreach ($this->custVAS as $kk => $vv) {
                        if ($vv['vas_key'] == $vasID[0]) {
                            foreach ($vv['vas_options'] as $x => $z) {
                                if ($z['id'] == $val) {
                                    //JSC-2435, additional check for Astro so that when NCP and astro are given
                                    // through backend link, price is not set to zero.
                                    if (($this->mainMem == "NCP" && ($vasID[0] != 'A')) || $this->mainMem == "ESP") {
                                        $v['vas_id'] = $z['id'];
                                        $v['price'] = 0;
                                        $v['orig_price'] = 0;
                                        $v['orig_price_formatted'] = 0;
                                        $v['discount_given'] = 0;
                                        $price = 0;
                                        $v['vas_price'] = 0;
                                        $v['vas_price_strike'] = 0;
                                    } else {
                                        $v['vas_id'] = $z['id'];
                                        $v['price'] = $z['price'];
                                        $v['orig_price'] = $z['orig_price'];
                                        $v['orig_price_formatted'] = $z['orig_price_formatted'];
                                        $v['discount_given'] = number_format($z['discount_given'], 2, '.', ',');
                                        $price = @preg_split('/\\D/', $v['price'], -1, PREG_SPLIT_NO_EMPTY);
                                        $v['vas_price'] = "" . number_format($price[0], 2, '.', ',');
                                        $v['vas_price_strike'] = $z['vas_price_strike'];
                                    }
                                    $v['remove_text'] = NULL;
                                    $v['change_text'] = NULL;
                                }
                            }
                        }
                    }
                }
                $totalVASOrigPrice+= $v['orig_price'];
                $totalVASPrice+= $v['price'];
                $totalVASCount+= 1;
                $totalVASDiscount+= $v['discount_given'];
                unset($v['discount_given']);
                unset($v['price']);
                $vasServices[] = $v;
            }
        } 
        else {
            $vasServices = NULL;
        }
        
        if (isset($mainServices)) {
            $finalCartPrice = $mainServices['price'] + $totalVASPrice;
            $finalCartDiscount = $mainServices['discount_given'] + $totalVASDiscount;
            $mainServices['price'] = $mainServices['display_price'];
            $cart_items['main_memberships'] = array(
                $mainServices
            );
        } 
        else {
            $finalCartPrice = $totalVASPrice;
            $finalCartDiscount = $totalVASDiscount;
            $cart_items['main_memberships'] = NULL;
        }
        
        unset($mainServices['display_price']);
        unset($mainServices['discount_given']);
        
        $cart_items['vas_memberships'] = $vasServices;
        $price = $finalCartPrice;
        
        if ($finalCartDiscount > 0) {
            if (isset($mainServices['orig_price'])) {
                $actualTotalPrice = "" . number_format($mainServices['orig_price'] + $totalVASOrigPrice, 2, '.', ',');
                $discount = '' . number_format((($mainServices['orig_price'] + $totalVASOrigPrice) - $finalCartPrice) , 2, '.', ',');
            } 
            else {
                $actualTotalPrice = '' . number_format($finalCartPrice + $finalCartDiscount, 2, '.', ',');
                $discount = '' . number_format($finalCartDiscount, 2, '.', ',');
            }
        } 
        else {
            $discount = NULL;
        }
        
        if ($this->mainMemDur == 1188) {
            $mainMembership = $this->mainMem . "L";
        } 
        else {
            $mainMembership = $this->mainMem . $this->mainMemDur;
        }
        
        if (isset($this->selectedVas) && !empty($this->selectedVas)) {
            $vasImpression = $this->selectedVas;
            if(isset($mainMembership) && !empty($mainMembership)){
            	$allMemberships = $mainMembership . "," . $vasImpression;
            } else {
            	$allMemberships = $vasImpression;
            }
        } 
        else {
            $allMemberships = $mainMembership;
        }
        
        $this->memApiFuncs->removeExtraParamsFromVAS($this->custVAS, $this);
        
        if ($this->mainMemDur == 1188) {
            $sub_dur = 'L';
        } 
        else {
            $sub_dur = $this->mainMemDur;
        }

        if(empty($sub_dur)){
        	$sub_dur = '';	
        }
        
        $cart_tax_text = "PRICE INCLUDES " . billingVariables::TAX_RATE . "% ". billingVariables::TAX_TEXT. billingVariables::TAX_TEXT_SB;
        
        $this->apply_coupon_text = NULL;
        $this->custVAS = null;
        
        $tracking_params = array(
            "source" => "603",
            "tab" => "63",
            "pgNo" => "3",
            "device" => $this->device,
            "allMemberships" => $allMemberships,
            "mainMembership" => $mainMembership,
            "vasImpression" => $vasImpression
        );
        
        $output = array(
            'title' => 'Your Cart',
            'subscription_id' => $this->mainMem,
            'subscription_duration' => $sub_dur,
            'currency' => $this->currency,
            'cart_items' => $cart_items,
            'totalVasPrice' => '' . number_format($totalVASPrice, 2, '.', ',') ,
            'totalVasCount' => '' . $totalVASCount,
            'cart_tax_text' => $cart_tax_text,
            'cart_bottom_text' => 'And, yes we have a 100% safe & secure payment gateway because your worry is our concern too.',
            'cart_price_text' => "You Pay",
            'cart_price' => "" . number_format($price, 2, '.', ',') ,
            'apply_coupon_text' => $this->apply_coupon_text,
            'coupon_discount_text' => $discount_text,
            'coupon_success' => "" . $coupon_success,
            'actual_total_text' => "Total",
            'actual_total_price' => $actualTotalPrice,
            'cart_discount' => $discount,
            'preSelectedESathiVas' => $preSelectedESathiVas,
            'preSelectedEValuePlusVas' => $preSelectedEValuePlusVas,
            'continueText' => $continueText,
            'device' => $this->device,
            'taxRate' => billingVariables::TAX_RATE,
            'userDetails' => $this->userDetails,
            'tracking_params' => $tracking_params,
            'proceed_text' => $skip_text,
            'backendLink' => array(
                'fromBackend' => $this->fromBackend,
                'checksum' => $this->profilechecksum,
                'profilechecksum' => $this->profilechecksum,
                'reqid' => $this->reqid
            ),
            'continueText' => "Continue",
            'username' => $userName,
            'skipVasPageMembershipBased'=>json_encode(VariableParams::$skipVasPageMembershipBased)
        );
        
        if ($this->device == 'desktop') {
            $chequeData = $this->generateChequePickupResponse($request);
            $request->setParameter('mainMembership', $this->mainMem . $this->mainMemDur);
            $request->setParameter('vasImpression', $this->selectedVas);
            $paymentOptionsData = $this->generatePaymentOptionsPageResponse($request, $chequeData);
            if ($this->currency == 'RS') {
                $branchData = $this->generatePayAtBranchesPageResponse();
                $output['payAtBranchesData'] = $branchData;
            }
            $output['chequeData'] = $chequeData;
            $output['paymentOptionsData'] = $paymentOptionsData;
        }
        
        if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == 'Android_app') {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '603', '63', '3', 'discount_link', $this->user_agent, $allMemberships, $mainMembership, $vasImpression, $finalCartDiscount, $finalCartPrice, 63, 'F');
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == 'desktop') {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '3', '3', '3', 'discount_link', $this->user_agent, $allMemberships, $mainMembership, $vasImpression, $finalCartDiscount, $finalCartPrice, 13, 'F');
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device != 'Android_app') {
            $this->memHandlerObj->trackMembershipProgress($this->userObj, '503', '53', '3', 'discount_link', $this->user_agent, $allMemberships, $mainMembership, $vasImpression, $finalCartDiscount, $finalCartPrice, 53, 'F');
        }
       
        return $output;
    }
    
    public function generatePaymentOptionsPageResponse($request, $pageSixResponse = NULL) {
        $payHandlerObj = new PaymentHandler();
        $netBanks = $payHandlerObj->getNetBanks();
        $paymentMode = paymentOption::$paymentMode;
        $ccCardType = paymentOption::$ccCardType;
        $dbCardType = paymentOption::$dbCardType;
        $walletType = paymentOption::$walletType;
        $helpText = VariableParams::$apiPageFiveHelpText;
        $serviceTax = billingVariables::TAX_RATE;

        if (!isset($this->mainMembership) || $this->device == 'desktop') {
        	$this->mainMembership = $this->mainMem . $this->mainMemDur;
        }
        if (!isset($this->vasImpression) || $this->device == 'desktop') {
        	$this->vasImpression = $this->selectedVas;
        }
       
        list($totalCartPrice, $totalCartDiscount) = $this->memApiFuncs->calculateCartPrice($request, $this);
    
        $creditCardIconMapping = paymentOption::$creditCardIconMapping;
        $debitCardIconMapping = paymentOption::$debitCardIconMapping;
        $walletCardIconMapping = paymentOption::$walletCardIconMapping;
        if ($this->profileid) $courier_visible = $payHandlerObj->getPickup($this->profileid);

        if ($this->currency == 'DOL') {
            unset($paymentMode['NB']);
            unset($paymentMode['CSH']);
        } else {
        	unset($paymentMode['PP']);
        }
        
        if ($totalCartPrice > 10000) {
            unset($paymentMode['CSH']);
        }
        
        if ($totalCartPrice > 6000) {
            unset($walletType['wallet7']);
        }
        
        foreach ($ccCardType as $key => $value) {
            $ccCardArr['ic_id'] = $creditCardIconMapping[$key];
            $ccCardArr['name'] = $value['name'];
            $ccCardArr['mode_option_id'] = $key;
            $paymentCCCardArr[] = $ccCardArr;
        }
        
        foreach ($dbCardType as $key => $value) {
            $dbCardArr['ic_id'] = $debitCardIconMapping[$key];
            $dbCardArr['name'] = $value['name'];
            $dbCardArr['mode_option_id'] = $key;
            $paymentDBCardArr[] = $dbCardArr;
        }
        
        foreach ($walletType as $key => $value) {
            $walletArr['ic_id'] = $walletCardIconMapping[$key];
            $walletArr['name'] = $value['name'];
            $walletArr['mode_option_id'] = $key;
            $walletCardArr[] = $walletArr;
        }
        foreach ($netBanks as $key => $value) {
            $bankArr['ic_id'] = NULL;
            $bankArr['name'] = $value;
            $bankArr['mode_option_id'] = $key;
            $bankNameArr[] = $bankArr;
        }

    	// Paypal has only one option, itself !
        $ppArr['ic_id'] = 'rv2_paypal';
        $ppArr['name'] = 'PayPal';
        $ppArr['mode_option_id'] = 'paypal';
        $paypalArr[] = $ppArr;
    
        
        $optionsArr = array(
            'CR' => $paymentCCCardArr,
            'DR' => $paymentDBCardArr,
            'NB' => $bankNameArr,
            'CSH' => $walletCardArr,
            'PP' => $paypalArr
        );
        
        foreach ($paymentMode as $key => $value) {
            $paymentModeArr['mode_id'] = $key;
            $paymentModeArr['name'] = $value;
            if ($key == 'CR' || $key == 'DR') {
                $hintText = 'Select your card';
                $paymentTitle = 'Select your card';
            } 
            else if ($key == 'NB') {
                $hintText = 'Select your bank';
                $paymentTitle = 'Select your bank';
            } 
            else if ($key == 'CSH') {
                $hintText = 'Select your wallet';
                $paymentTitle = 'Select your wallet';
            }
            else if ($key == 'PP') {
                $hintText = 'Select';
                $paymentTitle = 'Select';
            }
            $paymentModeArr['hint_text'] = $hintText;
            $paymentModeArr['payment_title'] = $paymentTitle;
            $paymentModeArr['continue_text'] = 'Continue';
            $paymentCard = $optionsArr[$key];
            $paymentModeArr['payment_options'] = $paymentCard;
            $paymentOptions[] = $paymentModeArr;
        }
        
        if (empty($pageSixResponse)) {
        	$pageSixResponse = $this->generateChequePickupResponse($request);
        }
        
        $cashChequePickup = array(
            "name" => "Cash/Cheque Pickup",
            "hint_text" => "No charge",
            "payment_title" => $pageSixResponse['payment_title'],
            "topBlockMessage" => $pageSixResponse['topBlockMessage'],
            "options" => $pageSixResponse['options'],
            "bottom_text" => $pageSixResponse['bottom_text'],
            "continue_text" => $pageSixResponse['bottom_text']
        );
        
        $title = 'Select Payment Method';
        $serviceTaxContent = "PRICE INCLUDES {$serviceTax}% ". billingVariables::TAX_TEXT . billingVariables::TAX_TEXT_SB;
        
        if ($this->currency == "RS") {
            $callUs = array(
                "callText" => $helpText['text2'],
                "value" => "18004196299",
                "action" => 'CALL'
            );
        } 
        else {
            $callUs = array(
                "callText" => $helpText['text2'],
                "value" => "+911204393500",
                "action" => 'CALL'
            );
        }
        
        if ($this->currency == "RS") {
            $requestCallBack = array(
                "title" => "Request Callback",
                "phone_number" => "1800-419-6299",
                "call_text" => "Call Us (Toll Free India)",
                "value" => "18004196299",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=" . $this->channel . "&callbackSource=" . $this->callbackSource
            );
        } 
        else {
            $requestCallBack = array(
                "title" => "Request Callback",
                "phone_number" => "+911204393500",
                "call_text" => "Call Us (India)",
                "value" => "+911204393500",
                "or_text" => "OR",
                "request_callback" => "Request Callback",
                "params" => "processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=" . $this->profileid . "&device=" . $this->device . "&channel=" . $this->channel . "&callbackSource=" . $this->callbackSource
            );
        }
        
        if ($this->mainMem == "ESP") {
            $eSathiFlag = 1;
        }
        
        if ($this->fromBackend == 1) {
            $this->device == 'discount_link';
        }
        
        $tracking_params = array(
            "source" => "604",
            "tab" => "64",
            "pgNo" => "4",
            "device" => $this->device,
            "allMemberships" => $this->allMemberships,
            "mainMembership" => $this->mainMembership,
            "vasImpression" => $this->vasImpression
        );
        
        if ($courier_visible != 'Y') $cashChequePickup = NULL;
        
        if ($this->currency == "DOL") {
            $cashChequePickup = NULL;
        }
        
        if(is_array($this->discountTypeInfo) && $this->discountTypeInfo["TYPE"]==discountType::LIGHTNING_DEAL_DISCOUNT){
            $cashChequePickup = NULL;
            $hidePayAtBranchesOption = true;
        }
        else{
            $hidePayAtBranchesOption = false;
        }
        
        if ($this->currency == "DOL") {
            $proceedText = "You Pay USD " . number_format($totalCartPrice, 2, '.', ',') . "";
        } 
        else {
            $proceedText = "Continue";
        }
       
        $output = array(
            'title' => $title,
            'currency' => $this->currency,
            'you_pay_text' => "You Pay",
            'you_pay_price' => number_format($totalCartPrice, 2, '.', ',') ,
            'tax_text' => "$serviceTaxContent",
            'payment_options' => $paymentOptions,
            'cash_cheque_pickup' => $cashChequePickup,
            'bottom_text' => $helpText['text1'],
            'call_us' => $callUs,
            'requestCallBack' => $requestCallBack,
            'proceed_text' => $proceedText,
            'pay_text1' => $helpText['text3'],
            'pay_text2' => $helpText['text4'],
            'eSathiFlag' => $eSathiFlag,
            'couponID' => $this->couponCode,
            'device' => $this->device,
            'tracking_params' => $tracking_params,
            'userProfile' => $this->profileid,
            'upgradeMem' => $this->upgradeMem,
            'hidePayAtBranchesOption'=>$hidePayAtBranchesOption,
            'backendLink' => array(
                'fromBackend' => $this->fromBackend,
                'checksum' => $this->profilechecksum,
                'profilechecksum' => $this->profilechecksum,
                'reqid' => $this->reqid
            )
        );
        
        if (empty($this->getAppData) && empty($this->trackAppData) && $this->device != 'Android_app' && $this->device != 'desktop') {
            if ($this->fromBackend == 1) {
                $this->memHandlerObj->trackMembershipProgress($this->userObj, '504', '54', '3', $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression, $totalCartDiscount, $totalCartPrice, 54, 'F');
            } 
            else {
                if(($this->upgradeMem && in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])) && ($this->device == "mobile_website" || $this->device == "JSAA_mobile_website")){
                    $this->memHandlerObj->trackMembershipProgress($this->userObj,'504','54','3',$this->device, $this->user_agent,$this->allMemberships, $this->mainMembership, $this->vasImpression,0, 0, 0, '', '', '', '',$this->upgradeMem);
                }
                else{
                    $this->memHandlerObj->trackMembershipProgress($this->userObj, '504', '54', '3', $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression);
                }
            }
        } 
        else if (empty($this->getAppData) && empty($this->trackAppData) && $this->device == 'Android_app') {
            if ($this->fromBackend == 1) {
                $this->memHandlerObj->trackMembershipProgress($this->userObj, '604', '64', '3', $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression, $totalCartDiscount, $totalCartPrice, 64, 'F');
            } 
            else {
                if($this->upgradeMem && in_array($this->upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                    $this->memHandlerObj->trackMembershipProgress($this->userObj,'604','64','3',$this->device, $this->user_agent,$this->allMemberships, $this->mainMembership, $this->vasImpression,0, 0, 0, '', '', '', '',$this->upgradeMem);
                }
                else{
                    $this->memHandlerObj->trackMembershipProgress($this->userObj, '604', '64', '3', $this->device, $this->user_agent, $this->allMemberships, $this->mainMembership, $this->vasImpression);
                }
            }
        }
        
        return $output;
    }
    
    public function generateChequePickupResponse($request) {
        $payHandlerObj = new PaymentHandler();
        $prefCities = $payHandlerObj->getNearByCities('Y');
        $pickupParams = VariableParams::$apiPageSixParams;
        
        $startDate = date('Y-m-d', time() + 1 * 86400);
        $dateDropdown = $this->memApiFuncs->getDateDropDown($startDate, 6);
        
        foreach ($dateDropdown as $key => $value) {
            $date['id'] = $key;
            $date['name'] = $value;
            $dateArr[] = $date;
        }
        
        foreach ($prefCities as $key => $value) {
            $city['id'] = $key;
            $city['name'] = $value;
            $cityArr[] = $city;
        }
        
        foreach ($pickupParams as $key => $value) {
            $paramsArr['id'] = $value['id'];
            $paramsArr['name'] = $value['name'];
            $paramsArr['hint_text'] = $value['hint_text'];
            if ($value['id'] == 'city') $paramsArr['input_data'] = $cityArr;
            else if ($value['id'] == 'date') $paramsArr['input_data'] = $dateArr;
            else $paramsArr['input_data'] = NULL;
            $pickupParamsArr[] = $paramsArr;
        }
        
        $title = 'Cash/Cheque Pickup';
        $topBlockMessage = 'Please provide details for Free cheque pickup';
        $bottomMessage = 'Pickup requests will be processed in 24 hours';
        $overlay_city_title = 'Select Your City';
        $overlay_date_title = 'Select Your Date';
        $overlay_proceed_text = 'Continue';
        
        $output = array(
            'payment_title' => $title,
            'topBlockMessage' => $topBlockMessage,
            'overlay_city_title' => $overlay_city_title,
            'overlay_date_title' => $overlay_date_title,
            'overlay_proceed_text' => $overlay_proceed_text,
            'options' => $pickupParamsArr,
            'bottom_text' => $bottomMessage,
            'proceed_text' => 'Submit',
            'device' => $this->device
        );
        return $output;
    }
    
    public function generateChequePickupSuccessResponse($request) {
        $payHandlerObj = new PaymentHandler();
        $profileDetails = $payHandlerObj->getPaymentCollectDetails($this->profileid);
        
        $dateTime = $profileDetails['DATE'] . " between 9:00 am to 6:00 pm";
        $title = 'Cash/Cheque Pickup';
        $topBlockMessage = 'Jeevansathi executive will contact';
        $text1 = "Your membership will get activated once";
        $text2 = "Jeevansathi executive collects";
        $text3 = " from you";
        $city = $profileDetails['CITY'];
        $customerDetail = $profileDetails['NAME'] . "<br>" . $profileDetails['PHONE'] . "<br>" . $profileDetails['ADDRESS'] . "<br><br>" . $dateTime . "<br>";
        
        $output = array(
            'title' => $title,
            'topBlockMessage' => $topBlockMessage,
            'customer_detail' => $customerDetail,
            'amount' => $profileDetails['AMOUNT'],
            'currency' => $this->currency,
            'text1' => $text1,
            'text2' => $text2,
            'text3' => $text3,
            'city' => $city,
            'name' => $profileDetails['NAME'],
            'phoneNo' => $profileDetails['PHONE'],
            'landline' => $profileDetails['PHONE_RES'],
            'mobile' => $profileDetails['PHONE_MOB'],
            'ref_number' => $profileDetails['REQ_ID'],
            'address' => $profileDetails['ADDRESS'],
            'dateTime' => $dateTime,
            'proceed_text' => 'OK',
            'userDetails' => $this->userDetails,
            'device' => $this->device
        );
        
        return $output;
    }
    
    public function generatePayAtBranchesPageResponse() {
        $payHandlerObj = new PaymentHandler();
        $states = $payHandlerObj->getStates();
        $cityRes = $payHandlerObj->getCityRes($this->profileid);
        $cityArr = FieldMap::getFieldLabel("city", 1, 1);
        $cityName = $cityArr[$cityRes];
        if($cityName=="New Delhi")
            $userCity="Delhi";
        else
            $userCity=$cityName;
        foreach ($states as $key => $val) {
        	$statesArr[] = $val['STATE'];
        }
        $branchesArrRaw = $payHandlerObj->getBranchesInCityArr($statesArr);
        foreach($branchesArrRaw as $key=>$val){
        	$branches[$val['STATE']][] = $val;
        }
        foreach ($branches as $key => $val) {
            foreach ($val as $kk => $vv) {
                $temp = explode("(", $vv['PHONE']);
                $temp[0] = str_replace("//", "/", $temp[0]);
                $temp[0] = str_replace("/", ", ", $temp[0]);
                if (count($temp) > 1) {
                    $temp[1] = "(" . $temp[1];
                    $temp = $temp[0] . $temp[1];
                } 
                else {
                    $temp = $temp[0];
                }
                $branches[$key][$kk]['PHONE'] = $temp;
                $temp2 = explode("(", $vv['MOBILE']);
                $temp2[0] = str_replace("//", "/", $temp2[0]);
                $temp2[0] = str_replace("/", ", ", $temp2[0]);
                if (count($temp2) > 1) {
                    $temp2[1] = "(" . $temp2[1];
                    $temp2 = $temp2[0] . $temp2[1];
                } 
                else {
                    $temp2 = $temp2[0];
                }
                $branches[$key][$kk]['MOBILE'] = $temp2;
            }
        }
        
        $output = array(
            'title' => 'Jeevansathi Branches',
            'branches_data' => $branches,
            'userCityRes' => $userCity,
            'goToHomeText' => 'Go To Home',
            'device' => $this->device,
            'continueText' => 'Select Another Payment Option'
        );
        
        return $output;
    }
    
    public function generateSuccessPageResponse() {
        $vowelsArr=array('a','i','e','o','u');
        $this->memApiFuncs->getTopBlockContent($this);
        $profileObj = LoggedInProfile::getInstance();
        $this->userDetails = $this->memHandlerObj->getUserData($this->profileid);
        $username = $profileObj->getUSERNAME();
        unset($profileObj);
        
        $order_content = $this->memApiFuncs->getOrderContent($this);
        //$checkMemUpgrade = $this->memHandlerObj->checkMemUpgrade($this->orderID,$profileObj->getPROFILEID(),true);
        
        if ($this->currency == 'RS') {
            $number_label = '1800-419-6299';
        } 
        else {
            $number_label = '+91-120-4393500';
        }

        if ($order_content['membership_plan']) {
            if(in_array($order_content['membership_plan'][0],$vowelsArr)){
        	   $order_message = 'You are now an ' . $order_content['membership_plan'] . ' member';
            }
            else{
                $order_message = 'You are now a ' . $order_content['membership_plan'] . ' member';
            }
        } else {
        	$order_message = 'Your services have been activated';
        }

        $output = array(
            'title' => 'Payment Successful',
            'topBlockMessage' => $this->topBlockMessage,
            'currency' => $order_content['currency'],
            'message' => $order_message,
            'order_content' => $order_content,
            'proceed_text' => 'Go To Home',
            'userDetails' => $this->userDetails,
            'device' => $this->device,
            'number_label' => $number_label
        );
        
        return $output;
    }
    
    public function generateFailurePageResponse() {
        $profileObj = LoggedInProfile::getInstance();
        $this->userDetails = $this->memHandlerObj->getUserData($this->profileid);
        $this->username = $profileObj->getUSERNAME();
        $title = "Payment Failed";
        $topHeading = "Online transaction unsuccessful";
        $message = $this->username . ", there was an error in processing your order. We will connect with you shortly.";
        $connect_message = "Meanwhile, you could ...";
        
        $order_content = $this->memApiFuncs->getOrderContent($this);
       
        $checkMemUpgrade = $this->memHandlerObj->checkMemUpgrade($this->orderID,$profileObj->getPROFILEID(),false);
        if ($this->currency == "RS") {
            $output = array(
                'title' => $title,
                'top_heading' => $topHeading,
                'failure_message' => $message,
                'currency' => $order_content['currency'],
                'connect_message' => $connect_message,
                'try_again' => 'Try Making Payment Again',
                'order_content' => $order_content,
                'toll_free' => array(
                    'label' => 'Call 1800-419-6299',
                    'value' => '18004196299',
                    'action' => 'CALL',
                    'number_label' => '1800-419-6299'
                ) ,
                'proceed_text' => 'Go To Home',
                'device' => $this->device,
                'checkMemUpgrade'=>$checkMemUpgrade
            );
        } 
        else {
            $output = array(
                'title' => $title,
                'top_heading' => $topHeading,
                'currency' => $order_content['currency'],
                'failure_message' => $message,
                'connect_message' => $connect_message,
                'try_again' => 'Try Making Payment Again',
                'order_content' => $order_content,
                'toll_free' => array(
                    'label' => 'Call +911204393500',
                    'value' => '+911204393500',
                    'action' => 'CALL',
                    'number_label' => '+91-120-4393500'
                ) ,
                'userDetails' => $this->userDetails,
                'proceed_text' => 'Go To Home',
                'device' => $this->device,
                'checkMemUpgrade'=>$checkMemUpgrade
            );
        }
        unset($profileObj);
        return $output;
    }
    
    public function generateOCBMessageResponse() {
        $serviceName = $this->activeServiceName;
        $ocbBannerObj = new billing_OCB_BANNER_MESSAGE();
        $ocbLowPriorityBannerObj = new billing_OCB_LOW_PRIPROTY_BANNER_MESSAGE();
        
        $todays_dt = date('Y-m-d H:i:s');
        $overrideMsg = $ocbBannerObj->getBannerMessage($todays_dt);
        unset($ocbBannerObj);
        $overrideLowPriorityMsg = $ocbLowPriorityBannerObj->getBannerMessage($todays_dt);
        unset($ocbLowPriorityBannerObj);
        //$vdodObj = new VariableDiscount();

        if ($this->profileid) {
            $validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid, $this->userType);
        } 
        else {
            $validityCheck = 1;
        }


        if ($validityCheck && is_array($overrideMsg) && !empty($overrideMsg['top']) && !empty($overrideMsg['bottom'])) {
            $top = $overrideMsg['top'];
            $bottom = json_decode(json_encode($overrideMsg['bottom']) , true);
            $pageId = $overrideMsg['pageId'];
        } 

        else if ($validityCheck && ($this->renewCheckFlag || $this->specialActive == 1 || $this->discountActive == 1 || $this->fest == 1 || ($this->upgradeActive == '1' && is_array($this->upgradePercentArr) && count($this->upgradePercentArr)>0) || $this->lightningDealActive == 1)) {
            if($this->lightningDealActive == '1'){
                $startingPlanData = $this->generateMembershipPlansStartingRange();
                $response = $this->generateLightningDealResponse($request, "MyjsOCB",$startingPlanData);
                $disc = $response['discount'];
                $startingPrice = $response['origStartingPrice'];
                $discountedPrice = $response['discountedPrice'];
                $currencySymbol = $response["currencySymbol"];
                $endTimeIST = date('H:i',strtotime('+9 hour 30 minutes',  strtotime($response['expiryDate'])));
                $top = "FLASH DEAL";
                //$bottom = "<p class='fontlig f16 pt5'>Plans starts @ <span class='strike cutcol1 opa70'><del>$currencySymbol$startingPrice</del></span> $currencySymbol$discountedPrice</p>";
                $bottom = "<p class='fontlig f16 pt5'>Prices starting @ $currencySymbol$discountedPrice</p>";
                if(MobileCommon::isApp()=='A'){
                    $extra = "<span class='f20'>$disc% OFF</span> on all plans";
                }else{
                    $extra = "<span class='f20'>$disc% OFF</span> on all plans till $endTimeIST (IST)";
                }
                $expiryDate = $response['expiryDate'];
                $valid = "Valid for";
            }
            else if($this->upgradeActive == '1'){
                $upgardeMemResponse = $this->generateUpgradeMemResponse($request,"MyjsOCB",$this);

                if(is_array($upgardeMemResponse)){
                    $top = "Upgrade to ".$upgardeMemResponse["upgradeMainMemName"];
                    $bottom = $upgardeMemResponse["upgradeCurrency"]."".$upgardeMemResponse["upgradeExtraPay"]." only. Valid till ".date('j M',strtotime($upgardeMemResponse["upgradeOfferExpiry"]));
                    $extra = $upgardeMemResponse["upgradeOCBBenefits"];
                }
            }
            else if ($this->renewCheckFlag) {
                if ($this->fest == 1) {
                    $top = "Get flat " . $this->renewalPercent . "% OFF";
                    $bottom = "or extra months if you renew before <strong>" . date("d M", strtotime($this->userObj->expiryDate)) . "</strong> !";
                } 
                else {
                    $top = "Get " . $this->renewalPercent . "% OFF";
                    $bottom = "if you renew your membership before <strong>" . date("d M", strtotime($this->userObj->expiryDate)) . "</strong> !";
                }
            } 
            elseif ($this->specialActive == 1) {
		$discountType ='VD';		
		$messageArr =$this->memHandlerObj->getOCBTextMessage($this->profileid, $discountType, $this->discPerc, $this->expiry_date, $this->fest);
		/*$discountVD = $vdodObj->getDiscountDetails($this->profileid);
		$maxVDDisc = $discountVD['MAX_DISCOUNT'];
		$flat = $discountVD['FLAT_DISCOUNT'];
                $this->discPerc = $maxVDDisc;
                if ($this->fest == 1) {
                    if ($flat) {
                        $top = "Get flat " . $this->discPerc . "% OFF";
                    } 
                    else {
                        $top = "Get upto " . $this->discPerc . "% OFF";
                    }
                    $bottom = "or extra months if you upgrade before <strong>" . date("d M", strtotime($this->expiry_date)) . "</strong> !";
                } 
                else {
                    if ($flat) {
                        $top = "Get flat " . $this->discPerc . "% OFF";
                    } 
                    else {
                        $top = "Get upto " . $this->discPerc . "% OFF";
                    }
                    $bottom = "if you upgrade your membership before <strong>" . date("d M", strtotime($this->expiry_date)) . "</strong> !";
                }*/
		$top =$messageArr['top'];
		$bottom =$messageArr['bottom'];
            } 
            elseif ($this->discountActive == 1) {
		$discountType ='CASH';
		$messageArr =$this->memHandlerObj->getOCBTextMessage($this->profileid, $discountType, $this->discPerc, $this->expiry_date, $this->fest);
		$top =$messageArr['top'];
		$bottom =$messageArr['bottom'];
            	/*$discountDisplayText = $vdodObj->getCashDiscountDispText($this->profileid,'small');
                $top = "Get " . $discountDisplayText . " " . $this->discPerc . "% OFF";
                $bottom = "if you upgrade your membership before <strong>" . date("d M", strtotime($this->expiry_date)) . "</strong> !";*/
            } 
            elseif ($this->fest == 1) {
                $top = "Get extra months";
                $bottom = "or attractive discounts if you upgrade before <strong>" . date("d M", strtotime($this->festEndDt)) . "</strong> !";
            }
            
            $pageId = "6";
        } 
        else if ($validityCheck && empty($top) && empty($bottom) && is_array($overrideLowPriorityMsg) && !empty($overrideLowPriorityMsg['top']) && !empty($overrideLowPriorityMsg['bottom'])) {
            if ($this->lowPriorityBannerDisplayCheck) {
                $top = $overrideLowPriorityMsg['top'];
                $bottom = json_decode(json_encode($overrideLowPriorityMsg['bottom']) , true);
                $pageId = $overrideLowPriorityMsg['pageId'];
            }
        }
        
        if (!$validityCheck && empty($top) && empty($bottom) && is_array($overrideLowPriorityMsg) && !empty($overrideLowPriorityMsg['top']) && !empty($overrideLowPriorityMsg['bottom'])) {
            if ($this->lowPriorityBannerDisplayCheck) {
                $top = $overrideLowPriorityMsg['top'];
                $bottom = json_decode(json_encode($overrideLowPriorityMsg['bottom']) , true);
                $pageId = $overrideLowPriorityMsg['pageId'];
            }
        }
        
        if (!empty($top) && !empty($bottom)) {
            $output = array(
                'membership_message' => array(
                    'top' => $top,
                    'bottom' => $bottom,
                    'pageId' => $pageId,
                    'extra' => $extra
                )
            );
            if(!empty($expiryDate)){
                $output['membership_message']['expiryDate'] = $expiryDate;
            }
            if(!empty($valid)){
                $output['membership_message']['valid'] = $valid;
            }
        } 
        else {
            $output = array(
                'membership_message' => NULL
            );
        }
        $memCacheObject = JsMemcache::getInstance();
        $memCacheObject->set($this->profileid . '_MEM_OBC_MESSAGE_API' . $this->appVersion, serialize($output) , 1800);
        return $output;
    }

    public function generateHamburgerMessageResponse() {
        $serviceName = $this->activeServiceName;
        if ($this->profileid) {
            $validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid, $this->userType,"hamburger");
        } 
        else {
            $validityCheck = 1;
        }

        $startingPlanData = $this->generateMembershipPlansStartingRange();
        
        $todays_dt = date('Y-m-d H:i:s');
        $vdodObj = new VariableDiscount();

        if ($validityCheck && ($this->renewCheckFlag || $this->specialActive == 1 || $this->discountActive == 1 || $this->fest == 1 || ($this->upgradeActive == '1' && is_array($this->upgradePercentArr) && count($this->upgradePercentArr)>0) || $this->lightningDealActive == 1)) {
            if($this->lightningDealActive == '1'){
                $response = $this->generateLightningDealResponse($request, "Hamburger",$startingPlanData);
                $disc = $response['discount'];
                $startingPrice = $response['origStartingPrice'];
                $discountedPrice = $response['discountedPrice'];
                $currencySymbol = $response["currencySymbol"];
                $top = "FLASH DEAL - FLAT $disc% OFF";
                $bottom = "Upgrade Membership";
                //$bottom = "$disc% OFF on all Plans.";
                //$bottom = "Upgrade Membership";
                $extra = "FD";
                $expiryDate = $response['expiryDate'];
            }
            else if($this->upgradeActive == '1'){
                $upgardeMemResponse = $this->generateUpgradeMemResponse($request,"Hamburger",$this);
                if(is_array($upgardeMemResponse)){
                    $top = "Upgrade to ".$upgardeMemResponse["upgradeMainMemName"];
                    $bottom = $upgardeMemResponse["upgradeCurrency"]."".$upgardeMemResponse["upgradeExtraPay"]." only. Valid till ".date('j M',strtotime($upgardeMemResponse["upgradeOfferExpiry"]));
                    $extra = $upgardeMemResponse["upgradeOCBBenefits"];
                    $expiryDate = $upgardeMemResponse["upgradeOfferExpiry"];
                }
            }
            else if ($this->renewCheckFlag) {
                if ($this->fest == 1) {
                    $top = "Extra Months & upto " . $this->renewalPercent . "% Off till " . date("d M", strtotime($this->userObj->expiryDate)) . "!";
                } 
                else {
                    $top = "Flat " . $this->renewalPercent . "% Off till " . date("d M", strtotime($this->userObj->expiryDate)) . "!";
                }
                $bottom = "Renew Membership";
                $expiryDate = date("Y-m-d", strtotime($this->userObj->expiryDate));
                $disc = $this->renewalPercent;
            } 
            elseif ($this->specialActive == 1) {
                $discountVD = $vdodObj->getDiscountDetails($this->profileid);
                $maxVDDisc = $discountVD['MAX_DISCOUNT'];
                $flat = $discountVD['FLAT_DISCOUNT'];
                $this->discPerc = $maxVDDisc;
                if ($this->fest == 1) {
                    if ($flat) {
                        $top = "Extra Months & flat " . $this->discPerc . "% Off till " . date("d M", strtotime($this->expiry_date)) . "!";
                    } 
                    else {
                        $top = "Extra Months & upto " . $this->discPerc . "% Off till " . date("d M", strtotime($this->expiry_date)) . "!";
                    }
                    $bottom = "Upgrade Membership";
                } 
                else {
                    if ($flat) {
                        $top = "Flat " . $this->discPerc . "% Off till " . date("d M", strtotime($this->expiry_date)) . "!";
                    } 
                    else {
                        $top = "Upto " . $this->discPerc . "% Off till " . date("d M", strtotime($this->expiry_date)) . "!";
                    }
                    $bottom = "Upgrade Membership";
                }
                $expiryDate = date("Y-m-d", strtotime($this->expiry_date));
                $disc = $this->discPerc;
            } 
            elseif ($this->discountActive == 1) {
            	$discountDisplayText = $vdodObj->getCashDiscountDispText($this->profileid,'cap');
                $top = $discountDisplayText . " " . $this->discPerc . "% Off till " . date("d M", strtotime($this->expiry_date)) . "!";
                $bottom = "Upgrade Membership";
                $expiryDate = date("Y-m-d", strtotime($this->expiry_date));
                $disc = $this->discPerc;
            } 
            elseif ($this->fest == 1) {
                $top = "Get extra months / discount till " . date("d M", strtotime($this->festEndDt)) . "!";
                $bottom = "Upgrade Membership";
                $expiryDate = date("Y-m-d", strtotime($this->festEndDt));
            }
        } 
        else {
            $top = NULL;
            //my membership in case of paid beyond renew
            if(!$validityCheck)
                $bottom = "My Membership";
            else
                $bottom = "Upgrade Membership";
        }
        if (!empty($bottom)) {
            $output = array(
                'hamburger_message' => array(
                    'top' => $top,
                    'bottom' => $bottom,
                    'expiry' => $expiryDate,
                    'extra'=>$extra
                )
            );
        } 
        else {
            $output = array(
                'hamburger_message' => NULL
            );
        }

        $output['startingPlan'] = $startingPlanData['startingPlan'];
        $output['maxDiscount'] = $disc;
        $output['userType'] = $this->userType;
        unset($startingPlanData);
        
        $memCacheObject = JsMemcache::getInstance();
        $memCacheObject->set($this->profileid . '_MEM_HAMB_MESSAGE', serialize($output) , 1800);
        return $output;
    }

    /*function to generate Membership plans starting range with discount details
    *@return: $output
    */
    public function generateMembershipPlansStartingRange(){
        $origStartingPrice = 9999999;  //max integer value
        $discountedStartingPrice = 9999999;  //max integer value
        if(is_array($this->minPriceArr) && $this->profileid){
            foreach($this->minPriceArr as $service => $val){
                if($val['PRICE_INR']>=0 && $origStartingPrice > $val['PRICE_INR']){
                    $discountedStartingPrice = $val['OFFER_PRICE'];
                    if($this->currency == "RS"){
                        $origStartingPrice = $val['PRICE_INR'];
                    }
                    else{
                        $origStartingPrice = $val['PRICE_USD'];
                    }
                }
            }
        }
        $output = array();
        if($origStartingPrice < 9999999){
            if($this->currency == "RS"){
                $output["startingPlan"]["membershipDisplayCurrency"] = '₹';
            }
            else{
                $output["startingPlan"]["membershipDisplayCurrency"] = '$';
            }
             
            $output["startingPlan"]["origStartingPrice"] = "".$origStartingPrice;
            $output["startingPlan"]["discountedStartingPrice"] = "".$discountedStartingPrice;
        }
        else{
            $output["startingPlan"] = null;
        }
        return $output;
    }
    
    public function requestCallBackResponse($request) {
        $request->setParameter("profileid", $this->callProfile);
        $request->setParameter("tabVal", $this->callTab);
        $request->setParameter("execCallbackType", $this->callType);
        $request->setParameter("INTERNAL", 1);
        $request->setParameter("rcbResponse", $this->rcbResponse);
        $request->setParameter("device", $this->device);
        $request->setParameter("channel", $this->channel);
        $request->setParameter("callbackSource", $this->callbackSource);
        ob_start();
        $data = sfContext::getInstance()->getController()->getPresentationFor('membership', 'addCallBck');
        $output = ob_get_contents();
        ob_end_clean();
        return array(
            "message" => $output
        );
    }
    
    public function validatePickupRequestResponse($request) {
        if (!$this->name) $errorArr[] = 'name';
        if (!$this->mobile) $errorArr[] = 'mobile';
        if (!$this->address) $errorArr[] = 'address';
        if (!$this->date) $errorArr[] = 'date';
        if ($this->device != 'desktop') {
            if (!$this->comment) $errorArr[] = 'comments';
            if (!$this->city || strpos($this->city, 'Select')) $errorArr[] = 'city';
            if (!$this->landline) $errorArr[] = 'landline';
        } 
        else {
            if (!$this->city) $errorArr[] = 'city';
        }
        
        if (count($errorArr) > 0) {
            $status = 0;
            $output = array(
                "status" => $status,
                "error_id" => $errorArr,
                "error_text" => "Please fill fields marked in Red",
                "params" => NULL
            );
        } 
        else {
            $status = 1;
            $output = array(
                "status" => $status,
                "params" => NULL
            );
        }
        return $output;
    }
    
    public function processPickupRequestResponse($request) {
        list($this->totalCartPrice, $this->discountCartPrice) = $this->memApiFuncs->calculateCartPrice($request, $this);
        $userData = $this->memHandlerObj->getUserData($this->profileid);
        
        if (!empty($this->couponCode)) {
            $couponResponse = $this->validateCouponResponse($this->mainMembership, $this->couponCode);
            if (is_array($couponResponse)) {
                $validation = $couponResponse['validationCode'];
                if (is_numeric($validation) && !empty($validation) && $validation > 0) {
                    if ($this->discPerc) {
                        $this->discPerc = (($this->discPerc + $validation) - ($this->discPerc * $validation / 100));
                    } 
                    else {
                        $this->discPerc = $validation;
                    }
                }
            }
        }
        
        $dataArr['USERNAME'] = $userData['USERNAME'];
        $dataArr['EMAIL'] = $userData['EMAIL'];
        $dataArr['PINCODE'] = $userData['PINCODE'];
        $chksum = explode('_', $this->checksum);
        $dataArr['PROFILEID'] = $this->profileid;
        $dataArr['checksum'] = $chksum[0];
        $dataArr['SERVICE'] = $this->mainMembership;
        $dataArr['ADDON_SERVICEID'] = $this->vasImpression;
        $dataArr['CUR_TYPE'] = $this->currency;
        $dataArr['DISCOUNT'] = $this->discountCartPrice;
        $dataArr['AMOUNT'] = $this->totalCartPrice;
        $dataArr['DISCOUNT_PERCENT'] = $this->discPerc;
        $dataArr['NAME'] = html_entity_decode($this->name);
        $dataArr['PHONE_RES'] = html_entity_decode($this->landline);
        $dataArr['PHONE_MOB'] = html_entity_decode($this->mobile);
        $dataArr['ADDRESS'] = html_entity_decode($this->address);
        //$dataArr['CITY'] = html_entity_decode($this->city);
        $dataArr['PREF_TIME'] = date("Y-m-d", strtotime($this->date));
        $dataArr['COMMENTS'] = html_entity_decode($this->comment);
        $paymentHandlerObj = new PaymentHandler();
        $paymentHandlerObj->submitPickupRequest($dataArr);
        $status = 1;
        
        $output = array(
            "status" => $status,
            "error_id" => NULL,
            "error_text" => NULL,
            "params" => "displayPage=7&mainMembership=" . $this->mainMembership . "&vasImpression=" . $this->vasImpression . "&profileid=" . $this->profileid."&upgradeMem=".$this->upgradeMem
        );
        unset($dataArr);
        return $output;
    }
    
    public function validateCouponResponse($selID, $couponCode) {
        $memHandlerObj = new MembershipHandler();
        $validation = $memHandlerObj->validateCouponCode($selID, $couponCode);
        if (is_numeric($validation) && $validation == 0) {
            $message = array(
                'success_code' => NULL,
                'message' => 'Coupon code entered is not valid'
            );
        } 
        elseif ($validation == "INVDUR") {
            $message = array(
                'success_code' => NULL,
                'message' => 'Coupon code is no longer valid'
            );
        } 
        elseif ($validation == "LIMEXP") {
            $message = array(
                'success_code' => NULL,
                'message' => 'Maximum usage limit reached'
            );
        } 
        elseif ($validation > 0) {
            $message = array(
                'success_code' => 1,
                'message' => $validation
            );
        }
        return array(
            "validationCode" => $validation,
            "message" => $message
        );
    }
    
    public function generateNewAppleOrderResponse($request) {
        include_once (JsConstants::$docRoot . "/commonFiles/connect_dd.inc");
        include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php");
        include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
        include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
        
        $planCategory = $request->getParameter('planCategory');
        if ($planCategory == "int") {
            $this->currency = 'RS';
        } 
        else if ($planCategory == "ext") {
            $this->currency = 'DOL';
        }
        
        $memObj = new Membership;
        $memObj->setProfileid($this->profileid);
        $payment = $memObj->forOnline($this->mainMembership, $this->currency, $this->mainMembership, $this->discSel, 'card2', $this->device, $this->couponCode);
        $total = $payment['total'];
        $service_main = $payment['service_str'];
        $service_str = "";
        $discount = $payment['discount'];
        $discount_type = $payment['discount_type'];
        //JSC-3335
        $membershipHandlerObj = new MembershipHandler();
        $isCityEntered = $membershipHandlerObj->isCityEntered($this->profileid);
        if($this->appVersion < VariableParams::$iOSVersion || $isCityEntered){
            $ORDER = newOrder($this->profileid, 'card2', $this->currency, $total, $service_str, $service_main, $discount, $setactivate, 'APPLEPAY', $discount_type, $this->device, $this->couponCode);
            $nameOfUserObj = new incentive_NAME_OF_USER();
            $userName = $nameOfUserObj->getName($this->profileid);

            $output = array(
                'inAppPurchaseId' => $ORDER["ORDERID"]
            );
        } else{
            $output = array(
                'cityNotFilledError' => "Please fill 'State' in 'Edit Profile'\n Basic Details section to proceed.",
                'responseStatusCode' => 1
            );
        }

        return $output;
    }
    
    public function generateAppleOrderProcessingResponse($request) {
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
        include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

        $serObj = new Services;
        $membershipObj = new Membership;
        $Order_Id = $request->getParameter('orderID');
        $receipt = $request->getParameter('receipt');
        $txnid = $request->getParameter('iosTxnId');
        $txnStatus = $request->getParameter('txnStatus');

        $billingOrdersObj = new BILLING_ORDERS();
		$billingAppleOrdersObj = new billing_APPLE_ORDERS();
		
		if(!empty($receipt) && !isset($txnStatus)){ // case when receipt is received but txnstatus not set // OLD APP
			$txnStatus = "S";
		} else if(!empty($txnid) && empty($receipt) && !isset($txnStatus)) { // txnstatus not set and txnid received without receipt // OLD APP
			$txnStatus = "RNR_S";
		}

		if(!empty($receipt) && $txnStatus == "S") { // purely successful case
			$itunesValidatorObj = new itunesReceiptValidator(gatewayConstants::$AppleLiveURL, $receipt);
	        $validatedResponse = $itunesValidatorObj->validateReceipt();

	        switch ($validatedResponse) {
	        	case 21007: // Check to see if response is a sandbox response 
	        			$itunesValidatorObj = new itunesReceiptValidator(gatewayConstants::$AppleTestURL, $receipt);
		        		$validatedResponse = $itunesValidatorObj->validateReceipt();
	        		break;
	        	case 21000: 
	        	case 21002: 
	        	case 21003:
	        	case 21004:
	        	case 21005:
	        	case 21006:
	        	case 21008: $logStatus = 6; // cases when receipt not validated via server to server call
	        		break; 
	        	default:
	        		break;
	        }

	        if(is_object($validatedResponse) && $validatedResponse->bundle_id == 'com.infoedge.jeevansathi' && $logStatus != 6){
	        	$inAppPurchasesArr = $validatedResponse->in_app;
	        	list($part1,$part2) = explode("-",$Order_Id);
				$orderDetails = $billingOrdersObj->getOrderDetailsForOrderID($part2, $part1);
				if($orderDetails[0]['GATEWAY'] == 'APPLEPAY'){
					$serviceMain = $orderDetails[0]['SERVICEMAIN'];
	        		foreach($inAppPurchasesArr as $key => $val){
						$pDet = explode(".",$val->product_id);
						if($serviceMain == $pDet[2].$pDet[3]){
							$appleId = $val->transaction_id;
							if ($billingAppleOrdersObj->checkIfAppleIdExists($appleId) == 0 && $appleId == $txnid) {
								$billingAppleOrdersObj->insertOrderDetails($this->profileid,$part2,$part1,$appleId,date("Y-m-d H:i:s"),$receipt);
								$logStatus = 10;        //Means pure success
							} else if ($billingAppleOrdersObj->checkIfAppleIdExists($appleId) == 1 && $appleId == $txnid) {
								$logStatus = 1;
							}
						}
	        		}
	        	}
	        }
	    } else {
	    	if($txnStatus == "RNR_S"){
	    		$logStatus = 5; // receipt not receiverd but payment successful as per apple	
	    	} else if($txnStatus == "F") {
	    		$logStatus = 2; // status returned as failure
	    	} else if($txnStatus == "I") {
	    		$logStatus = 3; // status returned as user initiated transaction  
	    	} else if($txnStatus == "C") {
	    		$logStatus = 4; // status returned as user cancelled 
	    	} else {
	    		$logStatus = 2; // default marking status returned as failure // OLD APP
	    	}
	    }
        
        if ($logStatus == 10) {
        	$status = 'transaction_successful';
            $AuthDesc = "Y";
            $ret_status="S";
        } else if ($logStatus == 1){
            $status = "receipt_verified";
            $AuthDesc = "V";
            $ret_status="V";
        } else if ($logStatus == 5){
            $status = 'receipt_pending';
            $AuthDesc = "RNR_S";
            $ret_status="F";
        } else if ($logStatus == 2){
            $status = 'transaction_failure';
            $AuthDesc = "N";
            $ret_status="F";
        } else if ($logStatus == 3){
            $status = 'user_initiated';
            $AuthDesc = "I";
            $ret_status="F";
        } else if ($logStatus == 4){
            $status = 'user_cancelled';
            $AuthDesc = "C";
            $ret_status="F";
        } else if ($logStatus == 6){
            $status = 'receipt_validation_failure';
            $AuthDesc = "R_F";
            $ret_status="F";
        } else if($logStatus ==""){
            $status = 'transaction_failure';
            $AuthDesc = "N";
            $ret_status="F";
            $logStatus = 2;
        }

        /*
        	Cases ::
        	1) New Transaction(Successful) : 3,0
        	2) New Transaction(Failure) : 3,2
        	3) User Cancells Transaction manually : 4
        	4) Transaction Verification by iOS App(background when we dont send response 1st time) : 3,0,1
        	5) Successful on Apple(but no receipt received) : 3,5
        	6) Successful Transaction but Receipt Invalidated using iTunesValidator : 3,6
        */

        $memHandlerObj = new MembershipHandler();

        $billingPaymentStatusLogObj = new billing_PAYMENT_STATUS_LOG();
       	$membershipObj->log_payment_status($Order_Id,$ret_status,'APPLEPAY',$status, $this->profileid);
        $dup = false;

        if ($AuthDesc=="Y") {
            $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
            if (!$dup && $ret) {
                if($logStatus == 10){
                    $logStatus = 0;
                }
                $membershipObj->startServiceOrder($Order_Id);
                $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
            }
        } 
        else if ($AuthDesc == "N") {
            $ret = $membershipObj->updtOrder ($Order_Id, $dup, $AuthDesc);
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        } 
        else if ($AuthDesc == "V") {
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        }
        else if ($AuthDesc == "RNR_S") {
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        }
        else if ($AuthDesc == "I") {
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        }
        else if ($AuthDesc == "C") {
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        }
        else if ($AuthDesc == "R_F") {
            $output = array('orderId' => $Order_Id,
                    'processingStatus' => $status,
                    'appleStatusCode' => $validatedResponse,
                    'paymentStatus' => $logStatus,
                    'incomingIp' => $this->ipAddress);
        }
        
        return $output;
    }
    
    public function processPaymentAndRedirect($request,$apiObj) {
        list($apiObj->totalCartPrice, $apiObj->discountCartPrice) = $apiObj->memApiFuncs->calculateCartPrice($request, $apiObj);
        if($apiObj->currency == "DOL" && $apiObj->usdTOinr && $apiObj->processPayment){
            $convRate = $apiObj->userObj->memObj->get_DOL_CONV_RATE();
            $apiObj->totalCartPrice *= $convRate;
            $apiObj->discountCartPrice *= $convRate;
            $apiObj->userObj->currency = "RS";
            $apiObj->currency= "RS";
        }
        $userData = $apiObj->memHandlerObj->getUserData($apiObj->profileid);
        $USERNAME = $userData['USERNAME'];
        $EMAIL = $userData['EMAIL'];
        $PINCODE = $userData['PINCODE'];
        $chksum = explode('_', $apiObj->checksum);
        $apiObj->checksum = $chksum[0];
        $apiObj->mainSubMemId = $apiObj->mainMembership;
        $apiObj->track_memberships = $apiObj->allMemberships;
        $apiObj->navigationString = $apiObj->allMemberships;
        $apiObj->service = $apiObj->mainMembership;
        $apiObj->service_main = $apiObj->mainMembership;
        $apiObj->userType = $apiObj->userObj->userType;
        
        //$apiObj->paymode = 'card2';
        $apiObj->paymentTab = '23';
        $apiObj->profileid = $apiObj->profileid;
        $apiObj->USERNAME = $USERNAME;
        $apiObj->EMAIL = $EMAIL;
        $apiObj->PINCODE = $PINCODE;
        $apiObj->curtype = $apiObj->currency;
        $apiObj->type = $apiObj->currency;
        $apiObj->track_discount = $apiObj->discountCartPrice;
        $apiObj->track_total = $apiObj->totalCartPrice;
        $apiObj->discountType = $apiObj->discountType;
        $apiObj->specialActive = $apiObj->specialActive;
        $apiObj->discountActive = $apiObj->discountActive;
        $apiObj->fromPaymentTab = 1;
        $apiObj->festActive = $apiObj->fest;
        $apiObj->device = $apiObj->device;
        $apiObj->couponCodeVal = $apiObj->couponCode;
        
        $apiObj->paymode = paymentOption::$cardTypeMapping[$apiObj->cardType];
        $walletTypeArr = paymentOption::$walletType;
        $paymentRedirectPageArr = paymentOption::$paymentRedirectPage;
        $apiObj->card_option = '';
        $apiObj->pageRedirectTo = '';
        $defaultGatewayRedirection = JsMemcache::getInstance()->get('JS_PAYMENT_GATEWAY');
        $gatewayOption = SelectGatewayRedirect::$gatewayOptions;
        if(!in_array($defaultGatewayRedirection,$gatewayOption) || $defaultGatewayRedirection == ''){
            $billingSelectedGateway = new billing_CURRENT_GATEWAY();
            $defaultGatewayRedirection = $billingSelectedGateway->fetchCurrentGateway();
            JsMemcache::getInstance()->set('JS_PAYMENT_GATEWAY',$defaultGatewayRedirection);
        }
        if ($apiObj->paymentMode == 'CSH' && array_key_exists("$apiObj->cardType", $walletTypeArr)) {
            $apiObj->walletSelected = 1;
            $apiObj->card_option = 'CCRD';
            $apiObj->CCRDType = paymentOption::$walletIdMapping[$apiObj->cardType];
            if ($apiObj->CCRDType == 'PAYTM') {
                $redirectTo = 'paytm';
            } 
            else {
                $redirectTo = 'ccavenue';
            }
        } 
        else if ($apiObj->paymentMode == 'NB') {
            $apiObj->netBank == 1;
            $apiObj->card_option = 'netBanking';
            $apiObj->net_banking_cards = $apiObj->cardType;
            $apiObj->paymode = 'ncard';
            $redirectTo = 'ccavenue';
        } 
        else if ($apiObj->paymentMode == 'PP') {
            $apiObj->paymode = 'card';
            $redirectTo = 'paypal';
        } 
        else if ($apiObj->paymentMode == 'CCP') {
            $redirectTo = 'r4';
        } 
        else if (($apiObj->paymentMode == "CR" && ($apiObj->cardType == 'card2' || $apiObj->cardType == 'card3')) || ($apiObj->paymentMode == "DR" && ($apiObj->cardType == 'card2' || $apiObj->cardType == 'card3' || $apiObj->cardType == 'card4'))) {
			//if(SelectGatewayRedirect::setDefaultGatewayRedirect == "payu"){            
			if($defaultGatewayRedirection == "payu"){
                $redirectTo = 'payu';
            }
			//else if(SelectGatewayRedirect::setDefaultGatewayRedirect == "ccavenue"){
            else if($defaultGatewayRedirection == "ccavenue"){
                $redirectTo = 'ccavenue';
            }
            else{
                $redirectTo = 'payu';   //Default redirection
            }
        } 
        else if ($apiObj->paymentMode == "CR" || $apiObj->paymentMode == "DR") {
        	if ($apiObj->currency == "DOL") {
				//if(SelectGatewayRedirect::setDefaultGatewayRedirect == "payu"){
                if($defaultGatewayRedirection == "payu"){
                    $redirectTo = 'payu';
                }
				//else if(SelectGatewayRedirect::setDefaultGatewayRedirect == "ccavenue"){
                else if($defaultGatewayRedirection == "ccavenue"){
                    $redirectTo = 'ccavenue';
                }
                else{
                    $redirectTo = 'payu';   //Default redirection
                }
            } else {
                if($apiObj->cardType == 'card1'){
                    //Redirection for AMEX irrespective of the option selected
                    $redirectTo = 'ccavenue';
                }
				//else if(SelectGatewayRedirect::setDefaultGatewayRedirect == "payu"){
                else if($defaultGatewayRedirection == "payu"){
                    $redirectTo = 'payu';
                }
				//else if(SelectGatewayRedirect::setDefaultGatewayRedirect == "ccavenue"){
                else if($defaultGatewayRedirection == "ccavenue"){
                    $redirectTo = 'ccavenue';
                }
                else{
                    $redirectTo = 'ccavenue'; //Default redirection
                }
            }
        }
        
        $apiObj->pageRedirectTo = $paymentRedirectPageArr[$redirectTo];

        if ($fromBackend == 1 && $apiObj->device == "Android_app") {
            $apiObj->memHandlerObj->trackMembershipProgress($apiObj->userObj, '604', '64', '4', 'backend_link', $apiObj->user_agent, $apiObj->allMemberships, $apiObj->mainMembership, $apiObj->vasImpression, $apiObj->totalCartPrice, $apiObj->discountCartPrice, 63, "F");
        }
        else if ($fromBackend == 1 && $apiObj->device != "Android_app") {
            $apiObj->memHandlerObj->trackMembershipProgress($apiObj->userObj, '504', '54', '4', 'backend_link', $apiObj->user_agent, $apiObj->allMemberships, $apiObj->mainMembership, $apiObj->vasImpression, $apiObj->totalCartPrice, $apiObj->discountCartPrice, 53, "F");
        }
	return $apiObj;
    }

    public function doTestBilling($request) {

    	if(JsConstants::$whichMachine == 'test'){
    		include_once (JsConstants::$docRoot . "/commonFiles/connect_dd.inc");
	        include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/pg/functions.php");
	        include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
	        include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
	        
	        $pCur = $request->getParameter('pCur');
	        
	        if ($pCur == "DOL") {
	            $this->currency = 'DOL';
	        } 
	        else {
	            $this->currency = 'RS';
	        }
	        
	        $memObj = new Membership;
	        $memObj->setProfileid($this->profileid);
	        if (!empty($this->mainMembership) && !empty($this->vasImpression)) {
	        	$allMemberships = $this->mainMembership.",".$this->vasImpression;
	        } elseif (empty($this->mainMembership) && !empty($this->vasImpression)) {
	        	$allMemberships = $this->vasImpression;
	        } elseif (!empty($this->mainMembership) && empty($this->vasImpression)) {
	        	$allMemberships = $this->mainMembership;
	        }
	        $payment = $memObj->forOnline($allMemberships, $this->currency, $this->mainMembership, $this->discSel, 'card2', $this->device, $this->couponCode);
	        $total = $payment['total'];
	        $service_main = $payment['service_str'];
	        $service_str = "";
	        $discount = $payment['discount'];
	        $discount_type = $payment['discount_type'];
	        $ORDER = newOrder($this->profileid, 'card2', $this->currency, $total, $service_str, $service_main, $discount, $setactivate, 'TEST', $discount_type, $this->device, $this->couponCode);
	        $nameOfUserObj = new incentive_NAME_OF_USER();
	        $userName = $nameOfUserObj->getName($this->profileid);
	        $Order_Id = $ORDER['ORDERID'];
	        $memHandlerObj = new MembershipHandler();
	        $billingPaymentStatusLogObj = new billing_PAYMENT_STATUS_LOG();
	       	$memObj->log_payment_status($Order_Id, "S", 'TEST', 'billing done on test server');
            $dup = false;
            $ret = $memObj->updtOrder($Order_Id, $dup, "Y");
            if (!$dup && $ret) {
                $memObj->startServiceOrder($Order_Id);
                $output = array('orderId' => $Order_Id,
                    'processingStatus' => 'successful',
                    'incomingIp' => $this->ipAddress);
            } 
    	} 
    	else {
    		$output = array('orderId' => 'invalid order',
	            'processingStatus' => 'invalid access',
	            'incomingIp' => $this->ipAddress);
    	}
    	return $output;
    }
    
    public function addRemoveUserForDolPayment($request) {
    	if(JsConstants::$whichMachine == 'test'){
            if($this->profileid){
                if($request->getParameter('add') == 1){
                    $this->memHandlerObj->addUserForDollarPayment($this->profileid);
                    $status = "successfully added $this->profileid";
                }
                elseif($request->getParameter('remove') == 1){
                    $this->memHandlerObj->removeUserForDollarPayment($this->profileid);
                    $status = "successfully removed $this->profileid";
                }
                else{
                    $status = "Parameter missing";
                }
            }
            else{
                $status = "Please login";
            }
            $output = array('processingStatus' => $status,
                    'incomingIp' => $this->ipAddress);
    	} 
    	else {
    		$output = array('processingStatus' => 'invalid access',
	            'incomingIp' => $this->ipAddress);
    	}
    	return $output;
    }
    
}
