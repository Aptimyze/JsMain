<?php
/**
 * CLASS WriteMessage
 * <BR>
 * The {@link ContactFactory} uses this class to instantiate it's own <BR>
 * and it's siblings {@link CancelAccept}, {@link Decline}, <BR>
 * {@link Reminder}, {@link CancelContact}, {@link Accept} <BR>
 * and {@link Initiate} class<BR>
 * 
 * This class is responsible to handle WriteMessage event between two users.<BR>
 * @package   jeevansathi
 * @subpackage   contacts
 * @extends ContactEvent {@link ContactEvent}
 * @author Esha Jain <esha.jain@jeevansathi.com>
 */
use MessageQueues as MQ;     //MessageQueues-having values defined for constants used in this class.

class WriteMessage extends ContactEvent{
  /**
   * 
   * Constructor for instantiating object of WriteMessage
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
   * Sets details required to display Pre Component for action WriteMessage
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
		$draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer(),$this->contactHandler->getViewed());
		$privilegeObj=$this->contactHandler->getPrivilegeObj();
		$privArr=$this->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		if($privArr[0]['COMMUNICATION']['MESSAGE'] == 'Y' && !$this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID() )
		{
			$this->component->innerTpl = 'profile_eoi_writeMessage_d';
		}
		if (is_array($draftsObj->getWriteDrafts())) {
			$draftArray = $draftsObj->getWriteDrafts();
			$this->component->drafts = $draftArray;
		$state = $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState();
		if(($state == 'E3' || $state == 'E4') && !$this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPaid())
		{
			$this->component->checkSenderReceiver = true;
		}
		
		
			
			
	}
		

	}

  /**
   * 
   * Submits the form and make database changes
   * 
   * <p>
   * This function updates message logs
   * </p>
   * 
   * @access public
   */
	public function submit(){
		$this->updateMemcache();
                $this->contactHandler->setElement("STATUS","R");
		$this->handleMessage();

//    $this->updateContactSeen();//
		$this->sendMail();
  }
  private function updateMemcache()
  {
    try {
	$profileMemcacheServiceViewedObj = new ProfileMemcacheService($this->contactHandler->getViewed());
	$messageLog = new MessageLog();
	$where             = array(
            "RECEIVER" => $this->contactHandler->getViewed()->getPROFILEID(),
            "SENDER" => $this->contactHandler->getViewer()->getPROFILEID(),
            "TYPE" => 'R',
            "IS_MSG" => 'Y'
        );
    $group = "SEEN";
	$messageCount = $messageLog->getMessageLogCount($where,$group);
	if($messageCount["TOTAL"]==0)
	{
        $profileMemcacheServiceViewedObj->update("MESSAGE",1);
        $profileMemcacheServiceViewerObj = new ProfileMemcacheService($this->contactHandler->getViewer());
				$profileMemcacheServiceViewerObj->update("MESSAGE_ALL",1);
				$profileMemcacheServiceViewerObj->updateMemcache();
				unset($profileMemcacheServiceViewerObj);
  }
    if($messageCount["TOTAL_NEW"]==0)
        $profileMemcacheServiceViewedObj->update("MESSAGE_NEW",1);
  
 
	$profileMemcacheServiceViewedObj->updateMemcache();

    //update redisc memcache for new notifications
      $message = trim($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE));

      $syncRecords[$this->contactHandler->getViewer()->getPROFILEID()]=round(microtime(true) * 1000);
      JsMemcache::getInstance()->setHashObject($this->contactHandler->getViewed()->getPROFILEID()."_lastCommunicationId",$syncRecords,24*60*60);

      $chatNotification[$this->contactHandler->getViewer()->getPROFILEID()."_".$this->contactHandler->getViewed()->getPROFILEID()]=json_encode(array("msg"=>$message,"ip"=>FetchClientIP(),"from"=>$this->contactHandler->getViewer()->getPROFILEID(),"id"=>"","to"=>$this->contactHandler->getViewed()->getPROFILEID()));
      JsMemcache::getInstance()->setHashObject("lastChatMsg",$chatNotification);
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }

