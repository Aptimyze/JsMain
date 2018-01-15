<?php

class matchalert_tracking_track_edit_dpp extends TABLE
{
	public function __construct($dbname = "")
	{
		parent::__construct($dbname);
	}
	
	//Insert Record
	public function InsertRecord($iProfileID,$cStatus,$szLogic)
	{
		if(!is_numeric(intval($iProfileID)))
		{
			throw new jsException("","iProfileID is not numeric in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
		}
		
		if(strlen($szLogic)==0)
		{
			throw new jsException("","szLogic is blacnk or null in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
		}
		
		$arrAllowedStatus = array('E','V','WV','WE');	
		if(!in_array($cStatus,$arrAllowedStatus))
			throw new jsException("","cStatus is not valid status in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
			
		try{
			$szToday = date('Y-m-d');
			$sql = "INSERT IGNORE INTO MATCHALERT_TRACKING.TRACK_EDIT_DPP (PROFILEID,DATE,STATUS,LOGIC) VALUES (:PID,:DATE,:STATUS,:LOGIC)";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE", $szToday,PDO::PARAM_STR);
			$pdoStatement->bindValue(":STATUS", $cStatus,PDO::PARAM_STR);
			$pdoStatement->bindValue(":LOGIC", $szLogic,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return $pdoStatement->rowCount();
		}
		catch (PDOException $e) {
				throw new jsException($e);
		}
	}
	
	//Update Status
	public function UpdateRecord($iProfileID,$cStatus,$szLogic)
	{
		if(!is_numeric(intval($iProfileID)))
		{
			throw new jsException("","iProfileID is not numeric in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
		}
		$arrAllowedStatus = array('E','V','WV','WE');	
		if(!in_array($cStatus,$arrAllowedStatus))
			throw new jsException("","cStatus is not valid status in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
		
		if(strlen($szLogic)==0)
		{
			throw new jsException("","szLogic is blacnk or null in InsertRecord OF matchalert_tracking_track_edit_dpp.class.php");
		}
			
		try{
			$szToday = date('Y-m-d');
			$sql = "UPDATE MATCHALERT_TRACKING.TRACK_EDIT_DPP SET STATUS=:STATUS WHERE PROFILEID=:PID AND DATE=:DATE AND LOGIC=:LOGIC";
			
			$pdoStatement = $this->db->prepare($sql);
			$pdoStatement->bindValue(":PID", $iProfileID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":DATE", $szToday,PDO::PARAM_STR);
			$pdoStatement->bindValue(":STATUS", $cStatus,PDO::PARAM_STR);
			$pdoStatement->bindValue(":LOGIC", $szLogic,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return $pdoStatement->rowCount();
		}
		catch (PDOException $e) {
				throw new jsException($e);
		}
	}
}
?>
