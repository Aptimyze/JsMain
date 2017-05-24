<?php
class newjs_INVALID_EMAIL_MAILER extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function delete($profileid) {
        try {
            $sql = "DELETE FROM newjs.INVALID_EMAIL_MAILER WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            return $result;
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>	
