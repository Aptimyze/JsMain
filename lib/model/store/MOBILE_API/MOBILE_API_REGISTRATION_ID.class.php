<?php
class MOBILE_API_REGISTRATION_ID extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
			$this->PROFILEID_BIND_TYPE = "INT";
			$this->REG_ID_BIND_TYPE = "STR";
			$this->OS_TYPE_BIND_TYPE = "STR";
			$this->NOTIFICATION_STATUS_BIND_TYPE = "STR";
			$this->APP_VERSION_BIND_TYPE = "STR";
			$this->OS_VERSION_BIND_TYPE = "STR";
			$this->DEVICE_BRAND_BIND_TYPE ="STR";
			$this->DEVICE_MODEL_BIND_TYPE ="STR";
        }
	public function insert($registrationid,$profileid='',$osType,$appVersion,$osVersion,$deviceBrand='',$deviceModel='')
	{
		$sqlInsert = "INSERT IGNORE INTO  MOBILE_API.REGISTRATION_ID (`REG_ID` ,`PROFILEID`,OS_TYPE,NOTIFICATION_STATUS,`TIME`,`APP_VERSION`,`OS_VERSION`,`DEVICE_BRAND`,`DEVICE_MODEL`) VALUES (:REG_ID,:PROFILEID,:OS_TYPE,:NOTIFICATION_STATUS,now(),:APP_VERSION,:OS_VERSION,:DEVICE_BRAND,:DEVICE_MODEL)";
		$resInsert = $this->db->prepare($sqlInsert);
		$resInsert->bindValue(":REG_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resInsert->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":NOTIFICATION_STATUS","Y",constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resInsert->bindValue(":APP_VERSION",$appVersion,constant('PDO::PARAM_'.$this->{'APP_VERSION_BIND_TYPE'}));
		$resInsert->bindValue(":OS_VERSION",$osVersion,constant('PDO::PARAM_'.$this->{'OS_VERSION_BIND_TYPE'}));
		$resInsert->bindValue(":DEVICE_BRAND",$deviceBrand,constant('PDO::PARAM_'.$this->{'DEVICE_BRAND_BIND_TYPE'}));
		$resInsert->bindValue(":DEVICE_MODEL",$deviceModel,constant('PDO::PARAM_'.$this->{'DEVICE_MODEL_BIND_TYPE'}));
		$resInsert->execute();
	}
	public function updateVersion($registrationid,$appVersion='',$osVersion='',$deviceBrand='',$deviceModel='')
	{
		if(!$registrationid)
			return;
                $sqlUpdate = "UPDATE  MOBILE_API.REGISTRATION_ID SET `APP_VERSION` =:APP_VERSION, `OS_VERSION`=:OS_VERSION, `DEVICE_BRAND`=:DEVICE_BRAND, `DEVICE_MODEL`=:DEVICE_MODEL,`TIME`=now() WHERE`REG_ID`=:REG_ID";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $resUpdate->bindValue(":REG_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resUpdate->bindValue(":APP_VERSION",$appVersion,constant('PDO::PARAM_'.$this->{'APP_VERSION_BIND_TYPE'}));
                $resUpdate->bindValue(":OS_VERSION",$osVersion,constant('PDO::PARAM_'.$this->{'OS_VERSION_BIND_TYPE'}));
                $resUpdate->bindValue(":DEVICE_BRAND",$deviceBrand,constant('PDO::PARAM_'.$this->{'DEVICE_BRAND_BIND_TYPE'}));
                $resUpdate->bindValue(":DEVICE_MODEL",$deviceModel,constant('PDO::PARAM_'.$this->{'DEVICE_MODEL_BIND_TYPE'}));
                $resUpdate->execute();
	}
	public function updateProfileId($registrationid,$profileid='')
	{
		$sqlUpdate = "UPDATE  MOBILE_API.REGISTRATION_ID SET `PROFILEID` =:PROFILEID,`TIME`=now() WHERE`REG_ID`=:REG_ID";
		$resUpdate = $this->db->prepare($sqlUpdate);
		$resUpdate->bindValue(":REG_ID",$registrationid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resUpdate->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resUpdate->execute();
	}
	public function updateRegId($oldRegistrationId,$newRegistrationId)
	{
		$sqlUpdate = "UPDATE  MOBILE_API.REGISTRATION_ID SET `REG_ID` =:NEW_REG_ID,`TIME`=now() WHERE`REG_ID`=:OLD_REG_ID";
		$resUpdate = $this->db->prepare($sqlUpdate);
		$resUpdate->bindValue(":OLD_REG_ID",$oldRegistrationId,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resUpdate->bindValue(":NEW_REG_ID",$newRegistrationId,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resUpdate->execute();
		return $resUpdate->rowCount();
	}
	public function deleteRegId($regid)
	{
		$sqlDelete = "DELETE FROM MOBILE_API.REGISTRATION_ID WHERE `REG_ID` = :REG_ID";
		$resDelete = $this->db->prepare($sqlDelete);
		$resDelete->bindValue(":REG_ID",$regid,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
		$resDelete->execute();
	}
        public function updateNotificationStatus($profileid,$notificationStatus)
        {
                if(!in_array($notificationStatus,array("Y","N")))
		     throw new jsException("","Error in value for update in notification status");
		$sqlUpdate = "UPDATE  MOBILE_API.REGISTRATION_ID SET NOTIFICATION_STATUS =:NOTIFICATION_STATUS,`TIME`=now() WHERE PROFILEID=:PROFILEID";
                $resUpdate = $this->db->prepare($sqlUpdate);
                $resUpdate->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resUpdate->bindValue(":NOTIFICATION_STATUS",$notificationStatus,constant('PDO::PARAM_'.$this->{'NOTIFICATION_STATUS_BIND_TYPE'}));
                $resUpdate->execute();
        }
        public function getNotificationState($registrationId)
        {
                $sqlSel = "SELECT NOTIFICATION_STATUS FROM MOBILE_API.REGISTRATION_ID WHERE REG_ID=:REG_ID AND NOTIFICATION_STATUS=:NOTIFICATION_STATUS";
                $resSel = $this->db->prepare($sqlSel);
                $resSel->bindValue(":REG_ID",$registrationId,constant('PDO::PARAM_'.$this->{'REG_ID_BIND_TYPE'}));
                $resSel->bindValue(":NOTIFICATION_STATUS","Y",constant('PDO::PARAM_'.$this->{'NOTIFICATION_STATUS_BIND_TYPE'}));
                $resSel->execute();
                if($rowSelectDetail =$resSel->fetch(PDO::FETCH_ASSOC))
                        $status =$rowSelectDetail['NOTIFICATION_STATUS'];
                return $status;
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
			$sqlSelectDetail = "SELECT $fields FROM MOBILE_API.REGISTRATION_ID WHERE ";
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
        public function getResObj($noOfScripts,$currentScript,$andAppVersion='',$iosAppVersion='',$androidMaxVersion='')
        {
                //$sqlUpdate = "SELECT DISTINCT(PROFILEID) FROM MOBILE_API.REGISTRATION_ID WHERE PROFILEID%:NO_OF_SCRIPTS=:CURRENT_SCRIPT";
		$sqlUpdate = "SELECT DISTINCT(PROFILEID) FROM MOBILE_API.REGISTRATION_ID WHERE PROFILEID%:NO_OF_SCRIPTS=:CURRENT_SCRIPT AND (";
        //To pick profiles having maximum app versions.
        if($androidMaxVersion)
            $sqlUpdate = $sqlUpdate."(APP_VERSION>=:AND_APP_VERSION AND APP_VERSION<:MAX_AND_APP_VERSION AND OS_TYPE=:AND_OS_TYPE)";
		elseif($andAppVersion)
			$sqlUpdate = $sqlUpdate."(APP_VERSION>=:AND_APP_VERSION AND OS_TYPE=:AND_OS_TYPE)";
		//else
		//	$sqlUpdate = $sqlUpdate."(OS_TYPE=:AND_OS_TYPE)";
		if($iosAppVersion)
			$sqlUpdate = $sqlUpdate." OR (APP_VERSION>=:IOS_APP_VERSION AND OS_TYPE=:IOS_OS_TYPE)";
		//else
		//	$sqlUpdate = $sqlUpdate." OR (OS_TYPE=:IOS_OS_TYPE)";
		$sqlUpdate = $sqlUpdate.")";
	        $resUpdate = $this->db->prepare($sqlUpdate);
		$resUpdate->bindValue(":NO_OF_SCRIPTS",$noOfScripts,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		$resUpdate->bindValue(":CURRENT_SCRIPT",$currentScript,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
		if($andAppVersion){
			$resUpdate->bindValue(":AND_APP_VERSION",$andAppVersion,constant('PDO::PARAM_'.$this->{'APP_VERSION_BIND_TYPE'}));
			$resUpdate->bindValue(":AND_OS_TYPE",'AND',constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
		}
        if($androidMaxVersion){
            $resUpdate->bindValue(":MAX_AND_APP_VERSION",$androidMaxVersion,constant('PDO::PARAM_'.$this->{'APP_VERSION_BIND_TYPE'}));
        }
		if($iosAppVersion){
			$resUpdate->bindValue(":IOS_APP_VERSION",$iosAppVersion,constant('PDO::PARAM_'.$this->{'APP_VERSION_BIND_TYPE'}));
			$resUpdate->bindValue(":IOS_OS_TYPE",'IOS',constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
		}
        	$resUpdate->execute();
		return $resUpdate;
        }
        public function appRegisteredProfile($profileid)
        {
                $sqlSel = "SELECT PROFILEID FROM MOBILE_API.REGISTRATION_ID WHERE PROFILEID=:PROFILEID AND NOTIFICATION_STATUS=:NOTIFICATION_STATUS";
                $resSel = $this->db->prepare($sqlSel);
                $resSel->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
                $resSel->bindValue(":NOTIFICATION_STATUS","Y",constant('PDO::PARAM_'.$this->{'NOTIFICATION_STATUS_BIND_TYPE'}));
                $resSel->execute();
		if($rowSelectDetail = $resSel->fetch(PDO::FETCH_ASSOC))
			$pid =$rowSelectDetail['PROFILEID'];
                return $pid;
        }

        public function getValidRegisteredProfiles($osType="AND")
        {
            $sqlSel = "SELECT PROFILEID,REG_ID FROM MOBILE_API.REGISTRATION_ID WHERE PROFILEID IS NOT NULL && PROFILEID <> 0 AND NOTIFICATION_STATUS=:NOTIFICATION_STATUS AND OS_TYPE=:OS_TYPE";
            $resSel = $this->db->prepare($sqlSel);
            $resSel->bindValue(":OS_TYPE",$osType,constant('PDO::PARAM_'.$this->{'OS_TYPE_BIND_TYPE'}));
            $resSel->bindValue(":NOTIFICATION_STATUS","Y",constant('PDO::PARAM_'.$this->{'NOTIFICATION_STATUS_BIND_TYPE'}));
            $resSel->execute();
            $output = array();
			while($rowSelectDetail = $resSel->fetch(PDO::FETCH_ASSOC))
				$output[] =$rowSelectDetail;
	        return $output;
	    }
        
        public function checkNotificationSubscriptionStatus($profileid){
        	try{
        		if($profileid){
        			$sql = "SELECT * from MOBILE_API.REGISTRATION_ID WHERE PROFILEID = :PROFILEID ORDER BY TIME DESC LIMIT 1";
	        		$res = $this->db->prepare($sql);
        			$res->bindValue(":PROFILEID",$profileid,constant('PDO::PARAM_'.$this->{'PROFILEID_BIND_TYPE'}));
	        		$res->execute();
	        		while($row = $res->fetch(PDO::FETCH_ASSOC)){
	        			$result["notificationStatus"]=$row['NOTIFICATION_STATUS'];
	        		}
	        		return $result;
	        	}
			}
			catch(PDOException $e)
			{
				throw new jsException($e);
			}
		}

}
?>
