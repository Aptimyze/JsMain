<?php

/**
 * Created by PhpStorm.
 * User: Pankaj Khandelwal
 * Date: 03/05/16
 * Time: 6:29 PM
 */
class ShowProfileStats
{

	/**
	 * @var
	 */
	private $profileObj;
	/**
	 * @var
	 */
	private $profileid;
	/**
	 * @var array
	 */
	private $mainDataArr;
	/**
	 * @var array
	 */
	private $detailedDataArr;

	/**
	 * ShowCRMStats constructor.
	 * @param $profileObj
	 */
	public function __construct($profileObj)
	{
		$this->profileObj = $profileObj;
		$this->profileid = $this->profileObj->getPROFILEID();
		$this->detailedDataArr = array();
		$this->mainDataArr = array();
	}

	/**
	 * @return array
	 */
	public function getDetailedProfileStats()
	{
		//Profile Contacts Count
		$this->getContactData();
		
		$this->getPhotoRequestDetail();
		$this->getHoroscopeDetail();
		$this->getProfileLength();
		$this->getMobileUsage();
		$this->getEOIRatio();
		$this->getMobileVerificaionStatus();
		$this->getAddressStatus();
		$this->getEmailStatus();
		$this->getMessangerStatus();
		$this->getContactViewedCount();
		// Membership Discount Details
		//print_r($this->profileObj);die;
		$this->geMembershipDiscount();
		$this->getServiceRequirement();
		$this->getScore();
		$this->getEoiLimit();
		$this->detailedDataArr['SOURCE'] = $this->profileObj->getSOURCE();

		// get Table Name for Contact
                if($this->profileObj->getACTIVATED()=='D')
                        $contactTableName="DELETED_PROFILE_CONTACTS";
                else
                        $contactTableName="CONTACTS";
		$this->detailedDataArr['CONTACT_TABLE_NAME'] =$contactTableName;

		return $this->detailedDataArr;
	}
        /**
         * @return array
         */
        public function geMainProfileStats($profileDetailArr)
        {
		$profileCompletion =$profileDetailArr['profileCompletion']['PCS'];
		$this->profileCompletion =$profileCompletion;
                $this->getOnlineStatus();
                $this->getMembesrhipDetails();
                $this->getServiceRequirement(1);
                $this->getProfileAllotedDetails();
		$this->geCustomisedUsername();

		$lastModDate =$this->profileObj->getMOD_DT();
		$lastModDate = date("M d, Y", strtotime($lastModDate));
		$this->mainDataArr['LAST_MOD_DT'] =$lastModDate;

		$privacy =$this->profileObj->getPRIVACY();
		$this->mainDataArr['PRIVACY'] =$privacy;
		include_once (sfConfig::get("sf_web_dir") . "/profile/ntimes_function.php");
      		$this->mainDataArr['PROFILE_VIEWS'] = ntimes_count($this->profileObj->getPROFILEID(), "SELECT"); 

                return $this->mainDataArr;
        }
        /**
         *
         */
        public function geCustomisedUsername()
        {
		$nameOfUserObj =new incentive_NAME_OF_USER('newjs_slave');
		$nameOfUser =$nameOfUserObj->getName($this->profileid);
		$this->mainDataArr['PROFILE_NAME'] =$nameOfUser;
	}
	/**
	 *
	 */
	public function geMembershipDiscount()
	{
		// Renewal Discount
		$memHandlerObj = new MembershipHandler();
		// Start - Conditions to display renewal discount only if profile applicable		
		$userObj = new memUser($this->profileid);
		list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency($this->profileid);
        $userObj->setIpAddress($ipAddress);
        $userObj->setCurrency($currency);
        if (!empty($this->profileid)) {
            $userObj->setMemStatus();
            $userType = $userObj->userType;
        }
        if($userType == 4 || $userType == 6) {
        	$renewalDiscount = $memHandlerObj->getVariableRenewalDiscount($this->profileid,1);
        }
        // End - Conditions to display renewal discount only if profile applicable

		// Vd discount
		$vdObj = new VariableDiscount();
		$vdDiscountDetails = $vdObj->getDiscDetails($this->profileid);
		if (is_array($vdDiscountDetails)) {
			$vdExpiryDate = date("d  M  Y", JSstrToTime($vdDiscountDetails['EDATE']));
			$vdApplicable = $vdObj->getDiscountWithMemType($this->profileid);
		}
		$vdLogDetails = $vdObj->getPreviousVdLogDetails($this->profileid);
		if($vdLogDetails['EDATE'])
			$lastVdExpiryDate = date("d  M  Y", JSstrToTime($vdLogDetails['EDATE']));
		$lastVdApplicable =$vdLogDetails['DISCOUNT'];	

		$this->detailedDataArr['RENEWAL_DISCOUNT'] = $renewalDiscount;
		$this->detailedDataArr['VARIABLE_DISCOUNT'] = $vdApplicable;
		$this->detailedDataArr['VD_EXPIRY'] = $vdExpiryDate;
		$this->detailedDataArr['LAST_VD_EXPIRY'] = $lastVdExpiryDate;
		$this->detailedDataArr['LAST_VD_APPLICABLE'] = $lastVdApplicable;
	}

