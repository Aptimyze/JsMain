<?php

class ReportInvalid
{
	public static $reportInvalidCountDuration = '90';

	public static function increaseQuotaImmediately($profileId,$submittee)
	{  
		
		$timeNow = date('Y-m-d H:i:s');
		$timeDaysAgo = date('Y-m-d H:i:s', strtotime('-'.self::$reportInvalidCountDuration.' days'));
		$loginProfile = LoggedInProfile::getInstance();
		$reportInvalidObj = new JSADMIN_REPORT_INVALID_PHONE();

		$countInvalids = $reportInvalidObj->getReportInvalidCountSubmitter($profileId , $timeNow , $timeDaysAgo);

		$previousEntryExists = (new JSADMIN_REPORT_INVALID_PHONE)->entryExistsForPair($profileId,$submittee);
 
		if($countInvalids < 10 && !$previousEntryExists && $loginProfile->getPROFILE_STATE()->getPaymentStates()->isPAID())
		{	
			$contactsAllotedObj = new jsadmin_CONTACTS_ALLOTED();
   			$contactsAllotedObj->updateAllotedContacts($profileId,1);
			return true;
		}

		return false;
	}

		public static function entryAlreadyExists($submitter,$submittee,$phoneStatus,$mobileStatus)
	{  
		
		$reportInvalidObj = new JSADMIN_REPORT_INVALID_PHONE();

		$entryArr = $reportInvalidObj->getEntryForPair($submitter,$submittee,$phoneStatus,$mobileStatus);

		if(is_array($entryArr))
		{	
			return true;	
		}
		return false;
	}

	public function sendExtraNotification($selfProfileId,$profileId,$reasonMap)
	{	
		if($reasonMap == 1|| $reasonMap ==4 ){
		include_once(sfConfig::get("sf_web_dir")."/profile/InstantSMS.php");
		$sendSMS = new InstantSMS('REPORT_INVALID',$profileId,array(),$selfProfileId);
		$sendSMS->send();
			}
	}
	
    /**
     * 
     * @param type $profileid
     * @param type $phoneFlag
     * @param type $mobileFlag
     * @return type
     */
    public static function markNumberUnverified($profileid, $phoneFlag, $mobileFlag) {
      
      $countOnWhichMarkUnverified = 2;
      $pogProfile = new Profile("", $profileid);
      $pogDetails = $pogProfile->getDetail($profileid, "PROFILEID", "PHONE_MOB,PHONE_WITH_STD, ISD");

      if($phoneFlag == 'Y') {
        $numberToInvalid = $pogDetails['ISD'].$pogDetails['PHONE_WITH_STD'];
      } else if($mobileFlag == 'Y') {
        $numberToInvalid = $pogDetails['ISD'].$pogDetails['PHONE_MOB'];
      }

      $phoneVerifiedLogStore = new PHONE_VERIFIED_LOG;
      $verifiedDate = $phoneVerifiedLogStore->getVerificationDate($profileid,$numberToInvalid);
      
      //If $verifiedDate is null or false then number is already unverified
      if(is_null($verifiedDate) || false == $verifiedDate) {
         //Number is already unverified
         return ;
      }

      $reportInvalidObj = new JSADMIN_REPORT_INVALID_PHONE();
      $count = $reportInvalidObj->getCountOfInvalid($profileid, $phoneFlag, $mobileFlag, date('Y-m-d H:i:s'), $verifiedDate);
       
      if($phoneFlag == 'Y') {
        $typeOfNumber = "L";
      } else if ($mobileFlag == 'Y') {
        $typeOfNumber = "M";
      }
       
      if($count < $countOnWhichMarkUnverified) {
        //JSADMIN
        return;
      }
  
      //Mark given profileid phone as unverified and send notifications also
      self::markPhoneUnverified($profileid, $typeOfNumber);
      self::phoneUnVerificationMailer($pogProfile, $numberToInvalid);
      self::phoneUnVerificationSms($profileid, $numberToInvalid, $pogDetails['ISD']);
    }
    
    /**
     * 
     * @param type $profileid
     * @param type $typeOfNumber
     */
    public static function markPhoneUnverified($profileid, $typeOfNumber) {
      
      $storeObj = null;
      switch($typeOfNumber) {
        case "L": //Landline 
          	$arrFields["LANDL_STATUS"]='N';
            $storeObj = new JPROFILE('newjs_master');
          break; 
        case "M": //Mobile 
            $arrFields["MOB_STATUS"]='N';
            $storeObj = new JPROFILE('newjs_master');
          break;
        case "A": //Alternate Mobile //TODO Need to add when required
          break;
        default:
          break;
      }
      
      //Update Store
      if($storeObj) {
        $storeObj->edit($arrFields, $profileid);
      }
      
      JsMemcache::getInstance()->delete($profileid."_PHONE_VERIFIED");
    }
    
    /**
     * 
     * @param type $varObject | Could be Profile Object or a Profileid
     * @param type $phoneNumber
     */
    public static function phoneUnVerificationMailer($varObject, $phoneNumber)
    {
      $iReportInvalidMailedId = 1879;
      
      $reportInvalidMailer =new EmailSender(MailerGroup::REPORT_PHONE_INVALID_EMAIL, $iReportInvalidMailedId);
      
      if($varObject instanceof Profile) {
        $tpl = $reportInvalidMailer->setProfile($varObject);
      } else { // VarObject must be profileid
        $tpl = $reportInvalidMailer->setProfileId($varObject); 
      }
      
      //Set Dynamic Mailer Content
      $smartyObj = $tpl->getSmarty();
      $smartyObj->assign("phoneNumber", $phoneNumber);

      $reportInvalidMailer->send();
    }
    
    /**
     * 
     * @param type $profileid
     * @param type $phoneNum
     * @param type $isd
     */
    public static function phoneUnVerificationSms($profileid, $phoneNum, $isd)
    {
      include_once JsConstants::$docRoot."/profile/InstantSMS.php";
      $arr=array('PHONE_MOB'=>$phoneNum, 'ISD'=>$isd);
      $smsViewer = new InstantSMS("PHONE_UNVERIFY",$profileid,$arr,'');
      $smsViewer->send();
    }
    
}
