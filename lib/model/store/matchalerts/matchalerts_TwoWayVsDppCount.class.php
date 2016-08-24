<?php

/*
 * this store is used for operations in table matchalerts_TwoWayVsDppCount
 */

class matchalerts_TwoWayVsDppCount extends TABLE {

        public function __construct($dbname = "") {
                $dbname = $dbname ? $dbname : "newjs_masterRep";
                parent::__construct($dbname);
        }

        /* this function returns entry date for a profileid
         * @param - profileid
         * @return - date(entry)
         */

        public function getEntryDateForProfile($profileId) {
                try {
                        if ($profileId) {
                                $sql = "SELECT PROFILEID,ENTRY_DT,CNT FROM MATCHALERT_TRACKING.TwoWayVsDppCount WHERE PROFILEID = :PROFILEID";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                                $prep->execute();
                                $entryData = $prep->fetch(PDO::FETCH_ASSOC);
                                return $entryData;
                        }
                } catch (PDOException $ex) {
                        throw new jsException($ex);
                }
        }

        /* this function entry of a profileid
         * @param - profileid
         */

        public function deleteEntryOfProfile($profileId) {
                try {
                        if ($profileId) {
                                $sql = "DELETE FROM MATCHALERT_TRACKING.TwoWayVsDppCount WHERE PROFILEID = :PROFILEID";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                                $prep->execute();
                        }
                } catch (PDOException $ex) {
                        throw new jsException($ex);
                }
        }

        /* this function inserts entry of a profileid
         * @param - profileid
         */

        public function insertEntryOfProfile($profileId, $date,$cnt) {
                try {
                        if ($profileId) {
                                $sql = "REPLACE INTO MATCHALERT_TRACKING.TwoWayVsDppCount(PROFILEID,ENTRY_DT,CNT) VALUES (:PROFILEID,:DATE,:CNT)";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                                $prep->bindValue(":CNT", $cnt, PDO::PARAM_INT);
                                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                                $prep->execute();
                        }
                } catch (PDOException $ex) {
                        throw new jsException($ex);
                }
        }

}
