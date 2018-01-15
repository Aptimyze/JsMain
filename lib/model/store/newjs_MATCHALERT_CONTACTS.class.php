<?php

class NEWJS_MATCHALERT_CONTACTS extends TABLE {
  
  public function __construct($dbname = "") {
    
    parent::__construct($dbname);

  }

  public function insert($sender_profileID, $receiver_profileID, $click_source, $stype) {
    $sql = "INSERT INTO newjs.MATCHALERT_CONTACTS(SENDER, RECEIVER, DATE, TYPE) VALUES (:SENDER, :RECEIVER, now(), :TYPE)";
    $to_insert = false;
    $type = "";
    $prep = $this->db->prepare($sql);
    switch($click_source) {
      
      case "matchalert": 
        $type = "";
        $to_insert = true;
        break;

      case "matchalert1": 
        $type = "1";
        $to_insert = true;
        break;

      case "matchalert2":
        $type = "2";
        $to_insert = true;
        break;

      default:
        break;
    }
    if (true === $to_insert) {
      
      $prep->bindValue(":SENDER", $sender_profileID, PDO::PARAM_INT);
      
      $prep->bindValue(":RECEIVER", $receiver_profileID, PDO::PARAM_INT);
      
      $prep->bindValue(":TYPE", $type, PDO::PARAM_STR);
      
      $prep->execute();

      $stype = "B";
    }

    return $stype;
  }
}
