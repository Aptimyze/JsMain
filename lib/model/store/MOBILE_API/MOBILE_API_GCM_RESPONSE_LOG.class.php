<?php
class MOBILE_API_GCM_RESPONSE_LOG extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->REGISTRATION_ID_BIND_TYPE = "STR";
			$this->HTTP_STATUS_CODE_BIND_TYPE = "INT";
			$this->STATUS_MESSAGE_BIND_TYPE = "STR";
			$this->NOTIFICATION_KEY_BIND_TYPE = "STR";

        }

	public function insert($logArray)
	{
		if(!is_array($logArray))
			return;
		try
		{
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.GCM_RESPONSE_LOG (PROFILEID, REGISTRATION_ID,HTTP_STATUS_CODE,STATUS_MESSAGE,NOTIFICATION_KEY,DATE) VALUES ";
		foreach($logArray as $i=>$x)
			$sqlArr[]="(:PROFILEID".$i.",:REGISTRATION_ID".$i.",:HTTP_STATUS_CODE".$i.",:STATUS_MESSAGE".$i.",:NOTIFICATION_KEY".$i.",now())";
		if(is_array($sqlArr))
		{
			$sqlStr = implode(",",$sqlArr);
			$sqlInsert.=$sqlStr;
			$resInsert = $this->db->prepare($sqlInsert);
			foreach($logArray as $k=>$v)
			{
				$resInsert->bindValue(":PROFILEID".$k,$v['PROFILEID'],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
				$resInsert->bindValue(":REGISTRATION_ID".$k,$v['REGISTRATION_ID'],constant('PDO::PARAM_'.$this->{'REGISTRATION_ID_BIND_TYPE'}));
				$resInsert->bindValue(":HTTP_STATUS_CODE".$k,$v['HTTP_STATUS_CODE'],constant('PDO::PARAM_'.$this->{'HTTP_STATUS_CODE_BIND_TYPE'}));
				$resInsert->bindValue(":STATUS_MESSAGE".$k,$v['STATUS_MESSAGE'],constant('PDO::PARAM_'.$this->{'STATUS_MESSAGE_BIND_TYPE'}));
				$resInsert->bindValue(":NOTIFICATION_KEY".$k,$v['NOTIFICATION_KEY'],constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
			}
			$resInsert->execute();
		}
		}
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
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
			if($fields!='returnOnlySql')
			{
                	        if(!stristr($fields,"*"))
                        	{
					if($fields)
					{
						foreach($defaultFieldsRequired as $k=>$fieldName)
						{
							if(!stristr($fields,$fieldName))
								$fields.=",".$fieldName;
						}
					}
					else
					{
						$fields = implode (", ",$defaultFieldsRequired);
					}
	                        }
			}
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.GCM_RESPONSE_LOG WHERE ";
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
        public function getDataCountForRange($startDate, $endDate)
        {
                try{
                        $sql = "SELECT count(distinct PROFILEID) count, NOTIFICATION_KEY, STATUS_MESSAGE MESSAGE FROM MOBILE_API.`GCM_RESPONSE_LOG` WHERE DATE >=:START_DATE AND DATE <=:END_DATE GROUP BY NOTIFICATION_KEY, STATUS_MESSAGE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[$row['NOTIFICATION_KEY']][$row['MESSAGE']] =$row['count'];
                        }
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
	public function deleteRecordDateWise($sdate,$edate)
        {
                try{
                        $sql = "delete FROM MOBILE_API.GCM_RESPONSE_LOG WHERE DATE>:ST_DATE AND DATE<:END_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":ST_DATE",$sdate,PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$edate,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
}
?>
