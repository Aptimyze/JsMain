<?php

class deliverTempContactsTask extends sfBaseTask
{
    private $contactDetailArr;
    private $contactObj; 
    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'deliverTempContacts';
        $this->briefDescription    = 'Deliver Temporary Contacts ';
        $this->detailedDescription = <<<EOF
The [deliverTempContats|INFO] task get temporary contacts made and when the profile is live delivers the contacts to the receiver profile.
Call it with:

  [php symfony deliverTempContacts|INFO]
EOF;
		$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
    }
    
    protected function execute($arguments = array(), $options = array())
    {
	ini_set("memory_limit","256M");
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);
		
        $contactTempObj = new NEWJS_CONTACTS_TEMP;
            
        $ts          = time();
        $ts4         = $ts - 72 * 60 * 60;
        $before4days = date("Y-m-d", $ts4); //Before 4 days
        
        $ts1        = $ts - 24 * 60 * 60;
        $before1day = date("Y-m-d", $ts1); //Previous day
        
        $startTime = $before4days . " 00:00:00";
        $endTime   = $before1day . " 23:59:59";
        
        $mainAdminObj     = new MAIN_ADMIN_LOG;
        $activatedProfile = $mainAdminObj->getActivatedProfile($startTime, $endTime);
        
        $jprofileObj                  = new JPROFILE;
        $value["ACTIVATED"]           = "'Y'";
        $value["INCOMPLETE"]          = "'N'";
        $value["activatedKey"]        = 1;
        $lessThanArray["ENTRY_DT"]    = $endTime;
        $greaterThanArray["ENTRY_DT"] = $startTime;
        $fields                       = "PROFILEID,GENDER,SUBSCRIPTION,ACTIVATED,INCOMPLETE,SOURCE";
        $newActivatedProfile          = $jprofileObj->getArray($value, '', $greaterThanArray, $fields, $lessThanArray);
        if (is_array($newActivatedProfile)) {
            foreach ($newActivatedProfile as $k => $v) {
                unset($newActivatedProfile[$k]);
                $newActivatedProfile[$v["PROFILEID"]] = $v;
            }
        }
        if (is_array($newActivatedProfile) && is_array($activatedProfile)) {
            $activatedProfile = array_merge($activatedProfile, $newActivatedProfile);
        } elseif (is_array($newActivatedProfile)) {
            $activatedProfile = $newActivatedProfile;
        }
	unset($newActivatedProfile);
        if (is_array($activatedProfile)) {
            foreach ($activatedProfile as $k => $v) {
                unset($activatedProfile[$k]);
                $activatedProfile[$v["PROFILEID"]] = $v;
            }
        }
        if (is_array($activatedProfile)) {
            $profilesArr = array_keys($activatedProfile);
            $tempContact    = $contactTempObj->getTempAllContacts($profilesArr);
        }
	unset($activatedProfile);
	unset($profilesArr);
        //If not delivered temporary contacts are available, deliver them and mark DELIVERED
        if ($tempContact) {
            //Array has been broken into chunks of 500
            $chunkOf500      = array_chunk($tempContact, 500);
            $availableChunks = count($chunkOf500);
            
            for ($i = 0; $i < $availableChunks; $i++) {
                $deliveredProfiles         = array();
                $notDeliveredProfilesError = array();
		$temporaryContact	   = array();
                $temporaryContact          = $chunkOf500[$i];
                foreach ($temporaryContact as $key => $val) {
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
        }
        $this->cleanUpSentContacts($contactTempObj);
    }
    
    /************
    Checks whether receiver is filtered or not
    Initiates Contact between sender and receiver
    ************/
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
    
    /********Checks whether temporary contact has to be delivered or not through
    Gender check
    If receiver is under screening
    If any contact exists between sender and receiver
    If sennder is offline member then disallow him for making contact
    returns error message if any.
    ***************/
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
                if ($this->contactDetailArr["CUST_MESSAGE"]);
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
        //$this->errorMsg = $this->errorMsg.'Caught Exception: '. $profileObj->getPROFILEID().'->'.$receiverObj->getPROFILEID().'=>'.$e->getMessage(). "";
        
        
        return null;
        
    }
    /** logs sfException
    @param $ex Exception Obj
    */
    private function setExceptionError($ex)
    {
        $this->errorMsg .= " " . $ex->getMessage();
    }
    
    /*
     * Those temporary contacts are deleted whose DELIVER_TIME is before 30 days
     */
    private function cleanUpSentContacts($contactTempObj)
    {
        $time = date('Y-m-d', JSstrToTime('-30 days')) . " 00:00:00";
        $contactTempObj->deleteOldDeliveredContact($time);
    }
    
    
}
