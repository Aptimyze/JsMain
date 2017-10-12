<?php
class AgentAllocationDetails
{
	//Returns executives eligible for the process
	public function fetchExecutives($processObj)
	{
	$jsAdminPSWRDSObj=new jsadmin_PSWRDS('newjs_slave');
	if($processObj->getProcessName()=="DeAllocation")

	{
		$method=$processObj->getMethod();
		if($method=="FTA_FTO"||$method=="SALESEXPIRY"||$method=="REALLOCATION")
		{
			$subMethod=$processObj->getSubMethod();
			switch($subMethod)
			{
				case "UPSELL" :
					$priv="%ExcUpS%";
					break;
				case "FTA" :
					$priv="%FTAFTO%";
					break;
				case "CENTRAL_RENEWAL" :
					$priv=array("%ExcFld%","%LR%");
					break;
				case "CENTRAL_RENEWAL_MONTHLY" :
                                        $priv="%LR%";
                                        break;
			}
			if($subMethod == "CENTRAL_RENEWAL"){
				$agents = array();
				foreach($priv as $key=>$val){
					$agentArr = $jsAdminPSWRDSObj->fetchAgentsWithPriviliges($val);
					if(is_array($agentArr) && !empty($agentArr)){
						$agents = array_merge($agents, $agentArr); 
					}
				}
				$agents = array_values(array_unique($agents));
			} else {
				$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges($priv);
			}
		}
		else
		{
			$mainAdminObj=new incentive_MAIN_ADMIN('newjs_masterRep');
			$agents=$mainAdminObj->fetchAgentsForDisp($processObj);
			$subMethod=$processObj->getSubMethod();
			if($subMethod=="LIMIT_EXCEED" || $subMethod=='LIMIT_EXCEED_RENEWAL')
			{
				/*  Check added for ignoring Renewal Agents, as discussed with Rohan */
				/*$priv1="%ExcRnw%";
				$renewalAgents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges($priv1);
				if(!is_array($renewalAgents))
					$renewalAgents =array();*/
				/* Check ended */	

				$resArr1 = array();
				for ($i = 0; $i < count($agents); $i++){
					$agent_name = explode(":",$agents[$i]);
					$Allagents[]=$agent_name[0];
				}
				/*if($subMethod=='LIMIT_EXCEED_RENEWAL')
					$restofagents =array_intersect($Allagents,$renewalAgents);
				elseif($subMethod=='LIMIT_EXCEED')
					$restofagents =array_diff($Allagents,$renewalAgents);*/

				$restofagents =$Allagents;
				$restofagents =array_unique($restofagents);
				$restofagents =array_values($restofagents);
				for ($k = 0; $k < count($agents); $k++){
					$agent_name = explode(":",$agents[$k]);
					for ($j = 0; $j < count($restofagents); $j++){
						if(($restofagents[$j]== $agent_name[0]) && !in_array($agents[$k],$resArr1))
							$resArr1[] = $agents[$k];
					}
				}
				$agents = $resArr1;
			}
		}
	}
	elseif($processObj->getProcessName()=="PreAllocation")
	{
		$locationObj=new incentive_LOCATION('newjs_slave');	
		$level=$processObj->getLevel();
		if($level==-5 || $level==-3||$level==-2||$level==-1||$level==0)
		{
			switch($level)
			{
				case -5	:
					$priv="%PreNri%";
					break;	
				case -3 :
					$priv="%FTAFTO%";
					break;
				case -2 :
					$priv="%ExcWFH%";
					break;
				case -1 :
					$priv="%ExcPrm%";
					break;
				case  0 :
                                        $priv="%ExcEP%";
                                        break;
			}
			$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges($priv);
		}
		elseif($level==-4){
			// New level -4 Introduced
			$privilege 	=$processObj->getPrivilege();
			$headAgent 	=trim($processObj->getExecutive());
			if($headAgent){
				$status='';
				if($privilege=='%LTFVnd%')
					$status ='ALL';
				$agents =$this->fetchAgentsByHierarchy($headAgent,$privilege,$status);
			}
			else
				$agents =$jsAdminPSWRDSObj->fetchAgentsWithPriviliges($privilege);
			// -4 level ends
		}	
		else
		{
			$center=strtoupper($processObj->getCenter());
			$preAllPrivilage ='PREALL'; 
			$agents =array();
			$agentDetails =$processObj->getAgentDetails();	
			foreach($agentDetails as $username=>$dataArr){
				$privilegeStr =$dataArr['PRIVILAGE'];
				if($center==$dataArr['SUB_CENTER'] && stristr($privilegeStr, $preAllPrivilage)){
					$agents[] =$username;	
				}
			}
			if(is_array($agents))
				$agents =array_unique($agents);
		}
	}
	elseif($processObj->getProcessName()=="Allocation")
	{
		$subMethod=$processObj->getSubMethod();
		$method	  =$processObj->getMethod();
		if($method=="UPSELL" || $subMethod=='UPSELL' || $subMethod == 'CENTRAL_RENEWAL' || $subMethod == 'CENTRAL_RENEWAL_MONTHLY'){
			$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExcUpS%");
		}
		elseif($method=="RENEWAL" || $subMethod=='RENEWAL')
			$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExcRnw%");
		elseif($method=="NEW_FAILED_PAYMENT" || $subMethod=='NEW_FAILED_PAYMENT')
			$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExcFP%");
		elseif($method=="WEBMASTER_LEADS" || $subMethod=='WEBMASTER_LEADS')
		{
            		if($subMethod == 'WEBMASTER_LEADS_EXCLUSIVE'){
                		$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExPmWL%");
            		}
            		else{
                		$agents=$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExcWL%");
            		}
            		if($agents){
                		shuffle($agents);
            		}
        	}
		elseif($method=="MANUAL"){
			$headAgent	=$processObj->getExecutive();
			$agents 	=$this->fetchAgentsByHierarchy($headAgent);
		}
		elseif($method=='TRANSFER_PROFILES' && $subMethod=='FIELD_SALES')
			$agents =$jsAdminPSWRDSObj->fetchAgentsWithPriviliges("%ExcFld%");
		elseif($method=='FIELD_SALES'){
			$center=$processObj->getCenter();
			$agents =$jsAdminPSWRDSObj->fetchAgentsWithPriviligesAndCenter("%ExcFld%",$center);
		}
		elseif($method=='TRANSFER_PROFILES' && $subMethod=='FRESH'){
			$incPATObj = new incentive_PROFILE_ALLOCATION_TECH();
			$agents =$incPATObj->getDistinctExecutives();
		}
	}
	return $agents;
}
//Returns profiles eligible for the process
public function fetchProfiles($processObj)
{	
	if($processObj->getProcessName()=="DeAllocation")
	{
		$mainAdminObj=new incentive_MAIN_ADMIN('newjs_masterRep');
		$subMethod=$processObj->getSubMethod();
		if($subMethod=="NEGATIVE_LIST")
		{
			$disp=$this->fetchDispositionNegativeListOrder();
			$processObj->setDisposition($disp);
			$allProfiles=$mainAdminObj->fetchProfilesForDispNegList($processObj);
			$profiles=$this->getMyDisposedProfiles($allProfiles);
		}
		elseif($processObj->getMethod()=="FTA_FTO")
		{
			$statesArray=array('C1','C2','C3');
			$agents=$processObj->getExecutives();
			$profiles=array();
			$profilesToBeDeleted=array();
			for($i=0;$i<count($agents);$i++)
			{
				$processObj->setUsername($agents[$i]);
				$profileids=$mainAdminObj->fetchProfilesForAgent($processObj);
				for($j=0;$j<count($profileids);$j++)
				{
					$profileid=$profileids[$j];
					$profileObj=new Profile("",$profileid);
					$ftoStatesObj=$profileObj->getPROFILE_STATE()->getFTOStates();
					$ftoSubState= $ftoStatesObj->getSubState();
					if(in_array("$ftoSubState",$statesArray))
						continue;
					else
						$profilesToBeDeleted[]=$profileid;
				}
				$profiles=array_merge($profiles,$profilesToBeDeleted);
			}
		}
		elseif($processObj->getMethod()=="SALESEXPIRY")
			$profiles=$mainAdminObj->fetchProfilesForSubMethod($processObj);
		elseif($processObj->getMethod() == "REALLOCATION"){
			$fsAgents = $processObj->getExecutives();
			// GET CURRENTLY ALLOTED PROFILES FOR THIS AGENT WITH STATUS 'FS'
			$profileList = array();
			$profiles = array();
			$startDt = $processObj->getStartDate();
			$endDt = $processObj->getEndDate();
			foreach($fsAgents as $key=>$val){
				$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
				$profileList = $mainAdminObj->getAllotedProfilesWithAllotTimeForAgent($val);
				$paymentDetailObj = new BILLING_PAYMENT_DETAIL('newjs_masterRep');
				foreach($profileList as $profileid=>$allotDate){
					// IF RETURN IS TRUE, WE ADD IT TO THE LIST OF PROFILES FOR FURTHER PROCESSING
					if($paymentDetailObj->getPaidStatusForProfileInRange($profileid, $allotDate, $startDt, $endDt)){
						$profiles[] = $profileid;
					}
				}
			}
		}
		elseif($subMethod=="FOLLOWUP_PENDING"){
			$last3day =date('Y-m-d',time()-2*86400)." 00:00:00";	
			$profiles=$mainAdminObj->getPendingFollowupProfiles($last3day);	
		}
		elseif($subMethod=='DELETED_PROFILES'){
			$startDt 	=date('Y-m-d',time()-86400)." 00:00:00";			
			$endDate 	=date('Y-m-d H:i:s');
			$status 	='D';	
			$jprofileObj 	=new JPROFILE('newjs_masterRep');			
			$profileDetails =$jprofileObj->getProfilesForDateRange($startDt, $endDate, $status);
			$profiles 	=array_keys($profileDetails);
			$processObj->setUsername($profileDetails);
		}
        	elseif($subMethod == 'DISPOSITION_BASED'){
            /*JSC-1094 
             * 1. No disposition has been marked within 2 days of allocation
               2. No 'field visit done' disposition is marked within 10 days
             */
            $profiles = array();
            $profilesFVD = array();
            $processId = $processObj->getIdAllot();
            $agentsObj = new jsadmin_PSWRDS("newjs_masterRep");
            $agents = $agentsObj->fetchAgentsWithPriviliges("%ExcFld%");
            $lastHandledDateObj = new incentive_LAST_HANDLED_DATE("newjs_masterRep");
            $lastHandledDate = $lastHandledDateObj->getHandledDate($processId);
            $curDate = date('Y-m-d');
            $lastHandledDate = $lastHandledDate?$lastHandledDate:date('Y-m-d',  strtotime('-1 day', strtotime($curDate)));
            $agentDeallocationObj = new AgentDeAllocation();
            $profiles = $agentDeallocationObj->fetchProfilesOnDisposition($lastHandledDate, $agents, 2); 
            $profilesFVD = $agentDeallocationObj->fetchProfilesOnDisposition($lastHandledDate, $agents, 10);
            $profiles = array_merge($profiles, $profilesFVD);
            unset($profilesFVD);
            unset($agentsObj);
            unset($agents);
            unset($lastHandledDateObj);
            unset($lastHandledDate);
            unset($agentDeallocationObj);
        	}
		else
			$profiles=$mainAdminObj->fetchProfilesForAgent($processObj);
	}
	elseif($processObj->getProcessName()=="PreAllocation")
	{
		$mainAdminPoolObj=new incentive_MAIN_ADMIN_POOL("newjs_slave");
		$preAllocationTempPoolObj=new incentive_PRE_ALLOCATION_TEMP_POOL();	
		$level=$processObj->getLevel();
		$city=$processObj->getCity();
		$cityNewArr=array();
		$execs=$processObj->getExecutives();
		$execsCount=count($execs);
		$limitArr=$processObj->getLimitArr();
		$discount_status = $processObj->getDiscountStatus();

		if($level==1||$level==2||$level==4||$level==5){
			$cities=array();
			$cities=$processObj->getProfileCities();
			$lowerScoreLimit=70;
		}
		if($level==2||$level==6){
			//$locationObj=new incentive_LOCATION('newjs_slave');	
			//$citySelArr=$locationObj->fetchSpecialCities();
			$citySelArr =$processObj->getSpecialCityList();
			if(array_key_exists($city,$citySelArr)){
				//$lowerScoreLimit=1;
				$lowerScoreLimit=70;	
			}
			if($level==6){
				$cityNewArr=$processObj->getSpecialCities();
				$state=$citySelArr[$city];
				$centers_str=$cityNewArr[$state];
				$cities=explode("','",$centers_str);
			}	
		}
		elseif($level==3){
			//$locationCityObj=new incentive_LOCATION_CITY('newjs_slave');		
			$state=$processObj->getState();
			//$locationObj=new incentive_LOCATION('newjs_slave');		
			//$citySelArr=$locationObj->fetchSpecialCities();
			$citySelArr =$processObj->getSpecialCityList();
			$cities=$this->fetchRestStateCities($state);
			$cities_str=implode("','",$cities);

			if(in_array("$state",$citySelArr)){
				$cityNewArr=$processObj->getSpecialCities();
				$cityNewArr[$state]=$cities_str;
				$processObj->setSpecialCities($cityNewArr);
			}
			$lowerScoreLimit=70;
		}
                if($level==-5){
			$preAllocationTempPoolObj =new incentive_PRE_ALLOCATION_TEMP_POOL();
                        $profilesArr =$preAllocationTempPoolObj->fetchNriProfiles();
			if(count($profilesArr)>0){
				foreach($profilesArr as $key=>$val){
					$isdVal 	=trim($val['ISD']);
					$isIndian       =$this->isIndianNo($isdVal);
					$isdVal    	=substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$isdVal),-1);
					if(!$isIndian && $isdVal!=1)
						$profiles[] =$val['PROFILEID'];
				}
			}
                }
		elseif($level==-4){
                        $agentsArr=$processObj->getExecutives();
                        $profiles=$this->fetchLtfProfilesForAgent($agentsArr);
                }
		elseif($level==-3){
			$profilesInC=FTOStateHandler::getProfilesInState(FTOStateTypes::FTO_ELIGIBLE);
			if(is_array($profilesInC))
			{
					$profiles=$profilesInC;
			}
		}
		elseif($level==-2)
			$profiles=$this->fetchPremiumOutSourcingProfiles();
		elseif($level==-1)
			$profiles=$this->fetchPremiumProfiles();
		elseif($level==0)
			$profiles=$this->fetchEverPaidProfiles();
		elseif($level==1){
                        $pincodes = $this->fetchPincodesOfCities($cities);
			$fields = "PROFILEID,PINCODE";
                        $jprofileObj = new JPROFILE('newjs_slave');	
			if(count($pincodes)>0){
	                	$valueStr['PINCODE'] = implode(",",$pincodes);
	                        $profilesData = $jprofileObj->getArray($valueStr,'','',$fields);
				for($p=0;$p<count($profilesData);$p++)
					$profiles[]=$profilesData[$p]['PROFILEID'];
			}
                }
		else{
			$loginDtEnd =date("Y-m-d",time()-2*24*60*60);	
			$profiles=$preAllocationTempPoolObj->fetchProfilesWithCities($cities,$lowerScoreLimit,$loginDtEnd);	
		}
			
