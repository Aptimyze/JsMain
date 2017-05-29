<?php

/**
 * Description of ProfileFilter
 * Library Class to handle Model for NEWJS_FILTERS Table
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @created     28th May 2017
 */
class ProfileFilter
{
    /**
     * Member Variable
     */

    /**
     * Object of Store class
     * @var instance of NEWJS_FILTER|null
     */
    private $objFilterMySql = null;
    
    /**
     *
     * @var type 
     */
    private $szDbName = null;
    
    /**
     *
     * @var type 
     */
    private $szActiveDbName = null;
    
    /**
     * 
     */
    const DELAYED_DB_CONNETION = true;
    
    
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($szDbName = "")
    {
        $szDbName=$szDbName?$szDbName:"newjs_master";
        $this->szDbName = $szDbName;
        
        if(false == self::DELAYED_DB_CONNETION) { 
            $this->initConnection();
        }
    }
    
    /**
     * 
     */
    private function initConnection()
    {
        if(is_null($this->objFilterMySql) || $this->szActiveDbName != $this->szDbName) {
            $this->szActiveDbName = $this->szDbName;
            $this->objFilterMySql = new NEWJS_FILTER($this->szActiveDbName);
        }
    }
    
    /**
     * 
     */
    private function getDBConnection()
    {
        //TODO Add Try catch
        if(self::DELAYED_DB_CONNETION) { 
            $this->initConnection();
        }
       
        return $this->objFilterMySql;
    }
    
    /**
     * 
     * @param type $iProfileId
     */
    public function fetchEntry($iProfileId)
    {
        $bServedFromCache = false;
        $objProCacheLib =ProfileCacheLib::getInstance();
        
        if ($objProCacheLib->isCached(ProfileCacheConstants::CACHE_CRITERIA, $iProfileId, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__)) {
            //Get From Cache
            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $iProfileId, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__);

            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }
            
            if(in_array(ProfileCacheConstants::NOT_FILLED, $result)) {
                $result = null;
            }
        }
        
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            //TODO
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        
        //Get Records from Mysql
        $result = $this->getDBConnection()->fetchEntry($iProfileId);
        
        if(is_null($result)) { 
            $dummyResult = array('PROFILEID'=>$iProfileId, "AGE"=>ProfileCacheConstants::NOT_FILLED);
        }
        
        if (is_array($result) && false === $objProCacheLib->isCommandLineScript()) {
            $result['PROFILEID'] = $iProfileId;
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result, __CLASS__);
        }
        
        if (is_array($dummyResult) && false === $objProCacheLib->isCommandLineScript()) {
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        }
        return $result;
    }
    
    /**
     * 
     * @param type $arrProfileIds
     */
    public function fetchFilterDetailsForMultipleProfiles($arrProfileIds)
    {
        $bServedFromCache = false;
        $objProCacheLib = ProfileCacheLib::getInstance();
        
        $result = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $arrProfileIds, ProfileCacheConstants::ALL_FIELDS_SYM,__CLASS__);
        
        if (is_array($result) && false !== $result) {
            $bServedFromCache = true;
            $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
        }
        
        if(is_array($result) && count($result)) {
            $tempResult = array();
            foreach($result as $k=>$v){
                $tempResult[$v['PROFILEID']] = $v;
                unset($result[$k]);
            }
            $result = $tempResult;
        }
        
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        
        //Get Records from Mysql
        $result = $this->getDBConnection()->fetchFilterDetailsForMultipleProfiles($arrProfileIds);
        
        if(is_array($result) && count($result) !== count($arrProfileIds)) {
            $arrDataNotExist = array();
            foreach($result as $key=>$val){
                $arrDataNotExist[] = $val['PROFILEID'];
            }
            $arrDataNotExist = array_diff($arrProfileIds, $arrDataNotExist);
            $dummyArray = array();
            foreach($arrDataNotExist as $k => $v){
                $dummyArray[] = array('PROFILEID'=>$v, "AGE"=>ProfileCacheConstants::NOT_FILLED);
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
     * @param type $iProfileId
     * @param type $updStr
     */
    public function updateFilters($iProfileId,$updStr)
    {
        
    }
    
    /**
     * 
     * @param type $iProfileId
     * @param type $updStr
     */
    public function insertFilterEntry($iProfileId,$updStr)
    {
        
    }
    
    
    public function fetchFilterDetails($profileId,$whrStr="",$selectStr="*")
    {
        
    }
    
    
    public function setAllFilters($profile)
    {
        
    }
    
    public function updateRecord($iProfileID,$arrRecordData)
    {
        
    }
    
    public function insertRecord($iProfileID,$arrRecordData)
    {

    }
    
    public function fetchField($field,$limit='',$offset='')
    {
        
    }
    
    public function updateField($field,$profileIdArr)
    {
        
    }
    
    /**
     * 
     * @param type $uptStr
     * @return type
     */
    private function convertUptStrToArray($uptStr) 
    {
        $arrayColumns = explode(",", $uptStr);
        $arrOut = array();
        foreach ($arrayColumns as $params) {
            $arrTokens = explode("=", $params);
            $szVal = $arrTokens[1];
            $szVal = str_replace(array('\'', '"', "\\"), "", $szVal);
            $arrOut[trim($arrTokens[0])] = trim($szVal);
        }
        return $arrOut;
    }
    
    /**
     * 
     * @param type $funName
     */
    private function logCacheConsumeCount($funName)
    {
        $key = 'cacheConsumption' . '_' . date('Y-m-d');
        JsMemcache::getInstance()->hIncrBy($key, $funName);

        JsMemcache::getInstance()->hIncrBy($key, $funName . '::' . date('H'));
    }
}

?>
