<?php

class PICTURE_PROCESS_INTERFACE_UPLOAD_TRACKING extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	public function insert($time,$size)
	{
		try
		{
			
			$sql="INSERT INTO PICTURE.PROCESS_INTERFACE_UPLOAD_TRACKING (LOAD_TIME_MS,SIZE) VALUES(:TIME,:SIZE)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":TIME",$time,PDO::PARAM_INT);
			$pdoStatement->bindValue(":SIZE",$size,PDO::PARAM_INT);
			
			$pdoStatement->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
}
?>
