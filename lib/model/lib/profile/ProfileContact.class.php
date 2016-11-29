<?php 

/**
 * ProfileJprofileContact
 * Library Class for store JPROFILE_CONTACT Table
 */

class ProfileContact
{
	
	/**
	 * @var Static Instance of this class
	 */
	private static $instance;

	/**
	 * Object of Store class
	 * @var instance of NEWJS_Jprofile_Contact|null
	 */
	private static $objJprofileContact = null;

	/**
	 * @param $dbName - Database to which the connection would be made
	 */
	private function __construct($dbname = "")
	{
		self::$objJprofileContact = new NEWJS_Jprofile_Contact($dbname);
	}

	/**
	 * To Stop clone of this class object
	 */
	private function __clone() {}

	/**
	 * To stop unserialize for this class object
	 */
	private function __wakeup() {}


	/**
	 * @fn getInstance
	 * @brief fetches the instance of the class
	 * @param $dbName - Database name to which the connection would be made
	 * @return instance of this class
	 */
	public static function getInstance($dbName = '')
	{
		if (!$dbName)
			$dbName = "newjs_master";
		if (isset(self::$instance)) {
			//If different instance is required
			if ($dbName != self::$instance->connectionName) {
				$class = __CLASS__;
				self::$instance = new $class($dbName);
				self::$instance->connectionName = $dbName;
			}
		}
		else {
			$class = __CLASS__;
			self::$instance = new $class($dbName);
			self::$instance->connectionName = $dbName;
		}
		return self::$instance;
	}

	/**
	* @fn getArray
	* @brief fetches results for multiple profiles to query from JPROFILE_CONTACT,it also caches the result in redis and after the result is set in cache, it returns from cache. The result is cached only when $excludeArray and $greaterThanArray both are empty.
	* @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
	* @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
	* @param $fields Columns to query
	* @return results Array according to criteria having incremented index
	* @exception jsException for blank criteria
	* @exception PDOException for database level error handling
	*/
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$indexProfileId = 0)
	{
		$bServedFromCache = false;
		$objProCacheLib = ProfileCacheLib::getInstance();

		if(is_array($valueArray) && in_array(ProfileCacheConstants::CACHE_CRITERIA, $valueArray) && $excludeArray == "" && $greaterThanArray == "")
		{
			// Todo: From cache nd set
			// profileId array
			$pid_arr = explode(",", $valueArray['PROFILEID']);
			$result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $pid_arr, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__);
			
			if($result && false !== $result)
			{
				$bServedFromCache = true;
				$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
			}

			if($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE)
			{
				$this->logCacheConsumeCount(__CLASS__);
				// Todo: $indexProfileId case handle
				return $result;
			}

			$result = self::$objJprofileContact->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $indexProfileId);

			if(is_array($result) && count($pid_arr) == 1 && false === ProfileCacheLib::getInstance()->isCommandLineScript())
			{
				$result['PROFILEID'] = $pid;
				ProfileCacheLib::getInstance()->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result);
			}

			if(is_array($pid))
			{
				ProfileCacheLib::getInstance()->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $result);
			}

			return $result;
		}
		return self::$objJprofileContact->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $indexProfileId);	
	}

	public function getProfileContacts($pid)
	{
		$bServedFromCache = false;
		$objProCacheLib = ProfileCacheLib::getInstance();

		if ($objProCacheLib->isCached(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__))
		{
			$result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__);
			//so for that case also we are going to query mysql
			if (false !== $result)
			{
				$bServedFromCache = true;
				$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
				// Todo: check if result is suitable acc to requirement
			}
		}

		if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE)
		{
			$this->logCacheConsumeCount(__CLASS__);
			return $result;
		}

		// Get Records from Mysql
		$result = self::$objJprofileContact->getProfileContacts($pid);
		// Request to Cache this Record, on demand
		if(is_array($result) && count($result))
		{
			$result['PROFILEID'] = $pid;
		}

		if(is_array($result) && isset($result['PROFILEID']) && false === ProfileCacheLib::getInstance()->isCommandLineScript())
		{
			$objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result, __CLASS__);
		}

		// todo : what is dummyResult case?
		return $result;
	}

	public function updateAltMobile($profileid, $altMobile)
	{
		$objProCacheLib = ProfileCacheLib::getInstance();
		$result = self::$objJprofileContact->updateAltMobile($profileid, $altMobile);
		if($result)
		{
			$paramArr = array('PROFILEID' => $profileid, 'ALT_MOBILE' => $altMobile);
			$objProCacheLib->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $profileid, __CLASS__);
		}
	}

	public function update($pid, $paramArr = array())
	{
		$bResult = self::$objJprofileContact->update($pid, $paramArr);
		if(true === $bResult) {
		  ProfileCacheLib::getInstance()->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $pid, __CLASS__);
		}

		return $bResult;
	}

	public function checkPhone($numberArray='',$isd='')
	{
		return self::$objJprofileContact->checkPhone($numberArray, $isd);
	}
}
?>