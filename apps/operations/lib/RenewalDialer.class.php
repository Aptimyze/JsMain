<?php

/**
 * Description of RenewalDialer
 *
 * @author nitish
 */
class RenewalDialer {
    public function __construct()
    {
        $this->inRenewalDialerInsObj = new incentive_RENEWAL_IN_DIALER();
    }
    //Create Temp Pool
    public function createTempPoolForRenewalDialer()
    {
        $inRenewalDialerTempPool = new incentive_RENEWAL_IN_DIALER_TEMP_POOL('newjs_master');
        $inRenewalDialerTempPool->truncate();
        $profiles = $this->inRenewalDialerInsObj->fetchRenewalDialerProfiles();
        if($profiles)
        {
            foreach($profiles as $key=>$val)
            {
                $inRenewalDialerTempPool->insertProfile($val);
            }
            unset($profiles);
        }
        unset($inRenewalDialerTempPool);
    }
    
    // Pre-filter loigc
    public function preFilter($processObj)
    {
        $method =$processObj->getMethod();
        if($method =='IN_RENEWAL_DIALER_ELIGIBILITY')
        {
            $inRenewDialerTempPool = new incentive_RENEWAL_IN_DIALER_TEMP_POOL('newjs_slave');
	    $inRenewDialerTempPoolRemove = new incentive_RENEWAL_IN_DIALER_TEMP_POOL();
            
            // DO NOT CALL Filter
            $filter ='DO_NOT_CALL';
            $profiles = $inRenewDialerTempPool->fetchDoNotCallProfiles();
            if(count($profiles) > 0)
            {
                $this->updateRenewalDialer($profiles,"N");
                $inRenewDialerTempPoolRemove->removeDoNotCallProfiles();
                unset($profiles);
            }
            
            // Negative Treatment Filter
            $filter ='NEGATIVE_TREATMENT';
            $profiles = $inRenewDialerTempPool->fetchNegativeTreatmentProfiles();
            if(count($profiles) > 0)
            {
                $this->updateRenewalDialer($profiles,"N");
                $inRenewDialerTempPoolRemove->removeNegativeTreatmentProfiles();
                unset($profiles);
            }
            
            // Pre-Allocation Filter
            $filter ='PRE_ALLOCATED';
            $profiles = $inRenewDialerTempPool->fetchPreAllocatedProfiles();
            if(count($profiles) > 0)
            {
                $this->updateRenewalDialer($profiles,"N");
                $inRenewDialerTempPoolRemove->removePreAllocatedProfiles();
                unset($profiles);
            }
        }
    }
    
    public function fetchProfiles($processObj)
    {
        $method =$processObj->getMethod();
        if($method=='IN_RENEWAL_DIALER_ELIGIBILITY')
        {
            $inRenewDialerObj = new incentive_RENEWAL_IN_DIALER_TEMP_POOL();
            $profiles = $inRenewDialerObj->fetchProfiles();
        }
        return $profiles;
    }
    
    public function filterProfiles($profileArr)
    {
        
        if($profileArr)
        {
            //Filter for Renewal Period (E-30 to E+10)
            $agentAllocationDetailsObj = new AgentAllocationDetails();
            $profilesInRenewalPeriod = $agentAllocationDetailsObj->getProfilesInRenewalPeriod();
            if(is_array($profilesInRenewalPeriod)){
                foreach($profilesInRenewalPeriod as $key => $val){
                    $eligibleProfilesArr[] = $val["PROFILEID"];
                }
            }
            $alertsObj = new JprofileAlertsCache('newjs_slave');
            $historyObj = new incentive_HISTORY('newjs_slave');
            $jprofileObj = new JPROFILE('newjs_slave');
            
            $excl_dnc_dt    =@date('Y-m-d',time()-(7-1)*86400);
            $excl_cf_dt     =@date('Y-m-d',time()-(7-1)*86400);
            
            $fields		='ENTRY_DT,ACTIVATED,INCOMPLETE,PHONE_FLAG,LAST_LOGIN_DT,MTONGUE,ISD';
            foreach($profileArr as $k => $dataField)
            {
                $profileid = $dataField;
                if(is_array($eligibleProfilesArr)){
                    if(!in_array($profileid,$eligibleProfilesArr))
                    {
                        $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
                        continue;
                    }
                }
                $memStatus = $alertsObj->fetchMembershipStatus($profileid);
                $memCall = $memStatus["MEMB_CALLS"];
                $offerCall = $memStatus["OFFER_CALLS"];
                if($memCall=='U')
                {
                    $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
                    continue;
                }
                if($offerCall=='U')
                {
                    $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
                    continue;
                }
                
                $jProfileArr = $jprofileObj->get($profileid,'PROFILEID',$fields);
                $mtongue = $jProfileArr['MTONGUE'];
                if($mtongue == 1)
                {
                    $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
                    continue;
                }
                // Check for Inactive Profile, Incomplete Profile, Invalid Phone, last login within 30 days and NRI Phone Numbers
                $jProfileCheck =$this->filterJprofileCheckList($profileid,$jProfileArr);
                if(!$jProfileCheck)
                {
                    continue;
                }
                
                $dispositionDetArr 	= $historyObj->getLastDispositionDetails($profileid, 'ENTRY_DT,DISPOSITION');
                $disposition		= $dispositionDetArr['DISPOSITION'];
                $dispEntryDtArr		= @explode(" ", $dispositionDetArr['ENTRY_DT']);
                $dispEntryDt		= $dispEntryDtArr[0];
                if($disposition)
                {
                    if(($disposition == 'D') || ($disposition == 'DNC' && $dispEntryDt >= $excl_dnc_dt) || ($disposition == 'CF' && $dispEntryDt >= $excl_cf_dt))
                    {
                        $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
                        continue;
                    }
		    if($disposition=='NI'){
			$todayDate 	=date("Y-m-d");
			$prev2Days    	=date('Y-m-d', strtotime('-1 days',strtotime($todayDate)));
			if(strtotime($dispEntryDt)>=strtotime($prev2Days)){
				$this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");	
				continue;	
			}
		    }	
                }
                $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "Y");
                unset($jProfileArr);
            }
        }
    }
    
    // JPROFILE filter check
    public function filterJprofileCheckList($profileid,$fieldsArr)
    {
        if($fieldsArr['ACTIVATED']!='Y' && $fieldsArr['ACTIVATED']!='H'){
            $filterCheck = true;
		}
		elseif($fieldsArr['INCOMPLETE']!='N'){
            $filterCheck = true;
		}
		elseif($fieldsArr['PHONE_FLAG']=='I'){
            $filterCheck = true;
		}	
		elseif(strtotime($fieldsArr['LAST_LOGIN_DT']) < strtotime(date('Y-m-d',strtotime('-29 days')))){
            $filterCheck = true;
		}
        elseif($fieldsArr['ISD']!='91'){
            $filterCheck = true;
		}
        if($filterCheck)
        {
            $this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, "N");
            return;
        }
        return true;
    }
    
    public function updateRenewalDialer($profiles, $status)
    {
        if(count($profiles)>0)
        {
            foreach($profiles as $key=>$val)
            {
				$profileid =$val['PROFILEID'];
				$this->inRenewalDialerInsObj->updateRenewalDialerEligibility($profileid, $status);
			}
            unset($profiles);
        }
    }
}
