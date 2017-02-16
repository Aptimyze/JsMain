<?php
/**
 * Description of ProfileCacheLib
 * Library Class to handle Profile Caching Logic
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @created     7th July 2016
 */

class ProfileCacheLib
{

    /**
     * @var Object
     */
    private static $instance = null;

    /**
     * Const
     */
    const KEY_PREFIX = ProfileCacheConstants::PROFILE_CACHE_PREFIX;

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
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
           return false;
        }

        $this->logThis(LoggingEnums::LOG_INFO,"Cache Criteria Check : {$criteria} and its value is {$key} and needs following fields {$fields}");
        
        if(false === $this->validateCriteria($criteria)) {
            return false;
        }

        //if from command line and not for update then not use cache
        if ($this->isCommandLineScript() && false === $fromUpdate) {
            return false;
        }

        //For Update case check only profile exist in cache or not
        if (isset($this->arrRecords[intval($key)]) && $fromUpdate) {
            return true;
        }

        if (isset($this->arrRecords[intval($key)]) && $this->checkFieldsAvailability($key, $fields, $storeName)) {
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(0 === count($this->getFromLocalCache($key))) {
            unset($this->arrRecords[intval($key)]);
            $this->logThis(LoggingEnums::LOG_INFO, "Cache Miss from first point for Criteria {$criteria} : {$key}");
            return false;
        }

        //Check all fields specified in param fields is present in cache also, right now we are assuming all fields are cached together
        if (false === $this->checkFieldsAvailability($key, $fields, $storeName)) {
            $this->logThis(LoggingEnums::LOG_INFO, "Cache Mis due to fields {$criteria} : {$key} and {$fields}");
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
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
        //If Criteria is other then PROFILEID then return false
        if (false === $this->validateCriteria($szCriteria)) {
            return false;
        }

        //Prepend Prefix on key
        $szKey = $this->getDecoratedKey($key);

        if (0 === count($arrParams)) {
            return false;
        }
        
        //Add Duplicate Fields Suffix
        $arrParams = $this->addDuplicateSuffix($arrParams, $storeName);
        
        //Store in Cache
        $this->storeInCache($szKey, $arrParams);
        
	//Update Local Cache also
        if (false === $this->isCommandLineScript()) {
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
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        $bUpdateFromMysql = false;
        if(false === $this->isCached($szCriteria, $key, array_keys($paramArr), $storeName, true)) {
            //If Cache does not exist then do not set cache
            return false;
        }

        //Now Process extraWhereCnd
        if (false === $bUpdateFromMysql && strlen($extraWhereCnd)) {
            $bUpdateFromMysql = $this->processGenericWhereClause($key, $extraWhereCnd) ? false : true;
        }

        if ($bUpdateFromMysql) {
            $this->logThis(LoggingEnums::LOG_DEBUG, "Updating from myql: Criteria: {$szCriteria} , Value: {$key} & extraWhereCnd : {$extraWhereCnd}");
            $result = $this->cacheFromMysql($szCriteria, $key, $extraWhereCnd);
        } else {
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
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        if (false === $this->validateCriteria($szCriteria)) {
           return false;
        }

        if (false === $this->isCached($szCriteria, $key, $fields, $storeName)) {
            return false;
        }

        if ($this->isCommandLineScript()) {
            //Throw exception of command line usages from command line script
            return false;
        }

        $arrData = $this->getFromLocalCache($key);
        if ($arrExtraWhereClause) {
            $this->processArrayWhereClause($arrData,$arrExtraWhereClause);
        }

        //If data is false then return false
        if(false === $arrData) {
            return false;
        }

        $arrFields = $this->getRelevantFields($fields, $storeName);
        $arrOut = array();
        
        //Check for Not-Filled Case
        if(strlen($storeName)) {
            $arrColumns = $this->getColumnArr($storeName);
            foreach ($arrColumns as $col) {
                if(isset($this->arrRecords[intval($key)][$col]) && 
                $this->arrRecords[intval($key)][$col] === ProfileCacheConstants::NOT_FILLED) {
                    $iProfileID = $arrData['PROFILEID'];
                    $arrData = array_fill_keys($arrFields, ProfileCacheConstants::NOT_FILLED);
                    $arrData['PROFILEID'] = $iProfileID;
                    break;
                }
            }
        }
        
        foreach ($arrFields as $k) {
            $indexKey = $k;
            
            $isDuplicateField = stripos($k, ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER);
            if(false !== $isDuplicateField) {
                $indexKey = substr($k, 0, $isDuplicateField);
            }
            
            $arrOut[$indexKey] = $arrData[$k];
        }

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
     * @param $szCriteria
     * @return bool
     */
    private function validateCriteria($szCriteria)
    {
        //If Criteria is other then PROFILEID then return false
        return (ProfileCacheConstants::CACHE_CRITERIA == strtoupper($szCriteria));
    }

    /**
     * @param $key
     */
    private function storeInLocalCache($key)
    {
        if($this->isCommandLineScript()) {
                unset($this->arrRecords);
                $this->arrRecords = array();
        }

        $stTime = $this->createNewTime();
        $this->arrRecords[intval($key)] = JsMemcache::getInstance()->getHashAllValue($this->getDecoratedKey($key));
        $this->calculateResourceUsages($stTime,'Get : '," for key {$key}");
    }

    /**
     * @param $key
     * @return mixed
     */
    private function getFromLocalCache($key)
    {
        return $this->arrRecords[intval($key)];
    }

    /**
     * @param $arrFields
     * @return array
     */
    private function getRelevantFields($arrFields, $storeName="")
    {
        $bStoreNameExist = strlen($storeName) ? true : false;
        $storeSuffix = $this->getStoreSuffix($storeName);
        
        if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM && strlen($storeName)) {
          $arrFields = $this->getColumnArr($storeName);
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
        
        if(count(array_diff(array_unique($arrFields),$array))){
          $this->logThis(LoggingEnums::LOG_ERROR, "Relevant Field in not present in cache : ".print_r(array_diff(array_unique($arrFields),$array),true));
          //throw new jsException("","Field in not present in cache : ".print_r(array_diff(array_unique($arrFields),$array),true));
        }
        
        //Check for duplicate fields
        if(false === is_null($storeSuffix)) {
            foreach($array as $key => $val)
            {
                if( in_array($val, ProfileCacheConstants::$arrDuplicateFieldsMap) && $bStoreNameExist) {
                    $newVal = $val.ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER.$storeSuffix;
                    unset($array[$key]);
                    $array[$key] = $newVal;
                }
            }
        }
        return $array;
    }

    /**
     * @param $arrData
     * @param $arrExtraWhereClause
     */
    private function processArrayWhereClause(&$arrData,$arrExtraWhereClause)
    {
        //jsCacheWrapperException::logThis(new Exception(print_r($arrExtraWhereClause,true).' extra where condition'));

        if(!is_array($arrExtraWhereClause) || 0 === count($arrExtraWhereClause))
            return ;
        
        foreach ($arrExtraWhereClause as $col => $val) {
            if($arrData[$col] != $val) {
                $arrData = false;
                break;
            }
        }
        return;
    }

    /**
     * @param $key
     * @param $fields
     * @return bool
     */
    private function checkFieldsAvailability($key, $fields, $storeName="")
    {
        $arrAllowableFields = $this->getRelevantFields($fields, $storeName);
        $arrFields = $arrAllowableFields;
        $suffix = $this->getStoreSuffix($storeName);
        $bIsStoreNameExist = (0 === strlen($storeName)) ? false : true;
        
        if ($fields == ProfileCacheConstants::ALL_FIELDS_SYM &&
            count($this->getFromLocalCache($key))  !== count($arrAllowableFields) &&
            false === $bIsStoreNameExist
        ) {
            return false;
        } else if ($fields !== ProfileCacheConstants::ALL_FIELDS_SYM) {
          $arrFields = $fields;
          if(is_string($fields)) {
              $arrFields = explode(",", $fields);
              foreach($arrFields as $k=>$v)
                $arrFields[$k] = trim($v);
          }
          foreach ($arrFields as $szColName) {
              $isDuplicateField = in_array($szColName, ProfileCacheConstants::$arrDuplicateFieldsMap);              
              if($suffix && false !== $isDuplicateField) {
                  $szColName = $szColName.ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER.$suffix;
              }
              
              if(!in_array($szColName, $arrAllowableFields)) {
                  return false;
              }
          }
        }
        
        //Check for Not-Filled Case
        if($bIsStoreNameExist) {
            $arrColumns = $this->getColumnArr($storeName);
            foreach ($arrColumns as $col) {
                if(isset($this->arrRecords[intval($key)][$col]) && 
                $this->arrRecords[intval($key)][$col] === ProfileCacheConstants::NOT_FILLED) {
                    return true;
                }
            }
        }
        
        if (isset($this->arrRecords[intval($key)])) {
            foreach ($arrFields as $szColName) {
                $isDuplicateField = in_array($szColName, ProfileCacheConstants::$arrDuplicateFieldsMap); 
                if($suffix && false !== $isDuplicateField) {
                    $szColName = $szColName.ProfileCacheConstants::DUPLICATE_FIELD_DELIMITER.$suffix;
                }
              
                if(!array_key_exists($szColName, $this->arrRecords[intval($key)])) {
                    return false;
                }
            }
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
        if(!is_array($arrFields)) {
            return false;
        }
        $this->logThis(LoggingEnums::LOG_INFO, "Setting local cache for key : {$key}");
        foreach($arrFields as $col => $val) {
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
     * @param $enLogType
     * @param $Var
     * @return bool
     */
    private function logThis($enLogType,$Var)
    {
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE_LOGS) {
            return false;
        }

        if($enLogType > ProfileCacheConstants::LOG_LEVEL) {
            return false;
        }

        $logManager = LoggingManager::getInstance(ProfileCacheConstants::PROFILE_LOG_PATH);
        switch ($enLogType) {
            case LoggingEnums::LOG_INFO:
                    $logManager->logThis(LoggingEnums::LOG_INFO,$Var);
                break;
            case LoggingEnums::LOG_DEBUG:
                    $logManager->logThis(LoggingEnums::LOG_DEBUG,$Var);
                break;
            case LoggingEnums::LOG_ERROR:
                    $logManager->logThis(LoggingEnums::LOG_ERROR,$Var);
                break;
            default:
                break;
        }

        return true;
    }

    /**
     * @param string $st_Time
     */
    private function calculateResourceUsages($st_Time='', $preMsg="",$postMessage="")
    {
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

        if ($var < 1024)
            $mem =  $var." bytes";
        elseif ($var < 1048576)
            $mem =  round($var/1024,2)." kilobytes";
        else
            $mem = round($var/1048576,2)." megabytes";

        $timeTaken = $end_time - $st_Time;
        $usages = "{$preMsg} Memory usages : {$mem} & Time taken : {$timeTaken} {$postMessage}";

        $this->logThis(LoggingEnums::LOG_INFO, $usages);
    }

    /**
     * @return mixed
     */
    private function createNewTime()
    {
        return microtime(TRUE);
    }

    /**
     * @return bool
     */
    public function isCommandLineScript()
    {
        return (php_sapi_name() === ProfileCacheConstants::COMMAND_LINE);
    }

    /**
     * @param $szKey
     * @param $arrParams
     * @return bool
     */
    private function storeInCache($szKey, $arrParams)
    {
        //Set Hash Object
        $stTime = $this->createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                JsMemcache::getInstance()->setHashObject($szKey, $arrParams, ProfileCacheConstants::CACHE_EXPIRE_TIME,true);
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while setting up in cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache Update failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        $this->calculateResourceUsages($stTime,'Set : '," for key {$key}");

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
        if(isset($this->arrRecords[intval($key)])){
            unset($this->arrRecords[intval($key)]);
        }

        $szKey = ProfileCacheConstants::PROFILE_CACHE_PREFIX.$key;

        $stTime = $this->createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                JsMemcache::getInstance()->delete($szKey,true);
                $this->logDelCount();
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache Purge Failed,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache Purge Failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);

            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        $this->calculateResourceUsages($stTime,'Delete : '," for key {$key}");

        return $bSuccess;
    }

    /**
     * Function to count number of delete calls
     */
    private function logDelCount()
    {
        $key = 'cacheDeleteCount'.date('Y-m-d');
        JsMemcache::getInstance()->incrCount($key);

        $key .= '::'.date('H');
        JsMemcache::getInstance()->incrCount($key);
    }
    
    /**
     * This function will be used to check profile data
     * @param type $iProfileId
     * @param type $fields
     * @return type
     */
    public function checkProfileData($iProfileId,$fields="")
    {
      $data = JsMemcache::getInstance()->getHashAllValue(ProfileCacheConstants::PROFILE_CACHE_PREFIX.$iProfileId);
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
      if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
           return false;
        }
   
        if(false === $this->validateCriteria($criteria)) {
            return false;
        }
        
        //Get Relevant Fields
        $arrFields = $this->getRelevantFields($fields, $storeName);
        
        //Get Decorated keys
        $arrDecoratedKeys = array_map(array("ProfileCacheLib","getDecoratedKey"), $arrKey);
        
        //Get Records from Cache
        $arrResponse = JsMemcache::getInstance()->getMultipleHashFieldsByPipleline($arrDecoratedKeys ,$arrFields);
        
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
          $this->logThis(LoggingEnums::LOG_INFO, "Cache does not exist for {$key}");
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
      //Set Hash Object
        $stTime = $this->createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                $arrResult = JsMemcache::getInstance()->setMultipleHashByPipleline($arrData, ProfileCacheConstants::CACHE_EXPIRE_TIME, true);
                $bSuccess = true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while setting up in cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache Update failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                foreach($arrData as $szKey=>$value) {
                    $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                    $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                    $producerObj->sendMessage($queueData);
                }
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        $this->calculateResourceUsages($stTime,'MUlti-Set : '," for key {$key}");

        return $bSuccess;
    }
    
    /**
     * 
     * @param type $arrParams
     * @param type $storeName
     */
    private function addDuplicateSuffix($arrParams, $storeName)
    {
        if(0 === strlen($storeName)) {
            return $arrParams;
        }
        
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
    
    /**
     * 
     * @param type $storeName
     * @return string
     */
    private function getStoreSuffix($storeName)
    {
        $suffixName = null;
        if(false !== stristr($storeName, "ASTRO")) {
            $suffixName = "ASTRO";
        }
        return $suffixName;
    }
    
    /**
     * 
     * @param type $storeName
     * @return type
     */
    private function getColumnArr($storeName)
    {
        if($storeName == "JPROFILE") {
            $arrFields = ProfileCacheConstants::$arrJProfileColumns;
        } else if(false !== stristr($storeName, "EDUCATION")) {
            $arrFields = ProfileCacheConstants::$arrJProfile_EducationColumns;
        } else if(false !== stristr($storeName, "NATIVE")) {
            $arrFields = ProfileCacheConstants::$arrNativePlaceColumns;
        } else if(false !== stristr($storeName, "ASTRO")) {
            $arrFields = ProfileCacheConstants::$arrAstroDetailsColumns;
        } elseif (false !== stristr($storeName, "Contact")) {
            $arrFields = ProfileCacheConstants::$arrJProfileContact;
        } else if(false !== stristr($storeName, "HOBBY")) {
            $arrFields = ProfileCacheConstants::$arrJHobbyColumns;
        }
        else if(false !== stristr($storeName, "Alerts")) {
            $arrFields = ProfileCacheConstants::$arrJProfileAlertsColumn;
       }
        else if (false !== stristr($storeName, "YOUR_INFO_OLD") ){
            $arrFields = ProfileCacheConstants::$arrOldYourInfo;
        }
        return $arrFields;
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
        $szKey = $this->getDecoratedKey($key);
        
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
        //Set Hash Object
        $stTime = $this->createNewTime();
        $bSuccess = false;
        $iTryCount = 0;
        do {
            try{
                $result = JsMemcache::getInstance()->hdel($szKey, $arrFields, true);
                $bSuccess =  true;
            } catch (Exception $ex) {
                $bSuccess = false;
                ++$iTryCount;
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache failed while deleting Sub fields from cache,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                $this->logThis(LoggingEnums::LOG_INFO, "Profile Cache HDEL failed, Adding in MQ for key : {$szKey}");

                //Add into MQ
                $producerObj = new Producer();
                
                $iProfileID = substr($szKey, strlen(ProfileCacheConstants::PROFILE_CACHE_PREFIX));
                $queueData = array('process' =>MessageQueues::PROCESS_PROFILE_CACHE_DELETE,'data'=>array('type' => '','body'=>array('PROFILEID'=>$iProfileID)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($queueData);
            }
        } while ($bSuccess === false && $iTryCount < ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT);
        $this->calculateResourceUsages($stTime,'HDEL : '," for key {$key}");
        
        return $bSuccess;
    }
}
?>
