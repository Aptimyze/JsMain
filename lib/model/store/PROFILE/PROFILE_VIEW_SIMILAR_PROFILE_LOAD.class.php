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

        public function set($sender_username,$receiver_username)
        {
            try
            {
                $sql = "INSERT INTO  `PROFILE`.`VIEW_SIMILAR_PROFILE_LOAD` (  `SENDER_USERNAME` ,  `RECEIVER_USERNAME` ,  `COUNT` ) VALUES (:sender_username,  :receiver_username,  '1') ON DUPLICATE KEY UPDATE count = count + 1";
                $pdoStatement = $this->db->prepare($sql);
                $pdoStatement->bindValue(":sender_username",$sender_username,PDO::PARAM_INT);
                $pdoStatement->bindValue(":receiver_username",$receiver_username,PDO::PARAM_INT);
                $res = $pdoStatement->execute();
                return $res;
            }
            catch (Exception $ex) 
            {
                die("I");
                throw new jsException($e);
            }
        }
}
