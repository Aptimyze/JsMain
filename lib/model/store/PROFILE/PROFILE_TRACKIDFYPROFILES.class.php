<?php

class PROFILE_TRACKIDFYPROFILES extends TABLE 
{
		/**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */
        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function insertTrackingData($profileId,$email,$date)
        {
        	try
            {
                $sql = "INSERT INTO  `PROFILE`.`TRACKIDFYPROFILES`(DATE,PROFILEID,EMAIL) VALUES (:DATETIME, :PROFILEID,  :EMAIL)";
                $pdoStatement = $this->db->prepare($sql);
                
                $pdoStatement->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                $pdoStatement->bindValue(":EMAIL",$email,PDO::PARAM_STR);
                $pdoStatement->bindValue(":DATETIME",$date,PDO::PARAM_STR);
                $res = $pdoStatement->execute();
                return $res;
            }
            catch (Exception $ex) 
            {
               jsException::nonCriticalError($e);
            }
        }
	
}
