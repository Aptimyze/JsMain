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
	 
}
