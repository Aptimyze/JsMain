<?php

/* This task is used to track new MatchAlertLogTempTracking
 *@author : Akash Kumar
 *created on : 27 Feb 2015 
 */

class MatchAlertLogTempTrackingTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'MatchAlertLogTempTracking';
    $this->briefDescription = 'new matchalert mailer Tracking';
    $this->detailedDescription = <<<EOF
      The task send new matchalert mailer .
      Call it with:

      [php symfony mailer:MatchAlertLogTempTracking] 
EOF;
  }
        protected function execute($arguments = array(), $options = array())
        { 
              $newMatchAlertTrackingObject = new matchalerts_LOG_TEMP();           // Object of library class SpamControl to check and INSERT or UPDATE Database for the number of emails sent by different service providers 
              $count = $newMatchAlertTrackingObject->getMatchAlertCount();
              
              if(date("H")>="7" && $count==0){
                      $mailContent = "ALERT-New Match Alert Task failed<br>Present Count=>0";
                      // ALERT
                      SendMail::send_email("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,akashkumardce@gmail.com,lavesh.rawat@jeevansathi.com",$mailContent,"Azkaban Crons Report ".date('y-m-d h:i:s'));
                      include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
                      $mobileArr         = array("9818424749","9873639543","9716918347");
                      $date = date("Y-m-d h");
                      $message        = "Mysql Error Count have reached logTempMatchalert $date within 5 minutes";
                      $from           = "JSSRVR";
                      $profileid      = "144111";

                      foreach($mobileArr AS $key=>$mobile){
                              $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
                      }
              }

             if($count!=0){
                      $myFile = sfConfig::get("sf_web_dir")."/uploads/SearchLogs/newMatchLogTempDetect.txt";

                      $fh = fopen($myFile, 'r');
                      $record = fgets($fh);
                      fclose($fh);

                      $fh = fopen($myFile, 'w');
                      fwrite($fh, $count);
                      fclose($fh);

                      if($count==$record && $count<600000)	
                      {       $from="alert@jeevansathi.com";
                              mail("lavesh.rawat@gmail.com,reshurajput@gmail.com,bhavanakadwal@gmail.com","SAME COUNT LOG_TEMP","matchalerts.LOG_TEMP- SAME COUNT<br>Last Count-".$count,"From: $from\n");
                              // ALERT
                              
                              include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
			      $mobileArr         = array("9818424749","9873639543","9650350387");
			      $date = date("Y-m-d h");
			      $message        = "Mysql Error Count have reached logTempMatchalert $date within 5 minutes";
			      $from           = "JSSRVR";
			      $profileid      = "144111";

			      foreach($mobileArr AS $key=>$mobile){
				      $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
			      }
                      }
              } 
        }

}

