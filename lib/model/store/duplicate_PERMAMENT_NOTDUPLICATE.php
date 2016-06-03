<?php

class PERMANENT_NOT_DUPLICATE extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }

        public function MarkPermanentNotDuplicates(RawDuplicate $rawDuplicateObj)
        {
        try {
		
            $sql = "insert into PERMANENT_NOT_DUPLICATE values(:PROFILE1,:PROFILE2,:REASON)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILE1",$rawDuplicateObj->getProfileid1(), PDO::PARAM_INT);
            $prep->bindValue(":PROFILE2", $rawDuplicateObj->getProfileid2(), PDO::PARAM_INT);
            $prep->bindValue(":REASON", $rawDuplicateObj->getReason(), PDO::PARAM_STR);
            
            $prep->execute();
            
                }
                catch (Exception $e) {
					//var_dump($e);
					
            throw new jsException($e);
                }
        }
        public function IsEntryPresent(RawDuplicate $rawDuplicateObj)
        {
        try 
        {
			
            $sql = "select PROFILE1 as cnt from PERMANENT_NOT_DUPLICATE where PROFILE1 IN(:PROFILE1,:PROFILE2) and PROFILE2 IN(:PROFILE1,:PROFILE2)";
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
        
}

?>
