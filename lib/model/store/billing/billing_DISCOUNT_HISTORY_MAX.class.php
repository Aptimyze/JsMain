<?php

/**
 * Store of table billing.DISCOUNT_HISTORY_MAX to store the log of the discount visible to the logged in user.
 *
 */
class billing_DISCOUNT_HISTORY_MAX extends TABLE{
    public function __construct($dbname=""){
        parent::__construct($dbname);
    }
    
    public function updateDiscountHistoryMax($paramsArr){
        if(is_array($paramsArr)){
            try{
                $sql = "INSERT INTO billing.DISCOUNT_HISTORY_MAX (PROFILEID, DATE, P, C, NCP, X) VALUES (:PROFILEID, :DATE, :P, :C, :NCP, :X) ON DUPLICATE KEY UPDATE P=:P, C=:C, NCP=:NCP, X=:X";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_STR);
                $prep->bindValue(":DATE",date('Y-m-d'),PDO::PARAM_STR);
                $prep->bindValue(":P",$paramsArr["P"],PDO::PARAM_INT);
                $prep->bindValue(":C",$paramsArr["C"],PDO::PARAM_INT);
                $prep->bindValue(":NCP",$paramsArr["NCP"],PDO::PARAM_INT);
                $prep->bindValue(":X",$paramsArr["X"],PDO::PARAM_INT);
                $prep->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
?>