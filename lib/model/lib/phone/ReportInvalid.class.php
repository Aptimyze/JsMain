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
	 
}
