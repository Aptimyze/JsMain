<?
class FTO_FTO_STATE_LOG extends TABLE
{
        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }

        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from FTO_CURRENT_STATE_LOG
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

        public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID")
        {
                if(!$valueArray && !$excludeArray  && !$greaterThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
                        $sqlSelectDetail = "SELECT $fields FROM FTO.FTO_STATE_LOG WHERE ";
                        $count = 1;
                        if(is_array($valueArray))
                        {
                                foreach($valueArray as $param=>$value)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $param IN ($value) ";
                                        else
                                                $sqlSelectDetail.=" AND $param IN ($value) ";
                                        $count++;
                                }
                        }
                        if(is_array($excludeArray))
                        {
                                foreach($excludeArray as $excludeParam => $excludeValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
                                        else
                                                $sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
                                        $count++;
                                }
                        }
                        if(is_array($greaterThanArray))
                        {
                                foreach($greaterThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam > '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam > '$gValue' ";
                                        $count++;
                                }
                        }

                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                        /*
                        foreach ($valueArray as $k => $val)
                        {
                                $resSelectDetail->bindValue(($k+1), $val);
                        }
                        */
                        $resSelectDetail->execute();
                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $rowSelectDetail;
                        }
                        return $detailArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }

        public function insertProfilesInStateLog($profileArray,$stateID,$comment)
        {
                if(!$profileArray || !$stateID)
                        throw new jsException("","profileArray or stateID not passed");
                try
                {
                        foreach($profileArray as $k=>$profileid)
                        {
                                $str.="(:PROFILEID".$k." ,now(), :STATE_ID, :COMMENT) ,";
                        }
                        $str= substr($str,0,-1);
                        $sql= "INSERT INTO FTO.FTO_STATE_LOG  (PROFILEID, ENTRY_DATE, STATE_ID, COMMENT) VALUES ".$str;
                        $res = $this->db->prepare($sql);
                        foreach($profileArray as $k=>$profileid)
                        {       $res->bindValue(":PROFILEID".$k, $profileid,PDO::PARAM_INT);}
                        $res->bindValue(":STATE_ID",$stateID,PDO::PARAM_INT);
                        $res->bindValue(":COMMENT",$comment,PDO::PARAM_STR);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }

	public function logFTOState($profileid,$currentStateID,$comment='')
	{
		if(!$profileid || !$currentStateID)
                        throw new jsException("","profileid not passed");
		try
		{
			$sql = "INSERT INTO FTO.FTO_STATE_LOG  (PROFILEID,STATE_ID,ENTRY_DATE,COMMENT) VALUES (:PROFILEID,:STATE_ID,now(),:COMMENT)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->bindValue(":STATE_ID",$currentStateID,PDO::PARAM_INT);
			$res->bindValue(":COMMENT",$comment,PDO::PARAM_INT);
			$res->execute();
			return true;
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function profileNeverInThisFTOStateID($profileid,$stateIDArray)
	{
		if($profileid  && $stateIDArray)
		{
			$paramArr['PROFILEID']=$profileid;
			$stateIDStr='';
			foreach($stateIDArray as $k=>$stateID)
                        {
				if($k!=0)
					$stateIDStr.=", ";
				$stateIDStr.=$stateID;
                        }
			$paramArr['STATE_ID']=$stateIDStr;
			$res=$this->getArray($paramArr,'','','ID');
			if(count($res)>0)
				return false;
			else
				return true;
			return $res;
		}
	}
	public function profileNeverPerformAction($profileid,$action)
	{
		if($profileid && $action)
		{
			$sql= "SELECT ID FROM FTO.FTO_STATE_LOG WHERE PROFILEID=:PROFILEID AND COMMENT=:COMMENT"; 
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
                        $res->bindValue(":COMMENT", $action,PDO::PARAM_STR);
                        $res->execute();
			$count=$res->rowCount();
			if($count>0)
				return false;
			else
				return true;
		}
	}
	public function profileExistsInFTOStateLog($profileid)
	{
		if($profileid)
		{
			$sql = "SELECT ID FROM FTO.FTO_STATE_LOG WHERE PROFILEID=:PROFILEID";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
                        $res->execute();
			$count=$res->rowCount();
			if($count>0)
				return true;
			else
				return false;

		}
	}
	public function getProfilesInStateBetweenDates($profileArray,$stateIdArray,$startDate,$endDate)
	{
		try
		{
			if($profileArray)
			{
				foreach($profileArray as $k=>$v)
				{
					$profileStr.=":PROFILEID".$k.",";
				}
				$profileStr = substr($profileStr,0,-1);
			}
			if(is_array($stateIdArray))
			{
				foreach($stateIdArray as $k1=>$v1)
				{
					$stateIdStr.=":STATE_ID".$k1.",";
				}
				$stateIdStr = substr($stateIdStr,0,-1);
			}
			else
				$stateIdStr = ":STATE_ID";
			$sql = "SELECT PROFILEID FROM FTO.FTO_STATE_LOG WHERE STATE_ID IN (".$stateIdStr.") AND PROFILEID IN (".$profileStr.") GROUP BY profileid HAVING MIN( ENTRY_DATE ) BETWEEN :START_DATE AND :END_DATE";
			$res = $this->db->prepare($sql);
			foreach($profileArray as $k=>$profileid)
				$res->bindValue(":PROFILEID".$k, $profileid,PDO::PARAM_INT);
			if(is_array($stateIdArray))
			{
				foreach($stateIdArray as $k1=>$stateId)
					$res->bindValue(":STATE_ID".$k1, $stateId,PDO::PARAM_INT);
			}
			else
					$res->bindValue(":STATE_ID", $stateIdArray,PDO::PARAM_INT);
			$res->bindValue(":START_DATE", $startDate,PDO::PARAM_STR);
			$res->bindValue(":END_DATE", $endDate,PDO::PARAM_STR);
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] = $row['PROFILEID'];
                        return $profileArr;

		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}

	/*
	* This function gives all the transitions for all the profiles happened on a given date. It is used in mis
	* @param - date
	* @return - array with index as profileid and value as an array having transition data and profileid
	*/	
	public function getAllStateTransitionsPerDayPerProfile($date)
	{
		if(!$date)
			throw new jsException("","DATE IS BLANK IN getAllStateTransitionsPerDayPerProfile() OF FTO_FTO_STATE_LOG.class.php");

		try
		{
			$sql = "SELECT GROUP_CONCAT(STATE_ID ORDER BY ENTRY_DATE ASC SEPARATOR ',') AS STATES,PROFILEID FROM FTO.FTO_STATE_LOG WHERE ENTRY_DATE BETWEEN :START_DATE AND :END_DATE GROUP BY PROFILEID ORDER BY PROFILEID ASC";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE", $date." 00:00:00", PDO::PARAM_STR);
                        $res->bindValue(":END_DATE", $date." 23:59:59", PDO::PARAM_STR);
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]] = $row;
                        }
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	/*
	* This function gives the last state of a given set of profiles prior to the given date. It is used in mis.
	* @param - date and profileid array
	* @return - array with index as profileid and value as the last state
	*/
	public function getLastStateOfProfileBeforeGivenDate($date,$profileArr)
	{
		if(!$date || !$profileArr || !is_array($profileArr))
                        throw new jsException("","DATE IS BLANK IN getLastStateOfProfileBeforeGivenDate() OF FTO_FTO_STATE_LOG.class.php");

		try
		{
			foreach($profileArr as $k=>$v)
				$paramArr[] = ":PROFILEID".$k;				

			$sql3 = "CREATE TEMPORARY TABLE test.TEMP_MIS1 (ENTRY_DATE datetime NOT NULL,PROFILEID int(11) NOT NULL,PRIMARY KEY (PROFILEID),KEY ENTRY_DATE (ENTRY_DATE)) ENGINE=MyISAM";
			$res = $this->db->prepare($sql3);
			$res->execute();
                        unset($res);

			$sql1 = "INSERT INTO test.TEMP_MIS1(ENTRY_DATE,PROFILEID) SELECT MAX(ENTRY_DATE) AS ENTRY_DATE,PROFILEID FROM FTO.FTO_STATE_LOG WHERE ENTRY_DATE<:DATE AND PROFILEID IN (".implode(",",$paramArr).") GROUP BY PROFILEID";
			$res = $this->db->prepare($sql1);
                        foreach($profileArr as $k=>$v)
                        {
                                $res->bindValue($paramArr[$k],$v, PDO::PARAM_INT);
                        }
                        $res->bindValue(":DATE", $date." 00:00:00", PDO::PARAM_STR);
                        $res->execute();
			unset($res);

			$sql = "SELECT F.PROFILEID AS PROFILEID,F.STATE_ID AS STATE_ID FROM FTO.FTO_STATE_LOG F, test.TEMP_MIS1 T WHERE F.ENTRY_DATE = T.ENTRY_DATE AND F.PROFILEID = T.PROFILEID";
			$res = $this->db->prepare($sql);
                        $res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]] = $row["STATE_ID"];
                        }
			unset($res);
			$sql2 = "DROP TABLE test.TEMP_MIS1";
			$res = $this->db->prepare($sql2);
			$res->execute();
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	/*
	* This function is used to find number of users going to FTO Active on a given date and number of days taken to be FTO Active. It is used in MIS
	* @param - date
	* @return - array having resultset rows
	*/
	public function getAllFtoActivePerDayAlongWithDaysTaken($date)
	{
		if(!$date)
			throw new jsException("","DATE IS BLANK IN getAllFtoActivePerDayAlongWithDaysTaken() OF FTO_FTO_STATE_LOG.class.php");

		try
		{
			$sql3 = "CREATE TEMPORARY TABLE test.TEMP_MIS3 (DAYS int(2) NOT NULL,PROFILEID int(11) NOT NULL,PRIMARY KEY (PROFILEID)) ENGINE=MyISAM";
			$res = $this->db->prepare($sql3);
			$res->execute();
                        unset($res);

			$sql = "INSERT INTO test.TEMP_MIS3(DAYS,PROFILEID) SELECT DATEDIFF(min(f1.ENTRY_DATE),j.ENTRY_DT) AS DAYS,f1.PROFILEID AS PROFILEID FROM FTO.FTO_STATE_LOG f1, FTO.FTO_STATES f2, newjs.JPROFILE j WHERE f1.STATE_ID = f2.STATE_ID AND f1.PROFILEID = j.PROFILEID AND f1.ENTRY_DATE BETWEEN :START_DATE AND :END_DATE AND f2.SUBSTATE IN (:S1,:S2,:S3,:S4,:S5,:S6,:S7) GROUP BY f1.PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":START_DATE", $date." 00:00:00", PDO::PARAM_STR);
			$res->bindValue(":END_DATE", $date." 23:59:59", PDO::PARAM_STR);
			$res->bindValue(":S1", FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD, PDO::PARAM_STR);
			$res->bindValue(":S2", FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD, PDO::PARAM_STR);
			$res->bindValue(":S3", FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD, PDO::PARAM_STR);
			$res->bindValue(":S4", FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD, PDO::PARAM_STR);
			$res->bindValue(":S5", FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT, PDO::PARAM_STR);
			$res->bindValue(":S6", FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT, PDO::PARAM_STR);
			$res->bindValue(":S7", FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT, PDO::PARAM_STR);
			$res->execute();
			unset($res);

			$sql1 = "SELECT COUNT(*) AS C,DAYS FROM test.TEMP_MIS3 GROUP BY DAYS";
			$res = $this->db->prepare($sql1);
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
			unset($res);
						$ddl_obj = parent::__construct('newjs_masterDDL');
                        $sql2 = "DROP TABLE test.TEMP_MIS3";
                        $res = $ddl_obj->db->prepare($sql2);
                        $res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
	}

	public function getFtoUsersWhoUploadedPhoto($dateVal)
	{
		try
		{
			$dateStart = $dateVal." 00:00:00";
			$dateEnd = $dateVal." 23:59:59";

			$sql = "SELECT DISTINCT F1.PROFILEID, DATEDIFF(F1.ENTRY_DATE,J.ENTRY_DT) AS DAYS FROM FTO.FTO_STATE_LOG F1 use index (ENTRY_DATE),newjs.JPROFILE J,FTO.FTO_STATES F2  WHERE F1.ENTRY_DATE <= :DATE_END AND F1.ENTRY_DATE >= :DATE_START AND F2.SUBSTATE IN (:STATE_ID1,:STATE_ID2) AND COMMENT = :COMMENT AND F1.STATE_ID = F2.STATE_ID AND F1.PROFILEID = J.PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DATE_START",$dateStart,PDO::PARAM_STR);
			$res->bindValue(":DATE_END",$dateEnd,PDO::PARAM_STR);
			$res->bindValue(":STATE_ID1",FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO,PDO::PARAM_INT);
			$res->bindValue(":STATE_ID2",FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD,PDO::PARAM_INT);
			$res->bindValue(":COMMENT",FTOStateUpdateReason::PHOTO,PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $result;
	}

	public function getUsersWhoPaid($dateVal)
	{
		try
		{
			$dateStart = $dateVal." 00:00:00";
			$dateEnd = $dateVal." 23:59:59";
			$sql = "SELECT F1.PROFILEID, DATE(F1.ENTRY_DATE) AS LAST_STATE_DATE,DATE(MAX( F2.ENTRY_DATE )) AS SECOND_LAST_STATE_DATE, GROUP_CONCAT( F2.STATE_ID ORDER BY F2.ENTRY_DATE DESC SEPARATOR ',' ) AS STATES FROM FTO.FTO_STATE_LOG F1 JOIN FTO.FTO_STATE_LOG F2 ON F1.PROFILEID = F2.PROFILEID JOIN FTO.FTO_STATES F3 ON F1.STATE_ID = F3.STATE_ID WHERE F3.SUBSTATE = :S1 AND F2.ENTRY_DATE < F1.ENTRY_DATE AND F1.ENTRY_DATE <= :DATE_END AND F1.ENTRY_DATE >= :DATE_START GROUP BY F1.PROFILEID ";
			$res = $this->db->prepare($sql);
			$res->bindValue(":DATE_START",$dateStart,PDO::PARAM_STR);
			$res->bindValue(":DATE_END",$dateEnd,PDO::PARAM_STR);
			$res->bindValue(":S1", FTOSubStateTypes::PAID, PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$result[] = $row;
			}
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $result;
	}

	/*
	* This function is used to get the profiles along with their gender which are in state E2 on a given date. It is used in MIS
	* @param - date
	* @return - array of resultset rows
	*/
	public function getAllUsersWhoseFtoExpiredOnGivenDate($date)
	{
		if(!$date)
			throw new jsException("","DATE IS BLANK IN getAllUsersWhoseFtoExpiredOnGivenDate() OF FTO_FTO_STATE_LOG.class.php");

		try
		{
			$sql = "SELECT DISTINCT f1.PROFILEID AS PROFILEID,j.GENDER AS GENDER FROM FTO.FTO_STATE_LOG f1, FTO.FTO_STATES f2, newjs.JPROFILE j WHERE f1.STATE_ID = f2.STATE_ID AND f1.PROFILEID = j.PROFILEID AND f1.ENTRY_DATE BETWEEN :START_DATE AND :END_DATE AND f2.SUBSTATE IN (:S1)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":START_DATE", $date." 00:00:00", PDO::PARAM_STR);
                        $res->bindValue(":END_DATE", $date." 23:59:59", PDO::PARAM_STR);
			$res->bindValue(":S1", FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED, PDO::PARAM_STR);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row;
                        }
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

        public function checkPaid($profileid, $stateId)
        {
                try
                {
                        $sql = "SELECT ID FROM FTO.FTO_STATE_LOG WHERE PROFILEID=:PROFILEID AND STATE_ID=:PAID";
                        $res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
			$res->bindValue(":PAID", $stateId,PDO::PARAM_INT);
                        $res->execute();
                        if($row = $res->fetch(PDO::FETCH_ASSOC))
                                $return = true;
			else
				$return = false;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $return;
        }
	public function getProfilesActivatedInDateRange($stDt,$endDt)
	{
		try
		{
			$sql="SELECT PROFILEID,ENTRY_DATE FROM FTO.FTO_STATE_LOG WHERE ENTRY_DATE> :START_DATE AND ENTRY_DATE < :END_DATE AND STATE_ID IN (5,6,7)";
			$res=$this->db->prepare($sql);
			$res->bindValue(":START_DATE",$stDt,PDO::PARAM_STR);
			$res->bindValue(":END_DATE",$endDt,PDO::PARAM_STR);
			$res->execute();
			while($result=$res->fetch(PDO::FETCH_ASSOC))
			{
				$activateDate=date("Y-m-d",JSstrToTime($result['ENTRY_DATE']));
				$profiles[$activateDate][]=$result['PROFILEID'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
}
?>
