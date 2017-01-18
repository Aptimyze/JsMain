<?php
/**
 * Description of ProfileInsertLib
 * This is a wrapper library on store classes 
 * To Insert JPROFILE* Stores (like JPROFILE,JPROFILE_CONTACT,JPROFILE_ALERTS erc),
 * It will be used in Non-symfony code for warpping all Queries which are Updating JPROFILE* Tables
 * 
 * @author Kunal Verma
 * @created 27th June 2016
 */
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

/**
 * JProfileUpdateLib Wrapper Library
 */
class ProfileInsertLib
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
   * JP_NTIME Store Object
   * @var Object
   */
  private $objProfileNTimesStore = null;

  /**
   * HOROSCOPE_FOR_SCREEN Store Object
   * @var Object
   */
  private $objProfileHoroscopeForScreenStore = null;

  /**
   * JPROFILE_ALERTS Store Object
   * @var Object
   */
  private $objProfileAlertsStore = null;

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
    $this->objProfileNTimesStore = new NEWJS_JP_NTIMES($dbname);
    $this->objProfileHoroscopeForScreenStore = new NEWJS_HOROSCOPE_FOR_SCREEN($dbname);
    $this->objProfileAlertsStore = new JprofileAlertsCache($dbname);
  }
  /**
   * __destruct
   */
  public function __destruct() {
    unset($this->objJProfileStore);
    unset($this->objProfileNTimesStore);
    unset($this->objProfileHoroscopeForScreenStore);
    unset($this->objProfileAlertsStore);
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
      self::$instance = new ProfileInsertLib($dbname);
    }

    //Compare Current DB Name and if its different changeConnection
    //and set new connection with desired dbname
    if(self::$instance->currentDBName !== $dbname) {
      self::$instance->currentDBName = $dbname;
      self::$instance->objJProfileStore->setConnection($dbname);
      self::$instance->objProfileNTimesStore->setConnection($dbname);
      self::$instance->objProfileHoroscopeForScreenStore->setConnection($dbname);
      unset(self::$instance->objProfileAlertsStore);
      self::$instance->objProfileAlertsStore = new JprofileAlertsCache($dbname);
    }

    return self::$instance;
  }

  /**
   * Funciton to Update Profile View Count in JP_NTIME Table
   * @param $iProfileID
   * @return bool|void
   */
  public function insertNTimeCount($iProfileID, $iCount)
  {
    try{
      return $this->objProfileNTimesStore->insertRecord($iProfileID, $iCount);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * insertHOROSCOPE_FOR_SCREEN
   * @param $iProfileID
   * @param $blobHoroscope
   * @param $cHoroscope
   * @return bool
   */
  public function insertHOROSCOPE_FOR_SCREEN($iProfileID,$blobHoroscope,$cHoroscope='')
  {
    try{
      return $this->objProfileHoroscopeForScreenStore->insertRecord($iProfileID,$blobHoroscope,$cHoroscope);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * @param $paramArr
   * @return bool|mixed
   */
  public function insertJPROFILE($arrParams)
  {
    try{
      return $this->objJProfileStore->insert($arrParams);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

  /**
   * insertJPROFILE_ALERTS
   * @param $arrRecordData
   * @return bool|mixed
   */
  public function insertJPROFILE_ALERTS($arrRecordData)
  {
    try{
      return $this->objProfileAlertsStore->insertRecord($arrRecordData);
    } catch (Exception $ex) {
      jsCacheWrapperException::logThis($ex);
      return false;
    }
  }

}
?>