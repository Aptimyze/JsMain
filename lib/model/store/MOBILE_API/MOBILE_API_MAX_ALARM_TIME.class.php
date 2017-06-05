<?php
class MOBILE_API_MAX_ALARM_TIME extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->MAX_ALARM_TIME_BIND_TYPE = "STR";

        }
	public function updateMaxAlarmTime($maxAlarmTime)
	{
		$sql = "TRUNCATE TABLE MOBILE_API.MAX_ALARM_TIME";
		$res = $this->db->prepare($sql);
		$res->execute();
		$sqlUpdate = "INSERT INTO MOBILE_API.MAX_ALARM_TIME VALUES (:MAX_ALARM_TIME)";
		$resUpdate = $this->db->prepare($sqlUpdate);
		$resUpdate->bindValue(":MAX_ALARM_TIME",$maxAlarmTime,constant('PDO::PARAM_'.$this->{'MAX_ALARM_TIME_BIND_TYPE'}));
		$resUpdate->execute();
	}

	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="*",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="", $lessThanEqualArrayWithoutQuote="", $like="",$nolike="",$groupBy="",$having="")
	{
		try
		{
			$arrays = array('valueArray'	=> "IN",
					'excludeArray'	=> "NOT IN",
					'greaterThanArray' => ">",
					'lessThanArray' => "<",
					'greaterThanEqualArrayWithoutQuote' => ">=",
					'lessThanEqualArrayWithoutQuote' => "<=",
					'like' => "LIKE",
					'nolike' => "NOT LIKE");
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.MAX_ALARM_TIME WHERE ";
			if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray && !$lessThanEqualArrayWithoutQuote)
				$sqlSelectDetail.="1";
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$sqlSelectDetail.= $this->getBindString($value,$param,$count,$relation);
						$count++;
					}
				}
			}
			if($groupBy)
			{
				$sqlSelectDetail.=" group by $groupBy ";
				if($having)
				{
					$sqlSelectDetail.=" having ".$having;
				}
			}
			if($orderby)
				$sqlSelectDetail.=" order by $orderby ";
			if($limit)
				$sqlSelectDetail.=" limit $limit ";
			if($fields=='returnOnlySql')
				return $sqlSelectDetail;
			$resSelectDetail = $this->db->prepare($sqlSelectDetail);
			$count = 1;
			foreach($arrays as $executionArray=>$relation)
			{
				if(is_array($$executionArray))
				{
					foreach($$executionArray as $param=>$value)
					{
						$paramBindValue = $this->{$param."_BIND_TYPE"};
						$values = explode(",",$value);
						foreach($values as $k1=>$val)
							$resSelectDetail->bindValue(":".$param."_".$count."_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
						$count++;
					}
				}
			}
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

	public function getBindString($value,$param,$count,$relation)
	{
		if($count>1)
			$return = " AND ";
		
		$array = explode(",",$value);
		foreach($array as $k1=>$val)
			$bindArr[] = ":".$param."_".$count."_".$k1;
		$str=implode(",",$bindArr);
		if(strstr($relation,"LIKE"))
			$return.=" ".$param." ".$relation." '%$str%' ";
		else
			$return.=" ".$param." ".$relation." (".$str.") ";
		return $return;
	}
}
?>
