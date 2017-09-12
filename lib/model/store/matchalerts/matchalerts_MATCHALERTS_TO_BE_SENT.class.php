<?php
/**
* This class with populate the ids for which we need to send the mailers.
*/
class matchalerts_MATCHALERTS_TO_BE_SENT extends TABLE
{
	public function __construct($dbname='')
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
                        $sql="TRUNCATE TABLE matchalerts.MATCHALERTS_TO_BE_SENT";
			$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
			//add mail/sms
                        throw new jsException($e);
                }
        }

	/**
	* Populate the table as per conditiob "conditionNew"
	* @param conditionNew
	*/
        public function populateTables($conditionNew)
        {
                try
                {
			$sql="INSERT IGNORE INTO matchalerts.MATCHALERTS_TO_BE_SENT(PROFILEID,PERSONAL_MATCHES,LAST_LOGIN_DT) SELECT jp.PROFILEID,jp.PERSONAL_MATCHES,jp.LAST_LOGIN_DT FROM newjs.JPROFILE as jp LEFT JOIN newjs.JPROFILE_CONTACT as jpc ON jpc.PROFILEID = jp.profileid WHERE ".$conditionNew." ORDER BY jp.LAST_LOGIN_DT DESC";
			$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
			//add mail/sms
                        throw new jsException($e);
                }
        }


	/**
	* Fetch 
	* @param 
	*/
	public function fetch($totalScript="1",$currentScript="0",$limit="",$FROM_REG=0)
	{
		try
		{
			$result = NULL;
			$sql = "SELECT PROFILEID , HASTRENDS,PERSONAL_MATCHES,MATCH_LOGIC FROM matchalerts.MATCHALERTS_TO_BE_SENT WHERE PROFILEID%:TOTAL_SCRIPT=:SCRIPT AND IS_CALCULATED=:STATUS";
                        
                        $sql .=    " AND FROM_REG=:FROM_REG";
                        
                        $sql .=    " ORDER BY LAST_LOGIN_DT DESC";
                        
			if($limit)
                                $sql.= " limit 0,:LIMIT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                        $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                        $prep->bindValue(":FROM_REG",$FROM_REG,PDO::PARAM_STR);
                        $prep->bindValue(":STATUS",'N',PDO::PARAM_STR);
                        if($limit)
                                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                        $prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row["PROFILEID"]]["HASTRENDS"] = $row["HASTRENDS"];
                                $result[$row["PROFILEID"]]["PERSONAL_MATCHES"] = $row["PERSONAL_MATCHES"];
                                $result[$row["PROFILEID"]]["MATCH_LOGIC"] = $row["MATCH_LOGIC"];
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}

	/**
	* update
	* @param pid
	*/
	public function update($pid)
	{
		try
		{
			$result = NULL;
			$sql = "UPDATE matchalerts.MATCHALERTS_TO_BE_SENT SET IS_CALCULATED='Y' WHERE PROFILEID=:PID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PID",$pid,PDO::PARAM_INT);
                        $prep->execute();
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
			$sql = "UPDATE matchalerts.MATCHALERTS_TO_BE_SENT m , twowaymatch.TRENDS t SET HASTRENDS = '1' WHERE m.PROFILEID =t.PROFILEID and ((t.INITIATED + t.ACCEPTED)>20)";
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
			$sql = "UPDATE matchalerts.MATCHALERTS_TO_BE_SENT m , newjs.MATCH_LOGIC t SET HASTRENDS = '0',MATCH_LOGIC='O' WHERE m.PROFILEID =t.PROFILEID AND LOGIC_STATUS = 'O' AND HASTRENDS='1'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
        }

        // to get total count from MATCHALERTS_TO_BE_SENT
        public function getTotalCount()
        {
        	try
        	{
        		$sql = "SELECT COUNT(*) as TOTALCOUNT FROM matchalerts.MATCHALERTS_TO_BE_SENT";
        		$prep = $this->db->prepare($sql);
        		$prep->execute();
        		while($row = $prep->fetch(PDO::FETCH_ASSOC))
        		{
        			$resultCount = $row["TOTALCOUNT"];
        		}        	
				return $resultCount;
        	}
        	catch (PDOException $e)
        	{
        		jsException::nonCriticalError($e);
        	}        
        }
        /**
         * get count for tracking 
         * @param type $totalScript
         * @param type $currentScript
         * @return type
         */
         public function getTotalCountWithScript($totalScript="1",$currentScript="0")
        {
        	try
        	{
        		$sql = "SELECT COUNT(*) as TOTALCOUNT FROM matchalerts.MATCHALERTS_TO_BE_SENT WHERE IS_CALCULATED = 'N' AND PROFILEID%:TOTAL_SCRIPT=:SCRIPT";
        		$prep = $this->db->prepare($sql);
                        $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                        $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
        		$prep->execute();
        		while($row = $prep->fetch(PDO::FETCH_ASSOC))
        		{
        			$resultCount = $row["TOTALCOUNT"];
        		}        	
				return $resultCount;
        	}
        	catch (PDOException $e)
        	{
        		jsException::nonCriticalError($e);
        	}        
        }
        /**
	* Populate the table as per conditiob "conditionNew"
	* @param conditionNew
	*/
        public function insertFromTempTable()
        {
                try
                {
			$sql="INSERT IGNORE INTO matchalerts.MATCHALERTS_TO_BE_SENT(PROFILEID,LAST_LOGIN_DT) SELECT PROFILEID,now() FROM matchalerts.MATCHALERTS_TO_BE_SENT_REG";
			$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        /**
	* Empty The table
	*/
        public function truncateTempTable()
        {
                try
                {
                        $sql="TRUNCATE TABLE matchalerts.MATCHALERTS_TO_BE_SENT_REG";
			$res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch (PDOException $e)
                {
			//add mail/sms
                        throw new jsException($e);
                }
        }
        public function insertIntoMatchAlertsTempTable($table,$PROFILEID,$fromReg = "1")
        {
                if($PROFILEID == NULL || !$PROFILEID || $PROFILEID == 0){
                        jsException::nonCriticalError("PROFILEID IS BLANK in insertIntoMatchAlertsTempTable");
                        return true;
                }
                try
                {
                        $tbl_name ="matchalerts.MATCHALERTS_TO_BE_SENT";
                        if($table == "temp"){
                                $tbl_name ="matchalerts.MATCHALERTS_TO_BE_SENT_REG";
                        }
			$sql="INSERT IGNORE INTO $tbl_name(PROFILEID,LAST_LOGIN_DT,FROM_REG) VALUES(:PROFILEID,now(),:FROM_REG)";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
                        $prep->bindValue(":FROM_REG",$fromReg,PDO::PARAM_STR);
        		$prep->execute();
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function fetchLastRecord()
	{
		try
		{
			$result = NULL;
			$sql = "SELECT PROFILEID, LAST_LOGIN_DT FROM matchalerts.MATCHALERTS_TO_BE_SENT ORDER BY LAST_LOGIN_DT DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result["LAST_LOGIN_DT"] = $row["LAST_LOGIN_DT"];
				$result["PROFILEID"] = $row["PROFILEID"];
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        /**
	* update
	* @param pid
	*/
	public function updateCommunity($pid,$STATUS)
	{
		try
		{
			$sql = "UPDATE matchalerts.MATCHALERTS_TO_BE_SENT SET COMMUNITY_ELIGIBLE=:COMMUNITY_ELIGIBLE WHERE PROFILEID=:PID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PID",$pid,PDO::PARAM_INT);
                        $prep->bindValue(":COMMUNITY_ELIGIBLE",$STATUS,PDO::PARAM_STR);
                        $prep->execute();
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        
        /**
	* Fetch 
	* @param 
	*/
	public function fetchCommunityProfiles($totalScript="1",$currentScript="0",$limit="")
	{
		try
		{
			$result = NULL;
			$sql = "SELECT PROFILEID , PERSONAL_MATCHES,MATCH_LOGIC FROM matchalerts.MATCHALERTS_TO_BE_SENT WHERE PROFILEID%:TOTAL_SCRIPT=:SCRIPT AND IS_CALCULATED=:STATUS";
                        
                        $sql .=    " AND COMMUNITY_ELIGIBLE=:COMMUNITY_ELIGIBLE";
                        
                        $sql .=    " ORDER BY LAST_LOGIN_DT DESC";
                        
			if($limit)
                                $sql.= " limit 0,:LIMIT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":TOTAL_SCRIPT",$totalScript,PDO::PARAM_INT);
                        $prep->bindValue(":SCRIPT",$currentScript,PDO::PARAM_INT);
                        $prep->bindValue(":STATUS",'Y',PDO::PARAM_STR);
                        $prep->bindValue(":COMMUNITY_ELIGIBLE",'E',PDO::PARAM_STR);
                        if($limit)
                                  $prep->bindValue(":LIMIT",$limit,PDO::PARAM_INT);
                        $prep->execute();
			while($row = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$result[$row["PROFILEID"]]["HASTRENDS"] = $row["HASTRENDS"];
                                $result[$row["PROFILEID"]]["PERSONAL_MATCHES"] = $row["PERSONAL_MATCHES"];
                                $result[$row["PROFILEID"]]["MATCH_LOGIC"] = $row["MATCH_LOGIC"];
			}
			return $result;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        
}
