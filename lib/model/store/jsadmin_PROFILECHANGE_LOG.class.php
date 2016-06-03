<?php
class PROFILECHANGE_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }


	/*This function insert all the changes and maitain the jsadmin user log 
	@param 1) crmuser 2) Profileid 3) comments 4) COMPANY
	@return 
	*/
	public function insertChangesDone($crmuser,$profileId,$comments,$COMPANY)
	{
		if($crmuser && $profileId )
		{
			try
			{
				$sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('',:crmuser,NOW(),:profileId,:comments,'F',:COMPANY)";
				
				$res = $this->db->prepare($sql);
				$res->bindValue(":crmuser", $crmuser, PDO::PARAM_STR);
				$res->bindValue(":profileId", $profileId, PDO::PARAM_INT);
				$res->bindValue(":comments", $comments, PDO::PARAM_STR);
				$res->bindValue(":COMPANY", $COMPANY, PDO::PARAM_STR);
				$res->execute();
				
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
		else
		{
			throw new jsException("","PROFILEID OR crmuser  IS BLANK IN jsadmin.PROFILECHANGE_LOG store class function insertChangesDone ");
		}
		
	}

}
?>
