<?php
class incentive_MAIN_ADMIN extends TABLE {

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function getFollowUpProfilesWithinDates($agentName,$startDate,$endDate)
	{
		try{
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME>:FOLLOWUP_TIME_START AND FOLLOWUP_TIME<:FOLLOWUP_TIME_END";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME_START", $startDate, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME_END", $endDate, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[]=$result['PROFILEID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getFollowUpProfiles($agentName)
	{
		try{
			$todaysDate =date("Y-m-d 23:59:59");
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME<=:FOLLOWUP_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME", $todaysDate, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[]=$result['PROFILEID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getFutureFollowUpProfiles($agentName)
	{
		try{
			$todaysDate =date("Y-m-d 23:59:59");
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME>:FOLLOWUP_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME", $todaysDate, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[]=$result['PROFILEID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}

	public function getFutureFollowUpProfileDetails($agentName)
	{
		try{
			$todaysDate =date("Y-m-d 23:59:59");
			$sql = "SELECT STATUS, ALLOT_TIME, FOLLOWUP_TIME, PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME>:FOLLOWUP_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME", $todaysDate, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[] = $result;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}

	public function getNonFollowupProfiles($agentName)
	{
		try{
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS!='F'";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[]=$result['PROFILEID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getProfilesForStatus($agentName,$status)
	{
		try{
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS=:STATUS";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":STATUS", $status, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[]=$result['PROFILEID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profiles;
	}
        public function getPendingFollowupProfiles($followUpTime)
        {
                try{
                        $sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE STATUS='F' AND FOLLOWUP_TIME<:FOLLOWUP_TIME";
                        $prep = $this->db->prepare($sql);
			$prep->bindParam(":FOLLOWUP_TIME",$followUpTime, PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[]=$result['PROFILEID'];
			return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }
	public function get($value="",$criteria="PROFILEID",$fields="",$extraWhereClause=null)
	{
		if(!$value)
			throw new jsException("","$criteria IS BLANK");
		try
		{
			$sql="SELECT $fields from incentive.MAIN_ADMIN WHERE $criteria = :$value";
			if(is_array($extraWhereClause))
			{
				foreach($extraWhereClause as $key=>$val)
				{
					$sql.=" AND $key='$val'";
				}
			}
			$resSelectDetail = $this->db->prepare($sql);
			$resSelectDetail->bindValue(":$value", $value, PDO::PARAM_INT);
			$resSelectDetail->execute();
			$result =$resSelectDetail->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$indexOutputBy="")
	{
		if(!$valueArray && !$excludeArray  && !$greaterThanArray)
			throw new jsException("","no where conditions passed");
		try
		{
			$sqlSelectDetail = "SELECT $fields FROM incentive.MAIN_ADMIN WHERE ";
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
			if(is_array($lessThanArray))
			{
				foreach($lessThanArray as $gParam => $gValue)
				{
					if($count == 1)
						$sqlSelectDetail.=" $gParam <= '$gValue' ";
					else
						$sqlSelectDetail.=" AND $gParam <= '$gValue' ";
					$count++;
				}
			}
			
			$resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$resSelectDetail->execute();
			while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
				if($indexOutputBy)
					$detailArr[$rowSelectDetail['PROFILEID']] = $rowSelectDetail;
				else
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

	public function fetchAgentsForDisp($processObj,$extraWhereClause='')
	{
		try
		{
			$sql="SELECT ALLOTED_TO,count(*) as CNT FROM incentive.MAIN_ADMIN WHERE STATUS NOT IN ('P','S') GROUP BY ALLOTED_TO HAVING CNT>:LIMIT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":LIMIT",$processObj->getLimit(),PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$executives[] = $result['ALLOTED_TO'].":".$result['CNT'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $executives;
	}
	public function fetchProfilesForDisp($exe)
	{
		try
		{
			$sql="SELECT ma.PROFILEID,ma.WILL_PAY,h.ENTRY_DT,ma.ALLOTED_TO FROM incentive.MAIN_ADMIN as ma JOIN incentive.HISTORY as h ON ma.PROFILEID=h.PROFILEID WHERE ma.ALLOTED_TO=:EXE AND ma.STATUS!='P' AND h.ENTRYBY=:EXE ORDER BY h.ENTRY_DT DESC";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":EXE",$exe,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[]=$result;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;

	}
	public function fetchProfilesForDispNegList($processObj)
	{
		try
		{
			$count = count($processObj->getDisposition());
			$in_params = trim(str_repeat('?, ', $count), ', ');
			$sql="SELECT PROFILEID,ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE WILL_PAY IN ({$in_params})";
			$prep = $this->db->prepare($sql);
			$prep->execute($processObj->getDisposition());
			$i=0;
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[$i]['PROFILEID']= $result['PROFILEID'];
				$profiles[$i]['ALLOTED_TO']= $result['ALLOTED_TO'];
				$i++;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
	public function deleteProfile($profileid,$username="")
	{
		try
		{
			$sql= "DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
			if($username)
			{
				$sql.=" AND ALLOTED_TO=:USERNAME";
			}
			$prep = $this->db->prepare($sql);
			if($username)
			{
				$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			}
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			$rowsAffected=$prep->rowCount();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $rowsAffected;
	}
/*	public function fetchProfilesForSub($end_date)
	{
		try
		{
			$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE STATUS='P' AND ALLOT_TIME<=':END_DATE'";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":END_DATE",$end_date,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
					$profiles[]=$result['PROFILEID'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}*/
	public function fetchProfilesForSubMethod($processObj)
	{
		try
		{
			$count = count($processObj->getExecutives());
			$in_params = trim(str_repeat('?, ', $count), ', ');
			$today = date("Y-m-d",time());
			$sql = "SELECT MA.PROFILEID AS PROFILEID FROM incentive.MAIN_ADMIN MA, incentive.CRM_DAILY_ALLOT CDA  WHERE CDA.DE_ALLOCATION_DT <= '$today' AND CDA.PROFILEID=MA.PROFILEID AND CDA.ALLOT_TIME=MA.ALLOT_TIME";
			if($processObj->getSubMethod()=="UPSELL")
				$sql.=" AND CDA.ALLOTED_TO IN({$in_params})";
			$prep = $this->db->prepare($sql);
			if($processObj->getSubMethod()=="UPSELL")
			{
				if($processObj->getExecutives())
					$prep->execute($processObj->getExecutives());
			}
			else
				$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[]=$result['PROFILEID'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getCurrentAllocDetails($profileid,$executive='')
	{
		try
		{
			$sql = "SELECT m.PROFILEID,m.STATUS,c.ALLOT_TIME,c.RELAX_DAYS,c.ALLOCATION_DAYS,c.DE_ALLOCATION_DT FROM incentive.MAIN_ADMIN m ,incentive.CRM_DAILY_ALLOT c WHERE c.PROFILEID=m.PROFILEID AND m.PROFILEID =:PROFILEID AND c.ALLOT_TIME=m.ALLOT_TIME ORDER BY c.DE_ALLOCATION_DT DESC LIMIT 1";
			if($executive)
				$sql.=" AND m.ALLOTED_TO=:ALLOTED_TO";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":PROFILEID", $profileid, PDO::PARAM_INT);
			if($executive)
				$prep->bindParam(":ALLOTED_TO", $executive, PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;
	}
	public function fetchProfilesForAgent($processObj)
	{
		try
		{
			$subMethod=$processObj->getSubMethod();
			$allotedDt=$processObj->getCurDate();
			if($allotedDt){
				$startTime =$allotedDt." 00:00:00";
				$endTime =$allotedDt." 23:59:59";
			}
			if($subMethod=="LIMIT_EXCEED" || $subMethod=="LIMIT_EXCEED_RENEWAL")
				$fields="PROFILEID,WILL_PAY,ALLOTED_TO";
			else
				$fields="PROFILEID";
			$sql="SELECT $fields FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = :USERNAME COLLATE latin1_bin";
			if($allotedDt)
				$sql .=" AND ALLOT_TIME>=:START_TIME AND ALLOT_TIME<=:END_TIME";


			if($subMethod=='UPSELL')
				$sql .=" AND STATUS='U'";
			elseif($subMethod=='RENEWAL')
				$sql .=" AND STATUS='R'";
			elseif($subMethod=='NEW_FAILED_PAYMENT')
				$sql .=" AND STATUS='FP'";
			elseif($subMethod=='FIELD_SALES')
			{
				$singleProfile = $processObj->getProfiles();
				if($singleProfile == '')
					$sql .=" AND STATUS='FS'";
			}
			
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":USERNAME",$processObj->getUsername(),PDO::PARAM_STR);
			if($allotedDt){
				$prep->bindValue(":START_TIME",$startTime,PDO::PARAM_STR);
				$prep->bindValue(":END_TIME",$endTime,PDO::PARAM_STR);
			}
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				if($subMethod=="LIMIT_EXCEED" || $subMethod=="LIMIT_EXCEED_RENEWAL")
					$profiles[]=$result;
				else
					$profiles[]=$result['PROFILEID'];
			}

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getFollowupProfilesForAgent($agentName)
	{
		try{
			$i=0;
			$sql = "SELECT PROFILEID,ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F'";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$profilesArr[$i]['PROFILEID']=$result['PROFILEID'];
				$profilesArr[$i]['ALLOT_TIME']=$result['ALLOT_TIME'];
				$i++;
			}
			return $profilesArr;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function allocateProfile($paramArr=array())
	{
		try
		{
			foreach($paramArr as $key=>$val)
				${$key} = $val;

			$sql = "INSERT IGNORE INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,FOLLOWUP_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,EMAIL,WILL_PAY,REASON,ORDERS,TIMES_TRIED) VALUES (:PROFILEID,:ALLOT_TIME,:CLAIM_TIME,:FOLLOWUP_TIME,:ALLOTED_TO,:STATUS,:ALTERNATE_NO,:MODE,now(),:COMMENTS,:RES_NO,:MOB_NO,:EMAIL,:WILL_PAY,:REASON,:ORDERS,:TIMES_TRIED)";
			$res = $this->db->prepare($sql);

			$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			$res->bindValue(":ALLOTED_TO", $ALLOTED_TO, PDO::PARAM_STR);
			$res->bindValue(":STATUS", $STATUS, PDO::PARAM_STR);
			$res->bindValue(":ALTERNATE_NO", $ALTERNATE_NO, PDO::PARAM_STR);
			$res->bindValue(":MODE", $MODE, PDO::PARAM_STR);
			$res->bindValue(":COMMENTS", $COMMENTS, PDO::PARAM_STR);
			$res->bindValue(":RES_NO", $RES_NO, PDO::PARAM_STR);
			$res->bindValue(":MOB_NO", $MOB_NO, PDO::PARAM_STR);
			$res->bindValue(":WILL_PAY", $WILL_PAY, PDO::PARAM_STR);
			$res->bindValue(":REASON", $REASON, PDO::PARAM_STR);
			$res->bindValue(":CLAIM_TIME", $CLAIM_TIME, PDO::PARAM_STR);
			$res->bindValue(":FOLLOWUP_TIME", $FOLLOWUP_TIME, PDO::PARAM_STR);
			$res->bindValue(":ALLOT_TIME", $ALLOT_TIME, PDO::PARAM_STR);
			$res->bindValue(":EMAIL", $EMAIL, PDO::PARAM_STR);
			$res->bindValue(":ORDERS", $ORDERS, PDO::PARAM_STR);
			$res->bindValue(":TIMES_TRIED", $TIMES_TRIED, PDO::PARAM_INT);
			if($ALLOTED_TO)
				$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function updateAllocation($paramArr=array(),$profileId)
	{
		if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN incentive_MAIN_ADMIN.class.php");
		try
		{
			foreach($paramArr as $key=>$val)
				$set[] = $key." = :".$key;
			$setValues = implode(",",$set);

			$sql = "UPDATE incentive.MAIN_ADMIN SET ".$setValues." ,CLAIM_TIME=if(CONVINCE_TIME='0000-00-00 00:00:00',NOW(),if(CONVINCE_TIME<CURDATE(),CONVINCE_TIME,CLAIM_TIME)),CONVINCE_TIME=now() WHERE PROFILEID=:PROFILEID";
			$res = $this->db->prepare($sql);
			foreach($paramArr as $key=>$val)
			{
				$res->bindValue(":".$key, $val);
			}
			$res->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
			$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function updateAllotedAgent($profileid,$executive,$allotedDt)
	{
		try
		{
			$startTime =$allotedDt." 00:00:00";
			$endTime   =$allotedDt." 23:59:59";
			$sql = "UPDATE incentive.MAIN_ADMIN SET ALLOTED_TO=:ALLOTED_TO WHERE PROFILEID=:PROFILEID AND ALLOT_TIME>=:START_TIME AND ALLOT_TIME<=:END_TIME";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->bindValue(":ALLOTED_TO",$executive,PDO::PARAM_STR);
			$res->bindValue(":START_TIME",$startTime,PDO::PARAM_STR);
			$res->bindValue(":END_TIME",$endTime,PDO::PARAM_STR);
			$res->execute();
			return true;
		}
		catch(PDOException $e){
			die($e);
			throw new jsException($e);
		}
	}
	public function fetchTotalAllocationCount($agentName)
	{
		try
		{
			$sql="SELECT count(*) CNT FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS NOT IN ('P','S')";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ALLOTED_TO",$agentName,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				$allotedCount =$result['CNT'];
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $allotedCount;
	}
	public function fetchTotalAllocation($agentName)
	{
		try
		{
			$sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS NOT IN ('P','S')";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ALLOTED_TO",$agentName,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$allotedProfileArr[] =$result['PROFILEID'];
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $allotedProfileArr;
	}
	public function getTotalAllocationCnt($status,$executivesArr)
	{
		if(!is_array($executivesArr))
			throw new jsException("","executives arr is blank");
		try
		{
			$executivesStr ="'".@implode("','",$executivesArr)."'";
			$sql="SELECT count(*) cnt, ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE STATUS=:STATUS AND ALLOTED_TO IN($executivesStr) GROUP BY ALLOTED_TO";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$alloted                   =$result['ALLOTED_TO'];
				$allocationCntArr[$alloted]=$result['cnt'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $allocationCntArr;
	}

	public function getFieldSalesAllotedProfilesForAgent($agent){

		$profileArray = array();
		$date = date("Y-m-d 23:59:59");
		try
		{
			$sql="SELECT STATUS, PROFILEID, ALLOT_TIME, FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN (:AGENT) AND ALLOT_TIME <=:DATE1";

			$prep=$this->db->prepare($sql);
			$prep->bindValue(":AGENT",$agent,PDO::PARAM_STR);
			$prep->bindValue(":DATE1",$date,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$profileArray[] = $result;
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}

		return $profileArray;
	}

	public function getFollowUpProfilesForRange($agent,$start_dt,$end_dt="")
	{
		$profileArray = array();
		try{
			if($end_dt){
				$sql = "SELECT STATUS, PROFILEID, ALLOT_TIME, FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME>=:FOLLOWUP_TIME_START AND FOLLOWUP_TIME<=:FOLLOWUP_TIME_END";
			} else {
				$sql = "SELECT STATUS, PROFILEID, ALLOT_TIME, FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME<=:FOLLOWUP_TIME_START";
			}
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agent, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME_START", $start_dt, PDO::PARAM_STR);
			if($end_dt){
				$prep->bindParam(":FOLLOWUP_TIME_END", $end_dt, PDO::PARAM_STR);
			}
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$profileArray[] = $result;
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $profileArray;
	}

	public function countFollowUpProfiles($agent,$start_dt,$end_dt)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME>=:FOLLOWUP_TIME_START AND FOLLOWUP_TIME<=:FOLLOWUP_TIME_END";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agent, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME_START", $start_dt, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME_END", $end_dt, PDO::PARAM_STR);
			$prep->execute();
			$res = $prep->fetch(PDO::FETCH_ASSOC);
			$cnt = $res['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $cnt;
	}
	public function countFollowUpProfilesBeforeDate($agentName,$dd)
	{
		try{
			$sql = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND STATUS='F' AND FOLLOWUP_TIME<:FOLLOWUP_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
			$prep->bindParam(":FOLLOWUP_TIME", $dd, PDO::PARAM_STR);
			$prep->execute();
			$res = $prep->fetch(PDO::FETCH_ASSOC);
			$cnt = $res['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $cnt;
	}
        public function countProfilesWithoutFollowupDate($agentName)
        {
                try{
                        $sql = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND FOLLOWUP_TIME='0000-00-00 00:00:00'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
                        $prep->execute();
                        $res = $prep->fetch(PDO::FETCH_ASSOC);
                        $cnt = $res['CNT'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $cnt;
        }
        public function getAllotedProfilesForAgents($agentArr)
        {
                try
                {
                        $agentList = implode("','", $agentArr);
                        $sql="SELECT PROFILEID, ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN('$agentList')";

                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        $res = array();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC)){
                                $res[$row['ALLOTED_TO']][] = $row['PROFILEID'];
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }
        public function getProfilesWithoutFollowupDate($agentName)
        {
                try{
                        $sql = "SELECT PROFILEID, ALLOT_TIME, FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO AND DATE(FOLLOWUP_TIME)='0000-00-00'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindParam(":ALLOTED_TO", $agentName, PDO::PARAM_STR);
                        $prep->execute();
						while($result=$prep->fetch(PDO::FETCH_ASSOC)){
							$profileData[] = $result;
						}
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $profileData;
        }
        public function getProfilesDetails($profileIds)
        {
                try{
            		$profileIds = implode(",", $profileIds);
                        $sql = "SELECT PROFILEID, ALLOT_TIME, FOLLOWUP_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID IN(".$profileIds.")"; 
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$profileData[] = $result;
			}
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $profileData;
        }
        public function getAllotedProfiles($agent)
        {
                try
                {
                        $sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO";
                        $prep=$this->db->prepare($sql);
                        $prep->bindParam(":ALLOTED_TO", $agent, PDO::PARAM_STR);
                        $prep->execute();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC)){
                        	$res[] = $row['PROFILEID'];                        	
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }

        public function getAllotedProfilesWithAllotTimeForAgent($agent){
        	try
        	{
        		$sql="SELECT PROFILEID, ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO=:ALLOTED_TO";
        		$prep=$this->db->prepare($sql);
        		$prep->bindParam(":ALLOTED_TO", $agent, PDO::PARAM_STR);
        		$prep->execute();
        		$res = array();
        		while($row=$prep->fetch(PDO::FETCH_ASSOC)){
        			$res[$row['PROFILEID']] = $row['ALLOT_TIME'];
        		}
        	}
        	catch(Exception $e){
        		throw new jsException($e);
        	}
        	return $res;
        }

	public function checkIfProfileAlloted($profileid){
    	try{
    		$sql = "SELECT PROFILEID FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
    		$prep = $this->db->prepare($sql);
    		$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
    		$prep->execute();
            $row = $prep->fetch(PDO::FETCH_ASSOC);
            if(isset($row['PROFILEID'])){
            	return $row['PROFILEID'];
            } else {
            	return 0;
            }
    	} catch(Exception $e) {
    		throw new jsException($e);
    	}
    }

        public function getAllotedExecForProfile($profileid)
        {
                try
                {
                        $sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                              $allotedTo =$result['ALLOTED_TO'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $allotedTo;
        }

       /**
     * @fn __getOnlineProfilesForAllocatedAgent
     * @brief get online allocated profiles for agent
     * @param $agent,$lastTimeOnlineDate(optional)
     * @return array of profileid
     */
        public function getOnlineProfilesForAllocatedAgent($agentsArray,$lastTimeOnlineDate="")
        {
                if(is_array($agentsArray))
                	$agentNamesStr = "'".implode("','",$agentsArray)."'";
                try
                {
                        /*$sql="SELECT A.userID AS PROFILEID,B.ALLOTED_TO AS AGENT FROM userplane.recentusers A JOIN incentive.MAIN_ADMIN B ON (A.userID = B.PROFILEID) WHERE B.ALLOTED_TO IN ($agentNamesStr)";
                        if($lastTimeOnlineDate)
                        	$sql = $sql." AND A.lastTimeOnline>=:lastTimeOnlineDate";
                        $prep = $this->db->prepare($sql);
                        if($lastTimeOnlineDate)
                        	$prep->bindParam(":lastTimeOnlineDate", $lastTimeOnlineDate, PDO::PARAM_STR);*/
			$sql ="SELECT B.PROFILEID,B.ALLOTED_TO AS AGENT FROM incentive.MAIN_ADMIN B WHERE B.ALLOTED_TO IN ($agentNamesStr)";
			$prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC)){
                        	$res[$row['PROFILEID']]['ACTION'] = 'ONLINE';
        			$res[$row['PROFILEID']]['PROFILEID'] = $row['PROFILEID'];
        			$res[$row['PROFILEID']]['AGENT'] = $row['AGENT'];
        		}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
        }
        /**
     * @fn __getAgentsForProfiles
     * @brief get agents for profiles
     * @param $profileid
     * @return agents list
     */
        public function getAgentsForProfile($profileidArr)
        {
                try
                {
                    $res = array();
                    if($profileidArr && is_array($profileidArr))
                    {
                    	$str = "(".implode(',', $profileidArr).")";
                    	$sql="SELECT PROFILEID,ALLOTED_TO AS AGENT FROM incentive.MAIN_ADMIN WHERE PROFILEID IN".$str;
                    	$prep = $this->db->prepare($sql);
                    	$prep->execute();
                
	                    while($row=$prep->fetch(PDO::FETCH_ASSOC)){
	    					$res[$row['PROFILEID']]['ACTION'] = 'FP';
	    					$res[$row['PROFILEID']]['PROFILEID'] = $row['PROFILEID'];
	    					$res[$row['PROFILEID']]['AGENT'] = $row['AGENT'];
	    				}
	    			}
    				return $res;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        
        public function getAllotedProfilesForAgentWithinDates($agents, $stDate, $endDate)
        {
            try{
                if($agents){
                    foreach($agents as $key=>$agentName){
                        if($key == 0)
                            $str = ":ALLOTED_TO".$key;
                        else
                            $str .= ",:ALLOTED_TO".$key;
                    }
                    unset($key);
                    unset($agentName);
                    $sql = "SELECT PROFILEID, ALLOT_TIME, ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO IN ($str) AND ALLOT_TIME >= :START_DATE AND ALLOT_TIME <= :END_DATE";
                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":START_DATE",$stDate,PDO::PARAM_STR);
                    $prep->bindValue(":END_DATE",$endDate,PDO::PARAM_STR);
                    foreach($agents as $key => $agentName){
                        $prep->bindValue(":ALLOTED_TO".$key, $agentName, PDO::PARAM_STR);
                    }
                    $prep->execute();
                    while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                        $result[$row["PROFILEID"]] = $row["ALLOT_TIME"];
                    }
                    return $result;
                }
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }
        public function getIST($dateTime='')
        {
                if(!$dateTime)
                        $dateTime =date("Y-m-d H:i:s");
                $sql = "SELECT CONVERT_TZ('$dateTime','SYSTEM','right/Asia/Calcutta') as time";
                $res = $this->db->prepare($sql);
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                        $dateTime = $row['time'];
                return $dateTime;
        }
}
?>
