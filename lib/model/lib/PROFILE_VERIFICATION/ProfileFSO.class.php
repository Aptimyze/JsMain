<?php

/**
 * Description of ProfileAstro
 * Library Class to handle Model for PROFILE_VERIFICATION.FSO Table
 *
 * @package     jeevansathi
 * @subpackage cache
 * @author      Palash Chordia
 * @created     23rd Sept 2016
 */
class ProfileFSO
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
    private static $objFSO = null;

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "")
    {
        self::$objFSO = new PROFILE_VERIFICATION_FSO($dbname);
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
     * 
     * @param type $pid
     * @return type
     */
    public function check($profileid)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();
        $fields = 'FSO_EXISTS';
        $bServedFromCache = false;
        
        if ($objProCacheLib->isCached('PROFILEID', $profileid,$fields , __CLASS__)) {
            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);                       
            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }
            $result = $result['FSO_EXISTS'];
            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            if($result && in_array($result, $validNotFilled)){
                $result = 0;
            }
        }
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        
        //Get Data from Mysql
        $result = self::$objFSO->check($profileid);
        
        
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['FSO_EXISTS'] = (intval($result) === 0) ? 'N' : $result;
        if(false === ProfileCacheFunctions::isCommandLineScript("set")){
                $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        }
        return $result;
         
    }
    
    /**
     * 
     * @param type $pid
     * @param type $paramArr
     * @return type
     */
    public function insert($pid)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();        
        self::$objFSO->insert($pid);
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['FSO_EXISTS'] = 1;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
    
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function delete($profileid)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();        
        self::$objFSO->delete($profileid);
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['FSO_EXISTS'] = 'N';
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);

    }
    
  private function logCacheConsumeCount($funName)
  {return;
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}
?>
