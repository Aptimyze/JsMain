<?php
class incentive_ALLOCATION_LIMIT_CRM extends TABLE {

        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function getAllocationLimit()
        {
                try
                {
                        $sql="SELECT PROCESS_ID,LIMIT_VALUE from incentive.ALLOCATION_LIMIT_CRM";
                        $resSelectDetail = $this->db->prepare($sql);
                        $resSelectDetail->execute();
                        while($row =$resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
				$processId 		=$row['PROCESS_ID'];	
				$limitArr[$processId] 	=$row['LIMIT_VALUE'];
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $limitArr;
        }

}
