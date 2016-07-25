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
	
	/**
		This function is used to get autoincrement id from the table.
		Column NO_USE_VARIABLE is used for maintaining unique r/l.So that everytime a replace commnad is run existing row gets repalced and we can get a new increment id and not even increasing rows of table. 
		@return ID id auto increment id which will be used as pictureId for CONTACT
        */
	public function getAutoIncrementMessageId()
	{
                $sql="REPLACE INTO newjs.CONTACTS_GET_ID(ID,NO_USE_VARIABLE) VALUES('','X')";
                $res=$this->db->prepare($sql);
				$res->execute();
				return $this->db->lastInsertId();
    }
	
}
