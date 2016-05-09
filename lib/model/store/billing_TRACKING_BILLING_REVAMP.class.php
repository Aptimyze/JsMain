<?php

class billing_TRACKING_BILLING_REVAMP extends TABLE {
  
 	public function __construct($dbname = "") 
	{
		parent::__construct($dbname);
  	}

        public function trackingMain($profileid,$userType,$service,$source,$navigationSuggestedString,$vasImpression,$discount,$total)
        {
                try
                {
                        $sql="INSERT INTO billing.TRACKING_BILLING_REVAMP(PROFILEID,USER_TYPE,SERVICE_SELECTED,TAB_BUTTON,ENTRY_DT,NAV_SERVICE_SUGGESTED,VAS_SUGGESTED_SELECTED,DISCOUNT,NET_AMOUNT) VALUES(:PROFILEID,:USERTYPE,:SERVICE_SELECTED,:TAB_BUTTON,now(),:NAV_SERVICE_SUGGESTED,:VAS_SUGGESTED_SELECTED,:DISCOUNT,:NET_AMOUNT)";
                        $row = $this->db->prepare($sql);
                        $row->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$row->bindValue(":USERTYPE",$userType, PDO::PARAM_INT);
			$row->bindValue(":SERVICE_SELECTED",$service, PDO::PARAM_STR);
			$row->bindValue(":NAV_SERVICE_SUGGESTED",$navigationSuggestedString, PDO::PARAM_STR);
			$row->bindValue(":VAS_SUGGESTED_SELECTED",$vasImpression, PDO::PARAM_STR);
			$row->bindValue(":TAB_BUTTON",$source, PDO::PARAM_INT);
			$row->bindValue(":DISCOUNT",$discount, PDO::PARAM_INT);
			$row->bindValue(":NET_AMOUNT",$total, PDO::PARAM_INT);
                        $row->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function trackingVAS($profileid,$userType,$service,$source,$navigationSuggestedString)
	{
		if(!$serviceId)
                        throw new jsException("","SERVICEID IS BLANK");
                try
                {
                        $sql="SELECT NAME from billing.SERVICES WHERE SERVICEID=:SERVICEID";
                        $resSelectDetail = $this->db->prepare($sql);
                        $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
                        return $rowSelectDetail['NAME'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
	public function trackingPayment()
	{
		if(!$serviceId)
                        throw new jsException("","SERVICEID IS BLANK");
                try
                {
                        $sql="SELECT NAME from billing.SERVICES WHERE SERVICEID=:SERVICEID";
                        $resSelectDetail = $this->db->prepare($sql);
                        $resSelectDetail->bindValue(":SERVICEID", $serviceId, PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
                        return $rowSelectDetail['NAME'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
