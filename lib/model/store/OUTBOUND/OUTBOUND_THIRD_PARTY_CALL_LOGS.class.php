<?php
/**
 * @author Kunal Verma
 * @Date 15th June 2017
 */
class OUTBOUND_THIRD_PARTY_CALL_LOGS extends TABLE {

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "notification_master") {
   $dbname = "notification_master";
    parent::__construct($dbname);
  }

  public function insertRecord($arrRecordData) {
    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in InsertRecord OF OUTBOUND_THIRD_PARTY_CALL_LOGS.class.php");

    try {
      $szINs = implode(',', array_fill(0, count($arrRecordData), '?'));

      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $arrFields[] = strtoupper($key);
      }
      $szFields = implode(",", $arrFields);

      $sql = "INSERT INTO OUTBOUND.THIRD_PARTY_CALL_LOGS ($szFields) VALUES ($szINs)";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      $pdoStatement->execute();
      
      return $pdoStatement->rowCount();
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }

  public function updateRecord($iCalledUserId, $arrRecordData) {
    if (!is_numeric(intval($iCalledUserId)) || !$iCalledUserId) {
      throw new jsException("", "iCalledUserId is not numeric in UpdateRecord OF OUTBOUND_THIRD_PARTY_CALL_LOGS.class.php");
    }

    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in UpdateRecord OF OUTBOUND_THIRD_PARTY_CALL_LOGS.class.php");

    if (isset($arrRecordData['CALLED_USER_ID']) && strlen($arrRecordData['CALLED_USER_ID']) > 0)
      throw new jsException("", "Trying to update CALLED_USER_ID in  in UpdateRecord OF OUTBOUND_THIRD_PARTY_CALL_LOGS.class.php");

    try {
      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $columnName = strtoupper($key);

        $arrFields[] = "$columnName = ?";
      }
      $szFields = implode(",", $arrFields);

      $sql = "UPDATE OUTBOUND.THIRD_PARTY_CALL_LOGS  $szFields WHERE CALLED_USER_ID = ?";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      ++$count;
      $pdoStatement->bindValue($count, $iCalledUserId);

      $pdoStatement->execute();
      
      return true;
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }

  public function getLastRecord($iCalledUserId) {
    if (!is_numeric(intval($iCalledUserId))) {
      throw new jsException("", "iCalledUserId is not numeric in UpdateRecord OF NEWJS_NATIVE_PLACE.class.php");
    }

    try {
      $sql = "SELECT * FROM OUTBOUND.THIRD_PARTY_CALL_LOGS WHERE CALLED_USER_ID = ? ORDER BY DATE_TIME DESC LIMIT 1";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      $pdoStatement->bindValue( ++$count, $iCalledUserId);

      $pdoStatement->execute();

      $arrResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
      if(is_array($arrResult) && count($arrResult)) {
        return $arrResult[0];
      }
      return false;
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }
  
  /**
   * 
   * @param type $callSid
   * @param type $apiresponse
   * @return boolean
   * @throws jsException
   */
  public function updateCallResponse($callSid, $apiresponse)
  {
    try {
      $sql = "UPDATE OUTBOUND.THIRD_PARTY_CALL_LOGS SET RESPONSE_FROM_THIRD_PARTY = CONCAT(RESPONSE_FROM_THIRD_PARTY, :API_RESP) WHERE CALLSID = :CALL_SID";
      $pdoStatement = $this->db->prepare($sql);
      
      $pdoStatement->bindValue( ":API_RESP", $apiresponse);
      $pdoStatement->bindValue( ":CALL_SID", $callSid);
      
      $pdoStatement->execute();

      return true;
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }

}
?>
