<?php

/**
 * Description of billing_NEGATIVE_TRANSACTIONS 
 * To store negative transactions i.e. Cancel, Refund, Cheque Bounce, Cancellation.
 * @author nitish
 */
class billing_NEGATIVE_TRANSACTIONS extends TABLE{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertRecord($params){
        if(is_array($params)){
            try{
                $params["ENTRY_DT"] = date('Y-m-d H:i:s');
                $sql = "INSERT INTO billing.NEGATIVE_TRANSACTIONS (RECEIPTID, BILLID, PROFILEID, TYPE, AMOUNT, CANCEL_TYPE, ENTRY_DT, INVOICE_NO) VALUES (:RECEIPTID, :BILLID, :PROFILEID, :TYPE, :AMOUNT, :CANCEL_TYPE, :ENTRY_DT, :INVOICE_NO)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":RECEIPTID",$params["RECEIPTID"],PDO::PARAM_INT);
                $prep->bindValue(":BILLID",$params["BILLID"],PDO::PARAM_INT);
                $prep->bindValue(":PROFILEID",$params["PROFILEID"],PDO::PARAM_INT);
                $prep->bindValue(":TYPE",$params["TYPE"],PDO::PARAM_INT);
                $prep->bindValue(":AMOUNT",$params["AMOUNT"],PDO::PARAM_INT);
                $prep->bindValue(":CANCEL_TYPE",$params["CANCEL_TYPE"],PDO::PARAM_INT);
                $prep->bindValue(":ENTRY_DT",$params["ENTRY_DT"],PDO::PARAM_INT);
                $prep->bindValue(":INVOICE_NO",$params["INVOICE_NO"],PDO::PARAM_INT);
                $prep->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
