<?php
/* This class provided functions for new_matches_emails.LOG_TEMP table
 * @author : Reshu Rajput
 * @created : Jun 19, 2014
*/
class new_matches_emails_LOG extends TABLE
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
        public function insertFromLog_Temp($dailyCron=0)
        {
                try
                {
                        if($dailyCron == 1){
                                $sql="INSERT INTO new_matches_emails.LOG SELECT * FROM new_matches_emails.LOG_TEMP_DAILY";
                        }else{
                                $sql="INSERT INTO new_matches_emails.LOG SELECT * FROM new_matches_emails.LOG_TEMP";
                        }
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {       
                        SendMail::send_email("lavesh.rawat@gmail.com","FMA Insert Failed","insert into Log_Temp failed","lavesh.rawat@gmail.com");
                        throw new jsException($e);
                }

        }
        
         public function deleteEntriesBeforeDate($deleteDate)
        {
                try
                {
                      
                        $sql="DELETE FROM new_matches_emails.LOG WHERE DATE < :DELETEDATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":DELETEDATE", $deleteDate, PDO::PARAM_STR);
                        $res->execute();
                }
                catch (PDOException $e)
                {       
                        SendMail::send_email("lavesh.rawat@gmail.com","FMA delete Failed","delete from Log failed","lavesh.rawat@gmail.com");
                        throw new jsException($e);
                }

        }
        
        
}

?>