	/**
	 *
	 */
	private function getScore()
	{
		$adminPoolObj = new incentive_MAIN_ADMIN_POOL('newjs_slave');
		$result = $adminPoolObj->get($this->profileid, 'PROFILEID', 'SCORE,ANALYTIC_SCORE');
		$this->detailedDataArr['SCORE'] = $result['SCORE'];
		$this->detailedDataArr['ANALYTIC_SCORE'] = $result['ANALYTIC_SCORE'];

	}

	private function getEoiLimit()
	{
		$startDates = CommonFunction::getContactLimitDates();
		if(is_array($startDates))
		{
			$this->detailedDataArr['weekStartDate'] = $startDates['weekStartDate'];
			$this->detailedDataArr['weekEndDate'] = CommonFunction::getLimitEndingDate("WEEK");
			$this->detailedDataArr['monthStartDate'] = $startDates['monthStartDate'];
			$this->detailedDataArr['monthEndDate'] = CommonFunction::getLimitEndingDate("MONTH");
		}
		
		$limitArr = CommonFunction::getContactLimits($this->profileObj->getSUBSCRIPTION(), $this->profileid);

		// Limits given
		$this->detailedDataArr['dayLimit'] = $limitArr['DAY_LIMIT'];
		$this->detailedDataArr['weeklyLimit'] = $limitArr['WEEKLY_LIMIT'];
		$this->detailedDataArr['monthlyLimit'] = $limitArr['MONTH_LIMIT'];

		//  Sent Limits
		$profileMemcacheServiceObj = new ProfileMemcacheService($this->profileObj);
		$this->detailedDataArr['todaySentLimit'] = $profileMemcacheServiceObj->get("TODAY_INI_BY_ME");
		$this->detailedDataArr['weeklySentLimit'] = $profileMemcacheServiceObj->get("WEEK_INI_BY_ME");
		$this->detailedDataArr['monthlySentLimit'] = $profileMemcacheServiceObj->get("MONTH_INI_BY_ME");
	}
	/**
	 *
	 */
	private function getOnlineStatus()
	{
                $jsCommonObj =new JsCommon();
                $onlineStatus =$jsCommonObj->getOnlineStatus($this->profileid);
		/*
		$recentOnlineObj = new userplane_recentusers();
		$onlineStatus = $recentOnlineObj->isOnline($this->profileid);*/
		$this->mainDataArr['ONLINE_STATUS'] = $onlineStatus;
	}

