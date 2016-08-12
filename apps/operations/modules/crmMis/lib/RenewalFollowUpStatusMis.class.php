<?php
//This class handles all the logics related to Renewal Follow-up Status MIS

class RenewalFollowUpStatusMis
{

	public function __construct(){}

	public function fetchProfilesWithoutFollowupDateCount($reporters){
		foreach($reporters as $agent){
			$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$res[$agent] = $maObj->countProfilesWithoutFollowupDate($agent);
		}
		return $res;
	}
	public function isPaymentSuccessful($profileId, $billid){
		$pdObj = new BILLING_PAYMENT_DETAIL('newjs_masterRep');
		return ($pdObj->getLatestPaymentDateOfProfileByAgent($profileId, $billid));
	}

	public function fetchSubscriptionExpiryDate($profileId){
		$start_dt = date('Y-m-d')." 00:00:00";
		$end_dt = date('Y-m-d', strtotime('+30 day'))." 00:00:00";
		$ssObj = new BILLING_SERVICE_STATUS('newjs_masterRep');
		$billids = $ssObj->getBillId($profileId);

		foreach($billids as $bid=>$exp_dt){
			$edate = $exp_dt." 00:00:00";
			$status = $this->isPaymentSuccessful($profileId, $bid);
			if(strtotime($edate)>=strtotime($start_dt) && strtotime($edate)<strtotime($end_dt)){
				if($status)	return $exp_dt;
			}
			else{
				if($status)	return;
			}
		}
	}

	public function fetchSubscriptionExpiryDate1($profileId){
                $start_dt = date('Y-m-d')." 00:00:00";
                $end_dt = date('Y-m-d', strtotime('+30 day'))." 00:00:00";
                $ssObj = new BILLING_SERVICE_STATUS('newjs_masterRep');
                $exp_dt = $ssObj->getBillId1($profileId);
		if($exp_dt && strtotime($exp_dt)>=strtotime($start_dt) && strtotime($exp_dt)<strtotime($end_dt))
                        return $exp_dt;
                else
                        return;
        }

	public function fetchSubscriptionExpiryDate_follow($profileId){
		$ssObj = new BILLING_SERVICE_STATUS('newjs_masterRep');
		$billids = $ssObj->getBillId($profileId);

		foreach($billids as $bid=>$exp_dt){
			$status = $this->isPaymentSuccessful($profileId, $bid);
			if($status)	return $exp_dt;
		}
	}

	public function fetchRenewalProfilesCount($reporters, $expireRangeArr){
		$maObj = new incentive_MAIN_ADMIN();
		$allotedProfiles = $maObj->getAllotedProfilesForAgents($reporters);

		foreach($allotedProfiles as $agent=>$profileIds){
			$exp_dt = array();
			foreach($profileIds as $id){
				$expDate = $this->fetchSubscriptionExpiryDate1($id);
				if($expDate)
					$exp_dt[$id] = $expDate;
			}
			$renewalProfiles[$agent] = count($exp_dt);

			foreach($exp_dt as $id=>$edate){
				$edate_0 = $edate." 23:59:59";
				$edate_29 = date('Y-m-d 00:00:00', (strtotime($edate) - 29*24*60*60));

				$hObj = new incentive_HISTORY('newjs_masterRep');
				$lastHandled_dt = $hObj->getLastHandledDateForProfile($id, $agent); 

				if(!$lastHandled_dt || ($lastHandled_dt && strtotime($lastHandled_dt) <= strtotime($edate_29))){
					$renewalProfilesNotFollowedup[$agent]++;
					$diff = round(abs(strtotime($edate)-strtotime(date('Y-m-d')))/(24*60*60));
					foreach($expireRangeArr as $i=>$num){   // Range-wise expiry date of main membership
						if($diff>=$num[0] && $diff<=$num[1]){
							$renewalProfilesNotFollowedupRangeWise[$i][$agent]++;
							break;
						}
					}
				}
				unset($hObj);
			}
		}
		return array($renewalProfiles, $renewalProfilesNotFollowedup, $renewalProfilesNotFollowedupRangeWise);
	}

