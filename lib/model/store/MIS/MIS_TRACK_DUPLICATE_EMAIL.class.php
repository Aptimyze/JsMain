<?php

class MIS_TRACK_DUPLICATE_EMAIL extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	* This function updates the table MIS.TRACK_DUPLICATE_EMAIL
	* whenever a user enters a duplicate email on registration.
	**/
	public function insert($value,$page, $ip, $flag) 
	{
		$date=date("Y-m-d H:i:s");

		if($value)
		{
			$sql = "INSERT INTO MIS.TRACK_DUPLICATE_EMAIL(EMAIL,IP,TIME,PAGE,FLAG) VALUES(:EMAIL,:IP,:DATE,:PAGE,:FLAG)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":EMAIL", $value, PDO::PARAM_STR);
			$res->bindValue(":IP", $ip, PDO::PARAM_STR);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res->bindValue(":PAGE", $page, PDO::PARAM_STR);
			$res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
			$res->execute();
		}
	}
}
 
?>
