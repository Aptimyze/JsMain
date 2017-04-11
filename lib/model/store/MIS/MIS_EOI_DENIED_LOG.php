<?php

class MIS_EOI_DENIED_LOG extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	
	
	public function insertLog($viewerpfid,$viewedpfid,$viewerGender,$typeBreached,$typeOfUser) 
	{  
		
		try
		{
			if(!$reporter || !$gender  || !$reason)
				return;
			$timeNow=(new DateTime)->format('Y-m-j');
			$sql="INSERT IGNORE INTO MIS.EOI_DENIED_LOG(VIEWER_PFID,VIEWED_PFID,DATE,GENDER,TYPE_BREACHED,TYPE_OF_USER) VALUES(:VIEWER_PFID,:VIEWED_PFID,:DATE,:GENDER,:TYPE_BREACHED,:TYPE_OF_USER)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":VIEWER_PFID",$viewerpfid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":VIEWED_PFID",$viewedpfid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			$pdoStatement->bindValue(":GENDER",$viewerGender,PDO::PARAM_INT);
			$pdoStatement->bindValue(":TYPE_BREACHED",$typeBreached,PDO::PARAM_INT);
			$pdoStatement->bindValue(":TYPE_OF_USER",$typeOfUser,PDO::PARAM_INT);

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

