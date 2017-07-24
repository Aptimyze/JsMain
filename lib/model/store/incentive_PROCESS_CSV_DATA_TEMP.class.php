<?php
class incentive_PROCESS_CSV_DATA_TEMP extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.PROCESS_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
        
        public function delete($process)
	{
		try
                {
                        $sql="delete from incentive.PROCESS_CSV_DATA_TEMP where PROCESS =:PROCESS";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
	public function insertProfile($profileid, $process)
        {
                try
                {
                        $sql = "INSERT IGNORE INTO incentive.PROCESS_CSV_DATA_TEMP (PROFILEID,PROCESS) VALUES(:PROFILEID,:PROCESS)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getProfiles()
        {
                try
                {
                        $sql ="SELECT PROFILEID FROM incentive.PROCESS_CSV_DATA_TEMP ORDER BY ENTRY_DT DESC";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profileArr[] = $result['PROFILEID'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profileArr;
        }

        public function getAllProfilesCount($process)
        {
                try
                {
                        $sql ="SELECT count(*) as CNT FROM incentive.PROCESS_CSV_DATA_TEMP where PROCESS = :PROCESS";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $cnt = $result['CNT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $cnt;
        }
	/*
        public function getLatestProfilesCount($max_dt,$process)
        {
                try
                {
                        $sql ="SELECT count(*) as CNT FROM incentive.PROCESS_CSV_DATA_TEMP WHERE ENTRY_DT=:MAX_DT AND PROCESS=:PROCESS";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":MAX_DT",$max_dt,PDO::PARAM_STR);
                        $prep->bindValue(":PROCESS",$process,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $cnt = $result['CNT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $cnt;
	}*/



        // delete given set of profiles
        public function removeProfiles($profileArr, $process="")
        {
                try
                {
			if(is_array($profileArr))
				$profileStr =@implode(",",$profileArr);
			if(!$profileStr)
				return;		
                        $sql ="delete from incentive.PROCESS_CSV_DATA_TEMP WHERE PROFILEID IN($profileStr)";
                        if($process!=""){
                            $sql .=" and PROCESS = '$process'";
                        }
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }


	
}
?>
