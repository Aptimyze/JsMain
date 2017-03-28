<?php
/**
 * The Contacts class contains get, set,insert,update and delete methods for Contacts table
 * Example how to call
 * <code>
 * $obj=new Contacts(Profile $pid1,Profile $pid2);
 * $obj->setType('I');
 * $obj->insert();
 * </code>
 * @package jeevansathi
 * @subpackage contacts
 * @author Rohit Khandelwal
 */
class Contacts {


	/**
	 *
	 * This variable holds the contactid value of the Contacts table.
	 * @access private
	 * @var integer
	 */
	private $CONTACTID;
	/**
	 *
	 * Holds the type of contact b/w sender and receiver.
	 * @access private
	 * @var string
	 */
	private $TYPE;
	/**
	 * holds the time of contact.
	 *
	 * @access private
	 * @var TimeDate
	 */
	private $TIME;
	/**
	 *
	 * This is used to store the frequency of the contact between sender and reciever.
	 * @access private
	 * @var int
	 */
	private $COUNT;
	/**
	 *
	 * Not in use now.
	 * @access private
	 * @var string
	 */
	private $MSG_DEL;
	/**
	 *
	 * Value is Y if EOI has been viewed by Reciver.
	 * @access private
	 * @var string
	 */
	private $SEEN;
	/**
	 *
	 * value is Y if the EOI goes into filtered folder.
	 * @access private
	 * @var string
	 */
	private $FILTERED;
	/**
	 *
	 * This variable is used to store the folder name.
	 * @access private
	 * @var unknown_type
	 */
	private $FOLDER;
	/**
	 *
	 * Profile Object for Sender.
	 * @access private
	 * @var Profile
	 */
	private $senderObj;
	/**
	 *
	 * Profile Object for Receiver
	 * @access private
	 * @var Profile
	 */
	private $receiverObj;
	/**
	 *
	 * This holds the contact records for sender and reciever if exists in Contacts table.
	 * @access private
	 * @var array
	 */
	private $contactRecord;
	/**
	 *
	 * Shard db for Sender.
	 * @access private
	 * @var string
	 */
	private $dbSender;
	/**
	 *
	 * Database object of Contacts table for Sender.
	 * @access private
	 * @var Object
	 */
	private $dbObjSender;
	/**
	 *
	 * Shard db for Receiver.
	 * @access private
	 * @var string
	 */
	private $dbReceiver;
	/**
	 *
	 * Database object of Contacts table for Receiver.
	 * @access private
	 * @var Object
	 */
	private $dbObjReceiver;
	const SEEN = 'Y';
	const NOTSEEN = 'N';
	const NOTSEEN_BLANK = '';
	const FILTERED = 'Y';
	const NOTFILTERED = 'N';
	const NOTFILTERED_BLANK = '';
	const TYPEDEFAULT="N";
	const CONTACTID_ERROR = "ContactId is not correct in contacts obj";
	const SENDER_ERROR = "Sender is not correct in contacts obj";
	const RECEIVER_ERROR = "Receiver is not correct in contacts obj";
	const TYPE_ERROR = "Type is not correct in contacts obj";
	const PROFILE_ERROR = "Object is not profile obj";
	const FILTER_ERROR = "Filter value in not correct in contacts obj";
	const SEEN_ERROR = "Seen value is not correct in contacts obj";
	const CONTACT_TYPE_CACHE_EXPIRY = 86400; //seconds
	const EXPIRING_INTEREST_UPPER_LIMIT = 90;
	const EXPIRING_INTEREST_LOWER_LIMIT = 84;
	/**
	 *
	 * Constructor for initializing object of Contacts class
	 * @param Profile $profileObj1
	 * @param Profile $profileObj2
	 * @throws jsException
	 */
	public function __construct($profileObj1,$profileObj2)
	{
		if(!($profileObj1 instanceof Profile) || !($profileObj2 instanceof Profile))
			throw new jsException("",Contacts::PROFILE_ERROR);

		$this->_setDefault($profileObj1,$profileObj2);
	}
	/*************************************Getter/Setter*****************************************************/
	/**
	 *
	 *
	 * @param int $CONTACTID
	 * @return void
	 * @access public
	 */
	public function setCONTACTID($CONTACTID)
	{
		if($CONTACTID)
			$this->CONTACTID = $CONTACTID;
		else
			throw new jsException("",Contacts::CONTACTID_ERROR);
	}
	/**
	 *
	 * @return int
	 * @access public
	 */
	public function getCONTACTID()
	{
		return $this->CONTACTID;
	}
	/**
	 *
	 * @param string TYPE
	 * @return void
	 * @access public
	 */
	public function setTYPE($TYPE)
	{
		if(in_array($TYPE,array('A','I','E','C','D','N')))
			$this->TYPE = $TYPE;
		else
			throw new jsException("",Contacts::TYPE_ERROR);
	}
	/**
	 * returns the type of contact between Sender and Reciever.
	 * @return string
	 * @access public
	 */
	public function getTYPE()
	{
		return $this->TYPE;
	}
	/**
	 *
	 * @param datetime TIME
	 * @return void
	 * @access public
	 */
	public function setPageSource($pageSource)
	{
			$this->pageSource = $pageSource;
		
	}
	/**
	 * returns the type of contact between Sender and Reciever.
	 * @return string
	 * @access public
	 */
	public function getPageSource()
	{
		return $this->pageSource;
	}
	/**
	 *
	 * @param datetime TIME
	 * @return void
	 * @access public
	 */


