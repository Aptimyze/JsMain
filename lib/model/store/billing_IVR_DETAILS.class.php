<?php
class billing_IVR_DETAILS extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function updateIvrDetails($billid, $billby, $refid)
    {
        try
        {
            $sql = "UPDATE billing.IVR_DETAILS SET BILLING='Y', BILLID=:BILLID, BILLING_BY=:BILLING_BY WHERE ID=:ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $res->bindValue(":ID", $refid, PDO::PARAM_INT);
            $res->bindValue(":BILLING_BY", $billby, PDO::PARAM_INT);
            $res->execute();
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
