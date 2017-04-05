<?php

class NEWJS_DELETED_CHAT_LOG_ELIGIBLE_FOR_RET extends TABLE
{

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "")
  {
    if (strpos($dbname, 'master') !== false && JsConstants::$communicationRep)
      $dbname = $dbname . "Rep";
    parent::__construct($dbname);
  }

  /**
   * 
   * @param type $iProfileID
   */
  public function insertRecordsFromChatLog($iProfileID)
  {
    try {
      $sql = "INSERT IGNORE INTO newjs.DELETED_CHAT_LOG_ELIGIBLE_FOR_RET SELECT * FROM newjs.CHAT_LOG WHERE SENDER = :PID OR RECEIVER = :PID";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $prep->execute();
    } catch (Exception $ex) {
      
    }
  }

  /**
   * 
   * @param type $iProfileID
   * @param type $listOfActiveProfiles
   */
  public function selectActiveProfileData($iProfileID, $listOfActiveProfiles)
  {

    if (!$iProfileID || !$listOfActiveProfiles) {
      throw new jsException("", "VALUE OR TYPE IS BLANK IN selectActiveDeletedData() of DELETED_CHAT_LOG_ELIGIBLE_FOR_RET.class.php");
    }

    try {
      $sql = "SELECT CHATID FROM newjs.DELETED_CHAT_LOG_ELIGIBLE_FOR_RET WHERE (SENDER = :PROFILEID OR RECEIVER =:PROFILEID) AND (SENDER IN (:LIST_ACTIVE_PID) OR RECEIVER IN (:LIST_ACTIVE_PID))";

      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PROFILEID", $iProfileID, PDO::PARAM_INT);
      $prep->bindValue(":LIST_ACTIVE_PID", $listOfActiveProfiles, PDO::PARAM_STR);
      $prep->execute();

      while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
        $output[] = $row['ID'];
      }
      return $output;
    } catch (PDOException $e) {
      throw new jsException($e);
    }
  }

  /**
   * 
   * @param type $iProfileID
   * @param type $listOfActiveProfiles
   * @throws jsException
   */
  public function deleteRecords($iProfileID, $listOfActiveProfiles)
  {
    try {
      if (!$iProfileID || !$listOfActiveProfiles) {
        throw new jsException("", "PROFILEID OR LISTOFACTIVEPROFILE IS BLANK IN deleteRecords() of DELETED_CHAT_LOG_ELIGIBLE_FOR_RET.class.php");
      }

      $sql = "DELETE FROM newjs.DELETED_CHAT_LOG_ELIGIBLE_FOR_RET WHERE (SENDER = :PID AND RECEIVER IN (:ACTIVE_USERS) ) OR (RECEIVER = :PID AND SENDER IN (:ACTIVE_USERS) )";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $prep->bindValue(":ACTIVE_USERS", $listOfActiveProfiles, PDO::PARAM_STR);
      $prep->execute();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }

}

?>
