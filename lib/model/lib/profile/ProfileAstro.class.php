<?php

/**
 * Description of ProfileAstro
 * Library Class to handle Model for ASTRO_DETAILS Table
 *
 * @package     jeevansathi
 * @subpackage cache
 * @author      Kunal Verma
 * @created     23rd Sept 2016
 */
class ProfileAstro
{
    /**
     * Member Variable
     */

    /**
     * @var Static Instance of this class
     */
    private static $instance;

    /**
     * Object of Store class
     * @var instance of NEWJS_PROFILE|null
     */
    private static $objAstroDetailMysql = null;
    
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    private function __construct($dbname = "")
    {
        self::$objAstroDetailMysql = new NEWJS_ASTRO($dbname);
    }

    /**
     * To Stop clone of this class object
     */
    private function __clone()
    {
        
    }

    /**
     * To stop unserialize for this class object
     */
    private function __wakeup()
    {
        
    }

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
     * 
     * @param type $pid
     * @return type
     */
    public function getAstros($pid)
    {
        $bServedFromCache = false;
        $fields='COUNTRY_BIRTH,CITY_BIRTH,PROFILEID';
        $objProCacheLib = ProfileCacheLib::getInstance();
        
        if ($objProCacheLib->isCached('PROFILEID', $pid,$fields , __CLASS__)) {
            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);                       
            //so for that case also we are going to query mysql
            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
                if(strlen($result['CITY_BIRTH'])) {
                    $result['CITY_BIRTH'] = substr($result['CITY_BIRTH'], 0, 50);
                }
            }
            
            if($result && in_array(ProfileCacheConstants::NOT_FILLED, $result)){
                $result = array();
            }
        }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }

        //Get Records from Mysql
        $result = self::$objAstroDetailMysql->getAstros($pid);
        
        //Request to Cache this Record, on demand
        if(is_array($result) && count($result)) {
          $result['PROFILEID'] = $pid;
        }
        
        if ( is_array($result) && count($result) && false === $objProCacheLib->isCommandLineScript()) {
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result, __CLASS__);
        }
        
        if(0 === count($result)) {
            $dummyResult = array('PROFILEID'=>$pid, "LAGNA_DEGREES_FULL"=>ProfileCacheConstants::NOT_FILLED);
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        }
        
        return $result;
         
    }
    
    /**
     * 
     * @param type $pid
     * @param type $paramArr
     * @return type
     */
    public function update($pid, $paramArr = array())
    {
        return $this->replaceRecord($pid,$paramArr);
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function insertInAstroPullingDetails($profileid)
    {
        return self::$objAstroDetailMysql->insertInAstroPullingDetails($profileid);
    }
    
    /**
     * 
     * @param type $profileIds
     * @param type $fields
     * @param type $setWithProfileId
     * @return type
     */
    public function getAstroDetails($profileidArray, $fields, $setWithProfileId='')
    {
        if ($fields == '') $fields = "*";
        
        $bServedFromCache = false;
        $objProCacheLib = ProfileCacheLib::getInstance();
        
        $result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $profileidArray,$fields,__CLASS__);
        
        if (is_array($result) && false !== $result) {
            $bServedFromCache = true;
            $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
        }
        
        if($result && count($result)) {
            foreach($result as $k=>$v){
                if($v['LAGNA_DEGREES_FULL'] === ProfileCacheConstants::NOT_FILLED) {
                    unset($result[$k]);
                }
            }
            
            $result = array_values($result);
            if(0 === count($result)) {
                $result = null;
            }
                
        }
        
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            if($setWithProfileId) {
                $arrOut = array();
                foreach($result as $k=>$val) {
                    $arrOut[$val['PROFILEID']] = $val;
                }
                $result = $arrOut;
            }
            return $result;
        }
        
        //Get Records from Mysql
        $result = self::$objAstroDetailMysql->getAstroDetails($profileidArray, $fields,$setWithProfileId);
        
        if(is_null($result) || (is_array($result) && count($result) !== count($profileidArray))) {
            $arrDataNotExist = array();
            foreach($result as $key=>$val){
                $arrDataNotExist[] = $val['PROFILEID'];
            }
            $arrDataNotExist = array_diff($profileidArray, $arrDataNotExist);
            $dummyArray = array();
            foreach($arrDataNotExist as $k => $v){
                $dummyArray[] = array('PROFILEID'=>$v, "LAGNA_DEGREES_FULL"=>ProfileCacheConstants::NOT_FILLED);
            }
        }
        
        if(is_array($result) && count($result)) {
            $objProCacheLib->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $result, __CLASS__);
        }
        
        if($dummyArray && is_array($dummyArray) && count($dummyArray)) {
            $objProCacheLib->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $dummyArray, __CLASS__);
        }
                
        return $result;
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function getIfAstroDetailsPresent($profileid)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();
        $fields = 'HAVE_ASTRO';
        $bServedFromCache = false;
        
        if ($objProCacheLib->isCached('PROFILEID', $profileid,$fields , __CLASS__)) {
            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);                       
            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }
            
            $result = $result['HAVE_ASTRO'];
            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            if($result && in_array($result, $validNotFilled)){
                $result = '0';
            }
        }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        
        //Get Data from Mysql
        $result = self::$objAstroDetailMysql->getIfAstroDetailsPresent($profileid);
        
        
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['HAVE_ASTRO'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function getIfHoroPresent($profileid)
    {
        if($this->getIfAstroDetailsPresent($profileid)){
            return true;
        }
        
        //return self::$objAstroDetailMysql->getIfHoroPresent($profileid);
    }
    
    /**
     * 
     * @param type $type
     * @param type $pid
     * @return type
     */
    public function updateType($type,$pid)
    {
        return $this->updateRecord($pid, array('TYPE'=>$type));         
    }
    
    /**
     * 
     * @param type $pid
     * @return type
     */
    public function deleteEntry($pid)
    {
        $bResult = self::$objAstroDetailMysql->deleteEntry($pid);
        if(true === $bResult) {
            ProfileCacheLib::getInstance()->removeFieldsFromCache($pid, __CLASS__);
        }
        return $bResult;
    }
    
    /**
     * 
     * @param type $iProfileID
     * @param type $arrRecordData
     * @return type
     */
    public function updateRecord($iProfileID, $arrRecordData)
    {
        $bResult = self::$objAstroDetailMysql->updateRecord($iProfileID, $arrRecordData);
        if(true === $bResult) {
            ProfileCacheLib::getInstance()->updateCache($arrRecordData, ProfileCacheConstants::CACHE_CRITERIA, $iProfileID, __CLASS__);
        }
    
        return $bResult;
    }
    
    /**
     * 
     * @param type $iProfileID
     * @param type $arrRecordData
     * @return type
     * 
     */
    public function replaceRecord($iProfileID, $arrRecordData) 
    {
        $bResult = self::$objAstroDetailMysql->replaceRecord($iProfileID, $arrRecordData);
        if(true === $bResult) {
            ProfileCacheLib::getInstance()->updateCache($arrRecordData, ProfileCacheConstants::CACHE_CRITERIA, $iProfileID, __CLASS__);
        }
        
        return $bResult;
    }
    
    /**
     * 
     * @param type $funName
     */
    private function logCacheConsumeCount($funName)
    {return;
        $key = 'cacheConsumption' . '_' . date('Y-m-d');
        JsMemcache::getInstance()->hIncrBy($key, $funName);

        JsMemcache::getInstance()->hIncrBy($key, $funName . '::' . date('H'));
    }

}

?>
