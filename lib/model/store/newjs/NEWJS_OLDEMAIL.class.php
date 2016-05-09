<?php
class newjs_OLDEMAIL extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function duplicateOldEmail($email) {
        try {
            // $sql = "SELECT PROFILEID FROM newjs.OLDEMAIL WHERE OLD_EMAIL = :EMAIL";
            // $prep = $this->db->prepare($sql);
            // $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            // $prep->execute();
            // $result = ($prep->fetch(PDO::FETCH_NUM) > 0) ? 1 : 0;
            $result = 0;
            return $result;
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function update($profileid,$email) {
        try {
            $sql = "INSERT IGNORE INTO newjs.OLDEMAIL VALUES(:PROFILEID,:EMAIL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>	
