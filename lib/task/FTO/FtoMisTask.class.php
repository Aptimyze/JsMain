<?php

/*
 * Author: Kumar Anand
 * This cron is used to generate MIS data for FTO
*/

class FtoMisTask extends sfBaseTask
{
	private $noOfDaysLag = 1;
	private $dayForWeeklyCron = "Monday";

 	protected function configure()
  	{
/*
		$this->addArguments(array(
		new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
		));
*/
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'FtoMis';
	    $this->briefDescription = 'generate MIS data for FTO';
	    $this->detailedDescription = <<<EOF
	This cron runs daily and generates data for FTO MIS on a daily basis.
	Call it with:

	  [php symfony cron:FtoMis] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

              	$dt=date('Y-m-d',JSstrToTime('now -'.$this->noOfDaysLag.' days'));
//		$dt = "2013-02-20";

		$this->generateDataForStateTransitionPerDay($dt);			//MIS1
		$this->generateDataForUsersMovingToFtoActivePerDay($dt);		//MIS3
		$this->generateLoginDataWeekly($dt);					//MIS9
		$this->generateAcceptanceCountOfUsersWhoseFtoExpired($dt);		//MIS8
		$this->ftoStateLogObj = new FTO_FTO_STATE_LOG('newjs_slave');
		$this->getUsersWhoBecamePaidMembers($dt);				//calls MIS2
		$this->getUsersPhonePhotoData($dt);
		$this->getUsersWhoUploadedPhoto($dt);
		$this->generateEoiCountOfUsersWhoseFtoExpired($dt);
  	}

	//MIS8 - This function generates data per day for users who go into FTO Expired State and classifies them on gender and acceptances received. 
	private function generateAcceptanceCountOfUsersWhoseFtoExpired($dt)
	{
		$fslObj = new FTO_FTO_STATE_LOG("newjs_slave");
		$expiredProfilesData = $fslObj->getAllUsersWhoseFtoExpiredOnGivenDate($dt);
		unset($fslObj);
		if($expiredProfilesData && is_array($expiredProfilesData))
		{
			foreach($expiredProfilesData as $k=>$v)
				$profileArr[] = $v["PROFILEID"];
			$crObj = new ContactsRecords;
			$acceptCount = $crObj->getAcceptanceCountForMultipleProfiles($profileArr,$date);
			unset($crObj);
			unset($profileArr);
			foreach($expiredProfilesData as $k=>$v)
			{
				if($acceptCount[$v["PROFILEID"]])
					$expiredProfilesData[$k]["ACCEPT_COUNT"] = $acceptCount[$v["PROFILEID"]];
				else
					$expiredProfilesData[$k]["ACCEPT_COUNT"] = 0;
			}
			unset($acceptCount);
			
			foreach($expiredProfilesData as $k=>$v)
			{
				if($v["GENDER"]=="M")
				{
					if($outputArray["M"] && is_array($outputArray["M"]))
					{
						if($outputArray["M"][$v["ACCEPT_COUNT"]])
							$outputArray["M"][$v["ACCEPT_COUNT"]] = $outputArray["M"][$v["ACCEPT_COUNT"]]+1;
						else
							$outputArray["M"][$v["ACCEPT_COUNT"]]=1;
					}
					else
						$outputArray["M"][$v["ACCEPT_COUNT"]] = 1;
				}
				elseif($v["GENDER"]=="F")
				{
					if($outputArray["F"] && is_array($outputArray["F"]))
                                        {
                                                if($outputArray["F"][$v["ACCEPT_COUNT"]])
                                                        $outputArray["F"][$v["ACCEPT_COUNT"]] = $outputArray["F"][$v["ACCEPT_COUNT"]]+1;
                                                else
                                                        $outputArray["F"][$v["ACCEPT_COUNT"]]=1;
                                        }
                                        else
                                                $outputArray["F"][$v["ACCEPT_COUNT"]] = 1;
				}
			}
			unset($expiredProfilesData);

			$fmfeucaObj = new FTO_MIS_FTO_EXPIRED_USER_COUNT_ACCEPTANCE;
			$fmfeucaObj->insertRecord($dt,$outputArray);
			unset($fmfeucaObj);
			unset($outputArray);
		}
	}

	//MIS9 - This function generates weekly login data of users and categorizes them on the basis of gender,community and fto state. 
	private function generateLoginDataWeekly($dt)
	{
		$day = date("l",JSstrToTime($dt));
		if($day == $this->dayForWeeklyCron)
		{
			$start_dt = date('Y-m-d',JSstrToTime($dt.' -1 days'));
			$end_dt = date('Y-m-d',JSstrToTime($dt.' -7 days'));
			$fcsObj = new FTO_FTO_CURRENT_STATE("newjs_slave");
			$loginData = $fcsObj->getLoginWeeklyData($start_dt,$end_dt);
			unset($fcsObj);
			if($loginData && is_array($loginData))
			{
				$fmlwlObj = new FTO_MIS_LAST_WEEK_LOGIN;
				$fmlwlObj->insertRecord($dt,$loginData);
				unset($fmlwlObj);
			}
			unset($loginData);
		}
	}

