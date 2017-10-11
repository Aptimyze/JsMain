<?php

class DUPLICATES_GROUPID extends TABLE {

        public function __construct($dbname="duplicates")
        {
            parent::__construct($dbname);
        }

        public function createGROUPID()
        {
        try {
			
            $sql = "insert into DUPLICATE_IDS VALUES('')";
            $prep = $this->db->prepare($sql);
            $prep->execute();
            
            return $this->db->lastInsertId(); 
            
            //echo "NIKHI".$result;
                }
                catch (Exception $e) {
            throw new jsException($e);
                }
        }
}

?>
