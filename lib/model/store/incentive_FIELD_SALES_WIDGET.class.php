<?php
class incentive_FIELD_SALES_WIDGET extends TABLE 
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
		$this->PROFILEID_BIND_TYPE = "INT";
		$this->ENTRY_DT_BIND_TYPE = "STR";
		$this->REQUESTED_BY_BIND_TYPE = "STR";
		$this->REQUESTED_VISIT_DT_BIND_TYPE = "STR";
		$this->VISITED_BIND_TYPE = "STR";
	}

    /*insert new field visit request entry
    * @inputs: $profileid,$requested_by("ONLINE"/agentName),$visited('N'/'Y')
    * @return : none
    */
	public function insertEntry($profileid,$requested_by="ONLINE",$visited='N'){
		try
		{
			$sql="INSERT IGNORE INTO incentive.FIELD_SALES_WIDGET(PROFILEID,ENTRY_DT,REQUESTED_BY,VISITED) VALUES (:PROFILEID,NOW(),:REQUESTED_BY,:VISITED)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->bindValue(":REQUESTED_BY",$requested_by,PDO::PARAM_STR);
            $prep->bindValue(":VISITED",$visited,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}

	public function checkIfProfileidExists($profileid){
		try
		{
			$sql="SELECT COUNT(*) AS COUNT FROM incentive.FIELD_SALES_WIDGET WHERE PROFILEID=:PROFILEID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$count = $result['COUNT'];
			} else {
				$count = 0;
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
		return $count;
	}

    public function checkProfileid($profileid){
        try
        {
            $sql="SELECT * FROM incentive.FIELD_SALES_WIDGET WHERE PROFILEID=:PROFILEID";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_STR);
            $prep->execute();
            
            if($result=$prep->fetch(PDO::FETCH_ASSOC)){
                return $result;
            } 
            else {
                return null;
            }
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
        public function getLastHourScheduledProfiles($entryTimeStart,$entryTimeEnd)
        {
                try
                {
                        $sql = "SELECT distinct PROFILEID FROM incentive.FIELD_SALES_WIDGET WHERE ENTRY_DT>=:ENTRY_TIME_START AND ENTRY_DT<=:ENTRY_TIME_END";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ENTRY_TIME_START",$entryTimeStart,PDO::PARAM_STR);
                        $prep->bindValue(":ENTRY_TIME_END",$entryTimeEnd,PDO::PARAM_STR);
                        $prep->execute();
                        while($result = $prep->fetch(PDO::FETCH_ASSOC))
                                $profiles[]=$result["PROFILEID"];
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
                return $profiles;
        }
        public function getMaxDate()
        {
                try
                {
                        $sql = "SELECT MAX(ENTRY_DT) as ENTRY_DT FROM incentive.FIELD_SALES_WIDGET";
                        $prep = $this->db->prepare($sql);
                        $prep->execute();
                        if($result = $prep->fetch(PDO::FETCH_ASSOC))
                                return $result['ENTRY_DT'];
                        return;
                }
                catch (PDOException $e)
                {
                        throw new jsException($e);
                }
        }

        /*insert selected columns in row
        * @input : $paramsArr
        * @return : none
        */
        public function insertSelectedParams($paramsArr)
        {
			try
			{
				if(is_array($paramsArr) && $paramsArr)
				{
					foreach ($paramsArr as $key => $value) {
						$inputColumns = $inputColumns.$key.",";
						$inputValues = $inputValues.":".$key.",";
					}
					$inputColumns = substr($inputColumns, 0,-1);
					$inputValues = substr($inputValues, 0,-1);
					$sql="INSERT IGNORE INTO incentive.FIELD_SALES_WIDGET(".$inputColumns.") VALUES (".$inputValues.")";
					$prep = $this->db->prepare($sql);
					foreach ($paramsArr as $key => $value) {
						$prep->bindValue(":".$key,$value,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
					}
					$prep->execute();
				}
			}
			catch(Exception $e)
			{
				throw new jsException($e);
			}
		}

		/*fetch rows from FIELD_SALES_WIDGET on some conditions
		* @inputs: $value,$criteria,$fields,$orderby,$limit
		* @return : array of matched rows
		*/
	public function getArray($value="",$criteria="PROFILEID",$fields="*",$orderby="",$limit="")
	{
        if(!value){
            throw new jsException("","$criteria IS BLANK in incentive_FIELD_SALES_WIDGET class");
        }
        try{
            $sql = "SELECT $fields FROM incentive.FIELD_SALES_WIDGET WHERE $criteria = :$criteria ";
            if($orderby){
                $sql.= " ORDER BY $orderby DESC ";
            }
            if($limit){
                $sql.= "LIMIT $limit";
            }
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":$criteria", $value, PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC)){
                $result[$row["PROFILEID"]] = $row;
            }
            return $result;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    /**
     * update details in FIELD_SALES_WIDGET---
     * @param : $criteria,$value,$updateStr
     * @return : none
     */
    public function updateEntry($criteria="PROFILEID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
    {
    	if(!$value && !$inWhereStr)
            throw new jsException("value or inWhereStr IS BLANK in updateEntry func of incentive_FIELD_SALES_WIDGET class");
    	if(!$updateArr)
            throw new jsException("updateArr IS BLANK in updateEntry func of incentive_FIELD_SALES_WIDGET class");
        $updateStr="";
        foreach ($updateArr as $key1 => $val1) {
            $updateStr = $updateStr."$key1=:$key1,";
            $extraBind[$key1]=$val1;
        }
        $updateStr = substr($updateStr,0,-1);
        try {    
            $sql = "UPDATE incentive.FIELD_SALES_WIDGET set $updateStr WHERE ";
            if($inWhereStr)
                $sql = $sql."$criteria IN :$criteria";
            else
                $sql = $sql."$criteria = :$criteria";
            if(is_array($extraWhereClause))
            {
	            foreach($extraWhereClause as $key=>$val)
	            {
	                $sql.=" AND $key=:$key";
	                $extraBind[$key]=$val;
	            }
            }
       
            $res = $this->db->prepare($sql);
            if($inWhereStr)
                $res->bindValue(":$criteria", $value, PDO::PARAM_STR);
            else
                $res->bindValue(":$criteria", $value, constant('PDO::PARAM_'.$this->{$criteria.'_BIND_TYPE'}));
            if(is_array($extraBind))
            {
            	foreach($extraBind as $key=>$val)
            		$res->bindValue(":$key", $val,constant('PDO::PARAM_'.$this->{$key.'_BIND_TYPE'}));
            }
            $res->execute();
        }
        catch(PDOException $e){
                throw new jsException($e);
        }
        return NULL;
    }
    
}
?>
