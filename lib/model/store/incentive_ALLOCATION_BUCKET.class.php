<?php
class incentive_ALLOCATION_BUCKET extends TABLE {

        public function __construct($dbname="")
        {
              parent::__construct($dbname);
        }
        public function get($processId)
        {
                if(!$processId)
                        throw new jsException("","$criteria IS BLANK");
                try
                {
                        $sql="SELECT * from incentive.ALLOCATION_BUCKET WHERE PROCESS_ID = :PROCESS_ID";
                        $resSelectDetail = $this->db->prepare($sql);
                        $resSelectDetail->bindValue(":PROCESS_ID", $processId, PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        while($row =$resSelectDetail->fetch(PDO::FETCH_ASSOC))
			{
				$bucketType		=$row['BUCKET_TYPE'];
                        	$result[$bucketType] 	=$row;
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $result;
        }

}
