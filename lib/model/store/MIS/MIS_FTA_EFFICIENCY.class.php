<?php

class MIS_FTA_EFFICIENCY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

		public function insertProfile($profileid,$type,$agent,$calledTime="")
		{
			try
			{
				$time=date("Y-m-d H:i:s",time());
				if($calledTime)
				{
					$sql ="INSERT IGNORE INTO MIS.FTA_EFFICIENCY(PROFILEID,TYPE,ENTRY_DT,EXECUTIVE,DATE) VALUES(:PROFILEID,:TYPE,:ENTRY_DT,:EXECUTIVE,:CALLED_TIME)";
				}	
				else
					$sql ="INSERT IGNORE INTO MIS.FTA_EFFICIENCY(PROFILEID,TYPE,ENTRY_DT,EXECUTIVE) VALUES(:PROFILEID,:TYPE,:ENTRY_DT,:EXECUTIVE)";
				$res = $this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$res->bindValue(":TYPE", $type, PDO::PARAM_STR);
				$res->bindValue(":ENTRY_DT", $time, PDO::PARAM_STR);
				$res->bindValue(":EXECUTIVE", $agent, PDO::PARAM_STR);
				if($calledTime)
					$res->bindValue(":CALLED_TIME", $calledTime, PDO::PARAM_STR);
				$res->execute();
			}
			catch(PDOException $e)
			{
					throw new jsException($e);
			}
		}
		public function updateProfilesParameters($profileid,$field,$value,$entry_dt)
		{
			try
			{
				$sql ="UPDATE MIS.FTA_EFFICIENCY SET DATE=:DATE WHERE PROFILEID=:PROFILEID AND TYPE=:TYPE AND ENTRY_DT>=:ENTRY_DATE AND ENTRY_DT<=:ACTION_DATE ORDER BY ENTRY_DT DESC LIMIT 1";
				$res = $this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$res->bindValue(":DATE", $value, PDO::PARAM_STR);
				$res->bindValue(":TYPE", $field, PDO::PARAM_STR);
				$res->bindValue(":ACTION_DATE", $value, PDO::PARAM_STR);
				$res->bindValue(":ENTRY_DATE", $entry_dt, PDO::PARAM_STR);
				$res->execute();
			}
			catch(PDOException $e)
			{
					throw new jsException($e);
			}
		}
		public function getCount($agentName,$type,$range)
		{
			try
			{
				$sql ="SELECT COUNT(*) AS COUNT FROM MIS.FTA_EFFICIENCY WHERE DATE>=:SDATE AND DATE <=:EDATE AND EXECUTIVE=:AGENT AND TYPE=:TYPE";
				$res = $this->db->prepare($sql);
				$res->bindValue(":AGENT", $agentName, PDO::PARAM_STR);
				$res->bindValue(":SDATE", $range["START_DATE"], PDO::PARAM_STR);
				$res->bindValue(":EDATE", $range["END_DATE"], PDO::PARAM_STR);
				$res->bindValue(":TYPE", $type, PDO::PARAM_STR);
				$res->execute();
				if($row=$res->fetch(PDO::FETCH_ASSOC))
				{
					$count=$row['COUNT'];
					return $count;
				}
			}
			catch(PDOException $e)
			{
					throw new jsException($e);
			}
		}
}
?>
