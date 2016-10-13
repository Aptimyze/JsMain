<?php

class billing_CURRENT_GATEWAY extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    /*
     * Select the gateway in table
     */
    public function fetchCurrentGateway(){
        try{
            $sql = "SELECT GATEWAY FROM billing.CURRENT_GATEWAY ORDER BY ENTRY_DT DESC LIMIT 1";
            $res = $this->db->prepare($sql);
            $res->execute();
            while($result=$res->fetch(PDO::FETCH_ASSOC)){
                $gateway = $result['GATEWAY'];
            }
            return $gateway;
        }catch(Exception $ex){
            throw new jsException($ex);
        }
    }
    
    public function setCurrentGateway($gateway,$changedBy){
        try{
            $todayDt = date("Y-m-d H:i:s");
            //$sql = "UPDATE billing.CURRENT_GATEWAY SET GATEWAY=:GATEWAY";
            $sql = "INSERT INTO billing.CURRENT_GATEWAY (GATEWAY, ENTRY_DT, CHANGED_BY) VALUES(:GATEWAY, :ENTRY_DT, :CHANGED_BY)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":GATEWAY",$gateway,PDO::PARAM_STR);
            $res->bindValue(":ENTRY_DT",$todayDt,PDO::PARAM_STR);
            $res->bindValue(":CHANGED_BY",$changedBy,PDO::PARAM_STR);
            $res->execute();
        }catch(Exception $ex){
            throw new jsException($ex);
        }
    }
}
