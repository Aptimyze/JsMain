<?php
class MOBILE_API_NOTIFICATION_LOG_ARCHIVE extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	public function insertRecord($date)
	{
		$sqlInsert = "INSERT INTO  MOBILE_API.NOTIFICATION_LOG_ARCHIVE SELECT * FROM MOBILE_API.NOTIFICATION_LOG WHERE SEND_DATE<:SEND_DATE";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":SEND_DATE",$date,PDO::PARAM_STR);
		$resInsert->execute();
	}
	public function insertOneRecord($pid,$nk,$mi,$sdate,$udate,$sent,$ot)
        {
                $sqlInsert = "INSERT INTO  MOBILE_API.NOTIFICATION_LOG_ARCHIVE VALUES ('$pid','$nk','$mi','$sdate','$udate','$sent','$ot')";
                $resInsert = $this->db->prepare($sqlInsert);
                $resInsert->execute();
        }
}
?>
