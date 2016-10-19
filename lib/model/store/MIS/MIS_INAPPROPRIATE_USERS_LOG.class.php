<?php
class MIS_INAPPROPRIATE_USERS_LOG extends TABLE
{
  public function __construct($dbName="") {
    parent::__construct($dbName="");
  }
  public function insert($profileid,$scoreArray)
  {
    try {
      $date= date("Y-m-d");
      $sql1="INSERT INTO MIS.INAPPROPRIATE_USERS_LOG(USERNAME,PROFILEID,RELIGION_COUNT,MSTATUS_COUNT,AGE_COUNT,TOTAL_SCORE,DATE) VALUES (:USERNAME,:ID,:RELIGION_COUNT,:MSTATUS_COUNT,:AGE_COUNT,:TOTAL_SCORE,:DATE)";
      $res1=$this->db->prepare($sql1);
      $totalScore = $scoreArray['R'] + $scoreArray['M'] + $scoreArray['A'];
      $res1->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res1->bindValue(":RELIGION_COUNT",$scoreArray['R'],PDO::PARAM_INT);
      $res1->bindValue(":MSTATUS_COUNT",$scoreArray['M'],PDO::PARAM_INT);
      $res1->bindValue(":AGE_COUNT",$scoreArray['A'],PDO::PARAM_INT);
      $res1->bindValue(":USERNAME",$scoreArray['USERNAME'],PDO::PARAM_STR);
      $res1->bindValue(":TOTAL_SCORE",$totalScore,PDO::PARAM_INT);
      $res1->bindValue(":DATE",$date,PDO::PARAM_STR);
      $res1->execute();
    } 
    catch(PDOException $e){
	throw new jsException($e);
    }
  }
  public function getDataForADate($date)
  {
    try {  
      $sql= "SELECT USERNAME,SUM(RELIGION_COUNT) AS RCOUNT,SUM(AGE_COUNT) AS ACOUNT,SUM(MSTATUS_COUNT) AS MCOUNT,SUM(TOTAL_SCORE) AS TCOUNT FROM MIS.INAPPROPRIATE_USERS_LOG WHERE DATE <= :DATE GROUP BY USERNAME";
      $prep=$this->db->prepare($sql);
      $prep->bindValue(":DATE",$date,PDO::PARAM_STR);
      $prep->execute();
      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        $records[] = $result;
      }
      return $records;
    }
    catch(PDOException $e){
	throw new jsException($e);
    }
  }
  
}
