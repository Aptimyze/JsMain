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
/*
    public function getAllHobby($hobby="",$pid)
    {   
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "";
        $bServedFromCache = false;



       if($objProCacheLib->isCached($criteria,$pid,$fields,__CLASS__))
       {
        $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $result = array();

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
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $result = $objJHB->getAllHobby($hobby);
   
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['JHOBBY_EXISTS'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;

    }



*/

        public function getUserHobbies($pid, $onlyValues="")
        {
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST";
        $bServedFromCache = false;

        if($onlyValues)
        { 
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
            return $result;
        }
            

         //Get Data from Mysql
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $result = $objJHB->getUserHobbies($pid, $onlyValues);
           

        $dummyResult = array();
        //print_r($result); 
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['RESULT_VAL'] = (intval($result) === 0 || ($result == NULL)) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;

        }

        }
        
    public function update($pid,$paramArr=array(),$criteria)
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
               $arrData = $this->get($value,$criteria,"PROFILEID");
               $iProfileId = $arrData['PROFILEID'];
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
    /*
    public function getHobbiesAndInterestAndSpokenLanguage()
    {
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "LABEL,VALUE,TYPE";
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
            return $result;
        }

         //Get Data from Mysql
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $result = $objJHB->getHobbiesAndInterestAndSpokenLanguage();
   
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['RESULT_VAL'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult,__CLASS__);
        return $result;

    }
    
    */
     public function getUserHobbiesApi($pid)
        {
        
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
            return $result;
        }

         //Get Data from Mysql
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $result = $objJHB->getUserHobbiesApi($pid);
   
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['RESULT_VAL'] = (intval($result) === 0 || ($result == NULL)) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult,__CLASS__);
        return $result;

        }
    
  /*
    public function getHobbyValueApi($hobby="")
    {
       $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "";
        $bServedFromCache = false;

       if($objProCacheLib->isCached($criteria,$pid,$fields,__CLASS__))
       {
        $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $result = $result['JHOBBY_EXISTS'];

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
        $objJHB = new NEWJS_HOBBIES($this->dbName); 

        $result = $objJHB->getHobbyValueApi($hobby);
   
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['JHOBBY_EXISTS'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;

    }
  */
    
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
