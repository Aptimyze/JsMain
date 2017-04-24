<?php

// crmInterface actions.
// @package    jeevansathi
// @subpackage crmInterface
// @author     Avneet Singh Bindra
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
class crmInterfaceActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }

    public function executeCouponInterface(sfWebRequest $request)
    {
        $this->cid                                                              = $request->getParameter('cid');
        $this->name                                                             = $request->getParameter('name');
        $commCrmFuncObj                                                         = new CommonCrmInterfaceFunctions();
        $this->dropdownData                                                     = $commCrmFuncObj->getNext15DaysForDropDown();
        list($this->serviceArray, $this->serviceDurations, $this->serviceNames) = $commCrmFuncObj->getCouponApplicableServiceArray();
        //Dynamic Width of the table, will adjust if services durations are added or removed
        $this->durPerc = floor(100 / (count($this->serviceDurations) + 1));
        if ($request->getParameter('submit')) {
            $this->usageLimit     = $request->getParameter('usageLimit');
            $this->expiryDate     = $request->getParameter('expiryDate');
            $this->serviceDiscArr = $request->getParameter('serviceDisc');
            $memHandlerObj        = new MembershipHandler();
            $couponOfferObj       = new billing_COUPON_OFFER();
            $couponOfferLookupObj = new billing_COUPON_DISCOUNT_LOOKUP();
            $couponSuccess        = false;
            while (!$couponSuccess) {
                $start_dt            = date("Y-m-d H:i:s");
                $this->generatedCode = $memHandlerObj->generateCouponCode();
                $couponID            = $couponOfferObj->getCouponID($this->generatedCode);
                if (empty($couponID)) {
                    $couponSuccess = true;
                }
            }
            if ($couponSuccess) {
                $couponOfferObj->insertNewCoupon($this->generatedCode, $start_dt, $this->expiryDate, $this->usageLimit);
            }
            foreach ($this->serviceDiscArr as $key => $val) {
                if ($val != 0) {
                    $couponInsertID = $couponOfferObj->getCouponID($this->generatedCode);
                    $couponOfferLookupObj->insertDiscount($couponInsertID, $key, $val);
                }
            }
            $this->expDateFormat = date("d M Y", strtotime($this->expiryDate));
            unset($memHandlerObj);
            unset($couponOfferObj);
            unset($couponOfferLookupObj);
            $this->setTemplate('couponGenerated');
        }
        unset($commCrmFuncObj);
        //die;
    }

    // Manage VD Offer
    public function executeManageVdOffer(sfWebRequest $request)
    {
        $this->cid                 = $request->getParameter('cid');
        $this->name                = $request->getParameter('name');
        $agentAllocationDetailsObj = new AgentAllocationDetails();
        $priv                      = $agentAllocationDetailsObj->getprivilage($this->cid);
        $priv                      = explode('+', $priv);
        if (in_array('MG', $priv) || in_array('SLHDO', $priv)) {
            $this->MGorSLHDO = 1;
        }
        if (in_array('CRMTEC', $priv) || in_array('DA', $priv)) {
            $this->CRMTECorDA = 1;
        }
    }
    // Extend VD Offer date
    public function executeExtendVdOffer(sfWebRequest $request)
    {
        $this->cid      = $request->getParameter('cid');
        $this->name     = $request->getParameter('name');
        $curDate        = date("Y-m-d");
        $commCrmFuncObj = new CommonCrmInterfaceFunctions();

        if ($request->getParameter('submit')) {
            $startDate = $request->getParameter('startDate');
            $expDate   = $request->getParameter('expiryDate');
            if ($expDate) {
                $commCrmFuncObj->extendVdOfferDate($expDate, $startDate);
                $this->vdExtend     = 1;
                $this->extendedDate = date("d M Y", strtotime($expDate));
                $this->startDate    = date("d M Y", strtotime($startDate));
            }
        }
        $this->vdStartDateDrowdown = $commCrmFuncObj->getVdStartDates();
        $vdExpiryDate              = $commCrmFuncObj->getVdExpiryDate();
        $this->vdExpiryDate        = date("d M Y", strtotime($vdExpiryDate));
        $this->expiryDateDropdown  = $commCrmFuncObj->getDateDropDown($curDate, 15);
    }
    // Start VD Offer
    public function executeStartVdOffer(sfWebRequest $request)
    {
        $this->cid      = $request->getParameter('cid');
        $commCrmFuncObj = new CommonCrmInterfaceFunctions();
        $curDate        = date("Y-m-d");

        if ($request->getParameter('submit') == 'Start') {
            $startDate = $request->getParameter('vdStartDate');
            $endDate   = $request->getParameter('vdEndDate');

            if ($startDate && $endDate && (strtotime($endDate) >= strtotime($startDate))) {
                $commCrmFuncObj->startVdOffer($startDate, $endDate);
                $this->startDate    = date("d M Y", strtotime($startDate));
                $this->endDate      = date("d M Y", strtotime($endDate));
                $this->vdSuccess    = true;
                $this->disableStart = true;
            } else {
                $this->vdError = true;
            }

        } else {
            $vdExpiryDate = $commCrmFuncObj->getVdExpiryDate();
            if (strtotime($vdExpiryDate) >= strtotime($curDate)) {
                $this->vdActive     = true;
                $this->vdExpiryDate = date("d M Y", strtotime($vdExpiryDate));
            }
        }
        $this->vdDateDropdown = $commCrmFuncObj->getDateDropDown($curDate, 15);
    }

    // Schedule VD Sms
    public function executeScheduleVdSms(sfWebRequest $request)
    {
        $formArr   = $request->getParameterHolder()->getAll();
        $this->cid = $formArr['cid'];

        $vdSmsLogObj = new billing_VARIABLE_DISCOUNT_SMS_LOG();
        if ($vdSmsLogObj->isStatusY()) {
            $this->errorMsg0 = "SMS is already scheduled.";
        } else {
            if ($formArr['frequency']) {
                $this->frequency    = $formArr['frequency'];
                $this->frequencyArr = range(1, $this->frequency);

                $this->dateArr = array();
                for ($i = 0; $i < $this->frequency + 10; $i++) {
                    $this->dateArr[] = date('Y-m-d', strtotime('+' . $i . ' days'));
                }
            }
            if ($formArr['isDone']) {

                $dateArr = $formArr['selectedDateArr'];
                for ($j = 0; $j < count($dateArr); $j++) {
                    if ($dateArr[$j] > $dateArr[$j + 1]) {
                        if ($j != count($dateArr) - 1) {
                            $this->errorMsg = "Oops, please provide correct date values";
                        }

                    }
                }
                if (count(array_unique($formArr['selectedDateArr'])) != $formArr['frequency']) {
                    $this->errorMsg = "Oops, please provide unique date values";
                }

                if (!$this->errorMsg) {
                    $selectedDateArr = array();
                    foreach ($formArr['selectedDateArr'] as $k => $dd) {

                        $selectedDateArr[$k] = date('Y-m-d', strtotime($dd));
                    }
                    $vdSmsLogObj->insertVdSmsSchedule($selectedDateArr, $formArr['frequency']);
                    $this->successMsg = "Updated successfully ...";
                }
            }
        }
        unset($vdSmsLogObj);
    }

    // Schedule VD Mailer
    public function executeScheduleVdMailer(sfWebRequest $request)
    {
        $formArr   = $request->getParameterHolder()->getAll();
        $this->cid = $formArr['cid'];
        $dd        = $formArr['selectedDate'];

        $vdMailerLogObj = new billing_VARIABLE_DISCOUNT_MAILER_LOG();
        if ($vdMailerLogObj->sendMailerToday($dd)) {
            $this->errorMsg0 = "Mailer is already scheduled.";
        } else {
            $this->dateArr = array();
            for ($i = 0; $i < 9; $i++) {
                $this->dateArr[] = date('Y-m-d', strtotime('+' . $i . ' days'));
            }
            if ($formArr['isDone']) {
                $selectedDate = date('Y-m-d', strtotime($dd));
                $vdMailerLogObj->insertVdMailerSchedule($selectedDate);
                $this->successMsg = "Updated successfully ...";
            }
        }
        unset($vdMailerLogObj);
    }

    // Manage Cash Discount Offer
    public function executeManageCashDiscountOffer(sfWebRequest $request)
    {
        $this->cid = $request->getParameter('cid');
    }

    // Start Cash Discount Offer
    public function executeStartCashDiscountOffer(sfWebRequest $request)
    {
        $this->cid            = $request->getParameter('cid');
        $agentAllocDetailsObj = new AgentAllocationDetails();
        $agentName            = $agentAllocDetailsObj->fetchAgentName($this->cid);
        $commCrmFuncObj       = new CommonCrmInterfaceFunctions();
        $curDate              = date("Y-m-d");

        if ($request->getParameter('submit') == 'Start') {
            $startDate = $request->getParameter('cdStartDate');
            $endDate   = $request->getParameter('cdEndDate');

            if ($startDate && $endDate && (strtotime($endDate) >= strtotime($startDate))) {
                $commCrmFuncObj->startCashDiscountOffer($startDate, $endDate, $agentName);
                $this->startDate       = date("d M Y", strtotime($startDate));
                $this->endDate         = date("d M Y", strtotime($endDate));
                $this->discountSuccess = true;
                $memCacheObject        = JsMemcache::getInstance();
                $membershipKeyArray    = VariableParams::$membershipKeyArray;
                $keys_removed          = "";
                foreach ($membershipKeyArray as $key => $keyVal) {
                    $memCacheObject->remove($keyVal);
                    $keys_removed .= $keyVal . ",\n";
                }
            } else {
                $this->discountError = true;
            }

        } else {
            $expiryDate = $commCrmFuncObj->getCashDiscountExpiryDate();
            if (strtotime($expiryDate) >= strtotime($curDate)) {
                $this->cashDiscountActive = true;
                $this->expiryDate         = date("d M Y", strtotime($expiryDate));
            }
        }
        $this->cashDiscountDateDropdown = $commCrmFuncObj->getDateDropDown($curDate, 15);
    }

    // Product-wise Cash Discount Offer
    public function executeProductWiseCashDiscount(sfWebRequest $request)
    {
        $this->cid                                                                                     = $request->getParameter('cid');
        $this->name                                                                                    = $request->getParameter('name');
        $commCrmFuncObj                                                                                = new CommonCrmInterfaceFunctions();
        list($this->serviceArray, $this->serviceDurations, $this->serviceNames, $this->activeServices) = $commCrmFuncObj->getActiveMainMembershipDetailsArr();
        $this->durPerc                                                                                 = floor(100 / (count($this->serviceDurations) + 1));
        // Check if Discount Offer is active
        $discountOfferLogObj = new billing_DISCOUNT_OFFER_LOG();
        $billDiscOffrObj     = new billing_DISCOUNT_OFFER('newjs_masterDDL');
        $discountOfferID     = $discountOfferLogObj->checkDiscountOffer();
        if ($discountOfferID) {
            $this->successMsg = "Discount offer is Currently Active";
            foreach ($this->activeServices as $key => &$val) {
                if ($disc = $billDiscOffrObj->getDiscountOffer($key)) {
                    $val = $disc;
                }
            }
        } else {
            $this->errorMsg = "Discount offer is Currently Inactive";
        }
        if ($request->getParameter('submit')) {
            $this->serviceDiscArr = $request->getParameter('serviceDisc');
            $discountOfferID      = $discountOfferLogObj->checkDiscountOffer();
            if (!$discountOfferID) {
                // Truncate table before inserting new entries
                $billDiscOffrObj->truncateTable();
            }
            foreach ($this->serviceDiscArr as $key => $val) {
                // Check if value already exists
                $existingVal = $billDiscOffrObj->getDiscountOffer($key);
                if (!empty($existingVal) && $val == 0) {
                    $billDiscOffrObj->removeDiscountValue($key);
                } elseif (!empty($existingVal) && $existingVal != $val) {
                    $billDiscOffrObj->updateDiscountValue($key, $val);
                } elseif (empty($existingVal) && $val != 0) {
                    $billDiscOffrObj->insertDiscountValue($key, $val);
                }
            }
            foreach ($this->activeServices as $key => &$val) {
                $disc = $billDiscOffrObj->getDiscountOffer($key);
                if ($disc) {
                    $val = $disc;
                } else {
                    $val = 0;
                }

            }
            // Memcache server flush
            $memHandlerObject = new MembershipHandler();
            $memHandlerObject->flushMemcacheForMembership();
            $this->successMsg   = "Discount Values Successfully Applied";
            $memCacheObject     = JsMemcache::getInstance();
            $membershipKeyArray = VariableParams::$membershipKeyArray;
            $keys_removed       = "";
            foreach ($membershipKeyArray as $key => $keyVal) {
                $memCacheObject->remove($keyVal);
                $keys_removed .= $keyVal . ",\n";
            }
        }
    }

    // Manage Festive Offer
    public function executeManageFestiveOffer(sfWebRequest $request)
    {
        $this->cid  = $request->getParameter('cid');
        $this->name = $request->getParameter('name');
    }

    // Festive Offer Mapping Interface
    public function executeFestiveOfferMappingInterface(sfWebRequest $request)
    {
        $this->cid         = $request->getParameter('cid');
        $this->name        = $request->getParameter('name');
        $commCrmFuncObj    = new CommonCrmInterfaceFunctions();
        $billFestLogRevObj = new billing_FESTIVE_LOG_REVAMP();
        $this->durPerc     = 33; // Set to static value since we have only three column fixed
        $fest              = $billFestLogRevObj->getFestiveFlag();
        if ($fest && $request->getParameter('submit')) {
            $this->errorMsg = "Sorry !! Festive Offer is Currently Active and Values cannot be updated";
        } else if ($fest) {
            $this->successMsg = "Festive Offer is Currently Active";
        } else {
            $this->errorMsg = "Festive Offer is Currently Inactive";
        }
        $this->offerArr = $commCrmFuncObj->getFestiveOfferMappingDetails();
        if ($request->getParameter('submit') && !$fest) {
            $this->discDurArr  = $request->getParameter('discDur');
            $this->discPercArr = $request->getParameter('discPerc');
            $festLookupObj     = new billing_FESTIVE_OFFER_LOOKUP();
            foreach ($this->discDurArr as $key => $value) {
                $tempID     = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
                $servID     = $tempID[0];
                $dur        = $tempID[1];
                $updatedSid = $servID . ($value + $dur);
                $festLookupObj->updateDurationDiscount($key, $value, $updatedSid);
            }
            foreach ($this->discPercArr as $key => $value) {
                $festLookupObj->updatePercentageDiscount($key, $value);
            }
            $this->successMsg = "Discount/Duration Values Successfully Applied";
            unset($this->errorMsg);
            $memCacheObject     = JsMemcache::getInstance();
            $membershipKeyArray = VariableParams::$membershipKeyArray;
            $keys_removed       = "";
            foreach ($membershipKeyArray as $key => $keyVal) {
                $memCacheObject->remove($keyVal);
                $keys_removed .= $keyVal . ",\n";
            }
            // get the updated values from database for display
            $this->offerArr = $commCrmFuncObj->getFestiveOfferMappingDetails();
        }
        // Memcache server flush
        $memHandlerObject = new MembershipHandler();
        $memHandlerObject->flushMemcacheForMembership();
    }

    // Manage Comissions interface
    public function executeManageCommissions(sfWebRequest $request)
    {
        $this->cid      = $request->getParameter('cid');
        $this->name     = $request->getParameter('name');
        $commCrmFuncObj = new CommonCrmInterfaceFunctions();

        // Fetch currently active Apple Comission Percentage
        $billingAppleCommPercLogObj  = new billing_APPLE_COMMISSION_PERCENTAGE_LOG();
        $this->activeAppleCommission = $billingAppleCommPercLogObj->getActiveAppleCommissionPercentage(date('Y-m-d H:i:s'));

        // Fetch all Agents which Franchisee Priviledge
        $jsadminPswrdsObj = new jsadmin_PSWRDS();
        $agentArr1        = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFSD%");
        if (empty($agentArr1)) {
            $agentArr1 = array();
        }
        $agentArr2 = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFID%");
        if (empty($agentArr2)) {
            $agentArr2 = array();
        }
        $tempArr          = array_merge($agentArr1, $agentArr2);
        $newArr['select'] = 'Select';
        foreach ($tempArr as $key => $val) {
            $newArr[$val] = $val;
        }
        $this->franchiseeAgentsArr = $newArr;

        // Fetch month dropdown
        $start               = date("Y-m-d", strtotime("-3 month", time()));
        $end                 = date("Y-m-d", strtotime("-1 month", time()));
        $this->monthDropDown = $commCrmFuncObj->getMonthDropDown($start, $end);

        // Fetch month dropdown Apple
        $start = date("Y-m-d", strtotime("-1 month", time()));
        $end   = date("Y-m-d", strtotime("+1 month", time()));

        $this->monthDropDownApple = $commCrmFuncObj->getMonthDropDown($start, $end);
        //$this->daysDropDownApple = $commCrmFuncObj->getDaysInMonthDropDown($start, $end);
        $arr['select'] = 'Select';
        for ($i = 1; $i <= 31; $i++) {
            $arr[$i] = $i;
        }

        $this->daysDropDownApple = $arr;

        if ($request->getParameter('submitFranchisee')) {
            if ($request->getParameter('agentName') == 'select') {
                $this->errorMsgFran = "Please select a valid Agent Name";
            } else if ($request->getParameter('selectedMonth') == 'select') {
                $this->errorMsgFran = "Please select a valid Month";
            } else if ($request->getParameter('franPerc') <= 1 || $request->getParameter('franPerc') > 99) {
                $this->errorMsgFran = "Please select a valid Percentage, should be between 1 - 99";
            }

            if (!isset($this->errorMsgFran)) {

                $selectedMonth = $request->getParameter('selectedMonth');
                $selectedAgent = $request->getParameter('agentName');
                //$daysInSelMonth = cal_days_in_month(CAL_GREGORIAN,date('m', strtotime($selectedMonth)),date('Y', strtotime($selectedMonth)));
                $startDt = date("Y-m-01 00:00:00", strtotime($selectedMonth));
                $endDt   = date("Y-m-31 23:59:59", strtotime($selectedMonth));

                $billingPaymentDetailObj        = new BILLING_PAYMENT_DETAIL();
                $incentiveCrmDailyAllotObj      = new CRM_DAILY_ALLOT();
                $monthlyIncentiveObj            = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();
                $franchiseeCommissionPercentage = $request->getParameter('franPerc');
                $profilesArr                    = $billingPaymentDetailObj->getProfilesWithinDateRange($startDt, $endDt);
                $franSuccessFlag                = 0;

                if (!empty($profilesArr)) {
                    foreach ($profilesArr as $key => $details) {
                        $allotedAgent = $incentiveCrmDailyAllotObj->getAllotedAgentToTransaction($details['PROFILEID'], $details['ENTRY_DT']);

                        if (!empty($allotedAgent) && $allotedAgent == $selectedAgent) {
                            $franSuccessFlag = 1;
                            $franComm        = ($franchiseeCommissionPercentage / 100) * ($details['AMOUNT'] - ((billingVariables::NET_OFF_TAX_RATE) * $details['AMOUNT']) - $details['APPLE_COMMISSION']);
                            $franComm        = round($franComm, 2);
                            $billingPaymentDetailObj->updateFranchiseeComissions($details['PROFILEID'], $details['BILLID'], $franComm);
                            //$monthlyIncentiveObj->updateFranchiseeComissions($details['PROFILEID'], $details['BILLID'], $franComm);
                        }
                        //print_r(array($details['PROFILEID'], $details['BILLID'], $franComm));
                        //print "\n\n";
                    }
                }

                if ($franSuccessFlag == 1) {
                    $this->successMsgFran = "Successfully updated commissions for agent {$selectedAgent}";
                }
            }
        }

        if ($request->getParameter('submitApple')) {
            if ($request->getParameter('applePerc') <= 1 || $request->getParameter('applePerc') > 99) {
                $this->errorMsgApple = "Please select a valid Percentage, should be between 1 - 99";
            } else if ($request->getParameter('selectedMonth') == 'select') {
                $this->errorMsgFran = "Please select a valid Month";
            } else if ($request->getParameter('selectedDay') == 'select' || $request->getParameter('selectedDay') > date('t', $request->getParameter('selectedMonth'))) {
                $this->errorMsgFran = "Please select a valid Day";
            }

            if (!isset($this->errorMsgApple)) {
                $appleCommissionObj = new billing_APPLE_COMMISSION_PERCENTAGE_LOG();
                $perc               = $request->getParameter('applePerc');
                $newDate            = $request->getParameter('selectedMonth') . "-" . str_pad($request->getParameter('selectedDay'), 2, 0, STR_PAD_LEFT);
                $appleCommissionObj->addNewCommissionPercentage($perc, $newDate);
                $this->successMsgApple = "Successfully updated new Apple Commissions Percentage to {$perc}% for {$newDate} !";
            }
        }

    }

    public function executeDocumentCollection(sfWebRequest $request)
    {
        $this->cid      = $request->getParameter('cid');
        $this->pid      = $request->getParameter('pid');
        $this->username = $request->getParameter('username');
        include_once sfConfig::get("sf_web_dir") . "/profile/InstantSMS.php";
        $agentAllocationDetailsObj = new AgentAllocationDetails();
        $this->name                = $agentAllocationDetailsObj->fetchAgentName($this->cid);
        $executiveDetails          = $agentAllocationDetailsObj->fetchExecutiveDetails($this->name);
        $executiveFullName         = $executiveDetails["FIRST_NAME"] . " " . $executiveDetails["LAST_NAME"];
        if ($request->getParameter('submit')) {
            $docCollect = $request->getParameter("docCollect");
            if ($docCollect == "") {
                $this->check_document = "Y";
                $this->setTemplate("documentCollection");
            } else {
                $mailerMsg = "Dear User,<br><br>You have agreed to submit copies of the following documents to Jeevansathi for the purpose of verification of your profile with username " . $this->username . " on " . date('d-m-Y') . ".<br><br>";
                foreach ($docCollect as $key => $val) {
                    $mailerMsg = $mailerMsg . $val . "<br>";
                }
                $mailerMsg       = $mailerMsg . "<br>On acceptance by the screening team, your profile will be marked verified along with the names of documents provided.<br>Please note that the submitted proofs will not be displayed to any other individual or organization without explicit approval of the profile users.<br><br>Please call us on 1800-419-6299 if you have any queries.<br><br>Regards<br>Team Jeevansathi";
                $jprofileObj     = new JPROFILE("newjs_slave");
                $jprofileDetails = $jprofileObj->get($this->pid, "PROFILEID", "EMAIL");
                $to              = $jprofileDetails["EMAIL"];
                $subject         = "Jeevansathi Document Collection Receipt";
                $from            = "info@jeevansathi.com";
                $from_name       = "Jeevansathi Info";
                SendMail::send_email($to, $mailerMsg, $subject, $from, "", "", "", "", "", "", "1", "", $from_name);
                $smsMsg                  = $executiveFullName . " has collected copies of some document for purpose of verification of your Jeevansathi profile. Please call 18004196299 for any queries.";
                $msgDetails["EXEC_NAME"] = $executiveFullName; //EXEC_NAME case in SMSLib.class
                $smsObj                  = new InstantSMS("DOCUMENT_COLLECTION", $this->pid, $msgDetails);
                $smsObj->send();
                $this->MSG = "An email and sms has been sent to the user.";
                $this->setTemplate("documentCollection");
            }
        } else {
            $this->setTemplate("documentCollection");
        }
    }

    // Manage Comissions interface
    public function executeSplitSalesInterface(sfWebRequest $request)
    {
        $this->cid      = $request->getParameter('cid');
        $this->name     = $request->getParameter('name');
        $commCrmFuncObj = new CommonCrmInterfaceFunctions();
        $this->flag     = 0;
        $start          = date("Y-m-d", strtotime("-1 month", time()));
        //if(date("n", time()) == 3){
        $end = date("Y-m-d", time());
        //} else {
        //    $end = date("Y-m-d", strtotime("-1 day", time()));
        //}
        $this->todaysDt = date("Y-m-d", strtotime("-1 day", time()));
        for ($i = strtotime($start); $i <= strtotime($end); $i = $i + 86400) {
            $temp                = date('Y-m-d', $i);
            $dateDropDown[$temp] = $temp;
        }
        $this->dateDropDown = $dateDropDown;

        $jprofileObj         = new JPROFILE();
        $jsadminPswrdsObj    = new jsadmin_PSWRDS();
        $monthlyIncentiveObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();

        if ($request->getParameter('submitSalesUsername')) {
            $this->username     = $request->getParameter('username');
            $this->selectedDate = $request->getParameter('selectedDate');
            if (!empty($this->username)) {
                $userDetails        = $jprofileObj->get($this->username, "USERNAME", "PROFILEID");
                $this->profileid    = $userDetails['PROFILEID'];
                $detailsArr         = $monthlyIncentiveObj->checkSalesSplitData($this->profileid, $this->selectedDate);
                $this->allotedAgent = $detailsArr[0]['ALLOTED_TO'];
                $this->allAgents    = $jsadminPswrdsObj->getAllExecutives();
                if ($this->allotedAgent) {
                    $this->successMsg = "The user : {$this->username} is alloted to agent : {$this->allotedAgent}";
                    $this->flag       = 1;
                } else {
                    $this->errorMsg = "No Payment Captured for user : {$this->username} for date : {$this->selectedDate}. Please try another user!";
                }
            } else {
                $this->errorMsg = "Please enter a valid Username";
            }
        }
        if ($request->getParameter('submitSalesUpdate')) {
            $this->flag          = 1;
            $this->username      = $request->getParameter('username');
            $this->profileid     = $request->getParameter('profileid');
            $this->selectedDate  = $request->getParameter('selectedDate');
            $this->allotedAgent  = $request->getParameter('allotedAgent');
            $this->selectedAgent = $request->getParameter('selectedAgent');
            $this->alloteeShare  = $request->getParameter('alloteeShare');
            $this->allAgents     = $jsadminPswrdsObj->getAllExecutives();
            $this->selectedShare = 100 - $this->alloteeShare;
            $getAllotteeData     = $monthlyIncentiveObj->selectSalesSplitData($this->profileid, $this->allotedAgent, $this->selectedDate);
            if (is_array($getAllotteeData) && !empty($getAllotteeData)) {
                foreach ($getAllotteeData as $key => $val) {
                    $monthlyIncentiveObj->updateSalesSplitData($val, $this->selectedAgent, $this->selectedShare);
                }
                $this->successMsg = "Updated Sales Split Details for Allottee : {$this->allotedAgent}, Split Agent : {$this->selectedAgent}, Allottee Share : {$this->alloteeShare}, Split Agent Share : {$this->selectedShare} !";
            } else {
                $this->errorMsg = "Cannot update the split sales details since valid record does not exist in system !";
            }
        }
    }

    public function executeUploadVDLookupTable(sfWebRequest $request)
    {
        $this->cid         = $request->getParameter('cid');
        $this->name        = $request->getParameter('name');
        $discountLookupObj = new billing_DISCOUNT_LOOKUP();
        $this->data        = $discountLookupObj->getTableData();
        if ($request->getParameter("SUCCESSFUL")) {
            $this->SUCCESSFUL = 1;
        } else if ($request->getParameter("NODATA")) {
            $this->NODATA = 1;
        } else {
            $this->UPLOAD = 1;
        }

    }

    public function executeUpdateDiscountLookupRecords(sfWebRequest $request)
    {
        $this->cid               = $request->getParameter('cid');
        $this->name              = $request->getParameter('name');
        $testDiscountLookupObj   = new test_DISCOUNT_LOOKUP_UPLOAD('newjs_local111');
        $discountLookupObj       = new billing_DISCOUNT_LOOKUP('newjs_masterDDL');
        $discountLookupBackupObj = new billing_DISCOUNT_LOOKUP_BACKUP('newjs_masterDDL');

        $records = $testDiscountLookupObj->getRecords();
        if ($records) {
            $discountLookupBackupObj->truncate(); //Truncate backup table
            $discountLookupBackupObj->addBackupFromDiscountLookup(); //Add DISCOUNT_LOOKUP data into the DISCOUNT_LOOKUP_BACKUP table

            $discountLookupObj->truncate(); //Truncate DISCOUNT_LOOKUP table

            //Add data into DISCOUNT_LOOKUP table
            foreach ($records as $key => $val) {
                $discountLookupObj->insertData($val);
            }
            $this->forwardTo("crmInterface", "uploadVDLookupTable?SUCCESSFUL=1&cid=" . $this->cid . "&name=" . $this->name);
        } else {
            $this->forwardTo("crmInterface", "uploadVDLookupTable?NODATA=1&cid=" . $this->cid . "&name=" . $this->name);
        }

    }

    public function forwardTo($module, $action)
    {
        $url = "/operations.php/$module/$action";
        $this->redirect($url);
    }

    public function executeFetchMailerData(sfWebRequest $request)
    {
        $this->cid          = $request->getParameter('cid');
        $this->name         = $request->getParameter('name');
        $this->image        = $request->getParameter('image');
        $this->ajaxType     = $request->getParameter('ajaxType');
        $mailerDataObj      = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG('newjs_slave');
        $mailerKeys         = $mailerDataObj->fetchUniqueKeys();
        $this->rangeYear    = date("Y");
        $mailStats          = array();
        $this->mailerKeys   = $mailerKeys;
        $this->mailerParams = array('Mailer Pool' => 'TOTAL_COUNT', 'Successfully Sent' => 'SENT', 'Hard Bounces' => 'HARD_BOUNCES', 'Invalid Email' => 'INVALID_EMAIL', 'Unsubscribe' => 'UNSUBSCRIBE', 'Open Rate' => 'OPEN_RATE');

        $this->mailerKeyReq   = $request->getParameter('mailer_key');
        $this->mailerParamReq = $request->getParameter('mailer_params');
        // Always add total Count
        $this->mailerParamReq[] = 'TOTAL_COUNT';
        if ($request->getParameter('submit')) {
            $formArr = $request->getParameterHolder()->getAll();
            $formArr["date1_dateLists_month_list"]++;
            $formArr["date2_dateLists_month_list"]++;
            $start_date = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
            $end_date   = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
            $start_date = date("Y-m-d", strtotime($start_date));
            $end_date   = date("Y-m-d", strtotime($end_date));
            if ($start_date > $end_date) {
                $this->errorMsg = "Invalid Date Selected";
            } else {
                $this->showMailer = 1;
            }

            $this->startDt = $start_date;
            $this->endDt   = $end_date;

            $mailerData = $mailerDataObj->fetchReqData($this->mailerKeyReq, $this->startDt, $this->endDt);

            foreach ($mailerKeys as $mailName) {
                foreach ($mailerData as $key => $val) {
                    if ($val['MAILER_KEY'] == $mailName && in_array($val['MAILER_KEY'], $this->mailerKeyReq)) {
                        if (strtotime($this->startDt) <= strtotime($val['ENTRY_DT']) && strtotime($this->endDt) >= strtotime($val['ENTRY_DT'])) {
                            foreach ($this->mailerParams as $param) {
                                $mailStats[$mailName][$param] += $val[$param];
                            }
                        }
                    }

                    $dt     = date("Y-m-d", strtotime($val['ENTRY_DT']));
                    $dispDt = date("d M y", strtotime($val['ENTRY_DT']));
                    if ($val['MAILER_KEY'] == $mailName && (!in_array($dt, array_keys($mailPerInsStat[$mailName])) || in_array($dt, array_keys($mailPerInsStat[$mailName]))) && in_array($val['MAILER_KEY'], $this->mailerKeyReq)) {
                        if (strtotime($this->startDt) <= strtotime($val['ENTRY_DT']) && strtotime($this->endDt) >= strtotime($val['ENTRY_DT'])) {
                            foreach ($this->mailerParamReq as $param) {
                                $mailPerInsStat[$mailName][$dispDt][$param] += $val[$param];
                                $dateArr[$dispDt] = "(" . date('D', strtotime($dispDt)) . ")";
                            }
                            foreach ($this->mailerParams as $param) {
                                if ($mailName == 'EOI_MAILER') {
                                    $mailPerInsStatId[$mailName][$dt]["TOTAL"][$param] += $val[$param];
                                } else {
                                    $mailPerInsStatId[$mailName][$dt][$val['ID']][$param] += $val[$param];
                                }

                                $dateArr[$dt] = "(" . date('D', strtotime($dt)) . ")";
                            }
                        }
                    }
                }
            }

            if ($this->ajaxType) {
                print_r(json_encode($output));
                die;
                return sfView::NONE;
            } else {
                $this->mailStats        = $mailStats;
                $this->mailPerInsStat   = $mailPerInsStat;
                $this->mailPerInsStatId = $mailPerInsStatId;
                $this->dateArr          = $dateArr;
            }
        } else if ($request->getParameter('pdfSubmit')) {
            $mailer_key                 = $request->getParameter('mailer_key');
            $mailer_params              = $request->getParameter('mailer_params');
            $date1_dateLists_day_list   = $request->getParameter('date1_dateLists_day_list');
            $date1_dateLists_month_list = $request->getParameter('date1_dateLists_month_list');
            $date1_dateLists_year_list  = $request->getParameter('date1_dateLists_year_list');

            $date2_dateLists_day_list   = $request->getParameter('date2_dateLists_day_list');
            $date2_dateLists_month_list = $request->getParameter('date2_dateLists_month_list');
            $date2_dateLists_year_list  = $request->getParameter('date2_dateLists_year_list');

            $url = sfConfig::get("app_site_url") . "/operations.php/crmInterface/fetchMailerData?";
            foreach ($mailer_key as $k => $v) {
                $url .= "mailer_key%5B%5D=" . $v . "&";
            }
            foreach ($mailer_params as $kk => $vv) {
                $url .= "mailer_params%5B%5D=" . $vv . "&";
            }
            $url .= "date1_dateLists_day_list=$date1_dateLists_day_list&date1_dateLists_month_list=$date1_dateLists_month_list&date1_dateLists_year_list=$date1_dateLists_year_list&date2_dateLists_day_list=$date2_dateLists_day_list&date2_dateLists_month_list=$date2_dateLists_month_list&date2_dateLists_year_list=$date2_dateLists_year_list&submit=Submit&name=$this->name&cid=$this->cid&dialer_check=1&image=1";
            $content = file_get_contents($url);
            header('Content-type: text/HTML');
            header('Content-Disposition: attachment; filename=mailerReport.html');
            echo $content;
            die;
        }
    }
    public function executeSlaveLagStatus(sfWebRequest $request)
    {
        $this->cid  = $request->getParameter('cid');
        $this->name = $request->getParameter('name');
        $field      = 'ENTRY_DT';

        $slaveNamesArr = array('newjs_master', 'newjs_slave', 'newjs_local111','crm_slave','newjs_masterRep');
        foreach ($slaveNamesArr as $key => $name) {
            $jprofileObj = new JPROFILE($name);
            $dataArr     = $jprofileObj->getLatestValue($field);

            if ($name == 'newjs_master') {
                $masterTime = $dataArr[0][$field];
            } else {
                $slaveTime = $dataArr[0][$field];
            }

            $diffTime           = strtotime($masterTime) - strtotime($slaveTime);
            $dateTimeArr[$name] = round($diffTime / 60) . " Min";
            unset($jprofileObj);
        }
        $this->masterTime = $masterTime;
        $this->misSlave   = $dateTimeArr['newjs_slave'];
        $this->slave111   = $dateTimeArr['newjs_local111'];
	$this->crmSlave   = $dateTimeArr['crm_slave'];
	$this->masterRep  = $dateTimeArr['newjs_masterRep'];
    }

    public function executeHelpBackend(sfWebRequest $request)
    {
        $exit                                          = 0;
        $id                                            = $request->getParameter("id");
        $helpQuestionSlaveObj                          = new jsadmin_HELP_QUESTIONS("newjs_slave");
        list($this->allQuestions, $this->editQuestion) = $helpQuestionSlaveObj->getAll($id);
        $this->allCategories                           = array_keys($this->allQuestions);
        $submit                                        = $request->getParameter("submit");
        if ($submit) {
            $question = $request->getParameter('question');
            $answer   = $request->getParameter('editor1');
            $script   = array("script");
            $answer   = CommonUtility::strip_selected_tags(html_entity_decode($answer), $script);
            $category = $request->getParameter('category');
            $status   = $request->getParameter('status');
            if ($category == 'new') {
                $category = $request->getParameter('other');
            }
            if (!($question && $answer && $category && $status)) {
                $this->errorMsg                 = "Some parameter missing";
                $this->editQuestion['QUESTION'] = $question;
                $this->editQuestion['ANSWER']   = $answer;
                $this->editQuestion['CATEGORY'] = $category;
                $this->editQuestion['ACTIVE']   = $status;
                $exit                           = 1;
            }
            if ($exit == 0) {
                $helpQuestionMasterObj = new jsadmin_HELP_QUESTIONS();
                if ($submit == "SUBMIT") {
                    $helpQuestionMasterObj->insert($question, $answer, $category, $status);
                    $this->successMsg = "Question has been added successfully. It will be visible in 10 minutes";
                } else {
                    $helpQuestionMasterObj->update($id, $question, $answer, $category, $status);
                    $this->successMsg = "Question has been modified successfully. It will be visible in 10 minutes";
                }
            }
        } else if ($request->getParameter("resubmit")) {
            $this->editQuestion['QUESTION'] = $request->getParameter('question');
            $this->editQuestion['ANSWER']   = $request->getParameter('editor1');
            $this->editQuestion['CATEGORY'] = $request->getParameter('category');
            $this->editQuestion['ACTIVE']   = $request->getParameter('status');
        }
    }

    public function executeFinanceDataInterface(sfWebRequest $request)
    {
        $this->cid         = $request->getParameter('cid');
        $this->name        = $request->getParameter('name');
        $this->rangeYear   = date("Y", time());
        $this->showInitial = 1;
        if ($request->getParameter("submit")) {
            $formArr = $request->getParameterHolder()->getAll();
            $formArr["date1_dateLists_month_list"]++;
            $formArr["date2_dateLists_month_list"]++;
            $start_date        = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
            $end_date          = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
            $start_date        = date("Y-m-d", strtotime($start_date));
            $end_date          = date("Y-m-d", strtotime($end_date));
            $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
            if ($start_date > $end_date) {
                $this->errorMsg = "Invalid Date Selected";
            }
            if (!$this->errorMsg) //If no error message then submit the page
            {
                $this->range_format = $formArr["range_format"];
                $this->start_date   = $start_date . " 00:00:00";
                $this->end_date     = $end_date . " 23:59:59";
                $this->showInitial  = 0;
                $this->showData     = 1;
                $purchaseObj        = new BILLING_PURCHASES('newjs_slave');
                $billServObj        = new billing_SERVICES('newjs_slave');
                $this->device       = $formArr["device"];
                $this->rawData      = $purchaseObj->fetchFinanceData($this->start_date, $this->end_date, $this->device);
                //Start:JSC-2667: Commented as change in legacy data not required 
                //$this->rawData      = $this->filterData($this->rawData);
                //End:JSC-2667: Commented as change in legacy data not required 
                $this->serviceData  = $billServObj->getFinanceDataServiceNames();
                if ($formArr["report_format"] == "XLS") {
                    $headerString = "Entry Date\tBillid\tReceiptid\tProfileid\tUsername\tServiceid\tService Name\tStart Date\tEnd Date\tCurrency\tList Price\tAmount\tDeferrable Flag\tASSD(Actual Service Start Date)\tASED(Actual Service End Date)\tInvoice No\r\n";
                    if ($this->rawData && is_array($this->rawData)) {
                        foreach ($this->rawData as $k => $v) {
                            $dataString = $dataString . $v["ENTRY_DT"] . "\t";
                            $dataString = $dataString . $v["BILLID"] . "\t";
                            $dataString = $dataString . $v["RECEIPTID"] . "\t";
                            $dataString = $dataString . $v["PROFILEID"] . "\t";
                            $dataString = $dataString . $v["USERNAME"] . "\t";
                            $dataString = $dataString . $v["SERVICEID"] . "\t";
                            $dataString = $dataString . $this->serviceData[$v["SERVICEID"]] . "\t";
                            $dataString = $dataString . $v["START_DATE"] . "\t";
                            $dataString = $dataString . $v["END_DATE"] . "\t";
                            $dataString = $dataString . $v["CUR_TYPE"] . "\t";
                            $dataString = $dataString . $v["PRICE"] . "\t";
                            $dataString = $dataString . $v["AMOUNT"] . "\t";
                            $dataString = $dataString . $v["DEFERRABLE"] . "\t";
                            $dataString = $dataString . $v["ASSD"] . "\t";
                            $dataString = $dataString . $v["ASED"] . "\t";
                            $dataString = $dataString . $v["INVOICE_NO"] . "\r\n";
                        }
                    }
                    $xlData = $headerString . $dataString;
                    $string .= $start_date . "_to_" . $end_date;
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=FinanceData_" . $string . ".xls");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo $xlData;
                    die;
                }
            }
        }
    }

    public function executeBillingManagementInterface(sfWebRequest $request)
    {
        $this->cid                 = $request->getParameter('cid');
        $this->name                = $request->getParameter('name');
        $agentAllocationDetailsObj = new AgentAllocationDetails();
        $priv                      = $agentAllocationDetailsObj->getprivilage($this->cid);
        $priv                      = explode('+', $priv);
        if (in_array('CRMTEC', $priv)) {
            $this->showOptions = 1;
        }
    }

    public function executeServiceDateChangeInterface(sfWebRequest $request)
    {
        $this->cid          = $request->getParameter('cid');
        $this->name         = $request->getParameter('name');
        $billingPurDetObj   = new billing_PURCHASE_DETAIL();
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $this->billid       = $request->getParameter('billid');
        $this->serviceid    = $request->getParameter('serviceid');
        $this->rangeYear    = '2099';
        $this->startYear    = date('Y') - 1;

        if ($request->getParameter('submitBillid')) {
            $this->purDet     = $billingPurDetObj->getAllDetailsForBillidArr(array($this->billid));
            $this->serStatDet = $billingServStatObj->fetchAllServiceDetailsForBillid($this->billid);
            if (!empty($this->purDet) && !empty($this->serStatDet)) {
                $this->detailedView = 1;
            } else {
                $this->error    = 1;
                $this->errorMsg = "Billid invalid or data corrupted, please change manually";
            }
        }

        if ($request->getParameter("submitServiceid")) //If form is submitted
        {
            $formArr = $request->getParameterHolder()->getAll();
            $formArr["date1_dateLists_month_list"]++;
            $formArr["date2_dateLists_month_list"]++;
            $start_date        = $formArr["date1_dateLists_year_list"] . "-" . $formArr["date1_dateLists_month_list"] . "-" . $formArr["date1_dateLists_day_list"];
            $end_date          = $formArr["date2_dateLists_year_list"] . "-" . $formArr["date2_dateLists_month_list"] . "-" . $formArr["date2_dateLists_day_list"];
            $start_date        = date("Y-m-d", strtotime($start_date));
            $end_date          = date("Y-m-d", strtotime($end_date));
            $this->displayDate = date("jS F Y", strtotime($start_date)) . " To " . date("jS F Y", strtotime($end_date));
            if ($start_date > $end_date) {
                $this->error    = 1;
                $this->errorMsg = "Invalid Date Selected";
            } else {

                $billingPurDetObj->updateActivationDates($this->billid, $this->serviceid, $start_date, $end_date);
                $billingServStatObj->updateActivationDates($this->billid, $this->serviceid, $start_date, $end_date);
                $this->profileid = $billingServStatObj->getProfileidForBillid($this->billid);
                $memCacheObject  = JsMemcache::getInstance();
                if ($memCacheObject) {
                    $memCacheObject->remove($this->profileid . '_MEM_NAME');
                    $memCacheObject->remove($this->profileid . "_MEM_OCB_MESSAGE_API17");
                    $memCacheObject->remove($this->profileid . "_MEM_HAMB_MESSAGE");
                    $memCacheObject->remove($this->profileid . "_MEM_SUBSTATUS_ARRAY");
                }
                $this->purDet       = $billingPurDetObj->getAllDetailsForBillidArr(array($this->billid));
                $this->serStatDet   = $billingServStatObj->fetchAllServiceDetailsForBillid($this->billid);

            	// Logging
            	$serviceActivationLog = new billing_SERVICE_ACTIVATION_LOG();
            	foreach($this->serStatDet as $key=>$dataArr){
            	    if(in_array("$this->serviceid", $dataArr))
			$serviceActivationLog->addLog('ACTIVE_DATES',$this->name, $dataArr['PROFILEID'], $dataArr['BILLID'],$this->serviceid,'','',$dataArr['ACTIVATED_ON'], $dataArr['ACTIVATE_ON'],$dataArr['EXPIRY_DT']);	
            	}
                $this->detailedView = 1;
            }
        }
    }

    public function executeServiceActivationChangeInterface(sfWebRequest $request)
    {
        $this->cid          = $request->getParameter('cid');
        $this->name         = $request->getParameter('name');
        $billingPurDetObj   = new billing_PURCHASE_DETAIL();
        $billingServStatObj = new BILLING_SERVICE_STATUS();
        $jprofileObj        = new JPROFILE();
        $this->billid       = $request->getParameter('billid');
        $this->serviceid    = $request->getParameter('serviceid');

        if ($request->getParameter('submitBillid')) {
            $this->profileid   = $billingServStatObj->getProfileidForBillid($this->billid);
            $this->jprofileDet = $jprofileObj->get($this->profileid, 'PROFILEID', 'USERNAME, PROFILEID, SUBSCRIPTION');
            $this->serStatDet  = $billingServStatObj->fetchAllServiceDetailsForBillid($this->billid);
            if (!empty($this->jprofileDet) && !empty($this->serStatDet)) {
                $this->detailedView = 1;
            } else {
                $this->error    = 1;
                $this->errorMsg = "Billid invalid or data corrupted, please change manually";
            }
        }

        if ($request->getParameter("submitServiceid")) //If form is submitted
        {
            $serviceStatus = $request->getParameter('serviceStatus');

            $billingServStatObj->updateActiveStatusForBillidAndServiceid($this->billid, $this->serviceid, $serviceStatus);
            $this->profileid = $billingServStatObj->getProfileidForBillid($this->billid);
            $subscription    = $billingServStatObj->getActiveServeFor($this->profileid);
            if (empty($subscription)) {
                $subscription = '';
            }
            $jprofileObj->edit(array('SUBSCRIPTION' => $subscription), $this->profileid, 'PROFILEID');
            $memCacheObject = JsMemcache::getInstance();
            if ($memCacheObject) {
                $memCacheObject->remove($this->profileid . '_MEM_NAME');
                $memCacheObject->remove($this->profileid . "_MEM_OCB_MESSAGE_API17");
                $memCacheObject->remove($this->profileid . "_MEM_HAMB_MESSAGE");
                $memCacheObject->remove($this->profileid . "_MEM_SUBSTATUS_ARRAY");
            }
            $this->jprofileDet  = $jprofileObj->get($this->profileid, 'PROFILEID', 'USERNAME, PROFILEID, SUBSCRIPTION');
            $this->serStatDet   = $billingServStatObj->fetchAllServiceDetailsForBillid($this->billid);

            // Logging
            $serviceActivationLog = new billing_SERVICE_ACTIVATION_LOG();
	    foreach($this->serStatDet as $key=>$dataArr){
		if(in_array("$this->serviceid", $dataArr))
            		$serviceActivationLog->addLog('ACTIVE_STATUS',$this->name, $dataArr['PROFILEID'], $dataArr['BILLID'],$this->serviceid, $dataArr['ACTIVATED'], $dataArr['ACTIVE']);
	    }	
            $this->detailedView = 1;
        }
    }

    public function executeChangeActiveServicesInterface(sfWebRequest $request)
    {
        $this->cid        = $request->getParameter('cid');
        $this->name       = $request->getParameter('name');
        $billingServObj   = new billing_SERVICES();
        $memHandlerObject = new MembershipHandler();
        // LIMIT SERVICES TO SHOW IN THIS INTERFACE
        $this->servArr = array('P' => 'eRishta', 'C' => 'eValue', 'NCP' => 'eAdvantage', 'X' => 'JS Exclusive', 'T' => 'Response Booster', 'R' => 'Featured Profile', 'A' => 'Astro Compatibility', 'I' => 'We Talk For You');
        if ($request->getParameter('submit')) {
            $params = $request->getParameterHolder()->getAll();
            unset($params['submit'], $params['name'], $params['cid'], $params['module'], $params['action'], $params['authFailure']);
            foreach ($params as $key => $val) {
                if ($val == 'Y') {
                    $activate[] = $key;
                } else {
                    $deactivate[] = $key;
                }
            }
            $activate   = "'" . implode("','", $activate) . "'";
            $deactivate = "'" . implode("','", $deactivate) . "'";
            $billingServObj->changeServiceActivations($activate, 'Y');
            $billingServObj->changeServiceActivations($deactivate, 'N');
            $memHandlerObject->flushMemcacheForMembership();
        }
        $this->servDet = $billingServObj->getServicesForActivationInterface(array_keys($this->servArr));
        $newServDet    = array();
        $skipArr       = array('C1', 'C1W', 'C2W', 'P1', 'P1W', 'P2W', 'NCP1', 'T1', 'A1', 'I10', 'R1', 'X1');
        foreach ($this->servDet as $sid => $arr) {
            $realID = $memHandlerObject->retrieveCorrectMemID($sid);
            if (!in_array($sid, $skipArr)) {
                $newServDet[$realID][$sid] = $arr;
            }
        }
        foreach ($this->servArr as $key => $val) {
            $servDet[$key] = $newServDet[$key];
        }
        $this->servDet = $servDet;
    }

    public function executeCrmSmsFunctionalityInterface(sfWebRequest $request)
    {
        $this->cid       = $request->getParameter('cid');
        $this->name      = $request->getParameter('name');
        $this->profileid = $request->getParameter('profileid');
        $submit          = $request->getParameter('submit');
        $this->smsType    = $request->getParameter('smsType');
        if (!$this->profileid) {
            $this->showError = 1;
            $this->errorMsg  = 'No profile selected to which SMS is to be sent ! Please go back and try again !';
        } else {
            $jprofileObj    = new JPROFILE('newjs_slave');
            $profileDetails = $jprofileObj->get($this->profileid, "PROFILEID", "USERNAME");
            $this->username = $profileDetails['USERNAME'];
            if ($submit) {
                $crmSmsLogObj = new incentive_CRM_SMS_LOG();
                $entryDt = date("Y-m-d", time());
                $sentKeys = $crmSmsLogObj->getSmsSentKeysForProfileAndDate($this->profileid, $entryDt);
                $sentCount = count($sentKeys);
                if ($sentCount >= 2) {
                    $this->showError = 1;
                    $this->errorMsg = "You have exceeded the number of SMS's that can be sent to this user today";
                } else {
                    if (in_array("B", $this->smsType) && !in_array("CRM_SMS_BRANCH", $sentKeys)) {
                        $newjsConObj = new NEWJS_CONTACT_US('newjs_slave');
                        $arrResult = array();
                        $newjsConObj->fetch_All_Contact($arrResult);
                        foreach($arrResult as $key=>$val) {
                            if (empty($branchArr[$val['STATE_VAL']][$val['CITY_ID']])) {
                                $branchArr[$val['STATE_VAL']][$val['CITY_ID']] = $val['ADDRESS'];
                            }
                        }
                        $payHandlerObj = new PaymentHandler();
                        $cityRes       = $payHandlerObj->getCityRes($this->profileid);
                        $state = ucwords(preg_replace("/[^A-Z]+/", "", $cityRes));
                        if (in_array($state, array_keys($branchArr))) {
                            if (in_array($cityRes, array_keys($branchArr[$state]))) {
                                $branchAddress = $branchArr[$state][$cityRes]; 
                            } else {
                                $branchAddress = array_values($branchArr[$state])[0]; 
                            }
                        } else {
                            $branchAddress = $branchAddress = array_values($branchArr['DE'])[0]; 
                        }
                        $branchAddress = wordwrap($branchAddress, 120);
                        $tokenArr      = array("BRANCH_ADDRESS" => $branchAddress);
                        CommonUtility::sendPlusTrackInstantSMS('CRM_SMS_BRANCH', $this->profileid, $tokenArr);
                        $crmSmsLogObj->insertSmsLog($this->profileid,'CRM_SMS_BRANCH',$entryDt);
                    } else if (in_array("B", $this->smsType) && in_array("CRM_SMS_BRANCH", $sentKeys)) {
                        $this->showError = 1;
                        $this->errorMsg = "You've already sent this type of SMS to profile for today";
                    }
                    if (in_array("O", $this->smsType) && !in_array("CRM_SMS_OFFER", $sentKeys)) {
                        $memHandlerObj = new MembershipHandler();
                        $discText = $memHandlerObj->getCrmSmsDiscountText($this->profileid);
                        if ($discText) {
                            $tokenArr = array("DISCOUNT_TEXT" => $discText);
                            CommonUtility::sendPlusTrackInstantSMS('CRM_SMS_OFFER', $this->profileid, $tokenArr);
                            $crmSmsLogObj->insertSmsLog($this->profileid,'CRM_SMS_OFFER',$entryDt);
                        } else {
                            $this->showError = 1;
                            $this->errorMsg = "No offer currently applicable for Profile";    
                        }
                    } else if (in_array("O", $this->smsType) && in_array("CRM_SMS_OFFER", $sentKeys)) {
                        $this->showError = 1;
                        $this->errorMsg = "You've already sent this type of SMS to profile for today";
                    }
                    if (in_array("N", $this->smsType) && !in_array("CRM_SMS_NOT_REACH", $sentKeys)) {
                        $agentAllocDetailsObj = new AgentAllocationDetails();
                        $agentName            = $agentAllocDetailsObj->fetchAgentName($this->cid);
                        if ($agentName) {
                            $tokenArr             = array("CRM_AGENT" => $agentName);
                            CommonUtility::sendPlusTrackInstantSMS('CRM_SMS_NOT_REACH', $this->profileid, $tokenArr);
                            $crmSmsLogObj->insertSmsLog($this->profileid,'CRM_SMS_NOT_REACH',$entryDt);
                        }
                    } else if (in_array("N", $this->smsType) && in_array("CRM_SMS_NOT_REACH", $sentKeys)) {
                        $this->showError = 1;
                        $this->errorMsg = "You've already sent this type of SMS to profile for today";
                    }
                    if (in_array("M", $this->smsType) && !in_array("CRM_SMS_APP_DOWNLOAD", $sentKeys)) {
                        $tokenArr = null;
                        CommonUtility::sendPlusTrackInstantSMS('CRM_SMS_APP_DOWNLOAD', $this->profileid, $tokenArr);
                        $crmSmsLogObj->insertSmsLog($this->profileid,'CRM_SMS_APP_DOWNLOAD',$entryDt);
                    } else if (in_array("M", $this->smsType) && in_array("CRM_SMS_APP_DOWNLOAD", $sentKeys)) {
                        $this->showError = 1;
                        $this->errorMsg = "You've already sent this type of SMS to profile for today";
                    }
                    if (!$this->showError) {
                        $this->successMsg = "SMS Successfully Sent";
                        $this->sent       = 1;
                    }
                }
            }
        }
    }
    public function filterData($profiles) {
        $k=0;
        $index = 0;
        $constantYears = 1;
        $serviceObj2 = new Services;
        //Start: JSC-2667: Fix for legacy data where start and end date is incorrect\
        foreach ($profiles as $key => $value){
         // print_r($profiles[$index]['ASED']);
            if (strstr($profiles[$index]['ASED'], '2099') && strstr($profiles[$index]['SERVICEID'],'L')) {
                //print_r("In first if");
                $invalidArray[$k]['PROFILEID'] = $profiles[$index]['PROFILEID']; /* storing all profiles where end date is invalid. */
                $invalidArray[$k]['ENTRY_DT'] = $profiles[$index]['ENTRY_DT'];   /* for fixing end dates of next record for same profileID  */
                $invalidArray[$k]['SERVICEID'] = $profiles[$index]['SERVICEID'];
                $invalidArray[$k]['ASSD'] = $profiles[$index]['ASSD'];
                $invalidArray[$k]['INDEX'] = $index;
                $invalidArray[$k]['BILLID'] = $profiles[$index]['BILLID'];

                $actual_start_date = $profiles[$index]['ASSD'];
                $actual_end_date = date("Y-m-d", strtotime($actual_start_date) + ($constantYears * (365 * 24 * 60 * 60)));
                $profiles[$index]['ASED'] = $actual_end_date;
                $invalidArray[$k]['ASED']= $profiles[$index]['ASED'];
                $k++;
                
            }
            $index++;
        }
        $k=0;
        $key=0;
        foreach ($profiles as $key => $value) {
            foreach ($invalidArray as $key2 => $value2) {
                if($profiles[$key]['PROFILEID'] == $invalidArray[$key2]['PROFILEID'] 
                        && !strstr($profiles[$key]['SERVICEID'],'L')
                        && (strstr($profiles[$key]['SERVICEID'],'C')
                        || strstr($profiles[$key]['SERVICEID'],'P')
                        || strstr($profiles[$key]['SERVICEID'],'NCP'))) {
                    $profiles[$key]['ASSD'] = $invalidArray[$key2]['ASED'];
                    //get duration
                    $duration = $serviceObj2->getDuration($profiles[$key]['SERVICEID']);
                    
                    $enddt = date("Y-m-d", strtotime($profiles[$key]['ASSD']) + ($duration* 24 * 60 * 60));
                    $profiles[$key]['ASED'] = $enddt;
                    $invalidArray[$key2]['ASED'] = $enddt;
                }
                if(strstr($profiles[$key]['SERVICEID'],'L') && (strstr($profiles[$key]['ASSD'],'2099'))){
                    $profiles[$key]['ASSD'] = $profiles[$key]['START_DATE'];
                    $enddt = date("Y-m-d", strtotime($profiles[$key]['ASSD']) + ($constantYears* 365* 24 * 60 * 60));
                    $profiles[$key]['ASED'] = $enddt;
                    $invalidArray[$key2]['ASED'] = $enddt;
                }
            }
        }
        return $profiles;
    }

}

