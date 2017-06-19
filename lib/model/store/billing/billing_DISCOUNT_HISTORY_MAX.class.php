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
                $sql = "INSERT INTO billing.DISCOUNT_HISTORY_MAX (PROFILEID, LAST_LOGIN_DATE, P, C, NCP, X,Date1) VALUES (:PROFILEID, :LAST_LOGIN_DATE, :P, :C, :NCP, :X,:DATE1) ON DUPLICATE KEY UPDATE P=CASE WHEN P<:P THEN :P ELSE P END, C=CASE WHEN C<:C THEN :C ELSE C END, NCP=CASE WHEN NCP<:NCP THEN :NCP ELSE NCP END, X=CASE WHEN X<:X THEN :X ELSE X END,LAST_LOGIN_DATE=:LAST_LOGIN_DATE,Date1=:DATE1";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$paramsArr["PROFILEID"],PDO::PARAM_STR);
                $prep->bindValue(":LAST_LOGIN_DATE",date('Y-m-d'),PDO::PARAM_STR);
                $prep->bindValue(":DATE1",date('Y-m-d H:i:s'),PDO::PARAM_STR);
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