<?php
class MAIL_contactViewers extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
        }

	/**
	  * 
	**/
	public function InsertMailerCV($pid,$sent)
	        {
	                try
	                {//print_r($pid);die;
							$sql = "INSERT IGNORE INTO  MAIL.contactViewersMail (`RECEIVER`,`DATE`,`SENT`) VALUES(:PROFILEID,now(),:SENT)";
							$res = $this->db->prepare($sql);
				            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
				            $res->bindValue(":SENT", $sent, PDO::PARAM_STR);
				            $res->execute();    
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }


        public function EmptyMailerCV()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE MAIL.contactViewersMail";
						$res = $this->db->prepare($sql);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	public function getMailCountForRange()
                {
                        try{
                                $sql = "SELECT count(1) as cnt,SENT FROM MAIL.contactViewersMail group by SENT";
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
                           throw new jsException($e);
                        }
                        return $output;
                }
}
