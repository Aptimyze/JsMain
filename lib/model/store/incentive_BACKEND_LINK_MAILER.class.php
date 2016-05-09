<?php

class incentive_BACKEND_LINK_MAILER extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
  }

	/**
     * Function to insert 1 month plan mail details into BACKEND_LINK_MAILER table
     *
     * @param   $paramArr
     * @return  none
     */ 
  public function addSentMailDetails($paramArr=array())
  {
    try
    {
      foreach($paramArr as $key=>$val)
        ${$key} = $val;

      $sql = "INSERT INTO incentive.BACKEND_LINK_MAILER (PROFILEID,SENT_DATE) VALUES(:PROFILEID,:SENT_DATE)";
      $res = $this->db->prepare($sql);
      $res->bindValue(":PROFILEID", $PROFILEID, PDO::PARAM_INT);
      $res->bindValue(":SENT_DATE", $SENT_DATE, PDO::PARAM_STR);
      $res->execute();
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }
}
?>