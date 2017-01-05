<?php
class incentive_INBOUND_SALES_LOG extends TABLE
{

    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }

    public function insertCampaignDetails($campaignName, $callCount)
    {
        try
        {
            $sql = "INSERT INTO incentive.INBOUND_SALES_LOG VALUES (NULL,:CAMPAIGN_NAME,:CALL_COUNT,NOW())";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CAMPAIGN_NAME", $campaignName, PDO::PARAM_STR);
            $res->bindValue(":CALL_COUNT", $callCount, PDO::PARAM_INT);
            $res->execute();
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }

    public function fetchCampaignDetailsWithinRange($campaignName, $startDt, $endDt, $rangeType)
    {
        try
        {
            if ($rangeType == "D") {
                $sql = "SELECT DAYOFMONTH(ENTRY_DT) AS DAY, CAMPAIGN_NAME AS NAME, SUM(CALL_COUNT) AS CNT FROM incentive.INBOUND_SALES_LOG WHERE CAMPAIGN_NAME=:CAMPAIGN_NAME AND ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT GROUP BY CAMPAIGN_NAME, DAYOFMONTH(ENTRY_DT)";
            } else if ($rangeType == "Q" || $rangeType == "M") {
                $sql = "SELECT YEAR(ENTRY_DT) AS YEAR, MONTH(ENTRY_DT) AS MONTH, CAMPAIGN_NAME AS NAME, SUM(CALL_COUNT) AS CNT FROM incentive.INBOUND_SALES_LOG WHERE CAMPAIGN_NAME=:CAMPAIGN_NAME AND ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT GROUP BY CAMPAIGN_NAME, YEAR(ENTRY_DT), MONTH(ENTRY_DT)";
            }
            $res = $this->db->prepare($sql);
            $res->bindValue(":CAMPAIGN_NAME", $campaignName, PDO::PARAM_STR);
            $res->bindValue(":START_DT", $startDt, PDO::PARAM_STR);
            $res->bindValue(":END_DT", $endDt, PDO::PARAM_STR);
            $res->execute();
            while ($result = $res->fetch(PDO::FETCH_ASSOC)) {
                $output[] = $result;
            }
            return $output;
        } catch (PDOException $e) {
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
