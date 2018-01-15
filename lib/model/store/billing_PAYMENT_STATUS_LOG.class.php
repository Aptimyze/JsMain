<?php
class billing_PAYMENT_STATUS_LOG extends TABLE{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function checkIfEntryExists($orderID)
	{
		try
		{
			$sql="SELECT COUNT(*) AS CNT FROM billing.PAYMENT_STATUS_LOG WHERE ORDERID=:ORDERID";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ORDERID",$orderID,PDO::PARAM_STR);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				return $result['CNT'];
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}

	public function insertEntry($orderid,$status,$gateway,$msg)
	{
		try
		{
			$sql="INSERT into billing.PAYMENT_STATUS_LOG (ORDERID,STATUS,GATEWAY,MSG,ENTRY_DT) values (:ORDERID,:STATUS,:GATEWAY,:MSG,NOW())";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":ORDERID",$orderid,PDO::PARAM_STR);
			$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
			$prep->bindValue(":GATEWAY",$gateway,PDO::PARAM_STR);
			$prep->bindValue(":MSG",$msg,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
