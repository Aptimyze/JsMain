<?php

/**
 * class PROFILE_VERIFICATION_FSO
 * This store class is responsible to handle FSO Status for verification seal.
 * @author Akash Kumar
 */
class PROFILE_VERIFICATION_FSO extends TABLE {

        public function __construct($dbname = "") {
                parent::__construct($dbname);
        }

        /**
         * This function checks FSO visit status for given profileID.
         * @param $profileid - profile ID of user
         */
        public function check($profileid) {
                try {
                        $sql = "select * FROM PROFILE_VERIFICATION.FSO WHERE PROFILEID=:profileid";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                        $this->logFunctionCalling(__FUNCTION__);
                        if (($res->fetchColumn()) > 0)
                                return 1;
                        else
                                return 0;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

        /**
         * This function checks insert FSO visit status for given profileID.
         * @param $profileid - profile ID of user
         */
        public function insert($profileid) {
                try {
                        $sql = "insert ignore into PROFILE_VERIFICATION.FSO (PROFILEID) values(:profileid)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                        $this->logFunctionCalling(__FUNCTION__);
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

        /**
         * This function delete FSO visit status for given profileID.
         * @param $profileid - profile ID of user
         */
        public function delete($profileid) {
                try {
                        $sql = "DELETE FROM PROFILE_VERIFICATION.FSO WHERE PROFILEID=:profileid";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $res->execute();
                        $this->logFunctionCalling(__FUNCTION__);
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }
        
        private function logFunctionCalling($funName)
        {return;
          // $key = __CLASS__.'_'.date('Y-m-d');
          // JsMemcache::getInstance()->hIncrBy($key, $funName);

          // JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
        }

}

?>
