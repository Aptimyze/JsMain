<?php

/**
 * Description of billing_NEGATIVE_TRANSACTIONS 
 * To store negative transactions i.e. Cancel, Refund, Cheque Bounce, Cancellation.
 * @author nitish
 */
class billing_NEGATIVE_TRANSACTIONS extends TABLE{
    
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
    
    public function insertRecord($params){
        if(is_array($params)){
            try{
                $params["ENTRY_DT"] = date('Y-m-d H:i:s');
                $sql = "INSERT INTO billing.NEGATIVE_TRANSACTIONS (";
                foreach($params as $key => $val){
                    $sql.="$key, ";
                }
                $sql = rtrim($sql,", ");
                $sql.=") VALUES (";
                foreach($params as $key => $val){
                    $sql.=":$key, ";
                }
                $sql = rtrim($sql,", ");
                $sql.=")";
                $prep = $this->db->prepare($sql);
                foreach($params as $key => $val){
                    $prep->bindValue(":$key",$val,constant("PDO::PARAM_".$this->{$key."_BIND_TYPE"}));
                }
                $prep->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
