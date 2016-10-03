
<?php
class MAIL_SHORTLISTED_PROFILES extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
        }

	/**
	  * 
	**/
	public function makeEntry($info)
	        {
	                try
	                {//print_r($pid);die;


							$sql = "INSERT IGNORE INTO  MAIL.SHORTLISTED_PROFILES (RECEIVER,USERS,COUNTS,DATE) VALUES(:PROFILEID,:USERSENDER,:COUNT,now())";
							$res = $this->db->prepare($sql);
				            $res->bindValue(":PROFILEID", $info['profileId'], PDO::PARAM_INT);
				            $res->bindValue(":USERSENDER",implode(',',$info['users']), PDO::PARAM_STR);
				            $res->bindValue(":COUNT", count($info['users']), PDO::PARAM_INT);
	                		$res->execute();    
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function UpdateMailer($pid,$mailStatus)
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "UPDATE MAIL.SHORTLISTED_PROFILES SET SENT=:STATUS WHERE RECEIVER=:PROFILEID";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			            $res->bindValue(":STATUS", $mailStatus, PDO::PARAM_INT);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }
	public function SelectMailerData($totalScript,$currentScript)
	        {
	                try
	                {
							$sql = "SELECT ID,RECEIVER,USERS,COUNTS,DATE FROM MAIL.SHORTLISTED_PROFILES WHERE SENT IS NULL AND RECEIVER%:totalScript=:currentScript";
							$res = $this->db->prepare($sql);
				           // $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
							$res->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
                			$res->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
	                		$res->execute();    
	                		while($row = $res->fetch(PDO::FETCH_ASSOC))
	                		{
								$profileMailData[$row['RECEIVER']][] =$row['USERS'];
								$profileMailData['COUNT'][]=$row['COUNTS'];
							}
							return $profileMailData;
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function EmptyMailer()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE MAIL.SHORTLISTED_PROFILES";
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
                        $sql = "SELECT count(1) as cnt,SENT FROM MAIL.SHORTLISTED_PROFILES group by SENT";
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
