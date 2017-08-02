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
	public function getPendingFollowUpEntries($followUpDate,$limit="",$offset=0)
	{
		try
		{
		    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS WHERE STATUS IN ('F0','F1','F2') AND ((STATUS LIKE 'F0' AND FOLLOWUP1_DT = :CURRENT_DT) OR (STATUS LIKE 'F1' AND FOLLOWUP2_DT = :CURRENT_DT) OR (STATUS LIKE 'F2' AND FOLLOWUP3_DT = :CURRENT_DT))";

		    $sql .= " GROUP BY MEMBER_ID ORDER BY STATUS DESC,ENTRY_DT ASC";
		   
		    if($offset>=0 && !empty($limit)){
		    	$sql .= " LIMIT ".$offset.",".$limit;
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

	/**
     * Function to get followups' count from billing.EXCLUSIVE_FOLLOWUPS table
     *
     * @param   
     * @return  array of rows
     */ 
	public function getPendingFollowUpEntriesCount($followUpDate)
	{
		try
		{
		    $sql = "SELECT count(*) AS CNT FROM billing.EXCLUSIVE_FOLLOWUPS WHERE STATUS IN ('F0','F1','F2') AND ((STATUS LIKE 'F0' AND FOLLOWUP1_DT = :CURRENT_DT) OR (STATUS LIKE 'F1' AND FOLLOWUP2_DT = :CURRENT_DT) OR (STATUS LIKE 'F2' AND FOLLOWUP3_DT = :CURRENT_DT))";
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CURRENT_DT", $followUpDate, PDO::PARAM_STR);
		    $res->execute();
		    
		    if($result=$res->fetch(PDO::FETCH_ASSOC)){
		        return $result['CNT'];
		    }
		    return 0;
		}
		catch(Exception $e){
		  throw new jsException($e);
		}
	}

     /**
     * Function to get pending con calls count from billing.EXCLUSIVE_FOLLOWUPS table for a
     * particular agent 
     * @param   
     * @return  array of rows
     */
    public function getPendingConcallsCount($date, $agent) {
        try {
            $sql = "SELECT count(*) AS CNT FROM billing.EXCLUSIVE_FOLLOWUPS where CONCALL_STATUS != 'Y' AND CONCALL_SCH_DT <=:DATE AND AGENT_USERNAME=:AGENT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":DATE", $date, PDO::PARAM_STR);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
            $res->execute();

            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return $result['CNT'];
            }
            return 0;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }


    /**
     * Function to get con calls details from billing.EXCLUSIVE_FOLLOWUPS table
     *
     * @param   
     * @return  array of rows
     */ 
	public function getPendingConcallsEntries($date,$agent,$limit="",$offset=0)
	{
		try
		{
		    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS"
                            . " WHERE CONCALL_STATUS != 'Y'"
                            . " AND CONCALL_SCH_DT <=:DATE"
                            . " AND AGENT_USERNAME=:AGENT"
                            . " ORDER BY CLIENT_ID ,CONCALL_SCH_DT ASC";
		    //print_r("$sql<br>$agent<br>$date");
		    if($offset>=0 && !empty($limit)){
		    	$sql .= " LIMIT ".$offset.",".$limit;
		    }
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":DATE", $date, PDO::PARAM_STR);
                    $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
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