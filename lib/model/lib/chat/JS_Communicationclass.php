<?php
class JS_Communication
{
	public static $RESULTS_PER_PAGE_CHAT= 20;
	private $loginProfile;
	private $otherProfile;
	private $contactObj;
	private $communicationType;
	private $message;
	private $chatID;
	
	public function __construct($loginProfile, $otherProfile,$communicationType,$message,$chatID=0,$ip='',$date)
	{
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
		$this->communicationType=$communicationType;
		$this->message=$message;
		$this->chatID=$chatID;
		$this->ip=$ip;
		$this->date=$date;
	}

	public function storeCommunication()
	{
			//$type=$this->contactObj->getTYPE();**To be removed**
			$type="A";
			
			$dbName1 = JsDbSharding::getShardNo($this->loginProfile);
			$dbName2 = JsDbSharding::getShardNo($this->otherProfile);
			
			$chatIdObj=new NEWJS_CHAT_LOG_GET_ID();
			$id=$chatIdObj->getAutoIncrementMessageId();
			
			if($this->communicationType="C"){
				$dbObj = new newjs_CHAT_LOG($dbName1);
				$dbObj->insertIntoChatLog($id,$this->loginProfile,$this->otherProfile,$type,'N',$this->chatID,$this->ip,$this->date);//sfContext::getInstance()->getRequest()->getParameter("chatID"));
				
				$dbObjMessage = new NEWJS_CHATS($dbName1);
				$dbObjMessage->insertSingleMessage($id,$this->message);
			
				if($dbName1 != $dbName2)
				{							
					$dbObj = new newjs_CHAT_LOG($dbName2);
				
					$dbObj->insertIntoChatLog($id,$this->loginProfile,$this->otherProfile,$type,'N',$this->chatID,$this->ip,$this->date);	
					$dbObjMessage = new NEWJS_CHATS($dbName2);
					$dbObjMessage->insertSingleMessage($id,$this->message);
				}
				return $id;
			}
	}

	public function getCommunication($msgIdNo)
	{
		//$type="A";
		$dbName1 = JsDbSharding::getShardNo($this->loginProfile);
		$dbName2 = JsDbSharding::getShardNo($this->otherProfile);
		if($this->communicationType=="C"){
			$dbObj = new newjs_CHAT_LOG($dbName1);		
			$result= $dbObj->getMessageHistory($this->loginProfile,$this->otherProfile,self::$RESULTS_PER_PAGE_CHAT,$msgIdNo);
			if(count($result)<20)
			{
				$loginProfileObj = new Profile();
				$loginProfileObj->getDetail($this->loginProfile, "PROFILEID", "*");
				
				$otherProfileObj = new Profile();
				$otherProfileObj->getDetail($this->otherProfile, "PROFILEID", "*");

				$this->contactObj = new Contacts($loginProfileObj, $otherProfileObj);
				//$type=$this->contactObj->getTYPE();
				$msgDbObj= new NEWJS_MESSAGE_LOG($dbName1);
				$eoiArray= $msgDbObj->getEOIMessages(array($this->loginProfile),array($this->otherProfile));

				$mergeArray=$eoiArray[0];
				$mergeArray["CHATID"]="";				
				$mergeArray["ID"]="";	
				$messageArr=explode("||",$mergeArray['MESSAGE']);
				$eoiMsgCount = count($messageArr);
				//print_r($messageArr);die;
				$i=count($result);
				for($j=($eoiMsgCount-1);$j>=0;$j--)
				//foreach($messageArr as $key=>$val)
				{
					$mergeArray["MESSAGE"]=$messageArr[$j];
					$mergeArray["IS_EOI"] = true;
					$result[$i]=$mergeArray;
					$i++;
				}
					//print_r($result);die;			
			}		
		}
		else
		{			
			echo "wrongCommuncicationType";die;
		}
		
		return $result;
		
	}

	public function validateChat()
	{
		$loginProfileObj = new Profile();
		$loginProfileObj->getDetail($this->loginProfile, "PROFILEID", "*");
		if($loginProfileObj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="FREE")
		{
			$dbName1 = JsDbSharding::getShardNo($this->loginProfile);
			$dbObj = new newjs_CHAT_LOG($dbName1);
			if($dbObj->getChatCount($this->loginProfile,$this->otherProfile))
				return true;
			else
				return false;
		}
		else
			return true;
	}
		
}
