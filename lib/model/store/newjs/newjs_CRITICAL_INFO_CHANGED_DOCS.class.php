<?php

class newjs_CRITICAL_INFO_CHANGED_DOCS extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function editedCriticalInfo($profileId) {
                try {
                        if ($profileId == "") {
                                throw new jsException($e);
                        }
                        $sql = "SELECT * FROM newjs.CRITICAL_INFO_CHANGED_DOCS WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $prep->execute();
                        if ($result = $prep->fetch(PDO::FETCH_ASSOC)){
                               return $result;
                        }
                        return false;
                } catch (PDOException $e) {
                        /*                         * * echo the sql statement and error message ** */
                        throw new jsException($e);
                }
        }

        /**
         * This function enters the entry of a user and the ignored profile in newjs.IGNORE_PROFILE.
         * */
        public function insert($profileid, $documentPath) {
                
                $sql = "REPLACE INTO newjs.CRITICAL_INFO_CHANGED_DOCS(PROFILEID, DOCUMENT_PATH, UPLOADED_ON) VALUES (:PROFILEID, :DOCUMENT_PATH, NOW())";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":DOCUMENT_PATH", $documentPath, PDO::PARAM_STR);
                $res->execute();
                return true;
        }
        public function updateById($profileid,$id, $documentPath) {
                
                $sql = "UPDATE newjs.CRITICAL_INFO_CHANGED_DOCS SET DOCUMENT_PATH = :DOCUMENT_PATH WHERE ID= :ID AND PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":ID", $id, PDO::PARAM_INT);
                $res->bindValue(":DOCUMENT_PATH", $documentPath, PDO::PARAM_STR);
                $res->execute();
                return true;
        }

}

?>
