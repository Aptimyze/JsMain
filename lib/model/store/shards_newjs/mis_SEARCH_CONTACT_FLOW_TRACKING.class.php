<?php

class MIS_SEARCH_CONTACT_FLOW_TRACKING_NEW extends TABLE {

  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  public function insert($profileid, $stype, $contactId, $fromDetailProfile = "N") {
 
    try {
      $sql = "INSERT IGNORE INTO MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW(PROFILEID, SEARCH_TYPE, CONTACTID, DATE, FROM_DETAILPROFILE) VALUES(:PROFILEID, :SEARCH_TYPE, :CONTACTID, :DATE, :FROM_DETAILPROFILE)";
      $prep = $this->db->prepare($sql);
      $prep->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
      $prep->bindValue(":SEARCH_TYPE", $stype, PDO::PARAM_STR);
      $prep->bindValue(":CONTACTID", $contactId, PDO::PARAM_INT);
      $prep->bindValue(":DATE", date("Y-m-d"), PDO::PARAM_STR);
      $prep->bindValue(":FROM_DETAILPROFILE", $fromDetailProfile, PDO::PARAM_STR);
      $prep->execute();
    }
    catch (PDOException $e) {
      throw new jsException($e);
    }
  
  }
}
