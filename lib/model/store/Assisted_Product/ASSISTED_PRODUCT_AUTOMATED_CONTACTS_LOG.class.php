<?php

/* 
 * this class is a store for the table AUTOMATED_CONTACTS_LOG
 */

class ASSISTED_PRODUCT_AUTOMATED_CONTACTS_LOG extends TABLE
{
  public function __construct($dbname='') {
    parent::__construct($dbname);
  }
  /*
   * this function inserts a row for a user id with its count of interests sent
   * @param - $sender-sender id
   */
  public function insert($sender) {
    if ($sender) {
      try {
         $sql= "INSERT IGNORE INTO Assisted_Product.AUTOMATED_CONTACTS_LOG(SENDER_ID) VALUES(:SENDER)";
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
   * this function deletes records from table where profile ids belong to a group of modulus 3
   * @param - $totalScript-modulus divisor,$currentScript-modulus remainder
   */
  public function delete($totalScript,$currentScript) {
      try {
         $sql= "DELETE FROM Assisted_Product.AUTOMATED_CONTACTS_LOG WHERE SENDER_ID%:totalScript=:currentScript";
         $res = $this->db->prepare($sql);
         $res->bindValue(":totalScript", $totalScript, PDO::PARAM_INT);
         $res->bindValue(":currentScript", $currentScript, PDO::PARAM_INT);
	 $res->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
  }
}