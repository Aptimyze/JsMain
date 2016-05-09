<?php

class billing_PURCHASE_DETAIL extends TABLE
{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function getTotalPriceOfTransaction($billId,$profileid)
    {
        try
        {
            $sql = 'SELECT SUM(`PRICE`) AS TOTAL FROM billing.`PURCHASE_DETAIL` WHERE BILLID=:BILLID AND PROFILEID=:PROFILEID ';
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLID",$billId,PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            $total = $row['TOTAL'];
            return $total;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function updateStatus($status, $billid)
    {
        try
        {
            $sql ="UPDATE billing.PURCHASE_DETAIL SET STATUS=:STATUS WHERE BILLID=:BILLID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    public function genericPurchaseDetailInsert($paramsStr, $valuesStr){
        if(empty($paramsStr) || empty($valuesStr)){
            throw new jsException("Error processing genericPurchaseDetailInsert");
        }
        try 
        {
            $sql = "INSERT INTO billing.PURCHASE_DETAIL ({$paramsStr}) VALUES ({$valuesStr})";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            return $this->db->lastInsertId();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function updateDiscountForBillid($discount, $billid, $serviceid){
        try 
        {
            $sql = "UPDATE billing.PURCHASE_DETAIL SET DISCOUNT=:DISCOUNT WHERE BILLID=:BILLID AND SERVICEID=:SERVICEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->bindValue(":DISCOUNT",$discount,PDO::PARAM_INT);
            $prep->bindValue(":SERVICEID",$serviceid,PDO::PARAM_STR);
            $prep->execute();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getAllDetailsForBillidArr($billidArr) {
        try {
        	$billidStr = implode(",", $billidArr);
            $sql = "SELECT * FROM billing.PURCHASE_DETAIL WHERE BILLID IN ($billidStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
}
?>
