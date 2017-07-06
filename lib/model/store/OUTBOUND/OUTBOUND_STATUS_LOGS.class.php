<?php 

/**
* 
*/
class OUTBOUND_STATUS_LOGS extends TABLE
{
	/**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  public function insertRecord($arrRecordData) {
    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in InsertRecord OF OUTBOUND_STATUS_LOGS.class.php");

    try {
      $szINs = implode(',', array_fill(0, count($arrRecordData), '?'));

      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $arrFields[] = strtoupper($key);
      }
      $szFields = implode(",", $arrFields);

      $sql = "INSERT INTO OUTBOUND.STATUS_LOGS ($szFields) VALUES ($szINs)";
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
}

?>