		$maxLimit = 0;
		$agentDetails =$processObj->getAgentDetails();  
		if(is_array($execs)){
			foreach($execs as $k => $v) {
				$uname 		=$v['NAME'];
				$subCenter 	=$agentDetails[$uname]['SUB_CENTER'];
				$maxLimit 	+=$limitArr[$subCenter];
			}
		}
		$profilesRequiredCount=$maxLimit;
		$everPaidPool =$processObj->getEverPaidPool();
		if(count($profiles)>0)
			$profiles=$this->filterProfilesForPreAllocation($profiles,$level,$profilesRequiredCount,$discount_status,$everPaidPool);
	}
	elseif($processObj->getProcessName()=="Allocation")
	{
		$method		=$processObj->getMethod();
		$subMethod 	=$processObj->getSubMethod();			

		if($method=='REALLOCATION'){
			$profiles = $processObj->getProfiles();
			// Check here for profiles which were DeAllocated already, but werent reAllocated due to cron failure
			$deAllocationObj = new incentive_DEALLOCATION_TRACK();
			$startDt = $processObj->getStartDate();
			$endDt = $processObj->getEndDate();
			// Code to check profiles which were de-allocated already when cron failed
			$deAllocatedProfiles = $deAllocationObj->getProfilesForReallocationWithinRange($startDt, $endDt, $subMethod);
			if(!isset($profiles) && is_array($deAllocatedProfiles)){
				$profiles = array();
			}
			if(is_array($deAllocatedProfiles)){
				$profiles = array_merge($profiles, $deAllocatedProfiles);
				// Code to check for duplicate entries (safe check)
				$profiles = array_values(array_unique($profiles));
			}
		}
		else if($method=='OUTBOUND_PROCESS_COUNT'){
			$profiles =$this->fetchOutboundProfiles($processObj);
		}
		else if($method=='OUTBOUND_PROCESS'){
			$profiles =$this->fetchOutboundProfiles($processObj);
			if($subMethod=='NEW_PROFILES' || $subMethod=='ONLINE_NEW_PROFILES' || $subMethod=='HANDLED' || $subMethod=='FOLLOWUP' || $subMethod=='FFOLLOWUP' || $subMethod=='FTA' || $subMethod=='SUB_EXPIRY' || $subMethod=='RENEWAL')
				$profiles =$this->fetchOutboundSortedProfiles($processObj,$profiles);	
		}
		else if($method=='TRANSFER_PROFILES'){
			if($subMethod == 'FRESH'){
				$incPATObj = new incentive_PROFILE_ALLOCATION_TECH();
				$profiles = $incPATObj->fetchProfilesForAgent($processObj->getUsername());
			}
			else{
				$mainAdminObj	=new incentive_MAIN_ADMIN();
				$profiles 	=$mainAdminObj->fetchProfilesForAgent($processObj);
			}
		}
		else{
			$billingSerStatusObj=new BILLING_SERVICE_STATUS('newjs_slave');
			if($method=="UPSELL")
				$profiles=$billingSerStatusObj->getUpsellEligibleProfiles();
			elseif($method=="RENEWAL")
				$profiles=$billingSerStatusObj->getRenewalEligibleProfiles();
			elseif($method=="NEW_FAILED_PAYMENT")
				$profiles=$this->fetchNewFailedPaymentEligibleProfiles();
			elseif($method=="WEBMASTER_LEADS")
		                $profiles=$this->fetchWebmasterLeadsEligibleProfiles($subMethod);
			elseif($method=='FIELD_SALES'){
				$processId 		=$processObj->getIdAllot();
				$lastHandledDtObj 	=new incentive_LAST_HANDLED_DATE();
				$screenedTimeStart 	=$lastHandledDtObj->getHandledDate($processId);
				$processObj->setStartDate($screenedTimeStart);

                                if($subMethod=='FIELD_SALES_VISIT'){
                                        $widgetLogObj =new incentive_FIELD_SALES_WIDGET('newjs_masterRep');
        	                        $widgetTimeEnd =$widgetLogObj->getMaxDate();
					$widgetTimeEndSet =date('Y-m-d H:i:s', strtotime('-3 hours',strtotime($widgetTimeEnd)));
	                                $processObj->setEndDate($widgetTimeEndSet);
                	                $profiles =$widgetLogObj->getLastHourScheduledProfiles($screenedTimeStart,$widgetTimeEnd);
                                }
                                else{
                                        $screeningLogObj =new jsadmin_SCREENING_LOG('newjs_masterRep');
					$screenedTimeEnd =$screeningLogObj->getScreenedMaxDate();
					//$screenedTimeEndSet =date('Y-m-d H:i:s', strtotime('-3 hours',strtotime($screenedTimeEnd)));
					$processObj->setEndDate($screenedTimeEnd);
					$profiles =$screeningLogObj->getLastHourScreenedProfiles($screenedTimeStart,$screenedTimeEnd);
				}
				if(count($profiles)>0){
					/*
					if($subMethod != 'FIELD_SALES_VISIT'){
						$phVerifiedProfiles =array();
						$phVerifiedProfiles =$processObj->getPhoneVerifiedProfiles();
						if(count($phVerifiedProfiles)>0){
							$profiles =array_intersect($profiles, $phVerifiedProfiles);
							$profiles =array_values($profiles);
						}
					}*/
					// get profiles from field sales allocation log
					$fieldSalesAllocLog =new incentive_FIELD_SALES_LOG();
					$preDate 	=date("Y-m-d",time()-9*24*60*60);
					$logProfiles 	=$fieldSalesAllocLog->getProfiles($preDate);
					if(count($logProfiles)>0){
						$profiles 	=array_diff($profiles, $logProfiles);
						$profiles 	=array_values($profiles);
					}
				}
			}
			if(is_array($profiles))
				$profiles=$this->filterProfilesForAllocation($profiles,$method,$processObj);
		}
	}
        elseif($processObj->getProcessName()=="failedPaymentInDialer")
        {
		$processName	=$processObj->getProcessName();
                $method   	=$processObj->getMethod();
		$startDt	=$processObj->getStartDate();
		$endDt		=$processObj->getEndDate();
                $profiles	=$this->fetchNewFailedPaymentEligibleProfiles($processName,$startDt,$endDt);
	}
	elseif($processObj->getProcessName()=="rcbCampaignInDialer"){
                $processName    =$processObj->getProcessName();
                $subMethod      =$processObj->getSubMethod();
                $startDt        =$processObj->getStartDate();
                $endDt          =$processObj->getEndDate();
                $profiles      	=$this->fetchWebmasterLeadsEligibleProfiles($subMethod, $startDt, $endDt, $processObj);
		if(count($profiles)>0){
			$obj =new incentive_MAIN_ADMIN();
			$profilesAllocated =$obj->getProfilesDetails($profiles);
			if(count($profilesAllocated)>0){
				foreach($profilesAllocated as $key=>$value){
					$allocated[] =$value['PROFILEID'];
				}
				$profiles =array_diff($profiles,$allocated);
				$profiles =array_values($profiles);
				unset($allocated);
			}
		}
	}
        elseif($processObj->getProcessName()=="upsellProcessInDialer")
        {
		$purchasesObj	=new BILLING_PURCHASES('newjs_slave');
                $processName    =$processObj->getProcessName();
		$processId      =$processObj->getIdAllot();
                $lastHandledDtObj =new incentive_LAST_HANDLED_DATE('newjs_slave');
                $startDate 	=$lastHandledDtObj->getHandledDate($processId);
		$endDate        =date("Y-m-d H:i:s");
                $processObj->setStartDate($startDate);
		$processObj->setEndDate($endDate);
		$profiles 	=$purchasesObj->getUpsellEligibleProfiles($startDate, $endDate);
        }
        elseif($processObj->getProcessName()=="renewalProcessInDialer")
        {
		$profiles =$this->getProfilesInRenewalPeriod();
        }
	elseif($processObj->getProcessName()=='paidCampaignProcess'){
		$purchasesObj   =new BILLING_PURCHASES('newjs_slave');
		$date 		=date('Y-m-d',time()-86400);
		$startDate 	=$date." 00:00:00";
		$endDate   	=$date." 23:59:59";
		$profiles1      =$purchasesObj->getFreshPaidProfiles();
		foreach($profiles1 as $dataSet){
			$entryDt =$dataSet['ENTRY_DT'];
			if((strtotime($entryDt)>=strtotime($startDate)) && (strtotime($entryDt)<=strtotime($endDate))){
				$profiles[] =$dataSet;	
			}	
		}
	}
	return $profiles;
}
public function fetchAllCenters()
{
	$statesObj=new incentive_BRANCH_STATE();
	$locationObj=new incentive_LOCATION();
	$subLocationObj=new incentive_SUB_LOCATION();
	$states=$statesObj->fetchStates();
	$noOfStates=count($states);
	for($i=0;$i<$noOfStates;$i++)
	{
		$statesLocations[$states[$i]]=array();
		$locations=$locationObj->fetchLocations($states[$i]);
		$statesLocations[$states[$i]]=$locations;
		for($j=0;$j<count($locations);$j++)
		{
			$stateLocations[$states[$i]][$locations[$j]]=array();
			$centers=$subLocationObj->fetchSubLocations($statesLocations[$states[$i]][$j]);
			$statesLocationsCenters[$states[$i]][$locations[$j]]=$centers;
		}
	}
	return $statesLocationsCenters;
}
public function fetchPremiumProfiles()
{
	$searchMaleObj=new NEWJS_SEARCH_MALE('newjs_slave');		
	$searchFemaleObj=new NEWJS_SEARCH_FEMALE('newjs_slave');	
	$fields="PROFILEID";
	//$endDt  =date('Y-m-d',time()-(5+1)*86400);
	//$startDt =date('Y-m-d',time()-(2-1)*86400);
        $endDt  =date('Y-m-d',time()-86400)." 23:59:59";
        $startDt =date('Y-m-d',time()-86400)." 00:00:00";
        $greaterArray['ENTRY_DT']="$startDt";
        $lessThanArray["ENTRY_DT"]="$endDt";
	$excludeArray['MTONGUE']="1,3,16,17,31";
	$profilesMale=$searchMaleObj->getArray("",$excludeArray,$greaterArray,$fields,$lessThanArray);
	$profilesFemale=$searchFemaleObj->getArray("",$excludeArray,$greaterArray,$fields,$lessThanArray);
	if(is_array($profilesFemale)&&is_array($profilesMale))
		$profiles=array_merge($profilesMale,$profilesFemale);
	elseif(is_array($profilesFemale))
		$profiles=$profilesFemale;
	else
		$profiles=$profilesMale;
	shuffle($profiles);
	return $profiles;
}
public function fetchPremiumOutSourcingProfiles()
{
	$searchMaleObj=new NEWJS_SEARCH_MALE('newjs_slave');		
	$searchFemaleObj=new NEWJS_SEARCH_FEMALE('newjs_slave');	
	$startDt=date('Y-m-d',time()-2*30*86400);
	$endDt  =date('Y-m-d',time()-1*30*86400);
	$endEntryDt=date('Y-m-d',time()-36*30*86400);
	$greaterArray['LAST_LOGIN_DT']="$startDt";
	$lessArray['LAST_LOGIN_DT']="$endDt";
	$excludeArray['MTONGUE']="1,3,16,17,31";
	$greaterArray['ENTRY_DT']="$endEntryDt";
	$fields="PROFILEID";
	$profilesMale=$searchMaleObj->getArray("",$excludeArray,$greaterArray,$fields,$lessArray);
	$profilesFemale=$searchFemaleObj->getArray("",$excludeArray,$greaterArray,$fields,$lessArray);
	if(is_array($profilesFemale)&&is_array($profilesMale))
		$profiles=array_merge($profilesFemale,$profilesMale);
	elseif(is_array($profilesFemale))
		$profiles=$profilesFemale;
	else
		$profiles=$profilesMale;
	shuffle($profiles);
	return $profiles;
}
public function fetchEverPaidProfiles()
{
	$serviceStatusObj = new billing_SERVICE_STATUS('newjs_slave');
	$profiles = $serviceStatusObj->fetchEverPaidProfiles();
        return $profiles;
}
public function fetchFilteredAgents($agents,$fixedAllotedNo)
{
	$l=0;
	$profileTechObj=new incentive_PROFILE_ALLOCATION_TECH();	
	for($i=0;$i<count($agents);$i++)
	{
		$uname = $agents[$i];
		$allotedCount=$profileTechObj->getProfileAllotedCount($uname);
		if($allotedCount < $fixedAllotedNo)
		{
				$userarr[$l]['NAME'] = $uname;
				$userarr[$l]['ALLOTED'] = $allotedCount;
				$l++;
		}
	}
	return $userarr;
}
public function fetchRestStateCities($state)
{
	$locationObj=new incentive_LOCATION('newjs_slave');	
	$locationCityObj=new incentive_LOCATION_CITY('newjs_slave');		
	$cities=$locationObj->fetchLocations($state);
	if(is_array($cities))
		$citiesStr="'".implode("','",$cities)."'";		
	$cities=$locationCityObj->fetchLocationWithoutBranches($state,$citiesStr);
	return $cities;
}
public function fetchRestIndiaStatesCities()
{
	$jsAdminPSWRDSObj=new jsadmin_PSWRDS('newjs_slave');
	$locationObj=new incentive_LOCATION('newjs_slave');
	$locationCityObj=new incentive_LOCATION_CITY('newjs_slave');
	$center=$jsAdminPSWRDSObj->fetchCentersOfAgents();
	$state=$locationObj->fetchStatesOfCities($center);
	$stateArr=$locationCityObj->fetchStateWithoutBranches($state);
	$cityArr=$locationCityObj->fetchCitiesOfStates($stateArr);
	return $cityArr;
}
public function filterProfilesForAllocation($profiles,$method,$processObj='')
{
	unset($noLoop);
	if($method!='NEW_FAILED_PAYMENT' && $method!='FIELD_SALES' && $method!="WEBMASTER_LEADS"){
		$mainAdminPoolObj	=new incentive_MAIN_ADMIN_POOL('newjs_slave');
		$alertsObj		=new JprofileAlertsCache('newjs_slave');
		$profilesStr		="'".implode("','",$profiles)."'";
		$valueArray['PROFILEID']=$profilesStr;
		$profiles		=$mainAdminPoolObj->getArray($valueArray,"","","PROFILEID");
		$check_day 		=JSstrToTime(date("Y-m-d",time()+29*24*60*60));
		$today_day              =JSstrToTime(date("Y-m-d"));
	}
	if($method=='FIELD_SALES'){	
		$excludeMtongue 	=crmParams::$fieldSalesIgnoreCommunity;
		$pincodeMappedCity	=crmParams::$fieldSalesPincodeMappedCity;
		$fieldSalesCity         =$this->fetchFieldSalesCity(); 
		$screeningObj		=new jsadmin_SCREENING_LOG('newjs_masterRep');
		$screenedTimeHandled 	=$processObj->getStartDate();
		$subMethod		=$processObj->getSubMethod();	  
		$pincodeList		=$this->getPincodeList();
		$profileData 		=$this->applyGenericFilters($profiles, $method,$subMethod);
		unset($profiles);
		foreach($profileData as $pid=>$data)
			$profiles[] =$pid;		
	}
	if($method=='WEBMASTER_LEADS' || $method=='NEW_FAILED_PAYMENT'){
		$profilesFinal =$this->applyGenericFilters($profiles, $method,$processObj->getSubMethod());
		$noLoop =1;
	}

	// filter profiles 
	for($i=0;$i<count($profiles);$i++)
	{
		if($method=='FIELD_SALES'){
			$profileid = $profiles[$i];
			if(!$profileid)
				continue;
			$cityRes 	=$profileData[$profileid]['CITY_RES'];
			$pincode 	=$profileData[$profileid]['PINCODE'];
			$entryDt 	=$profileData[$profileid]['ENTRY_DT'];
			$mtongue	=$profileData[$profileid]['MTONGUE'];

			// mtongue filter
			if(in_array($profileData['MTONGUE'],$excludeMtongue))
				continue;

			// field sales city filter
			if(!in_array("$cityRes",$fieldSalesCity))
				continue;

			// first time screening check
			if($subMethod=='FIELD_SALES'){
				$screenedTimeHandled =date('Y-m-d H:i:s');
				$screenedTimeHandled =date('Y-m-d H:i:s', strtotime('-24 hours',strtotime($screenedTimeHandled)));
				$lastScreenedTime=$screeningObj->lastScreenedTime($profileid,$screenedTimeHandled);	
				if(JSstrToTime($entryDt)>=JSstrToTime($lastScreenedTime)){}
				else
					continue;
			}
			if(!in_array("$cityRes",$pincodeMappedCity) || !$pincode)
				$pincode=0;
			if(!in_array($pincode ,$pincodeList))
				$pincode=0;
			if(!in_array($pincode ,$pincodeList) && in_array($cityRes,$pincodeMappedCity))
				continue;			

			$profilesFinal[$cityRes][$pincode][]=$profileid;
		}
		elseif(!$noLoop){
			$profileid = $profiles[$i]['PROFILEID'];
			if($this->profile_allocated($profileid,$method))
				continue;
			
			if($method=="RENEWAL"){
				$serviceStatusObj=new BILLING_SERVICE_STATUS('newjs_slave');
				$serviceDetails 	=$serviceStatusObj->getLastActiveServiceDetails($profileid);
		                $expiryDate 		=$serviceDetails['EXPIRY_DT'];
                		$serviceStartDate 	=$serviceDetails['ACTIVATED_ON'];
                		$renew30 		=date('Y-m-d 00:00:00', strtotime(' -29 days', strtotime($expiryDate)));

				$expiryDateTime =JSstrToTime($expiryDate);
				if(($expiryDateTime>=$today_day) && ($expiryDateTime<=$check_day)){}
				else
					continue;
		                // Get shard number for db connection
        		        $dbName = JsDbSharding::getShardNo($profileid,1);

		                // Number of Acceptances in E-30(-30 days of expiry) for the current membership
		                $contactsObj = new newjs_CONTACTS($dbName);
		                $eoi_accepted = $contactsObj->countAcceptancesReceived($profileid, $serviceStartDate, $renew30);

		                // Number of Direct Contact Views for the current membership
		                $viewContactsLogObj = new JSADMIN_VIEW_CONTACTS_LOG('newjs_slave');
		                $direct_contacts_view = $viewContactsLogObj->countDirectContactsView($profileid, $serviceStartDate, $renew30);
				if($direct_contacts_view<1 && $eoi_accepted<1)
					continue;
			}
			$status=$alertsObj->fetchMembershipStatus($profileid);
			if($status){
				if($status["MEMB_CALLS"]=='U' || $status["OFFER_CALLS"]=='U')
					continue;
			}
			if(!$this->check_profile($profileid, $method))
				continue;
			$profilesFinal[]=$profileid;
		}
	}
	return $profilesFinal;
}
public function filterProfilesForPreAllocation($profiles,$level,$profilesRequiredCount, $discount_status=0, $everPaidPool)
{
	$jprofileObj		=new JPROFILE('newjs_slave');
	$historyObj		=new incentive_HISTORY('newjs_slave');		
	$jprofileAlertsObj	=new JprofileAlertsCache ('newjs_slave');		
	$jprofileContactObj	= new ProfileContact('newjs_slave');
	$vdObj 			=new billing_VARIABLE_DISCOUNT('newjs_slave');
	$consentDncObj  	=new NEWJS_CONSENTMSG('newjs_slave');
	
	// extracting discounted profiles
	$centerAllocLevelArr =array(1,2,3,4,5,6);
	$pre_all_profileids = array();
	$discountedProfiles = array();

		// New-logic
		if(count($profiles)>0){
			foreach($profiles as $k=>$v){
				if($level==-2||$level==-1||$level==-3){
					if($profiles[$k]['PROFILEID'])
						$pre_all_profileids[] = $profiles[$k]['PROFILEID'];
				}
				else
					$pre_all_profileids[] =$v;
			}
		}
		$profiles =$this->applyGenericFilters($pre_all_profileids,'PRE_ALLOCATION');
		unset($pre_all_profileids);		
		if(count($profiles)>0){
			$profilesStr =implode(",", $profiles);
			$discount_profiles = $vdObj->getDiscountProfileArr($profilesStr);
			if(in_array($level, $centerAllocLevelArr))	
		                $optinProfiles = $consentDncObj->getOptinProfileArr($profiles);
		}
                for($i=0;$i<count($profiles);$i++){
                        if(in_array($profiles[$i],$discount_profiles))
                                $discountedProfiles[] = $profiles[$i];
                }

	if($discount_status==1 && count($discountedProfiles)>=0)
		$profiles = $discountedProfiles;
	if($discount_status==0 && count($profiles)>=0){
		if(count($discountedProfiles)>0)
			$profiles = array_diff($profiles, $discountedProfiles);
	}
	if(count($profiles)>0)
		$profiles =array_values($profiles);
	if(count($optinProfiles)==0)
		$optinProfiles =array();
	// End discount profiles	

	for($i=0;$i<count($profiles);$i++)
	{
		$profileid= $profiles[$i];
		$status=$jprofileAlertsObj->fetchMembershipStatus($profileid);
		if($status){
			if($status["MEMB_CALLS"]=='U' || $status["OFFER_CALLS"]=='U')
				continue;
		}

		$fields="DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,GENDER,DTOFBIRTH,SOURCE,SEC_SOURCE,PHONE_WITH_STD,PHONE_RES,PHONE_MOB,ISD,STD,COUNTRY_RES,HAVE_JCONTACT,MOB_STATUS,LANDL_STATUS,ENTRY_DT,INCOME,FAMILY_INCOME,MTONGUE,ACTIVATE_ON,SUBSCRIPTION,HAVEPHOTO,RELATION,INCOMPLETE";
		$valueArray['ACTIVATED']="'Y'";
		$valueArray['INCOMPLETE']="'N'";
		$valueArray['PROFILEID']="$profileid";
		$excludeArray['PHONE_FLAG'] ="'I'";

		if($level != -1 && $level !=-3 && $level!=-2){
			if($level==0 || $level==-4 || $level==-5){
				$lastLoginFiter = date('Y-m-d',time()-15*86400);
				$greaterArray['LAST_LOGIN_DT'] ="$lastLoginFiter";
			}
		}
		$profileData=$jprofileObj->getArray($valueArray,$excludeArray,$greaterArray,$fields,$lessArray);
		if($profileData)
		{
			 $dob=$profileData[0]['DTOFBIRTH'];
                         $age=$this->getAge($dob);
                         $gender=$profileData[0]['GENDER'];
                         if($gender=='M'&&$age<=23)
				continue;
			if((strstr($profileData[0]['SUBSCRIPTION'],"F")!="")||(strstr($profileData[0]['SUBSCRIPTION'],"D")!=""))
					continue;
			$ISD=$profileData[0]['ISD'];
			$indianNo =$this->isIndianNo($ISD);
			if($indianNo && $level ==-5){
				continue;
			}
			if(!$indianNo && $level !=-2 && $level != -1 && $level !=-5)
				continue;
			$permanent_excluded=0;
			$premiumProfile=FALSE;
			/*$profileObj=new Profile("",$profileid);
			$ftoStatesObj=$profileObj->getPROFILE_STATE()->getFTOStates();
			$ftoState= $ftoStatesObj->getState();*/
			$isDNC=0;
			$phoneVerified="N";
			$mtongue=$profileData[0]['MTONGUE'];
			$income=$profileData[0]['INCOME'];
			$familyIncome=$profileData[0]['FAMILY_INCOME'];
			$premiumIncome=array(13,14,17,18,19,20,21,22,23,24,25,26,27);
			if($level==-2)
				array_push($premiumIncome,12,16);
			$exclude_mtongue=array(1,3,16,17,31);
			if((in_array("$income",$premiumIncome)||in_array("$familyIncome",$premiumIncome)||!$indianNo)&&!in_array("$mtongue",$exclude_mtongue))
				$premiumProfile=TRUE;
			//only premium profiles at premium level
			if(!$premiumProfile && ($level == -2 || $level == -1))
				continue;
			$thirdDay  =date('Y-m-d',time()-3*86400);
			$secondDay =date('Y-m-d',time()-2*86400);

			$profileRegDate=date('Y-m-d',JSstrToTime($profileData[0]['ENTRY_DT']));
			//premium profiles activated 3 days before or earlier to non premium level
			if($premiumProfile && JSstrToTime($profileRegDate) > JSstrToTime($thirdDay) && $level!=-1 && $level!=-2 )
				continue;
			if(!$premiumProfile && JSstrToTime($profileRegDate) > JSstrToTime($secondDay) && $level!=-1 && $level!=-2)
				continue;
			if(($level!=-1 && $level!=-2 && $level!=-5)||($level==-1 && $indianNo)||($level==-2 && $indianNo))
			{
				$phoneNumStack  =array();
				$haveJContact   =$profileData[0]['HAVE_JCONTACT'];
				$phone_res      =$profileData[0]['PHONE_WITH_STD'];
				if(!$phone_res && $profileData[0]['PHONE_RES'])
					$phone_res =$profileData[0]['STD'].$profileData[0]['PHONE_RES'];
				if($phone_res){
					$phone_res =$this->phoneNumberCheck($phone_res);
					if($phone_res)
						array_push($phoneNumStack,"$phone_res");
				}
				$phone_mob      =$profileData[0]['PHONE_MOB'];
				if($phone_mob){
					$phone_mob =$this->phoneNumberCheck($phone_mob);
					if($phone_mob)
						array_push($phoneNumStack,"$phone_mob");
				}
				if($level==-3){
					unset($valueArray);
					$valueArray['PROFILEID']=$profileid;
					$fieldsRequired="ALT_MOBILE,ALT_MOB_STATUS";
					$contactDetails=$jprofileContactObj->getArray($valueArray,"","",$fieldsRequired);
					$phone_alternate=$contactDetails[0]['ALT_MOBILE'];
				}
				else	
					$phone_alternate=$this->getOtherPhoneNums($profileid);
				if($phone_alternate){
					$phone_alternate =$this->phoneNumberCheck($phone_alternate);
					if($phone_alternate)
						array_push($phoneNumStack,"$phone_alternate");
				}
				$DNCArray =$this->checkDNC($phoneNumStack);
				if($level!=-4 && $level!=0)
					$isDNC    =$DNCArray['STATUS'];

				if($haveJContact=='Y' || $phone_alternate || $profileData[0]['MOB_STATUS']=='Y' || $profileData[0]['LANDL_STATUS']=='Y'||$contactDetails[0]['ALT_MOB_STATUS']=='Y')
					$phoneVerified ='Y';
				else
					$phoneVerified ='N';
			}
			if($level==-3){
				$source=$profileData[0]['SOURCE'];
				$secSource=$profileData[0]['SEC_SOURCE'];
				if($source=="onoffreg"||$secSource=="C")
					continue;
			}
			//if($this->check_profile($profileid))
			//{
				if(!in_array($level,$centerAllocLevelArr)){
					if(!$isDNC && $level!=-1 && $level!=-2 && $level!=-4 && $level!=0 && $level!=-5)
						continue;
					if($level==-2 && $indianNo && (!$isDNC && $phoneVerified!='Y'))
						continue;
				}
				elseif(in_array($level, $centerAllocLevelArr) && $isDNC){
                                        //$optinStatus =$this->isOptinProfile($profileid);
                                        //if(!$optinStatus)
					if(!in_array($profileid, $optinProfiles))
                                                continue;
				}
				if($level!=-3)
				{
					//PARMANENT EXCLUSION RULE
					$permanent_excluded=0;
					$excl_d_dt=date('Y-m-d',time()-(45-1)*86400);
					$excl_dnc_dt=date('Y-m-d',time()-(45-1)*86400);
					$excl_ni_dt=date('Y-m-d',time()-(15-1)*86400);
					$excl_cf_dt=date('Y-m-d',time()-(7-1)*86400);

					//disposition
					$fields		="ENTRY_DT,DISPOSITION";
					$whereClause	="PROFILEID=$profileid";
					$orderBy	=" ENTRY_DT DESC";
					//$history=$historyObj->get($profileid,$fields,$whereClause,$orderBy," LIMIT 1");
                                        $allDispositionCount	=0;
                                        $singleDispostionCount	=0;
                                        $historyExist 		='N';
					unset($history);
                                        $historyArr =$historyObj->getHistoryArray($profileid,$fields,$whereClause,$orderBy);
                                        if(count($historyArr)>0){
                                                foreach($historyArr as $key=>$histData){
                                                        $historyExist ='O';
                                                        if(!$allDispositionCount)
                                                                $history =$histData;
                                                        $allDispositionCount +=1;
                                                        if($histData['DISPOSITION']=='CNC')
                                                                $singleDispostionCount +=1;
                                                }
                                        }
					if($history){
						if(($history["DISPOSITION"]=='NI' && $history["ENTRY_DT"]>=$excl_ni_dt) || ($history["DISPOSITION"]=='D'&& $history["ENTRY_DT"]>=$excl_d_dt )|| ($history["DISPOSITION"]=='DNC' && $history["ENTRY_DT"]>=$excl_dnc_dt) || ($history["DISPOSITION"]=='CF' && $history["ENTRY_DT"]>=$excl_cf_dt))
							continue;
					}
				}
				/*$status=$jprofileAlertsObj->fetchMembershipStatus($profileid);
				if($status){
					if($status["MEMB_CALLS"]=='U' || $status["OFFER_CALLS"]=='U')
						continue;
				}*/
				$go=1;
				if(($profileData[0]['PHONE_RES'] || $profileData[0]['PHONE_MOB'] || $phone_alternate ) && $level!=-1 && $level !=-2 && $level !=-5){
					$go=0;
						if($this->isIndianNo($profileData[0]['ISD']))
							$go=1;
				}
				//$allocated=$this->profile_allocated($profileid);
				//if($allocated || !$go)
				if(!$go)
					continue;

				//Code for JSC-77
				// Condition to check ever paid profile
				if(!in_array($profileid, $everPaidPool)){
	                                /*$allDispositionCountArr    =$historyObj->getCountOfDispositionArr($profileid);
        	                        $singleDispostionCount   =$historyObj->getCountOfDisposition($profileid,'CNC'); 
					if(count($allDispositionCountArr)>0){	
						foreach($allDispositionCountArr as $key=>$val)
							$allDispositionCount +=$val;
					}	
					$singleDispostionCount =$allDispositionCountArr['CNC'];*/
        	                        if($allDispositionCount>25 || $singleDispostionCount >=5)
        	                                continue;
				}
                                //End
				/*if($level==-3){
					$profiles[$i]['LAST_LOGIN_DT']=$profileData[0]['LAST_LOGIN_DT'];
					$profilesArray[]=$profiles[$i];
				}
				else{*/
					$new_profiles[$i]['PROFILE_TYPE'] =$historyExist;
					$new_profiles[$i]['LAST_LOGIN_DT']=$profileData[0]['LAST_LOGIN_DT'];
					$new_profiles[$i]['PROFILEID'] 	  =$profileid;
					$profilesArray[]		  =$new_profiles[$i];
				//}
				$finalCount=count($profilesArray);
				if($finalCount>=$profilesRequiredCount)
					break;
			//}                    
		}
	}
	return $profilesArray;
}
public function fetchProfilesCda($processObj)
{	
	$crmDailyAllotObj=new CRM_DAILY_ALLOT('newjs_slave');
	if($processObj->getSubMethod()=="NO_LONGER_WORKING")
	{
		$id_arr=Array();
		$pid_arr=$processObj->getProfiles();
		$username=$processObj->getUsername();
		for($i=0;$i<count($pid_arr);$i++)
		{
			$pid=$pid_arr[$i];
			if($pid)
			{
				$ids_arr =$crmDailyAllotObj->fetchProfilesAlloted($pid,$username);
				$id_arr[]=$ids_arr[0];
			}
			//$id_arr=array_merge($id_arr,$ids_arr);
		}
		return $id_arr;
	}
	elseif($processObj->getSubMethod()=="RELEASE_PROFILE")
	{
		$pid=$processObj->getProfiles();
		$name=$processObj->getUsername();
		$id_arr=$crmDailyAllotObj->fetchProfiles($pid[0],$name);	
		return $id_arr;
	}
}
public function fetchHistoryOfProfiles($profiles)
{
	$historyObj=new incentive_HISTORY('newjs_slave');
	for($i=0;$i<count($profiles);$i++)
	{
		$entryDt=$historyObj->fetchHistoryLastEntryDt($profiles[$i]['PROFILEID']);
		$profiles[$i]['ENTRY_DT']=$entryDt;
	}
	return $profiles;
}

