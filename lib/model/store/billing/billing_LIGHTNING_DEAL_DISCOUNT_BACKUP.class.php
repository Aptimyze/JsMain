<?php
class billing_LIGHTNING_DEAL_DISCOUNT_BACKUP extends TABLE{
    public function __construct($dbname="")
    {
		parent::__construct($dbname);
    }
    
    public function backupData($lessThanDate){
        try{
            $sql = "INSERT IGNORE INTO billing.LIGHTNING_DEAL_DISCOUNT_BACKUP SELECT * FROM billing.LIGHTNING_DEAL_DISCOUNT WHERE DEAL_DATE < :DEAL_DATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":DEAL_DATE", $lessThanDate, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
