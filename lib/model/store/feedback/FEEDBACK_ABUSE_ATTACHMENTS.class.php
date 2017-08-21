<?php
/**
 * FEEDBACK_ABUSE_ATTACHMENTS
 *
 * This class handles all database queries for table ABUSE_ATTACHMENTS
 *
 * @package    jeevansathi
 * @author     Kunal Verma
 * @created    10th August 2017
 */
class FEEDBACK_ABUSE_ATTACHMENTS extends TABLE 
{
	
  /**
   * Constructor function
   * @param type $dbname
   */
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }                                                                                        
 
  /**
   * 
   */
  public function insertRecord($arrRecordData) {
    if (false === is_array($arrRecordData)) {
      throw new jsException("", "Array is not passed in insertRecord OF FEEDBACK_ABUSE_ATTACHMENTS.class.php");
    }

    try {
      $szINs = implode(',', array_fill(0, count($arrRecordData), '?'));

      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $arrFields[] = strtoupper($key);
      }
      $szFields = implode(",", $arrFields);

      $sql = "INSERT IGNORE INTO feedback.ABUSE_ATTACHMENTS ($szFields) VALUES ($szINs)";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      $pdoStatement->execute();
      return $this->db->lastInsertId();
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }
  
  /**
   * 
   * @param type $arrRecordData
   * @param type $iID
   * @param type $Id2
   * @return boolean
   * @throws jsException
   */
  public function edit($arrRecordData, $iID, $Id2 = '') {
    if (!is_numeric(intval($iID)) || !$iID) {
      throw new jsException("", "iProfileID is not numeric in edit OF FEEDBACK_ABUSE_ATTACHMENTS.class.php");
    }

    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in edit OF FEEDBACK_ABUSE_ATTACHMENTS.class.php");

    if (isset($arrRecordData['ID']) && strlen($arrRecordData['ID']) > 0)
      throw new jsException("", "Trying to update PROFILEID in  in edit OF FEEDBACK_ABUSE_ATTACHMENTS.class.php");

    try {
      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $columnName = strtoupper($key);

        $arrFields[] = "$columnName = ?";
      }
      $szFields = implode(",", $arrFields);

      $sql = "UPDATE feedback.ABUSE_ATTACHMENTS SET $szFields WHERE ID = ?";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      ++$count;
      $pdoStatement->bindValue($count, $iID);

      $pdoStatement->execute();
      return true;
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }
  
  /**
   * 
   * @param type $tempAttachmentId
   * @return type
   * @throws jsException
   */
  public function insertFromTempAttachment($tempAttachmentId) {
    try{
      $sql = "INSERT INTO feedback.ABUSE_ATTACHMENTS (DOC_1,DOC_2,DOC_3,DOC_4,DOC_5) SELECT DOC_1,DOC_2,DOC_3,DOC_4,DOC_5 FROM feedback.ABUSE_TEMP_ATTACHMENTS WHERE ID = :TEMP_ID";
      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue("TEMP_ID", $tempAttachmentId, PDO::PARAM_INT);
      $pdoStatement->execute();
      return $this->db->lastInsertId();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }
  
  /**
   * 
   * @param type $attachmentId
   * @return boolean
   * @throws jsException
   */
  public function getRecord($attachmentId) {
    try{
      $sql = "SELECT DOC_1,DOC_2,DOC_3,DOC_4,DOC_5 FROM feedback.ABUSE_ATTACHMENTS WHERE ID = :ID";
      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue(":ID", $attachmentId, PDO::PARAM_INT);
      $pdoStatement->execute();
      //If Record Exist
      if($pdoStatement->rowCount())
          return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

      return false;//False Means No Record Exist    
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }
}
?>
