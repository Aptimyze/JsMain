<?php
class incentive_PROFILE_ALLOCATION_TECH extends TABLE
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
	public function getAllocationStatusOfProfile($profileid)
        {
                try
                {
                        $sql = "SELECT COUNT(*) AS COUNT FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $allotedCount=$result['COUNT'];
			if($allotedCount>0)
				return 1;
                	else
                        	return 0;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function insertProfile($profileid,$user_value,$status='N',$profile_type)
	{
		try
                {
			$date=date('Y-m-d',time());
                        $sql = "INSERT IGNORE INTO incentive.PROFILE_ALLOCATION_TECH (PROFILEID, ALLOTED_TO,HANDLED,ALLOT_DT,STATUS,PROFILE_TYPE) VALUES(:PROFILEID,:ALLOTED_TO,:HANDLED,:DATE,:STATUS,:PROFILE_TYPE)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->bindValue(":ALLOTED_TO",$user_value,PDO::PARAM_STR);
			$prep->bindValue(":HANDLED",'N',PDO::PARAM_STR);
			$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
			$prep->bindValue(":DATE",$date,PDO::PARAM_STR);
			$prep->bindValue(":PROFILE_TYPE",$profile_type,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

	}
	public function insertPreAllocationLog($profileid,$user_value,$level='',$analyticScore='',$lastLoginDt='')
        {
                try
                {
			if(!$analyticScore)
				$analyticScore='';
			if(!$level)
				$level='';
			if(!$lastLoginDt)
				$lastLoginDt='';	
                        $date=date('Y-m-d',time());
                        $sql = "INSERT INTO incentive.PRE_ALLOCATION_LOG (PROFILEID, ALLOTED_TO , ALLOT_DT, LEVEL, SCORE, LAST_LOGIN_DT) VALUES(:PROFILEID,:ALLOTED_TO,:DATE,:LEVEL,:SCORE,:LAST_LOGIN_DT)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->bindValue(":ALLOTED_TO",$user_value,PDO::PARAM_STR);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
			$prep->bindValue(":SCORE",$analyticScore,PDO::PARAM_INT);
			$prep->bindValue(":LAST_LOGIN_DT",$lastLoginDt,PDO::PARAM_STR);
			$prep->bindValue(":LEVEL",$level,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
	public function	getPreAllocatedProfiles($alloted_to)
	{
		try
                {
			$sql ="SELECT PROFILE_TYPE,PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO=:ALLOTED_TO AND HANDLED='N' AND STATUS='N' ORDER BY PROFILE_TYPE ASC ";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ALLOTED_TO",$alloted_to,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                        	$detailArr[] = $result;        
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
                return $detailArr;

	}
        public function getProfileType($profileStr)
        {
                try
                {
	 		if(!$profileStr)
        	         	throw new jsException("","no profileid passed");

	                $profileArr =@explode(",",$profileStr);
	                foreach($profileArr as $key=>$val)
	                        $str[] =":PROFILEID$key";
	                $newStr =@implode(",",$str);

                        $sql ="SELECT PROFILE_TYPE,PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID IN($newStr)";
                        $prep = $this->db->prepare($sql);
	                foreach($profileArr as $key=>$val)
        	                $prep->bindValue(":PROFILEID$key",$val,PDO::PARAM_STR);

                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
				$profileid		=$result['PROFILEID'];
                                $detailArr[$profileid]  =$result['PROFILE_TYPE'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
                return $detailArr;

        }
	public function updateHandledStatus($profileid)
	{
		try
                {
			$sql="UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED='Y' WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.PROFILE_ALLOCATION_TECH";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}	
	public function getDistinctExecutives()
	{
		try
                {
			$sql = "SELECT DISTINCT(ALLOTED_TO) AS USER FROM incentive.PROFILE_ALLOCATION_TECH";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
	                        $res[] = $result['USER'];
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
	}
	public function fetchProfilesForAgent($agent)
	{
		try
                {
			$sql = "SELECT PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO = :AGENT AND HANDLED='N'";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":AGENT",$agent,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
	                        $res[] = $result['PROFILEID'];
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
	}
	public function updateAllotedAgent($agent, $profileid)
	{
		try
                {
			$sql = "UPDATE incentive.PROFILE_ALLOCATION_TECH SET ALLOTED_TO=:AGENT WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":AGENT",$agent,PDO::PARAM_STR);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}	

	public function getFreshVisitTransferAllotDate($profileid){
		try
		{
			$sql = "SELECT ALLOT_DT FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID=:PROFILEID LIMIT 1";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$date = $result['ALLOT_DT'];
			}
		}catch(Exception $e){
			throw new jsException($e);
		}
		return $date;
	}
	public function fetchAllProfileIds()
	{
		try
                {
			$sql = "SELECT PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
	                        $res[] = $result['PROFILEID'];
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
	}
        public function getAllotedProfiles($profileIdArr)
        {
                try{
                        if(is_array($profileIdArr))
                        {
                                foreach($profileIdArr as $key=>$pid){
                                        if($key == 0)
                                                $str = ":PROFILEID".$key;
                                        else
                                                $str .= ",:PROFILEID".$key;
                                }
                                $sql = "SELECT distinct PROFILEID FROM incentive.PROFILE_ALLOCATION_TECH WHERE PROFILEID IN ($str) ";
                                $res=$this->db->prepare($sql);
                                unset($pid);
                                foreach($profileIdArr as $key=>$pid)
                                        $res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $result[] = $row['PROFILEID'];
                                return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getProfileAllotedCountArr($agentArr)
        {
                try{
                        if(is_array($agentArr))
                        {
                                foreach($agentArr as $key=>$agent){
                                        if($key == 0)
                                                $str = ":ALLOTED_TO".$key;
                                        else
                                                $str .= ",:ALLOTED_TO".$key;
                                }
                                $sql = "SELECT count(*) as cnt,ALLOTED_TO FROM incentive.PROFILE_ALLOCATION_TECH WHERE ALLOTED_TO IN($str) GROUP BY ALLOTED_TO";
                                $res=$this->db->prepare($sql);
                                unset($agent);
                                foreach($agentArr as $key=>$agent)
                                        $res->bindValue(":ALLOTED_TO$key", $agent, PDO::PARAM_STR);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $result[$row['ALLOTED_TO']] = $row['cnt'];
                                return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function insertProfileTemp($profileid,$user_value,$status='N',$profile_type)
        {
                try
                {
                        $date=date('Y-m-d',time());
                        $sql = "INSERT IGNORE INTO incentive.PROFILE_ALLOCATION_TECH_TEMP (PROFILEID, ALLOTED_TO,HANDLED,ALLOT_DT,STATUS,PROFILE_TYPE) VALUES(:PROFILEID,:ALLOTED_TO,:HANDLED,:DATE,:STATUS,:PROFILE_TYPE)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->bindValue(":ALLOTED_TO",$user_value,PDO::PARAM_STR);
                        $prep->bindValue(":HANDLED",'N',PDO::PARAM_STR);
                        $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
                        $prep->bindValue(":PROFILE_TYPE",$profile_type,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
        public function getProfileAllotedCountArrTemp($agentArr)
        {
                try{
                        if(is_array($agentArr))
                        {
                                foreach($agentArr as $key=>$agent){
                                        if($key == 0)
                                                $str = ":ALLOTED_TO".$key;
                                        else
                                                $str .= ",:ALLOTED_TO".$key;
                                }
                                $sql = "SELECT count(*) as cnt,ALLOTED_TO FROM incentive.PROFILE_ALLOCATION_TECH_TEMP WHERE ALLOTED_TO IN($str) GROUP BY ALLOTED_TO";
                                $res=$this->db->prepare($sql);
                                unset($agent);
                                foreach($agentArr as $key=>$agent)
                                        $res->bindValue(":ALLOTED_TO$key", $agent, PDO::PARAM_STR);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $result[$row['ALLOTED_TO']] = $row['cnt'];
                                return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function insertPreAllocationLogTemp($profileid,$user_value,$level='',$analyticScore='',$lastLoginDt='')
        {
                try
                {
                        if(!$analyticScore)
                                $analyticScore='';
                        if(!$level)
                                $level='';
                        if(!$lastLoginDt)
                                $lastLoginDt='';
                        $date=date('Y-m-d',time());
                        $sql = "INSERT INTO incentive.PRE_ALLOCATION_LOG_TEMP (PROFILEID, ALLOTED_TO , ALLOT_DT, LEVEL, SCORE, LAST_LOGIN_DT) VALUES(:PROFILEID,:ALLOTED_TO,:DATE,:LEVEL,:SCORE,:LAST_LOGIN_DT)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->bindValue(":ALLOTED_TO",$user_value,PDO::PARAM_STR);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
                        $prep->bindValue(":SCORE",$analyticScore,PDO::PARAM_INT);
                        $prep->bindValue(":LAST_LOGIN_DT",$lastLoginDt,PDO::PARAM_STR);
                        $prep->bindValue(":LEVEL",$level,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }

}
?>