	//MIS3 - This function generates the data of users moving to FTO Active state on a given date as well as the days taken by them from ENTRY_DT
	private function generateDataForUsersMovingToFtoActivePerDay($dt)
	{
		$fslObj = new FTO_FTO_STATE_LOG("newjs_slave");
		$activeData = $fslObj->getAllFtoActivePerDayAlongWithDaysTaken($dt);
		if($activeData && is_array($activeData))
		{
			$fmmtfaObj = new FTO_MIS_MOVE_TO_FTO_ACTIVE;
			$fmmtfaObj->insertRecord($dt,$activeData);
			unset($fmmtfaObj);
		}
		unset($activeData);
		unset($fslObj);
	}

	//MIS2 - This function generates the data to show the number of users present in each state on a given date
	private function generateDataForUsersInEachStatePerDay($dt,$paidUsersStateArr="")
	{
		$fcsObj = new FTO_FTO_CURRENT_STATE("newjs_slave");
		$stateData = $fcsObj->getUsersInEachState();
		if($paidUsersStateArr && is_array($paidUsersStateArr))
		{
			if($stateData && is_array($stateData))
			{
				foreach($paidUsersStateArr as $k=>$v)
				{
					if(array_key_exists($k,$stateData))
						$stateData[$k]["PAID_COUNT"] = $v;
					else
					{
						$stateData[$k]["STATE_ID"] = $k;
						$stateData[$k]["C"] = 0;
						$stateData[$k]["PAID_COUNT"] = $v;
					}
				}
			}
			else
			{
				foreach($paidUsersStateArr as $k=>$v)
				{
					$stateData[$k]["STATE_ID"] = $k;
					$stateData[$k]["C"] = 0;
					$stateData[$k]["PAID_COUNT"] = $v;
				}
			}
		}

		if($stateData && is_array($stateData))
		{
			$fmstpdObj = new FTO_MIS_USERS_IN_EACH_STATE;
			$fmstpdObj->insertRecord($dt,$stateData);
			unset($fmstpdObj);
		}
		unset($stateData);
		unset($fcsObj);
	}

	//This function gets a list of users who become paid members by state and be no of weeks b/w fto expiry and payment date
	private function getUsersWhoBecamePaidMembers($dt)
	{
		$paidUsers = $this->ftoStateLogObj->getUsersWhoPaid($dt);

		unset($paidUsersStateArr);
		unset($ftoExpiredPaidUsers);

		if(is_array($paidUsers))
		{
			foreach($paidUsers as $userData)
			{
				unset($stateArr);

				$stateArr = explode(",",$userData['STATES']);
				$paidUsersStateArr[$stateArr[0]]++;

				if(in_array($stateArr[0],array(8,9,10,11,12)))
				{
					$lastStateDate = $userData['LAST_STATE_DATE'];
					$secondLastStateDate = $userData['SECOND_LAST_STATE_DATE'];
					$noOfWeeks = ($lastStateDate - $secondLastStateDate)/7;
					$ftoExpiredPaidUsers[$noOfWeeks]++;
				}
			}

			$ftoExpiredPaidUsersObj =  new FTO_MIS_EXPIRED_USERS_WHO_PAID();
			if(is_array($ftoExpiredPaidUsers))
			$ftoExpiredPaidUsersObj->insertRecords($dt,$ftoExpiredPaidUsers);
			$this->generateDataForUsersInEachStatePerDay($dt,$paidUsersStateArr);
		}
	}

