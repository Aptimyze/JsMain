<?php

class MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION extends TABLE{
    
    public function __construct($dbName = "") {
        parent::__construct($dbName);
        $this->REG_ID_BIND_TYPE = "STR";
        $this->ACTIVATED_BIND_TYPE = "STR";
        $this->USER_AGENT_BIND_TYPE = "STR";
        $this->PROFILEID_BIND_TYPE = "INT";
        $this->AGENTID_BIND_TYPE = "INT";
        $this->ENTRY_DT_BIND_TYPE = "STR";
        $this->CHANNEL_BIND_TYPE = "STR";
        $this->FALIURE_BIND_TYPE = "INT";
        $this->DISABLED_BIND_TYPE = "STR";
    }
    
    public function getRegId($profileId = '', $agentId='', $channel = ''){
        try{
            if($profileId){
                $str = " AND PROFILEID = :PROFILEID";
            }
            else if($agentId){
                $str = " AND AGENTID = :AGENTID";
            }
            if($channel){
                foreach($channel as $key => $val){
                    if($key == 0){
                        $str.= " AND (CHANNEL = :CHANNEL$key";
                    }
                    else{
                        $str.= " OR CHANNEL = :CHANNEL$key";
                    }
                }
                $str.= ")";
            }
            $sql = "SELECT CHANNEL,AGENTID, PROFILEID, REG_ID FROM MOBILE_API.`BROWSER_NOTIFICATION_REGISTRATION` WHERE  ACTIVATED = 'Y' AND DISABLED = 'N'$str";
            $prep = $this->db->prepare($sql);
            if($profileId){
                $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            }
            else if($agentId){
                $prep->bindValue(":AGENTID",$agentId,PDO::PARAM_INT);
            }
            if($channel){
                foreach($channel as $key => $val){
                    $prep->bindValue(":CHANNEL$key",$val,PDO::PARAM_STR);
                }
            }
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                if($agentId)
                    $result[$row["CHANNEL"]][$row['AGENTID']] = $row['REG_ID'];
                else
                    $result[$row["CHANNEL"]][$row['PROFILEID']] = $row['REG_ID'];
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /*find registartion id and returns details
    * @params : $regId, $channel = ''
    * @return : matched rows
    */
    public function findRegId($regId, $channel = ''){
        try{
            if($regId){
                $str = " AND REG_ID = :REG_ID ";
            }
            if($channel)
                $str = $str." AND CHANNEL = :CHANNEL ";
            $sql = "SELECT PROFILEID, AGENTID, REG_ID FROM MOBILE_API.`BROWSER_NOTIFICATION_REGISTRATION` WHERE  ACTIVATED = 'Y' $str";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":REG_ID",$regId,PDO::PARAM_INT);
            if($channel){
                $prep->bindValue(":CHANNEL",$channel,PDO::PARAM_STR);
            }
            $prep->execute();
            $index =0;
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$index++] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }


    public function updateRegId($criteria="REG_ID",$value,$newRegID){
        try{
            $sql = "UPDATE MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION SET REG_ID=:NEW_REG_ID WHERE ".$criteria."=:".$criteria;
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":NEW_REG_ID",$newRegID,PDO::PARAM_STR);
            $prep->bindValue(":".$criteria,$value,constant('PDO::PARAM_'.$this->{$criteria.'_BIND_TYPE'}));
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
     * update details in MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION---
     * @param : $criteria,$value,$updateStr
     * @return : none
     */
    public function updateRegistrationDetails($criteria="REG_ID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
    {
        if(!$value && !$inWhereStr)
            throw new jsException("value or inWhereStr IS BLANK in updateRegistrationDetails func of MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION class");
        if(!$updateArr)
            throw new jsException("updateArr IS BLANK in updateRegistrationDetails func of MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION class");
        $updateStr="";
        foreach ($updateArr as $key1 => $val1) {
            $updateStr = $updateStr."$key1=:$key1,";
            $extraBind[$key1]=$val1;
        }

        $updateStr = substr($updateStr,0,-1);
        try {    
            $sql = "UPDATE MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION set ".$updateStr." WHERE ";
            if($inWhereStr)
                $sql = $sql.$criteria." IN (:".$criteria.")";
            else
                $sql = $sql.$criteria." = :".$criteria;
            if(is_array($extraWhereClause))
            {
                foreach($extraWhereClause as $key=>$val)
                {
                    $sql.=" AND $key=:$key";
                    $extraBind[$key]=$val;
                }
            }
           
            $res = $this->db->prepare($sql);
            if($inWhereStr)
                $res->bindValue(":".$criteria, $inWhereStr, PDO::PARAM_STR);
            else
                $res->bindValue(":".$criteria, $value, constant('PDO::PARAM_'.$this->{$criteria.'_BIND_TYPE'}));
            if(is_array($extraBind))
            {
                foreach($extraBind as $key=>$val)
                {
                    $res->bindValue(":".$key, $val,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
                }
            }
            $res->execute();
            
        }
        catch(PDOException $e){
                throw new jsException($e);
        }
        return NULL;
    }

    public function insertRegistrationDetails($paramsArr){
        try{
            if($paramsArr){
                foreach ($paramsArr as $key => $value) {
                   $insertValues = $insertValues.":".$key.",";
                   $insertColumns = $insertColumns.$key.",";
                }
                $insertColumns = substr($insertColumns, 0,-1);
                $insertValues = substr($insertValues, 0,-1);
                $sql = "INSERT IGNORE INTO MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION(".$insertColumns.") VALUES (".$insertValues.")";
                $prep = $this->db->prepare($sql);
                foreach ($paramsArr as $key => $value) {
                    $prep->bindValue(":".$key,$value,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
                }
                return $prep->execute();
            }
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function checkForRegisteredUser($profileid,$channel="",$fields="*"){
        try{
            $sql = "SELECT ".$fields." FROM MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION WHERE PROFILEID=:PROFILEID";
            if($channel)
                $sql = $sql." AND CHANNEL=:CHANNEL";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            if($channel)
               $prep->bindValue(":CHANNEL",$channel,PDO::PARAM_STR); 
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $res[] = $row;
        	}
            return $res;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function updateActivationStatus($profileid, $status,$channel=""){
        try{
            $sql = "UPDATE MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION SET ACTIVATED=:ACTIVATED WHERE PROFILEID=:PROFILEID";
            if($channel)
                $sql = $sql." AND CHANNEL=:CHANNEL";
           
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ACTIVATED",$status,PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            if($channel)
                $prep->bindValue(":CHANNEL",$channel,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /*update notification DISABLED status for profile on logout and login by setting DISABLED column
    *@inputs: $profileid,$channel,$status
    *@output: none
    */
    public function updateNotificationDisableStatus($profileid,$channel='',$status){
        try{
            $sql = "UPDATE MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION SET DISABLED=:DISABLED WHERE PROFILEID=:PROFILEID";
            if($channel)
                $sql = $sql." AND CHANNEL=:CHANNEL";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":DISABLED",$status,PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
            if($channel)
                $prep->bindValue(":CHANNEL",$channel,PDO::PARAM_STR);
            $prep->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*
     * @desc: get all data of website users leaving the CRM agents
     * @params: none
     * @return: all data of the table
     */
    public function getAllWebsiteUsers(){
        try{
            $sql = "SELECT * FROM MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION WHERE PROFILEID IS NOT NULL";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row['PROFILEID']][] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
