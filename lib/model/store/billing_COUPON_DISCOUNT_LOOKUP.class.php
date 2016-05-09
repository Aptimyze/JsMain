<?php
class billing_COUPON_DISCOUNT_LOOKUP extends TABLE {

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function getDiscount($id, $serviceid)
	{
		try{
			$sql = "SELECT DISCOUNT FROM billing.COUPON_DISCOUNT_LOOKUP WHERE COUPON_ID=:COUPON_ID AND SERVICEID=:SERVICEID";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_ID", $id, PDO::PARAM_INT);
			$prep->bindParam(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$discVal = $result['DISCOUNT'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $discVal;
	}

	public function insertDiscount($id, $serviceid, $discPerc)
	{
		try{
			$sql = "INSERT INTO billing.COUPON_DISCOUNT_LOOKUP (COUPON_ID,SERVICEID,DISCOUNT) VALUES (:COUPON_ID,:SERVICEID,:DISCOUNT)";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_ID", $id, PDO::PARAM_INT);
			$prep->bindParam(":SERVICEID", $serviceid, PDO::PARAM_STR);
			$prep->bindParam(":DISCOUNT", $discPerc, PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $discVal;
	}
}
?>
