<?php
class billing_PAYMENT_SOURCE_TRACKING extends TABLE{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function addSourceTracking($profileid, $pgNo, $fromSource)
	{
		try
		{
			$dt = date("Y-m-d H:i:s");
			if($profileid){
				$sql="INSERT INTO billing.PAYMENT_SOURCE_TRACKING(PROFILEID,PAGE,ENTRY_DT,SOURCE) VALUES(:PROFILEID,:PAGE,:ENTRY_DT,:SOURCE)";
			} else {
				$sql="INSERT INTO billing.PAYMENT_SOURCE_TRACKING(PAGE,ENTRY_DT,SOURCE) VALUES(:PAGE,:ENTRY_DT,:SOURCE)";
			}
			$prep=$this->db->prepare($sql);
			if($profileid){
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			}
			$prep->bindValue(":PAGE",$pgNo,PDO::PARAM_INT);
			$prep->bindValue(":SOURCE",$fromSource,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$dt,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
