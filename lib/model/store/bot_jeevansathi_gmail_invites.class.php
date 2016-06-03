<?php
class bot_jeevansathi_gmail_invites extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insert($username,$email) {
        try {
            $sql = "INSERT INTO bot_jeevansathi.gmail_invites(profileid,gmailid) values(:USERNAME,:EMAIL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
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
