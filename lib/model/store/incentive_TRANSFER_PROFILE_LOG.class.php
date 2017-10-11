<?php
class incentive_TRANSFER_PROFILE_LOG extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function updateTransferLog($profileid, $updateAgent, $agentForm, $agentTo, $type, $date){
		
		try
		{
			$sql ="INSERT INTO incentive.TRANSFER_PROFILE_LOG(PROFILEID,ALLOT_DT,TRANSFER_SUB_METHOD,TRANSFER_DT,TRANSFER_BY,TRANSFER_FROM,TRANSFER_TO) VALUES (:PROFILEID,:ALLOT_DT,:TRANSFER_SUB_METHOD,CURRENT_TIMESTAMP(),:TRANSFER_BY,:TRANSFER_FROM,:TRANSFER_TO)";
			$res = $this->db->prepare($sql);

			$res->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			if(isset($date)){
				$res->bindParam(":ALLOT_DT", $date, PDO::PARAM_STR);
			}
			$res->bindParam(":TRANSFER_SUB_METHOD", $type, PDO::PARAM_STR);
			$res->bindParam(":TRANSFER_BY", $updateAgent, PDO::PARAM_STR);
			$res->bindParam(":TRANSFER_FROM", $agentForm, PDO::PARAM_STR);
			$res->bindParam(":TRANSFER_TO", $agentTo, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
		
		return true;
	}
}
?>
