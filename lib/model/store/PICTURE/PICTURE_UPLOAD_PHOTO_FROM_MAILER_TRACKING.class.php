<?php

class PICTURE_UPLOAD_PHOTO_FROM_MAILER_TRACKING extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	//this function is used for tracking of users who click upload now that was sent in the mail
	public function insertTrackingRecord($profileId,$type,$date)
	{
		try
		{
			$sql = "INSERT into PICTURE_UPLOAD_PHOTO_FROM_MAILER_TRACKING (PROFILEID,DATE,TYPE) VALUES (:PROFILEID,:DATE,:TYPE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$prep->bindValue(":TYPE", $type, PDO::PARAM_INT);
			$prep->bindValue(":DATE", $date, PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $ex)
		{
			jsException::nonCriticalError($ex);
		}
	}
}