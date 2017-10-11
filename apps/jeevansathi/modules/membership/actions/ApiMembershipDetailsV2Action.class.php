<?php

// membershipDetails actions.
// @package    jeevansathi
// @subpackage membership
// @author     Avneet Singh Bindra
// @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $


class ApiMembershipDetailsV2Action extends sfAction
{

	function execute($request){
		$this->initializeAPI($request);
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if($this->generateResponseData($request)){
			if($this->response == "payment_redirect"){
			  	if($this->currency == "RS"){
			  		// PayU Code to handle generation of order details
				  	$this->setPayUParams();
				  	$this->setTemplate("payURedirect");
				  	// End PayU Code 
				} else {
					$this->setTemplate("apiPaymentRedirect");
				}
				// start autologin code //
			  	$jprofileObj = new JPROFILE();
			  	$fields="PROFILEID,PASSWORD,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT,COUNTRY_RES,EMAIL";
			  	$valueArray = array("PROFILEID"=>$this->profileid,"activatedKey"=>1);
			  	$profileDetails=$jprofileObj->getArray($valueArray,'','',$fields,'','','','','','','','');
			  	unset($jprofileObj);
			  	$protectObj = new protect();
				$protectObj->logout();
			  	$protectObj->postLogin($profileDetails[0]);
			  	unset($protectObj);
			  	// end autologin code //
			} else {
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$apiResponseHandlerObj->setResponseBody($this->response);	
			}
		} else {
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		}
		if($this->response != "payment_redirect"){
			$apiResponseHandlerObj->generateResponse();
		}
		if($request->getParameter('INTERNAL')==1 && empty($this->processCallback)){
			return sfView::NONE;
		} else {
			if($this->response != "payment_redirect"){			
				die;
			}
		}
	}

	public function initializeAPI($request){
		// Start :: Params required for pages 1-4 //
		$loginData = $request->getAttribute("loginData");
		if($loginData['PROFILEID']){
			$this->profileid = $loginData['PROFILEID'];
			$this->checksum = $loginData['CHECKSUM'];
		}
		$this->mainMem = $request->getParameter("mainMem");
		$this->mainMemDur = $request->getParameter("mainMemDur");
		$this->selectedVas = $request->getParameter("selectedVas");
		$this->displayPage = $request->getParameter("displayPage");
		$this->getAppData = $request->getParameter("getAppData");
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		//Special params for discount link handling using API
		$fromBackend = $request->getParameter("from_source");
		$this->backendChecksum = $request->getParameter("checksum");
		$this->profilechecksum = $request->getParameter("profilechecksum");
		$this->echecksum = $request->getParameter("echecksum");
		$this->reqid = $request->getParameter("reqid");
		$this->includeMatriProfileVAS = $request->getParameter('includeMatriProfileVAS');
		$this->JSXCheck = $request->getParameter('JSX');
		$this->couponCheck = $request->getParameter('CC');
		$this->couponCode = $request->getParameter('couponID');
		// End :: Params required for pages 1-4 //

		// Start :: Params required for pages 5 //
		$this->orderID = $request->getParameter("orderID");
		// End :: Params required for pages 5 //
		
		// Start :: Params required for tracking //
		$this->trackAppData = $request->getParameter('trackAppData');
		$this->source = $request->getParameter('source');
		$this->tab = $request->getParameter('tab');
		$this->pgNo = $request->getParameter('pgNo');
		$this->device = $request->getParameter('device');
		$this->allMemberships = $request->getParameter('allMemberships');
		$this->mainMembership = $request->getParameter('mainMembership');
		$this->vasImpression = $request->getParameter('vasImpression');
		$this->backDisc = $request->getParameter('backDisc');
		$this->backTot = $request->getParameter('backTot');
		// End :: Params required for tracking //
		
		// Start :: Params required for sitewideMembershipMessage //
		$this->getMembershipMessage = $request->getParameter('getMembershipMessage');
		// End :: Params required for sitewideMembershipMessage //
		
		// Start :: Params required for sitewideMembershipMessage //
		$this->getHamburgerMessage = $request->getParameter('getHamburgerMessage');
		// End :: Params required for sitewideMembershipMessage //

		// Start :: Params required for paymentProcessing //
		$this->processPayment = $request->getParameter('processPayment');
		$this->discountCartPrice = $request->getParameter('totalCartPrice');
		$this->totalCartPrice = $request->getParameter('discountCartPrice');
		// End :: Params required for paymentProcessing //

		// Start :: Params required for addCallback //
		$this->callType = $request->getParameter('execCallbackType');
		$this->callTab = $request->getParameter('tabVal');
		$this->callProfile = $request->getParameter('profileid');
		$this->processCallback = $request->getParameter('processCallback');
		// End :: Params required for addCallback //
		
		// Start :: Params for Coupon Code Validation //
		$this->validateCoupon = $request->getParameter('validateCoupon');
		$this->selID = $request->getParameter('serviceID');
		// End :: Params for Coupon Code Validation //

		// Start :: Internal Param Check
		$this->internalParamCheck = $request->getParameter('INTERNAL');
		// End :: Internal Param Check

		// Set common params and their values
		$this->memHandlerObj = new MembershipHandler();
		$this->userObj=new memUser($this->profileid);
		$purchasesObj = new BILLING_PURCHASES();
		list($this->ipAddress,$this->currency) = $this->memHandlerObj->getUserIPandCurrency();
		$this->userObj->setIpAddress($this->ipAddress);
		$this->userObj->setCurrency($this->currency);
		if(!empty($this->profileid)){
			$this->userObj->setMemStatus();
			$this->userType = $this->userObj->userType;
			$this->memID = @strtoupper($purchasesObj->getCurrentlyActiveService($this->profileid));
			$this->subStatus = $this->memHandlerObj->getSubscriptionStatusArray($this->userObj);
			if(is_array($this->subStatus) && !empty($this->subStatus)){
				$this->countActiveServices = count($this->subStatus);
			} else {
				$this->countActiveServices = 0;
			}
			$this->contactsRemaining=$this->userObj->getRemainingContacts($this->profileid);
		} else {
			$this->memID = "FREE";
		}
		// Top level flag to check if logged in user is applicable for Renewal Discount
		if($this->userObj->userType == 4 || $this->userObj->userType == 6){
			$this->renewCheckFlag = 1;
		}
		$this->setDiscountDetails();
		if($this->memID != "FREE"){
			$this->memID = $this->retrieveCorrectMemID($this->memID);
			$this->activeServiceName = $this->memHandlerObj->getUserServiceName($this->memID);
		}
		list($this->allMainMem, $this->minPriceArr) = $this->memHandlerObj->getMembershipDurationsAndPrices($this->userObj,$this->discountType);
		$this->curActServices = $this->memHandlerObj->getActiveServices();
		//Handle bifurcation for JS Exclusive
		if(!isset($this->JSXCheck) || empty($this->JSXCheck) || $this->JSXCheck != 1){
			if(($key = array_search("X", $this->curActServices)) !== false) {
				unset($this->curActServices[$key]);
			}
			$this->JSXFlag = 0;
		} else {
			$this->JSXFlag = 1;
		}

		// Remove Latest added eAdvantage membership
		if(($key = array_search("NCP", $this->curActServices)) !== false) {
			unset($this->curActServices[$key]);
		}
		
		$this->service_data = $this->getMembershipData();
		$this->vas_data = $this->memHandlerObj->getAllVASData($this->userObj);

		//Handle backend discount link
		if($fromBackend=="discount_link")
		{
			$this->displayPage = 4;
			$this->fromBackend = 1;
		}
	}

	public function generateResponseData($request){
		$output = array();
		if($this->getAppData == 1){
			$output = $this->consolidateAllPageResponses();
		} elseif($this->trackAppData == 1 && !empty($this->device)){
			if($this->device == 'Android_app'){
				$pTab = 23;
			} else {
				$pTab = 33;
			}
			if($this->device == 'discount_link'){
				$this->memHandlerObj->trackMembershipProgress($this->userObj,$this->source,$this->tab,$this->pgNo,$this->device,$this->user_agent,$this->allMemberships,$this->mainMembership,$this->vasImpression,$this->backDisc,$this->backTot,$pTab,$this->trackType,$this->specialActive,$this->discPerc,$this->discountActive);
			} else {
				$this->memHandlerObj->trackMembershipProgress($this->userObj,$this->source,$this->tab,$this->pgNo,$this->device,$this->user_agent,$this->allMemberships,$this->mainMembership,$this->vasImpression,0,0,$pTab,$this->trackType,$this->specialActive,$this->discPerc,$this->discountActive);
			}
			die;
		} elseif($this->processPayment == 1){
			$this->processPaymentAndRedirect($request);
			$output = "payment_redirect";
		} elseif($this->getMembershipMessage == 1) {
			$output = $this->generateSiteWideMembershipMessage();
		} elseif($this->getHamburgerMessage == 1) {
			$output = $this->generateHamburgerMessage();
		} elseif($this->validateCoupon == 1) {
			$outputArr = $this->validateCoupon($this->selID,$this->couponCode);
			if(is_array($outputArr))
				$output = $outputArr['message'];
		} elseif($this->processCallback){
			$output = $this->requestCallBackResponse($request);
		}else {
			if($this->displayPage == 1){
				$output = $this->generatePageOneResponse();
			} elseif($this->displayPage == 2){
				$output = $this->generatePageTwoResponse();
			} elseif($this->displayPage == 4 && !empty($this->mainMem) && !empty($this->mainMemDur) && $this->fromBackend !=1){
				$output = $this->generatePageFourResponse();
			} elseif($this->displayPage == 4 && $this->fromBackend == 1){
				$output = $this->generateBackendDiscountLinkData();
			}elseif($this->displayPage == 3 && !empty($this->mainMem)){
				$output = $this->generatePageThreeResponse();
			} elseif($this->displayPage == 5 && !empty($this->orderID)){
				$output = $this->generatePageFiveResponse();
			} elseif($this->displayPage == 6){
				$output = $this->generatePageSixResponse();
			}
		}

		if(isset($output) && !empty($output)){
			$this->response = $output;
			return true;
		} else {
			return false;
		}
	}


