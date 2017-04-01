<?php
class CRM_DAILY_ALLOT extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function fetchProfilesAlloted($pid,$username)
	{
		try
		{
			$sql="SELECT MAX( ID ) AS ID FROM incentive.CRM_DAILY_ALLOT WHERE ALLOTED_TO = :USERNAME AND PROFILEID=:PID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":PID",$pid,PDO::PARAM_STR);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$id_arr[] = $result['ID'];
			}

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $id_arr;

	}
	public function fetchProfiles($pid,$name)
	{
		try
		{
			$sql="SELECT ID,ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID=:PID AND ALLOTED_TO=:NAME ORDER BY ID DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$pid,PDO::PARAM_STR);
			$prep->bindValue(":NAME",$name,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$id_arr['ID'] = $result['ID'];
				$id_arr['ALLOTED_TO']=$result['ALLOTED_TO'];
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $id_arr;
	}
	public function deleteProfile($id,$alloted_to="")
	{
		try
		{
			$sql="DELETE FROM incentive.CRM_DAILY_ALLOT WHERE ID=:ID";
			if($alloted_to)
			{
				$sql.=" AND ALLOTED_TO=:USERNAME";
			}
			$prep = $this->db->prepare($sql);
			if($alloted_to)
			{
				$prep->bindValue(":USERNAME",$alloted_to,PDO::PARAM_STR);
			}
			$prep->bindValue(":ID",$id,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	/*public function expiredProfileCount($relax,$pid)
	{
		try
		{
			 $sql="SELECT COUNT(*) AS CNT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID = ':PID' AND DATE_ADD( ALLOT_TIME, INTERVAL( :RELAX + RELAX_DAYS ) DAY ) >=NOW()";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$pid,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$cnt=$result['PID'];
			}


		}
		catch(Exception $e)
		{
			throw new jsException();
		}
		return $cnt;
	}*/
	public function checkExpiry($profileid)
	{
		try
		{
			$sql="SELECT COUNT(*) AS CNT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID = :PROFILEID AND DE_ALLOCATION_DT>=now()";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
			$cnt=$result['CNT'];
			if($cnt>0)
				return false;
			return true;
		}
		catch(Exception $e)
		{
			throw new jsException();
		}
	}
	public function getLastAllocationDetails($profileid)
	{
		try
		{
			$sql="SELECT * FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID=:PID ORDER BY ID DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$profileid,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;

	}
	public function insertProfile($paramArr=array())
	{
		try
		{
			foreach($paramArr as $key=>$val)
				${$key} = $val;

			if(!$RELAX_DAYS)
				$RELAX_DAYS =0;
			if(!$ALLOCATION_DAYS)
				$ALLOCATION_DAYS =0;

			$sql = "INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT,RELAX_DAYS,ALLOCATION_DAYS) VALUES (:PROFILEID,:ALLOTED_TO,:ALLOT_TIME,:DE_ALLOCATION_DT,:RELAX_DAYS,:ALLOCATION_DAYS)";
			$res = $this->db->prepare($sql);

			$res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_STR);
			$res->bindValue(":ALLOTED_TO", $ALLOTED_TO, PDO::PARAM_STR);
			$res->bindValue(":ALLOT_TIME", $ALLOT_TIME, PDO::PARAM_STR);
			$res->bindValue(":DE_ALLOCATION_DT", $DE_ALLOCATION_DT, PDO::PARAM_STR);
			$res->bindValue(":RELAX_DAYS", $RELAX_DAYS, PDO::PARAM_INT);
			$res->bindValue(":ALLOCATION_DAYS", $ALLOCATION_DAYS, PDO::PARAM_INT);
			$res->execute();
			return true;
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
	public function updateRelaxDays($relaxDays,$profileid,$agentName)
	{
		try
		{
			$sql="UPDATE incentive.CRM_DAILY_ALLOT SET RELAX_DAYS=RELAX_DAYS+:RELAX_DAYS,DE_ALLOCATION_DT=DATE_ADD(DE_ALLOCATION_DT,INTERVAL :RELAX_DAYS DAY) WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO ORDER BY ID DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->bindValue(":ALLOTED_TO",$agentName,PDO::PARAM_STR);
			$prep->bindValue(":RELAX_DAYS",$relaxDays,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}

	}
	public function updateAllotedAgent($profileid,$executive,$allotedDt)
	{
		try
		{
			$startTime =$allotedDt." 00:00:00";
			$endTime   =$allotedDt." 23:59:59";
			$sql = "UPDATE incentive.CRM_DAILY_ALLOT SET ALLOTED_TO=:ALLOTED_TO WHERE PROFILEID=:PROFILEID AND ALLOT_TIME>=:START_TIME AND ALLOT_TIME<=:END_TIME";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$res->bindValue(":ALLOTED_TO",$executive,PDO::PARAM_STR);
			$res->bindValue(":START_TIME",$startTime,PDO::PARAM_STR);
			$res->bindValue(":END_TIME",$endTime,PDO::PARAM_STR);
			$res->execute();
			return true;
		}
		catch(PDOException $e){
			throw new jsException($e);
		}
	}
	public function getReleasedAllocationDetails($id)
	{
		try
		{
			$sql="SELECT * FROM incentive.CRM_DAILY_ALLOT WHERE ID=:PID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$id,PDO::PARAM_INT);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $result;
	}
	public function getProfilesAllottedInDateRange($stDt,$endDt,$agents,$forRCBMis=false)
	{
		try
		{
			$count = count($agents);
			$in_params = trim(str_repeat('?, ', $count), ', ');
			$sql="SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME >'$stDt' AND ALLOT_TIME < '$endDt' AND ALLOTED_TO IN({$in_params})";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":AGENTS",$agents);
			$prep->execute($agents);
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
			{
				if($forRCBMis == true){
					$profiles[$result['ALLOTED_TO']][] = array("PROFILEID"=>$result['PROFILEID'],"ALLOT_TIME"=>$result['ALLOT_TIME']);
				}
				else{
					$allotDate=date("Y-m-d",JSstrToTime($result['ALLOT_TIME']));
					$profiles[$allotDate][]=$result['PROFILEID'];
				}
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
	public function getValidAllocationForPayment($profileid, $entryDateTime)
	{
		try
		{
			$entryDate =date("Y-m-d",JSstrToTime("$entryDateTime"));
			$sql="select ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT where PROFILEID=:PROFILEID AND ALLOT_TIME<=:ENTRY_DATE_TIME AND DE_ALLOCATION_DT>=:ENTRY_DATE ORDER BY ID DESC LIMIT 1";

			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRY_DATE_TIME",$entryDateTime,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE",$entryDate,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				return $result;
			return;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function getDeallocationDateForProfile($profileid, $allot_time){
		try
		{
			$sql="SELECT DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT where PROFILEID=:PROFILEID AND ALLOT_TIME=:ALLOT_TIME";

			$prep=$this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ALLOT_TIME",$allot_time,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$deallocationDate = $result['DE_ALLOCATION_DT'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $deallocationDate;
	}
        public function updateDeallocationDt($profileid,$executive,$deAllocationDt)
        {
                try
                {
                        $sql = "UPDATE incentive.CRM_DAILY_ALLOT SET REAL_DE_ALLOCATION_DT=:REAL_DE_ALLOCATION_DT WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO ORDER BY ID DESC LIMIT 1";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $res->bindValue(":ALLOTED_TO",$executive,PDO::PARAM_STR);
			$res->bindValue(":REAL_DE_ALLOCATION_DT",$deAllocationDt,PDO::PARAM_STR);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }

	/*
	This function returns the ALLOT_TIME and DE_ALLOCATION_DT corresponding a profileid assigned to a username
	@param - username and profileid
	@return - result set array
	*/
	public function getAllocationDates($username,$profileid)
	{
		if(!$username || !$profileid)
                        throw new jsException("","USERNAME OR PROFILEID IS BLANK IN getValidAllocationForVisitDone() of incentive_CRM_DAILY_ALLOT.class.php");

		try
		{
			$sql = "SELECT ALLOT_TIME,DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID = :PROFILEID AND ALLOTED_TO = :ALLOTED_TO ORDER BY ID DESC LIMIT 1";
			$prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":ALLOTED_TO",$username,PDO::PARAM_STR);
			$prep->execute();
			$result=$prep->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $result;
	}

	public function getAgentAllotedProfileArray($agent, $start_date, $end_date){

		$agentAllotedProfileArray = array();

		try{
			$sql = "SELECT PROFILEID, ALLOT_TIME, DE_ALLOCATION_DT FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME>=:DATE1 AND ALLOT_TIME<=:DATE2 AND ALLOTED_TO IN(:AGENT) AND DE_ALLOCATION_DT <> '0000-00-00' ORDER BY ALLOT_TIME ASC";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DATE1", $start_date, PDO::PARAM_STR);
			$prep->bindValue(":DATE2", $end_date, PDO::PARAM_STR);
			$prep->bindValue(":AGENT", $agent, PDO::PARAM_STR);
			$prep->execute();
			while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
				$agentAllotedProfileArray[] = array('PROFILEID'=>$row['PROFILEID'], 'ALLOT_TIME'=>$row['ALLOT_TIME'], 'DE_ALLOCATION_DT'=>($row['DE_ALLOCATION_DT']." 23:59:59"));
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}

		return $agentAllotedProfileArray;
	}
	public function getAllotedAgentToTransaction($profileid, $billing_dt)
	{
		try{
			$sql = "SELECT ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME<=:BILLING_DT AND IF(REAL_DE_ALLOCATION_DT, REAL_DE_ALLOCATION_DT, DE_ALLOCATION_DT)>=:BILLING_DT AND PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$prep->bindValue(":BILLING_DT", $billing_dt, PDO::PARAM_STR);
			$prep->execute();
			$row=$prep->fetch(PDO::FETCH_ASSOC);
			$res = $row['ALLOTED_TO'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}

		return $res;
	}
        public function updateExpectedDeallocationDt($profileid,$executive,$allotTime,$deAllocationDt)
        {
                try
                {
                        $sql = "UPDATE incentive.CRM_DAILY_ALLOT SET DE_ALLOCATION_DT=:DE_ALLOCATION_DT WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO AND ALLOT_TIME=:ALLOT_TIME AND DE_ALLOCATION_DT<:DE_ALLOCATION_DT";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $res->bindValue(":ALLOTED_TO",$executive,PDO::PARAM_STR);
			$res->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
                        $res->bindValue(":DE_ALLOCATION_DT",$deAllocationDt,PDO::PARAM_STR);
                        $res->execute();
                        return true;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }

    public function getAllotedAgentWithinRangeForProfiles($profilesArr)
	{
		try
		{
			$profileList = implode(',',$profilesArr);
			$entryDate =date("Y-m-d");
			$sql="select PROFILEID, ALLOTED_TO,ALLOT_TIME,DE_ALLOCATION_DT,REAL_DE_ALLOCATION_DT from incentive.CRM_DAILY_ALLOT where PROFILEID IN ($profileList)";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$output[$result['PROFILEID']] = array('ALLOTED_TO'=>$result['ALLOTED_TO'],'ALLOT_TIME'=>$result['ALLOT_TIME'],'DE_ALLOCATION_DT'=>$result['DE_ALLOCATION_DT'],'REAL_DE_ALLOCATION_DT'=>$result['REAL_DE_ALLOCATION_DT']);
			}
			return $output;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}
}
?>
