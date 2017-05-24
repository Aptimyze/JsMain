<?php
class billing_VOUCHER_VIEWED extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function insertVoucherOptin($profileid)
    {
        try
        {
            $sql = "INSERT INTO billing.VOUCHER_VIEWED(ID,PROFILEID,VIEWED,ENTRY_DATE) VALUES ('',:PROFILEID,'',CURDATE())";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
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