	public function setTIME($TIME)
	{
		$this->TIME = $TIME;
	}
	/**
	 *
	 * @return datetime
	 * @access public
	 */
	public function getTIME()
	{
		return $this->TIME;
	}
	/**
	 *
	 * @param integer COUNT
	 * @return void
	 * @access public
	 */
	public function setCOUNT($COUNT)
	{
		$this->COUNT = $COUNT;
	}
	/**
	 *returns the count of contacts between Sender and Reciever.
	 * @return int
	 * @access public
	 */
	public function getCOUNT()
	{
		return $this->COUNT;
	}
	/**
	 *
	 * @param string $MSG_DEL
	 * @return void
	 * @access public
	 */
	public function setMSG_DEL($MSG_DEL)
	{
		$this->MSG_DEL = $MSG_DEL;
	}
	/**
	 *
	 * @return string
	 * @access public
	 */
	public function getMSG_DEL()
	{
		return $this->MSG_DEL;
	}
	/**
	 *
	 * @param string SEEN
	 * @return void
	 * @access public
	 */
	public function setSEEN($SEEN)
	{
		if(in_array($SEEN, array(Contacts::SEEN,Contacts::NOTSEEN,Contacts::NOTSEEN_BLANK)))
			$this->SEEN = $SEEN;
		else
			throw new jsException("",Contacts::SEEN_ERROR);
	}
	/**
	 *
	 * @return string
	 * @access public
	 */
	public function getSEEN()
	{
		return $this->SEEN;
	}
	/**
	 *
	 * @param string FILTERED
	 * @return void
	 * @access public
	 */
	public function setFILTERED($FILTERED)
	{
		if(in_array($FILTERED,array(Contacts::FILTERED,Contacts::NOTFILTERED,Contacts::NOTFILTERED_BLANK)))
			$this->FILTERED = $FILTERED;
		else
			throw new jsException("",Contacts::FILTER_ERROR);
	}
	/**
	 * returns the value of FILTERED,Y if contact is filtered otherwise 'N' or ''.
	 * @return string
	 * @access public
	 */
	public function getFILTERED()
	{
		return $this->FILTERED;
	}
	/**
	 * set the FOLDER.
	 * @param string $FOLDER
	 * @return void
	 * @access public
	 */
	public function setFOLDER($FOLDER)
	{
		$this->FOLDER = $FOLDER;
	}
	/**
	 * returns the Folder.
	 * @return string
	 * @access public
	 */
	public function getFOLDER()
	{
		return $this->FOLDER;
	}
	/**
	 * set the senderObj
	 * @param Profile profileObj
	 * @return void
	 * @access public
	 */
	public function setSenderObj( $profileObj )
	{
		$this->senderObj=$profileObj;
	}
	/**
	 * set the receiverObj
	 * @param Profile profileObj
	 * @return void
	 * @access public
	 */
	public function setReceiverObj( $profileObj )
	{
		$this->receiverObj=$profileObj;
	}
	/**
	 * returns the sender object
	 * @return Profile senderObj
	 * @access public
	 */
	public function getSenderObj()
	{
		return $this->senderObj;
	}
	/**
	 * returns the recieverObj
	 * @return Profile
	 * @access public
	 */
	public function getReceiverObj()
	{
		return $this->receiverObj;
	}
	/**
	 *Set default values for all the setter.
	 *
	 * @param Profile profileObj1
	 * @param Profile profileObj2
	 * @return void
	 * @access private
	 * @uses setSenderObj()
	 * @uses setReciverObj()
	 * @uses $contactRecord
	 * @uses setContactID()
	 * @uses setTYPE()
	 * @uses setCOUNT()
	 * @uses setMSG_DEL()
	 * @uses setFILTERED()
	 * @uses setSEEN()
	 */
	private function _setDefault($profileObj1,$profileObj2)
	{
		$this->setSenderObj($profileObj1);
		$this->setReceiverObj($profileObj2);

		$contactRecordsObj = new ContactsRecords();
		$this->contactRecord = $contactRecordsObj->getContactRecords($profileObj1->getPROFILEID(),$profileObj2->getPROFILEID());
		if(is_array($this->contactRecord))
		{
			if($this->contactRecord['RECEIVER']==$profileObj1->getPROFILEID())
			{
				$this->setSenderObj($profileObj2);
				$this->setReceiverObj($profileObj1);
			}
			$this->setCONTACTID($this->contactRecord['CONTACTID']);
			$this->setTYPE($this->contactRecord['TYPE']);
			$this->setCOUNT($this->contactRecord['COUNT']);
			$this->setTIME($this->contactRecord['TIME']);
			$this->setMSG_DEL($this->contactRecord['MSG_DEL']);
			$this->setFILTERED($this->contactRecord['FILTERED']);
			$this->setFOLDER($this->contactRecord['FOLDER']);
			if($this->contactRecord['SEEN'])
				$this->setSEEN($this->contactRecord['SEEN']);
			else
				$this->setSEEN(Contacts::NOTSEEN);
		}
		else    
			$this->setTYPE(Contacts::TYPEDEFAULT);
                        
                self::setContactsTypeCache($this->getSenderObj()->getPROFILEID(), $this->getReceiverObj()->getPROFILEID(), $this->getTYPE());

                        
	}

