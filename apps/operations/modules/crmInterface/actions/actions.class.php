<?php

// crmInterface actions.
// @package    jeevansathi
// @subpackage crmInterface
// @author     Avneet Singh Bindra

class crmInterfaceActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
		$this->forward('default', 'module');
	}

	public function executeCouponInterface(sfWebRequest $request){
		$this->cid = $request->getParameter('cid');
		$this->name = $request->getParameter('name');
		$commCrmFuncObj = new CommonCrmInterfaceFunctions();
		$this->dropdownData = $commCrmFuncObj->getNext15DaysForDropDown();
		list($this->serviceArray,$this->serviceDurations,$this->serviceNames) = $commCrmFuncObj->getCouponApplicableServiceArray();
		//Dynamic Width of the table, will adjust if services durations are added or removed
		$this->durPerc = floor(100/(count($this->serviceDurations)+1));
		if($request->getParameter('submit')){
			$this->usageLimit = $request->getParameter('usageLimit');
			$this->expiryDate = $request->getParameter('expiryDate');
			$this->serviceDiscArr = $request->getParameter('serviceDisc');
			$memHandlerObj = new MembershipHandler();
			$couponOfferObj = new billing_COUPON_OFFER();
			$couponOfferLookupObj = new billing_COUPON_DISCOUNT_LOOKUP();
			$couponSuccess = false;
			while(!$couponSuccess){
				$start_dt = date("Y-m-d H:i:s");
				$this->generatedCode = $memHandlerObj->generateCouponCode();
				$couponID = $couponOfferObj->getCouponID($this->generatedCode);
				if(empty($couponID)){
					$couponSuccess = true;
				}
			}
			if($couponSuccess){
				$couponOfferObj->insertNewCoupon($this->generatedCode,$start_dt,$this->expiryDate,$this->usageLimit);
			}
			foreach($this->serviceDiscArr as $key=>$val){
				if($val != 0){
					$couponInsertID = $couponOfferObj->getCouponID($this->generatedCode);
					$couponOfferLookupObj->insertDiscount($couponInsertID,$key,$val);
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
		public function executeManageVdOffer(sfWebRequest $request){
				$this->cid      =$request->getParameter('cid');
				$this->name     =$request->getParameter('name');
                $agentAllocationDetailsObj = new AgentAllocationDetails();
                $priv = $agentAllocationDetailsObj->getprivilage($this->cid);
                $priv = explode('+', $priv);
                if(in_array('MG', $priv) || in_array('SLHDO', $priv))
                {
                    $this->MGorSLHDO = 1;
                }
                if(in_array('CRMTEC', $priv) || in_array('DA', $priv))
                {
                    $this->CRMTECorDA = 1;
                }
		}
	// Extend VD Offer date
		public function executeExtendVdOffer(sfWebRequest $request){
				$this->cid 	=$request->getParameter('cid');
		$this->name 	=$request->getParameter('name');
		$curDate        =date("Y-m-d");
		$commCrmFuncObj	=new CommonCrmInterfaceFunctions();

				if($request->getParameter('submit')){
			$startDate      =$request->getParameter('startDate');
			$expDate  	=$request->getParameter('expiryDate');
			if($expDate){
				$commCrmFuncObj->extendVdOfferDate($expDate,$startDate);
				$this->vdExtend =1;
				$this->extendedDate =date("d M Y",strtotime($expDate));
				$this->startDate =date("d M Y",strtotime($startDate));	
			}					
		}
		$this->vdStartDateDrowdown	=$commCrmFuncObj->getVdStartDates();			
		$vdExpiryDate           	=$commCrmFuncObj->getVdExpiryDate();
		$this->vdExpiryDate             =date("d M Y",strtotime($vdExpiryDate));
		$this->expiryDateDropdown 	=$commCrmFuncObj->getDateDropDown($curDate,15);;
	}
		// Start VD Offer
		public function executeStartVdOffer(sfWebRequest $request){
				$this->cid      =$request->getParameter('cid');
				$commCrmFuncObj =new CommonCrmInterfaceFunctions();
		$curDate	=date("Y-m-d");

				if($request->getParameter('submit')=='Start'){
						$startDate      =$request->getParameter('vdStartDate');
			$endDate        =$request->getParameter('vdEndDate');

						if($startDate && $endDate && (strtotime($endDate) >= strtotime($startDate))){
								$commCrmFuncObj->startVdOffer($startDate,$endDate);
				$this->startDate 	=date("d M Y",strtotime($startDate));
				$this->endDate 		=date("d M Y",strtotime($endDate));
				$this->vdSuccess 	=true;
				$this->disableStart	=true;		
			}
			else
				$this->vdError =true;
				}else{
			$vdExpiryDate            =$commCrmFuncObj->getVdExpiryDate();
			if(strtotime($vdExpiryDate)>=strtotime($curDate)){
				$this->vdActive 	=true;
				$this->vdExpiryDate     =date("d M Y",strtotime($vdExpiryDate));	
			}
		}			
				$this->vdDateDropdown    =$commCrmFuncObj->getDateDropDown($curDate,15);
		}

		// Schedule VD Sms
		public function executeScheduleVdSms(sfWebRequest $request){
			$formArr = $request->getParameterHolder()->getAll();
			$this->cid = $formArr['cid'];

			$vdSmsLogObj = new billing_VARIABLE_DISCOUNT_SMS_LOG();
			if($vdSmsLogObj->isStatusY()) {
				$this->errorMsg0 = "SMS is already scheduled.";
			}
			else {
				if($formArr['frequency']) {
					$this->frequency = $formArr['frequency'];
					$this->frequencyArr = range(1, $this->frequency);

					$this->dateArr = array();
					for($i=1; $i<$this->frequency+10; $i++) {
						$this->dateArr[] = date('Y-m-d', strtotime('+'.$i.' days'));
					}
				} 
				if($formArr['isDone']) {

					$dateArr =$formArr['selectedDateArr'];
					for($j=0; $j<count($dateArr); $j++){
						if($dateArr[$j]>$dateArr[$j+1]){
							if($j!=count($dateArr)-1)
								$this->errorMsg = "Oops, please provide correct date values";
						}							
					}
					if(count(array_unique($formArr['selectedDateArr'])) != $formArr['frequency'])
						$this->errorMsg = "Oops, please provide unique date values";
					if(!$this->errorMsg){
						$selectedDateArr = array();
						foreach($formArr['selectedDateArr'] as $k => $dd) {
							
							$selectedDateArr[$k] = date('Y-m-d', strtotime('-1 day', strtotime($dd)));
						}
						$vdSmsLogObj->insertVdSmsSchedule($selectedDateArr, $formArr['frequency']);
						$this->successMsg = "Updated successfully ...";			
					}
				}
			}
			unset($vdSmsLogObj);
		}

		// Manage Cash Discount Offer 
		public function executeManageCashDiscountOffer(sfWebRequest $request){
				$this->cid      =$request->getParameter('cid');
		}

		// Start Cash Discount Offer 
		public function executeStartCashDiscountOffer(sfWebRequest $request){
				$this->cid      	=$request->getParameter('cid');
			$agentAllocDetailsObj   =new AgentAllocationDetails();
			$agentName 		=$agentAllocDetailsObj->fetchAgentName($this->cid);
				$commCrmFuncObj 	=new CommonCrmInterfaceFunctions();
				$curDate        	=date("Y-m-d");

				if($request->getParameter('submit')=='Start'){
						$startDate      =$request->getParameter('cdStartDate');
						$endDate        =$request->getParameter('cdEndDate');

						if($startDate && $endDate && (strtotime($endDate) >= strtotime($startDate))){
								$commCrmFuncObj->startCashDiscountOffer($startDate,$endDate,$agentName);
								$this->startDate        =date("d M Y",strtotime($startDate));
								$this->endDate          =date("d M Y",strtotime($endDate));
								$this->discountSuccess  =true;
						}
						else
								$this->discountError =true;
				}else{
						$expiryDate	=$commCrmFuncObj->getCashDiscountExpiryDate();
						if(strtotime($expiryDate)>=strtotime($curDate)){
								$this->cashDiscountActive       =true;
								$this->expiryDate     		=date("d M Y",strtotime($expiryDate));
						}
				}
				$this->cashDiscountDateDropdown    =$commCrmFuncObj->getDateDropDown($curDate,15);
		}

	// Product-wise Cash Discount Offer 
	public function executeProductWiseCashDiscount(sfWebRequest $request){
		$this->cid = $request->getParameter('cid');
		$this->name = $request->getParameter('name');
		$commCrmFuncObj = new CommonCrmInterfaceFunctions();
		list($this->serviceArray,$this->serviceDurations,$this->serviceNames,$this->activeServices) = $commCrmFuncObj->getActiveMainMembershipDetailsArr();
		$this->durPerc = floor(100/(count($this->serviceDurations)+1));
		// Check if Discount Offer is active
		$discountOfferLogObj=new billing_DISCOUNT_OFFER_LOG();
		$billDiscOffrObj = new billing_DISCOUNT_OFFER();
		$discountOfferID = $discountOfferLogObj->checkDiscountOffer();
		if($discountOfferID){
			$this->successMsg = "Discount offer is Currently Active";
			foreach($this->activeServices as $key=>&$val){
				if($disc = $billDiscOffrObj->getDiscountOffer($key)){
					$val = $disc;
				}
			}
		} else {
			$this->errorMsg = "Discount offer is Currently Inactive";
		}
		if($request->getParameter('submit')){
			$this->serviceDiscArr = $request->getParameter('serviceDisc');
			$discountOfferID = $discountOfferLogObj->checkDiscountOffer();
			if(!$discountOfferID){
				// Truncate table before inserting new entries
				$billDiscOffrObj->truncateTable();
			}
			foreach($this->serviceDiscArr as $key=>$val){
				// Check if value already exists
				$existingVal = $billDiscOffrObj->getDiscountOffer($key);
				if(!empty($existingVal) && $val == 0){
					$billDiscOffrObj->removeDiscountValue($key);
				} elseif(!empty($existingVal) && $existingVal != $val){
					$billDiscOffrObj->updateDiscountValue($key,$val);
				} elseif(empty($existingVal) && $val != 0){
					$billDiscOffrObj->insertDiscountValue($key,$val);
				}
			}
			foreach($this->activeServices as $key=>&$val){
				$disc = $billDiscOffrObj->getDiscountOffer($key);
				if($disc)
					$val = $disc;
				else 		
					$val = 0;
			}
                        // Memcache server flush 
                        $memHandlerObject =new MembershipHandler();
                        $memHandlerObject->flushMemcacheForMembership();
			$this->successMsg = "Discount Values Successfully Applied";
		}
	}

	// Manage Festive Offer
	public function executeManageFestiveOffer(sfWebRequest $request){
			$this->cid      =$request->getParameter('cid');
			$this->name     =$request->getParameter('name');
	}

	// Festive Offer Mapping Interface 
	public function executeFestiveOfferMappingInterface(sfWebRequest $request){
		$this->cid = $request->getParameter('cid');
		$this->name = $request->getParameter('name');
		$commCrmFuncObj = new CommonCrmInterfaceFunctions();
		$billFestLogRevObj = new billing_FESTIVE_LOG_REVAMP();
		$this->durPerc = 33; // Set to static value since we have only three column fixed
		$fest = $billFestLogRevObj->getFestiveFlag();
		if($fest && $request->getParameter('submit')){
			$this->errorMsg = "Sorry !! Festive Offer is Currently Active and Values cannot be updated";
		} else if($fest){
			$this->successMsg = "Festive Offer is Currently Active";
		} else {
			$this->errorMsg = "Festive Offer is Currently Inactive";
		}
		$this->offerArr = $commCrmFuncObj->getFestiveOfferMappingDetails();
		if($request->getParameter('submit') && !$fest){
			$this->discDurArr = $request->getParameter('discDur');
			$this->discPercArr = $request->getParameter('discPerc');
			$festLookupObj = new billing_FESTIVE_OFFER_LOOKUP();
			foreach($this->discDurArr as $key => $value){
				$tempID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);	
				$servID = $tempID[0];
				$dur = $tempID[1];
				$updatedSid = $servID.($value+$dur);
				$festLookupObj->updateDurationDiscount($key,$value,$updatedSid);
			}
			foreach($this->discPercArr as $key => $value){
				$festLookupObj->updatePercentageDiscount($key,$value);
			}
			$this->successMsg = "Discount/Duration Values Successfully Applied";
			unset($this->errorMsg);
			// get the updated values from database for display
			$this->offerArr = $commCrmFuncObj->getFestiveOfferMappingDetails();
		}
		// Memcache server flush 
		$memHandlerObject = new MembershipHandler();
		$memHandlerObject->flushMemcacheForMembership();
	}

	// Manage Comissions interface
	public function executeManageCommissions(sfWebRequest $request){
			$this->cid      =$request->getParameter('cid');
			$this->name     =$request->getParameter('name');
			$commCrmFuncObj = new CommonCrmInterfaceFunctions();
			
			// Fetch currently active Apple Comission Percentage
			$billingAppleCommPercLogObj = new billing_APPLE_COMMISSION_PERCENTAGE_LOG();
			$this->activeAppleCommission = $billingAppleCommPercLogObj->getActiveAppleCommissionPercentage(date('Y-m-d H:i:s'));

			// Fetch all Agents which Franchisee Priviledge
			$jsadminPswrdsObj = new jsadmin_PSWRDS();
			$agentArr1 = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFSD%");
			if(empty($agentArr1)){
				$agentArr1 = array();
			}
			$agentArr2 = $jsadminPswrdsObj->fetchAgentsWithPriviliges("%ExcFID%");
			if(empty($agentArr2)){
				$agentArr2 = array();
			}
			$tempArr = array_merge($agentArr1, $agentArr2);
			$newArr['select'] = 'Select';
			foreach($tempArr as $key=>$val){
				$newArr[$val] = $val;
			}
			$this->franchiseeAgentsArr = $newArr;			

			// Fetch month dropdown
			$start = date("Y-m-d", strtotime("-3 month", time()));
			$end = date("Y-m-d", strtotime("-1 month", time()));
			$this->monthDropDown = $commCrmFuncObj->getMonthDropDown($start, $end);

			// Fetch month dropdown Apple
			$start = date("Y-m-d", strtotime("-1 month", time()));
			$end = date("Y-m-d", strtotime("+1 month", time()));

			$this->monthDropDownApple = $commCrmFuncObj->getMonthDropDown($start, $end);
			//$this->daysDropDownApple = $commCrmFuncObj->getDaysInMonthDropDown($start, $end);		
			$arr['select'] = 'Select'; 
			for($i=1;$i<=31;$i++){
				$arr[$i] = $i;
			}

			$this->daysDropDownApple = $arr;

			if($request->getParameter('submitFranchisee')){
				if($request->getParameter('agentName') == 'select'){
					$this->errorMsgFran = "Please select a valid Agent Name";
				} else if($request->getParameter('selectedMonth') == 'select'){
					$this->errorMsgFran = "Please select a valid Month";
				} else if($request->getParameter('franPerc') <= 1 || $request->getParameter('franPerc') > 99){
					$this->errorMsgFran = "Please select a valid Percentage, should be between 1 - 99";
				}

				if(!isset($this->errorMsgFran)){
					
					$selectedMonth = $request->getParameter('selectedMonth');
					$selectedAgent = $request->getParameter('agentName');
					//$daysInSelMonth = cal_days_in_month(CAL_GREGORIAN,date('m', strtotime($selectedMonth)),date('Y', strtotime($selectedMonth)));
					$startDt = date("Y-m-01 00:00:00", strtotime($selectedMonth));
					$endDt = date("Y-m-31 23:59:59", strtotime($selectedMonth));

					$billingPaymentDetailObj = new BILLING_PAYMENT_DETAIL();
					$incentiveCrmDailyAllotObj = new CRM_DAILY_ALLOT();
					$monthlyIncentiveObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();
					$franchiseeCommissionPercentage = $request->getParameter('franPerc');
					$profilesArr = $billingPaymentDetailObj->getProfilesWithinDateRange($startDt, $endDt);
					$franSuccessFlag = 0;

					if(!empty($profilesArr)){
						foreach($profilesArr as $key=>$details) {
							$allotedAgent = $incentiveCrmDailyAllotObj->getAllotedAgentToTransaction($details['PROFILEID'], $details['ENTRY_DT']);

							if(!empty($allotedAgent) && $allotedAgent==$selectedAgent){
								$franSuccessFlag = 1;
								$franComm = ($franchiseeCommissionPercentage/100)*($details['AMOUNT'] - ((billingVariables::NET_OFF_TAX_RATE)*$details['AMOUNT']) - $details['APPLE_COMMISSION']);
								$franComm = round($franComm, 2);
								$billingPaymentDetailObj->updateFranchiseeComissions($details['PROFILEID'], $details['BILLID'], $franComm);
								//$monthlyIncentiveObj->updateFranchiseeComissions($details['PROFILEID'], $details['BILLID'], $franComm);
							}
							//print_r(array($details['PROFILEID'], $details['BILLID'], $franComm));
							//print "\n\n";
						}
					}

					if($franSuccessFlag == 1) {
						$this->successMsgFran = "Successfully updated commissions for agent {$selectedAgent}";
					}
				}
			}

			if($request->getParameter('submitApple')){
				if($request->getParameter('applePerc') <= 1 || $request->getParameter('applePerc') > 99){
					$this->errorMsgApple = "Please select a valid Percentage, should be between 1 - 99";
				} else if($request->getParameter('selectedMonth') == 'select'){
					$this->errorMsgFran = "Please select a valid Month";
				} else if($request->getParameter('selectedDay') == 'select' || $request->getParameter('selectedDay') > date('t', $request->getParameter('selectedMonth'))){
					$this->errorMsgFran = "Please select a valid Day";
				} 

				if(!isset($this->errorMsgApple)){
					$appleCommissionObj = new billing_APPLE_COMMISSION_PERCENTAGE_LOG();
					$perc = $request->getParameter('applePerc');
					$newDate = $request->getParameter('selectedMonth')."-".str_pad($request->getParameter('selectedDay'), 2, 0, STR_PAD_LEFT);
					$appleCommissionObj->addNewCommissionPercentage($perc, $newDate);
					$this->successMsgApple = "Successfully updated new Apple Commissions Percentage to {$perc}% for {$newDate} !";
				}
			}

	}
    
    public function executeDocumentCollection(sfWebRequest $request)
    {
        $this->cid = $request->getParameter('cid');
        $this->pid = $request->getParameter('pid');
        $this->username = $request->getParameter('username');
        include_once(sfConfig::get("sf_web_dir")."/profile/InstantSMS.php");
        $agentAllocationDetailsObj = new AgentAllocationDetails();
        $this->name = $agentAllocationDetailsObj->fetchAgentName($this->cid);
        $executiveDetails = $agentAllocationDetailsObj->fetchExecutiveDetails($this->name);
        $executiveFullName = $executiveDetails["FIRST_NAME"]." ".$executiveDetails["LAST_NAME"];
        if($request->getParameter('submit'))
        {
            $docCollect = $request->getParameter("docCollect");
            if($docCollect == "")
            {
                $this->check_document = "Y";
                $this->setTemplate("documentCollection");
            }
            else
            {
                $mailerMsg = "Dear User,<br><br>You have agreed to submit copies of the following documents to Jeevansathi for the purpose of verification of your profile with username ".$this->username." on ".date('d-m-Y').".<br><br>";
                foreach($docCollect as $key=>$val){
                    $mailerMsg = $mailerMsg.$val."<br>";
                }
                $mailerMsg = $mailerMsg."<br>On acceptance by the screening team, your profile will be marked verified along with the names of documents provided.<br>Please note that the submitted proofs will not be displayed to any other individual or organization without explicit approval of the profile users.<br><br>Please call us on 1800-419-6299 if you have any queries.<br><br>Regards<br>Team Jeevansathi";
                $jprofileObj = new JPROFILE("newjs_slave");
                $jprofileDetails =$jprofileObj->get($this->pid,"PROFILEID","EMAIL");
                $to = $jprofileDetails["EMAIL"];
                $subject = "Jeevansathi Document Collection Receipt";
                $from = "info@jeevansathi.com";
                $from_name = "Jeevansathi Info";
                SendMail::send_email($to,$mailerMsg, $subject, $from,"","","","","","","1","",$from_name);
                $smsMsg = $executiveFullName." has collected copies of some document for purpose of verification of your Jeevansathi profile. Please call 18004196299 for any queries.";
                $msgDetails["EXEC_NAME"] = $executiveFullName;                //EXEC_NAME case in SMSLib.class
                $smsObj = new InstantSMS("DOCUMENT_COLLECTION",$this->pid,$msgDetails);
                $smsObj->send();
                $this->MSG = "An email and sms has been sent to the user.";
                $this->setTemplate("documentCollection");
            }
        }
        else
        {
            $this->setTemplate("documentCollection");
        }
    }

    
    // Manage Comissions interface
	public function executeSplitSalesInterface(sfWebRequest $request){
		$this->cid      =$request->getParameter('cid');
		$this->name     =$request->getParameter('name');
		$commCrmFuncObj = new CommonCrmInterfaceFunctions();
		$this->flag = 0;
		$start = date("Y-m-d", strtotime("-1 month", time()));
		//if(date("n", time()) == 3){
			$end = date("Y-m-d", time());
		//} else {
		//	$end = date("Y-m-d", strtotime("-1 day", time()));
		//}
		$this->todaysDt = date("Y-m-d", strtotime("-1 day", time()));
		for ( $i = strtotime($start); $i <= strtotime($end); $i = $i + 86400 ) {
  			$temp = date('Y-m-d', $i);
  			$dateDropDown[$temp] = $temp;
		}
		$this->dateDropDown = $dateDropDown;

		$jprofileObj = new JPROFILE();
		$jsadminPswrdsObj = new jsadmin_PSWRDS();
		$monthlyIncentiveObj = new incentive_MONTHLY_INCENTIVE_ELIGIBILITY();

		if($request->getParameter('submitSalesUsername')){
			$this->username = $request->getParameter('username');
			$this->selectedDate = $request->getParameter('selectedDate');
			if(!empty($this->username)){
				$userDetails = $jprofileObj->get($this->username,"USERNAME","PROFILEID");
				$this->profileid = $userDetails['PROFILEID'];
				$detailsArr = $monthlyIncentiveObj->checkSalesSplitData($this->profileid, $this->selectedDate);
				$this->allotedAgent = $detailsArr[0]['ALLOTED_TO'];
				$this->allAgents = $jsadminPswrdsObj->getAllExecutives();
				if($this->allotedAgent){
					$this->successMsg = "The user : {$this->username} is alloted to agent : {$this->allotedAgent}";
					$this->flag = 1;
				} else {
					$this->errorMsg = "No Payment Captured for user : {$this->username} for date : {$this->selectedDate}. Please try another user!";
				}
			} else {
				$this->errorMsg = "Please enter a valid Username";
			}
		}
		if($request->getParameter('submitSalesUpdate')){
			$this->flag = 1;
			$this->username = $request->getParameter('username');
			$this->profileid = $request->getParameter('profileid');
			$this->selectedDate = $request->getParameter('selectedDate');
			$this->allotedAgent = $request->getParameter('allotedAgent');
			$this->selectedAgent = $request->getParameter('selectedAgent');
			$this->alloteeShare = $request->getParameter('alloteeShare');
			$this->allAgents = $jsadminPswrdsObj->getAllExecutives();
			$this->selectedShare = 100-$this->alloteeShare; 
			$getAllotteeData = $monthlyIncentiveObj->selectSalesSplitData($this->profileid, $this->allotedAgent, $this->selectedDate);
			if(is_array($getAllotteeData) && !empty($getAllotteeData)){
				foreach($getAllotteeData as $key=>$val){
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
        $this->cid      =$request->getParameter('cid');
        $this->name     =$request->getParameter('name');
        $discountLookupObj = new billing_DISCOUNT_LOOKUP();
        $this->data = $discountLookupObj->getTableData();
        if($request->getParameter("SUCCESSFUL"))
  			$this->SUCCESSFUL = 1;
  		else if($request->getParameter("NODATA"))
  			$this->NODATA=1;
  		else
  			$this->UPLOAD = 1;
    }
    
    public function executeUpdateDiscountLookupRecords(sfWebRequest $request)
    {
        $this->cid      =$request->getParameter('cid');
        $this->name     =$request->getParameter('name');
        $testDiscountLookupObj = new test_DISCOUNT_LOOKUP_UPLOAD('newjs_local111');
        $discountLookupObj = new billing_DISCOUNT_LOOKUP();
        $discountLookupBackupObj = new billing_DISCOUNT_LOOKUP_BACKUP();
        
        $records = $testDiscountLookupObj->getRecords();
        if($records)
        {
            $discountLookupBackupObj->truncate(); //Truncate backup table
            $discountLookupBackupObj->addBackupFromDiscountLookup(); //Add DISCOUNT_LOOKUP data into the DISCOUNT_LOOKUP_BACKUP table

            $discountLookupObj->truncate(); //Truncate DISCOUNT_LOOKUP table
            
            //Add data into DISCOUNT_LOOKUP table
            foreach ($records as $key => $val)
            {
                $discountLookupObj->insertData($val);
            }
            $this->forwardTo("crmInterface","uploadVDLookupTable?SUCCESSFUL=1&cid=".$this->cid."&name=".$this->name);
        }
        else
        {
            $this->forwardTo("crmInterface","uploadVDLookupTable?NODATA=1&cid=".$this->cid."&name=".$this->name);
        }
        
    }
    
    public function forwardTo($module,$action)
    {
        $url="/operations.php/$module/$action";
        $this->redirect($url);
    }

    public function executeFetchMailerData(sfWebRequest $request)
    {
        $this->cid = $request->getParameter('cid');
        $this->name = $request->getParameter('name');
        $this->image = $request->getParameter('image');
        $this->ajaxType = $request->getParameter('ajaxType');
        $mailerDataObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $mailerKeys = $mailerDataObj->fetchUniqueKeys();
	    $this->rangeYear = date("Y");
        $mailStats = array();
        $this->mailerKeys = $mailerKeys;
        $this->mailerParams = array('Mailer Pool'=>'TOTAL_COUNT','Successfully Sent'=>'SENT','Hard Bounces'=>'HARD_BOUNCES','Invalid Email'=>'INVALID_EMAIL','Unsubscribe'=>'UNSUBSCRIBE','Open Rate'=>'OPEN_RATE');

        $this->mailerKeyReq = $request->getParameter('mailer_key');
        $this->mailerParamReq = $request->getParameter('mailer_params');
        // Always add total Count
        $this->mailerParamReq[] = 'TOTAL_COUNT';
        if($request->getParameter('submit')){
			$formArr = $request->getParameterHolder()->getAll();
			$formArr["date1_dateLists_month_list"]++;
            $formArr["date2_dateLists_month_list"]++;
            $start_date = $formArr["date1_dateLists_year_list"]."-".$formArr["date1_dateLists_month_list"]."-".$formArr["date1_dateLists_day_list"];
            $end_date = $formArr["date2_dateLists_year_list"]."-".$formArr["date2_dateLists_month_list"]."-".$formArr["date2_dateLists_day_list"];
            $start_date = date("Y-m-d",strtotime($start_date));
            $end_date = date("Y-m-d",strtotime($end_date));
            if($start_date>$end_date){
                $this->errorMsg = "Invalid Date Selected";
            } else {
            	$this->showMailer = 1;
            }

            $this->startDt = $start_date;
            $this->endDt = $end_date;

            $mailerData = $mailerDataObj->fetchReqData($this->mailerKeyReq, $this->startDt, $this->endDt);

	        foreach($mailerKeys as $mailName){
	        	foreach($mailerData as $key=>$val){
	        		if($val['MAILER_KEY'] == $mailName && in_array($val['MAILER_KEY'], $this->mailerKeyReq)){
	        			if(strtotime($this->startDt) <= strtotime($val['ENTRY_DT']) && strtotime($this->endDt) >= strtotime($val['ENTRY_DT'])){
			        		foreach($this->mailerParams as $param){
			        			$mailStats[$mailName][$param] += $val[$param];
			        		}
			        	}
		        	}

		        	$dt = date("Y-m-d", strtotime($val['ENTRY_DT']));
		        	$dispDt = date("d M y", strtotime($val['ENTRY_DT']));
	        		if($val['MAILER_KEY'] == $mailName && (!in_array($dt, array_keys($mailPerInsStat[$mailName])) || in_array($dt, array_keys($mailPerInsStat[$mailName]))) && in_array($val['MAILER_KEY'], $this->mailerKeyReq)){
	        			if(strtotime($this->startDt) <= strtotime($val['ENTRY_DT']) && strtotime($this->endDt) >= strtotime($val['ENTRY_DT'])){
		        			foreach($this->mailerParamReq as $param){
			        			$mailPerInsStat[$mailName][$dispDt][$param] += $val[$param];
                                $dateArr[$dispDt] = "(".date('D', strtotime($dispDt)).")";
			        		}
			        		foreach($this->mailerParams as $param){
                                if($mailName == 'EOI_MAILER')
                                    $mailPerInsStatId[$mailName][$dt]["TOTAL"][$param] += $val[$param];
                                else
                                    $mailPerInsStatId[$mailName][$dt][$val['ID']][$param] += $val[$param];
                                $dateArr[$dt] = "(".date('D', strtotime($dt)).")";
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
		    	$this->mailStats = $mailStats;
		    	$this->mailPerInsStat = $mailPerInsStat;
		    	$this->mailPerInsStatId = $mailPerInsStatId;
                $this->dateArr = $dateArr;
		    }
		}
        else if($request->getParameter('pdfSubmit')){
                $mailer_key = $request->getParameter('mailer_key');
                $mailer_params = $request->getParameter('mailer_params');
                $date1_dateLists_day_list = $request->getParameter('date1_dateLists_day_list');
                $date1_dateLists_month_list = $request->getParameter('date1_dateLists_month_list');
                $date1_dateLists_year_list = $request->getParameter('date1_dateLists_year_list');

                $date2_dateLists_day_list = $request->getParameter('date2_dateLists_day_list');
                $date2_dateLists_month_list = $request->getParameter('date2_dateLists_month_list');
                $date2_dateLists_year_list = $request->getParameter('date2_dateLists_year_list');

                $url = sfConfig::get("app_site_url")."/operations.php/crmInterface/fetchMailerData?";
                foreach($mailer_key as $k => $v){
                    $url.="mailer_key%5B%5D=".$v."&";
                }
                foreach($mailer_params as $kk => $vv){
                    $url.="mailer_params%5B%5D=".$vv."&";
                }
                $url.= "date1_dateLists_day_list=$date1_dateLists_day_list&date1_dateLists_month_list=$date1_dateLists_month_list&date1_dateLists_year_list=$date1_dateLists_year_list&date2_dateLists_day_list=$date2_dateLists_day_list&date2_dateLists_month_list=$date2_dateLists_month_list&date2_dateLists_year_list=$date2_dateLists_year_list&submit=Submit&name=$this->name&cid=$this->cid&dialer_check=1&image=1";
                $content = file_get_contents($url);
                header('Content-type: text/HTML');
                header('Content-Disposition: attachment; filename=mailerReport.html');
                echo $content;
                die;
            }
    }
    public function executeSlaveLagStatus(sfWebRequest $request)
    {
        $this->cid      =$request->getParameter('cid');
        $this->name     =$request->getParameter('name');
	$field		='ENTRY_DT';

	$slaveNamesArr	=array('newjs_master','newjs_slave','newjs_bmsSlave','newjs_local111');		
	foreach($slaveNamesArr as $key=>$name){
	        $jprofileObj 	=new JPROFILE($name);
		$dataArr 	=$jprofileObj->getLatestValue($field);
		if($name=='newjs_master')
			$masterTime	=$dataArr[0][$field];
		$slaveTime	=$dataArr[0][$field];
		$diffTime 	=strtotime($slaveTime)-strtotime($masterTime);	
		//$dateTimeArr[$name] =date('H:i', $diffTime);
		$dateTimeArr[$name]   =$diffTime/60 ." Min";
		unset($jprofileObj);
	}
	$this->misSlave =$dateTimeArr['newjs_slave'];
	$this->bmsSlave	=$dateTimeArr['newjs_bmsSlave'];
	$this->slave111 =$dateTimeArr['newjs_local111'];	
	
    }

}
