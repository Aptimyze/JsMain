<?php
class AutomationDataGenerator
{
        public function __construct($contactStatus,$loggedInDetails,$otherDetails,$folder='',$hardFilter='',$newlyRegistered='')
	{
		$this->contactStatus = $contactStatus;
		$this->loggedInDetails = $loggedInDetails;
		$this->otherDetails = $otherDetails;
		$this->folder = $folder;
		$this->hardFilter = $hardFilter;
		$this->newlyRegistered = $newlyRegistered;
		$this->error = false;
		$this->errorMessage=NULL;
		$this->shard = $this->shardRand();
        }

	public function shardRand()
	{
		return $shard = rand(0,2);
	}

	public function getContactsContactedData($loggedIn,$loggedInProfiles)
	{
                $dbName = JsDbSharding::getShardNo($this->shard,'');
		$contactsObj = new newjs_CONTACTS($dbName);
		$contactsData = $contactsObj->getContactedAutomationData($loggedIn,$this->contactStatus,$noOfLoggedInProfiles = 5,$noOfOtherProfiles=1,$loggedInProfiles);
		return $contactsData;
	}
	public function getContactedProfiles()
	{
		$senderReceiverData = TestingLib::getSenderReceiver($this->contactStatus,$this->folder);
		$loggedIn = $senderReceiverData['loggedIn'];
		$other = $senderReceiverData['other'];
		do
		{
			$jprofileDataLoggedInObj = new JprofileData($this->loggedInDetails,$this->shard,'',$this->newlyRegistered);
			$LOGGEDIN = $jprofileDataLoggedInObj->getJprofileData();
			$loggedInProfilesStr = implode(",",array_keys($LOGGEDIN));
			unset($contactsData);
			$contactsData = $this->getContactsContactedData($loggedIn,$loggedInProfilesStr);
			foreach($contactsData as $k=>$v)
				$otherProfiles[] = $v[$other];
			if($otherProfiles)
			{
				$otherProfilesStr = implode(",",$otherProfiles);
				$jprofileDataOtherObj = new JprofileData($this->otherDetails,$this->shard,$otherProfilesStr);
				$OTHERS = $jprofileDataOtherObj->getJprofileData();
			}
		}while(!$OTHERS || count($OTHERS)<=0);
		foreach($contactsData as $k=>$v)
		{
			if($LOGGEDIN[$v[$loggedIn]] && $OTHERS[$v[$other]])
			{
				$final["LOGGED_IN"]=$LOGGEDIN[$v[$loggedIn]];
				$final["OTHER"]=$OTHERS[$v[$other]];
				break;
			}
		}
		return $final;
	}

	public function getContactsNoContactData($noOfOtherProfiles,$loggedIn,$others)
	{
        $dbName = JsDbSharding::getShardNo($this->shard,'');
		$contactsObj = new newjs_CONTACTS($dbName);
		$contactsData = $contactsObj->getNoContactAutomationData($noOfOtherProfiles,$loggedIn,$others);
		return $contactsData;
	}
	public function getNoContactProfiles()
	{
		$senderReceiver = "RECEIVER";
		$other = "SENDER";
		$senderDetails = $this->otherDetails;
		$receiverDetails = $this->loggedInDetails;
		if($this->hardFilter == "Y")
			$this->loggedInDetails['RELIGION']="1";
		$jprofileDataLoggedInObj = new JprofileData($this->loggedInDetails,$this->shard,'',$this->newlyRegistered);
		$LOGGEDIN = $jprofileDataLoggedInObj->getJprofileData();
		$loggedInProfilesStr = implode(",",array_keys($LOGGEDIN));
		do
		{
			if(($this->folder=="MATCHALERT"||$this->folder=="KUNDLI_MATCHES"||$this->folder=="VISITOR")&&$this->hardFilter!='Y')
			{
				$dt  =  date("Y-m-d",mktime(date("H") - 300, date("i"), date("s"), date("m"), date("d"), date("Y")));
				$paramArr["LENTRY_DT"] = $dt."T00:00:00Z";
                                $paramArr["HENTRY_DT"] = date("Y-m-d")."T".date("H:i:s")."Z";
				foreach($LOGGEDIN as $k=>$v)
				{
					$loggedInProfileObj = Profile::getInstance('newjs_master',$k);
					$dppMatchDetails[$k] = SearchCommonFunctions::getMyDppMatches('',$loggedInProfileObj,'','','');
					$otherProfilesStr = implode(",",$dppMatchDetails[$k][PIDS]);
					$jprofileDataOtherObj = new JprofileData($this->otherDetails,$this->shard,$otherProfilesStr);
					$OTHERS = $jprofileDataOtherObj->getJprofileData();
					if(is_array($OTHERS) &&count($OTHERS)>0)
						break;
				}
			}
			else
			{

				if($this->hardFilter=='Y')
					$this->otherDetails['RELIGION'] = "2";
				$jprofileDataOtherObj = new JprofileData($this->otherDetails,$this->shard,$otherProfilesStr);
				$OTHERS = $jprofileDataOtherObj->getJprofileData();
			}
		}while(!is_array($OTHERS) && count($OTHERS)<=0);
		foreach($LOGGEDIN as $k=>$v)
		{
			$noContactRecord = $this->getContactsNoContactData($noOfOtherProfiles=5,$k,$OTHERS);
			foreach($noContactRecord as $x=>$y)
			{
				$noContactData["LOGGED_IN"]=$LOGGEDIN[$x];
				$noContactData["OTHER"]=$y;
			}
		}
		return $noContactData;
	}
	public function getTestingData()
	{
		if($this->preCheck())
		{
			do
			{
				if($this->contactStatus=="N")
					$return = $this->getNoContactProfiles();
				else

					$return = $this->getContactedProfiles();
			}while(!is_array($return));
			$done = $this->postProcess($return);
			if($done['ERROR'])
				return $done;
			return $return;
		}
		return $this->errorMessage;
	}
	public function preCheck()
	{
		if($this->folder)
			if(!$this->verifyParamCombination())
			{
				$this->error = true;
				$this->errorMessage = "Wrong folder error";
				return false;
			}
		return true;
	}
	public function postProcess($return)
	{
		if($this->contactStatus=="I")
			TestingDataCreation::updateContactDate($return['LOGGED_IN']['PROFILEID'],$return['OTHER']['PROFILEID'],$this->folder);
		if($this->folder)
			$done = TestingDataCreation::createFolderData($return['LOGGED_IN']['PROFILEID'],$return['OTHER']['PROFILEID'],$this->folder);
		if($this->hardFilter=='Y')
			TestingDataCreation::addHardFilter($return['LOGGED_IN']['PROFILEID'],$return['OTHER']['PROFILEID']);
		return $done;
	}
	public function verifyParamCombination()
	{
		if(!in_array($this->folder,array("SHORTLIST","PHOTO_REQ_RECEIVED","PHOTO_REQ_SENT","MATCHALERT","IGNORED","VISITOR","KUNDLI_MATCHES","FILTERED","VIEWED_MY_DETAILS","AWAITING_RESPONSE","I_ACCEPTED","I_DECLINED","YET_TO_RESPOND","ACCEPTED_ME","NOT_INTERESTED_IN_ME")))
			return false;
		if($this->folder=="FILTERED"&&$this->contactStatus!="I")
			return false;
		if(in_array($this->folder,array("SHORTLIST","PHOTO_REQ_RECEIVED","PHOTO_REQ_SENT","MATCHALERT","IGNORED","VISITOR","KUNDLI_MATCHES")) && $this->contactStatus!="N")
			return false;
		return true;
	}
}
