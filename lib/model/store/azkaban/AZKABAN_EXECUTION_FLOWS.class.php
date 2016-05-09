<?php
/*
 * This Class provide functions for azkaban databases execution_flows table
 * @author Reshu Rajput
 * @created 9 MAY 2013
*/

class AZKABAN_EXECUTION_FLOWS extends TABLE{
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
        
	 /**
        This function is used to return failed or preparing status cron information .
        * @param $db database name for different server is different so it is provided
        * @param $type it can be either "failed" or "longPreparing" for crons in any of failed status or preparing status respectively  
        * @returns resultArr return the array of crons information including exec_id and flow_id
        **/

	public function getExecutionStatus($db,$type)
        {
		try 
		{
			if(!$db || !$type)
				throw new jsException("No db or type provided in AZKABAN_EXECUTION_FLOWS");
			$cur_time = time();
			$time_24hours = ($cur_time - (60*60*24))*1000; // it is given to get 24 hour report
			$time_30min = ($cur_time - (60*20))*1000; // 30 min for long preparing
			$max_pre_minutes = ($cur_time-(7*60))*1000;    // it is given to have maximum 6 minutes not starting any cron	
                        $time_5min = ($cur_time - (60*5))*1000; 
		        $sql = "SELECT exec_id,flow_id FROM ".$db.".execution_flows WHERE ";
			if($type=="failed")
				$sql .= "start_time>=".$time_24hours." AND STATUS IN (70, 60, 80)";
			
			if($type=="longPreparing")
				$sql.=" STATUS =20 AND submit_time >=".$time_30min." AND submit_time <=".$time_5min;
			if($type=="executing")
				$sql.=" start_time >=".$max_pre_minutes." AND start_time <=".$cur_time*1000;
			$sql.= " ORDER BY STATUS ,exec_id";
			$res=$this->db->prepare($sql);
                        $res->execute();
                        while($result = $res->fetch(PDO::FETCH_ASSOC))
                                        $resultArr[] = $result;
                        return $resultArr;
 
		}
		catch(PDOException $e)
		{
			/*** echo the sql statement and error message ***/
			throw new jsException($e);
		}
	}
		
		
}
?>
