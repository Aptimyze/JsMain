<?php
class incentive_DISCOUNT_NEGOTIATION_LOG extends TABLE
{

    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function insert($agentName, $profileid, $discountVal, $entryDt, $expiryDt)
    {
        try
        {
            $sql = "INSERT INTO incentive.DISCOUNT_NEGOTIATION_LOG(AGENT_NAME,PROFILEID,DISCOUNT,ENTRY_DT,EXPIRY_DT) VALUES (:AGENT_NAME,:PROFILEID,:DISCOUNT,:ENTRY_DT,:EXPIRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT_NAME", $agentName, PDO::PARAM_STR);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":DISCOUNT", $discountVal, PDO::PARAM_INT);
            $res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
            $res->bindValue(":EXPIRY_DT", $expiryDt, PDO::PARAM_STR);
            $res->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function getLastNegotiatedDiscountDetails($profileid)
    {
        try
        {
            $sql = "SELECT * FROM incentive.DISCOUNT_NEGOTIATION_LOG WHERE PROFILEID=:PROFILEID ORDER BY ENTRY_DT DESC LIMIT 1";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->execute();
            if ($row = $res->fetch(PDO::FETCH_ASSOC)){
                return $row;
            } else {
                return NULL;
            }
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
