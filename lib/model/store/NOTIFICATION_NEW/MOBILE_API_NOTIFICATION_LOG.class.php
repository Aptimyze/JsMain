<?php
class MOBILE_API_NOTIFICATION_LOG extends TABLE{
        public function __construct($dbname="")
        {
			$dbname ='notification_master';
			$this->databaseName ='NOTIFICATION_NEW';
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->NOTIFICATION_KEY_BIND_TYPE = "STR";
			$this->SENT_BIND_TYPE = "STR";
			$this->OS_TYPE_BIND_TYPE = "STR";
			$this->MESSAGE_ID_BIND_TYPE = "INT";
        }
	public function insert($profileid,$key,$messageId,$sent,$osType)
	{
		$sqlInsert = "INSERT IGNORE INTO  $this->databaseName.NOTIFICATION_LOG (`PROFILEID`,`NOTIFICATION_KEY`,`MESSAGE_ID`,`SEND_DATE`,`SENT`,`OS_TYPE`) VALUES (:PROFILEID,:NOTIFICATION_KEY,:MESSAGE_ID,now(),:SENT,:OS_TYPE)";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":NOTIFICATION_KEY",$key,constant('PDO::PARAM_'.$this->{'NOTIFICATION_KEY_BIND_TYPE'}));
		$resInsert->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
		$resInsert->bindValue(":SENT",$sent,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
		$resInsert->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
		$resInsert->execute();
	}
        public function updateSentPrev($pid,$notificationKey,$status)
        {
                if(!$pid||!$notificationKey||!$status)
                     throw new jsException("","pid or notificationKey or status not provided to updatestatus in $this->databaseName.SCHEDULED_APP_NOTIFICATIONS");
                try
                {
                        $sql = "UPDATE $this->databaseName.NOTIFICATION_LOG SET SENT =:SENT,`UPDATE_DATE`=now() WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY AND PROFILEID =:PROFILEID ORDER BY SEND_DATE DESC LIMIT 1";
                        $res=$this->db->prepare($sql);
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
        public function updateSent($messageId,$status,$osType)
        {
                if(!$messageId || !$status || !$osType)
                     throw new jsException("","pid/notificationKey/status not provided to updatestatus in $this->databaseName.SCHEDULED_APP_NOTIFICATIONS");
                try{
			$sql = "UPDATE $this->databaseName.NOTIFICATION_LOG SET SENT =:SENT,`UPDATE_DATE`=now() WHERE MESSAGE_ID=:MESSAGE_ID AND OS_TYPE=:OS_TYPE AND SENT IN('I','N','P','L')";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
                        $res->bindValue(":SENT",$status,constant('PDO::PARAM_'.$this->{'SENT_BIND_TYPE'}));
			$res->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
        public function deleteNotification($messageId,$osType)
        {
                if(!$messageId || !$osType)
                     throw new jsException("","pid/notificationKey/status not provided to updatestatus in $this->databaseName.SCHEDULED_APP_NOTIFICATIONS");
                try{
			$sql = "DELETE FROM $this->databaseName.NOTIFICATION_LOG WHERE MESSAGE_ID=:MESSAGE_ID AND OS_TYPE=:OS_TYPE AND SENT IN('I','N','P','L')";
                        $res=$this->db->prepare($sql);
                        $res->bindValue(":MESSAGE_ID",$messageId,constant('PDO::PARAM_'.$this->{'MESSAGE_ID_BIND_TYPE'}));
                        $res->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
                        $res->execute();
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
			$sqlSelectDetail = "SELECT $fields FROM $this->databaseName.NOTIFICATION_LOG WHERE ";
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
							$resSelectDetail->bindValue(":".$param."_".$count."_".$k1,$val,PDO::PARAM_STR);
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
        public function getNotificationProfiles($notificationKey)
        {
		try{
                	$sql = "SELECT distinct(PROFILEID) FROM $this->databaseName.NOTIFICATION_LOG WHERE NOTIFICATION_KEY=:NOTIFICATION_KEY AND SENT IN('Y','L')";
                	$res = $this->db->prepare($sql);
                	$res->bindValue(":NOTIFICATION_KEY",$notificationKey, PDO::PARAM_STR);
                	$res->execute();
                	while($rowSelectDetail = $res->fetch(PDO::FETCH_ASSOC))
 	        	       $detailArr[] = $rowSelectDetail['PROFILEID'];
                	return $detailArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }

        public function deleteRecord($date)
        {
                try{
                        $sql = "delete FROM $this->databaseName.NOTIFICATION_LOG WHERE SEND_DATE<:SEND_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":SEND_DATE",$date,PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
                return NULL;
        }
        public function getDataCountForRange($startDate, $endDate)
        {
                try{
                        $sql = "SELECT count(PROFILEID) count, NOTIFICATION_KEY, SENT,OS_TYPE FROM $this->databaseName.`NOTIFICATION_LOG` WHERE SEND_DATE>=:START_DATE AND SEND_DATE<=:END_DATE GROUP BY NOTIFICATION_KEY,SENT,OS_TYPE";
                        $res = $this->db->prepare($sql);
			$res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                        	$rowArr[$row['NOTIFICATION_KEY']][$row['SENT']][$row['OS_TYPE']] =$row['count'];
			}
                        return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function truncateTempNotificationLogTable()
        {
                try{
                        $sql = "truncate table test.TEMP_NOTIFICATION_LOG";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function truncateTempLoginTrackingTable()
        {
                try{
                        $sql = "truncate table test.TEMP_LOGIN_TRACKING";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function createTempTablePool($startDate, $endDate)
        {
                try{
                        $sql = "insert into test.TEMP_NOTIFICATION_LOG SELECT PROFILEID,NOTIFICATION_KEY from $this->databaseName.NOTIFICATION_LOG WHERE SEND_DATE>=:START_DATE AND SEND_DATE<=:END_DATE";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        $res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
                        $res->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
        public function getActiveProfileCount($startDate='', $endDate='')
        {
                try{
                        $sql = "select count(nl.PROFILEID) count, nl.NOTIFICATION_KEY FROM test.TEMP_NOTIFICATION_LOG nl,test.TEMP_LOGIN_TRACKING lt WHERE nl.PROFILEID=lt.PROFILEID"; 
			if($startDate && $endDate)
				$sql .=" AND lt.DATE>=:START_DATE AND lt.DATE<=:END_DATE";
			$sql .=" group by nl.NOTIFICATION_KEY";

                        $res = $this->db->prepare($sql);
			if($startDate && $endDate){
                        	$res->bindValue(":START_DATE",$startDate, PDO::PARAM_STR);
                        	$res->bindValue(":END_DATE",$endDate, PDO::PARAM_STR);
			}
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
                                $rowArr[$row['NOTIFICATION_KEY']] =$row['count'];
                        }
			return $rowArr;
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
	public function selectRecord($sdate,$edate)
        {
                $sql = "SELECT * FROM $this->databaseName.NOTIFICATION_LOG WHERE SEND_DATE>:ST_DATE AND SEND_DATE<:END_DATE";
                $res = $this->db->prepare($sql);
                $res->bindValue(":ST_DATE",$sdate,PDO::PARAM_STR);
                $res->bindValue(":END_DATE",$edate,PDO::PARAM_STR);
                $res->execute();
		while($row = $res->fetch(PDO::FETCH_ASSOC)){
                	$rowArr[] =$row;
                }
                return $rowArr;
 	}
	public function deleteRecordDateWise($sdate,$edate)
        {
                try{
                        $sql = "delete FROM $this->databaseName.NOTIFICATION_LOG WHERE SEND_DATE>:ST_DATE AND SEND_DATE<:END_DATE";
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
