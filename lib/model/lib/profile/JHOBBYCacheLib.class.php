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
        $fields = "ONLY_VALUES_HOBBY,ONLY_VALUES_FAV_MOVIE,ONLY_VALUES_FAV_TVSHOW,ONLY_VALUES_FAV_FOOD,ONLY_VALUES_FAV_BOOK,ONLY_VALUES_FAV_VAC_DEST";

            if($objProCacheLib->isCached($criteria,$pid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result && in_array($result['ONLY_VALUES_HOBBY'], $validNotFilled)){
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
        print_r($result); 
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['RESULT_VAL'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;

        }

        else
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
                $result = 0;
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
        $dummyResult['PROFILEID'] = $pid;
        $dummyResult['RESULT_VAL'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, __CLASS__);
        return $result;

    }

        }
        /*
    public function update($pid,$paramArr=array())
    {
   
        try {
            $keys="PROFILEID,";
            $values=":PROFILEID ,";
                foreach($paramArr as $key=>$value){
                    $keys.=$key.",";
                    $values.=":".$key.",";
                    $updateStr.=$key."=:".$key.",";
                }
                $updateStr=trim($updateStr,",");
                $keys=substr($keys,0,-1);
                $values=substr($values,0,-1);
                
                $sqlUpdateHobby="Update JHOBBY SET $updateStr where PROFILEID=:PROFILEID";
                $resUpdateHobby = $this->db->prepare($sqlUpdateHobby);
                foreach($paramArr as $key=>$val)
                    $resUpdateHobby->bindValue(":".$key, $val);
                $resUpdateHobby->bindValue(":PROFILEID", $pid);
                $resUpdateHobby->execute();
                if(!$resUpdateHobby->rowCount()) {
                    $sqlUpdateHobby="SELECT 1 FROM newjs.JHOBBY where PROFILEID=:PROFILEID";
                    $resUpdateHobby = $this->db->prepare($sqlUpdateHobby);
                    $resUpdateHobby->bindValue(":PROFILEID", $pid);
                    $resUpdateHobby->execute();
                }
                if(!$resUpdateHobby->rowCount())
                {
                    $sqlEditHobby = "REPLACE INTO JHOBBY ($keys) VALUES ($values)";
                    $resEditHobby = $this->db->prepare($sqlEditHobby);
                    foreach($paramArr as $key=>$val)
                        $resEditHobby->bindValue(":".$key, $val);
                    $resEditHobby->bindValue(":PROFILEID", $pid);               
                    $resEditHobby->execute();
                }
        $this->logFunctionCalling(__FUNCTION__);
                return true;
            }catch(PDOException $e)
                {
                    throw new jsException($e);
                }
    }

/**    
    This function is used to get all data related to HOBBY,INTEREST and LANGUAGE
    @return - resultset array
   **/   
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
        $dummyResult['RESULT_VAL'] = (intval($result) === 0) ? 'N' : $result;
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
