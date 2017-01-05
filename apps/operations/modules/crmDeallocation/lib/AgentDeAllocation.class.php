<?php
class AgentDeAllocation
{
	public function insertProfilesTemp($processObj)
	{
		$tempAllocBucketObj=new TEMP_ALLOCATION_BUCKET();
		$profiles=$processObj->getProfiles();
		for($i=0;$i<count($profiles);$i++)
		{
			$profile=$profiles[$i];
			$tempAllocBucketObj->insertProfiles($profile);
		}
	}		
	public function deleteProfileFromBucket($profileid)
	{
		$mainAdminLogObj=new incentive_MAIN_ADMIN_LOG();
		if($mainAdminLogObj->insertProfile($profileid))
		{	
			$mainAdminObj=new incentive_MAIN_ADMIN();
			$mainAdminPoolObj=new incentive_MAIN_ADMIN_POOL();
			$deleted=$mainAdminObj->deleteProfile($profileid);
			$mainAdminPoolObj->updateProfile($profileid);
			if($deleted)
				$status=1;
		}
        	else
                	$status=0;
		return $status;
	}
	public function deleteFromAllot($processObj)
	{
		$crmDailyObj=new CRM_DAILY_ALLOT();	
		if($processObj->getSubMethod()=="NO_LONGER_WORKING")
		{	
			$del_cda=0;
			$id_arr=$processObj->getIdAllot();
			$gone_user=$processObj->getUsername();
			for($i=0;$i<count($id_arr);$i++)
                	{
                		$id=$id_arr[$i];
                       		if($id)
                        	{
					$crmDailyObj->deleteProfile($id,$gone_user);
					$del_cda++;
                        	}
			}
			return $del_cda;
		}
		elseif($processObj->getSubMethod()=="RELEASE_PROFILE")
		{
			$id=$processObj->getIdAllot();
			$crmDailyObj->deleteProfile($id['ID']);
		}
	}
        public function trackFromCrmDailyAllot($processObj)
        {
                $crmDailyObj		=new CRM_DAILY_ALLOT('newjs_masterRep');
		$crmDailyTrackObj	=new CRM_DAILY_ALLOT_TRACK();
                if($processObj->getSubMethod()=="NO_LONGER_WORKING")
                {
                        $del_cda=0;
                        $id_arr=$processObj->getIdAllot();
                        $gone_user=$processObj->getUsername();
                        for($i=0;$i<count($id_arr);$i++){
                                $id=$id_arr[$i];
                                if($id && !in_array($id,$idSelArr)){
					$idSelArr[] =$id;
					$resultArr =$crmDailyObj->getReleasedAllocationDetails($id);
                                        $crmDailyTrackObj->insertTrackAllocationEntry($resultArr);
                                }
                        }
                }
                elseif($processObj->getSubMethod()=="RELEASE_PROFILE")
                {
                        $id=$processObj->getIdAllot();
			if($id){
	                        $resultArr =$crmDailyObj->getReleasedAllocationDetails($id['ID']);
        	                $crmDailyTrackObj->insertTrackAllocationEntry($resultArr);
			}
                }
        }
	public function updateSubExpProfiles($processObj)
	{
		$subExpProObj=new SUBSCRIPTION_EXPIRY_PROFILES();
		$subExpProObj->updateProfile($processObj);	
	}
	public function logout($checksum)
	{
		list($md, $userno)=explode("i",$checksum);
		if(md5($userno)!=$md)
		    return FALSE;
		else
		{
			/*$connectObj=new jsadmin_CONNECT();
			$res=$connectObj->deleteRowWithId($userno);
			if ($res)
				$ret = TRUE;
			else
				$ret = FALSE;*/
			$backendLibObj = new backendActionsLib(array("jsadmin_CONNECT"=>"newjs_master"),crmCommonConfig::$useCrmMemcache);
			$ret = $backendLibObj->deleteAgentLoginSession($userno);
			unset($backendLibObj);
		}
		return $ret;
	}
    public function fetchProfilesOnDisposition($lastHandledDate, $agents, $days)
    {
        $allotedProfiles = array();
        $dispositionArr = array();
        $deallocateArr = array();
        $curDate = date('Y-m-d');
        $nextDay = $days +1;
        $fetchProfilesStDate = date('Y-m-d', strtotime("-$days day",strtotime($lastHandledDate)))." 00:00:00";
        $fetchProfilesEndDate = date('Y-m-d', strtotime("-$nextDay day",  strtotime($curDate)))." 23:59:59";
        $dispStartDate = $fetchProfilesStDate;
        $dispEndDate = date('Y-m-d', strtotime('-1 day',  strtotime($curDate)))." 23:59:59";
        $mainAdminObj = new incentive_MAIN_ADMIN("newjs_masterRep");
        $allotedProfiles = $mainAdminObj->getAllotedProfilesForAgentWithinDates($agents, $fetchProfilesStDate, $fetchProfilesEndDate);
        $historyObj = new incentive_HISTORY("newjs_masterRep");
        $profileIds = implode(",",array_keys($allotedProfiles));
        $dispositionArr = $historyObj->getHistoryForProfileIds($profileIds, $dispStartDate, $dispEndDate);
        foreach($allotedProfiles as $pid => $allotTime){
            if($dispositionArr[$pid]){
                foreach($dispositionArr[$pid] as $dispPid => $val){
                    $flag = 0;
                    $endTime = date('Y-m-d',strtotime("+$days day",strtotime($allotTime)))." 23:59:59";
                    //if($val["ENTRY_DT"] >= $allotTime && ($val["ENTRY_DT"] <= $endTime) && ($days == 2 || ($days == 10 && $val["DISPOSITION"] == "FVD"))){
		    if($val["ENTRY_DT"] >= $allotTime && ($val["ENTRY_DT"] <= $endTime) && ($days == 2 || $days == 10)){	
                        $flag = 1;
                        break;
                    }
                }
                if($flag == 0){
                    $deallocateArr[] = $pid;
                }
            }
            else{
                $deallocateArr[] = $pid;
            }
        }
        unset($allotedProfiles);
        unset($dispositionArr);
        unset($mainAdminObj);
        unset($historyObj);
        return $deallocateArr;
    }
}
		
		
?>
