<?php
/**
 * CLASS Reminder
 *<BR>
 * The {@link ContactFactory} uses this class to instantiate it's own <BR>
 * and it's siblings {@link CancelAccept}, {@link Decline}, <BR>
 * {@link Accept}, {@link CancelContact}, {@link WriteMessage} <BR>
 * and {@link Initiate} class<BR>
 * 
 * This class is responsible to handle sending Reminder event between two users.<BR>
 * @package   jeevansathi
 * @subpackage   contacts
 * @extends ContactEvent {@link ContactEvent}
 * @author Esha Jain <esha.jain@jeevansathi.com>
 */
class Reminder extends ContactEvent {

  /**
   * Variable to store draft message for reminder mailer.
   *
   * @access private
   * @var string
   */

  private $_reminderDraft;
  /**
   * 
   * Constructor for instantiating object of Reminder class
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
   * Sets details required to display Pre Component for action Reminder
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
    if (is_array($draftsObj->getEoiDrafts())) {
      $this->component->drafts = $this->component->reminderDrafts = $draftsObj->getEoiDrafts();
    }
  }


  /**
   * 
   * Submits the form and make database changes
   * 
   * <p>
   * This function updates memcache
   * </p>
   * 
   * @access public
   */
  public function submit()
  {
    $errorArray = $this->errorHandlerObj->getAllError();

    if (is_array($errorArray) && ((in_array(ErrorHandler::FILTERED, $errorArray) !== false))) {
      $this->contactHandler->getContactObj()->setFILTERED(Contacts::FILTERED);
    }
    else {
      $this->contactHandler->getContactObj()->setFILTERED(Contacts::NOTFILTERED_BLANK);
    }
    
    $this->contactHandler->getContactObj()->setCOUNT($this->contactHandler->getContactObj()->getCOUNT()+1);
    $this->contactHandler->getContactObj()->updateContact();
    $this->contactHandler->setElement("STATUS","I");
    $this->handleMessage();
    $sendMailNot = $this->contactHandler->getElements('MAIL_AND_NOT') == 'N' ? false : true;
    
    $filteredState =$this->contactHandler->getContactObj()->getFILTERED();	

    if($filteredState!='Y' && $filteredState!='J' && $sendMailNot)
    {	
      $receiverId = $this->contactHandler->getViewed()->getPROFILEID();
      $senderId = $this->contactHandler->getViewer()->getPROFILEID();

      $notificationMsg = $this->getNotificationMessage($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE));

      // send the notification to app  
      $instantNotificationObj =new InstantAppNotification("EOI_REMINDER");
      $instantNotificationObj->sendNotification($receiverId,$senderId,$notificationMsg); 
      unset($instantNotificationObj);
      
      try
      {
        //send instant JSPC/JSMS notification
        
        $notificationData = array("notificationKey"=>"EOI_REMINDER","selfUserId" => $receiverId,"otherUserId" => $senderId,"message"=>$notificationMsg);
        
        $producerObj = new Producer(); 
        if($producerObj->getRabbitMQServerConnected())
        {
          $producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
        }
        unset($producerObj);
      }
      catch(Exception $e)
      {
        throw new jsException("Something went wrong while sending instant eoi reminder received notification-".$e);
      }
    } 

//    $viewedEntryDate = $this->contactHandler->getViewed()->getENTRY_DT();
//    $now = date("Y-m-d");
//    $dateDiff = abs(date("U", JSstrToTime($now)) - date("U", JSstrToTime($viewedEntryDate))) / 86400;
 // Instant mailer
    if($sendMailNot)
      $this->sendMail();
    
  }
  
  /**
   * This method is used to test if the string received is present or not. 
   * If not, a fallback is set as a hardcoded string which will be sent 
   * to the user
   * 
   * @param type String $message - Contains the notification message which needs to be validated
   * @return type String - Final message that will be sent to the user
   */
  
  private function getNotificationMessage($message) {
      
        // if the reminder message is present, set the limit to the message
        return ($message)? $this->getLimitedNotificationMessage($message) 
        // otherwise, a fallback is defined in case the reminder message is missing, this fallback is hardcoded  
                : $this->contactHandler->getViewer()->getUSERNAME().BrowserNotificationEnums::$EOINotificationReminderMsg;
  }

  /**
   * This method is used to set the limit to the string received as input.
   * The limit is present in the Enum file of Browser Notification Enum file.
   * @param type String $message - Input String where limit is set to the message
   * @return type String $message - Limited string which will be sent as notification message to the user
   */
  
  private function getLimitedNotificationMessage($message) {
      
        if(strlen($message)>BrowserNotificationEnums::$variableMessageLimit["EOI_REMINDER"]) {
                return substr($message,0,BrowserNotificationEnums::$variableMessageLimit["EOI_REMINDER"])."....";
        }
        return $message;
  }
  /**
   * 
   * function setPostComponent
   * 
   * <p>
   * This function sets details to create display template after the action Reminder is performed. It instantiates PostComponent and sets details required like message to be diplayed, title of page etc in the variable component 
   * </p>
   * 
   * @access public
   */
  public function setPostComponent()
  {
    $this->component	=	new PostComponent;
    $draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());
    if (is_array($draftsObj->getEoiDrafts())) {
      $this->component->drafts = $this->component->reminderDrafts = $draftsObj->getEoiDrafts();
      $this->setPostDrafts($this->component->drafts);
    }
  }
  
  /**
   * 
   */
  public function sendMail(){   
    $viewed = $this->contactHandler->getViewed();
    $viewer = $this->contactHandler->getViewer();
    
    $viewedSubscriptionStatus = $viewed->getPROFILE_STATE()->getPaymentStates()->isPaid();
    $this->_setReminderMailerDraft(stripslashes(htmlspecialchars($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE), ENT_QUOTES)));
    
    $producerObj=new Producer();
    if($producerObj->getRabbitMQServerConnected())
    {
      $sendMailData = array('process' =>MessageQueues::DELAYED_MAIL_PROCESS ,'data'=>array('type' => 'REMINDERCONTACT','body'=>array('senderid'=>$viewer->getPROFILEID(),'receiverid'=>$viewed->getPROFILEID(),'message'=>$this->_getReminderMailerDraft(), 'viewedSubscriptionStatus'=> $viewedSubscriptionStatus) ), 'redeliveryCount'=>0 );
      $producerObj->sendMessage($sendMailData);
    } else {
      ContactMailer::InstantReminderMailer($viewed->getPROFILEID(), $viewer->getPROFILEID(), $this->_getReminderMailerDraft(), $viewedSubscriptionStatus);
    }    
  }

  private function _setReminderMailerDraft($draft) {
    if ($draft) {
      $this->_reminderDraft = $draft;
    }
    else {
      $this->_reminderDraft = " ";
    }
  }

  private function _getReminderMailerDraft() {
    return $this->_reminderDraft;
  }

}
