<?php
class jsadmin_COUNTRY_IP_ADDRESS extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function getUserCountry($ipAddress)
	{
		try
		{
			/*
			$sql="select COUNTRY from jsadmin.COUNTRY_IP_ADDRESS WHERE IP1='$ipAddress' OR IP2='$ipAddress'";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":USER",$user,PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$country=$result['COUNTRY'];
			*/
			return 'INDIA';

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $country;
	}
}
?>
