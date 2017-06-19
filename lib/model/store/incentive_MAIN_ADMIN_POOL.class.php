<?php
class incentive_MAIN_ADMIN_POOL extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}
	public function get($value="",$criteria="PROFILEID",$fields="",$extraWhereClause=null)
        {
                try
                {
                        $sql="SELECT $fields from incentive.MAIN_ADMIN_POOL WHERE $criteria = :$value";
                        if(is_array($extraWhereClause))
                        {
                                foreach($extraWhereClause as $key=>$val)
                                {
                                        $sql.=" AND $key='$val'";
                                }
                        }
                        $resSelectDetail = $this->db->prepare($sql);
                        $resSelectDetail->bindValue(":$value", $value, PDO::PARAM_INT);
                        $resSelectDetail->execute();
                        $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
                        return $rowSelectDetail;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        function getEligibileProfile($profileid,$scoreMin,$scoreMax)
        {
                try
                {
                        $sql="select PROFILEID FROM incentive.MAIN_ADMIN_POOL where PROFILEID=:PROFILEID AND ANALYTIC_SCORE>=:ANALYTIC_SCORE1 AND ANALYTIC_SCORE<=:ANALYTIC_SCORE2";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_STR);
			$prep->bindValue(":ANALYTIC_SCORE1", $scoreMin, PDO::PARAM_INT);
			$prep->bindValue(":ANALYTIC_SCORE2", $scoreMax, PDO::PARAM_INT);
                        $prep->execute();
                        $res=$prep->fetch(PDO::FETCH_ASSOC);
                        if($res)
                        	return 1;
			return;
                }
                catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
        }
	 public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID")
        {
                if(!$valueArray && !$excludeArray  && !$greaterThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
                        $sqlSelectDetail = "SELECT $fields FROM incentive.MAIN_ADMIN_POOL WHERE ";
                        $count = 1;
                        if(is_array($valueArray))
                        {
                                foreach($valueArray as $param=>$value)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $param IN ($value) ";
                                        else
                                                $sqlSelectDetail.=" AND $param IN ($value) ";
                                        $count++;
                                }
                        }
                        if(is_array($excludeArray))
                        {
                                foreach($excludeArray as $excludeParam => $excludeValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $excludeParam NOT IN ($excludeValue) ";
                                        else
						 $sqlSelectDetail.=" AND $excludeParam NOT IN ($excludeValue) ";
                                        $count++;
                                }
                        }
                        if(is_array($greaterThanArray))
                        {
                                foreach($greaterThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam > '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam > '$gValue' ";
                                        $count++;
                                }
                        }

                        $resSelectDetail = $this->db->prepare($sqlSelectDetail);
                        /*
                        foreach ($valueArray as $k => $val)
                        {
                                $resSelectDetail->bindValue(($k+1), $val);
                        }
                        */
                        $resSelectDetail->execute();
                        while($rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        {
                                $detailArr[] = $rowSelectDetail;
                        }
			  return $detailArr;
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return NULL;
        }

	public function updateProfile($profileid)
	{
		try
		{
			$sql="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='Y' WHERE PROFILEID=:PROFILEID";
		   	$prep = $this->db->prepare($sql);
        		$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
        		$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       

	}


    public function setTimesTriedZero($profileid)
    {
        try
        {
            $sql="UPDATE incentive.MAIN_ADMIN_POOL SET TIMES_TRIED=:ZERO WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                $prep->bindValue(":ZERO",0,PDO::PARAM_STR);
                $prep->execute();
        }
        catch(Exception $e)
        {
                throw new jsException($e);
        }       

    }


        public function updateAllotmentStatus($profileid)
        {
                try
                {
                        $sql="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='N' WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }

        }
	public function updateScore($profileid,$score)
        {
                $sql="UPDATE incentive.MAIN_ADMIN_POOL SET SCORE=:SCORE WHERE PROFILEID = :PROFILEID";
                $res=$this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $res->bindValue(":SCORE", $score, PDO::PARAM_INT);
                $res->execute();
        }

	public function updatePoolForSubMethod($deAllMethodObj)
	{
		try
		{
			$sql = "UPDATE incentive.MAIN_ADMIN_POOL p, incentive.MAIN_ADMIN m, incentive.CRM_DAILY_ALLOT a SET p.ALLOTMENT_AVAIL ='Y' WHERE m.PROFILEID=p.PROFILEID AND a.PROFILEID=m.PROFILEID AND DATE_ADD(m.ALLOT_TIME, INTERVAL a.RELAX_DAYS DAY) < DATE_SUB(CURDATE(), INTERVAL :MAX_DAYS DAY) AND m.STATUS <>'P' AND a.ALLOT_TIME=m.ALLOT_TIME AND m.ALLOTED_TO NOT IN (:EXECUTIVES)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":MAX_DAYS",$deAllMethodObj->getMaxDays(),PDO::PARAM_STR);
                        $prep->bindValue(":EXECUTIVES",$deAllMethodObj->getExecutives(),PDO::PARAM_STR);
                        $prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
	public function fetchProfilesWithCities($centers,$lowerScoreLimit)
        {
                try
                {
                        if(is_array($centers))
                        {
                                //$count = count($centers);
                                //$in_params = trim(str_repeat('?, ', $count), ', ');

		                foreach($centers as $key=>$val)
        		                $str[] =":CITY_RES$key";
        		        $newStr =@implode(",",$str);
                                $sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES IN ($newStr) AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=:lowerScoreLimit AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC LIMIT 90000";
                                $prep = $this->db->prepare($sql);

		                foreach($centers as $key=>$val)
        		                $prep->bindValue(":CITY_RES$key",$val,PDO::PARAM_STR);
				$prep->bindValue(":lowerScoreLimit",$lowerScoreLimit,PDO::PARAM_INT);
                                $prep->execute();
                        }
                        else
                        {
                                $sql="SELECT PROFILEID FROM incentive.MAIN_ADMIN_POOL WHERE CITY_RES=:CITY AND ALLOTMENT_AVAIL ='Y' AND ANALYTIC_SCORE>=:lowerScoreLimit AND ANALYTIC_SCORE<=100 ORDER BY ANALYTIC_SCORE DESC LIMIT 90000";
                                $prep = $this->db->prepare($sql);
                                $prep->bindValue(":CITY",$centers,PDO::PARAM_STR);
				$prep->bindValue(":lowerScoreLimit",$lowerScoreLimit,PDO::PARAM_INT);
                                $prep->execute();
                        }
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profiles[] = $result['PROFILEID'];
                        }
                }
                catch(Exception $e)
                {
			throw new jsException($e);
                }
                return $profiles;
        }
	public function fetchProfilesWithScore($profiles)
	{
		if($profiles=='')
			return;
		try
                {
                        $sql="SELECT PROFILEID,CITY_RES,SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ({$profiles}) ORDER BY ANALYTIC_SCORE DESC";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profilesArr[] = $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $profilesArr;
	}
	 
	function getPhoneStatus($profileid)
	{ 
		try
		{
			$sql="select PROFILEID FROM incentive.MAIN_ADMIN_POOL where PROFILEID=:profileid AND TIMES_TRIED>3";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":profileid", $profileid, PDO::PARAM_STR);
			$prep->execute();
			$res=$prep->fetch(PDO::FETCH_ASSOC);
			if($res)
			return 'I';
		}
		catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}			
	}

	public function getScore($profileId)
        {
                if(!$profileId)
                        throw new jsException("","PROFILEID IS BLANK IN getScore() of incentive_MAIN_ADMIN_POOL.class.php");

                if(is_array($profileId))
                {
                        $profileIdStr = "'".implode("','",$profileId)."'";
                        $flag = 1;
                }
                else
                        $flag = 0;

                try
                {
                        if($flag)
                                $sql = "SELECT PROFILEID,SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID IN ($profileIdStr)";
                        else
                                $sql = "SELECT PROFILEID,SCORE FROM incentive.MAIN_ADMIN_POOL WHERE PROFILEID = :PROFILEID";
                        $res=$this->db->prepare($sql);
                        if(!$flag)
                                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[$row["PROFILEID"]]=$row["SCORE"];
                        }
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }

		if($output)
                        return $output;
                else
                        return null;
        }
        function getProfilesForRegulatrSales()
        {
                try
                {
                        $sql="select ENTRY_DT,PROFILEID,ANALYTIC_SCORE FROM incentive.MAIN_ADMIN_POOL WHERE ANALYTIC_SCORE>=1 AND ANALYTIC_SCORE<=100 AND CUTOFF_DT>=DATE_SUB(CURDATE(),INTERVAL 10 DAY) AND MTONGUE <> '1' AND ENTRY_DT<=DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
                        $prep=$this->db->prepare($sql);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC))
				$resultArr[] =$res;
                }
                catch(PDOException $e)
                {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                }
		return $resultArr;
        }
        function getAnalyticScore($profileid)
        {
                try
                {
                        $sql="select ANALYTIC_SCORE FROM incentive.MAIN_ADMIN_POOL where PROFILEID=:profileid";
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
	public function getProfileDetails($profileArr){
		try{
	                if(!is_array($profileArr))
        	                throw new jsException("","Blank array for profiles");
			$profileStr =implode(",",$profileArr);
			$sql = "SELECT jp.PROFILEID, jp.EMAIL, jp.PHONE_WITH_STD, jp.PHONE_MOB, map.SCORE SCORE FROM newjs.JPROFILE jp INNER JOIN incentive.MAIN_ADMIN_POOL map ON (jp.PROFILEID=map.PROFILEID) WHERE jp.PROFILEID IN($profileStr) AND jp.SOURCE<>'ofl_prof' AND jp.SUBSCRIPTION NOT LIKE '%T%' ORDER BY SCORE";
			$prep=$this->db->prepare($sql);
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC)){
				$output[] = $result;
			}
			return $output;
		} catch(PDOException $e){
			throw new jsException($e);
		}
	}

}
	/*
	public function updatePoolForRenewal($deAllMethodObj)
	{
		try
		{	
			$sql = "UPDATE incentive.MAIN_ADMIN_POOL p, incentive.MAIN_ADMIN m, incentive.CRM_DAILY_ALLOT a SET p.ALLOTMENT_AVAIL ='Y' WHERE m.PROFILEID=p.PROFILEID AND a.PROFILEID=m.PROFILEID AND DATE_ADD(m.ALLOT_TIME, INTERVAL a.RELAX_DAYS DAY) < DATE_ADD(:DAYS,INTERVAL 1 DAY)  AND DATE_ADD(m.ALLOT_TIME, INTERVAL a.RELAX_DAYS DAY) > :DAYS AND m.STATUS <>'P' AND a.ALLOT_TIME=m.ALLOT_TIME AND m.ALLOTED_TO IN (:REN_AGENT)";
		        $prep = $this->db->prepare($sql);
			$prep->bindValue(":DAYS",$deAllMethodObj->getDays(),PDO::PARAM_STR);
			$prep->bindValue(":REN_AGENT",$deAllMethodObj->getExecutives(),PDO::PARAM_STR);
        		$r=$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}

	}
	public function updatePoolForUpsell($deAllMethodObj)
	{
		try
		{
			$sql = "UPDATE incentive.MAIN_ADMIN_POOL p, incentive.MAIN_ADMIN m, incentive.CRM_DAILY_ALLOT a SET p.ALLOTMENT_AVAIL ='Y' WHERE m.PROFILEID=p.PROFILEID AND a.PROFILEID=m.PROFILEID AND m.ALLOT_TIME < DATE_SUB(CURDATE(), INTERVAL 15 DAY) AND a.ALLOT_TIME=m.ALLOT_TIME AND m.ALLOTED_TO IN (:UPSELL_AGENT)";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":UPSELL_AGENT",$deAllMethodObj->getExecutives(),PDO::PARAM_STR);
                        $prep->execute();

		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}

	}
	public function updatePoolForNormal($deAllMethodObj)
	{
		try
		{
			$sql = "UPDATE incentive.MAIN_ADMIN_POOL p, incentive.MAIN_ADMIN m, incentive.CRM_DAILY_ALLOT a SET p.ALLOTMENT_AVAIL ='Y' WHERE m.PROFILEID=p.PROFILEID AND a.PROFILEID=m.PROFILEID AND DATE_ADD(m.ALLOT_TIME, INTERVAL a.RELAX_DAYS DAY) < DATE_SUB(CURDATE(), INTERVAL 15 DAY) AND m.STATUS <>'P' AND a.ALLOT_TIME=m.ALLOT_TIME AND m.ALLOTED_TO NOT IN (:EXECUTIVES)";
			$prep = $this->db->prepare($sql);
                        $prep->bindValue(":EXECUTIVES",$deAllMethodObj->getExecutives(),PDO::
PARAM_STR);
                        $prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}*/
?>
