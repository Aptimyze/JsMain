<?php
class incentive_NAME_OF_USER extends TABLE{
       

        /**
         * @fn __construct
         * @brief Constructor function
         * @param $dbName - Database to which the connection would be made
         */

        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }

        public function getName($profileid)
        {
            try
            {
                if(is_array($profileid))
                {
                    $profileStr = implode(",", $profileid);
                    $whereStr = "PROFILEID IN(".$profileStr.")";
                }
                else
                {
                    $whereStr = "PROFILEID = :PROFILEID";
                }
                $sql="SELECT SQL_CACHE PROFILEID,NAME from incentive.NAME_OF_USER WHERE ".$whereStr;
                $resSelectDetail = $this->db->prepare($sql);
                if(!is_array($profileid))
                    $resSelectDetail->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                $resSelectDetail->execute();
                if(is_array($profileid))
                {
                    while($rowSelectDetail=$resSelectDetail->fetch(PDO::FETCH_ASSOC))
                        $output[$rowSelectDetail['PROFILEID']] =$rowSelectDetail['NAME'];
                    return $output;
                }
                else
                {    
                    $rowSelectDetail = $resSelectDetail->fetch(PDO::FETCH_ASSOC);
                    return $rowSelectDetail['NAME'];
                }
            }
            catch(Exception $e)
            {
                    throw new jsException($e);
            }
		}
		/* This function inserts entry into NAME_OF_USER table
		 * */
        public function insertName($profileid,$name)
        {
                try
                {
					$sqlUpdateName="Update incentive.NAME_OF_USER SET NAME=:NAME where PROFILEID=:PROFILEID";
					$resUpdateName = $this->db->prepare($sqlUpdateName);
					$resUpdateName->bindValue(":NAME", $name);
					$resUpdateName->bindValue(":PROFILEID", $profileid);
					$resUpdateName->execute();
				
					if(!$resUpdateName->rowCount())
					{
							$sql="REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME) VALUES(:PROFILEID,:NAME)";
							$resSelectDetail = $this->db->prepare($sql);
							$resSelectDetail->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
							$resSelectDetail->bindValue(":NAME", $name);
							$resSelectDetail->execute();
					}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
		
        /**
         * @fn getArray
         * @brief fetches results for multiple profiles to query from PROFILE_NAME
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
			$sqlSelectDetail = "SELECT $fields FROM incentive.NAME_OF_USER WHERE ";
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
		//Whoever have used this function should have first looked first function of this file.Same thing was already defined.
	public function get_name_of_user($profileid)
	{
		return $this->getName($profileid);
	}

                /* This function inserts entry into NAME_OF_USER table
                 * */
        public function insertNameInfo($profileid,$name,$display)
        {
                try
                {
				if(!$profileid || (!$name && !$display))
					throw new jsException("","data missing in insertNameInfo function profileid:".$profileid.",name:".$name.",display:".$display);
					
                                        $sqlUpdateName="Update incentive.NAME_OF_USER SET ";
					if($name)
						$sqlUpdateName.= " NAME=:NAME ";
					if($name && $display)
						$sqlUpdateName.=" , ";
					if($display)
						$sqlUpdateName.= " DISPLAY=:DISPLAY ";
					$sqlUpdateName.=" where PROFILEID=:PROFILEID";
                                        $resUpdateName = $this->db->prepare($sqlUpdateName);
                                        $resUpdateName->bindValue(":PROFILEID", $profileid);
					if($name)
						$resUpdateName->bindValue(":NAME", $name);
					if($display)
						$resUpdateName->bindValue(":DISPLAY", $display);
                                        $resUpdateName->execute();

                                        if(!$resUpdateName->rowCount() && $name!='')
                                        {
                                                        $sql="REPLACE INTO incentive.NAME_OF_USER(PROFILEID,NAME,DISPLAY) VALUES(:PROFILEID,:NAME,:DISPLAY)";
                                                        $resSelectDetail = $this->db->prepare($sql);
                                                        $resSelectDetail->bindValue(":PROFILEID", $profileid);
                                                        $resSelectDetail->bindValue(":NAME", $name);
							$resSelectDetail->bindValue(":DISPLAY", $display);
                                                        $resSelectDetail->execute();
                                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function updateNameInfo($profileid,$arr)
        {
                try
                {
                                if(!$profileid)
                                        throw new jsException("","data missing in insertNameInfo function profileid:".$profileid.",name:".$name.",display:".$display);
                                        foreach($arr as $k=>$v)
                                        {
                                                if($k=="NAME")
                                                        $changeName = true;
                                                if($k=="DISPLAY" && $v!="")
                                                        $changeDisplay = true;
                                        }
                                        if(!$changeName &&!$changeDisplay)
                                                return true;
                                        $sqlUpdateName="Update incentive.NAME_OF_USER SET ";
                                        if($changeName)
                                                $sqlUpdateName.= " NAME=:NAME ";
                                        if($changeName && $changeDisplay)
                                                $sqlUpdateName.=" , ";
                                        if($changeDisplay)
                                                $sqlUpdateName.= " DISPLAY=:DISPLAY ";
                                        $sqlUpdateName.=" where PROFILEID=:PROFILEID";
                                        $resUpdateName = $this->db->prepare($sqlUpdateName);
                                        $resUpdateName->bindValue(":PROFILEID", $profileid);
                                        if($changeName)
                                                $resUpdateName->bindValue(":NAME", $arr['NAME']);
                                        if($changeDisplay)
                                                $resUpdateName->bindValue(":DISPLAY", $arr['DISPLAY']);
                                        $resUpdateName->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }		
}
?>
