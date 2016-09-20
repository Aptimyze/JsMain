<?php

class MIS_ShortUrlLog extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertEntry($profileid,$page){
        try {
            
            $sql = "INSERT INTO MIS.ShortUrlMobileHitsLog ( `PROFILEID` , `URL` , `TIME`) VALUES (:PROFILEID,:PAGE, now())";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
            $res->bindValue(":PAGE", $page, PDO::PARAM_STR);
            $res->execute();
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
}
