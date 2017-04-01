<?php

/* This class provided count of new_matches_emails.RECEIVER where sent is N
 * @author : Akash Kumar
 * @created : Sep 16, 2014
*/
  
class new_matches_emails_RECEIVER  extends TABLE
{
	/* This will connect to matchalert slave by default*/
	public function __construct($dbname="")
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}
	
	public function getCountWaiting()
	{
		try 
		{
			$sql= " SELECT COUNT(*) AS WAITING FROM `new_matches_emails`.`RECEIVER` WHERE SENT = 'N'";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			$row = $prep->fetch(PDO::FETCH_ASSOC);
		
			return $row["WAITING"];			
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        
        public function truncateTable()
	{
		try 
		{
			$sql= "TRUNCATE TABLE new_matches_emails.RECEIVER";
			$prep = $this->db->prepare($sql);
			$prep->execute();
		}
		catch (PDOException $e)
		{
                        SendMail::send_email("lavesh.rawat@gmail.com","FMA truncate Failed","truncate RECEIVER failed","lavesh.rawat@gmail.com");
			throw new jsException($e);
		}
	}
        
        public function insertValuesFromJprofileAndJprofileAlerts($sortDate,$entryDate,$loginDate)
	{
		try 
		{
			// All parameters are in date time format.
			$sql= " INSERT INTO  new_matches_emails.RECEIVER(PROFILEID,SENT) SELECT J.PROFILEID AS PROFILEID,'N' FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_ALERTS JA ON J.PROFILEID = JA.PROFILEID WHERE (J.ACTIVATED='Y' OR ( J.ACTIVATED = 'N' AND J.INCOMPLETE = 'Y' ) ) AND J.SORT_DT >= :SORTDATE AND (J.ENTRY_DT >= :ENTRYDATE || (J.ENTRY_DT < :ENTRYDATE && (J.MOB_STATUS = 'Y' || J.LANDL_STATUS = 'Y')) && (J.LAST_LOGIN_DT >= :LOGINDATE)) AND (JA.NEW_MATCHES_MAILS IS NULL OR JA.NEW_MATCHES_MAILS='' OR JA.NEW_MATCHES_MAILS='S')";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":SORTDATE",$sortDate,PDO::PARAM_STR);
                        $prep->bindValue(":ENTRYDATE",$entryDate,PDO::PARAM_STR);
                        $prep->bindValue(":LOGINDATE",$loginDate,PDO::PARAM_STR);
			$prep->execute();
                        return $prep->rowCount();
		}
		catch (PDOException $e)
		{
                        SendMail::send_email("lavesh.rawat@gmail.com","FMA Insert Failed","insert into Receiver failed","lavesh.rawat@gmail.com");
			throw new jsException($e);
		}
	}
        
        public function updateSent($profileId)
	{
		try 
		{
			$sql= " UPDATE new_matches_emails.RECEIVER SET SENT = 'Y' WHERE PROFILEID = :PROFILEID";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        
        public function getProfilesToSendEmails($totalScript,$currentScript)
	{
		try 
		{
			$sql= " SELECT PROFILEID,HASTRENDS,DPP_SWITCH FROM new_matches_emails.RECEIVER WHERE SENT = 'N' AND PROFILEID%:TOTALSCRIPT= :CURRENTSCRIPT";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":TOTALSCRIPT",$totalScript,PDO::PARAM_INT);
                        $prep->bindValue(":CURRENTSCRIPT",$currentScript,PDO::PARAM_INT);
			$prep->execute();
                        while($row = $prep->fetch(PDO::FETCH_ASSOC))
                            $result[] = $row;
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        /*
         * 
         * This function updates HASTRENDS column if user has data in trends table
         */
        public function updateTrends(){
                try
		{
			$sql = "UPDATE new_matches_emails.RECEIVER m , twowaymatch.TRENDS t SET HASTRENDS = '1' WHERE m.PROFILEID =t.PROFILEID and ((t.INITIATED + t.ACCEPTED)>20)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
        }
        /*
         * This function reset HASTRENDS column to '0' if user switched to old match logic
         */
        public function resetTrendsIfOldLogicSet(){
                try
		{
			$sql = "UPDATE new_matches_emails.RECEIVER m , newjs.MATCH_LOGIC t SET HASTRENDS = '0', DPP_SWITCH = '1' WHERE m.PROFILEID =t.PROFILEID AND LOGIC_STATUS = 'O' AND HASTRENDS='1'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
        }
        
}