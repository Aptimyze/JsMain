<?php
class JSADMIN_OFFLINE_BILLING extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    /**
      * This function gets a list of profiles that have been viewed by a user.
      * Pass $keyVal as 1 if the profileids are to sent in the key of the returned array.
    **/

    public function fetch($pid)
    {
        try
        {
            $sql="SELECT BILLID,ENTRY_DATE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID= :profileid ORDER BY ENTRY_DATE DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
                return $result;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function Update($profileid,$entry_date,$bid)
    {
        try
        {
            $sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE= 'N' WHERE PROFILEID= :profileid AND ENTRY_DATE= :entry_date AND BILLID= :bid";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":entry_date",$entry_date,PDO::PARAM_STR);
            $prep->bindValue(":bid",$bid,PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }

    }
    
    public function DPPUpdated($profileid,$bid)
    {
        try
        {
            $sql="UPDATE jsadmin.OFFLINE_BILLING SET CHANGE_DPP='Y' WHERE PROFILEID= :profileid AND BILLID= :bid";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":profileid",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":bid",$bid,PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }

    }

    public function fetchAccAllowed($pid)
    {
        try
        {
            $sql="SELECT ACC_ALLOWED FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID=:PROFILEID AND ACTIVE='Y'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
                return $result['ACC_ALLOWED'];
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function updateActiveStatus($activeStatus, $pid)
    {
        try
        {
            $sql="UPDATE jsadmin.OFFLINE_BILLING SET ACTIVE=:ACTIVE WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
            $prep->bindValue(":ACTIVE",$activeStatus,PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function insertOfflineBillEntry($pid, $billid, $acc_count)
    {
        try
        {
            $sql="INSERT INTO jsadmin.OFFLINE_BILLING(PROFILEID,BILLID,ENTRY_DATE,ACC_ALLOWED,ACTIVE) VALUES(:PROFILEID,:BILLID,now(),:ACC_ALLOWED,'Y')";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->bindValue(":ACC_ALLOWED",$acc_count,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }
}
?>
