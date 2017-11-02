<?php
/**
 * Description of ProfileCacheLib
 * Library Class to handle Profile Caching Logic
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @Improvised	Esha Jain	
 * @created     7th July 2016
 */

class ProfileCacheLib
{
    const REDIS_BUCKET_2=2;

    /**
     * @var Object
     */
    private static $instance = null;
    /**
     * Const
     */
    const KEY_PREFIX = ProfileCacheConstants::PROFILE_CACHE_PREFIX;
    const KEY_PREFIX_DELIMITER = ".";
    /**
     * @var array
     */
    protected $arrRecords = array();

    /**
     * Constructor function
     */
    private function __construct($dbname="")
    {
    }

    /**
     * __destruct
     */
    public function __destruct() {
        self::$instance = null;
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
     * Get Instance
     * @return Object of ProfileCacheLib
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            $className =  __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     * @param $criteria
     * @param $key
     * @param $fields
     * @param $storeName
     * @param $fromUpdate
     * @return bool
     */
    public function isCached($criteria, $key, $fields, $storeName="", $fromUpdate=false)
    {
//IN USE
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE||(ProfileCacheFunctions::isCommandLineScript() && false === $fromUpdate) || (false === ProfileCacheFunctions::validateCriteria($criteria))) 
	{
		return false;
        }
        if (isset($this->arrRecords[intval($key)]) && $fromUpdate) 
	{
		return true;
        }
        $demandedFields = ProfileCacheFunctions::getFinalFieldsArrayWithPrefix($storeName,$fields);
        if (isset($this->arrRecords[intval($key)]) && $this->checkFieldsAvailability($key, $demandedFields)) 
	{
		return true;
        }
        $this->storeInLocalCache($key);

        if (false === $this->checkFieldsAvailability($key, $demandedFields, $storeName)) 
	{
		ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Cache Mis due to fields {$criteria} : {$key} and {$fields}");
		return false;
        }
        return true;
    }

    /**
     * @param $szCriteria
     * @param $key
     * @param $arrParams
     * @return bool
     */
    public function cacheThis($szCriteria, $key, $arrParams, $storeName="")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "cacheThis"."\n", FILE_APPEND);
//IN USE
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE || (0 === count($arrParams)) || false === ProfileCacheFunctions::validateCriteria($szCriteria)) 
	{
            return false;
        }
        $szKey = ProfileCacheFunctions::getDecoratedKey($key);
        $prefix = ProfileCacheFunctions::getStorePrefix($storeName);
        $arrParams = ProfileCacheFunctions::getRelevantKeysNameWithValues($arrParams,$prefix,'',self::KEY_PREFIX_DELIMITER);
        $this->storeInCache($szKey, $arrParams);
        if (false === ProfileCacheFunctions::isCommandLineScript()) 
	{
            $this->updateInLocalCache($key, $arrParams);
        }
        return true;
    }

    /**
     * @param $paramArr
     * @param $szCriteria
     * @param string|integer $key KEY of the Cache
     * @param string $storeName Name of the store
     * @param $extraWhereCnd
     * @return bool|void
     */
    public function updateCache($paramArr, $szCriteria, $key, $storeName="", $extraWhereCnd = "")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "updateCache"."\n", FILE_APPEND);
        if ((false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) || (false === $this->isCached($szCriteria, $key, array_keys($paramArr), $storeName, true)))
	{
            return false;
        }

        $bUpdateFromMysql = false;
        //Now Process extraWhereCnd
        if (false === $bUpdateFromMysql && strlen($extraWhereCnd)) 
	{
            $bUpdateFromMysql = $this->processGenericWhereClause($key, $extraWhereCnd) ? false : true;
        }

        if ($bUpdateFromMysql) 
	{
            ProfileCacheFunctions::logThis(LoggingEnums::LOG_DEBUG, "Updating from myql: Criteria: {$szCriteria} , Value: {$key} & extraWhereCnd : {$extraWhereCnd}");
            $result = $this->cacheFromMysql($szCriteria, $key, $extraWhereCnd);
        } 
	else 
	{
            $result = $this->cacheThis($szCriteria, $key, $paramArr, $storeName);
        }
        return $result;
    }

    /**
     * @param $iProfileID
     * @param $paramArr
     * @return bool
     */
    public function insertInCache($iProfileID, $paramArr, $storeName="")
    {
             $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "insertInCache"."\n", FILE_APPEND);
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        $paramArr[ProfileCacheConstants::CACHE_HASH_KEY] = $iProfileID;
        if(false === isset($paramArr[ProfileCacheConstants::ACTIVATED_KEY])) {
            $paramArr[ProfileCacheConstants::ACTIVATED_KEY] = 1;
        }

        return $this->cacheThis(ProfileCacheConstants::CACHE_HASH_KEY, $iProfileID, $paramArr, $storeName);
    }

    /**
     * @param $szCriteria
     * @param $key
     * @param $fields
     * @param null $arrExtraWhereClause
     * @return array|bool
     */
    public function get($szCriteria, $key, $fields, $storeName="", $arrExtraWhereClause = null)
    {
        $storeName = "";
        $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "get :: ".$storeName."\n", FILE_APPEND);
