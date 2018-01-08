<?php 

class MIS_APP_LOGINTRACKING extends TABLE{

	public function __construct($dbName = "") {
		parent::__construct($dbName);
	}

	public function getRecord($registrationid, $profileid)
	{
		try {
			$sql = "SELECT PROFILEID FROM MIS.APP_LOGINTRACKING where PROFILEID = :PROFILEID AND REGISTRATION_ID = :REGISTRATION_ID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindValue(":REGISTRATION_ID", $registrationid, PDO::PARAM_STR);
			$prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC)){
				$result[] = $row["PROFILEID"];
			}
			if(is_array($result) && $result)
			{
				return true;
			}
			return false;
		} catch (Exception $ex) {
			throw new jsException($ex);
		}
	}

	public function replaceRecord($profileid, $registrationid, $appType)
	{
		try {
			$sql = "REPLACE INTO MIS.APP_LOGINTRACKING (PROFILEID,REGISTRATION_ID, APP_TYPE) VALUES (:PROFILEID,:REGISTRATION_ID,:APP_TYPE)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":REGISTRATION_ID",$registrationid,PDO::PARAM_STR);
			$prep->bindValue(":APP_TYPE",$appType,PDO::PARAM_STR);
			$prep->execute();

		} catch (Exception $e) {
			throw new jsException($e);	
		}
	}
}

?>