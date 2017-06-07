<?php

/**
 * Initiate action class
 *
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @created Fri Nov 30 16:21:18 IST 2012
 * @package jeevansathi
 * @subpackage contacts
 * @see ProfileMemcache
 */
/**
 * Initiate action class
 *
 * The {@link ContactFactory} uses this class to instantiate it's own
 * and it's siblings {@link Accept}, {@link Decline},
 * {@link Reminder}, {@link CancelContact}, {@link WriteMessage}
 * and {@link CancelAccept} class
 *
 * This class is responsible to handle initiate contact event between two users.
 *
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @extends ContactEvent
 */
use MessageQueues as MQ; //MessageQueues-having values defined for constants used in this class.

class Initiate extends ContactEvent{

  /**#@+
   * @access private
   */
  /**
   * This holds the instance of ProfileMemcache class for viewer profile.
   *
   * @var Object of {@link ProfileMemcache} class.
   */
  private $viewerMemcacheObject;

  /**
   * This holds the instance of ProfileMemcache class for viewed profile.
   *
   * @var Object of {@link ProfileMemcache} class.
   */
  private $viewedMemcacheObject;

  /**
   * This holds the draft for EOI instant mailer.
   *
   * @var String
   */
  private $_draft;

  /**
   * This holds the instance of NEWJS_CONTACTS_ONCE store
   *
   * @var Object of {@link NEWJS_CONTACTS_ONCE} class.
   */
  private $_contactsOnceObj;

  /**
   * This holds all the errors
   *
   * @var array
   */
  private $_errorArray;

  /**
   * This holds Viewer profile object
   *
   * @var object
   */
  private $viewer;

  /**
   * This holds Viewed profile object
   *
   * @var object
   */
  private $viewed;

  /**
   * This holds the stype
   *
   * @var string
   */
  private $stype;

  /**
   * This variable holds if RabbitMQ needs to be used or not
   * @var bool
   */
  private $tasksThruQueue;

  private $producerObj;



  /**#@-*/