	/****************************************************************************************************/

	/**
	 * Make the shard connection and create db object for Sender and Receiver
	 * @return void
	 * @uses $dbSender
	 * @uses $dbReciever
	 * @uses $dbObjSender
	 * @uses $dbObjReciver
	 *
	 */
	public function getShardConnection()
	{
		$this->dbSender = JsDbSharding::getShardNo($this->getSenderObj()->getPROFILEID(),'');
		$this->dbObjSender = new newjs_CONTACTS($this->dbSender);
		$this->dbReceiver = JsDbSharding::getShardNo($this->getReceiverObj()->getPROFILEID(),'');
		$this->dbObjReceiver = new newjs_CONTACTS($this->dbReceiver);
	}
	/**
	 * Update contact b/w Sender and Receiver in Contacts Table.
	 * @return void
	 * @uses $dbSender
	 * @uses $dbReciever
	 * @uses $dbObjSender
	 * @uses $dbObjReciver
	 * @uses setTIME()
	 * @uses getShardConnection()
	 * @access public
	 *
	 */
	public function updateContact()
	{
		$this->setTIME(date("Y-m-d H:i:s"));
		$this->setFOLDER($this->GetFolderId());
		$result = false;
		if(JsConstants::$webServiceFlag == 1) {
			$result = true;
			$contactRecordsObj = new ContactsRecords();
			$result = $contactRecordsObj->update($this);
		}
		if($result == false) {
			$this->getShardConnection();
			$success=$this->dbObjSender->update($this);
			if(!$success)
				$success=$this->dbObjSender->update($this);
			if($this->dbSender!=$this->dbReceiver && $success)
				$this->dbObjReceiver->update($this);
                        if($success)
                            Contacts::setContactsTypeCache($this->getSenderObj()->getPROFILEID(), $this->getReceiverObj()->getPROFILEID(), $this->getTYPE());
		}
	}
	/**
	 * Insert Contact b/w Sender and Receiver in Contacts Table.
	 * @return void
	 * @uses $dbObjSender
	 * @uses $dbSender
	 * @uses $dbReceiver
	 * @uses $dbObjReceiver
	 * @uses setCONTACTID()
	 * @uses setTIME()
	 * @uses getShardConnection()
	 * @uses newjs_CONTACTS_GET_ID::generateId()
	 * @access public
	 *
	 */
	public function insertContact()
	{
		$dbContactGetId = new newjs_CONTACTS_GET_ID();
		$this->setCONTACTID($dbContactGetId->getAutoIncrementMessageId());
		//$dbContactGetId->delete();
		$this->setFOLDER($this->GetFolderId());

		$this->setTIME(date("Y-m-d H:i:s"));

		$this->setSEEN('N');
		$this->setCOUNT(1);
		$result = false;
		if(JsConstants::$webServiceFlag == 1 ){
			$result = true;
			$contactRecordsObj = new ContactsRecords();
			$result = $contactRecordsObj->insert($this);
		}
		if($result == false) {
			$this->getShardConnection();
			$success=$this->dbObjSender->insert($this);
			if(!$success)
				$success=$this->dbObjSender->insert($this);
			if($this->dbSender!=$this->dbReceiver && $success)
			{
                                $success = $this->dbObjReceiver->insert($this);
				if(!$success)
				{
                                    $success = $this->dbObjReceiver->insert($this); 
					if(!$success)
						$this->dbObjSender->delete($this);
				}

			}
                        if($success)
                                Contacts::setContactsTypeCache($this->getSenderObj()->getPROFILEID(), $this->getReceiverObj()->getPROFILEID(), $this->getTYPE());                            
		}

	}
	/**
	 * Delete Contact b/w Sender and Receiver from Contacts Table.
	 * @return void
	 * @uses $dbObjSender
	 * @uses $dbSender
	 * @uses $dbReceiver
	 * @uses $dbObjReceiver
	 * @access public
	 */
	public function deleteContact()
	{
		$result = false;
		if(JsConstants::$webServiceFlag == 1 ) {
			$result = true;
			$contactRecordsObj = new ContactsRecords();
			$result = $contactRecordsObj->delete($this);
		}
		if($result == false) {
			$this->getShardConnection();
			$success = $this->dbObjSender->delete($this);
			if (!success)
				$success = $this->dbObjSender->delete($this);
			if ($this->dbSender != $this->dbReceiver && $success)
				$this->dbObjReceiver->delete($this);
		}
	}

