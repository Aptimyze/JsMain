<?php

/**
 * class PROFILE_VERIFICATION_FSO_DELETION
 * This store class is responsible to handle FSO Deletion LOG for verification seal.
 * @author Akash Kumar
 */
class PROFILE_VERIFICATION_FSO_DELETION extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        /**
         * This function checks FSO visit deletion LOG for given profileID.
         * @param $profileid - profile ID of user
         */
        public function check($profileid) {
                try {
                        $sql = "select * FROM PROFILE_VERIFICATION.FSO_DELETION WHERE PROFILEID=:profileid";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        return $row;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

        /**
         * This function inserts FSO visit deletion LOG for given profileID.
         * @param $record - array of profile ID of user, userId of remover,and reason for deletion
         */
        public function deletionRecord($record) {
                try {
                        $sql = "REPLACE INTO PROFILE_VERIFICATION.FSO_DELETION (PROFILEID,DELETE_REASON,DELETE_REASON_DETAIL,DELETED_BY) values(:profileid,:reason,:detail,:by)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":profileid", $record["PROFILEID"], PDO::PARAM_INT);
                        $res->bindValue(":reason", $record["REASON"], PDO::PARAM_INT);
                        $res->bindValue(":detail", $record["DETAIL"], PDO::PARAM_STR);
                        $res->bindValue(":by", $record["BY"], PDO::PARAM_STR);
                        $res->execute();
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

}

?>
