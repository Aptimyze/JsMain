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
     * @return bool
     */
    public function isCached($criteria, $key, $fields,$fromUpdate=false)
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

        if (isset($this->arrRecords[intval($key)]) && $this->checkFieldsAvailability($key, $fields)) {
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(0 === count($this->getFromLocalCache($key))) {
            unset($this->arrRecords[intval($key)]);
            $this->logThis(LoggingEnums::LOG_INFO, "Cache Mis for Criteria {$criteria} : {$key}");
            return false;
        }

        //Check all fields specified in param fields is present in cache also, right now we are assuming all fields are cached together
        if (false === $this->checkFieldsAvailability($key, $fields)) {
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
    public function cacheThis($szCriteria, $key, $arrParams)
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
        $arrParams = $this->getRelevantParams($arrParams);

        if (0 === count($arrParams)) {
            return false;
        }

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
     * @param $key
     * @param $extraWhereCnd
     * @return bool|void
     */
    public function updateCache($paramArr, $szCriteria, $key, $extraWhereCnd = "")
    {
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        $bUpdateFromMysql = false;
        if(false === $this->isCached($szCriteria, $key, array_keys($paramArr), true)) {
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
            $result = $this->cacheThis($szCriteria, $key, $paramArr);
        }

        return $result;
    }

    /**
     * @param $iProfileID
     * @param $paramArr
     * @return bool
     */
    public function insertInCache($iProfileID, $paramArr)
    {
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        $paramArr[ProfileCacheConstants::CACHE_HASH_KEY] = $iProfileID;
        if(false === isset($paramArr[ProfileCacheConstants::ACTIVATED_KEY])) {
            $paramArr[ProfileCacheConstants::ACTIVATED_KEY] = 1;
        }

        return $this->cacheThis(ProfileCacheConstants::CACHE_HASH_KEY, $iProfileID, $paramArr);
    }

    /**
     * @param $szCriteria
     * @param $key
     * @param $fields
     * @param null $arrExtraWhereClause
     * @return array|bool
     */
    public function get($szCriteria, $key, $fields, $arrExtraWhereClause = null)
    {
        if (false === ProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }

        if (false === $this->validateCriteria($szCriteria)) {
           return false;
        }

        if (false === $this->isCached($szCriteria, $key, $fields)) {
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

        $arrFields = $this->getRelevantFields($fields);
        $arrOut = array();

        foreach ($arrFields as $k) {
            $arrOut[$k] = $arrData[$k];
        }

        return $arrOut;
    }

    /**
     * getRelevantParams
     *
     * @param $arrResult
     * @return array
     */
    private function getRelevantParams($arrResult)
    {
        return $arrResult;

        $arrOut = array();
        $arrAllowedHashKeys = ProfileCacheConstants::$arrHashSubKeys;

        foreach ($arrAllowedHashKeys as $key) {
            if (isset($arrResult[$key])) {
                $arrOut[$key] = $arrResult[$key];
            }
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
    private function getRelevantFields($arrFields)
    {
        if(is_string($arrFields) && $arrFields == ProfileCacheConstants::ALL_FIELDS_SYM) {
            $arrFields = ProfileCacheConstants::$arrHashSubKeys;
        } else if (is_string($arrFields) && $arrFields != ProfileCacheConstants::ALL_FIELDS_SYM) {
            $arrFields = explode(',',$arrFields);
        }
        //TODO: If $arrFields is not an array, handle this case  
        return array_intersect(ProfileCacheConstants::$arrHashSubKeys, $arrFields);
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
    private function checkFieldsAvailability($key, $fields)
    {
        $arrAllowableFields = $this->getRelevantFields($fields);

        if ($fields == ProfileCacheConstants::ALL_FIELDS_SYM &&
            count($this->getFromLocalCache($key))  !== count($arrAllowableFields)
        ) {
            return false;
        } else if ($fields !== ProfileCacheConstants::ALL_FIELDS_SYM) {
            $arrFields = $fields;
            if(is_string($fields)) {
                $arrFields = explode(",", $fields);
            }
            foreach ($arrFields as $szColName) {
                if(!in_array($szColName, $arrAllowableFields)) {
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
    public function storeInCache($szKey, $arrParams)
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
        if (is_array($Var)) {
            foreach($Var as $k => $iProfileID) {
                $this->purge($iProfileID);
            }
        } else {
            $this->purge($Var);
        }
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
}
?>
