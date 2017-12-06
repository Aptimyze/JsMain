<?php 

/**
 * ProfileJprofileContact
 * Library Class for store JPROFILE_CONTACT Table
 */

class ProfileContact
{

	/**
	 * Object of Store class
	 * @var instance of NEWJS_Jprofile_Contact|null
	 */
	private $objJprofileContact = null;

	/**
	 * @param $dbName - Database to which the connection would be made
	 */
	public function __construct($dbname = "")
	{
		$this->objJprofileContact = new NEWJS_Jprofile_Contact($dbname);
	}

	/**
	* @fn getArray
	* @brief fetches results for multiple profiles to query from JPROFILE_CONTACT,it also caches the result in redis and after the result is set in cache, it returns from cache. The result is cached only when $excludeArray and $greaterThanArray both are empty.
	* @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
	* @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
	* @param $fields Columns to query
	* @return results Array according to criteria having incremented index
	*/
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$indexProfileId = 0)
	{
		$bServedFromCache = false;
		$objProCacheLib = ProfileCacheLib::getInstance();
		if(is_array($valueArray) && in_array(ProfileCacheConstants::CACHE_CRITERIA, array_keys($valueArray)) && $excludeArray == "" && $greaterThanArray == "")
		{
			// profileId array
			$pid_arr = explode(",", $valueArray['PROFILEID']);
			$result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $pid_arr, $fields, __CLASS__);

			if($result && false !== $result)
			{
				$bServedFromCache = true;
				$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
			}

			if($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE)
			{
				// modify result according to indexProfileId
				foreach ($result as $key => $row)
				{
					if($indexProfileId == 1)
					{
							$detailArr[$pid_arr[$key]] = $row;
					}
					else
					{
							$detailArr[] = $row;
					}
				}
				$this->logCacheConsumeCount(__CLASS__);
				return $detailArr;
			}

			$result = $this->objJprofileContact->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $indexProfileId);
                                
			if(is_array($pid_arr) && false === ProfileCacheFunctions::isCommandLineScript("set"))
			{
				ProfileCacheLib::getInstance()->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $result,__CLASS__);
			}

			return $result;
		}
		return $this->objJprofileContact->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $indexProfileId);	
	}

	/*
	 * @fn getProfileContacts
	 * @brief fetch all contact details for a profile from cache, if not in cache then get 
	 * from store. The result is then cached.
	 * @param $pid Profile Id
	 * @return results Array containing all contact details for a profile.
	 */
	public function getProfileContacts($pid)
	{
		$bServedFromCache = false;
		$objProCacheLib = ProfileCacheLib::getInstance();

		if(!$pid)
			return ;

		if ($objProCacheLib->isCached(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__))
		{
			$result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__);
			//so for that case also we are going to query mysql
			if (false !== $result)
			{
				$bServedFromCache = true;
				$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
			}
		}

		if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE)
		{
			$this->logCacheConsumeCount(__CLASS__);
			return $result;
		}

		// Get Records from Mysql
		$result = $this->objJprofileContact->getProfileContacts($pid);
		// Request to Cache this Record, on demand
		if(false !== $result && is_array($result) && count($result))
		{
			$result['PROFILEID'] = $pid;
		}

		if(is_array($result) && isset($result['PROFILEID']) && false === ProfileCacheFunctions::isCommandLineScript("set"))
		{
			$objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result, __CLASS__);
		}

		if(false === $result && false === ProfileCacheFunctions::isCommandLineScript("set")) {
                        $dummyResult = array('PROFILEID'=>$pid, "ALT_MOBILE"=>ProfileCacheConstants::NOT_FILLED);
                        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
                }

		return $result;
	}

	/*
	 * @fn updateAltMobile
	 * @brief update Alternate Mobile details for a profile, and then update the result in
	 * cache.
	 * @param $profileid Profile Id
	 * @param $altMobile Alternate Mobile
	 */
	public function updateAltMobile($profileid, $altMobile)
	{
		$objProCacheLib = ProfileCacheLib::getInstance();
		$result = $this->objJprofileContact->updateAltMobile($profileid, $altMobile);
		if($result)
		{
			$paramArr = array('PROFILEID' => $profileid, 'ALT_MOBILE' => $altMobile);
			$objProCacheLib->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $profileid, __CLASS__);
		}
	}

	/*
	 * @fn update
	 * @brief update Contact details for a profile, and then update the result in
	 * cache.
	 * @param $pid Profile Id, profile whose details are updated.
	 * @param $paramArr - array with field name as key and field value as the value corresp
	 * to the key, which has to be updated.
	 */
	public function update($pid, $paramArr = array())
	{
		$bResult = $this->objJprofileContact->update($pid, $paramArr);
		if(true === $bResult) {
		  ProfileCacheLib::getInstance()->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $pid, __CLASS__);
		}

		return $bResult;
	}

	public function checkPhone($numberArray='',$isd='')
	{
		return $this->objJprofileContact->checkPhone($numberArray, $isd);
	}

	private function logCacheConsumeCount($funName)
	{return;
		/*$key = 'cacheConsumption' . '_' . date('Y-m-d');
		JsMemcache::getInstance()->hIncrBy($key, $funName);

		JsMemcache::getInstance()->hIncrBy($key, $funName . '::' . date('H'));*/
	}
}
?>