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
    const DELAYED_DB_CONNETION = false;
    
    
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
        
    }
    
    /**
     * 
     * @param type $arrProfileIds
     */
    public function fetchFilterDetailsForMultipleProfiles($arrProfileIds)
    {
        
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

}

?>
