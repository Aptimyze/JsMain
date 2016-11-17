<?php
class JHOBBYCacheLib extends TABLE{
       
}
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */ /*
        private $dbName;

        public function __construct($dbname="")
        {
            $this->dbName = $dbname
        } 
/*
    public function getAllHobby($hobby="",$pid)
    {   
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "JHOBBY_EXISTS";
        $storeName = "HOBBIES";
        $bServedFromCache = false;

       if($objProCacheLib->isCached($criteria,$pid,$fields,$storeName))
       {
        $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, $storeName);

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

        $result = $objJHB->getAllHobby($hobby);
   
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['JHOBBY_EXISTS'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, $storeName);
        return $result;

    }
*/


/*

        public function getUserHobbies($pid, $onlyValues="")
        {

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";
        $fields = "JHOBBY_EXISTS";
        $storeName = "HOBBIES";
        $bServedFromCache = false;

       if($objProCacheLib->isCached($criteria,$pid,$fields,$storeName))
       {
        $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $pid, $fields, $storeName);

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

        $result = $objJHB->getAllHobby($hobby);
   
        $dummyResult['PROFILEID'] = $profileid;
        $dummyResult['JHOBBY_EXISTS'] = (intval($result) === 0) ? 'N' : $result;
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $dummyResult['PROFILEID'], $dummyResult, $storeName);
        return $result;

        }
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

    
    This function is used to get all data related to HOBBY,INTEREST and LANGUAGE
    @return - resultset array
      
    public function getHobbiesAndInterestAndSpokenLanguage()
    {
        try
        {
            $sql = "SELECT LABEL,VALUE,IF(TYPE='HOBBY','H',IF(TYPE='INTEREST','I','L')) AS TYPE FROM newjs.HOBBIES WHERE TYPE IN (:TYPE1,:TYPE2,:TYPE3) ORDER BY SORTBY";
            $res = $this->db->prepare($sql);
            $res->bindValue(":TYPE1", 'HOBBY',PDO::PARAM_STR);
            $res->bindValue(":TYPE2", 'INTEREST',PDO::PARAM_STR);
            $res->bindValue(":TYPE3", 'LANGUAGE',PDO::PARAM_STR);
            $res->execute();
      $this->logFunctionCalling(__FUNCTION__);
            while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row;
                        }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
        return $output;
    }
    
    
     public function getUserHobbiesApi($pid)
        {
            try 
            {
                if($pid)
                { 
                    $sql="select HOBBY,FAV_MOVIE,FAV_TVSHOW,FAV_FOOD,FAV_BOOK,FAV_VAC_DEST from newjs.JHOBBY where PROFILEID=:PROFILEID";
                    $prep=$this->db->prepare($sql);
                    $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                    $prep->execute();
          $this->logFunctionCalling(__FUNCTION__);
                    $hobbies = array();
                    if($result = $prep->fetch(PDO::FETCH_ASSOC))
                    {
                            $hobby=$result[HOBBY];
                            if($result){
                                if($hobby)
                                $hobbies = $this->getHobbyValueApi($hobby);
                                $hobbies["FAV_MOVIE"] = $result["FAV_MOVIE"];
                                $hobbies["FAV_TVSHOW"] = $result["FAV_TVSHOW"];
                                $hobbies["FAV_FOOD"] = $result["FAV_FOOD"];
                                $hobbies["FAV_BOOK"] = $result["FAV_BOOK"];
                                $hobbies["FAV_VAC_DEST"] = $result["FAV_VAC_DEST"];
                            }
                    }
                    return $hobbies;
                }
            }
            catch(PDOException $e)
            {
                /*** echo the sql statement and error message **
                throw new jsException($e);
            }
        }
    
    
    public function getHobbyValueApi($hobby="")
    {
        try
        {
            if($hobby)
            {
                $sql="select SQL_CACHE TYPE,LABEL,VALUE from newjs.HOBBIES where VALUE IN ($hobby) order by SORTBY";
                $prep=$this->db->prepare($sql);
                $prep->execute();
        $this->logFunctionCalling(__FUNCTION__);
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $res[$result[TYPE]]["LABEL"][]=$result[LABEL];
                    $res[$result[TYPE]]["VALUE"][]=$result[VALUE];
                }
                
                if(is_array($res))
                    foreach($res as $key=>$val)
                    {
                        foreach($val as $k=>$v)
                        {
                            if($k=="LABEL")
                                $hobbies[$key][$k]=implode(", ",$v);
                            else
                                $hobbies[$key][$k]=implode(",",$v);
                        }
                        //$hobbies[$key]=implode(", ",$val);
                    }
                if(is_array($hobbies))
                    return $hobbies;
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***
            throw new jsException($e);
        }
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
*/