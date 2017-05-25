<?php

class billing_WELCOME_DISCOUNT extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
  }

	/**
     * Function to insert welcome discount profileid into WELCOME_DISCOUNT table
     *
     * @param   $pid
     * @return  none
     */ 
  public function addEntry($pid)
  {
    try
    {

      $sql = "INSERT IGNORE INTO billing.WELCOME_DISCOUNT (PROFILEID) VALUES(:PROFILEID)";
      $res = $this->db->prepare($sql);
      $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
      $res->execute();
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

  /**
     * Function to check whether profileid exists in table or not
     *
     * @param   $pid
     * @return  profileid if entry else null
     */ 
  public function ifAlreadyOfferedWD($pid)
  {
    try
    { 
        $sql = "SELECT PROFILEID FROM billing.WELCOME_DISCOUNT WHERE PROFILEID =:PROFILEID";
        $res=$this->db->prepare($sql);
        $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    catch(PDOException $e)
    {
            throw new jsException($e);
    }
  }
}
?>
