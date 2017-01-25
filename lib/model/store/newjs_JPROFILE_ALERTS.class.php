<?php
class newjs_JPROFILE_ALERTS extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    public function fetchMembershipStatus($profileid) {
        try {
            $sql = "SELECT MEMB_CALLS,OFFER_CALLS FROM newjs.JPROFILE_ALERTS WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $memStatus['MEMB_CALLS'] = $result['MEMB_CALLS'];
                $memStatus['OFFER_CALLS'] = $result['OFFER_CALLS'];
            }
            $this->logFunctionCalling(__FUNCTION__);
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $memStatus;
    }
    
    /** Function insert added by Hemant
     This function is used to insert the email. call,sms alert data from page 1 registration.
     *
     * @param $profileid
     * @param $alertArr
     *
     *
     *
     */
    public function insert($profileid, $alertArr, $argFrom = 'R') {
        try {
            $sql = "REPLACE INTO newjs.JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS) VALUES (:PROFILEID,:SERVICE_CALL,:SERVICE_CALL,:MEM_IVR,:MEM_IVR,:MEM_MAILS,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_SMS,:SERVICE_SMS,:SERVICE_SMS,:MEM_SMS,:MEM_SMS)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_EMAIL", $alertArr[SERVICE_EMAIL], PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_SMS", $alertArr[SERVICE_SMS], PDO::PARAM_STR);
            $prep->bindValue(":MEM_SMS", $alertArr[MEM_SMS], PDO::PARAM_STR);
            $prep->bindValue(":MEM_IVR", $alertArr[MEM_IVR], PDO::PARAM_STR);
            $prep->bindValue(":MEM_MAILS", $alertArr[MEM_MAILS], PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_CALL", $alertArr[SERVICE_CALL], PDO::PARAM_STR);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);

        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        
        //Update EDIT_LOG_JPROFILE_ALERTS
        
        try {
            $now = date("Y-m-d H-i-s");
            $sql = "INSERT IGNORE INTO newjs.JPROFILE_ALERTS_LOG (PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS,FROM_PAGE,MOD_DT) VALUES (:PROFILEID,:SERVICE_CALL,:SERVICE_CALL,:MEM_IVR,:MEM_IVR,:MEM_MAILS,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_EMAIL,:SERVICE_SMS,:SERVICE_SMS,:SERVICE_SMS,:MEM_SMS,:MEM_SMS,:FROM_PAGE,:MOD_DT)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_EMAIL", $alertArr[SERVICE_EMAIL], PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_SMS", $alertArr[SERVICE_SMS], PDO::PARAM_STR);
            $prep->bindValue(":MEM_SMS", $alertArr[MEM_SMS], PDO::PARAM_STR);
            $prep->bindValue(":MEM_IVR", $alertArr[MEM_IVR], PDO::PARAM_STR);
            $prep->bindValue(":MEM_MAILS", $alertArr[MEM_MAILS], PDO::PARAM_STR);
            $prep->bindValue(":SERVICE_CALL", $alertArr[SERVICE_CALL], PDO::PARAM_STR);
            $prep->bindValue(":FROM_PAGE", $argFrom, PDO::PARAM_STR);
            $prep->bindValue(":MOD_DT", $now, PDO::PARAM_STR);
            $prep->execute();
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
        return true;
    }
    public function getUnsubscribedProfiles($profileIdArr) {
        try {
            if (is_array($profileIdArr)) {
                foreach ($profileIdArr as $key => $pid) {
                    if ($key == 0) $str = ":PROFILEID" . $key;
                    else $str.= ",:PROFILEID" . $key;
                }
                $sql = "SELECT distinct PROFILEID FROM newjs.JPROFILE_ALERTS WHERE PROFILEID IN ($str) AND (MEMB_CALLS='U' OR OFFER_CALLS='U')";
                $res = $this->db->prepare($sql);
                unset($pid);
                foreach ($profileIdArr as $key => $pid) $res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
                $res->execute();
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) $result[] = $row['PROFILEID'];
                $this->logFunctionCalling(__FUNCTION__);
                return $result;
                
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    public function getSubscriptions($profileid, $field) {
        try {
            $sql = "SELECT " . $field . " FROM newjs.JPROFILE_ALERTS WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res = $result[$field];
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }

        return $res;
    }
    
    public function getAllSubscriptions($profileid) {
        try {
            $sql = "SELECT * FROM newjs.JPROFILE_ALERTS WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res = $result;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $res;
    }

    public function getAllSubscriptionsArr($profileArr) {
        try {
        	$profileStr = implode(",", $profileArr);
            $sql = "SELECT * FROM newjs.JPROFILE_ALERTS WHERE PROFILEID IN ($profileStr)";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $res[$result['PROFILEID']] = $result;
            }
            $this->logFunctionCalling(__FUNCTION__);
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $res;
    }
    
    public function update($profileid, $key, $val) {
        try {
            $sql = "UPDATE newjs.JPROFILE_ALERTS SET {$key}=:VAL WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->bindValue(":VAL", $val, PDO::PARAM_STR);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }

    public function insertNewRow($profileid) {
        try { 
            $sql = "INSERT INTO newjs.JPROFILE_ALERTS VALUES(:PROFILEID,'S','S','S','S','S','S','S','S','S','S','S','S','S','S','S')";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);
            return true;
        }
        catch(PDOException $e) {
            throw new jsException($e);
        }
    }

    /**
     * @param $arrRecordData
     * @return mixed
     */
    public function insertRecord($arrRecordData)
    {
        if(!is_array($arrRecordData))
            throw new jsException("","Array is not passed in InsertRecord OF newjs_JPROFILE_ALERTS.class.php");

        try{
            $szINs = implode(',',array_fill(0,count($arrRecordData),'?'));

            $arrFields = array();
            foreach($arrRecordData as $key=>$val)
            {
                $arrFields[] = strtoupper($key);
            }
            $szFields = implode(",",$arrFields);

            $sql = "INSERT IGNORE INTO newjs.JPROFILE_ALERTS ($szFields) VALUES ($szINs)";
            $pdoStatement = $this->db->prepare($sql);

            //Bind Value
            $count =0;
            foreach ($arrRecordData as $k => $value)
            {
                ++$count;
                $pdoStatement->bindValue(($count), $value);
            }
            $pdoStatement->execute();
            $this->logFunctionCalling(__FUNCTION__);
            return true;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    

     public function commonSelectFunction($profileid, $fields,$onlyValue = 0) {
        try {
            $sql = "SELECT " . $fields . " FROM newjs.JPROFILE_ALERTS WHERE PROFILEID=:PROFILEID"; 
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $prep->execute();
            $this->logFunctionCalling(__FUNCTION__);
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) { 
                $res = $result;
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }

        if($onlyValue){
            return $res[$fields];
        }

        return $res;
    }


    private function logFunctionCalling($funName)
    {
      $key = __CLASS__.'_'.date('Y-m-d');
      JsMemcache::getInstance()->hIncrBy($key, $funName);
      
      JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
    }
}
?>
