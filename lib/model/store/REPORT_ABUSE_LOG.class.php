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
                        $sql = "SELECT REPORTEE,count(*) AS CNT from feedback.REPORT_ABUSE_LOG WHERE REPORTEE IN ($pdoStr) GROUP BY REPORTEE"; 
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






}

?>

