<?php
class incentive_FTA_ALLOCATION_TECH extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function truncate()
	{
		try
                {
                        $sql="TRUNCATE TABLE incentive.FTA_ALLOCATION_TECH";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
	}
	public function insertProfile($profileid,$user_value,$profile_type)
        {
                try
                {
                        $date=date('Y-m-d',time());
                        $sql = "INSERT IGNORE INTO incentive.FTA_ALLOCATION_TECH (PROFILEID, ALLOTED_TO , ALLOT_DT,HANDLED,PROFILE_TYPE) VALUES(:PROFILEID,:ALLOTED_TO,:DATE,:HANDLED,:PROFILE_TYPE)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->bindValue(":ALLOTED_TO",$user_value,PDO::PARAM_STR);
                        $prep->bindValue(":HANDLED",'N',PDO::PARAM_STR);
                        $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
                        $prep->bindValue(":PROFILE_TYPE",$profile_type,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getPreAllotedFtaProfiles($alloted_to)
        {
                try
                {
                        $sql ="SELECT PROFILE_TYPE,PROFILEID FROM incentive.FTA_ALLOCATION_TECH WHERE ALLOTED_TO=:ALLOTED_TO AND HANDLED='N'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ALLOTED_TO",$alloted_to,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $detailArr;

        }
        public function updateHandledStatus($profileid)
        {
                try
                {
                        $sql="UPDATE incentive.FTA_ALLOCATION_TECH SET HANDLED='Y' WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
        }
}
?>
