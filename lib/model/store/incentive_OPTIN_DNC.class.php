<?php

class incentive_OPTIN_DNC extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function addOptinRecord($phoneNo)
    {
        try
        {
            $entryDt =date("Y-m-d H:i:s",time());
            $sql="INSERT IGNORE INTO incentive.OPTIN_DNC(PHONE,TIME) VALUES(:PHONE,'$entryDt')";
            $row = $this->db->prepare($sql);
            $row->bindValue(":PHONE",$phoneNo, PDO::PARAM_STR);
            $row->execute();

        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
}
