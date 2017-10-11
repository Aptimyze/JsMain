<?php
class bot_jeevansathi_user_online extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function delete($profileid) {
        try {
            $sql = "DELETE FROM bot_jeevansathi.user_online where  USER=:PROFILEID";
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
