<?php

class MIS_LOGIN_BY_USERNAME extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

        /**
        * Tracking of username
        **/
	public function insertRecord($profileid,$ip="") 
	{
			try{
			$date=date("Y-m-d H:i:s");
			$sql1="INSERT INTO MIS.LOGIN_BY_USERNAME(PID,DATE,IP) VALUES (:PROFILEID,:DATE,:IP)";
			$res1=$this->db->prepare($sql1);
			$res1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res1->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res1->bindValue(":IP", $ip, PDO::PARAM_STR);
			$res1->execute();
			}
			catch(PDOException $e){
                        throw new jsException($e);
			}
                
	}
}
 
?>
