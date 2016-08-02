<?php
class JS_Communication
{
	private static $RESULTS_PER_PAGE_CHAT=20;
	private $loginProfile;
	private $otherProfile;
	private $contactObj;
	private $communicationType;
	private $message;
	private $chatID;
	
	public function __construct($loginProfile, $otherProfile,$communicationType,$message,$chatID=0)
	{
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
		//$this->contactObj = new Contacts($this->loginProfile, $this->otherProfile);
		$this->communicationType=$communicationType;
		$this->message=$message;
		$this->chatID=$chatID;
	}

	public function storeCommunication()
	{
		if($this->validateChat()){
			//$type=$this->contactObj->getTYPE();**To be removed**
			$type="A";
			
			$dbName1 = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
			$dbName2 = JsDbSharding::getShardNo($this->otherProfile->getPROFILEID());
			
			$chatIdObj=new NEWJS_CHAT_LOG_GET_ID();
			$id=$chatIdObj->getAutoIncrementMessageId();
			
			if($this->communicationType="C"){
				$dbObj = new newjs_CHAT_LOG($dbName1);
				$dbObj->insertIntoChatLog($id,$this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID(),'N','N',0,$type,'N','U','U',$this->chatID);//sfContext::getInstance()->getRequest()->getParameter("chatID"));
				
				$dbObjMessage = new NEWJS_CHATS($dbName1);
				$dbObjMessage->insertSingleMessage($id,$this->message);
			
				if($dbName1 != $dbName2)
				{							
					$dbObj = new newjs_CHAT_LOG($dbName2);
				
					$dbObj->insertIntoChatLog($id,$this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID(),'N','N',0,$type,'N','U','U',$this->chatID);
				
					$dbObjMessage = new NEWJS_CHATS($dbName2);
					$dbObjMessage->insertSingleMessage($id,$this->message);
				}
				return $id;
			}
		
		}		
	}

	public function getCommunication($msgIdNo)
	{
		$type=$this->contactObj->getTYPE();
		
		$dbName1 = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
		$dbName2 = JsDbSharding::getShardNo($this->otherProfile->getPROFILEID());
		if($this->communicationType="C"){
			$dbObj = new newjs_CHAT_LOG($dbName1);		
			return $dbObj->getMessageHistory($this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID(),self::$RESULTS_PER_PAGE_CHAT,$msgIdNo);
			
			
		/*
			if($dbName1 != $dbName2)
			{							
				$dbObj = new newjs_CHAT_LOG($dbName2);
			
				$dbObj->getMessageHistory($this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID);
			
			}*/
		
		}
		else
		{			
			echo "wrongCommuncicationType";die;
		}
		
		return $id;
		
	}

	public function validateChat()
	{
		if($this->loginProfile->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="FREE")
		{
			$dbName1 = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
			$dbObj = new newjs_CHAT_LOG($dbName1);
			if($dbObj->getChatCount($this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID()))
				return true;
			else
				return false;
		}
		else
			return true;
	}
		
}
