<?php
class NEWJS_JPROFILE_EDUCATION extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

        public function getProfileEducation($pid,$from="")
        {
				try 
			{ 
				if($pid)
				{ 
					$str='';
					if (!is_array($pid)){
						$pidArray[]=$pid;
					}
					else $pidArray=$pid;
						foreach($pidArray as $k=>$v)
						{
						$idArr[]=$v;		
						$idSqlArr[]=":v$k";
						}
						$strId=implode(",",$idSqlArr);
						
                    if($from=="mailer")
                    $sql="SELECT A.*,B.SCREENING,B.EDU_LEVEL_NEW,B.PROFILEID FROM newjs.JPROFILE_EDUCATION A RIGHT JOIN newjs.JPROFILE B ON A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IN ($strId)";
                    else
                    $sql="SELECT * FROM newjs.JPROFILE_EDUCATION WHERE PROFILEID IN ($strId)";
                    $prep=$this->db->prepare($sql);
					foreach($idArr as $k=>$v)
					$prep->bindValue(":v$k", $v, PDO::PARAM_INT);
					$prep->execute();
          
          $this->logFunctionCalling(__FUNCTION__.$from);

					if (is_array($pid)){
						$resultArray=array();
						while($result = $prep->fetch(PDO::FETCH_ASSOC))
						{
							$resultArray[]=$result;
						}
						return $resultArray;
			}

			else {

						if($result = $prep->fetch(PDO::FETCH_ASSOC))
							{
							return $result;
							}
						return false;

				}

				}	
			}
			
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		
		}



	public function update($pid,$paramArr=array())
	{
   
		try {
			$keys="PROFILEID,";
			$values=":PROFILEID ,";
				foreach($paramArr as $key=>$value){
					$keys.=$key.",";
					$values.=":".$key.",";
					$updateStr.=$key."=:".$key.",";
				}
				$updateStr=trim($updateStr,",");
				$keys=substr($keys,0,-1);
				$values=substr($values,0,-1);
				
				$sqlUpdateEducation="Update JPROFILE_EDUCATION SET $updateStr where PROFILEID=:PROFILEID";
				$resUpdateEducation = $this->db->prepare($sqlUpdateEducation);
				foreach($paramArr as $key=>$val)
					$resUpdateEducation->bindValue(":".$key, $val);
				$resUpdateEducation->bindValue(":PROFILEID", $pid);
				$resUpdateEducation->execute();
				
				if(!$resUpdateEducation->rowCount())
				{
					$sqlSelectEducation="Select PROFILEID from JPROFILE_EDUCATION where PROFILEID=:PROFILEID";
					$resSelectEducation = $this->db->prepare($sqlSelectEducation);
					$resSelectEducation->bindValue(":PROFILEID", $pid);
					$resSelectEducation->execute();
					$result=$resSelectEducation->fetch(PDO::FETCH_ASSOC);
					if(!$result[PROFILEID])
					{
						$sqlEditEducation = "REPLACE INTO JPROFILE_EDUCATION ($keys) VALUES ($values)";
						$resEditEducation = $this->db->prepare($sqlEditEducation);
						foreach($paramArr as $key=>$val)
							$resEditEducation->bindValue(":".$key, $val);
						$resEditEducation->bindValue(":PROFILEID", $pid);
						$resEditEducation->execute();
					}
				}
        $this->logFunctionCalling(__FUNCTION__);
				return true;
			}catch(PDOException $e)
				{
					throw new jsException($e);
				}
	}
		

        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from JPROFILE_EDUCATION
         * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are include
