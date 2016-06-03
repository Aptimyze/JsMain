<?php
class incentive_TEMP_CSV_FTA_TECH extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertProfile($profileid,$score)
	{
		try
		{
			$sql= "INSERT IGNORE INTO incentive.TEMP_CSV_FTA_TECH(PROFILEID,PRIORITY) VALUES(:PROFILEID,:SCORE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SCORE",$score,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
	public function truncate()
	{
		try
		{
			$sql="TRUNCATE TABLE incentive.TEMP_CSV_FTA_TECH";
			$prep=$this->db->prepare($sql);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
}
?>
