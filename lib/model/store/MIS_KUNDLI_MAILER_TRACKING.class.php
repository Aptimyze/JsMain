<?php

class MIS_KUNDLI_MAILER_TRACKING extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertDateAndSub($entryDt) {
        try {
            $sql = "INSERT INTO MIS.KUNDLI_MAILER_TRACKING (DATE,UNSUBSCRIPTION) VALUES (:ENTRY_DT,1) ON DUPLICATE KEY UPDATE UNSUBSCRIPTION = UNSUBSCRIPTION+1";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
