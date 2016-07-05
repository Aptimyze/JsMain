<?php

class REPORT_ABUSE_LOG extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	
	
	public function insertReport($reporter,$reportee,$reason)
	{
		try
		{
			if(!$reporter || !$reportee || !$reason)
				return;
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="INSERT INTO feedback.REPORT_ABUSE_LOG(REPORTER,REPORTEE,REASON,DATE) VALUES(:REPORTER,:REPORTEE,:REASON,:DATE)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":REPORTER",$reporter,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REPORTEE",$reportee,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REASON",$reason,PDO::PARAM_STR);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			
			$pdoStatement->execute();
			
			return ;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	

}

?>
