<?php
/**
 * Description of ProfileCacheLib
 * Library Class to handle Profile Caching Logic
 *
 * @package     jeevansathi
 * @author      Esha Jain
 * @created     8th March 2017
 */

class PictureNewCacheLib
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
    public function isCacheable($paramArr,$storeName)
    {
        if (PictureNewCacheConstants::ENABLE_CACHE === false)
        {
                return false;
        }
        $this->logThis(LoggingEnums::LOG_INFO,"Cache Criteria Check : ".var_export($paramArr,true)." for class". $storeName);
        if ($this->isCommandLineScript())
        {
            return false;
        }
        if($this->validateCriteria($paramArr) === false)
        {
            return false;
        }
	return true;
    }
    public function isCached($paramArr, $storeName='')
    {
	$key = $paramArr['PROFILEID'];

        //For Update case check only profile exist in cache or not
        if (isset($this->arrRecords[intval($key)])) 
	{
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(count($this->getFromLocalCache($key)) === 0) 
	{
            unset($this->arrRecords[intval($key)]);
            $this->logThis(LoggingEnums::LOG_INFO, "Cache Miss from first point for ".var_export($paramArr,true));
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
    public function cacheThis($key, $arrParams)
    {
        $szKey = $this->getDecoratedKey($key);

        if (count($arrParams) === 0) 
	{
            return false;
        }
        
        //Store in Cache
        $this->storeInCache($szKey, $arrParams);
        
	$this->updateInLocalCache($key, $arrParams);
        return true;
    }

    /**
     * @param $szCriteria
     * @param $key
     * @param $fields
     * @param null $arrExtraWhereClause
     * @return array|bool
     */
    public function get($paramArr, $storeName="")
    {
        $arrData = $this->getFromLocalCache($paramArr['PROFILEID']);

        //If data is false then return false
        if(false === $arrData) {
            return false;
        }
	return $arrData;
    }

    /**
     * @param $key
     * @return string
     */
    private function getDecoratedKey($key)
    {
        return "PIC_NEW:".trim($key);
    }

    /**
     * @param $szCriteria
     * @return bool
     */
    private function validateCriteria($paramArr)
    {
	$keys = array_keys($paramArr);
	if(in_array(PictureNewCacheConstants::CACHE_CRITERIA,$keys) &&count(array_diff($keys,PictureNewCacheConstants::$POSSIBLE_CRITERIA))<=0)
		return true;
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
	$data = JsMemcache::getInstance()->getHashAllValue($this->getDecoratedKey($key));
	foreach($data as $k=>$v)
		$cachedData[$k]=json_decode($v,true);
        $this->arrRecords[intval($key)] = $cachedData;
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
     * @param $arrData
     * @param $arrExtraWhereClause
     */
    public function processWhere($arrData,$paramArr)
    {
	unset($paramArr['PROFILEID']);

        if(!is_array($paramArr) || 0 === count($paramArr))
            return $arrData;

        if(array_key_exists("ORDERING",$paramArr))
	{
		$result[] = $arrData[$paramArr['ORDERING']];

		if($result===false)
			return false;

		if(array_key_exists("PICTUREID",$paramArr))
		{
			if($arrData[$paramArr['ORDERING']]['PICTUREID']!=$paramArr['PICTUREID'])
				return false;
		}
		return $result;
	}

	if(array_key_exists("PICTUREID",$paramArr))
	{
		foreach($arrData as $k=>$v)
		{
			if($v['PICTUREID']==$paramArr['PICTUREID'])
			{
				$arr[]=$v;
				return $arr;
			}
		}
	}

        return false;
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

        $szKey = $this->getDecoratedKey($key);

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
                $this->logThis(LoggingEnums::LOG_INFO, "Picture new Cache Purge Failed,Retrying again and its count : {$iTryCount}");
            }
            //If Attempt Count Reached to Max Attempt Count
            if ($iTryCount === ProfileCacheConstants::CACHE_MAX_ATTEMPT_COUNT) {
                $this->logThis(LoggingEnums::LOG_INFO, "Picture new Cache Purge Failed, Adding in MQ for key : {$szKey}");
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
