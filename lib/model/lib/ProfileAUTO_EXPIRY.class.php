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
     * Object of Store class
     * @var instance of NEWJS_PROFILE|null
     */
    private $objAUTO_EXPIRY = null;

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "")
    {
        $this->objAUTO_EXPIRY = new jsadmin_AUTO_EXPIRY($dbname);
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
        }
        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
        // get from values database by query
        $result = $this->objAUTO_EXPIRY->getDate($profileid);
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['AUTO_EXPIRY_DATE'] = (intval($result) === 0) ? ProfileCacheConstants::NOT_FILLED : $result;
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
    public function replace($pid,$type,$date)
    {
        $objProCacheLib = ProfileCacheLib::getInstance();        
        $result = $this->objAUTO_EXPIRY->replace($pid,$type,$date);
        
        if($result) {
            $dummyResult['PROFILEID'] = $pid;
            $dummyResult['AUTO_EXPIRY_DATE'] = $date;
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $pid, $dummyResult, __CLASS__);
        }
        
    }
    
    /**
     * 
     * @param type $profileid
     * @return type
     */
    public function isAlive($profileid,$time)
    {

        $result = $this->getDate($profileid);        
        $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
        if(in_array($result, $validNotFilled))
            return true;
        // subtract 2 seconds first from result and then compare
        if((strtotime($result)-2) > strtotime($time))
            return false;    
        return true;

    }
    
    /**
    * This function is corresponding to this kind of sql query
    * select count(*) CNT from jsadmin.AUTO_EXPIRY WHERE PROFILEID = '$iProfileID' AND DATE > '$time'";
    * 
    * @param type $iProfileID
    * @param type $time
    * @return array("CNT"=>0/1) //As per Logic
    */
   public function getRecord($iProfileID,$time)
   {
       $dbTime = $this->getDate($iProfileID);
       if(0 === $dbTime) {
           $result = 0;
       }

       if($dbTime > $time){
           $result = 1;
       }
       return array("CNT"=>$result);
   }
  private function logCacheConsumeCount($funName)
  {return;
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}
?>
