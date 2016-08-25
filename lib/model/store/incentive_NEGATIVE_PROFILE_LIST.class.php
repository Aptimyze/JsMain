<?php
class incentive_NEGATIVE_PROFILE_LIST extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }
	public function checkNegativeList($profileid)
	{
		try
                {
			$sql = "SELECT COUNT(*) AS COUNT from incentive.NEGATIVE_PROFILE_LIST WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
                        $negativeProfile=$result['COUNT'];

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $negativeProfile;
	}
	public function AllEntry($profileid)
	{
		try
                {
                        $sql = "SELECT TYPE,ENTRY_DT,ENTRY_BY from incentive.NEGATIVE_PROFILE_LIST WHERE PROFILEID=:PROFILEID order by ID asc";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
	                {
			        $data[]=$result;
			}

                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
	}
        public function getProfileDetails($typeStr)
        {
                try{
                        $sql = "SELECT PROFILEID ,EMAIL,MOBILE ,ISD,STD_CODE,LANDLINE from incentive.NEGATIVE_PROFILE_LIST WHERE TYPE IN($typeStr)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
                                $data[]=$result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $data;
        }

}
?>
