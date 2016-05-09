<?php

class MIS_VCD_YES_NO_TRACKING extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
	* This function updates the table MIS.VCD_YES_NO_TRACKING
	* whenever a free user try to view user's contact details and does not have privilage. 
	**/
	public function insertTracking($viewer,$viewed,$channel,$type,$viewed_sub,$viewer_sub,$contactShown) 
	{

		if(!$viewer || !$viewed)
			throw new jsException("","PROFILEID IS BLANK IN MIS_VCD_TRACKING.class.php");
		try
		{
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "INSERT IGNORE INTO MIS.VCD_YES_NO_TRACKING(VIEWER,VIEWED,VIEWED_SUBSCRIPTION,VIEWER_SUBSCRIPTION,`TIME`,CHANNEL,CONTACT_TYPE,CONTACT_SHOWN) VALUES(:VIEWER,:VIEWED,:VIEWED_SUBSCRIPTION,:VIEWER_SUBSCRIPTION,:DATE1,:CHANNEL,:TYPE,:CONTACT_SHOWN)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":VIEWED", $viewed, PDO::PARAM_INT);
			$res->bindValue(":VIEWER", $viewer, PDO::PARAM_INT);
			$res->bindValue(":DATE1", $timeNow, PDO::PARAM_STR);
			$res->bindValue(":CHANNEL", $channel, PDO::PARAM_STR);
			$res->bindValue(":TYPE",$type,PDO::PARAM_STR);
			$res->bindValue(":VIEWED_SUBSCRIPTION",$viewed_sub,PDO::PARAM_STR);
			$res->bindValue(":VIEWER_SUBSCRIPTION",$viewer_sub,PDO::PARAM_STR);
			$res->bindValue(":CONTACT_SHOWN",$contactShown,PDO::PARAM_STR);
			$res->execute();
			return $this->db->lastInsertId(); 
		}
		catch (PDOException $e)
		{
			return null;
			//throw new jsException($e);
		}
	}
}
 
?>
