<?php

class LoginData
{
	private $dbObj;
	
	function __construct($pid)
	{
		$this->dbName = JsDbSharding::getShardNo($pid, "slave");
	}
	
	function logOut($profileID)
	{
		
		$this->dbObj = new LOG_LOGOUT_HISTORY($this->dbName);
		$result = $this->dbObj->logoutHistory($profileID);
		return $result;
	}
	function login($profileID)
	{
		
		$this->dbObj = new LOG_LOGIN_HISTORY($this->dbName);		
		$result = $this->dbObj->loginHistory($profileID);
		//print_r($result);die;
		return $result;
	}
	
}

?>
