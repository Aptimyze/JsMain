<?php

class newjs_CONTACT_ERROR extends TABLE {
	
	public function __construct($dbName="")
	{
		parent::__construct($dbName);
	}
	public function getErrorValues($contactHandlerObj)
	{
		try
		{
			$sql = "SELECT ERROR FROM CONTACT_ENGINE.CONTACT_ERROR WHERE SENDER_RECEIVER = :SENDER_RECEIVER AND CONTACT_TYPE = :CONTACT_TYPE AND ENGINE_TYPE = :ENGINE_TYPE";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SENDER_RECEIVER",$contactHandlerObj->getContactInitiator(),PDO::PARAM_STR);
			$prep->bindValue(":CONTACT_TYPE",$contactHandlerObj->getContactObj()->getTYPE(),PDO::PARAM_STR);
			$prep->bindValue(":ENGINE_TYPE",$contactHandlerObj->getEngineType(),PDO::PARAM_STR);
			$prep->execute(); 		
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row['ERROR'];
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
