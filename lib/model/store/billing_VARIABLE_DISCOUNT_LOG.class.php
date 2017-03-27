<?php

class billing_VARIABLE_DISCOUNT_LOG extends TABLE{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    

    // Maintains log for the expired discounts
    public function insertDataInLog($profileid,$discount,$sdate,$edate,$entryDt,$sent,$sendMail)
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_LOG(PROFILEID,DISCOUNT,SDATE,EDATE,ENTRY_DT,SENT,SENT_MAIL) VALUES(:PROFILEID,:DISCOUNT,:SDATE,:EDATE,:ENTRY_DT,:SENT,:SENT_MAIL)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
	    $prep->bindValue(":DISCOUNT",$discount,PDO::PARAM_STR);
	    $prep->bindValue(":SDATE",$sdate,PDO::PARAM_STR);
            $prep->bindValue(":EDATE",$edate,PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
	    $prep->bindValue(":SENT",$sent,PDO::PARAM_STR);
	    $prep->bindValue(":SENT_MAIL",$sendMail,PDO::PARAM_STR);				
            $prep->execute();
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    // Maintains log for the expired discounts
    public function insertDataFromVariableDiscountBackup1Day($todayDate)
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_LOG SELECT * FROM billing.VARIABLE_DISCOUNT_BACKUP_1DAY WHERE EDATE<:EDATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EDATE",$todayDate,PDO::PARAM_STR);
            $prep->execute();
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    // Maintains log for the expired discounts from variable discount table
    public function insertDataFromVariableDiscount($date)
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_LOG SELECT * FROM billing.VARIABLE_DISCOUNT WHERE EDATE < :EDATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EDATE",$date,PDO::PARAM_STR);
            $prep->execute();
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    // inserts contents from VARIABLE_DISCOUNT_OFFER_DURATION
    public function insertDataFromVariableDiscountOfferDuration()
    {
        try{
            $sql ="INSERT INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG SELECT billing.VARIABLE_DISCOUNT_OFFER_DURATION.*, billing.VARIABLE_DISCOUNT.EDATE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION left join billing.VARIABLE_DISCOUNT on billing.VARIABLE_DISCOUNT_OFFER_DURATION.PROFILEID=billing.VARIABLE_DISCOUNT.PROFILEID WHERE billing.VARIABLE_DISCOUNT.EDATE < CURDATE()";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>

