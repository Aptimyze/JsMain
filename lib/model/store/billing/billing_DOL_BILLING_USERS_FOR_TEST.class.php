<?php

/*
 * Entry to be made on hitting an API so that these users will have dollar on the membership flow.
 */
class billing_DOL_BILLING_USERS_FOR_TEST extends TABLE{
    
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }
    
    public function addUserForDol($profileid){
        if($profileid){
            try{
                $dt = date("Y-m-d H:i:s");
                $sql = "INSERT IGNORE INTO billing.DOL_BILLING_USERS_FOR_TEST (PROFILEID,ENTRY_DT) VALUES (:PROFILEID,:ENTRY_DT)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $res->bindValue(":ENTRY_DT",$dt,PDO::PARAM_STR);
                $res->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
    
    public function removeUserForDol($profileid){
        if($profileid){
            try{
                $sql = "DELETE FROM billing.DOL_BILLING_USERS_FOR_TEST WHERE PROFILEID = :PROFILEID";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $res->execute();
            return $gateway;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
    
    public function checkUserForDol($profileid){
        if($profileid){
            try{
                $sql = "SELECT * FROM billing.DOL_BILLING_USERS_FOR_TEST WHERE PROFILEID = :PROFILEID LIMIT 1";
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $res->execute();
                if($result=$res->fetch(PDO::FETCH_ASSOC)){
                    return true;
                }
                return false;
            return $gateway;
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
    
}
