<?php
class MIS_AUTOLOGIN_TRACKING extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->tableName = "MIS.AUTOLOGIN_TRACKING";
			$this->EXECUTIVE_NAME_BIND_TYPE = "STR";
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->IP_BIND_TYPE = "STR";
			$this->TIME_BIND_TYPE = "STR";
        }

	public function insert($logArray)
	{
		if(!is_array($logArray))
			return;
		try
		{
		$count = count($logArray);
		$sqlInsert = "INSERT INTO ".$this->tableName." (PROFILEID, EXECUTIVE_NAME,IP,TIME) VALUES ";
		foreach($logArray as $i=>$x)
			$sqlArr[]="(:PROFILEID".$i.",:EXECUTIVE_NAME".$i.",:IP".$i.",now())";
		$sqlStr = implode(",",$sqlArr);
		$sqlInsert.=$sqlStr;
		$resInsert = $this->db->prepare($sqlInsert);
		foreach($logArray as $k=>$v)
		{
			$resInsert->bindValue(":PROFILEID".$k,$v['PROFILEID'],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":EXECUTIVE_NAME".$k,$v['EXECUTIVE_NAME'],constant('PDO::PARAM_'.$this->{'EXECUTIVE_BIND_TYPE'}));
			$resInsert->bindValue(":IP".$k,$v['IP'],constant('PDO::PARAM_'.$this->{'IP_BIND_TYPE'}));
		}
		$resInsert->execute();
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
			$sqlSelectDetail = "SELECT $fields FROM ".$this->tableName." WHERE ";
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
