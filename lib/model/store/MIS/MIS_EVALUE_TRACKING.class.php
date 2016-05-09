<?php

class MIS_EVALUE_TRACKING extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	* This function updates the table MIS.MIS_EVALUE_TRACKING
	* whenever a view evalue user's contact details
	**/
	public function insertTracking($viewed,$viewer,$flag,$page,$type,$sub,$device) 
	{
		$date=date("Y-m-d H:i:s");

		if($viewed)
		{
			$sql = "INSERT INTO MIS.EVALUE_TRACKING(VIEWED,VIEWER,PAGE,TYPE,TIME,FLAG,SUBSCRIPTION,DEVICE) VALUES(:VIEWED,:VIEWER,:PAGE,:TYPE,:DATE,:FLAG,:SUBSCRIPTION,:DEVICE)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->bindValue(":VIEWER", $viewer, PDO::PARAM_INT);
			$res->bindValue(":DATE", $date, PDO::PARAM_STR);
			$res->bindValue(":PAGE", $page, PDO::PARAM_STR);
			$res->bindValue(":FLAG", $flag, PDO::PARAM_STR);
			$res->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$res->bindValue(":DEVICE",$device,PDO::PARAM_STR);
			$res->bindValue(":SUBSCRIPTION",$sub,PDO::PARAM_STR);
			$res->execute();
			return $this->db->lastInsertId(); 
		}
	}
	
	public function updateId($id)
	{
		$sql = "UPDATE MIS.EVALUE_TRACKING SET FLAG='Y' WHERE ID = :ID";
		$res = $this->db->prepare($sql);
		$res->bindValue(":ID",$id,PDO::PARAM_INT);
		$res->execute();
	}
}
 
?>
