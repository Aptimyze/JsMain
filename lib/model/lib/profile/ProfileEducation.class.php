<?php
/**
 * Description of Profile Education
 * Library Class to handle Model for JPROFILE_EDUCATION Table
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @created     28th August 2016
 */

class ProfileEducation
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
  private static $objEducationMysql = null;

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  private function __construct($dbname = "") 
  {
    self::$objEducationMysql = new NEWJS_JPROFILE_EDUCATION($dbname);
  }
  
  /**
   * To Stop clone of this class object
   */
  private function __clone() { }

  /**
   * To stop unserialize for this class object
   */
  private function __wakeup() { }

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
   * getProfileEducation
   * @param type $pid
   * @param type $from
   * @return type
   */
  public function getProfileEducation($pid, $from = "")
  {
    $bServedFromCache = false;
    
    //For $from== 'mailer', different handling required
    //
    if (0 === strlen($from) && 
        !is_array($pid) && 
        ProfileCacheLib::getInstance()->isCached(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__)
      ) {
      
      $result = ProfileCacheLib::getInstance()->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, ProfileCacheConstants::ALL_FIELDS_SYM, __CLASS__);
      
      if (false !== $result) {
        $bServedFromCache = true;
        $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
      }
    }
    
    if(strlen($from) && is_array($pid)) {
      $result = ProfileCacheLib::getInstance()->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA, $pid,"PROFILEID,PG_COLLEGE,PG_DEGREE,UG_DEGREE,OTHER_UG_DEGREE,OTHER_PG_DEGREE,SCHOOL,COLLEGE,OTHER_UG_COLLEGE,OTHER_PG_COLLEGE,SCREENING,EDU_LEVEL_NEW",__CLASS__);
      if ($result && false !== $result) {
        $bServedFromCache = true;
        $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
      }
    }
    
    if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
        $this->logCacheConsumeCount(__CLASS__);
      return $result;
    }
    
    //Get Records from Mysql
    $result = self::$objEducationMysql->getProfileEducation($pid, $from);
    
    if(is_array($result) && !is_array($pid) && false === ProfileCacheFunctions::isCommandLineScript("set")) {
      $result['PROFILEID'] = $pid;
      ProfileCacheLib::getInstance()->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result,__CLASS__);
    }
    
    if(false === $result && false === ProfileCacheFunctions::isCommandLineScript("set")) {
      $dummyResult['PROFILEID'] = $pid;
      $dummyResult['PG_COLLEGE'] = ProfileCacheConstants::NOT_FILLED;
      
      ProfileCacheLib::getInstance()->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult,__CLASS__);
    }
    
    if(is_array($pid) && false === ProfileCacheFunctions::isCommandLineScript("set")){
      ProfileCacheLib::getInstance()->cacheForMultiple(ProfileCacheConstants::CACHE_CRITERIA, $result,__CLASS__);
    }
    return $result;
  }
  
  /**
   * update
   * @param type $pid
   * @param type $paramArr
   * @return type
   */
  public function update($pid, $paramArr = array())
  {
    $bResult = self::$objEducationMysql->update($pid, $paramArr);
    if(true === $bResult) {
      ProfileCacheLib::getInstance()->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $pid, __CLASS__);
    }
    
    return $bResult;
  }

  /**
   * @fn getArray
   * @brief fetches results for multiple profiles to query from JPROFILE_EDUCATION
   * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are include
    d in the result
   * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
   * @param $fields Columns to query
   * @return results Array according to criteria having incremented index
   * @exception jsException for blank criteria
   * @exception PDOException for database level error handling
   */
  public function getArray($valueArray = "", $excludeArray = "", $greaterThanArray = "", $fields = "PROFILEID", $lessThanArray = "", $orderby = "", $limit = "", $greaterThanEqualArrayWithoutQuote = "") 
  {
    return self::$objEducationMysql->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $lessThanArray, $orderby, $limit, $greaterThanEqualArrayWithoutQuote);
  }

  public function gethaveEducationProfiles($date)
  {
    return self::$objEducationMysql->gethaveEducationProfiles($date);
  }

  /**
   * Function to fetch PROFILEID of profiles in JPROFILE whose EDU_LEVEL_NEW is a particular set of values and where PG_DEGREE or PG_COLLEGE is not NULL
   * @param $educationCodes- it is the array of codes of EDU_LEVEL_NEW
   * @param $codeFlag- this has value 0 or 1 and help's decide which query is to be build to be executed.
   * @return $profileArr - returns the array of profileID's that match the given criteria
   */
  public function getEducationData($educationCodes, $codeFlag)
  {
    return self::$objEducationMysql->getEducationData($educationCodes, $codeFlag);
  }
  
  private function logCacheConsumeCount($funName)
  {return;
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}

?>
