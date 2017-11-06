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
        public function updateIsQualityProfile($qualityProfiles){
                try
		{
                    if(!empty($qualityProfiles) && is_array($qualityProfiles)){
                        $sqlInsertW = array();
                        foreach($qualityProfiles as $k=>$profileId){
                                $sqlInsertW[] = ":PROFILEID".$k;
                        }
                        $sqlInsertW = implode(",",$sqlInsertW);
                        $sqlInsert = "UPDATE MIS.CAMPAIGN_KEYWORD_TRACKING SET IS_QUALITY = 'Y' WHERE PROFILEID IN ($sqlInsertW)";
                        $resInsert = $this->db->prepare($sqlInsert);
                        foreach($qualityProfiles as  $k=>$profileId){
                                $resInsert->bindValue(":PROFILEID".$k,$profileId,PDO::PARAM_STR);
                        }
                        $resInsert->execute();
                    }
		}
                catch(PDOException $e)
                {
                        jsException::nonCriticalError("Error in campaign Mis error");
                }
        }
        
        public function updateActivatedNPhoto($profileId,$value="Y",$photoUploaded="N"){
                try
		{
                    if($profileId != ""){
                        $sqlInsert = "UPDATE MIS.CAMPAIGN_KEYWORD_TRACKING SET ACTIVATED_STATUS = :ACTIVATED_STATUS, PHOTO_UPLOADED = :PHOTO_UPLOADED WHERE PROFILEID = :PROFILEID";
                        $resInsert = $this->db->prepare($sqlInsert);
                        $resInsert->bindValue(":ACTIVATED_STATUS",$value,PDO::PARAM_STR);
                        $resInsert->bindValue(":PHOTO_UPLOADED",$photoUploaded,PDO::PARAM_STR);
                        $resInsert->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $resInsert->execute();
                    }
		}
                catch(PDOException $e)
                {
                        jsException::nonCriticalError("Error in campaign Mis error");
                }
        }
}