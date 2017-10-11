<?php
class MANUAL_ALLOT extends TABLE
{
	public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	
	public function fetchProfile($pid)
	{
		try
		{
			$sql="SELECT ID,ALLOTED_TO FROM incentive.MANUAL_ALLOT WHERE PROFILEID=:PID ORDER BY ID DESC LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$pid,PDO::PARAM_STR);
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
	public function deleteProfile($id)
	{
		try
		{
			$sql="DELETE FROM incentive.MANUAL_ALLOT WHERE ID=:ID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":ID",$id,PDO::PARAM_STR);
			$prep->execute();


		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function insertProfile($paramsArr)
	{
		try
                {
			foreach($paramsArr as $key=>$val)
                                ${$key} = $val;

                        $sql="INSERT INTO incentive.MANUAL_ALLOT(PROFILEID,ALLOT_TIME,ALLOTED_TO,COMMENTS,CALL_SOURCE,ALLOTED_BY,ENTRY_DT) VALUES(:PROFILEID,:ALLOT_TIME,:ALLOTED_TO,:COMMENTS,:CALL_SOURCE,:ALLOTED_BY,now())";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":ALLOT_TIME",$ALLOT_TIME,PDO::PARAM_STR);
                        $prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
			$prep->bindValue(":ALLOTED_TO",$ALLOTED_TO,PDO::PARAM_STR);
			$prep->bindValue(":COMMENTS",$COMMENTS,PDO::PARAM_STR);
			$prep->bindValue(":CALL_SOURCE",$CALL_SOURCE,PDO::PARAM_STR);
			$prep->bindValue(":ALLOTED_BY",$ALLOTED_BY,PDO::PARAM_STR);
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
                        $sql = "UPDATE incentive.MANUAL_ALLOT SET ALLOTED_TO=:ALLOTED_TO WHERE PROFILEID=:PROFILEID AND ALLOT_TIME>=:START_TIME AND ALLOT_TIME<=:END_TIME";
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
        public function getTotalAllocationCntForFieldSales($executivesArr)
        {
                if(!is_array($executivesArr))
                        throw new jsException("","executives arr is blank");
                try
                {
                        $executivesStr 	="'".@implode("','",$executivesArr)."'";
			/*$startDt 	=date("Y-m-d")." 00:00:00";
			$endDt 		=date("Y-m-d")." 23:59:59";*/
			// New logic for FS
                        $checkTime      =date("Y-m-d")." 05:30:00";
			$currTime	=date("Y-m-d H:i:s");
			if(strtotime($currTime)>=strtotime($checkTime)){
				$startDt =$checkTime;
				$endDt   =date('Y-m-d',time()+86400)." 05:30:00";
			}
			else{
                                $startDt =date('Y-m-d',time()-86400)." 05:30:00";;	
                                $endDt   =$checkTime;
			}

                        $sql="SELECT count(*) cnt, ALLOTED_TO FROM incentive.MANUAL_ALLOT WHERE CALL_SOURCE='FS' AND ALLOTED_BY='jstech' AND ALLOTED_TO IN($executivesStr) AND ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT GROUP BY ALLOTED_TO";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
			$prep->bindValue(":END_DT",$endDt,PDO::PARAM_STR);
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
        public function getManualAllotedProfiles($entryDate)
        {
                try
                {
                        $sql="select PROFILEID, ENTRY_DT from incentive.MANUAL_ALLOT WHERE ALLOTED_BY!='jstech' AND ENTRY_DT>:ENTRY_DT ORDER BY ENTRY_DT ASC";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ENTRY_DT",$entryDate,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profilesArr[] =$result;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profilesArr;
        }
	public function checkCurrentManualAllocation($profileid,$allotedTo)
	{
                try
                {
                        $sql="SELECT COUNT(*) AS CNT FROM incentive.MANUAL_ALLOT m JOIN incentive.MAIN_ADMIN ma ON m.PROFILEID=ma.PROFILEID WHERE m.PROFILEID=:PROFILEID AND m.ALLOTED_TO=:ALLOTED_TO AND m.ALLOT_TIME>=ma.ALLOT_TIME AND m.ALLOTED_BY!='jstech'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                        	$count =$result['CNT'];       
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $count;
	}

	public function getFieldSalesAgents($startDt,$endDt)
	{
		$agents = array();
                try
                {
                        $sql="SELECT ALLOTED_TO, PROFILEID FROM incentive.MANUAL_ALLOT WHERE CALL_SOURCE='FS' AND ALLOT_TIME BETWEEN :DATE1 AND :DATE2";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DATE1",$startDt,PDO::PARAM_STR);
						$prep->bindValue(":DATE2",$endDt,PDO::PARAM_STR);
                        $prep->execute();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC))
                        	$agents[$row['ALLOTED_TO']][] = $row['PROFILEID'];  
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $agents;
	}

	public function getAllotedAgentsDetailsInDateRange($startDt,$endDt)
	{
		$agents = array();
                try
                {
                        $sql="SELECT ALLOTED_TO, PROFILEID, CALL_SOURCE, ALLOT_TIME FROM incentive.MANUAL_ALLOT WHERE ALLOT_TIME>=:DATE1 AND ALLOT_TIME<=:DATE2 ORDER BY ALLOT_TIME DESC";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DATE1",$startDt,PDO::PARAM_STR);
						$prep->bindValue(":DATE2",$endDt,PDO::PARAM_STR);
                        $prep->execute();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC))
                        	$agents[$row['ALLOTED_TO']][] = array('PROFILEID' => $row['PROFILEID'], 'CALL_SOURCE' => $row['CALL_SOURCE'], 'ALLOT_TIME' => $row['ALLOT_TIME']);  
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $agents;
	}
    public function getDistinctFieldSalesAgents($startDt,$endDt)
    {
        try
        {
            $sql="SELECT DISTINCT(ALLOTED_TO) AS ALLOTED_TO FROM incentive.MANUAL_ALLOT WHERE CALL_SOURCE='FS' AND ALLOT_TIME>=:START_DT AND ALLOT_TIME<=:END_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DT",$endDt,PDO::PARAM_STR);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
                $res[$row['ALLOTED_TO']] = 1;  
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $res;
    }
    public function getAllocationStatus($allotedTo,$profileId,$startDt,$endDt)
    {
        try
        {
            $sql="SELECT CALL_SOURCE FROM incentive.MANUAL_ALLOT WHERE ALLOTED_TO=:ALLOTED_TO AND PROFILEID=:PROFILEID AND ALLOT_TIME>=:START_DT AND ALLOT_TIME<=:END_DT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
            $prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DT",$endDt,PDO::PARAM_STR);
            $prep->execute();
            $row=$prep->fetch(PDO::FETCH_ASSOC);
            $stat = $row['CALL_SOURCE'];
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $stat;
    }

    public function getAgentAllotedProfileArrayforRCBCallSource($allotedTo,$startDt,$endDt)
    {
        try
        {
            $startDt = date("Y-m-d", strtotime($startDt)) . " 00:00:00";
            $endDt = date("Y-m-d", strtotime($endDt)) . " 23:59:59";
            $sql="SELECT PROFILEID, ALLOT_TIME FROM incentive.MANUAL_ALLOT WHERE ALLOTED_TO=:ALLOTED_TO AND ALLOT_TIME>=:START_DT AND ALLOT_TIME<=:END_DT AND CALL_SOURCE IN ('RCB','WL')";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
            $prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
            $prep->bindValue(":END_DT",$endDt,PDO::PARAM_STR);
            $prep->execute();
            while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                $agentAllotedProfileArray[] = array('PROFILEID'=>$row['PROFILEID'], 'ALLOT_TIME'=>$row['ALLOT_TIME']);
            }
            return $agentAllotedProfileArray;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
        return $stat;
    }

}	
?>