	/*
	 * Update folder, required by ap users
	 */
	public function GetFolderId()
	{

		$sender_profileid=$this->senderObj->getPROFILEID();
		$receiver_profileid=$this->receiverObj->getPROFILEID();
		$type=$this->getTYPE();
		$filtered=$this->getFILTERED();
		$recSub=$this->receiverObj->getSUBSCRIPTION();
		$senSub=$this->senderObj->getSUBSCRIPTION();

		$recSubArr=array();
		$senSubArr=array();
		if($recSub)
			$recSubArr=explode(",",$recSub);
		if($senSub)
			$senSubArr=explode(",",$senSub);

		//Only for Profile dispatcher
		if(in_array("L",$senSubArr) )
			$senderAA=1;
		if(in_array("L",$recSubArr))
			$recAA=1;

		if($senderAA || $recAA)
		{
			if($type=='I')
			{
				if($recAA)
				{
					if($filtered)
						$folder='FIL';
					else
						$check='SL';
				}
				else
				{
					if($recSub)
					{
						if(in_array("D",$recSubArr))
							$check='SL';
					}
				}
				if($check=='SL')
				{
					$rowCall=$this->getIntroCallHistory1();
					if($rowCall)
					{
						if($rowCall["CALL_STATUS"]=="Y")
							$folder='';
						else
							$folder='SL';
					}
					else
						$folder='SL';
				}
			}
			elseif($type=='A')
			{

				if($this->getFOLDER())
					$folder='';
				else
				{

					if($this->getFOLDER())
						$folder='';
					else
					{
						$rowCall=$this->getIntroCallHistory1();
						if($rowCall)
						{
							if($rowCall["CALL_STATUS"]=="Y")
								$folder='';
							else
								$folder='SL';
						}
						else
							$folder='SL';
					}
				}
			}
			elseif($type=='D' || $type=='C' || $type=="E")
			{
				$check=0;

				if($this->getFOLDER()=='TBD')
				{
					$rowCall=$this->getIntroCallHistory1();
					if($rowCall)
					{
						if($rowCall["CALL_STATUS"]=="Y")
							$folder='';
						else
							$folder='blank';
					}
					else
						$folder='blank';
				}
				elseif($this->getFOLDER()=="DIS")
					$folder='';
				else
				{

					if($this->getFOLDER())
					{
						$rowCall=$this->getIntroCallHistory1();
						if($rowCall)
						{
							if($rowCall["CALL_STATUS"]=="Y")
								$folder='';
							else
								$folder='blank';
						}
						else
							$folder='blank';
					}
					elseif($this->getFOLDER()=="DIS")
						$folder='';
					else
						$folder='blank';
				}
			}
			return $folder;

		}
		else
			return '';

	}
	/*
	 * returns intro ap_call_hitory log
	 */

