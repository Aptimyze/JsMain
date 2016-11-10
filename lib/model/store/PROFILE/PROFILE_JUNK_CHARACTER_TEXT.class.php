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

        public function getOriginalText()
        {
            $sql = "SELECT id,original_text FROM PROFILE.JUNK_CHARACTER_TEXT WHERE modified_automate IS NULL";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->execute();


            $res=$pdoStatement->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }

        public function updateModifiedText($id,$modified_automate)
        {
            $sql = "UPDATE PROFILE.JUNK_CHARACTER_TEXT set modified_automate =:MODIFIED_TEXT WHERE id =:ID";
            $pdoStatement = $this->db->prepare($sql);
            $pdoStatement->bindValue(":MODIFIED_TEXT",$modified_automate,PDO::PARAM_STR);
            $pdoStatement->bindValue(":ID",$id,PDO::PARAM_INT);
            $pdoStatement->execute();

        }

}
