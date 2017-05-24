<?php
/** 
* store class related with PSWRDS table
* PSWRDS contains information for the login of a backend user
*/
class mmmjs_PSWRDS extends TABLE
{
	public function __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}

	/**
	* check if entry for the username & password exists
	* @param $user - associative array with key (column name) & value
	* @throws - PDO Exception 
	*/
	public function entryExist($user)
	{  
		try
		{	
			$name = $user['username'];
			$pass = $user['password'];

			$sql = "select RESID from mmmjs.PSWRDS where USERNAME= :name and PASSWORD= :pass";
			$res=$this->db->prepare($sql);
			$res->bindValue(":name", $name, PDO::PARAM_STR);
			$res->bindValue(":pass", $pass, PDO::PARAM_STR);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC))
				return $row['RESID'];
			else
				return NULL;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
