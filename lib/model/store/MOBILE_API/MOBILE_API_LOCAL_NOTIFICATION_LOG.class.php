<?php
class MOBILE_API_LOCAL_NOTIFICATION_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->REGISTRATION_ID_BIND_TYPE = "STR";
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->NOTIFICATION_KEY_BIND_TYPE = "STR";
			$this->MESSAGE_ID_BIND_TYPE = "INT";
			$this->ALARM_TIME_BIND_TYPE = "STR";
			$this->OS_TYPE_BIND_TYPE = "STR";
			$this->APP_V_BIND_TYPE = "STR";
			$this->OS_V_BIND_TYPE = "STR";
                        $this->DEVICE_BRAND_BIND_TYPE ="STR";
                        $this->DEVICE_MODEL_BIND_TYPE ="STR";
			$this->SENT_BIND_TYPE = "STR";
        }
	public function insert($profileid='',$key='',$messageId='',$sent='',$nextPollTime='', $osType='')
	{
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.LOCAL_NOTIFICATION_LOG (`PROFILEID`,`NOTIFICATION_KEY`,`MESSAGE_ID`,`ENTRY_DATE`,`SENT`,`ALARM_TIME`,`OS_TYPE`) VALUES (:PROFILEID,:NOTIFICATION_KEY,:MESSAGE_ID,now(),:SENT,:ALARM_TIME,:OS_TYPE)";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":NOTIFICATION_KEY",$key,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
		$resInsert->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
		$resInsert->bindValue(":ALARM_TIME",$nextPollTime,constant('PDO::PARAM_'.$this->{'ALARM_TIME_BIND_TYPE'}));
		$resInsert->bindValue(":SENT",$sent,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
		$resInsert->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
		$resInsert->execute();
	}
        public function addLog($registrationid, $apiappVersion ,$currentOSversion, $profileid,$deviceBrand='',$deviceModel='')
        {
		if(!$deviceBrand)
			$deviceBrand='';
		if(!$deviceModel)
			$deviceModel='';
                $sqlInsert = "INSERT INTO MOBILE_API.LOCAL_LOG (`REGISTRATION_ID`,`APP_V`,`OS_V`,`ENTRY_DATE`,`PROFILEID`,`DEVICE_BRAND`,`DEVICE_MODEL`) VALUES (:REGISTRATION_ID,:APP_V,:OS_V,now(),:PROFILEID,:DEVICE_BRAND,:DEVICE_MODEL)";
                $resInsert = $this->db->prepare($sqlInsert);
                $resInsert->bindValue(":REGISTRATION_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REGISTRATION_ID_BIND_TYPE'}));
                $resInsert->bindValue(":APP_V",$apiappVersion,constant('PDO::PARAM_'.$this->{'APP_V_BIND_TYPE'}));
                $resInsert->bindValue(":OS_V",$currentOSversion,constant('PDO::PARAM_'.$this->{'OS_V_BIND_TYPE'}));
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resInsert->bindValue(":DEVICE_BRAND",$deviceBrand,constant('PDO::PARAM_'.$this->{'DEVICE_BRAND_BIND_TYPE'}));
                $resInsert->bindValue(":DEVICE_MODEL",$deviceModel,constant('PDO::PARAM_'.$this->{'DEVICE_MODEL_BIND_TYPE'}));
                $resInsert->execute();
        }
        public function updateSentPrev($pid,$notificationKey,$status)
        {
                if(!$pid||!$notificationKey||!$status)
                     throw new jsException("","pid or notificationKey or status not provided to update status in LOCAL_NOTIFICATION_LOG");
                try{
                        $sql = "UPDATE MOBILE_API.LOCAL_NOTIFICATION_LOG SET SENT =:SENT,`UPDATE_DATE`=now() WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY AND PROFILEID =:PROFILEID AND SENT!='L' ORDER BY ENTRY_DATE DESC LIMIT 1";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$pid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                        $res->bindValue(":NOTIFICATION_KEY",$notificationKey,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
                        $res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function updateSent($messageId,$status,$osType)
        {
                if(!$messageId || !$status || !$osType)
                     throw new jsException("","pid/notificationKey/status not provided to update status in MOBILE_API.LOCAL_NOTIFICATION_LOG");
                try{
                        $sql = "UPDATE MOBILE_API.LOCAL_NOTIFICATION_LOG SET SENT =:SENT,`UPDATE_DATE`=now() WHERE MESSAGE_ID=:MESSAGE_ID AND OS_TYPE=:OS_TYPE AND SENT!='L'";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
                        $res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
                        $res->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function deleteNotification($messageId,$osType)
        {
                if(!$messageId || !$osType)
                     throw new jsException("","pid/notificationKey/status not provided to update status in MOBILE_API.LOCAL_NOTIFICATION_LOG");
                try{
                        $sql = "DELETE FROM MOBILE_API.LOCAL_NOTIFICATION_LOG WHERE MESSAGE_ID=:MESSAGE_ID AND OS_TYPE=:OS_TYPE AND SENT!='L'";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
                        $res->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function getDataCountForRange($startDate, $endDate)
        {
            try{
                $sql = "SELECT count(distinct PROFILEID) count, NOTIFICATION_KEY, SENT, OS_TYPE FROM MOBILE_API.`LOCAL_NOTIFICATION_LOG` WHERE ENTRY_DATE>=:START_DATE AND ENTRY_DATE<=:END_DATE GROUP BY NOTIFICATION_KEY,SENT,OS_TYPE";
                $res = $this->db->prepare($sql);
                $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                $res->execute();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    $rowArr[$row['NOTIFICATION_KEY']][$row['SENT']][$row['OS_TYPE']] =$row['count'];
                }
                return $rowArr;
            }
            catch(PDOException $e){
                throw new jsException($e);
            }
        }
	public function deleteRecordDateWise($sdate,$edate)
        {
                try{
                        $sql = "delete FROM MOBILE_API.LOCAL_NOTIFICATION_LOG WHERE ENTRY_DATE>:ST_DATE AND ENTRY_DATE<:END_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":ST_DATE",$sdate,PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$edate,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
}
?>
