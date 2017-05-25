<?php

class PICTURE_PNG_PHOTO_TRACKING extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	public function insertPngTracking($date,$pictureId)
	{
		try
		{
			$sql="INSERT IGNORE INTO PICTURE.PNG_PHOTO_TRACKING(DATE,PICTUREID) VALUES (:DATE,:PICTUREID)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DATE", $date, PDO::PARAM_STR);
			$prep->bindValue(":PICTUREID", $pictureId, PDO::PARAM_INT);
			$prep->execute();
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}
}