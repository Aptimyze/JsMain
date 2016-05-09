<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of billing_SMS_REQUEST_CALLBACK
 *
 * @author nitish
 */
class billing_SMS_REQUEST_CALLBACK extends TABLE
{
    public function __construct($dbname="")
    {
        parent::__construct($dbname);
    }

	/**
     * Function to insert sms details into the table billing.`SMS_REQUEST_CALLBACK`.
     * The SMS are sent to the following users:
     * 1. Never Paid
     * 2. Have Received X = 4 or more acceptances
     * 3. Logged-in in last 15 days
     * 
     * Sms is sent only to those users whose entry are not in the table for the last 3 months.
     *
     * @param   $paramArr
     * @return  none
     */ 
  public function addSMSDetails($paramArr=array())
  {
    try
    {
        $now=date('Y-m-d',time());
        foreach($paramArr as $val){
            $sql = "INSERT INTO billing.SMS_REQUEST_CALLBACK (ID, PROFILEID, SMS_DATE) VALUES(NULL, :PROFILEID, :SMS_DATE)";
            $res = $this->db->prepare($sql);
            $res->bindValue(":PROFILEID", $val, PDO::PARAM_INT);
            $res->bindValue(":SMS_DATE", $now, PDO::PARAM_STR);
            $res->execute();
      }
    }
    catch(Exception $ex)
    {
      throw new jsException($ex);
    }
  }
  
  /**
     * Function to get filtered profiles on basis of sent_date in REQUEST_CALLBACK table 
     * 
     *
     * @param   $profileIdArr
     * @return  array of profileid and max sent date for that profileid
     */ 
  public function getFilterdProfiles($profileIdArr,$smsDate)
  {
    if(!is_array($profileIdArr) && !empty($profileIdArr)){
        throw new jsException("Profile array is empty or null");
    }
    $tempStr = implode(',', $profileIdArr);
    try{
        $sql = 'SELECT PROFILEID FROM billing.SMS_REQUEST_CALLBACK WHERE PROFILEID IN ('.$tempStr.') AND SMS_DATE > :SMSDATE';
        $prep=$this->db->prepare($sql);
        $prep->bindValue(":SMSDATE",$smsDate , PDO::PARAM_STR);
        $prep->execute();
        $i = 0;
        while ($row = $prep->fetch(PDO::FETCH_ASSOC))
        {
            $output[$i] = $row['PROFILEID'];
            $i++;
        }
    } catch (Exception $ex) {
        throw new jsException($ex);
    }
    return $output;
  }
}
?>