<?php
class billing_TRACK_TRANSACTION_DISCOUNT_APPROVAL extends TABLE
{
    /**
     * @fn __construct
     * @brief Constructor function
     * @param $dbName - Database to which the connection would be made
     */
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    /**
     * @param $billid
     * @return mixed
     */
    public function fetchApprovedBy($billidArr)
    {
        try
        {
            if(is_array($billidArr)){
                $billid = implode(",",$billidArr);
            } else {
                $billid = $billidArr;
            }
            if ($billid) {
                $sql = "SELECT APPROVED_BY, BILLID FROM billing.TRACK_TRANSACTION_DISCOUNT_APPROVAL WHERE BILLID IN ($billid)";
                $prep = $this->db->prepare($sql);
                $prep->execute();

                while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    $output[$result['BILLID']] = $result['APPROVED_BY'];
                } 
                return $output;
            }
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    /**
     * @param  $billid
     * @return mixed
     */
    public function fetchBillDetails($billid)
    {
        try
        {
            if ($id) {
                $sql = "SELECT * FROM billing.TRACK_TRANSACTION_DISCOUNT_APPROVAL WHERE BILLID = :BILLID";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":BILLID", $id, PDO::PARAM_INT);
                $prep->execute();

                if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                    return $result;
                } else {
                    return null;
                }
            }
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    /**
     * @param  $billid
     * @param  $profileid
     * @param  $discount_type
     * @param  $approved_by
     * @param  $discPerc
     * @param  $iniAmt
     * @param  $finAmt
     * @param  $services
     * @param  $entryDt
     * @return mixed
     */
    public function insert($billid, $profileid, $discount_type, $approved_by, $discPerc, $iniAmt, $finAmt, $services)
    {
        try
        {
            $sql = "INSERT IGNORE INTO billing.TRACK_TRANSACTION_DISCOUNT_APPROVAL VALUES (:BILLID, :PROFILEID, :DISCOUNT_TYPE, :APPROVED_BY, :DISCOUNT_PERC, :INITIAL_BILL_AMOUNT, :FINAL_BILL_AMOUNT, :SERVICES_PURCHASED, NOW())";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":BILLID", $billid, PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":DISCOUNT_TYPE", $discount_type, PDO::PARAM_INT);
            $prep->bindValue(":APPROVED_BY", $approved_by, PDO::PARAM_STR);
            $prep->bindValue(":DISCOUNT_PERC", $discPerc, PDO::PARAM_STR);
            $prep->bindValue(":INITIAL_BILL_AMOUNT", $iniAmt, PDO::PARAM_STR);
            $prep->bindValue(":FINAL_BILL_AMOUNT", $finAmt, PDO::PARAM_STR);
            $prep->bindValue(":SERVICES_PURCHASED", $services, PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
