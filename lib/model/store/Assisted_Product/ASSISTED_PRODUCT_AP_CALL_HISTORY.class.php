<?php
/*
This class is used to send queries to AP_PROFILE_INFO table in Assisted_Product database
*/
class ASSISTED_PRODUCT_AP_CALL_HISTORY extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }

	/*
	This function fetches the profile id's which are LIVE  
	@param 
	@return an array of profileid 
	*/
	public function Delete($pid)
	{
		try{
			$sql="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID=:profileid OR MATCH_ID=:profileid) AND CALL_STATUS='N'";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}
	public function Update($pid)
        {
                try{
                        $sql="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE (PROFILEID=:profileid OR MATCH_ID=:profileid) AND CALL_STATUS='Y'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
                }
                 catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
	/*
        This function inserts the full data corresponding to a profileid and matchid
        @param 1) profileId 2) matchId
        @return 
        */
        public function Fetch($pid,$match_id)
        {
                if($pid && $match_id)
                {
                        try
                        {
                                $sql = "SELECT * FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID IN(:PID,:MATCH_ID) AND MATCH_ID IN(:PID,:MATCH_ID)";
                                $res = $this->db->prepare($sql);
                                $res->bindValue(":MATCH_ID", $match_id, PDO::PARAM_INT);
                                $res->bindValue(":PID", $pid, PDO::PARAM_INT);
                                $res->execute();
                                if($result = $res->fetch(PDO::FETCH_ASSOC))
                                        return $result;
                                return '';
                        }
                        catch(PDOException $e)
                        {
                                throw new jsException($e);
                        }
                }
                else
                {
                        //throw new jsException("","PROFILEID OR MATCHID IS BLANK IN AP_CALL_HISTORY");
                }
        }
        
        
		public function getIntroCallProfiles($profileId)
		{
			try{
				if(!$profileId)
				{
					throw new jsException("","profileid are not specified in getIntroCallProfiles() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
				}
				$sql = "SELECT DISTINCT(MATCH_ID) AS RECEIVER, REQUEST_DATE AS TIME,CALL_STATUS,CALL_DATE,TELECALLER FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID= :PROFILEID AND CALL_STATUS!='C'";
				$res = $this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
				$res->execute();
				while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
					$result[] = $row;
				}
			}
			catch (PDOException $e) {
				throw new jsException($e);
			}
			return $result;
		}
                public function getCallHistory($profileId){
                    try{
				if(!$profileId)
				{
					throw new jsException("","profileid are not specified in getIntroCallProfiles() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
				}
				$sql = "SELECT DISTINCT(MATCH_ID),CALL_STATUS FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID=:PROFILEID ORDER BY REQUEST_DATE";
				$res = $this->db->prepare($sql);
				$res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
				$res->execute();
				while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
					$result[] = $row;
				}
			}
			catch (PDOException $e) {
				throw new jsException($e);
			}
			return $result;
                }


    public function DeleteProfileAP($pid)
	{
		try{
			$sql="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE (PROFILEID=:profileid OR MATCH_ID=:profileid)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
		}
		 catch(PDOException $e)
	        {
                        throw new jsException($e);
        	}
	}

	public function UpdateDeleteProfile($pid)
        {
                try{
                        $sql="UPDATE Assisted_Product.AP_CALL_HISTORY SET CALL_STATUS='C' WHERE (PROFILEID=:profileid OR MATCH_ID=:profileid)";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":profileid",$pid,PDO::PARAM_INT);
                        $prep->execute();
                }
                 catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        /*function to get intro calls count for profile
        * @param : $profileid
        * @return : $count
        */
        public function getIntroCallsCount($profileId,$CALL_STATUS="",$skippedProfile=''){
                    try{
                if(!$profileId)
                {
                    throw new jsException("","profileid are not specified in getIntroCallsCount() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
                }
                if($CALL_STATUS=="")
                    $CALL_STATUS = 'N';
                $sql = "SELECT COUNT(DISTINCT(MATCH_ID)) AS COUNT FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID=:PROFILEID AND CALL_STATUS=:CALL_STATUS";
                if($skippedProfile)
                {
                    $sql = $sql." AND MATCH_ID NOT IN (";
                    $count = 1;     
                    foreach($skippedProfile as $key1=>$value1)
                    {
                        $str = $str.":VALUE".$count.",";
                        $bindArr["VALUE".$count] = $value1;
                        $count++;
                    }
                    $str = substr($str, 0, -1);
                    $str = $str.")";
                    $sql = $sql.$str;
                }
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $res->bindValue(":CALL_STATUS", $CALL_STATUS, PDO::PARAM_STR);
                if(is_array($bindArr))
                    foreach($bindArr as $k=>$v)
                    {
                        if($v["TYPE"] =="STRING")
                        {
                            $res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
                        }
                        else
                        {
                            $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                        }
                    }
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC)) {
                   $result = $row;
                }
            }
            catch (PDOException $e) {
                throw new jsException($e);
            }
            return $result;
                }

        /*function to get intro call profiles for given profile
        * @param : $condition, $skipArray
        * @return : array of result rows
        */
        public function getCallHistoryConditionBased($condition,$skipArray,$CALL_STATUS)
    	{
    	$string = array('TYPE','SEEN','FILTER','TIME');
        try{
            if(!$condition)
            {
                throw new jsException("","condition is not specified in getCallHistoryConditionBased() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
            }
            $count = 0;
            foreach($condition as $key=>$value)
            {
                if($key=="WHERE")
                {
                    foreach($value as $key1=>$value1)
                    {
                        if($key1 == "NOT_IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $str = $str = "AP_CALL_HISTORY.".$keyName." NOT IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $keyValues =  implode(",",$keyValue);
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                if($keyName =="PROFILEID")
                                    $select = "MATCH_ID";
                                elseif($keyName == "MATCH_ID")
                                    $select = "PROFILEID";
                                $str = $str = "AP_CALL_HISTORY.".$keyName." IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "GREATER_THAN_EQUAL")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $arr[] = $keyName."<= :VALUE".$count;
                                $bindArr["VALUE".$count]["VALUE"] = $keyValue;
                                if(in_array($keyName,$string))
                                    $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                else
                                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                                $count++;
                            }
                        }
                    }
                    $where = "WHERE ".implode(" AND ",$arr);
                }
                if($key == "LIMIT")
                {
                    $limit = " LIMIT ";
                    $limit = $limit.$value;
                }
                if($key == "ORDER" && $value)
                {
                    $order = "ORDER BY $value DESC";
                } 
                    
            }
            if(is_array($skipArray))
            {
                if($select == "MATCH_ID")
                $str = "AP_CALL_HISTORY.MATCH_ID NOT IN (";
                else
                $str = "AP_CALL_HISTORY.PROFILEID NOT IN (";
                
                foreach($skipArray as $key=>$value)
                {
                    $str = $str.":VALUE".$count.",";
                    $bindArr["VALUE".$count]["VALUE"] = $value;
                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                    $count++;
                }
                $str = substr($str, 0, -1);
                $skipProfile = $str.")";
            
                if(!isset($where))
                    $skipProfile = "WHERE ".$skipProfile;
                else
                    $skipProfile = "AND ".$skipProfile;
            }
        $sql = "SELECT DISTINCT(AP_CALL_HISTORY.".$select.") as PROFILEID,AP_CALL_HISTORY.CALL_STATUS,AP_CALL_HISTORY.REQUEST_DATE as TIME,AP_MATCH_COMMENTS.COMMENTS AS CALL_COMMENTS,AP_MATCH_COMMENTS.ADDED_ON AS LAST_CALL_DATE FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS ON ( AP_CALL_HISTORY.MATCH_ID = AP_MATCH_COMMENTS.MATCH_ID ) ".$where." ".$skipProfile." AND AP_CALL_HISTORY.CALL_STATUS=:CALL_STATUS ".$order." ".$limit;		
        $res=$this->db->prepare($sql);
        if(is_array($bindArr))
            foreach($bindArr as $k=>$v)
            {
                if($v["TYPE"] =="STRING")
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
                }
                else
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                }
            }
        $res->bindValue(":CALL_STATUS",$CALL_STATUS,PDO::PARAM_STR);    
        $res->execute();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
            $output[$row["PROFILEID"]]["CALL_STATUS"] = $row["CALL_STATUS"];
            $output[$row["PROFILEID"]]["CALL_COMMENTS"] = $row["CALL_COMMENTS"];
            $output[$row["PROFILEID"]]["LAST_CALL_DATE"] = $row["LAST_CALL_DATE"];
        }
        }
        catch(PDOException $e)
        {
           throw new jsException($e);
        }
        return $output;
    }
    
    /*function to get intro call profiles pending for given profile
        * @param : $condition, $skipArray
        * @return : array of result rows
        */
        public function getHistoryOfIntroCallsPending($condition,$skipArray)
        {
        $string = array('TYPE','SEEN','FILTER','TIME');
        try{
            if(!$condition)
            {
                throw new jsException("","condition is not specified in getCallHistoryConditionBased() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
            }
            $count = 0;
            foreach($condition as $key=>$value)
            {
                if($key=="WHERE")
                {
                    foreach($value as $key1=>$value1)
                    {
                        if($key1 == "NOT_IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $str = $str = "AP_CALL_HISTORY.".$keyName." NOT IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $keyValues =  implode(",",$keyValue);
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                if($keyName =="PROFILEID")
                                    $select = "MATCH_ID";
                                elseif($keyName == "MATCH_ID")
                                    $select = "PROFILEID";
                                $str = $str = "AP_CALL_HISTORY.".$keyName." IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "GREATER_THAN_EQUAL")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $arr[] = $keyName."<= :VALUE".$count;
                                $bindArr["VALUE".$count]["VALUE"] = $keyValue;
                                if(in_array($keyName,$string))
                                    $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                else
                                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                                $count++;
                            }
                        }
                    }
                    $where = "WHERE ".implode(" AND ",$arr);
                }
                if($key == "LIMIT")
                {
                    $limit = " LIMIT ";
                    $limit = $limit.$value;
                }
                if($key == "ORDER" && $value)
                {
                    $order = "ORDER BY $value DESC";
                } 
                    
            }
            if(is_array($skipArray))
            {
                if($select == "MATCH_ID")
                $str = "AP_CALL_HISTORY.MATCH_ID NOT IN (";
                else
                $str = "AP_CALL_HISTORY.PROFILEID NOT IN (";
                
                foreach($skipArray as $key=>$value)
                {
                    $str = $str.":VALUE".$count.",";
                    $bindArr["VALUE".$count]["VALUE"] = $value;
                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                    $count++;
                }
                $str = substr($str, 0, -1);
                $skipProfile = $str.")";
            
                if(!isset($where))
                    $skipProfile = "WHERE ".$skipProfile;
                else
                    $skipProfile = "AND ".$skipProfile;
            }
        $sql = "SELECT DISTINCT(AP_CALL_HISTORY.".$select.") as PROFILEID,AP_CALL_HISTORY.CALL_STATUS,AP_CALL_HISTORY.REQUEST_DATE as TIME,AP_MATCH_COMMENTS.COMMENTS AS CALL_COMMENTS,AP_MATCH_COMMENTS.ADDED_ON AS LAST_CALL_DATE FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS ON ( AP_CALL_HISTORY.MATCH_ID = AP_MATCH_COMMENTS.MATCH_ID AND AP_CALL_HISTORY.PROFILEID = AP_MATCH_COMMENTS.PROFILEID) ".$where." ".$skipProfile." AND AP_CALL_HISTORY.CALL_STATUS='N' AND AP_MATCH_COMMENTS.COMMENTS IS NULL ".$order." ".$limit;       
        $res=$this->db->prepare($sql);
        if(is_array($bindArr))
            foreach($bindArr as $k=>$v)
            {
                if($v["TYPE"] =="STRING")
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
                }
                else
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                }
            }
           
        $res->execute();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
            $output[$row["PROFILEID"]]["CALL_STATUS"] = $row["CALL_STATUS"];
            $output[$row["PROFILEID"]]["CALL_COMMENTS"] = $row["CALL_COMMENTS"];
            $output[$row["PROFILEID"]]["LAST_CALL_DATE"] = $row["LAST_CALL_DATE"];
        }
        }
        catch(PDOException $e)
        {
           throw new jsException($e);
        }

        return $output;
    }
    /*delete entry of selected matchid from profile intro call list
    * @param : array of profileid,matchid
    */
    public function removeFromprofileICList($param)
    {
        try{
            $sql="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID=:PROFILEID AND MATCH_ID=:MATCH_ID AND CALL_STATUS=:CALL_STATUS";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$param["PROFILEID"],PDO::PARAM_INT);
            $prep->bindValue(":MATCH_ID",$param["MATCH_ID"],PDO::PARAM_INT);
            $prep->bindValue(":CALL_STATUS",$param["CALL_STATUS"],PDO::PARAM_STR);
            $prep->execute();
        }
         catch(PDOException $e)
        {
                    throw new jsException($e);
        }
    }

    /*function to get intro calls pending count for profile
        * @param : $profileid,$skippedprofile array
        * @return : $count
        */
        public function getIntroCallsPendingCount($profileId,$skippedProfile=''){
                    try{
                if(!$profileId)
                {
                    throw new jsException("","profileid are not specified in getIntroCallsPendingCount() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
                }
                $sql = "SELECT COUNT(DISTINCT(AP_CALL_HISTORY.MATCH_ID)) AS COUNT FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS ON ( AP_CALL_HISTORY.MATCH_ID = AP_MATCH_COMMENTS.MATCH_ID AND AP_CALL_HISTORY.PROFILEID = AP_MATCH_COMMENTS.PROFILEID) WHERE AP_CALL_HISTORY.PROFILEID=:PROFILEID AND AP_CALL_HISTORY.CALL_STATUS='N' AND AP_MATCH_COMMENTS.COMMENTS IS NULL";
                if($skippedProfile)
                {
                    $sql = $sql." AND AP_CALL_HISTORY.MATCH_ID NOT IN (";
                    $count = 1;     
                    foreach($skippedProfile as $key1=>$value1)
                    {
                        $str = $str.":VALUE".$count.",";
                        $bindArr["VALUE".$count] = $value1;
                        $count++;
                    }
                    $str = substr($str, 0, -1);
                    $str = $str.")";
                    $sql = $sql.$str;
                }
               
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
               
                if(is_array($bindArr))
                    foreach($bindArr as $k=>$v)
                    {
                        if($v["TYPE"] =="STRING")
                        {
                            $res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
                        }
                        else
                        {
                            $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                        }
                    }
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC)) {
                   $result = $row;
                }
            }
            catch (PDOException $e) {
                throw new jsException($e);
            }

            return $result;
                }
        /*function to get intro calls complete count for profile
        * @param : $profileid,$skippedprofile array
        * @return : $count
        */
        public function getIntroCallsCompleteCount($profileId,$skippedProfile=''){
                    try{
                if(!$profileId)
                {
                    throw new jsException("","profileid are not specified in getIntroCallsCompleteCount() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
                }
                $sql = "SELECT COUNT(DISTINCT(AP_CALL_HISTORY.MATCH_ID)) AS COUNT FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS ON ( AP_CALL_HISTORY.MATCH_ID = AP_MATCH_COMMENTS.MATCH_ID AND AP_CALL_HISTORY.PROFILEID = AP_MATCH_COMMENTS.PROFILEID) WHERE AP_CALL_HISTORY.PROFILEID=:PROFILEID AND ((AP_CALL_HISTORY.CALL_STATUS='N' AND AP_MATCH_COMMENTS.COMMENTS IS NOT NULL) OR (AP_CALL_HISTORY.CALL_STATUS='Y'))";
                if($skippedProfile)
                {
                    $sql = $sql." AND AP_CALL_HISTORY.MATCH_ID NOT IN (";
                    $count = 1;     
                    foreach($skippedProfile as $key1=>$value1)
                    {
                        $str = $str.":VALUE".$count.",";
                        $bindArr["VALUE".$count] = $value1;
                        $count++;
                    }
                    $str = substr($str, 0, -1);
                    $str = $str.")";
                    $sql = $sql.$str;
                }
               
                $res = $this->db->prepare($sql);
                $res->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
              
                if(is_array($bindArr))
                    foreach($bindArr as $k=>$v)
                    {                        
                            $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                    }
                $res->execute();

                if($row = $res->fetch(PDO::FETCH_ASSOC)) {
                   $result = $row;
                }
            }
            catch (PDOException $e) {
                throw new jsException($e);
            }

            return $result;
            
                }

    /*function to get intro call profiles complete for given profile
        * @param : $condition, $skipArray
        * @return : array of result rows
        */
        public function getHistoryOfIntroCallsComplete($condition,$skipArray)
        {
        $string = array('TYPE','SEEN','FILTER','TIME');
        try{
            if(!$condition)
            {
                throw new jsException("","condition is not specified in getHistoryOfIntroCallsComplete() OF ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php");
            }
            $count = 0;
            foreach($condition as $key=>$value)
            {
                if($key=="WHERE")
                {
                    foreach($value as $key1=>$value1)
                    {
                        if($key1 == "NOT_IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $str = $str = "AP_CALL_HISTORY.".$keyName." NOT IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $keyValues =  implode(",",$keyValue);
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "IN")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                if($keyName =="PROFILEID")
                                    $select = "MATCH_ID";
                                elseif($keyName == "MATCH_ID")
                                    $select = "PROFILEID";
                                $str = $str = "AP_CALL_HISTORY.".$keyName." IN(";
                                if(!is_array($keyValue))
                                    $keyValue = array($keyValue);
                                foreach($keyValue as $key2=>$value2)
                                {
                                    $str = $str.":VALUE".$count.",";
                                    $bindArr["VALUE".$count]["VALUE"] = $value2;
                                    if(in_array($keyName,$string))
                                        $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                    else
                                        $bindArr["VALUE".$count]["TYPE"] = "INT";
                                    $count++;
                                }
                                $str = substr($str, 0, -1);
                                $str = $str.")";
                                $arr[] = $str;
                            }
                        }
                        if($key1 == "GREATER_THAN_EQUAL")
                        {
                            foreach($value1 as $keyName=>$keyValue)
                            {
                                $arr[] = $keyName."<= :VALUE".$count;
                                $bindArr["VALUE".$count]["VALUE"] = $keyValue;
                                if(in_array($keyName,$string))
                                    $bindArr["VALUE".$count]["TYPE"] = "STRING";
                                else
                                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                                $count++;
                            }
                        }
                    }
                    $where = "WHERE ".implode(" AND ",$arr);
                }
                if($key == "LIMIT")
                {
                    $limit = " LIMIT ";
                    $limit = $limit.$value;
                }
                if($key == "ORDER" && $value)
                {
                    $order = "ORDER BY AP_MATCH_COMMENTS.ADDED_ON DESC";
                } 
                    
            }
            if(is_array($skipArray))
            {
                if($select == "MATCH_ID")
                $str = "AP_CALL_HISTORY.MATCH_ID NOT IN (";
                else
                $str = "AP_CALL_HISTORY.PROFILEID NOT IN (";
                
                foreach($skipArray as $key=>$value)
                {
                    $str = $str.":VALUE".$count.",";
                    $bindArr["VALUE".$count]["VALUE"] = $value;
                    $bindArr["VALUE".$count]["TYPE"] = "INT";
                    $count++;
                }
                $str = substr($str, 0, -1);
                $skipProfile = $str.")";
            
                if(!isset($where))
                    $skipProfile = "WHERE ".$skipProfile;
                else
                    $skipProfile = "AND ".$skipProfile;
            }
        $sql = "SELECT DISTINCT(AP_CALL_HISTORY.".$select.") as PROFILEID,AP_CALL_HISTORY.CALL_STATUS,AP_CALL_HISTORY.REQUEST_DATE as TIME,AP_MATCH_COMMENTS.COMMENTS AS CALL_COMMENTS,AP_MATCH_COMMENTS.ADDED_ON AS LAST_CALL_DATE FROM Assisted_Product.AP_CALL_HISTORY LEFT JOIN Assisted_Product.AP_MATCH_COMMENTS ON ( AP_CALL_HISTORY.MATCH_ID = AP_MATCH_COMMENTS.MATCH_ID AND AP_CALL_HISTORY.PROFILEID = AP_MATCH_COMMENTS.PROFILEID) ".$where." ".$skipProfile." AND ((AP_CALL_HISTORY.CALL_STATUS='N' AND AP_MATCH_COMMENTS.COMMENTS IS NOT NULL) OR (AP_CALL_HISTORY.CALL_STATUS='Y')) ".$order." ".$limit;       
        $res=$this->db->prepare($sql);
        if(is_array($bindArr))
            foreach($bindArr as $k=>$v)
            {
                if($v["TYPE"] =="STRING")
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_STR);
                }
                else
                {
                    $res->bindValue($k,$v["VALUE"],PDO::PARAM_INT);
                }
            }
           
        $res->execute();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $output[$row["PROFILEID"]]["TIME"] = $row["TIME"];
            $output[$row["PROFILEID"]]["CALL_STATUS"] = $row["CALL_STATUS"];
            $output[$row["PROFILEID"]]["CALL_COMMENTS"] = $row["CALL_COMMENTS"];
            $output[$row["PROFILEID"]]["LAST_CALL_DATE"] = $row["LAST_CALL_DATE"];
        }
        }
        catch(PDOException $e)
        {
           throw new jsException($e);
        }

        return $output;
    }
    
}
?>
