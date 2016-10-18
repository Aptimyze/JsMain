<?php
/* this class is used to track clicks on buttons on the critical action layer*/
class MIS_INAPPROPRIATE_USERS_LOG extends TABLE
{
  public function __construct($dbName="") {
    parent::__construct($dbName="");
  }
  /*this function is used to select record in table for today's entry
   * @param profile id,layer which has been opened,button which is clicked
   */
  public function getDataForAUserReported($profileid,$dateGreaterThan) {
    try {
      $sql="SELECT * FROM MIS.INAPPROPRIATE_USERS_LOG WHERE PROFILEID=:ID AND `DATE` >= :dateGT ORDER BY `DATE` DESC LIMIT 1";
      $res=$this->db->prepare($sql);
      $res->bindValue(":ID",$profileid,PDO::PARAM_INT);
      $res->bindValue(":dateGT",$dateGreaterThan,PDO::PARAM_STR);
      $res->execute();
      $result = $res->fetch(PDO::FETCH_ASSOC);
      return $result;
    } 
    catch(PDOException $e){
	throw new jsException($e);
    }
  }
  /*this function is used to insert record in table when a user clicks
   * on any of the two buttons on each partcular day based on type of the layer
   * @param profile id,layer which has been opened,button which is clicked
   */
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
  /*this function will return the count for no. of times a layer has been shown 
   * in day, how many days
   *@param- profile id,layer id for counting, count for today or total count
   *@return- count integer
   */
  public function getDataForADate($date)
  {
    try {  
      $sql= "SELECT * FROM MIS.INAPPROPRIATE_USERS_LOG WHERE DATE = :DATE ORDER BY `TOTAL_SCORE` DESC";
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

