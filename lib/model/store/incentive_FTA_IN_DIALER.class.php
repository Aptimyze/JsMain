<?php
class incentive_FTA_IN_DIALER extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function insert($profileid)
        {
                try
                {
						$now=date('Y-m-d',time());
                        $sql = "INSERT IGNORE INTO incentive.FTA_IN_DIALER (PROFILEID,ENTRY_DATE,ELIGIBLE) VALUES(:PROFILEID,:ENTRY_DATE,'Y')";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->bindValue(":ENTRY_DATE",$now,PDO::PARAM_STR);
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
                        $sql = "SELECT PROFILEID,ELIGIBLE,PRIORITY FROM incentive.FTA_IN_DIALER";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        $i=0;
						while($res=$prep->fetch(PDO::FETCH_ASSOC))
						{
							$profiles[$i]["PROFILEID"]=$res["PROFILEID"];
							$profiles[$i]["PRIORITY"]=$res["PRIORITY"];
							$profiles[$i]["ELIGIBLE"]=$res["ELIGIBLE"];
							$i++;
						}	
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
				return $profiles;
        }
		public function updateDialerEligibility($profileid,$eligible,$priority)
		{
			try
	                {
	                        $sql = "UPDATE incentive.FTA_IN_DIALER SET ELIGIBLE=:ELIGIBLE,PRIORITY=:PRIORITY WHERE PROFILEID=:PROFILEID";
	                        $prep = $this->db->prepare($sql);
	                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
	                        $prep->bindValue(":ELIGIBLE",$eligible,PDO::PARAM_STR);
	                        $prep->bindValue(":PRIORITY",$priority,PDO::PARAM_INT);
	                        $prep->execute();
							
					}
			                catch(Exception $e)
			                {
			                        throw new jsException($e);
			                }
			
		}
}
?>
