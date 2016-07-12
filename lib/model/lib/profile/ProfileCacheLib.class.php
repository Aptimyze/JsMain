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
     * @param $value
     * @param $fields
     * @return bool
     */
    public function isCached($criteria, $key, $fields)
    {
        //jsCacheWrapperException::logThis(new Exception("$fields is checked for profile cache"));

        if(false === $this->validateCriteria($criteria)) {
            return false;
        }

        if (isset($this->arrRecords[intval($key)])) {
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(0 === count($this->getFromLocalCache($key))) {
            unset($this->arrRecords[intval($key)]);
            return false;
        }
        return true;
    }

    /**
     * cacheThis
     * @param $szCriteria
     * @param $key
     * @param $result
     * @return bool
     */
    public function cacheThis($szCriteria, $key, $result)
    {
        //If Criteria is other then PROFILEID then return false
        if (false === $this->validateCriteria($szCriteria)) {
            return false;
        }

        //Prepend Prefix on key
        $szKey = $this->getDecoratedKey($key);
        $result = $this->getRelevantParams($result);

        if (0 === count($result)) {
            return false;
        }
        //Set Hash Object
        JsMemcache::getInstance()->setHashObject($szKey, $result);
        //TODO : Update Local Cache also
        return true;
    }

    public function updateCache($paramArr, $szCriteria, $value, $extraWhereCnd)
    {
        if (false === $this->validateCriteria($szCriteria)) {
            return false;
        }
    }

    public function insertInCache($paramArr)
    {

    }

    /**
     * @param $criteria
     * @param $key
     * @param $fields
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

        return array_intersect(ProfileCacheConstants::$arrHashSubKeys, $arrFields);
    }

    /**
     * @param $arrOut
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
}
?>
