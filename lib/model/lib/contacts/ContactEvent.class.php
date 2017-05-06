<?php
/**
 * CLASS ContactEvent
 * <p>Super class of {@link Accept}, {@link Decline}, <BR>
 * {@link CancelAccept}, {@link Reminder}, <BR>
 * {@link CancelContact}, {@link WriteMessage} <BR>
 * and {@link Initiate} class<BR>
 * This class decide first checks for errors, then perform pre or post action according to the phase of the event.</p>
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    Tanu Gupta <tanu.gupta@jeevansathi.com>
 * @copyright 2012 Tanu Gupta
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
 */
abstract class ContactEvent {

  /**#@+
   * @access protected
   */
  /**
   * This holds the instance of ErrorHandler class.
   *
   * @var Object
   */
  public  $errorHandlerObj;
  /**
   * This holds the instance of ContactHandler class.
   *
   * @var Object
   */
  public $contactHandler;
  /**
   * This holds the instance of one of the classes {@link PreComponent}, {@link PostComponent}, {@link ErrorComponent}, and contains the details of the template to be displayed on performing the requested action.
   *
   * @var Object
   */
  public $component;
  /**#@-*/
  
  /**
   * 
   * This holds the information of the page to redirect in case of Decline interest.
   * @var String
   */
  public $toPage;
  
  public $messageId;

  /**
   * Constructor for this class.
   *
   * <p>
   * Function to check for the error present using {@link ErrorHandler} member variables for this class.
   * This also calls the parent constructor of {@link ContactEvent} class.
   * </p>
   *
   * @param ContactHandler $contactHandler
   * @access public
   */
  public function __construct(){
    //To be used in JSException class
    Messages::setCeCalled();

    $this->errorHandlerObj = new ErrorHandler($this->contactHandler);
    
    $error = $this->errorHandlerObj->checkError();
		
    //print_r($error);die;
    if(!$error )
    {
      if($message = $this->errorHandlerObj->getErrorMessage())
      {
        $this->setErrorComponent($message);
  //      $this->setPreComponent();
      }
    }

    elseif($this->contactHandler->getAction()==ContactHandler::PRE) {
      $this->setPreComponent();
    }
    elseif($this->contactHandler->getAction()==ContactHandler::POST) {
      $this->submit();
      $this->setPostComponent();
	 
	  $this->unsetSkipProfilesMemcache();
    }
    else
      throw new jsException("","actionFlag of contactHandler is not set");

    $this->component->genderPronoun = ($this->contactHandler->getViewed()->getGENDER() === 'F') ? "she" : "he";
    $this->component->genderAddress = ($this->contactHandler->getViewed()->getGENDER() === 'F') ? "her" : "his";
    if(!isset($this->component->innerTpl))
	    $this->setTemplateName();
    if(!isset($this->component->layoutTpl))
	    $this->setLayoutName();
  }

  /**
   * 
   * Sets template name for the profile form the table
   * 
   * <p>
   * It instantiates CONTACT_ENGINE_TEMPLATE_NAME store class which fetches the template name to be displayed.
   * </p>
   * 
   * @access protected
   */
  protected function setTemplateName()
  {
	$profileState		=	$this->getProfileStatus() 	;
	//$templateNameObj	=	new CONTACT_ENGINE_TEMPLATE_NAME();
	$templateNameObj = new ContactsTemplate();
	$contactInitiator	=	(($this->contactHandler->getContactInitiator()==ContactHandler::SENDER)?"S":"R");
        if($this->component->errorMessage!="" && sfContext::getInstance()->getRequest()->getParameter("fmBack")!=1)
	{
		if($this->contactHandler->getEngineType()==ContactHandler::INFO)
		{
			$this->component->innerTpl	="profile_cd_error";
		}
		else
		{
			if (MobileCommon::isMobile()) {
				$this->component->innerTpl = "profile_mobile_eoi_error";
			}
			else {
				$this->component->innerTpl	= "profile_eoi_error";
			}
		}
	}
	else
	{
		$pageSource=$this->contactHandler->getPageSource();
		$tobestatus=$this->contactHandler->getToBeType();
		if($this->contactHandler->getEngineType()=="INFO")
		{
				$tobestatus="";
				$pageSource="";
		}	
		if($this->contactHandler->getPageSource()=="VDP" || $this->contactHandler->getPageSource()=="MOBILE"){
	        	$this->component->innerTpl      =       $templateNameObj->getTemplateName($this->contactHandler->getContactType(),$profileState,$tobestatus,$this->contactHandler->getEngineType(),$pageSource,$contactInitiator,$this->contactHandler->getAction(),$this->component->checkSenderReceiver);
		}
        	else
	        	$this->component->innerTpl      =       $templateNameObj->getTemplateName($this->contactHandler->getContactType(),$profileState,$tobestatus,$this->contactHandler->getEngineType(),"",$contactInitiator,$this->contactHandler->getAction());
	        	
	        //echo 	$this->component->innerTpl='profile_cd_eir';
	//	echo "0 ".$this->contactHandler->getContactType()."1 ".$profileState."2 ".$tobestatus."3 ".$this->contactHandler->getEngineType()."4 ".$contactInitiator."5 ".$this->contactHandler->getAction();
		if(!$this->component->innerTpl) $this->component->innerTpl = 'profile_eoi_default';
	}
  }