  /**
   * 
   * function setPostComponent
   * 
   * <p>
   * This function sets details to create display template after the action WriteMessage is performed. It instantiates PostComponent and sets details required like message to be diplayed, title of page etc in the variable component 
   * </p>
   * 
   * @access public
   */
  public function setPostComponent()
  {
    $this->component	=	new PostComponent;
    $draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer(),$this->contactHandler->getViewed());    
    $this->component->drafts = $draftsObj->getAcceptDrafts('Y');
    $this->component->acceptdrafts = $draftsObj->getAcceptDrafts();
    $this->setPostDrafts($this->component->acceptdrafts);
    $privilegeObj=$this->contactHandler->getPrivilegeObj();
	$privArr=$this->contactHandler->getPrivilegeObj()->getPrivilegeArray();
	if($privArr[0]['COMMUNICATION']['MESSAGE'] == 'Y' )
	{
		$this->component->innerTpl = 'profile_wm_post';
	}
  }
  
  public function sendMail(){
  $sender = $this->contactHandler->getViewer();
	$receiver = $this->contactHandler->getViewed();
	$message = trim($this->contactHandler->getElements(CONTACT_ELEMENTS::MESSAGE));
	if($message)
	{
    // redis key for conversation
    $key = $this->processMessage($sender, $receiver, $message);
    $producerObj=new Producer();
    if($producerObj->getRabbitMQServerConnected())
    {
        $sender = $this->contactHandler->getViewer();
        $receiver = $this->contactHandler->getViewed();
        $sendMailData = array('process' => MQ::WRITE_MSG_Q ,'data'=>array('type' => 'MESSAGE','body'=>array('senderid'=>$sender->getPROFILEID(),'receiverid'=>$receiver->getPROFILEID(),'message'=>$message, 'key'=>$key) ), 'redeliveryCount'=>0 );
        $producerObj->sendMessage($sendMailData);
        $gcmData=array('process'=>'GCM','data'=>array('type'=>'MESSAGE','body'=>array('receiverid'=>$receiver->getPROFILEID(),'senderid'=>$sender->getPROFILEID(),'message'=>$message ) ), 'redeliveryCount'=>0 );
        //$producerObj->sendMessage($gcmData); //Commenting due to notifications being sent through service
        try
        {
          //send instant JSPC/JSMS notification
          if(strlen($message)>BrowserNotificationEnums::$variableMessageLimit["MESSAGE_RECEIVED"])
            $notificationMsg = substr($message,0,BrowserNotificationEnums::$variableMessageLimit["MESSAGE_RECEIVED"])."....";
          else
            $notificationMsg = $message;
          $notificationData = array("notificationKey"=>"MESSAGE_RECEIVED","selfUserId" => $receiver->getPROFILEID(),"otherUserId" => $sender->getPROFILEID(),"message"=>$notificationMsg); 
          $producerObj->sendMessage(formatCRMNotification::mapBufferInstantNotification($notificationData));
        }
        catch(Exception $e)
        {
          throw new jsException("Something went wrong while sending instant message received notification-".$e);
        }
    }
		else
    {
      $mailer = new ContactMailer;
		  $mailer->sendMessageMailer($receiver, $sender,$message);
      // send instant app notification - start
      $instantNotificationObj = new InstantAppNotification("MESSAGE_RECEIVED");
      $senderProfileid = $this->contactHandler->getViewer()->getPROFILEID();
      $receiverProfileid = $this->contactHandler->getViewed()->getPROFILEID();
      //$instantNotificationObj->sendNotification($receiverProfileid, $senderProfileid, $message); //Commenting due to notifications being sent through service
      // send instant app notification - end
    }
	}
  else{
    return true;
  }
  }

  public function processMessage($sender, $receiver, $message)
  {
    $key = "_d_mg_";
    if($sender->getPROFILEID() < $receiver->getPROFILEID())
    {
      $key .= $sender->getPROFILEID().'-'.$receiver->getPROFILEID();
    }
    else
    {
      $key .= $receiver->getPROFILEID().'-'.$sender->getPROFILEID();
    }

    $orgTZ = date_default_timezone_get();
    date_default_timezone_set("Asia/Calcutta");
    $time = time();   

    $msgTime = date("g:i a",$time);
    $formattedMsg = '<strong><TAG>'.$sender->getUSERNAME()."</TAG>, $msgTime: ".'</strong>'.$message;
    $arrValue = array("time"=>time(),"message"=>$formattedMsg,"Receivers"=>$receiver->getPROFILEID(), "sendToBoth" => 0);
    // Key doesnt exists in Memcache
    $data = JsMemcache::getInstance()->getHashAllValue($key);
    if($data)
    {
      $arrValue['message'] = $data['message'].'<br>'.$arrValue['message'];
      if($receiver->getPROFILEID() != $data['Receivers'] && !$data['sendToBoth'])
      {
        $arrValue['sendToBoth'] = 1;
      }
      elseif ($data['sendToBoth'])
      {
        $arrValue['sendToBoth'] = 1;  
      }
    }
    
    JsMemcache::getInstance()->setHashObject($key,$arrValue,3600*6);

    date_default_timezone_set($orgTZ);
    return $key;
  }

}
?>
  
