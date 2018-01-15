<?php

/**
 * Description of RequestCallBack
 * Library for handling RequestCallBack Feature, it includes iteraction with model
 * 
 * @author Kunal Verma
 * @created 31st March 2016
 */

class RequestCallBack
{
  /**
   * Member Variables
   */
  
  /**
   * This Varaible will hold Current Profileid
   * @access Private
   */
  private $objProfile = null;
  
  /**
   * This Varaible will hold Current Profileid
   * @access Private
   */
  private $iProfileID = null;
  
  /**
   * Const Cached Suffix
   */
  const CACHED_KEY_SUFFIX = '__rcb';
  
  /**
   * Max Number of days after which we show rcb window(or communication)
   */
  const MAX_DAYS = 15; 
  
  /**
   * Cache expire time (24 hour)
   */
  const MAX_CACHE_AGE = 86400;
  
  /**
   * Logged In Profile is free or not
   */
  private $bIsFreeMember = null;
  
  /**
   * Member Function Definition
   */
  
  /**
   * __construct
   * @param LoggedInProfile $objLoggedInProfile
   * @access private
   */
  public function __construct($objLoggedInProfile)
  {
    $this->objProfile = $objLoggedInProfile;
    $this->bIsFreeMember = CommonFunction::isPaid($this->objProfile->getSUBSCRIPTION()) ? false : true; 
    $this->iProfileID = $this->objProfile->getPROFILEID();
  }
  
  /**
   * ifCached
   * @return boolean
   * @access private
   */
  private function ifCached()
  {
    $memObject=JsMemcache::getInstance();
    if($memObject->get($this->iProfileID.self::CACHED_KEY_SUFFIX)){
      $arrResult = $memObject->get($this->iProfileID.self::CACHED_KEY_SUFFIX);
      unset($memObject);
      return $arrResult;
    }
    
    return false;
  }
  
  /**
   * cachedThis
   * @param Array $arrResult
   * @access private
   */
  private function cachedThis($arrResult)
  {
    $memObject=JsMemcache::getInstance();
    $memObject->set($this->iProfileID.self::CACHED_KEY_SUFFIX,$arrResult,self::MAX_CACHE_AGE);
    unset($memObject);
  }
 
  /**
   * getRCBStatus
   * Check wheather we require to push for request callback feature or not, 
   * if yes then return true else false
   * @return boolean
   * @access public
   */
  public function getRCBStatus()
  {
    /**
     * If free member then RCB is not required
     */
    if (false === $this->bIsFreeMember) {
      return false;
    }
    
    $arrResult = $this->getData(); 
    
    if (false === $arrResult) {
      //First Time User
      return true;
    }
    
    $lastUpdateDate = new DateTime($arrResult['TIMESTAMP']);
    $currentDate = new DateTime(date("Y-m-d H:i:s"));
    
    $interval = $currentDate->diff($lastUpdateDate);
    
    //Check for Max days
    if ($interval->days >= self::MAX_DAYS) {
      return true;
    }
    
    return false;
  }
  
  /**
   * Function to get data from cache if available else from model and also cache the data
   * @return Array
   * @access private
   */
  private function getData()
  {
    //Check Cache First
    $arrResult = $this->ifCached();
    
    if (false === $arrResult) {
      $storeObj = new PROFILE_RCB_RESPONSE;
      $arrResult = $storeObj->getLatestRecord($this->iProfileID);
      unset($storeObj);
      
      $this->cachedThis($arrResult);
    }

    return $arrResult;
  }
  
  /**
   * updateThis
   * @param type $bStatus
   */
  public function updateThis($bStatus)
  {
    $timeStamp = date("Y-m-d H:i:s");
    $storeObj = new PROFILE_RCB_RESPONSE;
    $rowCount = $storeObj->insertRecord($this->iProfileID,$timeStamp,$bStatus);
    
    $arrResult = array('PROFILEID'=>$this->iProfileID,'TIMESTAMP'=>$timeStamp,'RESPONSE'=>$bStatus);
    $this->cachedThis($arrResult);
    unset($storeObj);
  }
}