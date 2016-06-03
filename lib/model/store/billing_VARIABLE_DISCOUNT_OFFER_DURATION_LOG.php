<?php

class billing_VARIABLE_DISCOUNT_OFFER_DURATION_LOG extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    // Maintain records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts in VARIABLE_DISCOUNT_OFFER_DURATION_LOG
    public function maintainExpiredDiscounts($todaysDate)
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG SELECT a.*, b.EDATE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION a, billing.VARIABLE_DISCOUNT_BACKUP_1DAY b where a.PROFILEID=b.PROFILEID AND b.EDATE<:EDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":EDATE", $todaysDate, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    public function getDiscountDetails($profileid)
    {
        try{
            $sql="SELECT * FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG WHERE PROFILEID=:PROFILEID ORDER BY EDATE DESC LIMIT 5";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
                $output[] = $result;
        }
        catch(PDOException $e){
            throw new jsException($e);
	}
        return $output;
    }

}
?>
