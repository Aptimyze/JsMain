<?php
class MAIL_FilterEOI extends TABLE
{
	public function __construct($dbname="")
	{
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
	}

	/**
	  * 
	**/
	public function InsertFilterEOI($pid,$usercode,$count)
	{
		try
		{
	        $sql = "INSERT IGNORE INTO  MAIL.FilterEOI (RECEIVER,SENDER,COUNTS,DATE) VALUES(:PROFILEID,:USERSENDER,:COUNT,now())";
	        $res = $this->db->prepare($sql);
	        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
	        $res->bindValue(":USERSENDER", $usercode, PDO::PARAM_STR);
	        $res->bindValue(":COUNT", $count, PDO::PARAM_INT);
	        $res->execute();    
	    }
	    catch(PDOException $e)
	    {
	        throw new jsException($e);
	    }
	}


	public function UpdateFilterEOI($pid,$status)
	        {
	                try
	                {
	                	
						$sql = "UPDATE MAIL.FilterEOI SET SENT=:STATUS WHERE RECEIVER=:PROFILEID";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
			            $res->bindValue(":STATUS", $status, PDO::PARAM_INT);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

public function SelectFilterEOI($totalScript,$currentScript)
	        {
	                try
	                {
							$sql = "SELECT ID,RECEIVER,SENDER AS USERS,COUNTS,DATE FROM MAIL.FilterEOI WHERE SENT IS NULL AND RECEIVER%:totalScript=:currentScript";
							$res = $this->db->prepare($sql);
				           // $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
							$res->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
                			$res->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
	                		$res->execute();    
	                		while($row = $res->fetch(PDO::FETCH_ASSOC))
	                		{
								$profileMailData[$row['RECEIVER']] =$row['USERS'];
								$profileMailData['COUNT'][]=$row['COUNTS'];
							}
							return $profileMailData;
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function EmptyFilterEOI()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE MAIL.FilterEOI";
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
				$sql = "SELECT count(1) as cnt,SENT FROM MAIL.FilterEOI group by SENT";
				$res=$this->db->prepare($sql);
				$res->execute();
				$total = 0;
				while($row = $res->fetch(PDO::FETCH_ASSOC))
				{
					if($row['SENT']=='Y')
						$output['SENT'] = $row['cnt'];
					if($row['SENT']=='B')
						$output['HARD_BOUNCED'] = $row['cnt'];
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
