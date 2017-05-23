<?php

class billing_RENEWAL_DISCOUNT_LOOKUP extends TABLE {
  
    public function __construct($dbname = "") {
    		parent::__construct($dbname);
    }

    public function getDiscountForScore($score)
    {
        try
        {
                $sql="SELECT DISCOUNT FROM billing.RENEWAL_DISCOUNT_LOOKUP WHERE SCORE_LOWER_LIMIT<=:SCORE AND SCORE_UPPER_LIMIT>=:SCORE";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":SCORE",$score,PDO::PARAM_INT);
                $prep->execute();
                $res = $prep->fetch(PDO::FETCH_ASSOC);
		if($res){
                	$discount =$res['DISCOUNT'];
		}
		return $discount;
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }
    }

}
