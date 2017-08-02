<?php

class matchalerts_LOG extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = $dbname?$dbname:"matchalerts_slave";
			parent::__construct($dbname);
	}
	public function getMatchAlertCount($profileId, $skippedProfile = '', $limit = '')
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT COUNT(USER) as CNT,CASE DATE WHEN ( SELECT MAX( DATE ) FROM matchalerts.LOG WHERE RECEIVER =:RECEIVER)THEN 1 ELSE 0 END AS TIME1 from matchalerts.LOG where RECEIVER = :RECEIVER";
				if ($skippedProfile) {
					$sql   = $sql . " AND USER NOT IN (";
					$count = 1;
					foreach ($skippedProfile as $key1 => $value1) {
						$str                       = $str . ":VALUE" . $count . ",";
						$bindArr["VALUE" . $count] = $value1;
						$count++;
					}
					$str = substr($str, 0, -1);
					//if not in blank
					if($count==1)
						$str=136580;
					$str = $str . ")";
					$sql = $sql . $str;
				}
				$sql = $sql." GROUP BY TIME1";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
				if (isset($bindArr))
					foreach ($bindArr as $k => $v)
						$prep->bindValue($k, $v);
				$prep->execute();
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
					if($row["TIME1"] == 1)
						$result["NEW"] = $row['CNT'];
					$result["TOTAL"] +=$row['CNT'];
				}
				return $result;
			}
			catch (PDOException $e) {
				//throw new jsException($e);
				jsException::log("getMatchAlertCount-->.$sql".$e);
				return 0;
			}
		} else
			return 0;
	}

	/*
	This function is used to get the matches sent to a profile
	@param - receiver profileid
	@return - array of matches
	*/
	public function getProfilesSentInMatchAlerts($profileId,$seperator="")
	{
		if(JsConstants::$alertServerEnable &&  $this->db)
                {
			if(!$profileId)
				throw new jsException("","PROFILEID IS BLANK IN getProfilesSentInMatchAlerts() of matchalerts_LOG.class.php");

			try
			{
				$sql = "SELECT USER FROM matchalerts.LOG WHERE RECEIVER = :RECEIVER";
				$prep = $this->db->prepare($sql);
                                $prep->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
				$prep->execute();
                                while($row = $prep->fetch(PDO::FETCH_ASSOC))
                                {
					if($seperator == 'spaceSeperator')
		                                $result.= $row["USER"]." ";
					else
	                                        $result[] = $row["USER"];
                                }
			}
			catch (PDOException $e)
                        {
                                //throw new jsException($e);
				jsException::log("getProfilesSentInMatchAlerts-->.$sql".$e);
				return 0;
                        }
			return $result;
		}
		else
		{
			return 0;
		}
	}

	/*This function is added by Reshu Rajput
        *This function is used to get logic level for all the users corresponding to a receiver
        *@param receiverId : recieved profile id
        *@param users :  array of users
        *@result result : array of users with corresponding logic level
        */
        public function getLogicLevelFromLogTemp($receiverId,$users)
        {
                try
                {
                        if(!is_array($users) && !$receiverId)
                                throw new jsException("no receiverId or user ids passed in getLogicLevelFromLogTemp function in matchalerts_LOG.class.php");
                        for($i=0;$i<sizeof($users) ;$i++)
                                $pids[]=":PID$i";
                      
                        $sql="SELECT USER,LOGICLEVEL FROM matchalerts.LOG_TEMP WHERE RECEIVER = :RECEIVERID AND USER IN (".implode(",",$pids).")";
                        $res = $this->db->prepare($sql);
                        for($i=0;$i<sizeof($users) ;$i++)
                        {
                                $res->bindValue(":PID$i", $users[$i], PDO::PARAM_INT);
                        }
                        $res->bindValue(":RECEIVERID", $receiverId, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                                $result[$row["USER"]] = $row["LOGICLEVEL"];
                        return $result;
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	public function getMatchAlertProfile($profileId, $skippedProfile = '', $limit = '',$date='')
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT USER as PROFILEID, DATE as TIME from matchalerts.LOG where RECEIVER = :RECEIVER";
				if ($skippedProfile) {
					$sql   = $sql . " AND USER NOT IN (";
					$count = 1;
					foreach ($skippedProfile as $key1 => $value1) {
						$str                       = $str . ":VALUE" . $count . ",";
						$bindArr["VALUE" . $count] = $value1;
						$count++;
					}
					$str = substr($str, 0, -1);
					//if not in blank
                                        if($count==1)
                                                $str=136580;
					$str = $str . ")";
					$sql = $sql . $str;
				}
				$sql = $sql." ORDER BY TIME DESC,PROFILEID DESC";
				if($limit)
					$sql = $sql." LIMIT ".$limit;
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
				if (isset($bindArr))
					foreach ($bindArr as $k => $v)
						$prep->bindValue($k, $v);
				$prep->execute();
				$maxDate = 0;
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
					if($date)
					{
						if($maxDate<= $row["TIME"])
						{
							$maxDate = $row["TIME"];
							$result[$row["PROFILEID"]] = $row;
						}
						else
							break;
					}
					else
						$result[$row["PROFILEID"]] = $row;
				}
			}
			catch (PDOException $e) {
				//throw new jsException($e);
				jsException::log("getMatchAlertProfile-->.$sql".$e);
				return;
			}
		}
		return $result;
	}

	/**
	* This function will return matchalerts profiles based on profileid and/or dateGreaterThanCondition.
	*/
	public function getMatchAlertProfiles($profileId,$dateGreaterThanCondition="")
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT SQL_CACHE USER,DATE from matchalerts.LOG where RECEIVER = :RECEIVER";
				if($dateGreaterThanCondition)
					$sql.=" AND DATE>:DATE";
				$prep = $this->db->prepare($sql);

				$prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
				if($dateGreaterThanCondition)
					$prep->bindValue(":DATE", $dateGreaterThanCondition, PDO::PARAM_INT);
				$prep->execute();
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) 
				{
					$arr[$row["USER"]] = $row["DATE"];
				}
			}
			catch (PDOException $e) {
				jsException::log("getMatchAlertProfiles-->.$sql".$e);
				return;
				//throw new jsException($e);
			}
		}
		return $arr;
	}


	public function getMatchAlertProfileCount($profileId, $skippedProfile,$date)
	{
		if (JsConstants::$alertServerEnable &&  $this->db) {
			try {
				$sql = "SELECT count(*) as count from matchalerts.LOG where RECEIVER = :RECEIVER AND DATE=:DATE ";
				$sql   = $sql . " AND USER NOT IN (";
				$count = 1;
				foreach ($skippedProfile as $key1 => $value1) {
					$str                       = $str . ":VALUE" . $count . ",";
					$bindArr["VALUE" . $count] = $value1;
					$count++;
				}
				$str = substr($str, 0, -1);
				//if not in blank
                                        if($count==1)
                                                $str=136580;
				$str = $str . ")";
				$sql = $sql . $str;
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
				$prep->bindValue(":DATE", $date, PDO::PARAM_STR);
				if (isset($bindArr))
					foreach ($bindArr as $k => $v)
						$prep->bindValue($k, $v);
				$prep->execute();
				$maxDate = 0;
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
					$result = $row['count'];
				}
			}
			catch (PDOException $e) {
				//throw new jsException($e);
				jsException::log("getMatchAlertProfile-->.$sql".$e);
				return;
			}
		}
		return $result;
	}

        public function getMatchAlertProfileForNotification($profileId, $skippedProfile,$date)
        {
                if (JsConstants::$alertServerEnable &&  $this->db) {
                        try {
                                $sql = "SELECT USER from matchalerts.LOG where RECEIVER = :RECEIVER AND DATE=:DATE ";
                                $sql   = $sql . " AND USER NOT IN (";
                                $count = 1;
                                foreach ($skippedProfile as $key1 => $value1) {
                                        $str                       = $str . ":VALUE" . $count . ",";
                                        $bindArr["VALUE" . $count] = $value1;
                                        $count++;
                                }
                                $str = substr($str, 0, -1);
                                //if not in blank
                                        if($count==1)
                                                $str=136580;
                                $str = $str . ")";
                                $sql = $sql . $str;
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":RECEIVER", $profileId, PDO::PARAM_INT);
                                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                                if (isset($bindArr))
                                        foreach ($bindArr as $k => $v)
                                                $prep->bindValue($k, $v);
                                $prep->execute();
                                $maxDate = 0;
                                while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                                        $result[] = $row['USER'];
                                }
                        }
                        catch (PDOException $e) {
                                //throw new jsException($e);
                                jsException::log("getMatchAlertProfile-->.$sql".$e);
                                return;
                        }
                }
                return $result;
        }
        
        /*
         * this function drops the oldest partition and creates a new one with higher ranges
         * @param - lastPartitionNumber
         */
        
        public function replacePartitions($dropPartitionName,$createPartitionName,$newPartitionRange)
	{
          //delete the oldest partition
            try{
              $sql = "ALTER TABLE matchalerts.LOG DROP PARTITION $dropPartitionName";
echo $sql."\n";
              $prep = $this->db->prepare($sql);
              $prep->execute();   
            }
            catch (PDOException $ex) {
                throw new jsException($ex);
            }
          
          //create a new partition with range added by 15
          try{
              $sql = "ALTER TABLE matchalerts.LOG ADD PARTITION (PARTITION $createPartitionName VALUES LESS THAN (:RANGE))";
echo $sql."\n";
              $prep = $this->db->prepare($sql);
              $prep->bindValue(":RANGE", $newPartitionRange, PDO::PARAM_INT);
              $prep->execute();   
            }
            catch (PDOException $ex) {
                throw new jsException($ex);
            }
        }
        public function getLatestPartitionRange($lastPartitionName){
            //get the range of the latest partition
            try{
		/*
                $sql = "SELECT PARTITION_DESCRIPTION FROM INFORMATION_SCHEMA.PARTITIONS WHERE TABLE_NAME =  'LOG' AND PARTITION_NAME =  :lastPartition";
		*/
		$sql = "SELECT MAX(PARTITION_DESCRIPTION) AS PARTITION_DESCRIPTION FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = 'matchalerts' AND TABLE_NAME = 'LOG'";
                $prep = $this->db->prepare($sql);
		/*
                $prep->bindValue(":lastPartition", $lastPartitionName, PDO::PARAM_STR);
		*/
                $prep->execute();
                $row = $prep->fetch(PDO::FETCH_ASSOC);
                $lastPartitionRange = $row['PARTITION_DESCRIPTION'];  
                return $lastPartitionRange;
            }
            catch (PDOException $ex) {
                throw new jsException($ex);
            }
        }
        /*
         * Get minimum partition Value
         */
        public function getLastPartitionRange(){
            //get the range of the latest partition
            try{
		$sql = "SELECT MIN(PARTITION_DESCRIPTION) AS PARTITION_DESCRIPTION FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = 'matchalerts' AND TABLE_NAME = 'LOG'";
                $prep = $this->db->prepare($sql);
                $prep->execute();
                $row = $prep->fetch(PDO::FETCH_ASSOC);
                $lastPartitionRange = $row['PARTITION_DESCRIPTION'];  
                return $lastPartitionRange;
            }
            catch (PDOException $ex) {
                throw new jsException($ex);
            }
        }
        public function insertLogRecords($receiverId, $userIds, $LogicLevel){
          $date=MailerConfigVariables::getNoOfDays();
          
          $sql_log="INSERT INTO matchalerts.LOG (RECEIVER,USER,DATE,LOGICLEVEL) VALUES ";
          $userCounter = 1;
          foreach($userIds as $userId){
            $sql_log .= "(:RECEIVER_ID,:USER_ID".$userCounter.",:DATE_VALUE,:LOGIC_LEVEL),";
            $userCounter++;
          }
          $sql_log = rtrim($sql_log, ',');
          
          $res = $this->db->prepare($sql_log);
          $res->bindValue(":RECEIVER_ID", $receiverId, PDO::PARAM_INT);
          $res->bindValue(":DATE_VALUE", $date, PDO::PARAM_INT);
          $res->bindValue(":LOGIC_LEVEL",$LogicLevel,PDO::PARAM_INT);
          $userCounter = 1;
          foreach($userIds as $userId){
            $res->bindValue(":USER_ID".$userCounter,$userId,PDO::PARAM_INT);
            $userCounter++;
          }
          $res->execute();
        }

        public function getMatchAlertCountForBackend($profileId)
        {
        	try
        	{
        		$sql = "SELECT COUNT(*) as COUNT from matchalerts.LOG where RECEIVER = :PROFILEID";

        		$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
				$prep->execute();
				while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
					$result=$row;
				}				
				return $result;

        	}
        	catch (PDOException $ex) 
        	{
                jsException::nonCriticalError($ex);
            }
        }
}
?>
