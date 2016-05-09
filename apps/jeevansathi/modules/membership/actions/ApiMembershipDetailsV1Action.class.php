<?php

// membershipDetails actions.
// @package    jeevansathi
// @subpackage membership
// @author     Avneet Singh Bindra
// @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $

class ApiMembershipDetailsV1Action extends sfAction
{

	function execute($request){
		$loginData=$request->getAttribute("loginData");
		$this->from_source = $request->getParameter("from_source");	
		// Setting profileid from login data
		if($loginData['PROFILEID']){
			$this->profileid = $loginData['PROFILEID'];
		}

		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		
		if($this->getAllPlans($this->profileid)){
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($this->output);	
		}else {
			// Some error occurred in getAllPlans
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		}

		$apiResponseHandlerObj->generateResponse();
		die;

	}

	private function getAllPlans($profileid){
		
		$userObj=new memUser($profileid);
		$purchasesObj = new BILLING_PURCHASES();
		$memHandlerObj=new MembershipHandler();

		// Code for tracking on App
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
		if($this->from_source)
			$fromSource  ="Android_app_".$this->from_source;
		else
			$fromSource  ="Android_app";		
		$memHandlerObj->addHitsTracking($this->profileid,'1','21',$fromSource,$user_agent);
		$source = 100; // Custom source for Mobile API
		
		if($profileid!='')
		{
			$userObj->setMemStatus();
			$userObj->contactsRemaining=$userObj->getRemainingContacts($userObj->getProfileid());
			// This function will pick only SERVICE ID for MAIN memberships in Uppercase format
			$memID = @strtoupper($purchasesObj->getCurrentlyActiveService($profileid));
			$memHandlerObj->trackMembership($userObj,$source);
		}

		// This code will retrieve the main membership key, i.e. P,C,ESP based on service selected
		if(!isset($memID)){
			$memID = "FREE";
		} else {
			$memID = $this->retrieveCorrectMemID($memID, $userObj);
		}

		$this->activeServices=$memHandlerObj->getActiveServices();
		$this->allMemberships = $this->activeServices;
		$this->serviceName = $memHandlerObj->getServiceNames($this->activeServices);
		$this->setSubscriptionExpDate($userObj,$memHandlerObj, $memID);

		if(strtotime($this->subStatus)>=strtotime(date("Y-m-d"))){
			// Check to set Subscription expiry message for MAIN memberships only
			// Show days for expiry within 30 days else date
			if(in_array($memID, array("P","C","ESP"))){
				$days = floor((strtotime($this->subStatus) - time())/(60*60*24))+1;
				if($days <= 30){
					$this->expiry_message = "Your membership expires in ".$days." days";	
				} else {
					$this->expiry_message = "Your membership expires on ".$this->subStatus;
				}
				
			}
		}

		// logic to get profile Picture
		if(isset($profileid) && $profileid!=''){
			$loggedInProfileObj = LoggedInProfile::getInstance();
			$loggedInProfileObj->getDetail("","","HAVEPHOTO");
			$pictureService = new PictureService($loggedInProfileObj);
			if($pictureService->getProfilePic()){
				$this->profilePicObj = $pictureService->getProfilePic()->getMainPicUrl();
			} else {
				$this->profilePicObj = NULL;	
			}
			
		} else {
			$this->profilePicObj = NULL;
		}
		 
		// Array will return default data for Main memberships
		$subscription_data = array();
		foreach($this->allMemberships as $key=>$value){
			$subscription_data[] = array("subscription_id"=>$value, 
				"subscription_name"=>$this->serviceName[$key],
				"icon_visibility"=>$this->getIconSetValues($value),
				"profile_picture"=>$this->profilePicObj,
				"plan_details"=>$this->getMembershipDetails($memHandlerObj, $userObj, $profileid, $value),
				"expiry_message"=>$this->expiry_message,
				"scroll_text"=>$this->currencySymbol.$this->startingPrice." Onwards");
		}

		// This will sort data based on existing membership of user, loggedin/loggedout state
		$final_data = $this->sortSubscriptionData($subscription_data, $memID, $memHandlerObj, $userObj, $profileid);
		$arrTollFree = array('msg'=>'You can call customer care from 9 am to 9 pm','label'=>'1-800-419-6299','value'=>'18004196299','action'=>'CALL');
		
		$this->output = array('all_subscriptions'=>json_decode(json_encode($final_data),FALSE), 
			'toll_free' => json_decode(json_encode($arrTollFree), FALSE));
		$this->output = json_decode(json_encode($this->output), TRUE);
		return $this->output;
	}

