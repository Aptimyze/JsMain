<?php
/**
 * @brief This class is used to handle all contacts functionalities of users
 * @author Prinka Wadhwa
 * @created 2012-08-16
 */

class ContactsRecords
{
	private $contactTypeC = "C";
	private $contactTypeD = "D";

	/**
	 *
	 *  @param - $senders - users for whom it is to be checked if the contact is made
	 *  @param - $receiver - user who has been contacted
	 *  @param - $contactStatus - status of the contact
	 *  @param - $key - if key=1, profileids are returned in the key of the array, else they are returned in the value of the array
	 *  @return - array of users who have contacted the $receiver
	 **/
	public function ifContactSent($senders, $receiver, $contactStatus, $key)
	{
		$dbName = JsDbSharding::getShardNo($receiver);
		$contactsObj = new newjs_CONTACTS($dbName);
		$contactsReceived = $contactsObj->getIfContactSent($senders, $receiver, $contactStatus, $key);
		return $contactsReceived;
	}

	public function getContactsSent($senders, $receivers,$viewerObj='')
	{
		if($viewerObj == '')
			$viewerObj = LoggedInProfile::getInstance("newjs_master",'');
		$viewer = $viewerObj->getPROFILEID();
		$results = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$results = true;
			$url = JsConstants::$contactUrl . "/v1/contacts";
			$url = $url . "/getProfilelistContact/" . $viewer;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["getProfilelistContact"];


			$idStr = str_replace("'", "", $senders);
			$idArr = explode(",", $idStr);
			foreach ($idArr as $k => $v)
				$idSqlArr[] = ":v$k";

			$ridStr = str_replace("'", "", $receivers);
			$ridArr = explode(",", $ridStr);
			foreach ($ridArr as $k => $v)
				$ridSqlArr[] = ":u$k";

			$profilelist['PROFILE'] = array_values(array_unique(array_merge($idArr, $ridArr)));

			$query = http_build_query($profilelist);
			$url = $url . "?" . $query;
			$url = $url . "&fields=SENDER,RECEIVER,TYPE";
			$results = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
		}
		if($results === false) {
			$dbName = JsDbSharding::getShardNo($viewer);
			$contactsObj = new newjs_CONTACTS($dbName);
			$results = $contactsObj->getContactsSent($senders, $receivers, $viewerObj->getPROFILEID());
		}
		if(is_array($results))
		{
			foreach($results as $detail)
			{
				if(($detail['RECEIVER'] == $viewer))
				{
					if($detail['TYPE'] == 'I')
						$viewerAwaitingContacts[$detail['SENDER']]=1;
					else
						$viewerContacts[$detail['SENDER']]=1;
					$overAllContacts[$detail['SENDER']]=$detail['TYPE'];
				}
				elseif($detail['SENDER'] == $viewer)
				{
					$viewerSentContacts[$detail['RECEIVER']]=$detail['TYPE'];
					$overAllContacts[$detail['RECEIVER']]='R'.$detail['TYPE'];
				}
			}
			$contactsReceived['viewerAwaitingContacts']=$viewerAwaitingContacts;
			$contactsReceived['viewerSentContacts']=$viewerSentContacts;
			$contactsReceived['viewerContacts']=$viewerContacts;
			$contactsReceived['allContacts']=$overAllContacts;
			return $contactsReceived;
		}
	}

	public function getTempContactsSent($sender, $receivers)
	{
		$viewerObj = LoggedInProfile::getInstance("newjs_master",'');
		$viewer = $viewerObj->getPROFILEID();
		$contactsTempObj = new newjs_CONTACTS_TEMP();
		$results = $contactsTempObj->getTempContacts($sender, $receivers);
		if(is_array($results))
			foreach($results as $receiverVal)
			{
				$rec[$receiverVal['RECEIVER']]=1;
			}
		return $rec;
	}

	public function getContactsList($profileid,$seperator='',$noAwaitingContacts='')
	{
		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts";
			$url = $url . "/viewer/" . $profileid;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["viewer"];
			if ($noAwaitingContacts) {
				$contacts = NULL;
				$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
				if (is_array($result)) {
					if ($seperator == "spaceSeperator")
						$contacts = implode(" ", $result);
					else
						$contacts = implode(",", $result);
				}
			} else {
				$contacts = NULL;
				$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
				if (is_array($result)) {
					if ($seperator == "spaceSeperator")
						$contacts = implode(" ", $result);
					else
						$contacts = implode(",", $result);
				}
			}
		}
		if($result === false) {
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactsObj = new newjs_CONTACTS($dbName);
			if ($noAwaitingContacts)
				$contacts = $contactsObj->getContactsRemovedFromSearchIncludingAwaitingContacts($profileid, $seperator);
			else
				$contacts = $contactsObj->getContactsRemovedFromSearch($profileid, $seperator);
		}
		return $contacts;
	}

	/*
	This function is called by photo request cron. It checks if contact type between 2 profiles is C/D
	@params dbname, profileid1, array of profileid's
	@output - true if contact type is C/D else false
	*/
	public function checkIfContactTypeIsCorD($dbName,$profileid1,$profileid2Array)
	{
		if(!$dbName || !$profileid1 || !$profileid2Array)
			throw new jsException("","DBNAME OR PROFILEID1 OR PROFILEID2ARRAY IS BLANK IN checkIfContactTypeIsCorD() of Contacts.class.php");

		try
		{
			$ncObj = new newjs_CONTACTS($dbName);
			$parameter_pass = $profileid1.",".implode(",",$profileid2Array);
			$contactInfo = $ncObj->getContactsSent($parameter_pass,$parameter_pass);
			unset($ncObj);
			if($contactInfo && is_array($contactInfo))
			{
				foreach($contactInfo as $v)
				{
					if($v["SENDER"]==$profileid1 || $v["RECEIVER"]==$profileid1)
					{
						if(in_array($v['TYPE'],array($this->contactTypeC,$this->contactTypeD)))
						{
							if($v["SENDER"]==$profileid1)
								$output[]=$v["RECEIVER"];
							else
								$output[]=$v["SENDER"];
						}
					}
				}
			}
			unset($contactInfo);
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	/*
	* This function is used to send queries to Contact table in shards and get the count of acceptance received for multiple senders
	* @param - sender array, date(optional)
	* @return - array with index as sender and value as the number of acceptances received.
	*/
	public function getAcceptanceCountForMultipleProfiles($senderArr,$dt="")
	{
		if(!$senderArr)
			throw new jsException("","SENDER ARRAY IS BLANK IN getAcceptanceCountForMultipleProfiles() of Contacts.class.php");
		$dbNameArr = JsDbSharding::getShardNumberForMultipleProfiles($senderArr,1);
		foreach($dbNameArr as $k=>$v)
		{
			$ncObj = new newjs_CONTACTS($k);
			foreach($v as $kk=>$vv)
				$profileArr[]=$kk;
			$output = $ncObj->getAcceptanceCountForMultipleProfiles($profileArr,$dt);
			unset($ncObj);
			unset($profileArr);
			if($output && is_array($output))
			{
				foreach($output as $kk=>$vv)
					$acceptCount[$vv["SENDER"]] = $vv["C"];
			}
			unset($output);
		}
		unset($dbNameArr);
		return $acceptCount;
	}

	public function getEoiCountForMultipleProfiles($senderArr)
	{
		if(!$senderArr)
			throw new jsException("","SENDER ARRAY IS BLANK IN getEoiCountForMultipleProfiles() of Contacts.class.php");
		$dbNameArr = JsDbSharding::getShardNumberForMultipleProfiles($senderArr,1);
		foreach($dbNameArr as $k=>$v)
		{
			$ncObj = new newjs_CONTACTS($k);
			foreach($v as $kk=>$vv)
				$profileArr[]=$kk;
			$output = $ncObj->getEoiCountForMultipleProfiles($profileArr);
			unset($ncObj);
			unset($profileArr);
			if($output && is_array($output))
			{
				foreach($output as $kk=>$vv)
					$eoiCount[$vv["SENDER"]] = $vv["C"];
			}
			unset($output);
		}
		unset($dbNameArr);
		return $eoiCount;
	}

	/*
	* This function is used to get the contacts count from given condition
	* @param - while condition, group by condition
	* @return - array of count with given condition
	*/
	public function getContactsCount( $where, $group='',$time='',$skipProfile='',$isProfileMemCacheService = '')
	{
		if(!$where["RECEIVER"]&&!$where["SENDER"])
		{
			throw new jsException("","No Sender or reciever is specified in funcion getContactsCount OF Contacts.class.php");
		}
		else
		{
			if($where["RECEIVER"])
				$profileid = $where["RECEIVER"];
			else
				$profileid = $where["SENDER"];
		}

		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts/getcontactscount";
			$url = $url . "/" . $profileid;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["getcontactscount"];
			$params['CONDITION'] = json_encode($where);
			$params['SKIPARRAY'] = json_encode($skipProfile);
			$params['GROUP'] = json_encode($group);
			$params['TIME'] = json_encode($time);
			$result = CommonUtility::webServiceRequestHandler($url, $params, "POST",$timeout);
			if (is_array($result)) {
				foreach ($result as $value) {
					$contactsCount[] = $value;
				}
			}
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactsObj = new newjs_CONTACTS($dbName);
			$contactsCount = $contactsObj->getContactsCount($where,$group,$time,$skipProfile);
		}

		$newArrayTime2 = array();
		if ( is_array($contactsCount))
		{
			foreach ($contactsCount as $key => $value) {
				if ( $value['TIME1'] == 2 )
				{
					$newArray = array('FILTERED' => $value['FILTERED'], 'TYPE' => $value['TYPE'],'SEEN'=>$value['SEEN'],'COUNT'=>$value['COUNT'] );
					array_push($newArrayTime2, $newArray);
				}

			}
		}

		$finalArray = array();
		foreach ($newArrayTime2 as $keyTime2 => $valueTime2) {
			$isPresent = 0;
			if ( is_array($contactsCount))
			{
				foreach ($contactsCount as $key => $value) {
				if ( $value['TIME1'] == 0 )
				{
					if ( $valueTime2['FILTERED'] == $value['FILTERED'] && $valueTime2['TYPE'] == $value['TYPE'] && $valueTime2['SEEN'] == $value['SEEN'])
					{
						$contactsCount[$key]['COUNT'] += $valueTime2['COUNT'];
						$isPresent = 1;
						break;
					}
				}
			}

			}
			if ( !$isPresent )
			{
				array_push($contactsCount,array('FILTERED' => $valueTime2['FILTERED'], 'TYPE' => $valueTime2['TYPE'],'SEEN'=>$valueTime2['SEEN'],'COUNT'=>$valueTime2['COUNT'] ));
			}
			
		}
	
		if ( $isProfileMemCacheService == '')
		{
			if ( is_array($contactsCount))
			{
				foreach ($contactsCount as $key => $value) {
					if ( $value['TIME1'] == 2 )
					{
						unset($contactsCount[$key]);
					}
				}
			}	
		}
		return $contactsCount;

	}

	public function getContactedProfiles($profileId, $senderReceiver, $type='',$count='')
	{
		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts";
			$url = $url . "/contactedProfile/" . $profileId;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["contactedProfile"];
			$params['SENDER_RECEIVER'] = $senderReceiver;
			if ($count)
				$params['COUNT'] = $count;
			$params['TYPE'] = $type;
			$query = http_build_query($params);
			$url = $url . "?" . $query;
			$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
			$contactedProfile = array();
			if (is_array($result)) {
				foreach ($result as $value) {
					$contactedProfile[$value["TYPE"]][] = $value["PROFILEID"];
				}
			}
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileId);
			$contactsObj = new newjs_CONTACTS($dbName);
			$contactedProfile = $contactsObj->getContactedProfiles($profileId,$senderReceiver,$type,$count);
		}


		return $contactedProfile;
	}


	public function getSkipContactedProfile($profileId,$skipContactType)
	{
		$senderProfile = $this->getContactedProfiles($profileId,"SENDER",$skipContactType);
		$receiverProfile = $this->getContactedProfiles($profileId,"RECEIVER",$skipContactType);
		$skipProfile["CONTACTED_BY_ME"]=$senderProfile;
		$skipProfile["CONTACTED_ME"] = $receiverProfile;
		return $skipProfile;
	}
	public function getContactedProfileArray($profileid,$condition,$skipArray)
	{

		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts/getcontactedprofilearray";
			$timeout = WebServicesTimeOut::$contactServiceTimeout["getcontactedprofilearray"];
			$viewerObj = LoggedInProfile::getInstance("newjs_master", '');
			$url = $url . "/" . $profileid;
			$params['CONDITION'] = json_encode($condition);
			$params['SKIPARRAY'] = json_encode($skipArray);
			$result = CommonUtility::webServiceRequestHandler($url, $params, "POST",$timeout);
			$output = array();
			if (is_array($result)) {
				foreach ($result as $row) {
					$output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
					$output[$row["PROFILEID"]]["COUNT"] = $row["COUNT"];
					$output[$row["PROFILEID"]]["SEEN"] = $row["SEEN"];
					$output[$row["PROFILEID"]]["FILTERED"] = $row["FILTERED"];
				}
			}
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactsObj = new newjs_CONTACTS($dbName);
			$output = $contactsObj->getContactedProfileArray($condition,$skipArray);
		}

		return $output;
	}
	public function getTempContact($sender,$receiver)
	{
		$contactTempObj = new NEWJS_CONTACTS_TEMP();
		$tempContact = $contactTempObj->getTempContact($sender,$receiver);
		return $tempContact;
	}
	public function getContactsDetails($profileid,$profileArray)
	{
		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts";
			$url = $url . "/getProfilelistContact/" . $profileid;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["getProfilelistContact"];
			$profiles['PROFILE'] = $profileArray;
			$query = http_build_query($profiles);
			$url = $url . "?" . $query;
			$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactsObj = new newjs_CONTACTS($dbName);
			$result = $contactsObj->getContactsDetails($profileid,$profileArray);
		}
		return $result;
	}

	public function getContactType($viewerObj,$viewedObj)
	{
		if($viewerObj->getPROFILEID()==$viewedObj->getPROFILEID())
		{
			return null;
		}
		$contactObj = new Contacts($viewerObj,$viewedObj);
		if($contactObj->getTYPE())
		{
			if($viewerObj->getPROFILEID() == $contactObj->getSenderObj()->getPROFILEID())
			{
				$output["TYPE"] = $contactObj->getTYPE();
			}
			else
				$output["TYPE"] = "R".$contactObj->getTYPE();
		}

		return $output;
	}
	/** This lib function is used to get SENDER or RECEIVER for a profile ID
	 *@param $parameter : Parameters to be fetched or *
	 *@param $whereArr : Array specified whether SENDER or RECEIVER to be find out
	 */
	public function getResultSet($parameter="*",$whereArr)
	{
		if(isset($whereArr['SENDER']))
		{
			$profileid=$whereArr['SENDER'];
			$where = "SENDER:".$profileid;
		}
		elseif(isset($whereArr['RECEIVER']))
		{
			$profileid=$whereArr['RECEIVER'];
			$where = "RECEIVER:".$profileid;
		}
		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts/" . $profileid;
			$url = $url . "?q=(" . $where . ")";
			$url = $url . "&fields=(" . $parameter . ")";
			$timeout = WebServicesTimeOut::$contactServiceTimeout["resultset"];
			$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactResultSetObj = new newjs_CONTACTS($dbName);
			$result=$contactResultSetObj->contactResultInfo($parameter,$whereArr);
		}
		return $result;
	}

	public function makeAllContactSeen($profileid,$type)
	{
		$result = false;
		if(JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts/updateseen/" . $profileid;
			$params['TYPE'] = $type;
			if ($type == ContactHandler::INITIATED ||$type == ContactHandler::FILTERED )
				$params["VIEWEDTYPE"] = "RECEIVER";
			elseif ($type == ContactHandler::ACCEPT)
				$params["VIEWEDTYPE"] = "SENDER";
			else if($type == ContactHandler::DECLINE)
				$params["VIEWEDTYPE"] = "SENDER";
			else if($type == ContactHandler::CANCEL_ALL)
				$params["VIEWEDTYPE"] = "SENDER";
			$timeout = WebServicesTimeOut::$contactServiceTimeout["updateseen"];
			$result = CommonUtility::webServiceRequestHandler($url, $params, "POST",$timeout);
		}
		if($result === false)
		{
			if($type == ContactHandler::CANCEL_ALL)
			{
				$dbName = JsDbSharding::getShardNo($profileid);
			$contactResultSetObj = new newjs_CONTACTS($dbName);
			$result = $contactResultSetObj->updateCancelSeen($profileid);
			}
			else
			{	
			$dbName = JsDbSharding::getShardNo($profileid);
			$contactResultSetObj = new newjs_CONTACTS($dbName);
			$result = $contactResultSetObj->updateContactSeen($profileid,$type);
			}
		}
		return $result;
	}

	public function getContactRecords($profileId1,$profileId2,$checkdb=0)
	{

		$result = false;
		if((JsConstants::$webServiceFlag == 1 && php_sapi_name() != 'cli') && $checkdb != 1) {
			$result = true;
			$url = JsConstants::$contactUrl . "/v1/contacts";
			$url = $url . "/viewer/" . $profileId1 . "/viewed/" . $profileId2;
			$timeout = WebServicesTimeOut::$contactServiceTimeout["viewerviewed"];
			$result = CommonUtility::webServiceRequestHandler($url,"","",$timeout);
		}
		if($result === false)
		{
			$dbName = JsDbSharding::getShardNo($profileId1);
			$contactObj = new newjs_CONTACTS($dbName);
			$result  = $contactObj->getContactRecord($profileId1,$profileId2);
		}
		return $result;
	}


	public function update($contactObj)
	{
		$url = JsConstants::$contactUrl . "/v1/contacts";
		$url = $url."/update";
		$data['CONTACTID'] = $contactObj->getCONTACTID();
		$data['SENDER'] = $contactObj->getSenderObj()->getPROFILEID();
		$data['RECEIVER'] = $contactObj->getReceiverObj()->getPROFILEID();
		$data['COUNT'] = $contactObj->getCOUNT();
		$data['TYPE'] = $contactObj->getTYPE();
		$data['TIME'] = $contactObj->getTIME();
		$data['MSG_DEL'] = $contactObj->getMSG_DEL();
		$data['SEEN'] = $contactObj->getSEEN();
		$data['FILTERED'] = $contactObj->getFILTERED();
		$data['FOLDER'] = $contactObj->getFOLDER();
		$timeout = WebServicesTimeOut::$contactServiceTimeout["update"];
		$response = CommonUtility::webServiceRequestHandler($url, $data,
			"POST",$timeout,array($data["SENDER"],$data["RECEIVER"]));
		return $response;
	}

	public function insert($contactObj)
	{
		$url = JsConstants::$contactUrl . "/v1/contacts";
		$data['CONTACTID'] = $contactObj->getCONTACTID();
		$data['SENDER'] = $contactObj->getSenderObj()->getPROFILEID();
		$data['RECEIVER'] = $contactObj->getReceiverObj()->getPROFILEID();
		$data['COUNT'] = $contactObj->getCOUNT();
		$data['TYPE'] = $contactObj->getTYPE();
		$data['TIME'] = $contactObj->getTIME();
		if($contactObj->getPageSource()=="AP")
			$data['MSG_DEL'] = "Y";
		else
			$data['MSG_DEL'] = "N";
		$data['SEEN'] = $contactObj->getSEEN();
		$data['FILTERED'] = $contactObj->getFILTERED();
		$data['FOLDER'] = $contactObj->getFOLDER();
		$timeout = WebServicesTimeOut::$contactServiceTimeout["insert"];
		$response = CommonUtility::webServiceRequestHandler($url, $data,
			"POST",$timeout,array($data["SENDER"],$data["RECEIVER"]));
		return $response;
	}

	public function delete($contactObj)
	{
		$url = JsConstants::$contactUrl . "/v1/contacts";
		$url = $url."/viewer/".$contactObj->getSenderObj()->getPROFILEID()
			."/viewed/".$contactObj->getReceiverObj()->getPROFILEID();
		$timeout = WebServicesTimeOut::$contactServiceTimeout["delete"];
		$response = CommonUtility::webServiceRequestHandler($url, "",
			"DELETE",$timeout,array($contactObj->getSenderObj()->getPROFILEID
			(),$contactObj->getReceiverObj()->getPROFILEID()));
		return $response;
	}

}
?>
