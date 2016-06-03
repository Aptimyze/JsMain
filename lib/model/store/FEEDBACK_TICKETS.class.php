<?php

class FEEDBACK_TICKETS extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function fetch_IDs(&$arrRef_Result,$szUserName,$szEmail,$iCagtegory)
	{
		try{
			$today=date("Y-m-d");
			
			$sql="SELECT ID FROM feedback.TICKETS WHERE (USERNAME=:UNAME OR EMAIL=:EMAIL) AND CATEGORY=:CAT AND ENTRY_DT LIKE '$today%'";				
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":UNAME",$szUserName,PDO::PARAM_STR);
			$pdoStatement->bindValue(":EMAIL",$szEmail,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CAT",$iCagtegory,PDO::PARAM_INT);
			$pdoStatement->execute();
			
			$arrRef_Result = $pdoStatement->fetchAll();
			
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	
	public function Insert($arrPara)
	{
		try
		{
			$szStatus = "OPEN";
			$szTimeStamp = date("Y-m-d H:i:s");
			$arrColumn = array('name'=>'NAME','uname'=>'USERNAME','email'=>'EMAIL','tracepath'=>'CATEGORY');
			foreach($arrPara as $key=>$val)
			{
				if($val!=null)
				{
					$arrInsertColumn[] = $arrColumn[$key];
					$arrToken[$key] = ":".strtoupper($key);
				}
			}
			$szInsertColumn = implode(",",$arrInsertColumn);
			$szToken = implode(",",array_values($arrToken));
			
			$sql="INSERT INTO feedback.TICKETS($szInsertColumn,STATUS,ENTRY_DT,FIRST_ENTRY_DT,COUNTER) VALUES($szToken,:STATUS,:TSTAMP,:TSTAMP,'1')";
			
			$pdoStatement = $this->db->prepare($sql);
			
			foreach($arrToken as $key=>$val)
			{
				if($key!='tracepath')
					$pdoStatement->bindValue($val,$arrPara[$key],PDO::PARAM_STR);
				else
					$pdoStatement->bindValue($val,$arrPara[$key],PDO::PARAM_INT);
			}
			$pdoStatement->bindValue(":TSTAMP",$szTimeStamp,PDO::PARAM_STR);
			$pdoStatement->bindValue(":STATUS",$szStatus,PDO::PARAM_STR);
			
			$pdoStatement->execute();
			
			return $this->db->lastInsertId();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	

}

?>
