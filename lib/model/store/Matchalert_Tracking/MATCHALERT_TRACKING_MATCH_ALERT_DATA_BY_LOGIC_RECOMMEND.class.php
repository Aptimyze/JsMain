<?php
/**
* This class is used for storing data into MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND table which contains Number of people - by Logic Type and No. of matches recommended
*/
class MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}

	public function insertCountByLogicTypeAndRecommendForDate($date,$countByLogicAndRecommendations)
	{
		try
		{		//print_r($countByLogicAndRecommendations);die;	
			$sql = "INSERT IGNORE INTO MATCHALERT_TRACKING.MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND (DATE,PIDCOUNT,LOGICLEVEL,RECOMMEDCOUNT) values";
			foreach($countByLogicAndRecommendations as $key=>$val)
			{
				$insertSql.= "(:DATEVAL, ";
				foreach($val as $k1=>$v1)
				{
					if($k1 == "PeopleCount")
					{
						$insertSql.= ":PCOUNT".$key.", ";
					}
					if($k1 == "LOGICLEVEL")
					{
						$insertSql.= ":LOGIC".$key.",";
					}
					if($k1 == "RecCount")
					{
						$insertSql.= " :RECOUNT".$key."),";
					}				
				}
			}
			$insertSql = rtrim($insertSql,",");
			$sql.=$insertSql;			
			$prep = $this->db->prepare($sql);

			$prep->bindValue(":DATEVAL",$date,PDO::PARAM_STR);
			foreach($countByLogicAndRecommendations as $key=>$val)
			{
				foreach($val as $k1=>$v1)
				{
					if($k1 == "PeopleCount")
					{
						$prep->bindValue(":PCOUNT".$key,$v1,PDO::PARAM_INT);
					}
					if($k1 == "LOGICLEVEL")
					{
						$prep->bindValue(":LOGIC".$key,$v1,PDO::PARAM_INT);
					}
					if($k1 == "RecCount")
					{
						$prep->bindValue(":RECOUNT".$key,$v1,PDO::PARAM_INT);
					}				
				}
			}
			$prep->execute();
			$count = $prep->rowCount();
            return $count; 
		}
		catch (PDOException $e)
		{
			//add mail/sms
			jsException::nonCriticalError($e);
		}
	}

}