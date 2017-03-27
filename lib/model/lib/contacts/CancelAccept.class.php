<?php
/**
 * CLASS CancelAccept
 * 
 *<BR>
 * The {@link ContactFactory} uses this class to instantiate it's own <BR>
 * and it's siblings {@link Accept}, {@link Decline}, <BR>
 * {@link Reminder}, {@link CancelContact}, {@link WriteMessage} <BR>
 * and {@link Initiate} class<BR>
 * 
 * This class is responsible to handle Cancel Accept event between two users.<BR>
 *
 * @package jeevansathi
 * @subpackage contacts
 * @extends ContactEvent
 * @author Esha Jain <esha.jain@jeevansathi.com>
 */
class CancelAccept extends ContactEvent{
  /**
   * Constructor for the class
   *
   * <p>
   * Function sets $contactHandler of {@link ContactEvent} class.
   * This also calls the parent constructor of its parent {@link ContactEvent} class.
   * </p>
   *
   * @param {@link ContactHandler} $contactHandler
   * @access public
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
   * Sets details required to display Pre Component for action CancelAccept
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
    $this->component->drafts=$draftsObj->getAcceptDrafts();
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
    $this->contactHandler->getContactObj()->setType(ContactHandler::CANCEL);
    $this->contactHandler->getContactObj()->setSEEN(Contacts::NOTSEEN);
    $this->contactHandler->getContactObj()->updateContact();
    $this->handleMessage();
    //Remove from acceptance roster
    $producerObj=new Producer();
    if($producerObj->getRabbitMQServerConnected())
    {
      $chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'CANCEL', 'body' => array('sender' => array('profileid'=>$this->contactHandler->getViewer()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewer()->getPROFILEID()),'username'=>$this->contactHandler->getViewer()->getUSERNAME()), 'receiver' => array('profileid'=>$this->contactHandler->getViewed()->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->contactHandler->getViewed()->getPROFILEID()),"username"=>$this->contactHandler->getViewed()->getUSERNAME()))), 'redeliveryCount' => 0);
      $producerObj->sendMessage($chatData);
    }

    //    $this->updateContactSeen();
    $this->sendMail();
  }
  /**
   * 
   * function setPostComponent
   * 
   * <p>
   * This function sets details to create display template after the action CancelAccept is performed. It instantiates PostComponent and sets details required like message to be diplayed, title of page etc in the variable component 
   * </p>
   * 
   * @access public
   */
  public function setPostComponent()
  {
    $this->component	=	new PostComponent;
    $draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());
    $this->component->drafts = $draftsObj->getAcceptDrafts();
  }
  /**
   * 
   * Sets Variables to be updated in Memcache while performing action CancelAccept
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
      if($currentFlag==ContactHandler::ACCEPT)
      {
        $profileMemcacheServiceViewerObj = new ProfileMemcacheService($this->contactHandler->getViewer());
        $profileMemcacheServiceViewedObj = new ProfileMemcacheService($this->contactHandler->getViewed());
        $profileMemcacheServiceViewerObj->update("ACC_ME",-1);
        if($this->contactHandler->getContactObj()->getSEEN() == Contacts::NOTSEEN)
		$profileMemcacheServiceViewerObj->update("ACC_ME_NEW",-1);
        $profileMemcacheServiceViewedObj->update("ACC_BY_ME",-1);
        $profileMemcacheServiceViewerObj->update("DEC_BY_ME",1);
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
