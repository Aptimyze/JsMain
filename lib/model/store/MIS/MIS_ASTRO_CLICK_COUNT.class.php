<?php

class MIS_ASTRO_CLICK_COUNT extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertInAstroClickCount($profileid,$type,$user_mtongue){
        try {
            $sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT (PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES (:PROFILEID, :TYPE, NOW(), :MTONGUE)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
            $res->bindValue(":TYPE", $type, PDO::PARAM_STR);
            $res->bindValue(":MTONGUE", $user_mtongue, PDO::PARAM_INT);
            $res->execute();
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
}
