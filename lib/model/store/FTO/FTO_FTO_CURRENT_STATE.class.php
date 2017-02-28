<?php
class FTO_FTO_CURRENT_STATE extends TABLE
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
        {return array();
                if(!$valueArray && !$excludeArray  && !$greaterThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
//                        $fields = $fields?$fields:$this->getFields();//Get columns to query
                        $sqlSelectDetail = "SELECT $fields FROM FTO.FTO_CURRENT_STATE WHERE ";
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
    public function getProfilesHavingSubStatesWithGivenExpiry($stateIdArray,$expiry_cond=''){
                try
                {
					$state_id_in=implode(',',$stateIdArray);
					if(is_array($expiry_cond)){
						$exp_cond="(";
						foreach($expiry_cond as $date){
							$exp_cond.="FTO_EXPIRY_DATE BETWEEN '$date 00:00:00' AND  '$date 23:59:59' OR ";
						}
						$exp_cond=substr($exp_cond,0,-3);
						$exp_cond.=")";
					}
					else
						$exp_cond="FTO_EXPIRY_DATE BETWEEN '$expiry_cond 00:00:00' AND  '$expiry_cond 23:59:59'";
//                        $fields = $fields?$fields:$this->getFields();//Get columns to query
                        $sqlSelectDetail = "SELECT PROFILEID FROM FTO.FTO_CURRENT_STATE WHERE STATE_ID IN ($state_id_in) AND $exp_cond";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                        $resSelectDetail->execute();
                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $rowSelectDetail[PROFILEID];
                        }
                        return $detailArr;
                        $count = 1;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
	}
	public function updateProfilesCurrentState($profileArray,$stateID)
	{
		if(!$profileArray || !$stateID)
			throw new jsException("","profileArray or stateID not passed");
		try
		{
			foreach($profileArray as $k=>$profileid)
			{
				$str.=":PROFILEID".$k." ,";
			}
			$str= substr($str,0,-1);
			$sql= "UPDATE FTO.FTO_CURRENT_STATE  SET STATE_ID=:STATE_ID WHERE PROFILEID IN (".$str.")";
			$res = $this->db->prepare($sql);
			foreach($profileArray as $k=>$profileid)
			{	$res->bindValue(":PROFILEID".$k, $profileid,PDO::PARAM_INT);}
			$res->bindValue(":STATE_ID",$stateID,PDO::PARAM_INT);
			$res->execute();
			return true;
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
        public function updateFTOCurrentState($profileid,$currentStateID='',$FTOEntryDate='',$FTOExpiryDate='')
        {
                if(!$profileid)
                        throw new jsException("","profileid not passed");
		try
                {
			$sql = "UPDATE FTO.FTO_CURRENT_STATE  SET ";
                        if($currentStateID!='')
                                $sql.=" STATE_ID=:STATE_ID ," ;
                        if($FTOEntryDate!='')
                                $sql.=" FTO_ENTRY_DATE=:FTO_ENTRY_DATE ,";
                        if($FTOExpiryDate!='')
                                $sql.=" FTO_EXPIRY_DATE=:FTO_EXPIRY_DATE ,";
			$sql = substr($sql,0,-1);
			$sql.=" WHERE PROFILEID=:PROFILEID ";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
                        if($currentStateID!='')
				$res->bindValue(":STATE_ID",$currentStateID,PDO::PARAM_INT);
                        if($FTOEntryDate!='')
				$res->bindValue(":FTO_ENTRY_DATE",$FTOEntryDate,PDO::PARAM_STR);
        	        if($FTOExpiryDate!='')
				$res->bindValue(":FTO_EXPIRY_DATE",$FTOExpiryDate,PDO::PARAM_STR);
			$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function insertFTOCurrentState($profileid,$currentStateID,$FTOEntryDate='',$FTOExpiryDate='',$FTOContactDetailsArr='')
	{
		if(!$profileid || !$currentStateID)
                        throw new jsException("","profileid or state id not passed");
		try
		{
			$str='';
			$str1='';
			if($FTOEntryDate!='')
			{
				$str.=" ,FTO_ENTRY_DATE";
				$str1.=" ,:FTO_ENTRY_DATE";
			}
			if($FTOExpiryDate!='')
			{
				$str.=" ,FTO_EXPIRY_DATE";
				$str1.=" ,:FTO_EXPIRY_DATE";
			}
                        if (isset($FTOContactDetailsArr))
                        {
				$str.= " , FLAG , INBOUND_LIMIT , OUTBOUND_LIMIT , TOTAL_LIMIT ";
				$str1.= " , :FLAG , :INBOUND_LIMIT , :OUTBOUND_LIMIT , :TOTAL_LIMIT ";
			}
			$sql = "INSERT IGNORE INTO FTO.FTO_CURRENT_STATE (PROFILEID , STATE_ID ".$str.") VALUES (:PROFILEID,:STATE_ID ".$str1.")";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
			$res->bindValue(":STATE_ID", $currentStateID,PDO::PARAM_INT);
			if($FTOEntryDate!='')
				$res->bindValue(":FTO_ENTRY_DATE", $FTOEntryDate,PDO::PARAM_STR);
			if($FTOExpiryDate!='')
				$res->bindValue(":FTO_EXPIRY_DATE", $FTOExpiryDate,PDO::PARAM_STR);
			if (isset($FTOContactDetailsArr))
			{
				$res->bindValue(":FLAG",$FTOContactDetailsArr["FLAG"],PDO::PARAM_STR);
				$res->bindValue(":INBOUND_LIMIT",$FTOContactDetailsArr["INBOUND_LIMIT"],PDO::PARAM_INT);
				$res->bindValue(":OUTBOUND_LIMIT",$FTOContactDetailsArr["OUTBOUND_LIMIT"],PDO::PARAM_INT);
				$res->bindValue(":TOTAL_LIMIT",$FTOContactDetailsArr["TOTAL_LIMIT"],PDO::PARAM_INT);
			}
			$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function getFTOCurrentStateID($profileid)
	{
			$PROFILEID['PROFILEID']=$profileid;
			$res=$this->getArray($PROFILEID,'','','PROFILEID,STATE_ID');
			return $res['0']['STATE_ID'];
	}
	public function getProfilesInState($stateIdArray,$expiry_cond='')
	{
			if(is_array($stateIdArray))
			{
				foreach($stateIdArray as $k=>$stateId)
					$STATE_ID['STATE_ID'].=$stateId.",";
				$STATE_ID['STATE_ID']=substr($STATE_ID['STATE_ID'],0,-1);
			}
			else
				$STATE_ID['STATE_ID']=$stateIdArray;
			$res=$this->getArray($STATE_ID,'','','*,DATEDIFF(FTO_EXPIRY_DATE,FTO_ENTRY_DATE) as DATEDIFF');
			return $res;
	}
	public function getFTOCurrentStateRow($profileid)
	{
			$PROFILEID['PROFILEID']=$profileid;
			$res=$this->getArray($PROFILEID,'','','*');
			return $res['0'];
	}
	public function getFTOExpiryDate($profileid)
	{
			$PROFILEID['PROFILEID']=$profileid;
			$res=$this->getArray($PROFILEID,'','','PROFILEID,FTO_EXPIRY_DATE');
			return $res['0']['FTO_EXPIRY_DATE'];
	}
	public function getFTOEntryDate($profileid)
	{
			$PROFILEID['PROFILEID']=$profileid;
			$res=$this->getArray($PROFILEID,'','','PROFILEID,FTO_ENTRY_DATE');
			return $res['0']['FTO_ENTRY_DATE'];
	}
	public function deleteFTOCurrentState($profileid)
	{
		try
		{
			$sql= "DELETE FROM FTO.`FTO_CURRENT_STATE` WHERE `PROFILEID` =:PROFILEID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid,PDO::PARAM_INT);
			$res->execute();
		}
                catch(PDOException $e)
		{
			throw new jsException($e);
		}

	}
	public function getNonExpiredProfilesWithExpiryNow($expiredStateIdArray)
	{
		try
		{
			$str= implode(", ",$expiredStateIdArray);
			$sql= "SELECT PROFILEID,DATEDIFF(FTO_EXPIRY_DATE,FTO_ENTRY_DATE) as DATEDIFF FROM FTO.FTO_CURRENT_STATE WHERE `FTO_EXPIRY_DATE` <= now() AND STATE_ID NOT IN (".$str.")";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
                                $detailArr[] = $row;
                        return $detailArr;

		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function getLimitArray($profileid)
        {
                if(!$profileId)
                        throw new jsException("","no profileid passed");
                try
                {
                        $sqlSelectDetail = "SELECT FLAG,INBOUND_LIMIT,OUTBOUND_LIMIT,TOTAL_LIMIT FROM FTO.FTO_CURRENT_STATE WHERE PROFILEID=:PROFILEID";
                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$resSelectDetail->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        if($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                                $StateArray = $rowSelectDetail;
                        return $StateArray;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }

	/*
	* This function gives the number of profiles in each state. It is used in MIS
	* @return - array having resultset rows
	*/
	public function getUsersInEachState()
	{
		try
		{
			$sql = "SELECT count(f.PROFILEID) AS C, f.STATE_ID AS STATE_ID FROM FTO.FTO_CURRENT_STATE f, newjs.JPROFILE j WHERE f.PROFILEID = j.PROFILEID AND j.ACTIVATED!=\"D\" AND j.SORT_DT>DATE_SUB(CURDATE(), INTERVAL 5 MONTH) GROUP BY f.STATE_ID";

			$res = $this->db->prepare($sql);
			$res->execute();
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$output[$row["STATE_ID"]] = $row;
			}
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
		return $output;
	}

	/*
	* This function generates the count of users who have logged in between the given dates and groups them on the basis of gender,mtongue and fto state
	* @param - start date and end date
	* @return - array of resultset rows
	*/
	public function getLoginWeeklyData($start_dt,$end_dt)
	{
		if(!$start_dt || !$end_dt)
			throw new jsException("","START DATE OR END DATE IS BLANK IN getLoginWeeklyData() OF FTO_FTO_CURRENT_STATE.class.php ");

		try
		{
			$sql = "SELECT COUNT(*) AS C,j.GENDER AS GENDER,j.MTONGUE AS MTONGUE,f.STATE_ID AS STATE_ID FROM newjs.JPROFILE j LEFT JOIN FTO.FTO_CURRENT_STATE f ON j.PROFILEID = f.PROFILEID WHERE DATE(j.LAST_LOGIN_DT) BETWEEN :START_DATE AND :END_DATE GROUP BY j.GENDER,j.MTONGUE,f.STATE_ID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":START_DATE",$end_dt,PDO::PARAM_STR);
			$res->bindValue(":END_DATE",$start_dt,PDO::PARAM_STR);
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
	public function getAllProfiles()
	{
		try
		{
			$sql  = "SELECT PROFILEID FROM FTO.FTO_CURRENT_STATE WHERE 1";
			$res = $this->db->prepare($sql);
			$res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                                $output[] = $row['PROFILEID'];
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
	}
}
?>
