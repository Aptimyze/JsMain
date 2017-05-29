<?php
class billing_GATEWAY_RESPONSE_LOG extends TABLE
{
    /**
     * @param $dbname
     */
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function insertResponseMessage($profileid, $order_id, $order_str, $gateway, $responseMsg)
    {
        try
        {
            $dt = date("Y-m-d H:i:s");
            $sql = "INSERT INTO billing.GATEWAY_RESPONSE_LOG (PROFILEID,ORDER_ID,ORDER_STR,GATEWAY,RESPONSE_MSG,ENTRY_DT) VALUES (:PROFILEID,:ORDER_ID,:ORDER_STR,:GATEWAY,:RESPONSE_MSG,:ENTRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":ORDER_ID", $order_id, PDO::PARAM_STR);
            $res->bindValue(":ORDER_STR", $order_str, PDO::PARAM_STR);
            $res->bindValue(":GATEWAY", $gateway, PDO::PARAM_INT);
            $res->bindValue(":RESPONSE_MSG", $responseMsg, PDO::PARAM_INT);
            $res->bindValue(":ENTRY_DT", $dt, PDO::PARAM_STR);
            $res->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function updateDupRetStatus($profileid, $order_id, $dup, $ret)
    {
        try
        {
            $sql = "UPDATE billing.GATEWAY_RESPONSE_LOG SET DUP=:DUP, RET=:RET WHERE PROFILEID=:PROFILEID AND ORDER_ID=
            :ORDER_ID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":ORDER_ID", $order_id, PDO::PARAM_INT);
            $res->bindValue(":DUP", $dup, PDO::PARAM_STR);
            $res->bindValue(":RET", $ret, PDO::PARAM_STR);
            $res->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
