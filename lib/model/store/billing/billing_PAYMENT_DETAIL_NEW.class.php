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
    /*
     * This function will update payment details new table with the franchisee commission amount
     * and apple commission amount and new amount which is 70% of net amount if apple flag is passed.
     * It updates the entry based on billid and profile id
     * it returns nothing 
     */
        public function updateComissions($profileid, $billid, $apple, $franchisee, $appleFlag = 0, $newAmount) {

        try {
            if (empty($apple)) {
                $apple = 0;
            }
            if (empty($franchisee)) {
                $franchisee = 0;
            }
            $sql = "UPDATE billing.PAYMENT_DETAIL_NEW SET FRANCHISEE_COMMISSION=:FRANCHISEE";
            //Start: JSC-2668: Apple Commission fix to calculate correct net amount in case billing is from apple device
            if ($appleFlag == 1) {
                $sql .= ", AMOUNT=:AMT, APPLE_COMMISSION=:APPLE";
            }
            $sql .= " WHERE PROFILEID=:PROFILEID AND BILLID=:BILLID AND AMOUNT>0";
//            if($appleFlag==1){
//                 $sql.= " AND APPLE_COMMISSION IS NULL";
//            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->bindValue(":FRANCHISEE", $franchisee, PDO::PARAM_INT);
            if ($appleFlag == 1) {
                $prep->bindValue(":APPLE", $apple, PDO::PARAM_INT);
                $prep->bindValue(":AMT", $newAmount, PDO::PARAM_INT);
            }
            //End: JSC-2668: Apple Commission fix to calculate correct net amount in case billing is from apple device
            $prep->execute();
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

}
