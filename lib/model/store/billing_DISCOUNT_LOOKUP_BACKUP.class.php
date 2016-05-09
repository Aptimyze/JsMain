<?php

class billing_DISCOUNT_LOOKUP_BACKUP extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function addBackupFromDiscountLookup()
    {
        try{
            $sql = "INSERT INTO billing.DISCOUNT_LOOKUP_BACKUP SELECT * FROM billing.DISCOUNT_LOOKUP";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function truncate()
    {
        try{
            $sql = "TRUNCATE TABLE billing.DISCOUNT_LOOKUP_BACKUP";
            $res = $this->db->prepare($sql);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
