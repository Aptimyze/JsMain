<?php
class AgentBucketHandler
{
        public function deallocate($processObj)		
        {
                $agentAllocDetailsObj=new AgentAllocationDetails();
                $agentDeAllocObj=new AgentDeAllocation();
                $subMethod = $processObj->getSubMethod();
                $method=$processObj->getMethod();
                if($subMethod=="LIMIT_EXCEED")
                {
                        $utilityObj =new crmUtility();
                        $limitArr = $utilityObj->getProcessLimit();
                        $processObj->setLimitArr($limitArr);
			$minLimit =min($limitArr);	
	
                        $processObj->setLimit($minLimit);
                        $msg=$this->deAllocateDisp($processObj,$agentAllocDetailsObj,$agentDeAllocObj);
                }
		elseif($subMethod=="LIMIT_EXCEED_RENEWAL"){
                        $processObj->setLimit(125);
                        $msg=$this->deAllocateDisp($processObj,$agentAllocDetailsObj,$agentDeAllocObj);
		}
		elseif($subMethod=="RELEASE_PROFILE")
			$msg=$this->removeProfiles($processObj,$agentAllocDetailsObj,$agentDeAllocObj);
		elseif($method=="FTA_FTO"||$subMethod=="UPSELL" || $method == "REALLOCATION")
		{
			$agents=$agentAllocDetailsObj->fetchExecutives($processObj);
			$processObj->setExecutives($agents);
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
			$processObj->setProfiles($profiles);	
			$msg=$this->removeProfiles($processObj,$agentAllocDetailsObj,$agentDeAllocObj);
		}	
                else
                {
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
			$processObj->setProfiles($profiles);
                        $msg=$this->removeProfiles($processObj,$agentAllocDetailsObj,$agentDeAllocObj);
                }
		return $msg;
        }
        public function preAllocate($processObj)
        {
                $agentAllocDetailsObj=new AgentAllocationDetails();
		$agentPreAllocationObj=new AgentPreAllocation();
                $locsArray=$processObj->getAllCentersArray();
                $level=$processObj->getLevel();
                $limitArr=$processObj->getLimitArr();
		//$locationObj=new incentive_LOCATION('newjs_slave');	
                $centers=array();
		$agentsFinal=array();
		$statesAgents=array();
		$agents=array();
		$cities=array();
		$cityAgents=array();
		$profiles=array();
		if($level==-5 || $level==-3||$level==-2||$level==-1||$level==0)
		{
                        $agents=$agentAllocDetailsObj->fetchExecutives($processObj);	
			if($level!=-3){
				$agents=$this->fetchFilteredAgents($agents,$limitArr,$processObj);
			}
			$processObj->setExecutives($agents);
			if(is_array($agents)){
                                $profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
                        }
			$processObj->setProfiles($profiles);
                        $agentPreAllocationObj->pre_all($processObj);
		}
		elseif($level==-4){
			$privilegeSet ='%LTFVSp%';
			$processObj->setPrivilege($privilegeSet);	
			$agents=$agentAllocDetailsObj->fetchExecutives($processObj);
			//echo "LTF Supervisors=";print_r($agents);			
			
			$privilegeSet ='%LTFVnd%';
			if(count($agents)>0){
			  foreach($agents as $key=>$agentName){							
				
				// get executive under hierarchy with privilege (active/inactive both)
				$processObj->setPrivilege($privilegeSet);
				$processObj->setExecutive($agentName);
				$agentsFinal=$agentAllocDetailsObj->fetchExecutives($processObj);

				// execute pre-allocation process and pre-allocate profiles to executives 
				$this->executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,'',$agentsFinal);
			  }
			}	
		}
                else
                {
                        foreach($locsArray as $state=>$cityArr)
                        {
                                $processObj->setState($state);
                                foreach($cityArr as $city=>$centerArr)
                                {
                                        $processObj->setCity($city);
                                        foreach($centerArr as $key=>$center)
                                        {
						$processObj->setCenter($center);
                                                $agents=$agentAllocDetailsObj->fetchExecutives($processObj);  
						if(is_array($agents)){
							$cityAgents=array_merge($cityAgents,$agents);
							$statesAgents=array_merge($statesAgents,$agents);
							$agentsFinal=array_merge($agentsFinal,$agents);
						}
                                                if($level==1)
							$this->executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,$center,$agents);
                                        }
					//$citySelArr=$locationObj->fetchSpecialCities();
					$citySelArr =$processObj->getSpecialCityList();
                                        if($level==2||($level==6 && array_key_exists("$city",$citySelArr))){
						$this->executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,$city,$agentsFinal,$cityAgents);
					}
					unset($cityAgents);
					$cityAgents=array();
					$cities[]=$city;
                                }
                                if($level==3||$level==5){
					$this->executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,$cities,$statesAgents);
				}
				unset($cities);
				unset($statesAgents);
				$statesAgents=array();
                        }
                        if($level==4){
                                //$cities=$agentAllocDetailsObj->fetchRestIndiaStatesCities();
				$cities =$processObj->getRestIndCities();
				$this->executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,$cities,$agentsFinal);
                        }
                }
        }
	public function fetchOutboundProfilesCount($processObj)
	{
		$agentAllocDetailsObj	=new AgentAllocationDetails();
                $subMethod 		=$processObj->getSubMethod();
                if(!$subMethod)
                        $subMethodArr =array("FOLLOWUP","FFOLLOWUP","NEW_PROFILES","ONLINE_NEW_PROFILES","SUB_EXPIRY","RENEWAL","UPSELL","RENEWAL_NOT_DUE","HANDLED","FTA",'FIELD_SALES');
                else
                        $subMethodArr =array($subMethod);

                for($i=0; $i<count($subMethodArr); $i++){
                        $subMethod =$subMethodArr[$i];
                        $processObj->setSubMethod($subMethod);
                        $profiles = $agentAllocDetailsObj->fetchProfiles($processObj);
			if($profiles)
                        	$profilesCountArr[$subMethod]=count(array_filter($profiles));
                }
                return $profilesCountArr;
	}
	public function fetchOutboundProfilesDisplayList($processObj, $pageLimit=0, $pageIndex=0)
	{
		$agentAllocDetailsObj   =new AgentAllocationDetails();
		$subMethod		=$processObj->getSubMethod();
		$profiles               =$agentAllocDetailsObj->fetchProfiles($processObj);
		$profiles		=array_filter($profiles);
		$profiles		=array_values($profiles);
		for($i=$pageIndex;$i<$pageIndex+$pageLimit;$i++){
			$profileid =$profiles[$i];
			if($profileid){			
				$profiles_arr =$agentAllocDetailsObj->fetchProfileDetails(array($profileid),$subMethod);
				$profilesArr[$profileid] =$profiles_arr[$profileid];
			}
		}
		return $profilesArr;
	}
	public function allocate($processObj,$paramsArr=array())
        {
		$agentAllocObj		=new AgentAllocation();
		$agentAllocDetailsObj	=new AgentAllocationDetails();
		$method 		=$processObj->getMethod();
		$subMethod		=$processObj->getSubMethod();

		if($method=='UPSELL' || $method=='RENEWAL' || $method=='NEW_FAILED_PAYMENT' || $method=='TRANSFER_PROFILES' || $method=='REALLOCATION' || $method=='WEBMASTER_LEADS'){
			$agents =$processObj->getExecutives();
			if(count($agents)<1){
                		$agents=$agentAllocDetailsObj->fetchExecutives($processObj);
				$processObj->setExecutives($agents);
			}
		
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
			//Transfer Field Sales Single Profile
			$singleProfile = $processObj->getProfiles();	
			if($method=='TRANSFER_PROFILES' && $subMethod == 'FIELD_SALES' && $singleProfile !='')
			{
				if(count($profiles)>0){
                                       	if(!in_array($singleProfile,$profiles))
                                               	return "NOT_ALLOTED";
                                }
                                else
                                       	return "NOT_ALLOTED";
                                $processObj->setProfiles(array($singleProfile));
			}
			//End
			else
			        $processObj->setProfiles($profiles);
			unset($profiles);
			$agentAllocObj->profileAllocation($processObj);
		}
		else if($method=='MANUAL' || $method=='MANUAL_EXT' || $method=='MANUAL_EXT_DAYS'){
			$profileDetailsArr=$agentAllocDetailsObj->fetchJProfileDetails($paramsArr['USERNAME'],'PROFILEID,PHONE_MOB,PHONE_WITH_STD,EMAIL');
			$paramsArr        =array_merge($paramsArr,$profileDetailsArr);
			$agentAllocObj->profileAllocation($processObj,$paramsArr);
		}
		else if($method=='FIELD_SALES'){
			$this->executeAllocationLocationBased($processObj);
			$processId =$processObj->getIdAllot();	
			$screenedTimeEnd =$processObj->getEndDate();
			$lastHandledDtObj =new incentive_LAST_HANDLED_DATE();		
			$lastHandledDtObj->setHandledDate($processId,$screenedTimeEnd);
		}
		else
			$agentAllocObj->profileAllocation($processObj,$paramsArr);
		
        }

	// Location based allocation process 
	public function executeAllocationLocationBased($processObj)
	{
		$method 		=$processObj->getMethod();
		$sublocationObj		=new incentive_SUB_LOCATION('newjs_slave');
		$agentAllocDetailsObj   =new AgentAllocationDetails();
		$pincodeMappedCity      =crmParams::$fieldSalesPincodeMappedCity;
		if($method=='FIELD_SALES'){
		
			$profiles =$agentAllocDetailsObj->fetchProfiles($processObj);
			//echo "profiles shortlisted=";print_r($profiles);

			//loop reduces the processing for each field sales city
			if(count($profiles)>0){
				foreach($profiles as $key=>$val)
					$cityArr[] =$key;

				foreach($cityArr as $cityKey=>$cityVal){
					if(in_array("$cityVal",$pincodeMappedCity)){
						$sublocationArr =$sublocationObj->fetchSubLocations($cityVal);
						//echo "\nsubLocation=";print_r($sublocationArr);
						foreach($sublocationArr as $sublocationKey=>$sublocation)
							$this->executeAllocation($processObj,$cityVal,$sublocation,$profiles);
					}
					else
						$this->executeAllocation($processObj,$cityVal,'',$profiles);
				}
			}
		}
	}	
	// Execute Allocation Process	
	public function executeAllocation($processObj,$city='',$sublocation='',$profiles='')
	{
		$method                 =$processObj->getMethod();
		$pincodeMapObj          =new newjs_PINCODE_MAPPING('newjs_slave');		
		$agentAllocDetailsObj	=new AgentAllocationDetails();	
		$locationObj		=new incentive_LOCATION('newjs_slave');		
		$subLocationObj		=new incentive_SUB_LOCATION('newjs_slave'); 	
		$agentAllotedObj	=new AGENT_ALLOTED('newjs_masterRep');
		$agentAllocObj          =new AgentAllocation();
		//$newCityMapping       =array('UP47'=>'UP25','UP48'=>'UP12');	
		$newCityMapping         =array('UP48'=>'UP12');
		$newProfiles    	=array();

		if($sublocation){
			$center 	=$sublocation;
			$pincodeArr 	=$pincodeMapObj->getPincode($sublocation);
			if(count($pincodeArr)>0){
				foreach($pincodeArr as $key=>$pincode){
					$profilesArr =$profiles[$city][$pincode];
					if(count($profilesArr)>0)
						$newProfiles =array_merge($newProfiles,$profilesArr);
					unset($profilesArr);
				}
				unset($pincodeArr);
			}
		}
		else{
			$newProfiles =$profiles[$city][0];
			if(array_key_exists("$city",$newCityMapping))
				$city=$newCityMapping[$city];
			$center =$locationObj->fetchLocationName($city);
			if(!$center)
				$center =$subLocationObj->fetchSubLocationLabel($city);	
		}
		if(count($newProfiles)>0){
			$processObj->setProfiles($newProfiles);		
			$processObj->setCenter($center);
			$allocLimit =$agentAllotedObj->getAllocationLimitForCenter($center,$method);
			$processObj->setLimit($allocLimit);
			$executives =$agentAllocDetailsObj->fetchExecutives($processObj);
			$processObj->setExecutives($executives);
			$agentAllocObj->profileAllocation($processObj);
			//echo "=====";echo "\ncenter=$center";echo "\nprofiles =";print_r($newProfiles);echo "\nexecutives=";print_r($executives);
		}		
	}
	
	public function executePreAllocation($processObj,$agentAllocDetailsObj,$agentPreAllocationObj,$centers,$agents,$cityAgents="")
	{
		$level=$processObj->getLevel();	
		$limitArr=$processObj->getLimitArr();
		$processObj->setProfileCities($centers);

		if($level==6||$level==2){
			$cityAgents=$this->fetchFilteredAgents($cityAgents,$limitArr,$processObj);
			$processObj->setExecutives($cityAgents);
		}
		else{
			if($level!=-4)
				$agents=$this->fetchFilteredAgents($agents,$limitArr,$processObj);
			$processObj->setExecutives($agents);
		}
		if(count($agents)||count($cityAgents))
		{	
			//echo "Registering Agents=";print_r($agents); 	
			$profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
			$processObj->setProfiles($profiles);
			//echo "Profiles=";print_r($profiles);	
	
                        // set executive for level -4
                        if($level==-4){
                                $privilegeSet ='%ExcFID%';
                                $processObj->setPrivilege($privilegeSet);
                                $agentsFinal=$agentAllocDetailsObj->fetchExecutives($processObj);
                                $agents=$this->fetchFilteredAgents($agentsFinal,$limitArr,$processObj);
                                $processObj->setExecutives($agents);
                        }
			// level -4 ends

			$agentPreAllocationObj->pre_all($processObj);
		}
	}
	public function removeProfiles($processObj,$agentAllocDetailsObj,$agentDeAllocObj) 		
	{
		$jprofileObj=new JPROFILE('newjs_masterRep');
		$profiles=$processObj->getProfiles();
		$deletedProfiles=array();
		$subMethod=$processObj->getSubMethod();
		$profilesForDeletion=array();
		$serviceStatusObj=new BILLING_SERVICE_STATUS('newjs_masterRep');
		$mainAdminObj=new incentive_MAIN_ADMIN('newjs_masterRep');
		if($subMethod=="SALES_OTHERS" || $subMethod=="NEGATIVE_LIST")
		{
			for($i=0;$i<count($profiles);$i++)
			{
				$profileid=$profiles[$i];
				$profilesDetails=$jprofileObj->get($profileid,"PROFILEID","SUBSCRIPTION");
				$subscription=$profilesDetails['SUBSCRIPTION'];
				if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
				{
					if($subMethod=='NEGATIVE_LIST'){
						$profilesForDeletion[]=$profiles[$i];
					}
					else{
						$expiryDt=$serviceStatusObj->getMaxExpiryDate($profileid);
						if(JSstrToTime($expiryDt)==JSstrToTime(date("Y-m-d",time())))
							$profilesForDeletion[]=$profiles[$i];
						else
							continue;
					}
				}
				else
					$profilesForDeletion[]=$profiles[$i];
			}
			$profiles=$profilesForDeletion;
		}
		if(is_array($profiles))
		{
			for($i=0;$i<count($profiles);$i++)
			{
				$curAllotedTo =$mainAdminObj->get($profiles[$i],'PROFILEID','ALLOTED_TO');
				$status=$agentDeAllocObj->deleteProfileFromBucket($profiles[$i]);
				if($status){
					$deletedProfiles[]		=$profiles[$i];
					$allotedAgent			=$curAllotedTo['ALLOTED_TO'];
					$allotedToArr[$profiles[$i]] 	=$allotedAgent;
					$agentProfileArr[$allotedAgent][]=$profiles[$i];
				}
			}
			if($processObj->getMethod()=="MANUAL")
			{
				$id_arr=$agentAllocDetailsObj->fetchProfilesCda($processObj);
				$processObj->setIdAllot($id_arr);
				$agentDeAllocObj->trackFromCrmDailyAllot($processObj);
				$del_cda=$agentDeAllocObj->deleteFromAllot($processObj);
				if($subMethod=="RELEASE_PROFILE")
					$msg=$this->mailMessage($deletedProfiles,$processObj,$processObj->getUsername());	
				else
					$msg=$this->mailMessage($deletedProfiles,$processObj,$allotedToArr);
			}
			else
				$msg=$this->mailMessage($deletedProfiles,$processObj,$allotedToArr,$agentProfileArr);
		}
		else
		{
			if($subMethod=="NO_LONGER_WORKING")
				$msg="Either Wrong Username Or No profiles Alloted to the user.";
			elseif($subMethod=="RELEASE_PROFILE")
				$msg="Wrong Username";
			else	
				$msg="No Eligible profiles found for $subMethod !!! <br>";
		}
		return $msg;
	}
        public function deAllocateDisp($processObj,$agentAllocDetailsObj,$agentDeAllocObj)	
        {
		$jprofileObj=new JPROFILE('newjs_masterRep');
                //$limit=$processObj->getLimit();
		$subMethod=$processObj->getSubMethod();
                $disp_order_arr=$agentAllocDetailsObj->fetchDispositionOrder();
                $tot_disp=count($disp_order_arr);
                $executives=$agentAllocDetailsObj->fetchExecutives($processObj);
                $tempAllocBucketObj=new TEMP_ALLOCATION_BUCKET('newjs_master');
		$mainAdminObj=new incentive_MAIN_ADMIN('newjs_masterRep');
                $tempAllocBucketObj->truncate();

		// Added new code for Renewal
		$utilityObj =new crmUtility();
                $jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
                $privilegeArr =$jsadminPswrdsObj->getPrivilegesForSalesTarget();

		for($i=0;$i<count($executives);$i++)
                {
                        $exe_arr = explode(":",$executives[$i]);
                        $exe = $exe_arr[0];
			$processObj->setUsername($exe);

			// Added new code for Renewal
			$privilegeChk =$privilegeArr[$exe];
			$processNameChk =$utilityObj->getProcessName($privilegeChk);
			$processNameChk =trim($processNameChk);

                        $profiles=$agentAllocDetailsObj->fetchProfiles($processObj);
			$profiles=$agentAllocDetailsObj->fetchHistoryOfProfiles($profiles);
			for($h=0;$h<count($profiles);$h++)
                        {
				/*if($subMethod=='LIMIT_EXCEED_RENEWAL'){
					$profilesForInsertion[]=$profiles[$h];
				}*/
				if($processNameChk =='CENTRAL_RENEW_TELE'){
					$profilesForInsertion[]=$profiles[$h];
				}
				else{
					$profileid=$profiles[$h]["PROFILEID"];
					$profilesDetails=$jprofileObj->get($profileid,"PROFILEID","SUBSCRIPTION");
                                	$subscription=$profilesDetails['SUBSCRIPTION'];
                                	if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
                                	                continue;
                                	else
                               			$profilesForInsertion[]=$profiles[$h];
				}	
                        }
			$processObj->setProfiles($profilesForInsertion);
                        $agentDeAllocObj->insertProfilesTemp($processObj);
                }
		$fexecutives=$tempAllocBucketObj->fetchFinalExecutives($processObj);
		// New limit logic
		/*$utilityObj =new crmUtility();	
		$jsadminPswrdsObj = new jsadmin_PSWRDS('newjs_slave');
		$privilegeArr =$jsadminPswrdsObj->getPrivilegesForSalesTarget();*/
		$limitArr =$processObj->getLimitArr();
		$exceed =0;
		// end

                for($i=0;$i<count($fexecutives);$i++)
                {
                        $exe_arr = explode(":",$fexecutives[$i]);
                        $exe = $exe_arr[0];
                        $cnt = $exe_arr[1];
			// New limit logic
			$privilege =$privilegeArr[$exe];
			$processName =$utilityObj->getProcessName($privilege);
			$limit = $limitArr[$processName];
			
			if($cnt>=$limit)
	                        $exceed = $cnt-$limit;
                        $processObj->setUsername($exe);
                        $processObj->setExceed($exceed);
                        for($d=1; $d<=$tot_disp; $d++)
                        {
                                $disposition =$disp_order_arr[$d];
                                $processObj->setDisposition($disposition);
                                $profiles=$agentAllocDetailsObj->fetchTempProfiles($processObj); 
                                for($j=0;$j<count($profiles);$j++)
                                {
					$curAllotedTo =$mainAdminObj->get($profiles[$j],'PROFILEID','ALLOTED_TO');
                                        $status=$agentDeAllocObj->deleteProfileFromBucket($profiles[$j]);
					if($status){
	                                        $deletedProfiles[]=$profiles[$j];
        	                                $allotedToArr[$profiles[$j]] =$curAllotedTo['ALLOTED_TO'];
					}
                                        $exceed--;
					if($exceed<1)
                                	        break;
                                }
                                if($exceed<1)
                                        break;
                        }
                }
		if(is_array($deletedProfiles))
			$msg=$this->mailMessage($deletedProfiles,$processObj,$allotedToArr);
		else
			$msg="No eligible Profiles For $subMethod <br>";
		return $msg;
        }
	public function mailMessage($profiles,$processObj,$username="",$agentProfileArr='')
	{
		$subMethod = $processObj->getSubMethod();
		$loggedinExec = $processObj->getExecutive();
		$deallocTrackObj=new incentive_DEALLOCATION_TRACK();
		$crmDailyAllotObj=new CRM_DAILY_ALLOT();
		$crmDailyAllotTrackObj =new CRM_DAILY_ALLOT_TRACK();
		$pswrdsObj =new jsadmin_PSWRDS('newjs_masterRep'); 
		$realDeAllocationDt =date('Y-m-d H:i:s',time());

		if($subMethod=="NO_LONGER_WORKING")
		{
			$msg="User Released";
			for($i=0;$i<count($profiles);$i++){
				$deallocTrackObj->insertDeAllocationEntry($profiles[$i],$subMethod,$username[$profiles[$i]],$realDeAllocationDt,$loggedinExec);
				$crmDailyAllotTrackObj->updateDeallocationDt($profiles[$i],$username[$profiles[$i]],$realDeAllocationDt);
			}
		}
		elseif($subMethod=="RELEASE_PROFILE")
		{
			$msg="Profile Released";
			$deallocTrackObj->insertDeAllocationEntry($profiles[0],$subMethod,$username,$realDeAllocationDt,$loggedinExec);
			$crmDailyAllotTrackObj->updateDeallocationDt($profiles[0],$username,$realDeAllocationDt);
		}
		else
		{
			$msg="Profiles Deleted in $subMethod are :<br> ";
			$fromEmail ="From:JeevansathiCrm@jeevansathi.com";
			for($i=0;$i<count($profiles);$i++)
			{	
				$msg.=$profiles[$i]."<br>";
				$deallocTrackObj->insertDeAllocationEntry($profiles[$i],$subMethod,$username[$profiles[$i]],$realDeAllocationDt,$loggedinExec);
				$crmDailyAllotObj->updateDeallocationDt($profiles[$i],$username[$profiles[$i]],$realDeAllocationDt);
			}
			if($subMethod=='DELETED_PROFILES'){
				$agentSub 	='Profile released because of deletion';
				$usernameArr 	=$processObj->getUsername();
				$agentNameArr 	=array_keys($agentProfileArr);
				$agentInfoStr =$pswrdsObj->fetchAgentInfo($agentNameArr);

				foreach($agentProfileArr as $agentName=>$valArr){	

					$usernameNewArr =array();
					$usernameStr ='';
					$valArrNew =array_values($valArr);					
					foreach($valArrNew as $key=>$profileid)
						$usernameNewArr[] =$usernameArr[$profileid];

					$usernameStr =@implode(",", $usernameNewArr);			
					$agentEmail =$agentInfoStr[$agentName]['EMAIL'];
					// send mail to executive 
					mail($agentEmail, $agentSub, $usernameStr, $fromEmail);
				}
			}
			$end_time=date("Y-m-d H:i:s");
			$to="manoj.rana@naukri.com";
	                $sub="Cron $subMethod Completed at $end_time";
        	        mail($to,$sub,$msg,$fromEmail);
		}
		return $msg;
	}
        public function checkUnEligibleCriteria($username,$agentName='',$manualExt='')
        {
		$exclRestDate   =date('Y-m-d',time()-7*86400);

                $jprofileObj 	=new JPROFILE();
                $profileDetails	=$jprofileObj->get($username,'USERNAME','ACTIVATED,PROFILEID,CITY_RES');
		$status		=$profileDetails['ACTIVATED'];
		$profileid 	=$profileDetails['PROFILEID'];
		$cityRes	=$profileDetails['CITY_RES'];

		if($manualExt){
			$agentAllocDetailsObj   =new AgentAllocationDetails();
			$history		=$agentAllocDetailsObj->fetchHistoryOfProfiles(array(0=>array("PROFILEID"=>$profileid)));
			$historyEntryDt		=$history[0]['ENTRY_DT'];
		}

		if($profileid && $status=='D'){	
                        $criteria['DELETED'] =$status;
			return $criteria;
		}
		else if(!$profileid){
			$criteria['WRONG_USERNAME'] ='Y';
			return $criteria;
		}
		else if($profileid){	
	                $mainAdminObj	=new incentive_MAIN_ADMIN();
	                $profile   	=$mainAdminObj->get($profileid,"PROFILEID","ALLOTED_TO");
			$allotedTo	=$profile['ALLOTED_TO'];
			
			if($manualExt=='extDays'){
				if(!$allotedTo){
					$criteria['CANNOT_ALLOT'] ='Y';
					return $criteria;
				}
				$extDaysCriteria =$this->checkExtDaysLimitExceed($profileid);
				if($extDaysCriteria){
					$criteria['DAYS_LIMIT_EXCEED'] ='Y';
					return $criteria;
				}
			}
	                elseif($allotedTo && !$manualExt){	
			       $criteria['ALLOTED_TO'] =$allotedTo;
				return $criteria;
			}
                        else if($allotedTo && $manualExt){
                                if($allotedTo==$agentName){
                                        if($historyEntryDt<$exclRestDate)
                                                $criteria['CANNOT_ALLOT'] ='Y';
					else{
						$extDaysCriteria =$this->checkExtDaysLimitExceed($profileid);
						if($extDaysCriteria)
							$criteria['DAYS_LIMIT_EXCEED'] ='Y';
					}
                                }
                                else
                                        $criteria['CANNOT_ALLOT'] ='Y';
				return $criteria;
                        }
                        else if($manualExt){
				$locationObj 	 =new incentive_LOCATION();
				$jsAdminPSWRDSObj=new jsadmin_PSWRDS();	

                                $location        =$locationObj->fetchLocationName($cityRes);
                                $agentDetails  	 =$jsAdminPSWRDSObj->getExecutiveDetails($agentName);
				$center		 =$agentDetails['CENTER'];
		
                                if($location!=$center){
					$crmDailyAllotObj	=new CRM_DAILY_ALLOT();
                                        $lastAllotedDetails 	=$crmDailyAllotObj->getLastAllocationDetails($profileid);
					$lastAllotedTo		=$lastAllotedDetails['ALLOTED_TO'];
					if($lastAllotedTo==''){
						$criteria['CANNOT_ALLOT'] ='Y';
                                                return $criteria;
					}
                                        if($lastAllotedTo==$agentName){
                                                if($historyEntryDt<$exclRestDate){
                                                        $criteria['CANNOT_ALLOT'] ='Y';
							return $criteria;
						}
                                        }
					else{
						$criteria['CANNOT_ALLOT'] ='Y';
                                                return $criteria;
					}
                                }
				$isPaid =$agentAllocDetailsObj->checkPaidProfile($profileid);
				if($isPaid){
					$criteria['PAID'] ='Y';
					return $criteria;
				}
                        }
			if($manualExt=='manualExt'){
                        	$profileObj=new Profile("",$profileid);
                        	$ftoStatesObj=$profileObj->getPROFILE_STATE()->getFTOStates();
                        	$ftoState= $ftoStatesObj->getState();
                        	if($ftoState!=FTOStateTypes::FTO_EXPIRED&&$ftoState!=FTOStateTypes::NEVER_EXPOSED){
					$criteria['INVALID_FTO_STATE'] ='Y';
                        	        return $criteria;
				}
			}
		}
		return;	
        }
	public function checkLocationCriteriaForAllotment($username,$agentName)
	{
                $jprofileObj    =new JPROFILE();
                $profileDetails =$jprofileObj->get($username,'USERNAME','CITY_RES,PROFILEID');
                $cityRes        =$profileDetails['CITY_RES'];
		$profileid	=$profileDetails['PROFILEID'];

                $mainAdminObj   =new incentive_MAIN_ADMIN();
                $profile        =$mainAdminObj->get($profileid,"PROFILEID","ALLOTED_TO");
                if($profile['ALLOTED_TO'])
			return false;

		$locationObj     =new incentive_LOCATION();
                $jsAdminPSWRDSObj=new jsadmin_PSWRDS();

                $location        =$locationObj->fetchLocationName($cityRes);
		$agentDetails    =$jsAdminPSWRDSObj->getExecutiveDetails($agentName);
		$center          =$agentDetails['CENTER'];

		if(($location!=$center) && $center!='NOIDA')
			return true;
		return false;			
	}
	public function checkExtDaysLimitExceed($profileid)
	{
		$agentAllocationObj		=new AgentAllocation();
		$extensionLimit			=$agentAllocationObj->fetchAllotedBucketDays('','EP');

		$agentAllocationDetailsObj 	=new AgentAllocationDetails();
		$lastAllotedDetails 		=$agentAllocationDetailsObj->fetchLastAllocationDetails($profileid);
                $relaxDays          		=$lastAllotedDetails['RELAX_DAYS'];
		if($relaxDays>=$extensionLimit)
			return true;
		return false;
	}
	public function fetchFilteredAgents($agents,$limitArr,$processObj)
        {
                $l=0;
		$allotedCountArr =array();
		$aadObj = new AgentAllocationDetails();
                $profileTechObj=new incentive_PROFILE_ALLOCATION_TECH();
		$agentDetails =$processObj->getAgentDetails();						
		if(count($agents)>0){
			$allotedCountArr =$profileTechObj->getProfileAllotedCountArr($agents); 	
			//$allotedCountArr =$profileTechObj->getProfileAllotedCountArrTemp($agents);
		}
                for($i=0;$i<count($agents);$i++)
                {
                        $uname 		=$agents[$i];
			//$limitVal 	=$aadObj->getAgentPreAllocationLimit($uname, $limitArr);
			$subCenter 	=$agentDetails[$uname]['SUB_CENTER'];
			$limitVal 	=$limitArr[$subCenter];
                        //$allotedCount	=$profileTechObj->getProfileAllotedCount($uname);
			$allotedCount	=$allotedCountArr[$uname];
                        if($allotedCount < $limitVal){
                                        $userarr[$l]['NAME'] = $uname;
                                        $userarr[$l]['ALLOTED'] = $allotedCount;
                                        $l++;
                        }
			unset($limitVal);
			unset($allotedCount);
                }
		unset($allotedCountArr);
                return $userarr;
        }

    public function updateTransferLog($profileArr, $agentName, $agentFrom, $agentTo, $subMethod, $allocationDt){
    	$transferLogObj = new incentive_TRANSFER_PROFILE_LOG();
    	if(!isset($allocationDt) && $subMethod == 'FRESH'){
    		$proAllocTechObj = new incentive_PROFILE_ALLOCATION_TECH();
    		$allocationDt = $proAllocTechObj->getFreshVisitTransferAllotDate($profileArr[0]);
    	}
       	foreach($profileArr as $key=>$val){
       		$transferLogObj->updateTransferLog($val, $agentName, $agentFrom, $agentTo, $subMethod, $allocationDt);	
       	}
       	return true;
    }
}
?>
