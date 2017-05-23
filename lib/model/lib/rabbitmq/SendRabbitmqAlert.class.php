<?php

/* This class sends rabbitmq error to rabbitmq error log file and alert mail to destination email-address. */
class SendRabbitmqAlert
{
 
/**
* 
* Function for sending alert mail to destination email address and logging message in destination log file
* 
* @access public
* @param $message
**/
  public static function sendAlert($message)
  {
    $to = "ankita.g@jeevansathi.com";//more ids to be added
    $subject="Rabbitmq Error";
    $message=$message.".....".date('d-m-Y H:i:s');
    $errorLogPath=JsConstants::$cronDocRoot.'/log/rabbitError.log';
    $val=error_log($message,3,$errorLogPath);
    SendMail::send_email($to,$message,$subject);
  }
}
?>
