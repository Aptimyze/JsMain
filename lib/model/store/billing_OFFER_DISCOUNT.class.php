<?php
class billing_OFFER_DISCOUNT extends TABLE{
       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function getDiscount($profileid)
	{
		try
                {
			$serviceid='P1';
			$sql="SELECT PROFILEID from billing.OFFER_DISCOUNT WHERE PROFILEID=:PROFILEID AND EXPIRY_DT>=NOW() AND SERVICEID=:SERVICEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SERVICEID",$serviceid,PDO::PARAM_STR);
                        $prep->execute();
			$rowsAffected=$prep->rowCount();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $rowsAffected;
	}	
}
?>