	public function retrieveCorrectMemID($memID, $userObj){
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
			if(!in_array($memID, array("P","C","ESP"))){
				$memID = $userObj->memStatus;
				$memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);	
				$memID = $memID[0];
				// Check to remove L in case of Unlimited Membership
				if(strpos($memID, "L")){
					$this->unlimited = true;
					$memID = substr($memID, 0, -1);
				}
				// Check for new memID again, this time using SERVICE_STATUS
				if(!in_array($memID, array("P","C","ESP"))){
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
		
		if($subscription_id == "P"){
			$icon_set_arr = array(array("icon_id"=>1,"icon_name"=>"Send/Receive\nInterest","status"=> 1), 
				array("icon_id"=>2,"icon_name"=>"See Contact\nDetails","status"=> 1),
				array("icon_id"=>3,"icon_name"=>"Initiate Chat\n& Messages","status"=> 1),
				array("icon_id"=>4,"icon_name"=>"Publish Contact\nDetails","status"=> 0), 
				array("icon_id"=>5,"icon_name"=>"Value Added\nServices","status"=> 0));
		} elseif ($subscription_id == "C") {
			$icon_set_arr = array(array("icon_id"=>1,"icon_name"=>"Send/Receive\nInterest","status"=> 1), 
				array("icon_id"=>2,"icon_name"=>"See Contact\nDetails","status"=> 1),
				array("icon_id"=>3,"icon_name"=>"Initiate Chat\n& Messages","status"=> 1),
				array("icon_id"=>4,"icon_name"=>"Publish Contact\nDetails","status"=> 1), 
				array("icon_id"=>5,"icon_name"=>"Value Added\nServices","status"=> 0));
		} elseif ($subscription_id == "ESP"){
			$icon_set_arr = array(array("icon_id"=>1,"icon_name"=>"Send/Receive\nInterest","status"=> 1), 
				array("icon_id"=>2,"icon_name"=>"See Contact\nDetails","status"=> 1),
				array("icon_id"=>3,"icon_name"=>"Initiate Chat\n& Messages","status"=> 1),
				array("icon_id"=>4,"icon_name"=>"Publish Contact\nDetails","status"=> 1), 
				array("icon_id"=>5,"icon_name"=>"Value Added\nServices","status"=> 1));
		} elseif ($subscription_id == "FREE"){
			$icon_set_arr = array(array("icon_id"=>1,"icon_name"=>"Send/Receive\nInterest","status"=> 1), 
				array("icon_id"=>2,"icon_name"=>"See Contact\nDetails","status"=> 0),
				array("icon_id"=>3,"icon_name"=>"Initiate Chat\n& Messages","status"=> 0),
				array("icon_id"=>4,"icon_name"=>"Publish Contact\nDetails","status"=> 0), 
				array("icon_id"=>5,"icon_name"=>"Value Added\nServices","status"=> 0));
		}

		return $icon_set_arr;
	}

	public function getMembershipDetails($memHandlerObj, $userObj, $profileid, $selectedService){
		
		// Don't go ahead if free service is being selected
		if($selectedService == "FREE"){
			return NULL;
		}

		// Currency Format calculator
		$JMembershipObj	=new JMembership();
		$ipAddress=getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):(getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):getenv("REMOTE_ADDR"));
		if(strstr($ipAddress, ","))
		{
			$ip_new = explode(",",$ipAddress);
			$ipAddress = $ip_new[0];
		}
		$isNRIUser 	=$JMembershipObj->fetchNRIStatus($ipAddress);
		if($isNRIUser)
		{
			$this->currency ='DOL';
			$currencyType=VariableParams::$otherCurrency;
			$this->currencySymbol = '$';
		}
		else
		{
			$this->currency = 'RS';
			$currencyType=VariableParams::$indianCurrency;
			$this->currencySymbol = 'RS ';
		}
		$userObj->setIpAddress($ipAddress);			
		$userObj->setCurrency($this->currency);

