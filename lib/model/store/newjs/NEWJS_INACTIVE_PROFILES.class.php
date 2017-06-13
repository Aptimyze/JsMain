<?php
class NEWJS_INACTIVE_PROFILES extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
        }

	/**
	  * 
	**/
 		public function ProfilesInactivated ($interval)
        {
                try
                {

                	$sql = "SELECT PROFILEID from newjs.JPROFILE WHERE DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME DAY) AND activatedKey ='1' AND ACTIVATED = 'Y'";
                	$prep = $this->db->prepare($sql);
                	$prep->bindValue(":INTERVALTIME", $interval, PDO::PARAM_INT);            	
                	$prep->execute();
                	while($row = $prep->fetch(PDO::FETCH_ASSOC))
						$profilemail[] =$row['PROFILEID'];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profilemail;
        }



        public function ProfilesInactivatedSecond ($interval)
        {
                try
                {

                	$sql = "SELECT PROFILEID from newjs.JPROFILE WHERE (DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME DAY) OR DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME1 DAY) OR DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME2 DAY) OR DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME3 DAY) OR DATE(LAST_LOGIN_DT) = DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME4 DAY))  AND activatedKey ='1' AND ACTIVATED = 'Y'";
                	$prep = $this->db->prepare($sql);
                	$prep->bindValue(":INTERVALTIME", $interval, PDO::PARAM_INT);
                	$prep->bindValue(":INTERVALTIME1", $interval+15, PDO::PARAM_INT);
                	$prep->bindValue(":INTERVALTIME2", $interval+30, PDO::PARAM_INT);
                	$prep->bindValue(":INTERVALTIME3", $interval+45, PDO::PARAM_INT);
                	$prep->bindValue(":INTERVALTIME4", $interval+75, PDO::PARAM_INT);
                	//$prep->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
                	//$prep->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);                	
                	$prep->execute();
                	while($row = $prep->fetch(PDO::FETCH_ASSOC))
						$profilemail[] =$row['PROFILEID'];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profilemail;
        }





        public function ProfilesInactivatedOneTime ($interval,$OneTimeInterval)
        {
                try
                {

                	$sql = "SELECT PROFILEID from newjs.JPROFILE WHERE DATE(LAST_LOGIN_DT) <= DATE_SUB(CURDATE(), INTERVAL :INTERVALTIME DAY) AND DATE(LAST_LOGIN_DT) > DATE_SUB(CURDATE(), INTERVAL :OneTimeInterval DAY) AND activatedKey ='1' AND ACTIVATED = 'Y'";
                	$prep = $this->db->prepare($sql);
                	$prep->bindValue(":INTERVALTIME", $interval, PDO::PARAM_INT);
                	$prep->bindValue(":OneTimeInterval", $OneTimeInterval, PDO::PARAM_INT);               	
                	$prep->execute();
                	while($row = $prep->fetch(PDO::FETCH_ASSOC))
						$profilemail[] =$row['PROFILEID'];
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profilemail;
        }


         public function InsertStatusAlert ($profileIdMailSent,$interval)
        {
                try
                {//print_r($profileIdMailSent);die;
                	$sqlPart="";
                	$status="NULL";
					$sql = "INSERT IGNORE INTO `INACTIVE_PROFILES` (PROFILEID,STATUS,TIME_INTERVAL) VALUES ";
					foreach($profileIdMailSent as $key=>$value)
					{
						if($sqlPart!='')
							$sqlPart.=",";
						$sqlPart.= "(:PROFILEID".$key.",:STATUS".$key.",:INTERVAL".$key.")";
					}
				$sql.=$sqlPart;
				$res = $this->db->prepare($sql);
				foreach($profileIdMailSent as $k=>$v)
				{
					$res->bindValue(":PROFILEID".$k,$v,PDO::PARAM_INT);
					$res->bindValue(":STATUS".$k,$status,PDO::PARAM_STR);
					$res->bindValue(":INTERVAL".$k,$interval,PDO::PARAM_INT);
				}
                		$res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

        public function UpdateStatusIncomplete($profileIdMailSent,$status)
        {
                try
                {
                	//echo "string";
                	//$status="Y";
						$sql = "UPDATE `INACTIVE_PROFILES` SET STATUS = :STATUS WHERE PROFILEID= :PROFILEID";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $profileIdMailSent, PDO::PARAM_INT);
			             $res->bindValue(":STATUS", $status, PDO::PARAM_STR);
                		$res->execute();    
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }


        public function SelectProfilesInactivated($totalScript,$currentScript)
	        {
	                try
	                {
							$sql = "SELECT PROFILEID,TIME_INTERVAL FROM newjs.INACTIVE_PROFILES WHERE STATUS is NULL AND PROFILEID%:totalScript=:currentScript";
							$res = $this->db->prepare($sql);
				           // $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
							$res->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
                			$res->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
	                		$res->execute();    
	                		while($row = $res->fetch(PDO::FETCH_ASSOC))
	                		{
								//$profileMailData[$row['PROFILEID']] =$row['EMAIL'];
								//$profileMailData[] =$row['PROFILEID'];
								$profileMailData[$row['PROFILEID']] =$row['TIME_INTERVAL'];
							}
							return $profileMailData;
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function EmptyIncomplete()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE newjs.INACTIVE_PROFILES";
						$res = $this->db->prepare($sql);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

		public function getMailCountForRange($timeInterval)
        	{
	                try{
        	                $sql = "SELECT COUNT(1) AS cnt,STATUS FROM newjs.INACTIVE_PROFILES WHERE TIME_INTERVAL=:timeInterval GROUP BY STATUS";
                	        $res=$this->db->prepare($sql);
				$res->bindValue("timeInterval", $timeInterval, PDO::PARAM_INT);
	                        $res->execute();
        	                $total = 0;
                	        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        	{
                                	if($row['STATUS']=='Y')
                                        	$output['SENT'] = $row['cnt'];
	                                if($row['STATUS']=='B')
        	                                $output['BOUNCED'] = $row['cnt'];
                	                if($row['STATUS']=='I')
                        	                $output['INCOMPLETE'] = $row['cnt'];
                                	if($row['STATUS']=='U')
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
