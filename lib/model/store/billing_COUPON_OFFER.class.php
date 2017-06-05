<?php
class billing_COUPON_OFFER extends TABLE {

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function validateCoupon($coupon)
	{
		try{
			$sql = "SELECT * FROM billing.COUPON_OFFER WHERE COUPON_CODE=:COUPON_CODE";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_CODE", $coupon, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				return $result;
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function getCouponID($coupon)
	{
		try{
			$sql = "SELECT ID FROM billing.COUPON_OFFER WHERE COUPON_CODE=:COUPON_CODE";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_CODE", $coupon, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$output = $result['ID'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $output;
	}

	public function updateCouponCount($coupon)
	{
		try{
			$sql = "UPDATE billing.COUPON_OFFER SET USED_COUNT = USED_COUNT+1 WHERE COUPON_CODE=:COUPON_CODE";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_CODE", $coupon, PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function insertNewCoupon($coupon,$start_dt,$end_dt,$user_limit)
	{
		try{
			$sql = "INSERT INTO billing.COUPON_OFFER(COUPON_CODE,START_DT,END_DT,USER_LIMIT,USED_COUNT) VALUES (:COUPON_CODE,:START_DT,:END_DT,:USER_LIMIT,0)";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":COUPON_CODE", $coupon, PDO::PARAM_STR);
			$prep->bindParam(":START_DT", $start_dt, PDO::PARAM_STR);
			$prep->bindParam(":END_DT", $end_dt, PDO::PARAM_STR);
			$prep->bindParam(":USER_LIMIT", $user_limit, PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

}
?>
