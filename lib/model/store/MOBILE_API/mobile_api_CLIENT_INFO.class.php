<?php

/**
  * Description of dbo_device_registration
  * Handles all the operations related to MOBILE_API.CLIENT_INFO table
  * @author Nitesh Sethi
  */

class MOBILE_API_CLIENT_INFO extends TABLE
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
	public function registerDevice($deviceInfo)
	{
		if(!$deviceInfo['authkey'] || !$deviceInfo['uid'])
             throw new jsException("","VALUE OR TYPE IS BLANK IN registerDevice() of MOBILE_API.CLIENT_INFO.class.php");
		try
		{

			$sql = "INSERT INTO MOBILE_API.CLIENT_INFO (AUTHKEY,UID,CURRENT_IP) VALUES(:authkey,:uid,:ip_address)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":authkey", $deviceInfo['authkey'], PDO::PARAM_STR);
			$res->bindValue(":uid", $deviceInfo['uid'], PDO::PARAM_STR);
			$res->bindValue(":ip_address", $deviceInfo['ip_address'], PDO::PARAM_STR);
			return $res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	 /**
        This function check whether there is there is any data stored for a particular authkey
        * @param  string $authkey
        * @return array table data
        **/
	function checkDeviceEntry($authkey)
	{
		if(!$authkey)
             throw new jsException("","VALUE  IS BLANK IN checkDeviceEntry() of MOBILE_API.CLIENT_INFO.class.php");
		try
		{
			$sql = "SELECT * FROM MOBILE_API.CLIENT_INFO WHERE AUTHKEY = :authkey";
			$res=$this->db->prepare($sql);
			$res->bindValue(":authkey", $authkey, PDO::PARAM_STR);
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
	
	 /**
        This function updates the device status in table
        * @param  string $uid
        * @return void
        **/
     function updateDeviceStatus($uid)
     {
		if(!$uid)
             throw new jsException("","VALUE  IS BLANK IN updateDeviceInfo() of MOBILE_API.CLIENT_INFO.class.php");
		try
		{
			$sql = "UPDATE MOBILE_API.CLIENT_INFO SET STATUS = 'E' WHERE UID = :uid";
			$res=$this->db->prepare($sql);
			$res->bindValue(":uid", $uid, PDO::PARAM_STR);
			$res->execute();
			return $res->rowCount();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		
     }
     
     /**
        This function updates the client info table
        * @param  object $apiClientInfoObj
        * @return int rowCount value
        **/
     
    public function update($apiClientInfoObj)
	{
		try 
		{
			
      		$sql = "UPDATE MOBILE_API.CLIENT_INFO SET APPID=:APPID,CLIENT=:CLIENT,EMAIL=:EMAIL,MOBILE=:MOBILE,AUTHKEY=:AUTHKEY,STATUS=:STATUS,ADD_TIME=:ADD_TIME,UID=:UID,CURRENT_IP=:CURRENT_IP,IP_COUNT=:IP_COUNT  WHERE AUTHKEY = :AUTHKEY";
      		$prep = $this->db->prepare($sql);
      		
      		$prep->bindValue(":APPID",$apiClientInfoObj->getAPPID(),PDO::PARAM_INT);
			$prep->bindValue(":CLIENT",$apiClientInfoObj->getCLIENT(),PDO::PARAM_STR);
			$prep->bindValue(":EMAIL",$apiClientInfoObj->getEMAIL(),PDO::PARAM_STR);
			$prep->bindValue(":MOBILE",$apiClientInfoObj->getMOBILE(),PDO::PARAM_STR);
			$prep->bindValue(":AUTHKEY",$apiClientInfoObj->getAUTHKEY(),PDO::PARAM_STR);
			$prep->bindValue(":STATUS",$apiClientInfoObj->getSTATUS(),PDO::PARAM_STR);
			$prep->bindValue(":ADD_TIME",$apiClientInfoObj->getADD_TIME(),PDO::PARAM_STR);
			$prep->bindValue(":UID",$apiClientInfoObj->getUID(),PDO::PARAM_STR);
			$prep->bindValue(":CURRENT_IP",$apiClientInfoObj->getCURRENT_IP(),PDO::PARAM_STR);
			$prep->bindValue(":IP_COUNT",$apiClientInfoObj->getIP_COUNT(),PDO::PARAM_INT);		
      	   
			
			if($prep->execute())
			{
				return $prep->rowCount();
			}
			else
			{
				return 0;
			}
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

}
?>
