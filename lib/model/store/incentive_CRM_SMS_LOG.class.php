<?php
class incentive_CRM_SMS_LOG extends TABLE{


	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertSmsLog($profileid,$smsKey,$entryDt)
	{
		try
		{
			$sql = "INSERT INTO incentive.CRM_SMS_LOG(PROFILEID, SMS_KEY, ENTRY_DT) VALUES (:PROFILEID, :SMS_KEY, :ENTRY_DT)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":SMS_KEY", $smsKey, PDO::PARAM_STR);
			$res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	public function getSmsSentKeysForProfileAndDate($profileid, $entryDt)
	{
		try
		{
			$sql = "SELECT ID, SMS_KEY FROM incentive.CRM_SMS_LOG WHERE PROFILEID=:PROFILEID AND ENTRY_DT=:ENTRY_DT" ;
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
			$res->execute();
			while($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$result['ID']] = $result['SMS_KEY'];
			}
			return $output;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
?>
