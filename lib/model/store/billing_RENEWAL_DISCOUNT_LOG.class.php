<?php

class billing_RENEWAL_DISCOUNT_LOG extends TABLE
{

    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }
    public function insert($profileid, $discount, $startDt, $expiry_dt)
    {
        try
        {
            $sql  = "INSERT INTO billing.RENEWAL_DISCOUNT_LOG VALUES(:PROFILEID, :DISCOUNT, :START_DT, :EXPIRY_DT)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":DISCOUNT", $discount, PDO::PARAM_INT);
            $prep->bindValue(":START_DT", $startDt, PDO::PARAM_STR);
            $prep->bindValue(":EXPIRY_DT", $expiry_dt, PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function removeProfilesAfterDate($start)
    {
        try
        {
            $sql  = "DELETE FROM billing.RENEWAL_DISCOUNT_LOG WHERE EXPIRY_DT>=:START_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start, PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }

    public function fetchRenewalDiscountForProfileAndDate($profileid, $date)
    {
        try
        {
            $sql  = "SELECT DISCOUNT FROM billing.RENEWAL_DISCOUNT_LOG WHERE START_DT<=:CALC_DATE AND EXPIRY_DT>=:CALC_DATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":CALC_DATE", $date, PDO::PARAM_STR);
            $prep->execute();
            if ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $discount = $row['DISCOUNT'];
            }
            return $discount;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
