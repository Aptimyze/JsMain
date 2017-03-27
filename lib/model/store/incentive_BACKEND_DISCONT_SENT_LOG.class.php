<?php
class incentive_BACKEND_DISCONT_SENT_LOG extends TABLE
{
    /**
     * @param $dbname
     */
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    /**
     * @param $agent : logged in backend executive name
     * @param $username : username of customer to whom the discount link is sent
     * @param $plans : comma seperated list of plans selected from backend discount link
     * @param $totalAmount : base amount without any discount on selected services
     * @param $discountedAmount : amount after deduction of tax on selected services
     */
    public function insertLinkDetails($agent, $username, $profileid, $plans, $currencyType, $totalAmount, $discountedAmount)
    {
        try
        {
            $dt = date("Y-m-d H:i:s");
            $sql = "INSERT INTO incentive.BACKEND_DISCOUNT_SENT_LOG (AGENT_NAME,USERNAME,PROFILEID,PLANS_SELECTED,CURRENCY,BASE_AMOUNT,PAYABLE_AMOUNT,ENTRY_DT) VALUES (:AGENT_NAME,:USERNAME,:PROFILEID,:PLANS_SELECTED,:CURRENCY,:BASE_AMOUNT,:PAYABLE_AMOUNT,:ENTRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":AGENT_NAME", $agent, PDO::PARAM_STR);
            $res->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $res->bindValue(":PLANS_SELECTED", $plans, PDO::PARAM_STR);
            $res->bindValue(":CURRENCY", $currencyType, PDO::PARAM_STR);
            $res->bindValue(":BASE_AMOUNT", $totalAmount, PDO::PARAM_INT);
            $res->bindValue(":PAYABLE_AMOUNT", $discountedAmount, PDO::PARAM_INT);
            $res->bindValue(":ENTRY_DT", $dt, PDO::PARAM_STR);
            $res->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
