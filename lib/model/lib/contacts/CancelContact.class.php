<?php

/**
 * CancelContact<BR>
 * <p>The {@link ContactFactory} uses this class to instantiate it's own 
 * and it's siblings {@link CancelAccept}, {@link Decline}, 
 * {@link Reminder}, {@link Accept}, {@link WriteMessage} 
 * and {@link Initiate} class
 * This class is responsible to handle Cancel Contact(or Cancel eoi) event between two users.
 *</p>
 * @package   jeevansathi
 * @subpackage   contacts
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @extends ContactEvent {@link ContactEvent}
 */
class CancelContact extends ContactEvent{
  /**
   * 
   * Constructor for instantiating object of CancelContact class
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
   * Sets details required to display Pre Component for action CancelConact
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
    $privilegeObj=$this->contactHandler->getPrivilegeObj();
    $this->component->privilegeArray = $privilegeObj->getPrivilegeArray();
    $draftsArray = $draftsObj->getEoiDrafts();
    if (is_array($draftsArray)) {
      $this->component->drafts = $this->component->cancelDrafts = $draftsArray;
    }
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
  public function submit(){
    $currentFlag	=	$this->contactHandler->getContactType();
    $this->updateMemcache($currentFlag);
    $this->contactHandler->getContactObj()->setType(ContactHandler::CANCEL_CONTACT);
    $this->contactHandler->getContactObj()->setSEEN(Contacts::NOTSEEN);
    $this->contactHandler->getContactObj()->updateContact();
    $this->handleMessage();
    //print_r($this->contactHandler);
    //    $this->updateContactSeen();//
    $producerObj=new Producer();
    if($producerObj->getRabbitMQServerConnected())
    {
        $sender = $this->contactHandler->getViewer();
        $receiver = $this->contactHandler->getViewed();
        $sendMailData = array('process' =>'MAIL','data'=>array('type' => 'CANCELCONTACT','body'=>array('senderid'=>$sender->getPROFILEID(),'receiverid'=>$receiver->getPROFILEID() ) ), 'redeliveryCount'=>0 );
        $producerObj->sendMessage($sendMailData);
	//Remove from contact roster
      $chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'CANCELCONTACT', 'body' => array('sender' => array('profileid'=>$this->contactHandler->getViewer()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewer()->getPROFILEID()),'username'=>$this->contactHandler->getViewer()->getUSERNAME()), 'receiver' => array('profileid'=>$this->contactHandler->getViewed()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewed()->getPROFILEID()),"username"=>$this->contactHandler->getViewed()->getUSERNAME()))), 'redeliveryCount' => 0);
      $producerObj->sendMessage($chatData);
    }
    else
    {
        $this->sendMail();
    }
    
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
    $this->component->drafts = $this->component->cancelDrafts = $draftsObj->getEoiDrafts();
  }

  /**
   * 
   * Sets Variables to be updated in Memcache while performing action CancelContact
   * 
   * <p>
   * This function sets and updates memcache variables according to the previous status type between sender and receiver
   * </p>
   * 
   * @access private
   * @param $currentFlag
   */
  private function updateMemcache($currentFlag)
  {
    try {
      $ContactTime = strtotime($this->contactHandler->getContactObj()->getTIME());
      $time = time();
      $daysDiff  = floor(($time - $ContactTime)/(3600*24));
      if($currentFlag==ContactHandler::INITIATED)
      {
        $profileMemcacheServiceViewerObj = new ProfileMemcacheService($this->contactHandler->getViewer());
        $profileMemcacheServiceViewedObj = new ProfileMemcacheService($this->contactHandler->getViewed());
        $profileMemcacheServiceViewerObj->update("TOTAL_CONTACTS_MADE",-1);
        $profileMemcacheServiceViewerObj->update("DEC_BY_ME",1);
        $profileMemcacheServiceViewerObj->update("NOT_REP",-1);
        if($daysDiff >= CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT && $daysDiff <= CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT)
        {
          $profileMemcacheServiceViewedObj->update("INTEREST_EXPIRING",-1);
        }
        if($this->contactHandler->getContactObj()->getFILTERED() === Contacts::FILTERED)
			$profileMemcacheServiceViewedObj->update("FILTERED_NEW",-1);
        else        
			$profileMemcacheServiceViewedObj->update("AWAITING_RESPONSE",-1);
        if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
		$profileMemcacheServiceViewedObj->update("AWAITING_RESPONSE_NEW",-1);
        $profileMemcacheServiceViewedObj->update("DEC_ME",1);
        $profileMemcacheServiceViewedObj->update("DEC_ME_NEW",1);
        $profileMemcacheServiceViewerObj->updateMemcache();	
        $profileMemcacheServiceViewedObj->updateMemcache();	
      }
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }

    public function sendMail(){
        $sender = $this->contactHandler->getViewer();
        $receiver = $this->contactHandler->getViewed();
        ContactMailer::sendCancelledMailer($receiver,$sender);
        return true;
    }
}
?>