  /**
   * 
   * Sets layout name for the profile form the table
   * 
   * <p>
   * It sets the name of the layout basis whether it is to view contact details or on an action like eoi, accept etc
   * </p>
   * 
   * @access protected
   */
  protected function setLayoutName()
  {
        $this->component->layoutTpl = "layout_dp";
        if($this->contactHandler->getEngineType()=="INFO")
        $this->component->layoutTpl = "layout_dp_cd";
  }

  /**
   * 
   * Returns Profile Status for template
   * 
   * <p>
   * checks if the profile is incomplete or paid or fto or free
   * </p>
   * 
   * @access private
   * @param $message
   */
  protected function getProfileStatus()
  {
    if($this->contactHandler->getViewer()->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()=='Y')
      $profileState = "IU";
    elseif($this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID()===true)
      $profileState = FTOSubStateTypes::PAID;
    elseif($this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState()!='' || $this->contactHandler->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()=="Y")
     {
		$profileState = $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState();
		$ftoState = $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getState();
		$ftoSubState = $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getSubState();
		if((in_array($ftoState,array(FTOStateTypes::FTO_ELIGIBLE, FTOStateTypes::FTO_ACTIVE)) || $ftoSubState == FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT) && $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag()=="I" && $this->contactHandler->getContactInitiator()==ContactHandler::RECEIVER)
		{
			$profileState=FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED;//"E1";
		}
		if($ftoSubState == FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT && $this->contactHandler->getViewer()->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag()=="T" && $this->contactHandler->getContactInitiator()==ContactHandler::RECEIVER)
		{
			$profileState=FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD;
		}
			
      }
    else
      $profileState = FTOSubStateTypes::NEVER_EXPOSED;
    return $profileState;
  }

  /**
   * 
   * Sets error message and other details in case Error template needs to be displayed
   * 
   * <p>
   * It instantiates ErrorComponent and sets the details required to display error template.
   * </p>
   * 
   * @access private
   * @param $message
   */
  private function setErrorComponent($message)
  {
    $this->component=new ErrorComponent;//handles all the error messages to be displayed
    $this->component->errorMessage=$message;


  }
  /**
   * 
   * getter of variable component
   * 
   * @access public
   */

  public function getComponent()
  {
    return $this->component;
  }
  /**
   * 
   * Sets details required to display Pre Component
   * 
   * @abstract
   * @access protected
   */
  abstract protected function setPreComponent();
  /**
   * 
   * Sets details required to display Post Component
   * 
   * @abstract
   * @access protected
   */
  abstract protected function setPostComponent();
  /**
   * 
   * perform submit action, updates memcache and contacts table
   * 
   * @abstract
   * @access protected
   */
  abstract protected function submit();

  public function sendMail(){
  }

  public function maintainLog(){
  }

  public function updateProfileSeen(){
  }
	/**
 * Updates message_log table.
 */
  public function handleMessage() {
	$messageHandler["MESSAGE"]=$this->contactHandler->getElements("MESSAGE");
	$messageHandler["VIEWER"] = $this->contactHandler->getViewer()->getPROFILEID();
	$messageHandler["VIEWED"] = $this->contactHandler->getViewed()->getPROFILEID();
	$messageHandler["STATUS"] = $this->contactHandler->getElements(CONTACT_ELEMENTS::STATUS);
    $message_communication_obj = new MessageCommunication($messageHandler);
    $message_communication_obj->insertMessage();
    $this->contactHandler->setElement(CONTACT_ELEMENTS::MESSAGE,$message_communication_obj->getMessage());
	$this->messageId = $message_communication_obj->getID();
    

  }
  	/**
 * Update component with drafts
 * @param Array $drafts 
 */
  public function setPostDrafts($drafts)
  {
  	$whatToDo = CommonFunction::checkDraftOverflow($this->contactHandler->getElements("MESSAGE"),$drafts);
    $this->component->SaveMessage=0;
    $this->component->overflow = 0;
    $this->component->draftMessage=$this->contactHandler->getElements("MESSAGE");
    if($whatToDo==1)
    {
		$this->component->overflow = 1;
		$this->component->SaveMessage=1;
    }
    if($whatToDo==2)
    {
    	$this->component->SaveMessage=1;
    }
  }


	/**
	 * This function added By Reshu
	 * Remove skip profiles Cache on any contact action 
	 */
	private function unsetSkipProfilesMemcache()
	{
		$viewerProfile = $this->contactHandler->getViewer()->getPROFILEID();
		$memcacheServiceObj = new ProfileMemcacheService($viewerProfile);
		$memcacheServiceObj->unsetSKIP_PROFILES();

    $viewedProfile = $this->contactHandler->getViewed()->getPROFILEID();
    $memcacheServiceViewedObj = new ProfileMemcacheService($viewedProfile);
    $memcacheServiceViewedObj->unsetSKIP_PROFILES();
    $memcacheServiceObj->updateMemcache();
	}
}
?>
