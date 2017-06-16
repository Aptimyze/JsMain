<?php

/**
 * Description of billing_PAYMENT_DETAIL_NEW 
 * To store negative transactions i.e. Cancel, Refund, Cheque Bounce, Cancellation.
 * @author nitish
 */
class billing_TAXBREAKUP extends TABLE{
    /*
     * CREATE TABLE billing.TAXBREAKUP(
ID INT( 11 ) AUTO_INCREMENT ,
BILLID INT( 11 ) NOT NULL DEFAULT  '0',
PROFILEID INT( 11 ) NOT NULL DEFAULT  '0',
SGST DOUBLE NOT NULL DEFAULT  '0',
IGST DOUBLE NOT NULL DEFAULT  '0',
COUNTRY_RES INT(3) NOT NULL DEFAULT  '0',
CGST DOUBLE NOT NULL DEFAULT  '0',
CITY_RES VARCHAR( 4 ) ,
ENTRY_DT DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
PRIMARY KEY ( ID )
)
     */
    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->ID_BIND_TYPE = "INT";
        $this->BILLID_BIND_TYPE = "INT";
        $this->PROFILEID_BIND_TYPE = "INT";
        $this->SGST_BIND_TYPE = "STR";
        $this->IGST_BIND_TYPE = "STR";
        $this->COUNTRY_RES_BIND_TYPE = "INT";
        $this->CGST_BIND_TYPE = "STR";
        $this->CITY_RES_BIND_TYPE = "STR";
        $this->ENTRY_DT_BIND_TYPE = "STR";
    }
    
    public function insertRecord($paramsStr,$valuesStr){
        if (empty($paramsStr) || empty($valuesStr)) {
            throw new jsException("Error processing insertRecord in billing.TAXBREAKUP, One of paramsStr or valuesStr is empty");
        }
        try{
            $sql = "INSERT INTO billing.TAXBREAKUP ({$paramsStr}) VALUES ({$valuesStr})";
            //print_r("<br>Sql:  $sql . <br>");
            $prep = $this->db->prepare($sql);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
