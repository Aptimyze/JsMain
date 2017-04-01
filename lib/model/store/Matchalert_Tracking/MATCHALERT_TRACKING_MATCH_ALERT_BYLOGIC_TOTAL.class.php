<?php
/**
* This class is used for storing data into match_alert_byLogic_total table which contains the total count of number of people eligible for Match Alerts each day
*/
class MATCHALERT_TRACKING_MATCH_ALERT_BYLOGIC_TOTAL extends TABLE
{
	public function __construct($dbname='')
	{
		$dbname = $dbname?$dbname:"matchalerts_slave";
		parent::__construct($dbname);
	}

	public function insertTotalCountForDate($date,$totalCount)
	{
		try
		{
			$sql = "INSERT IGNORE into MATCHALERT_TRACKING.MATCH_ALERT_BYLOGIC_TOTAL (DATE,TOTALCOUNT) values (:DATEVAL,:TOTALCOUNT)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":DATEVAL",$date,PDO::PARAM_STR);
			$prep->bindValue(":TOTALCOUNT",$totalCount,PDO::PARAM_INT);
            $prep->execute();
            $count = $prep->rowCount();
            return $count;            
		}
		catch (PDOException $e)
		{
			//add mail/sms
			throw new jsException($e);
		}
	}

}