<?php
class incentive_HISTORY extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function getProfileAllotedCount($uname)
	{
		try
		{
			$sql = "SELECT count(*) as cnt FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = :USERNAME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":USERNAME",$uname,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$allotedCount=$result['cnt'];

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $allotedCount;
	}
	public function fetchHistoryLastEntryDt($profileid, $agentName='',$entryDt='')
	{
		try
		{
			$sql = "SELECT ENTRY_DT FROM incentive.HISTORY WHERE ";
			if($agentName)
				$sql .=" ENTRYBY =:ENTRYBY AND ENTRY_DT>=:ENTRY_DT AND ";
			$sql .=" PROFILEID = :PROFILEID ORDER BY ENTRY_DT DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			if($agentName){
				$prep->bindValue(":ENTRYBY",$agentName,PDO::PARAM_STR);
				$prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
			}
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$entryDt=$result['ENTRY_DT'];

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $entryDt;

	}
	public function get($profileid,$fields,$whereClause,$orderBy,$limit)
	{
		try
		{
			$sql = "SELECT $fields FROM incentive.HISTORY";
			if($whereClause)
				$sql.=" WHERE $whereClause";
			if($orderBy)
				$sql.=" ORDER BY $orderBy";
			if($limit)
				$sql.=$limit;
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function addAllocationHistory($paramArr=array())
	{
		try
		{
			foreach($paramArr as $key=>$val)
				${$key} = $val;

			if(!$WILL_PAY)
				$WILL_PAY='';
			if(!$REASON)
				$REASON='';
			if(!$MODE)
				$MODE='';
			$ENTRY_DT =date("Y-m-d H:i:s");

			$sql ="INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES (:PROFILEID,:USERNAME,:ENTRYBY,:MODE,:DISPOSITION,:VALIDATION,:COMMENT,:ENTRY_DT)";
			$res = $this->db->prepare($sql);

			$res->bindParam(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
			$res->bindParam(":USERNAME", $USERNAME, PDO::PARAM_STR);
			$res->bindParam(":ENTRYBY", $ALLOTED_BY, PDO::PARAM_STR);
			$res->bindParam(":MODE", $MODE, PDO::PARAM_STR);
			$res->bindParam(":DISPOSITION", $WILL_PAY, PDO::PARAM_STR);
			$res->bindParam(":VALIDATION", $REASON, PDO::PARAM_STR);
			$res->bindParam(":COMMENT", $COMMENTS, PDO::PARAM_STR);
			$res->bindParam(":ENTRY_DT", $ENTRY_DT, PDO::PARAM_STR); 

			$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function getLastDisposingAgent($profileid)
	{
		try
		{
			$sql = "SELECT ENTRYBY FROM incentive.HISTORY WHERE PROFILEID = :PROFILEID ORDER BY ENTRY_DT DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$entryBy=$result['ENTRYBY'];
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $entryBy;
	}
	public function getLastDispositionDetails($profileid,$fields='')
	{
		try
		{
			if(!$fields)
				$fields='*';
			$sql = "SELECT $fields FROM incentive.HISTORY WHERE PROFILEID = :PROFILEID ORDER BY ENTRY_DT DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function checkLast15DaysDisposition($profileid, $allotedTo, $purchaseDate)
	{
		try
		{
			$sql = "SELECT COUNT(*) AS CNT FROM incentive.HISTORY WHERE PROFILEID=:PROFILEID AND ENTRYBY=:ENTRYBY AND DATEDIFF(:CHECKING_TIME,ENTRY_DT)<15 AND DATEDIFF(:CHECKING_TIME,ENTRY_DT)>=0";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRYBY",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":CHECKING_TIME",$purchaseDate,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				$count =$result['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $count;
	}
	public function checkDispositionStatus($profileid, $allotedTo, $allotTime, $purchaseTime)
	{
		try
		{
			$sql = "SELECT COUNT(*) AS CNT FROM incentive.HISTORY WHERE PROFILEID=:PROFILEID AND ENTRYBY=:ENTRYBY AND ENTRY_DT>=:ALLOT_TIME AND ENTRY_DT<:PURCHASE_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRYBY",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
			$prep->bindValue(":PURCHASE_TIME",$purchaseTime,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				$cnt =$result['CNT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $cnt;
	}

	public function getLastHandledDateForProfile($profileid, $agent){
		try
		{
			$sql = "SELECT MAX(ENTRY_DT) AS ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID=:PROFILEID AND ENTRYBY=:ENTRYBY";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRYBY",$agent,PDO::PARAM_INT);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				$entryDt =$result['ENTRY_DT'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $entryDt;
	}
	/*
	This function returns the maximum ENTRY_DT corresponding to a profileid alloted to a user for which the disposition is Visits Done and entry date lies between 2 given dates
	@param - usernmae array, start date, end date, profileid(optional)
	@return - result set array
	*/
	public function getFreshVisitDoneDataForExecs($execArray,$start_dt,$end_dt,$profileid="")
	{
		if(!$execArray || !is_array($execArray) || !$start_dt || !$end_dt)
			throw new jsException("","EXEC_ARRAY OR START DATE OR END DATE IS BLANK IN getFreshVisitDoneDataForExecs() of incentive_HISTORY.class.php");

		$i=0;
		$execStr = "";
		foreach($execArray as $k=>$v)
		{
			$execStr = $execStr.":PARAM".$i.",";
			$i++;
		}
		$execStr = rtrim($execStr,",");

		try
		{
			$sql = "SELECT min(ENTRY_DT) AS ENTRY_DT,ENTRYBY,PROFILEID,USERNAME FROM incentive.HISTORY WHERE ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND ENTRYBY IN (".$execStr.") AND DISPOSITION = :DISPOSITION";
			if($profileid)
				$sql = $sql." AND PROFILEID = :PROFILEID";
			$sql = $sql." GROUP BY ENTRYBY,PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DISPOSITION","FVD",PDO::PARAM_STR);
			$prep->bindValue(":DATE1",$start_dt,PDO::PARAM_STR);
			$prep->bindValue(":DATE2",$end_dt,PDO::PARAM_STR);
			if($profileid)
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$i=0;
			foreach($execArray as $k=>$v)
			{
				$prep->bindValue(":PARAM".$i,$v,PDO::PARAM_STR);
				$i++;
			}
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $output;
	}
	public function getVisitDoneDataForExecs($execArray,$start_dt,$end_dt,$profileid="")
	{
		if(!$execArray || !is_array($execArray) || !$start_dt || !$end_dt)
			throw new jsException("","EXEC_ARRAY OR START DATE OR END DATE IS BLANK IN getVisitDoneDataForExecs() of incentive_HISTORY.class.php");

		$i=0;
		$execStr = "";
		foreach($execArray as $k=>$v)
		{
			$execStr = $execStr.":PARAM".$i.",";
			$i++;
		}
		$execStr = rtrim($execStr,",");

		try
		{
			$sql = "SELECT max(ENTRY_DT) AS ENTRY_DT,ENTRYBY,PROFILEID,USERNAME FROM incentive.HISTORY WHERE ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND ENTRYBY IN (".$execStr.") AND DISPOSITION = :DISPOSITION";
			if($profileid)
				$sql = $sql." AND PROFILEID = :PROFILEID";
			$sql = $sql." GROUP BY ENTRYBY,DATE(ENTRY_DT),PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DISPOSITION","FVD",PDO::PARAM_STR);
			$prep->bindValue(":DATE1",$start_dt,PDO::PARAM_STR);
			$prep->bindValue(":DATE2",$end_dt,PDO::PARAM_STR);
			if($profileid)
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$i=0;
			foreach($execArray as $k=>$v)
			{
				$prep->bindValue(":PARAM".$i,$v,PDO::PARAM_STR);
				$i++;
			}
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$output[] = $row;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $output;
	}

	public function getAgentAllotedProfileFreshVisitArray($agent, $profileArray){

		$agentAllotedProfileFreshVisitArray = array();
		foreach($profileArray as $key=>$value){
			try{
				$sql = "SELECT ENTRY_DT, USERNAME FROM incentive.HISTORY WHERE PROFILEID = :PROID AND ENTRY_DT>=:DATE1 AND ENTRY_DT<=:DATE2 AND ENTRYBY IN (:AGENT) AND DISPOSITION = 'FVD' ORDER BY ENTRY_DT ASC";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROID", $value['PROFILEID'], PDO::PARAM_INT);
				$prep->bindValue(":DATE1", $value['ALLOT_TIME'], PDO::PARAM_STR);
				$prep->bindValue(":DATE2", $value['DE_ALLOCATION_DT'], PDO::PARAM_STR);
				$prep->bindValue(":AGENT", $agent, PDO::PARAM_STR);
				$prep->execute();
				while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
					$agentAllotedProfileFreshVisitArray[] = array('PROFILEID'=>$value['PROFILEID'], 'ENTRY_DT' => $row['ENTRY_DT'], 'USERNAME'=>$row['USERNAME']);
				}
			}
			catch(Exception $e){
				throw new jsException($e);
			}
		}

		return $agentAllotedProfileFreshVisitArray;
	}

	public function getCountOfDisposition($profileid,$disposition='')
        {
                try
                {
                        if(!$disposition)
	                        $sql = "SELECT COUNT(*) as CNT FROM incentive.HISTORY WHERE PROFILEID = :PROFILEID";
			else
				$sql = "SELECT COUNT(*) as CNT FROM incentive.HISTORY WHERE PROFILEID = :PROFILEID AND DISPOSITION=:DISPOSITION";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			if($disposition)
				$prep->bindValue(":DISPOSITION",$disposition,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        return $result['CNT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getCountOfDispositionArr($profileid)
        {
                try
                {
                        $sql = "SELECT COUNT(*) as CNT,DISPOSITION FROM incentive.HISTORY WHERE PROFILEID = :PROFILEID GROUP BY DISPOSITION";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$dataArr[$result['DISPOSITION']] =$result['CNT'];
			return $dataArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getHistoryArray($profileid,$fields,$whereClause,$orderBy='',$limit='')
        {
                try
                {
                        $sql = "SELECT $fields FROM incentive.HISTORY WHERE $whereClause";
                        if($orderBy)
                                $sql.=" ORDER BY $orderBy";
                        if($limit)
                                $sql.=$limit;
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $dataArr[]=$result;
                        return $dataArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        
        public function getHistoryForProfileIds($profileIds, $stDate, $endDate)
        {
            try{
                if($profileIds){
                    $sql = "SELECT PROFILEID, DISPOSITION, ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID IN ($profileIds) AND ENTRY_DT >= :START_DATE AND ENTRY_DT <= :END_DATE AND DISPOSITION != 'MA'";
                    $prep = $this->db->prepare($sql);
                    $prep->bindValue(":START_DATE", $stDate, PDO::PARAM_STR);
                    $prep->bindValue(":END_DATE", $endDate, PDO::PARAM_STR);
                    $prep->execute();
                    while($row = $prep->fetch(PDO::FETCH_ASSOC))
                    {
                        $temp["DISPOSITION"] = $row["DISPOSITION"];
                        $temp["ENTRY_DT"] = $row["ENTRY_DT"];
                        $result[$row["PROFILEID"]][] = $temp;
                    }
                    return $result;
                }
            } catch (Exception $ex) {
                throw new jsException($ex);
            }
        }

}
?>
