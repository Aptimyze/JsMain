<?php

class InstantSMS {
	
	private $smsKey;
	private $profileid;
	private $otherProfileId;
	private $profileDetails = array();
	private $otherProfileDetails = array();
	private $smsSettings = array();
	private $varArray = array();
	private $smsTypeIgnoreTimeRange = array("DETAIL_CONFIRM","FORGOT_PASSWORD","PAYMENT_MEMBERSHIP","VIEWED_CONTACT_SMS","FIELD_VISIT_SCHEDULE","OTP","DEL_OTP","MEM_REN_ACT_CRON","MEM_BACK_DISC_SMS","CRM_SMS_BRANCH","CRM_SMS_OFFER","CRM_SMS_NOT_REACH","CRM_SMS_APP_DOWNLOAD","REQ_CRM_DEL_SELF","REQ_CRM_DEL_OTHER","REPORT_INVALID");
	private $errorMessage = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";
	private $unverified_key = array("REGISTER_RESPONSE" ,"PHONE_UNVERIFY");
	private $customCriteria=0;
	private $settingIndependent = array("FORGOT_PASSWORD","VIEWED_CONTACT_SMS","OTP", "PHONE_UNVERIFY","DEL_OTP","REQ_CRM_DEL_SELF","REQ_CRM_DEL_OTHER","REPORT_INVALID");
	private $sendToInternational = array("FORGOT_PASSWORD");
	private $eoiSMSLimit = 2;
	private $otherProfileRequired = array("INSTANT_EOI","ACCEPTANCE_VIEWED","ACCEPTANCE_VIEWER","VIEWED_CONTACT_SMS","HOROSCOPE_REQUEST","REPORT_INVALID");
	private $kycCity = array("DE00", "UP25", "UP06", "RA07", "UP47", "UP12");
	private $kycLocality = "";//Comma separated
	
	function __construct ($smsKey, $profileid, $varArray=array(),$otherProfileId="") {
		
		$this->smsKey = $smsKey;
		$this->profileid = $profileid;
		if($otherProfileId)
			$this->otherProfileId = $otherProfileId;
		if($varArray) $this->varArray = $varArray;
		include_once(JsConstants::$docRoot."/classes/SMSLib.class.php");
		$this->SMSLib = new SMSLib("I");
	}
	
	private function setProfileDetails() {
				
		$sql = "SELECT EMAIL,JPROFILE.PROFILEID, GENDER, USERNAME, PASSWORD, SUBSCRIPTION, PHONE_MOB, CASTE, DTOFBIRTH, MSTATUS, MTONGUE,  MOB_STATUS,EMAIL, SEC_SOURCE,CITY_RES,COUNTRY_RES,PINCODE,ISD FROM newjs.JPROFILE LEFT JOIN newjs.JPROFILE_ALERTS ON JPROFILE.PROFILEID=JPROFILE_ALERTS.PROFILEID WHERE JPROFILE.PROFILEID = '$this->profileid'";
		if(!in_array($this->smsKey,$this->settingIndependent))
			$sql.=" and  (JPROFILE_ALERTS.SERVICE_SMS !=  'U' OR JPROFILE_ALERTS.SERVICE_SMS IS NULL)";
		if($this->smsKey != "REGISTER_CONFIRM")
			$sql.=" and JPROFILE.activatedKey=1";
		
		$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
		$row = mysql_fetch_assoc($res);

		// set ProfileDetails for the given ProfileID
		$this->profileDetails = $row;
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
		$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($this->profileid);
		foreach($ftoStateArray as $k=>$v)
			$this->profileDetails[$k] = $v;
		if($this->varArray["SANAME"] && $this->smsKey == "AGENT_KYC"){
			$sql = "SELECT FIRST_NAME,PHONE FROM jsadmin.PSWRDS WHERE USERNAME = '".$this->varArray["SANAME"]."'";
			$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
			while($row = mysql_fetch_assoc($res)){
				$this->varArray["SANAME"] = $row["FIRST_NAME"];
				$this->varArray["SAPHONE"] = $row["PHONE"];
			}
		}
                if($this->varArray) 
                    $this->profileDetails = array_merge($this->profileDetails,$this->varArray);
//		print_r($this->profileDetails);
		
	}	
	private function inDNC() {
		$db = connect_dnc();	
		$sql = "SELECT PHONE from DNC.DNC_LIST where PHONE = \"". $this->profileDetails["PHONE_MOB"]. "\";";
		$res = mysql_query($sql,$db) or logError($this->error,$sql,"ShowErrTemplate");
		$num_rows = mysql_num_rows($res);
		if ($num_rows == 0) return false;
		return true;
	}	
		
