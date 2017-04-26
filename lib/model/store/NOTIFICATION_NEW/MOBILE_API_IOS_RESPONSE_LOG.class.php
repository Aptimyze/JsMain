<?php
class MOBILE_API_IOS_RESPONSE_LOG extends TABLE{
        public function __construct($dbname="")
        {
                $dbname ='notification_master';
                $this->databaseName ='NOTIFICATION_NEW';
		parent::__construct($dbname);
		$this->PROFILEID_BIND_TYPE = "INT";
		$this->REGISTRATION_ID_BIND_TYPE = "STR";
		$this->MESSAGE_ID_BIND_TYPE = "INT";
		$this->STATUS_CODE_BIND_TYPE = "INT";
		$this->STATUS_MESSAGE_BIND_TYPE = "STR";
		$this->NOTIFICATION_KEY_BIND_TYPE = "STR";
        }

	public function insert($profileid, $registrationId, $messageId, $statusCode='', $statusMessage='', $notificationKey)
	{
		try
		{
			$sqlInsert = "INSERT IGNORE INTO  $this->databaseName.IOS_RESPONSE_LOG (PROFILEID, REGISTRATION_ID,MESSAGE_ID,STATUS_CODE,STATUS_MESSAGE,NOTIFICATION_KEY,SEND_DATE) VALUES (:PROFILEID, :REGISTRATION_ID, :MESSAGE_ID, :STATUS_CODE, :STATUS_MESSAGE, :NOTIFICATION_KEY, now())";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":PROFILEID", $profileid, constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":REGISTRATION_ID", $registrationId, constant('PDO::PARAM_'.$this->{'REGISTRATION_ID_BIND_TYPE'}));
			$resInsert->bindValue(":MESSAGE_ID", $messageId, constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
			$resInsert->bindValue(":STATUS_CODE", $statusCode, constant('PDO::PARAM_'.$this->{'STATUS_CODE_BIND_TYPE'}));
			$resInsert->bindValue(":STATUS_MESSAGE", $statusMessage, constant('PDO::PARAM_'.$this->{'STATUS_MESSAGE_BIND_TYPE'}));
			$resInsert->bindValue(":NOTIFICATION_KEY", $notificationKey,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
			$resInsert->execute();
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	// get Data Count 
        public function getDataCountForRange($startDate, $endDate)
        {
                try{
                        $sql = "SELECT count(PROFILEID) count, NOTIFICATION_KEY FROM $this->databaseName.`IOS_RESPONSE_LOG` WHERE SEND_DATE>=:START_DATE AND SEND_DATE<=:END_DATE GROUP BY NOTIFICATION_KEY";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[$row['NOTIFICATION_KEY']] =$row['count'];
                        }
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }

}
?>
