<?php

class MIS_MINI_REG_CUSTOMIZE extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	* This function updates the table MIS.TRACK_DUPLICATE_EMAIL
	* whenever a user enters a duplicate email on registration.
	**/
	public function showMiniRegStory($source) 
	{
		if($source)
		{
			try
			{
				$sql = "SELECT HEADING,COUP_IMAGE,CATEGORY,STORY FROM MIS.MINI_REG_CUSTOMIZE WHERE SOURCE=:SOURCE";
				$res = $this->db->prepare($sql);
				$res->bindValue(":SOURCE", $source, PDO::PARAM_STR);
				$res->execute();
				$result = $res->fetch(PDO::FETCH_ASSOC);
				return $result;
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}
	}

	public function sourceMiniReg()
	{
		$sql="SELECT SQL_CACHE SOURCE FROM MIS.MINI_REG_CUSTOMIZE";
		$res = $this->db->prepare($sql);
		$res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		$arrayValues[]=$row['SOURCE'];
		return $arrayValues;
	}
}
 
?>
