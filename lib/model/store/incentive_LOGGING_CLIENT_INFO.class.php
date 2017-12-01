<?php
class incentive_LOGGING_CLIENT_INFO extends TABLE 
{
        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
	
        public function insertIntoLoggingClientInfo($username,$remark,$crmID,$date){
            try{
                $sql = "INSERT INTO incentive.LOGGING_CLIENT_INFO(USERNAME,REMARKS,CRM_ID,ENTRY_DT) VALUES (:USERNAME,:REMARKS,:CRM_ID,:ENTRY_DT)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
                $prep->bindValue(":REMARKS",$remark,PDO::PARAM_STR);
                $prep->bindValue(":CRM_ID",$crmID,PDO::PARAM_STR);
                $prep->bindValue(":ENTRY_DT",$date,PDO::PARAM_STR);
                $result = $prep->execute();
                return $result;
            }catch(Exception $e){
                throw new jsException($e);
            }
        }
        
        public function DeleteLastThirtyDaysEntires(){
        try
        {
            $last30Days=date("Y-m-d H:i:s",time()-30*86400);
            $sql="DELETE  from incentive.LOGGING_CLIENT_INFO where ENTRY_DT<=:LAST30Days";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":LAST30Days",$last30Days,PDO::PARAM_STR);
            $prep->execute();
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $result;
    }        
}
