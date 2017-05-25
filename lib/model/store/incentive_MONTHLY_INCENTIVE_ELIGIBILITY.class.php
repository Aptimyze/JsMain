<?php
class incentive_MONTHLY_INCENTIVE_ELIGIBILITY extends TABLE
{
	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
	public function getMaxReceiptId()
	{
		try
		{
			$sql="select MAX(RECEIPTID) RECEIPTID from incentive.MONTHLY_INCENTIVE_ELIGIBILITY";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC))
				$receiptId =$result['RECEIPTID'];
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $receiptId;
	}
	public function deleteRecord($profileid, $allotedTo, $entryDate)
	{
		try
		{
			$sql="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO AND ENTRY_DT>=:ENTRY_DT";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
			$prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$entryDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}
	public function deleteCancelledPayment($receiptId, $billId)
	{
		try
		{
			$sql="DELETE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY where RECEIPTID=:RECEIPTID AND BILLID=:BILLID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":RECEIPTID",$receiptId,PDO::PARAM_INT);
			$prep->bindValue(":BILLID",$billId,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}
	public function insertProfile($receiptId,$billId,$profileid,$allotedTo,$center,$amount,$entryDate,$mode,$allotTime,$appleComm)
	{
		try
		{
			if(!$amount)
				$amount='';
			$sql="INSERT IGNORE INTO incentive.MONTHLY_INCENTIVE_ELIGIBILITY (RECEIPTID,BILLID,PROFILEID,ALLOTED_TO,CENTER,AMOUNT,ENTRY_DT,MODE,ALLOT_TIME,APPLE_COMMISSION) VALUES (:RECEIPTID,:BILLID,:PROFILEID,:ALLOTED_TO,:CENTER,:AMOUNT,:ENTRY_DT,:MODE,:ALLOT_TIME,:APPLE_COMM)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":RECEIPTID",$receiptId, PDO::PARAM_INT);
			$prep->bindValue(":BILLID",$billId, PDO::PARAM_INT);
			$prep->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
			$prep->bindValue(":ALLOTED_TO",$allotedTo,PDO::PARAM_STR);
			$prep->bindValue(":CENTER",$center,PDO::PARAM_STR);
			$prep->bindValue(":AMOUNT",$amount,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DT",$entryDate,PDO::PARAM_STR);
			$prep->bindValue(":MODE",$mode,PDO::PARAM_STR);
			$prep->bindValue(":ALLOT_TIME",$allotTime,PDO::PARAM_STR);
			$prep->bindValue(":APPLE_COMM",$appleComm,PDO::PARAM_INT);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}
	public function fetchDaywiseData($st_date, $end_date, $allotstr)
	{
		try
		{
			$allotstr = implode("','", $allotstr);
			$allotstr = "'".$allotstr."'";
			$sql="SELECT SUM((AMOUNT)*((100-SPLIT_SHARE)/100)) AS AMOUNT, DAYOFMONTH(ENTRY_DT) as dd, ALLOTED_TO, UCASE(CENTER) AS CENTER FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date' AND ALLOTED_TO IN ($allotstr) GROUP BY ALLOTED_TO,DAYOFMONTH(ENTRY_DT)";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[$row['ALLOTED_TO']]['CENTER'] = $row['CENTER'];
				$res[$row['ALLOTED_TO']]['AMOUNT'][$row['dd']] += $row['AMOUNT'];
			}
			$sql="SELECT SUM((AMOUNT)*(SPLIT_SHARE/100)) AS AMOUNT, DAYOFMONTH(ENTRY_DT) as dd, SPLIT_AGENT, UCASE(CENTER) AS CENTER FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date' AND SPLIT_SHARE!=0 AND SPLIT_AGENT IN ($allotstr) GROUP BY SPLIT_AGENT,DAYOFMONTH(ENTRY_DT)";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[$row['SPLIT_AGENT']]['CENTER'] = $row['CENTER'];
				$res[$row['SPLIT_AGENT']]['AMOUNT'][$row['dd']] += $row['AMOUNT'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $res;
	}
	public function fetchDistinctUsernames($st_date, $end_date)
	{
		try
		{
			$sql="SELECT DISTINCT ALLOTED_TO, UCASE(CENTER) AS CENTER FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT >='$st_date' AND ENTRY_DT<='$end_date'";
			$prep = $this->db->prepare($sql);
			$prep->execute();
			while($row=$prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[$row['ALLOTED_TO']]['CENTER'] = $row['CENTER'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $res;
	}

	/*
	This function fetches the count of distinct profileid's which have made a payment to a given set of executives on a daily basis
	@param - usernmae array, start date and end date
		@return - result set array
	*/
	public function getProfilesWhichPaidDataForExecs($execArray,$start_dt,$end_dt)
	{
		if(!$execArray || !is_array($execArray) || !$start_dt || !$end_dt)
						throw new jsException("","EXEC_ARRAY OR START DATE OR END DATE IS BLANK IN getProfilesWhichPaidDataForExecs() of incentive_MONTHLY_INCENTIVE_ELIGIBILITY.class.php");

				$i=0;
				$execStr = "";
				foreach($execArray as $k=>$v)
				{
						$execStr = $execStr.":PARAM".$i.",";
						$i++;
				}
				$execStr = rtrim($execStr,",");

				try
				{
			$sql = "SELECT DISTINCT PROFILEID,DATE(ENTRY_DT) AS D, ENTRY_DT, ALLOTED_TO FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND ALLOTED_TO IN (".$execStr.") GROUP BY D,ALLOTED_TO,PROFILEID";
			$prep = $this->db->prepare($sql);
						$prep->bindValue(":DATE1",$start_dt,PDO::PARAM_STR);
						$prep->bindValue(":DATE2",$end_dt,PDO::PARAM_STR);
						$i=0;
						foreach($execArray as $k=>$v)
						{
								$prep->bindValue(":PARAM".$i,$v,PDO::PARAM_STR);
								$i++;
						}
						$prep->execute();
						while($row=$prep->fetch(PDO::FETCH_ASSOC))
						{
								$output[] = $row;
						}
		}
		catch(Exception $e)
				{
						throw new jsException($e);
				}
				return $output;
	}

	/*
	This function fetches the total sales data for a given set of executives on a daily basis
	@param - usernmae array, start date and end date
		@return - result set array
	*/

	public function getSalesDataForExecs($execArray,$start_dt,$end_dt,$profileid='',$group_by_profileid='')
	{
		if(!$execArray || !is_array($execArray) || !$start_dt || !$end_dt)
						throw new jsException("","EXEC_ARRAY OR START DATE OR END DATE IS BLANK IN getSalesDataForExecs() of incentive_MONTHLY_INCENTIVE_ELIGIBILITY.class.php");

				$i=0;
				$execStr = "";
				foreach($execArray as $k=>$v)
				{
						$execStr = $execStr.":PARAM".$i.",";
						$i++;
				}
				$execStr = rtrim($execStr,",");

				try
				{
			$sql = "SELECT sum(AMOUNT) AS AMOUNT,DATE(ENTRY_DT) AS D, ENTRY_DT, ALLOTED_TO";
			if($group_by_profileid)
				$sql = $sql.",PROFILEID,ALLOT_TIME";
			$sql = $sql." FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND ALLOTED_TO IN (".$execStr.")";
			if($profileid)
				$sql = $sql." AND PROFILEID = :PROFILEID";
			$sql = $sql." GROUP BY D,ALLOTED_TO";
			if($group_by_profileid)
				$sql = $sql.",PROFILEID";
			$prep = $this->db->prepare($sql);
						$prep->bindValue(":DATE1",$start_dt,PDO::PARAM_STR);
						$prep->bindValue(":DATE2",$end_dt,PDO::PARAM_STR);
			if($profileid)
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
						$i=0;
						foreach($execArray as $k=>$v)
						{
								$prep->bindValue(":PARAM".$i,$v,PDO::PARAM_STR);
								$i++;
						}
						$prep->execute();
						while($row=$prep->fetch(PDO::FETCH_ASSOC))
						{
								$output[] = $row;
						}
		}
		catch(Exception $e)
				{
						throw new jsException($e);
				}
				return $output;
	}


	// Function takes the agents original allocated profile array and freshvisit array and cross checks
	// if there are any profiles that were paid within the original allocated period and also checks if that
	// profile belongs to the FVD Disposition category within the allocated period for the particular agent!

	public function getAgentAllotedProfilePaidArray($agent, $profileArray){
		$agentAllotedProfilePaidArray = array();
		foreach($profileArray as $key=>$value){
			try{
				$sql = "SELECT COUNT(*) AS COUNT, SUM(AMOUNT) AS AMOUNT, ENTRY_DT FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE PROFILEID=:PROID AND ENTRY_DT>=:DATE1 AND ENTRY_DT<=:DATE2 AND ALLOTED_TO IN(:AGENT) ORDER BY ENTRY_DT ASC";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROID", $value['PROFILEID'], PDO::PARAM_INT);
				$prep->bindValue(":DATE1", $value['ALLOT_TIME'], PDO::PARAM_STR);
				$prep->bindValue(":DATE2", $value['DE_ALLOCATION_DT'], PDO::PARAM_STR);
				$prep->bindValue(":AGENT", $agent, PDO::PARAM_STR);
				$prep->execute();
				while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
					$agentAllotedProfilePaidArray[] = array('PROFILEID'=>$value['PROFILEID'], 'COUNT'=>$row['COUNT'], 'AMOUNT'=>$row['AMOUNT'], 'ENTRY_DT'=>$row['ENTRY_DT']);
				}
			}
			catch(Exception $e){
				throw new jsException($e);
			}
		}
		return $agentAllotedProfilePaidArray;
	}
	public function getSalesDataForExecs1($execArray,$start_dt,$end_dt)
	{
		if(!$execArray || !is_array($execArray) || !$start_dt || !$end_dt)
						throw new jsException("","EXEC_ARRAY OR START DATE OR END DATE IS BLANK IN getSalesDataForExecs() of incentive_MONTHLY_INCENTIVE_ELIGIBILITY.class.php");

				$i=0;
				$execStr = "";
				foreach($execArray as $k=>$v)
				{
						$execStr = $execStr.":PARAM".$i.",";
						$i++;
				}
				$execStr = rtrim($execStr,",");

				try
				{
			$sql = "SELECT PROFILEID,AMOUNT,DATE(ENTRY_DT) AS D, ENTRY_DT, ALLOTED_TO FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE ENTRY_DT BETWEEN :DATE1 AND :DATE2 AND ALLOTED_TO IN (".$execStr.")";
			$prep = $this->db->prepare($sql);
						$prep->bindValue(":DATE1",$start_dt,PDO::PARAM_STR);
						$prep->bindValue(":DATE2",$end_dt,PDO::PARAM_STR);
						$i=0;
						foreach($execArray as $k=>$v)
						{
								$prep->bindValue(":PARAM".$i,$v,PDO::PARAM_STR);
								$i++;
						}
						$prep->execute();
						while($row=$prep->fetch(PDO::FETCH_ASSOC))
						{
								$output[] = $row;
						}
		}
		catch(Exception $e)
				{
						throw new jsException($e);
				}
				return $output;
	}
        public function fetchAgentForTransactionInfo($start_dt, $end_dt)
        {
                try{
                        $sql = "SELECT DISTINCT `ALLOTED_TO` FROM incentive.`MONTHLY_INCENTIVE_ELIGIBILITY` WHERE `ENTRY_DT` >= :START_DT AND `ENTRY_DT` <= :END_DT";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                                $res[] = $row['ALLOTED_TO'];
                        }
                }
                catch(Exception $e){
                        throw new jsException($e);
                }
                return $res;
        }
        public function fetchDaywiseTicketsAndAmount($start_dt, $end_dt)
        {
                try
                {
                        $sql = "SELECT SUM((AMOUNT)*((100-SPLIT_SHARE)/100)) AS AMT, COUNT(DISTINCT `BILLID`) AS CNT, DAYOFMONTH(`ENTRY_DT`) AS DD, ALLOTED_TO FROM incentive.`MONTHLY_INCENTIVE_ELIGIBILITY` WHERE `ENTRY_DT`>=:START_DT AND `ENTRY_DT`<=:END_DT GROUP BY ALLOTED_TO, DD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        while ($row=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $res[$row['ALLOTED_TO']][$row['DD']] = $row['CNT'];
                                $res[$row['ALLOTED_TO']]['TOTAL'] += $row['CNT'];
                                $res[$row['ALLOTED_TO']]['AMT'] += $row['AMT'];
                        }
                        $sql = "SELECT SUM((AMOUNT)*(SPLIT_SHARE/100)) AS AMT, COUNT(DISTINCT `BILLID`) AS CNT, DAYOFMONTH(`ENTRY_DT`) AS DD, SPLIT_AGENT FROM incentive.`MONTHLY_INCENTIVE_ELIGIBILITY` WHERE `ENTRY_DT`>=:START_DT AND `ENTRY_DT`<=:END_DT AND SPLIT_SHARE!=0 GROUP BY SPLIT_AGENT, DD";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":START_DT", $start_dt, PDO::PARAM_STR);
                        $prep->bindValue(":END_DT", $end_dt, PDO::PARAM_STR);
                        $prep->execute();
                        while ($row=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $res[$row['SPLIT_AGENT']][$row['DD']] += $row['CNT'];
                                $res[$row['SPLIT_AGENT']]['TOTAL'] += $row['CNT'];
                                $res[$row['SPLIT_AGENT']]['AMT'] += $row['AMT'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
        }

        public function updateFranchiseeComissions($profileid, $billid, $franchisee){
	
			try{
				if(empty($franchisee)){
					$franchisee = 0;
				}

				$sql = "UPDATE incentive.MONTHLY_INCENTIVE_ELIGIBILITY SET FRANCHISEE_COMMISSION=:FRANCHISEE WHERE PROFILEID=:PROFILEID AND BILLID=:BILLID";
				$prep=$this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
				$prep->bindValue(":BILLID",$billid,PDO::PARAM_INT);
				$prep->bindValue(":FRANCHISEE",$franchisee,PDO::PARAM_INT);
				$prep->execute();
	
			} catch(PDOException $e){
				throw new jsException($e);
			}
		}


	public function checkSalesSplitData($profileid, $entryDate)
	{
		try
		{
			$start = $entryDate." 00:00:00";
			$end = $entryDate." 23:59:59";
			$sql="SELECT * FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE PROFILEID=:PROFILEID AND ENTRY_DT>=:START AND ENTRY_DT<=:END";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
			$prep->bindValue(":START",$start,PDO::PARAM_STR);
			$prep->bindValue(":END",$end,PDO::PARAM_STR);
			$prep->execute();
			while ($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            }
            return $res;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function selectSalesSplitData($profileid, $agentName, $entryDate)
	{
		try
		{
			$start = $entryDate." 00:00:00";
			$end = $entryDate." 23:59:59";
			$sql="SELECT * FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO AND ENTRY_DT>=:START AND ENTRY_DT<=:END";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFILEID",$profileid, PDO::PARAM_INT);
			$prep->bindValue(":ALLOTED_TO",$agentName,PDO::PARAM_STR);
			$prep->bindValue(":START",$start,PDO::PARAM_STR);
			$prep->bindValue(":END",$end,PDO::PARAM_STR);
			$prep->execute();
			while ($row=$prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[] = $row;
            }
            return $res;
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function updateSalesSplitData($detailsArr, $agentName, $agentPerc)
	{
		try
		{
			foreach($detailsArr as $key=>$val){
				$sql="UPDATE incentive.MONTHLY_INCENTIVE_ELIGIBILITY SET SPLIT_AGENT=:SPLIT_AGENT, SPLIT_SHARE=:SPLIT_SHARE WHERE PROFILEID=:PROFILEID AND ALLOTED_TO=:ALLOTED_TO AND RECEIPTID=:RECEIPTID AND BILLID=:BILLID";
				$prep = $this->db->prepare($sql);
				$prep->bindValue(":PROFILEID",$detailsArr['PROFILEID'], PDO::PARAM_INT);
				$prep->bindValue(":ALLOTED_TO",$detailsArr['ALLOTED_TO'], PDO::PARAM_STR);
				$prep->bindValue(":BILLID",$detailsArr['BILLID'], PDO::PARAM_INT);
				$prep->bindValue(":RECEIPTID",$detailsArr['RECEIPTID'], PDO::PARAM_INT);
				$prep->bindValue(":SPLIT_SHARE",$agentPerc, PDO::PARAM_INT);
				$prep->bindValue(":SPLIT_AGENT",$agentName, PDO::PARAM_STR);
				$prep->execute();	
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}
    
    public function getSalesWithinDates($stDate, $endDate)
    {
        try{
            $sql = "SELECT ie.BILLID, ie.ALLOTED_TO, ie.AMOUNT, ie.APPLE_COMMISSION, ie.SPLIT_AGENT, ie.SPLIT_SHARE, ie.ENTRY_DT, p.TAX_RATE from incentive.MONTHLY_INCENTIVE_ELIGIBILITY as ie JOIN billing.PURCHASES as p USING(BILLID) where ie.ENTRY_DT >= :START_DATE and ie.ENTRY_DT <= :END_DATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":START_DATE",$stDate,PDO::PARAM_STR);
            $prep->bindValue(":END_DATE",$endDate,PDO::PARAM_STR);
            $prep->execute();
            while($row = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $res[$row['BILLID']] = $row;
            }
            return $res;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    public function getAgentAllotedProfilePaidArrayForProfileid($agent, $profileArray){
		$result = array();
        try{
            $sql = "SELECT AMOUNT, ENTRY_DT, BILLID FROM incentive.MONTHLY_INCENTIVE_ELIGIBILITY WHERE PROFILEID=:PROID AND ENTRY_DT>=:DATE1 AND ENTRY_DT<=:DATE2 AND ALLOTED_TO IN(:AGENT) ORDER BY ENTRY_DT ASC";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROID", $profileArray['PROFILEID'], PDO::PARAM_INT);
            $prep->bindValue(":DATE1", $profileArray['ALLOT_TIME'], PDO::PARAM_STR);
            $prep->bindValue(":DATE2", $profileArray['DE_ALLOCATION_DT'], PDO::PARAM_STR);
            $prep->bindValue(":AGENT", $agent, PDO::PARAM_STR);
            $prep->execute();
            while ($row=$prep->fetch(PDO::FETCH_ASSOC)) {
                $result[$row['BILLID']] = $row;
            }
        }
        catch(Exception $e){
            throw new jsException($e);
        }
        return $result;
    }
}

?>
