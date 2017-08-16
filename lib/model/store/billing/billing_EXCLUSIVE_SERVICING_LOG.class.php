<?php
class billing_EXCLUSIVE_SERVICING_LOG extends TABLE{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }

    public function addDeletedProfileFromExclusiveServicing($profileInfo){
        try{
            if(is_array($profileInfo) and !empty($profileInfo)){
                $sql = "INSERT INTO billing.EXCLUSIVE_SERVICING_LOG (AGENT_USERNAME, CLIENT_ID, ASSIGNED_DT, ENTRY_DT, SERVICE_DAY, SERVICE_SET_DT, BIODATA_LOCATION, BIODATA_UPLOAD_DT, SCREENED_DT, SCREENED_STATUS, EMAIL_STAGE) VALUES";
                $COUNT = 1;
                foreach($profileInfo as $index => $value){
                    $valueToInsert .= "(:KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["AGENT_USERNAME"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["CLIENT_ID"];
                    $bind["KEY".$COUNT]["TYPE"] = "INT";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["ASSIGNED_DT"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["ENTRY_DT"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["SERVICE_DAY"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["SERVICE_SET_DT"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["BIODATA_LOCATION"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["BIODATA_UPLOAD_DT"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["SCREENED_DT"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["SCREENED_STATUS"];
                    $bind["KEY".$COUNT]["TYPE"] = "STRING";
                    $COUNT++;
                    $valueToInsert .=":KEY".$COUNT.",";
                    $bind["KEY".$COUNT]["VALUE"] = $value["EMAIL_STAGE"];
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
                $flag = $pdoStatement->execute();
                return $flag;
            } else{
                return false;
            }
        } catch(Exception $e){
            throw new jsException($e);
        }
    }
}

?>