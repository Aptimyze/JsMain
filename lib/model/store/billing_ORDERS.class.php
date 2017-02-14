<?php
class BILLING_ORDERS extends TABLE{
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

    public function genericOrderInsert($paramsStr, $valuesStr){
        if(empty($paramsStr) || empty($valuesStr)){
            throw new jsException("Error processing genericOrdersInsert in BILLING_ORDERS.class.php");
        }
        try 
        {
            $sql = "INSERT INTO billing.ORDERS ({$paramsStr}) VALUES ({$valuesStr})";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            return $this->db->lastInsertId();
        } 
        catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function onlinePaymentDetails($id)
    {
        try 
        {
            if($id)
            { 
                $sql="SELECT ORDERID,ID,PAYMODE,CONVERT_TZ(ENTRY_DT,'SYSTEM','right/Asia/Calcutta') as ENTRY_DT,GATEWAY FROM billing.ORDERS WHERE ID = :ID ";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":ID",$id,PDO::PARAM_INT);
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
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getOrderDetails($profileid)
    {
        try     
        {       
            if($profileid) 
            {       
                $sql="SELECT * FROM billing.ORDERS WHERE PROFILEID = :PROFILEID";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
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
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function getFailedPaymentCount($profileid,$entryDt)
    {
        try
        {
            if($profileid && $entryDt)
            {
                $sql="SELECT count(*) cnt FROM billing.ORDERS WHERE PROFILEID = :PROFILEID AND ENTRY_DT>:ENTRY_DT AND STATUS=''";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
                $prep->execute();
                if($result = $prep->fetch(PDO::FETCH_ASSOC)){
                    $totCnt= $result['cnt'];
                    return $totCnt;
                }   
                return;
            }
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function updateOrder($paramArr=array(),$profileId)
    {
        if(!$profileId)
            throw new jsException("","PROFILEID IS BLANK IN edit() of billing_ORDERS.class.php");
        try
        {
            foreach($paramArr as $key=>$val)
                $set[] = $key." = :".$key;
            $setValues = implode(",",$set);

            $sql = "UPDATE billing.ORDERS SET $setValues WHERE PROFILEID = :PROFILEID AND STATUS=''";
            $res = $this->db->prepare($sql);
            foreach($paramArr as $key=>$val)
            {
                $res->bindValue(":".$key, $val);
            }
            $res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $res->execute();
            return true;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getFailedPaymentProfiles()
    {
        try
        {
            $sql="SELECT DISTINCT PROFILEID FROM billing.ORDERS WHERE STATUS='' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) UNION SELECT DISTINCT PROFILEID FROM billing.PAYMENT_HITS WHERE ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND PAGE>1";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $resArr[]= $result['PROFILEID'];
            }
            return $resArr;
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getOrderDetailsForOrderID($id, $orderid){
        try     
        {       
            $sql="SELECT * FROM billing.ORDERS WHERE ID=:ID AND ORDERID=:ORDERID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ID",$id,PDO::PARAM_INT);
            $prep->bindValue(":ORDERID",$orderid,PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {       
                $res[]= $result;
            }       
            return $res;

        }       
        catch(PDOException $e)
        {       
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function getFailedPayUOrders($entryDt){
        try     
        {       
            $sql="SELECT ID, ORDERID, CURTYPE, PROFILEID FROM billing.ORDERS WHERE STATUS='' AND PMTRECVD = '0000-00-00' AND GATEWAY = 'PAYU' AND ENTRY_DT>=:ENTRY_DT";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {       
                $res[] = $result;
            }       
            return $res;

        }       
        catch(PDOException $e)
        {       
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function updatePaymentReceivedStatus($pmt, $status, $id, $orderid)
    {
        try
        {
            $sql="UPDATE billing.ORDERS SET PMTRECVD= :PMTRECVD, STATUS = :STATUS WHERE ID = :ID AND ORDERID = :ORDERID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ORDERID", $orderid, PDO::PARAM_STR);
            $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->bindValue(":PMTRECVD", $pmt, PDO::PARAM_STR);
            $prep->bindValue(":ID", $id, PDO::PARAM_INT);
            $prep->execute();
            return $prep->rowCount();
        }
        catch(PDOException $e)
        {
            throw new jsException($e);
        }
    }

    public function getOrderDetailsForIdStr($idStr){
        try     
        {       
            $sql="SELECT ID, GATEWAY FROM billing.ORDERS WHERE ID IN ($idStr)";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {       
                $res[$result['ID']] = $result;
            }       
            return $res;

        }       
        catch(PDOException $e)
        {       
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function getAllOrdersForAppleWithinRange($start, $end){
        try     
        {       
            $sql="SELECT * FROM billing.ORDERS WHERE GATEWAY = 'APPLEPAY' AND ENTRY_DT>=:START AND ENTRY_DT<=:END";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":START", $start, PDO::PARAM_STR);
            $prep->bindValue(":END", $end, PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {       
                $res[$result['ID']] = $result;
            }       
            return $res;

        }       
        catch(PDOException $e)
        {       
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function getOrderDetailsForId($id){
        try     
        {       
            $sql="SELECT * FROM billing.ORDERS WHERE ID=:ID";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":ID", $id, PDO::PARAM_INT);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC))
            {       
                $res = $result;
            }       
            return $res;

        }       
        catch(PDOException $e)
        {       
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }       
    }

    public function updateOrderForReconsiliation($id)
    {
        try
        {
            $sql = "UPDATE billing.ORDERS SET PMTRECVD='0000-00-00', STATUS='' WHERE ID=:ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":ID",$id,PDO::PARAM_INT);
            $res->execute();
            return true;
        }
        catch(PDOException $e)
        {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
