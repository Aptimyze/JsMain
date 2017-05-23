<?php
class bot_jeevansathi_invite_send extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insert($email) {
        try {
            $sql = "INSERT INTO bot_jeevansathi.invite_send(PROFILEID,EMAIL) values(:PROFILEID,:EMAIL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
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
