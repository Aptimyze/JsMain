<?php

class billing_VARIABLE_DISCOUNT_BACKUP_1DAY extends TABLE {
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function truncate()
    {
        try{
            $sql = "TRUNCATE TABLE billing.VARIABLE_DISCOUNT_BACKUP_1DAY";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    public function insertDataFromVariableDiscount()
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_BACKUP_1DAY SELECT * FROM billing.VARIABLE_DISCOUNT";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
}
?>