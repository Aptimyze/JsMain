<?php
class membershipActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }

    public function executeMembershipMaster(sfWebRequest $request)
    {
        $this->redirect('/membership/jspc');
    }

    public function executeValueAddedMembership(sfWebRequest $request)
    {
        $this->redirect('/membership/jspc');
    }

    public function executePaymentOptions(sfWebRequest $request)
    {
        $this->redirect('/membership/jspc');
    }

    /*api to deactivate current membership of user
    * @inputs: $request
    * @return: api response
    */
    public function executeDeactivateCurrentMembershipV1(sfWebRequest $request){
        //parse request inputs and format
        if($request->getParameter("PROFILECHECKSUM")){
            $profileid = JsAuthentication::jsDecryptProfilechecksum($request->getParameter("PROFILECHECKSUM"));
            if($profileid){
                $membership = "MAIN";
                if($request->getParameter("MEMBERSHIP")){
                    $membership = $request->getParameter("MEMBERSHIP");
                }
                if(!$request->getParameter("USERNAME")){
                    $profileObj      = new PROFILE();
                    $detail       = $profileObj->getDetail($profileid, 'PROFILEID', "USERNAME");
                    unset($profileObj);
                    if(is_array($detail)){
                        $username = $detail["USERNAME"];
                    }
                }
                else{
                    $username = $request->getParameter("USERNAME");
                }
               
                //deactivate current membership based on inputs
                $memHandlerObj = new MembershipHandler();
                $deactivationStatus = $memHandlerObj->deactivateCurrentMembership(array("PROFILEID"=>$profileid,"USERNAME"=>$username,"MEMBERSHIP"=>$membership,"NEW_ORDERID"=>$request->getParameter("NEW_ORDERID")));
                unset($memHandlerObj);
            }
        }
        $respObj = ApiResponseHandler::getInstance();
        if(isset($deactivationStatus)){
            if($deactivationStatus == true){
                $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
            }
            else{
                $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
            }
        }
        else{
            $respObj->setHttpArray(ResponseHandlerConfig::$LOGIN_FAILURE_MISSING);
        }
        $respObj->generateResponse();
        die;
    }

    // JSPC
    public function executeJspc(sfWebRequest $request)
    {

        /**
         * Redirect to correct display view based on User Agent !
         */

        if (MobileCommon::isMobile()) {
            if (MobileCommon::isNewMobileSite()) {
                $this->forward("membership", "jsms");
            } else {
                $this->forward("membership", "mobileMembershipMaster");
            }
        }

        header('Cache-Control: no-transform');

        $this->loginData       = $request->getAttribute("loginData");
        $this->profileChecksum = $this->loginData['CHECKSUM'];
        $this->profileid       = $this->loginData['PROFILEID'];
        $request->setParameter('device', 'desktop');

        $memActFunc = new MembershipActionFunctions();
        $membershipHandlerObj = new MembershipHandler();
        list($displayPage, $pageURL, $mainMem, $mainMemDur, $orderID, $device, $fromBackend, $checksum, $profilechecksum, $reqid, $mainMembership, $vasImpression, $authChecksum) = $memActFunc->getReqParamsForRevMobMem($request);
        switch ($displayPage) {
            case '1':
                $apiParams = $pageURL . $authChecksum . "&device=" . $device;
                $template  = 'JSPCLandingPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);
                

                if ($data['dividerExpiry'] != null) {
                    list($this->days, $this->showCountdown, $this->countdown) = $memActFunc->setTickerData($data['dividerExpiry']);
                }

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMemPage1Url);
                break;

            case '2':
                $apiParams = $pageURL . $authChecksum . $device;
                $template  = 'JSPCVasPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMemPage2Url);
                break;

            case '3':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSPCCartPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);
                $profileID = $data["userDetails"]["PROFILEID"];
                $this->isCityEntered = $membershipHandlerObj->isCityEntered($profileID);
                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMemPage3Url);
                break;

            case '7':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSPCChequePickupSuccessPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);
                break;

            case '8':
                if (!isset($this->profileid) || empty($this->profileid)) {
                    $this->profileid = $request->getParameter('userProfile');
                }
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSPCTransactionSuccessPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMemPage4Url);
                break;

            case '9':
                if (!isset($this->profileid) || empty($this->profileid)) {
                    $this->profileid = $request->getParameter('userProfile');
                }
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSPCTransactionFailurePage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMemPage5Url);
                break;

            default:

                throw new Exception("Invalid parameters!");
                die();
                break;
        }
        if ($displayPage != 1 && $data['responseStatusCode'] == 9) {
            $this->logoutCase = 1;
            $this->errorJson  = json_encode($data);
        }
        $this->data = $data;

        //print_r($this->data); die;
        $this->referer = $request->getReferer();
        $this->setTemplate($template);
    }

    // JSMS
    public function executeJsms(sfWebRequest $request)
    {

        /**
         * Redirect to correct display view based on User Agent !
         */
        if (!MobileCommon::isMobile() && !MobileCommon::isNewMobileSite() && !MobileCommon::isAppWebView()) {
            $this->forward('membership', 'jspc');
        }

        header('Cache-Control: no-transform');

        $memActFunc = new MembershipActionFunctions();
        $membershipHandlerObj = new MembershipHandler();

        //parse request params for module
        list($displayPage, $pageURL, $mainMem, $mainMemDur, $orderID, $device, $fromBackend, $checksum, $profilechecksum, $reqid, $mainMembership, $vasImpression, $authChecksum,$upgradeMem) = $memActFunc->getReqParamsForRevMobMem($request);
        if ($device == "Android_app") {

            $_SERVER[HTTP_USER_AGENT] = "Chrome/39 JsAndWeb";
        }

        switch ($displayPage) {
            case '1':
                $loginData       = $request->getAttribute('loginData');
                $this->profileid = $loginData['PROFILEID'];
                $apiParams       = $pageURL . $authChecksum . "&device=" . $device . "&from_source=" . $fromBackend;
                $template        = 'JSMSLandingPage';
                $this->passedKey = $fromBackend;
                $data            = $this->fetchApiData($apiParams, $request, 3);
                $data            = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage1Url);
                break;

            case '2':
                $apiParams = $pageURL . $authChecksum . $device . "&from_source=" . $fromBackend;
                $template  = 'JSMSVasPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage2Url);
                break;

            case '3':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSMSCartPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);
                $profileID = $data["userDetails"]["PROFILEID"];
                $this->isCityEntered = $membershipHandlerObj->isCityEntered($profileID);
                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage3Url);
                break;

            case '4':
                $this->skipVasPageMembershipBased = json_encode(VariableParams::$skipVasPageMembershipBased);
                $this->upgradeMem = $upgradeMem;
                $template                         = 'JSMSCouponPage';

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage4Url);
                break;

            case '5':
                $apiParams            = $pageURL . $mainMem . $authChecksum . $device;
                $this->mainMembership = $mainMembership . $vasImpression;
                $template             = 'JSMSPaymentOptionsPage';
                $data                 = $this->fetchApiData($apiParams, $request, 3);

                $data = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage5Url);
                break;

            case '6':
                $apiParams            = $pageURL . $authChecksum . $device;
                $this->mainMembership = $mainMembership . $vasImpression;
                $template             = 'JSMSChequePickup';
                $data                 = $this->fetchApiData($apiParams, $request, 3);

                $data = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage6Url);
                break;

            case '7':
                $apiParams            = $pageURL . $mainMem . $authChecksum . $device;
                $this->mainMembership = $mainMembership . $vasImpression;
                $template             = 'JSMSChequePickupSuccessPage';
                $data                 = $this->fetchApiData($apiParams, $request, 3);

                $data = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage7Url);
                break;

            case '8':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSMSSuccessPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage8Url);
                break;

            case '9':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSMSFailurePage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage9Url);
                break;

            case '10':
                $apiParams = $pageURL . $mainMem . $authChecksum . $device;
                $template  = 'JSMSPayAtBranchesPage';
                $data      = $this->fetchApiData($apiParams, $request, 3);
                $data      = $memActFunc->formatDataForNewRevMobMem($request, $displayPage, $data);

                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage10Url);
                break;

            default:

                throw new Exception("Invalid parameters!");
                die();
                break;
        }
        if ($displayPage != 1 && $data['responseStatusCode'] == 9) {
            $this->logoutCase = 1;
            $this->errorJson  = json_encode($data);
        }
        $this->data = $data;
        //print_r($this->data); die;
        $this->referer = $request->getReferer();
        $this->setTemplate($template);
    }

    // API Caller
    public function fetchApiData($apiParams, $request, $version)
    {

        parse_str($apiParams, $result);
        foreach ($result as $key => $val) {
            $request->setParameter($key, $val);
        }
        $request->setParameter('INTERNAL', 1);
        ob_start();
        $data = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');

        $output = ob_get_contents();
        ob_end_clean();
        $data = json_decode($output, true);
        return $data;
    }

    // JSMS Coupon Code
    public function executeValidateCoupon(sfWebRequest $request)
    {
        $serviceid     = $request->getParameter("serviceid");
        $couponCode    = $request->getParameter("couponCode");
        $memHandlerObj = new MembershipHandler();
        $validation    = $memHandlerObj->validateCouponCode($serviceid, $couponCode);
        echo $validation;
        die();
        return sfView::NONE;
    }

    // JS Exclusive Page
    public function executeJsexclusiveDetail(sfWebRequest $request)
    {
        header('Cache-Control: no-transform');
        $this->dropDownDayArr = CommonFunction::getRCBDayDropDown();
        $this->dropDownTimeArr1 = CommonFunction::getRCBStartTimeDropDown();
        $this->dropDownTimeArr2 = CommonFunction::getRCBEndTimeDropDown();

        $loginData       = $request->getAttribute('loginData');
        $this->profileid = $loginData['PROFILEID'];
        if ($this->profileid) {
            $profileObj      = new PROFILE();
            $mobDetail       = $profileObj->getDetail($this->profileid, 'PROFILEID', "USERNAME,EMAIL,PHONE_MOB");
            $email           = $mobDetail['EMAIL'];
            $this->mobNumber = $mobDetail['PHONE_MOB'];
        }
        $date              = $request->getParameter('dropDownDaySelected');
        $startTime         = $request->getParameter('dropDownTimeStartSelected');
        $endTime           = $request->getParameter('dropDownTimeEndSelected');
        $this->callRequest = $request->getParameter('callRequest');
        if ($this->callRequest == 1) {
            $this->success = 1;
            $request->setParameter('jsSelectd', $request->getParameter('jsSelectd'));
            $request->setParameter("profileid", $this->profileid);
            $request->setParameter("tabVal", "1");
            $request->setParameter("execCallbackType", "JS_EXC");
            $request->setParameter("device", "desktop");
            $request->setParameter("channel", "JSPC");
            $request->setParameter("callbackSource", "JS_Exclusive");
            $request->setParameter("date", $date);
            $request->setParameter("startTime", $startTime);
            $request->setParameter("endTime", $endTime);
            $request->setParameter("INTERNAL", 1);
            ob_start();
            $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'addCallBck');
            $output = ob_get_contents();
            ob_end_clean();
        }

        $request->setParameter('displayPage', 1);
        $request->setParameter('JSX', '1');
        $request->setParameter('INTERNAL', 1);
        ob_start();
        $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');
        $output = ob_get_contents();
        ob_end_clean();
        $data       = json_decode($output, true);
        $memActFunc = new MembershipActionFunctions();
        $this->data = $memActFunc->formatDataForNewRevMobMem($request, 1, $data);

        $request->setParameter('getMembershipMessage', 1);
        $request->setParameter('JSX', '1');
        $request->setParameter('INTERNAL', 1);
        ob_start();
        $data   = sfContext::getInstance()->getController()->getPresentationFor('membership', 'ApiMembershipDetailsV3');
        $output = ob_get_contents();
        ob_end_clean();
        $data2 = json_decode($output, true);

        if ($data2['membership_message']['top'] != null && !strpos($data2['membership_message']['top'], "months")) {
            $this->discountText = "@ Attractive Discounts";
        }
        $this->setTemplate("jsexclusiveDetail");
    }

    // Old JSMS
    public function executeMobileMembershipMaster(sfWebRequest $request)
    {
        header('Cache-Control: no-transform');
        $this->loginData       = $request->getAttribute("loginData");
        $this->profileChecksum = $this->loginData['CHECKSUM'];
        $this->profileid       = $this->loginData['PROFILEID'];
        $fromBackend           = $request->getParameter('from_source');
        $memHandlerObj         = new MembershipHandler();

        if (MobileCommon::isMobile()) {
            if (MobileCommon::isNewMobileSite()) {
                $this->getRequest()->setParameter('displayPage', 2);
                $this->getRequest()->setParameter('device', 'mobile_website');
                $this->forward('membership', 'jsms');
            }

            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $memHandlerObj->addHitsTracking($this->profileid, '1', '11', 'old_mobile_website', $user_agent);
            $naviObj = new Navigator();
            $naviObj->navigation($request->getParameter("nav_type"), "", "");
            $this->BREADCRUMB     = $naviObj->onlyBackBreadCrumb;
            $this->NAVIGATOR      = $naviObj->NAVIGATOR;
            $this->nav_type       = $request->getParameter("nav_type");
            $this->FROMPOST       = 1;
            $userObj              = new memUser($this->profileid);
            $billingServiveStatus = new BILLING_SERVICE_STATUS();

            $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage1Url);

            if ($this->profileid != '') {
                $userObj->setMemStatus();
                $userObj->contactsRemaining = $userObj->getRemainingContacts($userObj->getProfileid());
                $expiryDate                 = $billingServiveStatus->getMaxExpiryDate($userObj->getProfileid());
            }

            if (strtotime($expiryDate) >= strtotime(date("Y-m-d"))) {
                $purchasesObj  = new BILLING_PURCHASES();
                $memID         = @strtoupper($purchasesObj->getCurrentlyActiveService($this->profileid));
                $memID         = $memHandlerObj->retrieveCorrectMemID($memID, $userObj);
                $this->memID   = $memID;
                $this->memName = $memHandlerObj->getUserServiceName($memID);
            }

            $this->activeServices = $memHandlerObj->getActiveServices();

            $this->allMemberships = implode(",", array_values($this->activeServices));
            $this->serviceName    = $memHandlerObj->getServiceNames($this->activeServices);
            $this->messages       = $memHandlerObj->getServiceMessages($this->activeServices);

            $mainSubMemId   = $request->getPostParameter('mainSubMemId');
            $allMemberships = $request->getPostParameter('allMemberships');
            $source         = 50;

            if ($mainSubMemId && $allMemberships) {
                $memHandlerObj->trackMembership($userObj, $source, $allMemberships, $mainSubMemId);
            } else {
                $memHandlerObj->trackMembership($userObj, $source);
            }

            $JMembershipObj = new JMembership();

            list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
            if ($currency == "DOL") {
                $this->currency = 'DOL';
                $currencyType   = VariableParams::$otherCurrency;
            } else {
                $this->currency = 'RS';
                $currencyType   = VariableParams::$indianCurrency;
            }
            $userObj->setIpAddress($ipAddress);
            $userObj->setCurrency($this->currency);

            $allMainMem = $memHandlerObj->fetchMembershipDetails("MAIN", $userObj, 'old_mobile_website');

            // ankita :Code removed to specifically remove 1 month membership from display
            /*if (isset($allMainMem['P']['P1'])) {
                unset($allMainMem['P']['P1']);
            }*/

            $discountTypeArr    = $memHandlerObj->getDiscountInfo($userObj,"NA",'old_mobile_website');
            $discountType       = $discountTypeArr['TYPE'];
            $this->discountType = $discountType;
            if (strpos(discountType::OFFER_DISCOUNT, $discountType) !== false) {
                $this->discountActive  = '1';
                $discntId              = $memHandlerObj->isDiscountOfferActive();
                $this->discount_expiry = $memHandlerObj->getDiscountExpiry($discntId);
                $this->discountPercent = $memHandlerObj->getDiscountUpto();
            }
            if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false) {
                $this->specialActive            = '1';
                $spclDiscntData                 = $memHandlerObj->getSpecialDiscount($userObj->getProfileId());
                $this->variable_discount_expiry = $spclDiscntData['EDATE'];
                $this->discountSpecial          = $spclDiscntData['DISCOUNT'];
            }

            $this->fest = $memHandlerObj->getFestiveFlag();
            if ($this->fest == "1") {
                $festiveLogRevampObj = new billing_FESTIVE_LOG_REVAMP();
                $offerDetails        = $festiveLogRevampObj->getActiveOfferDetails();
                $this->festEndDt     = date('d M Y', strtotime($offerDetails['END_DT']));
                $memActFunc          = new MembershipActionFunctions();
                $this->festDurBanner = $memActFunc->getFestDurBanner("L", $discountType, $userObj->getProfileid(), 0);
                unset($memActFunc);
            }
            $discountArr = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
            foreach ($allMainMem as $mainMem => $subMem) {
                if ($userObj->profileid != '') {
                    foreach ($subMem as $key => $value) {
                        //$discount = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
                        $discount     = $discountArr[$mainMem];
                        $mem_duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
                        if (strpos($mem_duration[0], "L")) {
                            $mem_duration = "L";
                        } else {
                            $mem_duration = $mem_duration[1];
                        }
                        $allMainMem[$mainMem][$key]['SPECIAL_DISCOUNT_PRICE'] = $allMainMem[$mainMem][$key]['PRICE'] - ($allMainMem[$mainMem][$key]['PRICE'] * $discount[$mem_duration]) / 100;
                    }
                }
            }
            $allMainMem  = $memHandlerObj->getOfferPrice($allMainMem, $userObj, 'old_mobile_website');
            $minPriceArr = $memHandlerObj->fetchLowestActivePrices($userObj, $allMainMem, 'old_mobile_website');

            $memHandlerObj->oldJSMSsetSubcriptionExp($userObj, $memHandlerObj, $this);
            $this->renewalPercent = $memHandlerObj->getVariableRenewalDiscount($userObj->getProfileid());

            $this->userObj      = $userObj;
            $this->minPriceArr  = $minPriceArr;
            $this->allMainMem   = $allMainMem;
            $this->currencyType = $currencyType;
            $this->setTemplate("mem_allPlans");
            $subStatus = $memHandlerObj->getSubStatus($userObj->getProfileid());
            if ($subStatus && is_array($subStatus)) {
                foreach ($subStatus as $key => &$value) {

                    $vasCheck = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $value['SERVICEID']);
                    if (!in_array($vasCheck[0], VariableParams::$mainMembershipsArr) && !strpos($vasCheck[0], 'L')) {
                        unset($subStatus[$key]);
                        continue;
                    }
                    $memID = $memHandlerObj->retrieveCorrectMemID($value['SERVICEID'], $userObj);
                    if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                        unset($subStatus[$key]);
                    } else {
                        $esathiCheck = $memHandlerObj->checkForESathiService($this->profileid, $value['ACTIVATED_ON'], $value['EXPIRY_DT'], $value['SERVICEID']);
                        if (substr($esathiCheck, 0, 3) == "ESP") {
                            $value['SERVICEID'] = $esathiCheck;
                            $memID              = "ESP";
                        }
                        $value['SERVICE_NAME'] = $memHandlerObj->getUserServiceName($memID);
                        if (filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT)) {
                            $value['SERVICE_DURATION'] = filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT);
                        } else {
                            $value['SERVICE_DURATION'] = 'Unlimited';
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
            $this->subStatus = $subStatus;
            if (is_array($this->subStatus) && !empty($this->subStatus)) {
                $this->countActiveServices = count($this->subStatus);
            } else {
                $this->countActiveServices = 0;
            }
            $this->setTemplate("mem_allPlans");
        }
    }

    // Old JSMS
    public function executeMobileMembershipPlanDetails(sfWebRequest $request)
    {
        header('Cache-Control: no-transform');
        $this->loginData       = $request->getAttribute("loginData");
        $this->profileChecksum = $this->loginData['CHECKSUM'];
        $this->profileid       = $this->loginData['PROFILEID'];
        $memHandlerObj         = new MembershipHandler();
        $billingServiveStatus  = new BILLING_SERVICE_STATUS();
        $this->memID           = $request->getParameter('service');
        $this->service         = $this->memID;
        $this->serviceName     = $memHandlerObj->getUserServiceName($this->memID);
        $user_agent            = $_SERVER['HTTP_USER_AGENT'];

        if (MobileCommon::isNewMobileSite()) {
            $this->getRequest()->setParameter('displayPage', 3);
            $this->getRequest()->setParameter('mainMem', $this->memID);
            $this->getRequest()->setParameter('device', 'mobile_website');
            $this->forward('membership', 'jsms');
        }

        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage2Url);

        $memHandlerObj->addHitsTracking($this->profileid, '2', '12', 'old_mobile_website', $user_agent);

        $userObj = new memUser($this->profileid);
        if ($this->profileid != '') {
            $userObj->setMemStatus();
            $userObj->contactsRemaining = $userObj->getRemainingContacts($userObj->getProfileid());
            $expiryDate                 = $billingServiveStatus->getMaxExpiryDate($userObj->getProfileid());
        }

        if (strtotime($expiryDate) >= strtotime(date("Y-m-d"))) {
            $memID         = $userObj->memStatus;
            $this->memName = $memHandlerObj->getUserServiceName($memID);
        }

        $JMembershipObj = new JMembership();

        list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
        if ($currency == "DOL") {
            $this->currency = 'DOL';
            $currencyType   = VariableParams::$otherCurrency;
        } else {
            $this->currency = 'RS';
            $currencyType   = VariableParams::$indianCurrency;
        }
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($this->currency);

        $allMainMem = $memHandlerObj->fetchMembershipDetails("MAIN", $userObj, 'old_mobile_website');
        $this->tabs = $memHandlerObj->getMobMembershipTabs($allMainMem, $this->memID);

        // ankita Code removed to specifically remove 1 month membership from display
        /*if (isset($allMainMem['P']['P1'])) {
            unset($allMainMem['P']['P1']);
            unset($this->tabs[1]);
        }*/

        $this->mainSubMemId   = $request->getParameter('mainSubMemId');
        $this->allMemberships = $memHandlerObj->getMobSuggestedService($this->memID, $this->tabs);

        if ($this->mainSubMemId && $this->allMemberships) {
            $source = 51;

            $memHandlerObj->trackMembership($userObj, $source, $this->allMemberships, $this->mainSubMemId);
        }

        $allMainMem         = $memHandlerObj->fetchMembershipDetails("MAIN", $userObj, 'old_mobile_website');
        $discountTypeArr    = $memHandlerObj->getDiscountInfo($userObj,"NA","old_mobile_website");
        $discountType       = $discountTypeArr['TYPE'];
        $this->discountType = $discountType;
        if (strpos(discountType::OFFER_DISCOUNT, $discountType) !== false) {
            $this->discountActive  = '1';
            $discntId              = $memHandlerObj->isDiscountOfferActive();
            $this->discount_expiry = $memHandlerObj->getDiscountExpiry($discntId);
            $this->discountPercent = $memHandlerObj->getDiscountUpto();
        }
        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false) {
            $this->specialActive            = '1';
            $spclDiscntData                 = $memHandlerObj->getSpecialDiscount($userObj->getProfileId());
            $this->variable_discount_expiry = $spclDiscntData['EDATE'];
            $this->discountSpecial          = $spclDiscntData['DISCOUNT'];
        }

        $this->fest = $memHandlerObj->getFestiveFlag();
        if ($this->fest == "1") {
            $festiveLogRevampObj = new billing_FESTIVE_LOG_REVAMP();
            $offerDetails        = $festiveLogRevampObj->getActiveOfferDetails();
            $this->festEndDt     = date('d M Y', strtotime($offerDetails['END_DT']));
            $memActFunc          = new MembershipActionFunctions();
            $this->festDurBanner = $memActFunc->getFestDurBanner("L", $discountType, $userObj->getProfileid(), 0);
            unset($memActFunc);
        }

        // if (strlen($memHandlerObj->isRenewable($userObj->getProfileid())) > 2) $this->renew = 1;
        $discountArr = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
        foreach ($allMainMem as $mainMem => $subMem) {
            if ($userObj->profileid != '') {
                foreach ($subMem as $key => $value) {
                    //$discount = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
                    $discount     = $discountArr[$mainMem];
                    $mem_duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
                    if (strpos($mem_duration[0], "L")) {
                        $mem_duration = "L";
                    } else {
                        $mem_duration = $mem_duration[1];
                    }
                    $allMainMem[$mainMem][$key]['SPECIAL_DISCOUNT_PRICE'] = $allMainMem[$mainMem][$key]['PRICE'] - ($allMainMem[$mainMem][$key]['PRICE'] * $discount[$mem_duration]) / 100;
                }
            }
        }
        $allMainMem  = $memHandlerObj->getOfferPrice($allMainMem, $userObj, 'old_mobile_website');
        $minPriceArr = $memHandlerObj->fetchLowestActivePrices($userObj, $allMainMem, 'old_mobile_website');

        $memHandlerObj->oldJSMSsetSubcriptionExp($userObj, $memHandlerObj, $this);
        $this->renewalPercent = $memHandlerObj->getVariableRenewalDiscount($userObj->getProfileid());

        $this->userObj      = $userObj;
        $this->minPriceArr  = $minPriceArr;
        $this->allMainMem   = $allMainMem;
        $this->currencyType = $currencyType;
        $subStatus          = $memHandlerObj->getSubStatus($userObj->getProfileid());
        if ($subStatus && is_array($subStatus)) {
            foreach ($subStatus as $key => &$value) {

                $vasCheck = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $value['SERVICEID']);
                if (!in_array($vasCheck[0], VariableParams::$mainMembershipsArr) && !strpos($vasCheck[0], 'L')) {
                    unset($subStatus[$key]);
                    continue;
                }
                $memID = $memHandlerObj->retrieveCorrectMemID($value['SERVICEID'], $userObj);
                if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                    unset($subStatus[$key]);
                } else {
                    $esathiCheck = $memHandlerObj->checkForESathiService($this->profileid, $value['ACTIVATED_ON'], $value['EXPIRY_DT'], $value['SERVICEID']);
                    if (substr($esathiCheck, 0, 3) == "ESP") {
                        $value['SERVICEID'] = $esathiCheck;
                        $memID              = "ESP";
                    }
                    $value['SERVICE_NAME'] = $memHandlerObj->getUserServiceName($memID);
                    if (filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT)) {
                        $value['SERVICE_DURATION'] = filter_var($value['SERVICEID'], FILTER_SANITIZE_NUMBER_INT);
                    } else {
                        $value['SERVICE_DURATION'] = 'Unlimited';
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
        $this->subStatus = $subStatus;
        if (is_array($this->subStatus) && !empty($this->subStatus)) {
            $this->countActiveServices = count($this->subStatus);
        } else {
            $this->countActiveServices = 0;
        }
        $this->setTemplate("mem_planDetails");
    }

    // Old JSMS
    public function executeMobilePaymentOptions(sfWebRequest $request)
    {
        header('Cache-Control: no-transform');
        $this->device          = 'old_mobile_website';
        $this->loginData       = $request->getAttribute("loginData");
        $this->profileChecksum = $this->loginData['CHECKSUM'];
        $this->profileid       = $this->loginData['PROFILEID'];
        $subMem                = $request->getPostParameter("mainSubMemId");
        $this->mainSubMemId    = $subMem;
        $this->service         = $request->getParameter('service');
        $this->memID           = $this->service;
        $allMemberships        = $request->getPostParameter("allMemberships");
        $this->selMemberships  = $request->getPostParameter("selMembrshpToPayment");
        $memHandlerObj         = new MembershipHandler();
        $fromBackend           = $request->getParameter("from_source");
        $userObj               = new memUser($this->profileid);
        $user_agent            = $_SERVER['HTTP_USER_AGENT'];

        if (MobileCommon::isNewMobileSite()) {
            $this->getRequest()->setParameter('displayPage', 4);
            $this->getRequest()->setParameter('device', 'mobile_website');
            $this->getRequest()->setParameter('mainMem', $this->memID);

            $tempMemID  = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $this->mainSubMemId);
            $tempMemID2 = $tempMemID[0];

            if (strpos($tempMemID2, "L")) {
                $this->getRequest()->setParameter('mainMemDur', 'L');
            } else {
                $this->getRequest()->setParameter('mainMemDur', $tempMemID[1]);
            }
            $this->forward('membership', 'jsms');
        }

        $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobMemPage3Url);

        $memHandlerObj->addHitsTracking($this->profileid, '3', '13', 'old_mobile_website', $user_agent);

        if ($this->profileid != '') {
            $userData        = $memHandlerObj->getUserData($this->profileid);
            $this->USERNAME  = $userData['USERNAME'];
            $this->ADDRESS   = $userData['CONTACT'];
            $this->EMAIL     = $userData['EMAIL'];
            $this->PHONE_RES = $userData['PHONE_RES'];
            $this->PHONE_MOB = $userData['PHONE_MOB'];
            $this->PINCODE   = $userData['PINCODE'];
        }
        if ($fromBackend == "discount_link") {
            $profileCheckSum      = $request->getParameter("profilechecksum");
            $profileCheckSumArray = explode("i", $profileCheckSum);
            $profileid            = $profileCheckSumArray[1];
            $idCheckSum           = $request->getParameter("reqid");
            $idCheckSumArray      = explode("i", $idCheckSum);
            $idBackend            = $idCheckSumArray[1];
            if (md5($idBackend) == $idCheckSumArray[0]) {
                list($allMemberships, $discountBackend, $profileid) = $memHandlerObj->handleBackendCase($idBackend, $profileid);
            }
            $this->discountBackend = $discountBackend;
            $this->fromBackend     = 1;
            $this->backendId       = $idBackend;
            $this->backendCheckSum = $idCheckSum;
            $this->profilechecksum = $profileCheckSum;
            $this->reqid           = $idCheckSum;
        }
        if ($request->getParameter("jsExcRadioSel") != '') {
            $this->jsSel = $request->getParameter("jsExcRadioSel");
            $subMem      = $this->jsSel;
        }
        if ($request->getParameter("continueMainSubId") != '') {
            $subMem = $request->getParameter("continueMainSubId");
        }
        if ($subMem) {
            $memHandlerObj->serviceIdWhitelistCheck($subMem);
            $mainMem = substr($subMem, 0, 1);
        } else if ($allMemberships) {
            $memArray = explode(",", $allMemberships);
            for ($p = 0; $p < count($memArray); $p++) {
                if (strpos($memArray[$p], "main") !== false) {
                    $subMem  = substr($memArray[$p], 4);
                    $mainMem = substr($subMem, 0, 1);
                }
            }
        }
        if ($mainMem == "E") {
            $mainMem = "ESP";
        }

        if ($fromBackend == "discount_link") {
            $this->service    = $mainMem;
            $this->memID      = $mainMem;
            $this->backendVAS = array_slice($memArray, 1);
            foreach ($this->backendVAS as $kk => $vv) {
                if (!empty($vv)) {
                    $actualVASId        = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $vv);
                    $actualVASId        = $actualVASId[0];
                    $temp[$actualVASId] = $vv;
                }
            }
            $this->backendVAS = $temp;
        }
        $i             = 0;
        $payHandlerObj = new PaymentHandler();

        list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
        if ($currency == "DOL") {
            $this->currency       = 'DOL';
            $currencyType         = VariableParams::$otherCurrency;
            $this->paypal_visible = "Y";
            $this->cheque_in_US   = 'Y';
        } else {
            $this->currency          = 'RS';
            $currencyType            = VariableParams::$indianCurrency;
            $this->cash_card_visible = "Y";
            $this->pay_at_branches   = "Y";
            if ($this->profileid) {
                $this->courier_visible = $payHandlerObj->getPickup($this->profileid);
            }

        }
        if ($this->currency == "RS") {
            $this->matriPrice = VariableParams::$matriProfilePriceRS;
        } else {
            $this->matriPrice = VariableParams::$matriProfilePriceDOL;
        }

        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($this->currency);
        if ($this->profileid != '') {
            $userObj->setMemStatus();
        }
        if ($request->getPostParameter("mainSubMemId")) {
            $source           = 52;
            $navigationString = $request->getPostParameter("navigationStringToPayment");
            $selectedString   = $request->getPostParameter("selectedStringToPayment");
            $selectedString   = str_replace("main", "", $selectedString);
            $vasImpression    = $request->getPostParameter("VASImpressionToPayment");
            if (!$request->getPostParameter("showAllToPayment")) {
                $vasImpression = substr($vasImpression, 0, 5);
            }

            $memHandlerObj->trackMembership($userObj, $source, $navigationString, $selectedString, $vasImpression);
        } else if ($request->getPostParameter("allMembershipsToMain")) {
            $source = 31;
        } else {
            $source = 3;
            $memHandlerObj->trackMembership($userObj, $source);
        }
        $allMainMem = $memHandlerObj->fetchMembershipDetails("MAIN", $userObj, 'old_mobile_website');
        $vaMem      = $memHandlerObj->fetchMembershipDetails("ADDON", $userObj, 'old_mobile_website');

        $mainMemPrice    = $allMainMem[$mainMem][$subMem]["PRICE"];
        $mainMemDuration = $allMainMem[$mainMem][$subMem]["DURATION"];
        if ($mainMem == 'ESP' && $mainMemDuration == 12) {
            $mainMemDuration = 1188;
        }
        if ($mainMemDuration == 1188) {
            $mainMemDuration = 'L';
        }

        $this->instaContacts = $allMainMem[$mainMem][$subMem]["CALL"];
        $this->fest          = $memHandlerObj->getFestiveFlag();
        $discountTypeArr     = $memHandlerObj->getDiscountInfo($userObj,"NA","old_mobile_website");
        $discountType        = $discountTypeArr['TYPE'];
        $this->discountType  = $discountType;
        if (strpos(discountType::OFFER_DISCOUNT, $discountType) !== false) {
            $this->discountActive  = '1';
            $discntId              = $memHandlerObj->isDiscountOfferActive();
            $this->discount_expiry = $memHandlerObj->getDiscountExpiry($discntId);
            $this->discountPercent = $memHandlerObj->getDiscountUpto();
        }
        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false) {
            $this->specialActive            = '1';
            $spclDiscntData                 = $memHandlerObj->getSpecialDiscount($userObj->getProfileId());
            $this->variable_discount_expiry = $spclDiscntData['EDATE'];
            $this->discountSpecial          = $spclDiscntData['DISCOUNT'];
        }

        $this->fest = $memHandlerObj->getFestiveFlag();
        if ($this->fest == "1") {
            $festiveLogRevampObj = new billing_FESTIVE_LOG_REVAMP();
            $offerDetails        = $festiveLogRevampObj->getActiveOfferDetails();
            $this->festEndDt     = date('d M Y', strtotime($offerDetails['END_DT']));
            $memActFunc          = new MembershipActionFunctions();
            $this->festDurBanner = $memActFunc->getFestDurBanner("L", $discountType, $userObj->getProfileid(), 0);
            unset($memActFunc);
        }

        // if (strlen($memHandlerObj->isRenewable($userObj->getProfileid())) > 2) $this->renew = 1;
        $discountArr = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
        foreach ($allMainMem as $mainMem => $subMem) {
            if ($userObj->profileid != '') {
                foreach ($subMem as $key => $value) {
                    //$discount = $memHandlerObj->getSpecialDiscountForAllDurations($userObj->getProfileid());
                    $discount     = $discountArr[$mainMem];
                    $mem_duration = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
                    if (strpos($mem_duration[0], "L")) {
                        $mem_duration = "L";
                    } else {
                        $mem_duration = $mem_duration[1];
                    }
                    $allMainMem[$mainMem][$key]['SPECIAL_DISCOUNT_PRICE'] = $allMainMem[$mainMem][$key]['PRICE'] - ($allMainMem[$mainMem][$key]['PRICE'] * $discount[$mem_duration]) / 100;
                    $temp                                                 = $allMainMem[$mainMemb][$key];
                    unset($allMainMem[$mainMemb][$key]);
                    $allMainMem[$mainMemb][$mem_duration] = $temp;
                }
            }
        }
        $allMainMem  = $memHandlerObj->getOfferPrice($allMainMem, $userObj, 'old_mobile_website');
        $minPriceArr = $memHandlerObj->fetchLowestActivePrices($userObj, $allMainMem, 'old_mobile_website');

        $allDiscounts['RENEWAL'] = $memHandlerObj->getVariableRenewalDiscount($userObj->getProfileid());

        $festOffrLookupObj       = new billing_FESTIVE_OFFER_LOOKUP();
        $allDiscounts['FESTIVE'] = $festOffrLookupObj->getMaxFestiveDiscountPercentage();
        unset($festOffrLookupObj);
        $allDiscounts['SPECIAL'] = $this->discountSpecial;
        $allDiscounts['OFFER']   = $offeredDiscountPercent;
        $this->allDiscounts      = $allDiscounts;
        $addOns                  = VariableParams::$eSathiAddOns;
        $this->currencyType      = $currencyType;
        $this->subMem            = $subMem;
        $this->userObj           = $userObj;
        $this->mainMem           = $mainMem;
        $this->mainMemName       = $memHandlerObj->getUserServiceName($this->service);
        $messages                = $memHandlerObj->getServiceMessages(array(
            $this->memID,
        ));
        $this->messages        = $messages[0];
        $this->mainMemPrice    = $mainMemPrice;
        $this->mainMemDuration = $mainMemDuration;

        // Handle discount link
        if ($fromBackend == "discount_link") {
            $this->specialActive = '1';
            $this->mainSubMemId  = $subMem;
            $this->discSel       = 1;
            foreach ($vaMem as $key => &$val) {
                foreach ($val as $kk => &$vv) {
                    $temp = explode("-", $vv['NAME']);
                    if ($temp[0] == "Matri") {
                        $vv['NAME'] = "Matri-Profile";
                    } else {
                        $vv['NAME'] = $temp[0];
                    }
                    if (in_array($kk, $this->backendVAS)) {
                        $totalVASPrice += $vv['PRICE'];
                    }
                }
            }
            if (isset($this->backendVAS) && is_array($this->backendVAS) && !empty($this->backendVAS)) {
                $this->valueAddedServices                                        = 1;
                $allMainMem[$this->memID][$this->mainMemDuration]['OFFER_PRICE'] = round(($allMainMem[$this->memID][$this->mainMemDuration]['PRICE'] + $totalVASPrice) * (1 - ($discountBackend / 100)), 0, PHP_ROUND_HALF_UP);
                $minPriceArr[$this->memID]['OFFER_PRICE']                        = round(($allMainMem[$this->memID][$this->mainMemDuration]['PRICE'] + $totalVASPrice) * (1 - ($discountBackend / 100)), 0, PHP_ROUND_HALF_UP);
                $this->mainSubMemId .= ',' . implode(',', $this->backendVAS);
            } else {
                $allMainMem[$this->memID][$this->mainMemDuration]['OFFER_PRICE'] = round($allMainMem[$this->memID][$this->mainMemDuration]['PRICE'] * (1 - ($discountBackend / 100)), 0, PHP_ROUND_HALF_UP);
                $minPriceArr[$this->memID]['OFFER_PRICE']                        = round($allMainMem[$this->memID][$this->mainMemDuration]['PRICE'] * (1 - ($discountBackend / 100)), 0, PHP_ROUND_HALF_UP);
            }
        }

        $this->allMainMem     = $allMainMem;
        $this->minPriceArr    = $minPriceArr;
        $this->vaMem          = $vaMem;
        $this->allMemberships = $allMemberships;
        $this->ORDERDATE      = date('M j,Y');

        if ($allMainMem[$this->memID][$this->mainMemDuration]['OFFER_PRICE'] != $allMainMem[$this->memID][$this->mainMemDuration]['PRICE']) {
            $this->track_total    = $allMainMem[$this->memID][$this->mainMemDuration]['OFFER_PRICE'];
            $this->track_discount = $allMainMem[$this->memID][$this->mainMemDuration]['PRICE'] - $allMainMem[$this->memID][$this->mainMemDuration]['OFFER_PRICE'];
        } else {
            $this->track_total    = $allMainMem[$this->memID][$this->mainMemDuration]['PRICE'];
            $this->track_discount = 0;
        }
        $this->setTemplate("mem_paymentOptions");
    }

    public function executeCityBranches(sfWebRequest $request)
    {
        $payHandlerObj  = new PaymentHandler();
        $city_value     = $request->getParameter('city_value');
        $nearByBranches = $payHandlerObj->getBranchesInCity($city_value);
        echo json_encode($nearByBranches);
        die;
        return sfView::NONE;
    }

    public function executePaymentOptionsTracking(sfWebRequest $request)
    {
        if (MobileCommon::isMobile()) {
            $source = 13;
            $device = 'old_mobile_website';
        } else {
            $device = 'desktop';
            $source = 3;
        }
        $this->loginData       = $request->getAttribute("loginData");
        $this->profileChecksum = $this->loginData['CHECKSUM'];
        $this->profileid       = $this->loginData['PROFILEID'];
        $memHandlerObj         = new MembershipHandler();
        $userObj               = new memUser($this->profileid);

        $ipAddress = getenv("HTTP_X_FORWARDED_FOR") ? getenv("HTTP_X_FORWARDED_FOR") : (getenv("HTTP_TRUE_CLIENT_IP") ? getenv("HTTP_TRUE_CLIENT_IP") : getenv("REMOTE_ADDR"));
        if (strstr($ipAddress, ",")) {
            $ip_new    = explode(",", $ipAddress);
            $ipAddress = $ip_new[0];
        }
        $JMembershipObj = new JMembership();

        list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
        if ($currency == "DOL") {
            $this->currency = 'DOL';
            $currencyType   = VariableParams::$otherCurrency;
        } else {
            $this->currency = 'RS';
            $currencyType   = VariableParams::$indianCurrency;
        }
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($this->currency);
        $userObj->setMemStatus();
        $navigationString = $request->getParameter('navigationString');
        $track_discount   = $request->getParameter('track_discount');
        $track_total      = $request->getParameter('track_total');
        $selectedString   = $request->getParameter('track_memberships');
        $trackType        = $request->getParameter('trackType');
        $paymentTab       = $request->getParameter('paymentTab');
        $memHandlerObj->trackMembership($userObj, $source, $navigationString, $selectedString, $vasImpression, $track_discount, $track_total, $paymentTab, $trackType, $device);
        die;
    }

    // Old JSMS
    public function executeAddCallBck(sfWebRequest $request)
    {
        $memHandlerObj = new MembershipHandler();

        $this->profileid      = $request->getAttribute('profileid');
        $this->device         = $request->getParameter('device');
        $this->channel        = $request->getParameter('channel');
        $this->callbackSource = $request->getParameter('callbackSource');
        $this->date           = $request->getParameter('date');
        $this->startTime      = $request->getParameter('startTime');
        $this->endTime        = $request->getParameter('endTime');
        
        $orgTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Calcutta");
        $currentTime = time();
        $cutoffTimeEnd = strtotime(date("Y-m-d 21:00:00"));
        $cutoffTimeStart = strtotime(date("Y-m-d 09:00:00"));            
        if(empty($this->date) || !isset($this->date)) {
            if ($currentTime < $cutoffTimeEnd) {
                $this->date = date("Y-m-d", time());
            } else {
                $this->date = date("Y-m-d", strtotime('+1 day', time()));
            }
        }
        if (date("H", strtotime($currentTime)) >= 20) {
            $this->date = date("Y-m-d", strtotime('+1 day', time()));
        }
        if(empty($this->startTime) || !isset($this->startTime)) {
            if (($cutoffTimeStart < $currentTime) && ($currentTime < $cutoffTimeEnd) || (date("H", strtotime($currentTime)) < 20 && date("H", strtotime($currentTime)) >= 9)) { 
                $this->startTime = date("H:i:s", time()+3600);
            } else {
                $this->startTime = "09:00:00";
            }
        }
        if(empty($this->endTime) || !isset($this->endTime)) {
            $this->endTime = "21:00:00";
        }
    	$reqDate =$this->date;
    	$reqTime =date('g:i A',strtotime($this->startTime));

        date_default_timezone_set($orgTZ);
        
        $profileObj = new PROFILE();

        //validate inputs if passed to prevent cross site scripting
        if ($request->getParameter('phNo') && is_numeric($request->getParameter('phNo')) == false) {
            $message = "Something went wrong, enter the valid phone no.";
            echo $message;
            die;
        }
        if ($request->getParameter('profileid') && is_numeric($request->getParameter('profileid')) == false) {
            $message = "Something went wrong";
            echo $message;
            die;
        }

        if ($request->getParameter('email') && strpos($request->getParameter('email'), '<script>') !== false) {
            $message = "Something went wrong";
            echo $message;
            die;
        } else {
            if ($this->profileid) {
                $bodyFields     = $profileObj->getDetail($this->profileid, 'PROFILEID', "USERNAME,EMAIL,PHONE_MOB,PHONE_WITH_STD,AGE,GENDER");
                $Username       = $bodyFields['USERNAME'];
                $email          = $bodyFields['EMAIL'];
                $mobile1        = $bodyFields['PHONE_MOB'];
                $phone_with_std = $bodyFields['PHONE_WITH_STD'];
                $age            = $bodyFields['AGE'];
                $gender         = $bodyFields['GENDER'];

                if ($mobile1 == '') {
                    $contact_str = "$phone_with_std";
                } else {
                    $contact_str = "$mobile1";
                }

                $this->mobNumber = $contact_str;
            }

            if (MobileCommon::isMobile() && !MobileCommon::isNewMobileSite() && !$request->getParameter('INTERNAL')) {
                $memCallback    = 1;
                $this->username = $Username;
                $msgContent     = "Membership Plans";
                $subject        = "$Username is interested in $msgContent";
                $msgBody        = "<html><body>$Username is interested in knowing more about $msgContent. Please contact at $email, $contact_str as requested on $reqDate at $reqTime.</body></html>";
                $emailSend      = $memHandlerObj->checkEmailSendForDay($this->profileid, $email);
                if (!$emailSend) {
                    if ($this->profileid) {
                        $profileAllotedExecEmail = $memHandlerObj->getAllotedExecEmail($this->profileid);
                    }

                    if (!$profileAllotedExecEmail) {
                        $profileAllotedExecEmail = 'inbound@jeevansathi.com';
                    }

                    $memHandlerObj->sendEmailForCallback($subject, $msgBody, $profileAllotedExecEmail);
                }
                $memHandlerObj->memCallbackTracking($this->profileid, $contact_str, $email, $this->device, $this->channel, $this->callbackSource, $this->date, $this->startTime, $this->endTime);
                $this->referer = $request->getReferer();
                $this->setTemplate('mem_addCallBack');
            } else {
                $this->profileid  = $request->getAttribute('profileid');
                $execCallbackType = $request->getParameter('execCallbackType');
                $tabVal           = $request->getParameter('tabVal');
                $tabContentArr    = VariableParams::$memTabContent;
                if ($execCallbackType == 'JS_ALL') {
                    $memCallback = 1;
                }

                if (!$this->profileid) {
                    $phoneNo   = $request->getParameter('phNo');
                    $email     = $request->getParameter('email');
                    $jsSelectd = $request->getParameter('jsSelectd');

                    if ($memCallback) {
                        $subject = "Lead for Inbound Sales";
                        $msgBody = $tabContentArr[1];
                    } else {
                        if (!MobileCommon::isNewMobileSite() && $request->getPostParameter('callRequest') == 1) {
                            $phoneNo = $request->getPostParameter('mobNumber');
                            $subject = "Callback request for JS Exclusive";
                            $msgBody = "<html><body>A callback request has been placed for explaining JS Exclusive service. Details below:</br>Username: Unregistered</br>Contact Number: " . $request->getPostParameter('mobNumber') . "</br>Date to call: " . $this->date . "</br>Time to call: From " . $this->startTime . " To " . $this->endTime . "</br></body></html>";
                        } else {
                            $subject = "Lead for JS Exclusive";
                            $msgBody = "JS Exclusive";
                            $msgBody = "<html><body>Someone is interested in knowing more about $msgBody. Please contact at " . $email . " or " . $phoneNo . " as requested on $reqDate at $reqTime.</body></html>";
                        }
                        $memHandlerObj->addCallBack($phoneNo, $email, $jsSelectd, 0, $this->device, $this->channel, $this->callbackSource, $this->date, $this->startTime, $this->endTime);
                    }
                } else {
                    $profileObj  = new PROFILE();
                    $contact_arr = array();
                    $contact_arr = $profileObj->getExtendedContacts(1);
                    if ($contact_arr != '') {
                        $mobile2 = $contact_arr['ALT_MOBILE'];
                    }

                    if ($tabVal && $memCallback) {
                        $msgContent = $tabContentArr[$tabVal];
                    } else {
                        $msgContent = 'JS Exclusive';
                    }

                    if ($mobile1 == '' && $phone_with_std == '') {
                        $contact_str = "$mobile2";
                    } else if ($mobile1 == '' && $mobile2 == '') {
                        $contact_str = "$phone_with_std";
                    } else if ($phone_with_std == '' && $mobile2 == '') {
                        $contact_str = "$mobile1";
                    } else if ($mobile1 == '') {
                        $contact_str = "$phone_with_std or $mobile2";
                    } else if ($mobile2 == '') {
                        $contact_str = "$mobile1 or $phone_with_std";
                    } else if ($phone_with_std == '') {
                        $contact_str = "$mobile1 or $mobile2";
                    } else {
                        $contact_str = "$mobile1 or $phone_with_std or $mobile2";
                    }

                    if (!MobileCommon::isNewMobileSite() && $request->getPostParameter('callRequest') == 1) {
                        $subject = "Callback request for JS Exclusive";
                        $msgBody = "<html><body>A callback request has been placed for explaining JS Exclusive service. Details below:</br>Username: " . $Username . "</br>Contact Number: " . $request->getPostParameter('mobNumber') . "</br>Date to call: " . $this->date . "</br>Time to call: From " . $this->startTime . " To " . $this->endTime . "</br></body></html>";
                    } else {
                        $subject = "$Username is interested in $msgContent";
                        $msgBody = "<html><body>$Username is interested in knowing more about $msgContent. Please contact at $email, $contact_str as requested on $reqDate at $reqTime.</body></html>";
                    }
                    if ($execCallbackType == 'JS_EXC') {
                        $jsSelectd = $request->getParameter('jsSelectd');
                        if (empty($jsSelectd)) {
                            $jsSelectd = "X";
                        }
                        $phoneNo = $request->getPostParameter('mobNumber');
                        if (empty($phoneNo)) {
                            $phoneNo = $mobile1;
                        }
                        $memHandlerObj->addCallBack($phoneNo, $email, $jsSelectd, $this->profileid, $this->device, $this->channel, $this->callbackSource, $this->date, $this->startTime, $this->endTime);
                    }
                }
                if ($memCallback) {
                    $emailSend = $memHandlerObj->checkEmailSendForDay($this->profileid, $email);
                    if (!$emailSend) {
                        if ($this->profileid) {
                            $profileAllotedExecEmail = $memHandlerObj->getAllotedExecEmail($this->profileid);
                        }

                        if (!$profileAllotedExecEmail) {
                            $profileAllotedExecEmail = 'inbound@jeevansathi.com';
                        }

                        $memHandlerObj->sendEmailForCallback($subject, $msgBody, $profileAllotedExecEmail);
                    }
                    if (empty($phoneNo)) {
                        $phoneNo = $mobile1;
                    }
                    $memHandlerObj->memCallbackTracking($this->profileid, $phoneNo, $email, $this->device, $this->channel, $this->callbackSource, $this->date, $this->startTime, $this->endTime);
                } else {
                    //if (!$this->profileid) {
                    if ($this->profileid && $gender == 'M' && $age <= 23) {} else {
                        $memHandlerObj->sendEmailForCallback($subject, $msgBody);
                    }

                    //}
                }
                if (MobileCommon::isMobile() || isset($this->device)) {
                    if ($this->device == 'desktop') {
                        $message = "Our customer service executive will contact you on your mobile number " . $request->getParameter('phNo') . " as requested.";
                    } elseif ($memCallback) {
                        $message = "Our customer service executive will contact you on your mobile number " . $this->mobNumber . " as requested.";
                    } else {
                        $message = "A Jeevansathi customer service executive will get in touch with you to explain the benefits of JS Exclusive plan.";
                    }
                } else {
                    $message = "Thank you for showing interest in our services.\nOur matchmaking expert will contact you as soon as possible.";
                }
                echo $message;
                $internalFlag = $request->getParameter("INTERNAL");
                //RCB Tracking
                $rcbStatus        = $request->getParameter('rcbResponse');
                $arrAllowedStatus = array('Y', 'N');
                if (isset($rcbStatus) && in_array($rcbStatus, $arrAllowedStatus)) {
                    $loginObj  = LoggedInProfile::getInstance();
                    $rcbObject = new RequestCallBack($loginObj);
                    $rcbObject->updateThis($rcbStatus);
                    unset($rcbObject);
                }
                if (!empty($internalFlag)) {
                    return sfView::NONE;
                }
                die();
            }
        }
    }

    // MyJS Schedule Visit
    public function executeScheduleVisit(sfWebRequest $request)
    {
        $profileid     = $request->getParameter('profileid');
        $memHandlerObj = new MembershipHandler();
        $userData      = $memHandlerObj->getUserData($profileid);
        $memHandlerObj->updateScheduleVisitEntry($profileid);
        die();
    }

    /*fetch currently active membership message data for profile
     * @return: $output as api response
     */
    public function executeGetMembershipMessageData(sfWebRequest $request)
    {
        $profileObj = LoggedInProfile::getInstance('newjs_master');
        $profileid  = $profileObj->getPROFILEID();

        $memObj = new MembershipHandler();
        $output = $memObj->getMembershipPanelContent($profileid);
        unset($memObj);

        $respObj = ApiResponseHandler::getInstance();

        if ($output) {
            $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        } else {
            $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        }

        $respObj->setResponseBody($output);
        $respObj->generateResponse();
        die();
    }
}
