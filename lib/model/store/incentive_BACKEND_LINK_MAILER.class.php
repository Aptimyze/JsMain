<?php

class incentive_BACKEND_LINK_MAILER extends TABLE
{
	public function __construct($dbname="")
	{
    parent::__construct($dbname);
  }

	/**
     * Function to log 1 month plan mail details into BACKEND_LINK_MAILER table
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

      $sql = "UPDATE incentive.BACKEND_LINK_MAILER SET SENT_DATE=:SENT_DATE WHERE PROFILEID=:PROFILEID";
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

	  /**
     * Function to insert 1 month plan mail details into BACKEND_LINK_MAILER table
     *
     * @param   $paramArr
     * @return  none
     */
  public function setDataforMailer($paramArr=array())
  {
    try
    {
      $sql = "INSERT INTO incentive.BACKEND_LINK_MAILER (PROFILEID,EMAIL,USERNAME,COUNTRY_RES,SENT_DATE) VALUES(:PROFILEID,:EMAIL,:USERNAME,:COUNTRY_RES,:SENT_DATE)";
      $res = $this->db->prepare($sql);
      $res->bindValue(":PROFILEID", $paramArr[PROFILEID], PDO::PARAM_INT);
      $res->bindValue(":EMAIL", $paramArr[EMAIL], PDO::PARAM_STR);
      $res->bindValue(":USERNAME", $paramArr[USERNAME], PDO::PARAM_STR);
      $res->bindValue(":COUNTRY_RES", $paramArr[COUNTRY_RES], PDO::PARAM_INT);	
      $res->bindValue(":SENT_DATE", '0000-00-00', PDO::PARAM_STR);
      $res->execute();
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
  }

	 /**
     * Function to insert 1 month plan mail details into BACKEND_LINK_MAILER table
     *
     * @param   $paramArr
     * @return  none
     */
  public function getDataforMailer($day)
  {
    try
    {
      $sql = "SELECT * FROM incentive.BACKEND_LINK_MAILER WHERE PROFILEID%3=$day";
      $prep = $this->db->prepare($sql);
      $prep->execute();
      while($res=$prep->fetch(PDO::FETCH_ASSOC))
      {
	$pid = $res["PROFILEID"];
	$profiles[$pid]["PROFILEID"]=$pid;
	$profiles[$pid]["EMAIL"]=$res["EMAIL"];
	$profiles[$pid]["USERNAME"]=$res["USERNAME"];
	$profiles[$pid]["COUNTRY_RES"]=$res["COUNTRY_RES"];
      }
    }
    catch(Exception $e)
    {
      throw new jsException($e);
    }
    return $profiles;
  }
}
?>
