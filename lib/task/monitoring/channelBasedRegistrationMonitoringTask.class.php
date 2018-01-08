<?php
/**
 * This cron runs on hourly basis and fetches the count of total number of registrations   *  completed under each channel in the last hour and then compares it with the data       *  collected under REGISTRATION_MONITORING_DATA table and accordingly if the value is     * below a certain given point, it issues a mail and a message.
 * * <code>
 * To execute : $ symfony monitoring:ChannelBasedRegistrationMonitoring"
 * </code>
 * @author Sanyam Chopra
 * @created 24th Feb 2016
 */
class ChannelBasedRegistrationMonitoringTask extends sfBaseTask
{
  /*
   * To Show Debug Information on console
   */
  private $m_bDebugInfo = false;
  
  /*
   * Const variable 
   * PERCETAGE_DEVIATION
   */
  const PERCETAGE_DEVIATION = 0.30;
  
  /*
   * Const variable 
   * EMAIL_LIST : List of email ids
   */
  const EMAIL_LIST = "sanyam1204@gmail.com,ankitshukla125@gmail.com,eshajain88@gmail.com";
  
  protected function configure()
  {

    $this->namespace        = 'monitoring';
    $this->name             = 'ChannelBasedRegistrationMonitoring';
    $this->briefDescription = 'This cron runs on hourly basis to fetch and check count of completed Registrations and accordingly send a mail and a message if the count is below a certain limit';
    $this->detailedDescription = <<<EOF
    The [monitoring:ChannelBasedRegistrationMonitoring|INFO] This cron runs on hourly basis and fetches the count of total number of registrations completed under each channel in the last hour and then compares it with the data collected under REGISTRATION_MONITORING_DATA table and accordingly if the value is below a certain given point, it issues a mail and a message.
    Call it with:

    [php symfony monitoring:ChannelBasedRegistrationMonitoring|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $st_Time = microtime(TRUE);
    // add your code here
    if($this->m_bDebugInfo){
      $this->logSection('Running');
    }
    $arrResult = $this->getMonitorData();
    if($arrResult === false){
      if($this->m_bDebugInfo)
        $this->logSection('issue while reteriving data');
      return ;
    }
    //No Complete 
    if($arrResult === 0){
      
      $date = date("d-m-Y H:i:s");
      $smsMessage="No complete registration in previous hour, current time".$date;
      $mailMessage = $smsMessage;
      $this->notify($mailMessage,$smsMessage);
      if($this->m_bDebugInfo)
        $this->logSection('No complete registration happens'); // No Profile Getting registerated
      return;
    }
    
    $mailBody = "";
    $smsBody = "";
    $hourIndex=$arrResult['0']['HOUR'];
    $monitoringObj = new REGISTER_REGISTRATION_MONITORING_DATA("newjs_master");

    //Loop the result array
    foreach($arrResult as $k=>$rowData){
      $countOfCompleteRegistration = $rowData['COUNT'];
      $dateOfRegistration = $rowData["DT"];
      $channelOfRegistration = $rowData["CHANNEL"];
      $resultData=$monitoringObj->getMonitoredData($hourIndex,$channelOfRegistration);
      $minThreshold=$resultData["0"]["MIN"];

      if( $countOfCompleteRegistration < $minThreshold && 
          ($minThreshold - $countOfCompleteRegistration) > ceil((self::PERCETAGE_DEVIATION * $minThreshold))
        ){ 
        // Less then Min threshold && 30% Less then minimun threshold
        $mailBody .= "Hi,\n\nFor the hour:".$hourIndex." on date:".$dateOfRegistration.", number of complete registrations reduced by more than 30% on ".$channelOfRegistration."\n"."The total number of registrations on ".$channelOfRegistration.", for the hour:".$hourIndex." were ".$countOfCompleteRegistration.", while the minimum expected count for was ".$minThreshold."\n";
        $smsBody.= "For the hour:".$hourIndex." on date:".$dateOfRegistration."\n Complete Registrations:".$countOfCompleteRegistration."\n Channel:".$channelOfRegistration."\n Min accepted registrations:".$minThreshold."\n Reduced by more than 30%";
       
        
      }
    }
    //Send Email & SMS
    if($mailBody!=""){
      $this->notify($mailBody,$smsBody);
    }
    
    
    //Get Script Statistics
    $this->endScript($st_Time);
  }
  
  /*
   * getMonitorData
   */
   
  private function getMonitorData(){
    try{
      $masterConnection = new REG_TRACK_CHANNEL('newjs_master');
      return $masterConnection->getHourlyRegistrationData();
    } catch (Exception $ex) {
      //Log this error 
      return false;
    }
  }
  
  /*
   * Notify 
   */
  private function notify($mailBody,$smsBody){
    
    $xmlData1="";
    //Send Email and SMS
    SendMail::send_email(self::EMAIL_LIST, $mailBody,"Complete Registration Number Reduced by more than 30%");
    $arrMob = array('8800470788','9711818214','9953457479');
    include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
    $smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
    $profileid = "144111";
    foreach($arrMob as $val){
      $xmlData1 = $xmlData1 . $smsVendorObj->generateXml($profileid,$val,$smsBody);
    }
    if($xmlData1){
      $smsVendorObj->send($xmlData1,"transaction");
    }
    unset($xmlData1);
  }
  
  /*
   * End script 
   * To note statistic of memory and time usages
   * @param : $st_Time [Start Time]
   * @return void
   */
  private function endScript($st_Time = '') {
    $end_time = microtime(TRUE);
    $var = memory_get_usage(true);

    if ($var < 1024)
      $mem = $var . " bytes";
    elseif ($var < 1048576)
      $mem = round($var / 1024, 2) . " kilobytes";
    else
      $mem = round($var / 1048576, 2) . " megabytes";

    if ($this->m_bDebugInfo) {
      $this->logSection('Memory usages : ', $mem);
      $this->logSection('Time taken : ', $end_time - $st_Time);
    }
  }

}
