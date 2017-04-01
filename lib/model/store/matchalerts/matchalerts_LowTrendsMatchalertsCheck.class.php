<?php

class matchalerts_LowTrendsMatchalertsCheck extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = $dbname?$dbname:"matchalerts_slave";
			parent::__construct($dbname);
	}
        
        
         /*this inserts a row with number of matches
         * @return - partition number
         */
         public function insertForProfile($profileid,$date,$logicLevel)
         {
            try
            {
                $sql="INSERT INTO matchalerts.LOW_TRENDS_MATCHALERTS_CHECK(PROFILEID,DATE,LOGICLEVEL) VALUES (:PROFILEID,:DATE,:LOGICLEVEL)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATE", $date, PDO::PARAM_STR);
                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $prep->bindValue(":LOGICLEVEL", $logicLevel, PDO::PARAM_INT);
                $prep->execute();
            }
            catch (PDOException $ex)
            {
                jsException::nonCriticalError($ex);
            }
        }

        // This function is used to get Low Count for profiles grouped by logic
        public function getLowCountGroupedByLogic($date)
        {            
            try
            {
                $sql="SELECT count( DISTINCT (PROFILEID) ) as CNT , LOGICLEVEL FROM matchalerts.`LOW_TRENDS_MATCHALERTS_CHECK` WHERE DATE >= :DATEVAL GROUP BY LOGICLEVEL";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATEVAL", $date, PDO::PARAM_INT);
                $prep->execute();               
                while ($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $resultArr[] = $row;          
                }              
                return $resultArr;
            }
            catch (PDOException $ex)
            {
                jsException::nonCriticalError($ex);
            }
        }

        /**
        * Empty The table
        */
        public function truncateTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE matchalerts.`LOW_TRENDS_MATCHALERTS_CHECK`";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
                        //add mail/sms
                        jsException::nonCriticalError($e);
                }
        }

        public function getLowCountGroupedByProfileIdLogic($date)
        {
            try
            {
                $sql="SELECT DISTINCT(PROFILEID) FROM matchalerts.`LOW_TRENDS_MATCHALERTS_CHECK` where DATE=:DATEVAL ";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":DATEVAL", $date, PDO::PARAM_INT);
                $prep->execute();               
                while ($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $resultArr[$row["PROFILEID"]] = $row["PROFILEID"];          
                }  
                return $resultArr;
            }
            catch (PDOException $ex)
            {
                jsException::nonCriticalError($ex);
            }
        }

        public function getZeroCountProfiles()
        {
            try
            {
                $sql = "SELECT COUNT(DISTINCT(a.PROFILEID)) AS TOTALCOUNT, 0 AS RECOMMENDCOUNT FROM matchalerts.`LOW_TRENDS_MATCHALERTS_CHECK` AS a LEFT JOIN matchalerts.`LOG_TEMP` l ON a.PROFILEID = l.RECEIVER WHERE l.RECEIVER IS NULL";
                $prep = $this->db->prepare($sql);                
                $prep->execute();
                while ($row = $prep->fetch(PDO::FETCH_ASSOC))
                {
                    $resultArr[] = $row;          
                }
                return $resultArr;
            }
            catch (PDOException $ex)
            {
                jsException::nonCriticalError($ex);
            }
        }
       
}