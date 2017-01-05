
<?php
//This class handles all the logics related to Field Sales Followup Status MIS

class FieldSalesFollowUpStatusMis
{

	public function __construct(){

	}

	public function generateProfileData($agent, $start_date, $end_date=""){
		$profileArray = array();
		$profileArray = $this->getFollowUpProfilesForRange($agent, $start_date, $end_date);;
		$profileArray = $this->getAllProfileDataForExecutive($profileArray, $agent);
		$profileArray = $this->sortProfileArrayByFollowUpDate($profileArray);
		return $profileArray;
	}


	// Function to get all alloted profiles for a particular agent from beginning of time
	public function getAllotedProfiles($agent){
		$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$profileArray = $mainAdminObj->getFieldSalesAllotedProfilesForAgent($agent);
		return $profileArray;
	}

	// Function to get followup profiles for date range
	public function getFollowUpProfilesForRange($agent, $start_date, $end_date=""){
		$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$profileArray = $mainAdminObj->getFollowUpProfilesForRange($agent, $start_date, $end_date);
		return $profileArray;
	}

	// Function to get Usernames of all Profiles
	public function getProfileUsernames($profileArray){
		$profileUsername = array();
		$jprofileObj = new JPROFILE('newjs_masterRep');
		foreach($profileArray as $key=>$value){
			if(is_array($value)){
				$profileArray[$key]['USERNAME'] = $jprofileObj->getUsername($value['PROFILEID']);
			}
		}
		return $profileArray;
	}

	// Filter profiles list acquired from above to select only free (non-paid) profiles
	public function getFreeProfilesFromAllotedProfiles($profileArray){
		$jprofileObj = new JPROFILE('newjs_masterRep');
		foreach($profileArray as $key=>$value){
			if(is_array($value)){
				$subscriptions = $jprofileObj->getProfileSubscription($value['PROFILEID']);
				$subscriptions = explode(",", $subscriptions);
				if(in_array('F', $subscriptions) || in_array('D', $subscriptions)){
					unset($profileArray[$key]);
				}
			}
		}
		return $profileArray;
	}

	// Get de-allocation dates for all free profiles from CRM_DAILY_ALLOT table
	public function getDeallocationDateForProfiles($profileArray){
		$crmDailyAllotObj = new CRM_DAILY_ALLOT('newjs_masterRep');
		foreach($profileArray as $key=>$value){
			if(is_array($value)){
				$deallocationDate = $crmDailyAllotObj->getDeallocationDateForProfile($value['PROFILEID'], $value['ALLOT_TIME']);
				$profileArray[$key]['DE_ALLOCATION_DT'] = $deallocationDate;
			}
		}
		return $profileArray;
	}

	// Get last handled date i.e. last processed date for all profiles from HISTORY table
	public function getLastHandledDateForProfiles($profileArray, $agent){
		$historyObj = new incentive_HISTORY('newjs_masterRep');
		foreach($profileArray as $key=>$value){
			if(is_array($value)){
				$lastHandledDate = $historyObj->getLastHandledDateForProfile($value['PROFILEID'], $agent);
				$profileArray[$key]['LAST_HANDLED_DATE'] = $lastHandledDate;
			}
		}
		return $profileArray;
	}

	// A Function to get Free profile data only, used in AllotedProfile count
	// Optimize
	public function getFreeProfileDataForExecutive($profileArray, $agent){
		$profileArray = $this->getProfileUsernames($profileArray);
		$profileArray = $this->getFreeProfilesFromAllotedProfiles($profileArray);
		$profileArray = $this->getDeallocationDateForProfiles($profileArray);
		$profileArray = $this->getLastHandledDateForProfiles($profileArray, $agent);
		return $profileArray;

	}

	// Function to get All Profile Data, including paid and non-paid
	// Optimize
	public function getAllProfileDataForExecutive($profileArray, $agent){
		$profileArray = $this->getProfileUsernames($profileArray);
		$profileArray = $this->getDeallocationDateForProfiles($profileArray);
		$profileArray = $this->getLastHandledDateForProfiles($profileArray, $agent);
		return $profileArray;
	}

