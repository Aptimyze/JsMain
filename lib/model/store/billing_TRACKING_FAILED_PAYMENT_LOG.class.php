<?php
class billing_TRACKING_FAILED_PAYMENT_LOG extends TABLE {
  
 	public function __construct($dbname = "") 
	{
		parent::__construct($dbname);
  	}

        public function trackingPaymentPage($profileid,$services,$netAmount,$discount,$currency,$paymentTabClick,$device)
        {
                try
                {
			$dateTime =date("Y-m-d H:i:s",time());
                        $sql="INSERT INTO billing.TRACKING_FAILED_PAYMENT_LOG(PROFILEID,ENTRY_DT,SERVICES,NET_AMOUNT,DISCOUNT,CURRENCY,PAYMENT_OPTION_SELECTED,SOURCE) VALUES(:PROFILEID,:DATE_TIME,:SERVICES,:NET_AMOUNT,:DISCOUNT,:CURRENCY,:PAYMENT_OPTION_SELECTED,:SOURCE)";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$row->bindValue(":SERVICES",$services, PDO::PARAM_STR);
			$row->bindValue(":NET_AMOUNT",$netAmount, PDO::PARAM_INT);
			$row->bindValue(":DISCOUNT",$discount, PDO::PARAM_INT);
			$row->bindValue(":CURRENCY",$currency, PDO::PARAM_STR);
			$row->bindValue(":PAYMENT_OPTION_SELECTED",$paymentTabClick, PDO::PARAM_STR);
			$row->bindValue(":DATE_TIME",$dateTime, PDO::PARAM_STR);
            $row->bindValue(":SOURCE",$device, PDO::PARAM_STR);
                        $row->execute();
                }
                catch(Exception $e)
                {	
                        throw new jsException($e);
                }
        }
	public function getFailedPaymentProfilesLog($regDt='',$profileid)
        {
                try{
                        $sql ="select count(*) as cnt from billing.TRACKING_FAILED_PAYMENT_LOG where ENTRY_DT>=:REG_DT AND PROFILEID=:PROFILEID AND SERVICES IS NULL";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":REG_DT",$regDt, PDO::PARAM_STR);
                        $row->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
                        $row->execute();
                        if($result=$row->fetch(PDO::FETCH_ASSOC)){
                                $count =$result['cnt'];
                        }
                        return $count;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function getFailedPaymentProfilesLogSourceWise($profileid,$source)
        {
                try{
                        $sql ="select count(*) as cnt from billing.TRACKING_FAILED_PAYMENT_LOG where SOURCE=:SOURCE AND PROFILEID=:PROFILEID";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":SOURCE",$source, PDO::PARAM_STR);
                        $row->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
                        $row->execute();
                        if($result=$row->fetch(PDO::FETCH_ASSOC)){
                                $count =$result['cnt'];
                        }
                        return $count;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
