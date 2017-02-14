<?php

class DUPLICATE_PROFILE_LOG extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }
	public function fetchDuplicateProfileLog(RawDuplicate $rawDuplicateObj,$noRefresh="0")
        {
                        try {
                        $sql="select * from DUPLICATE_PROFILE_LOG where IS_DUPLICATE=:IS_DUPLICATE and PROFILE1=:PROFILE1 AND SCREENED_ACTION=:SCREENED_ACTION";
	   if($rawDuplicateObj->getScreenAction()=="IN" || ($rawDuplicateObj->getScreenAction()=="OUT"&&(!$noRefresh)))
	   {
		$sql.=" AND SCREENED_BY=:SCREENED_BY ORDER BY ENTRY_DATE DESC limit 1";
	   }		
	   else
	   {
		$sql.=" AND PROFILE2=:PROFILE2 ORDER BY ENTRY_DATE DESC limit 1";
	   }		
           $prep = $this->db->prepare($sql);
	   if($rawDuplicateObj->getScreenAction()=="IN" || ($rawDuplicateObj->getScreenAction()=="OUT"&&(!$noRefresh)))
	   {
		$prep->bindValue(":SCREENED_BY",$rawDuplicateObj->getScreenedBy(), PDO::PARAM_STR);
	   }	
	   else
	           $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);

	   $prep->bindValue(":IS_DUPLICATE",$rawDuplicateObj->getIsDuplicate(), PDO::PARAM_STR);
	   $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
	   $prep->bindValue(":SCREENED_ACTION",$rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);	

           $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return=$result;
                         }
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }

                return $return;
                }
	//function to test if out profile is already seen by any supervisor	
	public function fetchDuplicateProfileLogForSup(RawDuplicate $rawDuplicateObj)
	{
		try
		{
			$sql="select * from DUPLICATE_PROFILE_LOG where PROFILE1=:PROFILE1 AND SCREENED_ACTION=:SCREENED_ACTION AND MARKED_BY=:MARKED_BY";
			$prep = $this->db->prepare($sql);

           		$prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
			$prep->bindValue(":SCREENED_ACTION",$rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
			$prep->bindValue(":MARKED_BY","SUPERVISOR");
			$prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return=$result;
                         }
                }
		catch(Exception $e)
		{
			jsCacheWrapperException::logThis($e);
		}
		return $return;
	}
	public function fetchCountDuplicateProfileLog($st_date,$end_date,$marked_by="")
        {
        	try 
		{
                	$sql="select IS_DUPLICATE,ENTRY_DATE,IDENTIFIED_ON,SCREENED_BY from DUPLICATE_PROFILE_LOG where ENTRY_DATE BETWEEN '$st_date' and '$end_date' and SCREENED_ACTION='OUT'";
			if($marked_by!="")
				$sql.=" AND MARKED_BY='$marked_by' ORDER BY ENTRY_DATE";
			else
				$sql.=" ORDER BY ENTRY_DATE";
           		$prep = $this->db->prepare($sql);
		        $prep->execute();
	        	while ($result = $prep->fetch(PDO::FETCH_ASSOC)) 
			{
                		$return[]=$result;
                	}
             	}
                catch (Exception $e) 
		{
	        	jsCacheWrapperException::logThis($e);
                }
                return $return;
	}

        public function fetchCountDuplicateProfileIdentified($st_date,$end_date)
        {
                try
                {
                        $sql="select count(*) as cnt,DATE_FORMAT(IDENTIFIED_ON,'%Y-%m-%d') AS identified_dt from DUPLICATE_PROFILE_LOG where IDENTIFIED_ON BETWEEN '$st_date' and '$end_date' AND MARKED_BY='EXECUTIVE' AND SCREENED_ACTION='IN' GROUP BY DATE_FORMAT(IDENTIFIED_ON,'%Y-%m-%d') ORDER BY IDENTIFIED_ON";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while ($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
				$date=$result['identified_dt'];
                                $return[$date]['cnt']=$result['cnt'];
                        }
                }
                catch (Exception $e)
                {
                        jsCacheWrapperException::logThis($e);
                }
                return $return;
        }

        public function fetchResultForAPair($profile1,$profile2)
        {
                try
                {
                        $sql="select * from DUPLICATE_PROFILE_LOG where PROFILE1 IN (:PROFILE1,:PROFILE2) AND PROFILE2 IN (:PROFILE1,:PROFILE2) ORDER BY ENTRY_DATE DESC LIMIT 1";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILE1",$profile1,PDO::PARAM_INT);
                        $prep->bindValue(":PROFILE2",$profile2,PDO::PARAM_INT);
                        $prep->execute();

                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                            return $result;
                
                }
                catch (Exception $e)
                {
                        jsCacheWrapperException::logThis($e);
                }
        }
        
        public function fetchLogForAProfile($profileId){
            try
                {
                        $sql="select * from DUPLICATE_PROFILE_LOG where PROFILE1=:PROFILEID OR PROFILE2=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_INT);
                        $prep->execute();

                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                $resultArr[]=$result;
                        
                        return $resultArr;
                
                }
                catch (Exception $e)
                {
                        jsCacheWrapperException::logThis($e);
                }
            
        }
        
        public function insertDuplicateProfileLog(RawDuplicate $rawDuplicateObj)
        {
        try {
		
            $sql = "insert into DUPLICATE_PROFILE_LOG values(:PROFILE1,:PROFILE2,:REASON,:ENTRY_DT,:IDENTIFIED_ON,:IS_DUPLICATE,:SCREENED_BY,:COMMENTS,:SCREENED_ACTION,:MARKED_BY)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
            $prep->bindValue(":REASON", $rawDuplicateObj->getReason(), PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DT", $rawDuplicateObj->getEntryDt(), PDO::PARAM_STR);
	    $prep->bindValue(":IDENTIFIED_ON", $rawDuplicateObj->getExtension('IDENTIFIED_ON'), PDO::PARAM_STR);
            $prep->bindValue(":IS_DUPLICATE", $rawDuplicateObj->getIsDuplicate(), PDO::PARAM_STR);
            $prep->bindValue(":SCREENED_BY", $rawDuplicateObj->getScreenedBy(), PDO::PARAM_STR);
            $prep->bindValue(":COMMENTS", $rawDuplicateObj->getComments(), PDO::PARAM_STR);
            $prep->bindValue(":SCREENED_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
	    $prep->bindValue(":MARKED_BY", $rawDuplicateObj->getExtension('MARKED_BY'), PDO::PARAM_STR);
            $prep->execute();

                }
                catch (Exception $e) {
					//var_dump($e);
					//$prep->errorInfo()[2];
            jsCacheWrapperException::logThis($e);
                }
        }
        public function updateGroupID($group1,$group2)
        {
        try {

            $sql = "update newjs.DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1 where DUPLICATE_ID=:GROUP2";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":GROUP1",$group1, PDO::PARAM_INT);
            $prep->bindValue(":GROUP2", $group2, PDO::PARAM_INT);
            $prep->execute();
            
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }
        }
        public function updateProfileGroupID($group1,$profile1,$profile2)
        {
        try {

            $sql = "insert ignore into  duplicates.DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1,PROFILEID=:PROFILEID1;insert ignore into  duplicates.DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1,PROFILEID=:PROFILEID2;";
            
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":GROUP1",$group1, PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID1", $profile1, PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID2", $profile2, PDO::PARAM_INT);
           
            $prep->execute();
            
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }
        }


        public function fetchConfirmedDuplicates($limit,$offset)
        {
        try {

            $sql = "SELECT PROFILE1, PROFILE2 from duplicates.DUPLICATE_PROFILE_LOG where IS_DUPLICATE='YES' ORDER BY ENTRY_DATE DESC LIMIT $limit OFFSET $offset ";
            
            $prep = $this->db->prepare($sql);
            
            $prep->execute();
            
            while ($result = $prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $finalR[]=$result;
                        }
                    
            return $finalR;
            } 
        catch (Exception $e) 
                {
                jsCacheWrapperException::logThis($e);
                }
        
}


 public function deleteProbableDuplicates()
        {
            try {


                $sql="delete  from duplicates.DUPLICATE_PROFILE_LOG where IS_DUPLICATE='PROBABLE'";
                $prep = $this->db->prepare($sql);
                $prep->execute();
             
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }

                return true;
        }
    //Three function for innodb transactions
    public function startTransaction()
    {
        $this->db->beginTransaction();
    }
    public function commitTransaction()
    {
        $this->db->commit();
    }

    public function rollbackTransaction()
    {
        $this->db->rollback();
    }
    //Three function for innodb transactions

}
?>
