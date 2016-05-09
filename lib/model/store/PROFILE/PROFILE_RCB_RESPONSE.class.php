<?php

/**
 * Description of PROFILE_RCB_RESPONSE
 * Store Class for CRUD Operation on PROFILE.PROFILE_RCB_RESPONSE
 * 
 * @author Kunal Verma
 * @created 31st March 2016
 */

/**
 * 
 */
class PROFILE_RCB_RESPONSE extends TABLE {

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  /**
   * insertRecord
   * @param integer $iProfileID
   * @param char $cResponse
   * @return boolean
   * @throws jsException
   */
  public function insertRecord($iProfileID,$timeStamp,$cResponse) 
  {
    if (!is_numeric(intval($iProfileID)) || !$iProfileID) {
      jsException::log("", "iProfileID is not numeric in insertRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
    }

    try {
      $sql = "INSERT INTO `PROFILE`.`RCB_RESPONSE` (`PROFILEID`,`TIMESTAMP`,`RESPONSE`) VALUES (:PID,:TS,:RES)";

      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $pdoStatement->bindValue(":TS", $timeStamp, PDO::PARAM_STR);
      $pdoStatement->bindValue(":RES", $cResponse, PDO::PARAM_STR);
      $pdoStatement->execute();

      return $pdoStatement->rowCount(); 
    }
    catch (Exception $ex) {
      jsException::nonCriticalError($e);
    }
  }
  
  /**
   * 
   * @param type $iProfileID
   * @return type
   * @throws jsException
   */
  public function getLatestRecord($iProfileID) 
  {
    if (!is_numeric(intval($iProfileID)) || !$iProfileID) {
      jsException::log("", "iProfileID is not numeric in insertRecord OF PROFILE_PROFILE_COMPLETION_SCORE.class.php");
    }
    
    try{
      $sql = "SELECT PROFILEID,TIMESTAMP,RESPONSE FROM `PROFILE`.`RCB_RESPONSE` WHERE PROFILEID=:PID ORDER BY TIMESTAMP DESC LIMIT 1";

      $pdoStatement = $this->db->prepare($sql);
      $pdoStatement->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $pdoStatement->execute();
      
      //If Record Exist
      if($pdoStatement->rowCount()) {
        $arrResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $arrResult[0];
      }
      //Else Reture False
      return false; 
    } catch (Exception $ex) {
      jsException::nonCriticalError($e);
    }
  }
}
