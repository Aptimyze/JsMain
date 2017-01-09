<?php

/**
 * Description of billing_NEGATIVE_TRANSACTIONS 
 * To store negative transactions i.e. Cancel, Refund, Cheque Bounce, Cancellation.
 * @author nitish
 */
class billing_NEGATIVE_PAYMENT_DETAIL extends TABLE{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->RECEIPTID_BIND_TYPE = "INT";
        $this->BILLID_BIND_TYPE = "INT";
        $this->PROFILEID_BIND_TYPE = "INT";
        $this->TYPE_BIND_TYPE = "STR";
        $this->AMOUNT_BIND_TYPE = "INT";
        $this->CANCEL_TYPE_BIND_TYPE = "STR";
        $this->ENTRY_DT_BIND_TYPE = "STR";
    }
    
    public function insertRecord($paramsStr,$valuesStr){
        if (empty($paramsStr) || empty($valuesStr)) {
            throw new jsException("Error processing insertRecord in negative payment detail");
        }
        try{
            $sql = "INSERT IGNORE INTO billing.NEGATIVE_PAYMENT_DETAIL ({$paramsStr}) VALUES ({$valuesStr})";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
