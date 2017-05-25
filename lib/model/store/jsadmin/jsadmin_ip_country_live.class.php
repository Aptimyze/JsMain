<?php
class jsadmin_ip_country_live extends TABLE
{
	public function __construct($dbname="")
	{
        	parent::__construct($dbname);
	}
	public function getUserCountry($ipAddress)
	{
		try
		{
			$ipAddress =trim($ipAddress);
			$sql ="SELECT country_code FROM jsadmin.ip_country_live WHERE INET_ATON(:IPADDRESS)>=ip_from AND INET_ATON(:IPADDRESS)<= ip_to";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":IPADDRESS",$ipAddress,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$country=trim($result['country_code']);
			if(!$country)
				$country='IN';
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $country;
	}
}
?>
