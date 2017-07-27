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

		if (!empty($shard1Profiles)) {
			$dbName = "shard1_slave";
			$result = $result + $this->getAcceptancesUtil($dbName,$shard1Profiles);
		} 
		unset($shard1Profiles);

		if (!empty($shard2Profiles)) {
			$dbName = "shard2_slave";
			$result = $result + $this->getAcceptancesUtil($dbName,$shard2Profiles);
		}
		unset($shard2Profiles); 

		if (!empty($shard3Profiles)) {
			$dbName = "shard3_slave";
			$result = $result + $this->getAcceptancesUtil($dbName,$shard3Profiles); 
		}
		unset($shard3Profiles);

		return $result;
	}

	public function getClientAndAgentDetails() {
		$servicingObj = new billing_EXCLUSIVE_SERVICING();
		$result = $servicingObj->getProfileIDandAgentNameForMailing();
		if (count($result)) {
			$pswrdsObj = new jsadmin_PSWRDS();
			$str_agents = implode(",", array_keys($result));
			$agentDetail = $pswrdsObj->getAgentDetailsForMatchMail($str_agents);
			foreach ($result as $key => $value) {
				foreach ($value as $k => $v) {
					$result[$key][$k]["AGENT_EMAIL"] = $agentDetail[$key
					]["EMAIL"];
					$result[$key][$k]["AGENT_NAME"] = $agentDetail[$key]["FIRST_NAME"];
					if ($agentDetail[$key]["LAST_NAME"]) {
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
		$res = $contactsObj->getSentAcceptancesForMatchMailer($profilesId,$lastWeekMailDate);
		$result = $contactsObj->getReceivedAcceptancesForMatchMailer($profilesId,$res,$lastWeekMailDate);
		unset($contactsObj);
		return $result;
	}

	public function getMailerProfiles() {
		$exclusiveMatchMailerObj = new incentive_ExclusiveMatchMailer();
		$res = $exclusiveMatchMailerObj->getAll();
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
			$date = date('Y-m-d h:m:s');
            $mailLogObj = new billing_EXCLUSIVE_MAIL_LOG();
			$mailLogObj->insertMailLog($value["RECEIVER"],"MATCH_MAIL",$count-1,$date);
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
}


?>