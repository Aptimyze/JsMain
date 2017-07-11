<?php

class CRITICAL_INFO_DOC_SCREENED_LOG extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        /**
         * This function enters the entry of a user and the ignored profile in newjs.IGNORE_PROFILE.
         * */
        public function insert($profileid, $assignedTo,$status,$documentPath) {
                
                $sql = "INSERT INTO newjs.CRITICAL_INFO_DOC_SCREENED_LOG(PROFILEID, ASSIGNED_TO, ALLOTED_TIME, SCREENED_STATUS, DOCUMENT_PATH) VALUES (:PROFILEID, :ASSIGNED_TO, now(), :SCREENED_STATUS, :DOCUMENT_PATH )";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":ASSIGNED_TO", $assignedTo, PDO::PARAM_STR);
                $res->bindValue(":SCREENED_STATUS", $status, PDO::PARAM_STR);
                $res->bindValue(":DOCUMENT_PATH", $documentPath, PDO::PARAM_STR);
                $res->execute();
                return true;
        }
}

?>
