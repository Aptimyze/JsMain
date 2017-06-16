<?php
class MOBILE_API_SCHEDULED_APP_NOTIFICATIONS extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->ID_BIND_TYPE = "INT";
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->NOTIFICATION_KEY_BIND_TYPE = "STR";
			$this->MESSAGE_BIND_TYPE = "STR";
			$this->LANDING_SCREEN_BIND_TYPE = "INT";
			$this->OS_TYPE_BIND_TYPE = "STR";
			$this->PRIORITY_BIND_TYPE = "INT";
			$this->COLLAPSE_STATUS_BIND_TYPE = "STR";
			$this->TTL_BIND_TYPE = "INT";
			$this->SCHEDULED_DATE_BIND_TYPE = "STR";
			$this->SENT_BIND_TYPE = "STR";
			$this->TITLE_BIND_TYPE = "STR";
			$this->COUNT_BIND_TYPE = "INT";
			$this->MSG_ID_BIND_TYPE = "INT";
            $this->PHOTO_URL_BIND_TYPE = "STR";
            $this->IOS_PHOTO_URL_BIND_TYPE = "STR";
            $this->PROFILE_CHECKSUM_BIND_TYPE = "STR";
            $this->REG_ID_BIND_TYPE = "STR";
			$this->tableName = "MOBILE_API.SCHEDULED_APP_NOTIFICATIONS";
        }
	public function truncate()
	{
		$sql = "TRUNCATE TABLE ". $this->tableName;
		$res = $this->db->prepare($sql);
                $res->execute();
	}
	public function insert($insertData)
	{
		if(!is_array($insertData))
			return;
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.SCHEDULED_APP_NOTIFICATIONS (`PROFILEID`,`NOTIFICATION_KEY`,`MESSAGE`,`LANDING_SCREEN`,`OS_TYPE`,`COLLAPSE_STATUS`,`TTL`,SCHEDULED_DATE,SENT,`TITLE`,`COUNT`,`PRIORITY`,`MSG_ID`,`PHOTO_URL`,`PROFILE_CHECKSUM`,`REG_ID`,`IOS_PHOTO_URL`) VALUES ";
		foreach($insertData as $k=>$v)
		{
			if($sqlPart!='')
				$sqlPart.=",";
			$sqlPart.= "(:PROFILEID".$k.",:NOTIFICATION_KEY".$k.",:MESSAGE".$k.",:LANDING_SCREEN".$k.",:OS_TYPE".$k.",:COLLAPSE_STATUS".$k.",:TTL".$k.",now(),:SENT".$k.",:TITLE".$k.",:COUNT".$k.",:PRIORITY".$k.",:MSG_ID".$k.",:PHOTO_URL".$k.",:PROFILE_CHECKSUM".$k.",:REG_ID".$k.",:IOS_PHOTO_URL".$k.")";
		}
		$sqlInsert.=$sqlPart;
		$resInsert = $this->db->prepare($sqlInsert);
		foreach($insertData as $k=>$v)
		{
			$resInsert->bindValue(":PROFILEID".$k,$v['PROFILEID'],constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$resInsert->bindValue(":NOTIFICATION_KEY".$k,$v['NOTIFICATION_KEY'],constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
			$resInsert->bindValue(":MESSAGE".$k,$v['MESSAGE'],constant('PDO::PARAM_'.$this->{'MESSAGE_BIND_TYPE'}));
			$resInsert->bindValue(":LANDING_SCREEN".$k,$v['LANDING_SCREEN'],constant('PDO::PARAM_'.$this->{'LANDING_SCREEN_BIND_TYPE'}));
			$resInsert->bindValue(":OS_TYPE".$k,$v['OS_TYPE'],constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
			$resInsert->bindValue(":COLLAPSE_STATUS".$k,$v['COLLAPSE_STATUS'],constant('PDO::PARAM_'.$this->{'COLLAPSE_STATUS_BIND_TYPE'}));
			$resInsert->bindValue(":TTL".$k,$v['TTL'],constant('PDO::PARAM_'.$this->{'TTL_BIND_TYPE'}));
			$resInsert->bindValue(":TITLE".$k,$v['TITLE'],constant('PDO::PARAM_'.$this->{'TITLE_BIND_TYPE'}));
			$resInsert->bindValue(":COUNT".$k,$v['COUNT'],constant('PDO::PARAM_'.$this->{'COUNT_BIND_TYPE'}));
			$resInsert->bindValue(":MSG_ID".$k,$v['MSG_ID'],constant('PDO::PARAM_'.$this->{'MSG_ID_BIND_TYPE'}));
			$resInsert->bindValue(":PRIORITY".$k,$v['PRIORITY'],constant('PDO::PARAM_'.$this->{'PRIORITY_BIND_TYPE'}));
			$resInsert->bindValue(":SENT".$k,$v['SENT'],constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
            $resInsert->bindValue(":PHOTO_URL".$k,$v['PHOTO_URL'],constant('PDO::PARAM_'.$this->{'PHOTO_URL_BIND_TYPE'}));
            $resInsert->bindValue(":IOS_PHOTO_URL".$k,$v['IOS_PHOTO_URL'],constant('PDO::PARAM_'.$this->{'IOS_PHOTO_URL_BIND_TYPE'}));
            $resInsert->bindValue(":PROFILE_CHECKSUM".$k,$v['PROFILE_CHECKSUM'],constant('PDO::PARAM_'.$this->{'PROFILE_CHECKSUM_BIND_TYPE'}));
            $resInsert->bindValue(":REG_ID".$k,$v['REG_ID'],constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		}
		
		//$resInsert->bindValue(":SENT","N",constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
		$resInsert->execute();
		if(is_array($insertData) && (count($insertData)==1))
		{
			return $this->db->lastInsertId();
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
						/* section code does not execute at all
						foreach($defaultFieldsRequired as $k=>$fieldName)
						{
							if(!stristr($fields,$fieldName))
								$fields.=",".$fieldName;
						}*/
					}
					else
					{
						$fields = implode (", ",$defaultFieldsRequired);
					}
	                        }
			}
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.SCHEDULED_APP_NOTIFICATIONS WHERE ";
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
	public function updateSent($idArr='',$notificationKey,$status,$pid='')
	{
                if((!is_array($idArr) && !$pid &&  !$msgId) || !$notificationKey || !$status)
		     throw new jsException("","(idArr & pid) or notificationKey or status not provided to updatestatus in MOBILE_API.SCHEDULED_APP_NOTIFICATIONS");
                try
                {
			foreach($idArr as $k=>$v)
				$arr[]=":ID".$k;
			$str=implode(",",$arr);
			$sql = "UPDATE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS SET SENT =:SENT WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY ";
			if(is_array($idArr))
				$sql.= " AND ID IN (".$str.") AND SENT='N'";
			if($pid)
				$sql.=" AND PROFILEID =:PROFILEID ";
			if($msdId)
				$sql.=" AND MSG_ID =:MSG_ID ";
		
			$res=$this->db->prepare($sql);
			if(is_array($idArr))
			{
				foreach($idArr as $k=>$v)
					$res->bindValue(":ID".$k,$v,constant('PDO::PARAM_'.$this->{'ID_BIND_TYPE'}));
			}
			if($pid)
				$res->bindValue(":PROFILEID",$pid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			if($msgId)
				$res->bindValue(":MSG_ID",$msgId,constant('PDO::PARAM_'.$this->{'MSG_ID_BIND_TYPE'}));
			$res->bindValue(":NOTIFICATION_KEY",$notificationKey,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
			$res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
			$res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
	}
        public function updateSuccessSent($status,$messageId)
        {
                if(!$status || !$messageId)
                     throw new jsException("","status or messageId not provided to updatestatus in MOBILE_API.SCHEDULED_APP_NOTIFICATIONS");
                try
                {
			$sql = "UPDATE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS SET SENT =:SENT WHERE MSG_ID=:MSG_ID AND SENT IN('I','N','P')";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":MSG_ID",$messageId,constant('PDO::PARAM_'.$this->{'MSG_ID_BIND_TYPE'}));
                        $res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function updateNotificationStatus($idArr='',$notificationKey,$status,$pid='')
		{
                if((!is_array($idArr)&&!$pid)||!$notificationKey||!$status)
		     throw new jsException("","(idArr & pid) or notificationKey or status not provided to updateCancelledStatus in MOBILE_API.SCHEDULED_APP_NOTIFICATIONS");
                try
                {
			foreach($idArr as $k=>$v)
				$arr[]=":ID".$k;
			$str=implode(",",$arr);
			$sql = "UPDATE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS SET SENT =:SENT WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY ";
			if(is_array($idArr))
				$sql.= " AND ID IN (".$str.")";
			if($pid)
				$sql.=" AND PROFILEID =:PROFILEID ";
			$res=$this->db->prepare($sql);
			if(is_array($idArr))
			{
				foreach($idArr as $k=>$v)
					$res->bindValue(":ID".$k,$v,constant('PDO::PARAM_'.$this->{'ID_BIND_TYPE'}));
			}
			if($pid)
				$res->bindValue(":PROFILEID",$pid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
			$res->bindValue(":NOTIFICATION_KEY",$notificationKey,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
			$res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
			$res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        
}
?>
