<?php

class jsadmin_SCREENING_LOG extends TABLE {
	
	public function __construct($dbName="")
	{
		parent::__construct($dbName);
	}
	
	public function getProfilesScreenedAfter($screenedTime)
	{
		try
		{
			$sql = "SELECT PROFILEID FROM jsadmin.SCREENING_LOG WHERE SCREENED_TIME>=:SCREENED_TIME GROUP BY PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":SCREENED_TIME",$screenedTime,PDO::PARAM_INT);
			$prep->execute();				
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$profiles[]=$result["PROFILEID"];
			}
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
		return $profiles;
	}
	public function earlierScreened($profileid)
	{
		try
		{
			$time=date("Y-m-d h:i:s",time()-24*60*60);
			$sql = "SELECT COUNT(*) AS CNT FROM jsadmin.SCREENING_LOG WHERE PROFILEID=:PROFILEID AND SCREENED_TIME <:SCREENED_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SCREENED_TIME",$time,PDO::PARAM_STR);
			$prep->execute();				
			if($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				if($result["CNT"]>0)
					return 1;
				else
					return 0;
			}
			else
				return 0;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
	public function getScreeningCount($profileid)
	{
		try
		{
			$time=date("Y-m-d h:i:s",time()-6*24*60*60);
			$sql = "SELECT COUNT(*) AS CNT FROM jsadmin.SCREENING_LOG WHERE PROFILEID=:PROFILEID AND SCREENED_TIME >=:SCREENED_TIME";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":SCREENED_TIME",$time,PDO::PARAM_STR);
			$prep->execute();				
			$result = $prep->fetch(PDO::FETCH_ASSOC);
			if($result["CNT"])
				return 1;
			else
				return 0;
		}
		catch (PDOException $e)
		{
			throw new jsException($e);
		}
	}
        public function getLastHourScreenedProfiles($screenedTimeStart,$screenedTimeEnd)
        {
                try
                {
                        $sql = "SELECT distinct PROFILEID FROM jsadmin.SCREENING_LOG WHERE SCREENED_TIME>=:SCREENED_TIME_START AND SCREENED_TIME<=:SCREENED_TIME_END";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SCREENED_TIME_START",$screenedTimeStart,PDO::PARAM_STR);
			$prep->bindValue(":SCREENED_TIME_END",$screenedTimeEnd,PDO::PARAM_STR);
                        $prep->execute();
                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                $profiles[]=$result["PROFILEID"];
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
        public function lastScreenedTime($profileid,$screenedTimeHandled)
        {
                try
                {
                        $sql = "SELECT SCREENED_TIME FROM jsadmin.SCREENING_LOG WHERE PROFILEID=:PROFILEID AND SCREENED_TIME <=:SCREENED_TIME ORDER BY ID DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":SCREENED_TIME",$screenedTimeHandled,PDO::PARAM_STR);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                return $result['SCREENED_TIME'];
			return;
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
        }
	public function getScreenedMaxDate()
	{
                try
                {
                        $sql = "SELECT MAX(SCREENED_TIME) as SCREENED_TIME FROM jsadmin.SCREENING_LOG";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                return $result['SCREENED_TIME'];
                        return;
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
	}

}
