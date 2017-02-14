<?php

/**
 * Description of IgnoredProfileCacheLib
 * Library Class to handle IgnoredProfile Caching Logic
 *
 * @package     jeevansathi
 * @author      Sanyam Chopra
 * @created     16th September 2016
 */
class IgnoredProfileCacheLib 
{
	/**
     * @var Object
     */
    private static $instance = null;

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

    public function isCached($key,$fromUpdate=false)
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
           return false;
        }

        //COPIED FROM KUNAL'S CODE. CHECK IF TO BE USED.
        //if from command line and not for update then not use cache
        // if ($this->isCommandLineScript() && false === $fromUpdate) {
        //     return false;
        // }

        //For Update case check only profile exist in cache or not
        //INCORPORATE THIS WHEN WE DO UPDATE
        if (isset($this->arrRecords[intval($key)]) && $fromUpdate) {
            return true;
        }

        //Get Record and Store in Local Cache
        $this->storeInLocalCache($key);

        //If array count is zero then record is not cached
        if(0 === count($this->getFromLocalCache($key))) {
            unset($this->arrRecords[intval($key)]);
            //$this->logThis(LoggingEnums::LOG_INFO, "Cache Miss from first point for Criteria {$criteria} : {$key}");
            return false;
        }
        return true;
    }

     /**
     * @param $key
     */
    private function storeInLocalCache($key)
    {
        $stTime = $this->createNewTime();
        $this->arrRecords[intval($key)] = JsMemcache::getInstance()->getSetsAllValue($key);        
        //$this->calculateResourceUsages($stTime,'Get : '," for key {$key}");
    }

    /**
     * @return mixed
     */
    private function createNewTime()
    {
        return microtime(TRUE);
    }

     /**
     * @param $key
     * @return mixed
     */
    private function getFromLocalCache($key)
    {
        return $this->arrRecords[intval($key)];
    }

    //This function takes the key and checks if the key exists and then accordingly fetches and returns the value array
    public function getSetsAllValue($key)
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
           return false;
        }
        //To check if key exists
        $keyExists = JsMemcache::getInstance()->keyExist($key);
        if($keyExists == 0)
        {
        	return ignoredProfileCacheConstants::NO_KEY;
        }

        $arrResponse = JsMemcache::getInstance()->getSetsAllValue($key);
        return $arrResponse;
    }

    //This function stores data in the redis cache 
    public function storeDataInCache($key,$arr,$extraParameter='')
    {
        if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
        if($extraParameter == "1")
        {
            foreach($arr as $ignoredProfiles=>$val)
            {
                $valArr[] = $ignoredProfiles; 
            }
        }
        else if($extraParameter == "2")
        {
            $valArr = $arr;
        }
        else
        {
            $arr = trim($arr," ");
            $valArr  = explode(" ",$arr);
        }
    	$resultVal = JsMemcache::getInstance()->storeDataInCacheByPipeline($key,$valArr);    	
    	return $resultVal;
    }

    //This function removes an ignoredProfileId from three different keys. (profileId_all,profileId_byMe,ignoredProfileId_all)
    public function deleteDataFromCache($profileid,$ignoredProfileid)
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
    	$pidKey1 = $profileid.ignoredProfileCacheConstants::ALL_DATA;
    	$pidKey2 =  $profileid.ignoredProfileCacheConstants::BYME_DATA;
        $pidKey3 = $ignoredProfileid.ignoredProfileCacheConstants::ALL_DATA;
        if(JsMemcache::getInstance()->keyExist($pidKey3))
        {
            JsMemcache::getInstance()->deleteSpecificDataFromCache($pidKey3,$profileid);   
        }
    	JsMemcache::getInstance()->deleteSpecificDataFromCache($pidKey1,$ignoredProfileid);
    	JsMemcache::getInstance()->deleteSpecificDataFromCache($pidKey2,$ignoredProfileid);
    }

    //This function adds an ignoredProfileId to three different keys. (profileId_all,profileId_byMe,ignoredProfileId_all)
    public function addDataToCache($profileid,$ignoredProfileid)
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
    	$pidKey1 = $profileid.ignoredProfileCacheConstants::ALL_DATA;
    	$pidKey2 =  $profileid.ignoredProfileCacheConstants::BYME_DATA;
        $pidKey3 = $ignoredProfileid.ignoredProfileCacheConstants::ALL_DATA;
        if(JsMemcache::getInstance()->keyExist($pidKey3))
        {
            JsMemcache::getInstance()->addDataToCache($pidKey3,$profileid);   
        }
    	JsMemcache::getInstance()->addDataToCache($pidKey1,$ignoredProfileid);
    	JsMemcache::getInstance()->addDataToCache($pidKey2,$ignoredProfileid);
    }

    //This function checks if a particular value exists in the redis corresponding to a given key and accordingly returns the boolean response
    public function checkIfDataExists($profileid,$ignoredProfileid,$suffix="")
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
        if($suffix == "byMe")
        {
           $pidKey = $profileid.ignoredProfileCacheConstants::BYME_DATA; 
        }
    	else
        {
             $pidKey = $profileid.ignoredProfileCacheConstants::ALL_DATA; 
        }
    	$keyExists = JsMemcache::getInstance()->keyExist($pidKey);
        if($keyExists == 1)
    	{
    		$response = JsMemcache::getInstance()->checkDataInCache($pidKey,$ignoredProfileid);
    		return $response;
    	}
    	else
    	{
    		return ignoredProfileCacheConstants::NO_KEY;
    	}
    }

    public function getCountFromCache($profileID)
    {
    	if (false === ignoredProfileCacheConstants::ENABLE_PROFILE_CACHE) {
            return false;
        }
    	$pidKey = $profileID.ignoredProfileCacheConstants::BYME_DATA;
    	$keyExists = JsMemcache::getInstance()->keyExist($pidKey);
    	if($keyExists == 1)
    	{
    		$response = JsMemcache::getInstance()->getCountFromCache($pidKey);
    		return $response;
    	}
    	else
    	{
    		return ignoredProfileCacheConstants::NO_KEY;
    	}
    }

	//This function fetches specific values from cache based on the condition specified.
    public function getSpecificValuesFromCache($viewerKey,$profileIdArr)
    {
        $keyExists = JsMemcache::getInstance()->keyExist($viewerKey);
    	if($keyExists == 1)
    	{
    		$response = JsMemcache::getInstance()->getSpecificValuesFromCache($viewerKey,$profileIdArr);
    		return $response;
    	}
    	else
    	{
    		return ignoredProfileCacheConstants::NO_KEY;
    	}
    }
}
?>