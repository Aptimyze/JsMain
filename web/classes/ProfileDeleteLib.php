<?php
/**
 * Description of ProfileDeleteLib
 * This is a wrapper library on store classes 
 * To Insert JPROFILE* Stores (like JPROFILE,JPROFILE_CONTACT,JPROFILE_ALERTS erc),
 * It will be used in Non-symfony code for warpping all Queries which are Updating JPROFILE* Tables
 * 
 * @author Kunal Verma
 * @created 30th June 2016
 */
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

/**
 * JProfileUpdateLib Wrapper Library
 */
class ProfileDeleteLib
{
  /**
   *
   * @var Object 
   */
  private static $instance = null;
  
  /**
   * HOROSCOPE_FOR_SCREEN Store Object
   * @var Object
   */
  private $objProfileHoroscopeForScreenStore = null;

  /**
   * HOROSCOPE Store Object
   * @var Object
   */
  private $objProfileHoroscopeStore = null;

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
    $this->objProfileHoroscopeStore = new newjs_HOROSCOPE($dbname);
    $this->objProfileHoroscopeForScreenStore = new NEWJS_HOROSCOPE_FOR_SCREEN($dbname);
  }
  /**
   * __destruct
   */
  public function __destruct() {
    unset($this->objProfileHoroscopeStore);
    unset($this->objProfileHoroscopeForScreenStore);
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
      self::$instance = new ProfileDeleteLib($dbname);
    }

    //Compare Current DB Name and if its different changeConnection
    //and set new connection with desired dbname
    if(self::$instance->currentDBName !== $dbname) {
      self::$instance->currentDBName = $dbname;
      self::$instance->objProfileHoroscopeStore->setConnection($dbname);
      self::$instance->objProfileHoroscopeForScreenStore->setConnection($dbname);
    }

    return self::$instance;
  }

  /**
   * deleteAllScreenedRecords
   * query to remove the entries from HOROSCOPE_FOR_SCREEN table
   * which have UPLOADED field as 'Y' or 'D'
   * @return bool
   */
  public function deleteAllScreenedRecords()
  {
    try{
      return $this->objProfileHoroscopeForScreenStore->deleteAllScreenedRecords();
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * deleteRecordHOROSCOPE_FOR_SCREEN
   * @param $iID
   * @return bool
   */
  public function deleteRecordHOROSCOPE_FOR_SCREEN($iID)
  {
    try{
      return $this->objProfileHoroscopeForScreenStore->deleteRecord($iID);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * deleteRecordHOROSCOPE
   * @param $iProfileID
   * @return bool
   */
  public function deleteRecordHOROSCOPE($iProfileID)
  {
    try{
      return $this->objProfileHoroscopeStore->deleteRecord($iProfileID);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

}
?>