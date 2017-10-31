<?php
class billing_EXCLUSIVE_FOLLOWUPS extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
        $this->ID_BIND_TYPE = "INT";
		$this->STATUS_BIND_TYPE = "STR";
		$this->FOLLOWUP_1_BIND_TYPE = "STR";
		$this->FOLLOWUP_2_BIND_TYPE = "STR";
		$this->FOLLOWUP_3_BIND_TYPE = "STR";
		$this->FOLLOWUP_4_BIND_TYPE = "STR";
		$this->FOLLOWUP1_DT_BIND_TYPE = "STR";
		$this->FOLLOWUP2_DT_BIND_TYPE = "STR";
		$this->FOLLOWUP3_DT_BIND_TYPE = "STR";
		$this->FOLLOWUP4_DT_BIND_TYPE = "STR";
		$this->CONCALL_SCH_DT_BIND_TYPE = "STR";

    }
    
    public function insertIntoExclusiveFollowups($params){
        if(is_array($params)){
            try{
                $sql = "INSERT INTO billing.EXCLUSIVE_FOLLOWUPS (AGENT_USERNAME, CLIENT_ID, MEMBER_ID, ENTRY_DT, FOLLOWUP1_DT, STATUS) VALUES (:AGENT_USERNAME, :CLIENT_ID, :MEMBER_ID, :ENTRY_DT, :FOLLOWUP1_DT, :STATUS)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":AGENT_USERNAME",$params["AGENT_USERNAME"],PDO::PARAM_STR);
                $res->bindValue(":CLIENT_ID",$params["CLIENT_ID"],PDO::PARAM_INT);
                $res->bindValue(":MEMBER_ID",$params["MEMBER_ID"],PDO::PARAM_INT);
                $res->bindValue(":ENTRY_DT",$params["ENTRY_DT"],PDO::PARAM_STR);
                $res->bindValue(":FOLLOWUP1_DT",$params["FOLLOWUP1_DT"],PDO::PARAM_STR);
                $res->bindValue(":STATUS",$params["STATUS"],PDO::PARAM_INT);                
                $res->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }

    /**
     * Function to get followup' details from billing.EXCLUSIVE_FOLLOWUPS table
     *
     * @param   followUpId
     * @return  row
     */ 
	public function getFollowUpEntry($followUpId)
	{
		if(is_numeric($followUpId)){
			try{
			    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS WHERE ID=:ID";
			    $res = $this->db->prepare($sql);
			    $res->bindValue(":ID", $followUpId, PDO::PARAM_INT);
			    $res->execute();
			    if($result=$res->fetch(PDO::FETCH_ASSOC)){
			        return $result;
			    }
			    return null;
			}
			catch(Exception $e){
			  throw new jsException($e);
			}
		}
		else{
			return null;
		}
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
		    $sql = "SELECT * FROM billing.EXCLUSIVE_FOLLOWUPS WHERE (STATUS LIKE 'F0' AND FOLLOWUP1_DT <= :CURRENT_DT) OR (STATUS LIKE 'F1' AND FOLLOWUP2_DT <= :CURRENT_DT) OR (STATUS LIKE 'F2' AND FOLLOWUP3_DT <= :CURRENT_DT) OR (STATUS LIKE 'F3' AND FOLLOWUP4_DT <= :CURRENT_DT)";
		    $sql .= "ORDER BY STATUS DESC,CLIENT_ID";
		    if($offset>=0 && !empty($limit)){
		    	$sql .= " LIMIT ".$offset.",".$limit;
		    }
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CURRENT_DT", $followUpDate, PDO::PARAM_STR);
		    $res->execute();
		    while($result=$res->fetch(PDO::FETCH_ASSOC)){ 
		    	if(strpos($result['FOLLOWUP_1'], "|")!==false){
		    		$followup1Str = explode("|", $result['FOLLOWUP_1']);
		    		$result['FOLLOWUP_1'] = $followup1Str[0];
		    		if(count($followup1Str)==3 && !empty($followup1Str[1])){
		    			$result['FOLLOWUP_1'] = $result['FOLLOWUP_1'].'('.$followup1Str[1].')';
		    		}
		    		unset($followup1Str);
		    	}
		    	if(strpos($result['FOLLOWUP_2'], "|")!==false){
		    		$followup2Str = explode("|", $result['FOLLOWUP_2']);
		    		$result['FOLLOWUP_2'] = $followup2Str[0];
		    		if(count($followup2Str)==3 && !empty($followup2Str[1])){
		    			$result['FOLLOWUP_2'] = $result['FOLLOWUP_2'].'('.$followup2Str[1].')';
		    		}
		    		unset($followup2Str);
		    	}
		    	if(strpos($result['FOLLOWUP_3'], "|")!==false){
		    		$followup3Str = explode("|", $result['FOLLOWUP_3']);
		    		$result['FOLLOWUP_3'] = $followup3Str[0];
		    		if(count($followup3Str)==3 && !empty($followup3Str[1])){
		    			$result['FOLLOWUP_3'] = $result['FOLLOWUP_3'].'('.$followup3Str[1].')';
		    		}
		    		unset($followup3Str);
		    	}
		    	if(strpos($result['FOLLOWUP_4'], "|")!==false){
		    		$followup4Str = explode("|", $result['FOLLOWUP_4']);
		    		$result['FOLLOWUP_4'] = $followup4Str[0];
		    		if(count($followup4Str)==3 &&  !empty($followup4Str[1])){
		    			$result['FOLLOWUP_4'] = $result['FOLLOWUP_4'].'('.$followup4Str[1].')';
		    		}
		    		unset($followup4Str);
		    	}
		        $rows[$result["CLIENT_ID"]][] = $result;
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

		    $sql = "SELECT count(*) AS CNT FROM billing.EXCLUSIVE_FOLLOWUPS WHERE (STATUS LIKE 'F0' AND FOLLOWUP1_DT <= :CURRENT_DT) OR (STATUS LIKE 'F1' AND FOLLOWUP2_DT <= :CURRENT_DT) OR (STATUS LIKE 'F2' AND FOLLOWUP3_DT <= :CURRENT_DT) OR (STATUS LIKE 'F3' AND FOLLOWUP4_DT <= :CURRENT_DT)";
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
                            . " ORDER BY CONCALL_SCH_DT DESC";
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
        
        	 /** Function to update screening status
	 *
	 * @param   $agentUsername,$clientId,$screenedStatus='Y'
	 * @return  none
	 */
	public function markConcallStatusForId($id,$status,$date){
		try
		{
		  if($id)
		  {
		    $sql = "UPDATE billing.EXCLUSIVE_FOLLOWUPS SET CONCALL_STATUS=:STATUS,CONCALL_ACTUAL_DT=:ACTUAL_DT WHERE ID=:ID";
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
		    $res->bindValue(":ACTUAL_DT", $date, PDO::PARAM_STR);
		    $res->bindValue(":ID",$id, PDO::PARAM_STR);
		    $res->execute();
		  }
		}
		catch(Exception $e)
		{
		  throw new jsException($e);
		}
	}

	/**
     * Function to update details of followup
     *
     * @param   $id,$updateArr=""
     * @return  none
     */ 
  public function updateFollowUp($id,$updateArr=""){
    try{
      if($id>=0 && is_array($updateArr) && count($updateArr)>0)
      {
      	$updateStr = "";
      	foreach ($updateArr as $key => $value) {
      		$updateStr .= $key."=:".$key.",";
      	}
      	$updateStr = substr($updateStr,0,-1);
        $sql = "UPDATE billing.EXCLUSIVE_FOLLOWUPS SET ".$updateStr." WHERE ID=:ID";
        $res = $this->db->prepare($sql);
        foreach ($updateArr as $key => $value) {
      		$res->bindValue(":".$key, $value, constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
      	}
        $res->bindValue(":ID", $id, PDO::PARAM_INT);
        $res->execute();
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  public function clientFollowupHistory($agent)
  {
  	try {
  		$sql = "SELECT MEMBER_ID,CLIENT_ID,ENTRY_DT,FOLLOWUP_1,FOLLOWUP_2,FOLLOWUP_3,FOLLOWUP_4,FOLLOWUP1_DT,FOLLOWUP2_DT,FOLLOWUP3_DT,FOLLOWUP4_DT,STATUS FROM billing.EXCLUSIVE_FOLLOWUPS"
                            . " WHERE AGENT_USERNAME=:AGENT"
                            . " ORDER BY ENTRY_DT DESC";
		    $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
		    $res->execute();
		    while($result=$res->fetch(PDO::FETCH_ASSOC)){
		        $rows[] = $result;
		    }
		    return $rows;
  	} catch (Exception $e) {
  		throw new jsException($e);
  	}
  }

  public function getDetailsForProposalMail(){
  	try{
        $tomorrowDT = date("Y-m-d",strtotime(' +1 day'));

  		$sql = "SELECT AGENT_USERNAME,CLIENT_ID,MEMBER_ID,STATUS FROM billing.EXCLUSIVE_FOLLOWUPS WHERE (STATUS = 'F0' AND FOLLOWUP1_DT = :CURRENT_DT) OR(STATUS = 'F1' AND FOLLOWUP2_DT = :CURRENT_DT) OR (STATUS = 'F2' AND FOLLOWUP3_DT = :CURRENT_DT) OR (STATUS = 'F3' AND FOLLOWUP4_DT = :CURRENT_DT)";

  		$prep = $this->db->prepare($sql);
  		$prep->bindValue(":CURRENT_DT",$tomorrowDT,PDO::PARAM_STR);
  		$prep->execute();
        $prep->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $prep->fetch()){
        	$result[] = $row;
		}

		return $result;
	}catch (Exception $e){
  		throw new jsException($e);
	}
  }
  public function followupHistoryForClient($agent,$clientid)
  {
  	try {
  		$date = date("Y-m-d",strtotime(" -7 days"));
  		$sql = "SELECT ID,CLIENT_ID,MEMBER_ID,FOLLOWUP_1,FOLLOWUP_2,FOLLOWUP_3,FOLLOWUP_4,FOLLOWUP1_DT,FOLLOWUP2_DT,FOLLOWUP3_DT,FOLLOWUP4_DT,CONCALL_ACTUAL_DT,STATUS FROM billing.EXCLUSIVE_FOLLOWUPS"
                            . " WHERE AGENT_USERNAME=:AGENT"
                            . " AND CLIENT_ID=:CLIENTID AND MAILER=:MAILER AND (FOLLOWUP1_DT >= :DATE OR FOLLOWUP2_DT >= :DATE OR FOLLOWUP3_DT >= :DATE OR FOLLOWUP4_DT >= :DATE OR CONCALL_ACTUAL_DT >= :DATE)";
  			print_r($sql);
		    $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
        	$res->bindValue(":DATE", $date, PDO::PARAM_STR);
            $res->bindValue(":CLIENTID", $clientid, PDO::PARAM_INT);
            $res->bindValue(":MAILER", 'Y', PDO::PARAM_STR);
		    $res->execute();
		    while($result=$res->fetch(PDO::FETCH_ASSOC)){
		        $rows[$result['MEMBER_ID']] = $result;
		    }
		    return $rows;
  	} catch (Exception $e) {
  		throw new jsException($e);
  	}
  }

  public function updateMailerFlag($idArr){
  	try{
  		$sql = "UPDATE billing.EXCLUSIVE_FOLLOWUPS SET MAILER = 'N' WHERE ID IN ($idArr)";
  		$prep = $this->db->prepare($sql);
  		$prep->execute();
  	}catch(Exception $e){
  		throw new jsException($e);
  	}
  }
}
?>
