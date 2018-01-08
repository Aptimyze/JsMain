<?php
class billing_APPLE_ORDERS extends TABLE{


	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertOrderDetails($profileid,$id,$orderid,$appleId,$entryDt,$receiptData)
	{
		try
		{
			$sql = "INSERT INTO billing.APPLE_ORDERS(PROFILEID,ID,ORDERID,APPLE_ID,ENTRY_DT,RECEIPT_DATA) VALUES (:PROFILEID,:ID,:ORDERID,:APPLE_ID,:ENTRY_DT,:RECEIPT_DATA)" ;
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":ID", $id, PDO::PARAM_INT);
			$res->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
			$res->bindValue(":APPLE_ID", $appleId, PDO::PARAM_STR);
			$res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
			$res->bindValue(":RECEIPT_DATA", $receiptData, PDO::PARAM_STR);
			$res->execute();
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}

	public function checkIfAppleIdExists($appleId)
	{
		try
		{
			$sql = "SELECT COUNT(*) AS CNT FROM billing.APPLE_ORDERS WHERE APPLE_ID=:APPLE_ID" ;
			$res = $this->db->prepare($sql);
			$res->bindValue(":APPLE_ID", $appleId, PDO::PARAM_STR);
			$res->execute();
			if($result = $res->fetch(PDO::FETCH_ASSOC))
			{
				return $result['CNT'];
			}
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
}
?>
