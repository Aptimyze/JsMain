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
            $sql = "SELECT GATEWAY FROM billing.CURRENT_GATEWAY";
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
    
    public function setCurrentGateway($gateway){
        try{
            $sql = "INSERT INTO billing.CURRENT_GATEWAY (GATEWAY) VALUES(:GATEWAY)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":GATEWAY",$gateway,PDO::PARAM_STR);
            $res->execute();
        }catch(Exception $ex){
            throw new jsException($ex);
        }
    }
}
