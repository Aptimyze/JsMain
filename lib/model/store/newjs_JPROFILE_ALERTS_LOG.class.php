<?php
class newjs_JPROFILE_ALERTS_LOG extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function update($profileid, $key, $val, $entryDt) {
        try {
            $sql = "INSERT IGNORE INTO newjs.JPROFILE_ALERTS_LOG(PROFILEID,$key,MOD_DT,FROM_PAGE) VALUES (:PROFILEID,:VAL,:ENTRY_DT,'U')";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->bindValue(":VAL", $val, PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
}
?>
