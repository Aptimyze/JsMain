<?php

class incentive_BRANCH_CITY extends TABLE
{
    public function __construct($szDBName = "")
    {
        parent::__construct($szDBName);
    }

    public function fetchNearBycities($chequePickup = null)
    {
        try {
            if (!empty($chequePickup)) {
                $sql = "SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y' AND CHEQUE_PICKUP='Y'";
            } else {
                $sql = "SELECT LABEL,VALUE from incentive.BRANCH_CITY where PICKUP='Y' ";
            }
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                if ($result["VALUE"] != "GU") {
                    $near_ar[$result["VALUE"]] = $result["LABEL"];
                }
            }
            return $near_ar;
        } catch (Exception $e) {
            throw new jsException($e);
        }
    }
}
