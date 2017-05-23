<?php

class billing_VARIABLE_DISCOUNT_REPORT extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function insertData($discount, $offered, $availed, $avgToken)
    {
        try {
            $sql = "INSERT INTO billing.VARIABLE_DISCOUNT_REPORT (DISCOUNT, OFFERED, AVAILED, AVG_TOKEN, ENTRY_DT) VALUES (:DISCOUNT, :OFFERED, :AVAILED, :AVG_TOKEN, :ENTRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":DISCOUNT", $discount, PDO::PARAM_INT);
            $res->bindValue(":OFFERED", $offered, PDO::PARAM_INT);
            $res->bindValue(":AVAILED", $availed, PDO::PARAM_INT);
            $res->bindValue(":AVG_TOKEN", $avgToken, PDO::PARAM_INT);
            $res->bindValue(":ENTRY_DT", date("Y-m-d"), PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getData($date)
    {
        try{
            $sql = "SELECT DISCOUNT, OFFERED, AVAILED, AVG_TOKEN FROM billing.VARIABLE_DISCOUNT_REPORT WHERE ENTRY_DT = :ENTRY_DT ORDER BY DISCOUNT";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ENTRY_DT", $date, PDO::PARAM_STR);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC))
            {
                $output[] =$row;
            }
            return $output;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
