<?php

class PROBABLE_DUPLICATES extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }

       
        public function insertProbable(RawDuplicate $rawDuplicateObj)
        {
        try {

            $sql = "insert ignore into  PROBABLE_DUPLICATES( PROFILE1,PROFILE2,REASON,ENTRY_DATE,CURRENT_STATE,SCREEN_ACTION) values( :PROFILE1 , :PROFILE2 , :REASON , :ENTRY_DATE , :CURRENT_STATE , :SCREEN_ACTION )";
           //echo $rawDuplicateObj->getProfileid1().$rawDuplicateObj->getProfileid2().$rawDuplicateObj->getReason().$rawDuplicateObj->getEntryDt().$rawDuplicateObj->getCurrentState().$rawDuplicateObj->getScreenAction();
            $prep = $this->db->prepare($sql);
	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILE2",$rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
            $prep->bindValue(":REASON", $rawDuplicateObj->getReason(), PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DATE",$rawDuplicateObj->getEntryDt(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE",$rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
           
            $prep->execute();
            
                }
                catch (Exception $e) {
            throw new jsException($e);
                }
        }
        public function updateProbable(RawDuplicate $rawDuplicateObj)
        {
        try {
            $sql = "replace into  PROBABLE_DUPLICATES( PROFILE1,PROFILE2,REASON,ENTRY_DATE,CURRENT_STATE,SCREEN_ACTION) values( :PROFILE1 , :PROFILE2 , :REASON , :ENTRY_DATE , :CURRENT_STATE , :SCREEN_ACTION )";
	    //print_r($rawDuplicateObj->getScreenAction());
            $prep = $this->db->prepare($sql);
	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILE2",$rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
            $prep->bindValue(":REASON", $rawDuplicateObj->getReason(), PDO::PARAM_STR);
            $prep->bindValue(":ENTRY_DATE",$rawDuplicateObj->getEntryDt(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE",$rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
           
            $prep->execute();
            
                }
                catch (Exception $e) {

            throw new jsException($e);
                }
        }

	public function unsetPriority(RawDuplicate $rawDuplicateObj)
        {
        try {
		$profile1 = $rawDuplicateObj->getProfileid1();
                $profile2 = $rawDuplicateObj->getProfileid2();

	    	if($rawDuplicateObj->getIsDuplicate()==IS_DUPLICATE::YES)
		{

		        $sqlDT1 = "UPDATE duplicates.PROBABLE_DUPLICATES as p,duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE1=:PROFILE1 AND p.PROFILE2=d.PROFILEID";
			$prep=$this->db->prepare($sqlDT1);
			$prep->bindValue(":PROFILE1",$profile1, PDO::PARAM_INT);
                        $prep->execute();

		        $sqlDT2 = "UPDATE duplicates.PROBABLE_DUPLICATES as p,duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE2=:PROFILE2 AND p.PROFILE1=d.PROFILEID";
			$prep=$this->db->prepare($sqlDT2);
			$prep->bindValue(":PROFILE2",$profile2, PDO::PARAM_INT);
                        $prep->execute();

			$sqlDT3 = "UPDATE duplicates.PROBABLE_DUPLICATES as p,duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE1=:PROFILE2 AND p.PROFILE2=d.PROFILEID";
                        $prep=$this->db->prepare($sqlDT3);
			$prep->bindValue(":PROFILE2",$profile2, PDO::PARAM_INT);
                        $prep->execute();

                        $sqlDT4 = "UPDATE duplicates.PROBABLE_DUPLICATES as p,duplicates.DUPLICATE_PROFILES as d SET p.PRIORITY='0' WHERE p.PROFILE2=:PROFILE1 AND p.PROFILE1=d.PROFILEID";
                        $prep=$this->db->prepare($sqlDT4);
			$prep->bindValue(":PROFILE1",$profile1, PDO::PARAM_INT);
                        $prep->execute();

		}
		
	    	$sql1 = "UPDATE duplicates.PROBABLE_DUPLICATES SET SCREEN_ACTION='NONE' WHERE PROFILE1=:PROFILE1 AND SCREEN_ACTION='IN'";
                $prep = $this->db->prepare($sql1);
		$prep->bindValue(":PROFILE1",$profile1, PDO::PARAM_INT);
                $prep->execute();

                $sql2 = "UPDATE duplicates.PROBABLE_DUPLICATES SET SCREEN_ACTION='NONE' WHERE PROFILE2=:PROFILE1 AND SCREEN_ACTION='IN'";
                $prep = $this->db->prepare($sql2);
		$prep->bindValue(":PROFILE1",$profile1, PDO::PARAM_INT);
                $prep->execute();

                $sql3 = "UPDATE duplicates.PROBABLE_DUPLICATES SET SCREEN_ACTION='NONE' WHERE PROFILE1=:PROFILE2 AND SCREEN_ACTION='IN'";
                $prep = $this->db->prepare($sql3);
		$prep->bindValue(":PROFILE2",$profile2, PDO::PARAM_INT);
                $prep->execute();

                $sql4 = "UPDATE duplicates.PROBABLE_DUPLICATES SET SCREEN_ACTION='NONE' WHERE PROFILE2=:PROFILE2 AND SCREEN_ACTION='IN'";
                $prep = $this->db->prepare($sql4);
		$prep->bindValue(":PROFILE2",$profile2, PDO::PARAM_INT);
                $prep->execute();
            }
                catch (Exception $e) {
            throw new jsException($e);
                }
        }

	public function fetchDuplicateStatus($profile)
        {
        	try 
		{

            		$sql="SELECT PROFILEID FROM duplicates.DUPLICATE_PROFILES WHERE PROFILEID=:PROFILE";
	        	$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILE",$profile, PDO::PARAM_INT);
        		$prep->execute();
            		if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
            			$return=$result;
            		}
            	}
                catch (Exception $e) 
		{
            		throw new jsException($e);
                }
		if($return)
			return 'Y';
		else
			return 'N';
        }

	public function screenIn(RawDuplicate $rawDuplicateObj)
        {
        try {

            $sql = "update PROBABLE_DUPLICATES set SCREEN_ACTION=:SCREEN_ACTION WHERE PROFILE1=:PROFILE1 AND SCREEN_ACTION!='OUT'";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->execute();

                }
                catch (Exception $e) {

            throw new jsException($e);
                }
        }

        public function removeProbable(RawDuplicate $rawDuplicateObj)
        {
        try {

            $sql = "delete from PROBABLE_DUPLICATES where PROFILE1 IN(:PROFILE1,:PROFILE2) and PROFILE2 IN(:PROFILE1,:PROFILE2)";
           
            $prep = $this->db->prepare($sql);
	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILE2",$rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
            $prep->execute();
            
                }
                catch (Exception $e) {
            throw new jsException($e);
                }
        }
        public function fetchProbableDuplicate(RawDuplicate $rawDuplicateObj)
        {
			try {
			$sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE ORDER BY PRIORITY DESC,REASON DESC,PROFILE1 DESC,PROFILE2 DESC limit 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
				  $return=$result;
			 }
                }
                catch (Exception $e) {
            throw new jsException($e);
                }
               
                return $return;
		}
	public function fetchProbableDuplicateIn(RawDuplicate $rawDuplicateObj)//For fetching the pair in the queue (IN).
	{	
		try{
		$sql="SELECT * FROM PROBABLE_DUPLICATES WHERE SCREEN_ACTION=:SCREEN_ACTION AND CURRENT_STATE=:CURRENT_STATE ORDER BY PRIORITY DESC,REASON DESC,PROFILE1 DESC,PROFILE2 DESC";
		$prep=$this->db->prepare($sql);
		$prep->bindValue(":SCREEN_ACTION",$rawDuplicateObj->getScreenAction(),PDO::PARAM_STR);
		$prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
		$prep->execute();
		while($result=$prep->fetch(PDO::FETCH_ASSOC))
		{
			$return[]=$result;
		}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
			
		return $return;
	}
	public function fetchProbableDuplicateOut(RawDuplicate $rawDuplicateObj)//For fetching the pair in the queue (OUT).
        {
                try{
                $sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE ORDER BY ENTRY_DATE ASC limit 1";
                $prep=$this->db->prepare($sql);
                $prep->bindValue(":SCREEN_ACTION",$rawDuplicateObj->getScreenAction(),PDO::PARAM_STR);
                $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
                $prep->execute();
                while($result=$prep->fetch(PDO::FETCH_ASSOC))
                {
                        $return[]=$result;
                }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

                return $return;
        }


	public function fetchProbableDuplicateOfProfile(RawDuplicate $rawDuplicateObj)
        {
                        try {
                        $sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE AND PROFILE1=:PROFILE1 ORDER BY PRIORITY DESC,REASON DESC,PROFILE2 DESC limit 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
	    $prep->bindValue(":PROFILE1", $rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return=$result;
                         }
                }
                catch (Exception $e) {
            throw new jsException($e);
                }

                return $return;
                }
	public function fetchProbableDuplicateForSupervisor(RawDuplicate $rawDuplicateObj)
        {
                        try {
                        $sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE ORDER BY ENTRY_DATE ASC limit 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return=$result;
                         }
                }
                catch (Exception $e) {
            throw new jsException($e);
                }

                return $return;
                }
	public function fetchProbableDuplicateOutForSupervisor(RawDuplicate $rawDuplicateObj)
        {
                        try {
                        $sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE ORDER BY ENTRY_DATE ASC";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return[]=$result;
                         }
                }
                catch (Exception $e) {
            throw new jsException($e);
                }

                return $return;
                }

	public function fetchProbableDuplicateOfProfileForSupervisor(RawDuplicate $rawDuplicateObj)
        {
                        try {
                        $sql="select * from PROBABLE_DUPLICATES where SCREEN_ACTION=:SCREEN_ACTION and CURRENT_STATE=:CURRENT_STATE AND PROFILE1=:PROFILE1 ORDER BY ENTRY_DATE ASC limit 1";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SCREEN_ACTION", $rawDuplicateObj->getScreenAction(), PDO::PARAM_STR);
            $prep->bindValue(":CURRENT_STATE", $rawDuplicateObj->getCurrentState(), PDO::PARAM_STR);
            $prep->bindValue(":PROFILE1", $rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return=$result;
                         }
                }
                catch (Exception $e) {
            throw new jsException($e);
                }

                return $return;
                }


	public function getProbableDuplicateOfProfileByReason($profileid,$reason)
	{
        	try
		{
			$return=array();
			$sql="select PROFILE2 from PROBABLE_DUPLICATES where PROFILE1=:PROFILE1 AND REASON=:REASON";
		        $prep = $this->db->prepare($sql);
		        $prep->bindValue(":PROFILE1", $profileid, PDO::PARAM_INT);
		        $prep->bindValue(":REASON", $reason, PDO::PARAM_STR);
		        $prep->execute();
			while ($result = $prep->fetch(PDO::FETCH_ASSOC)) 
			{
                        	$return[]=$result['PROFILE2'];
                	}
			$sql="select PROFILE1 from PROBABLE_DUPLICATES where PROFILE2=:PROFILE2 AND REASON=:REASON";
		        $prep = $this->db->prepare($sql);
		        $prep->bindValue(":PROFILE2", $profileid, PDO::PARAM_INT);
		        $prep->bindValue(":REASON", $reason, PDO::PARAM_STR);
		        $prep->execute();
			while ($result = $prep->fetch(PDO::FETCH_ASSOC)) 
			{
	                       	$return[]=$result['PROFILE1'];
			}
                }
		catch (Exception $e) 
		{
			throw new jsException($e);
                }
                return $return;
	}
	public function isEntryPresent($rawDuplicateObj)
	{
		try
        	{

        	    $sql = "select PROFILE1 as cnt from PROBABLE_DUPLICATES where PROFILE1 IN(:PROFILE1,:PROFILE2) and PROFILE2 IN(:PROFILE1,:PROFILE2) and FIND_IN_SET(:REASON,REASON)";
	            $prep = $this->db->prepare($sql);
        	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
	            $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
	            $prep->bindValue(":REASON", $rawDuplicateObj->getReason(), PDO::PARAM_STR);
        	    $prep->execute();
	             if ($result = $prep->fetch(PDO::FETCH_NUM)) {
				return true;
	                }
		}
		catch (Exception $e) {
	            throw new jsException($e);
		}
        	        return false;
	}
	public function ReasonPresent($rawDuplicateObj)
	{
		try
        	{

        	    $sql = "select REASON  from PROBABLE_DUPLICATES where PROFILE1 IN(:PROFILE1,:PROFILE2) and PROFILE2 IN(:PROFILE1,:PROFILE2)";
	            $prep = $this->db->prepare($sql);
        	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
	            $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
	            
        	    $prep->execute();
	             if ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
				return $result[REASON];
	                }
		}
		catch (Exception $e) {
	            throw new jsException($e);
		}
        	        return false;
	}
	public function updatePreviousProbable($rawDuplicateObj)
	{
		try
        	{

        	    $sql = "update  PROBABLE_DUPLICATES set REASON=:REASON where PROFILE1 IN(:PROFILE1,:PROFILE2) and PROFILE2 IN(:PROFILE1,:PROFILE2)";
	            $prep = $this->db->prepare($sql);
	            $prep->bindValue(":REASON",$rawDuplicateObj->getReason(), PDO::PARAM_STR);
        	    $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
	            $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
	            
        	    $prep->execute();
		}
		catch (Exception $e) {
	            throw new jsException($e);
		}
        	      
	}
	
}

?>
