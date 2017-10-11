<?php

 /**
 * MessageCommunication class handles the insertion of Contact logs into
 * Message_Log table, Messages table and Obscene_Message_Log table
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Hemant.a
 * 
 */
 
 
class MessageCommunication
{
	/**
   *
   * This holds the sender profile id
   *
   * @access private
   * @var integer
   */
	private $SENDER;
	
   /**
   *
   * This holds the receiver profile id
   *
   * @access private
   * @var integer
   */
	private $RECEIVER;
	
   /**
   *
   * This holds the action that will be performed
   *
   * @access private
   * @var string
   */
	private $TYPE;
	
	/**
   *
   * This holds the is_msg to track if message is written
   *
   * @access private
   * @var string
   */
	private $IS_MSG;
	
	/**
   *
   * This holds the id value auto generated to be inserted in message log table
   *
   * @access private
   * @var integer
   */
	private $ID;
	
	/**
   *
   * This holds the OBSCENE message flag
   *
   * @access private
   * @var string
   */
	private $OBSCENE;
	
   /**
   *
   * This holds the obscene msg id
   *
   * @access private
   * @var integer
   */
	private $MSG_OBS_ID;
	
	/**
   *
   * This holds the date on which action performed
   *
   * @access private
   * @var datetime
   */
	private $DATE;
	
	/**
   *
   * This holds the ip from which action is performed
   *
   * @access private
   * @var string
   */
	private $IP;
	
	/**
   *
   * This holds the message communicated
   *
   * @access private
   * @var string
   */
	private $MESSAGE;
	
   /**
   *
   * This holds the if anyone needs to be marked cc
   *
   * @access private
   * @var string
   */
	private $MARKCC;
	
  /**
   *
   * This holds if userid is blocked
   *
   * @access private
   * @var string
   */
	private $BLOCKED;
	
	/**
   *
   * This holds the user value
   *
   * @access private
   * @var string
   */
	private $USER;
	
   /**
   *
   * This holds the date edit
   *
   * @access private
   * @var datetime
   */
	private $DATE_EDIT;
	
	/**
   *
   * This holds sender status
   *
   * @access private
   * @var string
   */
	private $SENDER_STATUS;
	
   /**
   *
   * This holds receiver status
   *
   * @access private
   * @var string
   */
	private $RECEIVER_STATUS;
	
   /**
   *
   * This holds if the contact has been seen
   *
   * @access private
   * @var string
   */
	private $SEEN;
	
