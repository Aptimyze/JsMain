<?php

class ReportInvalid
{
	public static $reportInvalidCountDuration = '90';

	public static function increaseQuotaImmediately($profileId,$submittee)
	{  
		
		$timeNow = date('Y-m-d H:i:s');
		$timeDaysAgo = date('Y-m-d H:i:s', strtotime('-'.self::$reportInvalidCountDuration.' days'));
		$reportInvalidObj = new JSADMIN_REPORT_INVALID_PHONE();

		$countInvalids = $reportInvalidObj->getReportInvalidCountSubmitter($profileId , $timeNow , $timeDaysAgo);

		$previousEntryExists = (new JSADMIN_REPORT_INVALID_PHONE)->entryExistsForPair($profileId,$submittee);

		if($countInvalids < 10 && !$previousEntryExists)
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

		$entryArr = $reportInvalidObj->getEntryForPair($submitter,$submittee);

		if(is_array($entryArr))
		{	
			if(($entryArr['PHONE'] == $phoneStatus  && $phoneStatus == 'Y') ||($entryArr['MOBILE'] == $mobileStatus && $mobileStatus == 'Y'))
			{	
				
				return true;
			}
			return false;
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
