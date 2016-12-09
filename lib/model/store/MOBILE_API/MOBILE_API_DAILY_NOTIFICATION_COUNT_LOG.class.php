<?php
class MOBILE_API_DAILY_NOTIFICATION_COUNT_LOG extends TABLE{
        public function __construct($dbname="")
        {
        	parent::__construct($dbname);
        }
	public function insertData($notificationKey,$totalCount, $gcmPush,$gcmAccepted,$pushReceived,$localApiHit,$localDelivered,$localReceived,$active7Days, $active1Days, $totalIosPushed, $totalIosReceived, $entryDate,$channelType,$notificationOpenedCount1='0',$notificationOpenedCount2='0')
	{
		try{
			$sqlInsert ="INSERT INTO MOBILE_API.DAILY_NOTIFICATION_COUNT_LOG(`NOTIFICATION_KEY`,`TOTAL_COUNT`,`PUSHED_TO_GCM`,`ACCEPTED_BY_GCM`,`PUSH_ACKNOWLEDGED`,`LOCAL_API_HIT_BY_DEVICE`,`LOCAL_SENT_TO_DEVICE`,`LOCAL_ACKNOWLEDGED`,`ACTIVE_LOGIN_7DAY`,`ACTIVE_LOGIN_1DAY`,`PUSHED_TO_IOS`,`ACCEPTED_BY_IOS`,`ENTRY_DT`,`TYPE`,`OPENED_COUNT1`,`OPENED_COUNT2`) VALUES (:NOTIFICATION_KEY,:TOTAL_COUNT,:PUSHED_TO_GCM,:ACCEPTED_BY_GCM,:PUSH_ACKNOWLEDGED,:LOCAL_API_HIT_BY_DEVICE,:LOCAL_SENT_TO_DEVICE,:LOCAL_ACKNOWLEDGED,:ACTIVE_LOGIN_7DAY,:ACTIVE_LOGIN_1DAY,:PUSHED_TO_IOS,:ACCEPTED_BY_IOS,:ENTRY_DT,:TYPE,:OPENED_COUNT1,:OPENED_COUNT2)";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":NOTIFICATION_KEY",$notificationKey, PDO::PARAM_STR);
			$resInsert->bindValue(":TOTAL_COUNT",$totalCount, PDO::PARAM_INT);
			$resInsert->bindValue(":PUSHED_TO_GCM",$gcmPush, PDO::PARAM_INT);
			$resInsert->bindValue(":ACCEPTED_BY_GCM",$gcmAccepted, PDO::PARAM_INT);
			$resInsert->bindValue(":PUSH_ACKNOWLEDGED",$pushReceived, PDO::PARAM_INT);
			$resInsert->bindValue(":LOCAL_API_HIT_BY_DEVICE",$localApiHit, PDO::PARAM_INT);
			$resInsert->bindValue(":LOCAL_SENT_TO_DEVICE",$localDelivered, PDO::PARAM_INT);
			$resInsert->bindValue(":LOCAL_ACKNOWLEDGED",$localReceived, PDO::PARAM_INT);
			$resInsert->bindValue(":ACTIVE_LOGIN_7DAY",$active7Days, PDO::PARAM_INT);
			$resInsert->bindValue(":ACTIVE_LOGIN_1DAY",$active1Days, PDO::PARAM_INT);
			$resInsert->bindValue(":PUSHED_TO_IOS",$totalIosPushed, PDO::PARAM_INT);
			$resInsert->bindValue(":ACCEPTED_BY_IOS",$totalIosReceived, PDO::PARAM_INT);
			$resInsert->bindValue(":ENTRY_DT",$entryDate, PDO::PARAM_STR);
			$resInsert->bindValue(":TYPE",$channelType, PDO::PARAM_STR);
			$resInsert->bindValue(":OPENED_COUNT1",$notificationOpenedCount1, PDO::PARAM_INT);
			$resInsert->bindValue(":OPENED_COUNT2",$notificationOpenedCount2, PDO::PARAM_INT);
			$resInsert->execute();
		}
                catch(PDOException $e){
                        throw new jsException($e);
                }
	}
        public function getData($startDate, $endDate, $notificationKey='', $scheduledNotificaionStr='', $type)
        {
		try{
			if($notificationKey)
	     	           	$sql ="SELECT TOTAL_COUNT,PUSHED_TO_GCM,ACCEPTED_BY_GCM,PUSHED_TO_IOS,ACCEPTED_BY_IOS,PUSH_ACKNOWLEDGED,LOCAL_API_HIT_BY_DEVICE,LOCAL_SENT_TO_DEVICE,LOCAL_ACKNOWLEDGED,ACTIVE_LOGIN_7DAY,ACTIVE_LOGIN_1DAY,OPENED_COUNT1,OPENED_COUNT2,DAY(ENTRY_DT) DAY FROM MOBILE_API.DAILY_NOTIFICATION_COUNT_LOG WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY AND ENTRY_DT>=:START_DATE AND ENTRY_DT<=:END_DATE AND TYPE=:TYPE GROUP BY DAY(ENTRY_DT)";
			else if($scheduledNotificaionStr)
				$sql ="SELECT SUM(TOTAL_COUNT) TOTAL_COUNT, SUM(PUSHED_TO_GCM) PUSHED_TO_GCM, SUM(ACCEPTED_BY_GCM) ACCEPTED_BY_GCM, SUM(PUSHED_TO_IOS) PUSHED_TO_IOS, SUM(ACCEPTED_BY_IOS) ACCEPTED_BY_IOS, SUM(PUSH_ACKNOWLEDGED) PUSH_ACKNOWLEDGED, SUM(LOCAL_API_HIT_BY_DEVICE) LOCAL_API_HIT_BY_DEVICE, SUM(LOCAL_SENT_TO_DEVICE) LOCAL_SENT_TO_DEVICE, SUM(LOCAL_ACKNOWLEDGED) LOCAL_ACKNOWLEDGED, SUM(ACTIVE_LOGIN_7DAY) ACTIVE_LOGIN_7DAY, SUM(ACTIVE_LOGIN_1DAY) ACTIVE_LOGIN_1DAY, SUM(OPENED_COUNT1) OPENED_COUNT1,SUM(OPENED_COUNT2) OPENED_COUNT2,DAY(ENTRY_DT) DAY FROM MOBILE_API.DAILY_NOTIFICATION_COUNT_LOG WHERE NOTIFICATION_KEY IN($scheduledNotificaionStr) AND ENTRY_DT>=:START_DATE AND ENTRY_DT<=:END_DATE AND TYPE=:TYPE GROUP BY DATE(ENTRY_DT)";
			else
				$sql ="SELECT SUM(TOTAL_COUNT) TOTAL_COUNT, SUM(PUSHED_TO_GCM) PUSHED_TO_GCM, SUM(ACCEPTED_BY_GCM) ACCEPTED_BY_GCM, SUM(PUSHED_TO_IOS) PUSHED_TO_IOS, SUM(ACCEPTED_BY_IOS) ACCEPTED_BY_IOS, SUM(PUSH_ACKNOWLEDGED) PUSH_ACKNOWLEDGED, SUM(LOCAL_API_HIT_BY_DEVICE) LOCAL_API_HIT_BY_DEVICE, SUM(LOCAL_SENT_TO_DEVICE) LOCAL_SENT_TO_DEVICE, SUM(LOCAL_ACKNOWLEDGED) LOCAL_ACKNOWLEDGED, SUM(ACTIVE_LOGIN_7DAY) ACTIVE_LOGIN_7DAY, SUM(ACTIVE_LOGIN_1DAY) ACTIVE_LOGIN_1DAY, SUM(OPENED_COUNT1) OPENED_COUNT1,SUM(OPENED_COUNT2) OPENED_COUNT2,DAY(ENTRY_DT) DAY FROM MOBILE_API.DAILY_NOTIFICATION_COUNT_LOG WHERE ENTRY_DT>=:START_DATE AND ENTRY_DT<=:END_DATE AND TYPE=:TYPE GROUP BY DATE(ENTRY_DT)";
            $res =$this->db->prepare($sql);
			
			if($notificationKey)
				$res->bindValue(":NOTIFICATION_KEY",$notificationKey, PDO::PARAM_STR);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
			$res->bindValue(":TYPE",$type, PDO::PARAM_STR);
                	$res->execute();
                	while($row = $res->fetch(PDO::FETCH_ASSOC)){
 	        	       $dataArr[$row['DAY']] = $row;
			}
		
               		return $dataArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }


}
?>
