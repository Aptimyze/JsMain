<?php 


/**
* Helper class for MatchMailer
*/
class ExclusiveMatchMailer {
    
	public function getAcceptances($receivers) {
		$result = array();

		if (empty($receivers)) {
			return $result;
		}

		$shard1Profiles = array();
		$shard2Profiles = array();
		$shard3Profiles = array();

		foreach ($receivers as $key => $value) {
			if ($value%3 == 0) {
				$shard1Profiles[] = $value;
			} else if ($value%3 == 1) {
				$shard2Profiles[] = $value;
			} else {
				$shard3Profiles[] = $value;
			}
		}

        $result= array();
		$result1= array();
		$result2= array();
		if (!empty($shard1Profiles)) {
			$dbName = "shard1_slave";
			$result = $this->getAcceptancesUtil($dbName,$shard1Profiles);
			if(!is_array($result))
			    $result = array();
		} 
		unset($shard1Profiles);

		if (!empty($shard2Profiles)) {
			$dbName = "shard2_slave";
            $result1 = $this->getAcceptancesUtil($dbName,$shard2Profiles);
            if(!is_array($result1))
                $result1 = array();
		}
		unset($shard2Profiles); 

		if (!empty($shard3Profiles)) {
			$dbName = "shard3_slave";
            $result2 = $this->getAcceptancesUtil($dbName,$shard3Profiles);
            if(!is_array($result2))
                $result2 = array();
        }
		unset($shard3Profiles);
        $result4 = $result + $result1 + $result2;
		return $result4;
	}

	public function getClientAndAgentDetails() {
		$servicingObj = new billing_EXCLUSIVE_SERVICING();
		$result = $servicingObj->getProfileIDandAgentNameForMailing();
		if (count($result)) {
			$pswrdsObj = new jsadmin_PSWRDS();
			$agentDetail = $pswrdsObj->getAgentDetailsForMatchMail(array_keys($result));
			foreach ($result as $key => $value) {
				foreach ($value as $k => $v) {
					$result[$key][$k]["AGENT_EMAIL"] = $agentDetail[$key
					]["EMAIL"];
                    $result[$key][$k]["AGENT_NAME"] = $agentDetail[$key]["FIRST_NAME"];
					if (!$result[$key][$k]["AGENT_NAME"]){
					    $result[$key][$k]["AGENT_NAME"] = $key;
                    } else {
						$result[$key][$k]["AGENT_NAME"].= " ".$agentDetail[$key]["LAST_NAME"];
					}
					$result[$key][$k]["AGENT_PHONE"] = $agentDetail[$key]["PHONE"];
				}
			}
		} else {
			$result = "No client has service day tommorow";
		}
		return $result;
	}

	public function getAcceptancesForMatchMailer($dbName,$profilesId){
		$contactsObj = new newjs_CONTACTS($dbName);
                $lastWeekMailDate = date('Y-m-d h:m:s',strtotime(" -7 days"));
		$res1 = $contactsObj->getSentAcceptancesForMatchMailer($profilesId,$lastWeekMailDate);
		$flag=1;
		$result = array();
		if (is_array($res1) && !empty($res1)) {
			foreach ($res1 as $key => $value) {
				if (count($value)<20) {
					$flag=0;
				}
				foreach ($value as $k => $v) {
					$result[$key][] = $v;
				}
			}
		}	
		if($flag == 0) {
                    // This method will return the list of Acceptance list
                    $res2 = $contactsObj->getReceivedAcceptancesForMatchMailer($profilesId,$lastWeekMailDate);
                }
		if (is_array($res2) && !empty($res2)) {
			foreach ($res2 as $key => $value) {
				foreach ($value as $k => $v) {
					$result[$key][] = $v;
				}
			}
		}
		unset($contactsObj);
		return $result;
	}

	public function getMailerProfiles() {
		$exclusiveMatchMailerObj = new incentive_ExclusiveMatchMailer();
		$res = $exclusiveMatchMailerObj->getAllProfilesToSendMail();
		unset($exclusiveMatchMailerObj);
		$user = "USER";
		foreach ($res as $key => $value) {
			$str = $value["ACCEPTANCES"];
			unset($res[$key]["ACCEPTANCES"]);
			$str = explode(",", $str);
			$count = 1;
			foreach ($str as $k => $v) {
				if ($count>20) {
					break;
				}
				$res[$key][$user.$count] = $v;
				$count++;
			}
		}
		return $res;
	}

