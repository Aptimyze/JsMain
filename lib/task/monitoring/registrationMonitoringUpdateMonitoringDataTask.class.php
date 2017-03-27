<?php
/**
 * Description of registrationMonitoringUpdateMonitorningDataTask
 * Cron job for computing profile completeion data and updating in REGISTER.REGISTRATION_MONITORING_DATA
 * @author Sanyam Chopra
 * @created 25th Feb 2016
 */

class registrationMonitoringUpdateMonitoringDataTask extends sfBaseTask
{
	/*
   * To Show Debug Information on console
   */
  private $m_bDebugInfo = false;

  protected function configure()
  {
    $this->namespace        = 'monitoring';
    $this->name             = 'RegistrationMonitoringUpdateMonitoringData';
    $this->briefDescription = 'To monitor registration number in every week on every channel and save the monitoring data in REGISTER.REGISTRATION_MONITORING_DATA';
    $this->detailedDescription = <<<EOF
The [monitoring:RegistrationMonitoringUpdateMonitoringData|INFO] task runs in every week and computes Average, Min, Max count of data of the last week for the completed registration per hour for each channel and stores the data computed in (TABLE).
Call it with:

  [php symfony monitoring:RegistrationMonitoringUpdateMonitoringData|INFO]
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

    $monitoringObj = new REGISTER_REGISTRATION_MONITORING_DATA('newjs_master');
    $response = $monitoringObj->insertMonitoringData($arrResult);
       
    //Get Script Statistics
    $this->endScript($st_Time);
  }

  /*
   * getMonitorData
   */
  private function getMonitorData(){
    try{
      $masterConnection = new REG_TRACK_CHANNEL('newjs_master');
      return $masterConnection->getRegistrationMonitoringData();
    } catch (Exception $ex) {
      //Log this error 
      return false;
    }
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