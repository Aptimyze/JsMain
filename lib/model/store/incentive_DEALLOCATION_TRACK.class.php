<?php
class incentive_DEALLOCATION_TRACK extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
        public function insertDeAllocationEntry($profile,$process,$username="",$dateTime,$deallocated_by="SYSTEM")
        {
                try
                {
			if(!$deallocated_by)
				$deallocated_by='SYSTEM';
			//$dateTime=date('Y-m-d H:i:s',time());
			//if($process=="RELEASE_PROFILE")
				$sql = "INSERT INTO incentive.DEALLOCATION_TRACK(PROFILEID,PROCESS_NAME,DEALLOCATION_DT,ALLOTED_TO,DEALLOCATED_BY) VALUES(:PROFILEID,:PROCESS,:DATETIME,:USER,:DEALLOCATED_BY)";
			//else	
			//	$sql = "INSERT INTO incentive.DEALLOCATION_TRACK(PROFILEID,PROCESS_NAME,DEALLOCATION_DT,ALLOTED_TO) VALUES(:PROFILEID,:PROCESS,:DATETIME,'')";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profile,PDO::PARAM_STR);
			$prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
			$prep->bindValue(":DATETIME",$dateTime,PDO::PARAM_STR);
			//if($process=="RELEASE_PROFILE")
				$prep->bindValue(":USER",$username,PDO::PARAM_STR);
				$prep->bindValue(":DEALLOCATED_BY",$deallocated_by,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function getLastDayDeallocatedProfiles()
	{
		try
                {
			$today=date("Y-m-d",time());
			$today=$today." 00:00:00";
                        $sql = "SELECT ID,PROFILEID FROM incentive.DEALLOCATION_TRACK WHERE DEALLOCATION_DT>=:DEALLDATE AND ALLOTED_TO=''";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DEALLDATE",$today,PDO::PARAM_STR);
                        $prep->execute();
			$i=0;
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profiles[$i]['PROFILEID']= $result['PROFILEID'];
				$profiles[$i]['ID']= $result['ID'];
				$i++;	
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $profiles;
	}
	public function updateDeAllocatedAgent($profile,$agent)
	{
		try
                {
                        $sql = "UPDATE incentive.DEALLOCATION_TRACK SET ALLOTED_TO=:ALLOTED_TO WHERE PROFILEID=:PROFILEID AND ID=:ID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profile['PROFILEID'],PDO::PARAM_STR);
                        $prep->bindValue(":ALLOTED_TO",$agent,PDO::PARAM_STR);
			$prep->bindValue(":ID",$profile['ID'],PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
	public function getManualReleasedProfiles($entryDate)
	{
                try
                {
                        $sql = "SELECT PROFILEID,ALLOTED_TO,DEALLOCATION_DT FROM incentive.DEALLOCATION_TRACK WHERE DEALLOCATION_DT>:DEALLOCATION_DT AND PROCESS_NAME IN('RELEASE_PROFILE','NO_LONGER_WORKING') ORDER BY DEALLOCATION_DT ASC";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":DEALLOCATION_DT",$entryDate,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profiles[]= $result;
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $profiles;
	}
        public function getLastDeAllocationId($profileid,$allotedTo)
        {
                try
                {
                        $sql = "SELECT ID from incentive.DEALLOCATION_TRACK where PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO ORDER BY ID DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $id =$result['ID'];
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $id;
        }
        public function checkValidDeAllocation($lastDeAllocationId, $allotTime, $deAllocationDt,$manualAllot='')
        {
                try
                {
                        $sql = "SELECT PROFILEID from incentive.DEALLOCATION_TRACK where ID=:ID AND DATE(DEALLOCATION_DT)<=:DEALLOCATION_DATE";
			if($manualAllot)
				$sql .=" AND DEALLOCATION_DT<=:ALLOT_TIME";	
			else
				$sql .=" AND DEALLOCATION_DT>:ALLOT_TIME";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ID",$lastDeAllocationId,PDO::PARAM_INT);
			$prep->bindValue(":DEALLOCATION_DATE",$deAllocationDt,PDO::PARAM_STR);
                        $prep->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
                        $prep->execute();
                        if($result=$prep->fetch(PDO::FETCH_ASSOC))
                        	return $result['PROFILEID']; 
			return;
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
        }

        public function getProfilesForReallocationWithinRange($startDt, $endDt, $subMethod)
        {
        	$startDt = date("Y-m-d H:i:s", (strtotime($startDt)+86400));
        	$endDt = date("Y-m-d H:i:s", (strtotime($endDt)+86400));
        	try
        	{
        		$sql = "SELECT D.PROFILEID FROM incentive.DEALLOCATION_TRACK D LEFT JOIN incentive.CRM_DAILY_ALLOT C USING(PROFILEID) WHERE D.PROCESS_NAME=:PROCESS_NAME AND D.DEALLOCATION_DT>=:START_DT AND D.DEALLOCATION_DT<=:END_DT";
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue(":START_DT",$startDt,PDO::PARAM_STR);
        		$prep->bindValue(":END_DT",$endDt,PDO::PARAM_STR);
			$prep->bindValue(":PROCESS_NAME",$subMethod,PDO::PARAM_STR);
        		$prep->execute();
        		while($row=$prep->fetch(PDO::FETCH_ASSOC))
        		{
        			$res[] = $row['PROFILEID'];
        		}
        	}
        	catch(Exception $e)
        	{
        		throw new jsException($e);
        	}
        	return $res;
        }
} 