	/**
	 *
	 */
	private function getMembesrhipDetails()
	{
		$subscriptionArr = array();
		$actionsReqArr =array('Free member, explain membership benefits','Paid member, explain EoI, Acceptance, viewing contact details');		
		$vasDetailsArr = VariableParams::$vasNamesAndDescription;
		$mainMembershipNamesArr =VariableParams::$mainMembershipNamesArr;
		$subscription = $this->profileObj->getSUBSCRIPTION();
		$subscriptionArr = explode(',',$subscription);

		// Main Membership
		if (in_array("F", $subscriptionArr))
			$mainMembership = true;
		if (in_array("F", $subscriptionArr) && in_array("D", $subscriptionArr) && in_array("N", $subscriptionArr))
			$membership = $mainMembershipNamesArr['NCP'];
		else if (in_array("F", $subscriptionArr) && in_array("D", $subscriptionArr))
			$membership = mainMem::EVALUE_LABEL;
		elseif (in_array("F", $subscriptionArr) && in_array("X", $subscriptionArr))
			$membership = mainMem::JSEXCLUSIVE_LABEL;
		elseif (in_array("F", $subscriptionArr))
			$membership = mainMem::ERISHTA_LABEL;
		elseif (in_array("D", $subscriptionArr))
			$membership = mainMem::ECLASSIFIED_LABEL;
		else
			$membership = 'Free Member';

		// Membesrhip Vas
		$vasArr =VariableParams::$vasOrder;
		foreach ($subscriptionArr as $key => $val) {
			if(!in_array("$val",$vasArr))
				continue;
			$vas = $vasDetailsArr[$val]['name'];
			$vasName[] =str_replace('<br>',' ',$vas);
		}
		if (is_array($vasName))
			$membershipVas = implode(" , ", $vasName);

		// Membership Expiry Date
		if ($mainMembership) {
			$serviceStatusObj = new BILLING_SERVICE_STATUS('newjs_slave');
			$expDate = $serviceStatusObj->getMaxExpiryDate($this->profileid);
			if($expDate)
				$membershipExpDate = date("M d, Y", strtotime($expDate));
			else
				$membershipExpDate ='';
		}
		if(!$mainMembership)
			$actionRequired =$actionsReqArr[0];
		else
			$actionRequired =$actionsReqArr[1];

		$this->mainDataArr['MEMBERSHIP'] = $membership;
		$this->mainDataArr['MEMBERSHIP_VAS'] = $membershipVas;
		$this->mainDataArr['MEMBERSHIP_EXPIRY'] = $membershipExpDate;
		$this->mainDataArr['ACTION_REQUIRED'] = $actionRequired;
		/*if($membershipExpDate)
			$this->mainDataArr['PAID_MEMBERSHIP_EXPIRY'] = $membershipExpDate." 23:59:59";*/
	}

	/**
	 *
	 */
	private function getServiceRequirement($searchReq='')
	{
		$todayDate = date("Y-m-d");
		$havePhoto = $this->profileObj->getHAVEPHOTO();
		$lastLoginDt = $this->profileObj->getLAST_LOGIN_DT();
		$entryDt = $this->profileObj->getENTRY_DT();
		$seriousnessCount = $this->profileObj->getSERIOUSNESS_COUNT();	

		$profilePercent = $this->profileCompletion;  
		$totalContacted = $this->detailedDataArr["CONTACTS"]["TOTAL_SENT"];
		$totalAcceptance= $this->detailedDataArr["CONTACTS"]["ACC_BY_ME"];
		$totalConViewed = $this->detailedDataArr["CONTACTS"]["CONTACTS_VIEWED"];

		$date10DayPrev = date("Y-m-d", time() - 10 * 24 * 60 * 60);
		$loginFreqDetails =$this->getLoginFrequencyDetails($this->profileid);
		list($loginFrequencyPer,$loginCount,$ageInMonths,$ageOfRegistration) =$loginFreqDetails;
		$loginFrequency =$loginCount.'/ '.$ageOfRegistration;

		if($searchReq){
			$searchQueryObj = new MIS_SEARCHQUERY('newjs_slave');
			$searchFrequency = $searchQueryObj->performedSearchInLast10Days($this->profileid, $date10DayPrev);
		}
		$serviceRequirement = $this->serviceRequirement($havePhoto, $totalContacted, $lastLoginDt, $profilePercent, $searchFrequency, $ageOfRegistration, $date10DayPrev);
		$this->mainDataArr['SERVICE_REQUIREMENT'] = $serviceRequirement;

		$rbEligibilityFlag =$this->rbEligibilityFlag($havePhoto,$totalContacted,$totalAcceptance,$loginFrequencyPer,$totalConViewed,$ageInMonths,$seriousnessCount);
		$this->detailedDataArr['RB_ELIGIBILITY_FLAG'] =$rbEligibilityFlag;
		$this->detailedDataArr['LOGIN_FREQ'] =$loginFrequency;
	}
	/**
	 * @param $havePhoto
	 * @param $totalContacted
	 * @param $lastLoginDt
	 * @param $profilePercent
	 * @param $searchFrequency
	 * @param $daysDiff
	 * @param $date10DayPrev
	 * @return string
	 */
	private function serviceRequirement($havePhoto, $totalContacted, $lastLoginDt, $profilePercent, $searchFrequency='', $daysDiff, $date10DayPrev)
	{
		if ($daysDiff < 6)
			$messageArr[] = "Explain how to use Jeevansathi.com";
		if ($havePhoto != "Y")
			$messageArr[] = "Explain benefit of uploading photo";
		if ($totalContacted < 1)
			$messageArr[] = "Explain how to send Eoi";
		if (strtotime($lastLoginDt) < strtotime($date10DayPrev))
			$messageArr[] = "Explain benefits of logging frequently";
		if ($profilePercent <= 60)
			$messageArr[] = "Update profile with more information";
		if (!$searchFrequency)
			$messageArr[] = "Explain benefits of actively searching";
		if (is_array($messageArr))
			$requirementMessage = implode(",", $messageArr);
		else
			$requirementMessage = "N/A";
		return $requirementMessage;
	}
	private function rbEligibilityFlag($photo,$totalContacted,$totalAcceptance,$loginFrequency,$totalConViewed,$diffInMonths,$seriousnessCount)
	{
		$rbEligibleArr =array(1=>'Eligible',2=>'Eligible,If photo is Uploaded',3=>'Not Eligible');
		$starProfile=$totalConViewed/pow($diffInMonths,0.7);
		if($starProfile>=3)
			$flag=1;
		elseif($seriousnessCount>1){
			if($photo=='Y')
				$flag=1;
			else
				$flag=2;
		}
		elseif($totalContacted==0){
			if($photo=='Y')
				$flag=1;
			else
				$flag=2;
		}
		elseif($totalContacted>=1 && $totalAcceptance<=4 && $loginFrequency< 0.25){
			if($photo=='Y')
				$flag=1;
			else
				$flag=2;
		}
		else
	                $flag=3;
		$rbEligible =$rbEligibleArr[$flag];
        	return $rbEligible;
	}
        private function getLoginFrequencyDetails()
        {
		$regDate = $this->profileObj->getENTRY_DT();
		$dbName=JsDbSharding::getShardNo($this->profileid);
		$csvHandlerObj =new csvGenerationHandler();
		$result =$csvHandlerObj->calculateLoginFrequency($this->profileid,$regDate,$dbName);
		return $result;
        }
	/**
	 *
	 */
	private function getProfileAllotedDetails()
	{
		$mainAdminObj = new incentive_MAIN_ADMIN();
		$result = $mainAdminObj->get($this->profileid, 'PROFILEID', 'ALLOTED_TO');
		$this->mainDataArr['ALLOTED_AGENT'] = $result['ALLOTED_TO'];
	}

