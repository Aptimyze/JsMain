<?php
class MOBILE_API_GCM_NOTIFICATION_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->TYPE_BIND_TYPE = "STR";
			$this->KEY_BIND_TYPE = "STR";
			$this->MESSAGE_BIND_TYPE = "STR";
			$this->ADD_DATE_BIND_TYPE = "STR";
			$this->SENT_BIND_TYPE = "STR";
			$this->DELIVERED_BIND_TYPE = "STR";
        }
	public function insert($profileid,$type,$key,$message,$sent,$delivered='Y')
	{
		$sqlInsert = "INSERT INTO  MOBILE_API.GCM_NOTIFICATION_LOG (`PROFILEID`,`TYPE`,`KEY`,`MESSAGE`,`ADD_DATE`,`SENT`,`DELIVERED`) VALUES (:PROFILEID,:TYPE,:KEY,:MESSAGE,now(),:SENT,:DELIVERED)";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":TYPE",$type,constant('PDO::PARAM_'.$this->{'TYPE_BIND_TYPE'}));
		$resInsert->bindValue(":KEY",$key,constant('PDO::PARAM_'.$this->{'KEY_BIND_TYPE'}));
		$resInsert->bindValue(":MESSAGE",$message,constant('PDO::PARAM_'.$this->{'MESSAGE_BIND_TYPE'}));
		$resInsert->bindValue(":SENT",$sent,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
		$resInsert->bindValue(":DELIVERED",$delivered,constant('PDO::PARAM_'.$this->{'DELIVERED_BIND_TYPE'}));
		$resInsert->execute();
	}
}
?>
