<?php

class billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING extends TABLE
{
	public function __construct($dbname="")
	{
    	parent::__construct($dbname);
  	}

  	/**
     * Function to insert entry into EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $params
     * @return  none
     */ 
	public function addClientMemberEntry($params)
	{
		try{
			if(is_array($params) && $params){
				$sql = "INSERT IGNORE INTO billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING (ID,CLIENT_ID,MEMBER_ID,ENTRY_DT,SCREENED_STATUS) VALUES(NULL,:CLIENT_ID,:MEMBER_ID,:ENTRY_DT,:SCREENED_STATUS)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":CLIENT_ID", $params["CLIENT_ID"], PDO::PARAM_INT);
				$res->bindValue(":MEMBER_ID", $params["MEMBER_ID"], PDO::PARAM_INT);
				$res->bindValue(":SCREENED_STATUS", $params["SCREENED_STATUS"], PDO::PARAM_STR);
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
     * Function to get pending entry from EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $none
     * @return  none
     */ 
	public function getPendingJsExclusiveEoiData()
	{
		try{
				$sql = "select * from  billing.EXCLUSIVE_CLIENT_MEMBER_MAPPING where SCREENED_STATUS='P' /*and DATE(ENTRY_DT)=CURDATE()*/";
				$res = $this->db->prepare($sql);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{	
					$output[] = $row;
				}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	/**
     * Function to update screened status entry in EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $id
     * @return  none
     */ 
	public function updateScreenedStatus($id)
	{
		try{
			if(is_array($params) && $params){
				$sql = "UPDATE  `EXCLUSIVE_CLIENT_MEMBER_MAPPING` SET  `SCREENED_STATUS` =  'Y' WHERE  `ID` =:ID ";
				$res = $this->db->prepare($sql);
				$res->bindValue(":ID", $ID, PDO::PARAM_INT);
				$res->execute();
				return true;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
			return false;
		}
		
	}

	/**
     * Function to update error reason and status entry in EXCLUSIVE_CLIENT_MEMBER_MAPPING table
     *
     * @param   $id
     * @return  none
     */ 
	public function updateSendEoiError($id,$reason)
	{
		try{
			if(is_array($params) && $params){
				$sql = "UPDATE  `EXCLUSIVE_CLIENT_MEMBER_MAPPING` SET  `SCREENED_STATUS` =  'E' AND `FAILURE_REASON`=:REASON WHERE  `ID` =:ID ";
				$res = $this->db->prepare($sql);
				$res->bindValue(":ID", $ID, PDO::PARAM_INT);
				$res->bindValue(":REASON", $ID, PDO::PARAM_STR);
				$res->execute();
				return true;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
			return false;
		}
		
	}
}
?>