//IN USE
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE || ProfileCacheFunctions::isCommandLineScript()||(false === ProfileCacheFunctions::validateCriteria($szCriteria)) || (false === $this->isCached($szCriteria, $key, $fields, $storeName)))  // CHECK THIS
	{
		//return false;
        }

        $arrData = $this->getFromLocalCache($key);
	$prefix = ProfileCacheFunctions::getStorePrefix($storeName);
        if ($arrExtraWhereClause) 
	{
		//$arrExtraWhereClause = ProfileCacheFunctions::getRelevantKeysNameWithValues($arrExtraWhereClause,$prefix,'',self::KEY_PREFIX_DELIMITER);
                $arrExtraWhereClause = ProfileCacheFunctions::getFinalFieldsArrayWithPrefix($storeName,$arrExtraWhereClause);      
		ProfileCacheFunctions::processArrayWhereClause($arrData,$arrExtraWhereClause);
        }
        if(false === $arrData) 
	{
		return false;
        }

        $arrOut = array();
        //Check for Not-Filled Case
        //if(strlen($storeName)) 
	//{
        //print_r($arrData);die;
//                $allStoreFields = ProfileCacheFunctions::getColumnArr($storeName);
//                $arrFields = $this->getRelevantFields($fields, $storeName,$allStoreFields);
//		$allStoreFields = ProfileCacheFunctions::getRelevantKeysName($allStoreFields,$prefix,'',self::KEY_PREFIX_DELIMITER);
		$allStoreFields = ProfileCacheFunctions::getFinalFieldsArrayWithPrefix($storeName,$fields);
                //print_r($allStoreFields);die;
                if(strlen($storeName)) {
                        foreach ($allStoreFields as $col) 
                        {
                                        if(isset($this->arrRecords[intval($key)][$col]) && $this->arrRecords[intval($key)][$col] === ProfileCacheConstants::NOT_FILLED) 
                                        {
                                                $iProfileID = $arrData['PROFILEID'];
                                                $arrData = array_fill_keys($allStoreFields[$col1], ProfileCacheConstants::NOT_FILLED);
                                                $arrData['PROFILEID'] = $iProfileID;
                                                break;
                                        }
                        }
                }
        //}
                print_r($arrData);die;
	$arrOut = ProfileCacheFunctions::getOriginalKeysNameWithValues($arrData,$prefix,'',self::KEY_PREFIX_DELIMITER);
        return $arrOut;
    }

    /**
     * @param $key
     * @return string
     */
    private function getDecoratedKey($key)
    {
        return self::KEY_PREFIX.trim($key);
    }

    /**
     * @param $key
     */
    private function storeInLocalCache($key)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "storeInLocalCache"."\n", FILE_APPEND);
