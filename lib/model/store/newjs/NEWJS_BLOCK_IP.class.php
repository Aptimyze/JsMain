<?php
class NEWJS_BLOCK_IP extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        This function check whether the there are more than 5 login attempts from the same IP address within one minute.
        * @return boolean
        **/
	public function blockIP($ip)
	{
		try
		{
			$ts = time();
			$current_time = date("Y-m-d G:i:s",$ts);
			$ts -= 60;
			$before_one_minute = date("Y-m-d G:i:s",$ts);
			$sql = $sql_ip = "SELECT Count(IP) FROM newjs.BLOCK_IP WHERE IP = :ip AND TIME BETWEEN :before_one_minute AND :current_time";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ip", $ip, PDO::PARAM_STR);
			$prep->bindParam(":before_one_minute", $before_one_minute, PDO::PARAM_STR);
			$prep->bindParam(":current_time", $current_time, PDO::PARAM_STR);
			$prep->execute();
			$res=$prep->fetch(PDO::FETCH_ASSOC);
			if($res['Count(IP)']>5)
			return true;
			else
			return false;
			
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	
	/**
        This function insert the Ip address of the user.
        * @return boolean
        **/
	public function insertIP($ip)
	{
		try
		{
			$now = date("Y-m-d G:i:s");
			$sql = $sql_ip = "INSERT INTO newjs.BLOCK_IP(IP,TIME) VALUES(:ip,:now)";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ip", $ip, PDO::PARAM_STR);
			$prep->bindParam(":now", $now, PDO::PARAM_STR);
			$prep->execute();
			
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
