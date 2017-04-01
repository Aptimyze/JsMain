<?php
class MAIL_ALTERNATE_EMAIL_MAILER extends TABLE
{
        public function __construct($dbname="newjs_master")
        {
		parent::__construct($dbname);
        }

	/**
	  * 
	**/
	public function makeEntry($receiver,$sent)
	        {
	                try
	                {//print_r($pid);die;


							$sql = "INSERT INTO  MAIL.ALTERNATE_EMAIL_VER_MAILER (RECEIVER,TIME,SENT) VALUES(:PROFILEID,now(),:SENT)";
							$res = $this->db->prepare($sql);
				            $res->bindValue(":PROFILEID", $receiver, PDO::PARAM_INT);
				            $res->bindValue(":SENT", $sent, PDO::PARAM_INT);
	                		$res->execute();    
	                }
	                catch(PDOException $e)
	                {
	                        return false;
	                }
	        }

	        public function UpdateMailer($pid,$mailStatus)
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "UPDATE MAIL.ALTERNATE_EMAIL_VER_MAILER SET SENT=:STATUS WHERE RECEIVER=:PROFILEID";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			            $res->bindValue(":STATUS", $mailStatus, PDO::PARAM_INT);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                       return false;
	                }
	        }
	        public function EmptyMailer()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE MAIL.ALTERNATE_EMAIL_VER_MAILER";
						$res = $this->db->prepare($sql);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        return false;
	                }
	        }

	public function getMailCountForRange()
    	{           
                try{    
                        $sql = "SELECT count(1) as cnt,SENT FROM MAIL.ALTERNATE_EMAIL_VER_MAILER group by SENT";
                        $res=$this->db->prepare($sql);
                        $res->execute();
			$total = 0;
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
				if($row['SENT']=='Y')
                        		$output['SENT'] = $row['cnt'];
				if($row['SENT']=='B')
                                        $output['BOUNCED'] = $row['cnt'];
				if($row['SENT']=='I')
                                        $output['INCOMPLETE'] = $row['cnt'];
				if($row['SENT']=='U')
                                        $output['UNSUBSCRIBE'] = $row['cnt'];
	                       	$total = $total+$row['cnt'];
			}
			$output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {  
                   return false;
                }

                return $output;
    
    	}
}