	//MIS1 - This function generates the data to show the state transitions happening on a given date
	private function generateDataForStateTransitionPerDay($dt)
	{
		$fslObj = new FTO_FTO_STATE_LOG("newjs_slave");
		$transitionData = $fslObj->getAllStateTransitionsPerDayPerProfile($dt);

		if($transitionData && is_array($transitionData))
		{
			foreach($transitionData as $k=>$v)
			{
				if($v["PROFILEID"])
					$idArr[] = $v["PROFILEID"];
				
				if(strstr($v["STATES"],","))
				{
					$tempArr = explode(",",$v["STATES"]);
					foreach($tempArr as $kk=>$vv)
					{
						if($kk==count($tempArr)-1)
						{
							break;
						}
						else
						{
							$outputArr = $this->generateOutputArray($vv,$tempArr[$kk+1],$outputArr);
						}
					}
					unset($tempArr);
				}
			}
		}

		$prevStateData = $fslObj->getLastStateOfProfileBeforeGivenDate($dt,$idArr);

		if($prevStateData && is_array($prevStateData))
		{
			foreach($prevStateData as $k=>$v)
			{
				if($transitionData[$k]["STATES"])
				{
					$tempArr = explode(",",$transitionData[$k]["STATES"]);
					$outputArr = $this->generateOutputArray($v,$tempArr[0],$outputArr);
				}
			}
		}
		unset($prevStateData);
		unset($transitionData);
		unset($fslObj);

		if($outputArr && is_array($outputArr))
		{
			$i=0;
			foreach($outputArr as $k=>$v)
			{
				$temp = explode("-",$k);
				$dataArr[$i]["COUNT"] = $v;
				$dataArr[$i]["OLD_STATE"] = $temp[0];
				$dataArr[$i]["NEW_STATE"] = $temp[1];
				$i++;
				unset($temp);
			}
			$fmstpdObj = new FTO_MIS_STATE_TRANSITION_PER_DAY;
			$fmstpdObj->insertRecord($dt,$dataArr);
			unset($fmstpdObj);	
			unset($outputArr);
			unset($dataArr);
		}
	}

	//This function is called by MIS1
	private function generateOutputArray($value1,$value2,$outputArr='')
	{
		if($value1!=$value2 && $value1 && $value2)
		{
			$index = $value1."-".$value2;
			if($outputArr && is_array($outputArr))
			{
				if(array_key_exists($index,$outputArr))
					$outputArr[$index] = $outputArr[$index]+1;
				else
					$outputArr[$index] = 1;
			}
			else
			{
				$outputArr[$index] = 1;
			}
		}
		return $outputArr;
	}
	private function getUsersWhoUploadedPhoto($dt)
	{
		unset($misArray);
		$photoUploadUsers = $this->ftoStateLogObj->getFtoUsersWhoUploadedPhoto($dt);
		if(is_array($photoUploadUsers))
		{
			foreach($photoUploadUsers as $userData)
			{
				$noOfDays = $userData['DAYS'];
				$misArray[$noOfDays]++;
			}

			$misPhotoObj = new FTO_MIS_FIRST_PHOTO_UPLOAD();
			$misPhotoObj->insertRecords($dt,$misArray);
		}
	}


	private function getUsersPhonePhotoData($dt)
	{
		$totalUsers = 0;
		$phoneVerifiedUsers = 0;
		$photoVerifiedUsers = 0;

		$genderArr = array("MALE","FEMALE");

		foreach($genderArr as $gender)
		{
			$tableName = "NEWJS_SEARCH_".$gender;
			$searchObj = new $tableName("newjs_slave");
			$totalResults = $searchObj->getArray("1","","","COUNT(*) AS COUNT");
			$noOfUsersWithVerifiedPhone = $searchObj->getArray(array("CHECK_PHONE"=>"'V'"),"","","COUNT(*) AS COUNT");
			$noOfUsersWithApprovedPhoto = $searchObj->getArray(array("HAVEPHOTO"=>"'Y'"),"","","COUNT(*) AS COUNT");

			$totalUsers += $totalResults[0]['COUNT'];
			$phoneVerifiedUsers += $noOfUsersWithVerifiedPhone[0]['COUNT'];
			$photoVerifiedUsers += $noOfUsersWithApprovedPhoto[0]['COUNT'];
		}

		$percentUsersWithApprovedPhoto = ($photoVerifiedUsers / $totalUsers) * 100;
		$percentUsersWithVerifiedPhone = ($phoneVerifiedUsers / $totalUsers) * 100;

		$phoneVerificationObj = new PHONE_VERIFIED_LOG("newjs_slave");
		$noOfProfilesWithVerifiedPhone = $phoneVerificationObj->getProfilesVerifiedOnADate($dt);

		$photoPhoneMisObj = new FTO_MIS_USERS_PHONE_PHOTO_DATA();
		$photoPhoneMisObj->updateSearchableDbCount($dt,$percentUsersWithApprovedPhoto,$percentUsersWithVerifiedPhone,$noOfProfilesWithVerifiedPhone);

	}
	
