<?php 
	/**
	* 
	*/
	class deliverTempContacts
	{
		private $contactDetailArr;
    	private $contactObj;
		/*
		Checks whether receiver is filtered or not
		Initiates Contact between sender and receiver
		*/
		private function deliverContact($senderProfile, $receiverProfile)
		{
			$success = false;
			$error   = false;
			
			$this->contactObj = new Contacts($senderProfile, $receiverProfile);
			$error   = $this->deliverContactError( $senderProfile, $receiverProfile);
			if (!$error) {
				$this->sendEOI();
				$success = true;
			}
			$delivered = array(
				"ERROR" => $error,
				"SUCCESS" => $success
			);
			return $delivered;
		}
		
		/*
		Checks whether temporary contact has to be delivered or not through
		Gender check
		If receiver is under screening
		If any contact exists between sender and receiver
		If sennder is offline member then disallow him for making contact
		returns error message if any.
		*/
		private function deliverContactError( $senderProfile, $receiverProfile)
		{
			$contactError = false;
			if (!$senderProfile->getPROFILEID() && !$receiverProfile->getPROFILEID()) {
				$contactError = "BLANK_PROFILEID";
				return $contactError;
			}
			if ($senderProfile->getGENDER() == $receiverProfile->getGENDER()) {
				$contactError = "WRONG_GENDER";
				return $contactError;
			}
			if ($receiverProfile->getACTIVATED() == "N") {
				$contactError = "NOT_ACTIVATED";
				return $contactError;
			}
			if ($receiverProfile->getACTIVATED() == "U") {
				$contactError = "UNDER_SCREENED";
				return $contactError;
			}
			if ($receiverProfile->getACTIVATED() == "H") {
				$contactError = "HIDDEN";
				return $contactError;
			}
			if ($receiverProfile->getACTIVATED() == "D") {
				$contactError = "DELETED";
				return $contactError;
			}
			if ($senderProfile->getSOURCE() == "ofl_prof") {
				$contactError = "SENDER_OFFLINE";
				return $contactError;
			}
			if ($this->contactObj->getType() != 'N' && $this->contactObj->getType() != 'E') {
				$contactError = "CONTACTED";
				return $contactError;
			}
			return $contactError;
		}
		
		/**
		 * send EOI's
		 * @return $contactEngineObj
		 * @param $profileObj
		 * @param $receiverObj
		 * @access private
		 */
		private function sendEOI()
		{
			try {
				if ($this->contactObj->getTYPE() == 'N') {
					$contactHandlerObj = new ContactHandler($this->contactObj->getSenderObj(), $this->contactObj->getReceiverobj(), "EOI", $this->contactObj, 'I', ContactHandler::POST);
					if ($this->contactDetailArr["CUST_MESSAGE"])
					$contactHandlerObj->setElement("MESSAGE", $this->contactDetailArr["CUST_MESSAGE"]);
					if ($this->contactDetailArr["DRAFT_NAME"])
						$contactHandlerObj->setElement("DRAFT_NAME", $this->contactDetailArr["DRAFT_NAME"]);
					$contactHandlerObj->setElement("STATUS", "I");
					$contactHandlerObj->setElement("PROFILECHECKSUM", JsCommon::createChecksumForProfile($this->contactObj->getSenderObj()->getPROFILEID()));
					$contactHandlerObj->setElement("STYPE", $this->contactDetailArr["STYPE"]);
					$contactEngineObj = ContactFactory::event($contactHandlerObj);
					return $contactEngineObj;
				}
			}
			catch (Exception $e) {
				$this->setExceptionError($e);
			}
			
			return null;
			
		}

		/** logs sfException
		@param $ex Exception Obj
		*/
		private function setExceptionError($ex)
		{
			$this->errorMsg .= " " . $ex->getMessage();
		}
		
		private function cleanUpSentContacts($contactTempObj)
		{
			$time = date('Y-m-d', JSstrToTime('-30 days')) . " 00:00:00";
			$contactTempObj->deleteOldDeliveredContact($time);
		}
		
		public function deliverContactsTemp($profileId)
		{
			// consumption logging
			$currdate = date('Y-m-d');
			$file = fopen(JsConstants::$docRoot."/uploads/SearchLogs/ScreenQConsume-$currdate", "a+");
			fwrite($file, "$profileId\n");
			fclose($file);
			$contactTempObj = new NEWJS_CONTACTS_TEMP;
			$tempContact = $contactTempObj->getTempAllContacts(array($profileId));
			if ($tempContact)
			{
				foreach ($tempContact as $key => $val)
				{
					$senderProfileid        = $val["SENDER"];
					$receiverProfileid      = $val["RECEIVER"];
					$this->contactDetailArr = $val;
					$senderProfile          = new Profile('', $senderProfileid);
					$receiverProfile        = new Profile('', $receiverProfileid);
					$senderProfile->getDetail('', '', '*');
					$receiverProfile->getDetail('', '', '*');
					$contactDelivered = $this->deliverContact($senderProfile, $receiverProfile);
					$contactTempObj->setDeliveredInTempContacts($senderProfileid, $receiverProfileid, $contactDelivered["ERROR"]);
					unset($this->contactDetailArr);
					unset($senderProfile);
					unset($receiverProfile);
				}
			}
			// $this->cleanUpSentContacts($contactTempObj);
		}
	}

?>