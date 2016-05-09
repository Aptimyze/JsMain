<?php
class billing_MATRI_PURCHASES extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function insert($profileid, $billid)
    {
        try
        {
            $sql = "INSERT INTO billing.MATRI_PURCHASES (PROFILEID,BILLID,ENTRY_DT) VALUES (:PROFILEID,:BILLID,NOW())";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
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
