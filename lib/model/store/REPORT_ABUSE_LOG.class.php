<?php

class REPORT_ABUSE_LOG extends TABLE
{
	public function __construct($szDbName = "")
	{
		parent::__construct($szDBName);
	}	
	
	
	
	public function insertReport($reporter,$reportee,$category,$reason)
	{
		try
		{
			if(!$reporter || !$reportee || !$category)
				return;
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="INSERT INTO feedback.REPORT_ABUSE_LOG(REPORTER,REPORTEE,OTHER_REASON,DATE,REASON) VALUES(:REPORTER,:REPORTEE,:REASON,:DATE,:CATEGORY)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":REPORTER",$reporter,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REPORTEE",$reportee,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REASON",$reason,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CATEGORY",$category,PDO::PARAM_STR);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			
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
        

}

?>
