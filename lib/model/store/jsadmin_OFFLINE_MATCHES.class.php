<?php
class jsadmin_OFFLINE_MATCHES extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function updateOfflineBillingDetails($pid)
    {
        try
        {
            $sql="UPDATE jsadmin.OFFLINE_MATCHES SET SHOW_ONLINE='Y' WHERE PROFILEID=:PROFILEID AND DATEDIFF(now(),MATCH_DATE)<47";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }



    public function updateCategory($profileid,$category)
    {
        try
        {
            $sql="UPDATE jsadmin.OFFLINE_MATCHES SET CATEGORY=:CATEGORY WHERE MATCH_ID=:PROFILEID AND CATEGORY=''";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->bindValue(":CATEGORY",$category,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }
}
?>
