<?php
class MOBILE_API_ALARM_TIME extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->ALARM_TIME_BIND_TYPE = "STR";

        }
	public function insert($profileid,$alarmTime)
	{
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.ALARM_TIME (`REG_ID` ,`PROFILEID`,OS_TYPE,NOTIFICATION_STATUS,`TIME`) VALUES (:REG_ID,:PROFILEID,:OS_TYPE,:NOTIFICATION_STATUS,now())";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":REG_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":NOTIFICATION_STATUS","Y",constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->execute();
	}
        public function replace($data)
        {
		if(!is_array($data))
			throw new jsException("","replace data not found");
                $sqlInsert = "REPLACE INTO  MOBILE_API.ALARM_TIME (`PROFILEID`,`ALARM_TIME`) VALUES ";
		foreach($data as $k=>$v)
		{
			$sqlInsertArr[]= "(:PROFILEID_".$k.",:ALARM_TIME_".$k.")";
		}
		$sqlInsert.=implode(",", $sqlInsertArr);
                $resInsert = $this->db->prepare($sqlInsert);
		foreach($data as $k=>$v)
		{
			$resInsert->bindValue(":PROFILEID_".$k,$k,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":ALARM_TIME_".$k,$v,constant('PDO::PARAM_'.$this->{'ALARM_TIME_BIND_TYPE'}));
		}
                $resInsert->execute();
        }

	public function updateAlarmTime($profileid,$alarmTime)
	{
		$sqlUpdate = "UPDATE  MOBILE_API.ALARM_TIME SET `PROFILEID` =:PROFILEID,`TIME`=now() WHERE`REG_ID`=:REG_ID";
		$resUpdate = $this->db->prepare($sqlUpdate);
		$resUpdate->bindValue(":REG_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resUpdate->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resUpdate->execute();
	}
        public function getData($profileid)
        {
                $sqlSel = "SELECT ALARM_TIME FROM MOBILE_API.ALARM_TIME WHERE PROFILEID=:PROFILEID";
                $resSel = $this->db->prepare($sqlSel);
		$resSel->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resSel->execute();
                if($rowSelectDetail =$resSel->fetch(PDO::FETCH_ASSOC))
                        $alaremTime =$rowSelectDetail['ALARM_TIME'];
                return $alaremTime;
        }
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="", $lessThanEqualArrayWithoutQuote="", $like="",$nolike="",$groupBy="",$having="")
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
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.ALARM_TIME WHERE ";
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
        public function getIST()
        {
		$dateTime =date("Y-m-d H:i:s");
                $sql = "SELECT CONVERT_TZ('$dateTime','SYSTEM','right/Asia/Calcutta') as time";
                $res = $this->db->prepare($sql);
                $res->execute();
                if($row = $res->fetch(PDO::FETCH_ASSOC))
                	$dateTime = $row['time'];
                return $dateTime;
        }


}
?>
