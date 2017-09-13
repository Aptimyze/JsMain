<?php
/*
 * This table is to log those profiles which have been deleted due to negative treatment process. The table logs data where the profile was sent an email infroming about the deletion of the profile.
 */
class incentive_NEGATIVE_DELETE_EMAIL_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
    

    public function insertNegativeDeleteLogEntry($profileid)
    {
        try
        {
            $sql ="INSERT IGNORE INTO incentive.NEGATIVE_DELETE_EMAIL_LOG(PROFILEID,ENTRY_DT) VALUES(:PROFILEID,now())";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $res->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    



}


