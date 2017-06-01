<?php

class newjs_CRITICAL_INFO_CHANGED extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function editedCriticalInfo($profileId) {
                try {
                        if ($profileId == "") {
                                throw new jsException($e);
                        }
                        $sql = "SELECT PROFILEID FROM newjs.CRITICAL_INFO_CHANGED WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $prep->execute();
                        if ($result = $prep->fetch(PDO::FETCH_ASSOC)){
                                return true;
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
        public function insert($profileid, $fields) {
                
                $sql = "REPLACE INTO newjs.CRITICAL_INFO_CHANGED(PROFILEID, EDITED_FIELDS, DATE) VALUES (:PROFILEID, :EDITED_FIELDS, NOW())";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":EDITED_FIELDS", $fields, PDO::PARAM_STR);
                $res->execute();
        }

}

?>
