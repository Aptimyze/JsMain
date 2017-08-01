<?php
class billing_EXCLUSIVE_FOLLOWUPS extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /**
     * Function to get followups' details from billing.EXCLUSIVE_FOLLOWUPS table
     *
     * @param   
     * @return  array of rows
     */ 
	public function getPendingFollowUpEntries($followUpDate,$orderBy="",$groupBy="")
	{
		try
		{
		    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS WHERE STATUS IN ('F0','F1','F2') AND ((STATUS LIKE 'F0' AND FOLLOWUP1_DT = :CURRENT_DT) OR (STATUS LIKE 'F1' AND FOLLOWUP2_DT = :CURRENT_DT) OR (STATUS LIKE 'F2' AND FOLLOWUP3_DT = :CURRENT_DT))";
		    if($groupBy){
		    	$sql .= " GROUP BY ".$groupBy;
		    }
		    if($orderBy){
		      $sql = $sql." ORDER BY ".$orderBy." DESC,ENTRY_DT ASC";
		    }
		    
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CURRENT_DT", $followUpDate, PDO::PARAM_STR);
		    $res->execute();
		    while($result=$res->fetch(PDO::FETCH_ASSOC)){
		        $rows[] = $result;
		    }
		    return $rows;
		}
		catch(Exception $e){
		  throw new jsException($e);
		}
	}
}
?>