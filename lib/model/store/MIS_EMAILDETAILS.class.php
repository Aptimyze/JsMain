<?php
class MIS_EMAILDETAILS extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function updateInsert($date) {
        try {
            $sql = "UPDATE MIS.EMAILDETAILS set EMAIL_UPDATED=EMAIL_UPDATED+1 where ENTRY_DATE=:ENTRY_DATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DATE", $date, PDO::PARAM_STR);
            $prep->execute();
            $val = $this->db->lastInsertId();
            if (empty($val)) {
                $sql2 = "INSERT INTO MIS.EMAILDETAILS VALUES('','','','1',:ENTRY_DATE)";
                $prep2 = $this->db->prepare($sql2);
                $prep2->bindValue(":ENTRY_DATE", $date, PDO::PARAM_STR);
                $prep2->execute();
            }
        }
        catch(PDOException $e) {            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
