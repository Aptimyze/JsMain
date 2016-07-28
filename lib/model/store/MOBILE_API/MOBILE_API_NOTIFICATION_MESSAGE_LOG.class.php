<?php
class MOBILE_API_NOTIFICATION_MESSAGE_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
                        parent::__construct($dbname);
                        $this->NOTIFICATION_KEY_BIND_TYPE = "STR";
                        $this->MESSAGE_BIND_TYPE = "STR";
			$this->TITLE_BIND_TYPE = "STR";
                        $this->MESSAGE_ID_BIND_TYPE = "INT";
        }
	public function insert($key,$msgId,$message,$title)
	{
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.NOTIFICATION_MESSAGE_LOG (`NOTIFICATION_KEY` ,`MESSAGE_ID`,`MESSAGE`,`TITLE`,`ENTRY_DT`) VALUES (:NOTIFICATION_KEY,:MESSAGE_ID,:MESSAGE,:TITLE,now())";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":NOTIFICATION_KEY",$key,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
		$resInsert->bindValue(":MESSAGE_ID",$msgId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
		$resInsert->bindValue(":MESSAGE",$message,constant('PDO::PARAM_'.$this->{'MESSAGE_BIND_TYPE'}));
		$resInsert->bindValue(":TITLE",$title,constant('PDO::PARAM_'.$this->{'TITLE_BIND_TYPE'}));
		$resInsert->execute();
	}

}
?>
