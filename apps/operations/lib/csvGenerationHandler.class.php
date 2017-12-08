<?php
/***************************
@developed Date : 20-10-2013
***************************/
include_once(JsConstants::$docRoot."/profile/functions.inc");	
$fromCron=1;
include_once(JsConstants::$docRoot."/profile/connect.inc");	
include_once(JsConstants::$docRoot."/sugarcrm/custom/include/language/en_us.lang.php");

class csvGenerationHandler 
{
	function __construct()
	{
		global $smarty;
		include_once(JsConstants::$smartyDir);
		$smarty=new Smarty;
		$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/jeevansathi");
		$smarty->setCompileDir(JsConstants::$docRoot."/smarty/templates_c");
		$db=connect_db();
	}
	public function fetchIST($time)
	{
		$ISTtime=strftime("%Y-%m-%d %H:%M",JSstrToTime("$time + 10 hours 30 minutes"));
			return $ISTtime;
	}
	public function removeOldProfiles($processObj)
	{
		$processName=$processObj->getProcessName();
		if($processName=="FTA_REGULAR")
		{
			$ftaCsvData=new incentive_FTA_CSV_DATA();	
			$csvEntryDate=date("Y-m-d",time()-30*24*60*60);
			$ftaCsvData->removeProfiles($csvEntryDate);
		}
		elseif($processName=="SALES_REGULAR")
		{
			// remove more than 30 days old profiles  from regular sales table 
			$salesRegularCampaignTables  =crmParams::$salesRegularCampaignTables;
			foreach($salesRegularCampaignTables as $key=>$tableName){	
							$salesCsvData=new $tableName();
							$csvEntryDate=date("Y-m-d",time()-30*24*60*60);
							$salesCsvData->removeProfiles($csvEntryDate);
			}
			// truncate regular sales temp table
			$saleCsvTempObj =new incentive_SALES_CSV_DATA_TEMP('newjs_master');
			$saleCsvTempObj->truncate();	
		}
		elseif($processName=="SALES_REGISTRATION")
		{
						$salesRegCsvData=new incentive_SALES_REGISTRATION_CSV_DATA();
						$csvEntryDate=date("Y-m-d",time()-30*24*60*60);
						$salesRegCsvData->removeProfiles($csvEntryDate);
	
			// truncate registration sales temp table
			$saleCsvTempObj =new incentive_SALES_REGISTRATION_CSV_DATA_TEMP('newjs_master');
			$saleCsvTempObj->truncate();
		}
		elseif($processName=="SUGARCRM_LTF")
		{
			$sugarcrmLtfCsvObj=new incentive_SUGARCRM_LTF_CSV_DATA();
			$csvEntryDate=date("Y-m-d",time()-30*24*60*60);
			$sugarcrmLtfCsvObj->removeProfiles($csvEntryDate);
		}
                elseif($processName=="failedPaymentInDialer")
                {
			// set dial status=0 for profiles older than last 12 hours
			$dateTime =date("Y-m-d H:i:s",time()-12*60*60);		
			$failedPaymentObj =new incentive_SALES_CSV_DATA_FAILED_PAYMENT();
			$profiles =$failedPaymentObj->getObseleteProfiles();
			$failedPaymentObj->updateDialStatus($dateTime,$profiles);
                        
                        // delete from sales temp table
			$fpCsvTempObj =new incentive_PROCESS_CSV_DATA_TEMP('newjs_master');
			$fpCsvTempObj->delete($processName);	
                }
		elseif($processName=="paidCampaignProcess"){
                        $dateTime =date("Y-m-d",time()-6*24*60*60);
                        $paidCampaignObj =new incentive_SALES_CSV_DATA_PAID_CAMPAIGN();
                        $paidCampaignObj->updateDialStatus($dateTime);
		}
                elseif($processName=="rcbCampaignInDialer"){
                        $dateTime =date("Y-m-d H:i:s",time()-2*24*60*60);
                        $paidCampaignObj =new incentive_SALES_CSV_DATA_RCB();
                        $paidCampaignObj->updateDialStatus($dateTime);
                }
	}
	public function storeTemporaryProfiles($processObj,$profiles)
	{
		$processName=$processObj->getProcessName();
		if($processName=="SALES_REGULAR")
		{
			$tempSalesCsvObj =new incentive_SALES_CSV_DATA_TEMP();
			$totalCount	 =count($profiles);
			for($i=0; $i<$totalCount; $i++){
				$profileArr =$profiles[$i];
				$tempSalesCsvObj->insertProfile($profileArr['PROFILEID'],$profileArr['USERNAME'] ,$profileArr['ENTRY_DT']);
			}	
		}
		elseif($processName=="SALES_REGISTRATION")
		{
			$tempSalesCsvObj = new incentive_SALES_REGISTRATION_CSV_DATA_TEMP();
			for($i=0; $i<count($profiles); $i++){
				$profileDetails =$profiles[$i];
				$tempSalesCsvObj->insertProfile($profileDetails);
			}
		}
                elseif($processName=="failedPaymentInDialer"||$processName == "renewalProcessInDialer")
                {
                        $tempFPCsvObj =new incentive_PROCESS_CSV_DATA_TEMP();
			$totalCount	 =count($profiles);
			for($i=0; $i<$totalCount; $i++){
				$profileArr =$profiles[$i];
                                if($processName == "renewalProcessInDialer"){
                                    $tempFPCsvObj->insertProfile($profileArr['PROFILEID'], $processName);
                                }else{
                                    $tempFPCsvObj->insertProfile($profileArr['PROFILEID'], $processName);
                                }
				
			}
                    
                }
	}
		public function fetchTemporaryProfiles($processObj,$profiles)
		{
				$processName=$processObj->getProcessName();
				if($processName=="SALES_REGULAR"){
						$tempSalesCsvObj = new incentive_SALES_CSV_DATA_TEMP();
			$profiles =$tempSalesCsvObj->getProfiles();
			}
				elseif($processName=="SALES_REGISTRATION"){
						$tempSalesCsvObj = new incentive_SALES_REGISTRATION_CSV_DATA_TEMP();
						$profiles =$tempSalesCsvObj->getProfiles();
				}
		return $profiles;
		}

