<?php

class FEEDBACK_TICKET_MESSAGES extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function fetch_QUERY($iID)
	{
		try{
			
			$sql="SELECT QUERY FROM feedback.TICKET_MESSAGES WHERE TICKETID=:TICKETID";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":TICKETID",$iID,PDO::PARAM_INT);
			$pdoStatement->execute();
			
			return $pdoStatement->fetch();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function Insert($iTicketID,$szMsg,$szIPAddress)
	{
		try
		{	
			$szTimeStamp = date("Y-m-d H:i:s");
			$sql="INSERT INTO feedback.TICKET_MESSAGES(TICKETID,ENTRY_DT,QUERY,IP) VALUES(:TICKETID,:TSTAMP,:MSG,:IP)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":MSG",$szMsg,PDO::PARAM_STR);
			$pdoStatement->bindValue(":IP",$szIPAddress ,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TSTAMP",$szTimeStamp ,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TICKETID",$iTicketID,PDO::PARAM_INT);
			
			$pdoStatement->execute();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		
	}
	
}

?>
