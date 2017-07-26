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
	public function getUnScreenedExclusiveMembers($agentUsername="",$orderBy="")
	{
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

	/**
     * Function to insert profileid into EXCLUSIVE_MEMBERS table
     *
     * @param   $params
     * @return  none
     */ 
	public function addExclusiveServicingClient($params)
	{
		try
		{
			if(is_array($params) && $params)
			{
				$sql = "INSERT IGNORE INTO billing.EXCLUSIVE_SERVICING (ID,AGENT_USERNAME,CLIENT_ID,ASSIGNED_DT,ENTRY_DT) VALUES(NULL,:AGENT_USERNAME,:CLIENT_ID,:ASSIGNED_DT,:ENTRY_DT)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":AGENT_USERNAME", $params["AGENT_USERNAME"], PDO::PARAM_STR);
				$res->bindValue(":CLIENT_ID", $params["CLIENT_ID"], PDO::PARAM_INT);
				$res->bindValue(":ASSIGNED_DT", $params["ASSIGNED_DT"], PDO::PARAM_STR);
				$res->bindValue(":ENTRY_DT", date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$res->execute();
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	/**
	 * Function to remove exclusive client entry
	 *
	 * @param   $agentUsername,$clientId
	 * @return  none
	 */ 
	public function removeExclusiveClientEntry($agentUsername,$clientId)
	{
		try
		{
		  if($clientId && $agentUsername)
		  {
		    $sql = "DELETE FROM billing.EXCLUSIVE_SERVICING WHERE CLIENT_ID=:CLIENT_ID AND AGENT_USERNAME=:AGENT_USERNAME AND SCREENED_STATUS=:SCREENED_STATUS";
		    error_log("ankita DELETE FROM billing.EXCLUSIVE_SERVICING WHERE CLIENT_ID=$clientId AND AGENT_USERNAME=$agentUsername AND SCREENED_STATUS=N");
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
		    $res->bindValue(":AGENT_USERNAME", $agentUsername, PDO::PARAM_STR);
		    $res->bindValue(":SCREENED_STATUS", 'N', PDO::PARAM_STR);
		    $res->execute();
		  }
		}
		catch(Exception $e)
		{
		  throw new jsException($e);
		}
	}

	/**
	 * Function to get profileId and agentName for sending MatchMails
	 * 
	 * @return list of agentName and profileIDs
	 */
	public function getProfileIDandAgentNameForMailing()	{
		try {
			$tomorrow = strtoupper(date('D',strtotime(' +1 day')));
			$sql = "SELECT AGENT_USERNAME, CLIENT_ID 
					FROM billing.EXCLUSIVE_SERVICING 
					WHERE SERVICE_DAY = :tomorrow ;" ;

			$prep = $this->db->prepare($sql);
			$prep->bindValue(':tomorrow',$tomorrow,PDO::PARAM_STR);
			$prep->execute();
			$prep->setFetchMode(PDO::FETCH_ASSOC);
			
			while ($res = $prep->fetch()) {
				$result[$res['AGENT_USERNAME']][] = $res;
			}

			return $result;
		} catch (Exception $e) {
			throw new jsException($e);
		}
	}
}
?>