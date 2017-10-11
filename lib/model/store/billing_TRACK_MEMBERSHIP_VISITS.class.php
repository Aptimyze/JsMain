<?php
class billing_TRACK_MEMBERSHIP_VISITS extends TABLE{


	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertDetails($profileid, $count)
	{
		try
		{
			$sql = "INSERT IGNORE INTO billing.TRACK_MEMBERSHIP_VISITS(PROFILEID,COUNT) VALUES (:PROFILEID,:COUNT)" ;
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":COUNT", $count, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function updateCount($profileid)
	{
		try
		{
			$sql = "UPDATE billing.TRACK_MEMBERSHIP_VISITS SET COUNT=COUNT+1 WHERE PROFILEID=:PROFILEID" ;
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function getCount($profileid)
	{
		try
		{
			$sql="SELECT COUNT FROM billing.TRACK_MEMBERSHIP_VISITS WHERE 	PROFILEID=:PROFILEID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$count = $result['COUNT'];
			}
			return $count;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