	/**
	 *
	 */
	private function getContactData()
	{
		$this->profileid = $this->profileObj->getPROFILEID();
		$activatedStatus = $this->profileObj->getACTIVATED();
		if($activatedStatus == "D")
		{
			$this->contactObj = new DeletedProfileContacts();
			$skipContactedType  = SkipArrayCondition::$default;
			$skipProfileObj     = SkipProfile::getInstance($this->profileid);
			$skipProfile        = $skipProfileObj->getSkipProfiles($skipContactedType);
			$group             = "FILTERED,TYPE,SEEN";
			$contactsCount     = $this->contactObj->getContactsCount(Array(
				"SENDER" => $this->profileid,
				"TYPE" => Array(
					'A',
					'D',
					'I',
					'E',
					'C'
				)
			), $group, 0,$skipProfile);
			if (is_array($contactsCount)) {
				foreach ($contactsCount as $key => $value) {
					switch ($value["TYPE"]) {
						case 'A':
							if ($value["SEEN"] != 'Y') {
								$ACC_ME_NEW = $ACC_ME_NEW + $value["COUNT"];
							}
							$ACC_ME = $ACC_ME + $value["COUNT"];
							break;
						case 'I':
							if ($value["SEEN"] != 'Y') {
								$NOT_OPEN = $NOT_OPEN + $value["COUNT"];
							}
							$NOT_REP = $NOT_REP + $value["COUNT"];
							break;
						case 'D':
							if ($value["SEEN"] != 'Y') {
								$DEC_ME_NEW = $DEC_ME_NEW + $value["COUNT"];
							}
							$DEC_ME = $DEC_ME + $value["COUNT"];
							break;
						case 'E':
							$CANCELLED_EOI = $CANCELLED_EOI + $value["COUNT"];
							$DEC_BY_ME     = $DEC_BY_ME + $value["COUNT"];
							break;
						case 'C':
							$DEC_BY_ME = $DEC_BY_ME + $value["COUNT"];
							break;
						default:
							break;
					}
				}
			}
			$contactsCount = $this->contactObj->getContactsCount(Array(
				"RECEIVER" => $this->profileid,
				"TYPE" => Array(
					'A',
					'D',
					'I',
					'E',
					'C'
				)
			), $group, 0,$skipProfile);
			if (is_array($contactsCount)) {
				foreach ($contactsCount as $key => $value) {
					switch ($value["TYPE"]) {
						case 'A':
							$ACC_BY_ME = $ACC_BY_ME + $value["COUNT"];
							break;
						case 'D':
							$DEC_BY_ME = $DEC_BY_ME + $value["COUNT"];
							break;
						case 'I':
							if ($value["FILTERED"] == 'Y'){
								if ($value['TIME1']=='0'){
									if ($value["SEEN"] != 'Y')
										$FILTERED_NEW = $FILTERED_NEW + $value["COUNT"];
									$FILTERED = $FILTERED + $value["COUNT"];
								}
							}
							else {
								if($value["TIME1"] == 0)
								{
									if ($value["SEEN"] != 'Y')
									{
										$AWAITING_RESPONSE_NEW = $AWAITING_RESPONSE_NEW + $value["COUNT"];
									}
									$AWAITING_RESPONSE = $AWAITING_RESPONSE + $value["COUNT"];
								}
							}
							break;
						case 'C':
						case 'E':
							if ($value["SEEN"] != 'Y') {
								$DEC_ME_NEW = $DEC_ME_NEW + $value["COUNT"];
							}
							$DEC_ME = $DEC_ME + $value["COUNT"];
							break;
						default:
							break;
					}
				}
			}
			$this->detailedDataArr["CONTACTS"]["AWAITING_RESPONSE"] = $AWAITING_RESPONSE;
			$this->detailedDataArr["CONTACTS"]["AWAITING_RESPONSE_NEW"] = $AWAITING_RESPONSE_NEW;
			$this->detailedDataArr["CONTACTS"]["FILTERED"] = $FILTERED;
			$this->detailedDataArr["CONTACTS"]["ACC_BY_ME"] = $ACC_BY_ME;
			$this->detailedDataArr["CONTACTS"]["DEC_BY_ME"] = $DEC_BY_ME;
			$this->detailedDataArr["CONTACTS"]["TOTAL_RECEIVED"] = $AWAITING_RESPONSE+$FILTERED+$ACC_BY_ME+$DEC_BY_ME;
			$this->detailedDataArr["CONTACTS"]["NOT_REP"] = $NOT_REP;
			$this->detailedDataArr["CONTACTS"]["NOT_OPEN"] = $NOT_OPEN;
			$this->detailedDataArr["CONTACTS"]["VIEWED"] = $NOT_REP-$NOT_OPEN;
			$this->detailedDataArr["CONTACTS"]["ACC_ME"] = $ACC_ME;
			$this->detailedDataArr["CONTACTS"]["DEC_ME"] = $DEC_ME;
			$this->detailedDataArr["CONTACTS"]["TOTAL_ACC"] = $ACC_ME+$ACC_BY_ME;
			$this->detailedDataArr["CONTACTS"]["TOTAL_SENT"] = $DEC_ME+$ACC_ME+$NOT_REP;
			$this->detailedDataArr["CONTACTS"]["TOTAL_EOI"] = $this->detailedDataArr["CONTACTS"]["TOTAL_SENT"]+$this->detailedDataArr["CONTACTS"]["TOTAL_RECEIVED"];
		}
		else
		{
			$profileMemcachServiceObj = new ProfileMemcacheService($this->profileObj);
			$this->detailedDataArr["CONTACTS"]["AWAITING_RESPONSE"] = $profileMemcachServiceObj->get("AWAITING_RESPONSE");
			$this->detailedDataArr["CONTACTS"]["AWAITING_RESPONSE_NEW"] = $profileMemcachServiceObj->get("AWAITING_RESPONSE_NEW");
			$this->detailedDataArr["CONTACTS"]["FILTERED"] = $profileMemcachServiceObj->get("FILTERED");
			$this->detailedDataArr["CONTACTS"]["ACC_BY_ME"] = $profileMemcachServiceObj->get("ACC_BY_ME");
			$this->detailedDataArr["CONTACTS"]["DEC_BY_ME"] = $profileMemcachServiceObj->get("DEC_BY_ME");
			$this->detailedDataArr["CONTACTS"]["TOTAL_RECEIVED"] = $profileMemcachServiceObj->get("AWAITING_RESPONSE")+$profileMemcachServiceObj->get("FILTERED")+$profileMemcachServiceObj->get("ACC_BY_ME")+$profileMemcachServiceObj->get("DEC_BY_ME");
			$this->detailedDataArr["CONTACTS"]["NOT_REP"] = $profileMemcachServiceObj->get("NOT_REP");
			$this->detailedDataArr["CONTACTS"]["NOT_OPEN"] = $profileMemcachServiceObj->get("NOT_REP")-$profileMemcachServiceObj->get("OPEN_CONTACTS");
			$this->detailedDataArr["CONTACTS"]["VIEWED"] = $profileMemcachServiceObj->get("OPEN_CONTACTS");
			$this->detailedDataArr["CONTACTS"]["ACC_ME"] = $profileMemcachServiceObj->get("ACC_ME");
			$this->detailedDataArr["CONTACTS"]["DEC_ME"] = $profileMemcachServiceObj->get("DEC_ME");
			$this->detailedDataArr["CONTACTS"]["TOTAL_ACC"] = $profileMemcachServiceObj->get("ACC_BY_ME")+$profileMemcachServiceObj->get("ACC_ME");
			$this->detailedDataArr["CONTACTS"]["TOTAL_SENT"] = $profileMemcachServiceObj->get("DEC_ME")+$profileMemcachServiceObj->get("ACC_ME")+$profileMemcachServiceObj->get("NOT_REP");
			$this->detailedDataArr["CONTACTS"]["TOTAL_EOI"] = $this->detailedDataArr["CONTACTS"]["TOTAL_SENT"]+$this->detailedDataArr["CONTACTS"]["TOTAL_RECEIVED"];
			
		}
		$this->detailedDataArr["CONTACTS"]["FREE_CONTACTED_BY_ME"] = "NA";
		$this->detailedDataArr["CONTACTS"]["FREE_CONTACTED_ME"] = "NA";

	}


