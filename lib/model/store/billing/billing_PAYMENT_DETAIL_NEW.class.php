<?php

/**
 * Description of billing_PAYMENT_DETAIL_NEW 
 * To store negative transactions i.e. Cancel, Refund, Cheque Bounce, Cancellation.
 * @author nitish
 */
class billing_PAYMENT_DETAIL_NEW extends TABLE{
    
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
            $sql = "INSERT INTO billing.PAYMENT_DETAIL_NEW ({$paramsStr}) VALUES ({$valuesStr})";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getCancelledBillIdDetails($billid){
        if($billid){
            try{
                $sql = "SELECT * from billing.PAYMENT_DETAIL_NEW WHERE BILLID = :BILLID AND STATUS = 'CANCEL' ORDER BY RECEIPTID LIMIT 1";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
                $prep->execute();
                while($result = $prep->fetch(PDO::FETCH_ASSOC)){
                    $output = $result;
                }
                return $output;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