	private function isWhitelistedProfile() {
		if($this->smsKey=='OTP') return true;
		if($this->smsKey=="DEL_OTP") return true;
		if($this->smsKey=="MEM_BACK_DISC_SMS") return true;
		if($this->smsKey=="CRM_SMS_OFFER") return true;
		if($this->smsKey=="CRM_SMS_BRANCH") return true;
		if($this->smsKey=="CRM_SMS_APP_DOWNLOAD") return true;
		if($this->smsKey=="CRM_SMS_NOT_REACH") return true;
		if($this->smsKey=='PHONE_UNVERIFY') return true;
		if($this->smsKey=='REQ_CRM_DEL_SELF') return true;
		if($this->smsKey=='REQ_CRM_DEL_OTHER') return true;
		if($this->smsKey == 'REPORT_INVALID') return true;
		
		$sendToInt = in_array($this->smsKey, $this->sendToInternational);
		if(!$sendToInt && !$this->SMSLib->getMobileCorrectFormat($this->profileDetails["PHONE_MOB"],$this->profileDetails["ISD"], $sendToInt))
			return false;
		switch ($this->smsKey) {
			
			case "REGISTER_CONFIRM":
				return !$this->inDNC();
				break;
			
			case "FORGOT_PASSWORD":
				return ($this->profileDetails["MOB_STATUS"] == 'Y');
			
			case "PAYMENT_PHONE_VERIFY":
				$whitelisted=false;
				if($this->profileDetails["MOB_STATUS"] != 'Y'){
					include_once $this->SMSLib->path."/ivr/jsivrFunctions.php";
					$phoneStatus=chkDuplicatePhone($this->profileDetails["PHONE_MOB"],"M",$this->profileDetails["PROFILEID"]);
					if(substr($phoneStatus,0,1)=="U") $whitelisted=true;
				}
				return $whitelisted;
			case "PROFILE_APPROVE":
				$this->customCriteria=(in_array($this->profileDetails["SEC_SOURCE"],array('M','C')))?"0":"1";//0 for sugar profiles and 1 for non sugar profiles
				return $this->profileDetails["MOB_STATUS"] == 'Y';
			case "INSTANT_EOI":
				$sql = "SELECT COUNT(*) AS COUNT FROM SMS_DETAIL WHERE SMS_KEY = 'INSTANT_EOI' AND PROFILEID = '".$this->profileid."'";
				$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
				$row = mysql_fetch_assoc($res);
				if($row["COUNT"]<$this->eoiSMSLimit)
					return $this->profileDetails["MOB_STATUS"] == 'Y';
				else
					return false;
			case "REGISTER_KYC":
				if($this->kycLocality){
					$sql = "SELECT PINCODE FROM newjs.PINCODE_MAPPING WHERE LOCALITY IN ('".$this->kycLocality."')";
					$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
					while($row = mysql_fetch_assoc($res)){
						$pincode[] = $row["PINCODE"];
					}
				}
                                //if((in_array($this->profileDetails["CITY_RES"],$this->kycCity)) || (in_array($this->profileDetails["PINCODE"],$pincode)))
                                if(in_array($this->profileDetails["CITY_RES"],$this->kycCity))
                                        return $this->profileDetails["MOB_STATUS"] == 'Y';
				return false;
                        case "AGENT_KYC":
				if($this->profileDetails["SANAME"] && $this->profileDetails["SAPHONE"]) return true;
				return false;
				
				case "FIELD_VISIT_SCHEDULE":
					return ($this->profileDetails["MOB_STATUS"] == 'Y');


				 case "OTP":
				 return true;

					 case "DEL_OTP":
				 		return true;

				 //added case for sending sms to a user in case mail gets bounced
				 case "BOUNCED_MAILS":
				 	return true;

				 case "REQ_CRM_DEL_SELF":
				 	return true;

				 case "REQ_CRM_DEL_OTHER":
				 	return true;

				 case "REPORT_INVALID":
				 	return true;	

			default:
				return $this->profileDetails["MOB_STATUS"] == 'Y';
		}		
	}		
	
