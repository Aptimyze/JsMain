<?php

class ReportInvalid
{
	public static $reportInvalidCountDuration = '90';

	public static function increaseQuotaImmediately($profileId)
	{  

		$timeNow = date('Y-m-d H:i:s');
		$timeDaysAgo = date('Y-m-d H:i:s', strtotime('-'.self::$reportInvalidCountDuration.' days'));
		$reportInvalidObj = new JSADMIN_REPORT_INVALID_PHONE();

		$countInvalids = $reportInvalidObj->getReportInvalidCountSubmitter($profileId , $timeNow , $timeDaysAgo);

		if($countInvalids <= 10)
		{	
			$contactsAllotedObj = new jsadmin_CONTACTS_ALLOTED();
   			$contactsAllotedObj->updateAllotedContacts($selfProfileID,1);
			return true;
		}

		return false;
	}
	 
}
