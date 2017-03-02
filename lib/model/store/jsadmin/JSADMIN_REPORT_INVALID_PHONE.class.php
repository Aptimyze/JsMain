<?php
/*This class is used to insert in OFFLINE_ASSIGNED table
 * @author Palash Chordia
 * @created 2013-06-30
*/
class JSADMIN_REPORT_INVALID_PHONE extends TABLE{
        public function __construct($dbname="")
        {
                        parent::__construct($dbname);
        }
	 /** 
        This function is used to insert record in the REPORT_INVALID_PHONE table.
        * @param  $profileId Int
        * 
        **/
	public function insertReport($submitter,$submittee,$phone,$mob,$comments,$reason,$otherReason){
               
                            if(!$submitter || !$submittee || !$phone || !$mob || !$reason)
                        throw new jsException("","Any one or more of SUBMITTER, SUBMITTEE, PHONE, MOBILE, REASON IS BLANK IN INSERTREPORT() OF NEWJS_CONTACT_ARCHIVE.class.php");

                try
                {

					$now = date("Y-m-d G:i:s");
					$sql = "REPLACE INTO jsadmin.REPORT_INVALID_PHONE(SUBMITTER,SUBMITTEE,SUBMIT_DATE,PHONE,MOBILE,COMMENTS,REASON,OTHER_REASON) VALUES(:SUBMITTER,:SUBMITTEE,:SUBMIT_DATE,:PHONE,:MOBILE,:COMMENTS,:REASON,:OTHER_REASON)";
					$res = $this->db->prepare($sql);
                                        $comments=$comments?$comments:'';
				  	$res->bindValue(":SUBMITTER", $submitter, PDO::PARAM_INT);	
				  	$res->bindValue(":SUBMITTEE", $submittee, PDO::PARAM_STR);	
                                        $res->bindValue(":SUBMIT_DATE", $now, PDO::PARAM_STR);                                  
                                        $res->bindValue(":PHONE", $phone, PDO::PARAM_STR);                                  
                                        $res->bindValue(":MOBILE", $mob, PDO::PARAM_STR);                                  
				  	$res->bindValue(":COMMENTS", $comments, PDO::PARAM_STR);
                    $res->bindValue(":REASON", $reason, PDO::PARAM_STR);
                    $res->bindValue(":OTHER_REASON", $otherReason, PDO::PARAM_STR);	
					$res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
	}


public function updateAsVerified($submittee){
               
                            if(!$submittee)
                        throw new jsException("","Any one or more of SUBMITTEE,PROFILEID IS BLANK IN INSERTREPORT() OF NEWJS_CONTACT_ARCHIVE.class.php");

                try
                {

                    $sql = "UPDATE jsadmin.REPORT_INVALID_PHONE SET VERIFIED=:VERIFIED where `SUBMITTEE`=:PROFILEID";
                    $res = $this->db->prepare($sql);
                                        $comments=$comments?$comments:'';
                    $res->bindValue(":PROFILEID", $submittee, PDO::PARAM_INT);  
                    $res->bindValue(":VERIFIED", 'Y', PDO::PARAM_STR);  
                    $res->execute();
                }
                catch(PDOException $e)
                {
                        /*** echo the sql statement and error message ***/
                        throw new jsException($e);
                }
    }

        public function getReportInvalidLog($startDate,$endDate)
    {
        try     
        {   
            $sql = "SELECT * from jsadmin.REPORT_INVALID_PHONE WHERE DATE(`SUBMIT_DATE`) BETWEEN :STARTDATE AND :ENDDATE ORDER BY `SUBMIT_DATE` DESC";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STARTDATE",$startDate,PDO::PARAM_STR);
            $prep->bindValue(":ENDDATE",$endDate,PDO::PARAM_STR);
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



    public function getReportInvalidCount($profileId , $timeOfMarking , $lastDateToCheck)
    {
        try     
        {   


                        if(!($profileId) || !$timeOfMarking || !$lastDateToCheck )
                            throw new jsException("","profileId IS not passed or blank , also check if start and end dates are mentioned");

                        $sql = 'SELECT count( * ) AS CNT
                        FROM jsadmin.REPORT_INVALID_PHONE
                        WHERE DATE( `SUBMIT_DATE` ) <= DATE("'.$timeOfMarking.'")
                        AND DATE( `SUBMIT_DATE` ) >= DATE( "'.$lastDateToCheck.'" )
                        AND SUBMITTEE ='.$profileId;
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
    public function getTotalReportInvalidCount( $startDate , $endDate)
    {
        try     
        {   


                        if(!$startDate || !$endDate )
                            throw new jsException("","check if month and year are mentioned");

                        $sql = 'SELECT count( * ) AS CNT,DATE( `SUBMIT_DATE` ) AS DT FROM jsadmin.REPORT_INVALID_PHONE WHERE DATE(`SUBMIT_DATE`) BETWEEN :STARTDATE AND :ENDDATE  GROUP BY DT';
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":STARTDATE", $startDate, PDO::PARAM_STR);  
                        $res->bindValue(":ENDDATE", $endDate, PDO::PARAM_STR);  
                        $res->execute();

                        while($row=$res->fetch(PDO::FETCH_ASSOC))   
                            $output[]=$row;
                        return $output;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    }

        public function getReportInvalidCountMIS($profileId,$startDate,$endDate)
    {
       try     
        {   


                        if(!($profileId))
                            throw new jsException("","profileId IS not passed or blank");

                    $sql = 'SELECT count( * ) AS CNT
                        FROM jsadmin.REPORT_INVALID_PHONE
                        WHERE DATE( `SUBMIT_DATE` ) <= DATE("'.$startDate.'")
                        AND DATE( `SUBMIT_DATE` ) >= DATE( "'.$endDate.'" )
                        AND SUBMITTEE ='.$profileId;
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

    public function getReportInvalidForUser($profileid)
    {
        try     
        {   
            $sql = "SELECT SUBMITTER,SUBMIT_DATE,REASON,OTHER_REASON from jsadmin.REPORT_INVALID_PHONE WHERE SUBMITTEE = :PROFID";
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


    public function getReportInvalidInterval($profileId, $interval)
    {
        try     
        {   
            $sql = "SELECT * from jsadmin.REPORT_INVALID_PHONE WHERE SUBMITTEE = :PROFILEID AND TIMESTAMPDIFF(DAY , `SUBMIT_DATE`, NOW()) <= :INTERVAL ORDER BY `SUBMIT_DATE` DESC";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":PROFILEID",$profileId,PDO::PARAM_STR);
            $prep->bindValue(":INTERVAL",$interval,PDO::PARAM_INT);
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

 public function getReportInvalidCountSubmitter($profileId,$startDate,$endDate)
    {
       try     
        {   


                        if(!($profileId) || !($startDate) || !($endDate))
                            throw new jsException("","profileId IS not passed or blank, check for start and end dates as well ");

                    $sql = 'SELECT count(1) AS CNT
                        FROM jsadmin.REPORT_INVALID_PHONE
                        WHERE DATE( `SUBMIT_DATE` ) BETWEEN :ENDDATE and :STARTDATE 
                        AND SUBMITTER = :PROFILEID';
                        $prep = $this->db->prepare($sql);
                        
                        $prep->bindValue(":STARTDATE", $startDate, PDO::PARAM_STR);  
                        $prep->bindValue(":ENDDATE", $endDate, PDO::PARAM_STR);
                        $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_STR);
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

public function entryExistsForPair($submitter,$submittee)
    {
        try     
        {  
            $sql = "SELECT count(1) AS CNT from jsadmin.REPORT_INVALID_PHONE WHERE SUBMITTEE = :SUBMITTEE AND SUBMITTER = :SUBMITTER";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":SUBMITTEE",$submittee,PDO::PARAM_INT);
            $prep->bindValue(":SUBMITTER",$submitter,PDO::PARAM_INT);
            $prep->execute();

            $result = 0;

            if($row=$prep->fetch(PDO::FETCH_ASSOC))
            $result = $row['CNT'];

            return $result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    
    } 

}
?>    
