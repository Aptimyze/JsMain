<?php
class billing_ExclusiveProposalMailer extends TABLE{

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function insertMailLog($mailer){
        try{
            if (is_array($mailer) && !empty($mailer)){
                $tomorrowDT = date("Y-m-d",strtotime(' +1 day'));
                $sql = "INSERT IGNORE INTO billing.ExclusiveProposalMailer (`RECEIVER`,`AGENT_NAME`,`AGENT_EMAIL`,`TUPLE_ID`,`FOLLOWUP_STATUS`,`DATE`,`AGENT_PHONE`,`USERNAME`) VALUES ";
                $COUNT = 1;
                foreach($mailer as $key=>$value) {
                    $valueToInsert .= "(:KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["MEMBER_ID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["NAME"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["EMAIL"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["CLIENT_ID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["STATUS"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $tomorrowDT;
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["PHONE"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["USERNAME"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueInsert .= rtrim($valueToInsert,',')."),";
                    $valueToInsert="";
                }
                $valueInsert = rtrim($valueInsert,',');
                $sql .=$valueInsert;
                $pdoStatement = $this->db->prepare($sql);
                foreach($bind as $key=>$val) {
                    if($val["TYPE"] == "STRING")
                        $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_STR);
                    else
                        $pdoStatement->bindValue($key, $val["VALUE"], PDO::PARAM_INT);
                }
                $pdoStatement->execute();
            }
        }catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getProfilesToSendProposalMail($agentMail='',$isInstant=true){
        try{
            if(!$isInstant){
                $moreThanDate = date("Y-m-d");
            }
            $tomorrowDT = date("Y-m-d",strtotime(' +1 day'));
            $sql = "SELECT RECEIVER,AGENT_NAME,AGENT_EMAIL,TUPLE_ID,AGENT_PHONE,USERNAME FROM billing.ExclusiveProposalMailer WHERE ";

            if(!$isInstant){
                $sql .= " DATE >= :MORETHANDATE AND DATE <= :LESSTHANDATE AND STATUS IN (:STATUS1,:STATUS2) AND FOLLOWUP_STATUS != :FSTATUS ";
            } else{
                $sql .= " DATE = :LESSTHANDATE AND STATUS = :STATUS1 AND FOLLOWUP_STATUS = :FSTATUS ";
            }

            if(!empty($agentMail)){
                $sql .= " AND AGENT_EMAIL = :AGENTEMAIL";
            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STATUS1",'N',PDO::PARAM_STR);
            $prep->bindValue(":LESSTHANDATE",$tomorrowDT,PDO::PARAM_STR);
            $prep->bindValue(":FSTATUS",'F0',PDO::PARAM_STR);
            if(!empty($agentMail)){
                $prep->bindValue(":AGENTEMAIL",$agentMail,PDO::PARAM_STR);
            }
            if(!$isInstant){
                $prep->bindValue(":STATUS2",'U',PDO::PARAM_STR);
                $prep->bindValue(":MORETHANDATE",$moreThanDate,PDO::PARAM_STR);
            }
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            $COUNT=0;
            while ($row = $prep->fetch()){
                $result[$COUNT]["RECEIVER"] = $row["RECEIVER"];
                $result[$COUNT]["AGENT_NAME"] = $row["AGENT_NAME"];
                $result[$COUNT]["AGENT_EMAIL"] = $row["AGENT_EMAIL"];
                $result[$COUNT]["USER1"] = $row["TUPLE_ID"];
                $result[$COUNT]["AGENT_PHONE"] = $row["AGENT_PHONE"];
                $result[$COUNT]["USERNAME"] = $row["USERNAME"];
                $COUNT++;
            }
            return $result;
        }catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function updateStatus($receiver,$tupleID,$status,$date){
        try{
            $sql = "UPDATE  billing.ExclusiveProposalMailer 
                    SET STATUS = :STATUS  
                    WHERE RECEIVER = :RECEIVER AND DATE = :DATE AND TUPLE_ID = :TUPLEID";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
            $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
            $prep->bindValue(":TUPLEID",$tupleID,PDO::PARAM_INT);
            $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $prep->execute();
        }catch(Exception $e){
            throw new jsException($e);
        }
    }

    public function getUnderprocessIDsCount($date, $status = 'U'){
        try{
            $sql = "SELECT count(*) AS COUNT FROM  billing.ExclusiveProposalMailer WHERE DATE = :DATE AND STATUS = :STATUS";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
            $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $prep->fetch()){
                $count = $row["COUNT"];
            }
            if(!isset($count))
                $count = 0;
            return $count;
        }catch(Exception $e){
            throw new jsException($e);
        }
    }
    
}
?>