<?php

class incentive_SALES_PROCESS_WISE_TRACKING extends TABLE{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insert($paramsArr)
    {
        try{
            if(is_array($paramsArr)){
                $sql = "INSERT IGNORE INTO incentive.SALES_PROCESS_WISE_TRACKING (DATE, INBOUND_TELE, CENTER_SALES, FP_TELE, CENTRAL_RENEW_TELE, FIELD_SALES, FRANCHISEE_SALES, OUTBOUND_TELE, RCB_TELE, UNASSISTED_SALES) VALUES (:DATE, :INBOUND_TELE, :CENTER_SALES, :FP_TELE, :CENTRAL_RENEW_TELE, :FIELD_SALES, :FRANCHISEE_SALES, :OUTBOUND_TELE, :RCB_TELE, :UNASSISTED_SALES)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATE",$paramsArr["DATE"], PDO::PARAM_STR);
                $prep->bindValue(":INBOUND_TELE",$paramsArr["INBOUND_TELE"], PDO::PARAM_INT);
                $prep->bindValue(":CENTER_SALES",$paramsArr["CENTER_SALES"], PDO::PARAM_INT);
                $prep->bindValue(":FP_TELE",$paramsArr["FP_TELE"], PDO::PARAM_INT);
                $prep->bindValue(":CENTRAL_RENEW_TELE",$paramsArr["CENTRAL_RENEW_TELE"], PDO::PARAM_INT);
                $prep->bindValue(":FIELD_SALES",$paramsArr["FIELD_SALES"], PDO::PARAM_INT);
                $prep->bindValue(":FRANCHISEE_SALES",$paramsArr["FRANCHISEE_SALES"], PDO::PARAM_INT);
                $prep->bindValue(":OUTBOUND_TELE",$paramsArr["OUTBOUND_TELE"], PDO::PARAM_INT);
                $prep->bindValue(":RCB_TELE",$paramsArr["RCB_TELE"], PDO::PARAM_INT);
                $prep->bindValue(":UNASSISTED_SALES",$paramsArr["UNASSISTED_SALES"], PDO::PARAM_INT);
                $prep->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getData($stDate, $endDate, $range)
    {
        try{
            $sql = "SELECT ".$range."(DATE) as DT_RANGE, SUM(INBOUND_TELE) as INBOUND_TELE, SUM(CENTER_SALES) as CENTER_SALES, SUM(FP_TELE) as FP_TELE, SUM(CENTRAL_RENEW_TELE) as CENTRAL_RENEW_TELE, SUM(FIELD_SALES) as FIELD_SALES, SUM(FRANCHISEE_SALES) as FRANCHISEE_SALES, SUM(OUTBOUND_TELE) as OUTBOUND_TELE, SUM(RCB_TELE) as RCB_TELE, SUM(UNASSISTED_SALES) as UNASSISTED_SALES from incentive.SALES_PROCESS_WISE_TRACKING AS t WHERE DATE >= :ST_DATE AND DATE <= :END_DATE GROUP by DT_RANGE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ST_DATE", $stDate, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $endDate, PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[] = $row;
           }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
