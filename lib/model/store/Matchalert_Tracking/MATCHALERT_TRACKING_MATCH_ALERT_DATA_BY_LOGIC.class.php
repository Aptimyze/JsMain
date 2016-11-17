<?php
/**
* This class is used for storing data into MATCH_ALERT_DATA_BY_LOGIC table which contains Number of people eligible for Recommendations - by logic type
*/
class MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}

	public function insertCountByLogicTypeForDate($date,$countByLogicArr)
	{
		try
		{
		
			// print_R($countByLogicArr);die;					
			$sql = "INSERT into MATCHALERT_TRACKING.MATCH_ALERT_DATA_BY_LOGIC (DATE,LOGICLEVEL,COUNT) values ";		
			foreach($countByLogicArr as $key=>$val)
			{
				$insertSql.= "(:DATEVAL, "; 
				foreach($val as $k1=>$v1)
				{				
					if($k1 == "LOGICLEVEL")
					{
						$insertSql.= ":LOGIC".$key.",";
					}
					if($k1 == "CNT")
					{
						$insertSql.= ":COUNT".$key."), ";
						$totalCount+=$v1;
					}
								
				}
			}

			$insertSql = rtrim($insertSql,", ");
			$sql.= $insertSql;
			$prep = $this->db->prepare($sql);

			$prep->bindValue(":DATEVAL",$date,PDO::PARAM_STR);
			foreach($countByLogicArr as $key=>$val)
			{
			
				foreach($val as $k1=>$v1)
				{				
					if($k1 == "LOGICLEVEL")
					{					
						$prep->bindValue(":LOGIC".$key,$v1,PDO::PARAM_INT);
					}
					if($k1 == "CNT")
					{
						$prep->bindValue(":COUNT".$key,$v1,PDO::PARAM_INT);
					}
				}
			}		
		 	$prep->execute();
		 	echo("DONE");die;
		}
		catch (PDOException $e)
		{
			//add mail/sms
			throw new jsException($e);
		}
	}

}