public function fetchTempProfiles($processObj)
{
	$tempAllocBucketObj=new TEMP_ALLOCATION_BUCKET();
	$profiles=$tempAllocBucketObj->fetchProfiles($processObj);
	return $profiles;
}
public function fetchDispositionOrder()
{
	$disposition_order_arr =array(1=>"D",2=>"DNC",3=>"CF",4=>"NI",5=>"CNC",6=>"SEQ",7=>"L",8=>"A",9=>"AA",10=>"SPR",11=>"SC");
	return $disposition_order_arr;
}
public function fetchDispositionNegativeListOrder()
{
	$disposition_del_arr=array(0=>"D",1=>"DNC",2=>"CF",3=>"NI");
	$disposition_del_str =@implode("','",$disposition_del_arr);
	return $disposition_del_arr;
}
public function getprivilage($checksum)
{
	list($md, $userno)=explode("i",$checksum);
	if(md5($userno)!=$md)
	    return FALSE;
	else
	{
		/*$jsadConObj=new jsadmin_CONNECT();
		$user=$jsadConObj->fetchUser($userno);
		if ($user)
		{
			$jsadPswrdsObj=new jsadmin_PSWRDS();
			$priv=$jsadPswrdsObj->getPrivilage($user);
			if($priv)
				$ret = $priv;
			else
				$ret = FALSE;
		}
		else
		{
			$ret = FALSE;
		}*/
		$backendLibObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),crmCommonConfig::$useCrmMemcache);
		$details = $backendLibObj->fetchPSWRDSDetailsBySessionID($userno,"","PRIVILAGE");
		unset($backendLibObj);
		if(is_array($details) && $details)
			$ret = $details[0]["PRIVILAGE"];
		else
			$ret = FALSE;
	}
	return $ret;
}
public function fetchAgentName($cid)
{
	$temp   =@explode("i",$cid);
	$id     =$temp[1];
	if($id)
	{	
		$backendLibObj = new backendActionsLib(array("jsadmin_PSWRDS"=>"newjs_slave","jsadmin_CONNECT"=>"newjs_master"),crmCommonConfig::$useCrmMemcache);
		$details = $backendLibObj->fetchPSWRDSDetailsBySessionID($id,"","USERNAME");
		unset($backendLibObj);
		if(is_array($details) && $details)
			$username = $details[0]["USERNAME"];
		else
			return false;
		/*$jsadConObj     =new jsadmin_CONNECT();
		$user           =$jsadConObj->fetchUser($id);
		$jsadPswrdsObj  =new jsadmin_PSWRDS();
		$username       =$jsadPswrdsObj->getName($user);*/
		return $username;
	}
	return false;
}
public function getLocationsCenters()
{
	$locsObj=new incentive_LOCATIONS();
	$locations=$locsObj->getLocationsVal();
	$noOfLocs=count($locations);
	$centers=array();
	for($i=0;$i<$noOfStates;$i++)
	{       
		$centers=$this->getSubLocationsCenters($locations[$i]);
		$final_centers=array_merge($centers,$final_centers);
	}
	return $final_centers;

}
public function getSubLocationsCenters()
{
	$subLocsObj=new incentive_SUBLOCATIONS();
	$centers=$subLocsObj->getSubLocsVal();
	return $centers;
}
public function getStatesCenters()
{
	$stateObj=new incentive_BRANCH_STATE();
	$states=$stateObj->getStateVal();
	$noOfStates=count($states);
	$centers=array();
	for($i=0;$i<$noOfStates;$i++)
	{
		$centers=$this->getLocationsCenters($states[$i]);
		$final_centers=array_merge($centers,$final_centers);
	}
	return $final_centers;
}

