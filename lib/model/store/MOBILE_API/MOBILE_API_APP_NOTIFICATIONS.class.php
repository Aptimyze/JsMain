<?php
class MOBILE_API_APP_NOTIFICATIONS extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->ID_BIND_TYPE = "INT";
			$this->NOTIFICATION_KEY_BIND_TYPE = "INT";
			$this->MESSAGE_BIND_TYPE = "STR";
			$this->LANDING_SCREEN_BIND_TYPE = "INT";
			$this->OS_TYPE_BIND_TYPE = "STR";
			$this->STATUS_BIND_TYPE = "STR";
			$this->FREQUENCY_BIND_TYPE = "STR";
			$this->TIME_CRITERIA_BIND_TYPE = "INT";
			$this->PRIORITY_BIND_TYPE = "STR";
			$this->COUNT_BIND_TYPE = "STR";
			$this->COLLAPSE_STATUS_BIND_TYPE = "STR";
			$this->TTL_BIND_TYPE = "INT";
			$this->GENDER_BIND_TYPE = "STR";
			$this->SUBSCRIPTION_BIND_TYPE = "STR";

        }
	public function getArray($valueArray="",$excludeArray="",$greaterThanArray="",$fields="PROFILEID",$lessThanArray="",$orderby="",$limit="",$greaterThanEqualArrayWithoutQuote="", $lessThanEqualArrayWithoutQuote="", $like="",$nolike="",$groupBy="",$having="")
	{
		if(!$valueArray && !$excludeArray  && !$greaterThanArray && !$lessThanArray && !$lessThanEqualArrayWithoutQuote)
			throw new jsException("","no where conditions passed");
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
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.APP_NOTIFICATIONS WHERE ";
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
        public function getActiveNotifications($fields='')
        {
                try{
			if(!$fields)
				$fields ='NOTIFICATION_KEY';
                        $sql = "SELECT distinct $fields FROM MOBILE_API.APP_NOTIFICATIONS WHERE STATUS='Y' ORDER BY FREQUENCY";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[] =$row;
                        }
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function getScheduledNotifications()
        {
                try{
                        $sql = "SELECT distinct NOTIFICATION_KEY FROM MOBILE_API.APP_NOTIFICATIONS WHERE STATUS='Y' AND FREQUENCY NOT IN('I')";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[] =$row['NOTIFICATION_KEY'];
                        }
			$rowArr[] ='PROFILE_VISITOR';
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
}
?>
