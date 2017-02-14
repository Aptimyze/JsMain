<?php

class INCENTIVE_NEGATIVE_TREATMENT_LIST extends TABLE {

    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }


public function isFtoDuplicate($profileId){

        try {
            $sql = "SELECT PROFILEID FROM incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and TYPE=:TYPE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
            $prep->bindValue(":TYPE",'Fto_Duplicate', PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;  
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
   



}

    public function isFlagViewable ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and FLAG_VIEWABLE=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;  
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function isFlagInboxEoi ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM  incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and FLAG_INBOX_EOI=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function isFlagContactDetail ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM  incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and FLAG_CONTACT_DETAIL=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function isFlagOutboundCall ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM  incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and FLAG_OUTBOUND_CALL=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function isFlagInboundCall ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and FLAG_INBOUND_CALL=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }
    public function isChatInitiation ($profileid,$flag)
    {
        try {
            $sql = "SELECT PROFILEID FROM  incentive.NEGATIVE_TREATMENT_LIST WHERE  PROFILEID=:PROFILEID and CHAT_INITIATION=:FLAG";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            $prep->bindValue(":FLAG", $flag, PDO::PARAM_STR);
            $prep->execute();
            if($result = $prep->fetch(PDO::FETCH_ASSOC)) {
              return 1;
            }
            return 0;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
    }

	/*
	*This function inserts record into the incentive.NEGATIVE_TREATMENT_LIST table
	*/
	public function addRecord($paramArr,$fto="")
	{
		if(!isset($paramArr["PROFILEID"]) || !isset($paramArr["TYPE"])  || !isset($paramArr["ENTRY_BY"])  || !isset($paramArr["FLAG_VIEWABLE"])  || !isset($paramArr["FLAG_INBOX_EOI"])  || !isset($paramArr["FLAG_CONTACT_DETAIL"])  || !isset($paramArr["FLAG_OUTBOUND_CALL"])  || !isset($paramArr["FLAG_INBOUND_CALL"]) || !isset($paramArr["CHAT_INITIATION"]))
			throw new jsException("","paramArr IS BLANK IN addRecord() OF incentive_NEGATIVE_TREATMENT_LIST.class.php");

		try
		{
			if($fto)
				$sql = "INSERT INTO incentive.NEGATIVE_TREATMENT_LIST (PROFILEID,TYPE,ENTRY_BY,ENTRY_DT,FLAG_VIEWABLE,FLAG_INBOX_EOI,FLAG_CONTACT_DETAIL,FLAG_OUTBOUND_CALL,FLAG_INBOUND_CALL,CHAT_INITIATION) VALUES (:PROFILEID,:TYPE,:ENTRY_BY,NOW(),:FLAG_VIEWABLE,:FLAG_INBOX_EOI,:FLAG_CONTACT_DETAIL,:FLAG_OUTBOUND_CALL,:FLAG_INBOUND_CALL,:CHAT_INITIATION) ON DUPLICATE KEY UPDATE TYPE=:TYPE,ENTRY_BY=:ENTRY_BY,ENTRY_DT=NOW(),FLAG_VIEWABLE=:FLAG_VIEWABLE,FLAG_INBOX_EOI=:FLAG_INBOX_EOI,FLAG_OUTBOUND_CALL=:FLAG_OUTBOUND_CALL";
			else
				$sql = "INSERT IGNORE INTO incentive.NEGATIVE_TREATMENT_LIST (PROFILEID,TYPE,ENTRY_BY,ENTRY_DT,FLAG_VIEWABLE,FLAG_INBOX_EOI,FLAG_CONTACT_DETAIL,FLAG_OUTBOUND_CALL,FLAG_INBOUND_CALL,CHAT_INITIATION) VALUES (:PROFILEID,:TYPE,:ENTRY_BY,NOW(),:FLAG_VIEWABLE,:FLAG_INBOX_EOI,:FLAG_CONTACT_DETAIL,:FLAG_OUTBOUND_CALL,:FLAG_INBOUND_CALL,:CHAT_INITIATION)";
				
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_INT);
			$prep->bindValue(":TYPE", $paramArr["TYPE"], PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_BY", $paramArr["ENTRY_BY"], PDO::PARAM_STR);
			$prep->bindValue(":FLAG_VIEWABLE", $paramArr["FLAG_VIEWABLE"], PDO::PARAM_STR);	
			$prep->bindValue(":FLAG_INBOX_EOI", $paramArr["FLAG_INBOX_EOI"], PDO::PARAM_STR);
			$prep->bindValue(":FLAG_CONTACT_DETAIL", $paramArr["FLAG_CONTACT_DETAIL"], PDO::PARAM_STR);
			$prep->bindValue(":FLAG_OUTBOUND_CALL", $paramArr["FLAG_OUTBOUND_CALL"], PDO::PARAM_STR);
			$prep->bindValue(":FLAG_INBOUND_CALL", $paramArr["FLAG_INBOUND_CALL"], PDO::PARAM_STR);
			$prep->bindValue(":CHAT_INITIATION", $paramArr["CHAT_INITIATION"], PDO::PARAM_STR);
			$prep->execute();
                        return $this->db->lastInsertId();
		}
		catch(Exception $e)
		{

            if(sfContext::getInstance()->getRequest()->getParameter('phoneVerification')==1)
                jsCacheWrapperException::logThis($e);
            else 
                throw new jsException($e);

        }
	}
	/*
        *This function inserts record into the incentive.NEGATIVE_TREATMENT_LIST table
        */
	public function updateRecord($paramArr)
        {

                try
                {
			$sql = "UPDATE incentive.NEGATIVE_TREATMENT_LIST set TYPE=:TYPE,ENTRY_BY=:ENTRY_BY,ENTRY_DT=:ENTRY_DT, FLAG_VIEWABLE=:FLAG_VIEWABLE,FLAG_INBOX_EOI=:FLAG_INBOX_EOI,FLAG_OUTBOUND_CALL=:FLAG_OUTBOUND_CALL,FLAG_INBOUND_CALL=:FLAG_INBOUND_CALL,CHAT_INITIATION=:CHAT_INITIATION,FLAG_CONTACT_DETAIL=:FLAG_CONTACT_DETAIL where PROFILEID=:PROFILEID";

                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID", $paramArr["PROFILEID"], PDO::PARAM_INT);
                        $prep->bindValue(":TYPE", $paramArr["TYPE"], PDO::PARAM_STR);
                        $prep->bindValue(":ENTRY_BY", $paramArr["ENTRY_BY"], PDO::PARAM_STR);
                        $prep->bindValue(":ENTRY_DT", $paramArr["ENTRY_DT"], PDO::PARAM_STR);
                        $prep->bindValue(":FLAG_VIEWABLE", $paramArr["FLAG_VIEWABLE"], PDO::PARAM_STR);
                        $prep->bindValue(":FLAG_INBOX_EOI", $paramArr["FLAG_INBOX_EOI"], PDO::PARAM_STR);
                        $prep->bindValue(":FLAG_CONTACT_DETAIL", $paramArr["FLAG_CONTACT_DETAIL"], PDO::PARAM_STR);
                        $prep->bindValue(":FLAG_OUTBOUND_CALL", $paramArr["FLAG_OUTBOUND_CALL"], PDO::PARAM_STR);
                        $prep->bindValue(":FLAG_INBOUND_CALL", $paramArr["FLAG_INBOUND_CALL"], PDO::PARAM_STR);
                        $prep->bindValue(":CHAT_INITIATION", $paramArr["CHAT_INITIATION"], PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
	/*
        *This function removes record into the incentive.NEGATIVE_TREATMENT_LIST table
        */
        public function deleteRecord($profileid)
        {

                try
                {
			if($profileid)
			{
				$sql = "delete from  incentive.NEGATIVE_TREATMENT_LIST where PROFILEID=:PROFILEID and TYPE='Fto_Duplicate'";
	
                        	$prep = $this->db->prepare($sql);
        	                $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                	        $prep->execute();
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function removeNegativeIdsFromList($flagParameters,$profileIdArr)
	{
		$sql="SELECT PROFILEID FROM incentive.NEGATIVE_TREATMENT_LIST";
		if($flagParameters)
		{
			$parameterCount = 1;
			foreach($flagParameters as $key => $value)
			{
				$parameter .= "$key = :VALUE$parameterCount AND ";
				$bindArr["VALUE".$parameterCount] = $value;
				$parameterCount++;
			}
			$parameter = substr($parameter, 0, -4);
	}
	if($profileIdArr)
	{
			$profileCount = 1;
			foreach($profileIdArr as $key => $value)
			{
				$profileIdStr .= ":PROFILEID$profileCount,";
				$bindProfileArr["PROFILEID".$profileCount] = $value;
				$profileCount++;
			}
			$profileIdStr = substr($profileIdStr, 0, -1);
		}
			$sql.=" WHERE $parameter";
			$sql.= "AND PROFILEID IN ($profileIdStr)";
		$res = $this->db->prepare($sql);
		if(is_array($bindArr))
			foreach($bindArr as $k=>$v)
				$res->bindValue($k,$v,PDO::PARAM_STR);
		if(is_array($bindProfileArr))
			foreach($bindProfileArr as $k=>$v)
				$res->bindValue($k,$v,PDO::PARAM_INT);
		$res->execute();
		while($myrow =$res->fetch(PDO::FETCH_ASSOC))
		{
			$pidArr[]=$myrow["PROFILEID"];
		}
		if(!is_array($pidArr))
			return $profileIdArr;
		$finalArr=array_diff($profileIdArr,$pidArr);
			return $finalArr;
	}

	public function isNegativeTreatmentRequired($profileid)
	{
		try
		{
			if($profileid)
			{
				$sql = "SELECT COUNT(*) as CNT FROM incentive.NEGATIVE_TREATMENT_LIST WHERE FLAG_OUTBOUND_CALL='N' AND PROFILEID=:PROFILEID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
				$prep->execute();
				$myrow = $prep->fetch(PDO::FETCH_ASSOC);
				if($myrow["CNT"]>0) {
	            	return 1;
	            } else {
	            	return 0;
				}
			}
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
        public function getNegativeListProfiles($profileIdArr)
        {
                try{
                        if(is_array($profileIdArr))
                        {
                                foreach($profileIdArr as $key=>$pid){
                                        if($key == 0)
                                                $str = ":PROFILEID".$key;
                                        else
                                                $str .= ",:PROFILEID".$key;
                                }
                                $sql = "SELECT distinct PROFILEID FROM incentive.NEGATIVE_TREATMENT_LIST WHERE FLAG_OUTBOUND_CALL='N' AND PROFILEID IN ($str) ";
                                $res=$this->db->prepare($sql);
                                unset($pid);
                                foreach($profileIdArr as $key=>$pid)
                                        $res->bindValue(":PROFILEID$key", $pid, PDO::PARAM_INT);
                                $res->execute();
                                while($row = $res->fetch(PDO::FETCH_ASSOC))
                                        $result[] = $row['PROFILEID'];
                                return $result;
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
  /*
   * this function gets all the profiles marked as duplicate after a given date
   * @param : $afterDate date after which profiles have to be fetched
   * @param : $totalScript divisor
   * @param : $currentScript remainder
   * @return : output $records array of records
   */              
  public function getAllDuplicateProfiles($afterDate,$totalScript,$currentScript) {
    try {
      $sql = "Select N.PROFILEID AS PRO_ID from incentive.NEGATIVE_TREATMENT_LIST AS N LEFT JOIN duplicates.DUPLICATE_PROFILES_MAIL_LOG AS L ON N.PROFILEID=L.PROFILEID JOIN newjs.JPROFILE AS J ON N.PROFILEID=J.PROFILEID WHERE N.PROFILEID%:totalScript=:currentScript AND J.ACTIVATED != 'D' AND N.TYPE='Fto_Duplicate' AND J.ENTRY_DT >:AFTER_DATE AND L.PROFILEID IS NULL";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":AFTER_DATE", $afterDate, PDO::PARAM_INT);
      $prep->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
      $prep->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
      $prep->execute();
      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        $records[]=$result[PRO_ID];
      }
      return $records;
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }
  /*
   * get maximum id
   * @return - $result- maximum id value
   */
  public function getMaxId() {
    try {
      $sql = "Select MAX(ID) AS MAX_ID FROM incentive.NEGATIVE_TREATMENT_LIST";
      $prep = $this->db->prepare($sql);
      $prep->execute();
      $result = $prep->fetch(PDO::FETCH_ASSOC);
      return $result[MAX_ID];
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }


  public function removeProfile($negType, $negativeVal) 
  {
        try {
            $sql = "DELETE FROM incentive.NEGATIVE_TREATMENT_LIST WHERE $negType=:VALUE_VAL";
            $prep = $this->db->prepare($sql);
	    $prep->bindValue(":VALUE_VAL", $negativeVal, PDO::PARAM_STR);
            $prep->execute();
	    $rows_affected =$prep->rowCount();
	    return $rows_affected;
        }
        catch (Exception $e) {
            throw new jsException($e);
        }
  }

    //Three function for innodb transactions
    public function startTransaction()
    {
        $this->db->beginTransaction();
    }
    public function commitTransaction()
    {
        $this->db->commit();
    }

    public function rollbackTransaction()
    {
        $this->db->rollback();
    }
    //Three function for innodb transactions

}