	public function fetchTeamWiseData($reportersOfAgent, $profilesWithoutFollowups, $renewalProfiles, $renewalProfilesNotFollowedup, $renewalProfilesNotFollowedupRangeWise){
		foreach($reportersOfAgent as $agent=>$reporters){
			if($reporters && is_array($reporters) && count($reporters)>0){
				foreach($reporters as $r){
					if($profilesWithoutFollowups[$r])
						$profilesWithoutFollowups_t[$agent] += $profilesWithoutFollowups[$r];
					if($renewalProfiles[$r])
						$renewalProfiles_t[$agent] += $renewalProfiles[$r];
					if($renewalProfilesNotFollowedup[$r])
						$renewalProfilesNotFollowedup_t[$agent] += $renewalProfilesNotFollowedup[$r];
					foreach($renewalProfilesNotFollowedupRangeWise as $i=>$val){
						if($renewalProfilesNotFollowedupRangeWise[$i][$r])
							$renewalProfilesNotFollowedupRangeWise_t[$i][$agent] += $renewalProfilesNotFollowedupRangeWise[$i][$r];
					}
				}
			}
		}
		return array($profilesWithoutFollowups_t, $renewalProfiles_t, $renewalProfilesNotFollowedup_t, $renewalProfilesNotFollowedupRangeWise_t);
	}

	public function getSubscriptionExpiry($profileArray, $fstatus=0){
		if($fstatus==1){
			foreach($profileArray as $key=>$value){
				if(is_array($value))
					$profileArray[$key]['SUBS_EXPIRY'] = $this->fetchSubscriptionExpiryDate_follow($value['PROFILEID']);
			}
		}
		else{
			foreach($profileArray as $key=>$value){
				if(is_array($value))
					$profileArray[$key]['SUBS_EXPIRY'] = $this->fetchSubscriptionExpiryDate($value['PROFILEID']);
			}
		}
		return $profileArray;
	}

	public function getPaidOnDate($profileArray, $fstatus=0){
		$start_dt = date('Y-m-d')." 00:00:00";
		$end_dt = date('Y-m-d', strtotime('+30 day'))." 00:00:00";
		foreach($profileArray as $key=>$value){
			$ssObj = new BILLING_SERVICE_STATUS('newjs_masterRep');
			$billids = $ssObj->getBillId($value['PROFILEID']);

			if($billids && is_array($billids) && count($billids)>=1){
				foreach($billids as $bid=>$exp_dt){
					if($fstatus==1){
						$pdObj = new BILLING_PAYMENT_DETAIL('newjs_masterRep');
						$profileArray[$key]['PAID_ON'] = $pdObj->getLatestPaymentDateOfProfileByAgent($value['PROFILEID'], $bid);
						if($profileArray[$key]['PAID_ON'])
							break;
						unset($pdObj);				
					}
					else{
						$edate = $exp_dt." 00:00:00";
						if(strtotime($edate)>=strtotime($start_dt) && strtotime($edate)<strtotime($end_dt)){
							$pdObj = new BILLING_PAYMENT_DETAIL();
							$profileArray[$key]['PAID_ON'] = $pdObj->getLatestPaymentDateOfProfileByAgent($value['PROFILEID'], $bid);
							if($profileArray[$key]['PAID_ON'])
								break;
							unset($pdObj);				
						}
						else break;						
					}
				}
			}
			unset($ssObj);
		}
		return $profileArray;
	}

	public function sortProfileArray(&$profileArray)
	{
		foreach($profileArray as $key=>$value){
			$subs_expiry[$key] = $value['SUBS_EXPIRY'];
			$lastHandled_dt[$key] = $value['LAST_HANDLED_DATE'];
		}
		array_multisort($subs_expiry,SORT_ASC,$lastHandled_dt,SORT_ASC,$profileArray);
	}

	public function fetchProfileDataWithoutFollowupDate($agent){
		$profileData = array();
		$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$fsObj = new FieldSalesFollowUpStatusMis();
		$profileData = $maObj->getProfilesWithoutFollowupDate($agent);
		$profileData = $fsObj->getAllProfileDataForExecutive($profileData, $agent);
		$profileData = $this->getPaidOnDate($profileData, 1);
		$profileData = $this->getSubscriptionExpiry($profileData, 1);
		$this->sortProfileArray($profileData);
		return $profileData;
	}