		public function filterMalesWhoseAgeGreaterThanTwentyThree($processObj,$profile){
			
			$processName = $processObj->getProcessName();

			if($processName=="SUGARCRM_LTF"){
				$pid = $processObj->getIdAllot();
				if(isset($profile['age']) && isset($profile['gender_val'])){
					if($profile['gender_val'] == 'F'){
						return true;
					} elseif($profile['gender_val'] == 'M' && $profile['age'] > 23){
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			}
			
		}

	public function fetchProfilesDetail($processObj, $profileArr='' ,$fields='', $extraParam='')
	{
		$processName = $processObj->getProcessName();
		
		if($processName=="SUGARCRM_LTF")
		{
			$attribute 	=array();
			$mmScoreArr 	=$processObj->getScore();	
			$pid 		=$processObj->getIdAllot();   				// pid = profileid
			$attribute[$pid]=$this->getDetailedValues($pid);
			$attribute[$pid]=$this->getPhoneValues($attribute, $pid);
			if(!$attribute[$pid])
				return;
			$attribute[$pid] = $this->getPriorityValue($attribute, $pid, $mmScoreArr);
			return $attribute[$pid];
		}
		else if($processName=='QA_ONLINE'){
			$mainAdminPoolObj =new incentive_MAIN_ADMIN_POOL('newjs_masterRep');
			if(count($profileArr)>0)
				$profileDetails =$mainAdminPoolObj->getProfileDetails($profileArr);	
			return $profileDetails;	
		}
		else if(!$fields)
		{
			$fields ="PROFILEID,USERNAME,ISD,COUNTRY_RES,MTONGUE,FAMILY_INCOME,ENTRY_DT,PHONE_WITH_STD,DTOFBIRTH,STD,PHONE_MOB,CITY_RES,GENDER,RELATION,AGE,INCOME,SEC_SOURCE,HAVEPHOTO,MSTATUS,PHONE_FLAG,INCOMPLETE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,SUBSCRIPTION";
			if($processName=='paidCampaignProcess'){
				$fields .=",YOURINFO,FAMILYINFO,FATHER_INFO,SPOUSE,SIBLING_INFO,JOB_INFO";	
			}
			$jprofileObj  		=new JPROFILE('newjs_masterRep');
			$AgentDetailsObj   	=new AgentAllocationDetails();
                        $mainAdminPoolObj       =new incentive_MAIN_ADMIN_POOL('newjs_masterRep');	

			foreach($profileArr as $key=>$profileid)
			{
				$details			=$jprofileObj->get($profileid,"PROFILEID",$fields);
				$details['PHONE_MOB'] 		=$AgentDetailsObj->phoneNumberCheck($details['PHONE_MOB']);	
				$details['PHONE_WITH_STD'] 	=$AgentDetailsObj->phoneNumberCheck($details['PHONE_WITH_STD']);
				$details['PHONE_ALTERNATE']  	=$AgentDetailsObj->phoneNumberCheck($AgentDetailsObj->getOtherPhoneNums($profileid));
				$details['ENTRY_DT']  	        =date("Y-m-d",JSstrToTime($details['ENTRY_DT']));
	
				if($processName=='failedPaymentInDialer' || $processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='rcbCampaignInDialer'){
					$analyticScore 	=$mainAdminPoolObj->getAnalyticScore($profileid);
					$details['ANALYTIC_SCORE']	=$analyticScore;
				}
				if($processName=='upsellProcessInDialer'){
					$paymentDetailsObj =new BILLING_PAYMENT_DETAIL('newjs_masterRep');
					$paymentDetails =$paymentDetailsObj->getDetails($extraParam);		
					$details['AMOUNT'] =$paymentDetails[0]['AMOUNT'];
				}	
				$profilesDetailArr[$profileid] 	=$details;
			}
			unset($jprofileObj);
			unset($AgentDetailsObj);
			unset($mainAdminPoolObj);
			return $profilesDetailArr;
		}
	}

	public function fetchProfiles($processObj)
	{
		$processName=$processObj->getProcessName();
		if($processName=="DAILY_GHARPAY")
		{
			$paymentCollectObj =new incentive_PAYMENT_COLLECT('newjs_masterRep');			
			$profiles =$paymentCollectObj->getGharpayProfiles();

		}
                else if($processName=="QA_ONLINE")
                {
                        $startDate    =$processObj->getStartDate();          
                        $endDate      =$processObj->getEndDate(); 
                        $paymentDetailObj =new BILLING_PAYMENT_DETAIL('newjs_masterRep');

			// get profile for date range
			$profiles =$paymentDetailObj->getProfilesWithinDateRange($startDate, $endDate);
                        if(count($profiles)>0){
				foreach($profiles as $key=>$value){
					$profileArr[] =$value['PROFILEID'];
				}
				$profileArr =array_unique($profileArr);
				$profileArr =array_values($profileArr);

				// logic to get fresh paid and repeat paid profiles
				$countDetails =$paymentDetailObj->getPaidCountForProfiles($profileArr);	
                                foreach($countDetails as $key=>$data){
                                        $profileid      =$data['PROFILEID'];
                                        $cnt            =$data['CNT'];
                                        if($cnt==1)
                                                $newProfileArr[] =$profileid;
                                        else
                                                $repeatProfileArr[] =$profileid;
                                }
                        }
                        $profiles =array("N"=>$newProfileArr,"R"=>$repeatProfileArr);
                }
                else if($processName=="failedPaymentInDialer" || $processName=="upsellProcessInDialer" || $processName=='rcbCampaignInDialer')
                {
			$agentAllocDetailsObj   =new AgentAllocationDetails();
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
                }
		elseif($processName=='renewalProcessInDialer'){
			$agentAllocDetailsObj   =new AgentAllocationDetails();
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
		}
                elseif($processName=='paidCampaignProcess'){
                        $agentAllocDetailsObj   =new AgentAllocationDetails();
                        $profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
                }
		else if($processName=="SALES_REGULAR")
		{
			$jprofileObj 	=new JPROFILE('newjs_masterRep');
			//$loginDtStart	=date("Y-m-d",time()-3*24*60*60);
			$loginDtStart   =date("Y-m-d",time()-2*24*60*60)." 00:00:00";
			$loginDtEnd	=date("Y-m-d H:i:s",time());	
			$profiles	=$jprofileObj->getLoggedInProfilesForDateRange($loginDtStart, $loginDtEnd);
		}
		else if($processName=="SALES_REGISTRATION")
		{
			$jprofileObj			=new JPROFILE();
			$excludeMtongue			=crmParams::$salesRegIgnoreCommunity;
			$includeCityRes			=crmParams::$salesRegConsiderCity;
			$includeCityStr			="'".@implode("','",$includeCityRes)."'";
			$greaterThanArray['ENTRY_DT']	=date("Y-m-d",strtotime("$today -1 day"))." 14:30:00";
			$lessThanArray['ENTRY_DT']	=date("Y-m-d")." 14:30:00";
			$excludeArray 			=array("PHONE_FLAG"=>"'I'","MTONGUE"=>"$excludeMtongue");
			$valueArray 			=array("INCOMPLETE"=>"N","ISD"=>"'91','0091','+91'","CITY_RES"=>"$includeCityStr","ACTIVATED"=>"'Y'");

						$fields="PROFILEID,USERNAME,GENDER,AGE,SUBSCRIPTION,ENTRY_DT,RELATION,CITY_RES,PHONE_MOB,PHONE_WITH_STD,PINCODE";
						$profiles=$jprofileObj->getArray($valueArray,$excludeArray,$greaterThanArray,$fields,$lessThanArray);
		}
		else if($processName=="FTA_REGULAR")
		{
			$jprofileObj=new JPROFILE();
			$screeningObj=new jsadmin_SCREENING_LOG();
			$date=date("Y-m-d H:i:s",time()-24*60*60);
			$greaterThanArray['ENTRY_DT']=$date;
					$profiles=$screeningObj->getProfilesScreenedAfter($date);
					$fields="PROFILEID,ISD,PHONE_FLAG,ACTIVATED,INCOMPLETE,SOURCE,SEC_SOURCE,PRIVACY,SERIOUSNESS_COUNT,MTONGUE,AGE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,HAVEPHOTO,RELATION,USERNAME,MOB_STATUS,LANDL_STATUS,PHONE_MOB,PHONE_WITH_STD,ENTRY_DT";
					$profilesCount=count($profiles);
					for($j=0;$j<$profilesCount;$j++)
					{	
				$earlierScreened=$screeningObj->earlierScreened($profiles[$j]);
				$details=$jprofileObj->get($profiles[$j],"PROFILEID",$fields);
				if($earlierScreened)
				{	
					$entryDt=$details["ENTRY_DT"];
					$last24hrs=time()-24*60*60;
					$entryDt=strtotime($entryDt);
					if($entryDt>=$last24hrs)
					{
						$finalProfiles[]=$details;
					}
				}
				else
				{
					$finalProfiles[]=$details;
				}
			} 
					return $finalProfiles;
		}
		else if($processName=="FTA_ONE_TIME")
		{
			$jprofileObj=new JPROFILE();
			$date=date("Y-m-d H:i:s",time()-60*24*60*60);
			$greaterThanArray['ENTRY_DT']=$date;
			$fields="PROFILEID,ISD,PHONE_FLAG,ACTIVATED,INCOMPLETE,SOURCE,SEC_SOURCE,PRIVACY,SERIOUSNESS_COUNT,MTONGUE,AGE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,HAVEPHOTO,RELATION,USERNAME,MOB_STATUS,LANDL_STATUS,PHONE_MOB,PHONE_WITH_STD,ENTRY_DT";
			$profiles=$jprofileObj->getArray($valueArray,"",$greaterThanArray,$fields);
		}
		else if($processName=="FTA_CHECK_ELIGIBLE")
		{
			$inDialerObj=new incentive_FTA_IN_DIALER();
			$profiles=$inDialerObj->fetchProfiles();	
			$profilesCount=count($profiles);
			$jprofileObj=new JPROFILE();
			$fields="PROFILEID,ISD,PHONE_FLAG,ACTIVATED,INCOMPLETE,SOURCE,SEC_SOURCE,PRIVACY,SERIOUSNESS_COUNT,MTONGUE,AGE,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,HAVEPHOTO,RELATION,USERNAME,MOB_STATUS,LANDL_STATUS,PHONE_MOB,PHONE_WITH_STD,ENTRY_DT";	
			for($k=0;$k<$profilesCount;$k++)
			{
				$profileid=$profiles[$k]["PROFILEID"];
				$details=$jprofileObj->get($profileid,"PROFILEID",$fields);
				$profile[0]=$details;
				$filteredProfile=$this->filterProfiles($processObj,$profile);
				if(count($filteredProfile)>0)
				{
					$eligible='Y';
					$filteredProfile=$this->getProfilesFTAScore($profile,$processObj);
					$priority=$filteredProfile[0]['PRIORITY'];
				}
				else
				{
					$eligible='N';
					$priority=$profiles[$k]["PRIORITY"];
				}
				
				$dialerEligible=$this->checkDialerEligibility($filteredProfile);
				if($dialerEligible)
					$eligible=$dialerEligible;
				$inDialerObj->updateDialerEligibility($profileid,$eligible,$priority);
			}
		}
		else if($processName=="FTA_DIALER_UPDATE")
		{
			$campaignObj=new incentive_CAMPAIGN();
			$inDialerObj=new incentive_IN_DIALER();
			$campArray=$campaignObj->getCampaigns();	
			$profiles=$inDialerObj->fetchProfiles();
			$profilesCount=count($profiles);
			for($i=0;$i<$profilesCount;$i++)
			{	
				if($profiles['ELIGIBLE']=='N')
					$ignore_array[] = $profiles["PROFILEID"];
				else
					$eligible_array[] = $profiles["PROFILEID"];
			}
			return array($ignore_array,$eligible_array,$campArray);
		}
		else if($processName=="SUGARCRM_LTF")
		{
			$sugarcrmLeadsObj = new sugarcrm_leads('newjs_masterRep');
			$subMethod = $processObj->getSubMethod();

			if($subMethod == "LTF_MOBILE_LEADS")
				$profiles = $sugarcrmLeadsObj->getMobileLeads();
			else if($subMethod == "LTF_OTHER_LEADS")
				$profiles = $sugarcrmLeadsObj->getOtherLeads();
		} else if($processName=="MOBILE_APP_REGISTRATIONS") {
			$jprofileObj = new JPROFILE('newjs_masterRep');
			$mainAdminPoolObj = new incentive_MAIN_ADMIN_POOL('newjs_masterRep');
			$AgentAllocDetailsObj = new AgentAllocationDetails();
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$jprofileAlertsObj = new JprofileAlertsCache('newjs_masterRep');
			// fetch all registrations done 2 days ago.
			$greaterThanArray['ENTRY_DT'] = "'".date("Y-m-d",time() - 3 * 60 * 60 * 24)." 00:00:00"."'";
			$lessThanArray['ENTRY_DT'] = "'".date("Y-m-d",time() - 3 * 60 * 60 * 24)." 23:59:59"."'";
			$valueArray =array("MTONGUE"=>"'10','19','33','27','20','13','28','7','12','6','36','30','34','37'"
				,"SUBSCRIPTION"=>"''","MOB_STATUS"=>"'Y'","ACTIVATED"=>"'Y'","SOURCE"=>"'AndroidApp'");
			$whereCondition = "(GENDER =  'F' OR AGE >=25)";
			$fields="PROFILEID,USERNAME,GENDER,AGE,HAVEPHOTO,PHONE_MOB,EMAIL";
			// retrieve basic profile data
			$profiles=$jprofileObj->getArray($valueArray,'','',$fields,'','','',$greaterThanArray,$lessThanArray,'','',$whereCondition);
			// update data
			foreach($profiles as $key=>$val){
				// get score for all profileid's
				$profiles[$key]['SCORE'] = $mainAdminPoolObj->getScore($val['PROFILEID']);
				if(!$profiles[$key]['SCORE']){
					$profiles[$key]['SCORE'] = 0;
				}
				// check DNC status
				$dnc_status = $AgentAllocDetailsObj->checkDNC(array($val['PHONE_MOB']));
				if($dnc_status['STATUS']){
					$profiles[$key]['DNC'] = 1;
				} else {
					$profiles[$key]['DNC'] = 0;
				}
				// check Alloted status
				if($mainAdminObj->checkIfProfileAlloted($val['PROFILEID'])){
					$profiles[$key]['ALLOTED'] = 'Y';
				} else {
					$profiles[$key]['ALLOTED'] = NULL;
				}
				// Check MEMB_CALLS status
				if($membcalls = $jprofileAlertsObj->fetchMembershipStatus($val['PROFILEID'])){
					$profiles[$key]['MEMB_CALLS'] = $membcalls['MEMB_CALLS'];
				} else {
					$profiles[$key]['MEMB_CALLS'] = NULL;
				}
				// bucket logic
				$gender = $profiles[$key]['GENDER'];
				$age = $profiles[$key]['AGE'];
				$havePhoto = $profiles[$key]['HAVEPHOTO'];
				$alloted = $profiles[$key]['ALLOTED'];
				$membcalls = $profiles[$key]['MEMB_CALLS'];
				// conditions for alloting buckets
				if($gender == 'F' && $age>=25 && $havePhoto == 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 6;
				} elseif($gender == 'F' && $age<25 && $havePhoto == 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 5;
				} elseif($gender == 'M' && $havePhoto == 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 4;
				} elseif($gender == 'F' && $age>=25 && $havePhoto != 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 3;
				} elseif($gender == 'F' && $age<25 && $havePhoto != 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 2;
				} elseif($gender == 'M' && $havePhoto != 'Y' && $alloted == '' && $membcalls == 'S'){
					$profiles[$key]['PRIORITY'] = 1;
				} else {
					$profiles[$key]['PRIORITY'] = 0;
				}
			}
		}
		return $profiles;
	}

	public function getTemporaryProfilesCount($max_date)
	{
		$salesCsvTemp =new incentive_SALES_CSV_DATA_TEMP();	
		$currLatestCnt = $salesCsvTemp->getLatestProfilesCount($max_date);
		return $currLatestCnt;
	}
        
        public function getTemporaryFPProfilesCount($max_date,$process)
	{
		$fpCsvTemp =new incentive_PROCESS_CSV_DATA_TEMP();	
		$currLatestCnt = $fpCsvTemp->getLatestProfilesCount($max_date,$process);
		return $currLatestCnt;
	}
	public function preFilter($processObj, $profiles='')
	{
			$processName=$processObj->getProcessName();
			if($processName=="SALES_REGULAR")
				{
			// Total profiles count
			$max_dt         =$processObj->getEndDate();
			$salesCsvTemp 	=new incentive_SALES_CSV_DATA_TEMP();	
			$info 		=$this->updateSalesLog('TOTAL_PROFILES',$max_dt);

			// Filter profile registered within2 days
			$filter ='REGISTERED_WITHIN_2DAYS';
			$profiles =$salesCsvTemp->fetchProfilesRegisteredWithin2Days();
			if(count($profiles)>0){
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
							$salesCsvTemp->deleteProfilesRegisteredWithin2Days();
				unset($profiles);  
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

			// In Dialer Filter
			$filter ='INDIALER';
			$profiles =$salesCsvTemp->fetchInDialerProfiles();
			if(count($profiles)>0){
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
				$salesCsvTemp->removeInDialerProfiles();
				unset($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

			// Negative Treatment Filter
			$filter ='NEGATIVE_TREATMENT';
			$profiles =$salesCsvTemp->fetchNegativeTreatmentProfiles();
			if(count($profiles)>0){	
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
							$salesCsvTemp->removeNegativeTreatmentProfiles();
				unset($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

			// DO NOT CALL Filter
			$filter ='DO_NOT_CALL';
			$profiles= $salesCsvTemp->fetchDoNotCallProfiles();
			if(count($profiles)>0){	
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
				$salesCsvTemp->removeDoNotCallProfiles();
				unset($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

			// Paid within 30days Filter 
			$filter ='PAID_WITHIN_30DAYS';
			$profiles =$salesCsvTemp->fetchPaidWithin30Days();
			if(count($profiles)>0){
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
				$salesCsvTemp->removePaidWithin30Days();
				unset($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

			// Pre-Allocation Filter
			$filter ='PRE_ALLOCATED';
			$profiles =$salesCsvTemp->fetchPreAllocatedProfiles();
			if(count($profiles)>0){
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);	
				$salesCsvTemp->removePreAllocatedProfiles();
				unset($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);

						//Failed PaymentFilter
			$filter ='FAILED_PAYMENT'; 
			$AgentAllocDetailsObj =new AgentAllocationDetails();
						$profiles =$AgentAllocDetailsObj->fetchNewFailedPaymentEligibleProfiles($processName);
			if(count($profiles)>0){
				$this->salesCsvProfileLog('','','N',$filter,'','','',$profiles);
							$salesCsvTemp->removeProfiles($profiles);
			}
			$info = $this->updateSalesLog($filter,$max_dt, $info[0], $info[1]);
			unset($profiles);
				}
		elseif($processName=="SALES_REGISTRATION")
		{
			$salesCsvTemp =new incentive_SALES_REGISTRATION_CSV_DATA_TEMP();
			$salesCsvTemp->removeDoNotCallProfiles();
			$salesCsvTemp->removeNegativeListProfiles();
			$salesCsvTemp->removeNegativeTreatmentProfiles();
			$salesCsvTemp->removeAllocatedProfiles();
			$salesCsvTemp->removeSalesRegistrationLogProfiles();
		}
		elseif($processName=='failedPaymentInDialer' || $processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='paidCampaignProcess')
		{
                    if($processName=='failedPaymentInDialer'|| $processName == 'renewalProcessInDialer'){
                        $fplogging = true;
                    }
                    
                        if($fplogging == true){
                            $fpCsvTemp 	=new incentive_PROCESS_CSV_DATA_TEMP();	
                            $info 	=$this->updateFPLog('TOTAL_PROFILES', 0,$processName);
                        }
			$profileArr =array();
			$dataArrPool =array();
			$dataArrPoolNew =array();
			foreach($profiles as $key=>$dataArr){
				$profileid =$dataArr['PROFILEID'];
				$dataArrPool[$profileid] =$dataArr;
				$profileArr[] =$profileid;					
			}
			if($processName=='renewalProcessInDialer'){
				$renewalInDialerObj =new incentive_RENEWAL_IN_DIALER('newjs_masterRep');	
	                        $profilesRenewalDialer =$renewalInDialerObj->fetchRenewalDialerProfiles();
	                        if(count($profilesRenewalDialer)>0){
					$profileArr =array_diff($profileArr,$profilesRenewalDialer);
					$profileArr =array_values($profileArr);
                	        }
                                //Update fp temp logs
                                if($fplogging == true){
                                    $filter ='RENEWAL_PROCESS_IN_DIALER';
                                    if(count($profilesRenewalDialer)>0){
                                        $this->fpCsvProfileLog('','','N',$filter,'','','',$profilesRenewalDialer,$processName);
                                        $fpCsvTemp->removeProfiles($profilesRenewalDialer,$processName);
                                        unset($profilesRenewalDialer);
                                    }
                                    $info = $this->updateFPLog($filter,$info[0],$processName);
                                } 
			}
			if($processName!='paidCampaignProcess'){
                           
				$obj= new incentive_DO_NOT_CALL('newjs_masterRep'); 
				$profilesDoNotCall =$obj->getDoNotCallProfiles($profileArr);
				if(count($profilesDoNotCall)>0){
					$profileArr =array_diff($profileArr,$profilesDoNotCall);
					$profileArr =array_values($profileArr);	
				}
                                //Update fp temp logs
                                if($fplogging == true){
                                    $filter ='DO_NOT_CALL';
                                    if(count($profilesDoNotCall)>0){
                                        $this->fpCsvProfileLog('','','N',$filter,'','','',$profilesDoNotCall,$processName);
                                        $fpCsvTemp->removeProfiles($profilesDoNotCall,$processName);
                                        unset($profilesDoNotCall);
                                    }
                                    $info = $this->updateFPLog($filter,$info[0],$processName);
                                } 
				if($fplogging == true)
					$filter ='ALLOCATED';
	                        if(count($profileArr)>0){
        	                        $obj =new incentive_MAIN_ADMIN('newjs_masterRep');
        	                        $profilesAllocated =$obj->getProfilesDetails($profileArr);
        	                        if(count($profilesAllocated)>0){
        	                                foreach($profilesAllocated as $key=>$value){
        	                                        $allocated[] =$value['PROFILEID'];
        	                                }
        	                                $profileArr =array_diff($profileArr,$allocated);
        	                                $profileArr =array_values($profileArr);
                                                // Allocation Filter
                                                if($fplogging == true){
                                                    $this->fpCsvProfileLog('','','N',$filter,'','','',$profilesAllocated,$processName);
                                                    $fpCsvTemp->removeProfiles($allocated,$processName);
                                                    unset($allocated);
                                                }
        	                        }
        	                }
				if($fplogging == true){
					$info = $this->updateFPLog($filter,$info[0],$processName);
				}
			}
			if($fplogging == true)
				$filter ='NEGATIVE_TREATMENT';
			if(count($profileArr)>0){
				$obj =new INCENTIVE_NEGATIVE_TREATMENT_LIST('newjs_masterRep');	
				$profilesNegative =$obj->getNegativeListProfiles($profileArr);
				if(is_array($profilesNegative)){
					$profileArr =array_diff($profileArr,$profilesNegative);
					$profileArr =array_values($profileArr);
				}
                                if($fplogging == true){
                                    // Negative Treatment Filter
                                    if(count($profilesNegative)>0){	
                                        $this->fpCsvProfileLog('','','N',$filter,'','','',$profilesNegative,$processName);
					$fpCsvTemp->removeProfiles($profilesNegative,$processName);
                                        unset($profilesNegative);
                                    }
                                }
			}
			if($fplogging == true)
				$info = $this->updateFPLog($filter, $info[0],$processName);
			/*if(count($profileArr)>0){
				$obj =new incentive_MAIN_ADMIN();
	                        $profilesAllocated =$obj->getProfilesDetails($profileArr);
				if(count($profilesAllocated)>0){
					foreach($profilesAllocated as $key=>$value){
						$allocated[] =$value['PROFILEID'];
					}
		                        $profileArr =array_diff($profileArr,$allocated);
					$profileArr =array_values($profileArr);
					unset($allocated);
				}
			}*/
			if($processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer'){
				if($fplogging == true)
					$filter ='PRE_ALLOCATED';
				if(count($profileArr)>0){
					$obj =new incentive_PROFILE_ALLOCATION_TECH('newjs_masterRep');
		                        $preAllocated =$obj->getAllotedProfiles($profileArr);
					if(is_array($preAllocated)){
		        	                $profileArr =array_diff($profileArr,$preAllocated);
						$profileArr =array_values($profileArr);
					}
                                         // Pre-Allocation Filter
                                        if($fplogging == true && count($preAllocated)>0){
                                            $this->fpCsvProfileLog('','','N',$filter,'','','',$preAllocated,$processName);	
                                            $fpCsvTemp->removeProfiles($preAllocated,$processName);
                                            
                                        }
                                        unset($preAllocated);
				}
                                if($fplogging == true)
	                                $info = $this->updateFPLog($filter,$info[0], $processName);
			}
			if($processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='paidCampaignProcess'){
				if($fplogging == true)
					$filter ='UNSUBSCRIBED';
				if(count($profileArr)>0){
					$obj =new JprofileAlertsCache('newjs_masterRep');
		                        $profilesUnsubscribed =$obj->getUnsubscribedProfiles($profileArr);
					if(is_array($profilesUnsubscribed)){
		        	                $profileArr =array_diff($profileArr,$profilesUnsubscribed);
						$profileArr =array_values($profileArr);
					}
                                        // Unsubscribed Filter
                                        if($fplogging == true && count($profilesUnsubscribed)>0){
                                            $this->fpCsvProfileLog('','','N',$filter,'','','',$profilesUnsubscribed,$processName);
                                            $fpCsvTemp->removeProfiles($profilesUnsubscribed,$processName);
                                        }
                                        unset($profilesUnsubscribed);
				}
				if($fplogging == true)
					$info = $this->updateFPLog($filter,$info[0],$processName);
			}
			if(count($profileArr)>0){
				foreach($profileArr as $key=>$profileid)
					$dataArrPoolNew[] =$dataArrPool[$profileid];
			}
			return $dataArrPoolNew;
		}
	}
	public function filterForJprofile($processObj,$fieldsArr)
	{
				$processName	=$processObj->getProcessName();
		$profileid 	=$fieldsArr['PROFILEID'];
		$username	=$fieldsArr['USERNAME'];	

				if(count($fieldsArr)==0)
						return;
		if($processName=='SALES_REGISTRATION'){
			if($fieldsArr['GENDER']=='M' && $fieldsArr['AGE']<=23)
				return;
		}
		else{
			if($fieldsArr['SUBSCRIPTION']!=''){
				$filter	 	='SUBSCRIPTION_PURCHASED';
				$filterVal 	=$fieldsArr['SUBSCRIPTION'];
			}
			elseif($fieldsArr['PHONE_FLAG']=="I"){
				$filter		='INVALID_PHONE';
				$filterVal	=$fieldsArr['PHONE_FLAG'];
			}
			elseif($fieldsArr['INCOMPLETE']!='N'){
								$filter         ='INCOMPLETE';
								$filterVal      =$fieldsArr['INCOMPLETE'];
			}
			elseif($fieldsArr['ACTIVATED']!='Y'){
								$filter         ='INACTIVE';
								$filterVal      =$fieldsArr['ACTIVATED'];
			}
			elseif($fieldsArr['GENDER']=='M' && $fieldsArr['AGE']<=23){
								$filter         ='MAILE_WITHIN_24AGE';
								$filterVal      =$fieldsArr['AGE'];
			}
		}
				if(!$fieldsArr['PHONE_MOB'] && !$fieldsArr['PHONE_WITH_STD'] && !$fieldsArr['PHONE_ALTERNATE']){
			$filter ='PHONENO_NOT_EXIST';
			$filterVal ='Y';	
		}
		if($filter){
			$this->salesCsvProfileLog($profileid,$username,'N',$filter,$filterVal);
			return;
		}
				return true;
	}
	
	public function filterProfiles($processObj,$profiles,&$filter)
	{
		global $smarty;
		$processName=$processObj->getProcessName();
		if($processName=="FTA_REGULAR"||$processName=="FTA_CHECK_ELIGIBLE"||$processName=="FTA_ONE_TIME")
		{
			$doNotCallObj=new incentive_DO_NOT_CALL();
			$negativeTreatmentObj=new incentive_NEGATIVE_TREATMENT_LIST();
			$tempCsvObj=new incentive_TEMP_CSV_FTA_TECH();
			$profilesCount=count($profiles);
			for($i=0;$i<$profilesCount;$i++)
			{	
				//Incomplete Check
				if($profiles[$i]["INCOMPLETE"]!='N')
					continue;
				//Activated Check	
				if($profiles[$i]["ACTIVATED"]!='Y')
					continue;
				//searchable check
				if($profiles[$i]["PRIVACY"]=='C')
					continue;
				//Indian Check
				$isd=$profiles[$i]["ISD"];
				if(!$this->isIndianNo($isd))
					continue;
				//invalid Phone Check
				if($profiles[$i]["PHONE_FLAG"]=="I")
					continue;
				//ignore Profiles registered by LTF
				if($profiles[$i]["SOURCE"]=="onoffreg")
					continue;
				//secondary source not called
				if($profiles[$i]["SEC_SOURCE"]=="C")
					continue;
				//mtongue check
				$excludeMtongue=array(1,3,16,17,31);
				if(in_array($profiles[$i]["MTONGUE"],$excludeMtongue))
					continue;
				//male age check
				if($profiles[$i]['GENDER']=="M" && $profiles[$i]["AGE"]<=23)
					continue;
				//paid check
				if((strstr($profiles[$i]['SUBSCRIPTION'],"F")!="")||(strstr($profiles[$i]['SUBSCRIPTION'],"D")!=""))
								continue;
				//DO Not Call Check
				if($doNotCallObj->checkProfileDNC($profiles[$i]["PROFILEID"])>0)
					continue;
				//Negative Treatment List
				if($negativeTreatmentObj->isFlagOutboundCall($profiles[$i]["PROFILEID"],'N'))
					continue;
				//Searchable Check	
				if($negativeTreatmentObj->isFlagViewable($profiles[$i]["PROFILEID"],'N'))
					continue;
				$filteredProfiles[]=$profiles[$i];
			}
		}
		elseif($processName=="SALES_REGULAR")
		{
			$AgentAllocDetailsObj	=new AgentAllocationDetails();
			$mainAdminPoolObj	=new incentive_MAIN_ADMIN_POOL('newjs_masterRep');	

			/* profile suffix count array stored in process Obj,reducess the process to get total records in Data limit check */
						$campaignCntArr    =$processObj->getCampaignCntArr();
			$dataLimit         =$processObj->getLimit();
			$max_dt 	   =$processObj->getEndDate();
			foreach($profiles as $profileid=>$dataArr){
				$username     =$dataArr['USERNAME'];

				// Data Limit exceed check
				$campaignName =$this->getCampaignName($profileid,$username,$dataArr['MTONGUE'],$dataArr['CITY_RES'],$dataArr['ISD'],$dataArr['COUNTRY_RES']);
				if(!$campaignName){
					if($dataArr['ENTRY_DT']==$max_dt) $filter['campaignUndefinedCnt_L']++;
					$filter['campaignUndefinedCnt']++;
					continue;
				}
				$campaignNameNew =$this->getCampaignName($profileid,$username,$dataArr['MTONGUE'],$dataArr['CITY_RES'],$dataArr['ISD'],$dataArr['COUNTRY_RES'],true);	
				$campaignProfilesCnt =$campaignCntArr[$campaignName];
				if($campaignProfilesCnt>=$dataLimit){
					if($dataArr['ENTRY_DT']==$max_dt) $filter['dataLimitExceedCnt_L']++;
						$filter['dataLimitExceedCnt']++;
					$this->salesCsvProfileLog($profileid,$username,'N','DATA_LIMIT_EXCEED','Y');
					continue;
				}

				// Jprofile filter 
				$jprofileEligible =$this->filterForJprofile($processObj, $dataArr);
				if(!$jprofileEligible){
					if($dataArr['ENTRY_DT']==$max_dt) $filter['jprofileCnt_L']++;
					$filter['jprofileCnt']++;
					continue;
				}					
				// DNC No. check filter
				$phoneNumStack =array("PHONE1"=>"$dataArr[PHONE_MOB]","PHONE2"=>"$dataArr[PHONE_ALTERNATE]","PHONE3"=>"$dataArr[PHONE_WITH_STD]");
				$DNCArray =$AgentAllocDetailsObj->checkDNC($phoneNumStack);
				$isDNC    =$DNCArray['STATUS'];
				if($isDNC){
	                                // Optin-check
	                                $optinStatus =$AgentAllocDetailsObj->isOptinProfile($profileid);
	                                if(!$optinStatus){
	                                        if($dataArr['ENTRY_DT']==$max_dt) $filter['nonOptinProfileCnt_L']++;
	                                        $filter['nonOptinProfileCnt']++;
	                                        $this->salesCsvProfileLog($profileid,$username,'N','NON_OPTIN','Y');
	                                        continue;
	                                }
				}
				foreach($phoneNumStack as $key=>$value){
					if($value && !$phone1)
						$phone1 =$value;
					elseif($value && !$phone2)
						$phone2 =$value;
					if($phone1 && $phone2)
						break;
				}
				$dataArr['PHONE1']=$phone1;
				$dataArr['PHONE2']=$phone2;

				// FTO profile check filter
				$income 	=$dataArr['INCOME'];
				$familyIncome 	=$dataArr['FAMILY_INCOME'];
				$incomeBasedEligibility =$this->premiumIncomeBasedCheck($income,$familyIncome,$dataArr['ENTRY_DT']);
				if(!$incomeBasedEligibility){
					if($dataArr['ENTRY_DT']==$max_dt) $filter['premiumIncomeCnt_L']++;
					$filter['premiumIncomeCnt']++;
					$this->salesCsvProfileLog($profileid,$username,'N','PREMIUM_INCOME',"$income,$familyIncome");
					continue;
				}					
				// Disposition/Alerts based filter
				$allotedAgent =$AgentAllocDetailsObj->getAllotedAgent($profileid);
				if(!$allotedAgent){
					$profileAlerts =$this->profileAlertsCheck($profileid,$username);
					if(!$profileAlerts){
						if($dataArr['ENTRY_DT']==$max_dt) $filter['alertsCnt_L']++;
						$filter['alertsCnt']++;
						continue;
					}
					$dispositionValidity =$this->checkDispositionValidity($profileid,$username);
					if(!$dispositionValidity){
						if($dataArr['ENTRY_DT']==$max_dt) $filter['dispositionValidityCnt_L']++;
						$filter['dispositionValidityCnt']++;							
						continue;
					}
				}
				// Analytic score based filter
				$analyticScore =$mainAdminPoolObj->getAnalyticScore($profileid);			
				if($analyticScore<1 || $analyticScore>100){
					if($dataArr['ENTRY_DT']==$max_dt) $filter['scoreValidityCnt_L']++;
					$filter['scoreValidityCnt']++;
					$this->salesCsvProfileLog($profileid,$username,'N','ANALYTIC_SCORE_ZERO',$analyticScore);
					continue;
				}
                                
								// filtered profile stored to check campaign limit
				$campaignCntArr[$campaignName] +=1;
				$dataArr['ALLOTED_TO'] 		=$allotedAgent;
				$dataArr['ANALYTIC_SCORE'] 	=$analyticScore;
				$dataArr['CAMPAIGN_NAME']	=$campaignName;	
				$dataArr['CAMPAIGN_NAME_NEW']	=$campaignNameNew;
				$filteredProfiles[] 		=$dataArr;
			}
			$processObj->setCampaignCntArr($campaignCntArr);
		}
		elseif($processName=="SALES_REGISTRATION")
		{
			$AgentDetailsObj =new AgentAllocationDetails();
			foreach($profiles as $key=>$dataArr){

				// Jprofile filter
				$dataArr['PHONE_ALTERNATE'] =$AgentDetailsObj->getOtherPhoneNums($dataArr['PROFILEID']);
								$jprofileEligible =$this->filterForJprofile($processObj, $dataArr);
								if(!$jprofileEligible)
										continue;
				// Phone number filter
				$mobile1	=$AgentDetailsObj->phoneNumberCheck($dataArr['PHONE_MOB']);
				$mobile2	=$AgentDetailsObj->phoneNumberCheck($dataArr['PHONE_ALTERNATE']);
				$landline	=$AgentDetailsObj->phoneNumberCheck($dataArr['PHONE_WITH_STD']);
				if(!$mobile1 && !$mobile2 && !$landline)
					continue;	
				$filteredProfiles[] =$dataArr;
			}
		}
		else if($processName=='failedPaymentInDialer' || $processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='paidCampaignProcess' || $processName=='rcbCampaignInDialer'){
			$method 		=$processObj->getMethod();	
                        $AgentAllocDetailsObj   =new AgentAllocationDetails();
			$southIndianCommunity	=crmParams::$southIndianCommunity;
                        if($processName == 'failedPaymentInDialer' || $processName =='renewalProcessInDialer'){
                            $fplogging=true;
                        }
                        foreach($profiles as $profileid=>$dataArr){
				if(!$profileid)
					continue;

				if ($processName == 'rcbCampaignInDialer') {
                	$allotedAgent = $AgentAllocDetailsObj->getAllotedAgent($profileid);
			$subscription =$dataArr['SUBSCRIPTION'];
                	if ((strstr($subscription, "F") !== false) || (strstr($subscription, "D") !==  false) || $allotedAgent) {
                    		continue;
                	}
                }
                
				if($processName!='rcbCampaignInDialer'){
	                                if($dataArr["ACTIVATED"]!='Y'){
                                            if($fplogging==true){
                                                $filter['notActivatedCnt']++;
                                                $this->fpCsvProfileLog($profileid,'','N','NOT_ACTIVATED','Y','','','',$processName);
                                            }
                                            continue;
                                        }
	                                if($dataArr["PHONE_FLAG"]=="I"){
                                            if($fplogging==true){
                                                $filter['invalidPhoneCnt']++;
                                                $this->fpCsvProfileLog($profileid,'','N','INVALID_PHONE','Y','','','',$processName);
                                            }   
                                            continue;
                                        }
				}
				if($method=='NEW_FAILED_PAYMENT'){
	                                if($dataArr['GENDER']=="M" && $dataArr["AGE"]<24){
                                            if($fplogging==true){
                                                $filter['maleAgeCnt']++;
                                                $this->fpCsvProfileLog($profileid,'','N','MALE_AGE','Y','','','',$processName);
                                            }   
                                            continue;
                                        }
				}
				if($method=='RENEWAL'){
					$lastLoginDt 	=$dataArr['LAST_LOGIN_DT'];
					$checkDay 	=JSstrToTime(date("Y-m-d",time()-14*24*60*60));
					if(JSstrToTime($lastLoginDt)<$checkDay){
                                            if($fplogging==true){
                                                $filter['lastLoginCnt']++;
                                                $this->fpCsvProfileLog($profileid,'','N','LAST_LOGIN','Y','','','',$processName);
                                            }
                                            continue;	
                                        }
				}
				elseif($method=='PAID_CAMPAIGN'){
					// income >35lakh and above
					$income =$dataArr['INCOME'];
					$familyIncome =$dataArr['FAMILY_INCOME'];
					if($income>=24 || $familyIncome>=24)
						continue;

					// South Indian languages and others
					$mtongueVal =$dataArr['MTONGUE'];
					if(in_array($mtongueVal,$southIndianCommunity))
						continue;		

					// Profile length>700
	                                $profileLength =strlen($dataArr['YOURINFO'])+strlen($dataArr['FAMILYINFO'])+strlen($dataArr['FATHER_INFO'])+strlen($dataArr['SPOUSE'])+strlen($dataArr['SIBLING_INFO'])+strlen($dataArr['JOB_INFO']);
					if($profileLength>700)
						continue;		
				}
				// NRI Check
				$isdVal =$dataArr['ISD'];			
				$isIndian =$this->isIndianNo($isdVal);
				if(!$isIndian){	
                                    if($fplogging==true){
                                        $filter['nriCnt']++;
                                        $this->fpCsvProfileLog($profileid,'','N','NRI','Y','','','',$processName);
                                    }    
                                    continue;
                                }

                                // DNC No. check filter
				if(!$dataArr['PHONE_MOB'] && !$dataArr['PHONE_ALTERNATE'] && !$dataArr['PHONE_WITH_STD']){
                                    if($fplogging==true){
                                        $filter['noPhoneCnt']++;
                                        $this->fpCsvProfileLog($profileid,'','N','NO_PHONE','Y','','','',$processName);
                                    }    
                                    continue;
                                }
					
                                $phoneNumStack =array("PHONE1"=>"$dataArr[PHONE_MOB]","PHONE2"=>"$dataArr[PHONE_ALTERNATE]","PHONE3"=>"$dataArr[PHONE_WITH_STD]");
                                $DNCArray =$AgentAllocDetailsObj->checkDNC($phoneNumStack);
                                $isDNC    =$DNCArray['STATUS'];
                                if($isDNC){
                                        // Optin-check
                                        $optinStatus =$AgentAllocDetailsObj->isOptinProfile($profileid);
                                        if(!$optinStatus){
                                            if($fplogging==true){
                                                $filter['nonOptinProfileCnt']++;
                                            }
                                            $this->fpCsvProfileLog($profileid,'','N','NON_OPTIN','Y','','','',$processName);
                                            continue;
                                        }
                                }
                                foreach($phoneNumStack as $key=>$value){
                                        if($value && !$phone1)
                                                $phone1 =$value;
                                        elseif($value && !$phone2)
                                                $phone2 =$value;
                                        if($phone1 && $phone2)
                                                break;
                                }
				if(!$phone1 && !$phone2){
                                    if($fplogging==true){
                                        $filter['noPhoneExistsCnt']++;
                                    }
                                    $this->fpCsvProfileLog($profileid,'','N','NO_PHONE_EXISTS','Y','','','',$processName);
                                    continue;
                                }
                                $dataArr['PHONE1']=$phone1;
                                $dataArr['PHONE2']=$phone2;
				$filteredProfiles[] =$dataArr;
			}
		}
		return $filteredProfiles;
	}
	public function getProfilesFTAScore($profiles,$processObj)
	{
		global $smarty;
		$screeningObj=new jsadmin_SCREENING_LOG();
		$searchQueryObj=new MIS_SEARCHQUERY();
		$profilesCount=count($profiles);
		for($k=0;$k<$profilesCount;$k++)
		{
			$profileid=$profiles[$k]["PROFILEID"];
			$lastLoginDt=$profiles[$k]["LAST_LOGIN_DT"];
			$havePhoto=$profiles[$k]["HAVEPHOTO"];
			if($processObj->getProcessName()=="FTA_REGULAR")
				$screeningCount=1;
			else
				$screeningCount=$screeningObj->getScreeningCount($profileid);
			$today=date("Y-m-d",time());
			$today=strtotime($today);
			$lastLoginDt=strtotime($lastLoginDt);
			$diff=$today-$lastLoginDt;
			$intervalDays=$diff/86400;
			$dbName=JsDbSharding::getShardNo($profileid);
			$contactsObj=new newjs_CONTACTS($dbName);
			$where['TYPE']="A";
			$where['SENDER']=$profileid;	
			$eoi_accepted_arr=$contactsObj->getContactsCount($where);
			$eoi_accepted=$eoi_accepted_arr[0]['COUNT'];
			$where['TYPE']="D";
			$eoi_declined_arr=$contactsObj->getContactsCount($where);
			$eoi_declined=$eoi_declined_arr[0]['COUNT'];
			$where['TYPE']="I";
			$eoi_waiting_arr=$contactsObj->getContactsCount($where);
			$eoi_waiting=$eoi_waiting_arr[0]['COUNT'];
			$where['TYPE']="E";
			$eoi_canceled_arr=$contactsObj->getContactsCount($where);
			$eoi_canceled=$eoi_canceled_arr[0]['COUNT'];
			$eoi_sent=$eoi_accepted+$eoi_waiting+$eoi_declined+$eoi_canceled;
			$profiles[$k]["EOI_WAITING"]=$eoi_waiting;
			$profiles[$k]["EOI_DECLINED"]=$eoi_declined;
			$profiles[$k]["EOI_SENT"]=$eoi_sent;
			$profilePercent=profile_percent($profileid,'','1');
			$Days10Before=date("Y-m-d h:i:s",time()-10*24*60*60);
			$performedSearchLast10Days=$searchQueryObj->performedSearchInLast10Days($profileid,$Days10Before);
			
			//if screened first time
			if($screeningCount==1)
				$score=6;
			else
				$score=0;
			//photo check
			if($havePhoto=="Y")
				$score+=1;
			//if sent EOI greater than 0
			if($eoi_sent>=1)
				$score+=1;
			//if logged in last 10 days
			if($intervalDays<=10)
				$score+=1;
			//profile completeness check
			if($profilePercent>60)
				$score+=1;
			//performed search in last 10 days
			if($performedSearchLast10Days>0)
				$score+=1;
			$profiles[$k]["PRIORITY"]=$score;
		}
		return $profiles;
	}
	public function saveProfileSet($processObj,$profiles)
	{
		$processName=$processObj->getProcessName();
			if($processName=="FTA_REGULAR"||$processName=="FTA_ONE_TIME")
			{
			$ftaDataObj=new incentive_FTA_CSV_DATA();
			$jprofileContactObj= new ProfileContact();
			$viewContactsLogObj=new jsadmin_VIEW_CONTACTS_LOG();
			$jpViewsObj=new NEWJS_JP_NTIMES();
			$in_dialerObj=new incentive_FTA_IN_DIALER();
			$profilesCount=count($profiles);	
			for($i=0;$i<$profilesCount;$i++)
			{
				$profileid=$profiles[$i]["PROFILEID"];
				$relation=$profiles[$i]["RELATION"];
				$username=$profiles[$i]["USERNAME"];
				$regEntryDate=$profiles[$i]["ENTRY_DT"];
				$srs_cnt=$profiles[$i]["SERIOUSNESS_COUNT"];
				$regDate=date("d/m/y",strtotime($regEntryDate));
				$gender=$profiles[$i]["GENDER"];
				$postedBy=FieldMap::getFieldLabel("relation",$relation);
				$havePhoto=$profiles[$i]["HAVEPHOTO"];
				if($havePhoto=='Y')
					$photo="Yes";
				elseif($havePhoto=='U')
					$photo="Scrn";
				else
					$photo="No";
				$valueArray['PROFILEID']=$profileid;
				$altContactInfo=$jprofileContactObj->getArray($valueArray,'','',"ALT_MOBILE,ALT_MOB_STATUS");
				$mobile2=$altContactInfo[0]['ALT_MOBILE'];
				$mobile2=$this->phoneNumberCheck($mobile2);	
				if($profiles[$i]["MOB_STATUS"]=="Y"||$profiles[$i]["LANDL_STATUS"]=="Y"||$altContactInfo[0]['ALT_MOB_STATUS']=='Y')
					$phoneVerified="Y";
				else
					$phoneVerified="N";
				$priority=$profiles[$i]["PRIORITY"];
				if($priority=="")
					$priority=0;
				$mobile1=$profiles[$i]["PHONE_MOB"];
				$landline=$profiles[$i]["PHONE_WITH_STD"];
				$mobile1=$this->phoneNumberCheck($mobile1);
				$landline=$this->phoneNumberCheck($landline);
				
				$eoi_sent=0;
				$dbName=JsDbSharding::getShardNo($profileid);
				list($login_freq_perc,$loginCount,$ageInMonths,$ageOfRegistration)=$this->calculateLoginFrequency($profileid,$regDate,$dbName);
				$photoRequestObj=new NEWJS_PHOTO_REQUEST($dbName);
				$photo_request=$photoRequestObj->getPhotoRequestReceived($profileid);
				
				$eoi_waiting=$profiles[$i]["EOI_WAITING"];
				$eoi_declined=$profiles[$i]["EOI_DECLINED"];
				$eoi_sent=$profiles[$i]["EOI_SENT"];
				
				$tot_con_viewed=$viewContactsLogObj->totalContactsViewed($profileid);
				if($login_freq_perc=="")
					$login_freq_perc=0;	
				if($eoi_waiting=="")
					$eoi_waiting=0;
				if($eoi_declined=="")
					$eoi_declined=0;
				if($photo_request=="")
					$photo_request=0;
				unset($where);
				$eoi_rcvd_vs_viewed="";
				$where['RECEIVER']=$profileid;
				$where['TYPE']='I';
				$messageLogObj = new newjs_MESSAGE_LOG($dbName);
				$eoiRecieved = $messageLogObj->getMessageLogCount($where);
				$profileViews=$jpViewsObj->getProfileViews($profileid);
				if($eoiRecieved[0]["COUNT"]>0 && $profileViews>0)
				{
					$eoi_rcvd_vs_viewed=($eoiRecieved[0]["COUNT"]/$profileViews)*100;
					$eoi_rcvd_vs_viewed=round($eoi_rcvd_vs_viewed);
				}
				if($eoi_rcvd_vs_viewed=="")
					$eoi_rcvd_vs_viewed=0;
				$eligibleForRB=$this->rbEligibilityFlag('N',$eoi_sent,count($eoi_accepted),$loginCount,$ageOfRegistration,$total_con_viewed,$ageInMonths,$srs_cnt);
				if($eligibleForRB=="Eligible,If photo is Uploaded")
					$eligible='Y';
				else
					$eligible='N';
				$ftaDataObj->insertProfile($profileid,$username,$regEntryDate,$gender,$postedBy,$photo,$phoneVerified,$login_freq_perc,$eoi_waiting,$eoi_declined,$eoi_rcvd_vs_viewed,$photo_request,$eligible,$mobile1,$mobile2,$landline,$mobile1,$mobile2,$landline,$priority);
				$in_dialerObj->insert($profileid);
			}
		}
		elseif($processName=="paidCampaignProcess"){
			$salesCampaignTables    =crmParams::$salesCampaignTables;
                       	$salesCampaign          =crmParams::$salesCampaign;
                        $campaignName           =$salesCampaign[$processName];
                        $tablesName             =$salesCampaignTables[$processName];
                        $salesCsvDataObj        =new $tablesName;
			$leadIdSuffix           =$processObj->getLeadIdSuffix();
			$serviceObj		=new billing_SERVICES('newjs_masterRep');
			$leadId         	=$campaignName.$leadIdSuffix;	
			$dialerDialStatus	=1;
			$serviceArr		=$serviceObj->getServiceDetailsArr('SERVICEID,NAME,ADDON');
			$profilesCount          =count($profiles);	
                        for($i=0;$i<$profilesCount;$i++){
                                $dataArr        =$profiles[$i];
                                $profileid      =$dataArr['PROFILEID'];
                                if(!$profileid)
                                        continue;
				$serviceId      =$dataArr['SERVICEID'];
				if(strstr($serviceId,'L'))
					continue;	
                                $username       =$dataArr['USERNAME'];
				$gender         =FieldMap::getFieldLabel('gender',$dataArr['GENDER']);
				$paymentDate    =$dataArr['PAYMENT_ENTRY_DT'];
				$serviceIdArr	=explode(",", $serviceId);
				foreach($serviceIdArr as $key=>$serviceid){
					$name =$serviceArr[$serviceid]['NAME'];
					$addon =$serviceArr[$serviceid]['ADDON'];				
					if($addon=='Y')
						$addoneArr[] =$name;
					else
						$mainMemArr[] =$name;
					if(strstr($serviceid,'NCP')){
						$addoneArr[] ='Response Booster';
						$addoneArr[] ='Featured Profile';
					}
				}
				if(is_array($mainMemArr))
					$membership =implode(", ",$mainMemArr);
				if(is_array($addoneArr))
					$addon =implode(", ",$addoneArr);

                                $salesCsvDataObj->insertProfile($profileid,$dialerDialStatus,$dataArr['USERNAME'],$dataArr['PHONE1'],$dataArr['PHONE2'],$gender,$membership,$addon,$paymentDate,$leadId);
			}
		}
		elseif($processName=="SALES_REGULAR" || $processName=='failedPaymentInDialer' || $processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='rcbCampaignInDialer')
		{
			if($processName=="SALES_REGULAR"){
				$salesRegularCampaignTables  =crmParams::$salesRegularCampaignTables;
				$inDialerCampaign	=crmParams::$inDialerCampaign;
				$inDialerCampaignNewArr =crmParams::$inDialerCampaignNewArr;
				$salesCsvDataTempObj 	=new incentive_SALES_CSV_DATA_TEMP();
				$inDialerObj            =new incentive_IN_DIALER();
				$inDialerNewOutboundObj =new incentive_IN_DIALER_NEW();
	                        $salesRegularRangeValue =crmParams::$salesRegularValueRange;
                        	$scoreRange2    	=$salesRegularRangeValue['SCORE2'];
				$nonAutoCampaign	=crmParams::$nonAutoCampaign;	

			}
			else if($processName=='failedPaymentInDialer' || $processName=='upsellProcessInDialer' || $processName=='renewalProcessInDialer' || $processName=='rcbCampaignInDialer'){
				$servicesObj 		=new billing_SERVICES();
				//$userplaneObj		=new userplane_recentusers();
		                $jsCommonObj 		=new JsCommon();
				$salesCampaignTables	=crmParams::$salesCampaignTables;
	                        $salesCampaign          =crmParams::$salesCampaign;
        	                $campaignName           =$salesCampaign[$processName];
				$tablesName     	=$salesCampaignTables[$processName];
				$salesCsvDataObj	=new $tablesName;
				$callTimeArr		=$processObj->getProfiles();
				$AgentAllocDetailsObj   =new AgentAllocationDetails();
			}
			$method			=$processObj->getMethod();
			$leadIdSuffix           =$processObj->getLeadIdSuffix();		
			$vdDiscountObj    	=new billing_VARIABLE_DISCOUNT('newjs_masterRep');
			$renewalDiscountObj	=new billing_RENEWAL_DISCOUNT('newjs_masterRep');
			$purchaseObj            =new BILLING_PURCHASES();
			$profilesCount		=count($profiles);
			for($i=0;$i<$profilesCount;$i++)
			{
				$dataArr 	=$profiles[$i];
				$profileid	=$dataArr['PROFILEID'];
				if(!$profileid)
					continue;
				$username	=$dataArr['USERNAME'];
				$score		=$dataArr['ANALYTIC_SCORE'];
				if($method=='RENEWAL'){
                                        $renewalDiscountArr  =$renewalDiscountObj->getDiscount($profileid);
                                        $vdDiscount          =$renewalDiscountArr['DISCOUNT'];
					$expiryDate	     =$dataArr['EXPIRY_DT'];	
					$everPaid	     ='Yes';	 	
				}
				else{
					$vdDiscountArr	=$vdDiscountObj->getDiscount($profileid);
					$vdDiscount	=$vdDiscountArr[$profileid]['DISCOUNT'];	
					$everPaid	=$purchaseObj->getPaidStatus($profileid);
					if($everPaid)
						$everPaid='Yes';
					else
						$everPaid ='No';
				}
				if($dataArr['HAVEPHOTO'])
					$havePhoto='Yes';
				else
					$havePhoto='No';
				if($processName=='failedPaymentInDialer' || $processName=='renewalProcessInDialer'){
					$allotedAgent =$AgentAllocDetailsObj->getAllotedAgent($profileid);
					$dataArr['ALLOTED_TO'] =$allotedAgent;	
				}
				$dialerPriority 	=$this->fetchDialerPriority($dataArr['ALLOTED_TO'],$vdDiscount,$score,$processName);
				$dialerDialStatus 	=$this->fetchDialerStatus($dataArr['ALLOTED_TO'],$vdDiscount,$score,$processName);
				$relation 		=FieldMap::getFieldLabel('relation',$dataArr['RELATION']);
				$gender   		=FieldMap::getFieldLabel('gender',$dataArr['GENDER']);
				$mstatus  		=FieldMap::getFieldLabel('mstatus',$dataArr['MSTATUS']);

				// insert in regular sales csv table
				if($processName=="SALES_REGULAR"){
                                        $campaignName           =$dataArr['CAMPAIGN_NAME'];
                                        $campaignNameNew        =$dataArr['CAMPAIGN_NAME_NEW'];
					
                                        // non auto campaign ,.ie, mumbai pune nri will go here
                                        if(in_array("$campaignName",$nonAutoCampaign)) {
						$dialerEligible ='Y';
                                                if($dataArr['ALLOTED_TO']=='')
                                                        $dialerDialStatus =1;
                                                else
                                                        $dialerDialStatus=2;
					}
                                        // auto campaign - noida, delhi, delhi-auto will go here
                                        else {
                                            // defines the start and end of score range
                                            $scoreRangeBase = $salesRegularRangeValue['SCORE71'];
                                            $scoreRangeMax = $salesRegularRangeValue['SCORE90'];
                                            
                                            $dialerEligible = 'N';
                                            // initially, we assume that the profile will not enter the auto table
                                            $dialerEligibleNew ='N';
                                            $dialerDialStatusNew =0;
                                            
                                            // the profile is now eligible to for calling, validating further for auto table
                                            if($score >= $scoreRangeBase && $score <= $scoreRangeMax) {
                                             // valid for auto table, hence setting making ineligible in new table, and eligible in auto table
                                                $dialerEligible = 'N';
                                                $dialerEligibleNew = 'Y';
                                                
                                                $dialerDialStatusNew = $dialerDialStatus;
                                                $dialerDialStatus = 0;
                                             } 
                                             else {
                                                    // logic - if profileid % 11 == 1, do not call
                                                    if($profileid % 11 == 1) {
                                                        $dialerDialStatus = 0;
                                                    }
                                                    else {
                                                        // verify if the call is really eligible for calling
                                                        if($dialerDialStatus == 1) {
                                                            $dialerEligible = 'Y';
                                                        }
                                                    }
                                             }
                                        }
					$leadId         =$campaignName.$leadIdSuffix;	
					$leadId 	=str_replace('pune','mumbai',$leadId);
					$tablesName 	=$salesRegularCampaignTables[$campaignName];
	                                $salesCsvDataObj=new $tablesName;
	                                $salesCsvDataObj->insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$dataArr['ALLOTED_TO'],$vdDiscount,$dataArr['LAST_LOGIN_DT'],$dataArr['PHONE1'],$dataArr['PHONE2'],$havePhoto,$dataArr['DTOFBIRTH'],$mstatus,$everPaid,$gender,$relation,$leadId);
					if(in_array("$campaignNameNew",$inDialerCampaignNewArr)){
						$leadId         =$campaignNameNew.$leadIdSuffix;
						$tablesName     =$salesRegularCampaignTables[$campaignNameNew];
						$salesCsvDataObj=new $tablesName;	
						$salesCsvDataObj->insertProfile($profileid,$dialerPriority,$score,$dialerDialStatusNew,$dataArr['ALLOTED_TO'],$vdDiscount,$dataArr['LAST_LOGIN_DT'],$dataArr['PHONE1'],$dataArr['PHONE2'],$havePhoto,$dataArr['DTOFBIRTH'],$mstatus,$everPaid,$gender,$relation,$leadId);
					}
					
				}
				else if($processName=="renewalProcessInDialer"){
					$campaignType	=$this->getCampaignType($processName, $dataArr['MTONGUE']);
					if($campaignType=='OB_RENEWAL_MAH'){
						$campaignName 	=$salesCampaign[$campaignType];	
						$leadId 	=$campaignName.$leadIdSuffix;	
					}
					else{
						$leadId         =$campaignName.$leadIdSuffix;
					}
					$salesCsvDataObj->insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$dataArr['ALLOTED_TO'],$vdDiscount,$dataArr['LAST_LOGIN_DT'],$dataArr['PHONE1'],$dataArr['PHONE2'],$havePhoto,$dataArr['DTOFBIRTH'],$mstatus,$everPaid,$gender,$relation,$leadId,$campaignType,$expiryDate);
				}
				else if($processName=="rcbCampaignInDialer"){
					$country        =FieldMap::getFieldLabel('country',$dataArr['COUNTRY_RES']);
					$callTime	=$callTimeArr[$profileid]['PREFERRED_START_TIME_IST'];
					$leadId =$campaignName.$leadIdSuffix;
					//$source =$campaignName;
					$source = $callTimeArr[$profileid]['CALLBACK_SOURCE'];
                                        //$csvDateTime =$processObj->getStartDate();
					$csvDateTime =$processObj->getEndDate();
                                        if($profileid>0)
                                                $salesCsvDataObj->insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$dataArr['ALLOTED_TO'],$vdDiscount,$dataArr['LAST_LOGIN_DT'],$dataArr['PHONE1'],$dataArr['PHONE2'],$havePhoto,$dataArr['DTOFBIRTH'],$mstatus,$everPaid,$gender,$relation,$leadId,$csvDateTime,$username,$country,$source,$callTime);
                                         $rcbInDialerLog =new incentive_RCB_LOG();
                                         $rcbInDialerLog->insertData($profileid, $csvDateTime);
				}
				else if($processName=="failedPaymentInDialer" || $processName=="upsellProcessInDialer"){
					$country	=FieldMap::getFieldLabel('country',$dataArr['COUNTRY_RES']);
					//$onlineStatus =$userplaneObj->isOnline($profileid);
			                $onlineStatus 	=$jsCommonObj->getOnlineStatus($profileid);
					$services	=$dataArr['SERVICE_SELECTED'];
					$discount	=$dataArr['DISCOUNT'];
					if($processName=="failedPaymentInDialer"){
						$fpEntryDt	=$dataArr['FP_ENTRY_DT'];
						$netAmount	=$dataArr['LAST_AMOUNT_TRIED'];
						$source		=$dataArr['SOURCE'];
						$webLead	=$dataArr['WEB_LEAD'];
					}
					else if($processName=="upsellProcessInDialer"){
						$fpEntryDt      =$dataArr['PAYMENT_ENTRY_DT'];
						$netAmount      =$dataArr['AMOUNT'];	
					}
					if($services){
						$serviceNamesArr =$servicesObj->getServices($services);	
						$serviceSelected =implode(",",$serviceNamesArr);						
					}	
					if($onlineStatus)
						$onlineStatus ='Y';
					else
						$onlineStatus ='N';

                                        /*if($webLead && $processName=="failedPaymentInDialer"){
                                                $leadId         =$webLead.$leadIdSuffix;
                                                $source         =$webLead;
                                        }
                                        else*/
                                        $leadId         =$campaignName.$leadIdSuffix;
		
					$csvDateTime	=$processObj->getStartDate();				
					if($profileid>0)
						$salesCsvDataObj->insertProfile($profileid,$dialerPriority,$score,$dialerDialStatus,$dataArr['ALLOTED_TO'],$vdDiscount,$dataArr['LAST_LOGIN_DT'],$dataArr['PHONE1'],$dataArr['PHONE2'],$havePhoto,$dataArr['DTOFBIRTH'],$mstatus,$everPaid,$gender,$relation,$leadId,$csvDateTime,$username,$serviceSelected,$fpEntryDt,$discount,$onlineStatus,$netAmount,$country,$source);
                                        if($processName=="failedPaymentInDialer"){
                                                $fpInDialerLog =new incentive_FP_CSV_LOG();
                                                $fpInDialerLog->insertData($profileid, $csvDateTime);
                                        }
				}
				// Insert into Dialer table and logging
				if($processName=="SALES_REGULAR"){
					$this->salesCsvProfileLog($profileid,$username,'Y','','',$campaignName,$dialerDialStatus);
					if(in_array("$campaignName", $inDialerCampaign))		
						$inDialerObj->insertProfile($profileid,$dialerPriority,$campaignName,$dialerEligible);
					if(in_array("$campaignNameNew",$inDialerCampaignNewArr)){
						$inDialerNewOutboundObj->insertProfile($profileid,$dialerPriority,$campaignNameNew,$dialerEligibleNew);		
					}
				}
				elseif($processName=="renewalProcessInDialer"){
					$renewalInDialerObj =new incentive_RENEWAL_IN_DIALER(); 	
					$renewalInDialerObj->insertProfile($profileid,$dialerPriority,$campaignType);
				}
				unset($salesCsvDataObj);
				unset($salesCsvDataTempObj);
				unset($vdDiscountObj);
				unset($purchaseObj);
				unset($inDialerObj);
				unset($renewalInDialerObj);
				unset($webLead);
			}
		}
		elseif($processName=="DAILY_GHARPAY")
		{
			$crmCsvTables    	=crmParams::$crmCsvTables;
			$billingObj		=new billing_SERVICES();
                        $profilesCount          =count($profiles);
                        for($i=0;$i<$profilesCount;$i++)
                        {
                                $dataArr        =$profiles[$i];
				$pref_date_val 	=date("d-M-y");
				$quantity	=1;
				$amount		=$dataArr['AMOUNT'];
				$prefixName	=$dataArr['PREFIX_NAME'];
				$addonService 	=$dataArr["ADDON_SERVICEID"];
				$serviceStr	=$dataArr["MAIN_SER"];
		
                        	if($dataArr["PREF_TIME"] !="0000-00-00 00:00:00")
					$prefTime =date("d-M-y",strtotime($dataArr["PREF_TIME"]));
                                if(!$prefixName)
                                        $prefixName ='Mr.';
	                        if($addonService)
                                	$serviceStr .=",".$addonService;
				if($serviceStr){
					$serviceNameArr	=$billingObj->getServices($serviceStr);
	                        	$serviceNameStr =@implode(",",$serviceNameArr);
				}
                                $phoneArr       =array($dataArr["PHONE_MOB"],$dataArr["PHONE_RES"]);
                                $contactNumber  =@implode(",",$phoneArr);
                                $address        =ereg_replace("\r\n|\n\r",",",$dataArr['ADDRESS']); 
                                $landmark       =ereg_replace("\r\n|\n\r",",",$dataArr['LANDMARK']);
                                $comments       =ereg_replace("\r\n|\n\r",",",$dataArr['COMMENTS']);

				if($amount>0){
					$tableName =$crmCsvTables[$processName];
					$csvTableObj =new $tableName;
                                        $csvDate =$processObj->getCurDate();
                                        $csvTableObj->insertProfile($prefixName, $dataArr['NAME'], $contactNumber, $address, $landmark, $dataArr['EMAIL'], $dataArr['PIN'], $dataArr['CITY'], $dataArr['USERNAME'], $amount, $prefTime, $serviceNameStr, $quantity, $dataArr['ENTRYBY'], $csvDate);
					$idArr[] =$dataArr['ID'];
				}
				unset($serviceStr);
				unset($serviceNameArr);
			}
			if(count($idArr)>0){
				// insert data in LOG
				$logObj =new incentive_LOG();
				$logObj->addRecord($idArr);			

				// update PAYMENT_COLLECT
				$paymentCollectObj =new incentive_PAYMENT_COLLECT();				
				$paymentCollectObj->updateRecordForIds($idArr);

				// insert dat in INVOICE_TRACK	
				$invoiceTrackObj =new incentive_INVOICE_TRACK();
				$invoiceTrackObj->addRecord($idArr,'cron_script');
			}
		}
                elseif($processName=="QA_ONLINE")
                {
                        $crmCsvTables           =crmParams::$crmCsvTables;
                        $profilesCount          =count($profiles);
			$type			=$processObj->getState();
                        for($i=0;$i<$profilesCount;$i++)
                        {
                                $dataArr        =$profiles[$i];
                                $profileid      =$dataArr['PROFILEID'];
                                $email    	=$dataArr['EMAIL'];
                                $phoneMob   	=$dataArr["PHONE_MOB"];
                                $phoneRes     	=$dataArr["PHONE_WITH_STD"];
				$score		=$dataArr['SCORE'];

                                $tableName =$crmCsvTables[$processName];
                                $csvTableObj =new $tableName;
                                $csvDate =$processObj->getCurDate();
                                $csvTableObj->insertProfile($profileid, $email, $phoneMob, $phoneRes, $score, $type, $csvDate);
                         }
		}
		elseif($processName=="SALES_REGISTRATION")
		{
			$salesRegLogObj		=new incentive_NEW_REGISTRATION_SALES_LOG();
			$salesRegCsvDataObj 	=new incentive_SALES_REGISTRATION_CSV_DATA();
			$crmUtilityObj 		=new crmUtility();
			$pincodeMappingObj	=new newjs_PINCODE_MAPPING();
						$profilesCount 		=count($profiles);
						for($i=0; $i<$profilesCount; $i++)
						{
								$dataArr        =$profiles[$i];
								$profileid      =$dataArr['PROFILEID'];
				$entryDt	=$crmUtilityObj->fetchIST($dataArr['ENTRY_DT']);
						$cityRes        =FieldMap::getFieldLabel('city',$dataArr['CITY_RES']);
						$relation       =FieldMap::getFieldLabel('relation',$dataArr['RELATION']);
						$pincode        =$dataArr['PINCODE'];

				// locality logic
				$locality ='';	
				if(strstr($cityRes,'Delhi')){
					if($pincode)
						$locality =$pincodeMappingObj->getLocalityForPincode($pincode);
				}
				else
					$locality = $cityRes;

				// Store data in table
				$salesRegCsvDataObj->insertProfile($profileid,$dataArr['USERNAME'],$entryDt,$dataArr['GENDER'],$relation,$cityRes,$locality,$dataArr['PHONE_MOB'],$dataArr['PHONE_WITH_STD'],$dataArr['PHONE_ALTERNATE']);

				// Insert into log table
				$salesRegLogObj->insertProfile($profileid);
			}
		}
		elseif($processName=="SUGARCRM_LTF")
		{
			$sugarcrmObj = new incentive_SUGARCRM_LTF_CSV_DATA();		
			$pid = $processObj->getIdAllot();

			$MT = csvFields::$csvMotherTongueArr;
			$DNC = csvFields::$csvDNCvalues;
			$mt = crmParams::$mother_tongue;

			for($i=0; $i<count($mt); $i++)
			{
			if(in_array($profiles['mother_tongue_val'],$mt[$i]))
			{
				$csv_val = $i + 1;
				$csv_type = $MT[$csv_val];
				break;
			}
			}

		// default condition for Hind-Others
		if(!$profiles['mother_tongue_val'])
			$csv_type =$MT[6];

			$dnc_val = $profiles['isDNC'];
		$dnc = $DNC[$dnc_val];
		$profiles['password']=PasswordHashFunctions::createHash($profiles['password']);
		if($csv_type)
				$sugarcrmObj->insertProfile($pid,$profiles['lead_name'],$profiles['age'],$profiles['gender'],$profiles['height'],$profiles['marital_status'],$profiles['religion'],$profiles['mother_tongue'],$profiles['caste'],$profiles['education'],$profiles['occupation'],$profiles['income'],$profiles['manglik'],$profiles['phone_no1'],$profiles['phone_no2'],$profiles['campaign_source'],$profiles['lead_source'],$profiles['enquirer_name'],$profiles['email'],$profiles['campaign_username'],$profiles['campaign_description'],$profiles['campaign_newspaper'],$profiles['campaign_newspaper_date'],$profiles['campaign_edition'],$profiles['campaign_emailid'],$profiles['campaign_mobile'],$profiles['priority'],$profiles['username'],$profiles['password'],$profiles['ent_date'],$dnc,$csv_type);
		}
		elseif ($processName=="MOBILE_APP_REGISTRATIONS") {
			$mobRegCsvObj = new incentive_SALES_CSV_DATA_MOBILE_APP_REGISTRATIONS();
			$profilesCount = count($profiles);
			for($i=0;$i<$profilesCount;$i++){
				$phoneMob = $profiles[$i]['PHONE_MOB'];
				$username = $profiles[$i]['USERNAME']; 
				$email = $profiles[$i]['EMAIL'];
				$profileid = $profiles[$i]['PROFILEID'];
				$score = $profiles[$i]['SCORE'];
				$priority = $profiles[$i]['PRIORITY'];
				$dnc = $profiles[$i]['DNC'];
				$mobRegCsvObj->insertProfile($phoneMob,$username,$email,$profileid,$score,$priority,$dnc);
			}
		}

	}
	public function generateCSV($processName,$date,$csvType='')
	{
		$fNamesArr	=csvFields::$csvName;
		$fName 		=$fNamesArr[$processName];
		$fileName	=JsConstants::$docRoot."/uploads/csv_files/$fName.dat";
		$fp		=fopen($fileName,"w+");
		if(!$fp)
			die("no file pointer");

		$lead_id	=csvFields::$csvLeadId[$processName];
		$dialStatus	=1;
		$csvDataObj	=$this->getDataObj($processName);

		// Add file header code
                $fileHeaderArr  =csvFields::$csvFileHeader;
                $fileHeader	=$fileHeaderArr[$processName];
                if($fileHeader){
			$fileHeader."\n";
                        fwrite($fp,$fileHeader);
		}

		if($processName=='PHONE_DIALER')
			$dialStatusReq =1;
		if($processName=='sugarcrmLtf')
		{
			if($csvType=='DNC_MobileLead')
				$csvData  =  $csvDataObj->getMobileLeadData($date);
			elseif($csvType){
				$typeArr = explode("_", $csvType);
				$MT = csvFields::$csvMotherTongueArr;
				if(in_array("$typeArr[1]",$MT))				
					$csvData  =  $csvDataObj->getData($date, $csvType);
			}
			echo $sugarLtfHeader ="LEAD ID|LEAD NAME|AGE|GENDER|HEIGHT|MARITAL STATUS|RELIGION|MOTHER TONGUE|CASTE|EDUCATION|OCCUPATION|INCOME|MANGLIK|PHONE_NO1|PHONE_NO2|CAMPAIGN SOURCE|LEAD SOURCE|ENQUIRER NAME|EMAIL|CAMPAIGN USERNAME|CAMPAIGN DESCRIPTION|CAMPAIGN NEWSPAPER|CAMPAIGN NEWSPAPER DATE|CAMPAIGN EDITION|CAMPAIGN EMAILID|CAMPAIGN MOBILE|PRIORITY|USERNAME|PASSWORD|ENTRY_DATE|\n";
			fwrite($fp,$sugarLtfHeader);
		} elseif($processName=='MOBILE_APP_REGISTRATIONS' || $processName=='QA_ONLINE'){
			$csvData = $csvDataObj->getData($date,$csvType);
		}
		else
			$csvData	=$csvDataObj->getData($date);

		/*if($processName=="failedPaymentInDialer"){
			$fpInDialerLog =new incentive_FP_CSV_LOG();
			if(count($csvData)>0){
				foreach($csvData as $key=>$value){
					$fpInDialerLog->insertData($value['PROFILEID'],$value['DATE_FP']);	
				}
			}	
		}*/
		
		$csvPhoneFieldsArr =csvFields::$csvPhoneFieldsArr; 
		$csvDateFieldsArr  =csvFields::$csvDateFieldsArr;
		$csvRemoveFieldsArr=csvFields::$csvRemoveFieldsArr;

		if(is_array($csvData))
		{
			foreach($csvData as $row => $value)
			{
				if($lead_id!='')
					$line="$lead_id";
				foreach($value as $key=>$field)
				{
					if(in_array($key,$csvRemoveFieldsArr))
						continue;
					if(in_array($key,$csvPhoneFieldsArr) && $field)
						$field ='0'.$field;
					if(in_array($key,$csvDateFieldsArr) && $processName!='sugarcrmLtf'){
						$field =$this->fetchIST($field);
						$field =date("d/m/y",JSstrToTime($field));
					}
					if($line!='')
						$line.="|"."$field";	
					else
						$line.="$field";	
				}
				if($lead_id || $dialStatusReq)
					$line.="|"."$dialStatus"."|";
				else
					$line.="|";
				echo $line.="\r\n";	
				fwrite($fp,$line);
				unset($line);
			}
		}
		else if($processName!="failedPaymentInDialer")
			successfullDie("No Data Available For This Date !!!");
	}
	public function getDataObj($processName)		
	{
		if($processName=="ftaRegular")
			$csvDataObj=new incentive_FTA_CSV_DATA('newjs_masterRep');
		elseif($processName=="PHONE_DIALER")
			$csvDataObj = new incentive_PHONE_OPS_DIALER_DATA('newjs_masterRep');
		elseif($processName=="salesRegularNoida")
			$csvDataObj=new incentive_SALES_CSV_DATA_NOIDA('newjs_masterRep');
                elseif($processName=="salesRegularNoidaNew")
                        $csvDataObj=new incentive_SALES_CSV_DATA_NOIDA_NEW('newjs_masterRep');
		elseif($processName=="salesRegularDelhi")
			$csvDataObj=new incentive_SALES_CSV_DATA_DELHI('newjs_masterRep');
		elseif($processName=="salesRegularMumbai")
			$csvDataObj=new incentive_SALES_CSV_DATA_MUMBAI('newjs_masterRep');
		elseif($processName=="salesRegularPune")
			$csvDataObj=new incentive_SALES_CSV_DATA_PUNE('newjs_masterRep');
		elseif($processName=="salesRegularNri")	
			$csvDataObj=new incentive_SALES_CSV_DATA_NRI('newjs_masterRep');
		elseif($processName=="salesRegistration")
			$csvDataObj=new incentive_SALES_REGISTRATION_CSV_DATA('newjs_masterRep');
		elseif($processName=="sugarcrmLtf")
			$csvDataObj=new incentive_SUGARCRM_LTF_CSV_DATA('newjs_masterRep');
		elseif($processName=="MOBILE_APP_REGISTRATIONS")
			$csvDataObj=new incentive_SALES_CSV_DATA_MOBILE_APP_REGISTRATIONS('newjs_masterRep');
                elseif($processName=="failedPaymentInDialer")
                        $csvDataObj=new incentive_SALES_CSV_DATA_FAILED_PAYMENT();
                elseif($processName=="upsellProcessInDialer")
                        $csvDataObj=new incentive_SALES_CSV_DATA_UPSELL('newjs_masterRep');
		elseif($processName=="renewalProcessInDialer")
			$csvDataObj=new incentive_SALES_CSV_DATA_RENEWAL('newjs_masterRep');
                elseif($processName=="DAILY_GHARPAY")
                        $csvDataObj=new incentive_GHARPAY_CSV_DATA('newjs_masterRep');
                elseif($processName=="QA_ONLINE")
                        $csvDataObj=new incentive_QA_ONLINE_CSV_DATA('newjs_masterRep');
                elseif($processName == "VDImpactReport")
                        $csvDataObj = new billing_VARIABLE_DISCOUNT_REPORT('newjs_masterRep');
                else
			die("Not a Process !!");
		return $csvDataObj;
	}
	public function isIndianNo($num)
	{
		 if($num && ($num==91 || $num=='0091' || $num=='+91' || $num=='091'))
			 return 1;
		 else
			 return 0;
	}
	public function rbEligibilityFlag($photo,$eoiCount,$acceptance,$loginCount,$ageOfRegistration,$contactViewCount,$actualAge,$SERIOUSNESS_COUNT,$ftoState)
	{
		$loginFrequency=$loginCount/$ageOfRegistration;
		$starProfile=$contactViewCount/pow($actualAge,0.7);
		if($ftoState==FTOStateTypes::FTO_ELIGIBLE||$ftoState==FTOStateTypes::FTO_ACTIVE||$ftoState==FTOStateTypes::DUPLICATE)
			$rbEligible="Not Eligible";
		elseif($starProfile>=3)
			$rbEligible="Eligible";
		elseif($SERIOUSNESS_COUNT>1)
		{
			if($photo=='Y')
				$rbEligible="Eligible";
			else
				$rbEligible="Eligible,If photo is Uploaded";
		}
		elseif($eoiCount==0)
		{
			if($photo=='Y')
				$rbEligible="Eligible";
			else
				$rbEligible="Eligible,If photo is Uploaded";
		}
		elseif($eoiCount>=1 && $acceptance <=4 && $loginFrequency < 0.25)
		{
			if($photo=='Y')
				$rbEligible="Eligible";
			else
				$rbEligible="Eligible,If photo is Uploaded";
		}
		else
			$rbEligible="Not Eligible";	
		return $rbEligible;
	}
	public function checkDialerEligibility($profile)
	{
		$priority=$profile[0]['PRIORITY'];
		$entryDt=$profile[0]['ENTRY_DT'];
		if($priority==5)
		{
			$eligible="N";
			return $eligible;
		}
		$today=date("Y-m-d",time());
			$today=strtotime($today);
			$entryDt=strtotime($entryDt);
			$diff=$today-$entryDt;
			$intervalDays=$diff/86400;
		if($intervalDays==6||$intervalDays==16||$intervalDays==31||$intervalDays==61||$intervalDays==91)
			$eligible="Y";
		return $eligible;
	}	
	
	public function phoneNumberCheck($phoneNumber)
		{
				$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
				$phoneNumber    =ltrim($phoneNumber,0);
				if(!is_numeric($phoneNumber))
						return false;
				if(strlen($phoneNumber)!=10)
						return false;
				return $phoneNumber;
		}
		public function calculateLoginFrequency($profileid,$regDate,$dbName)
		{	
		$loginHistoryObj=new NEWJS_LOGIN_HISTORY($dbName);
		$interval=date('Y-m-d',time()-90*24*60*60);
		$loginCount=$loginHistoryObj->getLoginCount($profileid,$interval);
			
				list($b_dd,$b_mm,$b_yy) = explode("/",$regDate);
				$regDate = mktime(0,0,0,$b_mm,$b_dd,$b_yy);
				$days = (time()-$regDate);
				$diff = ceil($days/(24*60*60)); // find the number of days a user has been registered with us
				if ($diff >= 90)
			$diff = 90;
		$login_freq_perc=$loginCount/$diff*100;
		$ageInMonths=round($diff*12/365,2);
		$login_freq_perc=round($login_freq_perc);	
		return array($login_freq_perc,$loginCount,$ageInMonths,$diff);
	}
	public function fetchLargeFileData()
	{
		$largeFileObj =new incentive_LARGE_FILE('newjs_masterRep');	
		$resultArr =$largeFileObj->getLargeFileData();
		return $resultArr;	
	}
	public function getCampaignType($processName,$mtongue){

		$renewalSouthCommunity 	=crmParams::$renewalSouthCommunity;
		$campaignNames		=crmParams::$campaignNames;	

		if($processName=='renewalProcessInDialer'){
			if(in_array($mtongue, $renewalSouthCommunity))	
				$campaignType =$campaignNames['renewalMah'];
			else
				$campaignType =$campaignNames['renewal'];
		}
		return $campaignType;
	}
	public function getCampaignName($profileid,$username,$mtongue,$city,$isd,$country,$campaignNewFlag='')
	{
		$considerCommunity 	=crmParams::$salesRegularConsiderCommunity;
		$ignoreCommunity 	=crmParams::$salesRegularIgnoreCommunity;
		$puneCity 		=crmParams::$salesRegularPuneCity;
		$delhiCity 		=crmParams::$salesRegularDelhiCity;
		$nriCountry 		=crmParams::$salesRegularNriCountry;
		$salesRegularCampaign	=crmParams::$salesRegularCampaign;
		$salesRegularCommunityNewOutbound =crmParams::$salesRegularCommunityNewOutbound;

		if($mtongue==1)
			$filter ='MTONGUE';
		else{
			$indianNo =$this->isIndianNo($isd);
			if($indianNo){
							if(in_array($mtongue,$considerCommunity) && in_array($city,$puneCity))
							$campaignId = 3;
							elseif(in_array($mtongue,$considerCommunity))
							$campaignId = 2;
							elseif(!in_array($mtongue,$ignoreCommunity) && in_array($city,$delhiCity))
							$campaignId = 5;
							elseif(!in_array($mtongue,$ignoreCommunity))
							$campaignId = 1;
							else
							$filter='MTONGUE';

					                if($campaignNewFlag && in_array($mtongue,$salesRegularCommunityNewOutbound))
                        					$campaignId =6;

			}
			elseif(in_array($country,$nriCountry))
				$campaignId = 4;
			else
				$filter='COUNTRY';
		}
		if($filter){
			if($filter=='MTONGUE')
				$filterVal =$mtongue;
			elseif($filter='COUNTRY')
				$filterVal =$country;		
			$this->salesCsvProfileLog($profileid,$username,'N',$filter,$filterVal);	
			unset($filter);
			return;
		}
		$campaign =$salesRegularCampaign[$campaignId];
		return $campaign;
	}
	function fetchCampaignCntArr()
	{
		$salesRegularCampaign   	=crmParams::$salesRegularCampaign;
		$salesRegularCampaignTables  	=crmParams::$salesRegularCampaignTables;
		
		foreach($salesRegularCampaign as $key=>$campaign){
			$tableName 		=$salesRegularCampaignTables[$campaign];
			$salesCsvData		=new $tableName();	
			$totalCnt 		=$salesCsvData->getCampaignRecordsCnt();
			$campaignCntArr[$campaign] =$totalCnt;
		}	
		return $campaignCntArr;
	}
	public function premiumIncomeBasedCheck($income,$familyIncome,$regEntryDt)
	{
		return true;
		$premiumIncome  =crmParams::$premiumIncome;
		$today		=date('Y-m-d',time());
		$regEntryDtArr	=@explode(" ",$regEntryDt);
		$regEntryDt     =$regEntryDtArr[0];

		if(in_array("$income",$premiumIncome) || in_array("$familyIncome",$premiumIncome)){
			$profileReg3Dt=date('Y-m-d',JSstrToTime($today. ' - 3 day'));
			if(JSstrToTime($regEntryDt)<=JSstrToTime($profileReg3Dt))
				$eligibleProfile=1;
		}
		else{
			//$profileReg2Dt=date('Y-m-d',JSstrToTime($today. ' - 2 day'));
			//if(JSstrToTime($regEntryDt)<=JSstrToTime($profileReg2Dt))
			$eligibleProfile=1;
		}
		if($eligibleProfile)
			return true;
		return;
	}
	public function checkDispositionValidity($profileid,$username)
	{
		$excl_d_dt	=JSstrToTime(date('Y-m-d',time()-(30-1)*86400));
		$excl_dnc_dt	=JSstrToTime(date('Y-m-d',time()-(30-1)*86400));
		$excl_cf_dt	=JSstrToTime(date('Y-m-d',time()-(7-1)*86400));
		$excl_ni_dt	=JSstrToTime(date('Y-m-d',time()-(7-1)*86400));

		$historyObj	=new incentive_HISTORY('newjs_masterRep');
		$details 	=$historyObj->getLastDispositionDetails($profileid,'ENTRY_DT,DISPOSITION');
		$entryDt 	=JSstrToTime($details['ENTRY_DT']);			
		$disposition 	=$details['DISPOSITION'];

		if($entryDt>=$excl_cf_dt && $disposition=='CF') 
			$filter ='DISPOSITION_COMPLAIN_FEEDBACK';
		elseif($disposition=='D' && $entryDt>=$excl_d_dt) 
			$filter ='DISPOSITION_DELETE_PROFILE';	
		elseif($disposition=='DNC' && $entryDt>=$excl_dnc_dt) 
			$filter ='DISPOSITION_DO_NOT_CALL';
		elseif($disposition=='NI' && $entryDt>=$excl_ni_dt)
			$filter	='DISPOSITION_NOT_INTERESTED';		
		if($filter){
			$this->salesCsvProfileLog($profileid,$username,'N',$filter,$disposition);
			return;
		}	
		return true;		
	}
	public function fetchDialerPriority($allotedTo,$vdDiscount,$score,$processName)
	{
		if($processName=="SALES_REGULAR" || $processName=='failedPaymentInDialer' || $processName=='renewalProcessInDialer' || $processName=='rcbCampaignInDialer') {
			 if($allotedTo=='')
			 {
				 if($score>=81 && $score<=100)
					 $priority='2';
				 elseif($score>=41 && $score<=80)
					 $priority='1';
				 else
					 $priority='0';
			 }
			 else
				 $priority='0';
		}
		else
		{
			if($processName=='upsellProcessInDialer')
				$priority='6';
			elseif($allotedTo=='' && $vdDiscount && $score>=1 && $score<=100)
				$priority='6';
			elseif( $allotedTo=='' && !$vdDiscount){
				$priority =$this->fetchDialerPriorityForScore($score);
			}
			elseif($allotedTo){
				if($score>=1 && $score <=100)
					$priority='0';
			}
		}
		return $priority;
	}
	public function fetchDialerPriorityForScore($score)
	{
		if($score>=81 && $score<=100)
			$priority='5';
		elseif($score>=61 && $score<=80)
			$priority='4';
		elseif($score>=41 && $score<=60)
			$priority='3';
		elseif($score>=21 and $score<=40)
			$priority='2';
		elseif($score>=11 and $score<=20)
			$priority='1';
		elseif($score>=1 and $score<=10)
			$priority='0';
		return $priority;
	}
	public function fetchDialerStatus($allotedTo,$vdDiscount,$score,$processName)
	{
		if($processName=='upsellProcessInDialer' || $processName=='rcbCampaignInDialer')
			$dial_status=1;
		elseif($processName=='SALES_REGULAR'){
			$salesRegularRangeValue =crmParams::$salesRegularValueRange;
			$scoreRange1 	=$salesRegularRangeValue['SCORE1'];
			$scoreRange2 	=$salesRegularRangeValue['SCORE2'];
			$scoreRange3 	=$salesRegularRangeValue['SCORE3'];
			$discountRange1 =$salesRegularRangeValue['DISCOUNT1'];
			$discountRange2 =$salesRegularRangeValue['DISCOUNT2'];

			if($score>=$scoreRange1 and $score<$scoreRange2){
				$discount =$vdDiscount; 
				if($discount>=$discountRange1 && $discount<=$discountRange2){
					$dial_status=1;
				}
			}
			elseif($score>=$scoreRange2 && $score<=$scoreRange3)
				$dial_status=1;
			if($allotedTo=='' && $dial_status==1)
				$dial_status=1;
			else
				$dial_status = '2';			
		}	
		else{
			if($allotedTo=='')
				$dial_status = '1';
			else
				$dial_status = '2';
		}
		return $dial_status;
	}
	public function profileAlertsCheck($profileid,$username)
	{
		$jprofileAlertsObj =new JprofileAlertsCache('newjs_masterRep');
		$alerts 	=$jprofileAlertsObj->fetchMembershipStatus($profileid);
		$memCall 	=$alerts['MEMB_CALLS'];
		$offerCall 	=$alerts['OFFER_CALLS'];
		if($memCall=='U'){
			$this->salesCsvProfileLog($profileid,$username,'N','ALERT_MEMBERSHIP_CALL',$memCall);
			return;
		}
		if($offerCall=='U'){
			$this->salesCsvProfileLog($profileid,$username,'N','ALERT_OFFER_CALL',$offerCall);
			return;
		}
		return true;
	}
	
	public function getDetailedValues($pid)  // pid = profileid
	{
		global $app_list_strings;
		$sugarcrmLeadsCstmObj = new sugarcrm_leads_cstm('newjs_masterRep');
		$res = $sugarcrmLeadsCstmObj->getDetails($pid);
				
		if(!$res['age_c'])
		{
			if($res['date_birth_c']!='0000-00-00')       
				 $attribute[$pid]['dob'] = $res['date_birth_c'];
			if($attribute[$pid]['dob'])		     
				 $attribute[$pid]['age'] = getAge($attribute[$pid]['dob']);
		}
		else
			$attribute[$pid]['age'] = $res['age_c'];
			
		$gender_val = $res['gender_c'];
		$attribute[$pid]['gender'] = $app_list_strings['gender_list'][$gender_val];
		$attribute[$pid]['gender_val'] =$gender_val;
	
		$marital_status_val = $res['marital_status_c'];
		$attribute[$pid]['marital_status'] = $app_list_strings['Mstatus'][$marital_status_val];

		$religion_val = $res['religion_c'];
		$attribute[$pid]['religion'] = $app_list_strings['Religion'][$religion_val];

			$attribute[$pid]['mother_tongue_val'] = $res['mother_tongue_c'];
		$attribute[$pid]['mother_tongue'] = $app_list_strings['Mtongue'][$attribute[$pid]['mother_tongue_val']];

		$caste_val = $res['caste_c'];
		$attribute[$pid]['caste'] = $app_list_strings['Caste'][$caste_val];

			$education_val = $res['education_c'];
		$attribute[$pid]['education'] = $app_list_strings['Education'][$education_val];

			$occupation_val = $res['occupation_c'];
		$attribute[$pid]['occupation'] = $app_list_strings['Occupation'][$occupation_val];

			$income_val = $res['income_c'];
		$attribute[$pid]['income'] = $app_list_strings['Income'][$income_val];

		$manglik_val = $res['manglik_c'];
		$attribute[$pid]['manglik'] = $app_list_strings['Manglik_list'][$manglik_val];

			$campaign_source_val = $res['source_c'];
		$attribute[$pid]['campaign_source'] = $app_list_strings['source_dom'][$campaign_source_val];

		$attribute[$pid]['enq_mobile'] = $res['enquirer_mobile_no_c'];
		$attribute[$pid]['enq_landline'] = $res['enquirer_landline_c'];
		$attribute[$pid]['std_enquirer'] = $res['std_enquirer_c'];
		$attribute[$pid]['std_lead'] = $res['std_c'];

		$attribute[$pid]['score'] = $res['score_c'];
		$attribute[$pid]['username'] = $res['jsprofileid_c'];

		$height_val = $res['height_c'];
		$heightObj = new NEWJS_HEIGHT('newjs_masterRep');	
		$attribute[$pid]['height'] = $heightObj->getHeightLabel($height_val);

		$emailAddressIdObj = new sugarcrm_email_addr_bean_rel('newjs_masterRep');	
		$attribute[$pid]['email_address_id'] = $emailAddressIdObj->getEmailAddressID($pid);
		$emailAddressObj = new sugarcrm_email_addresses('newjs_masterRep');	
		$attribute[$pid]['email'] = $emailAddressObj->getEmailAddress($attribute[$pid]['email_address_id']);

		$leadsObj = new sugarcrm_leads('newjs_masterRep');	
		$detail = $leadsObj->getLeadDetailById($pid);	

		$attribute[$pid]['lead_mobile'] = $detail['phone_mobile'];
		$attribute[$pid]['lead_landline'] = $detail['phone_home'];				
			$attribute[$pid]['enquirer_name'] = $detail['assistant'];
		$attribute[$pid]['lead_name'] = $detail['last_name'];
		$attribute[$pid]['ent_date'] = $detail['date_entered'];
		$attribute[$pid]['campaign_id'] = $detail['campaign_id'];
		$lead_source_val = $detail['lead_source'];
		$attribute[$pid]['lead_source'] = $app_list_strings['lead_source_list'][$lead_source_val];
		$attribute[$pid]['status'] = $detail['status'];

		$campaignObj = new sugarcrm_campaigns('newjs_masterRep');	
		$info = $campaignObj->getInfo($detail['campaign_id']);				
			$attribute[$pid]['campaign_username'] = $info['name'];
			$attribute[$pid]['campaign_description'] = trim($info['content']);

			$campaignCstmObj = new sugarcrm_campaigns_cstm('newjs_masterRep');	
			$info = $campaignCstmObj->getInfo($detail['campaign_id']);
			$campaign_newspaper_val = $info['newspaper_c'];
		$attribute[$pid]['campaign_newspaper'] = $app_list_strings['type_lead'][$campaign_newspaper_val];
		$attribute[$pid]['campaign_newspaper_date'] = $info['edition_c'];
		$campaign_edition_val = $info['newspaper_edition_c'];
		$attribute[$pid]['campaign_edition'] = $app_list_strings['newspaper_edition_list'][$campaign_edition_val];
		$attribute[$pid]['campaign_emailid'] = $info['email_id_c'];
		$attribute[$pid]['campaign_mobile'] = $info['mobile_no_c'];

		$attribute[$pid]['password'] = '';

		return $attribute[$pid];
	}


	public function getPhoneValues($attribute, $pid)  // pid = profileid
	{
		$AgentDetailsObj = new AgentAllocationDetails();
		$phoneNumArray = array();

		$phoneNumArray['PHONE1'] = $AgentDetailsObj->phoneNumberCheck($attribute[$pid]['enq_mobile']);
		$phoneNumArray['PHONE2'] = $AgentDetailsObj->phoneNumberCheck($attribute[$pid]['lead_mobile']);
		$phoneNumArray['PHONE3'] = $AgentDetailsObj->phoneNumberCheck($attribute[$pid]['std_enquirer'].$attribute[$pid]['enq_landline']);
		$phoneNumArray['PHONE4'] = $AgentDetailsObj->phoneNumberCheck($attribute[$pid]['std_lead'].$attribute[$pid]['lead_landline']);

		if(!$phoneNumArray['PHONE1'] && !$phoneNumArray['PHONE2'] && !$phoneNumArray['PHONE3'] && !$phoneNumArray['PHONE4'])
			return;
		$phoneNumArray = $AgentDetailsObj->checkDNC($phoneNumArray);

		$attribute[$pid]['isDNC'] = $phoneNumArray["STATUS"];

		if(!$phoneNumArray["STATUS"])
		{
			if($phoneNumArray['PHONE1S']=='Y' || $phoneNumArray['PHONE1']=='')
				$attribute[$pid]['enq_mobile'] = "";

			if($phoneNumArray['PHONE2S']=='Y' || $phoneNumArray['PHONE2']=='')
				$attribute[$pid]['lead_mobile'] = "";

			if($phoneNumArray['PHONE3S']=='Y' || $phoneNumArray['PHONE3']=='')
				$attribute[$pid]['enq_landline'] = "";

			if($phoneNumArray['PHONE4S']=='Y' || $phoneNumArray['PHONE4']=='')
				$attribute[$pid]['lead_landline'] = "";
		}
		if($attribute[$pid]['enq_mobile']!='')
					$attribute[$pid]['enq_mobile'] = $phoneNumArray['PHONE1'];
		if($attribute[$pid]['lead_mobile']!='')
			$attribute[$pid]['lead_mobile'] = $phoneNumArray['PHONE2'];
		if($attribute[$pid]['enq_landline']!='')
			$attribute[$pid]['enq_landline'] = $phoneNumArray['PHONE3'];
		if($attribute[$pid]['lead_landline']!='')
			$attribute[$pid]['lead_landline'] = $phoneNumArray['PHONE4'];

		$attribute[$pid]['phone_no1'] = '';
		$attribute[$pid]['phone_no2'] = '';
		$attribute[$pid]['phone_no1'] = $AgentDetailsObj->get_phone_no($attribute[$pid]['enq_mobile'],$attribute[$pid]['lead_mobile'],$attribute[$pid]['enq_landline'],$attribute[$pid]['lead_landline'],1);
		$attribute[$pid]['phone_no2'] = $AgentDetailsObj->get_phone_no($attribute[$pid]['enq_mobile'],$attribute[$pid]['lead_mobile'],$attribute[$pid]['enq_landline'],$attribute[$pid]['lead_landline'],2);
	
		return $attribute[$pid];
	}


	public function getPriorityValue($attribute, $pid, $mm_score)
	{
		$quartile1 = '';
		$quartile2 = '';
		$quartile3 = '';
		$quartile4 = '';

		$max_score = $mm_score['max'];
		$min_score = $mm_score['min'];

		$diff_limit = ($max_score-$min_score)/4;
		$quartile1 = $max_score-$diff_limit;
		$quartile2 = $quartile1-$diff_limit;
		$quartile3 = $quartile2-$diff_limit;
		$quartile4 = $quartile3-$diff_limit;
				
		if($attribute[$pid]['score']>=$quartile1)
			$attribute[$pid]['quartile']=1;
		elseif($attribute[$pid]['score']>=$quartile2)
			$attribute[$pid]['quartile']=2;
		elseif($attribute[$pid]['score']>=$quartile3)
			$attribute[$pid]['quartile']=3;
		else
			$attribute[$pid]['quartile']=4;
		$AgentDetailsObj =  new AgentAllocationDetails();
		$attribute[$pid]['priority'] = $AgentDetailsObj->get_priority($attribute[$pid]['status'],$attribute[$pid]['lead_source'],$attribute[$pid]['gender_val'],$attribute[$pid]['ent_date'],$attribute[$pid]['quartile']);
	
		return $attribute[$pid];
	}
	public function getLeadScore()
	{
		$sugarcrmLeadsCstmObj = new sugarcrm_leads_cstm('newjs_masterRep');
		$mm_score = $sugarcrmLeadsCstmObj->getMaxMinScore();
		return $mm_score;
	}
		public function sendEmailAlert($data, $to, $from, $subject)
		{
				$message = '<html><body>';
				$message .= "<a href=https://docs.google.com/spreadsheets/d/146ktGIdAIbIb9fiIDtFnbvl_J5lj6A9paLks89tnUmc/edit#gid=0><b>Click for Filters Definitions</b></a><br><br>";
				$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
				$message .= "<tr style='background: #eee;'><td><strong>FILTER</strong></td><td><strong>FILTERED_PROFILES</strong></td><td><strong>COUNT</strong></td><td><strong>LATEST_REG_DT</strong></td><td><strong>LATEST_REG_FILTERED_PROFILES</strong></td><td><strong>LATEST_REG_COUNT</strong></td></tr>";
				$lastCount = 0;
				foreach($data as $k=>$v)
				{
					$message .= "<tr><td>$v[FILTER]</td><td>$v[FILTERED_PROFILES]</td><td>$v[COUNT]</td><td>$v[LATEST_REG_DT]</td><td>$v[LATEST_REG_FILTERED_PROFILES]</td><td>$v[LATEST_REG_COUNT]</td></tr>";
					$lastCount = $v['LATEST_REG_COUNT'];
				}
				$message .= "</table>";
				$message .= "</body></html>";

				$headers = "From: ".$from."\r\n";
				$headers .= "Reply-To: ".$to."\r\n";
				
				// Only send email to manoj and vibhor is count is below 300	
				if($lastCount < 2000){
					$headers .= "CC: manoj.rana@naukri.com,vibhor.garg@jeevansathi.com\r\n";
				}

				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				mail($to, $subject, $message, $headers);
		}
                
                public function sendEmailForRenewalProcessCSVLogging($data, $to, $from, $subject)
		{
				$message = '<html><body>';
				$message .= "<a href=https://docs.google.com/spreadsheets/d/146ktGIdAIbIb9fiIDtFnbvl_J5lj6A9paLks89tnUmc/edit#gid=0><b>Click for Filters Definitions</b></a><br><br>";
				$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
				$message .= "<tr style='background: #eee;'><td><strong>FILTER</strong></td><td><strong>FILTERED_PROFILES</strong></td><td><strong>COUNT</strong></td></tr>";
				$lastCount = 0;
				foreach($data as $k=>$v)
				{
					$message .= "<tr><td>$v[FILTER]</td><td>$v[FILTERED_PROFILES]</td><td>$v[COUNT]</td></tr>";
					$lastCount = $v['COUNT'];
				}
				$message .= "</table>";
				$message .= "</body></html>";

				$headers = "From: ".$from."\r\n";
				$headers .= "Reply-To: ".$to."\r\n";
				
				// Only send email to manoj and vibhor is count is below 300	
				if($lastCount < 2000){
					$headers .= "CC: manoj.rana@naukri.com,vibhor.garg@jeevansathi.com\r\n";
				}

				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                //SendMail::send_email($to, $message);
				mail($to, $subject, $message, $headers);
		}
                
                public function sendEmailForFailedPaymentCSVLogging($data, $to, $from, $subject)
		{
				$message = '<html><body>';
				$message .= "<a href=https://docs.google.com/spreadsheets/d/146ktGIdAIbIb9fiIDtFnbvl_J5lj6A9paLks89tnUmc/edit#gid=0><b>Click for Filters Definitions</b></a><br><br>";
				$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
				$message .= "<tr style='background: #eee;'><td><strong>FILTER</strong></td><td><strong>FILTERED_PROFILES</strong></td><td><strong>COUNT</strong></td></tr>";
				$lastCount = 0;
                                
                                for($i=0;$i<11;$i++){
                                    $message .= "<tr><td>".$data[$i][2]."</td><td>".$data[$i][0]."</td><td>".$data[$i][1]."</td></tr>";
                                    $lastCount = $data[$i][3];
                                }
				
				$message .= "</table>";
				$message .= "</body></html>";

				$headers = "From: ".$from."\r\n";
				$headers .= "Reply-To: ".$to."\r\n";
				
				// Only send email to manoj and vibhor is count is below 300	
				if($lastCount < 2000){
					$headers .= "CC: manoj.rana@naukri.com,vibhor.garg@jeevansathi.com\r\n";
				}

				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                //SendMail::send_email($to, $message);
				mail($to, $subject, $message, $headers);
		}

		public function updateSalesLog2($totalCnt, $latestProfilesCnt, $max_dt, $filter)
		{
				$dd     =date('Y-m-d');
				$salesRegLogObj =new incentive_SALES_REGULAR_LOG();

				$cnt    =$totalCnt-$filter['campaignUndefinedCnt'];
				$cnt_L  =$latestProfilesCnt-$filter['campaignUndefinedCnt_L'];
				$salesRegLogObj->insertCount($dd,'CAMPAIGN_UNDEFINED',$filter['campaignUndefinedCnt'],$cnt,$max_dt,$filter['campaignUndefinedCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['jprofileCnt'];
				$cnt_L  =$cnt_L-$filter['jprofileCnt_L'];
				$salesRegLogObj->insertCount($dd,'JPROFILE',$filter['jprofileCnt'],$cnt,$max_dt,$filter['jprofileCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['dncCnt'];
				$cnt_L  =$cnt_L-$filter['dncCnt_L'];
				$salesRegLogObj->insertCount($dd,'DNC',$filter['dncCnt'],$cnt,$max_dt,$filter['dncCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['premiumIncomeCnt'];
				$cnt_L  =$cnt_L-$filter['premiumIncomeCnt_L'];
				$salesRegLogObj->insertCount($dd,'PREMIUM_INCOME',$filter['premiumIncomeCnt'],$cnt,$max_dt,$filter['premiumIncomeCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['alertsCnt'];
				$cnt_L  =$cnt_L-$filter['alertsCnt_L'];
				$salesRegLogObj->insertCount($dd,'ALERTS',$filter['alertsCnt'],$cnt,$max_dt,$filter['alertsCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['dispositionValidityCnt'];
				$cnt_L  =$cnt_L-$filter['dispositionValidityCnt_L'];
				$salesRegLogObj->insertCount($dd,'DISPOSITION',$filter['dispositionValidityCnt'],$cnt,$max_dt,$filter['dispositionValidityCnt_L'],$cnt_L);
				$cnt    =$cnt-$filter['scoreValidityCnt'];
				$cnt_L  =$cnt_L-$filter['scoreValidityCnt_L'];
				$salesRegLogObj->insertCount($dd,'ANALYTIC_SCORE_ZERO',$filter['scoreValidityCnt'],$cnt,$max_dt,$filter['scoreValidityCnt_L'],$cnt_L);

				$cnt    =$cnt-$filter['dataLimitExceedCnt'];
				$cnt_L  =$cnt_L-$filter['dataLimitExceedCnt_L'];
				$salesRegLogObj->insertCount($dd,'DATA_LIMIT_EXCEED',$filter['dataLimitExceedCnt'],$cnt,$max_dt,$filter['dataLimitExceedCnt_L'],$cnt_L);
		}
                
                public function updateFPLogs($totalCnt, $filter, $process)
		{
				$dd     =date('Y-m-d');
				$fpRegLogObj =new incentive_PROCESS_REGULAR_LOG();

				$cnt    =$totalCnt-$filter['notActivatedCnt'];
				$fpRegLogObj->insertCount($dd,'NOT_ACTIVATED',$filter['notActivatedCnt'],$cnt,$process);

				$cnt    =$cnt-$filter['invalidPhoneCnt'];
				$fpRegLogObj->insertCount($dd,'INVALID_PHONE',$filter['invalidPhoneCnt'],$cnt,$process);

				$cnt    =$cnt-$filter['maleAgeCnt'];
				$fpRegLogObj->insertCount($dd,'MALE_AGE',$filter['maleAgeCnt'],$cnt,$process);

				$cnt    =$cnt-$filter['nriCnt'];
				$fpRegLogObj->insertCount($dd,'NRI',$filter['nriCnt'],$cnt,$process);

				$cnt    =$cnt-$filter['nonOptinProfileCnt'];
				$fpRegLogObj->insertCount($dd,'NON_OPTIN',$filter['nonOptinProfileCnt'],$cnt,$process);

				$cnt    =$cnt-$filter['noPhoneExistsCnt'];
				$fpRegLogObj->insertCount($dd,'NO_PHONE_EXISTS',$filter['noPhoneExistsCnt'],$cnt,$process);
                                
                                $cnt    =$cnt-$filter['noPhoneCnt'];
				$fpRegLogObj->insertCount($dd,'NO_PHONE',$filter['noPhoneCnt'],$cnt,$process);
                                //Filter only applicable in renewal process task
                                if($process=='renewalProcessInDialer'){
                                    $cnt    =$cnt-$filter['lastLoginCnt'];
                                    $fpRegLogObj->insertCount($dd,'LAST_LOGIN',$filter['lastLoginCnt'],$cnt,$process);
                                }
				
		}
                
                
		public function updateSalesLog($filterLabel, $max_dt, $prevTotalCnt=0, $prevLatestCnt=0)
		{
				$dd             =date('Y-m-d');
				$salesCsvTemp   =new incentive_SALES_CSV_DATA_TEMP(); 	
				$salesRegLogObj =new incentive_SALES_REGULAR_LOG();
				$currTotalCnt   =$salesCsvTemp->getAllProfilesCount();
				$currLatestCnt  =$salesCsvTemp->getLatestProfilesCount($max_dt);
				if($prevTotalCnt==0 && $prevLatestCnt==0){
						$netTotalCnt    =0;
						$netLatestCnt   =0;
				}else{
						$netTotalCnt    =$prevTotalCnt-$currTotalCnt;;
						$netLatestCnt   =$prevLatestCnt-$currLatestCnt;
				}
				$salesRegLogObj->insertCount($dd,$filterLabel,$netTotalCnt,$currTotalCnt,$max_dt,$netLatestCnt,$currLatestCnt);
				return array($currTotalCnt, $currLatestCnt);
		}
                
                public function updateFPLog($filterLabel, $prevTotalCnt=0, $process)
		{
				$dd             =date('Y-m-d');
				$fpCsvTemp   =new incentive_PROCESS_CSV_DATA_TEMP(); 	
				$fpRegLogObj =new incentive_PROCESS_REGULAR_LOG();
				$currTotalCnt   =$fpCsvTemp->getAllProfilesCount($process);
				//$currLatestCnt  =$fpCsvTemp->getLatestProfilesCount($max_dt, $process);
				if($prevTotalCnt==0){
						$netTotalCnt    =0;
				}else{
						$netTotalCnt    =$prevTotalCnt-$currTotalCnt;;
				}
				$fpRegLogObj->insertCount($dd,$filterLabel,$netTotalCnt,$currTotalCnt,$process);
				return array($currTotalCnt);
		}

	// sales CSV Profile logging
	public function salesCsvProfileLog($profileid,$username,$csvSent,$filter='',$filterVal='',$campaignName='',$dialStatus='',$profiles=array())
	{
		$salesCsvDataTempObj	=new incentive_SALES_CSV_DATA_TEMP();
		$salesCsvProfileLogObj 	=new incentive_SALES_CSV_PROFILE_LOG();

		if($filter=='FAILED_PAYMENT')
			$profiles =$salesCsvDataTempObj->fetchFailedPaymentProfiles($profiles);

		if(count($profiles)>0){
			foreach($profiles as $key=>$val)
				$salesCsvProfileLogObj->insertProfile($val['PROFILEID'],$val['USERNAME'],$csvSent,$filter,'Y');
		}
		elseif($profileid)	
			$salesCsvProfileLogObj->insertProfile($profileid,$username,$csvSent,$filter,$filterVal,$campaignName,$dialStatus);
		unset($profiles);
	}
        
        	// FP CSV Profile logging
	public function fpCsvProfileLog($profileid,$username,$csvSent,$filter='',$filterVal='',$campaignName='',$dialStatus='',$profiles='', $process)
	{
		$fpCsvDataTempObj	=new incentive_PROCESS_CSV_DATA_TEMP();
		$fpCsvProfileLogObj 	=new incentive_PROCESS_CSV_PROFILE_LOG();
		if(!$profiles)
			$profiles=array();

                if($process=="renewalProcessInDialer"){
                    if(count($profiles)>0){
			foreach($profiles as $key=>$val){
                            if($filter == "NEGATIVE_TREATMENT")
				$fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else if($filter =="DO_NOT_CALL")
                                $fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else if($filter =="RENEWAL_PROCESS_IN_DIALER")
                                $fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else if($filter =="UNSUBSCRIBED")
                                $fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else{
                                $fpCsvProfileLogObj->insertProfile($val['PROFILEID'],'',$csvSent,$filter,'Y','','',$process);
                            }
                        }
		}
		elseif($profileid)	
			$fpCsvProfileLogObj->insertProfile($profileid,$username,$csvSent,$filter,$filterVal,$campaignName,$dialStatus, $process);
                }
                else{
			if(count($profiles)>0){
			foreach($profiles as $key=>$val){
                            if($filter == "NEGATIVE_TREATMENT")
				$fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else if($filter =="DO_NOT_CALL")
                                $fpCsvProfileLogObj->insertProfile($val,'',$csvSent,$filter,'Y','','',$process);
                            else
                                $fpCsvProfileLogObj->insertProfile($val['PROFILEID'],'',$csvSent,$filter,'Y','','',$process);
                        }
			}
			elseif($profileid){	
				$fpCsvProfileLogObj->insertProfile($profileid,$username,$csvSent,$filter,$filterVal,$campaignName,$dialStatus, $process);
			}
		}	
		unset($profiles);
        
        }
}	
