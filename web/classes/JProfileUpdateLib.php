<?php
/**
 * Description of JProfileUpdateLib
 * This is a wrapper library on store classes 
 * To update JPROFILE* Stores (like JPROFILE,JPROFILE_CONTACT,JPROFILE_ALERTS erc),
 * It will be used in Non-symfony code for warpping all Queries which are Updating JPROFILE* Tables
 * 
 * @author Kunal Verma
 * @created 26th May 2016
 */
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

/**
 * JProfileUpdateLib Wrapper Library
 */
class JProfileUpdateLib
{
  /**
   *
   * @var Object 
   */
  private static $instance = null;
  
  /**
   * JPROFILE Store Object
   * @var Object 
   */
  private $objJProfileStore = null;
  
  /**
   * JPROFILE_EDUCATION Store Object
   * @var Object 
   */
  private $objProfileEducationStore = null;
  
  /**
   * JPROFILE_CONTACT Store Object
   * @var Object 
   */
  private $objProfileContactStore = null;
  
  /**
   * JPROFILE_CONTACT Store Object
   * @var Object 
   */
  private $objProfileHobbyStore = null;

  /**
   * JP_NTIME Store Object
   * @var Object
   */
  private $objProfileNTimesStore = null;

  /**
   * JP_CHRISTIAN Store Object
   * @var Object
   */
  private $objProfileChristianStore = null;

  /**
   * ASTRO_DETAILS Store Object
   * @var Object
   */
  private $objProfileAstroDetailsStore = null;

  /**
   * HOROSCOPE_FOR_SCREEN Store Object
   * @var Object
   */
  private $objProfileHoroscopeForScreenStore = null;

  /**
   * HOROSCOPE_FOR_SCREEN Store Object
   * @var Object
   */
  private $objProfileAlertStore = null;
  /**
   *
   * @var String 
   */
  private $currentDBName = null;
  
