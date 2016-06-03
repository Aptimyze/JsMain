<?php
/*Fuctions added by Sandipan*/
//include_once("../classes/Mysql");

function getCountrySource()
{

	if(trackIP() == 'IN')
	{
		return 'IND';
	}
	else
	return 'FOR';
}
/*
*	Name 		: ConvertToIPNumber
*	Description : This function is used to convert IP Address into IP Number.
* 	Inputs		: $IPaddress - IP Address in IP V4 formet ex. XXX.XXX.XXX.XXX
*	Returns		: integer - IP Number for given IP Address
* 	Created By	:	Abhinav Gupta
* 	Created On	:	 29th July,2008*/

function ConvertToIPNumber($IPaddress)
{
	if ($IPaddress == "") {
		return 0;
	} else {
		$ips = explode('.', $IPaddress);
		return ($ips[3] + $ips[2] * 256 + $ips[1] * 65536 + $ips[0] * 16777216);
	}
}

/*
*	Name 		: trackIP
*	Description : This function is used to Track IP Address of User coming to Site.
* 	Inputs		: $IPaddress - IP Address in IP V4 formet ex. XXX.XXX.XXX.XXX
*	Returns		: String - Returns the Country Code to which the given IP belongs.
* 	Created By	:	Abhinav Gupta
* 	Created On	:	 29th July,2008*/

function trackIP($IP_address="")
{
	if ($IP_address == "") {
		$IP_address = FetchClientIP();
	}
	$IP_number = ConvertToIPNumber($IP_address);

	$mysqlObj = new Mysql;
	$mysqlObj->connect();
	$sql = "SELECT * FROM bms2.COUNTRY_IP WHERE BEGIN_IP_NUM<=$IP_number AND END_IP_NUM >= $IP_number";

	$res = $mysqlObj->query($sql);
	if($row = $mysqlObj->fetchAssoc($res))
	{
		return $row['COUNTRY_CODE'];
	}
	return '0';
}
?>
