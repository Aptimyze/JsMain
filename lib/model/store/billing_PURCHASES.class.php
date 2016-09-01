<?php
class BILLING_PURCHASES extends TABLE{


    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function orderDetails($billId)
    {
        try 
        {
            if($billId)
            { 
                $sql="SELECT ORDERID FROM billing.PURCHASES WHERE BILLID = :BILLID ";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":BILLID",$billId,PDO::PARAM_INT);
                $prep->execute();
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $res[]= $result;
                }
                return $res;
            }   
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getPaidStatus($profileid)
    {
        try     
        {       
            if($profileid)
            {       
                $sql="SELECT COUNT(*) AS CNT from billing.PURCHASES WHERE PROFILEID =:PROFILEID AND STATUS='DONE'";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
                $result = $prep->fetch(PDO::FETCH_ASSOC);
                $count =$result['CNT'];
                if($count>0)
                    return true;
                else
                    return false;   
            }       
        }       
        catch(PDOException $e)
        {       
            throw new jsException($e);
        }       
    }

    public function getLastPurchaseDate($profileid)
    {
        try
        {
            if($profileid)
            {
                $sql="SELECT ENTRY_DT from billing.PURCHASES WHERE PROFILEID =:PROFILEID AND STATUS='DONE' AND MEMBERSHIP='Y' ORDER BY ENTRY_DT DESC limit 1";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
                $result = $prep->fetch(PDO::FETCH_ASSOC);
                $entryDt =$result['ENTRY_DT'];
                return $entryDt;
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getProfilesPaidAfter($time)
    {
        try
        {
            if($time)
            {
                $sql="SELECT PROFILEID,ENTRY_DT from billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y' AND ENTRY_DT>=:ENTRY_DT ORDER BY ENTRY_DT DESC";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":ENTRY_DT",$time,PDO::PARAM_INT);
                $prep->execute();
                $i=0;
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $profiles[$i]["PROFILEID"]=$result["PROFILEID"];
                    $profiles[$i]["ENTRY_DT"]=$result["ENTRY_DT"];
                    $i++;
                }
                return $profiles;
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }   

    public function isPaidBefore($profileid,$time)
    {
        try
        {
            if($time)
            {
                $sql="SELECT COUNT(*) AS CNT from billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y' AND ENTRY_DT<=:ENTRY_DT AND PROFILEID=:PROFILEID";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":ENTRY_DT",$time,PDO::PARAM_INT);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
                if($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $cnt=$result['CNT'];
                    if($cnt>0)
                        return 1;
                    else
                        return 0;
                }
                return 0;
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getPurchaseDetails($billId)
    {
        try 
        {
            $sql="SELECT PROFILEID,ENTRY_DT,SERVICEID FROM billing.PURCHASES WHERE BILLID = :BILLID AND STATUS='DONE'";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLID",$billId,PDO::PARAM_INT);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
                return $result;
            return;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function getAgentAllotedMainMemPaidArray($profileArray)
    {
        $agentAllotedMainMemPaidArray = array();
        foreach($profileArray as $key=>$value){
            try{
                $sql = "SELECT COUNT(DISTINCT(PROFILEID)) AS COUNT FROM billing.PURCHASES WHERE PROFILEID = :PROID AND ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND SERVEFOR LIKE '%F%' GROUP BY PROFILEID ORDER BY ENTRY_DT ASC";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROID", $value['PROFILEID'], PDO::PARAM_INT);
                $prep->bindValue(":DATE1", $value['ALLOT_TIME'], PDO::PARAM_STR);
                $prep->bindValue(":DATE2", $value['DE_ALLOCATION_DT'], PDO::PARAM_STR);
                $prep->execute();
                while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                    $agentAllotedMainMemPaidArray[$key] = array('PROFILEID'=>$value['PROFILEID'], 'MAIN_PAID_COUNT'=>$row['COUNT']);
                }
            }
            catch(Exception $e){
                throw new jsException($e);
            }
        }
        return $agentAllotedMainMemPaidArray;
    }

    
    public function bmsCheckRenewalDiscountGiven($profileid){
        if(!$profileid)
            throw new jsException("","PROFILEID  IS BLANK IN bmsCheckRenewalDiscountGiven() OF billing_PURCHASES.class.php");
        try{
            $sql="SELECT PROFILEID FROM billing.PURCHASES WHERE PROFILEID =:PROFILEID AND DISCOUNT_TYPE IN (1,7) AND STATUS = 'DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            $count =count($result['PROFILEID']);
            if($count)
                return true;
            else
                return false;   
        }       
        catch(PDOException $e)
        {       
            throw new jsException($e);
        }       
    }

    public function fetchTransactionInfo($start_dt, $end_dt, $currency)
    {
        try{
            $sql = "SELECT p.`BILLID`,  p.`PROFILEID`, `SERVICEID`, `USERNAME`, `DISCOUNT`*(1+`TAX_RATE`/100) AS TOTAL_DISCOUNT, `DISCOUNT_TYPE`, p.`ENTRY_DT` AS BILLING_DT, `DOL_CONV_RATE` FROM billing.`PURCHASES` p JOIN billing.`PAYMENT_DETAIL` pd USING(BILLID,PROFILEID) WHERE p.`ENTRY_DT` >= :START_DT AND p.`ENTRY_DT` <= :END_DT AND `CUR_TYPE` = :CURRENCY AND `DISCOUNT` > 0 AND p.STATUS='DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
            $prep->bindValue(":CURRENCY", $currency, PDO::PARAM_STR);
            $prep->execute();
            while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $row;
            }
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $res;
    }


    public function getCurrentlyActiveService($profileid){
        try{
            $sql = "SELECT PU.SERVICEID FROM billing.PURCHASES PU LEFT JOIN billing.SERVICE_STATUS SS USING(BILLID) WHERE SS.PROFILEID=:PROFILEID AND SS.SERVEFOR LIKE '%F%' AND SS.ACTIVE='Y' ORDER BY SS.EXPIRY_DT ASC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                $res = $row['SERVICEID'];
                if(!empty($res)){
                	$temp = explode(",",$res);
                	$res = $temp[0];
                } else {
                	$res = "FREE";	
                }
            } else {
                $res = "FREE";
            }
        } catch (Exception $e){
            throw new jsException($e);
        }
        return $res;
    }

    public function getFirstMainMembershipPurchaseDate($profileid)
    {
        try{
            if($profileid)
            {
                $sql="SELECT MIN(ENTRY_DT) AS ENTRY_DT FROM billing.PURCHASES WHERE PROFILEID=:PROFILEID AND STATUS='DONE' AND MEMBERSHIP='Y'";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->execute();
                $result = $prep->fetch(PDO::FETCH_ASSOC);
                return $result['ENTRY_DT'];
            }
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function fetchTotalSalesProfileWise($rangeType, $start_dt, $end_dt)
    {
        try{
            $sql = "SELECT p.PROFILEID, p.ENTRY_DT, if(TYPE='DOL',ROUND(AMOUNT*DOL_CONV_RATE*((100/(100+TAX_RATE))),0),ROUND(AMOUNT*((100/(100+TAX_RATE))),0)) as SALE, ".$rangeType."(p.ENTRY_DT) as rangeType FROM billing.PAYMENT_DETAIL AS pd, billing.PURCHASES AS p WHERE p.BILLID=pd.BILLID AND p.STATUS='DONE' AND pd.STATUS='DONE' AND p.ENTRY_DT >= :START_DT AND p.ENTRY_DT <= :END_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
            $prep->execute();
            return ($prep->fetchAll(PDO::FETCH_ASSOC));
        }
        catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function getPaidProfiles($entry_dt)
    {
        try{
            $sql="SELECT PROFILEID FROM billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y' AND DATE(ENTRY_DT)=:ENTRY_DT";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DT",$entry_dt,PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $row['PROFILEID'];
            }
            return $res;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function getPurchaseCount($profileid)
    {
        try{
            $sql="SELECT COUNT(*) AS COUNT FROM billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y' AND PROFILEID=:PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        catch(PDOException $e){
            throw new jsException($e);
        }
    }

    public function isPaidEver($profileStr, $start_dt='')
    {
        try
        {
            $sql="SELECT PROFILEID FROM billing.PURCHASES WHERE STATUS = 'DONE' AND MEMBERSHIP = 'Y' AND PROFILEID IN ($profileStr)";
            if($start_dt)
                $sql .=" AND ENTRY_DT >= :START_DT";
            $prep=$this->db->prepare($sql);
            if($start_dt)
                $prep->bindValue(":START_DT",$start_dt,PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row['PROFILEID'];
            }
            return $res;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function fetchAmountPaid($profileid, $start_dt)
    {
        try
        {
            $sql="SELECT COUNT(*) AS CNT, SUM( IF (pd.TYPE = 'DOL', pd.AMOUNT * DOL_CONV_RATE, pd.AMOUNT) ) AS AMT FROM billing.PAYMENT_DETAIL AS pd, billing.PURCHASES AS pur WHERE pd.BILLID = pur.BILLID AND pd.AMOUNT >0 AND pd.STATUS = 'DONE' AND pur.PROFILEID = :PROFILEID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            $res = $prep->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getFPRBPromotionalMailerProfiles()
    {
        try
        {
            $date1start = date('Y-m-d 00:00:00', strtotime('-10 day'));
            $date1end = date('Y-m-d 23:59:59', strtotime('-10 day'));
            $date2start = date('Y-m-d 00:00:00', strtotime('-20 day'));
            $date2end = date('Y-m-d 23:59:59', strtotime('-20 day'));
            $sql="SELECT PROFILEID FROM billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y' AND (ENTRY_DT>=:START_DT1 AND ENTRY_DT<=:END_DT1) OR (ENTRY_DT>=:START_DT2 AND ENTRY_DT<=:END_DT2)";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":START_DT1",$date1start,PDO::PARAM_STR);
            $prep->bindValue(":END_DT1",$date1end,PDO::PARAM_STR);
            $prep->bindValue(":START_DT2",$date2start,PDO::PARAM_STR);
            $prep->bindValue(":END_DT2",$date2end,PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[] = $row['PROFILEID'];
            }
            return $res;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }
    
    public function fetchEverPaidPool()
    {
        try
        {
            $sql="SELECT DISTINCT(PROFILEID) FROM billing.PURCHASES WHERE STATUS='DONE' AND MEMBERSHIP='Y'";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[]=$result['PROFILEID'];
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    public function getUpsellEligibleProfiles($prevDateTime,$currtDateTime)
    {
        try
        {
            $sql ="SELECT PROFILEID,BILLID,SERVICEID,ENTRY_DT,DISCOUNT FROM billing.PURCHASES p WHERE p.STATUS = 'DONE' AND p.MEMBERSHIP='Y' AND p.ENTRY_DT >=:PREVDATETIME AND p.ENTRY_DT <:CURRDATETIME";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PREVDATETIME",$prevDateTime,PDO::PARAM_STR);
            $prep->bindValue(":CURRDATETIME",$currtDateTime,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
                $profiles[]=$result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }
    public function getFreshPaidProfiles()
    {
        try
        {
            //$sql ="SELECT count(PROFILEID) cnt, PROFILEID, SERVICEID,ENTRY_DT FROM billing.PURCHASES p WHERE p.STATUS = 'DONE' AND p.MEMBERSHIP='Y' AND p.ENTRY_DT >=:START_DATE AND p.ENTRY_DT <=:END_DATE AND SERVICEID NOT LIKE '%X%' GROUP BY PROFILEID";
	    $sql ="SELECT count(PROFILEID) cnt, PROFILEID, SERVICEID,ENTRY_DT FROM billing.PURCHASES p WHERE p.STATUS = 'DONE' AND p.MEMBERSHIP='Y' AND SERVICEID NOT LIKE '%X%' GROUP BY PROFILEID HAVING cnt=1";	
            $prep = $this->db->prepare($sql);
            //$prep->bindValue(":START_DATE",$startDate,PDO::PARAM_STR);
            //$prep->bindValue(":END_DATE",$endDate,PDO::PARAM_STR);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC))
                $profiles[]=$result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }
    public function updateStatus($status, $billid)
    {
        try
        {
            $sql ="UPDATE billing.PURCHASES SET STATUS=:STATUS WHERE BILLID=:BILLID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $profiles;
    }

    public function genericPurchaseInsert($paramsStr, $valuesStr){
        if(empty($paramsStr) || empty($valuesStr)){
            throw new jsException("Error processing genericPurchaseInsert");
        }
        try 
        {
            $sql = "INSERT INTO billing.PURCHASES ({$paramsStr}) VALUES ({$valuesStr})";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            return $this->db->lastInsertId();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function fetchAllDataForBillid($billid){
        try 
        {
            $sql = "SELECT * FROM billing.PURCHASES WHERE BILLID=:BILLID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC)){
                $profiles=$result;
            }
        } 
        catch (Exception $e){
            throw new jsException($e);
        }

        return $profiles;
    }

    public function fetchPrintBillDataForBillid($billid){
        try 
        {
            $sql = "SELECT PD.SERVICEID,PD.CUR_TYPE,PD.PRICE AS PRICE_RS,PUR.DISCOUNT,PD.START_DATE,PD.END_DATE,PUR.PROFILEID,USERNAME,PUR.NAME,PUR.WALKIN,PUR.OVERSEAS,PUR.ENTRY_DT, ADDRESS, CITY, PIN, EMAIL,DISCOUNT_TYPE, TAX_RATE,SERVICE_TAX_CONTENT FROM billing.PURCHASES AS PUR, billing.PURCHASE_DETAIL AS PD WHERE PD.BILLID=PUR.BILLID AND PUR.BILLID=:BILLID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
            $prep->execute();
            while($result=$prep->fetch(PDO::FETCH_ASSOC)){
                $output[]=$result;
            }
        } 
        catch (Exception $e){
            throw new jsException($e);
        }

        return $output;
    }

    /*function : fetchPaymentCount
    * returns the count of previous main membership payments by profile with reference to  * given billid
    *@params: $profileid,$currentBillId
    * @return: count
    */
    public function fetchPaymentCount($profileid,$currentBillId){
        try 
        {
            if(!$profileid || !$currentBillId){
                return 0;
            } else {
                $sql = "SELECT COUNT( DISTINCT BILLID ) AS CNT FROM billing.PURCHASES WHERE PROFILEID =:PROFILEID AND BILLID < :BILLID AND STATUS = :STATUS AND MEMBERSHIP = :MEMBERSHIP";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":BILLID",$currentBillId,PDO::PARAM_INT);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->bindValue(":STATUS","DONE",PDO::PARAM_STR);
                $prep->bindValue(":MEMBERSHIP",'Y',PDO::PARAM_STR);
                $prep->execute();
                if($result=$prep->fetch(PDO::FETCH_ASSOC)){
                    return $result['CNT'];
                }
                else{
                    return 0;
                }
            }
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function fetchAllDataForBillidArr($billIdArr){
        try 
        {
        	if(empty($billIdArr)){
        		return NULL;
        	} else {
        		$billIdStr = implode(",",$billIdArr);
        		$sql = "SELECT * FROM billing.PURCHASES WHERE BILLID IN ($billIdStr)";
	            $prep=$this->db->prepare($sql);
	            $prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
	            $prep->execute();
	            while($result=$prep->fetch(PDO::FETCH_ASSOC)){
	                $profiles[$result['BILLID']] = $result;
	            }
	            return $profiles;
        	}
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getProfilesWithinDateRange($start_dt, $end_dt) {
        try {
            $sql = "SELECT * FROM billing.PURCHASES WHERE ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT AND STATUS='DONE'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
            $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
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

    public function getProfilesForReconsiliationAfter($time)
    {
        try
        {
            if($time)
            {
                $sql="SELECT * from billing.PURCHASES WHERE STATUS='DONE' AND ENTRY_DT>=:ENTRY_DT ORDER BY ENTRY_DT DESC LIMIT 1";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":ENTRY_DT",$time,PDO::PARAM_INT);
                $prep->execute();
                $i=0;
                while($result = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $profiles[$result["BILLID"]]=$result;
                }
                return $profiles;
            }
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function fetchFinanceData ($startDt, $endDt) {
        try {
            $sql = "SELECT pd.ENTRY_DT,pd.BILLID,pd.RECEIPTID,pd.PROFILEID,p.USERNAME,pur_d.SERVICEID,pur_d.START_DATE,pur_d.END_DATE,pur_d.CUR_TYPE,ROUND(((pd.AMOUNT*pur_d.SHARE)/100),2) AS AMOUNT,pur_d.DEFERRABLE,pur_d.SUBSCRIPTION_START_DATE AS ASSD, pur_d.SUBSCRIPTION_END_DATE AS ASED FROM billing.PAYMENT_DETAIL pd,billing.PURCHASE_DETAIL pur_d,billing.PURCHASES p WHERE p.BILLID=pd.BILLID AND p.PROFILEID=pd.PROFILEID AND pd.PROFILEID=pur_d.PROFILEID AND pd.BILLID=pur_d.BILLID AND pd.ENTRY_DT>=:START_DATE AND pd.ENTRY_DT<=:END_DATE AND pd.STATUS='DONE' AND pd.AMOUNT!=0 ORDER BY BILLID ASC";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":START_DATE",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $profiles[] = $result;
            }
            return $profiles;
        } 
        catch (PDOException $e) 
        {
            throw new jsException($e);
        }
    }   
}
?>
