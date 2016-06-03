<?php
class Header
{
	
	
	public static function checkMemcacheUpdated($profile)
	{
		if($profile instanceof Profile)
			$profileid = $profile->getPROFILEID();
		else
			$profileid =$profile;
		$requiredGroups = Header::getRequiredKey();
		$memcacheObj = new ProfileMemcacheService($profileid);
		foreach($requiredGroups as $key=>$value)
		{
			if($memcacheObj->get($value,TRUE)=== false)
			{
				return 2;
			}
		}
		return 1;
		
	}
	
	public static function getRequiredKey()
	{
		$arr = array('ACC_BY_ME',
								'ACC_ME',
								'ACC_ME_NEW',
								'DEC_BY_ME',
								'DEC_ME',
								'DEC_ME_NEW',
								'AWAITING_RESPONSE',
								'AWAITING_RESPONSE_NEW',
								'FILTERED',
								'NOT_REP',
								'OPEN_CONTACTS',
								'CANCELLED_EOI');
		return $arr;
	}
}	
