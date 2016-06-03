<?php
class billing_EASY_BILL extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function updateEasyBill($billid, $refid)
    {
        try
        {
            $sql = "UPDATE billing.EASY_BILL SET BILLING='Y',BILLID=:BILLID WHERE REF_ID=:ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $res->bindValue(":ID", $refid, PDO::PARAM_INT);
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
