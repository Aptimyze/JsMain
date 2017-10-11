<?php
class incentive_PRE_ALLOCATION_TEMP_POOL extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}
	public function truncate()
	{
		$sql="TRUNCATE TABLE incentive.PRE_ALLOCATION_TEMP_POOL";
		$prep = $this->db->prepare($sql);
		$prep->execute();
		
	}	
	public function insertProfile($profileid,$cityRes,$isd,$lastLoginDt)
	{
		try
		{
			$sql="INSERT IGNORE INTO incentive.PRE_ALLOCATION_TEMP_POOL(PROFILEID,CITY_RES,ISD,LAST_LOGIN_DT) VALUES (:PROFILEID,:CITY_RES,:ISD,:LAST_LOGIN_DT)";
			$prep = $this->db->prepare($sql);
        		$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":CITY_RES",$cityRes,PDO::PARAM_STR);
			$prep->bindValue(":ISD",$isd,PDO::PARAM_STR);
			$prep->bindValue(":LAST_LOGIN_DT",$lastLoginDt,PDO::PARAM_STR);
        		$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}

	}
        public function removeAllotedProfiles()
        {
                try
                {
                        $sql="DELETE from incentive.PRE_ALLOCATION_TEMP_POOL WHERE ALLOTMENT!=:ALLOTMENT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ALLOTMENT",'Y',PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
        public function removePreAllotedProfiles()
        {
                try
                {
                        $sql="delete incentive.PRE_ALLOCATION_TEMP_POOL.* from incentive.PRE_ALLOCATION_TEMP_POOL, incentive.PROFILE_ALLOCATION_TECH pat where incentive.PRE_ALLOCATION_TEMP_POOL.PROFILEID=pat.PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
        public function updateProfileDetail($profileid, $analyticScore, $allotmentAvail)
        {
                try
                {
                        $sql="update incentive.PRE_ALLOCATION_TEMP_POOL SET ANALYTIC_SCORE=:ANALYTIC_SCORE,ALLOTMENT=:ALLOTMENT_AVAIL WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ANALYTIC_SCORE", $analyticScore, PDO::PARAM_INT);
			$prep->bindValue(":ALLOTMENT_AVAIL", $allotmentAvail, PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
        public function getProfileDetails()
        {
                try
                {
                        $sql="select map.PROFILEID,map.ALLOTMENT_AVAIL,map.ANALYTIC_SCORE from incentive.PRE_ALLOCATION_TEMP_POOL pt JOIN incentive.MAIN_ADMIN_POOL map ON map.PROFILEID=pt.PROFILEID AND map.ALLOTMENT_AVAIL='Y'";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
				$profiles[] =$result;
			return $profiles;	
               }
               catch(Exception $e)
               {
                       throw new jsException($e);
               }

        }
        function getAnalyticScore($profileid)
        {
                try
                {
                        $sql="select ANALYTIC_SCORE FROM incentive.PRE_ALLOCATION_TEMP_POOL where PROFILEID=:profileid";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":profileid", $profileid, PDO::PARAM_INT);
                        $prep->execute();
                        $res=$prep->fetch(PDO::FETCH_ASSOC);
                        if($res)
                                $score =$res['ANALYTIC_SCORE'];
                        return $score;
                }
                catch(PDOException $e)
                {
                                throw new jsException($e);
                }
        }
        public function fetchProfilesWithCities($centers,$lowerScoreLimit,$loginDtEnd)
        {
                try
                {
                        if(is_array($centers))
                        {
                                foreach($centers as $key=>$val)
                                        $str[] =":CITY_RES$key";
                                $newStr =@implode(",",$str);
                                $sql="SELECT PROFILEID FROM incentive.PRE_ALLOCATION_TEMP_POOL WHERE CITY_RES IN ($newStr) AND ANALYTIC_SCORE>=:lowerScoreLimit AND ANALYTIC_SCORE<=100 AND LAST_LOGIN_DT<:LAST_LOGIN_DT ORDER BY ANALYTIC_SCORE DESC";
                                $prep = $this->db->prepare($sql);
                                foreach($centers as $key=>$val)
                                        $prep->bindValue(":CITY_RES$key",$val,PDO::PARAM_STR);
				$prep->bindValue(":LAST_LOGIN_DT",$loginDtEnd,PDO::PARAM_STR);
                                $prep->bindValue(":lowerScoreLimit",$lowerScoreLimit,PDO::PARAM_INT);
                                $prep->execute();
                        }
                        else
                        {
                                $sql="SELECT PROFILEID FROM incentive.PRE_ALLOCATION_TEMP_POOL WHERE CITY_RES=:CITY AND ANALYTIC_SCORE>=:lowerScoreLimit AND ANALYTIC_SCORE<=100 AND LAST_LOGIN_DT<:LAST_LOGIN_DT ORDER BY ANALYTIC_SCORE DESC";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":CITY",$centers,PDO::PARAM_STR);
				$prep->bindValue(":LAST_LOGIN_DT",$loginDtEnd,PDO::PARAM_STR);
                                $prep->bindValue(":lowerScoreLimit",$lowerScoreLimit,PDO::PARAM_INT);
                                $prep->execute();
                        }
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                $profiles[] = $result['PROFILEID'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
        function fetchNriProfiles()
        {
                try
                {
                        $sql="SELECT PROFILEID,ISD FROM incentive.PRE_ALLOCATION_TEMP_POOL WHERE ISD!='91' AND ISD>0 ORDER BY ANALYTIC_SCORE DESC";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC)){
        	                $profiles[]=$result;
        	        }
                        return $profiles;
                }
                catch(PDOException $e)
                {
                                throw new jsException($e);
                }
        }
}	
?>
