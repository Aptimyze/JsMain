<?php

class duplicates_DUPLICATE_TEMP_TABLE extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }
	public function getProfileArray($inString)
        {
            if(!$inString) return false;
                        try {
                        $sql="select * from duplicates.DUPLICATE_TEMP_TABLE where PROFILEID IN ($inString)";
            $prep = $this->db->prepare($sql);	  
             $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                                 $return[$result['PROFILEID']]=$result['STATUS'];
                         }
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }

                return $return;
                }
        public function insertEntry($profile1,$status)
        {
        try {
		
            $sql = "replace into duplicates.DUPLICATE_TEMP_TABLE values(:PROFILE1,:STATUS)";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILE1",$profile1, PDO::PARAM_INT);
            $prep->bindValue(":STATUS", $status, PDO::PARAM_STR);
            $prep->execute();

                }
                catch (Exception $e) {
					//var_dump($e);
					//$prep->errorInfo()[2];
            jsCacheWrapperException::logThis($e);
                }
        }
        public function getNonDuplicateProfiles()
        {            
            try {
                        
                
                $sql="select PROFILEID from duplicates.DUPLICATE_TEMP_TABLE where STATUS='Y'";
                $prep = $this->db->prepare($sql);
                $prep->execute();
             while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
                        $return[]=$result['PROFILEID'];
                         }
                }
                catch (Exception $e) {
            jsCacheWrapperException::logThis($e);
                }

                return $return;
            
        }
    //Three function for innodb transactions

}
?>