	/**
	 *
	 */
	private function getPhotoRequestDetail()
	{
		if($this->profileObj->getACTIVATED()!="D")
		{
			$profilememcacheObj = new ProfileMemcacheService($this->profileObj);
			$this->detailedDataArr["CONTACTS"]["PHOTO_REQUEST_COUNT"] = $profilememcacheObj->get("PHOTO_REQUEST");
		}
	}

	/**
	 *
	 */
	private function getHoroscopeDetail()
	{
		if($this->profileObj->getACTIVATED()!="D")
		{
			$profilememcacheObj = new ProfileMemcacheService($this->profileObj);
			$this->detailedDataArr["CONTACTS"]["HOROSCOPE_REQUEST_COUNT"] = $profilememcacheObj->get("HOROSCOPE");
		}
	}

	/**
	 *
	 */
	private function getContactViewedCount()
	{
		if($this->profileObj->getACTIVATED()!="D")
		{
			$profilememcacheObj = new ProfileMemcacheService($this->profileObj);
			$contactByMeProfile = unserialize($profilememcacheObj->memcache->getCONTACTED_BY_ME());
			foreach ($contactByMeProfile as $type=>$profileids)
			{
				foreach($profileids as $key=>$profileid)
				{
					$profiles[] = $profileid;
				}
			}

			$this->detailedDataArr["CONTACTS_VIEWED"] = $profilememcacheObj->get("CONTACTS_VIEWED");
			$viewLogObj = new JSADMIN_VIEW_CONTACTS_LOG();
			$this->detailedDataArr["DIRECT_CONTACTS_VIEWED"] = $viewLogObj->totalContactsByViewer($this->profileid);
			$jsadmin_CONTACTS_ALLOTED_OBJ =new jsadmin_CONTACTS_ALLOTED();
			$this->detailedDataArr["REMAINING_CONTACT"] = $jsadmin_CONTACTS_ALLOTED_OBJ->getViewedContacts($this->profileid);
			$selfContactViewed = $profilememcacheObj->get("PEOPLE_WHO_VIEWED_MY_CONTACTS");
			$this->detailedDataArr["CONTACT_VIEWED_FREQ"] = $this->detailedDataArr["DIRECT_CONTACTS_VIEWED"]."/".$this->detailedDataArr["CONTACTS_VIEWED"];
		}

	}


