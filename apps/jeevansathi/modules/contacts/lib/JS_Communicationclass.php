<?php
class JS_Communication
{
	private static $RESULTS_PER_PAGE_APP=30;
	private $loginProfile;
	private $otherProfile;
	private $contactObj;
	private $communicationType;
	private $message;
	
	public function __construct($loginProfile, $otherProfile,$communicationType,$message)
	{
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
		$this->contactObj = new Contacts($this->loginProfile, $this->otherProfile);
		$this->communicationType=$communicationType;
		$this->message=$message;
	}

	public function storeCommunication()
	{
		$type=$this->contactObj->getTYPE();
		
		$dbName1 = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
		$dbName2 = JsDbSharding::getShardNo($this->otherProfile->getPROFILEID());
		
		$chatIdObj=new NEWJS_MESSAGE_LOG_GET_ID();
		$id=$chatIdObj->getAutoIncrementMessageId();
		
		if($this->communicationType="C"){
			$dbObj = new newjs_CHAT_LOG($dbName1);		
			$dbObj->insertIntoChatLog($id,$this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID(),'N','N',0,$type,'N','U','U',0);
			
			$dbObjMessage = new newjs_MESSAGES($dbName1);
			$dbObjMessage->insertSingleMessage($id,$this->message);
		
			if($dbName1 != $dbName2)
			{							
				$dbObj = new newjs_CHAT_LOG($dbName2);
			
				$dbObj->insertIntoChatLog($id,$this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID(),'N','N',0,$type,'N','U','U',0);
			
				$dbObjMessage = new newjs_MESSAGES($dbName2);
				$dbObjMessage->insertSingleMessage($id,$message);
			}
		
		}
		else
		{			
			echo "wrongCommuncicationType";die;
		}
		
		return $id;
		
	}

	public function getCommunication()
	{
		$type=$this->contactObj->getTYPE();
		
		$dbName1 = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID());
		$dbName2 = JsDbSharding::getShardNo($this->otherProfile->getPROFILEID());
		
		if($this->communicationType="C"){
			$dbObj = new newjs_CHAT_LOG($dbName1);		
			return $dbObj->getMessageHistory($this->loginProfile->getPROFILEID(),$this->otherProfile->getPROFILEID());
			
			
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


}
