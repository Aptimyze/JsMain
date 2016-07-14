<?php

class newjs_CONTACTS_GET_ID extends TABLE {
	
	public function __construct($dbName="")
	{
		parent::__construct($dbName);
	}
	public function generateId()
	{
		try
		{
			$sql = "INSERT INTO newjs.CONTACTS_GET_ID(ID) VALUES('')";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			return $this->db->lastInsertId();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}			
	}
	public function delete()
	{
		try
		{	
			$sql= "DELETE FROM newjs.CONTACTS_GET_ID WHERE 1";
			$prep = $this->db->prepare($sql);
			$prep->execute();			
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
}
