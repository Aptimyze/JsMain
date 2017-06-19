<?php
/**
 * Store of table billing.DISCOUNT_HISTORY_MAX to store the log of max discount over period of 1 month visible to the logged in user.
 *
 */
class billing_DISCOUNT_HISTORY_MAX extends TABLE{
    public function __construct($dbname=""){
        parent::__construct($dbname);
    }
    
    public function updateDiscountHistoryMax($paramsArr){
        if(is_array($paramsArr)){
            try{
                $sql = "INSERT INTO billing.DISCOUNT_HISTORY_MAX (PROFILEID, LAST_LOGIN_DATE, MAX_DISCOUNT_DATE, MAX_DISCOUNT) VALUES (:PROFILEID, :LAST_LOGIN_DATE_NEW, :MAX_DISCOUNT_DATE_NEW, :MAX_DISCOUNT_NEW) ON DUPLICATE KEY UPDATE LAST_LOGIN_DATE=:LAST_LOGIN_DATE_NEW,MAX_DISCOUNT_DATE=CASE WHEN MAX_DISCOUNT<:MAX_DISCOUNT_NEW THEN :MAX_DISCOUNT_DATE_NEW ELSE MAX_DISCOUNT_DATE END,MAX_DISCOUNT=CASE WHEN MAX_DISCOUNT<:MAX_DISCOUNT_NEW THEN :MAX_DISCOUNT_NEW ELSE MAX_DISCOUNT END";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_STR);
                $prep->bindValue(":LAST_LOGIN_DATE_NEW",date('Y-m-d'),PDO::PARAM_STR);
                $prep->bindValue(":MAX_DISCOUNT_DATE_NEW",date('Y-m-d'),PDO::PARAM_STR);
                $prep->bindValue(":MAX_DISCOUNT_NEW",$paramsArr["MAX_DISCOUNT"],PDO::PARAM_INT);
                
                $prep->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
?>