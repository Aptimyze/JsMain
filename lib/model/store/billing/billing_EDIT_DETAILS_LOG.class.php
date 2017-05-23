<?php

class billing_EDIT_DETAILS_LOG extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->CHANGES_BIND_TYPE = "STR";
		$this->PROFILEID_BIND_TYPE = "INT";
		$this->BILLID_BIND_TYPE = "INT";
		$this->RECEIPTID_BIND_TYPE = "INT";
		$this->ENTRYBY_BIND_TYPE = "STR";
		$this->ENTRY_DT_BIND_TYPE = "STR";
    }

    /*function - logEntryInsert
    * maps passed array to log values to be inserted
    * @inputs: $paramsArr
    * @outputs: none
    */
    public function logEntryInsert($paramsArr = array()){
        if(empty($paramsArr)){
            throw new jsException("Error processing logEntryInsert in billing_EDIT_DETAILS_LOG");
        }
        try 
        {
			$sqlInsert = "INSERT IGNORE INTO billing.EDIT_DETAILS_LOG (`PROFILEID`,`BILLID`,`RECEIPTID`,`CHANGES`,`ENTRYBY`,`ENTRY_DT`) VALUES (:PROFILEID,:BILLID,:RECEIPTID,:CHANGES,:ENTRYBY,:ENTRY_DT)";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":PROFILEID",$paramsArr["PROFILEID"],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":RECEIPTID",$paramsArr["RECEIPTID"],constant('PDO::PARAM_'.$this->{'RECEIPTID_BIND_TYPE'}));
			$resInsert->bindValue(":CHANGES",$paramsArr["CHANGES"],constant('PDO::PARAM_'.$this->{'CHANGES_BIND_TYPE'}));
			$resInsert->bindValue(":BILLID",$paramsArr["BILLID"],constant('PDO::PARAM_'.$this->{'BILLID_BIND_TYPE'}));
			$resInsert->bindValue(":ENTRYBY",$paramsArr["ENTRYBY"],constant('PDO::PARAM_'.$this->{'ENTRYBY_BIND_TYPE'}));
			$resInsert->bindValue(":ENTRY_DT",$paramsArr["ENTRY_DT"],constant('PDO::PARAM_'.$this->{'ENTRY_DT_BIND_TYPE'}));
			$resInsert->execute();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }
}
?>