	//MIS7 
	private function generateEoiCountOfUsersWhoseFtoExpired($dt)
	{
		$fslObj = new FTO_FTO_STATE_LOG("newjs_slave");
		$expiredProfilesData = $fslObj->getAllUsersWhoseFtoExpiredOnGivenDate($dt);
		unset($fslObj);
		if($expiredProfilesData && is_array($expiredProfilesData))
		{
			foreach($expiredProfilesData as $k=>$v)
				$profileArr[] = $v["PROFILEID"];
			$crObj = new ContactsRecords;
			$eoiCount = $crObj->getEoiCountForMultipleProfiles($profileArr);
			unset($crObj);
			unset($profileArr);
			foreach($expiredProfilesData as $k=>$v)
			{
				if($eoiCount[$v["PROFILEID"]])
					$expiredProfilesData[$k]["EOI_COUNT"] = $eoiCount[$v["PROFILEID"]];
				else
					$expiredProfilesData[$k]["EOI_COUNT"] = 0;
			}
			unset($eoiCount);
			foreach($expiredProfilesData as $k=>$v)
			{			
				if($v["GENDER"]=="M")
				{
					if($v['EOI_COUNT']==0)
					{
						if($outputArray["M"] && is_array($outputArray["M"]))
						{
							if($outputArray["M"]["0"])
								$outputArray["M"]["0"] +=1;
							else
								$outputArray["M"]["0"]=1;
						}
						else
							$outputArray["M"]["0"] = 1;
					}
					elseif($v['EOI_COUNT']<=5)
					{
						if($outputArray["M"] && is_array($outputArray["M"]))
						{
							if($outputArray["M"]["1-5"])
								$outputArray["M"]["1-5"] +=1;
							else
								$outputArray["M"]["1-5"]=1;
						}
						else
							$outputArray["M"]["1-5"] = 1;
					}
					elseif($v['EOI_COUNT']<=15)
					{
						if($outputArray["M"] && is_array($outputArray["M"]))
						{
							if($outputArray["M"]["6-15"])
								$outputArray["M"]["6-15"] +=1;
							else
								$outputArray["M"]["6-15"]=1;
						}
						else
							$outputArray["M"]["6-15"] = 1;
					}
					elseif($v['EOI_COUNT']<=30)
					{
						if($outputArray["M"] && is_array($outputArray["M"]))
						{
							if($outputArray["M"]["16-30"])
								$outputArray["M"]["16-30"] +=1;
							else
								$outputArray["M"]["16-30"]=1;
						}
						else
							$outputArray["M"]["16-30"] = 1;
					}
					elseif($v['EOI_COUNT']>30)
					{
						if($outputArray["M"] && is_array($outputArray["M"]))
						{
							if($outputArray["M"]["30 and More"])
								$outputArray["M"]["30 and More"] +=1;
							else
								$outputArray["M"]["30 and More"]=1;
						}
						else
							$outputArray["M"]["30 and More"] = 1;
					}	
				}
				elseif($v["GENDER"]=="F")
				{
					if($v['EOI_COUNT']==0)
					{
						if($outputArray["F"] && is_array($outputArray["F"]))
						{
							if($outputArray["F"]["0"])
								$outputArray["F"]["0"] +=1;
							else
								$outputArray["F"]["0"]=1;
						}
						else
							$outputArray["F"]["0"] = 1;
					}
					elseif($v['EOI_COUNT']<=5)
					{
						if($outputArray["F"] && is_array($outputArray["F"]))
						{	
							if($outputArray["F"]["1-5"])
								$outputArray["F"]["1-5"] +=1;
							else
								$outputArray["F"]["1-5"]=1;
						}
						else
							$outputArray["F"]["1-5"] = 1;
					}
					elseif($v['EOI_COUNT']<=15)
					{
						if($outputArray["F"] && is_array($outputArray["F"]))
						{
							if($outputArray["F"]["6-15"])
								$outputArray["F"]["6-15"] +=1;
							else
								$outputArray["F"]["6-15"]=1;
						}
						else
							$outputArray["F"]["6-15"] = 1;
					}
					elseif($v['EOI_COUNT']<=30)
					{
						if($outputArray["F"] && is_array($outputArray["F"]))
						{
							if($outputArray["F"]["16-30"])
								$outputArray["F"]["16-30"] +=1;
							else
								$outputArray["F"]["16-30"]=1;
						}
						else
							$outputArray["F"]["16-30"] = 1;
					}
					elseif($v['EOI_COUNT']>30)
					{
						if($outputArray["F"] && is_array($outputArray["M"]))
						{
							if($outputArray["F"]["30 and More"])
								$outputArray["F"]["30 and More"] +=1;
							else
								$outputArray["F"]["30 and More"]=1;
						}
						else
							$outputArray["F"]["30 and More"] = 1;
					}	
				}
		}
	}
	unset($expiredProfilesData);

	if(is_array($outputArray))
	{
		$dbObj = new FTO_MIS_FTO_USERS_EOI_COUNT();
		$dbObj->insertRecord($dt,$outputArray);
	}
	unset($dbObj);
	unset($outputArray);
}
}