	public function getAcceptancesUtil($dbName, $shardProfiles) {
		$limit = 200;
		$count = 0;
		$length = count($shardProfiles);
		$result = array();
		while ($count <= $length) {
			$temp = array_slice($shardProfiles, $count, $count+$limit-1);
			$str_temp = implode(",", $temp);
			$res = $this->getAcceptancesForMatchMailer($dbName,$str_temp);
			if(!empty($res))
				$result = $result + $res;
			$count += $limit;
		}
		return $result;
	}

    public function logMails() {
        $exclusiveMatchMailerObj = new incentive_ExclusiveMatchMailer();
        $result = $exclusiveMatchMailerObj->getAllProfiles();
        $date = date('Y-m-d');
        $mailLogObj = new billing_EXCLUSIVE_MAIL_LOG();
        foreach ($result as $key => $value){
            $acceptances = explode(",",$value["ACCEPTANCES"]);
            if(!$acceptances[0])
                $count = 0;
            else
                $count = count($acceptances);
            $mailLogObj->insertMailLog($value["RECEIVER"],"MATCH_MAIL",$count,$date);
        }
	}
    
    public function logMatchMailProfiles($data,$profileid){
        if($data && is_array($data)){
            $exclusiveMailLogObj = new billing_EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS();
            $params["STATUS"] = "U";
            $params["ENTRY_DT"] = date('Y-m-d');
            $params["CLIENT_ID"] = $profileid;
            foreach($data as $key =>$value){
                $params["ACCEPTANCE_ID"] = $value;
                $exclusiveMailLogObj->insertForFollowup($params);
            }
        }
    }
    public function getClientAndAgentForToday() {
		$servicingObj = new billing_EXCLUSIVE_SERVICING();
		$nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
		$result = $servicingObj->getProfileIDandAgentNameForToday();
		if(is_array($result)){
			foreach ($result as $key => $value) {
                $clientIdArr[] = $value["CLIENT_ID"];
		    }
		    $clientIdStr = implode(",", $clientIdArr);
		    $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID" => $clientIdStr), "", "", "PROFILEID,NAME,DISPLAY");
		    foreach($clientNameArr as $key => $val){
		            $nameTempArr[$val["PROFILEID"]] = $val;
		    }
			if (count($result)) {
				$pswrdsObj = new jsadmin_PSWRDS();
				$followupObj = new billing_EXCLUSIVE_FOLLOWUPS();
				foreach ($result as $key => $value) {
					$agentArr[$result[$key]['AGENT_USERNAME']] = $key;
					$result[$key]['NAME'] = $nameTempArr[$key]['NAME'];
					$result[$key]["DISPLAY"] = $nameTempArr[$key]['DISPLAY'];
					$memberDetails = $followupObj -> followupHistoryForClient($result[$key]['AGENT_USERNAME'],$result[$key]['CLIENT_ID']);
					$newResult[$key] = $memberDetails; 
					$newResult[$key]["NAME"] = $nameTempArr[$key]["NAME"];
					$newResult[$key]["DISPLAY"] = $nameTempArr[$key]["DISPLAY"];
					$memberID = array_keys($memberDetails);
					if(is_array($memberID)){
						$jprofileObj = new JPROFILE("newjs_slave");
						$memberIDStr = implode(",", $memberID);
		                $memberUsername = $jprofileObj->getArray(array("PROFILEID" => $memberIDStr),"","","PROFILEID,USERNAME");
		                $memberName = $nameOfUserObj->getArray(array("PROFILEID" => $clientIdStr), "", "", "PROFILEID,NAME,DISPLAY");
		                foreach ($memberName as $key => $value) {
		                	$name[$value["PROFILEID"]]["NAME"] = $value["NAME"];
		                	$name[$value["PROFILEID"]]["DISPLAY"] = $value["DISPLAY"]; 
		                }
		                if(is_array($memberUsername)){
		                	foreach ($memberUsername as $key => $value) {
		                		$usernames[$value["PROFILEID"]] = $value["USERNAME"];
		                	}
		                }
					}
				}
				$agentDetail = $pswrdsObj->getAgentDetailsForMatchMail(array_keys($agentArr));
				foreach ($agentDetail as $key => $value) {
					$agentDetail[$key]["NAME"] = $value["FIRST_NAME"];
					if($value["LAST_NAME"])
						$agentDetail[$key]["NAME"] .= " ".$value["LAST_NAME"];
					unset($agentDetail[$key]["FIRST_NAME"]);
					unset($agentDetail[$key]["LAST_NAME"]);
				}
				foreach ($newResult as $key => $value) {
					$newResult[$key]["AGENT_DETAIL"] = $agentDetail[$result[$key]["AGENT_USERNAME"]];
					$flag=0;
					// print_r($value);die;
					foreach ($value as $k => $v) {
						if(is_numeric($k))
							$flag=1;
						if(is_array($v)){
							if($v["STATUS"] == "Y" || $v["STATUS"] == "N" || $v["STATUS"] == "F4"){
								$updateArr[] = $v["ID"];
							}
							$status = $newResult[$key][$k]["STATUS"];
							if($status == 'F1')
							    $newResult[$key][$k]["FOLLOWUP2_DT"] = null;
							if($status == 'F2')
							    $newResult[$key][$k]["FOLLOWUP3_DT"] = null;
							if($status == 'F3')
							    $newResult[$key][$k]["FOLLOWUP4_DT"] = null;
							$newResult[$key][$k]["MEMBER_USERNAME"] = $usernames[$k];
							$newResult[$key][$k]["NAME"] = $name[$k]["NAME"]; 
							$newResult[$key][$k]["DISPLAY"] = $name[$k]["DISPLAY"];

							if(!$v["FOLLOWUP1_DT"]){
								unset($newResult[$key][$k][$v]);
								continue;
							}
							if($v["FOLLOWUP_1"]){
								$reason = explode("|", $v["FOLLOWUP_1"]);
								if($reason[0] == "RNR/Switched off" || $reason[0] == "Not reachable" || $reason[0]=="RNR/Switched off/Not reachable")
									$reason[0] = "Non Contactable";
								if($reason[0]=="Others")
								    $reason[0]="Member already communicating with another profile";    	    
								$newResult[$key][$k]["FOLLOWUP_1"] = $reason[0];
							}
							if($v["FOLLOWUP_2"]){
								$reason = explode("|", $v["FOLLOWUP_2"]);
								if($reason[0] == "RNR/Switched off" || $reason[0] == "Not reachable" ||$reason[0]=="RNR/Switched off/Not reachable")
									$reason[0] = "Non Contactable";
								if($reason[0]=="Others")
								    $reason[0]="Member already communicating with another profile";
								$newResult[$key][$k]["FOLLOWUP_2"] = $reason[0];
							} 
							if (!$v["FOLLOWUP2_DT"]) {
								if($v["STATUS"] == "Y"){
									$newResult[$key][$k]["FOLLOWUP_1"] = "Confirmed";
									continue;	
								} else if ($v["STATUS"] == "N") {
									$newResult[$key][$k]["FOLLOWUP_1"] = "Declined";
									continue;
								}
							}
							
							if($v["FOLLOWUP_3"]){
								$reason = explode("|", $v["FOLLOWUP_3"]);
								if($reason[0] == "RNR/Switched off" || $reason[0] == "Not reachable"||$reason[0]=="RNR/Switched off/Not reachable")
									$reason[0] = "Non Contactable";
								if($reason[0]=="Others")
								    $reason[0]="Member already communicating with another profile";
								$newResult[$key][$k]["FOLLOWUP_3"] = $reason[0];
							}

							if (!$v["FOLLOWUP3_DT"]) {
								if($v["STATUS"] == "Y"){
									$newResult[$key][$k]["FOLLOWUP_2"] = "Confirmed";
									continue;	
								} else if ($v["STATUS"] == "N") {
									$newResult[$key][$k]["FOLLOWUP_2"] = "Declined";
									continue;
								}
							}
							
							if($v["FOLLOWUP_4"]){
								$reason = explode("|", $v["FOLLOWUP_4"]);
								if($reason[0] == "RNR/Switched off" || $reason[0] == "Not reachable"||$reason[0]=="RNR/Switched off/Not reachable")
									$reason[0] = "Non Contactable";
								if($reason[0]=="Others")
								    $reason[0]="Member already communicating with another profile";
								$newResult[$key][$k]["FOLLOWUP_4"] = $reason[0];
							}
							
							if (!$v["FOLLOWUP4_DT"]) {
								if($v["STATUS"] == "Y"){
									$newResult[$key][$k]["FOLLOWUP_3"] = "Confirmed";
									continue;	
								} else if ($v["STATUS"] == "N") {
									$newResult[$key][$k]["FOLLOWUP_3"] = "Declined";
									continue;
								}
							} else{
								if($v["STATUS"] == "Y"){
									$newResult[$key][$k]["FOLLOWUP_4"] = "Confirmed";
									continue;	
								} else if ($v["STATUS"] == "N") {
									$newResult[$key][$k]["FOLLOWUP_4"] = "Declined";
									continue;
								}
							}
						}
					}
					if($flag==0)
						unset($newResult[$key]);
				}
			}
		}
		if(is_array($updateArr)){
			$updateStr = implode(",", $updateArr);
			$followupObj->updateMailerFlag($updateStr);
		}
		return $newResult;
	}
}


?>

