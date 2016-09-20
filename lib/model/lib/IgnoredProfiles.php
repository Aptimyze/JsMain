<?php
/**
 * @brief This class is used to handle ignore functionality between users
 * @author Lavesh Rawat
 * @created 2012-08-14
 */

class IgnoredProfiles
{
        protected $dbname = "";
        /**
         * Constructor to set DB
         * @param type $dbname
         */
        public function __construct($dbName = '') {
        	if (!$dbName)
        	{
                $this->dbname = searchConfig::getSearchDb();
        	}
        	else
        	{
        		$this->dbname = $dbname;
        	}      
        }
        /**
        *  This function list the two way ignored profile ie.
        1) ignored by user       2) users ignored
	@param pid profileid of userfor which two way ignore need to be find.
        */
        public function listIgnoredProfile($pid,$seperator='')
        {
        	//changed pidKey and made call to ignoredProfileCacheLib
        	$pidKey = $pid."_all";
        	$resultArr = IgnoredProfileCacheLib::getInstance()->getSetsAllValue($pidKey);
        	if($resultArr == "noKey" || $resultArr == false)
        	{
        		$NEWJS_IGNOREObj = new newjs_IGNORE_PROFILE($this->dbname);
				$resultArr = $NEWJS_IGNOREObj->listIgnoredProfile($pid,$seperator);
				IgnoredProfileCacheLib::getInstance()->storeDataInCache($pidKey,$resultArr);
        		return $resultArr;
        	}
        	else
        	{
        		return $resultArr;
        	}
        }

        public function ignoreProfile($profileid, $ignoredProfileid)
        {
        	$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
        	$ignObj->ignoreProfile($profileid,$ignoredProfileid);
        	IgnoredProfileCacheLib::getInstance()->addDataToCache($profileid,$ignoredProfileid);
        }

	public function undoIgnoreProfile($profileid, $ignoredProfileid)
	{
		$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
		$ignObj->undoIgnoreProfile($profileid,$ignoredProfileid);
		IgnoredProfileCacheLib::getInstance()->deleteDataFromCache($profileid,$ignoredProfileid);
	}

	public function ifProfilesIgnored($profileIdStr, $viewer, $key='')
	{
		$viewerKey = $viewer."_byMe";
		if($profileIdStr == '')
		{
			$resultArr = IgnoredProfileCacheLib::getInstance()->getSetsAllValue($viewerKey);
		}
		else
		{
			//considering that it is a comma seperated string 
			$profileIdArr = explode(',', $profileIdStr);
			$resultArr = IgnoredProfileCacheLib::getInstance()->getSpecificValuesFromCache($viewerKey,$profileIdArr);
		}
		if($resultArr == "noKey")
		{
			$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
			$ignProfile = $ignObj->getIgnoredProfiles($profileIdStr,$viewer,$key);
			return $ignProfile;
		}
		else
			return $resultArr;
		
	}

	//This function is left as it is since it is better off being in sql rather than in Redis
	public function getIgnoredProfile($profileid,$condition='',$skipArray='')
	{
		if(is_array($skipArray))
		{
			$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
			$ignoredProfile = $ignObj->getIgnoredProfilesList($profileid,$condition,$skipArray);
			return $ignoredProfile;
		}
		else
		{
			$pidKey = $profileid."_byMe";
			$resultArr = IgnoredProfileCacheLib::getInstance()->getSetsAllValue($pidKey);
			return $resultArr;
		}
	}

	public function ifIgnored($profileid,$otherProfileId)
	{
		 $response = IgnoredProfileCacheLib::getInstance()->checkIfDataExists($profileid,$ignoredProfileid);
		 if($response == "noKey" || $response == false)
		 {
		 	$ignoreObj = new newjs_IGNORE_PROFILE($this->dbname);
			return $ignoreObj->isIgnored($profileid,$otherProfileId);
		 }
		 elseif($response == 1)
		 {
		 	return true;
		 }
		 else
		 {
		 	return false;
		 }
	}
 		//check in case redis is off 
	public function getCountIgnoredProfiles($profileID)
	{
		$response = IgnoredProfileCacheLib::getInstance()->getCountFromCache($profileID);
		if($response == "noKey" || $response == false)
		{
			$ignoreObj = new newjs_IGNORE_PROFILE($this->dbname);
			return $ignoreObj->getCountIgnoredProfiles($profileID);
		}
		else
		{
			return $response;
		}
	}
}
?>