public function fetchExecutiveDetails($agent)
{
	$jsAdminPSWRDSObj =new jsadmin_PSWRDS();
	$agentDetails =$jsAdminPSWRDSObj->getExecutiveDetails($agent);
	return $agentDetails;
}
public function fetchDiscount($profileidArr=array(),$profileDetailsArr=array())
{
	if(count($profileidArr)==0)
		return;

	$varDiscountObj =new billing_VARIABLE_DISCOUNT;
	$profileStr     =implode(",",$profileidArr);
	$discountArr    =$varDiscountObj->getDiscount($profileStr);
	if($discountArr){
		foreach($discountArr as $key=>$val){
			$profileid      =$key;
			$dataArr        =$discountArr[$profileid];
			$discount       =$dataArr['DISCOUNT'];
			$date           =$dataArr['EDATE'];
			$dateArr        =explode("-",$date);
			$eDate          =$dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
			if($discount)
				$profileDetailsArr[$profileid]['DISCOUNT'] ="Up to ".$discount."% valid till ".$eDate;
		}
	}
	return $profileDetailsArr;
}
function fetchAllocationDetails($profileidArr=array(),$profileDetailsArr=array())
{
	if(count($profileidArr)==0)
		return;

	$mainAdminObj   	=new incentive_MAIN_ADMIN();
	$profileStr             =implode(",",$profileidArr);
	$valueArr['PROFILEID']  =$profileStr;
	$fields                 ="PROFILEID,ALLOT_TIME,CONVINCE_TIME,ALLOTED_TO";
	$result                 =$mainAdminObj->getArray($valueArr,'','',$fields);
	foreach($result as $key=>$val){
		$pid                                    	=$val['PROFILEID'];
		$profileDetailsArr[$pid]["ALLOT_TIME"]         	=$val['ALLOT_TIME'];
		$profileDetailsArr[$pid]["CONVINCE_TIME"]      	=$val['CONVINCE_TIME'];
		$profileDetailsArr[$pid]["ALLOTED_TO"]       	=$val['ALLOTED_TO'];
	}
	return $profileDetailsArr;
}
function fetchFreeProfiles($profilesArr)
{
	if(count($profilesArr)==0)
		return;
	$jprofileObj		 =new JPROFILE();
	$profilesStr             =@implode(",",$profilesArr);
	$valueArr['PROFILEID']   =$profilesStr;
	$result                  =$jprofileObj->getArray($valueArr,'','','PROFILEID,SUBSCRIPTION');
	foreach($result as $key=>$val){
			if((strstr($val['SUBSCRIPTION'],"F")=="") && (strstr($val['SUBSCRIPTION'],"D")==""))
				$profileArr[] =$val['PROFILEID'];
	}
	return $profileArr;
}
public function fetchOrderDetails($profileid)
{
	$crmUtilityObj 	 =new crmUtility();
	$servicesObj	 =new billing_SERVICES();
	$orderDetailsObj =new BILLING_ORDERS();
	$orderDetails 	 =$orderDetailsObj->getOrderDetails($profileid);
	if(count($orderDetails)>0){
		$i =0;
		foreach($orderDetails as $key=>$val){
			$arr[$i]["ORDERID"]	=$val['ORDERID']."-".$val['ID'];
			$arr[$i]["ENTRY_DT"]	=$val['ENTRY_DT'];
			$service		=$val['SERVICEMAIN'];
			$paymode		=$val['PAYMODE'];
			$curtype 		=$val['CURTYPE'];
			$arr[$i]["PAYMODE"]	=$crmUtilityObj->fetchPayMode($paymode,$curtype);
			if($service)
				$serviceArr 	=@explode(",",$service);
				$serviceStr 	="'".implode("','",$serviceArr)."'";
				$serviceArrNew 	=$servicesObj->getServices($serviceStr);				
				$arr[$i]["SERVICE"]	=@implode(",",$serviceArrNew);;
			$i++;
		}
		return $arr;
	}
}
function fetchProfileDetails($profilesArr,$subMethod='',$fields='')
{
	if(!$fields)
		$fields ="USERNAME,EMAIL,PROFILEID,AGE,CITY_RES,AGE,ACTIVATED,GENDER,ENTRY_DT,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,PHONE_MOB,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,HAVEPHOTO,SUBSCRIPTION,RELATION,DTOFBIRTH,PINCODE,CONTACT,ISD";

	$profileStr=implode(",",$profilesArr);
	if($subMethod=='NEW_PROFILES' || $subMethod=='FOLLOWUP') {
		$billPurObj = new billing_PURCHASES('newjs_slave');
		$everPaidProfiles = $billPurObj->isPaidEver($profileStr);
	}
	if($profileStr){
		$crmUtilityObj          =new crmUtility();	
		$cityObj		=new newjs_CITY_NEW();
		$usernameObj		=new incentive_NAME_OF_USER();
		$jprofileObj		=new JPROFILE();
		$valueStr['PROFILEID']	=$profileStr;
		$setProfileArr1		=$jprofileObj->getArray($valueStr,'','',$fields);

		foreach($setProfileArr1 as $key=>$val){
			$pid				 	=$val['PROFILEID'];
			$temp_email     		        =explode("@",$val["EMAIL"]);
			$email          		        =$temp_email[0]."@xxx.com";
			$setProfileArr[$pid]["EMAIL"]           =$email;
			$isdNo					=$val['ISD'];

			$city           		        =$cityObj->getCityLabel($val['CITY_RES']);
			$setProfileArr[$pid]["CITY_INDIA"]      =$city['LABEL'];

			$setProfileArr[$pid]["NAME"]            =$usernameObj->getName($pid);
			$setProfileArr[$pid]["ENTRY_DT"]        =$crmUtilityObj->fetchIST($val['ENTRY_DT']);
			$setProfileArr[$pid]["RELATION"]        =FieldMap::getFieldLabel("relation",$val['RELATION']);
			$setProfileArr[$pid]["EOI_SENT"]	=$this->fetchEoiSent($pid);
			$setProfileArr[$pid]["PHOTO_REQ_REC"]   =$this->fetchPhotoRequestReceived($pid);
			$setProfileArr[$pid]["MOB_NO"]          =$this->phoneNumberCheck($val['PHONE_MOB'], $isdNo);
			$setProfileArr[$pid]["RES_NO"]  	=$this->phoneNumberCheck($val['PHONE_WITH_STD'], $isdNo);
			$setProfileArr[$pid]["ALTERNATE_NO"]	=$this->phoneNumberCheck($this->getOtherPhoneNums($pid), $isdNo);
			$setProfileArr[$pid]["PROFILEID"]       =$val['PROFILEID'];
			$setProfileArr[$pid]["USERNAME"]        =$val['USERNAME'];
			$setProfileArr[$pid]["AGE"]             =$this->getAge($val['DTOFBIRTH']);
			$setProfileArr[$pid]["ACTIVATED"]       =$val['ACTIVATED'];
			$setProfileArr[$pid]["GENDER"]          =$val['GENDER'];
			$setProfileArr[$pid]["LAST_LOGIN_DT"]   =$crmUtilityObj->fetchIST($val['LAST_LOGIN_DT']);
			$setProfileArr[$pid]["SUBSCRIPTION"]    =$val['SUBSCRIPTION'];
			$setProfileArr[$pid]["MOB_STATUS"] 	=$val['MOB_STATUS'];
			$setProfileArr[$pid]["LANDL_STATUS"]    =$val['LANDL_STATUS'];
			$setProfileArr[$pid]["HAVEPHOTO"]       =$val['HAVEPHOTO'];
			$setProfileArr[$pid]["ADDRESS"]       	=trim($val['CONTACT']);
			$setProfileArr[$pid]["PINCODE"]       	=$val['PINCODE'];
			$setProfileArr[$pid]["ISD"]         	=$isdNo;

		        if($subMethod=='NEW_PROFILES' || $subMethod=='ONLINE_NEW_PROFILES' || $subMethod=='FOLLOWUP' || $subMethod=='FFOLLOWUP'){
        		        if($setProfileArr[$pid]["MOB_NO"] && $isdNo)
                        		$setProfileArr[$pid]["MOB_NO"] =$isdNo."-".$setProfileArr[$pid]["MOB_NO"];
                		if($setProfileArr[$pid]["RES_NO"] && $isdNo)
                        		$setProfileArr[$pid]["RES_NO"] =$isdNo."-".$setProfileArr[$pid]["RES_NO"];
				if($setProfileArr[$pid]["ALTERNATE_NO"] && $isdNo)
					$setProfileArr[$pid]["ALTERNATE_NO"] =$isdNo."-".$setProfileArr[$pid]["ALTERNATE_NO"];
				if($subMethod=='NEW_PROFILES' || $subMethod=='FOLLOWUP') {
					if(in_array($pid, array_keys($everPaidProfiles))) {
						$setProfileArr[$pid]["EVER_PAID"] = 'Y';
					} else {
						$setProfileArr[$pid]["EVER_PAID"] = 'N';
					}
				}
			}
			
			$setProfileArr[$pid]["CHECKSUM"]         =md5($pid)."i".$pid;

		}
	}
	foreach($setProfileArr as $key=>$val)
		$profileidArr[] =$key;

	$setProfileArr =$this->fetchJprofileContact($profileidArr,$setProfileArr);
	$setProfileArr =$this->fetchServiceDetails($setProfileArr);
	$setProfileArr =$this->fetchDiscount($profileidArr,$setProfileArr);
	if($subMethod=='HANDLED' || $subMethod=='FAILED_PAYMENT' || $subMethod=='PAYMENT_HITS'){
		$setProfileArr =$this->fetchAllocationDetails($profileidArr,$setProfileArr);
		if($subMethod=='FAILED_PAYMENT' || $subMethod=='PAYMENT_HITS')
			$setProfileArr =$this->fetchPaymentOrderStatusCount($profileidArr,$setProfileArr,$subMethod);
	}
	if($subMethod=='NEW_FAILED_PAYMENT')
		$setProfileArr =$this->fetchNewFailedPaymentDetals($profileidArr,$setProfileArr);	
	if($subMethod=='NEW_PROFILES')
		$setProfileArr =$this->fetchPreAllotedProfileType($profileidArr,$setProfileArr);

	return $setProfileArr;
}

