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

    public function logEntryInsert($paramsArr = array()){
        if(empty($paramsArr)){
            throw new jsException("Error processing logEntryInsert in billing_EDIT_DETAILS_LOG");
        }
        try 
        {
			$sqlInsert = "INSERT IGNORE INTO billing.EDIT_DETAILS_LOG (`PROFILEID`,`BILLID`,`RECEIPTID`,`CHANGES`,`ENTRYBY`,`ENTRY_DT`) VALUES (:PROFILEID,:BILLID,:RECEIPTID,:CHANGES,:ENTRYBY,:ENTRY_DT)";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":RECEIPTID",$key,constant('PDO::PARAM_'.$this->{'RECEIPTID_BIND_TYPE'}));
			$resInsert->bindValue(":CHANGES",$key,constant('PDO::PARAM_'.$this->{'CHANGES_BIND_TYPE'}));
			$resInsert->bindValue(":BILLID",$messageId,constant('PDO::PARAM_'.$this->{'BILLID_BIND_TYPE'}));
			$resInsert->bindValue(":ENTRYBY",$nextPollTime,constant('PDO::PARAM_'.$this->{'ENTRYBY_BIND_TYPE'}));
			$resInsert->bindValue(":ENTRY_DT",$sent,constant('PDO::PARAM_'.$this->{'ENTRY_DT_BIND_TYPE'}));
			$resInsert->execute();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }
}
?>