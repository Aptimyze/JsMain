<?php
//This class is used to execute queries on newjs.LOGIN_FAILED1 table

class LOGIN_FAILED1 extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	
	/**
        This function inserts all failed login belonging to login in jeevansathi
        * @param  username ,$password
        **/
	public function insertFailedLogin($email,$password,$USER_AGENT,$ip)
	{
		if(!$email)
			throw new jsException("","username IS BLANK IN insertFailedLogin() of LOGIN_FAILED1.class.php");

		try
		{
			$sql = "INSERT INTO LOGIN_FAILED1(USERNAME,PASSWORD,DATE,USER_AGENT,IP) VALUES (:USERNAME,:PASSWORD,:date,:USER_AGENT,:IP)";
			$res = $this->db->prepare($sql);
			$res->bindParam(":USERNAME", $email, PDO::PARAM_STR);
			$res->bindParam(":PASSWORD", $password, PDO::PARAM_STR);
			$res->bindParam(":USER_AGENT", $USER_AGENT, PDO::PARAM_STR);
			$res->bindParam(":date",date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$res->bindParam(":IP", $ip, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

}
?>
