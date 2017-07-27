<?php

class billing_MAIL_LOG extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function insertMailLog($profileID,$mailType,$acptCount,$date) {
        try{
            $sql = "INSERT INTO billing.MAIL_LOG (PROFILE, MAIL_TYPE, ACPT_COUNT, DATE) 
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
}

?>