	// Function to filter future followUps from i.e. from tomorrow onwards
	public function getFutureFollowUps($agent){
		$profileArray = array();
		$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
		$profileArray = $mainAdminObj->getFutureFollowUpProfileDetails($agent);
		$profileArray = $this->getProfileUsernames($profileArray);
		$profileArray = $this->getDeallocationDateForProfiles($profileArray);
		$profileArray = $this->getLastHandledDateForProfiles($profileArray, $agent);
		return $profileArray;
	}

	// Function to sort profiles by FollowUp Date, used for display purposes according to PRD
	public function sortProfileArrayByFollowUpDate($profileArray){
		$profileArray = $this->compare($profileArray, 'FOLLOWUP_TIME');
		$profileArray = $this->putEmptyFollowupAtEnd($profileArray);
		$profileArray = $this->re_sort($profileArray, 'ALLOT_TIME');
		$profileArray = array_values($profileArray);
		//print_r($profileArray);
		return $profileArray;
	}

	// Custom function to compare inner array values based on passed parameters
	public function compare($a,$subkey){
		foreach($a as $k=>$v){
			$b[$k] = $v[$subkey];
		}
		asort($b);
		foreach($b as $key=>$val){
			$c[] = $a[$key];
		}
		return $c;
	}

	// Function to put empty Follwoup Profiles at the end
	public function putEmptyFollowupAtEnd($profileArray){
		$tempArr = array();
		foreach($profileArray as $key=>$value){
			if($value['FOLLOWUP_TIME'] == '0000-00-00 00:00:00'){
				$tempArr[] = $value;
				unset($profileArray[$key]);
			}
		}
		$profileArray = array_values($profileArray);
		$tempArr = array_values($tempArr);
		$tempArr = $this->compare($tempArr, 'ALLOT_TIME');
		foreach($tempArr as $key=>$value){
			array_push($profileArray, $value);
		}
		$profileArray = array_values($profileArray);
		return $profileArray;
	}

	// 2nd level sorting
	public function re_sort($a,$subkey){
		$b = array();
		foreach($a as $k=>$v){
			if(!in_array($v[$subkey], $b)){
				$b[$k] = $v[$subkey];
			}
		}
		asort($b);
		foreach($b as $key=>$val){
			$a[$key][$subkey] = $b[$key];
		}
		return $a;
	}

	// Function to count alloted (non-paid) profiles for a set of agents(i.e $reporters)
	public function fetchAllocationBucketCount($reporters){
		foreach($reporters as $agent){
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$bucket = $mainAdminObj->getFieldSalesAllotedProfilesForAgent($agent);
			$freeProfiles = $this->getFreeProfilesFromAllotedProfiles($bucket);
			$all[$agent] =  count($bucket);
			$free[$agent] =  count($freeProfiles);
			unset($mainAdminObj);
		}
		return array($all, $free);
	}