//IN USE
        $stTime = ProfileCacheFunctions::createNewTime();
        $this->arrRecords[intval($key)] = JsMemcache::getInstance()->getHashAllValue(ProfileCacheFunctions::getDecoratedKey($key),'',self::REDIS_BUCKET_2);
        ProfileCacheFunctions::calculateResourceUsages($stTime,'Get : '," for key {$key}");
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFromLocalCache($key)
    {
//IN USE
        return $this->arrRecords[intval($key)];
    }

    /**
     * @param $arrFields
     * @return array
     */
	private function getRelevantFields($arrFields, $storeName="",$allStoreFields='')
	{
//IN USE
		if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM && strlen($storeName)) 
		{
			$array = $allStoreFields;
		} 
		else if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM) 
		{
			$array = ProfileCacheConstants::$arrHashSubKeys;
		} 
		else if (is_string($arrFields) && $arrFields != ProfileCacheConstants::ALL_FIELDS_SYM) 
		{
			$array = explode(',',$arrFields);
			foreach($array as $k=>$v)
			{
				$array[$k] = trim($v);
			}
			$array = array_intersect(ProfileCacheConstants::$arrHashSubKeys, $array);
		}
		if(count(array_diff(array_unique($arrFields),$array)))
		{
			ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Relevant Field in not present in cache : ".print_r(array_diff(array_unique($arrFields),$array),true));
		}
        return $array;
    }

    /**
     * @param $key
     * @param $fields
     * @return bool
     */
    private function checkFieldsAvailability($key, $demandedFields)
    {
             $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "checkFieldsAvailability"."\n", FILE_APPEND);