   /**
   *
   * This holds the folderid
   *
   * @access private
   * @var integer
   */
	private $FOLDERID;
	
	
	private $UPDATE;
	
	
	const YES = 'Y';
	const NO = 'N';
	const UNREAD = 'U';
	const TEMPORARY = 'T';
	const ZERO = 0;
	const MESSAGE= 'R';
	const DATE_TIME = 'Y-m-d H:i:s';
	const NIL = '';
	const TYPE_MISMATCH = "type not found";
	const OBS_MSG_ID_ERROR = "Obscene Message ID Can not be Null";
	
	
	 /**
         * 
         * Constructor function
         * @param $contactHandlerObj - contact Handler object 
       */
	function __construct($contactHandlerObj='',$profileid='')
	{
		if($contactHandlerObj)
		{
			$this->contactHandlerObj = $contactHandlerObj;
			$this->setDefaults();
		}
		else
		{
			$this->SENDER = $profileid;
			$messagePostParameters = sfContext::getInstance()->getRequest()->getPostParameters()?sfContext::getInstance()->getRequest()->getPostParameters():sfContext::getInstance()->getRequest()->getGetParameters();
				$appVersion=sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION")?sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION"):0; 
			if(MobileCommon::isAPP()=="A" && $appVersion>=100)
			{
				$this->ID = sfContext::getInstance()->getRequest()->getParameter("messageid");
				$this->MESSAGE =htmlentities(urldecode(sfContext::getInstance()->getRequest()->getParameter("draft")))?htmlentities(urldecode(sfContext::getInstance()->getRequest()->getParameter("draft"))):htmlentities($_GET["chatMessage"]);
			}
			else
			{
				$this->ID = $messagePostParameters["messageid"]?$messagePostParameters["messageid"]:$_GET["messageid"];
				$this->MESSAGE = htmlentities($messagePostParameters["draft"])?htmlentities($messagePostParameters["draft"]):htmlentities($_GET["chatMessage"]);			
			}
			$this->setValue();
			$this->IS_MSG = MessageCommunication::YES;	
			$this->UPDATE = true;
		}
	}
	
	/***************************** Getter Setter      **************************/
	/*
	 * return array of contact
	 *@return Array
	 */
	private function getTypeArray() 
	{
		return array('I','A','R','D','C','E');
	}
	
	/**
	 * sets ID
	 * @param integer $ID
	 * @access public
	 * 
	 */
	public function setID($ID)
	{
		if($ID)
			$this->ID = $ID;
		else
			throw new JSException("",Messages::MESSAGE_ID_ERROR);
	}
	
	/**
	 * gets ID value
	 * 
	 * @access public
	 * @return integer
	 */
	public function getID()
	{
		return $this->ID;
	}
	
	
	/**
	 * sets receiver value
	 * @param integer $RECEIVER
	 * @access public
	 * 
	 */
	public function setRECEIVER($RECEIVER)
	{
		$this->RECEIVER = $RECEIVER;
	}
	
	/**
	 * gets receiver value
	 * 
	 * @access public
	 * @return integer
	 */
	public function getRECEIVER()
	{
		return $this->RECEIVER;
	}
	
	/**
	 * sets sender value
	 * @param integer $SENDER

	 * @access public
	 * 
	 */
	
	public function setSENDER($SENDER)
	{
		$this->SENDER = $SENDER;
	}
	
	/**
	 * gets sender value
	 * 
	 * @access public
	 * @return integer
	 */
	public function getSENDER()
	{
		return $this->SENDER;
	}
	
	/**
	 * sets is_msg value
	 * @param integer $IS_MSG
	 * @access public
	 * 
	 */
	public function setIS_MSG($IS_MSG)
	{
		$this->IS_MSG = $IS_MSG;
	}
	
	/**
	 * gets is_msg value
	 * 
	 * @access public
	 * @return integer
	 */
	public function getIS_MSG()
	{
		return $this->IS_MSG;
	}
	
	
	/**
	 * sets obs_msg_id value
	 * @param integer $OBS_MSG_ID
	 * @access public
	 * 
	 */
	public function setOBS_MSG_ID($OBS_MSG_ID)
	{
		if(isset($OBS_MSG_ID))
			$this->OBS_MSG_ID = $OBS_MSG_ID;
		else
			throw new JsException("",MessageCommunication::OBS_MSG_ID_ERROR);
	}
	
	/**
	 * gets obs_msg_id value
	 * 
	 * @access public
	 * @return integer
	 */
	public function getOBS_MSG_ID()
	{
		return $this->OBS_MSG_ID;
	}
	
	
	/**
	 * sets date value	 
	 * @param date $DATE 
	 * @access public
	 */
	public function setDATE($DATE)
	{
		$this->DATE = $DATE;
	}
	
	/**
	 * gets date value
	 * 
	 * @access public
	 * @return date
	 */
	public function getDATE()
	{
		return $this->DATE;
	}
	
	/** sets IP value
	 * @param string $IP
	 * @access public
	 */
	public function setIP($IP)
	{
		$this->IP = $IP;
	}
	
	/**
	 * gets IP value
	 * @access public
	 * @return string
	 */
	public function getIP()
	{
		return $this->IP;
	}
	
	/**
	 * sets type value
	 * @param string $TYPE
	 * @access public
	 */
	public function setTYPE($TYPE)
	{
		if(in_array($TYPE,$this->getTypeArray()))
			$this->TYPE = $TYPE;
		else
			throw new JsException("",MessageCommunication::TYPE_MISMATCH);
	}
	
	/**
	 * gets type value
	 * 
	 * @access public
	 * @return string
	 */
	public function getTYPE()
	{
		return $this->TYPE;
	}
	
	/**
	 * sets obscene value
	 * @param string $OBSCENE
	 * @access public
	 */
	public function setOBSCENE($OBSCENE)
	{
		$this->OBSCENE = $OBSCENE;
	}
	
	/**
	 * gets obscene value
	 * 
	 * @access public
	 * @return string
	 */
	public function getOBSCENE()
	{
		return $this->OBSCENE;
	}
	
	
	/**
	 * sets message value
	 * @param string $MESSAGE
	 * @access public
	 */
	public function setMESSAGE($MESSAGE)
	{
		$this->MESSAGE = $MESSAGE;
	}
	
	/**
	 * gets message value
	 * 
	 * @access public
	 * @return string
	 */
	public function getMESSAGE()
	{
		return 	html_entity_decode($this->MESSAGE,ENT_QUOTES);
	}
	
	
	/**
	 * sets user value
	 * @param string $USER
	 * @access public
	 */
	public function setUSER($USER)
	{
		$this->USER = $USER;
	}
	
	/**
	 * gets user value
	 * 
	 * @access public
	 * @return string
	 */
	public function getUSER()
	{
    if(is_null($this->USER)) {
      $this->USER = "";
    }
		return $this->USER;
	}
	
	
	/**
	 * sets date_edit value
	 * @param datetime $DATE_EDIT 
	 * @access public
	 */
	public function setDATE_EDIT($DATE_EDIT)
	{
		$this->DATE_EDIT = $DATE_EDIT;
	}
	
	/**
	 * gets date_edit value
	 * 
	 * @access public
	 * @return string
	 */	
	public function getDATE_EDIT()
	{
    if(is_null($this->DATE_EDIT)) {
      $this->DATE_EDIT = "0000-00-00 00:00:00";
    }
    
		return $this->DATE_EDIT;
	}
	
	
	/**
	 * sets blocked value
	 * @param enum $BLOCKED 
	 * @access public
	 */
	public function setBLOCKED($BLOCKED)
	{
		$this->BLOCKED = $BLOCKED;
	}
	
	/**
	 * gets blocked value
	 * 
	 * @access public
	 * @return string
	 */
	public function getBLOCKED()
	{
    if(is_null($this->BLOCKED)) {
      $this->BLOCKED = MessageCommunication::TEMPORARY;
    }
		return $this->BLOCKED;
	}
	
	/**
	 * sets markcc value
	 * @param string $MARKCC 
	 * @access public
	 */
	public function setMARKCC($MARKCC)
	{
		$this->MARKCC = $MARKCC;
	}
	
	/**
	 * gets markcc value
	 * 
	 * @access public
	 * @return string
	 */
	public function getMARKCC()
	{ 
    if(is_null($this->MARKCC) || 0 === strlen($this->MARKCC)) {
      //use default config
      $this->MARKCC = MessageCommunication::NO;
    }
    
		return $this->MARKCC;
	}
	
	
	/**
	 * sets receiver_status value
	 * @param string $RECEIVER_STATUS 
	 * @access public
	 */
	public function setRECEIVER_STATUS($RECEIVER_STATUS)
	{
		$this->RECEIVER_STATUS = $RECEIVER_STATUS;
	}
	
	/**
	 * gets receiver_status value
	 * 
	 * @access public
	 * @return string
	 */
	public function getRECEIVER_STATUS()
	{
		return $this->RECEIVER_STATUS;
	}
	
	
	/**
	 * sets sender_status value
	 * @param string $SENDER_STATUS 
	 * @access public
	 */
	public function setSENDER_STATUS($SENDER_STATUS)
	{
		$this->SENDER_STATUS = $SENDER_STATUS;
	}
	
	/**
	 * gets sender_status value
	 * 
	 * @access public
	 * @return string
	 */
	public function getSENDER_STATUS()
	{
		return $this->SENDER_STATUS;
	}
	
	/**
	 * sets folderid value
	 * @param integer $FOLDERID 
	 * @access public
	 */
	public function setFOLDERID($FOLDERID)
	{
		$this->FOLDERID = $FOLDERID;
	}
	
	/**
	 * gets folder_id value
	 * 
	 * @access public
	 * @return string
	 */
	public function getFOLDERID()
	{
		return $this->FOLDERID;
	}
	
	/**
	 * sets seen value
	 * @param string $SEEN 
	 * @access public
	 */
	public function setSEEN($SEEN)
	{
		$this->SEEN = $SEEN;
	}
	
	/**
	 * gets SEEN value
	 * 
	 * @access public
	 * @return string
	 */
	public function getSEEN()
	{
		return $this->SEEN;
	}
	
	public function setUPDATE($UPDATE)
	{
		$this->UPDATE = $UPDATE;
	}
	public function getUPDATE()
	{
		return $this->UPDATE;
	}
	/**
	 * sets default values to be inserted into message tables
	 * 
	 * @access public
	 * 
	 */
	public function setDefaults()
	{
		if(!$this->getID())
		{
			$this->setSENDER($this->contactHandlerObj["VIEWER"]);
			$this->setRECEIVER($this->contactHandlerObj["VIEWED"]);
			
			// logic for generated id
			$dbObj = new newjs_MESSAGE_LOG_GET_ID();
			$generatedId = $dbObj->getAutoIncrementMessageId();
			$this->setID($generatedId);
			//$dbObj->deleteGenerateID();
			$this->setIS_MSG(MessageCommunication::NO) ;
			$this->setOBSCENE(MessageCommunication::NO);
			$this->setOBS_MSG_ID(MessageCommunication::ZERO);
			$this->setSENDER_STATUS(MessageCommunication::UNREAD);
			$this->setRECEIVER_STATUS(MessageCommunication::UNREAD);
			$this->setFOLDERID(MessageCommunication::ZERO);
			$this->setSEEN(MessageCommunication::NO);
			$this->setMARKCC(MessageCommunication::NO);
			$this->setBLOCKED(MessageCommunication::TEMPORARY);
			$this->setUSER(MessageCommunication::NIL);
			$this->setDATE_EDIT(MessageCommunication::NIL);
			
			$date=date(MessageCommunication::DATE_TIME);
			$this->setDATE($date);
			
			$ip = CommonFunction::getIP();
                        if(!$ip) $ip = JsConstants::$localHostIp;
			$this->setIP($ip);
			
			$custMessage = $this->contactHandlerObj["MESSAGE"];
			if($custMessage)
			{
				$this->setIS_MSG(MessageCommunication::YES);
				$customMessage = addslashes(stripslashes(htmlspecialchars($custMessage,ENT_QUOTES)));
				$this->setMESSAGE($customMessage);
			}
			else
			{
				$this->setMESSAGE(MessageCommunication::NIL);
			}
			
			$type = $this->contactHandlerObj["STATUS"];
			if($type)
			{
				$this->setTYPE($type);
			}
			else
			{
				$this->setTYPE(MessageCommunication::MESSAGE) ;
			}
		}
	}
	
	
	
	/**
	 * inserts data into message_log and obscene_message_log tables
	 * 
	 * @access public
	 * 
	 */
	
	public function insertMessage()
	{
			
		if($this->obsceneMessage())
		{
			$this->setObscene(MessageCommunication::YES);
			$this->setIS_MSG(MessageCommunication::YES);
      
			$obsceneId = $this->insertIntoObsceneMessage();
			
			//set message as blank if it is obscene
			$this->setMESSAGE(MessageCommunication::NIL);
			$this->setOBS_MSG_ID($obsceneId);
			$this->insertIntoMessageLog();
		}
		else
		{
			$this->insertIntoMessageLog();
		}
		
	}
	
	
	/**
	 * inserts data into obscene_message_log tables
	 * 
	 * @access public
	 * 
	 */
	
	function insertIntoObsceneMessage()
	{
		$dbObj = new newjs_OBSCENE_MESSAGE();
		$obsceneId = $dbObj->updateObsceneMessage($this);
		return $obsceneId;
	}
	
	/**
	 * inserts data into message_log  tables
	 * 
	 * @access public
	 * 
	 */
	function insertIntoMessageLog()
	{
		$dbName1 = JsDbSharding::getShardNo($this->getSENDER());
		$dbName2 = JsDbSharding::getShardNo($this->getRECEIVER());
		
		$dbObj = new newjs_MESSAGE_LOG($dbName1);
		if($this->getUPDATE())
			$dbObj->updateMessageLogDetails($this);
		else
			$dbObj->updateMessageLog($this);
		if($this->getIS_MSG() == MessageCommunication::YES)
		{
			$dbObjMessage = new newjs_MESSAGES($dbName1);
			if($this->getUPDATE())
				$dbObjMessage->updateMessagesValue($this);
			else
				$dbObjMessage->updateMessages($this);
		}
		
		if($dbName1 != $dbName2)
		{
			$dbObj = new newjs_MESSAGE_LOG($dbName2);
			if($this->getUPDATE())
				$dbObj->updateMessageLogDetails($this);
			else
				$dbObj->updateMessageLog($this);
			if($this->getIS_MSG() == MessageCommunication::YES)
			{
				$dbObjMessage = new newjs_MESSAGES($dbName2);
				if($this->getUPDATE())
				$dbObjMessage->updateMessagesValue($this);
			else
				$dbObjMessage->updateMessages($this);
			}
		}
	}
	
	/**
	 * checks if message is obscene
	 * @access public
	 * 
	 * @return boolean
	 */	
	 
	function obsceneMessage()
	{
		
		$message = $this->getMESSAGE();
		$ip = $this->getIP();		
		$dbObj = new newjs_OBSCENE_WORDS();
		$obscene = $dbObj->getObsceneWord();
		$messageArr = explode(" ",$message);
		foreach($obscene as $index=>$value)
		{
			foreach($messageArr as $k=>$messWord){
				$messWord = preg_replace('/\s+/', '', $messWord);
				if(strtolower($messWord) == strtolower($value))
				{
					return true;
				}
			}
		}
		
		$suspected = CommonFunction::suspectedIP($ip);
		
		if($suspected)
		{
			return true;	
		}
		return false;
	}
	
	public function setValue()
	{
		$dbName1 = JsDbSharding::getShardNo($this->getSENDER());
		$dbObj = new newjs_MESSAGE_LOG($dbName1);
		$value = $dbObj->getMessageLogDetails($this->getID());
		$this->setRECEIVER($value["RECEIVER"]);
		$this->setDATE($value["DATE"]);
		$this->setIP($value["IP"]);
		$this->setRECEIVER_STATUS($value["RECEIVER_STATUS"]);
		$this->setFOLDERID($value["FOLDERID"]);
		$this->setOBS_MSG_ID($value["MSG_OBS_ID"]);
		$this->setSENDER_STATUS($value["SENDER_STATUS"]);
		$this->setTYPE($value["TYPE"]);
		$this->setOBSCENE($value["OBSCENE"]);
		$this->setIS_MSG($value["IS_MSG"]);
		$this->setSEEN($value["SEEN"]);
	}
}

?>