	private function getProfileLength()
	{
		$length = strlen($this->profileObj->getYOURINFO())
			+strlen($this->profileObj->getFAMILYINFO())
			+strlen($this->profileObj->getFATHER_INFO())
			+strlen($this->profileObj->getSPOUSE())
			+strlen($this->profileObj->getSIBLING_INFO())
			+strlen($this->profileObj->getJOB_INFO());
		$this->detailedDataArr["PROFILE_LENGTH"]  = $length;

	}
	
	private function getMobileUsage()
	{
		$lastMonth = date('Y-m-d', strtotime('-30 days'));
		$loginTrackingobj = new MIS_LOGIN_TRACKING('crm_slave');
			$data = $loginTrackingobj->getLoginChannel($this->profileid,$lastMonth);
		if(is_array($data) && (in_array('A',$data) || in_array('I',$data)))
			$mobile_usage = "Uses Mobile App";
		else if(is_array($data) && (in_array('M',$data) || in_array('N',$data)))
			$mobile_usage = "Uses Mobile site but no mobile app - inform about app download";
		else
			$mobile_usage = "No Mobile Usage - inform about mobile site and app";
		$this->detailedDataArr["MOBILE_USAGE"] = $mobile_usage;
	}
	private function getEOIRatio()
	{
		$ntimesObj = new NEWJS_JP_NTIMES();
		$ntimes = $ntimesObj->getProfileViews($this->profileid);
		$this->detailedDataArr["TOTAL_VIEW"] = $ntimes;
		$this->detailedDataArr["EOI_RATIO"] = round((($this->detailedDataArr["CONTACTS"]["TOTAL_RECEIVED"]/$ntimes)*100),1);
	}