	private function getMessage () {
		
		$DEFAULT_SUBSCRIPTION = "A";
		$DEFAULT_GENDER = "A";
		
		$SUBSCRIPTION = "A";
		if ($this->profileDetails["SUBSCRIPTION"] != "") $SUBSCRIPTION = "P";
		elseif($this->profileDetails['STATE']!=FTOStateTypes::NEVER_EXPOSED) $SUBSCRIPTION = $this->profileDetails['SUBSTATE'];
		else $SUBSCRIPTION = "F";
		$GENDER = "A";
		if ($this->profileDetails["GENDER"] == "M") $GENDER = "M";
		else $GENDER = "F";
		
		$sql = "SELECT MESSAGE, GENDER, SUBSCRIPTION, TIME_CRITERIA, CUSTOM_CRITERIA from newjs.SMS_TYPE WHERE SMS_TYPE = 'I' and (SUBSCRIPTION LIKE \"%".$SUBSCRIPTION."%\" OR SUBSCRIPTION = \"".$DEFAULT_SUBSCRIPTION."\") and GENDER IN ( \"".$GENDER."\", \"".$DEFAULT_GENDER."\") and  STATUS = 'Y' and SMS_KEY = '".$this->smsKey."'";
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
				
		$sql = "SELECT PROFILEID, GENDER, USERNAME, PASSWORD,AGE,HEIGHT, SUBSCRIPTION, PHONE_MOB, CASTE, DTOFBIRTH, MSTATUS, MTONGUE,  MOB_STATUS,EDU_LEVEL,INCOME,OCCUPATION,COUNTRY_RES,CITY_RES,EMAIL, SEC_SOURCE,EDU_LEVEL_NEW, ISD, SHOWPHONE_MOB,PHONE_WITH_STD  FROM newjs.JPROFILE WHERE PROFILEID = '$this->otherProfileId'";
		$res = mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->error,$sql,"ShowErrTemplate");
		$row = mysql_fetch_assoc($res);
		// set ProfileDetails for the given ProfileID
                if($this->smsKey == "VIEWED_CONTACT_SMS")
                {
                    $loginProfile = LoggedInProfile::getInstance();
                    $otherProfile = new Profile();
                    $otherProfile->getDetail($this->otherProfileId, "PROFILEID","*");
                    $contactObj = new Contacts($loginProfile, $otherProfile);
                    $contactHandlerObj = new ContactHandler($loginProfile, $otherProfile, "INFO", $contactObj, 'CONTACT_DETAIL', ContactHandler::PRE);
                    $contactEngineObj  = ContactFactory::event($contactHandlerObj);
                    $contactDetailsArr = $contactEngineObj->getComponent()->contactDetailsObj->getContactDetailArr();
                    foreach ($contactDetailsArr as $key => $value) {
			if (strstr($value["LABEL"], "Mobile") && !strstr($value["LABEL"], "Alternate")) {
				$row["ISD_PHONE_MOB"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
			}
			if (strstr($value["LABEL"], "LandLine")) {
				$row["ISD_LANDLINE_COMMA"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
			}
			if (strstr($value["LABEL"], "Alternate")) {
				$row["ISD_ALT_MOB_COMMA"]  = strstr($value["VALUE"],"+")?$value["VALUE"]:"+".$value["VALUE"];
			}
                    }
                }

                if($this->smsKey == "REPORT_INVALID")
                {
                	$rowInvalid['OTHER_EMAIL'] = $row['EMAIL'];
                	$rowInvalid['USERNAME'] = $row['USERNAME'];
                	$rowInvalid['CITY_RES'] = $row['CITY_RES'];
                	$rowInvalid['COUNTRY_RES'] = $row['COUNTRY_RES'];
                	$rowInvalid['PROFILEID'] = $row['PROFILEID'];
                	if($row['SHOWPHONE_MOB'] == 'Y')
                	{	
                		$rowInvalid['ISD_PHONE_MOB'] = '+'.$row['ISD'].$row['PHONE_MOB'];
               		}

                }
                if($this->SMS_KEY == "REPORT_INVALID")
                {
                $this->otherProfileDetails["DATA"] = $rowInvalid;			
                }
                else{
                $this->otherProfileDetails["DATA"] = $row;		
				}
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
			$this->otherProfileDetails["EMAIL"] = $this->profileDetails["EMAIL"];
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
		if ($this->isWhitelistedProfile()) { 
			$message = $this->getMessage();
			$message = $this->getActualMessage($message);
			
		}
		return $message;
		
	}	

	//Log sms history
        private function insertInSmsLog($message,$sent)
        {
			$message = addslashes($message);
            
                $sql = "INSERT INTO newjs.SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, ADD_DATE,SENT) VALUES ('$this->profileid', 'I', '$this->smsKey', '$message', '".$this->profileDetails['ISD'].$this->profileDetails["PHONE_MOB"]."', now(),'$sent')";
                mysql_query($sql,$this->SMSLib->dbMaster) or logError($this->errorMessage,$sql,"ShowErrTemplate");
    }

    // GET SMS Message
    public function getSmsMessage()
    {
        $message = '';
        $this->setProfileDetails();
        $message = $this->getMessage();
        $message = $this->getActualMessage($message);
        return $message;
    }
    //Send sms
    public function send($acc = "transaction")
    {
        $message = $this->getSMS();
        if ($message) {
            include_once $this->SMSLib->path . "/classes/SmsVendorFactory.class.php";
            $sent = "N";

            if (in_array($this->smsKey, $this->smsTypeIgnoreTimeRange) || $this->SMSLib->inSmsSendTimeRange()) {
                $sent         = "Y";
                $smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
                $xmlResponse  = $smsVendorObj->generateXml($this->profileid, $this->profileDetails['ISD'] . $this->profileDetails["PHONE_MOB"], $message, $this->smsSettings["SEND_TIME"]);
                $smsVendorObj->send($xmlResponse, $acc);
            }
            //Insert in sms log
            $this->insertInSmsLog($message, $sent);
        }
    }
}
