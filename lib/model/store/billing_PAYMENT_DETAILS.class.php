<?php
class BILLING_PAYMENT_DETAIL extends TABLE
{
    
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function modeDetails($pid) {
        try {
            if ($pid) {
                $sql = "SELECT BILLID,MODE,CD_NUM,CD_DT,CD_CITY,BANK,IPADD,STATUS,CONVERT_TZ(ENTRY_DT,'SYSTEM','right/Asia/Calcutta') as ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID = :PROFILEID ";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
                $prep->execute();
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[] = $result;
                }
                return $res;
            }
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    public function chequeDetails($pid) {
        try {
            if ($pid) {
                $sql = "SELECT CD_NUM,CD_DT,CD_CITY,STATUS FROM billing.PAYMENT_DETAIL WHERE PROFILEID = :PROFILEID ";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
                $prep->execute();
                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $res[] = $result;
                }
                return $res;
            }
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
    
    public function getLatestPaymentDateOfProfile($pid) {
        try {
            $sql = "SELECT MAX(ENTRY_DT) AS PAYMENT_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID = :PROFILEID ";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res = $result['PAYMENT_DT'];
            }
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        return $res;
    }
    
    public function getLast30DaysCancelledBill() {
        try {
            $sql = "SELECT RECEIPTID,BILLID FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY) AND ENTRY_DT<=NOW() AND STATUS<>'DONE'";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) $billDetailsArr[] = $result;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        return $billDetailsArr;
    }
    
    public function getPaidProfiles($receiptId) {
        try {
            $sql = "SELECT PROFILEID,RECEIPTID,BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,MODE,APPLE_COMMISSION FROM billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND AMOUNT>0 AND RECEIPTID>:RECEIPTID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":RECEIPTID", $receiptId, PDO::PARAM_INT);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $profilesArr[] = $result;
            }
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        return $profilesArr;
    }

    public function getPaymentDetails($profileid, $entryDate) {
        try {
            $profilesArr = array();
            $sql = "SELECT PROFILEID,RECEIPTID,BILLID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,MODE,APPLE_COMMISSION FROM billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND AMOUNT>0 AND PROFILEID=:PROFILEID AND ENTRY_DT>=:ENTRY_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":ENTRY_DT", $entryDate, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $profilesArr[] = $result;
            }
            return $profilesArr;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getLatestPaymentDateOfProfileByAgent($pid, $billid) {
        try {
            $sql = "SELECT ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID=:PROFILEID AND STATUS='DONE' AND BILLID=:BILLID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            $res = $row['ENTRY_DT'];
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        return $res;
    }
    
    public function getPaidStatusForProfileInRange($profileid, $allotDate, $start_dt, $end_dt) {
        try {
            $sql = "SELECT PD.PROFILEID FROM billing.PAYMENT_DETAIL PD LEFT JOIN billing.PURCHASES PU USING(PROFILEID) WHERE PD.PROFILEID=:PROFILEID AND PD.STATUS='DONE' AND PU.MEMBERSHIP='Y' AND PU.ENTRY_DT>=:ALLOT_DT AND PU.ENTRY_DT>=:START_DT AND PU.ENTRY_DT<=:END_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":ALLOT_DT", $allotDate, PDO::PARAM_STR);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                return true;
            } 
            else {
                return false;
            }
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getProfilesWithinDateRange($start_dt, $end_dt) {
        try {
            $sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT AND STATUS='DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function updateComissions($profileid, $billid, $apple, $franchisee) {
        
        try {
            if (empty($apple)) {
                $apple = 0;
            }
            if (empty($franchisee)) {
                $franchisee = 0;
            }
            
            $sql = "UPDATE billing.PAYMENT_DETAIL SET APPLE_COMMISSION=:APPLE, FRANCHISEE_COMMISSION=:FRANCHISEE WHERE PROFILEID=:PROFILEID AND BILLID=:BILLID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->bindValue(":APPLE", $apple, PDO::PARAM_INT);
            $prep->bindValue(":FRANCHISEE", $franchisee, PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function updateFranchiseeComissions($profileid, $billid, $franchisee) {
        
        try {
            if (empty($franchisee)) {
                $franchisee = 0;
            }
            
            $sql = "UPDATE billing.PAYMENT_DETAIL SET FRANCHISEE_COMMISSION=:FRANCHISEE WHERE PROFILEID=:PROFILEID AND BILLID=:BILLID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->bindValue(":FRANCHISEE", $franchisee, PDO::PARAM_INT);
            $prep->execute();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    public function getDetails($billId) {
        try {
            $sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID=:BILLID AND STATUS='DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":BILLID", $billId, PDO::PARAM_INT);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function genericPaymentInsert($paramsStr, $valuesStr) {
        if (empty($paramsStr) || empty($valuesStr)) {
            throw new jsException("Error processing genericPaymentInsert");
        }
        try {
            $sql = "INSERT INTO billing.PAYMENT_DETAIL ({$paramsStr}) VALUES ({$valuesStr})";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            return $this->db->lastInsertId();
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function fetchAllDataForReceiptId($receiptid) {
        try {
            $sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE RECEIPTID=:RECEIPTID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":RECEIPTID", $receiptid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output = $result;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }

        return $output;
    }
    
    public function fetchPrintBillDataForReceiptId($receiptid) {
        try {
            $sql = "SELECT MODE, TYPE, AMOUNT, CD_NUM, CD_DT, CD_CITY, ENTRY_DT,ENTRYBY,DEPOSIT_BRANCH from billing.PAYMENT_DETAIL where RECEIPTID=:RECEIPTID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":RECEIPTID", $receiptid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output = $result;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        
        return $output;
    }

    public function getPaidCountForProfiles($profileidArr) {
        try {
            $profileStr = implode(",", $profileidArr);
            $sql = "SELECT count(PROFILEID) AS CNT,PROFILEID FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN($profileStr) GROUP BY PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getPaymentsDuringVariableDiscountPeriod($startDate, $endDate)
    {
        try{
            $sql = "SELECT PROFILEID, AMOUNT FROM billing.PAYMENT_DETAIL WHERE (ENTRY_DT >= :START_DATE AND ENTRY_DT <= :END_DATE )";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE", $startDate, PDO::PARAM_STR);
            $prep->bindValue(":END_DATE", $endDate, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $output[] = $result;
            }
            return $output;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getProfilesWithinDateRangeWithTaxRate($start_dt, $end_dt) {
        try {
            $sql = "SELECT pd.PROFILEID, pd.TYPE, pd.AMOUNT, pd.BILLID, pd.ENTRY_DT, pd.APPLE_COMMISSION, pd.DOL_CONV_RATE, p.TAX_RATE FROM billing.PAYMENT_DETAIL as pd JOIN billing.PURCHASES as p on pd.BILLID=p.BILLID WHERE pd.ENTRY_DT>=:START_DT AND pd.ENTRY_DT<=:END_DT AND pd.STATUS='DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }

    public function getAllDetailsForBillidArr($billidArr) {
        try {
        	$billidStr = implode(",", $billidArr);
            $sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID IN ($billidStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[$result['BILLID']] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }

    public function fetchAverageTicketSizeNexOfTaxForBillidArr($billidArr) {
        try {
            $billidStr = implode(",", $billidArr);
            $sql = "SELECT if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT, BILLID FROM billing.PAYMENT_DETAIL WHERE BILLID IN ($billidStr) AND STATUS='DONE' AND AMOUNT>0";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output += $result['AMOUNT']*(1-billingVariables::NET_OFF_TAX_RATE);
            }
            return round($output,2);
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getStatusTransactions($billId,$statusArr) {
        try {
            $statusStr = implode(",",$statusArr);
            $sql = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID=:BILLID AND STATUS IN ($statusStr)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":BILLID", $billId, PDO::PARAM_INT);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }
}
?>