  /**
   * Constructor function
   */
  private function __construct($dbname="") 
  {
    $this->currentDBName = $dbname;
    $this->objJProfileStore = new JPROFILE($dbname);
    $this->objProfileEducationStore = ProfileEducation::getInstance($dbname);
    $this->objProfileContactStore = new ProfileContact($dbname);
    $this->objProfileHobbyStore = new JHOBBYCacheLib($dbname);
    $this->objProfileNTimesStore = new NEWJS_JP_NTIMES($dbname);
    $this->objProfileChristianStore = new NEWJS_JP_CHRISTIAN($dbname);
    $this->objProfileAstroDetailsStore = ProfileAstro::getInstance($dbname);
    $this->objProfileHoroscopeForScreenStore = new NEWJS_HOROSCOPE_FOR_SCREEN($dbname);
    $this->objProfileAlertStore = new JprofileAlertsCache($dbname);

  }
  /**
   * __destruct
   */
  public function __destruct() {
    unset($this->objJProfileStore);
    unset($this->objProfileContactStore);
    unset($this->objProfileEducationStore);
    unset($this->objProfileHobbyStore);
    unset($this->objProfileNTimesStore);
    unset($this->objProfileChristianStore);
    unset($this->objProfileAstroDetailsStore);
    unset($this->objProfileHoroscopeForScreenStore);
    unset($this->objProfileAlertStore);
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
   * Current DB Name like newjs_master
   * @return type
   */
  public function getCurrentDBName()
  {
    return $this->currentDBName?$this->currentDBName:"newjs_master";
  }
  
  /**
   * Get Instance 
   * @return Object of JProfileUpdateLib
   */
  public static function getInstance($dbname="")
  {
		if (null === self::$instance) {
      self::$instance = new JProfileUpdateLib($dbname);
    }
    
    //Compare Current DB Name and if its different changeConnection
    //and set new connection with desired dbname
    if(self::$instance->currentDBName !== $dbname) {
      self::$instance->currentDBName = $dbname;
      self::$instance->objJProfileStore->setConnection($dbname);
      self::$instance->objProfileEducationStore = ProfileEducation::getInstance($dbname);
      self::$instance->objProfileContactStore->setConnection($dbname);
      self::$instance->objProfileHobbyStore->setConnection($dbname);
      self::$instance->objProfileNTimesStore->setConnection($dbname);
      self::$instance->objProfileChristianStore->setConnection($dbname);
      self::$instance->objProfileAstroDetailsStore = ProfileAstro::getInstance($dbname);
      self::$instance->objProfileHoroscopeForScreenStore->setConnection($dbname);
      unset(self::$instance->objProfileAlertStore);
      self::$instance->objProfileAlertStore = new JprofileAlertsCache($dbname);
    }
    
    return self::$instance;
  }
  
 /**
  * edit function is a wrapper of JPROFILE::edit()
  * @brief edits JPROFILE
  * @param $value Query criteria value
  * @param $criteria Query criteria column
  * @param $paramArr key-value pair of columns and values to edit
  * @return edits results
  * @exception jsException for blank criteria
  * @exception PDOException for database level error handling
  */
  public function editJPROFILE($paramArr=array(), $value, $criteria="PROFILEID",$extraWhereCnd=""){
    try {
      return $this->objJProfileStore->edit($paramArr, $value, $criteria,$extraWhereCnd);
    } catch(Exception $ex) {
      //Log this error
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  
  /**
  * updateProfileForArchive
  * Update newjs.JPROFILE Columns for archive i.e. setting 
  * PREACTIVATED,ACTIVATED,activatedKey,JsArchived,MOD_DT column
  * @param type $iProfileID
  * @throws jsException
  */
  public function updateJProfileForArchive($iProfileID)
  {
    try {
      return $this->objJProfileStore->updateProfileForArchive($iProfileID);
    } catch(Exception $ex) {
      //Log this error
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
	/**
  * updateJProfileLoginSortDate
  * Update newjs.JPROFILE Columns for login date in authentication i.e. setting 
  * @param type $iProfileID
  * @throws jsException
  */
	public function updateJProfileLoginSortDate($iProfileID)
	{
		try {
		  return $this->objJProfileStore->updateLoginSortDate($iProfileID);
		} catch(Exception $ex) {
		  //Log this error
          jsCacheWrapperException::logThis($ex);
		  return false;
		}
	}
	
	
  /**
  * updateProfileForBilling
  * Update newjs.JPROFILE Columns for archive i.e. setting 
  * PREACTIVATED,ACTIVATED,activatedKey column
  * @param type $iProfileID
  * @throws jsException
  */
  public function updateJProfileForBilling($paramArr=array(), $value, $criteria="PROFILEID", $extrStr='')
  {
    try{	
      return $this->objJProfileStore->updateProfileForBilling($paramArr, $value, $criteria,$extrStr);
    } catch(Exception $ex) {
      //Log this error
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * convertUpdateStrToArray : A utility Function
   * Convert string in any of following formats
   *  a) AGE=\"N\", MSTATUS=\"N\", RELIGION=\"N\", COUNTRY_RES=\"N\"
   *  b) AGE='N', MSTATUS='N', RELIGION='N', COUNTRY_RES='N'
   *  
   * to array in which column name are key and value is value specified in string 
   * i.e.
   *    array( "AGE"=>'N',"MSTATUS"=>'N', "RELIGION"=>'N', "COUNTRY_RES"='N');
   * @param type $uptStr
   * @return array
   */
  public function convertUpdateStrToArray($uptStr)
  {
    return CommonFunction::convertUpdateStrToArray($uptStr);
  }
  
  /**
   * updateJPROFILE_EDUCATION
   * Function to update newjs.JPROFILE_EDUCATION Store
   * @param int $iProfileID
   * @param array $arrParams
   * @return boolean
   */
  public function updateJPROFILE_EDUCATION($iProfileID, $arrParams=array())
  {
    try {
      return $this->objProfileEducationStore->update($iProfileID, $arrParams);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  
  /**
   * updateJPROFILE_CONTACT
   * Function to update newjs.JPROFILE Contact Store
   * @param int $iProfileID
   * @param array $arrParams
   * @return boolean
   */
  public function updateJPROFILE_CONTACT($iProfileID, $arrParams=array())
  {
    try {
      return $this->objProfileContactStore->update($iProfileID, $arrParams);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  
  /**
   * updateJHOBBY
   * Function to update newjs.JHOBBY Contact Store
   * @param int $iProfileID
   * @param array $arrParams
   * @return boolean
   */
  public function updateJHOBBY($iProfileID, $arrParams=array())
  {
    try {
      return $this->objProfileHobbyStore->update($iProfileID, $arrParams);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * updateProfileSeriousnessCount
   * @param $profileArr
   * @return bool
   */
  public function updateProfileSeriousnessCount($profileArr)
  {
    try {
      return $this->objJProfileStore->updateProfileSeriousnessCount($profileArr);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  
  
  /**
   * update Photo settings for mutiple profiles
   * @param $profileArr
   * @return bool
   */
  public function updateForMutipleProfiles($params,$profileArr)
  {
		try {
      return $this->objJProfileStore->updateForMutipleProfiles($params,$profileArr);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * Funciton to Update Profile View Count in JP_NTIME Table
   * @param $iProfileID
   * @return bool|void
   */
  private function updateProfileViews($iProfileID)
  {
    try{
      return $this->objProfileNTimesStore->updateProfileViews($iProfileID);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * Funciton to Update JP_CHRISTIAN Table
   * @param $iProfileID
   * @return bool|void
   */
  private function updateJP_CHRISTIAN($iProfileID,$paramArray=array())
  {
    try{
      return $this->objProfileChristianStore->update($iProfileID,$paramArray);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  
  /**
   * update sort date in Jprofile
   * @param $profileId 
   * @return bool
   */
  public function updateSortDateForAPLogin($profileId)
  {
    try {
      return $this->objJProfileStore->updateSortDate($profileId);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
  public function deactivateProfiles($profileArr)
  {
    try {
      return $this->objJProfileStore->DeactiveProfiles($profileArr);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * update sort date in Jprofile
   * @param $profileId
   * @return bool
   */
  public function updateASTRO_DETAILS($profileId,$paramArr)
  {
    try {
      return $this->objProfileAstroDetailsStore->updateRecord($profileId,$paramArr);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * @param $iProfileID
   * @param $arrRecordData
   * @return bool|void
   */
  public function updateHOROSCOPE_FOR_SCREEN($iProfileID,$arrRecordData)
  {
    try {
      return $this->objProfileHoroscopeForScreenStore->updateRecord($iProfileID,$arrRecordData);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * updateJPROFILE_ALERTS
   * @param $iProfileID
   * @param $szKey
   * @param $szValue
   * @return bool
   */
  public function updateJPROFILE_ALERTS($iProfileID,$szKey,$szValue)
  {
    try {
      return $this->objProfileAlertStore->update($iProfileID,$szKey,$szValue);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * Function to remove Cache of specified profileid or array of profileid
   * @param $Var
   * @return bool
   */
  public function removeCache($Var)
  {
    try {
      ProfileCacheLib::getInstance()->removeCache($Var);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**	
   * @param $iProfileID
   * @return bool|void
   */
  public function deactiveSingleProfile($iProfileID)
  {
    try {
      return $this->objJProfileStore->Deactive($iProfileID);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * @param $privacy
   * @param $profileid
   * @param $dayinterval
   * @return bool|void
   */
  public function updateHideJPROFILE($privacy, $profileid, $dayinterval)
  {
    try {
      return $this->objJProfileStore->updateHide($privacy, $profileid, $dayinterval);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * @param $privacy
   * @param $profileid
   * @return bool|void
   */
  public function updateUnHideJPROFILE($privacy, $profileid)
  {
    try {
      return $this->objJProfileStore->updateUnHide($privacy, $profileid);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }
}
?>
