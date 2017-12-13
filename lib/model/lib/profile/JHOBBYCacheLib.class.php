<?php

class JHOBBYCacheLib extends TABLE
{

    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    protected $dbName;
    
    /**
     * Object of Store class
     * @var instance of NEWJS_HOBBIES|null
     */
    private $objJHobbyMysql = null;
    
    /**
     * 
     * @param type $dbname
     */
    public function __construct($dbname = "")
    {
        $this->dbName = $dbname;
        $this->objJHobbyMysql = new NEWJS_HOBBIES($dbname);
    }
    
    /**
     * 
     * @param type $hobby
     */
    public function getAllHobby($hobby="")
    {
        return $this->objJHobbyMysql->getAllHobby($hobby);
    }
    
    /**
     * 
     * @param type $pid
     * @param type $onlyValues
     * @return array
     */
    public function getUserHobbies($pid, $onlyValues = "")
    {
        if (!$pid) {
            return NULL;
        }

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST";

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
                $result = array();
            }
        }

         if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
        }

        if ($onlyValues && $bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            return $result;
        }
    
        if (false === $bServedFromCache) {
            //Get Data from Mysql
            
            $toSend = '1';
            $result = $this->objJHobbyMysql->getUserHobbies($pid, $toSend);

            $noResult = $result;
            
            if(count($noResult) === 0) {
                $dummyResult = ProfileCacheFunctions::setNotFilledArray(__CLASS__,$pid);
            } else {
                $dummyResult = $result;
            }
            $dummyResult[ProfileCacheConstants::CACHE_CRITERIA] = $pid;
            
            if(false === ProfileCacheFunctions::isCommandLineScript("set")){
                $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $pid, $dummyResult, __CLASS__);
            }
        }

        if ($onlyValues) {
            return $result;
        }
        else {
            $hobby = $result[HOBBY];
            $hobbies = array();
            if ($result) {
                if ($hobby) {
                    $hobbies = $this->objJHobbyMysql->getAllHobby($hobby);
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
    
    /**
     * 
     * @param type $pid
     * @param type $paramArr
     * @return type
     */
    public function update($pid, $paramArr = array())
    {
        $updatedResult = $this->objJHobbyMysql->update($pid, $paramArr);

        if (true === $updatedResult) {
            ProfileCacheLib::getInstance()->updateCache($paramArr, ProfileCacheConstants::CACHE_CRITERIA, $pid, __CLASS__);
        }
        return $updatedResult;
    }

    /**
      This function is used to get all data related to HOBBY,INTEREST and LANGUAGE
      @return - resultset array
     * */
    public function getHobbiesAndInterestAndSpokenLanguage()
    {
        return $this->objJHobbyMysql->getHobbiesAndInterestAndSpokenLanguage();
    }

    /**
     * 
     * @param type $pid
     * @return type
     */
    public function getUserHobbiesApi($pid)
    {
        if (!$pid)
            return NULL;

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST";
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
                $result = array();
            }
        }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
        }


        //Get Data from Mysql
        if (!$bServedFromCache) {
            $result = $this->objJHobbyMysql->getUserHobbiesApi($pid, "1");

            $noResult = $result;

            if (0 === count($noResult)) {
                $dummyResult = ProfileCacheFunctions::setNotFilledArray(__CLASS__,$pid);
            }
            else {
                $dummyResult = $result;
                $dummyResult['PROFILEID'] = $pid;
            }
            //Cache the RAW DATA
            if(false === ProfileCacheFunctions::isCommandLineScript("set")){
                $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $pid, $dummyResult, __CLASS__);
            }
        }

        $hobbies = array();
        if ($result) {
           
            $hobby = $result[HOBBY];
            if ($hobby) {
                $hobbies = $this->getHobbyValueApi($hobby);
            }
            $hobbies["FAV_MOVIE"] = $result["FAV_MOVIE"];
            $hobbies["FAV_TVSHOW"] = $result["FAV_TVSHOW"];
            $hobbies["FAV_FOOD"] = $result["FAV_FOOD"];
            $hobbies["FAV_BOOK"] = $result["FAV_BOOK"];
            $hobbies["FAV_VAC_DEST"] = $result["FAV_VAC_DEST"];
        }

        return $hobbies;
    }
    
    /**
     * 
     * @param type $hobby
     * @return type
     */
    public function getHobbyValueApi($hobby="")
    {
        return $this->objJHobbyMysql->getHobbyValueApi($hobby);
    }
    
    /**
     * 
     * @param type $funName
     */
    private function logCacheConsumeCount($funName)
    {
        return;
       /* $key = 'cacheConsumption' . '_' . date('Y-m-d');
        JsMemcache::getInstance()->hIncrBy($key, $funName);

        JsMemcache::getInstance()->hIncrBy($key, $funName . '::' . date('H'));*/
    }

}

?>
