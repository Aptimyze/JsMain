<?php

class billing_EXCLUSIVE_MAIL_LOG extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function insertMailLog($profileID,$mailType,$acptCount,$date) {
        try{
            $sql = "INSERT INTO billing.EXCLUSIVE_MAIL_LOG (PROFILE, MAIL_TYPE, ACPT_COUNT, DATE) 
                    VALUES ( :PROFILEID , :MAIL_TYPE , :COUNT , :DATE ) ;" ;

            $prep = $this->db->prepare($sql);
            $prep->bindValue(':PROFILEID',$profileID,PDO::PARAM_INT);
            $prep->bindValue(':MAIL_TYPE',$mailType,PDO::PARAM_STR);
            $prep->bindValue(':COUNT',$acptCount,PDO::PARAM_INT);
            $prep->bindValue(':DATE',$date,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function updateStatus($pid,$status,$date) {
        try {
            $sql = "UPDATE  billing.EXCLUSIVE_MAIL_LOG 
                    SET STATUS = :STATUS  
                    WHERE PROFILE = :PROFILE AND DATE = :DATE ;";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(':STATUS',$status,PDO::PARAM_STR);
            $prep->bindValue(':PROFILE',$pid,PDO::PARAM_INT);
            $prep->bindValue(':DATE',$date,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function getProfiles($status,$mailType,$date){
        try{
            $sql = "SELECT PROFILE
                    FROM billing.EXCLUSIVE_MAIL_LOG
                    WHERE STATUS = :STATUS AND MAIL_TYPE = :MAILTYPE AND DATE = :DATE ;" ;

            $prep = $this->db->prepare($sql);
            $prep->bindValue(':STATUS',$status,PDO::PARAM_STR);
            $prep->bindValue(':MAILTYPE',$mailType,PDO::PARAM_STR);
            $prep->bindValue(':DATE',$date,PDO::PARAM_STR);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $prep->fetch){
                $result[] = $row["PROFILE"];
            }
            return $result;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}

?>