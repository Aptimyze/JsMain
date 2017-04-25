<?php

/**
 * Store of table billing.DISCOUNT_HISTORY to store the log of the discount visible to the logged in user.
 *
 * @author nitish
 */
class billing_DISCOUNT_HISTORY extends TABLE{
    public function __construct($dbname=""){
        parent::__construct($dbname);
    }
    
    public function insertDiscountHistory($paramsArr){
        if(is_array($paramsArr)){
            try{
                $sql = "INSERT INTO billing.DISCOUNT_HISTORY (PROFILEID, DATE, P, C, NCP, X) VALUES (:PROFILEID, :DATE, :P, :C, :NCP, :X) ON DUPLICATE KEY UPDATE P=:P, C=:C, NCP=:NCP, X=:X";
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

    public function truncateTable($beforeDt=""){
        try{
            if($beforeDt == ""){
                $sql = "TRUNCATE TABLE billing.DISCOUNT_HISTORY";
            }
            else{
                $sql = "DELETE FROM billing.DISCOUNT_HISTORY WHERE DATE<:BEFORE_DT";
            }
            $prep = $this->db->prepare($sql);
            if($beforeDt != ""){
                $prep->bindValue(":BEFORE_DT",$beforeDt,PDO::PARAM_STR);
            }
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function getLastLoginProfilesAfterDate($profileStr="",$lastLoginDt){   
        if(!$lastLoginDt)
                throw new jsException("","date blank passed");
        try
        {
            $sql = "select PROFILEID,MAX(P) AS P_MAX from billing.DISCOUNT_HISTORY where ";
            if(!empty($profileStr)){
                $sql .= "PROFILEID IN($profileStr) AND ";
            }
            $sql .= "DATE>=:DATE GROUP BY PROFILEID ORDER BY DATE DESC";
            $res = $this->db->prepare($sql);
            $res->bindValue(":DATE",$lastLoginDt,PDO::PARAM_STR);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC))
                    $profilesArr[$result['PROFILEID']] = $result;
            return $profilesArr;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }
}
