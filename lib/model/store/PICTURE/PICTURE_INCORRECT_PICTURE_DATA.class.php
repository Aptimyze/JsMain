<?php

class PICTURE_INCORRECT_PICTURE_DATA extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	public function insertIncorrectPicDetail($profileId,$pictureId,$ordering,$reason)
	{
		try
		{
			$sql = "INSERT IGNORE into PICTURE.INCORRECT_PICTURE_DATA VALUES (:PICTUREID,:PROFILEID,:ORDERING,:REASON)";
			$prep=$this->db->prepare($sql);
			$prep->bindParam(":PICTUREID", $pictureId, PDO::PARAM_INT);
			$prep->bindParam(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->bindParam(":ORDERING", $ordering, PDO::PARAM_INT);
			$prep->bindParam(":REASON", $reason, PDO::PARAM_STR);
			$prep->execute();
    	}
    	catch(PDOException $e)
		{
			/** echo the sql statement and error message **/
			 throw new jsException($e);
		}
	}
}
?>