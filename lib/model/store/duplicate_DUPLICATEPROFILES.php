<?php

class DUPLICATES_PROFILES extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }

        public function getDuplicateID(RawDuplicate $rawDuplicateObj)
        {
        try {

            $sql = "Select DUPLICATE_ID from DUPLICATE_PROFILES where PROFILEID IN(:PROFILEID1,:PROFILEID2)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILEID2",$rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
                        $prep->execute();
            while ($result = $prep->fetch(PDO::FETCH_NUM)) {
                $records[]=$result[0];
                        }
                        return $records;
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }
        }
        public function updateGroupID($group1,$group2)
        {
        try {

            $sql = "update DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1 where DUPLICATE_ID=:GROUP2";
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
		try 
		{
			$sql = "insert ignore into  DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1,PROFILEID=:PROFILEID1";
			$prep1 = $this->db->prepare($sql);
			$prep1->bindValue(":GROUP1",$group1, PDO::PARAM_INT);
			$prep1->bindValue(":PROFILEID1", $profile1, PDO::PARAM_INT);
			$prep1->execute();
			$sql="insert ignore into  DUPLICATE_PROFILES set DUPLICATE_ID=:GROUP1,PROFILEID=:PROFILEID2;";
			$prep2 = $this->db->prepare($sql);
			$prep2->bindValue(":GROUP1",$group1, PDO::PARAM_INT);
			$prep2->bindValue(":PROFILEID2", $profile2, PDO::PARAM_INT);
			$prep2->execute();
		}
		catch (Exception $e) {
		jsCacheWrapperException::logThis($e);
		}
        }
        public function getProfileDuplicates($profileid)
        {
        try {
			$sql="select dp2.PROFILEID from DUPLICATE_PROFILES as dp1 , DUPLICATE_PROFILES as dp2 where dp1.PROFILEID=:PROFILEID and dp1.DUPLICATE_ID=dp2.DUPLICATE_ID";
            $prep = $this->db->prepare($sql);
            
            
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_NUM)) {
				 $return[]=$result[0];
			 }
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }
                return $return;
        }
        public function removeProfileAsDuplicate($profileid)
        {
			try{
				$sql="delete from DUPLICATE_PROFILES where PROFILEID=:PROFILE";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILE", $profileid, PDO::PARAM_INT);
				$prep->execute();
			}
			catch (Exception $e) {
				jsCacheWrapperException::logThis($e);
			}
		}
}

?>
