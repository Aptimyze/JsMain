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
}
?>