	private function getMobileVerificaionStatus(){
		$altContact = new ProfileContact();
		$data = $altContact->getProfileContacts($this->profileid);
		$altMobileStatus = $data["ALT_MOB_STATUS"];
		$mobStatus = $this->profileObj->getMOB_STATUS();
		$landLineStatus = $this->profileObj->getLANDL_STATUS();
		$str = "";
		if($mobStatus == "Y")
			$str = $str."Mobile 1";
		if($str && $altMobileStatus == "Y")
			$str = $str." ,";
		if($altMobileStatus == "Y")
			$str = $str." Mobile 2";
		if($str && $landLineStatus == "Y")
			$str = $str." ,";
		if($landLineStatus == "Y")
			$str = $str." Landline";
		if($altMobileStatus == "Y" || $mobStatus == "Y" || $landLineStatus=="Y")
			$str =$str ." Verified";
		else
			$str = $str." Not Verified";

		$this->detailedDataArr["MOB_STATUS"] = $str;
	}

	private function getEmailStatus()
	{
		$verifyEmailObj = new NEWJS_VERIFY_EMAIL();
		$verification = $verifyEmailObj->getVerifyEmail($this->profileid);
		if($verification == "Y")
			$status = "Verified";
		else {
			$bouncedObj = new bounces_BOUNCED_MAILS();
			$bounced = $bouncedObj->checkEntry($this->profileObj->getEMAIL());
			if ($bounced)
				$status = "Bounced: " . $this->profileObj->getEMAIL();
			else
				$status = "Not Verified";
		}
		$this->detailedDataArr["EMAIL_STATUS"] = $status;
	}

	private function getAddressStatus()
	{
		$addVerificationObj = new jsadmin_ADDRESS_VERIFICATION();
		$status = $addVerificationObj->getAddressVerificionStatus($this->profileid);
		if($status == "Y")
			$this->detailedDataArr["ADDRESS_STATUS"] = "Verified";
		else
			$this->detailedDataArr["ADDRESS_STATUS"] = "Not Verified";
	}

	private function getMessangerStatus()
	{
		$MESSENGER_ID = $this->profileObj->getMESSENGER_ID();
		if($MESSENGER_ID)
		{
			$MESSENGER_ID =trim($MESSENGER_ID);
			$check_messenger =stristr($MESSENGER_ID,'@gmail');
			if($check_messenger)
				$messenger_id ='Present (Gtalk id)';
			else
				$messenger_id ='Present (Other id)';
		}
		else
			$messenger_id ='Not Present';
		$this->detailedDataArr["MESSENGER_ID"] = $messenger_id;
	}



}
