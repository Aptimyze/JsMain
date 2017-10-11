<?php
/**
 * Description of ProfileCompletionScore_OneTimeTask
 * Cron job for computing profile completeion score and storing in table
 * <code>
 * To execute : $ symfony monitoring:RegistrationMonitor"
 * </code>
 * @author Kunal Verma
 * @created 20th Jan 2016
 */
class RegistrationMonitorTask extends sfBaseTask
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
  const EMAIL_LIST = "ankitshukla125@gmail.com,eshajain88@gmail.com";
  
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = 'monitoring';
    $this->name             = 'RegistrationMonitor';
    $this->briefDescription = 'To monitor registration number in every hour and looks for count of complete registered user in last two and fire email  & sms as per threshold given in registrationThreshold.csv';
    $this->detailedDescription = <<<EOF
The [monitoring:RegistrationMonitor|INFO] task runs in every hour and looks for count of complete registered user in last two and fire email  & sms as per threshold given in registrationThreshold.csv
Call it with:

  [php symfony monitoring:RegistrationMonitor|INFO]
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
      $this->notify("No complete registration in previous two hour, current time:".$date );
      if($this->m_bDebugInfo)
        $this->logSection('No complete registration happens'); // No Profile Getting registerated
      return;
    }
    
    //Read Threshold File
    $arrCsvData = array_map('str_getcsv', file(JsConstants::$cronDocRoot."//lib//task//monitoring//registrationThreshold.csv"));
    
    $mailBody = "";
    
    //Loop the result array
    foreach($arrResult as $k=>$rowData){
      $hourIndex = $rowData['HOUR'];
      $countOfCompleteRegistration = $rowData['COUNT'];
      $dateOfRegistration = $rowData["DT"];
      
      $arrThreshold = $arrCsvData[$hourIndex + 1];
      $minThreshold = intval($arrThreshold[2/*MIN*/]);
      
      if( $countOfCompleteRegistration < $minThreshold && 
          ($minThreshold - $countOfCompleteRegistration) > (self::PERCETAGE_DEVIATION * $minThreshold)
        ){ 
        // Less then Min threshold && 30% Less then minimun threshold
        $mailBody .= "For the hour:".$hourIndex." on date:".$dateOfRegistration." number of complete registrations reduced by more than 30%\n";
      }
    }
    
    //Send Email & SMS
    if(strlen($mailBody)){
      $this->notify( "Hi,\n\n".$mailBody);
    }
    
    //Get Script Statistics
    $this->endScript($st_Time);
  }
  
  /*
   * getMonitorData
   */
   
  private function getMonitorData(){
    try{
      $slaveConnection = new JPROFILE('newjs_slave');
      return $slaveConnection->registrationMonitorQuery();
    } catch (Exception $ex) {
      //Log this error 
      return false;
    }
  }
  
  /*
   * Notify 
   */
  private function notify($mailBody){
    
    //Send Email
    SendMail::send_email(self::EMAIL_LIST, $mailBody,"Complete Registration Number Reduced by more than 30%");
    
//    include(JsConstants::$docRoot . "/commonFiles/sms_inc.php");
//    $arrMob = array('9711818214','9953457479');
//    $message = "Mysql Error Count have reached Registration reduced by 30% within 5 minutes";//substr($mailBody, 0,158);
//    $from = "JSSRVR";
//    $profileid = "144111";
//    foreach ($arrMob as $mobile1){
//      var_dump($mobile1." ".$message);
//      $smsState = send_sms($message, $from, $mobile1, $profileid, '', 'Y');
//    }
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
