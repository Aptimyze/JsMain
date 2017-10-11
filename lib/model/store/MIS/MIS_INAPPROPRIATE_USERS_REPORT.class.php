<?php
class MIS_INAPPROPRIATE_USERS_REPORT extends TABLE
{
  public function __construct($dbName="") {
    parent::__construct($dbName="");
  }
  public function insert($profileid,$totalCount,$rCount,$aCount,$mCount,$uName,$abuseCount =0 ,$invalidCount= 0)
  {
    try {
      $date= date("Y-m-d");
      $sql1="INSERT INTO MIS.INAPPROPRIATE_USERS_REPORT(USERNAME,PROFILEID,RELIGION_COUNT,MSTATUS_COUNT,AGE_COUNT,TOTAL_SCORE,DATE,REPORT_ABUSE_COUNT,REPORT_INVALID_COUNT) VALUES (:USERNAME,:ID,:RELIGION_COUNT,:MSTATUS_COUNT,:AGE_COUNT,:TOTAL_SCORE,:DATE,:REPORT_ABUSE_COUNT,:REPORT_INVALID_COUNT)";
      $res1=$this->db->prepare($sql1);
      $res1->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res1->bindValue(":RELIGION_COUNT",$rCount,PDO::PARAM_INT);
      $res1->bindValue(":MSTATUS_COUNT",$mCount,PDO::PARAM_INT);
      $res1->bindValue(":AGE_COUNT",$aCount,PDO::PARAM_INT);
      $res1->bindValue(":USERNAME",$uName,PDO::PARAM_STR);
      $res1->bindValue(":TOTAL_SCORE",$totalCount,PDO::PARAM_INT);
      $res1->bindValue(":DATE",$date,PDO::PARAM_STR);
      $res1->bindValue(":REPORT_ABUSE_COUNT",$abuseCount,PDO::PARAM_INT);
      $res1->bindValue(":REPORT_INVALID_COUNT",$invalidCount,PDO::PARAM_INT);
      $res1->execute();
    } 
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_INAPPROPRIATE_USERS_LOG.class.php-->.$sql".$e);
                        return '';
    }
  }
  public function getMaxForUser($profileid,$startDate,$endDate)
  {
    try {  
      $sql= "SELECT MAX(TOTAL_SCORE) as MAX FROM MIS.INAPPROPRIATE_USERS_REPORT WHERE PROFILEID = :PROFILEID AND DATE <= :EDATE AND DATE>=:SDATE";
      $prep=$this->db->prepare($sql);
      $prep->bindValue(":SDATE",$startDate,PDO::PARAM_STR);
      $prep->bindValue(":EDATE",$endDate,PDO::PARAM_STR);
      $prep->bindValue(":PROFILEID",$profileid,PDO::PARAM_INT);
      $prep->execute();
      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        return $result;
      }
    }
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_INAPPROPRIATE_USERS_REPORT.class.php-->.$sql".$e);
                        return '';
    }
  }
  public function truncateTable($startDate)
  {
    try {  
      $sql= "DELETE FROM MIS.INAPPROPRIATE_USERS_REPORT WHERE DATE < :SDATE";
      $prep=$this->db->prepare($sql);
      $prep->bindValue(":SDATE",$startDate,PDO::PARAM_STR);
      $prep->execute();
    }
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_INAPPROPRIATE_USERS_REPORT.class.php-->.$sql".$e);
                        return '';

        }
  }
  public function getReportForADate($date,$minTotalScore=0)
  {
    try {  
      $minScoreQuery = $minTotalScore ? "AND TOTAL_SCORE >= $minTotalScore " :"";
      $sql= "SELECT * FROM MIS.INAPPROPRIATE_USERS_REPORT WHERE DATE = :EDATE $minScoreQuery ORDER BY TOTAL_SCORE DESC";
      $prep=$this->db->prepare($sql);
      $prep->bindValue(":EDATE",$date,PDO::PARAM_STR);
      $prep->execute();
      while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
         $result[]=$row;
      }
      return $result;
    }
    catch(PDOException $e){
		jsException::nonCriticalError("lib/model/store/MIS/MIS_INAPPROPRIATE_USERS_REPORT.class.php-->.$sql".$e);
                        return '';
    }
  }
  
}
