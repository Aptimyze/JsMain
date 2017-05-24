<?php

/* 
 * this is a store class to store profile ids to whom an email has been sent
 */
class duplicates_DUPLICATE_PROFILES_MAIL_LOG extends TABLE
{
  public function __construct($dbname='') {
    parent::__construct($dbname);
  }
  /*
   * this function inserts a row for a user id to whom an email has been sent
   * @param : $sender sender id
   */
  public function insert($profileId) {
    if ($profileId) {
      try {
         $sql= "INSERT IGNORE INTO duplicates.DUPLICATE_PROFILES_MAIL_LOG(PROFILEID) VALUES(:PROFILE)";
         $res = $this->db->prepare($sql);
	 $res->bindValue(":PROFILE", $profileId, PDO::PARAM_INT);
	 $res->execute();
      } 
      catch (PDOException $e) {
        throw new jsException($e);
      }
    }
  }
  /*
   * this function deletes records from table where profile ids belong to a group of modulus 3
   * @param : $totalScript modulus divisor
   * @param : $currentScript modulus remainder
   */
  public function delete($totalScript,$currentScript) {
      try {
         $sql= "DELETE FROM duplicates.DUPLICATE_PROFILES_MAIL_LOG WHERE PROFILEID%:totalScript=:currentScript";
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

