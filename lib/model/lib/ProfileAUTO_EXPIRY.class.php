<?php

/**
 * Description of ProfileAstro
 * Library Class to handle Model for jsadmin.AUTO_EXPIRY Table
 *
 * @package     jeevansathi
 * @subpackage cache
 * @author      Palash Chordia
 * @created     10th Dec 2016
 */
class ProfileAUTO_EXPIRY
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
    private static $objAUTO_EXPIRY = null;

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "")
    {
        self::$objAUTO_EXPIRY = new jsadmin_AUTO_EXPIRY($dbname);
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
     * 
     * @param type $pid
     * @return type
     */
    public function getDate($profileid)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();
        $fields = 'AUTO_EXPIRY_DATE';
        $bServedFromCache = false;

        if ($objProCacheLib->isCached('PROFILEID', $profileid,$fields , __CLASS__)) {
            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);                       
            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }
            $result = $result['AUTO_EXPIRY_DATE'];
//            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
  //          if($result && in_array($result, $validNotFilled)){
    //            return $result;
      //      }
        }
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        
        $result = self::$objAUTO_EXPIRY->getDate($profileid);
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['AUTO_EXPIRY_DATE'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;
         
    }
    
    /**
     * 
     * @param type $pid
     * @param type $paramArr
     * @return type
     */
    public function replace($pid,$type,$date)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();        
        self::$objAUTO_EXPIRY->replace($pid,$type,$date);
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['AUTO_EXPIRY_DATE'] = $date;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
    
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function isAlive($profileid,$time)
    {

        $result = $this->getDate($profileid);
        if($result===false){
            
            return self::$objAUTO_EXPIRY->isAlive($profileid,$time);
        }
        
        $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
        if(in_array($result, $validNotFilled))
            return true;
        
        if((strtotime($result['AUTO_EXPIRY_DATE'])-2) > strtotime($time))return false;    
        return true;

    }
    
  private function logCacheConsumeCount($funName)
  {
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}
?>
