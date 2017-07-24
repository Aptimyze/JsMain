<?php

class newjs_CRITICAL_INFO_CHANGED extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        public function editedCriticalInfo($profileId,$returnData=false) {
                try {
                        if ($profileId == "") {
                                throw new jsException($e);
                        }
                        $sql = "SELECT PROFILEID,EDITED_FIELDS,SCREENED_STATUS FROM newjs.CRITICAL_INFO_CHANGED WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $prep->execute();
                        if ($result = $prep->fetch(PDO::FETCH_ASSOC)){
                                if($returnData == true){
                                        return $result;
                                }
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
        public function insert($profileid, $fields,$screenedStatus = 'Y') {
                
                $sql = "INSERT INTO newjs.CRITICAL_INFO_CHANGED(PROFILEID, EDITED_FIELDS, DATE,SCREENED_STATUS) VALUES (:PROFILEID, :EDITED_FIELDS, NOW(),:SCREENED_STATUS)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":EDITED_FIELDS", $fields, PDO::PARAM_STR);
                $res->bindValue(":SCREENED_STATUS", $screenedStatus, PDO::PARAM_STR);
                $res->execute();
        }
        /**
         * This function enters the entry of a user and the ignored profile in newjs.IGNORE_PROFILE.
         * */
        public function updateStatus($profileid, $status) {
                
                $sql = "UPDATE newjs.CRITICAL_INFO_CHANGED SET SCREENED_STATUS = :SCREENED_STATUS WHERE  PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":SCREENED_STATUS", $status, PDO::PARAM_STR);
                $res->execute();
        }

}

?>
