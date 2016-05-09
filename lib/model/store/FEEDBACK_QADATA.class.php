<?php

class FEEDBACK_QADATA extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function fetch_FAQ(&$arrRef_Result,$iTracePath)
	{
		try{
			
			$sql="SELECT ID,QUESTION,ANSWER FROM feedback.QADATA WHERE PARENT = :TRACEPATH AND PUBLISH='Y'";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":TRACEPATH",$iTracePath,PDO::PARAM_INT);
			$pdoStatement->execute();
			
			$arrRef_Result = $pdoStatement->fetchAll();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function fetchFAQLabel(&$arrRef_Result)
	{
		try{
			$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE PARENT=0 AND PUBLISH='Y'";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->execute();
			
			$arrRef_Result = $pdoStatement->fetchAll();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function fetchQuestion($iTraceID)
	{
		try{
			$sql="SELECT QUESTION FROM feedback.QADATA WHERE ID = :TRACEID";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":TRACEID",$iTraceID,PDO::PARAM_INT);
			$pdoStatement->execute();
			
			$arrResult = $pdoStatement->fetchAll();
			return $arrResult;
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	
}

?>
