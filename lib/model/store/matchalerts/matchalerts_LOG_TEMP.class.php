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
}
?>

