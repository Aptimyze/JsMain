<?php
/* This class provided functions for matchalerts.MAILER table
 * @author : Ankit Shukla
*/
  
class PROFILE_DPP_REVIEW_MAILER_LOG extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	/**
        * Empty The table
        */
        public function truncateTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE PROFILE.DPP_REVIEW_MAILER_LOG";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
                        //add mail/sms
                        throw new jsException($e);
                }
        }
        
	/*
         * this function returns data for different sent status
         * @return - array of number of rows for different sent status
         */
	public function getMailCountForRange()
    	{
                try{
                        $sql = "SELECT count(1) as cnt,SENT FROM PROFILE.DPP_REVIEW_MAILER_LOG GROUP BY SENT";
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
        /*
         * insert entry for a receiver in the table
         * @param - profileid of receiver , sent status, date
         */
        public function insertDppReviewMailerEntry($pid,$sentStatus,$currentDate)
	{
		try
		{
	        $sql = "INSERT IGNORE INTO  PROFILE.DPP_REVIEW_MAILER_LOG (RECEIVER,SENT,DATE) VALUES(:PROFILEID,:SENT,:DATE)";
	        $res = $this->db->prepare($sql);
	        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
	        $res->bindValue(":SENT", $sentStatus, PDO::PARAM_STR);
                $res->bindValue(":DATE", $currentDate, PDO::PARAM_STR);
	        $res->execute();    
	    }
	    catch(PDOException $e)
	    {
	        throw new jsException($e);
	    }
	}

}
?>
