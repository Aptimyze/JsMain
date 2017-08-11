<?php
/*
 * Email Stage key:
 * Q: Queued
 * C: Completed
 */
class billing_EXCLUSIVE_SERVICING extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    /**
     * Function to get count of pending welcome calls for a particular agent
     *
     * @param   $agent ID
     * @return  array of rows
     */
    public function getWelcomeCallsCount($agent) {
        try {
            $sql = "SELECT COUNT(1) AS CNT FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE SERVICE_DAY IN ('','NA')";
            $sql = $sql . " AND AGENT_USERNAME =:AGENT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return $result['CNT'];
            }
            return NULL;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    /**
     * Function to get profile details from EXCLUSIVE_SERVICING table
     *
     * @param   $fields,$assigned flag
     * @return  array of rows
     */
    public function getClientsForWelcomeCall($fields = "*", $agent, $orderBy = "") {
        try {
            $sql = "SELECT " . $fields . " FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE SERVICE_DAY IN ('','NA')";
            $sql = $sql . " AND AGENT_USERNAME ='$agent'";
            if ($orderBy)
                $sql = $sql . " ORDER BY " . $orderBy . " DESC";
            $res = $this->db->prepare($sql);
            $res->execute();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $rows[$result["CLIENT_ID"]] = $result;
            }
            return $rows;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    /**
     * Function to get bio data location and upload date for a particular user
     *
     * @param   $profileid
     * @return  array of biodata location and biodata upload date
     */
    public function checkBioData($profileid) {
        try {
            $sql = "SELECT BIODATA_LOCATION,BIODATA_UPLOAD_DT,AGENT_USERNAME FROM billing.EXCLUSIVE_SERVICING where CLIENT_ID = :CLIENTID LIMIT 1";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return $result;
            }
            return false;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

     /**
     * Function to delete the biodata location for a particular client
     *
     * @param   $profileid
     * @return  nothing
     */
    public function deleteBioData($profileid) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET BIODATA_LOCATION = '' where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

     /**
     * Function to set the biodata location and the timestamp when it was uploaded
     *
     * @param   $profileid, $location
     * @return  nothing
     */
    public function setBioDataLocation($profileid,$location) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET BIODATA_LOCATION =:PATH, BIODATA_UPLOAD_DT = now() where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PATH", $location, PDO::PARAM_STR);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
     /**
     * Function to set the service day and date when it was set
     *
     * @param   $profileid,$serviceDay
     * @return  nothing
     */
    public function setServiceDay($profileid,$serviceDay,$emailStage) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET SERVICE_DAY=:SERVICE_DAY, SERVICE_SET_DT = now(),EMAIL_STAGE=:EMAIL_STAGE where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->bindValue(":SERVICE_DAY", $serviceDay, PDO::PARAM_STR);
            $res->bindValue(":EMAIL_STAGE", $emailStage, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
         /**
     * Function to set the service day and date when it was set
     *
     * @param   $profileid,$serviceDay
     * @return  nothing
     */
    public function updateMailerStatus($profileid,$emailStatus) {
        try {
            $sql = "update billing.EXCLUSIVE_SERVICING SET EMAIL_STAGE =:EMAIL_STAGE where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->bindValue(":EMAIL_STAGE", $emailStatus, PDO::PARAM_STR);
            $res->execute();
            return true;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
     /**
     * Funtion to get the service day,date when it was set and the stage of email
     *
     * @param   $profileid
     * @return  array of service day and service set date
     */
    public function getServiceDay($profileid) {
        try {
            $sql = "SELECT SERVICE_DAY,SERVICE_SET_DT,EMAIL_STAGE FROM billing.EXCLUSIVE_SERVICING where CLIENT_ID = :CLIENTID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $profileid, PDO::PARAM_STR);
            $res->execute();
            if ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                return array($result['SERVICE_DAY'], $result['SERVICE_SET_DT'],$result['EMAIL_STAGE']);
            }
            return false;
        } catch (Exception $e) {
            throw new jsException($e);
        }
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
     * Function to get unscreened clients' count from billing.EXCLUSIVE_SERVICING table
     *
     * @param   $agentUsername
     * @return  count
     */ 
	public function getUnScreenedClientCount($agentUsername)
	{
		if($agentUsername){
			try{
			    $sql = "SELECT COUNT(DISTINCT CLIENT_ID) AS CNT FROM billing.EXCLUSIVE_SERVICING WHERE SCREENED_STATUS = :SCREENED_STATUS AND AGENT_USERNAME = :AGENT_USERNAME";
			    $res = $this->db->prepare($sql);
			    $res->bindValue(":SCREENED_STATUS",'N',PDO::PARAM_STR);
			    $res->bindValue(":AGENT_USERNAME", $agentUsername, PDO::PARAM_STR);
			    $res->execute();
			    if($result=$res->fetch(PDO::FETCH_ASSOC))
			    {
			        return $result["CNT"];
			    }
			    return 0;
			}
			catch(Exception $e)
			{
			  throw new jsException($e);
			}
		}
		else{
			return 0;
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
				$sql = "INSERT IGNORE INTO billing.EXCLUSIVE_SERVICING (ID,AGENT_USERNAME,CLIENT_ID,ASSIGNED_DT,ENTRY_DT,BILLID) VALUES(NULL,:AGENT_USERNAME,:CLIENT_ID,:ASSIGNED_DT,:ENTRY_DT,:BILLID)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":AGENT_USERNAME", $params["AGENT_USERNAME"], PDO::PARAM_STR);
				$res->bindValue(":CLIENT_ID", $params["CLIENT_ID"], PDO::PARAM_INT);
				$res->bindValue(":ASSIGNED_DT", $params["ASSIGNED_DT"], PDO::PARAM_STR);
				$res->bindValue(":ENTRY_DT", date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$res->bindValue(":BILLID",$params["BILLID"],PDO::PARAM_INT);
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
	public function removeExclusiveClientEntry($clientId,$agentUsername="",$billid=0)
	{
		try
		{
		  if($clientId)
		  {
		    $sql = "DELETE FROM billing.EXCLUSIVE_SERVICING WHERE CLIENT_ID=:CLIENT_ID AND SCREENED_STATUS=:SCREENED_STATUS";
		    if(!empty($agentUsername))
		        $sql.= " AND AGENT_USERNAME=:AGENT_USERNAME";
		    if($billid != 0)
		        $sql.= " AND BILLID=:BILLID";
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
		    if(!empty($agentUsername))
    		    $res->bindValue(":AGENT_USERNAME", $agentUsername, PDO::PARAM_STR);
		    if($billid != 0)
                $res->bindValue(":BILLID", $billid, PDO::PARAM_INT);
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
	 /** Function to update screening status
	 *
	 * @param   $agentUsername,$clientId,$screenedStatus='Y'
	 * @return  none
	 */
	public function updateScreenedStatus($agentUsername,$clientId,$screenedStatus='Y'){
		try
		{
		  if($clientId && $agentUsername)
		  {
		    $sql = "UPDATE billing.EXCLUSIVE_SERVICING SET SCREENED_STATUS=:SCREENED_STATUS,SCREENED_DT=:SCREENED_DT WHERE CLIENT_ID=:CLIENT_ID AND AGENT_USERNAME=:AGENT_USERNAME";
		    $res = $this->db->prepare($sql);
		    $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
		    $res->bindValue(":AGENT_USERNAME", $agentUsername, PDO::PARAM_STR);
		    $res->bindValue(":SCREENED_STATUS",$screenedStatus, PDO::PARAM_STR);
		    $res->bindValue(":SCREENED_DT",date("Y-m-d"), PDO::PARAM_STR);
		    $res->execute();
		  }
		}
		catch(Exception $e)
		{
		  throw new jsException($e);
		}
	}
        
            /**
     * Function to get count of users/clients assigned to a particular agent for each service day
     *
     * @param   $agent ID
     * @return  array of rows
     */
    public function getDayWiseAssignedCount($agent) {
        try {
            $sql = "SELECT COUNT(SERVICE_DAY) AS CNT,SERVICE_DAY FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE AGENT_USERNAME =:AGENT GROUP BY SERVICE_DAY";
            $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT", $agent, PDO::PARAM_STR);
            $res->execute();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $output[$result['SERVICE_DAY']] = $result['CNT'];
            }
            return $output;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
    
     /* Function to get detail for a client id to check if it exists in the system
     *
     * @param   $agent ID
     * @return  array of rows
     */
    public function getAllDataForClient($clientid,$billid=0) {
        try {
            $sql = "SELECT * FROM billing.EXCLUSIVE_SERVICING";
            $sql = $sql . " WHERE CLIENT_ID =:CLIENTID";
            if($billid!=0)
                $sql.= " AND BILLID =:BILLID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENTID", $clientid, PDO::PARAM_STR);
            if($billid!=0)
                $res->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $res->execute();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
?>
