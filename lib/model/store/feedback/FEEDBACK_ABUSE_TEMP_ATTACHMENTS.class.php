<?php
/**
 * FEEDBACK_ABUSE_TEMP_ATTACHMENTS
 *
 * This class handles all database queries for table ABUSE_TEMP_ATTACHMENTS
 *
 * @package    jeevansathi
 * @author     Kunal Verma
 * @created    14th August 2017
 */
class FEEDBACK_ABUSE_TEMP_ATTACHMENTS extends TABLE 
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
   * @param type $id
   * @return type
   * @throws jsException
   */
  public function recordExist($id) {
    try{
      $sql = "SELECT 1 FROM feedback.ABUSE_TEMP_ATTACHMENTS WHERE ID = :ID";
      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue(":ID", $id, PDO::PARAM_INT);
      $pdoStatement->execute();
      return $pdoStatement->rowCount();
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
      $sql = "SELECT DOC_1,DOC_2,DOC_3,DOC_4,DOC_5 FROM feedback.ABUSE_TEMP_ATTACHMENTS WHERE ID = :ID";
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
  
  /**
   * 
   * @param type $arrRecordData
   * @return type
   * @throws jsException
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

      $sql = "INSERT IGNORE INTO feedback.ABUSE_TEMP_ATTACHMENTS ($szFields) VALUES ($szINs)";
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
   * @param type $attachmentId
   * @param type $arrData
   */
  public function updateRecord($attachmentId, $arrData) {
    
    if(isset($arrData['ID'])) {
      unset($arrData['ID']);
    }
    
    if( false === is_array($arrData) || 0 === count($arrData) ) {
      throw new jsException("", "Empty Array is passed in updateRecord of FEEDBACK_ABUSE_TEMP_ATTACHMENTS");
    }
    
    try{
      
      foreach ($arrData as $key => $val) {
        $set[] = $key . " = :" . $key;
      }
      $setValues = implode(",", $set);
      
      $sql = "UPDATE feedback.ABUSE_TEMP_ATTACHMENTS SET {$setValues} WHERE ID=:ID";
      $pdoStatement = $this->db->prepare($sql);
      
      foreach ($arrData as $key => $val) {
        $pdoStatement->bindValue(":" . $key, $val);
      }
      
      $pdoStatement->bindValue(":ID", $attachmentId, PDO::PARAM_INT);
      $pdoStatement->execute();
      //If Record Exist
      if($pdoStatement->rowCount())
          return true;

      return false;//False Means No Record Exist    
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }
  
  /**
   * 
   * @param type $id
   * @throws jsException
   */
  public function deleteRecord($id) {
    try{
      $sql = "DELETE FROM feedback.ABUSE_TEMP_ATTACHMENTS WHERE ID = :ID";
      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue(":ID", $id, PDO::PARAM_INT);
      $pdoStatement->execute();
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }
}
?>
