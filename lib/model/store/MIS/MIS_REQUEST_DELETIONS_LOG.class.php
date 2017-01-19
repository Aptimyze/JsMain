<?php

class MIS_REQUEST_DELETIONS_LOG extends TABLE{



    public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }



        public function logThis	($crmUser,$userPFID,$requestedBy) 
	{  
		
		try
		{
			if(!$crmUser || !$userPFID || !$requestedBy)
				return;
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="INSERT INTO MIS.REQUEST_DELETIONS_LOG(DATE,CRM_USER,REPORTEE,REQUESTED_BY) VALUES(:DATE,:CRM_USER,:REPORTEE,:REQUESTED_BY)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":REPORTEE",$userPFID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REQUESTED_BY",$requestedBy,PDO::PARAM_STR);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CRM_USER",$crmUser,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return ;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	   public function getAllUsersRequestedDeletion() 
	{  
		
		try
		{

		 $date = new DateTime();
		 $date->sub(new DateInterval('P2D')); //get the date which was 2 days ago
         $lastDateToCheck = $date->format('Y-m-d H:i:s');
			$sql = "SELECT REPORTEE FROM MIS.REQUEST_DELETIONS_LOG where `DATE` >= ".$lastDateToCheck;
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->execute();
			while($row=$pdoStatement->fetch(PDO::FETCH_ASSOC))
            $result[]=$row;
        return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

}
