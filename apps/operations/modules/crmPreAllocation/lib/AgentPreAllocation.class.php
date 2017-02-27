<?php
class AgentPreAllocation
{
        public function createTempPoolForPreAllocation()
        {
                $profileTechObj=new incentive_PROFILE_ALLOCATION_TECH('newjs_masterDDL');
                $profileTechObj->truncate();
        }
	public function truncateProfilesTech()
	{
		$profileTechObj=new incentive_PROFILE_ALLOCATION_TECH('newjs_masterDDL');
		$profileTechObj->truncate();
	}
	public function truncateFTAProfilesTech()
        {
                $ftaProfileTechObj=new incentive_FTA_ALLOCATION_TECH('newjs_masterDDL');
                $ftaProfileTechObj->truncate();
        }
        public function truncatePreAllocTempPool()
        {
                $preAllocationTempPoolObj=new incentive_PRE_ALLOCATION_TEMP_POOL('newjs_masterDDL');
                $preAllocationTempPoolObj->truncate();
        }
        public function createPoolForPreAllocation()
        {
                $jprofileObj    =new JPROFILE('newjs_slave');
                $preAllocationTempPoolObj =new incentive_PRE_ALLOCATION_TEMP_POOL();

                $loginDtStart   =date("Y-m-d",time()-14*24*60*60)." 00:00:00";
                $loginDtEnd     =date("Y-m-d H:i:s");
                $profiles       =$jprofileObj->getLoggedInProfilesForPreAlloc($loginDtStart, $loginDtEnd);

		// Add profile in temp pool
                foreach($profiles as $key=>$val){
                        $preAllocationTempPoolObj->insertProfile($val['PROFILEID'],$val['CITY_RES'],$val['ISD'],$val['LAST_LOGIN_DT']);
                }
		unset($profiles);
		// Update profile details in temp pool
		$profileDetails =$preAllocationTempPoolObj->getProfileDetails();
		foreach($profileDetails as $key=>$val)
			$preAllocationTempPoolObj->updateProfileDetail($val['PROFILEID'],$val['ANALYTIC_SCORE'],$val['ALLOTMENT_AVAIL']);
	
		unset($profileDetails);
                $preAllocationTempPoolObj->removeAllotedProfiles();
        }
        /*public function createPoolForCenterPreAllocation()
        {
		$jprofileObj    =new JPROFILE('newjs_slave');
		$preAllocationTempPoolObj =new incentive_PRE_ALLOCATION_TEMP_POOL();	

		$loginDtStart   =date("Y-m-d",time()-14*24*60*60);
		$loginDtEnd     =date("Y-m-d",time()-2*24*60*60);
		$profiles       =$jprofileObj->getLoggedInProfilesForPreAlloc($loginDtStart, $loginDtEnd);

		foreach($profiles as $key=>$val){	
			$preAllocationTempPoolObj->insertProfile($val['PROFILEID'],$val['CITY_RES']);		
		}
		unset($profiles);
		$preAllocationTempPoolObj->updateScore();
		$preAllocationTempPoolObj->removeAllotedProfiles();			
        }*/
	// level wise sorting 
	public function sort_pre_all(&$profiles, $level='')
	{
       		foreach($profiles as $k=>$v){
                	$analytic_score[$k] = $v['ANALYTIC_SCORE'];
	                $last_login[$k] = $v['LAST_LOGIN_DT'];
        	}
        	if($level == 0 || $level ==-5)
		        array_multisort($analytic_score,SORT_DESC,$profiles);
	 	else
		        array_multisort($analytic_score,SORT_DESC,$last_login,SORT_DESC,$profiles);
	}
	public function pre_all($processObj)
	{
		$agents=$processObj->getExecutives();
		$profiles=$processObj->getProfiles();
		$limitArr=$processObj->getLimitArr();
		$level=$processObj->getLevel();
		$n=0;
		$aadObj = new AgentAllocationDetails();	
		$profileTechObj=new incentive_PROFILE_ALLOCATION_TECH();	
		$historyObj=new incentive_HISTORY('newjs_slave');	
		$mainAdminPoolObj =new incentive_MAIN_ADMIN_POOL('newjs_slave');	
		$total_executives=count($agents);
		$preAllocationTempPoolObj =new incentive_PRE_ALLOCATION_TEMP_POOL();	
		$agentDetails =$processObj->getAgentDetails();

		if($level==-3){
			$FTATechObj=new incentive_FTA_ALLOCATION_TECH();	
			// level wise sorting 
	                for($i=0;$i<count($profiles);$i++){
                                $profiles[$i]['ANALYTIC_SCORE'] =$mainAdminPoolObj->getAnalyticScore($profiles[$i]['PROFILEID']);
                                $this->sort_pre_all($profiles);
                        }
			$j=0;
			for($i=0;$i<count($profiles);$i++){
				$user_value = $agents[$j];
				$profileid=$profiles[$i]['PROFILEID'];
				$profile_type=$profiles[$i]['STATE_ID'];
				$lastLoginDt =$profiles[$i]['LAST_LOGIN_DT'];
				$analyticScore =$profiles[$i]['ANALYTIC_SCORE'];
				if($profileid && $user_value){
					$FTATechObj->insertProfile($profileid,$user_value,$profile_type);
					$profileTechObj->insertPreAllocationLog($profileid,$user_value,$level,$analyticScore,$lastLoginDt);
				}
				$j++;
				if($j == $total_executives)
					$j = 0;
			}
		}
		else
		{
			// level wise sorting 
			for($i=0;$i<count($profiles);$i++)
			{
				if($level>=1 || $level==-5)
					$profiles[$i]['ANALYTIC_SCORE'] =$preAllocationTempPoolObj->getAnalyticScore($profiles[$i]['PROFILEID']);
				else
					$profiles[$i]['ANALYTIC_SCORE'] =$mainAdminPoolObj->getAnalyticScore($profiles[$i]['PROFILEID']);
				$this->sort_pre_all($profiles, $level);
			}
			for($i=0;$i<count($profiles);$i++)
			{
				$uname =$agents[$n]['NAME'];
				$subCenter =$agentDetails[$uname]['SUB_CENTER'];
				$limitVal =$limitArr[$subCenter];	

				//if($agents[$n]['ALLOTED'] < $aadObj->getAgentPreAllocationLimit($agents[$n]['NAME'], $limitArr))
				if($agents[$n]['ALLOTED'] < $limitVal)
				{
					//while($agents[$n]['ALLOTED'] > $aadObj->getAgentPreAllocationLimit($agents[$n]['NAME'], $limitArr))
					while($agents[$n]['ALLOTED'] > $limitVal){
						$n++;
						if($n == $total_executives)
							$n = 0;
					}
					$user_value = $agents[$n]['NAME'];
					if($user_value !=''){
						$profileid=$profiles[$i]['PROFILEID'];
						$lastLoginDt =$profiles[$i]['LAST_LOGIN_DT'];
						$fields="ENTRY_DT";
						$whereClause="PROFILEID=$profileid";
						$orderBy=" ENTRY_DT DESC";
						$profile_type =$profiles[$i]['PROFILE_TYPE'];
						$analyticScore = $profiles[$i]['ANALYTIC_SCORE'];
						if($profileid && $user_value){
							//$profileTechObj->insertProfileTemp($profileid,$user_value,'N',$profile_type);
							//$profileTechObj->insertPreAllocationLogTemp($profileid,$user_value,$level,$analyticScore,$lastLoginDt);
							$profileTechObj->insertProfile($profileid,$user_value,'N',$profile_type);
							$profileTechObj->insertPreAllocationLog($profileid,$user_value,$level,$analyticScore,$lastLoginDt);
						}
						$agents[$n]['ALLOTED']++;
						/*$n++;
						if($n == $total_executives)
							$n = 0;*/
					}
				}
				$n++;
				if($n == $total_executives)
					$n = 0;
			}
			// delete profile for Temp Pool which are allocated
			$preAllocationTempPoolObj->removePreAllotedProfiles();		
		}
	}
}	
