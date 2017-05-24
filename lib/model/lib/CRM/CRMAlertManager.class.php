<?php

/* This class consists of common helper functions used by CRM related files. */
class CRMAlertManager
{
 
/**
* 
* Function for sending alert mail to destination email address 
* @access public
* @param $message
**/
  public static function sendMailAlert($message,$to="default")
  {
    $emailAlertArray=array("default"=>"vibhor.garg@jeevansathi.com,ankita.g@jeevansathi.com,manoj.rana@naukri.com,nitish.sharma@jeevansathi.com",
                            "VDUploadFromTable"=>"ankita.g@jeevansathi.com,manoj.rana@naukri.com",
                            "AgentNotifications"=>"ankita.g@jeevansathi.com,vibhor.garg@jeevansathi.com",
                            "test"=>"nsitankita@gmail.com,nitish.sharma@jeevansathi.com"
                          );            
    
    $emailTo=$emailAlertArray[$to];
    $subject="CRM Error";
    $message=$message.".....".date('d-m-Y H:i:s');
    SendMail::send_email($emailTo,$message,$subject);           
  }
}
?>
