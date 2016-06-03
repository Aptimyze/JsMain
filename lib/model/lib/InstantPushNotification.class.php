<?php

class InstantPushNotification {
	
	private $notificationKey;
	private $profileid;
	private $otherProfileId;
	private $profileDetails = array();
	private $otherProfileDetails = array();
	private $smsSettings = array();
	private $varArray = array();
	private $errorMessage = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";
	private $unverified_key = array("REGISTER_RESPONSE" ,"PHONE_UNVERIFY");
	private $customCriteria=0;
	private $otherProfileRequired = array("EOI_APP","ACCEPT_APP","PHOTO_REQUEST_APP");
	private $notificationLimitDaily = array(EOI_APP	=>3,PHOTO_REQUEST_APP=>1);
	function __construct ($notificationKey, $profileid, $varArray=array(),$otherProfileId="") {
		
		$this->notificationKey = $notificationKey;
		$this->profileid = $profileid;
		if($otherProfileId)
			$this->otherProfileId = $otherProfileId;
		if($varArray) $this->varArray = $varArray;
		include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
		$this->SMSLib = new SMSLib("I");
		$this->inLimit = $this->checkLimit();
	}
	
	private function checkLimit()
	{
		if(in_array($this->notificationKey,array_keys($notificationLimitDaily)))
		{
			$date = date("Y-m-d");
			$sql = "SELECT * FROM MOBILE_API.GCM_NOTIFICATION_LOG WHERE PROFILEID='".$this->profileid."' AND `KEY`='".$this->notificationKey."' AND ADD_DATE BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59'";
			$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
			$num_rows = mysql_num_rows($res);
			if($notificationLimitDaily[$this->notificationKey]>=3)
				return false;
		}
		return true;
	}
	
	private function setProfileDetails() 
	{
		$sql = "SELECT PROFILEID, GENDER, USERNAME, PASSWORD, SUBSCRIPTION, PHONE_MOB, CASTE, DTOFBIRTH, MSTATUS, MTONGUE,  MOB_STATUS,EMAIL, SEC_SOURCE,CITY_RES,PINCODE FROM newjs.JPROFILE WHERE PROFILEID = '$this->profileid' and SERVICE_MESSAGES!='U'";
		$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
		$row = mysql_fetch_assoc($res);
		$this->profileDetails = $row;
		if($this->varArray)
			$this->profileDetails = array_merge($this->profileDetails,$this->varArray);
	}	
		
	private function getMessage () 
	{
		$DEFAULT = "A";
		$DEFAULT_SUBSCRIPTION = "A";
		$DEFAULT_GENDER = "A";
		$SUBSCRIPTION = "A";
		if($this->profileDetails["SUBSCRIPTION"] != "")
			$SUBSCRIPTION = "P";
		else 
			$SUBSCRIPTION = "F";
		$GENDER = $this->profileDetails["GENDER"];
		$sql = "SELECT MESSAGE, GENDER, SUBSCRIPTION, TIME_CRITERIA, CUSTOM_CRITERIA,SMS_TYPE from newjs.SMS_TYPE WHERE SMS_TYPE IN ('I','MI') and (SUBSCRIPTION LIKE \"%".$SUBSCRIPTION."%\" OR SUBSCRIPTION = \"".$DEFAULT_SUBSCRIPTION."\") and GENDER IN ( \"".$GENDER."\", \"".$DEFAULT_GENDER."\") and  STATUS = 'Y' and SMS_KEY = '".$this->smsKey."'";
		if(in_array($this->customCriteria,array("0","1")))
			$sql.=" AND CUSTOM_CRITERIA IN ('".$this->customCriteria."')";
		$sql.=";";
		$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
		$row=mysql_fetch_array($res);
		if($row["MESSAGE"]){
			$this->smsSettings = $row;
			$this->setSendTime();
		}
		return $row["MESSAGE"];
	}

	private function setSendTime(){
		$sendTime = "";
		if($this->smsSettings["TIME_CRITERIA"]){
			$orgTZ = date_default_timezone_get();
			date_default_timezone_set("Asia/Calcutta");
			$sendTimeStamp = time()+$this->smsSettings["TIME_CRITERIA"]*60;
			$sendTime=date("Y-m-d H:i:s",$sendTimeStamp);			
			date_default_timezone_set($orgTZ);
		}
		$this->smsSettings["SEND_TIME"] = $sendTime;
	}
	
	private function setOtherProfile() {
				
		$sql = "SELECT PROFILEID, GENDER, USERNAME, PASSWORD,AGE,HEIGHT, SUBSCRIPTION, PHONE_MOB, CASTE, DTOFBIRTH, MSTATUS, MTONGUE,  MOB_STATUS,EDU_LEVEL,INCOME,OCCUPATION,CITY_RES,EMAIL, SEC_SOURCE,EDU_LEVEL_NEW  FROM newjs.JPROFILE WHERE PROFILEID = '$this->otherProfileId'";
		$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
		$row = mysql_fetch_assoc($res);
		// set ProfileDetails for the given ProfileID
		$this->otherProfileDetails["DATA"] = $row;		
		
	}
	
	private function getActualMessage ($message) {
		
		$mLength = strlen($message);
		$messageToken = "";
		$startToken = 0;
		$actualMessage = "";
		$this->smsKey;
		if(in_array($this->smsKey,$this->otherProfileRequired))
		{
			$this->setOtherProfile();
			$this->otherProfileDetails["RECEIVER"]["USERNAME"] = $this->profileDetails["USERNAME"];
			$this->otherProfileDetails["RECEIVER"]["PROFILEID"] = $this->profileDetails["PROFILEID"];
			$this->otherProfileDetails["DATA_TYPE"] = "OTHER";
		}
		for ($i = 0; $i < $mLength; $i++) {
			
			if ($message[$i] != '{' && $message[$i] != '}' && !$startToken) $actualMessage .= $message[$i];
			
			else if ($message[$i] == '{') {
				$startToken = 1;
				continue;
			}
			else if ($message[$i] == '}') { 
				if(in_array($this->smsKey,$this->otherProfileRequired))
					$actualMessage .= $this->SMSLib->getTokenValue($messageToken,$this->otherProfileDetails);
				else
					$actualMessage .= $this->SMSLib->getTokenValue($messageToken,$this->profileDetails);
					
				$messageToken = "";
				$startToken = 0;
			}	
			if ($startToken) $messageToken .= $message[$i];
		}	
		return $actualMessage;
	}	

	//Returns sms text	
	private function getSMS () {
		
		$this->setProfileDetails();
		$message = "";
			$message = $this->getMessage();
			$message = $this->getActualMessage($message);
		return $message;
		
	}	

	public function sendNotification()
	{
		if($this->inLimit==false)
			return;
		$message = $this->getSMS();
		$profileDetails[$this->profileid]['price']=$message;
		$profileDetails[$this->profileid]['SMS_TYPE']=$this->smsSettings['SMS_TYPE'];
		$profileDetails[$this->profileid]['SMS_KEY']=$this->smsKey;
		$profileDetails[$this->profileid]['MESSAGE']=$message;
		$gcmSenderObj = new GCMSender;
		$gcmSenderObj->sendNotifications($profileDetails);
	}
}	
?>
