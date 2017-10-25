<?php
class MIS_CAMPAIGN_KEYWORD_TRACKING extends TABLE{
        public function __construct($dbname=""){
            parent::__construct($dbname);
        }
        
        public function insertEntry($pid,$campArr){
            if($pid){
                try
		{
                    foreach(RegistrationEnums::$campaignParamList as $key=>$val){
                       if(isset ($campArr[$key])){
                            $sqlInsert1 .= ",".$val;
                            $sqlInsert2 .= ",:".$val;
                       }
                    }
                    if($sqlInsert1){
                        $sqlInsert = "INSERT IGNORE INTO MIS.CAMPAIGN_KEYWORD_TRACKING (PROFILEID $sqlInsert1) VALUES (:PROFILEID $sqlInsert2)";
                        $resInsert = $this->db->prepare($sqlInsert);
                        $resInsert->bindValue(":PROFILEID",$pid,PDO::PARAM_INT);
                        foreach(RegistrationEnums::$campaignParamList as $key=>$val){
                            if(isset ($campArr[$key]))
                                $resInsert->bindValue(":".$val,$campArr[$key],PDO::PARAM_STR);
                        }
                        $resInsert->execute();
                    }
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
            }
            
        }
}