// $profileidArr as Input parameter, $profileDetailsArr as Output parameter
public function fetchPaymentOrderStatusCount($profileidArr=array(),$profileDetailsArr=array(),$subMethod)
{
	if(count($profileidArr)==0)
		return;
	$orderDetailsObj=new BILLING_ORDERS();
	$historyObj     =new incentive_HISTORY();
	$paymentHistObj =new BILLING_PAYMENT_HITS();
	if(count($profileidArr)>0){
		foreach($profileidArr as $key=>$profileid){
			$agentName =$profileDetailsArr[$profileid]['ALLOTED_TO'];
			$lastDispositionDt =$historyObj->fetchHistoryLastEntryDt($profileid,$agentName);
			if($subMethod=='FAILED_PAYMENT')
				$profileHitsCount =$orderDetailsObj->getFailedPaymentCount($profileid,$lastDispositionDt);
			elseif($subMethod=='PAYMENT_HITS')
				$profileHitsCount =$paymentHistObj->getMembershiptHitCount($profileid,$lastDispositionDt);
			$profileDetailsArr[$profileid]['TIMES_TRIED'] =$profileHitsCount;
		}
	}
	return $profileDetailsArr;
}

// $profileidArr as Input parameter, $profileDetailsArr as Output parameter
public function fetchJprofileContact($profileidArr=array(),$profileDetailsArr=array())
{
	if(count($profileidArr)==0)
		return;

	$jprofileContactObj    = new ProfileContact();
	$valueArr['PROFILEID']  =@implode(",",$profileidArr);;
	$result                 =$jprofileContactObj->getArray($valueArr,'','','PROFILEID,ALT_MOBILE,ALT_MOB_STATUS,ALT_MOBILE_ISD');
	if($result){
		foreach($result as $key=>$val){
			$pid                               	=$val['PROFILEID'];
			$profileDetailsArr[$pid]['ALT_MOB_STATUS']	=$val['ALT_MOB_STATUS'];
			$profileDetailsArr[$pid]["ALT_MOBILE"]  	=$this->phoneNumberCheck($val['ALT_MOBILE']);
			$altMobIsd					=$val['ALT_MOBILE_ISD'];	
			if($altMobIsd && $profileDetailsArr[$pid]["ALT_MOBILE"])
				$profileDetailsArr[$pid]["ALT_MOBILE"] =$altMobIsd."-".$profileDetailsArr[$pid]["ALT_MOBILE"];
		}
	}
	return $profileDetailsArr;
}
public function fetchPreAllotedProfileType($profileidArr=array(),$profileDetailsArr=array())
{
	if(count($profileidArr)==0)
		return;
	$profileAllocTechObj	=new incentive_PROFILE_ALLOCATION_TECH();
	$profileStr             =@implode(",",$profileidArr);
	$result            	=$profileAllocTechObj->getProfileType($profileStr);
	if($result){
		foreach($result as $key=>$val)
			$profileDetailsArr[$key]['PROFILE_TYPE'] =$val;
	}
	return $profileDetailsArr;
}

// function to fetch the details of New failed payment profiles
public function fetchNewFailedPaymentDetals($profileidArr=array(),$profileDetailsArr=array())
{
	if(count($profileidArr)==0)
		return;
	$crmUtilityObj     =new crmUtility();
	$servicesObj       =new billing_SERVICES();
	$trackFailedPayObj =new billing_TRACKING_FAILED_PAYMENT();
	foreach($profileidArr as $key=>$pid){
		$profileDetails 			=$trackFailedPayObj->getLatestProfileDetails($pid);
		$profileDetailsArr[$pid]['ENTRY_DT'] 	=$crmUtilityObj->fetchIST($profileDetails['ENTRY_DT']);

		$serviceArr 				=@explode(",",$profileDetails['SERVICES']);
		$serviceStr     			="'".implode("','",$serviceArr)."'";
		$serviceArrNew  			=$servicesObj->getServices($serviceStr);
		$profileDetailsArr[$pid]["SERVICES"]	=@implode(",",$serviceArrNew);;

		$profileDetailsArr[$pid]['NET_AMOUNT'] 	=$profileDetails['NET_AMOUNT'];
		$profileDetailsArr[$pid]['DISCOUNT'] 	=$profileDetails['DISCOUNT'];
		$profileDetailsArr[$pid]['CURRENCY'] 	=$profileDetails['CURRENCY'];
		$profileDetailsArr[$pid]['PAYMENT_OPTION_SELECTED'] =$profileDetails['PAYMENT_OPTION_SELECTED'];
	}
	return $profileDetailsArr;
}

// function to fetch the filed payments profiles
public function fetchNewFailedPaymentEligibleProfiles($processName='',$startDt='',$endDt='')
{
	//$jprofileObj		=new JPROFILE();
	if($processName=='SALES_REGULAR'){
	$purchasesObj		=new billing_PURCHASES('newjs_slave');
	$paymentCollectObj	=new incentive_PAYMENT_COLLECT('newjs_slave');
	$trackFailedPayObj	=new billing_TRACKING_FAILED_PAYMENT('newjs_slave');
	$trackFailedPayLogObj   =new billing_TRACKING_FAILED_PAYMENT_LOG('newjs_slave');
	}
	else{
	$purchasesObj           =new billing_PURCHASES();
        $paymentCollectObj      =new incentive_PAYMENT_COLLECT();
        $trackFailedPayObj      =new billing_TRACKING_FAILED_PAYMENT();
        $trackFailedPayLogObj   =new billing_TRACKING_FAILED_PAYMENT_LOG();
	}
	if($processName=='SALES_REGULAR'){
		$startDt	=date("Y-m-d H:i:s", time()-25*60*60);			
		$endDt 		=date("Y-m-d H:i:s", time());
	}	

	$profiles 		=$trackFailedPayObj->getFailedPaymentProfiles($startDt, $endDt);

	// everPaid logic check
	$profileidIdArr 	=array();
	$everPaidProfileArr 	=array();
	foreach($profiles as $key=>$val){
		$profileidIdArr[] =$val['PROFILEID'];
	}
	if(count($profileidIdArr)>0){
		$profileStr =implode(",",$profileidIdArr);
		$everPaidProfileArr =array_keys($purchasesObj->isPaidEver($profileStr));
	}
	// everPaid

	for($i=0; $i<count($profiles); $i++){
		$profileid 	=$profiles[$i]['PROFILEID'];	
		$fPaymentDate 	=$profiles[$i]['ENTRY_DT'];
		$servicesSelected=$profiles[$i]['SERVICES'];
		$source		=$profiles[$i]['SOURCE'];
		$net_amount     =$profiles[$i]['NET_AMOUNT'];
		$discount       =$profiles[$i]['DISCOUNT'];
		$source		=$profiles[$i]['SOURCE'];

		$lastPurchaseDate =$purchasesObj->getLastPurchaseDate($profileid);				
		if(JSstrToTime($lastPurchaseDate)>JSstrToTime($fPaymentDate))
			continue;
		$lastOfflineOrderDate =$paymentCollectObj->getLastOfflineOrderGeneratedDate($profileid);
		if(JSstrToTime($lastOfflineOrderDate)>JSstrToTime($fPaymentDate))
			continue;

		$everPaid =false;
		if(in_array("$profileid",$everPaidProfileArr))
			$everPaid =true;

		if($source=='Android_app' || $source=='mobile_website')
                {
       	                $cnt=$trackFailedPayLogObj->getFailedPaymentProfilesLogSourceWise($profileid,$source);
                        if($cnt<3 && !$everPaid)
       	                        continue;
               	}

		if($processName=='failedPaymentInDialer'){
			$profilesFinalArr[] = array('PROFILEID'=>"$profileid",'FP_ENTRY_DT'=>"$fPaymentDate",'SERVICES'=>"$servicesSelected",'NET_AMOUNT'=>"$net_amount",'DISCOUNT'=>"$discount",'SOURCE'=>"$source");
		}
		else{
			$profilesFinalArr[] =$profileid;
		}
	}
	if($processName=='AGENT_NOTIFICATIONS')
	{
		$adminObj = new AllocatedProfiles();
		$profilesFinalArr = $adminObj->getAgentsForProfile($profilesFinalArr,'newjs_slave');
		unset($adminObj);
	}
	return $profilesFinalArr;
}

public function fetchWebmasterLeadsEligibleProfiles($subMethod='', $startDt='', $endDt='',$processObj='')
{
        $execCallbackObj      =new billing_EXC_CALLBACK();
	$crmUtilityObj        =new crmUtility();
	if($subMethod!='RCB_WEBMASTER_LEADS'){
		$startDt        =date("Y-m-d H:i:s", time()-2*60*60);
        	$endDt          =date("Y-m-d H:i:s", time());
                $startDt        =$crmUtilityObj->getIST($startDt);
                $endDt          =$crmUtilityObj->getIST($endDt);
	}
        if($subMethod == "WEBMASTER_LEADS_EXCLUSIVE"){
            $profiles =$execCallbackObj->getWebmasterLeadsForExclusive($startDt, $endDt);
        }
        elseif($subMethod == "RCB_WEBMASTER_LEADS"){
            $profilesNew 	=$execCallbackObj->getRcbLeads($startDt, $endDt);
	    $profilesFinalArr 	=array_keys($profilesNew);
	    $processObj->setProfiles($profilesNew); 		
	    return $profilesFinalArr;	
	}
        else{
            $profiles =$execCallbackObj->getWebmasterLeads($startDt, $endDt);
        }
	for($i=0; $i<count($profiles); $i++){
                $profileid      =$profiles[$i]['PROFILEID'];
                $profilesFinalArr[] =$profileid;
        }
        return $profilesFinalArr;
}

