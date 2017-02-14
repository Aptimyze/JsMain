<?php
/*
   * Author: Neha Gupta
   * It contains all the logics and updation process of In-Dialer 
*/

class Dialer
{
        public function __construct(){
		$this->indialerLogObj = new incentive_IN_DIALER_PROFILE_LOG();
		$this->indialerInsObj = new incentive_IN_DIALER();
	}
	// Create Temp Pool
        public function createTempPoolForDialer()
        {
		$indialerTempPool =new incentive_IN_DIALER_TEMP_POOL();
                $profiles =$this->indialerInsObj->fetchDialerProfiles();

                foreach($profiles as $key=>$val)
                        $indialerTempPool->insertProfile($val);

                unset($profiles);
		unset($indialerTempPool);
        }
	// Pre-filter loigc 
        public function preFilter($processObj)
        {
		$method =$processObj->getMethod();
                if($method=='IN_DIALER_ELIGIBILITY'){
			$indialerTempPool =new incentive_IN_DIALER_TEMP_POOL('newjs_slave');
			$indialerTempPoolRemove =new incentive_IN_DIALER_TEMP_POOL();

                        // DO NOT CALL Filter
                        $filter ='DO_NOT_CALL';
                        $profiles= $indialerTempPool->fetchDoNotCallProfiles();
                        if(count($profiles)>0){
                                $this->dialerProfileLog($profiles,'N',$filter,'Y');
                                $indialerTempPoolRemove->removeDoNotCallProfiles();
                                unset($profiles);
                        }
                        // Negative Treatment Filter
                        $filter ='NEGATIVE_TREATMENT';
                        $profiles =$indialerTempPool->fetchNegativeTreatmentProfiles();
                        if(count($profiles)>0){
                                $this->dialerProfileLog($profiles,'N',$filter,'Y');
                                $indialerTempPoolRemove->removeNegativeTreatmentProfiles();
                                unset($profiles);
                        }
                        // Pre-Allocation Filter
                        $filter ='PRE_ALLOCATED';
                        $profiles =$indialerTempPoolRemove->fetchPreAllocatedProfiles();
                        if(count($profiles)>0){
                                $this->dialerProfileLog($profiles,'N',$filter,'Y');
                                $indialerTempPoolRemove->removePreAllocatedProfiles();
                                unset($profiles);
                        }
		}	
        }

	// logging of Dialer eligible/in-eligible profiles
	public function updateIndialerProfileLog($profileid,$username,$eligible,$filterName='',$filterValue='')
	{
		// data logging 
		$this->indialerLogObj->insertProfile($profileid, $username, $eligible, $filterName, $filterValue);

		// IN_DIALER eligible/in-eligible updates	
		$this->indialerInsObj->updateDialerEligibility($profileid, $eligible);
	}
	// fetch profiles from Dialer Pool
	public function fetchProfiles($processObj){

		$method =$processObj->getMethod();
		if($method=='IN_DIALER_ELIGIBILITY'){
			$indialerObj    =new incentive_IN_DIALER_TEMP_POOL();
			$profiles       =$indialerObj->getDialerProfileBasedOnJoins('incentive.MAIN_ADMIN_POOL', 'PROFILEID,ANALYTIC_SCORE');
		}
		return $profiles;	
	}
	// filter profiles to check Dialer Eligibility
	public function filterProfiles($profileArr){

		if($profileArr){
			$alertsObj 	=new JprofileAlertsCache('newjs_slave');
			$historyObj 	=new incentive_HISTORY('newjs_slave');
			$jprofileObj    =new JPROFILE('newjs_slave');
	                $purchaseObj 	=new BILLING_PURCHASES('newjs_slave');
        	        $everPaidPool  	=$purchaseObj->fetchEverPaidPool();

			$excl_dnc_dt    =@date('Y-m-d',time()-(30-1)*86400);
			$excl_ni_dt     =@date('Y-m-d',time()-(7-1)*86400);
			$excl_cf_dt     =@date('Y-m-d',time()-(7-1)*86400);
			$excl_d_dt      =@date('Y-m-d',time()-(30-1)*86400);
			$fields		='USERNAME,SUBSCRIPTION,ENTRY_DT,ACTIVATED,INCOMPLETE,PHONE_FLAG,COUNTRY_RES,LAST_LOGIN_DT,MTONGUE';
			
			foreach($profileArr as $k => $dataFieldArr){

                        	$analyticScore  =$dataFieldArr['ANALYTIC_SCORE'];
                        	$profileid      =$dataFieldArr['PROFILEID'];
	
                        	if($analyticScore<70 || $analyticScore>100){
                        	        $this->updateIndialerProfileLog($profileid,$username,'N',"ANALYTIC_SCORE",$analyticScore);
					continue;	
				}
	
				$memStatus 	=$alertsObj->fetchMembershipStatus($profileid);
				$memCall   	=$memStatus["MEMB_CALLS"];
				$offerCall	=$memStatus["OFFER_CALLS"];
				if($memCall=='U'){
					$this->updateIndialerProfileLog($profileid,$username,'N',"MEMBBERSHIP_CALL",$memCall);  
					continue;
				}
				if($offerCall=='U'){
					$this->updateIndialerProfileLog($profileid,$username,'N',"OFFER_CALL",$offerCall);
					continue;
				}
				$dispositionDetArr 	=$historyObj->getLastDispositionDetails($profileid, 'ENTRY_DT,DISPOSITION');
				$disposition		=$dispositionDetArr['DISPOSITION'];
				$dispEntryDtArr		=@explode(" ", $dispositionDetArr['ENTRY_DT']);
				$dispEntryDt		=$dispEntryDtArr[0];	
				if($disposition){
					if(($disposition=='D' && $dispEntryDt>=$excl_d_dt) || ($disposition=='DNC' && $dispEntryDt>=$excl_dnc_dt) || ($disposition=='NI' && $dispEntryDt>=$excl_ni_dt) || ($disposition=='CF' && $dispEntryDt>=$excl_cf_dt)){
						$this->updateIndialerProfileLog($profileid,$username,'N',"DISPOSITION",$disposition);
						continue;
					}	
					if(!in_array($profileid, $everPaidPool)){
						$allDispositionCount =$historyObj->getCountOfDisposition($profileid);
						$singleDispositionCount =$historyObj->getCountOfDisposition($profileid,'CNC');
						if($allDispositionCount>25 || $singleDispositionCount >=5){
							$this->updateIndialerProfileLog($profileid,$username,'N',"DISPOSITION",$disposition);
							continue;
						}
					}
				}

				$jProfileArr    =$jprofileObj->get($profileid,'PROFILEID',$fields);
                                $username       =$jProfileArr['USERNAME'];
                                $mtongue        =$jProfileArr['MTONGUE'];

				if($mtongue==1){
                                        $this->updateIndialerProfileLog($profileid,$username,'N',"MTONGUE",$mtongue);
                                        continue;
				}

				$jProfileCheck =$this->filterJprofileCheckList($profileid,$jProfileArr);
				if(!$jProfileCheck) 
					continue;	
				$logincFreqCheck =$this->loginFrequencyFilter($profileid,$username,$dispEntryDt,$jProfileArr['ENTRY_DT']);
				if(!$logincFreqCheck)
					continue;
				$this->updateIndialerProfileLog($profileid,$username,'Y');
				unset($jProfileArr);
				unset($dispositionDetArr);
				unset($dispEntryDtArr);
			}
		}
	}

