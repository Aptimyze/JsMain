<?php
class billing_VARIABLE_DISCOUNT extends TABLE{
       
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
	public function getDiscount($profileStr,$vdStartDate='')
	{
		try
                {
			if($profileStr){
				$profileStr=trim($profileStr);
				if($vdStartDate)
					$dt =$vdStartDate;
				else
					$dt = date("Y-m-d");
				if($vdStartDate)
					$sql="SELECT PROFILEID,DISCOUNT,EDATE,SDATE from billing.VARIABLE_DISCOUNT WHERE PROFILEID IN($profileStr) AND SDATE=:DATE AND DISCOUNT>0";
				else
					$sql="SELECT PROFILEID,DISCOUNT,EDATE,SDATE from billing.VARIABLE_DISCOUNT WHERE PROFILEID IN($profileStr) AND SDATE<=:DATE AND DISCOUNT>0";
	                        $prep = $this->db->prepare($sql);
				$prep->bindValue(":DATE", $dt, PDO::PARAM_STR);
	                        $prep->execute();
	                        while($result=$prep->fetch(PDO::FETCH_ASSOC))
				{
					$dataset[$result['PROFILEID']]['DISCOUNT']=$result['DISCOUNT'];
					$dataset[$result['PROFILEID']]['EDATE']=$result['EDATE'];
					$dataset[$result['PROFILEID']]['SDATE']=$result['SDATE'];
				}
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $dataset;
	}
        public function getDiscountProfileArr($profileStr)
        {
                try
                {
                        if($profileStr){
                                $profileStr=trim($profileStr);
                                $dt = date("Y-m-d");
                                $sql="SELECT PROFILEID from billing.VARIABLE_DISCOUNT WHERE PROFILEID IN($profileStr) AND CURDATE()>=DATE(SDATE) AND CURDATE()<=DATE(EDATE) AND DISCOUNT>0";
                                $prep = $this->db->prepare($sql);
                                $prep->execute();
                                while($result=$prep->fetch(PDO::FETCH_ASSOC))
                                        $dataset[]=$result['PROFILEID'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $dataset;
        }
	/*
	This function is used to get the discount details of a profileid if its eligible for a discount on the current date
	@param - profileid
	@return - array having details or blank
	*/
	public function getDiscountDetails($profileid,$type='')
	{
		if(!$profileid)
			throw new jsException("","PROFILEID IS BLANK IN getDiscountDetails() OF billing_VARIABLE_DISCOUNT.class.php");

		try
		{
			$dt = date("Y-m-d");
			$sql = "SELECT DISCOUNT,EDATE FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID = :PROFILEID AND SDATE<=:DATE AND EDATE>=:DATE";
            if(!empty($type)){
                $sql .= " AND TYPE=:TYPE";
            }
			$res = $this->db->prepare($sql);
            $res->bindValue(":DATE", $dt, PDO::PARAM_STR);
            $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
            if(!empty($type)){
                $res->bindValue(":TYPE", $type, PDO::PARAM_STR);
            }
            $res->execute();
			$row = $res->fetch(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
                {
                        throw new jsException($e);
                }
		return $row;
	}	
        public function getProfileidWithDiscount($profileid)
        {
                try
                {
                        $sql="SELECT PROFILEID from billing.VARIABLE_DISCOUNT WHERE PROFILEID=:PROFILEID AND CURDATE()>=DATE(SDATE) AND CURDATE()<=DATE(EDATE) AND DISCOUNT>0";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->execute();
                        $result=$prep->fetch(PDO::FETCH_ASSOC);
			$res = $result['PROFILEID'];
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
                return $res;
        }

        public function updateSendVDStatus($profileid,$status){
        	try
        	{
        		$sql="update billing.VARIABLE_DISCOUNT SET SENT=:STATUS WHERE PROFILEID=:PROFILEID";
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
        		$prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
        		$prep->execute();
        	}
        	catch(Exception $e)
        	{
        		throw new jsException($e);
        	}
        }
        public function updateVDMailerStatus($profileid,$status){
                try
                {
                        $sql="update billing.VARIABLE_DISCOUNT SET SENT_MAIL=:STATUS WHERE PROFILEID=:PROFILEID";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
                        $prep->bindValue(":STATUS",$status,PDO::PARAM_STR);
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function extendVdExpiryDate($endDate, $startDate){
                try
                {
                        $sql="update billing.VARIABLE_DISCOUNT SET EDATE=:EDATE WHERE SDATE=:SDATE";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":EDATE",$endDate,PDO::PARAM_STR);
			$prep->bindValue(":SDATE",$startDate,PDO::PARAM_STR);	
                        $prep->execute();
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }
        public function getVdExpiryDate()
        {
                try
                {
                        $sql = "SELECT MAX(EDATE) EDATE FROM billing.VARIABLE_DISCOUNT";
                        $res = $this->db->prepare($sql);
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
			$eDate =$row['EDATE'];
			if($eDate)
				return $eDate;		 
			return;   
        	}
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

        public function checkValidProfileCountForDate($entryDt,$noOfTimes,$frequency){
        	try{
        		$sql = "SELECT COUNT(*) AS CNT FROM billing.VARIABLE_DISCOUNT WHERE :ENTRY_DT BETWEEN SDATE AND EDATE AND PROFILEID%:TIMES=:FREQUENCY AND SENT!='Y'";
        		$prep = $this->db->prepare($sql);
        		$prep->bindValue(":ENTRY_DT",$entryDt,PDO::PARAM_STR);
        		$prep->bindValue(":TIMES",$noOfTimes,PDO::PARAM_INT);
        		$prep->bindValue(":FREQUENCY",$frequency,PDO::PARAM_INT);
        		$prep->execute();
        		$row = $prep->fetch(PDO::FETCH_ASSOC);
        		$count = $row['CNT'];
        		return $count;
        	} catch(Exception $e){
        		throw new jsException($e);
        		
        	}
        }

        public function getVdStartDate()
        {
                try
                {
                        $sql = "SELECT distinct SDATE FROM billing.VARIABLE_DISCOUNT";
                        $res = $this->db->prepare($sql);
                        $res->execute();
        	        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        	$dateArr[] =$row['SDATE'];
                        return $dateArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

	public function addVDProfile($profileid,$discount,$sDate,$eDate,$entryDt,$sentMail="",$sendSMS="",$sendAlert=false,$type="")
	{

                try
                {
                        if($sentMail && $sendSMS)
                            $sql = "INSERT IGNORE INTO billing.VARIABLE_DISCOUNT (PROFILEID,DISCOUNT,SDATE,EDATE,ENTRY_DT,SENT_MAIL,SENT,TYPE) VALUES(:PROFILEID,:DISCOUNT,:SDATE,:EDATE,:ENTRY_DT,:SENT_MAIL,:SENT";
                        else
                            $sql = "INSERT IGNORE INTO billing.VARIABLE_DISCOUNT (PROFILEID,DISCOUNT,SDATE,EDATE,ENTRY_DT,TYPE) VALUES(:PROFILEID,:DISCOUNT,:SDATE,:EDATE,:ENTRY_DT";
                        if(!empty($type)){
                            $sql .= ",:TYPE";
                        }
                        $sql .= ")";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
                        $res->bindValue(":DISCOUNT", $discount, PDO::PARAM_INT);
                        $res->bindValue(":SDATE", $sDate, PDO::PARAM_STR);
                        $res->bindValue(":EDATE", $eDate, PDO::PARAM_STR);
                        $res->bindValue(":ENTRY_DT", $entryDt, PDO::PARAM_STR);
                        if(!empty($type))
                            $res->bindValue(":TYPE", $type, PDO::PARAM_STR);
                        
                        if($sentMail && $sendSMS)
                        {
                            $res->bindValue(":SENT", $sendSMS, PDO::PARAM_STR); 
                            $res->bindValue(":SENT_MAIL", $sentMail, PDO::PARAM_STR); 
                        }
                        $res->execute();

                }
                catch(Exception $e)
                {
                    if($sendAlert==true)
                    {
                        $message = "Error in running populateVDEntriesFromTempTable cron in addVDProfile func of billing_VARIABLE_DISCOUNT.class.php";
                        CRMAlertManager::sendMailAlert($message,"VDUploadFromTable");
                    }
                    throw new jsException($e);
                }
	}
        public function getVdProfilesForMailer()
        {
                try
                {
			$curDate =date("Y-m-d");
                        $sql = "SELECT PROFILEID,DISCOUNT,EDATE FROM billing.VARIABLE_DISCOUNT WHERE EDATE>=:EDATE AND SENT_MAIL!='Y' AND DISCOUNT > 0";
                        $res = $this->db->prepare($sql);
			$res->bindValue(":EDATE", $curDate, PDO::PARAM_STR);
                        $res->execute();
                        while($row = $res->fetch(PDO::FETCH_ASSOC)){
				$detailsArr['DISCOUNT'] 	=$row['DISCOUNT'];
				$detailsArr['EDATE'] 		=$row['EDATE']; 
                                $dataArr[$row['PROFILEID']] 	=$detailsArr;
			}
                        return $dataArr;
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
        }

	public function getMailCountForRange($startDate,$endDate)
        {
                try{
                        $sql = "SELECT count(1) as cnt,SENT_MAIL as SENT FROM billing.VARIABLE_DISCOUNT WHERE SDATE>=:START_DATE AND SDATE<=:END_DATE group by SENT_MAIL";
                        $res=$this->db->prepare($sql);
                        $res->bindValue("START_DATE",$startDate,PDO::PARAM_STR);
                        $res->bindValue("END_DATE",$endDate,PDO::PARAM_STR);
                        $res->execute();
                        $total = 0;
                        while($row = $res->fetch(PDO::FETCH_ASSOC))
                        {
                                if($row['SENT']=='Y')
                                        $output['SENT'] = $row['cnt'];
                                if($row['SENT']=='B')
                                        $output['BOUNCED'] = $row['cnt'];
                                if($row['SENT']=='I')
                                        $output['INCOMPLETE'] = $row['cnt'];
                                if($row['SENT']=='U')
                                        $output['UNSUBSCRIBE'] = $row['cnt'];
                                $total = $total+$row['cnt'];
                        }
                        $output['TOTAL'] = $total;
                }
                catch(PDOException $e)
                {
                   throw new jsException($e);
                }
                return $output;

        }
    
    public function deleteVariableDiscountEndingYesterday()
    {
        try{
            $todayDate = date("Y-m-d");
            $sql ="DELETE FROM billing.VARIABLE_DISCOUNT WHERE EDATE<:EDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":EDATE", $todayDate, PDO::PARAM_STR);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function deleteVariableDiscount($pid)
    {
        try{
            $todayDate = date("Y-m-d");
            $sql ="DELETE FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID=:PROFILEID";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
            $res->execute();
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }

    public function selectToBeDeletedProfilesWhoseVariableDiscountIsEndingYesterday()
    {
        try{
            $todayDate = date("Y-m-d");
            $sql ="SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT WHERE EDATE<:EDATE";
            $res = $this->db->prepare($sql);
            $res->bindValue(":EDATE", $todayDate, PDO::PARAM_STR);
            $res->execute();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){
            	$output[$row['PROFILEID']] = $row['PROFILEID'];
            }
            return $output;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    
    /*Used earlier to generate VD Impact Report
     * Now removed the join with VARIABLE_DISCOUNT_POOL_TECH
     * 2016-06-30
     */
    public function getVariableDiscountProfilesEndingYesterday()
    {
        try{
            $yesterdayDate = date("Y-m-d", strtotime("-1 day"));
            $sql = "SELECT vd.PROFILEID, vd.DISCOUNT, vd.SDATE, vd.EDATE FROM billing.VARIABLE_DISCOUNT vd JOIN billing.VARIABLE_DISCOUNT_POOL_TECH ON (vd.PROFILEID = VARIABLE_DISCOUNT_POOL_TECH.PROFILEID) AND vd.EDATE = :EDATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EDATE",$yesterdayDate,PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $vdData[] = $result;
            }
            return $vdData;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }
    
    public function getVDProfilesEndingYesterday(){
        try{
            $yesterdayDate = date("Y-m-d", strtotime("-1 day"));
            $sql = "SELECT vd.PROFILEID, vd.DISCOUNT, vd.SDATE, vd.EDATE FROM billing.VARIABLE_DISCOUNT as vd WHERE vd.EDATE = :EDATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EDATE",$yesterdayDate,PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC))
            {
                $vdData[] = $result;
            }
            return $vdData;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
    public function getVDProfilesExpiringToday($todayDate){
        try{
            $sql = "SELECT * FROM billing.VARIABLE_DISCOUNT WHERE EDATE=:EDATE";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":EDATE",$todayDate,PDO::PARAM_STR);
            $prep->execute();
            while($result = $prep->fetch(PDO::FETCH_ASSOC)){
                $dataArr[] = $result;
            }
            return $dataArr;
        } catch (Exception $ex) {
            throw new jsException($ex);
        }
    }
}
?>