public function fetchOutboundProfiles($processObj)
{
	$subMethod      =$processObj->getSubMethod();
	$agentName  	=$processObj->getExecutive();	
	$mainAdminObj	=new incentive_MAIN_ADMIN();

	if($subMethod=="NEW_PROFILES"){
		$profileAllocTechObj   	=new incentive_PROFILE_ALLOCATION_TECH();
		$profileArr           	=$profileAllocTechObj->getPreAllocatedProfiles($agentName);
	}
	else if($subMethod=="ONLINE_NEW_PROFILES"){
                $profileAllocTechObj    =new incentive_PROFILE_ALLOCATION_TECH();
                //$recentusersObj       =new userplane_recentusers();
		$onlineProfiles		=$this->getOnlineProfiles();
                $preallocateArr         =$profileAllocTechObj->getPreAllocatedProfiles($agentName);
		for($i=0; $i<count($preallocateArr); $i++){
			$profileid =$preallocateArr[$i]['PROFILEID'];
			if(in_array($profileid, $onlineProfiles))
				$profileArr[] = $profileid;
		}
		/*if(count($idArr)>0)
	                $profileArr = $recentusersObj->fetchOnlineProfiles($idArr);*/
	}
	else if($subMethod=="FTA"){
		$ftaAllocTechObj        =new incentive_FTA_ALLOCATION_TECH();
		$profileArr             =$ftaAllocTechObj->getPreAllotedFtaProfiles($agentName);
	}
	else if($subMethod=="FAILED_PAYMENT" || $subMethod=="PAYMENT_HITS")
		$profileArr =$this->fetchProfilesForPaymentStatus($processObj);
	else if($subMethod=="NEW_FAILED_PAYMENT")
		$profileArr =$mainAdminObj->getProfilesForStatus($agentName,'FP');
	else if($subMethod=="WEBMASTER_LEADS")
                $profileArr =$mainAdminObj->getProfilesForStatus($agentName,'WL');
	else if($subMethod=="FIELD_SALES")
		$profileArr =$mainAdminObj->getProfilesForStatus($agentName,'FS');	
	else if($subMethod=="FOLLOWUP" || $subMethod=="FFOLLOWUP")
		$profileArr =$this->fetchProfilesWithDate($processObj);
	elseif($subMethod=="HANDLED")
		$profileArr =$this->fetchProfilesWithPaidDate($processObj);
	elseif($subMethod=="RENEWAL")
		 $profileArr =$mainAdminObj->getProfilesForStatus($agentName,'R');
	elseif($subMethod=="UPSELL")
		$profileArr =$mainAdminObj->getProfilesForStatus($agentName,'U');
	elseif($subMethod=="SUB_EXPIRY"){
		$profileArr1 =$this->fetchProfilesWithPaidDate($processObj);
		$profileArr2 =$mainAdminObj->getProfilesForStatus($agentName,'R');
		if($profileArr2)
			$profileArr  =array_diff($profileArr1,$profileArr2);
		else
			$profileArr = $profileArr1;
	}
	elseif($subMethod=="RENEWAL_NOT_DUE"){
		$profileArr2 =array();
		$profileArr1 =$this->fetchProfilesWithPaidDate($processObj);
		$profileArr2 =$mainAdminObj->getProfilesForStatus($agentName,'U');
		if($profileArr2)
			$profileArr  =array_diff($profileArr1,$profileArr2);
		else
			$profileArr = $profileArr1;
	}
	return $profileArr;
}

// function to fetch profiles from MAIN_ADMIN based payment order status 
public function fetchProfilesForPaymentStatus($processObj)
{
	$mainAdminObj	=new incentive_MAIN_ADMIN();
	$orderDetailsObj=new BILLING_ORDERS();
	$historyObj	=new incentive_HISTORY();
	$paymentHistObj =new BILLING_PAYMENT_HITS();
	
	$agentName  =$processObj->getExecutive();
	$subMethod  =$processObj->getSubMethod();

	$profilesArr=$mainAdminObj->getFollowupProfilesForAgent($agentName);	
	if(count($profilesArr)>0){
		for($i=0; $i<count($profilesArr); $i++){
			$profileid =$profilesArr[$i]['PROFILEID'];
			$allotTime =$profilesArr[$i]['ALLOT_TIME'];

			$lastDispositionDt =$historyObj->fetchHistoryLastEntryDt($profileid,$agentName,$allotTime);
			if(!$lastDispositionDt)
				$lastDispositionDt =$allotTime;
			$profileValidity =$orderDetailsObj->getFailedPaymentCount($profileid,$lastDispositionDt);
			if($subMethod=='PAYMENT_HITS'){
				if(!$profileValidity)
					$profileValidity =$paymentHistObj->getMembershiptHitCount($profileid,$lastDispositionDt);
				else
					$profileValidity ='';
			}
			if($profileValidity){
				//$isPaid =$this->checkPaidProfile($profileid);	
				//if(!$isPaid)
				$profilesNewArr[] =$profileid;
			}
			unset($lastDispositionDt);
		}
	}
	return $profilesNewArr;
}

public function fetchProfilesWithDate($processObj)
{
		$mainAdminObj=new incentive_MAIN_ADMIN();

		$agentName  =$processObj->getExecutive();
		$subMethod  =$processObj->getSubMethod();
		$startDate  =$processObj->getStartDate();
		$endDate    =$processObj->getEndDate();
		
		if($startDate && $endDate){
			$profilesArr=$mainAdminObj->getFollowUpProfilesWithinDates($agentName,$startDate,$endDate);
		}
		else{
			if($subMethod=='FOLLOWUP')
				$profilesArr=$mainAdminObj->getFollowUpProfiles($agentName);
			else if($subMethod=='FFOLLOWUP')
				$profilesArr=$mainAdminObj->getFutureFollowUpProfiles($agentName);
		}
		return $profilesArr;
}
public function fetchProfilesWithPaidDate($processObj)
{
		$mainAdminObj    =new incentive_MAIN_ADMIN();
		$serviceStatusObj=new BILLING_SERVICE_STATUS();

		$agentName  =$processObj->getExecutive();	
		$subMethod =$processObj->getSubMethod();

		if($subMethod=='HANDLED')
			$profileList = $mainAdminObj->getProfilesForStatus($agentName,'C');
		else
			$profileList = $mainAdminObj->getNonFollowupProfiles($agentName);
		if($profileList){
			if($subMethod=='SUB_EXPIRY'){
				foreach($profileList as $key=>$val){
                                        $mainMemExpiryDate = $serviceStatusObj->getLastExpiry($val);
					if($mainMemExpiryDate)
					{
						$expiryDateSetw30 =date('Y-m-d',time()+29*86400);
						$expiryDateSetw10 =date('Y-m-d',time()-10*86400);
                	                        if($mainMemExpiryDate>=date('Y-m-d',time()) && $mainMemExpiryDate<=$expiryDateSetw30)
							$profilesArr[] = $val;
						elseif($mainMemExpiryDate<date('Y-m-d',time()) && $mainMemExpiryDate>=$expiryDateSetw10)
							$profilesArr[] = $val;
					}
				}
			}
			elseif($subMethod=='RENEWAL_NOT_DUE'){
				foreach($profileList as $key=>$val){
					$mainMemExpiryDate = $serviceStatusObj->getLastExpiry($val);
					if($mainMemExpiryDate)
                                        {
                                                if($mainMemExpiryDate>date('Y-m-d',time()+29*86400))
                                                        $profilesArr[] = $val;
                                        }
				}
			}
			elseif($subMethod=='HANDLED')
				$profilesArr =$this->fetchFreeProfiles($profileList);
			array_unique($profilesArr);
			$profilesArr =array_filter($profilesArr);
			$profilesArr =array_values($profilesArr);
			return $profilesArr;
		}
}

function fetchOutboundSortedProfiles($processObj,$profileArr)
{
	$subMethod      	=$processObj->getSubMethod();
	$final_profileArr       =array();
	if(count($profileArr)==0)
		return $final_profileArr;

	if($subMethod=='NEW_PROFILES' || $subMethod=='FTA'){
		foreach($profileArr as $key=>$val){
			$pid 			=$val['PROFILEID'];
			$ptype 			=$val['PROFILE_TYPE'];
			$profileArrNew[] 	=$pid;
			$profileDetailsArr[$pid]=$ptype;
		}
		$str    =implode(",",$profileArrNew);
	}
	elseif($subMethod=='SUB_EXPIRY' || $subMethod=='RENEWAL'){
		$serviceStatusObj       =new BILLING_SERVICE_STATUS();
		$str                    =implode(",",$profileArr);
		$final_profileArrTemp   =$serviceStatusObj->getSortedProfilesExpiryBased($str);
		asort($final_profileArrTemp);
		foreach($final_profileArrTemp as $key=>$val)
			$final_profileArr[] =$key;
		return $final_profileArr;	      
	}
	else
		$str    =implode(",",$profileArr);

	$mainAdminPoolObj       =new incentive_MAIN_ADMIN_POOL('newjs_slave');
	$profileScoresArr=$mainAdminPoolObj->fetchProfilesWithScore($str);
	$final_profileArr =$this->fetchSortedProfileArr($profileScoresArr,$subMethod,$profileDetailsArr,$profileArr);
	return $final_profileArr;	
}
public function fetchSortedProfileArr($profileScoresArr,$subMethod,$profileDetailsArr,$profileArr)
{
	if($subMethod=='FTA'){
		foreach($profileDetailsArr as $key=>$val){
			$priority =$this->fetchFtaPriority($val,$key);
			$profiles_arr[]=$priority."i".$key;
		}
	}
	else{
		foreach($profileScoresArr as $key=>$val){
			$pid    =$val['PROFILEID'];
			$score  =$val['SCORE'];
			if($subMethod=='NEW_PROFILES' || $subMethod=='ONLINE_NEW_PROFILES')
				$profiles_arr[]=$profileDetailsArr[$pid]."i".$score."i".$pid;
			else if($subMethod=='FOLLOWUP' || $subMethod=='FFOLLOWUP' || $subMethod=='HANDLED')
				$profiles_arr[]=$score."i".$pid;
		}
	}
	rsort($profiles_arr);
	for($i=0;$i<count($profiles_arr);$i++){
		if($subMethod=='NEW_PROFILES' || $subMethod=='ONLINE_NEW_PROFILES')
			list($profileType,$score,$profileid) =explode("i",$profiles_arr[$i]);
		else if($subMethod=='FOLLOWUP' || $subMethod=='FFOLLOWUP' || $subMethod=='HANDLED' || $subMethod=='FTA')
			list($score,$profileid) =explode("i",$profiles_arr[$i]);
		if($profileid)
			$final_profileArr[]=$profileid;
	}
	if($subMethod=='FOLLOWUP' || $subMethod=='FFOLLOWUP' || $subMethod=='HANDLED'){
		$profileidArrDiff =array_diff($profileArr,$final_profileArr);
		$final_profileArr =array_merge($final_profileArr,$profileidArrDiff);
	}

	return $final_profileArr;
}

