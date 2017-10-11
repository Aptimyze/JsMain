<?php

class billing_VARIABLE_DISCOUNT_DURATION_POOL_TECH extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function getDiscountArr($profileid)
    {
        try
        {
            $sql="SELECT * FROM billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH WHERE PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
		$discountArr[] = $result['2_DISCOUNT'];
                $discountArr[] = $result['3_DISCOUNT'];
		$discountArr[] = $result['6_DISCOUNT'];
		$discountArr[] = $result['12_DISCOUNT'];
		$discountArr[] = $result['L_DISCOUNT'];
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
        return $discountArr;
    }

    public function getTechDiscountDetails($profileid)
    {
        $maxDiscount = 0;
        try
        {
            $sql="SELECT * FROM billing.VARIABLE_DISCOUNT_DURATION_POOL_TECH WHERE PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $output = array();
            while($result = $prep->fetch(PDO::FETCH_ASSOC)){
                $output[] = $result;
                $maxDiscount = max($maxDiscount,$result['1_DISCOUNT'],$result['2_DISCOUNT'],$result['3_DISCOUNT'],$result['6_DISCOUNT'],$result['12_DISCOUNT'],$result['L_DISCOUNT']);
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
        return array($output,$maxDiscount);
    }


}   
?>
