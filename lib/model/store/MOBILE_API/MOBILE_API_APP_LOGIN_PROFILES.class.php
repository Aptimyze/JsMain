<?php

/**
  * Description of dbo app login profiles from app
  * Handles all the operations related to MOBILE_API.APP_LOGIN_PROFILES table
  * @author Nitesh Sethi
  */

class MOBILE_API_APP_LOGIN_PROFILES extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	 /**
        This function insert the device data in table
        * @param  array $deviceInfo
        * @return void
        **/
	public function getAppLoginProfile($pid)
	{
		if(!$pid)
             throw new jsException("","VALUE OR TYPE IS BLANK IN getAppLoginProfile() of MOBILE_API_APP_LOGIN_PROFILES.class.php");
		try
		{

			$sql = "select PROFILEID from MOBILE_API.APP_LOGIN_PROFILES where PROFILEID =:PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				return true;
			}
			return false;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	public function insertAppLoginProfile($pid,$appType,$date)
	{
		if(!$pid)
             throw new jsException("","VALUE OR TYPE IS BLANK IN getAppLoginProfile() of MOBILE_API_APP_LOGIN_PROFILES.class.php");
		try
		{

			$sql = "REPLACE INTO MOBILE_API.APP_LOGIN_PROFILES (PROFILEID,DATE,APP_TYPE) VALUES(:PROFILEID,:DATE,:APP_TYPE)";
			
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res->bindValue(":APP_TYPE", $appType, PDO::PARAM_STR);
			$res->execute();
			
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function ifUserIsEligible($pid)
	{
		try
		{
			$sql = "select * from MOBILE_API.APP_LOGIN_PROFILES where PROFILEID =:PROFILEID";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			$res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getLastLoginProfilesForDate($pid,$date,$appType)
	{
		try
		{
			if($pid && $date && $appType)
			{
				$sql = "select PROFILEID from MOBILE_API.APP_LOGIN_PROFILES where PROFILEID =:PROFILEID AND APP_TYPE = :APP_TYPE AND DATE>=:DATE";
				$res=$this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
				$res->bindValue(":APP_TYPE", $appType, PDO::PARAM_STR);
				$res->bindValue(":DATE", $date, PDO::PARAM_STR);
				$res->execute();
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					return true;
				}
				return false;
			}
			
		}
		catch(PDOException $e)
		{
			jsException::nonCriticalError($e." Mobile_api_app_login_profiles");
		}
	}

}
?>