	public function fetchRenewalProfileIds($agent){
		$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$allotedProfiles = $maObj->getAllotedProfiles($agent);
		$renewalProfiles = array();
		foreach($allotedProfiles as $id){
			$exp_dt = $this->fetchSubscriptionExpiryDate($id);
			if($exp_dt)
				$renewalProfiles[] = $id;
		}
		return $renewalProfiles;
	}

	public function fetchRenewalProfilesWithoutFollowedupIds($agent){
		$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$allotedProfiles = $maObj->getAllotedProfiles($agent);
		$exp_dt = array();
		foreach($allotedProfiles as $id){
			$edate = $this->fetchSubscriptionExpiryDate($id);
			if($edate)
				$exp_dt[$id] = $edate;
		}

		$renewalProfilesNotFollowedup = array();
		foreach($exp_dt as $id=>$edate){
			$edate_0 = $edate." 23:59:59";
			$edate_29 = date('Y-m-d 00:00:00', (strtotime($edate) - 29*24*60*60));

			$hObj = new incentive_HISTORY('newjs_masterRep');
			$lastHandled_dt = $hObj->getLastHandledDateForProfile($id, $agent); 

			if(!$lastHandled_dt || $lastHandled_dt && strtotime($lastHandled_dt) <= strtotime($edate_29))
				$renewalProfilesNotFollowedup[] = $id;
			unset($hObj);
		}
		return $renewalProfilesNotFollowedup;
	}

	public function fetchRenewalProfilesWithoutFollowedupRangeWiseIds($agent, $expireRangeArr, $col_id){
		$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$allotedProfiles = $maObj->getAllotedProfiles($agent);
		$exp_dt = array();
		foreach($allotedProfiles as $id){
			$edate = $this->fetchSubscriptionExpiryDate($id);
			if($edate)
				$exp_dt[$id] = $edate;
		}

		$renewalProfilesNotFollowedupRangeWise = array();
		foreach($exp_dt as $id=>$edate){
			$edate_0 = $edate." 23:59:59";
			$edate_29 = date('Y-m-d 00:00:00', (strtotime($edate) - 29*24*60*60));

			$hObj = new incentive_HISTORY('newjs_masterRep');
			$lastHandled_dt = $hObj->getLastHandledDateForProfile($id, $agent); 

			if(!$lastHandled_dt || $lastHandled_dt && strtotime($lastHandled_dt) <= strtotime($edate_29)){
				$diff = round(abs(strtotime($edate)-strtotime(date('Y-m-d')))/(24*60*60)); 
				if($diff>=$expireRangeArr[$col_id-4][0] && $diff<=$expireRangeArr[$col_id-4][1])
					$renewalProfilesNotFollowedupRangeWise[] = $id;
			}
			unset($hObj);
		}
		return $renewalProfilesNotFollowedupRangeWise;
	}					

	public function fetchProfileData($agent, $expireRangeArr, $col_id){
		$profileData = array();
		$maObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$fsObj = new FieldSalesFollowUpStatusMis();
		if($col_id == 2)
			$profileIds = $this->fetchRenewalProfileIds($agent);
		else if($col_id==0 || $col_id==3){
			$profileIds = $this->fetchRenewalProfilesWithoutFollowedupIds($agent);
		}
		else 
			$profileIds = $this->fetchRenewalProfilesWithoutFollowedupRangeWiseIds($agent, $expireRangeArr, $col_id);

		if($profileIds && is_array($profileIds)){
			$profileData = $maObj->getProfilesDetails($profileIds);
			$profileData = $fsObj->getAllProfileDataForExecutive($profileData, $agent);
			$profileData = $this->getPaidOnDate($profileData);
			$profileData = $this->getSubscriptionExpiry($profileData);
			$this->sortProfileArray($profileData);
		}
		return $profileData;
	}

}
?>
