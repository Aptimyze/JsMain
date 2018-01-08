<?php
class billing_AUTOINCREMENT_RECEIPTID extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertNewAutoIncrementReceiptId() {
        try {
            $dtTime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO billing.AUTOINCREMENT_RECEIPTID (ID,ENTRY_DT) VALUES(NULL,:ENTRY_DT)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DT", $dtTime, PDO::PARAM_STR);
            $prep->execute();
            return $this->db->lastInsertId();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getLastInsertedRow(){
        try {
            $sql = "SELECT * from billing.AUTOINCREMENT_RECEIPTID ORDER BY ID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function truncateAutoIncrementReceiptIdTable(){
        try {
            $sql = "TRUNCATE TABLE billing.AUTOINCREMENT_RECEIPTID";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
}
?>
