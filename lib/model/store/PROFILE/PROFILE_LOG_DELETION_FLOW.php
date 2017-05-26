<?php
class PROFILE_LOG_DELETION_FLOW extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertEntry($pfid,$table)
	{
		try
		{
			$sql="INSERT INTO PROFILE.LOG_DELETION_FLOW(PROFILEID,`TIME`,TABLE_NAME) VALUES(:PFID,:TIME_NOW,:TABLE_NAME)";	

			$prep = $this->db->prepare($sql);
			$now = date("Y-m-d G:i:s");

			$prep->bindValue(":PFID", $pfid, PDO::PARAM_INT);	
			$prep->bindValue(":TIME_NOW", $now, PDO::PARAM_STR);
			$prep->bindValue(":TABLE_NAME", $table, PDO::PARAM_STR);	
			
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $executives;

	}
}
?>
