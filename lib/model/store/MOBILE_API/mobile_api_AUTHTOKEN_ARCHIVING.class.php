<?php

/**
  * Description of MOBILE_API_AUTHTOKEN_ARCHIVING
  * Handles all the operations related to MOBILE_API.AUTHTOKEN_ARCHIVING table
  * @author Nitesh Sethi
  */

class MOBILE_API_AUTHTOKEN_ARCHIVING extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	 /**
        This function insert the AuthToken in table
        * @param  array $authToken
        * @return void
        **/
	public function insertAuthToken($authToken)
	{
		if(!$authToken)
             throw new jsException("","VALUE OR TYPE IS BLANK IN insertAuthToken() of MOBILE_API_AUTHTOKEN_ARCHIVING.class.php");
		try
		{
			$date = date("Y-m-d H-m-s");
			$sql = "INSERT INTO MOBILE_API.AUTHTOKEN_ARCHIVING (AUTHTOKEN,ENTRY_DT) VALUES(:AUTHTOKEN,:ENTRY_DT)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":AUTHTOKEN", $authToken, PDO::PARAM_STR);
			$res->bindValue(":ENTRY_DT", $date, PDO::PARAM_STR);
			
			return $res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	 /**
        This function check whether there is there is any data stored for a particular authtoken
        * @param  string $authToken
        * @return bool 
        **/
	function checkAuthTokenEntry($authToken)
	{
		if(!$authToken)
             throw new jsException("","VALUE OR TYPE IS BLANK IN checkAuthTokenEntry() of MOBILE_API_AUTHTOKEN_ARCHIVING.class.php");
		try
		{
			$sql = "SELECT * FROM MOBILE_API.AUTHTOKEN_ARCHIVING WHERE AUTHTOKEN = :AUTHTOKEN";
			$res=$this->db->prepare($sql);
			$res->bindValue(":AUTHTOKEN", $authToken, PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[]=$row;
			}
			return $result[0];
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
}
?>
