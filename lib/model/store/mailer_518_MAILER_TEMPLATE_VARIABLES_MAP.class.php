<?php

//include_once('../lib/mailer/Variable.class.php');

class newjs_MAILER_TEMPLATE_VARIABLES_MAP extends TABLE {
  
  public function __construct($dbname="") {
    parent::__construct($dbname);
  }

  public function getVariableInfo() {
    try {
      $sql = "SELECT * FROM newjs.MAILER_TEMPLATE_VARIABLES_MAP";


      $prep = $this->db->prepare($sql);
      $prep->execute();

      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        $records[] = $result;
      }
      return $records;
    } 
    catch (Exception $e) {
      throw new jsException($e);
    }
  }
}