	public function consolidateAllPageResponses(){
		$conDataArr = array();
		$conDataArr['page_1'] = $this->generatePageOneResponse();
		$conDataArr['page_2'] = $this->generatePageTwoResponse();
		$validMemberships = VariableParams::$mainMembershipsArr;
		foreach($this->allMainMem as $key=>$val){
			if(in_array($key, $validMemberships) && in_array($key, array_values($this->curActServices))){
				$this->mainMem = $key;
				$conDataArr['page_3']['currency'] = $this->currency;
				$conDataArr['page_3']['title'] = "Duration";
				$conDataArr['page_3']['proceed_text'] = "Continue";
				$conDataArr['page_3']['durations'][] = $this->generatePageThreeResponse();
				foreach($val as $kk=>$vv){
					$this->mainMemDur = $vv['DURATION'];
					if($kk == "ESPL"){
						$this->mainMemDur = '1188';
					}
					//$conDataArr['page_4'][] = $this->generatePageFourResponse();
				}
			}
		}
		
		return $conDataArr;
	}


	public function generatePageOneResponse(){
		if(isset($this->profileid) && !empty($this->profileid) && $this->profileid != ''){
			$jprofileObj = new JPROFILE();
			$username = $jprofileObj->getUsername($this->profileid);
			$title = "Hi ".$username."!";
			unset($jprofileObj);
		} else {
			$title = "Hi!";
		}

		list($message,$offer_msg) = $this->getMessageFromCode($this->code);
		if($this->userObj->userType == 5 || $this->userObj->userType == 6 || $this->userObj->userType == memUserType::UPGRADE_ELIGIBLE){
			$benefitMsg = VariableParams::$apiPageOnePerMembershipBenefits ;
			$benefitArr = VariableParams::$apiPageOnePerMembershipBenefitsVisibility;
			foreach($benefitArr as $key=>$value){
				if($key == $this->memID){
					foreach($value as $kk=>$vv){
						if($vv == 1){
							$benefits[] = $benefitMsg[$kk];
						}
					}
				}
			}
		} else {
			$benefits = VariableParams::$apiPageOneBenefits;
		}

		$benefits = implode("<br>", $benefits);
		
		if(isset($this->expiry_date) && !empty($this->expiry_date)){
			if((floor((strtotime($this->expiry_date) - time())/(60*60*24))+1) <=1){
				$expDt = date("Y-m-d", strtotime($this->expiry_date))." 23:59:59";
			} else {
				$expDt = date("Y-m-d", strtotime($this->expiry_date))." 23:59:59";
			}
		}
		$validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid);
		if(!$validityCheck){
			$expDt = NULL;
		}

		if($this->subStatus[0]['EXPIRY_DT']){
			$subscriptionExp = date("Y-m-d", strtotime($this->subStatus[0]['EXPIRY_DT']));
		} else {
			$subscriptionExp = NULL;
		}

		if($this->currency == 'RS'){		
		$call_us = array("title"=>"Need<br>Help?",
			"phone_number"=>"1800-419-6299",
			"call_text"=>"Call Us (Toll Free India)",
			"value"=>"18004196299",
			"or_text"=>"OR",
			"request_callback"=>"Request Callback",
			"params"=>"processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=".$this->profileid);
		}
		else{
		$call_us = array("title"=>"Need<br>Help?",
                        "phone_number"=>"+911204393500",
                        "call_text"=>"Call Us (India)",
                        "value"=>"+911204393500",
                        "or_text"=>"OR",
                        "request_callback"=>"Request Callback",
                        "params"=>"processCallback=1&INTERNAL=1&execCallbackType=JS_ALL&tabVal=1&profileid=".$this->profileid);
                }

		$tracking_params = array("source"=>"200",
			"tab"=>"20",
			"pgNo"=>"0",
			"device"=>$this->device,
			"allMemberships"=>NULL,
			"mainMembership"=>NULL,
			"vasImpression"=>NULL);

		if($this->JSXFlag){
			$output = array('title'=>$title,
				'message'=>$message,
				'subscriptionExp'=>$subscriptionExp,
				'currency'=>$this->currency,
				'offer_expiry_date'=> $expDt,
				'offer_expiry_message'=> $offer_msg,
				'benefits_message'=>$benefits,
				'tracking_params'=>$tracking_params,
				'call_us'=>$call_us,
				'browse_plan'=>'Browse Plans');	
		} else {
			$output = array('title'=>$title,
				'message'=>$message,
				'subscriptionExp'=>$subscriptionExp,
				'currency'=>$this->currency,
				'offer_expiry_date'=> $expDt,
				'offer_expiry_message'=> $offer_msg,
				'benefits_message'=>$benefits,
				'tracking_params'=>$tracking_params,
				'browse_plan'=>'Browse Plans');
		}
		
