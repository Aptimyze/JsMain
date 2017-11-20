<?php
class MOBILE_API_DAILY_MATCHALERT_NOTIFICATION extends TABLE{
        public function __construct($dbname="")
        {
        	$dbname ='notification_master';
                $this->databaseName ='NOTIFICATION_NEW';
                parent::__construct($dbname);
        }
	public function addRecord($receiver, $otherProfileid, $otherPicUrl, $otherPicIosUrl, $count, $receiverLastLoginDate, $status)
	{
	        $istTime = date("Y-m-d H:i:s", strtotime('+10 hour 30 minutes'));
		$sqlInsert = "INSERT IGNORE INTO  $this->databaseName.DAILY_MATCHALERT_NOTIFICATION (`RECEIVER`,`OT_PROFILEID`,`OT_PIC_URL`,`OT_PIC_IOS_URL`,`COUNT`,`REC_LAST_LOGIN_DATE`,`IST_ENTRY_DATE`,`STATUS`) VALUES (:RECEIVER,:OT_PROFILEID,:OT_PIC_URL,:OT_PIC_IOS_URL,:COUNT,:REC_LAST_LOGIN_DATE,:IST_ENTRY_DATE,:STATUS)";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":RECEIVER",$receiver,PDO::PARAM_INT);
		$resInsert->bindValue(":OT_PROFILEID",$otherProfileid,PDO::PARAM_INT);
		$resInsert->bindValue(":OT_PIC_URL",$otherPicUrl,PDO::PARAM_STR);
		$resInsert->bindValue(":OT_PIC_IOS_URL",$otherPicIosUrl,PDO::PARAM_STR);
		$resInsert->bindValue(":COUNT",$count,PDO::PARAM_INT);
                $resInsert->bindValue(":REC_LAST_LOGIN_DATE",$receiverLastLoginDate,PDO::PARAM_STR);
                $resInsert->bindValue(":IST_ENTRY_DATE",$istTime,PDO::PARAM_STR);
                $resInsert->bindValue(":STATUS",$status,PDO::PARAM_STR);
		$resInsert->execute();
	}
        public function updateRecord($id,$receiverLastLoginDate,$otherProfileid,$otherPicUrl,$otherPicIosUrl,$count=0,$status)
        {
                try
                {
                        $sql = "UPDATE $this->databaseName.DAILY_MATCHALERT_NOTIFICATION SET OT_PROFILEID=:OT_PROFILEID,OT_PIC_URL=:OT_PIC_URL,OT_PIC_IOS_URL=:OT_PIC_IOS_URL,COUNT=:COUNT,REC_LAST_LOGIN_DATE=:REC_LAST_LOGIN_DATE WHERE ID=:ID AND STATUS=:STATUS";
			$res=$this->db->prepare($sql);
	                $res->bindValue(":OT_PROFILEID",$otherProfileid,PDO::PARAM_INT);
	                $res->bindValue(":OT_PIC_URL",$otherPicUrl,PDO::PARAM_STR);
	                $res->bindValue(":OT_PIC_IOS_URL",$otherPicIosUrl,PDO::PARAM_STR);
	                $res->bindValue(":COUNT",$count,PDO::PARAM_INT);
	                $res->bindValue(":STATUS",$status,PDO::PARAM_STR);
			$res->bindValue(":REC_LAST_LOGIN_DATE",$receiverLastLoginDate,PDO::PARAM_STR);
			$res->bindValue(":ID",$id,PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function updateStatus($id,$status)
        {
                try
                {
                        $sql = "UPDATE $this->databaseName.DAILY_MATCHALERT_NOTIFICATION SET STATUS=:STATUS WHERE ID=:ID";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":ID",$id,PDO::PARAM_INT);
                        $res->bindValue(":STATUS",$status,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function getRecordForReceiver($receiver,$status)
        {
                try{
                        $sql = "SELECT * FROM $this->databaseName.DAILY_MATCHALERT_NOTIFICATION WHERE RECEIVER=:RECEIVER AND STATUS=:STATUS ORDER BY ID DESC LIMIT 1";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":RECEIVER",$receiver, PDO::PARAM_STR);
			$res->bindValue(":STATUS",$status, PDO::PARAM_STR);
                        $res->execute();
                        if($rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC)){
                               $detailArr = $rowSelectDetail;
			}
                        return $detailArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
        public function getRecords($dateTime,$status, $noOfScripts, $currentScript, $limit)
        {
                try{
                        $sql = "SELECT * FROM $this->databaseName.DAILY_MATCHALERT_NOTIFICATION WHERE STATUS=:STATUS AND IST_ENTRY_DATE<:IST_ENTRY_DATE AND ID%:NO_OF_SCRIPTS=:CURRENT_SCRIPT LIMIT :LIMIT";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":IST_ENTRY_DATE",$dateTime, PDO::PARAM_STR);
                        $res->bindValue(":STATUS",$status, PDO::PARAM_STR);
                        $res->bindValue(":NO_OF_SCRIPTS",$noOfScripts,PDO::PARAM_INT);
                        $res->bindValue(":CURRENT_SCRIPT",$currentScript,PDO::PARAM_INT);
			$res->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                        $res->execute();
                        while($rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC)){
                               $detailArr[] = $rowSelectDetail;
                        }
                        return $detailArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
        public function countNotSentMails()
        {
                try{
			$status='N';
                        $sql = "SELECT count(*) CNT FROM $this->databaseName.DAILY_MATCHALERT_NOTIFICATION WHERE STATUS=:STATUS";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":STATUS",$status, PDO::PARAM_STR);
                        $res->execute();
                        while($rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC)){
                               $cnt = $rowSelectDetail['CNT'];
                        }
                        return $cnt;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
	public function truncateTable()
        {
                try{
                        $sql = "truncate table $this->databaseName.DAILY_MATCHALERT_NOTIFICATION";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
    
}
?>
