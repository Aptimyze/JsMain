<?php

/**
 * Description of JUNK_CHARACTER_TEXT
 * 
 * @author Mohammad Shahjahan
 */

/**
 * 
 */
class PROFILE_JUNK_CHARACTER_TEXT extends TABLE {


        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */
        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function getOriginalText($limitValue)
        {
            try
            {
                $sql = "SELECT id,original_text FROM PROFILE.JUNK_CHARACTER_TEXT WHERE modified_automate IS NULL limit :limitValue";
                $pdoStatement = $this->db->prepare($sql);
                $pdoStatement->bindValue(":limitValue",$limitValue,PDO::PARAM_INT);
                $pdoStatement->execute();
                $res=$pdoStatement->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            }
            catch (Exception $ex) 
            {
                throw new jsException($e);
            }
        }


        public function updateModifiedText($idArr,$modified_automate)
        {
            try
            {
                foreach($idArr as $k=>$v)
                {
                    $queryArr[]=":ID".$k;
                }
                $queryStr = "(".implode(",", $queryArr).")";
                $sql = "UPDATE PROFILE.JUNK_CHARACTER_TEXT set modified_automate =:MODIFIED_TEXT WHERE id IN ".$queryStr;
                $pdoStatement = $this->db->prepare($sql);
                $pdoStatement->bindValue(":MODIFIED_TEXT",$modified_automate,PDO::PARAM_STR);
                foreach($idArr as $k=>$v)
                    $pdoStatement->bindValue(":ID".$k,$v,PDO::PARAM_INT);
                $pdoStatement->execute();
            }
            catch (Exception $ex) 
            {
                throw new jsException($e);
            }

        }

}
