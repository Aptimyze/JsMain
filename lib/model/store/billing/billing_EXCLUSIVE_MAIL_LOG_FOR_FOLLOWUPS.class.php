<?php


class billing_EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertForFollowup($param){
        try{
            $sql = "INSERT INTO billing.EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS (CLIENT_ID, ACCEPTANCE_ID, STATUS, ENTRY_DT) VALUES (:CLIENT_ID, :ACCEPTANCE_ID, :STATUS, :ENTRY_DT)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENT_ID",$param["CLIENT_ID"],PDO::PARAM_INT);
            $res->bindValue(":ACCEPTANCE_ID",$param["ACCEPTANCE_ID"],PDO::PARAM_INT);
            $res->bindValue(":STATUS",$param["STATUS"],PDO::PARAM_STR);
            $res->bindValue(":ENTRY_DT",$param["ENTRY_DT"],PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getDataDateWise($clientId,$status){
        try{
            $sql = "SELECT * from billing.EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS where CLIENT_ID = :CLIENT_ID AND STATUS = :STATUS ORDER BY ENTRY_DT DESC";
            $res = $this->db->prepare($sql);
            $res->bindValue(":CLIENT_ID", $clientId, PDO::PARAM_INT);
            $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $result[$row["ENTRY_DT"]][] = $row;
            }
            return $result;
        } catch (Exception $ex) {

        }
    }
    
    public function updateStatusForClientId($acceptanceIdStr,$status){
        try{
            $sql = "UPDATE billing.EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS SET STATUS = :STATUS WHERE ACCEPTANCE_ID IN ($acceptanceIdStr)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
