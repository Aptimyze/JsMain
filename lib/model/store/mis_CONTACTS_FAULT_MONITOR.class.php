<?php
/************
  TYPE:
  T: Today
  M: Monthly
  O: Overall
 ************/
class MIS_CONTACTS_FAULT_MONITOR extends TABLE {

  public function __construct($dbname = "") {
    parent::__construct($dbname);
    $this->year = date("Y");
    $this->month = date("m");
    $this->day = date("d");
  }


  public function getRecords($profileid, $type){
    try {
      if($type=="T" || $type == "W") {
        $criteria = " AND YEAR=:YEAR AND MONTH=:MONTH AND DAY=:DAY";
      }
      else if($type=="M") {
        $criteria = " AND YEAR=:YEAR AND MONTH=:MONTH";
      }
      else if ($type == "O" || $type == "I") {
        $criteria = "";
      }

      $sql = "SELECT PROFILEID FROM MIS.CONTACTS_FAULT_MONITOR WHERE PROFILEID=:PROFILEID AND TYPE=:TYPE".$criteria;
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $prep->bindValue(":TYPE", $type, PDO::PARAM_STR);
      
      if($type=="T" || $type == "W") {
        $prep->bindValue(":YEAR", $this->year, PDO::PARAM_INT);
        $prep->bindValue(":MONTH", $this->month, PDO::PARAM_INT);
        $prep->bindValue(":DAY", $this->day, PDO::PARAM_INT);
      }
      else if($type=="M") {
        $prep->bindValue(":YEAR", $this->year, PDO::PARAM_INT);
        $prep->bindValue(":MONTH", $this->month, PDO::PARAM_INT);
      }
      $prep->execute();

      $records = $prep->fetch(PDO::FETCH_ASSOC);
      return $records;
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }

  }

  public function insert($profileid, $username, $type) {

    try {
      $sql_insert = "INSERT INTO MIS.CONTACTS_FAULT_MONITOR (PROFILEID, USERNAME, DATE, TYPE, YEAR, MONTH, DAY, SPAM_CALC) VALUES(:PROFILEID, :USERNAME, now(), :TYPE, :YEAR, :MONTH, :DAY, 'N')";
      $prep_insert = $this->db->prepare($sql_insert);
      $prep_insert->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $prep_insert->bindValue(":USERNAME", $username, PDO::PARAM_STR);
      $prep_insert->bindValue(":TYPE", $type, PDO::PARAM_STR);
      $prep_insert->bindValue(":YEAR", $this->year, PDO::PARAM_INT);
      $prep_insert->bindValue(":MONTH", $this->month, PDO::PARAM_INT);
      $prep_insert->bindValue(":DAY", $this->day, PDO::PARAM_INT);
      $prep_insert->execute();
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  }
}