	// JPROFILE filter check
	public function filterJprofileCheckList($profileid,$fieldsArr)
	{
		if($fieldsArr['ACTIVATED']!='Y' && $fieldsArr['ACTIVATED']!='H'){
			$filterName 	='INACTIVE';
			$filterVal	=$fieldsArr['ACTIVATED'];
		}
		elseif($fieldsArr['INCOMPLETE']!='N'){
			$filterName 	='INCOMPLETE';
			$filterVal      =$fieldsArr['INCOMPLETE'];
		}
		elseif($fieldsArr['PHONE_FLAG']=='I'){
			$filterName 	='PHONE_INVALID';
			$filterVal      =$fieldsArr['PHONE_FLAG'];
		}
		elseif($fieldsArr['COUNTRY_RES']!=51){
			$filterName 	='COUNTRY_NRI';
			$filterVal      =$fieldsArr['COUNTRY_RES'];		
		}	
		elseif(strtotime($fieldsArr['LAST_LOGIN_DT']) < strtotime(date('Y-m-d',strtotime('-29 days')))){
			$filterName 	='LAST_LOGIN_30DAYS';
			$filterVal      =$fieldsArr['LAST_LOGIN_DT'];
		}
		elseif((strstr($fieldsArr['SUBSCRIPTION'],"F")!="") || (strstr($fieldsArr['SUBSCRIPTION'],"D")!="")){
			$filterName	='MAIN_SUBSCRIPTION';
			$filterVal      =$fieldsArr['SUBSCRIPTION'];
		}
		if($filterName){
			$this->updateIndialerProfileLog($profileid,$fieldsArr['USERNAME'],'N',$filterName,$filterVal);		
			return;
		}
		return true;
	}

	// login frequency check 
	public function loginFrequencyFilter($profileid,$username,$dispEntryDt,$regEntryDt)
	{
		$excl_5day_dt	=strtotime(date('Y-m-d',time()-5*86400));
		$excl_3day_dt	=strtotime(date('Y-m-d',time()-3*86400));
		$dispDate	=strtotime($dispEntryDt);
		$regEntryDtArr	=@explode(" ",$regEntryDt);
		$regEntryDt	=$regEntryDtArr[0];

		$dt1 		=date_create(date('Y-m-d'));
		$dt2 		=date_create($regEntryDt);
		$interval	=date_diff($dt2,$dt1);
		$diff 		=$interval->format('%R%a'); // find the number of days a user has been registered with us
		if($diff > 30)
			$diff =30;
		$date_30Days 	=date('Y-m-d', strtotime('-29 days'));	

		$dbName 	=JsDbSharding::getShardNo($profileid);
		$loginObj 	=new NEWJS_LOGIN_HISTORY($dbName,"slave");
		$loginCnt 	=$loginObj->getLoginCount($profileid,$date_30Days);
		unset($loginObj);

		if($loginCnt && ($diff>0))
			$login_frequency=abs(($loginCnt/$diff)*100); 
		if($login_frequency>33){
			if($dispDate>$excl_5day_dt)
				$filterName ='LOGIN_FREQUENCY';
		}
		else if($dispDate>$excl_3day_dt)
			$filterName ='LOGIN_FREQUENCY';

		if($filterName){
			$this->updateIndialerProfileLog($profileid,$username,'N',$filterName,$login_frequency);
			return;
		}
		return true;
	}
        // sales CSV Profile logging
        public function dialerProfileLog($profiles,$eligible,$filterName,$filterVal)
        {
                if(count($profiles)>0){
                        foreach($profiles as $key=>$val){
				$profileid =$val['PROFILEID'];
				$this->updateIndialerProfileLog($profileid,$username,$eligible,$filterName,$filterVal);
			}
			unset($profiles);
                }
        }
}
?>