	// Function to count today's followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchTodayFollowUpsCount($reporters){
		foreach($reporters as $agent){
			$start_dt =date("Y-m-d 00:00:00");
			$end_dt =date("Y-m-d 23:59:59");
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$cnt = $mainAdminObj->countFollowUpProfiles($agent, $start_dt, $end_dt);
			$res[$agent] =  $cnt;
		}
		return $res;
	}

	// Function to count yesterday's followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchYesterdayFollowUpsCount($reporters){
		foreach($reporters as $agent){
			$day =date('Y-m-d', strtotime('-1 day'));
			$start_dt =$day." 00:00:00";
			$end_dt =$day." 23:59:59";
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$cnt = $mainAdminObj->countFollowUpProfiles($agent, $start_dt, $end_dt);
			$res[$agent] =  $cnt;
		}
		return $res;
	}

	// Function to count "day before yesterday's" followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchDayBeforeYesterdayFollowUpsCount($reporters){
		foreach($reporters as $agent){
			$day =date('Y-m-d', strtotime('-2 day'));
			$start_dt =$day." 00:00:00";
			$end_dt =$day." 23:59:59";
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$cnt = $mainAdminObj->countFollowUpProfiles($agent, $start_dt, $end_dt);
			$res[$agent] =  $cnt;
		}
		return $res;
	}

	// Function to count "earlier than day before yesterday's" followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchEarlierThanDayBeforeYesterdayFollowUpsCount($reporters){
		foreach($reporters as $agent){
			$day =date('Y-m-d', strtotime('-2 day'));
			$start_dt =$day." 00:00:00";
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$cnt = $mainAdminObj->countFollowUpProfilesBeforeDate($agent, $start_dt);
			$res[$agent] =  $cnt;
		}
		return $res;
	}

	// Function to count total pending followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchTotalPendingFollowUpsCount($reporters, $todayFollowUps, $yesterdayFollowUps, $dayBeforeYesterdayFollowUps, $earlierThanDayBeforeYesterdayFollowUps){
		foreach($reporters as $agent)
			$res[$agent] =  $todayFollowUps[$agent]+$yesterdayFollowUps[$agent]+$dayBeforeYesterdayFollowUps[$agent]+$earlierThanDayBeforeYesterdayFollowUps[$agent];
		return $res;
	}

	// Function to count future followups (non-paid and paid both) for a set of agents(i.e $reporters)
	public function fetchFutureFollowUpsCount($reporters){
		foreach($reporters as $agent){
			$mainAdminObj = new incentive_MAIN_ADMIN('newjs_masterRep');
			$profiles = $mainAdminObj->getFutureFollowUpProfiles($agent);
			$res[$agent] =  count($profiles);
		}
		return $res;
	}

	// Function to retrieve background color for a set of agents(i.e $reporters)
	public function getBackgroundColor($reporters){
		foreach($reporters as $agent){
			$misObj = new misGenerationhandler();
			$jsObj = new jsadmin_PSWRDS('newjs_masterRep');
			$priv = $jsObj->getPrivilegeForAgent($agent);
			$color = $misObj->getRowColour($priv);
			$res[$agent] =  $color;
		}
		return $res;
	}

	// Function to get reporters(direct and indirect reporters both) of agents
	public function fetchReportersOfAgent($reporters){
		foreach($reporters as $agent){
			$hObj = new hierarchy($agent);
			$reporters = $hObj->getAllReporters();
			$res[$agent] =  $reporters;
		}
		return $res;
	}

	// Function to fetch team-wise data for a set of agents(i.e $reporters)
	public function fetchTeamWiseData($reportersOfAgent, $todayFollowUps, $yesterdayFollowUps, $dayBeforeYesterdayFollowUps, $earlierThanDayBeforeYesterdayFollowUps, $totalPendingFollowUps, $futureFollowUps){
		foreach($reportersOfAgent as $agent=>$reporters){
			if($reporters && is_array($reporters) && count($reporters)>0){
				foreach($reporters as $r){
					$todayFollowUps_t[$agent] += $todayFollowUps[$r];
					$yesterdayFollowUps_t[$agent] += $yesterdayFollowUps[$r];
					$dayBeforeYesterdayFollowUps_t[$agent] += $dayBeforeYesterdayFollowUps[$r];
					$earlierThanDayBeforeYesterdayFollowUps_t[$agent] += $earlierThanDayBeforeYesterdayFollowUps[$r];
					$totalPendingFollowUps_t[$agent] += $totalPendingFollowUps[$r];
					$futureFollowUps_t[$agent] += $futureFollowUps[$r];
				}
			}
		}
		return array($todayFollowUps_t, $yesterdayFollowUps_t, $dayBeforeYesterdayFollowUps_t, $earlierThanDayBeforeYesterdayFollowUps_t, $totalPendingFollowUps_t, $futureFollowUps_t);
	}

	// Function to check visibility in the "Sales Followup Status MIS" interface for a set of agents(i.e $reporters)
	public function visibiltyCheck($reportersOfAgent, $allocationBucket){
		foreach($reportersOfAgent as $agent=>$reporters){
			$res[$agent] = 1;
			if(!$allocationBucket[$agent]){
				$res[$agent] = 0;
				if($reporters && is_array($reporters) && count($reporters)>0){
					foreach($reporters as $r){
						if($allocationBucket[$r]){
							$res[$agent] = 1;
							break;
						}
					}
				}
			}
		}
		return $res;
	}

}


?>
