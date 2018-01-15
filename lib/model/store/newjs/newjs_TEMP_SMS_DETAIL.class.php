<?php
class newjs_TEMP_SMS_DETAIL extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->SMS_TYPE_BIND_TYPE  = "STR";
			$this->SMS_KEY_BIND_TYPE   = "STR";
			$this->MESSAGE_BIND_TYPE   = "STR";
			$this->ADD_DATE_BIND_TYPE  = "STR";
			$this->PHONE_MOB_BIND_TYPE = "STR";
			$this->PRIORITY_BIND_TYPE  = "STR";
			$this->SENT_BIND_TYPE	   = "STR";
			$this->MUL_SMS_BIND_TYPE   = "STR";
			$this->TYPE_BIND_TYPE	   = "STR";
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
					/*if($fields)
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
					}*/
	                        }
			}
			$sqlSelectDetail = "SELECT $fields FROM newjs.TEMP_SMS_DETAIL WHERE ";
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
	public function updateSent($pStr)
	{
		$paramBindValue = $this->{"PROFILEID_BIND_TYPE"};
		$str = $this->getBindString($pStr,"PROFILEID",1,"IN");
                $sqlUpdate="UPDATE newjs.TEMP_SMS_DETAIL SET `SENT` = 'Y' WHERE ".$str." AND SENT!='Y'";// AND TYPE='N'";
		$resUpdate = $this->db->prepare($sqlUpdate);
		$values = explode(",",$pStr);
		foreach($values as $k1=>$val)
			$resUpdate->bindValue(":PROFILEID_1_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
		$resUpdate->execute();
	}
        public function updateSentForVD($pStr)
        {
                $paramBindValue = $this->{"PROFILEID_BIND_TYPE"};
                $str = $this->getBindString($pStr,"PROFILEID",1,"IN");
                $sqlUpdate="UPDATE newjs.TEMP_SMS_DETAIL SET `SENT` = 'Y' WHERE ".$str." AND SENT!='Y' AND SMS_KEY IN ('VD1','VD2')";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $values = explode(",",$pStr);
                foreach($values as $k1=>$val)
                        $resUpdate->bindValue(":PROFILEID_1_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
                $resUpdate->execute();
        }
        public function updateSentForNotification($pStr,$notificationKey)
        {
                $paramBindValue = $this->{"PROFILEID_BIND_TYPE"};
		$smsKeyBindType =$this->{"SMS_KEY_BIND_TYPE"}; 
                $str = $this->getBindString($pStr,"PROFILEID",1,"IN");
                $sqlUpdate="UPDATE newjs.TEMP_SMS_DETAIL SET `SENT` = 'Y' WHERE ".$str." AND SENT!='Y' AND SMS_KEY=:SMS_KEY";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $values = explode(",",$pStr);
                foreach($values as $k1=>$val)
                        $resUpdate->bindValue(":PROFILEID_1_".$k1,$val,constant('PDO::PARAM_'.$paramBindValue));
		$resUpdate->bindValue(":SMS_KEY",$notificationKey, constant('PDO::PARAM_'.$smsKeyBindType));
                $resUpdate->execute();
        }

    public function deletePreviousVdEntries($pidArr)
    {
		if(empty($pidArr) || !is_array($pidArr)){
			throw new jsException("newjs_TEMP_SMS_DETAIL::deletePreviousVdEntries - Profile Array is either empty or not an array");
		} else {
			foreach ($pidArr as $key=>$profileid) {
				$sqlUpdate="DELETE FROM newjs.TEMP_SMS_DETAIL WHERE PROFILEID=:PROFILEID AND SMS_KEY IN ('VD1','VD2')";	
				$resUpdate = $this->db->prepare($sqlUpdate);
				$resUpdate->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$resUpdate->execute();
			}
		}
    }
}
?>
