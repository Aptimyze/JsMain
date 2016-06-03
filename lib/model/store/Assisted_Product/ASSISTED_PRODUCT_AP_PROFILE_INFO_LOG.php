<?php

/**
* @author Ankit Shukla
* @created 2015-06-23
*/
class ASSISTED_PRODUCT_AP_PROFILE_INFO_LOG extends TABLE
{
  public function __construct($dbname='') {
    parent::__construct($dbname);
  }
  /*
   * this function gets the total count for number of profiles in the table
   * @return the count for number of records 
   */
  public function getCount() {
      try {
         $sql= "SELECT COUNT(*) AS CNT FROM Assisted_Product.AP_PROFILE_INFO_LOG";
         $res = $this->db->prepare($sql);
	 $res->execute();
         $row = $res->fetch(PDO::FETCH_ASSOC);
         return $row['CNT'];
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
  }
  /*
   * this function inserts a row for a profile id for whom EOIs have been sent
   * @param - $sender-sender id
   */
  public function insert($sender) {
    if ($sender) {
      try {
         $sql= "INSERT IGNORE INTO Assisted_Product.AP_PROFILE_INFO_LOG(PROFILEID) VALUES(:SENDER)";
         $res = $this->db->prepare($sql);
	 $res->bindValue(":SENDER", $sender, PDO::PARAM_INT);
	 $res->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
    }
  }
  /*
   * this function deletes records from table
   */
  public function delete() {
      try {
         $sql= "TRUNCATE TABLE Assisted_Product.AP_PROFILE_INFO_LOG";
         $res = $this->db->prepare($sql);
	 $res->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
  }
}
