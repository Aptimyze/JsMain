<?php

class matchalerts_LOG_TEMP extends TABLE
{
	public function __construct($dbname="")
	{
			$dbname = $dbname?$dbname:"matchalerts_slave";
			parent::__construct($dbname);
	}

	/**
        * Empty The table
        */
        public function truncateTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE matchalerts.LOG_TEMP";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
                        //add mail/sms
                        throw new jsException($e);
                }
        }

	public function getMatchAlertCount()
	{
               $sql = "SELECT COUNT(*) AS CNT from matchalerts.LOG_TEMP"; 
               $prep = $this->db->prepare($sql);
               $prep->execute();
               
               while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                        $COUNT=$row['CNT'];
		}
               return $COUNT;
        }
        public function insertLogRecords($receiverId, $userIds, $LogicLevel){
          $date=MailerConfigVariables::getNoOfDays();
          
          $sql_log="INSERT INTO matchalerts.LOG_TEMP (RECEIVER,USER,DATE,LOGICLEVEL) VALUES ";
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
        /*
	This function is used to get the profile count on the basis of logic level
	@param - receiver profileid
	@return - array of matches
	*/
	public function getProfilesCountOfLogicLevel($profileId,$logicLevel)
	{
		if(JsConstants::$alertServerEnable &&  $this->db)
                {
			if(!$profileId)
				throw new jsException("","PROFILEID IS BLANK IN getProfilesSentInMatchAlerts() of matchalerts_LOG.class.php");

			try
			{
				$sql = "SELECT count(*) as CNT FROM matchalerts.LOG_TEMP WHERE RECEIVER = :RECEIVER AND LOGICLEVEL = :LOGICLEVEL";
				$prep = $this->db->prepare($sql);
                                $prep->bindValue(":RECEIVER",$profileId,PDO::PARAM_INT);
                                $prep->bindValue(":LOGICLEVEL",$logicLevel,PDO::PARAM_INT);
				$prep->execute();
                                $row = $prep->fetch(PDO::FETCH_ASSOC);
                                if($row){
                                        return $row['CNT'];
                                }
                                return 0;
			}
			catch (PDOException $e)
                        {
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

  public function getCountGroupedByLogic()
  {
    try
    {
      $sql = "SELECT count( DISTINCT (RECEIVER) ) as CNT, LOGICLEVEL FROM matchalerts.`LOG_TEMP` GROUP BY LOGICLEVEL; "; 
               $prep = $this->db->prepare($sql);
               $prep->execute();               
               while ($row = $prep->fetch(PDO::FETCH_ASSOC))
              {
                $resultArr[] = $row;          
              }              
               return $resultArr;
    }
    catch (PDOException $e)
    {
                        //add mail/sms
      jsException::nonCriticalError($e);
    }
  }

  public function getCountGroupedByLogicAndRecommendation()
  {
    try
    {
      $sql = "SELECT COUNT(DISTINCT(RECEIVER)) as PeopleCount , LOGICLEVEL, RecCount
      FROM (

        SELECT COUNT( * ) AS RecCount, LOGICLEVEL, RECEIVER
        FROM  matchalerts.`LOG_TEMP` 
        GROUP BY LOGICLEVEL, RECEIVER
      ) AS tablename
GROUP BY LOGICLEVEL, RecCount"; 
               $prep = $this->db->prepare($sql);
               $prep->execute();               
               while ($row = $prep->fetch(PDO::FETCH_ASSOC))
               {
                $resultArr[] = $row;          
              }              
              return $resultArr;
    }
    catch (PDOException $e)
    {
                        //add mail/sms
      jsException::nonCriticalError($e);
    }
  }

  public function getDate()
  {
    try
    {
      $sql = "SELECT DATE from matchalerts.`LOG_TEMP` ORDER BY DATE LIMIT 1";
      $prep = $this->db->prepare($sql);
      $prep->execute();
      while ($row = $prep->fetch(PDO::FETCH_ASSOC))
      {
        $resultArr = $row;          
      }              
      return $resultArr["DATE"]; 

    }
    catch (PDOException $e)
    {
                        //add mail/sms
      jsException::nonCriticalError($e);
    }
  }
  public function getTotalCountGroupedByLogicAndReceiver()
  {
    try
    {
      $sql = "SELECT COUNT(RECEIVER) as TOTALCOUNT, RECOMMENDCOUNT FROM (SELECT DISTINCT (RECEIVER), COUNT( * ) AS RECOMMENDCOUNT FROM matchalerts.`LOG_TEMP` 
              GROUP BY RECEIVER) as a GROUP BY RECOMMENDCOUNT";
      $prep = $this->db->prepare($sql);
      $prep->execute();
      while ($row = $prep->fetch(PDO::FETCH_ASSOC))
      {
        $resultArr[] = $row;          
      }

      return $resultArr;
    }
    catch (PDOException $e)
    {
                        //add mail/sms
      jsException::nonCriticalError($e);
    }
  }
}
?>

