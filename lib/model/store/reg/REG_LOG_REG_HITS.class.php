<?php

/**
 * Description of REG_LOG_REG_HITS
 * Store Class for CRUD Operation on reg.LOG_REG_HITS
 * 
 * 
 * @author Kunal Verma
 * @created 26th August 2015
 */
class REG_LOG_REG_HITS extends TABLE {
  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = ""){
    parent::__construct($dbname);
  }
  
  /*
   * Insert Record
   * @param : $url  : Hit which we are logging
   * @param : $actualIPAddr : Actual Ip Address By  CommonFunction::getClientIP()
   * @param : $reportedIPAddr : Ip Reported By CommonFunction::getIP & FetchClientIP
   */
  public function insertRecord($url,$actualIPAddr,$reportedIPAddr){
    if(!$url && !$actualIPAddr && !$reportedIPAddr){
      throw new jsException($e,"Some Issue in specifying parameters in insertRecord of REG_LOG_REG_HITS.class.php");
    }
    
    try{
      $now = date("Y-m-d H-i-s");
      
      $sql = "INSERT INTO reg.LOG_REG_HITS (`URL`,`ACTUAL_IP`,`REPORTED_IP`,`ENTRY_DT`) VALUES (:URL,:A_IP,:R_IP,:NOW)";
      $pdoStatement = $this->db->prepare($sql);
      
      $pdoStatement->bindValue(":URL",$url,PDO::PARAM_STR);
      $pdoStatement->bindValue(":A_IP",$actualIPAddr,PDO::PARAM_STR);
      $pdoStatement->bindValue(":R_IP",$reportedIPAddr,PDO::PARAM_STR);
      $pdoStatement->bindValue(":NOW",$now,PDO::PARAM_STR);
      
      $pdoStatement->execute();
      
      return $pdoStatement->rowCount();
    } catch (Exception $ex) {
      throw new jsException($e,"Issue while inserting record in insertRecord of REG_LOG_REG_HITS.class.php");
    }
  }
  
}
?>
