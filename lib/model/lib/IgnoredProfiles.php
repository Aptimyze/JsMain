<?php
/**
 * @brief This class is used to handle ignore functionality between users
 * @author Lavesh Rawat
 * @created 2012-08-14
 */

class IgnoredProfiles
{
        protected $dbname = "";
        private $measurePerformance = true;
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
        	$this->addDataToFile("old");
        	//changed pidKey and made call to ignoredProfileCacheLib
        	$pidKey = $pid."_all";
        	$resultArr = IgnoredProfileCacheLib::getInstance()->getSetsAllValue($pidKey);
        	if($resultArr == "noKey" || $resultArr == false)
        	{
        		$NEWJS_IGNOREObj = new newjs_IGNORE_PROFILE($this->dbname);
				$resultArr = $NEWJS_IGNOREObj->listIgnoredProfile($pid,$seperator);
				$this->addDataToFile("new");
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
        	$this->addDataToFile("old");
        	$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
        	$ignObj->ignoreProfile($profileid,$ignoredProfileid);
        	$this->addDataToFile("new");
        	IgnoredProfileCacheLib::getInstance()->addDataToCache($profileid,$ignoredProfileid);
        }

	public function undoIgnoreProfile($profileid, $ignoredProfileid)
	{
		$this->addDataToFile("old");
		$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
		$ignObj->undoIgnoreProfile($profileid,$ignoredProfileid);
		$this->addDataToFile("new");
		IgnoredProfileCacheLib::getInstance()->deleteDataFromCache($profileid,$ignoredProfileid);
	}

	public function ifProfilesIgnored($profileIdStr, $viewer, $key='')
	{
		$this->addDataToFile("old");
		$viewerKey = $viewer."_byMe";
		if($profileIdStr == '0')
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
			if($profileIdStr == 0 && $key =="")
			{
				IgnoredProfileCacheLib::getInstance()->storeDataInCache($viewerKey,$ignProfile);
			}
			$this->addDataToFile("new");
			return $ignProfile;
		}
		else
		{
			return $resultArr;
		}
	}

	//This function is left as it is since it is better off being in sql rather than in Redis
	public function getIgnoredProfile($profileid,$condition='',$skipArray='')
	{
		$this->addDataToFile("old");
		$ignObj = new newjs_IGNORE_PROFILE($this->dbname);
		$ignoredProfile = $ignObj->getIgnoredProfilesList($profileid,$condition,$skipArray);
		$this->addDataToFile("new");
		return $ignoredProfile;
	}

	public function ifIgnored($profileid,$otherProfileId)
	{
		 $this->addDataToFile("old");
		 $response = IgnoredProfileCacheLib::getInstance()->checkIfDataExists($profileid,$otherProfileId);
		 if($response == "noKey" || $response == false)
		 {
		 	$ignoreObj = new newjs_IGNORE_PROFILE($this->dbname);
		 	$this->addDataToFile("new");
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
		$this->addDataToFile("old");
		$response = IgnoredProfileCacheLib::getInstance()->getCountFromCache($profileID);
		if($response == "noKey" || $response == false)
		{
			$ignoreObj = new newjs_IGNORE_PROFILE($this->dbname);
			$this->addDataToFile("new");
			return $ignoreObj->getCountIgnoredProfiles($profileID);
		}
		else
		{
			return $response;
		}
	}

	public function addDataToFile($param)
	{
		if($this->measurePerformance)
		{
			if($param == "new")
			{
				$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/ignoredProfilesCount_NEW_".date('Y-m-d').".txt";
			}
			else if($param == "old")
			{
				$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/ignoredProfilesCount_OLD_".date('Y-m-d').".txt";
			}
			$file = fopen($fileName,"a+");
			$contents = fread($file,filesize($fileName));
			if($contents == 0)
			{
				ftruncate($file, 0);
				fwrite($file,"1");
			}
			else
			{
				$contents = $contents+1;
				ftruncate($file, 0);
				fwrite($file,$contents);
			}
		}
	}
}
?>
