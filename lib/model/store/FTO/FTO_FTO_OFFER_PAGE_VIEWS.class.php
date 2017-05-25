<?php

class FTO_FTO_OFFER_PAGE_VIEWS extends TABLE {

  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  public function getOfferPageView($profileid, $stateId) {
    try {
      $sql = "SELECT SQL_CACHE STATE_ID, TIMES_VIEWED FROM FTO.FTO_OFFER_PAGE_VIEWS WHERE PROFILEID = :PROFILEID AND STATE_ID = :STATE_ID";
      $res = $this->db->prepare($sql);
      $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $res->bindValue(":STATE_ID", $stateId, PDO::PARAM_INT);
      $res->execute();
      $row = $res->fetch(PDO::FETCH_ASSOC);
      if ($row[TIMES_VIEWED] && $row[STATE_ID]) {
        return array($row[STATE_ID], $row[TIMES_VIEWED]);
      }
      else {
        return array(0, 0);
      }
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  }

  public function updateOfferPageView($profileid, $stateId) {
    try {
      $sql = "UPDATE FTO.FTO_OFFER_PAGE_VIEWS SET TIMES_VIEWED = TIMES_VIEWED + 1 WHERE PROFILEID = :PROFILEID AND STATE_ID = :STATE_ID";
      $res = $this->db->prepare($sql);
      $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $res->bindValue(":STATE_ID", $stateId, PDO::PARAM_INT);
      $res->execute();
      if ($res->errorCode() === '00000') { //Update Successful.
        return 1;
      }
      else {
        return 0;
      }
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  }

  public function insertOfferPageView($profileid, $stateId) {
    try {
      list($state, $timesViewed) = $this->getOfferPageView($profileid, $stateId);
      if (($timesViewed === 0) || ($state === 0)) {
        $sql = "REPLACE INTO FTO.FTO_OFFER_PAGE_VIEWS(PROFILEID, STATE_ID, TIMES_VIEWED) VALUES(:PROFILEID, :STATE_ID, 1)";
        $res = $this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $res->bindValue(":STATE_ID", $stateId, PDO::PARAM_INT);
        $res->execute();
        if ($res->errorCode() === '00000') { //Insert Successful.
          return 1;
        }
        else {
          return -1;
        }
      }
      else { //Record already exists.
        return 0; 
      }
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  }
}
