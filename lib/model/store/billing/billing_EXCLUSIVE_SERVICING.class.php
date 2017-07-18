<?php

class billing_EXCLUSIVE_SERVICING extends TABLE
{
	public function __construct($dbname="")
	{
    	parent::__construct($dbname);
  	}

  	/**
     * Function to get unscreened clients' details from billing.EXCLUSIVE_SERVICING table
     *
     * @param   $fields="*",$assignedTo=""$orderBy=""
     *     $assignedGtDt="*",$formatBillIdWise=true
     * @return  array of rows
     */ 
	public function getUnScreenedExclusiveMembers($agentUsername="",$orderBy=""){
	try{
	    $sql = "SELECT DISTINCT CLIENT_ID AS CLIENT_ID FROM billing.EXCLUSIVE_SERVICING WHERE SCREENED_STATUS = :SCREENED_STATUS";
	    if(!empty($agentUsername)){
	      $sql = $sql." AND AGENT_USERNAME = :AGENT_USERNAME";
	    }
	    if($orderBy)
	      $sql = $sql." ORDER BY ".$orderBy;
	    $res = $this->db->prepare($sql);
	    $res->bindValue(":SCREENED_STATUS",'N',PDO::PARAM_STR);
	    if(!empty($agentUsername)){
	      $res->bindValue(":AGENT_USERNAME", $agentUsername, PDO::PARAM_STR);
	    }
	    $res->execute();
	    while($result=$res->fetch(PDO::FETCH_ASSOC))
	    {
	        $rows[] = $result['CLIENT_ID'];
	    }
	    return $rows;
	}
	catch(Exception $e)
	{
	  throw new jsException($e);
	}
	}

}
?>