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
    public function isCached($criteria, $key, $fields)
    {
        //jsCacheWrapperException::logThis(new Exception("$fields is checked for profile cache"));

        if(false === $this->validateCriteria($criteria)) {
            return false;
        }

        if (isset($this->arrRecords[intval($key)]) && $this->checkFieldsAvailability($key, $fields)) {
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(0 === count($this->getFromLocalCache($key))) {
            unset($this->arrRecords[intval($key)]);
            return false;
        }

        //Check all fields specified in param fields is present in cache also, right now we are assuming all fields are cached together
        if (false === $this->checkFieldsAvailability($key, $fields)) {
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
        //Set Hash Object
        JsMemcache::getInstance()->setHashObject($szKey, $arrParams);
        //TODO : Update Local Cache also
        $this->updateInLocalCache($key, $arrParams);
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
        if(false === $this->isCached($szCriteria, $key, array_keys($paramArr))) {
            return ;
        }
        $arrData = $this->cacheThis($szCriteria, $key, $paramArr);

        //Now Process extraWhereCnd

        return $arrData;
    }

    /**
     * @param $iProfileID
     * @param $paramArr
     * @return bool
     */
    public function insertInCache($iProfileID, $paramArr)
    {
        $paramArr[ProfileCacheConstants::CACHE_HASH_KEY] = $iProfileID;
        $paramArr[ProfileCacheConstants::ACTIVATED_KEY] = 1;
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
        if (false === $this->validateCriteria($szCriteria)) {
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
        $this->arrRecords[intval($key)] = JsMemcache::getInstance()->getHashAllValue($this->getDecoratedKey($key));
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
}
?>
