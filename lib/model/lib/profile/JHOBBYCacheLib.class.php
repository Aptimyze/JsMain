<?php
class JHOBBYCacheLib extends TABLE{
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */ 
        protected $dbName;

        public function __construct($dbname="")
        {
            $this->dbName = $dbname;

        } 

        public function getUserHobbies($pid, $onlyValues="")
        { 
          if(!$pid)
            return NULL;
          
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST";

        $bServedFromCache = false;
            if($objProCacheLib->isCached($criteria,$pid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result && in_array($result, $validNotFilled)){
                $result = NULL;
            }

            }


         
            
            if($onlyValues && $bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE ){
            $this->logCacheConsumeCount(__CLASS__);      
            return $result;             
        }


         //Get Data from Mysql
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $toSend = '1';

        if(!$bServedFromCache)
        $result = $objJHB->getUserHobbies($pid, $toSend);

        $noResult = $result;

        $result['PROFILEID'] = $pid;

        if(!$bServedFromCache)
        { 
        $dummyResult = array(); 
        $dummyResult['RESULT_VAL'] = (intval($noResult) === 0 || ($noResult == NULL)) ? 'N' : $result;

        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['RESULT_VAL']['PROFILEID'], $dummyResult['RESULT_VAL'], __CLASS__);
        }

        if($onlyValues)  
       {  
          return $result;
       }

        else
        { 
          $hobby=$result[HOBBY];
              if($result){
                if($hobby)
                {
                   $hobbies = $objJHB->getAllHobby($hobby);
                }
                $hobbies["FAV_MOVIE"] = $result["FAV_MOVIE"];
                $hobbies["FAV_TVSHOW"] = $result["FAV_TVSHOW"];
                $hobbies["FAV_FOOD"] = $result["FAV_FOOD"];
                $hobbies["FAV_BOOK"] = $result["FAV_BOOK"];
                $hobbies["FAV_VAC_DEST"] = $result["FAV_VAC_DEST"];
              }
          return $hobbies;

        }

        }
        
    public function update($pid,$paramArr=array(),$criteria = "PROFILEID",$extraWhereCnd = "")
    {
        $objJHB = new NEWJS_HOBBIES($this->dbName);
        $updatedResult = $objJHB->update($pid,$paramArr);

        if(true === $updatedResult) {
            ProfileCacheLib::getInstance()->updateCache($paramArr, $criteria, $pid, __CLASS__, $extraWhereCnd);
        }

        //If Criteria is not PROFILEID then remove data from cache.
        if ($updatedResult && $criteria != "PROFILEID") {
           if(isset($paramArr['PROFILEID'])) {
               $iProfileId = $paramArr['PROFILEID'];
           } else {
               $iProfileId = $pid;
           }

           //Remove From Cache
           ProfileCacheLib::getInstance()->removeCache($iProfileId);
        }
        return $updatedResult;
    }

/**    
    This function is used to get all data related to HOBBY,INTEREST and LANGUAGE
    @return - resultset array
   **/   
    
    public function getHobbiesAndInterestAndSpokenLanguage()
    {
        $callingObj = new NEWJS_HOBBIES;
        return($callingObj->getHobbiesAndInterestAndSpokenLanguage());
    }
    
    
     public function getUserHobbiesApi($pid)
    {
        if(!$pid)
            return NULL;

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST";
        $bServedFromCache = false;

       if($objProCacheLib->isCached($criteria,$pid,$fields,__CLASS__))
       { 
        $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

           if (false !== $result) { 
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result && in_array($result, $validNotFilled)){
                $result = NULL;
            }

       }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
        }

         //Get Data from Mysql
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        if(!$bServedFromCache)
        { 
        $result = $objJHB->getUserHobbiesApi($pid);

        $noResult = $result;
        $result['PROFILEID'] = $pid;

        $dummyResult['RESULT_VAL'] = (intval($noResult) === 0 || ($noResult == NULL)) ? 'N' : $result;

        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['RESULT_VAL']['PROFILEID'], $dummyResult['RESULT_VAL'],__CLASS__);
        }

         $this->logFunctionCalling(__FUNCTION__);

        $hobbies = array();
          if($result)
          { 
              $hobby=$result[HOBBY];
                if($hobby){
                $hobbies = $objJHB->getHobbyValueApi($hobby);
                }
                $hobbies["FAV_MOVIE"] = $result["FAV_MOVIE"];
                $hobbies["FAV_TVSHOW"] = $result["FAV_TVSHOW"];
                $hobbies["FAV_FOOD"] = $result["FAV_FOOD"];
                $hobbies["FAV_BOOK"] = $result["FAV_BOOK"];
                $hobbies["FAV_VAC_DEST"] = $result["FAV_VAC_DEST"];
              }
          
          return $hobbies;
        }
    
 
    
    private function logFunctionCalling($funName)
  {
    $key = __CLASS__.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);

    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }


    private function logCacheConsumeCount($funName)
  { 
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}

?>
