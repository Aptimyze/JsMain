<?php
class billing_DISCOUNT_OFFER_LOG extends TABLE
{
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function checkDiscountOffer()
	{
		try
		{
			$discount_offer='0';
			$sql="SELECT ID FROM billing.DISCOUNT_OFFER_LOG WHERE CURDATE()>=START_DT AND CURDATE()<=END_DT AND STATUS='Y' ORDER BY ID DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$discount_offer=$result['ID'];
			}
			return $discount_offer;
		}
		catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }

	}
        public function getActiveOfferDetails()
        {
                try
                {
                        $discount_offer='0';
                        $sql="SELECT SQL_CACHE START_DT,END_DT FROM billing.DISCOUNT_OFFER_LOG WHERE CURDATE()>=START_DT AND CURDATE()<=END_DT AND STATUS='Y' ORDER BY ID DESC LIMIT 1";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC)){
                                return $result;
                        }
                        return;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }

        }
	public function getExpiryDate($id)
        {
                try
                {
                        $discount_expiry_dt='0';
                        $sql="SELECT END_DT FROM billing.DISCOUNT_OFFER_LOG WHERE ID=:id";
                        $prep=$this->db->prepare($sql);
			$prep->bindValue(":id", $id, PDO::PARAM_INT);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $discount_expiry_dt=$result['END_DT'];
                        }
                        return $discount_expiry_dt;
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }

        }
        public function deActivateOffer()
        {
                try
                {
                        $sql="update billing.DISCOUNT_OFFER_LOG SET STATUS=:STATUS";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":STATUS", "N", PDO::PARAM_STR);
                        $prep->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function setDiscountOfferDates($startDate, $endDate, $executive)
        {
                try
                {
			$status ='Y';
			$activationDate =$startDate." 00:00:00";
			$deActivationDate=$endDate." 00:00:00";
                        $sql="insert into billing.DISCOUNT_OFFER_LOG(`EXECUTIVE`,`STATUS`,`START_DT`,`END_DT`,`ACTIVATION_DT`,`DE_ACTIVATION_DT`) VALUES(:EXECUTIVE,:STATUS,:START_DT,:END_DT,:ACTIVATION_DT,:DE_ACTIVATION_DT)";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":EXECUTIVE", $executive, PDO::PARAM_STR);
                        $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
                        $prep->bindValue(":START_DT", $startDate, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $endDate, PDO::PARAM_STR);
                        $prep->bindValue(":ACTIVATION_DT", $activationDate, PDO::PARAM_STR);
                        $prep->bindValue(":DE_ACTIVATION_DT", $deActivationDate, PDO::PARAM_STR);
                        $prep->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}	
?>
