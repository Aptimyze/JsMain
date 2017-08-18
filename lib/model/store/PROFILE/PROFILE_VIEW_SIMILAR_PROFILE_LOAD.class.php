<?php

/**
 * Description of JUNK_CHARACTER_TEXT
 * 
 * @author Mohammad Shahjahan
 */

/**
 * 
 */
class PROFILE_VIEW_SIMILAR_PROFILE_LOAD extends TABLE {


        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */
        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function set($viewer,$viewed)
        {
            try
            {
                $sql = "INSERT INTO  `PROFILE`.`VIEW_SIMILAR_PROFILE_LOAD` (  `viewer` ,  `viewed`) VALUES (:viewer,  :viewed)";
                $pdoStatement = $this->db->prepare($sql);
                $pdoStatement->bindValue(":viewer",$viewer,PDO::PARAM_INT);
                $pdoStatement->bindValue(":viewed",$viewed,PDO::PARAM_INT);
                $res = $pdoStatement->execute();
                return $res;
            }
            catch (Exception $ex) 
            {
               jsException::nonCriticalError($e);
            }
        }
}