d in the result
         * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
         * @param $fields Columns to query
         * @return results Array according to criteria having incremented index
         * @exception jsException for blank criteria
         * @exception PDOException for database level error handling
         */

	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="")
	{
		if(!$valueArray && !$excludeArray && !$greaterThanArray)
			throw new jsException("","no where conditions passed");
		try
		{
			$fields = $fields?$fields:$this->getFields();//Get columns to query
			$sqlSelectDetail = "SELECT $fields FROM newjs.JPROFILE_EDUCATION WHERE ";
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
      $this->logFunctionCalling(__FUNCTION__);
			return $detailArr;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
		return NULL;
	}
        public function gethaveEducationProfiles($date)
        {
                        try
                        {
                                if($date)
                                {
					$sql="SELECT  J.PROFILEID AS PROFILEID FROM JPROFILE_EDUCATION AS JE INNER JOIN JPROFILE J ON ( J.PROFILEID = JE.PROFILEID ) WHERE ENTRY_DT >  :ENTRY_DT AND HAVE_JEDUCATION =  :HAVE_JEDUCATION AND ( SCHOOL != :SCHOOL OR COLLEGE != :COLLEGE OR OTHER_UG_DEGREE !=  :OTHER_UG_DEGREE  OR OTHER_PG_DEGREE != :OTHER_PG_DEGREE OR PG_COLLEGE != :PG_COLLEGE OR PG_DEGREE != :PG_DEGREE OR UG_DEGREE !=:UG_DEGREE)";
                                        $prep=$this->db->prepare($sql);
                                        $prep->bindValue(":ENTRY_DT",$date,PDO::PARAM_STR);
                                        $prep->bindValue(":HAVE_JEDUCATION","N",PDO::PARAM_STR);
                                        $prep->bindValue(":SCHOOL",'',PDO::PARAM_STR);
                                        $prep->bindValue(":COLLEGE",'',PDO::PARAM_INT);
                                        $prep->bindValue(":OTHER_UG_DEGREE",'',PDO::PARAM_STR);
                                        $prep->bindValue(":OTHER_PG_DEGREE",'',PDO::PARAM_STR);
                                        $prep->bindValue(":PG_COLLEGE",'',PDO::PARAM_STR);
                                        $prep->bindValue(":PG_DEGREE",'',PDO::PARAM_STR);
                                        $prep->bindValue(":UG_DEGREE",'',PDO::PARAM_STR);
                                        $prep->execute();
                                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                        {
                                                $res[] = $result[PROFILEID];
                                        }
                                        $this->logFunctionCalling(__FUNCTION__);
                                        return $res;
                                }
                        }
                        catch(PDOException $e)
                        {
                                /*** echo the sql statement and error message ***/
                                throw new jsException($e);
                        }
                }
       //          /**
	   			// * Function to reset JPROFILE_EDUCATION PG and UG data based on inputs provided
	   			// * @param $dataArr- it is the array of profile id's for which the changes are to be made
	   			// * @param $codeFlag- this has value 0 or 1 and help's decide which query is to be build to be executed.
	  			 // */ 
       //          public function resetEducationData($profileID,$codeFlag){
       //          	try{
       //          		if($codeFlag=='0') {
       //          			$sql="UPDATE newjs.JPROFILE_EDUCATION SET PG_COLLEGE=NULL,PG_DEGREE=NULL,OTHER_PG_DEGREE=NULL,OTHER_PG_COLLEGE=NULL WHERE PROFILEID=:PROFILEID";
       //          		}
       //          		else {
       //          			$sql="UPDATE newjs.JPROFILE_EDUCATION SET PG_COLLEGE=NULL,PG_DEGREE=NULL,OTHER_PG_DEGREE=NULL,OTHER_PG_COLLEGE=NULL,UG_DEGREE=NULL,OTHER_UG_DEGREE=NULL,OTHER_UG_COLLEGE=NULL,COLLEGE=NULL WHERE PROFILEID=:PROFILEID";
       //          		}

       //          		$prep=$this->db->prepare($sql);
       //          		$prep->bindValue(":PROFILEID", $profileID, PDO::PARAM_INT);
       //          		$prep->execute();	
       //          	}
       //          	catch(PDOException $e)
       //          	{
       //          		/*** echo the sql statement and error message ***/
       //          		throw new jsException($e);
       //          	}
       //          }
                 /**
	   			* Function to fetch PROFILEID of profiles in JPROFILE whose EDU_LEVEL_NEW is a particular set of values and where PG_DEGREE or PG_COLLEGE is not NULL
	   			* @param $educationCodes- it is the array of codes of EDU_LEVEL_NEW
	   			* @param $codeFlag- this has value 0 or 1 and help's decide which query is to be build to be executed.
	   			* @return $profileArr - returns the array of profileID's that match the given criteria
	  			 */ 
                public function getEducationData($educationCodes,$codeFlag){
                	try{
                		if($codeFlag=='0') {
                			$sql="SELECT J.PROFILEID FROM newjs.JPROFILE AS J JOIN newjs.JPROFILE_EDUCATION AS E ON J.PROFILEID = E.PROFILEID WHERE EDU_LEVEL_NEW IN ($educationCodes) AND (PG_COLLEGE!= ''  OR PG_DEGREE!='' )";
                		} else {
                			$sql="SELECT J.PROFILEID FROM newjs.JPROFILE AS J JOIN newjs.JPROFILE_EDUCATION AS E ON J.PROFILEID = E.PROFILEID WHERE EDU_LEVEL_NEW IN ($educationCodes) AND (PG_COLLEGE!= ''  OR PG_DEGREE!='' OR COLLEGE !='' OR UG_DEGREE!='')";
                		}
                			
                		$prep=$this->db->prepare($sql);	
                		$prep->execute();
                		$profilesArr = array();
                		while($res=$prep->fetch(PDO::FETCH_ASSOC)){
                			$profilesArr[$res["PROFILEID"]] =$res;
                		}
                    
                    $this->logFunctionCalling(__FUNCTION__);
                    
                		return $profilesArr;
                	}
                	catch(PDOException $e)
                	{
                		/*** echo the sql statement and error message ***/
                		throw new jsException($e);
                	}
                }
		private function logFunctionCalling($funName)
    {return;
      /*$key = __CLASS__.'_'.date('Y-m-d');
      JsMemcache::getInstance()->hIncrBy($key, $funName);
      
      JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));*/
    }
}
?>
