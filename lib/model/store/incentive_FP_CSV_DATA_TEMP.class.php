<?php
class incentive_FP_CSV_DATA_TEMP extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.FP_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
	public function insertProfile($profileid, $entryDt, $process)
        {
                try
                {
                        $sql = "INSERT IGNORE INTO incentive.FP_CSV_DATA_TEMP (PROFILEID,ENTRY_DT,PROCESS) VALUES(:PROFILEID,:ENTRY_DT,:PROCESS)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
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
                        $sql ="SELECT PROFILEID FROM incentive.FP_CSV_DATA_TEMP ORDER BY ENTRY_DT DESC";
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
        public function getMaxDate()
        {
                try
                {
                        $sql ="SELECT MAX(ENTRY_DT) as MAX_DT FROM incentive.FP_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $max_dt = $result['MAX_DT'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $max_dt;
        }
        public function getAllProfilesCount()
        {
                try
                {
                        $sql ="SELECT count(*) as CNT FROM incentive.FP_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
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
        public function getLatestProfilesCount($max_dt)
        {
                try
                {
                        $sql ="SELECT count(*) as CNT FROM incentive.FP_CSV_DATA_TEMP WHERE ENTRY_DT=:MAX_DT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":MAX_DT",$max_dt,PDO::PARAM_STR);
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
        public function getScore($profileid)
        {
                try
                {	return;
                        $sql ="SELECT ANALYTIC_SCORE FROM incentive.FP_CSV_DATA_TEMP WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                	return $result['ANALYTIC_SCORE'];      
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	// Do not call filter
        public function removeDoNotCallProfiles()
        {
                try
		{
                        $sql ="delete incentive.SALES_CSV_DATA_TEMP.* from incentive.SALES_CSV_DATA_TEMP , incentive.DO_NOT_CALL d where incentive.SALES_CSV_DATA_TEMP.PROFILEID=d.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}

	// Payment done filter
        public function removePaidWithin30Days()
        {
                try
		{
                        $sql ="delete incentive.SALES_CSV_DATA_TEMP.* from incentive.SALES_CSV_DATA_TEMP , billing.PURCHASES b where incentive.SALES_CSV_DATA_TEMP.PROFILEID=b.PROFILEID AND b.STATUS='DONE' AND b.ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}
	// Profile Pre-allocation filter	
        public function removePreAllocatedProfiles()
        {
                try
		{
                        $sql ="delete incentive.SALES_CSV_DATA_TEMP.* from incentive.SALES_CSV_DATA_TEMP , incentive.PROFILE_ALLOCATION_TECH b where incentive.SALES_CSV_DATA_TEMP.PROFILEID=b.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}

	// Negative treatment filter
        public function removeNegativeTreatmentProfiles()
        {
                try
		{
                        $sql ="delete incentive.SALES_CSV_DATA_TEMP.* from incentive.SALES_CSV_DATA_TEMP , incentive.NEGATIVE_TREATMENT_LIST b where incentive.SALES_CSV_DATA_TEMP.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}
        // delete given set of profiles
        public function removeProfiles($profileArr, $process="")
        {
                try
                {
			if(is_array($profileArr))
				$profileStr =@implode(",",$profileArr);
			if(!$profileStr)
				throw new jsException("","no profiles passed");			
                        $sql ="delete from incentive.FP_CSV_DATA_TEMP WHERE PROFILEID IN($profileStr)";
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

	// detele profile Registered within 32 days
        public function deleteProfilesRegisteredWithin2Days()
        {
                try{
                        $sql ="delete from incentive.SALES_CSV_DATA_TEMP WHERE ENTRY_DT>DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }
        
	// fetch profile Registered within 32 days
        public function fetchProfilesRegisteredWithin2Days()
        {
                try{
                        $sql ="select PROFILEID,USERNAME from incentive.SALES_CSV_DATA_TEMP WHERE ENTRY_DT>DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }

	// fetch INDIALER profiles
        public function fetchInDialerProfiles()
        {
                try{
                        $sql ="select t.PROFILEID,t.USERNAME from incentive.SALES_CSV_DATA_TEMP t , incentive.IN_DIALER d where t.PROFILEID=d.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
			return $profileArr;
                }
                catch(Exceprtion $e){
                        throw new jsException($e);
		}
        }

	// fetch Negative Treatment profiles		
        public function fetchNegativeTreatmentProfiles()
        {
                try{
                        $sql ="select t.PROFILEID,t.USERNAME from incentive.SALES_CSV_DATA_TEMP t , incentive.NEGATIVE_TREATMENT_LIST d where t.PROFILEID=d.PROFILEID AND d.FLAG_OUTBOUND_CALL='N'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }

	// fetch DO NOT CALL profiles
        public function fetchDoNotCallProfiles()
        {
                try{
                        $sql ="select distinct t.PROFILEID,t.USERNAME from incentive.SALES_CSV_DATA_TEMP t , incentive.DO_NOT_CALL d where t.PROFILEID=d.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }

	// fetch paid profile within 30 days 
        public function fetchPaidWithin30Days()
        {
                try{
                        $sql ="select distinct t.PROFILEID,t.USERNAME from incentive.SALES_CSV_DATA_TEMP t , billing.PURCHASES p where t.PROFILEID=p.PROFILEID AND p.STATUS='DONE' AND p.ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 30 DAY)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }
	
	// fetch Pre-Allocated profiles
        public function fetchPreAllocatedProfiles()
        {
                try{
                        $sql ="select t.PROFILEID,t.USERNAME from incentive.SALES_CSV_DATA_TEMP t ,incentive.PROFILE_ALLOCATION_TECH d where t.PROFILEID=d.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }
        // fetch Pre-Allocated profiles
        public function fetchFailedPaymentProfiles($profileArray)
        {
                try{
                       if(is_array($profileArray))
                                $profileStr =@implode(",",$profileArray);
                        if(!$profileStr)
                                throw new jsException("","no profiles passed");
                        $sql ="select PROFILEID,USERNAME from incentive.SALES_CSV_DATA_TEMP WHERE PROFILEID IN($profileStr)";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profileArr[] =$result;
                        return $profileArr;
                }
                catch(Exception $e){
                        throw new jsException($e);
		}
        }
	
}
?>
