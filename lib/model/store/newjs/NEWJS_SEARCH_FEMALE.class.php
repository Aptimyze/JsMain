<?php
class NEWJS_SEARCH_FEMALE extends TABLE
{
	public function __construct($dbname='')
	{
		parent::__construct($dbname);
	}

	/**
        This function deletes record from newjs.SEARCH_MALE table
        @params profileid
        **/	
	public function deleteRecord($profileId)
	{
		if(!$profileId)
			throw new jsException("","PROFILEID IS BLANK IN deleteRecord() OF NEWJS_SEARCH_FEMALE.class.php");

		try
		{
			$sql = "DELETE FROM newjs.SEARCH_FEMALE WHERE PROFILEID = :PROFILEID";
			$res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
			$res->execute();
		}
		catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="")
        {
                if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray)
                        throw new jsException("","no where conditions passed");
                try
                {
                        $fields = $fields?$fields:"PROFILEID";
                        $sqlSelectDetail = "SELECT $fields FROM newjs.SEARCH_FEMALE ";
			if(is_array($valueArray) || is_array($excludeArray)  || is_array($greaterThanArray) || is_array($lessThanArray))
			$sqlSelectDetail .= " WHERE ";
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
                        if(is_array($greaterThanEqualArrayWithoutQuote))
                        {
                                foreach($greaterThanEqualArrayWithoutQuote as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam >= $gValue ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam >= $gValue ";
                                        $count++;
                                }
                        }
			if(is_array($lessThanArray))
                        {
                                foreach($lessThanArray as $gParam => $gValue)
                                {
                                        if($count == 1)
                                                $sqlSelectDetail.=" $gParam < '$gValue' ";
                                        else
                                                $sqlSelectDetail.=" AND $gParam < '$gValue' ";
                                        $count++;
                                }
                        }
                        if($orderby)
                        {
                                $sqlSelectDetail.=" order by $orderby ";
                        }
                        if($limit)
                        {
                                $sqlSelectDetail.=" limit $limit ";
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

	/*
        This function is used to get data from search tables of female for apis such as bandhan,doctor republic
        @param - api source (optional),limit(optional)
        @return - resultset rows
        */
	public function getDataForOtherApis($apiSource="",$limit="")
        {
                try
                {
                        $sql = "SELECT S.PROFILEID AS PROFILEID,USERNAME,\"F\" AS GENDER,MANGLIK,CASTE,MSTATUS,MTONGUE,RELIGION,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EDU_LEVEL_NEW,SMOKE,DRINK,HAVECHILD,BTYPE,COMPLEXION,DIET,HANDICAPPED,RELATION,AGE,INCOME FROM newjs.SEARCH_FEMALE S,newjs.SEARCH_FEMALE_TEXT T WHERE S.PROFILEID = T.PROFILEID";
                        if($apiSource == "DOCTOR")
                                $sql = $sql." AND S.EDU_LEVEL_NEW IN (:E1,:E2,:E3)";
                        if($limit)
                                $sql = $sql." LIMIT :LIMIT";

                        $res = $this->db->prepare($sql);
                        if($apiSource == "DOCTOR")
                        {
                                $res->bindValue(":E1",17,PDO::PARAM_INT);
                                $res->bindValue(":E2",19,PDO::PARAM_INT);
                                $res->bindValue(":E3",21,PDO::PARAM_INT);
                        }
                        if($limit)
                                $res->bindValue(":LIMIT",$limit,PDO::PARAM_INT);

                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                $output[] = $row;
                        }
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
                return $output;
        }
                	public function getProfiles($date)
        {
                        //echo $tableName." ".$fieldQuery. " ".$limit;die;
                        try
                        {

                                $sql="SELECT PROFILEID FROM newjs.SEARCH_FEMALE WHERE ENTRY_DT < :date  order by MOD_DT desc ";
                                $prep=$this->db->prepare($sql);
                                //$prep->bindValue(":tableName",$tableName,PDO::PARAM_STR);
                                ////$prep->bindValue("$fieldQuery",$fieldQuery,PDO::PARAM_STR);
                                $prep->bindValue(":date",$date,PDO::PARAM_STR);
                                $prep->execute();

                                while ($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $records[] = $result;
                                }

                                return $records;
                        }
                        catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
         }
	 public function getDailyProfiles($date1,$date2)
        {
                        //echo $tableName." ".$fieldQuery. " ".$limit;die;
                        try
                        {

                                $sql="SELECT PROFILEID FROM newjs.SEARCH_FEMALE WHERE ENTRY_DT BETWEEN :date1 AND :date2  order by MOD_DT desc";
                                $prep=$this->db->prepare($sql);
                                $prep->bindValue(":date1",$date1,PDO::PARAM_STR);
                                $prep->bindValue(":date2",$date2,PDO::PARAM_STR);
                                $prep->execute();

                                while ($result = $prep->fetch(PDO::FETCH_ASSOC))
                                {
                                        $records[] = $result;
                                }

                                return $records;
                        }
                        catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
         }
   /* This function will return those profile id's which have gender male in 
    * JPROFILE and are present in search_female table
    * @return - resultset (profileid array)
    */
   public function getProfilesGenderDiscrepancy() {
     try {
       $sql="SELECT F.PROFILEID FROM newjs.SEARCH_FEMALE AS F JOIN newjs.JPROFILE AS J ON F.PROFILEID=J.PROFILEID WHERE J.GENDER='M'";
       $prep=$this->db->prepare($sql);
       $prep->execute();
       while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
         $records[] = $result;
       }
       return $records;
     }
     catch(PDOException $e) {
       /*** echo the sql statement and error message ***/
       throw new jsException($e);
     }
   }
   /* This function will return those profile id's which have negative flag in 
    * NEGATIVE_TREATMENT_LIST and are present in search_female table
    * @return - resultset (profileid array)
    */
   public function getProfilesNegativeFlag() {
     try {
       $sql="SELECT F.PROFILEID FROM newjs.SEARCH_FEMALE AS F JOIN incentive.NEGATIVE_TREATMENT_LIST AS N ON F.PROFILEID=N.PROFILEID WHERE N.FLAG_VIEWABLE='N'";
       $prep=$this->db->prepare($sql);
       $prep->execute();
       while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
         $records[] = $result;
       }
       return $records;
     }
     catch(PDOException $e) {
       /*** echo the sql statement and error message ***/
       throw new jsException($e);
     }
   }
   /*This function will delete those records which have gender discrepancy 
    * @params profileList
    */
   public function deleteProfilesGenderNFlag($profileList) {
     try {
       $vals = "";
       foreach($profileList as $k=>$v) {
         $vals = $vals.":PRO".$k.",";
       }
       $vals = rtrim($vals,",");
       $sql = "DELETE FROM newjs.SEARCH_FEMALE WHERE PROFILEID IN (".$vals.")";
       $res = $this->db->prepare($sql);
       foreach($profileList as $k=>$v) {
         $res->bindValue(":PRO".$k, $v[PROFILEID], PDO::PARAM_STR);
       }
       $res->execute();
     }
     catch(PDOException $e) {
       throw new jsException($e);
     }
   }
   /* This function will return profile id's and email
    * @return - resultset (profileid and email array)
    */
   public function getProfilesAndEmail() {
     try {
       $sql="SELECT S.PROFILEID,J.EMAIL FROM newjs.SEARCH_FEMALE AS S LEFT JOIN newjs.JPROFILE AS J ON S.PROFILEID=J.PROFILEID";
       $prep=$this->db->prepare($sql);
       $prep->execute();
       while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
         $records[$row['PROFILEID']] = $row['EMAIL'];
       }
       return $records;
     }
     catch(PDOException $e) {
       /*** echo the sql statement and error message ***/
       throw new jsException($e);
     }
   }
   public function updateModifiedTime($profileId,$currentTime)
   {
    try
    {
        $sql = "UPDATE newjs.SEARCH_FEMALE set MOD_DT = :CURRENTTIME WHERE PROFILEID = :PROFILEID";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
        $res->bindValue(":CURRENTTIME", $currentTime, PDO::PARAM_STR);
        $res->execute();
    }
    catch(PDOException $e)
    {
       throw new jsException($e);
    }
   }
}
?>
