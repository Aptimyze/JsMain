<?php

/*
This class is used to fetch Count of eoi's sent to receivers and insert into this table
*/
class ASSISTED_PRODUCT_EOI_RECEIVED_COUNT extends TABLE
{
	public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
  
  
  /*this functions fetches profileids which have count above a particular limit
   * @param- $date for which records have to be fetched
   * @param - $receiver profile id of receiver
   * @return - count of eois
   */
  public function getReceiversWithLimit($count) {
    if ($count) {
      try {
        $sql = "SELECT RECEIVER FROM Assisted_Product.EOI_RECEIVED_COUNT WHERE EOI_COUNT >= :COUNT";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":COUNT",$count, PDO::PARAM_INT);
        $prep->execute();
        while($row = $prep->fetch(PDO::FETCH_ASSOC))
            $result.= " ".$row[RECEIVER];
        return $result;
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      } 
    }
  }
  
  /*this functions inserts or updates entry for a receiver
   * @param- $date for which records have to be fetched
   * @param - $receiver profile id of receiver
   * @return - count of eois
   */
  public function insertOrUpdateEntryForReceiver($receiver) {
    if ($receiver) {
      try {
        $sql = "INSERT INTO Assisted_Product.EOI_RECEIVED_COUNT(RECEIVER,EOI_COUNT) VALUES (:RECEIVER,1) ON DUPLICATE KEY UPDATE EOI_COUNT = EOI_COUNT + 1";
        $prep = $this->db->prepare($sql);
        $prep->bindValue(":RECEIVER",$receiver, PDO::PARAM_INT);
        $prep->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      } 
    }
  }

  /*this functions truncates the table
   */
  public function emptyTable() {
      try {
        $sql = "TRUNCATE TABLE Assisted_Product.EOI_RECEIVED_COUNT";
        $prep = $this->db->prepare($sql);
        $prep->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
  }

  
}