	public function getIntroCallHistory1()
	{
		$sender_profileid=$this->senderObj->getPROFILEID();
		$receiver_profileid=$this->receiverObj->getPROFILEID();
		$fields="*";
		if($sender_profileid && $receiver_profileid)
		{
			$apCallObj=new ASSISTED_PRODUCT_AP_CALL_HISTORY;

			return $apCallObj->Fetch($sender_profileid,$receiver_profileid);

		}
		else
			return '';
	}
        // profileId1 is sender and profileid2 receiver of interest
        public static function setContactsTypeCache($profileId1,$profileId2,$type){
            if(!$profileId1 || !$profileId2 || !$type)return false;
            $sortedArray = $profileId1 > $profileId2 ? array($profileId2,$profileId1) : array($profileId1,$profileId2); 
            $smallIsWho = $sortedArray[0] == $profileId1 ? 'S' : 'R';
            $result = $type."_".$smallIsWho;
            JsMemcache::getInstance()->setRedisKey($sortedArray[0].'_'.$sortedArray[1].'_contactType',$result,self::CONTACT_TYPE_CACHE_EXPIRY);
            return $result;
            
        }
        public static function unSetContactsTypeCache($profileId1,$profileId2){
            if(!$profileId1 || !$profileId2)return false;
            $sortedArray = $profileId1 > $profileId2 ? array($profileId2,$profileId1) : array($profileId1,$profileId2); 
            JsMemcache::getInstance()->delete($sortedArray[0].'_'.$sortedArray[1].'_contactType');
            return true;
            
        }
        public static function getContactsTypeCache($profileId1,$profileId2)
        {
            if(!$profileId1 || !$profileId2)return false;
            $sortedArray = $profileId1 > $profileId2 ? array($profileId2,$profileId1) : array($profileId1,$profileId2); 
            $result = JsMemcache::getInstance()->getRedisKey($sortedArray[0].'_'.$sortedArray[1].'_contactType');
            
            if(!$result)
                {
				$ignoreObj = new IgnoredProfiles();
                                $whoignored = $ignoreObj->ifIgnored($profileId1,$profileId2)? 1 :($ignoreObj->ifIgnored($profileId2,$profileId1) ? 2 : 0);
				if($whoignored)
                                {
                                       $type='B';
                                       $result = ($whoIgnored == 1) ? self::setContactsTypeCache($profileId1, $profileId2, $type) : self::setContactsTypeCache($profileId1, $profileId2, $type);
                                }
				else
				{	 
                                $shardNo = JsDbSharding::getShardNo($profileId1);
                                $dbObj = new newjs_CONTACTS($shardNo);
                                $resArray = $dbObj->getContactRecord($profileId1, $profileId2);
                                $type = $resArray['TYPE'] ? $resArray['TYPE'] : 'N' ;
                                if($resArray)
                                    $result = self::setContactsTypeCache($resArray['SENDER'], $resArray['RECEIVER'], $type);
                                else
                                    $result = self::setContactsTypeCache($profileId1, $profileId2, $type);

                                }
            
                }
     return $result;
        
    }

    /**
	 * checks if message is obscene
	 * @return boolean
	 */
	public function isObscene($message)
	{
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
    }
}
?>
