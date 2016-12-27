<?php

class MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND_TOTAL extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}

	public function insertTotalCountForRecommedByDate($totalCountArr,$todayDate)
	{		
		$i=0;
		$bind = 0;
		try
		{		
			$sql = "INSERT IGNORE INTO MATCHALERT_TRACKING.MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND_TOTAL (DATE,RECOMMENDCOUNT,TOTALCOUNT) values ";
			foreach($totalCountArr as $key=>$value)
			{
				$insertSql.= "(:DATEVAL, :RECCOUNT".$i.", :TOTALCOUNT".$i."),";
				$i++;
			}
			$insertSql = rtrim($insertSql,",");
			$sql.=$insertSql;				
			$prep = $this->db->prepare($sql);

			$prep->bindValue(":DATEVAL",$todayDate,PDO::PARAM_STR);
			foreach($totalCountArr as $key=>$value)
			{				
				$prep->bindValue(":RECCOUNT".$bind,$value["RECOMMENDCOUNT"],PDO::PARAM_INT);
				$prep->bindValue(":TOTALCOUNT".$bind,$value["TOTALCOUNT"],PDO::PARAM_INT);
				$bind++;
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