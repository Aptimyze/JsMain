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
    $this->objProfileEducationStore = new NEWJS_JPROFILE_EDUCATION($dbname);
    $this->objProfileContactStore = new NEWJS_JPROFILE_CONTACT($dbname);
    $this->objProfileHobbyStore = new NEWJS_HOBBIES($dbname);
  }
  /**
   * __destruct
   */
  public function __destruct() {
    unset($this->objJProfileStore);
    unset($this->objProfileContactStore);
    unset($this->objProfileEducationStore);
    unset($this->objProfileHobbyStore);
    
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
      self::$instance->objJProfileStore->setConnection($dbName);
      self::$instance->objProfileEducationStore->setConnection($dbName);
      self::$instance->objProfileContactStore->setConnection($dbName);
      self::$instance->objProfileHobbyStore->setConnection($dbName);
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
      jsException::log($ex);
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
      jsException::log($ex);
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
      jsException::log($ex);
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
      jsException::log($ex);
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
      jsException::log($ex);
      return false;
    }
  }
  
}
?>
