<?php
class incentive_IN_DIALER extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function insertProfile($profileid,$priority,$campaignName,$eligible)
        {
                try
                {
			$now=date('Y-m-d',time());
                        $sql = "INSERT IGNORE INTO incentive.IN_DIALER (PROFILEID,PRIORITY,CAMPAIGN_NAME,ENTRY_DATE,ELIGIBLE) VALUES(:PROFILEID,:PRIORITY,:CAMPAIGN_NAME,:ENTRY_DATE,:ELIGIBLE)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->bindValue(":PRIORITY",$priority,PDO::PARAM_INT);
			$prep->bindValue(":CAMPAIGN_NAME",$campaignName,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE",$now,PDO::PARAM_STR);
			$prep->bindValue(":ELIGIBLE",$eligible,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	public function fetchProfiles()
        {
                try
                {
                        $sql = "SELECT PROFILEID,ELIGIBLE,PRIORITY FROM incentive.IN_DIALER";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
						while($res=$prep->fetch(PDO::FETCH_ASSOC))
						{
							$profiles[]["PROFILEID"]=$res["PROFILEID"];
							$profiles[]["PRIORITY"]=$res["PRIORITY"];
							$profiles[]["ELIGIBLE"]=$res["ELIGIBLE"];
						}	
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $profiles;
        }
	public function updateDialerEligibility($profileid,$eligible)
	{
		try
		{
			$sql = "UPDATE incentive.IN_DIALER SET ELIGIBLE=:ELIGIBLE WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ELIGIBLE",$eligible,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		
	}
	public function getDialerProfileBasedOnJoins($tableName, $fields)  // $tableName = $databaseName.$tableName (fullname)
	{
		try
		{
			$sql = "SELECT ".$fields." FROM incentive.IN_DIALER id JOIN ".$tableName." tb USING (PROFILEID)";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC)){	
				$res[] = $row;
			}
			return $res;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
        public function fetchDialerProfiles()
        {
                try
                {
                        $sql = "SELECT PROFILEID FROM incentive.IN_DIALER";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        	$profiles[]=$res["PROFILEID"];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
        public function fetchDialerProfilesDetails()
        {
                try
                {
                        $sql = "SELECT * FROM incentive.IN_DIALER";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC)){
				$pid	   		=$res["PROFILEID"];	
                                $profileArr[$pid]	=$res;
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profileArr;
        }

}
?>
