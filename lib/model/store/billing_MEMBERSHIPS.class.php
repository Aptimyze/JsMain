<?php

class billing_MEMBERSHIPS extends TABLE {

	public function __construct($dbname = "") {
		parent::__construct($dbname);
	}

	public function getServiceNameByStatus($status)
	{
		try
		{
			if(!$status){
				$sql="SELECT SERVICE, SERVICE_NAME from billing.MEMBERSHIPS";
			} else {
				$sql="SELECT SERVICE, SERVICE_NAME from billing.MEMBERSHIPS WHERE MAIN=:STATUS";
			}
			$resSelectDetail = $this->db->prepare($sql);
			if($status){
				$resSelectDetail->bindValue(":STATUS",$status,PDO::PARAM_STR);
			}
			$resSelectDetail->execute();
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC)){
				$res[$rowSelectDetail['SERVICE']] = $rowSelectDetail['SERVICE_NAME'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $res;
	}
	
}

