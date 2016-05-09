<?php
class incentive_FIELD_SALES_LOG extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

	public function insertRecord($profileid, $allotTime)
	{
		try
		{
			$sql= "INSERT INTO incentive.FIELD_SALES_LOG(PROFILEID,ALLOT_TIME) VALUES(:PROFILEID,:ALLOT_TIME)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}
        public function getProfiles($dateTime)
        {
                try
                {
                        $sql="SELECT distinct PROFILEID FROM incentive.FIELD_SALES_LOG WHERE ALLOT_TIME>=:ALLOT_TIME";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":ALLOT_TIME",$dateTime,PDO::PARAM_STR);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profiles[]=$res['PROFILEID'];
                        }
			return $profiles;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
