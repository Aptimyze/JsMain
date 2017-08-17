<?php
class billing_ExclusiveProposalMailer extends TABLE{

    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function insertMailLog($mailer){
        try{
            if (is_array($mailer) && !empty($mailer)){
                $tomorrowDT = date("Y-m-d",strtotime(' +1 day'));
                $sql = "INSERT IGNORE INTO billing.ExclusiveProposalMailer (`RECEIVER`,`AGENT_NAME`,`AGENT_EMAIL`,`TUPLE_ID`,`FOLLOWUP_STATUS`,`DATE`) VALUES ";
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

    public function getProfilesToSendProposalMail(){
        try{
            $tomorrowDT = date("Y-m-d",strtotime(' +1 day'));
            $sql = "SELECT RECEIVER,AGENT_NAME,AGENT_EMAIL,TUPLE_ID FROM billing.ExclusiveProposalMailer WHERE DATE = :DATE AND STATUS = :STATUS";

            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DATE",$tomorrowDT,PDO::PARAM_STR);
            $prep->bindValue(":STATUS",'N',PDO::PARAM_STR);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            $COUNT=0;
            while ($row = $prep->fetch()){
                $result[$COUNT]["RECEIVER"] = $row["RECEIVER"];
                $result[$COUNT]["AGENT_NAME"] = $row["AGENT_NAME"];
                $result[$COUNT]["AGENT_EMAIL"] = $row["AGENT_EMAIL"];
                $result[$COUNT]["USER1"] = $row["TUPLE_ID"];
                $COUNT++;
            }
            return $result;
        }catch(Exception $e){
            throw new jsException($e);
        }
    }
}
?>