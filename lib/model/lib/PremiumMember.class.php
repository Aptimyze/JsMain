<?php
/**
This class handles the business logic for premium members for whom dummy profiles are created.
**/
class PremiumMember
{
        public function __construct()
        {
        }

	/*
	This function returns true if the logged in profileid is a dummy user of a premium member else returns false. It also sets the profilechecksum in memcache
	@param - profileid
	@return - true/false
	*/
	public static function isDummyProfile($profileid)
	{
		$flag = 0;
		$key = $profileid."_DUMMY_USER";
		if(JsMemcache::getInstance()->get($key)=="no")
		{
			return false;
		}
		elseif(JsMemcache::getInstance()->get($key))
		{
			if(JsMemcache::getInstance()->get($key)==CommonFunction::createChecksumForProfile($profileid))
				return true;
			else
				$flag = 1;
		}
		else
		{
			$flag = 1;
		}

		if($flag==1)
		{
			$jpuObj = new jsadmin_PremiumUsers;
           		if($jpuObj->isDummy($profileid))
			{
				JsMemcache::getInstance()->set($key,CommonFunction::createChecksumForProfile($profileid),3600);
               			return true;
			}
			else
			{
				JsMemcache::getInstance()->set($key,"no",3600);
				return false;
			}
		}
	}
}
?>
