<?php
/**
 * CLASS Accept 
 * This class is responsible to handle Accept event between two users.
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage contacts
 * The {@link ContactFactory} uses this class to instantiate it's own 
 * and it's siblings {@link CancelAccept}, {@link Decline}, 
 * {@link Reminder}, {@link CancelContact}, {@link WriteMessage} 
 * and {@link Initiate} class
 * @extends ContactEvent
 */



class Accept extends ContactEvent
{
  /**
   * 
   * Constructor for instantiating object of Accept class
   * 
   * <p>
   * It sets the contactHandler variable of parent class ContactEvent and then call the constructior of parent class ContactEvent.
   * </p>
   * 
   * @access public
   * @param ContactHandler $contactHandler
   */
  public function __construct(ContactHandler $contactHandler)
  {
    try
    {
      $this->contactHandler = $contactHandler;
      parent::__construct();
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
  /**
   * 
   * Sets details required to display Pre Component for action Accept
   * 
   * <p>
   * This function sets information required to built pre component. It instantiates PreComponent, set the details like drafts, template name etc. required and returns this component.
   * </p>
   * 
   * @access public
   */
  public function setPreComponent()
  {
    $this->component	=	new PreComponent;
    $draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());
    $this->component->drafts=$this->component->acceptdrafts = $draftsObj->getAcceptDrafts();

    //gets lists of drafts to be displayed (full drop down menu displayed for paid users) when user clicks on accept
    $this->component->declinedrafts = $draftsObj->getDeclineDrafts();//gets lists of drafts to be displayed (full drop down menu displayed for paid users) when user clicks on accept
    //$this->component->layoutTpl="layout_dp";
    //$this->component->innerTpl="profile_eoi_pia";
  }
  /**
   * 
   * Submits the form and make database changes
   * 
   * <p>
   * This function updates:
   * <ol>
   * <li> memcache </li>
   * <li> contacts table </li>
   * </ol>
   * </p>
   * 
   * @access public
   */
  public function submit()
  { 
    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
    $currentFlag	=	$this->contactHandler->getContactType();//depends on function provided by nikhil
    $this->updateMemcache($currentFlag,$this->contactHandler->getContactObj()->getFILTERED());
    $this->contactHandler->getContactObj()->setType(ContactHandler::ACCEPT);
    $this->contactHandler->getContactObj()->setSEEN(Contacts::NOTSEEN);
    $this->contactHandler->getContactObj()->updateContact();
    $responseTracking =  $this->contactHandler->getElements("RESPONSETRACKING");
    JSResponseTracking::updateResponseTracking($this->contactHandler,$responseTracking);
    $FTOContactDetails = new FTOContactDetails($this->contactHandler->getViewer());
    $FTOContactDetails->updateFTOContactViewedLog($this->contactHandler->getContactObj()->getSenderObj(),$this->contactHandler->getContactObj()->getReceiverObj());
    $action = FTOStateUpdateReason::ACCEPT_SENT;
    $this->contactHandler->getViewer()->getPROFILE_STATE()->updateFTOState($this->contactHandler->getViewer(), $action);
    $action = FTOStateUpdateReason::ACCEPT_RECEIVED;
    $this->contactHandler->getViewed()->getPROFILE_STATE()->updateFTOState($this->contactHandler->getViewed(), $action);
    $this->contactHandler->setElement("STATUS","A");
    $this->handleMessage();
    $producerObj=new Producer();
    if($producerObj->getRabbitMQServerConnected())
    {
        $sender = $this->contactHandler->getViewer();
        $receiver = $this->contactHandler->getViewed();
        $channel =  MobileCommon::getChannel();
        $date = date('Y-m-d H:i:s');
        $sendMailData = array('process' =>MessageQueues::DELAYED_MAIL_PROCESS ,'data'=>array('type' => 'ACCEPTCONTACT','body'=>array('senderid'=>$sender->getPROFILEID(),'receiverid'=>$receiver->getPROFILEID(),'whichChannel'=>$channel,'currentTime'=>$date,'type'=>'ACC') ), 'redeliveryCount'=>0 );
        $producerObj->sendMessage($sendMailData);
        if (CommonFunction::isPaid($sender->getSUBSCRIPTION()))
        {
          $senderSmsData=array('process'=>'SMS','data'=>array('type'=>'ACCEPTANCE_VIEWER','body'=>array('senderid'=>$sender->getPROFILEID(),'receiverid'=>$receiver->getPROFILEID() ) ), 'redeliveryCount'=>0 );
          $producerObj->sendMessage($senderSmsData);
        }
        if (CommonFunction::isPaid($receiver->getSUBSCRIPTION()))
        {
          $receiverSmsData=array('process'=>'SMS','data'=>array('type'=>'ACCEPTANCE_VIEWED','body'=>array('receiverid'=>$receiver->getPROFILEID(),'senderid'=>$sender->getPROFILEID() ) ), 'redeliveryCount'=>0 );
          $producerObj->sendMessage($receiverSmsData);
        }
        $gcmData=array('process'=>'GCM','data'=>array('type'=>'ACCEPTANCE','body'=>array('receiverid'=>$receiver->getPROFILEID(),'senderid'=>$sender->getPROFILEID() ) ), 'redeliveryCount'=>0 );
        $producerObj->sendMessage($gcmData);
	//Add to acceptance roster
      $chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'ACCEPTANCE', 'body' => array('sender' => array('profileid'=>$this->contactHandler->getViewer()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewer()->getPROFILEID()),'username'=>$this->contactHandler->getViewer()->getUSERNAME()), 'receiver' => array('profileid'=>$this->contactHandler->getViewed()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewed()->getPROFILEID()),"username"=>$this->contactHandler->getViewed()->getUSERNAME()))), 'redeliveryCount' => 0);
      $producerObj->sendMessage($chatData);
        
    }
    else
    {
      $this->sendMail();
      $this->postAcceptanceSMS();
      try
      {
        $instantNotificationObj = new InstantAppNotification("ACCEPTANCE");
        $instantNotificationObj->sendNotification($this->contactHandler->getViewed()->getPROFILEID(), $this->contactHandler->getViewer()->getPROFILEID());
      }
      catch(Exception $e)
      {
      throw new jsException($e);
      }
    
    }
    
    //Outbound Event
    $iPgID = $this->contactHandler->getViewer()->getPROFILEID();
    $iPogID =$this->contactHandler->getViewed()->getPROFILEID();
    
    //Pg accpets interest
    GenerateOutboundEvent::getInstance()->generate(OutBoundEventEnums::ACCEPT_INTEREST, $iPgID, $iPogID);
    
    //Pog Interest has been interested
    GenerateOutboundEvent::getInstance()->generate(OutBoundEventEnums::INTEREST_ACCEPTED, $iPogID, $iPgID);
  }	

  /**
   * 
   * function setPostComponent
   * 
   * <p>
   * This function sets details to create display template after the action Accept is performed. It instantiates PostComponent and sets details required like message to be diplayed, title of page etc in the variable component 
   * </p>
   * 
   * @access public
   */
  public function setPostComponent()
  {
    $this->component	=	new PostComponent;
    $draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());    
    $this->component->drafts = $draftsObj->getAcceptDrafts('Y');
    $this->component->declinedrafts = $draftsObj->getDeclineDrafts();
    $this->component->acceptdrafts = $draftsObj->getAcceptDrafts();
    $this->setPostDrafts($this->component->acceptdrafts);    
  }
  /**
   * 
   * Sets Variables to be updated in Memcache while performing action Accept
   * 
   * <p>
   * This function sets and updates memcache variables according to the previous status type between sender and receiver
   * </p>
   * 
   * @access private
   * @param $currentFlag
   */
  private function updateMemcache($currentFlag,$filtered='')
  {
    try {
      $profileMemcacheServiceViewerObj = new ProfileMemcacheService($this->contactHandler->getViewer());
      $profileMemcacheServiceViewedObj = new ProfileMemcacheService($this->contactHandler->getViewed());
      $ContactTime = strtotime($this->contactHandler->getContactObj()->getTIME());
      $time = time();
      $daysDiff  = floor(($time - $ContactTime)/(3600*24));
      if($currentFlag==ContactHandler::INITIATED)
      {
        $profileMemcacheServiceViewerObj->update("ACC_BY_ME",1);
        $profileMemcacheServiceViewedObj->update("ACC_ME",1);
        $profileMemcacheServiceViewedObj->update("ACC_ME_NEW",1);
        $profileMemcacheServiceViewedObj->update("NOT_REP",-1);
        if ($filtered != 'Y' || $filtered != 'J'){
          if ( $daysDiff > CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT )
          {
            $profileMemcacheServiceViewerObj->update("INTEREST_ARCHIVED",-1);
          }
          else
          {
            if($daysDiff >= CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT && $daysDiff <= CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT)
            {
              $profileMemcacheServiceViewerObj->update("INTEREST_EXPIRING",-1);
            }
            $profileMemcacheServiceViewerObj->update("OPEN_CONTACTS",-1);
            $profileMemcacheServiceViewerObj->update("AWAITING_RESPONSE",-1);
            if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
    		$profileMemcacheServiceViewerObj->update("AWAITING_RESPONSE_NEW",-1);
            
          }
                }
        else $profileMemcacheServiceViewerObj->update("FILTERED",-1);


      }
      elseif($currentFlag==ContactHandler::DECLINE)
      {
        $profileMemcacheServiceViewerObj->update("ACC_BY_ME",1);
        $profileMemcacheServiceViewedObj->update("ACC_ME",1);
        $profileMemcacheServiceViewedObj->update("ACC_ME_NEW",1);
        $profileMemcacheServiceViewerObj->update("DEC_BY_ME",-1);
        $profileMemcacheServiceViewedObj->update("DEC_ME",-1);
        if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
		$profileMemcacheServiceViewedObj->update("DEC_ME_NEW",-1);
      }
      elseif($currentFlag==ContactHandler::CANCEL)
      {
        $profileMemcacheServiceViewedObj->update("ACC_BY_ME",1);
        $profileMemcacheServiceViewerObj->update("ACC_ME",1);
        $profileMemcacheServiceViewerObj->update("ACC_ME_NEW",1);
        $profileMemcacheServiceViewerObj->update("DEC_BY_ME",-1);
        $profileMemcacheServiceViewedObj->update("DEC_ME",-1);
        if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
		$profileMemcacheServiceViewedObj->update("DEC_ME_NEW",-1);
      }
      $profileMemcacheServiceViewerObj->updateMemcache();
      $profileMemcacheServiceViewedObj->updateMemcache();
       InboxUtility::cachedInboxApi('del',sfContext::getInstance()->getRequest(),$this->contactHandler->getViewer()->getPROFILEID(),"",1);
    InboxUtility::cachedInboxApi('del',sfContext::getInstance()->getRequest(),$this->contactHandler->getViewed()->getPROFILEID(),"",1);
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }
  
	public function sendMail()
	{
		$sender = $this->contactHandler->getViewer();
		$receiver = $this->contactHandler->getViewed();
		ContactMailer::sendAcceptanceMailer($receiver,$sender);
		return true;
	}
private function postAcceptanceSMS()
	{
    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
    $sender = $this->contactHandler->getViewer();
    $receiver = $this->contactHandler->getViewed();
    if (CommonFunction::isPaid($sender->getSUBSCRIPTION())) { // WHOEVER IS PAID HE/SHE WOULD RECEIVE THE SMS
    	$smsViewer = new InstantSMS("ACCEPTANCE_VIEWER",$sender->getPROFILEID(),'',$receiver->getPROFILEID());//this is the acceptor
      $smsViewer->send();
      }
    if (CommonFunction::isPaid($receiver->getSUBSCRIPTION())){
    	$smsReceiver = new InstantSMS("ACCEPTANCE_VIEWED",$receiver->getPROFILEID(),'',$sender->getPROFILEID());//this is the one whos interest is being accepted
      $smsReceiver->send();

      } 
    		
	}
        
        
}
?>
