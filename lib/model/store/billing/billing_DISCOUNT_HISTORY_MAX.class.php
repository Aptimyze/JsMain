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
                $prep->bindValue(":LAST_LOGIN_DATE_NEW",$paramsArr["LAST_LOGIN_DATE"],PDO::PARAM_STR);
                $prep->bindValue(":MAX_DISCOUNT_DATE_NEW",$paramsArr["MAX_DISCOUNT_DATE"],PDO::PARAM_STR);
                $prep->bindValue(":MAX_DISCOUNT_NEW",$paramsArr["MAX_DISCOUNT"],PDO::PARAM_INT);
                
                $prep->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }

    public function getLastLoginProfilesAfterDateCountMax($lastLoginDt){   
        if(!$lastLoginDt)
                throw new jsException("","date blank passed");
        try
        {
            $sql = "select count(distinct PROFILEID) as cnt from billing.DISCOUNT_HISTORY_MAX where ";
            $sql .= "DATE>=:DATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":LAST_LOGIN_DATE",$lastLoginDt,PDO::PARAM_STR);
            $res->execute();
            if($result = $res->fetch(PDO::FETCH_ASSOC))
                return $result['cnt'];
            return 0;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function getLastLoginProfilesMaxAfterDate($profileStr="",$lastLoginDt,$limit="",$offset=""){   
        if(!$lastLoginDt)
                throw new jsException("","date blank passed");
        try
        {
            $sql = "select DISTINCT(PROFILEID) AS PROFILEID,MAX_DISCOUNT from billing.DISCOUNT_HISTORY_MAX where ";
            if(!empty($profileStr)){
                $sql .= "PROFILEID IN($profileStr) AND ";
            }
            $sql .= "LAST_LOGIN_DATE>=:LAST_LOGIN_DATE ORDER BY LAST_LOGIN_DATE DESC";
            if($limit!=""){
                if(empty($offset)){
                    $offset = 0;
                }
                $sql .= " LIMIT $offset,$limit";
            }
            $res = $this->db->prepare($sql);
            $res->bindValue(":LAST_LOGIN_DATE",$lastLoginDt,PDO::PARAM_STR);
            $res->execute();
            while($result = $res->fetch(PDO::FETCH_ASSOC)){
                if(!($result['MAX_DISCOUNT'] == 0))
                    $profilesArr[$result['PROFILEID']] = $result;
            }
            return $profilesArr;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }
}
?>