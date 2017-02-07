<?php

class ProfileYourInfoOld extends TABLE
{

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    protected $dbName;
    
    /**
     * Object of Store class
     */
    private $objProfileYourInfoOld = null;
    
    /**
     * 
     * @param type $dbname
     */
    public function __construct($dbname = "")
    {
        $this->dbName = $dbname;
        $this->objProfileYourInfoOld = new YOUR_INFO_OLD($dbname);
    }
    

    /**
     * 
     * @param type $pid
     * @param type $onlyValues
     * @return array
     */
    public function getAboutMeOld($pid)
    {
        if (!$pid) {
            return NULL;
        }

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "YOUR_INFO_OLD";

        $bServedFromCache = false;

        if ($objProCacheLib->isCached($criteria, $pid, $fields, __CLASS__)) {

            $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            //Case : When Row does not exist in store
            if ($result && is_array($result) &&
                (in_array(ProfileCacheConstants::NOT_FILLED, $result) || in_array('N', $result))
            ) {
                    $this->logCacheConsumeCount(__CLASS__);
                    return NULL; 
              }
        }

         if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $result;
        }
    
        if (false === $bServedFromCache) {
          $result =$this->objProfileYourInfoOld->getAboutMeOld($pid);
          $noResult = $result;

            if (0 === count($noResult) || $noResult == NULL) {   
                $dummyResult['PROFILEID'] = $pid;
                $dummyResult['YOUR_INFO_OLD'] = ProfileCacheConstants::NOT_FILLED;
            }
            else {
                $dummyResult = $result['YOUR_INFO_OLD'];
                $dummyResult['PROFILEID'] = $pid;
            }

            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $pid, $dummyResult, __CLASS__);
            return $result;

        }
  
  }

   /**
     * 
     * @param type $funName
     */
    private function logCacheConsumeCount($funName)
    {
        $key = 'cacheConsumption' . '_' . date('Y-m-d');
        JsMemcache::getInstance()->hIncrBy($key, $funName);

        JsMemcache::getInstance()->hIncrBy($key, $funName . '::' . date('H'));
    }

}
?>
