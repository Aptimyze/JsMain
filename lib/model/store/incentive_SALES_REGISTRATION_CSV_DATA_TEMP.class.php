<?php
class incentive_SALES_REGISTRATION_CSV_DATA_TEMP extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.SALES_REGISTRATION_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
	public function insertProfile($profileDetails)
        {
                try
                {
                        foreach($profileDetails as $key=>$val)
				${$key} = $val;
	
                        $sql = "INSERT IGNORE INTO incentive.SALES_REGISTRATION_CSV_DATA_TEMP (PROFILEID,USERNAME,GENDER,AGE,SUBSCRIPTION,ENTRY_DT,RELATION,CITY_RES,PHONE_MOB,PHONE_WITH_STD,PINCODE) VALUES(:PROFILEID,:USERNAME,:GENDER,:AGE,:SUBSCRIPTION,:ENTRY_DT,:RELATION,:CITY_RES,:PHONE_MOB,:PHONE_WITH_STD,:PINCODE)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$PROFILEID,PDO::PARAM_INT);
                        $prep->bindValue(":USERNAME",$USERNAME,PDO::PARAM_STR);
			$prep->bindValue(":GENDER",$GENDER,PDO::PARAM_STR);
			$prep->bindValue(":AGE",$AGE,PDO::PARAM_STR);
			$prep->bindValue(":SUBSCRIPTION",$SUBSCRIPTION,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$ENTRY_DT,PDO::PARAM_STR);
			$prep->bindValue(":RELATION",$RELATION,PDO::PARAM_STR);
			$prep->bindValue(":CITY_RES",$CITY_RES,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_MOB",$PHONE_MOB,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_WITH_STD",$PHONE_WITH_STD,PDO::PARAM_STR);
			$prep->bindValue(":PINCODE",$PINCODE,PDO::PARAM_STR);

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
                        $sql ="SELECT * FROM incentive.SALES_REGISTRATION_CSV_DATA_TEMP";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profileArr[] = $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profileArr;
        }
        public function getScore($profileid)
        {
                try
                {
                        $sql ="SELECT ANALYTIC_SCORE FROM incentive.SALES_REGISTRATION_CSV_DATA_TEMP WHERE PROFILEID=:PROFILEID";
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

        // Negative treatment filter
        public function removeNegativeTreatmentProfiles()
        {
                try
                {
                        $sql ="delete incentive.SALES_REGISTRATION_CSV_DATA_TEMP.* from incentive.SALES_REGISTRATION_CSV_DATA_TEMP , incentive.NEGATIVE_TREATMENT_LIST b where incentive.SALES_REGISTRATION_CSV_DATA_TEMP.PROFILEID=b.PROFILEID AND b.FLAG_OUTBOUND_CALL='N'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	// Negative profile list filter	
        public function removeNegativeListProfiles()
        {
                try
		{
                        $sql ="delete incentive.SALES_REGISTRATION_CSV_DATA_TEMP.* from incentive.SALES_REGISTRATION_CSV_DATA_TEMP , incentive.NEGATIVE_PROFILE_LIST where incentive.SALES_REGISTRATION_CSV_DATA_TEMP.PROFILEID=incentive.NEGATIVE_PROFILE_LIST.PROFILEID";
                        $prep = $this->db->prepare($sql);
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
                        $sql ="delete incentive.SALES_REGISTRATION_CSV_DATA_TEMP.* from incentive.SALES_REGISTRATION_CSV_DATA_TEMP , incentive.DO_NOT_CALL where incentive.SALES_REGISTRATION_CSV_DATA_TEMP.PROFILEID=incentive.DO_NOT_CALL.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}
	// Profile allocation filter	
        public function removeAllocatedProfiles()
        {
                try
		{
                        $sql ="delete incentive.SALES_REGISTRATION_CSV_DATA_TEMP.* from incentive.SALES_REGISTRATION_CSV_DATA_TEMP , incentive.PROFILE_ALLOCATION_TECH b where incentive.SALES_REGISTRATION_CSV_DATA_TEMP.PROFILEID=b.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
		{
                        throw new jsException($e);
                }
	}
	// Registration sales log filter
        public function removeSalesRegistrationLogProfiles()
        {
                try
                {
                        $sql ="delete incentive.SALES_REGISTRATION_CSV_DATA_TEMP.* from incentive.SALES_REGISTRATION_CSV_DATA_TEMP,incentive.NEW_REGISTRATION_SALES_LOG b where incentive.SALES_REGISTRATION_CSV_DATA_TEMP.PROFILEID=b.PROFILEID";
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
