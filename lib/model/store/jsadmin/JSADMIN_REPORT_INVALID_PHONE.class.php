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
	public function insertReport($submitter,$submittee,$phone,$mob,$comments){
               
                            if(!$submitter || !$submittee || !$phone || !$mob)
                        throw new jsException("","Any one or more of SUBMITTER, SUBMITTEE, PHONE, MOBILE IS BLANK IN INSERTREPORT() OF NEWJS_CONTACT_ARCHIVE.class.php");

                try
                {

					$now = date("Y-m-d G:i:s");
					$sql = "REPLACE INTO jsadmin.REPORT_INVALID_PHONE(SUBMITTER,SUBMITTEE,SUBMIT_DATE,PHONE,MOBILE,COMMENTS) VALUES(:SUBMITTER,:SUBMITTEE,:SUBMIT_DATE,:PHONE,:MOBILE,:COMMENTS)";
					$res = $this->db->prepare($sql);
                                        $comments=$comments?$comments:'';
				  	$res->bindValue(":SUBMITTER", $submitter, PDO::PARAM_INT);	
				  	$res->bindValue(":SUBMITTEE", $submittee, PDO::PARAM_STR);	
                                        $res->bindValue(":SUBMIT_DATE", $now, PDO::PARAM_STR);                                  
                                        $res->bindValue(":PHONE", $phone, PDO::PARAM_STR);                                  
                                        $res->bindValue(":MOBILE", $mob, PDO::PARAM_STR);                                  
				  	$res->bindValue(":COMMENTS", $comments, PDO::PARAM_STR);				  	
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

       public function getUniqieSubmitees($startDate,$endDate)
    {
        try     
        {   
            $sql = "SELECT distinct SUBMITTEE from jsadmin.REPORT_INVALID_PHONE WHERE DATE(`SUBMIT_DATE`) BETWEEN :STARTDATE AND :ENDDATE ORDER BY `SUBMIT_DATE` DESC";
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


  public function getLatestDate($startDate,$endDate,$profileId)
    {
        try     
        {   
            $sql = "SELECT max(SUBMIT_DATE) as sbDate from jsadmin.REPORT_INVALID_PHONE WHERE DATE(`SUBMIT_DATE`) BETWEEN :STARTDATE AND :ENDDATE AND SUBMITTEE=".$profileId ;
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":STARTDATE",$startDate,PDO::PARAM_STR);
            $prep->bindValue(":ENDDATE",$endDate,PDO::PARAM_STR);
            $prep->execute();
            $result=$prep->fetch(PDO::FETCH_ASSOC);
        return $result;
        }
        catch(Exception $e)
        {
            throw new jsException($e);
        }
    
    }



}
?>    