public function fetchServiceDetails($profilesArr=array())
{
	if(count($profilesArr)==0)
		return;
	$contactsObj    	=new jsadmin_CONTACTS_ALLOTED();
	$servicesObj    	=new billing_SERVICES();
	$serviceStatusObj	=new BILLING_SERVICE_STATUS();	

	foreach($profilesArr as $key=>$val)
		$pidArr[] =$key;
	$profileStr             =implode(",",$pidArr);
	$valueArr['PROFILEID']	=$profileStr;
	$serviceStatusArr      	=$serviceStatusObj->getArray($valueArr,'','','','PROFILEID,EXPIRY_DT,ACTIVATED_ON,SERVICEID');
	foreach($serviceStatusArr as $key=>$val){
		$profileid =$val['PROFILEID'];
		$servStatusTemp[$profileid] =$val;
	}
	foreach($profilesArr as $key=>$val){
		$pid					=$key;
		$activatedDate				=$servStatusTemp[$pid]['ACTIVATED_ON'];
		$serviceId				=$servStatusTemp[$pid]['SERVICEID'];
		$profilesArr[$pid]['EXPIRY_DT']	 	=$servStatusTemp[$pid]['EXPIRY_DT'];
		$profilesArr[$pid]['ACTIVATED_ON']	=$activatedDate;
		$profilesArr[$pid]['CONTACT_VIEWED']    =$contactsObj->getViewedContacts($pid);
		if($serviceId)
			$profilesArr[$pid]['SERVICE_PURCHASE']  =$servicesObj->getServiceName($serviceId);
	}
	return $profilesArr; 
}
public function fetchProfileDisplayDetails($profileid,$privilege)
{
	$usernameObj            =new incentive_NAME_OF_USER();
	$purchaseObj            =new BILLING_PURCHASES();
	$jProfileObj            =new JPROFILE();
	$crmUtilityObj		=new crmUtility();
	$mainAdminObj		=new incentive_MAIN_ADMIN();

	$nameOfUser       	=$usernameObj->getName($profileid);
	$jProfileDetails  	=$jProfileObj->get($profileid,"PROFILEID","USERNAME,EMAIL,GENDER,PHONE_MOB,PHONE_WITH_STD,ACTIVATED,INCOMPLETE,SUBSCRIPTION,ISD");
	$activeStatusMsg  	=$crmUtilityObj->fetchActiveStatus($jProfileDetails['ACTIVATED'],$jProfileDetails['INCOMPLETE']);

	$wasPaidStatus    	=$purchaseObj->getPaidStatus($profileid);
	$mainAdminDetails       =$mainAdminObj->get($profileid,"PROFILEID","WILL_PAY,REASON,ALLOTED_TO,PROFILEID,ALLOT_TIME");
	//$jcontactDetails	=$this->fetchJprofileContact(array($profileid));
	$history	  	=$this->fetchHistoryList($profileid,$privilege,$mainAdminDetails['ALLOT_TIME']);
	$orderDetails		=$this->fetchOrderDetails($profileid);

	$email                  	=$jProfileDetails['EMAIL'];
	$isdNo				=$jProfileDetails['ISD'];
	$detailsArr['EMAIL']          	=trim($email);
	$detailsArr['CRM_NAME']		=$nameOfUser;
	$detailsArr['ACTIVE_STATUS_MESSAGE']	=$activeStatusMsg;
	$detailsArr['WAS_PAID']		=$wasPaidStatus;
	$detailsArr['HISTORY']		=$history;
	$detailsArr['ORDER_DETAILS']    =$orderDetails;
	$detailsArr['ALTERNATE_NO']	=$this->phoneNumberCheck($this->getOtherPhoneNums($profileid), $isdNo);
	$detailsArr['PHONE_MOB']	=$this->phoneNumberCheck($jProfileDetails['PHONE_MOB'], $isdNo);
	$detailsArr['PHONE_WITH_STD']	=$this->phoneNumberCheck($jProfileDetails['PHONE_WITH_STD'], $isdNo);
	$altMob = $this->fetchJprofileContact(array($profileid));
        $detailsArr['ALT_MOB'] = $this->phoneNumberCheck($altMob[$profileid]['ALT_MOBILE']);
	
	if($mainAdminDetails['PROFILEID'])		
		$details		=array_merge($jProfileDetails,$detailsArr,$mainAdminDetails);
	else
		$details                =array_merge($jProfileDetails,$detailsArr);
	return $details;
}
public function fetchHistoryList($profileid,$privilege,$allotTime='')
{
	$urlPath =sfConfig::get("sf_web_dir");
	$symfonyVar =1;
	include_once($urlPath."/crm/connect.inc");
	include_once($urlPath."/crm/history.php");

	$priv =explode("+",$privilege);
	if(in_array("SLHD",$priv) || in_array("SLSUP",$priv) || in_array("P",$priv) || in_array("MG",$priv) || in_array("TRNG",$priv))
		$historyLimit =0;
	else{
		$limitCount =getHistoryCount($profileid,$allotTime);
		if($limitCount>=5)
			$historyLimit =$limitCount;
		else
			$historyLimit =5;
	}
	$historyRow =gethistory($profileid,$historyLimit);
	return $historyRow;
}
public function profile_allocated($profileid,$method="")
{
	if($method=='WEBMASTER_LEADS' || $method=='FIELD_SALES')
		$profileAllTechObj=new incentive_PROFILE_ALLOCATION_TECH('newjs_slave');
	else
		$profileAllTechObj=new incentive_PROFILE_ALLOCATION_TECH();		
	$status=$profileAllTechObj->getAllocationStatusOfProfile($profileid);
	return $status;
}
public function phoneNumberCheck($phoneNumber, $isdNo='')
{
	$isIndian =true;
	if(!$phoneNumber)
		return false;
	if($isdNo)
		$isIndian =$this->isIndianNo($isdNo);
	
	if($isIndian){
		$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
		$phoneNumber    =ltrim($phoneNumber,0);
		if(strlen($phoneNumber)!=10)
			return false;
	}
	else{
		$phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-15);
		$phoneNumber    =ltrim($phoneNumber,0);
		$totLength 	=strlen($phoneNumber);
		if($totLength<6 || $totLength>14)
			return false;
	}
	if(!is_numeric($phoneNumber))
		return false;
	return $phoneNumber;
}
public function getOtherPhoneNums($profileid)
{
	$alternateNumberObj=new PROFILE_ALTERNATE_NUMBER('newjs_slave');
	$AL_NUMBER=$alternateNumberObj->getAlternateNumber($profileid);
	return $AL_NUMBER;
}
public function checkDNC($phoneNumberArray)
{
	$DNCArr=array();
	$DNC_NumberArr=array();
	$selectedArr=array();
	$status=true;
	$dncListObj=new dnc_DNC_LIST();

	if(!is_array($phoneNumberArray) || count($phoneNumberArray)=='0')
		return false;
	else{
		foreach($phoneNumberArray as $key1=>$val1)
		{
			if($val1)
				$selectedArr[] =$val1;
		}
	}
	$DNC_NumberArr=$dncListObj->fetchDNCNumberArray($selectedArr);
	foreach($phoneNumberArray as $key=>$val)
	{
		if(in_array($val,$DNC_NumberArr)){
			$DNCArr[$key] =$val;
			$key1 =$key."S";
			$DNCArr[$key1] ='Y';
		}
		else{
			$DNCArr[$key] =$val;
			$key1 =$key."S";
			$DNCArr[$key1] ='N';
			if(in_array($val,$selectedArr))
				$status =false;
		}
	}
	$DNCArr['STATUS'] =$status;
	return $DNCArr;
}
public function applyGenericFilters($profileArr, $method='',$subMethod='')
{
	if(count($profileArr)==0)
		return;	
	$methodForJprofileFilter =array('WEBMASTER_LEADS','NEW_FAILED_PAYMENT','FIELD_SALES');

        // Main admin check
        $mainAdminObj=new incentive_MAIN_ADMIN();
        $profileDetails =$mainAdminObj->getProfilesDetails($profileArr);
	if(count($profileDetails)>0){
		foreach($profileDetails as $key=>$val)
			$profileArrNew[] =$val['PROFILEID'];
		if(count($profileArrNew)>0)
			$profileArr =array_diff($profileArr,$profileArrNew);
		$profileArr =array_values($profileArr);
		unset($profileArrNew);
		unset($profileDetails);
	}
        // Do not call check
	if($method!='WEBMASTER_LEADS'){
	if(count($profileArr)>0){
	        $DNCObj=new incentive_DO_NOT_CALL('newjs_slave');
	        $profileArrNew =$DNCObj->getDoNotCallProfiles($profileArr);
	        if(count($profileArrNew)>0)
	                $profileArr =array_diff($profileArr,$profileArrNew);
		$profileArr =array_values($profileArr);
		unset($profileArrNew);
	}

        // Negative profile list check
	if(count($profileArr)>0){
	        $negProfileObj=new incentive_NEGATIVE_TREATMENT_LIST('newjs_slave');
	        $profileArrNew =$negProfileObj->getNegativeListProfiles($profileArr);
		if(count($profileArrNew)>0)
			$profileArr =array_diff($profileArr,$profileArrNew);
		$profileArr =array_values($profileArr);
		unset($profileArrNew);
	}}

        // Pre-Allocation Check
        if($method=='FIELD_SALES' || $method=='PRE_ALLOCATION'){
                if(count($profileArr)>0){
			if($method=='FIELD_SALES')
				$preAllocationObj =new incentive_PROFILE_ALLOCATION_TECH('newjs_masterRep');
			else
                        	$preAllocationObj =new incentive_PROFILE_ALLOCATION_TECH();
                        $profileArrNew =$preAllocationObj->getAllotedProfiles($profileArr);
                        if(count($profileArrNew)>0)
                                $profileArr =array_diff($profileArr,$profileArrNew);
                        $profileArr =array_values($profileArr);
                        unset($profileArrNew);
                }
        }
        // Invalid phone check
        if(in_array($method, $methodForJprofileFilter) && count($profileArr)>0){
		$jprofileObj =new JPROFILE('newjs_masterRep');
		$fields	='PROFILEID,ISD,PHONE_FLAG,ACTIVATED,GENDER,AGE,ENTRY_DT,MTONGUE,SUBSCRIPTION,CITY_RES,PINCODE,MOB_STATUS,LANDL_STATUS,INCOME';
		$profileStr =implode(",", $profileArr);	
		$valueArray['PROFILEID'] =$profileStr;
                $resDetails=$jprofileObj->getArray($valueArray,"","",$fields);

		foreach($resDetails as $key=>$data){
			$profileid =$data['PROFILEID'];
			$phoneFlag =$data['PHONE_FLAG'];
			$flag =1;
            if($subMethod=='FIELD_SALES' && (($data['GENDER']=='M' && ($data['AGE'] <=24 || $data['INCOME'] == 15) ) || ($data['GENDER']=='F' && $data['AGE'] <=21))){
                $profileArrNew[] =$profileid;
                $flag=0;
            }
            if($flag == 1 && ($phoneFlag=='I' || $data['ACTIVATED']!='Y' || ($data['GENDER']=='M' && $data['AGE'] <24))){
                if($subMethod!='WEBMASTER_LEADS'){
					$profileArrNew[] =$profileid;
					$flag=0;
				}
			}
			if($flag==1){
				$indianNo =$this->isIndianNo($data['ISD']);
				if($method=='NEW_FAILED_PAYMENT' || $subMethod=='WEBMASTER_LEADS'){
					if($indianNo || $phoneFlag=='I')
						$profileArrNew[] =$profileid;
				}
				else if($method=='FIELD_SALES'){
					if(!$indianNo)
						$profileArrNew[] =$profileid;	
			       		if((strstr($data['SUBSCRIPTION'],"F")!="")||(strstr($data['SUBSCRIPTION'],"D")!=""))
						$profileArrNew[] =$profileid;
					if($data['MOB_STATUS']!='Y' && $data['LANDL_STATUS']!='Y')
						$profileArrNew[] =$profileid;
					$dataSetArr[$profileid] =array('MTONGUE'=>$data['MTONGUE'],'CITY_RES'=>$data['CITY_RES'],'PINCODE'=>$data['PINCODE']);
				}
			}
		}
		if(count($profileArrNew)>0)
			$profileArr =array_diff($profileArr,$profileArrNew);
		$profileArr =array_values($profileArr);
		unset($profileArrNew);
        }
	if($method=='FIELD_SALES'){
		foreach($profileArr as $key=>$profileid)
			$profileArrNew[$profileid] =$dataSetArr[$profileid];	
		unset($profileArr);
		$profileArr =$profileArrNew;
	}
	// return valid profiles	
	return $profileArr;	
}
public function check_profile($profileid,$method='')
{
	// Do not call check
	$DNCObj=new incentive_DO_NOT_CALL('newjs_slave');
	$doNotCall=$DNCObj->checkProfileDNC($profileid);
	if($doNotCall>0)
		return false;

	// Main admin check
	$mainAdminObj=new incentive_MAIN_ADMIN();
	$alloted=$mainAdminObj->get($profileid,"PROFILEID","COUNT(*) AS CNT");
	if($alloted['CNT']>0)
		return false;
	$jprofileObj=new JPROFILE('newjs_slave');

	// Invalid phone check
	if($method!='FIELD_SALES'){
		$resDetails=$jprofileObj->get($profileid,"PROFILEID","PHONE_FLAG,ACTIVATED,DATE(LAST_LOGIN_DT) LAST_LOGIN_DT,ISD");
		if($method=='RENEWAL'){
			$lastLoginDt =$resDetails['LAST_LOGIN_DT'];
			$checkDay =JSstrToTime(date("Y-m-d",time()-14*24*60*60));
			if(JSstrToTime($lastLoginDt)<$checkDay)
				return false;
                        $isdVal         =trim($resDetails['ISD']);
                        $isIndian       =$this->isIndianNo($isdVal);
                        if($isIndian)
                        	return false;

		}
		if($resDetails['PHONE_FLAG']=='I' || $resDetails['ACTIVATED']!='Y')
			return false;
	}

	// Negative profile list check
	$negProfileObj=new incentive_NEGATIVE_TREATMENT_LIST('newjs_slave');
	$negativeProfile=$negProfileObj->isFlagOutboundCall($profileid,'N');
	if($negativeProfile>0)
		return false;
	return true;
}
public function isIndianNo($num){
 if($num && ($num==91 || $num=='0091' || $num=='+91' || $num=='091'))
	 return 1;
 else
	 return 0;
}

// function for fetching agents for the manager privilege
public function fetchAgentsForManager($agent)
{
	$jsAdminPSWRDSObj=new jsadmin_PSWRDS();	
	$mgrPrivilegeArr=array("P","MG","TRNG");

	$privilege =$jsAdminPSWRDSObj->getPrivilegeForAgent($agent);
	$privilegeArr =@explode("+", $privilege);
	foreach($mgrPrivilegeArr as $privKey=>$privVal){
		if(in_array("$privVal",$privilegeArr)){
			$mgrPrivilege =1;
			break;
		}
	}
	if($mgrPrivilege){
		$usernamesArr =$jsAdminPSWRDSObj->getAllExecutives();
		return $usernamesArr;
	}
	return;
}
public function fetchAgentsByHierarchy($agent,$privilege='',$status='',$mgr='')
{
	$jsAdminPSWRDSObj=new jsadmin_PSWRDS('newjs_slave');	

	// Code added for manager accessibility
	if($mgr){
		$usernamesArr =$this->fetchAgentsForManager($agent);		
		if($usernamesArr)		
			return $usernamesArr;
	}
	//ends

	$execDetails 	=$jsAdminPSWRDSObj->getExecutiveDetails($agent);
	$headId		=$execDetails['EMP_ID'];
	$employeeIdsArr	=$jsAdminPSWRDSObj->getEmployeeIdForHead($headId,$privilege,$status);

	$employeeIds  	=@implode(",",$employeeIdsArr);		
	if($employeeIds)
		$employeeIdStr  =$employeeIds.",".$headId;
	else
		$employeeIdStr  =$headId;

	$force_break_loop =0;
	$empIdArr =array();	
	if($employeeIds){
		while(1){
			 $employeeIds =rtrim($employeeIds,',');
			 unset($employeeIdsNew);

			$employeeIdMore =$jsAdminPSWRDSObj->getEmployeeIdForHead($employeeIds,$privilege,$status);
			if(count($employeeIdMore)>0){
				foreach($employeeIdMore as $key=>$val){
					//if(0==strstr($employeeIdsNew,"$val")){
					if(!in_array($val, $empIdArr)){
						$employeeIdsNew .="$val,";
						$empIdArr[] =$val;
					}
				}
			}
			if(!$employeeIdsNew)
				break;
			$employeeIds 	=$employeeIdsNew;
			$employeeIdStr 	=$employeeIdsNew.$employeeIdStr;

			$force_break_loop++;
			if($force_break_loop > 10){
				echo $force_break_loop;
				die;
			}
		}
	}
	$empIdArray   	=@explode(",",$employeeIdStr);
	$empIdArray   	=array_unique($empIdArray);
	$employeeIdStr	=@implode(",",$empIdArray);

	$usernameArr 	=$jsAdminPSWRDSObj->getUsernames($employeeIdStr,$privilege,$status);
	return $usernameArr;
}
function fetchJProfileDetails($profileValue,$fields='')
{
	if(is_numeric($value))
		$criteria ='PROFILEID';
	else
		$criteria ='USERNAME';	
	$jprofileObj            =new JPROFILE();
	$profileDetails         =$jprofileObj->get($profileValue,$criteria,$fields);
	return $profileDetails;
}
public function fetchEoiSent($profileid)
{
	$dbName         =JsDbSharding::getShardNo($profileid);
	$dbObj          =new newjs_CONTACTS($dbName);
	$responseArr    =$dbObj->getResponseCount($profileid);
	foreach($responseArr as $key=>$val){
		if($val['TYPE']=='I'){
			$eoiSent =$val['COUNT'];
			break;
		}
	}
	return $eoiSent;
}
public function fetchFtaPriority($stateId,$profileid)
{
	$eoi_sent =$this->fetchEoiSent($profileid);	

	if($stateId==1 && $eoi_sent>0)
		$priority=8;
	elseif($stateId==2 && $eoi_sent>0)
		$priority=7;
	elseif($stateId==3 && $eoi_sent>0)
		$priority=6;
	elseif($stateId==1 && $eoi_sent==0)
		$priority=5;
	elseif($stateId==2 && $eoi_sent==0)
		$priority=4;
	elseif($stateId==3 && $eoi_sent==0)
		$priority=3;
	elseif($stateId==4 && $eoi_sent>0)
		$priority=2;

	return $priority;
}
public function fetchPhotoRequestReceived($profileid)
{
	$dbName = JsDbSharding::getShardNo($profileid);
	$photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
	$totPhotoReqReceived =$photoRequestObj->getPhotoRequestReceived($profileid);
	return $totPhotoReqReceived;
}
public function getAge($newDob)
{
	$today=date("Y-m-d");
	$datearray=explode("-",$newDob);
	$todayArray=explode("-",$today);

	$years=($todayArray[0]-$datearray[0]);

	if(intval($todayArray[1]) < intval($datearray[1]))
		$years--;
	elseif(intval($todayArray[1]) == intval($datearray[1]) && intval($todayArray[2]) < intval($datearray[2]))
	$years--;

	return $years;
}
public function getMyDisposedProfiles($profiles)
{
	$historyObj=new incentive_HISTORY();
	$myDisposedProfiles=array();
	for($i=0;$i<count($profiles);$i++)
	{
		$profileid=$profiles[$i]['PROFILEID'];
		$agent=$historyObj->getLastDisposingAgent($profileid);
		if($agent==$profiles[$i]['ALLOTED_TO'])
			$myDisposedProfiles[]=$profileid;
	}
	return $myDisposedProfiles;
}
/*
 * This function take the profileid of the user as input parameter
 * It returns 1 if the user is paid, 
 * It returns nothing if the user is free.
 */
