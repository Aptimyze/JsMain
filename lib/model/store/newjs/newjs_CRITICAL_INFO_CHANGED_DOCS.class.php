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
        public function updateStatus($profileid,$status) {
                
                $sql = "UPDATE newjs.CRITICAL_INFO_CHANGED_DOCS SET SCREENED_STATUS = :SCREENED_STATUS WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":SCREENED_STATUS", $status, PDO::PARAM_STR);
                $res->execute();
                return true;
        }
        /**
        * Allot profile to screening user based on oldest 1st.
	* @param dt max upload date should be less than the passed date.
	* @return array containing profileid
        **/
        public function allottProfile($dt)
        {
		$sql = "SELECT A.PROFILEID, MAX(A.UPLOADED_ON) AS D FROM newjs.CRITICAL_INFO_CHANGED_DOCS A LEFT JOIN newjs.CRITICAL_INFO_DOC_ASSIGNED B ON A.PROFILEID = B.PROFILEID WHERE A.SCREENED_STATUS=:FLAG AND B.PROFILEID IS NULL GROUP BY A.PROFILEID HAVING D<:DATE ORDER BY D ASC LIMIT 1";
                $res=$this->db->prepare($sql);
                $res->bindValue(":DATE", $dt, PDO::PARAM_STR);
                $res->bindValue(":FLAG", "N", PDO::PARAM_STR);
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                        return $row;
		return NULL;
        }

}

?>
