<?php
class NEWJS_FRESHCHAT extends TABLE{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function insertRestoreID($profileid,$restoreid){
        try{
            $sql = "INSERT IGNORE INTO newjs.FRESHCHAT (PROFILEID, RESTOREID) VALUES (:PROFILEID,:RESTOREID)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":RESTOREID",$restoreid,PDO::PARAM_STR);
            $prep->execute();
        }catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function getRestoreID($profileid){
        try{
            $sql = "SELECT RESTOREID FROM newjs.FRESHCHAT WHERE PROFILEID = :PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $prep->fetch()){
                $result = $row;
            }
            return $result;
        }catch (Exception $e){
            throw new jsException($e);
        }
    }
}
?>