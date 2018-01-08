<?php

class MIS_FEEDBACK_RESULT extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function Insert($iCategory,$iTicketID)
	{
		try{
			$szTimeStamp = date("Y-m-d H:i:s");
			$sql="insert into MIS.FEEDBACK_RESULT (`CATEGORY`,`DATE`,`TICKETID`) values(:CATEGORY,:TSTAMP,:TICKETID)";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":CATEGORY",$iCategory,PDO::PARAM_INT);
			$pdoStatement->bindValue(":TICKETID",$iTicketID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":TSTAMP",$szTimeStamp,PDO::PARAM_STR);
			$pdoStatement->execute();
				
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
}

?>
