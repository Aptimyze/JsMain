<?php

/* This class provided functions for new_matches_emails.LOG_TEMP table
 * @author : Ankit Shukla
 * @created : Jun 19, 2014
*/
class new_matches_emails_LOG_TEMP extends TABLE
{

	/* This will connect to matchalert slave by default*/

	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}
        /*This function is used to get logic level for all the users corresponding to a receiver
        *@param receiverId : recieved profile id
        *@param users :  array of users
        *@result result : array of users with corresponding logic level
        */
        public function getLogicLevelFromLogTemp($receiverId,$users)
        {
                try
                {
                        if(!is_array($users) && !$receiverId)
                                throw new jsException("no receiverId or user ids passed in getLogicLevelFromLogTemp function in new_matches_emails_LOG.class.php");
                        for($i=0;$i<sizeof($users) ;$i++)
                                $pids[]=":PID$i";
                      
                        $sql="SELECT USER,LOGICLEVEL FROM new_matches_emails.LOG_TEMP WHERE RECEIVER = :RECEIVERID AND USER IN (".implode(",",$pids).")";
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
        
        public function truncateTable($dailyCron=0)
        {
                try
                {
                        if($dailyCron == 1){
                                $sql="TRUNCATE TABLE new_matches_emails.LOG_TEMP_DAILY";
                        }else{
                                $sql="TRUNCATE TABLE new_matches_emails.LOG_TEMP";
                        }
                        $res = $this->db->prepare($sql);
                        $res->execute();
		        return $result;
                }
                catch (PDOException $e)
                {
                        SendMail::send_email("lavesh.rawat@gmail.com","FMA truncate Failed","truncate Log_Temp failed","lavesh.rawat@gmail.com");
                        throw new jsException($e);
                }

        }
        
        public function insertLogRecords($receiverId, $userIds, $LogicLevel,$dailyCron=0){
          $date=MailerConfigVariables::getNoOfDays();
          if($dailyCron == 1){
                $sql_log="INSERT INTO new_matches_emails.LOG_TEMP_DAILY (RECEIVER,USER,DATE,LOGICLEVEL) VALUES ";
          }else{
                $sql_log="INSERT INTO new_matches_emails.LOG_TEMP (RECEIVER,USER,DATE,LOGICLEVEL) VALUES ";
          }
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

