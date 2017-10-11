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
        public function getProfileDetails($typeStr,$id1='', $id2='')
        {
                try{
                        $sql = "SELECT PROFILEID,EMAIL,MOBILE,ISD,STD_CODE,LANDLINE,COMMENTS,TYPE from incentive.NEGATIVE_PROFILE_LIST WHERE TYPE IN($typeStr)";
			if($id1 && $id2)
				$sql .=" AND ID>='$id1' AND ID<='$id2'";
                        $prep = $this->db->prepare($sql);
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
    public function removeProfile($negType, $negativeVal)
    {
        try {
            $sql = "DELETE FROM incentive.NEGATIVE_PROFILE_LIST WHERE $negType=:VALUE_VAL";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":VALUE_VAL", $negativeVal, PDO::PARAM_STR);
            $prep->execute();
            $rows_affected =$prep->rowCount();
            return $rows_affected;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function removeProfileUsingPhone($negativeVal)
    {
        try {
            $sql = "DELETE FROM incentive.NEGATIVE_PROFILE_LIST WHERE (MOBILE=:VALUE_VAL OR LANDLINE=:VALUE_VAL OR CONCAT(ISD,STD_CODE,LANDLINE)=:VALUE_VAL)";
            $prep = $this->db->prepare($sql);
       	    $prep->bindValue(":VALUE_VAL", $negativeVal, PDO::PARAM_STR);
            $prep->execute();
            $rows_affected =$prep->rowCount();
            return $rows_affected;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }

}
?>
