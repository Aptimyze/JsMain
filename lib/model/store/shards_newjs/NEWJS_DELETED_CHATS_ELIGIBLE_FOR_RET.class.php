<?php

class NEWJS_DELETED_CHATS_ELIGIBLE_FOR_RET extends TABLE
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
   * @param type $arrChatIds
   * @return type
   * @throws jsExcception
   */
  public function insertRecordsFromChats($arrChatIds)
  {
    try {
      if (0 === count($arrChatIds)) {
        throw new jsException("", "Empty array passed to insertIntoEligibleForRetrieve in NEWJS_DELETED_CHATS_ELIGIBLE_FOR_RET.class.php");
      }

      $strChatIds = "'".implode("','", $arrChatIds)."'";

      $sql = "INSERT INTO newjs.DELETED_CHATS_ELIGIBLE_FOR_RET SELECT * FROM newjs.CHATS where ID IN ({$strChatIds})";

      $prep = $this->db->prepare($sql);
      $prep->execute();
      return $prep->rowCount();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }

  /**
   * 
   * @param type $arrChatIds
   * @return type
   * @throws jsException
   */
  public function removeRecords($arrChatIds)
  {
    try {
      if (0 === count($arrChatIds)) {
        throw new jsException("", "Empty array passed to removeRecords in NEWJS_DELETED_CHATS_ELIGIBLE_FOR_RET.class.php");
      }

      $strChatIds = "'".implode("','", $arrChatIds)."'";

      $sql = "DELETE FROM newjs.DELETED_CHATS_ELIGIBLE_FOR_RET where ID IN ({$strChatIds})";

      $prep = $this->db->prepare($sql);
      $prep->execute();
      return $prep->rowCount();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }

}

?>