		$allMainMem=$memHandlerObj->fetchMembershipDetails("MAIN",$userObj);
		$this->tabs = $memHandlerObj->getMobMembershipTabs($allMainMem,$selectedService);

		// Code to compute final output
		
		$arrTemp = array();
		//print_r($this->tabs);
		foreach($this->tabs as $key=>$value){
			
			$this->tabs[$key]['SERVICE_ID'] = $selectedService.$key;

			if($key == 'L'){
				$arrTemp1['Unlimited'] = $this->tabs[$key];
				$arrTemp1['Unlimited']['SERVICE_ID'] = $selectedService.'L';
				unset($this->tabs[$key]);
				$arrTemp1['Unlimited']['DURATION'] = 'Unlimited';
				$this->tabs['Unlimited'] = $arrTemp1['Unlimited'];
			}
		}

		foreach($this->tabs as $key=>$value){
			if(is_array($value)){
				foreach($value as $k=>$v){
					if($k == 'CALL'){
						$this->tabs[$key][$k] = array("View <b>".$v." contacts</b>"); //, "View <b>unlimited contacts</b> after acceptance");
						$temp = $this->tabs[$key][$k];
						unset($this->tabs[$key][$k]);
						$this->tabs[$key]['description'] = $temp;
					}elseif($k == 'PRICE'){
						$prices_arr[$key] = $this->tabs[$key][$k];
						$this->tabs[$key][$k] = $this->tabs[$key]['DURATION']." Months for ".$this->currencySymbol.$this->tabs[$key][$k];
						$temp = $this->tabs[$key][$k];
						unset($this->tabs[$key][$k]);
						$this->tabs[$key]['heading'] = $temp;
					}else{
						unset($this->tabs[$key][$k]);
					}
				}
			}
		}

		// Set minimum price for selected service
		$this->startingPrice = min($prices_arr);
		
		return array_values($this->tabs);

	}

	public function setSubscriptionExpDate($userObj,$memHandlerObj, $memID){
		$subStatus = $memHandlerObj->getSubStatus($userObj->getProfileid());
		// Break condition in case no services are set
		if(!isset($subStatus)){
			$this->subStatus = NULL;
			return;
		}

		// Remove all services which are not MAIN Memberships
		$arrExp = array();
		foreach($subStatus as $key=>$value){
			$tempid = $this->retrieveCorrectMemID($value['SERVICEID']);
			if($tempid == "P" || $tempid == "C" || $tempid == "ESP"){
				$arrExp[] = strtotime($value['EXPIRY_DT']);
			}
		}
		// Use the latest expiry date
		$this->subStatus=date('Y-m-d', max($arrExp));
		
		if($this->subStatus == '1970-01-01'){
			$this->subStatus = NULL;
		} else {
			$this->subStatus = date("j M Y",strtotime($this->subStatus));
		}

	}

	public function sortSubscriptionData($subscription_data, $memID, $memHandlerObj, $userObj, $profileid){

		if(isset($profileid) && $profileid!=''){
			// Case where member is not a free member
			if(isset($memID) && $memID != "FREE"){
				$default_data[] = array("subscription_id"=>$memID, 
				"subscription_name"=>$memHandlerObj->getUserServiceName($memID),
				"icon_visibility"=>$this->getIconSetValues($memID),
				"profile_picture"=>$this->profilePicObj,
				"plan_details"=>$this->getMembershipDetails($memHandlerObj, $userObj, $profileid, $memID),
				"expiry_message"=>$this->expiry_message,
				"scroll_text"=>NULL);
				foreach($subscription_data as $key=>$value){
					if($value['subscription_id'] == $memID){
						unset($subscription_data[$key]);
					}
				}	
			} else {
				// Case where user is either logged out or free
				$default_data[] = array("subscription_id"=>"FREE", 
				"subscription_name"=>"Free Member",
				"icon_visibility"=>$this->getIconSetValues('FREE'),
				"profile_picture"=>$this->profilePicObj,
				"plan_details"=>NULL,
				"expiry_message"=>NULL,
				"scroll_text"=>"Upgrade your membership, swipe to browse plans");
			}
			$final_data = array_merge_recursive($default_data, $subscription_data);	
		} else {
			$final_data = $subscription_data;
		}
				
		return $final_data;

	}

}
