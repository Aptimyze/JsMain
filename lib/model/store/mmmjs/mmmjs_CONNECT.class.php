<?php
/** 
 * Class for the connect table
 */
class mmmjs_CONNECT extends TABLE
{
        public function __construct($dbname="matchalerts_slave_localhost")
        {
                parent::__construct($dbname);
        }

	/**
	* gets the 'last access time'
	* @param $id unique identifier for every user stored in cookie.
	* @return time or NULL (if field is empty)
	* @throws - PDO Exception 
	*/
	public function getTime($id)
	{ 
		try
		{	
			$sql = "select TIME from mmmjs.CONNECT where ID = :id";
			$res = $this->db->prepare($sql);
			$res->bindValue(":id", $id, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC))
				return $row['TIME'];
			else
			return NULL;
		}
		catch(PDOException $e)
		{		
			throw new jsException($e);
		}
	}


	/**
	* insert entries in the connect table 
	* @param $user - user id
	* @return id of the entry inserted
	* @throws - PDO Exception 
	*/
	public function insertEntry($user)
	{
		try
		{
			$time = time();
			$sql = "insert into mmmjs.CONNECT values (:id, :user, :ipaddr, :time)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":user", $user, PDO::PARAM_STR);
			$res->bindValue(":id", '', PDO::PARAM_STR); 
			$res->bindValue(":ipaddr", '', PDO::PARAM_STR); 
			$res->bindValue(":time", $time, PDO::PARAM_STR); 
			$res->execute();
			return $this->db->lastInsertId();
		}
		catch(PDOException $e)
		{		
			throw new jsException($e);
		}
	}

	/**
	* check if entry exists
	* @param $id 
	* @return True or False
	* @throws - PDO Exception 
	*/
	public function checkEntry($id)
	{
		try
		{
			$sql = "select count(*) as cnt from mmmjs.CONNECT where ID = :id"; //where time
			$res=$this->db->prepare($sql);
			$res->bindValue(":id", $id, PDO::PARAM_STR);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			if($row['cnt'] > 0)
				return True;
			else
			return False;
		}
		catch(PDOException $e)
		{	
			throw new jsException($e);
		}
	}
}    
?>    