//IN USE
	$localCacheData = $this->getFromLocalCache($key);
	if(0 === count($localCacheData))
	{
		return false;
	}
	$localCacheFields = array_keys($localCacheData);
	$isSubset = (count(array_diff($demandedFields,$localCacheFields))==0);
        if (!$isSubset) 
	{
            return false;
        } 
        return true;
    }

    /**
     * @param $key
     * @param $arrFields
     * @return bool
     */
    private function updateInLocalCache($key, $arrFields)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "updateInLocalCache"."\n", FILE_APPEND);
        if(!is_array($arrFields)) {
            return false;
        }
        ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Setting local cache for key : {$key}");
        foreach($arrFields as $col => $val) 
	{
            $this->arrRecords[intval($key)][$col] = $val;
        }
        return true;
    }

    /**
     * @param $key
     * @param $szWhereCnd
     * @return bool
     */
    private function processGenericWhereClause($key, $szWhereCnd)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "processGenericWhereClause"."\n", FILE_APPEND);
        $arrData = $this->getFromLocalCache($key);
        $arrAllowedWhereCnd = array('activatedKey=1','activatedKey= 1','activatedKey = 1','activatedKey =1');

        if (in_array($szWhereCnd, $arrAllowedWhereCnd) && $arrData['activatedKey'] == '1') {
            return true;
        }

        return false;
    }

    /**
     * @param $szCriteria
     * @param $key
     * @param null $extraWhereCnd
     * @return bool
     */
    private function cacheFromMysql($szCriteria, $key, $extraWhereCnd = null)
    {
        $storeObj = NEWJS_JPROFILE::getInstance();
        $arrData = $storeObj->get($key, $szCriteria, ProfileCacheConstants::ALL_FIELDS_SYM);
        //TODO : If execution is from some skiped scripts then do not cachce
        return $this->cacheThis(ProfileCacheConstants::CACHE_HASH_KEY, $arrData[ProfileCacheConstants::CACHE_HASH_KEY], $arrData);
    }

    /**
     * @param $szKey
     * @param $arrParams
     * @return bool
     */
    private function storeInCache($szKey, $arrParams)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "storeInCache"."\n", FILE_APPEND);
        //Set Hash Object
        $stTime = ProfileCacheFunctions::createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                JsMemcache::getInstance()->setHashObject($szKey, $arrParams, ProfileCacheConstants::CACHE_EXPIRE_TIME,true,self::REDIS_BUCKET_2);
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while setting up in cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache Update failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        ProfileCacheFunctions::calculateResourceUsages($stTime,'Set : '," for key {$key}");

        return $bSuccess;
    }

    /**
     * @param $Var
     */
    public function removeCache($Var)
    {
        $status = true;
        if (is_array($Var)) {
            foreach($Var as $k => $iProfileID) {
              $status = $status && $this->purge($iProfileID);
            }
        } else {
            $status = $this->purge($Var);
        }
        return $status;
    }

    /**
     * @param $key
     * @return mixed
     */
    private function purge($key)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "purge"."\n", FILE_APPEND);
        if(isset($this->arrRecords[intval($key)])){
            unset($this->arrRecords[intval($key)]);
        }

        $szKey = ProfileCacheConstants::PROFILE_CACHE_PREFIX.$key;

        $stTime = ProfileCacheFunctions::createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                JsMemcache::getInstance()->delete($szKey,true,self::REDIS_BUCKET_2);
                $this->logDelCount();
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache Purge Failed,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache Purge Failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);

            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        ProfileCacheFunctions::calculateResourceUsages($stTime,'Delete : '," for key {$key}");

        return $bSuccess;
    }

    /**
     * Function to count number of delete calls
     */
    private function logDelCount()
    {
	    return;
    }
    
    /**
     * This function will be used to check profile data
     * @param type $iProfileId
     * @param type $fields
     * @return type
     */
    public function checkProfileData($iProfileId,$fields="")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "checkProfileData"."\n", FILE_APPEND);
      $data = JsMemcache::getInstance()->getHashAllValue(ProfileCacheConstants::PROFILE_CACHE_PREFIX.$iProfileId,'',self::REDIS_BUCKET_2);
      $allowedFields = explode(",", $fields);
      $bAllFields = false;
      if(count($allowedFields) && in_array('ALL',$allowedFields)){
        $bAllFields = true;
      }
      $arrOut = array();
      if(0 === count($data)) {
        $arrOut['msg'] = "Redis data does not exist for profileid : {$iProfileId}";
      }
      
      $len = count($data);
      if($len){
        $arrOut['msg'] = "No of columns exist for profileid: {$iProfileId} is {$len}";
      }
      
      if (false === $bAllFields && $len) {
        foreach ($allowedFields as $key) {
          if(strlen($key))
            $arrOut[$key] = $data[$key];
        }
      }
      else if($len){
        $arrOut = $data;
      }

      return $arrOut;
    }
    
    /**
     * getForMultipleKeys
     * @param type $criteria
     * @param type $key
     * @param type $fields
     * @param type $storeName
     */
    public function getForMultipleKeys($criteria, $arrKey, $fields, $storeName="")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "getForMultipleKeys"."\n", FILE_APPEND);
      if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
           return false;
        }
   
        if(false === ProfileCacheFunctions::validateCriteria($criteria)) {
            return false;
        }
        
        //Get Relevant Fields
        $arrFields = $this->getRelevantFields($fields, $storeName);
        
        //Get Decorated keys
        $arrDecoratedKeys = array_map(array("ProfileCacheLib","getDecoratedKey"), $arrKey);
        
        //Get Records from Cache
        $arrResponse = JsMemcache::getInstance()->getMultipleHashFieldsByPipleline($arrDecoratedKeys ,$arrFields,self::REDIS_BUCKET_2);
        
        //Check data
        if(false === $this->checkMulipleDataAvailability($arrResponse, $arrFields)) {
          return false;
        }
        
        //Remove Duplicate Suffix
        if(is_array($arrResponse) && count($arrResponse)) {
            foreach($arrResponse as $key=>$val) {
                $arrResponse[$key] = $this->removeDuplicateSuffix($val, $storeName);
            }
        }
        
        //TODO : Handle Exception Cases  
        return array_values($arrResponse);
    }
    
    /**
     * 
     * @param type $szCriteria
     * @param type $arrResponse
     */
    public function cacheForMultiple($szCriteria, $arrResponse, $storeName="")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "cacheForMultiple"."\n", FILE_APPEND);
        foreach($arrResponse as $key=>$rowData){
            $rowData = $this->addDuplicateSuffix($rowData, $storeName);
            $arrData[ProfileCacheConstants::PROFILE_CACHE_PREFIX.$rowData[$szCriteria]] = $rowData;
            unset($arrResponse[$key]);
        }
      
      $this->storeForMultipleProfileInCache($arrData);
      //TODO : Handle Exception Cases
    }
    
    /**
     * 
     * @param type $arrData
     * @param type $arrFields
     * @return boolean
     */
    private function checkMulipleDataAvailability(&$arrData, $arrFields)
    { 
      foreach($arrData as $key=>$value)
      {
        if(in_array(ProfileCacheConstants::NOT_FILLED, $value)) {
            unset($arrData[$key]);
            continue;
        }
        if(false === $this->isDataExistInCache($value)) {
          ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Cache does not exist for {$key}");
          return false;
        }
      }
      return true;
    }
    
    /**
     * isDataExistInCache
     * @param type $arr
     * @return boolean
     */
    private function isDataExistInCache($arr)
    {
      foreach($arr as $k=>$v){      
        if(is_null($v)) return false;
      }
      return true;
    }
    
    /**
     * 
     * @param type $arrData
     * @return boolean
     */
    private function storeForMultipleProfileInCache($arrData)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "storeForMultipleProfileInCache"."\n", FILE_APPEND);
      //Set Hash Object
        $stTime = ProfileCacheFunctions::createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                $arrResult = JsMemcache::getInstance()->setMultipleHashByPipleline($arrData, ProfileCacheConstants::CACHE_EXPIRE_TIME, true,self::REDIS_BUCKET_2);
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while setting up in cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache Update failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                foreach($arrData as $szKey=>$value) {
                    $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                    $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                    $producerObj->sendMessage($queueData);
                }
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        ProfileCacheFunctions::calculateResourceUsages($stTime,'MUlti-Set : '," for key {$key}");

        return $bSuccess;
    }
    
    /**
     * 
     * @param type $arrParams
     * @param type $storeName
     */
    private function addDuplicateSuffix($arrParams, $storeName)
    {
        
        $suffixName = $this->getStoreSuffix($storeName);
                       
        if(is_null($suffixName)) {
            return $arrParams;
        }
        
        foreach ($arrParams as $key=>$val) {
            if(in_array($key, ProfileCacheConstants::$arrDuplicateFieldsMap)) {
                unset($arrParams[$key]);
                $newKey = $key.ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER.$suffixName;
                $arrParams[$newKey] = $val;
            }
        }
        return $arrParams;
    }
    
    /**
     * 
     * @param type $arrParams
     * @param type $storeName
     */
    private function removeDuplicateSuffix($arrParams, $storeName)
    {
        if(0 === strlen($storeName)) {
            return $arrParams;
        }
        
        $suffixName = $this->getStoreSuffix($storeName);
                       
        if(is_null($suffixName)) {
            return $arrParams;
        }
        
        foreach ($arrParams as $key=>$val) {           
            
            $isDuplicateField = stripos($key, ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER);
            if(false !== $isDuplicateField) {
                $newKey = substr($key, 0, $isDuplicateField);
                $arrParams[$newKey] = $val;
                unset($arrParams[$key]);
            }
        }
        return $arrParams;
    }
    
    private function getStoreSuffix($storeName)
    {
        return false;
    }
    
    /**
     * removeFieldsFromCache
     * @param type $key
     * @param type $storeName
     * @param type $fields
     */
    public function removeFieldsFromCache($key, $storeName, $fields = ProfileCacheConstants::ALL_FIELDS_SYM)
    {
        //Prepend Prefix on key
        $szKey = ProfileCacheFunctions::getDecoratedKey($key);
        
        //Get Columns to delete
        $arrColumns = $this->getRelevantFields($fields, $storeName);
        
        //Remove Common Fields
        foreach($arrColumns as $k=>$v) {
            if(in_array($v, ProfileCacheConstants::$arrCommonFieldsMap))
                unset($arrColumns[$k]);
        }

        
        return $this->deleteSubFields($szKey, $arrColumns);
    }
    
    /**
     * 
     * @param type $szKey
     * @param type $arrFields
     * @return boolean
     */
    private function deleteSubFields($szKey, $arrFields)
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "deleteSubFields"."\n", FILE_APPEND);
        //Set Hash Object
        $stTime = ProfileCacheFunctions::createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                $result = JsMemcache::getInstance()->hdel($szKey, $arrFields, true,self::REDIS_BUCKET_2);
                $bSuccess =  true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while deleting Sub fields from cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                ProfileCacheFunctions::logThis(LoggingEnums::LOG_INFO, "Profile Cache HDEL failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        ProfileCacheFunctions::calculateResourceUsages($stTime,'HDEL : '," for key {$key}");
        
        return $bSuccess;
    }

    /**
     * Returns an array of keys (profile ids) whose $arrFields(atleast one field) is 
     * null.
     * @param type $arrData
     * @param type $arrFields
     * @return array
     */
    private function getMulipleDataNotAvailabilityKeys(&$arrData, $arrFields)
    { 
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "getMulipleDataNotAvailabilityKeys"."\n", FILE_APPEND);
      $arrPids = array();
      foreach($arrData as $key=>$value)
      {
        if(in_array(ProfileCacheConstants::NOT_FILLED, $value)) {
            unset($arrData[$key]);
            continue;
        }
        if(false === $this->isDataExistInCache($value)) {
          $profileId = ProfileCacheFunctions::getRawKey($key);
          array_push($arrPids, $profileId);
        }
      }
      return $arrPids;
    }

    /**
     * getForPartialKeys
     * @param type $criteria
     * @param type $key
     * @param type $fields
     * @param type $storeName
     */
    
    public function getForPartialKeys($criteria, $arrKey, $fields, $storeName="")
    {
      if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
        return false;
      }
      if(false === ProfileCacheFunctions::validateCriteria($criteria)) {
          return false;
      }

      if(false === $this->checkRelevantFields($fields, $storeName)){
        return false;
      }

      //Get Relevant Fields
      $arrFields = $this->getRelevantFields($fields, $storeName);
      
      //Get Decorated keys
      $arrDecoratedKeys = array_map(array("ProfileCacheLib","getDecoratedKey"), $arrKey);
      
      //Get Records from Cache
      $arrResponse = JsMemcache::getInstance()->getMultipleHashFieldsByPipleline($arrDecoratedKeys ,$arrFields,self::REDIS_BUCKET_2);

      if(!isset($arrResponse))
        return false;

      // Get array of profile ids for which data doesnt exist in cache
      $arrPids = $this->getMulipleDataNotAvailabilityKeys($arrResponse, $arrFields);

      // Array of profile ids which exist in cache
      $cachedPids = array_diff($arrKey, $arrPids);

      $cachedResult = False;
      if(!empty($cachedPids))
      {
        $cachedResult = array();
        foreach ($cachedPids as $key)
        {
          $val = $arrResponse[ProfileCacheFunctions::getDecoratedKey($key)];
          $cachedResult[] = $this->removeDuplicateSuffix($val, $storeName);
        }
      }

      $result = array(
        'cachedResult' => $cachedResult,
        'notCachedPids' => implode(',', $arrPids),
      );
      return $result;
    }

    /**
     * @param $arrFields
     * @param $storeName
     * @return bool
     */
    private function checkRelevantFields($arrFields, $storeName="")
    {
            $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/JUSTJOINED_HOUR_COUNT.txt";
        file_put_contents($fileName, "checkRelevantFields"."\n", FILE_APPEND);
        $bStoreNameExist = strlen($storeName) ? true : false;
        $storeSuffix = $this->getStoreSuffix($storeName);

        if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM && strlen($storeName)) {
          $arrFields = ProfileCacheFunctions::getColumnArr($storeName);
        } else if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM) {
            $arrFields = ProfileCacheConstants::$arrHashSubKeys;
        } else if (is_string($arrFields) && $arrFields != ProfileCacheConstants::ALL_FIELDS_SYM) {
            $arrFields = explode(',',$arrFields);
            foreach($arrFields as $k=>$v)
                $arrFields[$k] = trim($v);
        }
        //TODO: If $arrFields is not an array, handle this case
        $array = array_intersect(ProfileCacheConstants::$arrHashSubKeys, $arrFields);
        $array = array_unique($array);

        if(count(array_diff(array_unique($arrFields),$array)))
        {
          return false;
        }

        // the fields are relevant
        return true;
    }

}
?>