public function checkPaidProfile($profileid)
{
	$jprofileObj =new JPROFILE();
	$jResult     =$jprofileObj->get($profileid,'PROFILEID','SUBSCRIPTION');
	if((strstr($jResult['SUBSCRIPTION'],"F")!="") || (strstr($jResult['SUBSCRIPTION'],"D")!=""))
		return 1;
	return;
}
/*
 * This functions checks if the user's service expiry date is within E-30,E. 
 * It takes profile id of the user as input
 * It returns 1 if the expiry is outside of (E-30, E)
 * It returns nothing if the expiry is with (E-30, E)
 */
public function checkExpiry($profileid) {
        include_once (JsConstants::$docRoot . "/classes/Services.class.php");
        $serviceObj = new Services();
        $expiry_date = $serviceObj->getPreviousExpiryDate($profileid, "F");
        $expiry_date = $expiry_date['EXPIRY_DATE'];
        $date = date("Y-m-d");
        $diff = date_diff(date_create($expiry_date), date_create($date))->format("%a");
        if ($diff > 30) {
            return 1;
        }
    }

public function fetchAllocationLimit()
{
	$agentAllocationObj     =new AgentAllocation();
	$allocationLimit        =$agentAllocationObj->fetchAllotedBucketDays('','AL');
	return $allocationLimit;
}
public function fetchLtfProfilesForAgent($agentsArr=array())
{
	if(count($agentsArr>0)){
		$agentsStr ="'".@implode("','",$agentsArr)."'";	
		$ltfObj	=new MIS_LTF('newjs_slave');		//TRANSFER_TO_SLAVE
		$profiles=$ltfObj->getLtfProfilesForAgent($agentsStr);
		return $profiles;
	}
	return;	
}
public function fetchExceededAllocationCount($agentName)
{
	$mainAdminObj		=new incentive_MAIN_ADMIN();
	$totalAllotedArr	=$mainAdminObj->fetchTotalAllocation($agentName);

	if(count($totalAllotedArr)>0){
		foreach($totalAllotedArr as $key=>$profileid){
			$paidStatus =$this->checkPaidProfile($profileid);
			if(!$paidStatus)
				$totalAllotedArrNew[] =$profileid;
		}
	}
	$totalAllotedCnt 	=count($totalAllotedArrNew);	
	$allocationLimit	=$this->fetchAllocationLimit();		
	if($totalAllotedCnt>$allocationLimit){
		$exceedLimit =$totalAllotedCnt-$allocationLimit;
		return $exceedLimit;	
	}
	return;	
}
public function fetchLastAllocationDetails($profileid='',$username='')
{
	if($username){
		$jprofileObj =new JPROFILE();
		$jResult     =$jprofileObj->get($username,'USERNAME','PROFILEID');
		$profileid   =$jResult['PROFILEID'];						
	}
	if($profileid){
		$crmDailyAllotObj       =new CRM_DAILY_ALLOT();
		$lastAllotedDetails     =$crmDailyAllotObj->getLastAllocationDetails($profileid);
		return $lastAllotedDetails;
	}
}
public function getAllotedAgent($profileid)
{
	$mainAdminObj	=new incentive_MAIN_ADMIN('newjs_slave');
	$allotedAgent	=$mainAdminObj->get($profileid,"PROFILEID","ALLOTED_TO");
	$agentName 	=$allotedAgent['ALLOTED_TO'];	
	if($agentName)
		return $agentName;
	return;
}

public function fetchFieldSalesCity()
{
	$fieldSalesCityObj =new incentive_FIELD_SALES_CITY('newjs_slave');
	$fieldSalesCityArr =$fieldSalesCityObj->getAllCity();
	return $fieldSalesCityArr;
}
public function getLocalityLimit($method)
{
	$agentAllotedObj        =new AGENT_ALLOTED('newjs_slave');
	$localityArr =$agentAllotedObj->getLocalityLimit($method);
	return $localityArr;
}
public function updateLocalityLimit($limit,$center,$method)
{
	$agentAllotedObj =new AGENT_ALLOTED();
	$agentAllotedObj->updateAllocationLimit($limit,$center,$method);
}
public function pincodeMappingExist($pincode)
{
        $pincodeMappingObj =new newjs_PINCODE_MAPPING('newjs_slave');
        $pincodeExist =$pincodeMappingObj->checkPincodeExist($pincode);
	if($pincodeExist)
		return true;
	return false;	
}
public function getPincodeList()
{
        $pincodeMappingObj =new newjs_PINCODE_MAPPING('newjs_slave');
        $pincodeArr =$pincodeMappingObj->getPincodeList();
        return $pincodeArr;
}
public function fetchPincodesOfCities($cities)
{
        $pincodeObj =new incentive_PRE_ALLOCATION_PINCODE_MAPPING('newjs_slave');	
        $pincodes =$pincodeObj->getPincodes($cities);
        return $pincodes;
}
	public function getValidUsersForSalesTarget($usernameArr='')
	{
		$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_masterRep');
		if($usernameArr && is_array($usernameArr))
			$privileges = $jsadminPswrdsObj->get_name_priv($usernameArr);
		else
			$privileges = $jsadminPswrdsObj->getPrivilegesForSalesTarget();

		$usernames = array();
		$boss = array();
		foreach($privileges as $key => $value)
		{
	                $priv = explode("+", $value);

        	        if(in_array('ExcSl',$priv) || in_array('SLMNTR',$priv) || in_array('SLSUP',$priv) || in_array('SLMGR',$priv) || in_array('SLSMGR',$priv) || in_array('SLHD',$priv) || in_array('SLHDO',$priv))
                	        $usernames[] = $key;
	
        	        if(in_array('SLHDO',$priv))
                	        $boss[] = $key;
        	}
		return array('USERNAMES' => $usernames, 'BOSS' => $boss);
        }

	public function getValidUsersForFieldSalesTarget()
	{
		$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
        	$privileges = $jsadminPswrdsObj->getPrivilegesForSalesTarget();

        	$usernames = array();
                $boss = array();
                foreach($privileges as $key => $value)
                {
                        $priv = explode("+", $value);

			if(in_array("ExcFld",$priv) || in_array("SupFld",$priv) || in_array("MgrFld",$priv) || in_array("SLHDO",$priv))
                                $usernames[] = $key;

                        if(in_array('SLHDO',$priv))
                                $boss[] = $key;
                }
                return array('USERNAMES' => $usernames, 'BOSS' => $boss);
	}

	function get_phone_no($m1,$m2,$l1,$l2,$suffix)
	{
		if($m1)
			$p1 = $m1;
		elseif($m2)
			$p1 = $m2;
		elseif($l1)
			$p1 = $l1;
		else
			$p1 = $l2;

		if($suffix == 1)
                        return $p1;

		$p2='';
		if($p1 == $m1)
		{
			if($m2) 
				$p2 = $m2;
			elseif($l1) 
				$p2 = $l1;
			elseif($l2) 
				$p2 = $l2;
		}
		elseif($p1 == $m2)
		{
			if($l1)       
                                $p2 = $l1;
                        elseif($l2)       
                                $p2 = $l2;
		}
		elseif($p1 == $l1)
		{
			if($l2)
                                $p2 = $l2;
		}

		return $p2;
	}
	
	function get_priority($status,$lead_source,$gender_val,$ent_date,$quartile)
	{
		if($status == 16)
		{
			if($gender_val=='F')
				return 28; 
			if($gender_val=='M')
				return 27;
		}
		if($lead_source == 'TV Show')
                {
                        if($gender_val=='F')
                                return 26;
                        if($gender_val=='M')
                                return 25;
                }
		$date_30=date('Y-m-d',time()-30*86400);
		$date_90=date('Y-m-d',time()-90*86400);
		if($gender_val=='F')
		{
			if($ent_date>=$date_30)
			{
				if($quartile==1)
					return 24;
				elseif($quartile==2)
					return 23;
				elseif($quartile==3)
					return 22;
				else
					return 6;
			}
			elseif($ent_date>=$date_90)
			{
				if($quartile==1)
					return 18;
                                elseif($quartile==2)
					return 17;
                                elseif($quartile==3)
					return 16;
				else
                                        return 5;
			}
			else
			{
				if($quartile==1)
					return 12;
                                elseif($quartile==2)
					return 11;
                                elseif($quartile==3)
					return 10;
				else
                                        return 4;
			}
		}
		else
		{
			if($ent_date>=$date_30)
                        {
                                if($quartile==1)
                                        return 21;
                                elseif($quartile==2)
                                        return 20;
                                elseif($quartile==3)
                                        return 19;
				else
                                        return 3;
                        }		
                        elseif($ent_date>=$date_90)
                        {
                                if($quartile==1)
					return 15;
                                elseif($quartile==2)
					return 14;
                                elseif($quartile==3)
					return 13;
				else
                                        return 2;
                        }
                        else
                        {
                                if($quartile==1)
					return 9;
                                elseif($quartile==2)
					return 8;
                                elseif($quartile==3)
					return 7;
				else
                                        return 1;
                        }

		}
	}
	public function fetchAllCentersOfStates()
	{
		$statesObj=new incentive_BRANCH_STATE('newjs_slave');	
		$locationObj=new incentive_LOCATION_CITY('newjs_slave');	
		$states=$statesObj->fetchStates();

		$res = array();
		for($i=0;$i<count($states);$i++)
		{
			$allCities = $locationObj->fetchValueOfState($states[$i]);
			$res = array_merge($res, $allCities);
		}
		return $res;
	}
	public function getAgentPreAllocationLimit($agent, $limitArr) {
                $pswrdObj = new jsadmin_PSWRDS('newjs_slave');	
                $subcenter = $pswrdObj->getSubCenter($agent);
		return $limitArr[$subcenter];		
	}

	// Check VD Offer is active or not
        public function isVdOfferActive(){
		$curDate	=date("Y-m-d");
		$vdObj 		=new billing_VARIABLE_DISCOUNT('newjs_slave');	
		$maxDate 	=$vdObj->getVdExpiryDate();
		if($maxDate){
			if(JSstrToTime($maxDate)>=JSstrToTime($curDate))
				return true;
		}
		return;
        }
        public function isOptinProfile($profileid){
                $consentDncObj  =new NEWJS_CONSENTMSG('newjs_slave');
                $consentStatus  =$consentDncObj->getConsentStatus($profileid);
                return $consentStatus;
        }
	public function getEverPaidPool(){
                $purchaseObj =new BILLING_PURCHASES('newjs_slave');
                $everPaidProfiles  =$purchaseObj->fetchEverPaidPool();
                return $everPaidProfiles;
	}
        public function createPhoneVerifiedPool($processObj)
	{
		//$dateTime =date("Y-m-d H:i:s",time()-10*24*60*60);
		$dateTime =date("Y-m-d H:i:s",time()-48*60*60);
                $phoneVerifiedObj =new PHONE_VERIFIED_LOG('newjs_masterRep');
                $verifiedProfiles =$phoneVerifiedObj->fetchVerifiedProfiles($dateTime);
		$processObj->setPhoneVerifiedProfiles($verifiedProfiles);
        }
    	public function getProfilesInRenewalPeriod()
    	{
                $billingSerStatusObj    =new BILLING_SERVICE_STATUS('newjs_slave');
                $startDate              =date("Y-m-d", time()-9*86400);
                $endDate                =date("Y-m-d", time()+15*86400);
                $profiles               =$billingSerStatusObj->getRenewalProfilesForDates($startDate,$endDate);
                //Handle scenrio for exclusive profile
                $exclusiveProfile       =$billingSerStatusObj->getExclusiveProfileForDates(date("Y-m-d h:i:sa"));
                if(!is_array($exclusiveProfile)){
                    $exclusiveProfile = array();
                }
                foreach($profiles as $key=>$data){
                        $profileid =$data['PROFILEID'];
                        $eDate     =$data['EDATE'];
                        if(strtotime($eDate)>=strtotime($starDate) && strtotime($eDate)<=strtotime($endDate)){
                            if(!array_key_exists($profileid,$exclusiveProfile)){
                                $profilesArr[] =$data;
                            }
                        }
                }
        	return $profilesArr;
    	}
	public function getAgentInfo()
	{
    		$pswrdsObj =new jsadmin_PSWRDS('newjs_slave');
		$agentInfoArr =$pswrdsObj->fetchAgentInfo();
		return $agentInfoArr;
	}
        public function getOnlineProfiles()
        {
        	$jsCommonObj =new JsCommon();
                $profilesArr =$jsCommonObj->getOnlineUsetList();
		return $profilesArr;
        }
	
    public function mailForLowDiscount($username,$agentName,$discountNegVal){
        $to = "anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,amit.malhotra@jeevansathi.com,princy.gulati@jeevansathi.com,shubhda.sinha@jeevansathi.com";
        //$to = "nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com";
        $from = "js-sums@jeevansathi.com";
        $subject = "Low Capped Discount by executive";
        $msgBody = "Username: $username<br>Discount Capped Value: $discountNegVal<br>CRM ID: $agentName";
        SendMail::send_email($to, $msgBody, $subject, $from);
    }

}
?>
