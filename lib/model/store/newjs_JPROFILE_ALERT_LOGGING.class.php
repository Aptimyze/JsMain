<?php

class newjs_JPROFILE_ALERT_LOGGING extends TABLE
{

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insert($profileid,$changetype,$perviousValue,$currentValue,$date){
        try {
            $sql="INSERT INTO newjs.JPROFILE_ALERT_LOGGING(PROFILEID,CHANGE_TYPE,PREVIOUS_VALUE,CURRENT_VALUE,MOD_DT) VALUES (:PROFILEID,:CHANGETYPE,:PREVIOUSVALUE,:CURRENTVALUE,:MODDT)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->bindValue(":CHANGETYPE", $changetype, PDO::PARAM_STR);
            $prep->bindValue(":PREVIOUSVALUE", $perviousValue, PDO::PARAM_STR);
            $prep->bindValue(":CURRENTVALUE", $currentValue, PDO::PARAM_STR);
            $prep->bindValue(":MODDT", $date, PDO::PARAM_STR);
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }
}

