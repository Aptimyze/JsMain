<?php


class billing_EXCLUSIVE_FOLLOWUPS extends TABLE {

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function insertIntoExclusiveFollowups($params){
        if(is_array($params)){
            try{
                $sql = "INSERT INTO billing.EXCLUSIVE_FOLLOWUPS (AGENT_USERNAME, CLIENT_ID, MEMBER_ID, ENTRY_DT, FOLLOWUP1_DT, STATUS) VALUES (:AGENT_USERNAME, :CLIENT_ID, :MEMBER_ID, :ENTRY_DT, :FOLLOWUP1_DT, :STATUS)";
                $res = $this->db->prepare($sql);
                $res->bindValue(":AGENT_USERNAME",$params["AGENT_USERNAME"],PDO::PARAM_STR);
                $res->bindValue(":CLIENT_ID",$params["CLIENT_ID"],PDO::PARAM_INT);
                $res->bindValue(":MEMBER_ID",$params["MEMBER_ID"],PDO::PARAM_INT);
                $res->bindValue(":ENTRY_DT",$params["ENTRY_DT"],PDO::PARAM_STR);
                $res->bindValue(":FOLLOWUP1_DT",$params["FOLLOWUP1_DT"],PDO::PARAM_STR);
                $res->bindValue(":STATUS",$params["STATUS"],PDO::PARAM_INT);                
                $res->execute();
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
    }
}
?>
