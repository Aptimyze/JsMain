<?php

class kundli_alert_LOG extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = "matchalerts_slave";
			parent::__construct($dbname);
	}

	public function getLatestPartitionRange($lastPartitionName){
            //get the range of the latest partition
            try{
		/*
                $sql = "SELECT PARTITION_DESCRIPTION FROM INFORMATION_SCHEMA.PARTITIONS WHERE TABLE_NAME =  'LOG' AND PARTITION_NAME =  :lastPartition";
		*/
		$sql = "SELECT MAX(PARTITION_DESCRIPTION) AS PARTITION_DESCRIPTION FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = 'kundli_alert' AND TABLE_NAME = 'LOG_NEW'";
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
         * this function drops the oldest partition and creates a new one with higher ranges
         * @param - lastPartitionNumber
         */
        
        public function replacePartitions($dropPartitionName,$createPartitionName,$newPartitionRange)
		{
          	//delete the oldest partition
            try
            {
            	$sql = "ALTER TABLE kundli_alert.LOG_NEW DROP PARTITION $dropPartitionName";
            	echo $sql."\n";
            	$prep = $this->db->prepare($sql);
            	$prep->execute();   
            }
            catch (PDOException $ex) 
            {
                throw new jsException($ex);
            }
          
          //create a new partition with range added by 15
          try
          {
          	$sql = "ALTER TABLE kundli_alert.LOG_NEW ADD PARTITION (PARTITION $createPartitionName VALUES LESS THAN (:RANGE))";
          	echo $sql."\n";
          	$prep = $this->db->prepare($sql);
          	$prep->bindValue(":RANGE", $newPartitionRange, PDO::PARAM_INT);
          	$prep->execute();   
            }
            catch (PDOException $ex) 
            {
                throw new jsException($ex);
            }
        }

        public function getDeDupedProfiles($profileId,$requiredDate)
        {
        	try
        	{
        		$sql = "SELECT USER FROM kundli_alert.LOG_NEW WHERE RECEIVER = :PROFILEID AND DATE > :REQUIREDDATE";
        		$prep = $this->db->prepare($sql);
          		$prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $prep->bindValue(":REQUIREDDATE", $requiredDate, PDO::PARAM_INT);
          		$prep->execute();
          		while($row = $prep->fetch(PDO::FETCH_ASSOC))
          		{
          			$resultArr[]=$row["USER"];
          		}
          		return $resultArr;
        	}
        	catch (PDOException $ex) 
            {
                throw new jsException($ex);
            }
        }

        public function insertDataInLogs($finalArr,$date,$profileId)
        {
        	if(count($finalArr)>1)
        	{
        		$i=1;
        		$count=1;
        		try
        		{    		
        			$sql.= "INSERT ignore INTO kundli_alert.LOG_NEW (RECEIVER,USER,DATE) VALUES ";

        			foreach($finalArr as $user=>$userId)
        			{        			
        				if($user!="SENT" && $i<=16 && in_array($user,kundliMatchAlertMailerEnums::$userArray) && $userId != 0)
        				{
        					$sql .= "(:RECEIVERID,:USER".$i.",:DATE), ";
        				}
        				$i++;        		        		
        			}

        			$sql = rtrim($sql,", ");
        			$pdoStatement = $this->db->prepare($sql);
        			foreach($finalArr as $user=>$userId)
        			{        			
        				if($user!="SENT" && $count<=16 && in_array($user,kundliMatchAlertMailerEnums::$userArray) && $userId != 0)
        				{
        					$pdoStatement->bindValue(":USER".$count, $userId, PDO::PARAM_INT);
        				}
        				$count++;        				        		       	
        			}
        			$pdoStatement->bindValue(":DATE", $date, PDO::PARAM_INT);
        			$pdoStatement->bindValue(":RECEIVERID", $profileId, PDO::PARAM_INT);
        			$pdoStatement->execute();

        		}
        		catch (PDOException $ex) 
        		{
        			throw new jsException($ex);
        		}
        	}
        	
        }
}
