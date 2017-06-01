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
     *
     * @var type 
     */
    private $arrDefaultValue = array(
                                    'AGE' => 'Y',
                                    'MSTATUS' => 'Y',
                                    'RELIGION' => 'Y',
                                    'CASTE' => 'Y',
                                    'COUNTRY_RES' => 'Y',
                                    'CITY_RES' => 'Y',
                                    'MTONGUE' => 'Y',
                                    'INCOME' => 'Y',
                                    'HARDSOFT' => 'Y',
                                    'COUNT' => 1,
                                );
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
        if(false === is_array($arrProfileIds)) {
            return ;
        }
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
        $bResult = $this->getDBConnection()->updateFilters($iProfileId, $updStr);
        
        if(true === $bResult) {
            $arrRecordData = $this->convertUptStrToArray($updStr);
            ProfileCacheLib::getInstance()->updateCache($arrRecordData, ProfileCacheConstants::CACHE_CRITERIA, $iProfileId, __CLASS__);
        }
        return $bResult;
    }
    
    /**
     * 
     * @param type $iProfileId
     * @param type $updStr
     */
    public function insertFilterEntry($iProfileId,$updStr)
    {
        $bResult = $this->getDBConnection()->updateFilters($iProfileId, $updStr);
        
        if(true === $bResult) {
            $arrRecordData = $this->convertUptStrToArray($updStr);
            ProfileCacheLib::getInstance()->insertInCache($iProfileId, $arrRecordData, __CLASS__);
        }
        return $bResult;
    }
    
    
    /**
     * 
     * @param type $profileId
     * @param type $whrStr
     * @param type $selectStr
     * @return type
     */
    public function fetchFilterDetails($profileId,$whrStr="",$selectStr="*")
    {
        //This function is not in use other then page6Action of register module
        //Which is legacy code, not in use at production
        return $this->getDBConnection()->fetchFilterDetails($profileId, $whrStr, $selectStr);
    }
    
    /**
     * 
     * @param type $iProfileId
     */
    public function setAllFilters($iProfileId)
    {
        $bResult = $this->getDBConnection()->setAllFilters($iProfileId);
        if(true === $bResult) {
            $arrRecordData = $this->arrDefaultValue;
            $arrRecordData['PROFILEID'] = $iProfileId;
            ProfileCacheLib::getInstance()->insertInCache($iProfileId, $arrRecordData, __CLASS__);
        }
        return $bResult;
    }
    
    /**
     * 
     * @param type $iProfileId
     * @param type $arrRecordData
     */
    public function updateRecord($iProfileId,$arrRecordData)
    {
        $bResult = $this->getDBConnection()->updateRecord($iProfileId, $arrRecordData);
        
        if(true === $bResult) {
            ProfileCacheLib::getInstance()->updateCache($arrRecordData, ProfileCacheConstants::CACHE_CRITERIA, $iProfileId, __CLASS__);
        }
        return $bResult;
    }
    
    /**
     * 
     * @param type $iProfileId
     * @param type $arrRecordData
     * @return type
     */
    public function insertRecord($iProfileId,$arrRecordData)
    {
        $bResult = $this->getDBConnection()->insertRecord($iProfileId, $arrRecordData);
        
        if(1 == $bResult) {
            ProfileCacheLib::getInstance()->insertInCache($iProfileId, $arrRecordData, __CLASS__);
        }
        return $bResult;
    }
    
    /**
     * 
     * @param type $field
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function fetchField($field,$limit='',$offset='')
    {
        return $this->getDBConnection()->fetchField($field, $limit, $offset);
    }
    
    /**
     * 
     * @param type $field
     * @param type $profileIdArr
     */
    public function updateField($field,$profileIdArr)
    {
        $bResult = $this->getDBConnection()->updateField($field, $profileIdArr);
        
        if(true === $bResult) {
            $objProfileCache = ProfileCacheLib::getInstance();
        
            foreach($profileIdArr as $key => $val) {
                $objProfileCache->removeFieldsFromCache($val['PROFILEID'], __CLASS__);
            }
        }
        return $bResult;        
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
