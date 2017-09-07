<?php

class billing_SUSPENDED_UNLIMITED_SERVICE_LOG extends TABLE {

    public function __construct($dbname = ""){
        parent::__construct($dbname);
    }

    public function insertSuspendedServices($infoArr){
        try{
            if(is_array($infoArr) && !empty($infoArr)){
                $sql = "INSERT IGNORE INTO billing.SUSPENDED_UNLIMITED_SERVICE_LOG (OLD_BILLID, OLD_SERVICEID, NEW_BILLID, NEW_SERVICEID, SUSPENDED_DATE, CONTACTS_ALLOTED, CONTACTS_VIEWED, CONTACTS_CREATED, IS_SUSPENDED) VALUES ";
                $COUNT = 1;
                foreach($infoArr as $key=>$value) {
                    $valueToInsert .= "(:KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["OLDBILLID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["OLDSERVICEID"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["NEWBILLID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["NEWSERVICEID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = date("Y-m-d h:m:s");
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["CONTACTS_ALLOTED"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["CONTACTS_VIEWED"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["CONTACTS_CREATED"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["STATUS"];
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

    public function updateStatus($oldBillID,$newBillID,$status){
        try{
            $sql = "UPDATE billing.SUSPENDED_UNLIMITED_SERVICE_LOG SET IS_SUSPENDED = :STATUS WHERE OLD_BILLID = :OLDID AND NEW_BILLID = :NEWID LIMIT 1 ";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
            $prep->bindValue(":OLDID",$oldBillID,PDO::PARAM_INT);
            $prep->bindValue(":NEWID",$newBillID,PDO::PARAM_INT);
            $prep->execute();
        }catch (Exception $e){
            throw new jsException($e);
        }
    }

    public function getContactAllotted($oldBillID,$newBillID){
        try{
            $sql = "SELECT CONTACTS_ALLOTED, CONTACTS_VIEWED, CONTACTS_CREATED FROM billing.SUSPENDED_UNLIMITED_SERVICE_LOG WHERE OLD_BILLID = :OLDID AND NEW_BILLID = :NEWID LIMIT 1 ";
            $prep=$this->db->prepare($sql);
            $prep->bindValue(":OLDID",$oldBillID,PDO::PARAM_INT);
            $prep->bindValue(":NEWID",$newBillID,PDO::PARAM_INT);
            $prep->execute();
            $prep->setFetchMode(PDO::FETCH_ASSOC);
            $result = $prep->fetch();
            return $result;
        }catch (Exception $e){
            throw new jsException($e);
        }
    }
}

?>