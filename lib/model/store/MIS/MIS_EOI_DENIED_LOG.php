<?php

class MIS_EOI_DENIED_LOG extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	
	
	public function insertLog($viewerpfid,$viewedpfid,$viewerGender,$typeBreached,$typeOfUser,$countBreached) 
	{  
		
		try
		{
			if(!$viewerpfid || !$viewerGender  || !$typeBreached || !$viewedpfid || !$typeOfUser || !$countBreached)
				return;
			$timeNow=(new DateTime)->format('Y-m-j');
			$sql="INSERT IGNORE INTO MIS.EOI_DENIED_LOG(VIEWER_PFID,VIEWED_PFID,DATE,GENDER,TYPE_BREACHED,TYPE_OF_USER,COUNT_BREACHED) VALUES(:VIEWER_PFID,:VIEWED_PFID,:DATE,:GENDER,:TYPE_BREACHED,:TYPE_OF_USER,:COUNT_BREACHED)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":VIEWER_PFID",$viewerpfid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":VIEWED_PFID",$viewedpfid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			$pdoStatement->bindValue(":GENDER",$viewerGender,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TYPE_BREACHED",$typeBreached,PDO::PARAM_STR);
			$pdoStatement->bindValue(":TYPE_OF_USER",$typeOfUser,PDO::PARAM_STR);
			$pdoStatement->bindValue(":COUNT_BREACHED",$countBreached,PDO::PARAM_INT);

			$pdoStatement->execute();
			
			return ;
		}
		catch(Exception $e)
		{
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR,$e);
			return;
		}
	}

}

?>

