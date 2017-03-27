<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of incentive_SALES_PROCESS_WISE_TRACKING_HEAD_COUNT
 *
 * @author nitish
 */
class incentive_SALES_PROCESS_WISE_TRACKING_HEAD_COUNT extends TABLE {
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function getData($paramsArr){
        try{
            $sql = "SELECT * FROM incentive.SALES_PROCESS_WISE_TRACKING_HEAD_COUNT WHERE MONTH_YR=:MONTH_YR";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":MONTH_YR", $paramsArr['MONTH_YR'], PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['MONTH_YR']] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function insert($paramsArr){
        try{
            if(is_array($paramsArr)){
                $sql = "REPLACE INTO incentive.SALES_PROCESS_WISE_TRACKING_HEAD_COUNT (MONTH_YR, INBOUND_TELE, CENTER_SALES, FP_TELE, CENTRAL_RENEW_TELE, FIELD_SALES, FRANCHISEE_SALES, OUTBOUND_TELE, RCB_TELE, UNASSISTED_SALES) VALUES (:MONTH_YR, :INBOUND_TELE, :CENTER_SALES, :FP_TELE, :CENTRAL_RENEW_TELE, :FIELD_SALES, :FRANCHISEE_SALES, :OUTBOUND_TELE, :RCB_TELE, :UNASSISTED_SALES)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":MONTH_YR",$paramsArr["MONTH_YR"], PDO::PARAM_STR);
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
}
