<?php

/**
 * Description of dppSuggestionsCacheLib
 * Library Class to handle dppSuggestions Caching Logic
 *
 * @package     jeevansathi
 * @author      Sanyam Chopra
 * @created     27th September 2016
 */
class dppSuggestionsCacheLib 
{
	/**
     * @var Object
     */
    private static $instance = null;
    private static $enableCaching = true;

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
     * Get Instance
     * @return Object of IgnoredProfileCacheLib
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            $className =  __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    public function getHashValueForKey($pidKey)
    {
        $keyExists = JsMemcache::getInstance()->keyExist($pidKey);
        if($keyExists == 0)
        {
            return "noKey";
        }
        $resultArr = JsMemcache::getInstance()->getHashAllValue($pidKey);
        return $resultArr;
    }

    public function storeHashValueForKey($pidKey,$trendsArr)
    {
        if(false === $this->enableCaching)
        {
            return false;
        }
        $resultStr = JsMemcache::getInstance()->setHashObject($pidKey,$trendsArr);
        return $resultStr;
    }

}
?>