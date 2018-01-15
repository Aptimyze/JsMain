<?php

/**
 * Description of seach_MATCH_ALERT_LAST_VISIT
 * Store Class for CRUD Operation on PROFILE.PROFILE_COMPLETION_SCORE
 * 
 * @author Kunal Verma
 * @created 25th Feb 2016
 */
class seach_MATCH_ALERT_LAST_VISIT extends TABLE {

  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  /*
   * log records
   * Store last time just joined search is run for a user.
   */

  public function ins($pid) {
    try {
      if (!$pid)
        throw new jsException("", "PROFILEID IS BLANK IN ins() of search.MATCH_ALERT_LAST_VISIT");
      $dt = date("Y-m-d h:i:s");
      $sql = "REPLACE INTO search.MATCH_ALERT_LAST_VISIT(PROFILEID,LAST_VISITED_DT) VALUES (:PID,:DT)";
      $res = $this->db->prepare($sql);
      $res->bindParam(":PID", $pid, PDO::PARAM_INT);
      $res->bindParam(":DT", $dt, PDO::PARAM_INT);
      $res->execute();
      return $dt;
    }
    catch (Exception $e) {
       jsException::log($e);
    }
  }

  /*
   * get records
   * get last time when just joined search is run for a user.
   */

  public function getDt($pid) {
    try {
      if (!$pid)
        throw new jsException("", "PROFILEID IS BLANK IN get() of search.MATCH_ALERT_LAST_VISIT");
      $sql = "SELECT LAST_VISITED_DT FROM search.MATCH_ALERT_LAST_VISIT WHERE PROFILEID=:PID";
      $res = $this->db->prepare($sql);
      $res->bindParam(":PID", $pid, PDO::PARAM_INT);
      $res->execute();
      $row = $res->fetch(PDO::FETCH_ASSOC);
      if (!$row["LAST_VISITED_DT"])
        return NULL;
      return $row["LAST_VISITED_DT"];
    }
    catch (Exception $e) {
      throw new jsException($e);
    }
  }

}

?>
