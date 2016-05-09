<?php
class incentive_PAYMENT_COLLECT extends TABLE
{
    public function __construct($dbname = "") {
        parent::__construct($dbname);
    }
    
    public function getMembershipDetails($id, $profileid) {
        try {
            $sql = "SELECT PROFILEID,SERVICE,ADDON_SERVICEID,DISCOUNT_PERCENT FROM incentive.PAYMENT_COLLECT WHERE ID=:ID AND PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ID", $id, PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $details['PROFILEID'] = $result['PROFILEID'];
                $details['MAIN_SERVICE'] = $result['SERVICE'];
                $details['ADDON_SERVICE'] = $result['ADDON_SERVICEID'];
                $details['DISCOUNT'] = $result['DISCOUNT_PERCENT'];
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
        return $details;
    }
    
    public function getLastOfflineOrderGeneratedDate($profileid) {
        try {
            $sql = "SELECT ENTRY_DT FROM incentive.PAYMENT_COLLECT WHERE PROFILEID=:PROFILEID ORDER BY ID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) $entryDt = $result['ENTRY_DT'];
            return $entryDt;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function getLastOfflineOrderDetails($profileid) {
        try {
            $sql = "SELECT * FROM incentive.PAYMENT_COLLECT WHERE PROFILEID=:PROFILEID ORDER BY ID DESC LIMIT 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) return $result;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function addProfile($paramArr = array()) {
        try {
            foreach ($paramArr as $key => $val) $ {
                $key
            } = $val;
            
            $sql = "INSERT IGNORE INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,ENTRY_DT,COMMENTS,PREF_TIME,COURIER_TYPE,ADDON_SERVICEID,DISCOUNT,AMOUNT,CUR_TYPE,PICKUP_TYPE,REQ_DT,DISCOUNT_PERCENT) VALUES(:PROFILEID,:USERNAME,:NAME,:EMAIL,:PHONE_RES,:PHONE_MOB,:SERVICE,:ADDRESS,:CITY,:PIN,:BYUSER,:CONFIRM,:ENTRY_DT,:COMMENTS,:PREF_TIME,:COURIER_TYPE,:ADDON_SERVICEID,:DISCOUNT,:AMOUNT,:CUR_TYPE,:PICKUP_TYPE,:REQ_DT,:DISCOUNT_PERCENT)";
            $res = $this->db->prepare($sql);
            
            $res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
            $res->bindValue(":USERNAME", $USERNAME, PDO::PARAM_STR);
            $res->bindValue(":NAME", $NAME, PDO::PARAM_STR);
            $res->bindValue(":EMAIL", $EMAIL, PDO::PARAM_STR);
            $res->bindValue(":PHONE_RES", $PHONE_RES, PDO::PARAM_STR);
            $res->bindValue(":PHONE_MOB", $PHONE_MOB, PDO::PARAM_STR);
            $res->bindValue(":SERVICE", $SERVICE, PDO::PARAM_STR);
            $res->bindValue(":ADDRESS", $ADDRESS, PDO::PARAM_STR);
            $res->bindValue(":CITY", $CITY, PDO::PARAM_STR);
            $res->bindValue(":PIN", $PIN, PDO::PARAM_INT);
            $res->bindValue(":BYUSER", $BYUSER, PDO::PARAM_STR);
            $res->bindValue(":CONFIRM", $CONFIRM, PDO::PARAM_STR);
            $res->bindValue(":ENTRY_DT", $ENTRY_DT, PDO::PARAM_STR);
            $res->bindValue(":COMMENTS", $COMMENTS, PDO::PARAM_STR);
            $res->bindValue(":PREF_TIME", $PREF_TIME, PDO::PARAM_STR);
            $res->bindValue(":COURIER_TYPE", $COURIER_TYPE, PDO::PARAM_STR);
            $res->bindValue(":ADDON_SERVICEID", $ADDON_SERVICEID, PDO::PARAM_STR);
            $res->bindValue(":DISCOUNT", $DISCOUNT, PDO::PARAM_STR);
            $res->bindValue(":AMOUNT", $AMOUNT, PDO::PARAM_INT);
            $res->bindValue(":CUR_TYPE", $CUR_TYPE, PDO::PARAM_STR);
            $res->bindValue(":PICKUP_TYPE", $PICKUP_TYPE, PDO::PARAM_STR);
            $res->bindValue(":REQ_DT", $REQ_DT, PDO::PARAM_STR);
            $res->bindValue(":DISCOUNT_PERCENT", $DISCOUNT_PERCENT, PDO::PARAM_STR);
            $res->execute();
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    /**
     * Function to update payment details of user in PAYMENT_COLLECT table
     *
     * @param   $paramArr
     * @return  none
     */
    public function updatePaymentDetails($paramArr) {
        try {
            foreach ($paramArr as $key => $val) {
                $ {
                    $key
                } = $val;
            }
            
            $sql = "REPLACE INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,EMAIL,BYUSER,CONFIRM,ENTRY_DT,ENTRYBY,SERVICE,ADDON_SERVICEID,DISPLAY,PICKUP_TYPE,REQ_DT) 
            VALUES(:PROFILEID,:USERNAME,:EMAIL,:BYUSER,:CONFIRM,:ENTRY_DT,:ENTRYBY,:SERVICE,:ADDON_SERVICEID,:DISPLAY,:PICKUP_TYPE,:REQ_DT)";
            $res = $this->db->prepare($sql);
            
            $res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
            $res->bindValue(":USERNAME", $USERNAME, PDO::PARAM_STR);
            $res->bindValue(":EMAIL", $EMAIL, PDO::PARAM_STR);
            $res->bindValue(":BYUSER", $BYUSER, PDO::PARAM_STR);
            $res->bindValue(":CONFIRM", $CONFIRM, PDO::PARAM_STR);
            $res->bindValue(":ENTRY_DT", $ENTRY_DT, PDO::PARAM_STR);
            $res->bindValue(":ENTRYBY", $ENTRYBY, PDO::PARAM_STR);
            $res->bindValue(":SERVICE", $SERVICE, PDO::PARAM_STR);
            $res->bindValue(":ADDON_SERVICEID", $ADDON_SERVICEID, PDO::PARAM_STR);
            $res->bindValue(":DISPLAY", $DISPLAY, PDO::PARAM_STR);
            $res->bindValue(":PICKUP_TYPE", $PICKUP_TYPE, PDO::PARAM_STR);
            $res->bindValue(":REQ_DT", $REQ_DT, PDO::PARAM_STR);
            $res->execute();
            $insert_id = $this->db->lastInsertId();
            return $insert_id;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function getLinkDiscount($linkIdSel, $profileid) {
        try {
            $sql = "SELECT DISCOUNT_PERCENT FROM incentive.PAYMENT_COLLECT WHERE ID=:ID AND PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":ID", $linkIdSel, PDO::PARAM_INT);
            $prep->execute();
            if ($result = $prep->fetch(PDO::FETCH_ASSOC)) return $result['DISCOUNT_PERCENT'];
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function updateRecordForIds($idArr) {
        try {
            $idStr = implode(",", $idArr);
            if ($idStr) {
                $sql = "UPDATE incentive.PAYMENT_COLLECT set AR_GIVEN='Y', ARAMEX_DT=now() ,ENTRYBY='cron_script',ENTRY_DT=now() WHERE ID IN($idStr)";
                $res = $this->db->prepare($sql);
                $res->execute();
            }
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }

    public function getGharpayProfiles() {
        try {
            $sql = "SELECT pc.ID,pc.PROFILEID,pc.USERNAME,pc.NAME,pc.EMAIL,pc.PHONE_RES,pc.PHONE_MOB,pc.ENTRY_DT,pc.SERVICE as MAIN_SER,pc.ADDON_SERVICEID,pc.ADDRESS,pc.COMMENTS,pc.PREF_TIME,pc.COURIER_TYPE,bc.LABEL as CITY,pc.PIN,pc.ENTRYBY,pc.DISCOUNT,pc.PREFIX_NAME,pc.LANDMARK,pc.AMOUNT from incentive.PAYMENT_COLLECT pc, incentive.BRANCH_CITY bc where pc.COURIER_TYPE='GHARPAY' and pc.CONFIRM='Y' and pc.AR_GIVEN='' and pc.DISPLAY <> 'N' and pc.CITY=bc.VALUE order by pc.CITY";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            return $data;
        }
        catch(Exception $e) {
            throw new jsException($e);
        }
    }
    
    public function updatePaymentCollectForAirex($profileid) {
        try {
            $sql = "UPDATE incentive.PAYMENT_COLLECT set BILLING='Y' where PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
            $res->execute();
        }
        catch(PDOException $e) {
            
            /*** echo the sql statement and error message ***/
            throw new jsException($e);
        }
    }
}
?>