		if(empty($this->getAppData) && empty($this->trackAppData)){
			$this->memHandlerObj->trackMembershipProgress($this->userObj,'300','30','0',$this->device,$this->user_agent);
		}
		return $output;
	}

	public function generatePageTwoResponse(){
		$tracking_params = array("source"=>"201",
			"tab"=>"21",
			"pgNo"=>"1",
			"device"=>$this->device,
			"allMemberships"=>implode(",", $this->curActServices),
			"mainMembership"=>NULL,
			"vasImpression"=>NULL);

		$output = array('membership_plans'=>$this->service_data,
			'currency'=>$this->currency,
			'tracking_params'=>$tracking_params,
			'proceed_text'=>'Select Duration');

		if(empty($this->getAppData) && empty($this->trackAppData)){
			$this->memHandlerObj->trackMembershipProgress($this->userObj,'301','31','1',$this->device,$this->user_agent,implode(",",$this->curActServices));
		}
		return $output;
	}

	public function generatePageThreeResponse($currency=''){
		$response = array();
		foreach ($this->allMainMem[$this->mainMem] as $key => $value) {
			if($value['DURATION'] == 1188 || $key == 'ESPL'){
				$service['duration'] = 'Unlimited';
				$service['duration_id'] = '1188';
			} else {
				$service['duration'] = $value['DURATION'];
				$service['duration_id'] = $value['DURATION'];
			}

			if($this->fest == 1 && $this->mainMem!="X"){
				$service['duration_text'] = $this->festDurBanner[$service['duration_id']];
				if(empty($service['duration_text'])){
					$service['duration_text'] = 'Months';	
				}
			} else {
				if($service['duration_id'] == '1188'){
					$service['duration_text'] = 'Unlimited Months';	
				} else {
					$service['duration_text'] = 'Months';
				}
			}

			if($value['PRICE'] != $value['OFFER_PRICE']){
				$service['price'] = "".$value['OFFER_PRICE'];
				$service['price_top'] = $value['PRICE'];
				$service['price_bottom'] = 'You Save CUR'.($value['PRICE'] - $value['OFFER_PRICE']);
			} else {
				$service['price'] = "".$value['PRICE'];
				$service['price_top'] = NULL;
				$service['price_bottom'] = 'CUR';
			}

			$service['price_bottom'] = str_replace('CUR', $this->currency.' ', $service['price_bottom']);
			if($service['price_top'] != ''){
				$service['price_top'] = $this->currency." ".$service['price_top'];	
			}
			
			$service['contacts'] = $value['CALL'];
			$tempArr[] = $service;
		}
		foreach($tempArr as $key=>$val){
			if($val['duration_id'] == 1188){
				$allMemberships[] = $this->mainMem."L";
				$tempArr[$key]['duration_id'] = "L";
			} else {
				$allMemberships[] = $this->mainMem.$val['duration_id'];
			}
		}
		$allMemberships = implode(",", $allMemberships);

		$tracking_params = array("source"=>"202",
			"tab"=>"22",
			"pgNo"=>"2",
			"device"=>$this->device,
			"allMemberships"=>$allMemberships,
			"mainMembership"=>NULL,
			"vasImpression"=>NULL);

		$output = array(//'title'=>'Duration',
			//'sub-title'=>$this->memHandlerObj->getUserServiceName($this->mainMem),
			'subscription_id'=>$this->mainMem,
			//'currency'=>$this->currency,
			'prices'=>$tempArr,
			'tracking_params'=>$tracking_params);
			//'proceed_text'=>'Continue');

		if(empty($this->getAppData) && empty($this->trackAppData) && $this->internalParamCheck != 1){
			$this->memHandlerObj->trackMembershipProgress($this->userObj,'302','32','2',$this->device,$this->user_agent,$allMemberships);
		}
		return $output;
	}

	public function generatePageFourResponse(){
		$id = $this->mainMem;
		$mainServices['service_name'] = $this->memHandlerObj->getUserServiceName($id);		
		if($this->mainMemDur == "L"){
			$subId = $this->mainMem.'L';
			$mainServices['service_duration'] = 'Unlimited Months';
		} else {
			if($this->fest == 1 && $this->mainMem!="X"){
				$festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
				$addedDur = $festOffrLookup->getDurationDiscountOnService($this->mainMem.$this->mainMemDur);
				unset($festOffrLookup);
				if(!empty($addedDur) && $addedDur > 0){
					$addonMonths = filter_var($this->festDurBanner[$this->mainMemDur], FILTER_SANITIZE_NUMBER_INT);
				}
			}
			$subId = $this->mainMem.$this->mainMemDur;
			if($addonMonths){
				$mainServices['service_duration'] = $this->mainMemDur.$addonMonths.' MONTHS';
			} else {
				$mainServices['service_duration'] = $this->mainMemDur.' MONTHS';
			}
		}
		$mainServices['service_contacts'] = $this->allMainMem[$id][$subId]['CALL'].' Contacts';
		$mainServices['standard_price'] = $this->allMainMem[$id][$subId]['PRICE'];
		// Checking if coupon code was applied successfully
		if($this->couponCheck == 1 && !empty($this->couponCode)){
			$couponResponse = $this->validateCoupon($this->mainMem.$this->mainMemDur,$this->couponCode);
			if(is_array($couponResponse)){
				$validation = $couponResponse['validationCode'];
			}
		}
		if($this->allMainMem[$id][$subId]['OFFER_PRICE'] != $this->allMainMem[$id][$subId]['PRICE']){
			$mainServices['price'] = "".$this->allMainMem[$id][$subId]['OFFER_PRICE'];
			$mainServices['discount_given'] = $mainServices['standard_price'] - $mainServices['price'];
			// Running discount and additive Coupon Code
			if(is_numeric($validation) && $validation > 0){
				$tempPrice = $mainServices['price'];
				$mainServices['price'] = "".($mainServices['price'] - floor($tempPrice*($validation/100)));
				$mainServices['discount_given'] += floor($tempPrice*($validation/100));
			}
		// Standard Price and Coupon Code
		} elseif(is_numeric($validation) && $validation > 0) {
			$tempPrice = $mainServices['standard_price'];
			$mainServices['price'] = "".($tempPrice - floor($tempPrice*($validation/100)));
			$mainServices['discount_given'] += floor($tempPrice*($validation/100));
		} else {
			$mainServices['standard_price'] = NULL;
			$mainServices['price'] = "".$this->allMainMem[$id][$subId]['PRICE'];
			$mainServices['discount_given'] = NULL;
		}
		// formatting number format
		if($mainServices['standard_price'] != NULL){
			$mainServices['display_standard_price'] = number_format($mainServices['standard_price']);
		} else {
			$mainServices['display_standard_price'] = NULL;
		}
		$mainServices['display_price'] = number_format($mainServices['price']);
		
		$this->custVAS = $this->customizeVASDataForAPI($validation);

		if($mainServices['service_name'] == "eSathi" || $mainServices['service_name'] == "JS Exclusive"){
			$this->custVAS = null;
			if($this->mainMemDur == "L" && $mainServices['service_name'] == "eSathi"){
				$dur = '12';
			} else {
				$dur = $this->mainMemDur;
			}
			if($mainServices['service_name'] == "eSathi"){
				$this->selectedVas = implode($dur.',',VariableParams::$eSathiAddOns).$dur.'0';
			} else {
				$this->selectedVas = NULL;
			}
			$vas_text = NULL;
			$skip_text = "Proceed to Payment";
		} else {
			if($this->specialActive == 1 || $this->discountActive == 1 || $this->renewalActive == 1 || $this->fest == 1){
				if($this->renewCheckFlag) {
					$vas_text = 'Select Value Added Services @ '.$this->renewalPercent.'% OFF';
				} elseif($this->specialActive == 1){
					$vdDisc = $this->memHandlerObj->getSpecialDiscountForAllDurations($this->profileid);
					if($vdDisc[$this->mainMemDur]){
						$vas_text = 'Select Value Added Services @ '.$vdDisc[$this->mainMemDur].'% OFF';
					} else {
						$vas_text = 'Select Value Added Services';		
					}
				} else if($this->discountActive == 1){
					// $offerDisc = $this->memHandlerObj->getDiscountOffer($this->mainMem.$this->mainMemDur);
					// if($offerDisc){
					// 	$vas_text = 'Select Value Added Services @ '.$offerDisc.'% OFF';
					// } else {
						$vas_text = 'Select Value Added Services';
					// }
				} else if($this->fest == 1){
					// Check if Percentage Discount is valid on selected duration
					$festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
					$perc = $festOffrLookup->getPercDiscountOnService($subId);
					unset($festOffrLookup);
					if($this->specialActive == 1 || $this->renewalActive == 1){
						$perc = $this->discPerc;
					}
					if(!empty($perc) && $perc > 0){
						$vas_text = 'Select Value Added Services @ '.$perc.'% OFF';
					} else {
						$vas_text = 'Select Value Added Services';		
					}
				} else {
					if($this->discPerc){
						$vas_text = 'Select Value Added Services @ '.$this->discPerc.'% OFF';
					} else {
						$vas_text = 'Select Value Added Services';		
					}
				}	
			} else {
				$vas_text = 'Select Value Added Services';
			}
			$skip_text = "Pay Now";
		}
		// Code to handle selected VAS from API
		if(isset($this->selectedVas) && !empty($this->selectedVas)){
			$totalVASPrice = 0;
			$vasArr = explode(",",$this->selectedVas);
			foreach($vasArr as $key=>$val){
				$vasID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $val);
				$v['service_name'] = VariableParams::$vasNamesAndDescription[$vasID[0]]['name'];
				if($vasID[0] == "I"){
					$v['service_duration'] = $vasID[1].' PROFILES';
				} else {
					$v['service_duration'] = $vasID[1].' MONTHS';
				}
				if(is_array($this->custVAS)){
				foreach($this->custVAS as $kk=>$vv){
					if($vv['vas_key'] == $vasID[0]){
						foreach($vv['vas_options'] as $x=>$z){
							if($z['id'] == $val){
								$v['price'] = $z['price'];
								$v['discount_given'] = $z['discount_given'];
								$price = @preg_split('/\\D/',$v['price'],-1,PREG_SPLIT_NO_EMPTY);
								$v['price'] = "".$price[0];
							}
						}
					}
					foreach($vv['vas_options'] as $x=>$z){
						//unset($this->custVAS[$kk]['vas_options'][$x]['discount_given']);
						//unset($this->custVAS[$kk]['vas_options'][$x]['standard_price']);
					}
				}
				}
				$totalVASPrice += $v['price'];
				$totalVASDiscount += $v['discount_given'];
				//unset($v['discount_given']);
				$vasServices[] = $v;
			}
		} else {
			$vasServices = NULL;
		}

		$finalCartPrice = $mainServices['price'] + $totalVASPrice;
		$finalCartDiscount = $mainServices['discount_given'] + $totalVASDiscount;
		//unset($mainServices['standard_price']);
		//unset($mainServices['discount_given']);
		if(!empty($mainServices['discount_given'])|| !is_null($mainServices['discount_given']) || $mainServices['discount_given'] != ''){
			$mainServices['discount_given'] .= ""; 
		} else {
			$mainServices['discount_given'] = "0"; 
		}
		$cart_items['main_memberships'] = array($mainServices);
		$cart_items['vas_memberships'] = $vasServices;
		//$price = $this->currency.''.$finalCartPrice;
		$price = $finalCartPrice;
		$discount = $this->currency.''.$finalCartDiscount;

		if($this->mainMemDur == 1188){
			$allMemberships = $this->mainMem."L";
		} else {
			$allMemberships = $this->mainMem.$this->mainMemDur;
		}
		$mainMembership = $allMemberships;
		if(isset($this->selectedVas) && !empty($this->selectedVas)){
			$vasImpression = $this->selectedVas;
		}

		$this->removeExtraParamsFromVAS($this->custVAS);

		if($this->mainMemDur == 1188){
			$sub_dur = 'L';
		} else {
			$sub_dur = $this->mainMemDur;
		}

		if($this->currency == 'RS'){
			$cart_bottom_text = "PRICE INCLUDES ".billingVariables::TAX_RATE."% ". billingVariables::TAX_TEXT;
		} else {
			$cart_bottom_text = NULL;
		}
		
		if($this->couponCheck == 1){
			$apply_coupon_text = "Apply Coupon";
		}
		
		if(empty($this->couponCode)){
			$coupon_message = null;
			$coupon_success = 0;
		}
		else{
			if(is_array($couponResponse)){
				$coupon_message =$couponResponse['message']['message'];
				if($validation==0 || $validation =="INVDUR" || $validation =="LIMEXP"){
					$coupon_success = 0;
				} else {
					$coupon_success =$couponResponse['message']['success_code'];
				}
			}
		}
		/*} elseif($validation == 0){ 
			$coupon_message = 'Coupon code entered is not valid';
			$coupon_success = 0;
		} elseif($validation == "INVDUR"){
			$coupon_message = 'Coupon code is no longer valid';
			$coupon_success = 0;
		} elseif($validation == "LIMEXP"){
			$coupon_message = 'Coupon code has exceeded the maximum usage';
			$coupon_success = 0;
		} elseif($validation > 0){
			$coupon_message = NULL;
			$coupon_success = 1;
		}*/
		if(is_numeric($validation) && $validation > 0){
			$vas_text = "Add/Remove Value Added Services";
			$discount_text = "You save ";
			$apply_coupon_text = "Coupon Applied";
		} else {
			$discount_text = NULL;
		}

		if($this->device == 'mobile_website'){
			$tracking_params = array("source"=>"303",
			"tab"=>"33",
			"pgNo"=>"3",
			"device"=>$this->device,
			"allMemberships"=>$allMemberships,
			"mainMembership"=>$mainMembership,
			"vasImpression"=>$vasImpression);	
		} else {
			$tracking_params = array("source"=>"203",
			"tab"=>"23",
			"pgNo"=>"3",
			"device"=>$this->device,
			"allMemberships"=>$allMemberships,
			"mainMembership"=>$mainMembership,
			"vasImpression"=>$vasImpression);
		}
		
		if($this->couponCheck){
				$output = array('title'=>'Cart',
				'subscription_id'=>$this->mainMem,
				'subscription_duration'=>$sub_dur,
				'currency'=>$this->currency,
				'cart_items'=>$cart_items,
				'cart_bottom_text'=>$cart_bottom_text,
				'vas_text'=>$vas_text,
				'vas_services'=>$this->custVAS,
				'cart_price'=>"".$price,
				'apply_coupon_text'=>$apply_coupon_text,
				'coupon_discount_text'=>$discount_text,
				'coupon_message'=>$coupon_message,
				'coupon_success'=>"".$coupon_success,
				//'cart_discount'=>$discount,
				'tracking_params'=>$tracking_params,
				'proceed_text'=>$skip_text);
		} else {
			$output = array('title'=>'Cart',
				'subscription_id'=>$this->mainMem,
				'subscription_duration'=>$sub_dur,
				'currency'=>$this->currency,
				'cart_items'=>$cart_items,
				'cart_bottom_text'=>$cart_bottom_text,
				'vas_text'=>$vas_text,
				'vas_services'=>$this->custVAS,
				'cart_price'=>"".$price,
				//'cart_discount'=>$discount,
				'tracking_params'=>$tracking_params,
				'proceed_text'=>$skip_text);
		}
		return $output;
	}

	public function generateBackendDiscountLinkData(){
		$profileCheckSum = $this->profilechecksum;
		$profileCheckSumArray = explode("i",$profileCheckSum);
		$profileid = $profileCheckSumArray[1];
		$idCheckSum = $this->reqid;
		$idCheckSumArray = explode("i",$idCheckSum);
		$idBackend = $idCheckSumArray[1];
		if(md5($idBackend) == $idCheckSumArray[0])
		{
			list($allMemberships,$discountBackend,$profileid)=$this->memHandlerObj->handleBackendCase($idBackend,$profileid);
		}
		$this->discountBackend=$discountBackend;	
		$this->fromBackend=1;
		$this->backendId=$idBackend;
		$this->backendCheckSum=$idCheckSum;
		if($allMemberships)
		{
			$memArray=explode(",",$allMemberships);
			for($p=0;$p<count($memArray);$p++)
			{
				if(strpos($memArray[$p],"main")!==false)
				{
					$subMem=substr($memArray[$p],4);
					$tempMem=@preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $subMem);
					$this->mainMem = $tempMem[0];
					// Check to remove L in case of Unlimited Membership
					if(strpos($this->mainMem, "L")){
						$this->mainMemDur = "L";
						$this->mainMem = substr($mainMem, 0, -1);
					} else {
						$this->mainMemDur = $tempMem[1];
					}
				}
			}	
		}
		
		if($this->mainMem=="E"){
			$this->mainMem="ESP";
		}
		$this->memID = $this->mainMem;
		$id = $this->mainMem;
		$mainServices['service_name'] = $this->memHandlerObj->getUserServiceName($id);		
		if($this->mainMemDur == "L"){
			$subId = $this->mainMem.'L';
			$mainServices['service_duration'] = 'Unlimited Months';
		} else {
			$subId = $this->mainMem.$this->mainMemDur;
			$mainServices['service_duration'] = $this->mainMemDur.' MONTHS';
		}
		$mainServices['service_contacts'] = $this->allMainMem[$id][$subId]['CALL'].' Contacts';
		$mainServices['standard_price'] = $this->allMainMem[$id][$subId]['PRICE'];
		$mainServices['discount_given'] = "".floor(($this->allMainMem[$id][$subId]['PRICE'])*($this->discountBackend/100));
		$mainServices['price'] = "".($mainServices['standard_price'] - $mainServices['discount_given']);
		// formatting number format
		if($mainServices['standard_price'] != NULL){
			$mainServices['display_standard_price'] = number_format($mainServices['standard_price']);
		} else {
			$mainServices['display_standard_price'] = NULL;
		}
		$mainServices['display_price'] = number_format($mainServices['price']);

		$this->custVAS = $this->customizeVASDataForAPI();

		if($mainServices['service_name'] == "eSathi"){
			//$this->custVAS = null;
			if($this->mainMemDur == "L"){
				$dur = '12';
			} else {
				$dur = $this->mainMemDur;
			}
			//$this->selectedVas = implode($dur.',',array('T','A','R','I')).$dur.'0';
			$vas_text = NULL;
			$skip_text = "Proceed to Payment";
		} else {
			$vas_text = NULL;
			$skip_text = "Pay Now";
		}
		$this->backendVAS = array_slice($memArray, 1);
		foreach($this->backendVAS as $kk=>$vv){
			if(!empty($vv)){
				$actualVASId = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $vv);	
				$actualVASId = $actualVASId[0];
				$temp[$actualVASId] = $vv;
			} 
		}
		$this->backendVAS = $temp;
		if(!empty($this->backendVAS)){
			if(!empty($this->selectedVas)){
				$this->selectedVas .= ",".implode(',',array_values($this->backendVAS));
			} else {
				$this->selectedVas = implode(',',array_values($this->backendVAS));
			}
		}
		// Code to handle selected VAS from API
		if(isset($this->selectedVas) && !empty($this->selectedVas)){
			$totalVASPrice = 0;
			$vasArr = explode(",",$this->selectedVas);
			foreach($vasArr as $key=>$val){
				$vasID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $val);
				$v['service_name'] = VariableParams::$vasNamesAndDescription[$vasID[0]]['name'];
				if($vasID[0] == "I"){
					$v['service_duration'] = $vasID[1].' PROFILES';
				} else {
					$v['service_duration'] = $vasID[1].' MONTHS';
				}
				foreach($this->custVAS as $kk=>$vv){
					if($vv['vas_key'] == $vasID[0]){
						foreach($vv['vas_options'] as $x=>$z){
							if($z['id'] == $val){
								$v['discount_given'] = "".floor((filter_var($z['price'], FILTER_SANITIZE_NUMBER_INT))*($this->discountBackend/100));
								$v['price'] = filter_var($z['price'], FILTER_SANITIZE_NUMBER_INT) - $v['discount_given'];
								$price = @preg_split('/\\D/',$v['price'],-1,PREG_SPLIT_NO_EMPTY);
								$v['price'] = "".$price[0];
							}
						}
					}
					foreach($vv['vas_options'] as $x=>$z){
						unset($this->custVAS[$kk]['vas_options'][$x]['discount_given']);
						//unset($this->custVAS[$kk]['vas_options'][$x]['standard_price']);
					}
				}
				$totalVASPrice += $v['price'];
				$totalVASDiscount += $v['discount_given'];
				unset($v['discount_given']);
				$vasServices[] = $v;
			}
		} else {
			$vasServices = NULL;
		}

		$finalCartPrice = $mainServices['price'] + $totalVASPrice;
		$finalCartDiscount = $mainServices['discount_given'] + $totalVASDiscount;
		//unset($mainServices['standard_price']);
		unset($mainServices['discount_given']);
		$cart_items['main_memberships'] = array($mainServices);
		$cart_items['vas_memberships'] = $vasServices;
		$price = $this->currency.''.$finalCartPrice;
		$discount = $this->currency.''.$finalCartDiscount;

		if($this->mainMemDur == 1188){
			$allMemberships = $this->mainMem."L";
		} else {
			$allMemberships = $this->mainMem.$this->mainMemDur;
		}
		$mainMembership = $allMemberships;
		if(isset($this->selectedVas) && !empty($this->selectedVas)){
			$vasImpression = $this->selectedVas;
		}

		$this->removeExtraParamsFromVAS($this->custVAS);

		if($this->mainMemDur == 1188){
			$sub_dur = 'L';
		} else {
			$sub_dur = $this->mainMemDur;
		}
		// unset custVAS since this is discount link
		$this->custVAS = null;

		if($this->device == 'mobile_website'){
			$tracking_params = array("source"=>"303",
			"tab"=>"33",
			"pgNo"=>"3",
			"device"=>'discount_link',
			"allMemberships"=>$allMemberships,
			"mainMembership"=>$mainMembership,
			"vasImpression"=>$vasImpression,
			"trackType"=>null);
		} else {
			$tracking_params = array("source"=>"203",
			"tab"=>"23",
			"pgNo"=>"3",
			"device"=>'discount_link',
			"allMemberships"=>$allMemberships,
			"mainMembership"=>$mainMembership,
			"vasImpression"=>$vasImpression,
			"trackType"=>null);
		}

		$output = array('title'=>'Cart',
			'subscription_id'=>$this->mainMem,
			'subscription_duration'=>$sub_dur,
			'currency'=>$this->currency,
			'cart_items'=>$cart_items,
			'cart_bottom_text'=>"PRICE INCLUDES ".billingVariables::TAX_RATE."% ".billingVariables::TAX_TEXT,
			'vas_text'=>$vas_text,
			'vas_services'=>$this->custVAS,
			'cart_price'=>$price,
			'cart_discount'=>$discount,
			'tracking_params'=>$tracking_params,
			'proceed_text'=>$skip_text);

		// if(empty($this->getAppData) && empty($this->trackAppData) && $this->device != 'Android_app'){
		// 	$this->memHandlerObj->trackMembershipProgress($this->userObj,'303','33','3','discount_link',$this->user_agent,$allMemberships,$mainMembership,$vasImpression,$finalCartDiscount,$finalCartPrice,null);
		// }
		return $output;
	}

	public function generatePageFiveResponse(){
		$jprofileObj = new JPROFILE();
		$username = $jprofileObj->getUsername($this->profileid);
		unset($jprofileObj);
		list($orderid,$id) = explode("-",$this->orderID);
		$billOrdObj = new BILLING_ORDERS();
		$orderArr = $billOrdObj->getOrderDetailsForOrderID($id,$orderid);
		unset($billOrdObj);
		$ser_name =$this->memHandlerObj->getServiceName($orderArr[0]["SERVICEMAIN"]);
		list($vas, $main) = $this->memHandlerObj->getMobileDisplayServiceArray($ser_name, $id, $orderid,$this->profileid, $orderArr[0]["ENTRY_DT"],$orderArr[0]["EXPIRY_DT"]);

		if($orderArr[0]['CURTYPE'] == 'DOL'){
			$amount = 'DOL '.$orderArr[0]['AMOUNT']/VariableParams::$DOL_CONV_RATE;
		} else {
			$amount = 'RS '.$orderArr[0]['AMOUNT'];
		}

		foreach($main as $key=>$val){
			$main_serv_name = $val['NAME'];
			$main_serv_dur = $val['DURATION'];
			if($val['EXTRA']){
				$main_serv_dur .= ' +'.$val['EXTRA'];
			}
			$main_serv_dur .= ' Months';
		}
		$vas_arr = array();
		if(is_array($vas) && !empty($vas)){
			foreach($vas as $key=>$val){
				if($val['KEY'] == 'I'){
					$vas_arr[] = $val['NAME'].' - '.$val['DURATION'].' Profiles';
				} else {
					$vas_arr[] = $val['NAME'].' - '.$val['DURATION'].' Months';
				}
			}
		} else {
			$vas_arr = NULL;
		}

		$order_content = array('amount'=>$amount,
			'membership_plan'=>$main_serv_name,
			'currency'=>$this->currency,
			'duration'=>$main_serv_dur,
			'vas_services'=>$vas_arr,
			'orderid'=>$orderid.'-'.$id,
			'transaction_date'=>date("M d, Y",strtotime($orderArr[0]['ENTRY_DT'])));

		$output = array('title'=>'Receipt',
			'message'=>$username.', you are now an '.$main_serv_name.' member',
			'order_content'=>$order_content,
			'proceed_text'=>'Browse Desired Partner Matches');

		return $output;
	}

	public function generatePageSixResponse(){
		$jprofileObj = new JPROFILE();
		$this->username = $jprofileObj->getUsername($this->profileid);
		$title = "Failure";
		$message = $this->username.", there was an error in processing your order. We will connect with you shortly. Meanwhile, you could ..";
		if($this->currency == 'RS'){
		$output = array('title'=>$title,
			'failure_message'=>$message,
			'try_again'=>'Try Again',
			'toll_free'=>array('label'=>'Call 1800-419-6299','value'=>'18004196299','action'=>'CALL'),
			'proceed_text'=>'Skip To Desired Partner Matches');
		}
		else{
		$output = array('title'=>$title,
                        'failure_message'=>$message,
                        'try_again'=>'Try Again',
                        'toll_free'=>array('label'=>'Call +911204393500','value'=>'+911204393500','action'=>'CALL'),
                        'proceed_text'=>'Skip To Desired Partner Matches');
		}
		unset($jprofileObj);
		return $output;
	}

	public function processPaymentAndRedirect($request){
		// this function will set the required params for correct payment redirection
		if(!empty($this->vasImpression)){
			$track_memberships = $this->mainMembership.','.$this->vasImpression;
		} else {
			$track_memberships = $this->mainMembership;
		}
		$servObj = new billing_SERVICES();
		$mems = explode(',', $track_memberships);
		if($this->specialActive == 1){
			$vdDiscount = $this->memHandlerObj->getSpecialDiscountForAllDurations($this->profileid);
			$memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $this->mainMembership);	
			if(strpos($memID[0], "L")){
				$dur = 'L';
			} else {
				$dur=$memID[1];
			}
			if(in_array($dur, array_keys($vdDiscount))){
				$this->discPerc = $vdDiscount[$dur];
			}
		}
		$fromBackend = $request->getParameter('fromBackend');
		if($fromBackend == 1){
			$this->specialActive = 1;
			$checksum = $request->getParameter("checksum");
			$profilechecksum = $request->getParameter("profilechecksum");
			$this->checksum = $profilechecksum;
			$reqid = $request->getParameter("reqid");
			$profileCheckSumArray = explode("i",$profilechecksum);
			$this->profileid = $profileCheckSumArray[1];
			$idCheckSumArray = explode("i",$reqid);
			$idBackend = $idCheckSumArray[1];
			if(md5($idBackend) == $idCheckSumArray[0])
			{
				list($allMemberships,$discountBackend,$this->profileid)=$this->memHandlerObj->handleBackendCase($idBackend,$this->profileid);
			}
			// $this->getRequest()->setParameter('fromBackend',1);
			// $this->getRequest()->setParameter('backendId',$idBackend);
			// $this->getRequest()->setParameter('discountBackend',$discountBackend);
			// $this->getRequest()->setParameter('backendCheckSum',$reqid);
			// $this->getRequest()->setParameter('discSel',$reqid);
			$this->fromBackend = 1;
			$this->backendId = $idBackend;
			$this->discountBackend = $discountBackend;
			$this->backendCheckSum = $reqid;
			$this->discSel = $reqid;
		}
		if($this->currency == 'RS'){
			$price = 0;
			foreach($mems as $key=>$val){
				$servDetails = $servObj->fetchServiceDetailForRupeesTrxn($val); 
				$price += $servDetails['PRICE'];
			}
			if(!empty($this->discPerc) && $this->discPerc !=0){
				$this->discountCartPrice = ceil(($price*($this->discPerc/100)));
			} else {
				$this->discountCartPrice = 0;
			}
			if(isset($discountBackend)){
				$this->discountCartPrice = ceil(($price*($discountBackend/100)));
			}
			$this->totalCartPrice = ceil(($price - $this->discountCartPrice));
		} else {
			$price = 0;
			foreach($mems as $key=>$val){
				$servDetails = $servObj->fetchServiceDetailForDollarTrxn($val); 
				$price += $servDetails['PRICE'];
			}
			if(!empty($this->discPerc) && $this->discPerc !=0){
				$this->discountCartPrice = ceil(($price*($this->discPerc/100)));
			} else {
				$this->discountCartPrice = 0;
			}
			if(isset($discountBackend)){
				$this->discountCartPrice = ceil(($price*($discountBackend/100)));
			}
			$this->totalCartPrice = ceil(($price - $this->discountCartPrice));
		}
		
		$userData=$this->memHandlerObj->getUserData($this->profileid);
		$USERNAME=$userData['USERNAME'];
		$EMAIL=$userData['EMAIL'];
		$PINCODE=$userData['PINCODE'];
		$chksum = explode('_', $this->checksum);
		$this->checksum = $chksum[0];
		// $this->getRequest()->setParameter('mainSubMemId',$this->mainMembership);
		// $this->getRequest()->setParameter('track_memberships',$track_memberships);
		// $this->getRequest()->setParameter('navigationString',$track_memberships);
		// $this->getRequest()->setParameter('service',$this->mainMembership);
		// $this->getRequest()->setParameter('service_main',$this->mainMembership);
		// $this->getRequest()->setParameter('user-type',$this->userObj->userType);
		// $this->getRequest()->setParameter('paymode','card2');
		// $this->getRequest()->setParameter('paymentTab','23');
		// $this->getRequest()->setParameter('profileid',$this->profileid);
		// $this->getRequest()->setParameter('checksum',$this->checksum);
		// $this->getRequest()->setParameter('USERNAME',$USERNAME);
		// $this->getRequest()->setParameter('EMAIL',$EMAIL);
		// $this->getRequest()->setParameter('PINCODE',$PINCODE);
		// $this->getRequest()->setParameter('curtype',$this->currency);
		// $this->getRequest()->setParameter('type',$this->currency);
		// $this->getRequest()->setParameter('track_discount',$this->discountCartPrice);
		// $this->getRequest()->setParameter('track_total',$this->totalCartPrice);
		// $this->getRequest()->setParameter('discountType',$this->discountType);
		// $this->getRequest()->setParameter('specialActive',$this->specialActive);
		// $this->getRequest()->setParameter('discountActive',$this->discountActive);
		// $this->getRequest()->setParameter('fromPaymentTab',1);
		// $this->getRequest()->setParameter('festActive',$this->fest);
		// $this->getRequest()->setParameter('device',$this->device);
		$this->mainSubMemId = $this->mainMembership;
		$this->track_memberships = $track_memberships;
		$this->navigationString = $track_memberships;
		$this->service = $this->mainMembership;
		$this->service_main = $this->mainMembership;
		$this->userType = $this->userObj->userType;
		$this->paymode = 'card2';
		$this->paymentTab = '23';
		$this->profileid = $this->profileid;
		//$this->checksum = $this->checksum;
		$this->USERNAME = $USERNAME;
		$this->EMAIL = $EMAIL;
		$this->PINCODE = $PINCODE;
		$this->curtype = $this->currency;
		$this->type = $this->currency;
		$this->track_discount = $this->discountCartPrice;
		$this->track_total = $this->totalCartPrice;
		$this->discountType = $this->discountType;
		$this->specialActive = $this->specialActive;
		$this->discountActive = $this->discountActive;
		$this->fromPaymentTab = 1;
		$this->festActive = $this->fest;
		$this->device = $this->device;
		$this->couponCodeVal = $this->couponCode;
		$vasImpression = str_replace($this->mainMembership.',','',$this->track_memberships);
		if($fromBackend == 1){
			$this->memHandlerObj->trackMembershipProgress($this->userObj,'203','23','3','backend_link',$this->user_agent,$this->track_memberships,$this->mainMembership,$vasImpression,$this->totalCartPrice,$this->discountCartPrice,"F");
		}
	}

	public function setDiscountDetails(){
		list($discountType,$discountActive,$discount_expiry,$discountPercent,$specialActive,$variable_discount_expiry,$discountSpecial,$fest,$festEndDt,$festDurBanner,$renewalPercent,$renewalActive,$expiry_date,$discPerc,$code) = $this->memHandlerObj->getUserDiscountDetailsArray($this->userObj,1188,2);
		$this->discountType = $discountType;
		$this->discountActive = $discountActive;
		$this->discount_expiry = $discount_expiry;
		$this->discountPercent = $discountPercent;
		$this->specialActive = $specialActive;
		$this->variable_discount_expiry = $variable_discount_expiry;
		$this->discountSpecial = $discountSpecial;
		$this->fest = $fest;
		$this->festEndDt = $festEndDt;
		$this->festDurBanner = $festDurBanner;
		$this->renewalPercent = $renewalPercent;
		$this->renewalActive = $renewalActive;
		$this->expiry_date = $expiry_date;
		$this->discPerc = $discPerc;
		$this->code = $code;
	}

	public function getMembershipData(){
		//$allMemberships=$this->memHandlerObj->getActiveServices();
		$allMemberships = $this->curActServices;
		$serviceName = $this->memHandlerObj->getServiceNames($allMemberships);
		foreach($allMemberships as $key=>$value){
			if($this->currency == 'RS'){
				$starting_price = 'RS '.number_format($this->minPriceArr[$value]['OFFER_PRICE']);
			} else {
				$starting_price = 'DOL '.number_format($this->minPriceArr[$value]['OFFER_PRICE']);
			}
			if($value == "X"){
				if($this->currency == "RS"){
					if($this->minPriceArr["X"]["PRICE_INR"] != $this->minPriceArr["X"]["OFFER_PRICE"]){
						$standardJSPrice = $this->minPriceArr["X"]["PRICE_INR"];
					} else {
						$standardJSPrice = NULL;
					}
				} else {
					if($this->minPriceArr["X"]["PRICE_USD"] != $this->minPriceArr["X"]["OFFER_PRICE"]){
						$standardJSPrice = $this->minPriceArr["X"]["PRICE_USD"];
					} else {
						$standardJSPrice = NULL;
					}
				}
				if($standardJSPrice != NULL){
					$starting_strikeout = "#".$this->currency." ".number_format($standardJSPrice)."#";
				} else {
					$starting_strikeout = NULL;
				}
				$service_data[] = array('subscription_id'=>$value,
				'sub_heading'=>"Finding your soulmate is our only mission!",
				'subscription_name'=>$serviceName[$key],
				'icon_visibility'=>$this->getIconSetValues($value),
				'starting_price'=>$serviceName[$key].' starting from ',
				'starting_price_string'=>"".$starting_price,
				'starting_strikeout'=>$starting_strikeout,
				'request_callback'=>array('label'=>'Request Callback',
					'params'=>'processCallback=1&INTERNAL=1&execCallbackType=JS_EXC&tabVal=1profileid='.$this->profileid));	
			} else {
				$service_data[] = array('subscription_id'=>$value,
					'subscription_name'=>$serviceName[$key],
					'icon_visibility'=>$this->getIconSetValues($value),
					'starting_price'=>$serviceName[$key].' starting from '.$starting_price);
			}
		}
		return $service_data;
	}

	public function customizeVASDataForAPI($validation = 0){ 
		$newData = array();
		$vasDesc = VariableParams::$vasNamesAndDescription;
		$vdDisc = $this->memHandlerObj->getSpecialDiscountForAllDurations($this->profileid);
		$mainDisc = $this->memHandlerObj->getDiscountOffer($this->mainMem.$this->mainMemDur);
		foreach($this->vas_data as $key=>&$value){
			$tempArr = array();
			if(!in_array($key, array_keys($vasDesc))){
				unset($this->vas_data[$key]);
				continue;
			} else {
				$tempArr['vas_name'] = $vasDesc[$key]['name'];
				$tempArr['vas_description'] = $vasDesc[$key]['description'];
				$tempArr['vas_id'] = "".$vasDesc[$key]['vas_id'];
				$tempArr['vas_key'] = $key;
				// Skipping Matri-Profile if flag set
				if($this->includeMatriProfileVAS == 'false' || $this->includeMatriProfileVAS == 0){
					if($tempArr['vas_key'] == 'M'){
						continue;
					}
				}
				$lowest = 9999999;
				//$validVASDurations = array('3','6','9','12','30','60','90','120');
				foreach($value as $kk=>$vv){
					//if(in_array($vv['DURATION'], $validVASDurations)){
						$priceArr = array();
						$priceArr['standard_price'] = "".ceil($vv['PRICE']);
						if($this->specialActive == 1 || $this->discountActive == 1 || $this->renewalActive == 1 || $this->fest == 1){
							// Use case for Variable Discount
							if($this->specialActive == 1){
								$temp = ($vv['PRICE']-($vv['PRICE']*($vdDisc[$this->mainMemDur]/100)));
								$priceArr['price'] = "".ceil($temp);
								$temp = ($priceArr['standard_price'] - $priceArr['price']);
								$priceArr['discount_given'] = "".ceil($temp);
							// Use case for Cash Discount(Generic)
							} else if($this->discountActive == 1){
								if($mainDisc){
									$offerDisc = $this->memHandlerObj->getDiscountOffer($kk);
									$temp = ($vv['PRICE']-($vv['PRICE']*($offerDisc/100)));
									$priceArr['price'] = "".ceil($temp);
									$temp = ($priceArr['standard_price'] - $priceArr['price']);
									$priceArr['discount_given'] = "".ceil($temp);	
								} else {
									$priceArr['standard_price'] = NULL;
									$priceArr['price'] = $vv['PRICE'];
									$priceArr['discount_given'] = NULL;
								}
							// Use case for Festive Discount(Generic)
							} else if($this->fest == 1){
								// Check if Percentage Discount is valid on selected duration
								$festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
								$perc = $festOffrLookup->getPercDiscountOnService($this->mainMem.$this->mainMemDur);
								unset($festOffrLookup);
								if($this->specialActive == 1 || $this->renewalActive == 1){
									$perc = $this->discPerc;
								}
								if(!empty($perc) && $perc > 0){
									$temp = ($vv['PRICE']-($vv['PRICE']*($perc/100)));
									$priceArr['price'] = "".ceil($temp);
									$temp = ($priceArr['standard_price'] - $priceArr['price']);
									$priceArr['discount_given'] = "".ceil($temp);
								} else {
									$priceArr['standard_price'] = NULL;
									$priceArr['price'] = $vv['PRICE'];
									$priceArr['discount_given'] = NULL;
								}
							// Use case for Renewal Discount
							} else {
								$temp = ($vv['PRICE']-($vv['PRICE']*($this->discPerc/100)));
								$priceArr['price'] = "".ceil($temp);
								$temp = ($priceArr['standard_price'] - $priceArr['price']);
								$priceArr['discount_given'] = "".ceil($temp);
							}
						// Standard prices
						} else {
							$priceArr['standard_price'] = NULL;
							$priceArr['price'] = $vv['PRICE'];
							$priceArr['discount_given'] = NULL;
						}
						if(is_numeric($validation) && $validation > 0){
							$priceArr['standard_price'] = "".ceil($vv['PRICE']);
							$tempPrice = $priceArr['price'];
							$priceArr['price'] = $priceArr['price'] - floor($tempPrice*($validation/100));
							$priceArr['discount_given'] += floor($tempPrice*($validation/100));
						}
						if($priceArr['price'] <= $lowest){
							$lowest = $priceArr['price'];
						}
						if($this->currency == 'RS'){
							$priceArr['display_vas_price'] = 'RS '.number_format($priceArr['price']);
							$priceArr['display_price'] = number_format($priceArr['price']);
							$priceArr['price'] = "".$priceArr['price'];
						} else {
							$priceArr['display_vas_price'] = 'DOL '.number_format($priceArr['price']);
							$priceArr['display_price'] = number_format($priceArr['price']);
							$priceArr['price'] = "".$priceArr['price'];
						}
						// Code to format standard price key, and change name to 'display_standard_price'
						if($priceArr['standard_price'] != NULL && ($priceArr['standard_price'] != $priceArr['price'])){
							$priceArr['display_standard_price'] = number_format($priceArr['standard_price']);
						} else {
							$priceArr['display_standard_price'] = NULL;
						}
						unset($priceArr['standard_price']);
						if(!empty($priceArr['discount_given'])|| !is_null($priceArr['discount_given']) || $priceArr['discount_given'] != ''){
							$priceArr['discount_given'] .= "";
						} else {
							$priceArr['discount_given'] = "0";
						}

						$priceArr['duration'] = $vv['DURATION'];
						if($key == "I"){
							$priceArr['text'] = 'PROFILES';
						} else {
							$priceArr['text'] = 'MONTHS';
						}
						$priceArr['id'] = $kk;
						$tempArr['vas_options'][] = $priceArr;
					//}
				}
				if($this->currency == 'RS'){
					$tempArr['starting_price'] = 'Starting @ RS '.number_format($lowest);
				} else {
					$tempArr['starting_price'] = 'Starting @ DOL '.number_format($lowest);
				}
			}
			usort($tempArr['vas_options'], function($a, $b) {
				return $a['duration'] - $b['duration'];
			});
			$newData[] = $tempArr;
		}
		return $newData;
	}

	public function removeExtraParamsFromVAS($vasArr){
		if(is_array($vasArr)){
		foreach($vasArr as $key=>$val){
			foreach($val['vas_options'] as $kk=>$vv){
				//unset($this->custVAS[$key]['vas_options'][$kk]['standard_price']);
				//unset($this->custVAS[$key]['vas_options'][$kk]['discount_given']);
			}
		}
		}
	}

	// This function is re-written for API purposes since we only check for a single service here.
	public function retrieveCorrectMemID($memID){
		if($memID != "FREE"){
			// Check for multiple services, since the service ID is picked from PURCHASES 
			$memID = @explode(",", $memID);
			// by default main membership is always first entry
			$memID = $memID[0];
			// Check to remove months from service ID
			$memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);	
			$memID = $memID[0];
			// Check to remove L in case of Unlimited Membership
			if(strpos($memID, "L")){
				$this->unlimited = true;
				$memID = substr($memID, 0, -1);
			}
			// Code to check if the existing user's membership is one of Main membership (Purchases check)
			if(!in_array($memID, VariableParams::$mainMembershipsArr)){
				$memID = $this->userObj->memStatus;
				$memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);	
				$memID = $memID[0];
				// Check to remove L in case of Unlimited Membership
				if(strpos($memID, "L")){
					$this->unlimited = true;
					$memID = substr($memID, 0, -1);
				}
				// Check for new memID again, this time using SERVICE_STATUS
				if(!in_array($memID, VariableParams::$mainMembershipsArr)){
					$memID = "FREE";
				}
			}
			return $memID;
		} else {
			// Do nothing and return FREE
			return $memID;
		}
	}

	public function getIconSetValues($subscription_id){
		if($subscription_id == "X"){
			$icons = VariableParams::$apiMembershipIconsJSExclusive;
		} else {
			$icons = VariableParams::$apiMembershipIcons;
			$iconValues = VariableParams::$iconsPerMembership;
			foreach($icons as $key=>$val){
				$icons[$key]['visibility'] = $iconValues[$subscription_id][$key];
			}	
		}
		return $icons;
	}

	public function getMessageFromCode($code){
		$serviceName = $this->activeServiceName;
		$days = floor((strtotime($this->subStatus[0]['EXPIRY_DT']) - time())/(60*60*24))+1;
		// Top level Banner display check
		if($this->profileid){
			$validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid);
		} else {
			$validityCheck = 1;
		}
		if($validityCheck){
			switch($code) {
				case 0: $message = "Become a premium member & instantly connect with profiles you are interested in.";
					$offer_msg = NULL;
					break;
				case 8: $message = "Your <strong>".$this->activeServiceName."</strong> membership privileges till ".date("d F Y", strtotime($this->subStatus[0]['EXPIRY_DT']));
					$offer_msg = NULL;
					break;
				case 1:
				case 2: if(time() <= strtotime($this->subStatus[0]['EXPIRY_DT'])){
						$message = "Your ".$this->activeServiceName." membership expires on ".date("d F Y", strtotime($this->subStatus[0]['EXPIRY_DT'])).", renew your plan before it expires and get ".$this->renewalPercent."% discount.";
						$offer_msg = NULL;
					} else {
						$message = "Your ".$this->activeServiceName." membership expired on ".date("d F Y", strtotime($this->expiry_date)-864000).", renew your plan before ".date("d F Y", strtotime($this->expiry_date))." and get ".$this->renewalPercent."% discount.";
						$$offer_msg = NULL;
					}
					break;
				case 3:
				case 5:
					$message = "Now get extra months of subscription/special discounts on all plans - avail the offer before it expires on ".date("d F Y", strtotime($this->expiry_date)).".";
					$offer_msg = NULL;
					break;
				case 4:
				case 6:
					$message = "You are selected for special discounts of up to ".$this->discPerc."% by Jeevansathi.com - avail the offer before it expires on ".date("d F Y", strtotime($this->expiry_date)).".";
					$offer_msg = NULL;
					break;
				case 7: $message = "Now get extra months of subscription/special discounts on all plans - avail the offer before it expires on ".date("d F Y", strtotime($this->expiry_date)).".";
					$offer_msg = NULL;
					break;
				default: $message = NULL;
					$offer_msg = NULL;
					break;
			}
		} else {
			$message = "Your <strong>".$this->activeServiceName."</strong> membership privileges till ".date("d F Y", strtotime($this->subStatus[0]['EXPIRY_DT']));
			$offer_msg = NULL;
		}
		return array($message,$offer_msg);
	}

	public function generateSiteWideMembershipMessage(){
		$serviceName = $this->activeServiceName;
		$ocbBannerObj = new billing_OCB_BANNER_MESSAGE();
		// Top level Banner display check
		if($this->profileid){
			$validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid,$this->userType);
		} else {
			$validityCheck = 1;
		}
		// Check for preset overriding OCB Message in Database
		$todays_dt = date('Y-m-d H:i:s');
		$overrideMsg = $ocbBannerObj->getBannerMessage($todays_dt);
		unset($ocbBannerObj);
		if($validityCheck && is_array($overrideMsg) && !empty($overrideMsg['top']) && !empty($overrideMsg['bottom'])){
			$top = $overrideMsg['top'];
			$bottom = json_decode(json_encode($overrideMsg['bottom']),true);
		} else if($validityCheck && ($this->renewCheckFlag || $this->specialActive == 1 || $this->discountActive == 1 || $this->fest == 1)){
			if($this->renewCheckFlag){
				if($this->fest == 1){
					$top = "Get flat ".$this->renewalPercent."% OFF";
					$bottom = "or extra months if you renew before <strong>".date("d M", strtotime($this->userObj->expiryDate))."</strong> !";
				} else {
					$top = "Get ".$this->renewalPercent."% OFF";
					$bottom = "if you renew your membership before <strong>".date("d M", strtotime($this->userObj->expiryDate))."</strong> !";
				}
			} elseif($this->specialActive == 1){
				// Logic for differentiating between flat and upto Variable Discount
				/*$vdodObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
				$discountVD = $vdodObj->getDiscountDetailsForProfile($this->profileid);*/
                                $vdodObj =new VariableDiscount();
                                $discountVD =$vdodObj->getAllDiscountForProfile($this->profileid);

				$maxVDDisc = max(array_values($discountVD));
				unset($vdodObj);
				if (count(array_unique($discountVD)) == 1 ) {
					$flat = true;
				} else {
					$flat = false;
				}

				$this->discPerc = $maxVDDisc;
				if($this->fest == 1){
					if($flat){
						$top = "Get flat ".$this->discPerc."% OFF";
					} else {
						$top = "Get upto ".$this->discPerc."% OFF";
					}
					$bottom = "or extra months if you upgrade before <strong>".date("d M", strtotime($this->expiry_date))."</strong> !";
				} else {
					if($flat){
						$top = "Get flat ".$this->discPerc."% OFF";
					} else {
						$top = "Get upto ".$this->discPerc."% OFF";
					}
					$bottom = "if you upgrade your membership before <strong>".date("d M", strtotime($this->expiry_date))."</strong> !";
				}

			} elseif($this->discountActive == 1){
				$top = "Get upto ".$this->discPerc."% OFF";
				$bottom = "if you upgrade your membership before <strong>".date("d M", strtotime($this->expiry_date))."</strong> !";
			} elseif($this->fest == 1){
				$top = "Get extra months";
				$bottom = "or attractive discounts if you upgrade before <strong>".date("d M", strtotime($this->festEndDt))."</strong> !";
			}
		}
		if(!empty($top) && !empty($bottom)){
			$output = array('membership_message'=>array('top'=>$top,'bottom'=>$bottom));
		} else {
			$output = array('membership_message'=>NULL);
		}
		return $output;
	}

	public function setPayUParams(){
		include_once(JsConstants::$docRoot."/commonFiles/connect_dd.inc");
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
		// Using legacy function to process payments, generate order and payment details
		$memObj = new Membership;
		$memObj->setProfileid($this->profileid);
		$payment = $memObj->forOnline($this->track_memberships,$this->type,$this->service,$this->discSel,$this->paymode,$this->device,$this->couponCode);
		$total=$payment['total'];
		$service_main=$payment['service_str'];
		$discount=$payment['discount'];
		$discount_type=$payment['discount_type'];
		$ORDER = newOrder($this->profileid,$this->paymode,$this->type,$total,$service_str,$service_main,$discount,$setactivate,'PAYU',$discount_type,$this->device,$this->couponCode);
		$nameOfUserObj = new incentive_NAME_OF_USER();
		$userName = $nameOfUserObj->getName($this->profileid);
		// End processing payment params
		if($this->currency == "RS") {
            if(JsConstants::$whichMachine == 'test') {
                $this->key = gatewayConstants::$PayUTestRsMerchantId;
                $this->salt = gatewayConstants::$PayUTestRsSalt;
                $this->gatewayURL = gatewayConstants::$PayUTestGatewayURL;
            } else {
                $this->key = gatewayConstants::$PayULiveRsMerchantId;
                $this->salt = gatewayConstants::$PayULiveRsSalt;
                $this->gatewayURL = gatewayConstants::$PayULiveGatewayURL;
            }
        } 
        elseif ($this->currency == "DOL") {
            if(JsConstants::$whichMachine == 'test') {
                $this->key = gatewayConstants::$PayUTestDolMerchantId;
                $this->salt = gatewayConstants::$PayUTestDolSalt;
                $this->gatewayURL = gatewayConstants::$PayUTestGatewayURL;
            } else {
                $this->key = gatewayConstants::$PayULiveDolMerchantId;
                $this->salt = gatewayConstants::$PayULiveDolSalt;
                $this->gatewayURL = gatewayConstants::$PayULiveGatewayURL;
            }   
        }

		$this->txnid = $ORDER["ORDERID"]; // Order ID
		$this->amount = (float)floor($ORDER["AMOUNT"]); // Final Calculated Amount for the Order
		$this->productinfo = "UsrID: {$this->profileid}, Mem: {$this->track_memberships}, Amt: {$this->amount}, Discnt: {$discount}, DisntTyp: {$discount_type}";
		$userData=$this->memHandlerObj->getUserData($this->profileid);
		if($username){
			$this->firstname = $username;
		} else {
			$this->firstname = $userData['USERNAME'];
		}
		$this->email = $this->EMAIL;$ORDER["STATE"];
		$this->phone = $userData['PHONE_MOB'];
		$this->lastname = ""; // We don't store this, but can be added later
		$this->address1 = $ORDER["CONTACT"];
		$city_country=explode(",",$ORDER["COUNTRY"]);
        $city_order=$city_country[0];
        $country_order=$city_country[1];
		$this->city = $city_order;
		$this->state= $ORDER["STATE"];
		$this->country = $country_order;
		$this->zipcode = $ORDER["PINCODE"];
		$this->surl = JsConstants::$siteUrl."/profile/pg/payU_return.php";
		$this->furl = JsConstants::$siteUrl."/profile/pg/payU_return.php";
		$this->curl = JsConstants::$siteUrl."/profile/pg/payU_return.php";
		$this->udf1 = $this->checksum;

		// Hashing function used the following pattern
		// hash=sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||SALT)
		$hashText = "{$this->key}|{$this->txnid}|{$this->amount}|{$this->productinfo}|{$this->firstname}|{$this->email}|{$this->udf1}||||||||||{$this->salt}";
		$this->hash = hash("sha512", $hashText);
		$this->drop_category = "EMI,COD"; // This will disable EMI payment for Jeevansathi
		$this->custom_note = "Jeevansathi Matrimony Payments";
	}

	public function requestCallBackResponse($request) {
	  	$request->setParameter("profileid",$this->callProfile);
	  	$request->setParameter("tabVal",$this->callTab);	
	  	$request->setParameter("execCallbackType",$this->callType);
	  	$request->setParameter("INTERNAL",1);
		ob_start();
		$data = sfContext::getInstance()->getController()->getPresentationFor('membership', 'addCallBck');
		$output = ob_get_contents();
		ob_end_clean();
		return array("message"=>$output);
	}

	public function generateHamburgerMessage(){
		$serviceName = $this->activeServiceName;
		if($this->profileid){
			$validityCheck = $this->memHandlerObj->checkIfUserIsPaidAndNotWithinRenew($this->profileid,$this->userType);
		} else {
			$validityCheck = 1;
		}
		$todays_dt = date('Y-m-d H:i:s');
		if($validityCheck && ($this->renewCheckFlag || $this->specialActive == 1 || $this->discountActive == 1 || $this->fest == 1)){
			if($this->renewCheckFlag){
				if($this->fest == 1){
					$top = "Extra Months & upto ".$this->renewalPercent."% Off till ".date("d M", strtotime($this->userObj->expiryDate));
				} else {
					$top = "Flat ".$this->renewalPercent."% Off till ".date("d M", strtotime($this->userObj->expiryDate));
				}
				$bottom = "Renew Membership";

			} elseif($this->specialActive == 1){
				// Logic for differentiating between flat and upto Variable Discount
				/*$vdodObj = new billing_VARIABLE_DISCOUNT_OFFER_DURATION();
				$discountVD = $vdodObj->getDiscountDetailsForProfile($this->profileid);*/
                                $vdodObj =new VariableDiscount();
                                $discountVD =$vdodObj->getAllDiscountForProfile($this->profileid);

				$maxVDDisc = max(array_values($discountVD));
				unset($vdodObj);
				if (count(array_unique($discountVD)) == 1 ) {
					$flat = true;
				} else {
					$flat = false;
				}
				$this->discPerc = $maxVDDisc;
				if($this->fest == 1){
                                        if($flat){
                                                $top = "Extra Months & flat ".$this->discPerc."% Off till ".date("d M", strtotime($this->expiry_date));
                                        } else {
                                                $top = "Extra Months & upto ".$this->discPerc."% Off till ".date("d M", strtotime($this->expiry_date));
                                        }
                                        $bottom = "Upgrade Membership";

				} else {
                                        if($flat){
                                                $top = "Flat ".$this->discPerc."% Off till ".date("d M", strtotime($this->expiry_date));
                                        } else {
                                                $top = "Upto ".$this->discPerc."% Off till ".date("d M", strtotime($this->expiry_date));
                                        }
                                        $bottom = "Upgrade Membership";
				}

			} elseif($this->discountActive == 1){
				$top = "Upto ".$this->discPerc."% Off till ".date("d M", strtotime($this->expiry_date));
				$bottom = "Upgrade Membership";
			} elseif($this->fest == 1){
				$top = "Get extra months/discount till ".date("d M", strtotime($this->festEndDt));
				$bottom = "Upgrade Membership";
			}
		} else {
			$top = NULL;
			$bottom = "Upgrade Membership";
		}
		if(!empty($bottom)){
			if($top != NULL){
				$top .= "!";
			}
			$output = array('hamburger_message'=>array('top'=>$top,'bottom'=>$bottom));
		} else {
			$output = array('hamburger_message'=>array('top'=>NULL,'bottom'=>"Buy Membership"));
		}
		return $output;
	}

	public function validateCoupon($selID, $couponCode){
	  	$memHandlerObj = new MembershipHandler();
	  	$validation = $memHandlerObj->validateCouponCode($selID,$couponCode);
		if(is_numeric($validation) && $validation == 0){ 
			$message = array('success_code'=>NULL,'message'=>'Coupon code entered is not valid');
		} elseif($validation == "INVDUR"){
			$message = array('success_code'=>NULL,'message'=>'Coupon code is no longer valid');
		} elseif($validation == "LIMEXP"){
			$message = array('success_code'=>NULL,'message'=>'Coupon code has exceeded the maximum usage');
		} elseif($validation > 0){
			$message = array('success_code'=>1,'message'=>NULL);
		}
		return array("validationCode"=>$validation,"message"=>$message);
  	}

}
