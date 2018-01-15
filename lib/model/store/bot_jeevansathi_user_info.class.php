<?php
class bot_jeevansathi_user_info extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function delete($profileid, $email) {
        try {
            $sql = "DELETE FROM bot_jeevansathi.user_info WHERE profileID=:PROFILEID OR gmail_ID=:EMAIL";
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

    public function insert($profileid,$email,$username) {
        try {
            $sql = "INSERT IGNORE INTO bot_jeevansathi.user_info('gmail_ID','on_off_flag','show_in_search','profileID','jeevansathi_ID') values(:EMAIL,0,1,:PROFILEID,:USERNAME)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $prep->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>	
