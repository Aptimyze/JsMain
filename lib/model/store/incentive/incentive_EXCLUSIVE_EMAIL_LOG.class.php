<?php
class incentive_EXCLUSIVE_EMAIL_LOG extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
    

    public function insertExclusiveLogEntry($profileid,$executive)
    {
        try
        {
            $sql ="INSERT IGNORE INTO incentive.EXCLUSIVE_EMAIL_LOG(PROFILEID,ENTRY_DT, EXECUTIVE) VALUES(:PROFILEID,now(),:EXECUTIVE)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
	    $res->bindValue(":EXECUTIVE", $executive, PDO::PARAM_STR);
            $res->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    



}


