<?php

class billing_EDIT_DETAILS_LOG extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function logEntryInsert($paramsArr = array()){
        if(empty($paramsStr) || empty($valuesStr)){
            throw new jsException("Error processing logEntryInsert in billing_EDIT_DETAILS_LOG");
        }
        try 
        {
        	$keys_arr=array_keys($paramArr);
			$keys= implode(",",$keys_arr);
			$values=":".implode(",:",$keys_arr);
			$sqlProfile = "INSERT INTO billing.EDIT_DETAILS_LOG ($keys) VALUES($values)";
			$resProfile = $this->db->prepare($sqlProfile);
			foreach($paramArr as $key=>$val){
				$resProfile->bindValue(":".$key, $val);
			}
			$resProfile->execute();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }
}
?>