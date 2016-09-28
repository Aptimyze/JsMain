<?php

class PICTURE_ALBUM_VIEW_LOGGING extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}

	public function insertLogEntry($loggedInProfileid,$profileid,$date,$channel)
	{
		return true;
		try
		{
			$sql = "REPLACE INTO PICTURE.ALBUM_VIEW_LOGGING (VIEWER_ID,VIEWED_ID,DATETIME,CHANNEL) VALUES (:VIEWER_ID,:VIEWED_ID,:DATETIME,:CHANNEL)";
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":VIEWER_ID",$loggedInProfileid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":VIEWED_ID",$profileid,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATETIME",$date,PDO::PARAM_INT);
			$pdoStatement->bindValue(":CHANNEL",$channel,PDO::PARAM_STR);
			$pdoStatement->execute();
		}
		catch(Exception $e)
		{
			jsException::log("Proifle Album View Logging entry could not be inserted in PICTURE.ALBUM_VIEW_LOGGING");
		}
	}
}
