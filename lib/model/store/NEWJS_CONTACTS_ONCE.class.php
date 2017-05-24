<?php

class NEWJS_CONTACTS_ONCE extends TABLE
{
    
    public function __construct($dbname = "")
    {
        parent::__construct($dbname);
    }
    
    public function insert($contactId, $sender_profileid, $receiver_profileid, $draft, $status)
    {
        
        try {
            
            $sql = "INSERT IGNORE INTO newjs.CONTACTS_ONCE(CONTACTID, SENDER, RECEIVER, TIME, MESSAGE, SENT) VALUES (:CONTACTID, :SENDER, :RECEIVER, :TIME, :MESSAGE, :STATUS)";
            
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":CONTACTID", $contactId, PDO::PARAM_INT);
            $prep->bindValue(":SENDER", $sender_profileid, PDO::PARAM_INT);
            $prep->bindValue(":RECEIVER", $receiver_profileid, PDO::PARAM_INT);
            $prep->bindValue(":TIME", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $prep->bindValue(":MESSAGE", $draft, PDO::PARAM_STR);
            $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function update($sender_profileid, $receiver_profileid, $status)
    {
        
        try {
            
            $sql = "UPDATE newjs.CONTACTS_ONCE SET SENT=:STATUS WHERE SENDER=:SENDER AND RECEIVER=:RECEIVER";
            
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SENDER", $sender_profileid, PDO::PARAM_INT);
            $prep->bindValue(":RECEIVER", $receiver_profileid, PDO::PARAM_INT);
            $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getUnsentContacts($limit,$current)
    {
        try {
            $sql  = "SELECT SENDER, RECEIVER, MESSAGE,CONTACTID FROM `CONTACTS_ONCE` WHERE SENT = 'N' AND RECEIVER%".$limit." = ".$current." GROUP BY  'RECEIVER' ,CONTACTID ORDER BY RECEIVER";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            return $result;
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    public function updateContactsOnce($receiver,$status)
    {
        try {
            $sql  = "UPDATE `CONTACTS_ONCE` SET SENT = :STATUS WHERE RECEIVER = :RECEIVER";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":RECEIVER", $receiver, PDO::PARAM_INT);
             $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
  
      public function updateMessage($contactId,$message)
    {
        if(!$contactId) return;
        try {
            $sql  = "UPDATE `CONTACTS_ONCE` SET `MESSAGE` = :MESSAGE WHERE CONTACTID = :CONTACTID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":MESSAGE", $message, PDO::PARAM_STR);
            $prep->bindValue(":CONTACTID", $contactId, PDO::PARAM_INT);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
  

    public function updateUnSentContactsOnce($notSendContactsId)
    {
        $contactId = implode("','", $notSendContactsId);
        try {
            $sql  = "UPDATE `CONTACTS_ONCE` SET SENT = 'Y' WHERE CONTACTID IN ('" . $contactId . "')";
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    //To delete enteries from contacts_once whom mailers have been sent from cronSendEoiEmailTask;
	public function delete()
    {
        
        try {
            
            $sql ="DELETE FROM newjs.CONTACTS_ONCE WHERE SENT!='N'";
            
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
    
    public function getContactOnceInfoOfProfiles($profileid1,$profileid2)
    {
		if(!$profileid1||!$profileid2)
			throw new jsException("","profileid is not specified in getContactsDetails() OF newjs_MESSAGE_LOG.class.php");               
		try{ 
			$sql = "SELECT * FROM newjs.CONTACTS_ONCE WHERE (SENDER = :PROFILEID1 AND RECEIVER = :PROFILEID2) OR (RECEIVER = :PROFILEID1 AND SENDER =:PROFILEID2) ORDER BY TIME DESC LIMIT 1";
			$res=$this->db->prepare($sql);
			$res->bindValue("PROFILEID1",$profileid1,PDO::PARAM_INT);
			$res->bindValue("PROFILEID2",$profileid2,PDO::PARAM_INT);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output = $row;
			}
		}
		catch(PDOException $e)
		{
		   throw new jsException($e);
		}
		return $output;

    }

    public function getMailCountForRange()
    {
                try{
                        $sql = "SELECT count(1) as cnt,SENT FROM newjs.CONTACTS_ONCE GROUP BY SENDER, SENT";
                        $res=$this->db->prepare($sql);
                        
                        $res->execute();
			$total = 0;
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				if($row['SENT']=='Y')
					$output['SENT'] = $output['SENT'] + 1;
				if($row['SENT']=='B')
					$output['BOUNCED'] = $output['BOUNCED'] + 1;
				if($row['SENT']=='I')
					$output['INCOMPLETE'] = $output['INCOMPLETE'] + 1;
				if($row['SENT']=='U')
					$output['UNSUBSCRIBE']  =$output['UNSUBSCRIBE'] + 1;
				$total = $total+1;
			}
			$output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;

    }
    
    
   //To delete enteries from contacts_once whom mailers have been sent from cronSendEoiEmailTask;
	public function deleteYesterData()
    {
        
        try {
            $todayDate=date('Y-m-d');
            $sql ="DELETE FROM newjs.CONTACTS_ONCE WHERE SENT!='N' AND DATE(`TIME`)<'$todayDate'";
            
            $prep = $this->db->prepare($sql);
            $prep->execute();
        }
        catch (PDOException $e) {
            throw new jsException($e);
        }
    }
     public function getCountOfSentMailsToday($receiver)
    {
		if(!$receiver)
			throw new jsException("","receiver is not specified in getContactsDetails() OF newjs_MESSAGE_LOG.class.php");               
		try{    
                        $todayDate=date('Y-m-d');
			$sql = "SELECT count(*) as CNT FROM newjs.CONTACTS_ONCE WHERE RECEIVER = :PROFILEID1 AND DATE(`TIME`)='$todayDate' AND SENT='Y'";
			$res=$this->db->prepare($sql);
			$res->bindValue(":PROFILEID1",$receiver,PDO::PARAM_INT);
			$res->execute();
			if($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output = $row;
			}
		}
		catch(PDOException $e)
		{
		   throw new jsException($e);
		}
		return $output['CNT'];

    }

    
    
    
}
