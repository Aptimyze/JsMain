<?php

class billing_REV_MASTER extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    /**
     * Function to get transaction data from REV_MASTER table on the basis of sale ID
     *
     * @param   $transactionId
     * @return  $arrResult Transaction Data
     */ 
    public function getTransactionById($transactionId)
    {
      try
      {
        if(!$transactionId || !is_numeric($transactionId)){
            return 0;
        }
        $sql = "SELECT SALEID,START_DATE,END_DATE,SALE_DES,COMP_NAME FROM billing.REV_MASTER WHERE SALEID=:TRANSACTIONID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":TRANSACTIONID", $transactionId, PDO::PARAM_INT);
        $res->execute();
        if($arrResult = $res->fetch(PDO::FETCH_ASSOC)){
            return $arrResult;
        }   
        return 0;
      }catch(Exception $e){
         throw new jsException($e,$e->getMessage());
      }
    }
}
?>