  /**
   * Constructor for this class.
   *
   * <p>
   * Function to initialize member variables for this class.
   * This also calls the parent constructor of {@link ContactEvent} class.
   * </p>
   *
   * @param ContactHandler $contactHandler
   * @access public
   */
  public function __construct(ContactHandler $contactHandler) {
    try
    {
    $this->contactHandler = $contactHandler;
    $this->_draft = null;
    $this->_contactsOnceObj = null;
    $this->viewer = $this->contactHandler->getViewer();
    $this->viewed = $this->contactHandler->getViewed();
    $this->stype = null;
    $this->viewerMemcacheObject = new ProfileMemcacheService($this->viewer->getPROFILEID());
    $this->viewedMemcacheObject = new ProfileMemcacheService($this->viewed->getPROFILEID());
    $this->_sendMail=null;
    $this->tasksThruQueue = true;

    if($this->tasksThruQueue)
    {
		$this->producerObj = new Producer();
    }

        if ($this->contactHandler->getPageSource() == "AP")
    $this->optionalFlag = true;
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
    parent::__construct();

  }

  /**#@+
   * @access public
   *
  /**
   * Sets Pre-action values which will be displayed.
   *
   * <p>
   * This function sets all the values that will be displayed before the action is done.
   * This instantiates the object of {@link PreComponent} class.
   * </p>
   */
  public function setPreComponent()
  {
    $this->component = null;
    $this->component = new PreComponent;
    $draftsObj = new ProfileDrafts($this->viewer);
    if (is_array($draftsObj->getEoiDrafts())) {
      $draftArray = $draftsObj->getEoiDrafts();
      $this->component->drafts = $this->component->eoiDrafts = $draftArray;
    }
  }

  /**
   * Sets Post-action values which will be displayed
   *
   * <p>
   * This function sets all the values that will be displayed after the action is done.
   * This instantiates the object of {@link PostComponent} class.
   * </p>
   */
  public function setPostComponent()
  {
    $this->component = null;
    $this->component = new PostComponent;
    $draftsObj = new ProfileDrafts($this->viewer);
    if (is_array($draftsObj->getEoiDrafts())) {
      $draftArray = $draftsObj->getEoiDrafts();
      $this->component->drafts = $this->component->eoiDrafts = $draftArray;
      $this->setPostDrafts($this->component->drafts);
    }
    if ($this->viewer->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()=="Y" && FTOLiveFlags::IS_FTO_LIVE != 1)
    {
      $this->component->innerTpl = 'profile_eoi_iuni_post';
    }
  }

  /**
   * Performs submit action related to this class.
   *
   * <p>
   * This performs the submit action for Initiate class. This updates various
   * <ol>
   * <li>stores</li>
   * <li>JsMemcache</li>
   * </ol>
   * </p>
   *
   * @uses _submitTemporaryContacts()   To submit contacts into {@link NEWJS_CONTACTS_TEMP}
   *                                    when the initiator profile is in underscreening state.
   * @uses _addContactHitLimit()        To update {@link MIS_CONTACTS_FAULT_MONITOR}
   *                                    when contact limit is hit.
   * @uses _searchContactFlowTracking() To update {@link MIS_SEARCH_CONTACT_FLOW_TRACKING_NEW}
   *                                    to keep a track from where EOI is done.
   * @uses handleMessage()              Handles message that is initiated by sender.
   * @uses _makeEntryInContactsOnce()    Makes entry in contacts once table for cron to fire mails.
   * @uses sendMail()                   To send mail in whatever cases.
   */
  public function submit() {
    try {
      $this->_errorArray = $this->errorHandlerObj->getAllError();
      if(is_array($this->_errorArray) && ((in_array(ErrorHandler::UNDERSCREENING, $this->_errorArray) !== false) || (in_array(ErrorHandler::INCOMPLETE, $this->_errorArray) !== false)))
      {
        $this->_submitTemporaryContacts();
        return true;
      }
      else if (is_array($this->_errorArray) && ((in_array(ErrorHandler::FILTERED, $this->_errorArray) !== false))) {
        $this->contactHandler->getContactObj()->setFILTERED(Contacts::FILTERED);
      }

      if ($this->contactHandler->getContactObj()->getTYPE() === "E" && $this->contactHandler->getToBeType() === "I") {
        // Do Not update memcache variables.
      }
      else {
        $this->viewerMemcacheObject->update("NOT_REP",1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("TOTAL_CONTACTS_MADE",1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("MONTH_INI_BY_ME",1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("TODAY_INI_BY_ME",1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("WEEK_INI_BY_ME",1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("CONTACTS_MADE_AFTER_DUP",1,$this->optionalFlag);
        $this->viewerMemcacheObject->updateMemcache();
        if ($this->contactHandler->getContactObj()->getFILTERED() === Contacts::FILTERED) {
          $this->viewedMemcacheObject->update("FILTERED",1,$this->optionalFlag);
          $this->viewedMemcacheObject->update("FILTERED_NEW",1,$this->optionalFlag);
        }
        else {
          $this->viewedMemcacheObject->update("AWAITING_RESPONSE",1,$this->optionalFlag);
          $this->viewedMemcacheObject->update("AWAITING_RESPONSE_NEW",1,$this->optionalFlag);
        }
        $this->viewedMemcacheObject->updateMemcache();
      }

      $this->_addContactHitLimit();

      $this->contactHandler->getContactObj()->setType("I");
      
    if ($this->contactHandler->getContactObj()->getFILTERED() != Contacts::FILTERED && $this->contactHandler->getPageSource()!="AP") {
  
        try
        {
            $msg = $this->contactHandler->getElements("MESSAGE");
            $splitArr = explode("--",$msg);
            $correctedSplit = CommonUtility::correctSplitOnBasisDate($splitArr,1);
            if($msg && $correctedSplit){
                $id = $splitArr[3];
                $body = array("otherUserId" => $this->viewed->getPROFILEID(), "selfUserId" => $this->viewer->getPROFILEID(), "message" => $splitArr[0],"exUrl" => '',"extraParams" => array('CHAT_ID'=>$id));
                if(!$this->sendDataOfQueue(MQ::INSTANT_EOI_PROCESS, 'INSTANT_CHAT_EOI_MSG', $body))
                {  
                  $instantNotificationObj = new InstantAppNotification("CHAT_EOI_MSG");
                  $instantNotificationObj->sendNotification($this->viewed->getPROFILEID(),$this->viewer->getPROFILEID(),$splitArr[0],'',array('CHAT_ID'=>$id));
                }
                unset($body);
            }
            else
            {
                if(!$this->sendDataOfQueue(
                  MQ::INSTANT_EOI_PROCESS,
                  'INSTANT_EOI',
                  array("otherUserId" => $this->viewed->getPROFILEID(),"selfUserId" => $this->viewer->getPROFILEID())))
                {
                  $instantNotificationObj = new InstantAppNotification("EOI");
                  $instantNotificationObj->sendNotification($this->viewed->getPROFILEID(),$this->viewer->getPROFILEID());
                }
            }
        }
        catch(Exception $e)
        {
          throw new jsException($e);
        }
        try
        {
          //send instant JSPC/JSMS notification
          $producerObj = new Producer();
          if($producerObj->getRabbitMQServerConnected())
          {
            $notificationData = array("notificationKey"=>"EOI","selfUserId" => $this->viewed->getPROFILEID(),"otherUserId" => $this->viewer->getPROFILEID());
            $producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
          }
          unset($producerObj);
        }
        catch (Exception $e) {
          throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
        }
    }
      
      
      
      

      if($this->contactHandler->getContactType()==ContactHandler::CANCEL_CONTACT) {
        $this->contactHandler->getContactObj()->updateContact();
        $this->viewerMemcacheObject->update("CANCELLED_EOI",-1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("NOT_REP",1,$this->optionalFlag);
        $this->viewedMemcacheObject->update("DEC_ME",-1,$this->optionalFlag);
        if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
    $this->viewedMemcacheObject->update("DEC_ME_NEW",-1,$this->optionalFlag);
        $this->viewerMemcacheObject->update("DEC_BY_ME",-1,$this->optionalFlag);
        if ($this->contactHandler->getContactObj()->getFILTERED() === Contacts::FILTERED) {
          $this->viewedMemcacheObject->update("FILTERED",1,$this->optionalFlag);
          $this->viewedMemcacheObject->update("FILTERED_NEW",1,$this->optionalFlag);
        }
        else {
          $this->viewedMemcacheObject->update("AWAITING_RESPONSE",1,$this->optionalFlag);
          $this->viewedMemcacheObject->update("AWAITING_RESPONSE_NEW",1,$this->optionalFlag);
        }
        $this->viewerMemcacheObject->updateMemcache();
        $this->viewedMemcacheObject->updateMemcache();
      }
      else
      {
        $this->contactHandler->getContactObj()->setCount(1);
        $pageSource=$this->contactHandler->getPageSource();
        $this->contactHandler->getContactObj()->setPageSource($pageSource);
        $this->contactHandler->getContactObj()->insertContact();
        $action = FTOStateUpdateReason::EOI_SENT;
        $this->viewer->getPROFILE_STATE()->updateFTOState($this->viewer, $action);
        
      }
                $requestTimeOut = 300;
    //curl for analytics team by Nitesh for Lavesh team
    /*if(JsConstants::$vspServer == 'live'){
      $feedURL = JsConstants::$postEoiUrl;
      $postParams = json_encode(array("PROFILEID"=>$this->contactHandler->getViewer()->getPROFILEID(),"PROFILEID_POG"=>$this->contactHandler->getViewed()->getPROFILEID(),'ACTION'=>'I'));
      $profilesList = CommonUtility::sendCurlPostRequest($feedURL,$postParams,$requestTimeOut);
                        if($profilesList === false){
                            $date = date("Y-m-d");
                            $file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/eoiTimedout_".$date.".txt","a");
                            $stringToWrite = $this->contactHandler->getViewer()->getPROFILEID().",".$this->contactHandler->getViewed()->getPROFILEID().",".date("H:i:s",time());
                            fwrite($file,$stringToWrite."\n");
                            fclose($file);
                        }
    }*/

      $this->_searchContactFlowTracking();
      if(in_array($this->stype, array(SearchTypesEnums::MATCHALERT_MYJS_JSMS,SearchTypesEnums::MATCHALERT_MYJS_IOS,  SearchTypesEnums::AppMyJsMatchAlertSection)))
            {
            $this->viewerMemcacheObject->update("MATCHALERT_TOTAL",-1,$this->optionalFlag);
            $this->viewerMemcacheObject->setMATCHALERT(0);
            } 
      $this->contactHandler->setElement(CONTACT_ELEMENTS::STATUS,"I");
      $this->handleMessage();
      $this->_contactsOnceObj = new NEWJS_CONTACTS_ONCE();
      $sentMailsToday=$this->_contactsOnceObj->getCountOfSentMailsToday($this->viewed->getPROFILEID());
      if($sentMailsToday<5)
          $this->_sendMail='Y';
      else
          $this->_sendMail='N';
          
      $isFiltered = $this->_makeEntryInContactsOnce();


	    try {
		    //send instant JSPC/JSMS notification
		    if(JsConstants::$directRosterUpdate) {
			    if($this->checkAndCreateOFUser($this->viewer->getPROFILEID()))
			    {
				    $this->updateRoster($this->viewer, $this->viewed, "intsent");
			    }
			    if ($this->contactHandler->getContactObj()->getFILTERED() == "Y")
				    $roster = "NOTINUSE";
			    else
				    $roster = "intrec";
			    if($this->checkAndCreateOFUser($this->viewed->getPROFILEID()))
			    {
				    $this->updateRoster($this->viewed, $this->viewer, $roster);
			    }
		    }
		    else
		    {
			    $this->sendDataOfQueue(
				    'CHATROSTERS', 'INITIATE',
				    array('sender' => array('profileid'=>$this->viewer->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->viewer->getPROFILEID()),'username'=>$this->viewer->getUSERNAME()), 'receiver' => array('profileid'=>$this->viewed->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->viewed->getPROFILEID()),"username"=>$this->viewed->getUSERNAME()),"filter"=>$this->contactHandler->getContactObj()->getFILTERED()=="Y"?"Y":"N"));
		    }
	    } catch (Exception $e) {
		    throw new jsException("Something went wrong while creating Roster -" . $e);
	    }

      if (!$isFiltered && $this->contactHandler->getPageSource()!='AP' && $this->_sendMail=='Y') { // Instant mailer
        $this->sendMail();
      }
      else {
             try {
        $channel =  MobileCommon::getChannel();
        $date = date('Y-m-d H:i:s');
        $this->sendDataOfQueue(
            'MAIL', 'INITIATECONTACT',
				array('type'=>'EOI','whichChannel' =>$channel,'currentTime'=>$date,'onlyLogging'=>1 ));
      } catch (Exception $e) {
        
      }

          
      }
       
       $viewedEntryDate = $this->viewed->getENTRY_DT();
       $now = date("Y-m-d");
       $dateDiff = (JSstrToTime($now) - JSstrToTime($viewedEntryDate)) / 86400;
      if($dateDiff <=1 ) { //Instant SMS
          include_once(JsConstants::$docRoot. "/profile/InstantSMS.php");
          $sms= new InstantSMS("INSTANT_EOI",$this->viewed->getPROFILEID(),array(),$this->viewer->getPROFILEID());
          $sms->send();
      }
      
      if(!$this->optionalFlag)
          $this->getNegativeScoreForUser();
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
    // delete data of Match of the day
    JsMemcache::getInstance()->set("cachedMM24".$this->viewed->getPROFILEID(),"");
    JsMemcache::getInstance()->set("cachedMM24".$this->viewer->getPROFILEID(),"");
    /** caching unset **/
    sfContext::getInstance()->getRequest()->setParameter("caching",1);
    SearchUtility::cachedSearchApi('del',sfContext::getInstance()->getRequest(),$this->viewed->getPROFILEID());
    SearchUtility::cachedSearchApi('del',sfContext::getInstance()->getRequest(),$this->viewer->getPROFILEID());
    InboxUtility::cachedInboxApi('del',sfContext::getInstance()->getRequest(),$this->viewed->getPROFILEID());
    InboxUtility::cachedInboxApi('del',sfContext::getInstance()->getRequest(),$this->viewer->getPROFILEID());
    /** caching unset **/
  }

	public function sendMail()
	{
		$sender = $this->viewer;
		$receiver = $this->viewed;
		$viewedSubscriptionStatus = $this->viewed->getPROFILE_STATE()->getPaymentStates()->isPaid();
                $channel =  MobileCommon::getChannel();
                $date = date('Y-m-d H:i:s');
                // the variable only logging ensures that if it is 0 then mail will be sent and logging done .. if it is 1 then no mail is sent and only logging is done
		if(! $this->sendDataOfQueue(
            'MAIL', 'INITIATECONTACT',
				array('senderid'=>$sender->getPROFILEID(),'receiverid'=>$receiver->getPROFILEID(),'message'=>$this->_getEOIMailerDraft(),'viewedSubscriptionStatus'=>$viewedSubscriptionStatus,'type'=>'EOI','whichChannel' =>$channel,'currentTime'=>$date,'onlyLogging'=>0 ))
		)
		{
			ContactMailer::InstantEOIMailer($receiver->getPROFILEID(), $sender->getPROFILEID(), $this->_getEOIMailerDraft(), $viewedSubscriptionStatus);
		}

		//Update in CONTACTS_ONCE
		$this->_contactsOnceObj->insert(
			$this->contactHandler->getContactObj()->getCONTACTID(),
			$this->viewer->getPROFILEID(),
			$this->viewed->getPROFILEID(),
			$this->_getEOIMailerDraft(),
			"Y");
	}

  /**#@+
   * @access private
   */

  /**
   * Match alert contacts tracking
   *
   * <p>
   * This function tracks the contacts made through match alert mailers.
   * </p>
   *
   * @uses NEWJS_MATCHALERT_CONTACTS->insert() To insert into matchalert_contacts table.
   */
  /***
    private function _matchAlertContactsTracking() {

    $stype = null;

    $clicksource = $this->contactHandler->getElements(CONTACT_ELEMENTS::CLICKSOURCE);
    if ($clicksource) {

    $matchAlertContactsObj = new NEWJS_MATCHALERT_CONTACTS();
    $stype = $matchAlertContactsObj->insert($this->viewer->getPROFILEID(), $this->viewed->getPROFILEID(), $clicksource);

    unset($matchAlertContactsObj);
    }

    return $stype ? $stype : null;
    }
   **/
  /**
   * Tracks pages from where EOI is done.
   *
   * <p>
   * This function logs the search type and the pages from where EOI was done. {@link MIS_SEARCH_CONTACT_FLOW_TRACKING_NEW} store executes
   * queries related to the page from where EOI was performed.
   * </p>
   *
   * @uses JsDbSharding::getShardNo() To get the shard db number on which the query should run.
   */
  private function _searchContactFlowTracking() {

    $shard = JsDbSharding::getShardNo($this->viewer->getPROFILEID());

    $searchContactFlowTrackingObj = new MIS_SEARCH_CONTACT_FLOW_TRACKING_NEW($shard);

    $from_detailProfile = ($this->contactHandler->getPageSource() === "VDP") ? "Y" : "N";
    //MatchAlert handling setting stype to B for matchalert1 clicksource
    $clicksource = $this->contactHandler->getElements(CONTACT_ELEMENTS::CLICKSOURCE);
    if (!$this->stype) {
      $this->stype = $this->contactHandler->getElements(CONTACT_ELEMENTS::STYPE);
      if($this->stype=='CO' || $this->stype=='CN' || $this->stype=='CN2')
        $this->stype = "C";
      else if($this->stype=='VO' || $this->stype=='VN')
        $this->stype='V';
    }
            
            $searchContactFlowTrackingObj->insert(
        $this->viewer->getPROFILEID(),
        $this->stype,
        $this->contactHandler->getContactObj()->getCONTACTID(),
        $from_detailProfile
        );
  } // end of _searchContactFlowTracking

  /**
   * Submit Temporary contacts made by sender.
   *
   * <p>
   * This function logs the contacts of an underscreened profile in {@link NEWJS_CONTACTS_TEMP} class.
   * It also updates JsMemcache data variables.
   * </p>
   *
   */

  private function _submitTemporaryContacts() {

    $receiver_username = $this->viewed->getUSERNAME();
    $contact_status = $this->contactHandler->getContactType();

    $sender_profileid = $this->viewer->getPROFILEID();
    $receiver_profileid = $this->viewed->getPROFILEID();

    if ($sender_profileid && $receiver_profileid) {

      $this->viewerMemcacheObject->update("TOTAL_CONTACTS_MADE",1,$this->optionalFlag);
      $this->viewerMemcacheObject->update("MONTH_INI_BY_ME",1,$this->optionalFlag);
      $this->viewerMemcacheObject->update("TODAY_INI_BY_ME",1,$this->optionalFlag);
      $this->viewerMemcacheObject->update("WEEK_INI_BY_ME",1,$this->optionalFlag);
      $this->viewerMemcacheObject->update("CONTACTS_MADE_AFTER_DUP",1,$this->optionalFlag);
      $this->viewerMemcacheObject->updateMemcache();


      $contacts_temp_obj = new NEWJS_CONTACTS_TEMP();
      $contacts_temp_obj->insert(
          $this->viewer->getPROFILEID(),
          $this->viewed->getPROFILEID(),
          $this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE),
          $this->contactHandler->getElements(CONTACT_ELEMENTS::DRAFT_NAME),
          $this->contactHandler->getElements(CONTACT_ELEMENTS::DRAFT_WRITE),
          $this->contactHandler->getElements(CONTACT_ELEMENTS::STYPE)
          );
    }
  } // end of _submitTemporaryContacts

  /**
   * Identify the limit hit and log it.
   *
   * <p>
   * This function identifies one of the type of limit hit:
   * <ol>
   * <li>Today's</li>
   * <li>Month's</li>
   * <li>Overall</li>
   * </ol>
   * <br />
   * And triggers the respective query to be fired.
   * </p>
   *
   * @uses _insertContactHitLimitInDb() Inserts the hit limit type in DB.
   */
  private function _addContactHitLimit(){
    $data = CommonFunction::getContactLimits($this->viewer->getSUBSCRIPTION(),$this->viewer->getPROFILEID());

    if($this->viewerMemcacheObject->get("TODAY_INI_BY_ME",$this->optionalFlag) >= $data[DAY_LIMIT])
    {
      $this->_insertContactHitLimitInDb("T");
    }
    else if (
        $this->viewerMemcacheObject->get("MONTH_INI_BY_ME",$this->optionalFlag) >= $data[MONTH_LIMIT]
        )
    {
      $this->_insertContactHitLimitInDb("M");
    }
    else if (
        !stristr($this->viewer->getSUBSCRIPTION(), 'F') &&
        $this->viewerMemcacheObject->get("TOTAL_CONTACTS_MADE",$this->optionalFlag) >= $data[OVERALL_LIMIT]
        )
    {
      $this->_insertContactHitLimitInDb("O");
    }
    else if ($this->viewerMemcacheObject->get("WEEK_INI_BY_ME",$this->optionalFlag) >= $data[WEEKLY_LIMIT]) {
      $this->_insertContactHitLimitInDb("W");
    }
    else if (($this->viewerMemcacheObject->get("CONTACTS_MADE_AFTER_DUP",$this->optionalFlag) >= $data[NOT_VALIDNUMBER_LIMIT]) &&
        @in_array(ErrorHandler::PHONE_NOT_VERIFIED, $this->_errorArray)) {
      $this->_insertContactHitLimitInDb("I");
    }

  } // end of _addContactHitLimit

  /**
   * Set draft member for EOI instant mailer
   *
   * <p>
   * This function sets the draft to be displayed in Instant EOI mailer.
   * </p>
   *
   * @param $draft The message viewer wrote to viewed.
   */
  private function _setEOIMailerDraft($draft) {
    $this->_draft = $draft;
  } // end of _setEOIMailerDraft

  /**
   * Get draft member for EOI instant mailer
   *
   * <p>
   * This function gets the draft to be displayed in Instant EOI mailer.
   * </p>
   * @return string.
   */
  private function _getEOIMailerDraft() {
    if (isset($this->_draft)) {
      return $this->_draft;
    }
    else {
      return " "; // In case when no draft was set (to be on the safer side)
    }
  } // end of _getEOIMailerDraft

  /**
   * Make an entry in NEWJS_CONTACTS_ONCE table.
   *
   * <p>
   * This function makes an entry in CONTACTS_ONCE table for EOI mailer cron.
   * </p>
   * @return boolean
   */
  private function _makeEntryInContactsOnce() {


    if ($this->_contactsOnceObj) {
    $presetMessage = PresetMessage::getPresentMessage($this->viewer,CONTACTHANDLER::INITIATED);
    if(strcmp(trim($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE)),trim($presetMessage)) != 0)
      $this->_setEOIMailerDraft(stripslashes(htmlspecialchars($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE), ENT_QUOTES)));
      // Make an entry in CONTACTS_ONCE.
      if (in_array(ErrorHandler::FILTERED, $this->_errorArray) && is_array($this->_errorArray)) {
        return true;
      }
      else{
                if($this->_sendMail=='N' || $this->contactHandler->getPageSource() == "AP" )
                {
                    
                $this->_contactsOnceObj->insert(
                $this->contactHandler->getContactObj()->getCONTACTID(),
                $this->viewer->getPROFILEID(),
                $this->viewed->getPROFILEID(),
                $this->_getEOIMailerDraft(),
                "N");
                }
        return false;
      }
    }
  } // end of _makeEntryInContactsOnce

  /**
   * When Contact Limit is hit, fire query to insert in DB.
   *
   * <p>
   * This function inserts into DB for the type of limit hit for sender.
   * If the entry is already present, then the insert query is not fired.
   * The DB access functions are present in {@link MIS_CONTACTS_FAULT_MONITOR} class.
   * </p>
   *
   * @param string $type
   */
  private function _insertContactHitLimitInDb($type){

    $contact_fault_monitor_obj = new MIS_CONTACTS_FAULT_MONITOR();

    $sender_username = $this->viewer->getUSERNAME();

    $records = null;

    $sender_profileid = $this->viewer->getPROFILEID();

    $records = $contact_fault_monitor_obj->getRecords($sender_profileid, $type);

    if (!$records) {
      $contact_fault_monitor_obj->insert(
          $sender_profileid,
          $sender_username,
          $type
          );
    }
  } // end of _insertContactHitLimitInDb
  /**#@-*/

    public function getErrorArray()
  {
    return $this->_errorArray;
  }
public function getNegativeScoreForUser()
  {
    $senderRow=$this->viewer;
    $receiverRow=$this->viewed;
    $receiverDPP = UserFilterCheck::getInstance($senderRow, $receiverRow)->getDppParameters();
    $receiverProfileId=$receiverRow->getPROFILEID();
    $score=array('R'=>0,'A'=>0,'M'=>0);
// RELIGION CHECK
    $religionExclude=array('1','4','7','9');
    if(!(in_array($senderRow->getRELIGION(),$religionExclude ) && in_array($receiverRow->getRELIGION(),$religionExclude )) && $senderRow->getRELIGION()!='8')
    {
        if($receiverDPP['RELIGION'])
        {
            if(!in_array($senderRow->getRELIGION(),$receiverDPP['RELIGION']))
                $score['R']=1;
        }        
    }
 // MARITAL STATUS CHECK
    if($receiverDPP['MSTATUS'])
    {
        $marriedArray=array('S','D','W','A','M');
        $unMarriedArray=array('N');
        $Rmstatus=$receiverRow->getMSTATUS();
        $Smstatus=$senderRow->getMSTATUS();
        if(!( (in_array($Smstatus,$marriedArray) && in_array($Rmstatus,$marriedArray))
        || (in_array($Smstatus,$unMarriedArray) && in_array($Rmstatus,$unMarriedArray))
           ))
        {
            if(!in_array($Smstatus, $receiverDPP['MSTATUS']))
                    $score['M']=1;
            
        }     
        
    }
    // AGE DIFFERENCE CHECK
    $Rage=$receiverRow->getAGE();
    $Sage=$senderRow->getAGE();
    if($receiverDPP['LAGE'] && $receiverDPP['HAGE'] && ($Sage<35 || $Rage<35))
    {
        $ageDiff = $Sage - $Rage;
        if($ageDiff<0)$ageDiff=$ageDiff*(-1);
        if($ageDiff>=10 && ($Sage < $receiverDPP['LAGE'] || $Sage>$receiverDPP['HAGE']))
                    $score['A']=1;
    }

    $totalScore = $score['A'] + $score['R'] + $score['M'];
    
    if($totalScore)
    {   
        $score['USERNAME'] = $senderRow->getUSERNAME();
        (new MIS_INAPPROPRIATE_USERS_LOG())->insert($senderRow->getPROFILEID(),$score);
        
    }
    }

    public function sendDataOfQueue($process, $type, $body)
    {
	    if($this->tasksThruQueue && $this->producerObj->getRabbitMQServerConnected())
      {
          $trackingData = array('process' => $process,
                                  'data' =>
                                  array(
                                  'body' => $body,
                                  'type' => $type,
                                  'redeliveryCount' => 0)
                                  );
          $this->producerObj->sendMessage($trackingData);
          return true;
		  }
      return false;
    }

	private function updateRoster($profile,$otherprofile,$roster)
	{
		$profileid = $profile->getPROFILEID();
		$otherprofileid = $otherprofile->getPROFILEID();
		$otherusername = $otherprofile->getUSERNAME();
		$md5 = MD5($otherprofileid);
		$uname = $otherusername."|".$md5;
		$string = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
			<rosterItem>
 			<jid>".$otherprofileid."@".JsConstants::$openfireConfigInternal['SERVER_NAME']."</jid>
 			<nickname>".$uname."</nickname>
 			<subscriptionType>3</subscriptionType>
 			<groups>  
 				<group>".$roster."</group>
 			</groups>
 			</rosterItem>";
		$url = JsConstants::$openfireConfigInternal['HOST'] . ":" . JsConstants::$openfireConfigInternal['PORT'] . "/plugins/restapi/v1/users/" . $profileid ."/roster";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$headers = array();
		$headers[] = 'Authorization: ' . JsConstants::$openfireRestAPIKey;
		$headers[] = 'Content-Type: application/xml';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
		curl_exec($ch);
		echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($httpcode == 201)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	private function checkAndCreateOFUser($profileid)
	{
		$pass = md5($profileid);
		$url = JsConstants::$openfireConfigInternal['HOST'] . ":" . JsConstants::$openfireConfigInternal['PORT'] . "/plugins/restapi/v1/users/" . $profileid;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$headers = array();
		$headers[] = 'Authorization: ' . JsConstants::$openfireRestAPIKey;
		$headers[] = 'Content-Type: application/json';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpcode == 200) {
			return true;
		} else {
			$string = "{\"username\": \"" . $profileid . "\",\"password\": \"" . $pass . "\",\"properties\": {\"property\": [{\"@key\": \"createroster\",\"@value\": \"true\"}]}}";
			$url = JsConstants::$openfireConfigInternal['HOST'] . ":" . JsConstants::$openfireConfigInternal['PORT'] . "/plugins/restapi/v1/users";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $string);

			$headers = array();
			$headers[] = 'Authorization: ' . JsConstants::$openfireRestAPIKey;
			$headers[] = 'Content-Type: application/json';

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_exec($ch);
			echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($httpcode == 201 || $httpcode == 200)
			{
				return true;
			}
			else
			{
				return false;
			}

		}
	}



} // end of Initiate Class.
