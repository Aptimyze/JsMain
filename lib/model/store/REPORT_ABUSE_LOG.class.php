<?php

class REPORT_ABUSE_LOG extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	
	
	public function insertReport($reporter,$reportee,$category,$reason,$from='',$crmUser='', $attachment_id=-1) 
	{  
		
		try
		{
			if(!$reporter || !$reportee || !$category)
				return;
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="INSERT INTO feedback.REPORT_ABUSE_LOG(REPORTER,REPORTEE,OTHER_REASON,DATE,REASON,CATEGORY,CRM_USER,ATTACHMENT_ID) VALUES(:REPORTER,:REPORTEE,:REASON,:DATE,:CATEGORY,:COMINGFROM,:CRM_USER,:ATTACHMENT_ID)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":REPORTER",$reporter,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REPORTEE",$reportee,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REASON",$reason,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CATEGORY",$category,PDO::PARAM_STR);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			$pdoStatement->bindValue(":COMINGFROM",$from,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CRM_USER",$crmUser,PDO::PARAM_STR);
            $pdoStatement->bindValue(":ATTACHMENT_ID",$attachment_id,PDO::PARAM_INT);
			$pdoStatement->execute();
			
			return ;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}


	public function getReportAbuseLog($startDate,$endDate)
	{
		try	 	
		{	
			$sql = "SELECT * from feedback.REPORT_ABUSE_LOG WHERE DATE(`DATE`) BETWEEN :STARTDATE AND :ENDDATE ORDER BY `DATE` DESC";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":STARTDATE",$startDate,PDO::PARAM_INT);
			$prep->bindValue(":ENDDATE",$endDate,PDO::PARAM_INT);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
			$result[]=$row;
		return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}
	private function insertReviewStatusLog($review_status, $user, $report_id){
		try{
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql = "INSERT INTO feedback.REVIEW_STATUS_LOG(DATE, STATUS, USER, REPORT) VALUES(:vDATE, :vSTATUS, :vUSER, :vREPORT)";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(':vDATE', $timeNow, PDO::PARAM_STR);
			$prep->bindValue(':vSTATUS', $review_status, PDO::PARAM_STR);
			$prep->bindValue(':vUSER', $user, PDO::PARAM_STR);
			$prep->bindValue(':vREPORT', $report_id, PDO::PARAM_INT);
			$prep->execute();
			
			return 1;
		}catch(Exception $e){
			throw new jsException($e);
		}
		return 0;
	}
	public function updateReviewStatus($report_id, $status, $user){
		$review_status = 'N';
		if($status == 'Y'){
			$review_status = 'Y';
		}else if($status == 'N'){
			$review_status = 'N';
		}else return 0;
		try{
			$sql = "UPDATE feedback.REPORT_ABUSE_LOG SET REVIEW_STATUS = :vREVIEW_STATUS WHERE (REPORTER, REPORTEE) = (:vREPORTER, :vREPORTEE) AND DATE = :vTIMESTAMP";
			$sql = "UPDATE feedback.REPORT_ABUSE_LOG SET REVIEW_STATUS = :vREVIEW_STATUS WHERE ID = :vID";
			
			$prep = $this->db->prepare($sql);
			$prep->bindValue(':vID' , $report_id, PDO::PARAM_INT);
			$prep->bindValue(':vREVIEW_STATUS', $review_status, PDO::PARAM_STR);
			$prep->bindValue(':vREPORTEE', $reportee, PDO::PARAM_INT);
			$prep->bindValue(':vREPORTER', $reporter, PDO::PARAM_INT);
			$prep->execute();
			$row_affected=$prep->rowCount();
			if($row_affected>0){
				if($this->insertReviewStatusLog($review_status, $user, $report_id))
					return 1;
			}
		}catch(Exception $e)
		{
			throw new jsException($e);
		}
		return 0;
	}
	public function getReportAbuseCount($profileArray)
	{
		try	 	
		{	
                        if(!is_array($profileArray))
                            throw new jsException("","profileArray IS not array or blank");
                        $pdoStr="";
                        foreach($profileArray as $k=>$v)
						{

                                                    $pdoStr.=":v".$k.",";
                                                    
						}
                        $pdoStr = substr($pdoStr, 0, -1);                                                     
                        $sql = "SELECT REPORTEE,count(DISTINCT(REPORTER)) AS CNT from feedback.REPORT_ABUSE_LOG WHERE REPORTEE IN ($pdoStr) GROUP BY REPORTEE";
                        $prep = $this->db->prepare($sql);
                        foreach($profileArray as $k=>$v)
                            $prep->bindValue(":v".$k,$v,PDO::PARAM_INT);
                        $prep->execute();
                        while($row=$prep->fetch(PDO::FETCH_ASSOC))
                            $result[$row['REPORTEE']]=$row['CNT'];
                        
                        return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}


	public function getReportAbuseHistoryOfUser($profileid)
	{
		try	 	
		{	
			$sql = "SELECT REPORTER,`DATE`,REASON,OTHER_REASON,ATTACHMENT_ID from feedback.REPORT_ABUSE_LOG WHERE REPORTEE = :PROFID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PROFID",$profileid,PDO::PARAM_INT);
            $prep->execute();
            while($row=$prep->fetch(PDO::FETCH_ASSOC))
			$result[]=$row;
		return $result;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}
        

        public function getReportAbuseCountMIS($profileID,$startDate,$endDate)
	{
		try	 	
		{	
                        if(!($profileID))
                            throw new jsException("","profileID IS not array or blank");
                                                                             
                        $sql = 'SELECT count( DISTINCT(REPORTER)) AS CNT
                        FROM feedback.REPORT_ABUSE_LOG
                        WHERE DATE( `DATE` ) <= DATE("'.$startDate.'")
                        AND DATE( `DATE` ) >= DATE( "'.$endDate.'" )
                        AND REPORTEE ='.$profileID;
                        $prep = $this->db->prepare($sql);
                        $prep->execute();

                          if($row=$prep->fetch(PDO::FETCH_ASSOC))   
                            $output=$row['CNT'];
                        return $output;

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}

	   public function canReportAbuse($reporteeProfileID,$reporterProfileID)
	{
		try	 	
		{	
                        if(!($reporterProfileID) || !$reporteeProfileID )
                            throw new jsException("","reporter or reportee not present");                
                        $sql = 'SELECT count(*) AS CNT
                        FROM feedback.REPORT_ABUSE_LOG
                        WHERE REPORTER = :REPORTERPROFILEID
                        AND REPORTEE = :REPORTEEPROFILEID';
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":REPORTERPROFILEID",$reporterProfileID,PDO::PARAM_INT);
                        $prep->bindValue(":REPORTEEPROFILEID",$reporteeProfileID,PDO::PARAM_INT);
                        $prep->execute();

                          if($row=$prep->fetch(PDO::FETCH_ASSOC))   
                            $output=$row['CNT'];
                        if($output>=2)
                        	return 0;
                        return 1;

		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	
	}
    
    /**
     * 
     * @param type $iProfileId
     * @param type $withInLastDays
     * @return type
     * @throws jsException
     */
    public function getListOfAllReporters($iProfileId, $withInLastDays=null) {
      try{
        $sql = "SELECT REPORTER, DATE FROM feedback.REPORT_ABUSE_LOG WHERE REPORTEE=:PID";
        
        if ( $withInLastDays ) {
          $time = new DateTime();
          
          $time->sub(date_interval_create_from_date_string($withInLastDays));
          $timeInLastDays = $time->format('Y-m-d H:i:s');
          
          $sql .= " AND DATE >= :WITH_IN_DAYS";
        }
        
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":PID",$iProfileId,PDO::PARAM_INT);
        
        if ( $withInLastDays ) {
          $prep->bindValue(":WITH_IN_DAYS",$timeInLastDays,PDO::PARAM_STR);
        }
        $prep->execute();
        $arrResult = $prep->fetchAll(PDO::FETCH_ASSOC);
                
        return $arrResult;
      } catch (Exception $ex) {
        throw new jsException($ex);
      }
    }

}

?>

