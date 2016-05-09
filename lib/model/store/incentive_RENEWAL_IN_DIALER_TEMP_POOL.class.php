<?php

/**
 * Description of incentive_RENEWAL_IN_DIALER_TEMP_POOL
 *
 * @author nitish
 */
class incentive_RENEWAL_IN_DIALER_TEMP_POOL extends TABLE {
    public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.RENEWAL_IN_DIALER_TEMP_POOL";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
        public function fetchProfiles()
        {
                try
                {
                        $sql = "SELECT PROFILEID FROM incentive.RENEWAL_IN_DIALER_TEMP_POOL";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
	                        $profiles[] =$res["PROFILEID"];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
	public function insertProfile($profileid)
        {
                try
                {
                        $sql = "INSERT IGNORE INTO incentive.RENEWAL_IN_DIALER_TEMP_POOL(PROFILEID) VALUES(:PROFILEID)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
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
                        $sql ="delete incentive.RENEWAL_IN_DIALER_TEMP_POOL.* from incentive.RENEWAL_IN_DIALER_TEMP_POOL , incentive.DO_NOT_CALL d where incentive.RENEWAL_IN_DIALER_TEMP_POOL.PROFILEID=d.PROFILEID";
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
                        $sql ="delete incentive.RENEWAL_IN_DIALER_TEMP_POOL.* from incentive.RENEWAL_IN_DIALER_TEMP_POOL , incentive.PROFILE_ALLOCATION_TECH b where incentive.RENEWAL_IN_DIALER_TEMP_POOL.PROFILEID=b.PROFILEID";
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
                        $sql ="delete incentive.RENEWAL_IN_DIALER_TEMP_POOL.* from incentive.RENEWAL_IN_DIALER_TEMP_POOL , incentive.NEGATIVE_TREATMENT_LIST b where incentive.RENEWAL_IN_DIALER_TEMP_POOL.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}
	// fetch Negative Treatment profiles		
        public function fetchNegativeTreatmentProfiles()
        {
                try{
                        $sql ="select t.PROFILEID from incentive.RENEWAL_IN_DIALER_TEMP_POOL t , incentive.NEGATIVE_TREATMENT_LIST d where t.PROFILEID=d.PROFILEID AND d.FLAG_OUTBOUND_CALL='N'";
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
                        $sql ="select distinct t.PROFILEID from incentive.RENEWAL_IN_DIALER_TEMP_POOL t , incentive.DO_NOT_CALL d where t.PROFILEID=d.PROFILEID";
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
                        $sql ="select t.PROFILEID from incentive.RENEWAL_IN_DIALER_TEMP_POOL t ,incentive.PROFILE_ALLOCATION_TECH d where t.PROFILEID=d.PROFILEID";
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