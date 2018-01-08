<?php

class NEWJS_SEARCH_SORT_DT extends TABLE {

        public function __construct($dbname = '') {
                parent::__construct($dbname);
        }

        public function updateSortDate($profileId, $sortDate) {
                try {
                        $sql = "REPLACE INTO newjs.SEARCH_SORT_DT(PROFILEID,SORT_DT) values(:PROFILEID,:SORT_DT)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->bindValue(":SORT_DT", $sortDate, PDO::PARAM_STR);
                        $res->execute();
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

}

?>
