<?php
/**
 * Description of ImproveProfileScoreTask
 * Cron job for send an Email asking the user to fill key details about his/her profile. The Email should be fired 1 day, 7 days, 14 days, 21 days and 30 days after registration if profile completion score is less than 60%.
 * <code>
 * To execute : $ symfony profile:ImproveProfileScoreTask  "1 days" 
 * example  $ php symfony profile:ImproveProfileScoreTask  
 * </code>
 * @author Kunal Verma
 * @created 31st July 2015
 */
ini_set('memory_limit','128M');
class ImproveProfileScoreTask extends sfBaseTask
{
    /**
	 * Declaration of Member Variables
	 */ 
    /*
     * MySql Object For Slave Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $m_SlaveObj =null;
    
    
    /*
     * MySql Object For master dll Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $m_MasterDDLObj =null;
    
    /*
     * MySql Object For Slave Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $m_MasterObj =null;
        
    /*
     * When to Update Status
     * @access Const
     */
    const UPDATE_STATUS_THRESHOLD = 500;
    
    /*
     * Score less than threshold then fire the mailer
     * @access Const
     */
    const SCORE_THRESHOLD = 60;
    
    /*
     * Array of profiles 
     * @access private
     * @var Array
     */
    private $m_arrProfiles = null;
    
    /*
     * Debug Info
     * @access private
     * @var Boolean
     */
    private $m_bDebugInfo = false;
    /**
	 * Definition of Member functions
	 */ 
    
    /*
     * Configure function 
     */
    protected function configure()
    { 
      //Command line arguements
      $this->addArguments(array(
        new sfCommandArgument('verifiedBy', sfCommandArgument::REQUIRED, 'verifiedBy'),
      ));
      
      $this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
      $this->namespace        = 'profile';
      $this->name             = 'ImproveProfileScore';
      $this->briefDescription = 'Task for send an Email asking the user to fill key details about his/her profile. The Email should be fired 1 day, 7 days, 14 days, 21 days and 30 days after registration if profile completion score is less than 60%.';
      $this->detailedDescription = <<<EOF
      The [ImproveProfileScore|INFO] task does things.
        Sends an Email asking the user to fill key details about his/her profile. The Email should be fired 1 day, 7 days, 14 days, 21 days and 30 days after registration if profile completion score is less than 60%.
      Call it with:
        
      [php symfony profile:ImproveProfileScore "verifiedBy"]]
        verifiedBy - A string specifying how amny days ago to start 
                          like 1) '1 days' means consider from yesterday
                               2) '0 days' means consider from today
EOF;
    }
    /*
     * InitConnection , initalize all connections
     * @access private
     * @return void
     * @param void
     */
    private function initConnection()
    {
      //NewJs_Slave Connection
      $this->m_SlaveObj  =  new PROFILE_PROFILE_COMPLETION_SCORE('newjs_slave');
      $this->m_MasterObj  =  new PROFILE_PROFILE_COMPLETION_SCORE();
      $this->m_MasterDDLObj  =  new PROFILE_PROFILE_COMPLETION_SCORE('newjs_master');
      
    }
    
    /*
     * getProfiles
     * Function to get all profiles which are logged in last 6 months 
     * @access private
     * @retrun void
     */
    private function getProfiles($arguments)
    {
      $szVerifiedBy = $arguments["verifiedBy"]; // total no of scripts
    
      if(null === $this->m_SlaveObj)
      {
          if($this->m_bDebugInfo)
              $this->logSection('Error: ', 'Not able to establish connection with newjs_slave');
          return false;
      }

      /*
       * Where Condition for reteriveing set of profiles
       */
      $time = new DateTime();
      $time->sub(date_interval_create_from_date_string($szVerifiedBy));
      
      $time1days = $time->format('Y-m-d');
      
      $time->sub(date_interval_create_from_date_string("7 days"));
      $time7days = $time->format('Y-m-d');
      
      $time->sub(date_interval_create_from_date_string("7 days"));
      $time14days = $time->format('Y-m-d');
      
      $time->sub(date_interval_create_from_date_string("7 days"));
      $time21days = $time->format('Y-m-d');
      
      $time->sub(date_interval_create_from_date_string("9 days"));
      $time30days = $time->format('Y-m-d');
      
      $whereCndArray= array('TIME_1'=>$time1days,'TIME_7'=>$time7days,'TIME_14'=>$time14days,'TIME_21'=>$time21days,'TIME_30'=>$time30days,'SCORE'=>self::SCORE_THRESHOLD);
      if($this->m_bDebugInfo)
      {
          $this->logSection('DebugInfo: Where Cond on VERIFY_ACTIVATED_DT', $whereCndArray['TIME_1']);
      }
      
      /*
       * Get list of all profiles
       */
      try{
        if(false === $this->m_MasterObj->isImproveScoreTableExist()){
          $this->m_MasterDDLObj->createImproveScoreTable();
        }

        //Get Profile Id in Improve Score Table From Slave
        $this->m_arrProfiles = $this->m_SlaveObj->getVerifiedProfiles($whereCndArray);

        //Store Records in Master 
        if(count($this->m_arrProfiles)){
          $this->m_MasterObj->storeVerifiedProfiles($this->m_arrProfiles);

          //Get all records whose status are set to 'N'
          $this->m_arrProfiles = $this->m_MasterObj->getImproveScoreRecords();
        }  
      } catch (Exception $ex) {
        //Send Mail
        $subject = "Improve Profile Score Mailer : Some issue in retrieving profiles";
        $szMailBody = "Where Cnd Araray : ";
        $szMailBody .= "\n\n'".print_r($whereCndArray,true)."'";

        SendMail::send_email("kunal.test02@gmail.com",$szMailBody,$subject);
        
        //Log on shell
        if($this->m_bDebugInfo){
          $this->logSection('DebugInfo: Exception while retrieving profiles');
        }
        
        $this->m_arrProfiles = array();
      }
      

      if($this->m_bDebugInfo)
      {
          $this->logSection('DebugInfo: Counts of profiles reterieved : ',count($this->m_arrProfiles));
      }
      unset($this->m_SlaveObj);
    }   
    
    /*
     * Function to send Improve Score Mailer
     * @access private
     * @return void
     */
    private function sendImproveScoreMailer()
    {
      if(0 === count($this->m_arrProfiles))
      {
          if($this->m_bDebugInfo)
              $this->logSection('DebugInfo: ', 'No Profile exists');
          return ;
      }

      $itr = count($this->m_arrProfiles);
      $arrUpdateStatus = array();
      foreach($this->m_arrProfiles as $key=>$profileInfo)
      {
        try{
          $iProfileId = intval($profileInfo['PROFILEID']);
          $completionObj = ProfileCompletionFactory::getInstance("API",null,$iProfileId);

          $score = $completionObj->improveScoreMailer($profileInfo['SCORE']);
          $arrUpdateStatus[] = $profileInfo['PROFILEID'];
          if($this->m_bDebugInfo)
          {
              $debugInfo = ' ProfileId -> '.$iProfileId.' and Score -> '.$score;
              $this->logSection('DebugInfo :',$debugInfo);
          }
          
          if(count($arrUpdateStatus) === self::UPDATE_STATUS_THRESHOLD){
            $this->m_MasterObj->setStatusImproveScoreTable($arrUpdateStatus);
            unset($arrUpdateStatus);
            $arrUpdateStatus = array();
          }
          
          unset($completionObj); 
        } catch (Exception $ex) {
          if($this->m_bDebugInfo)
          {
              $this->logSection('DebugInfo: ', 'Exception occurred');
              var_dump($ex);
          }
          $this->m_MasterObj->setStatusImproveScoreTable($arrUpdateStatus);
          unset($this->m_MasterObj);
          unset($completionObj); 
        }
      }
      
      if(count($arrUpdateStatus)){
        $this->m_MasterObj->setStatusImproveScoreTable($arrUpdateStatus);
      }
      
      if(count($this->m_arrProfiles)){
        //Drop table if all goes well
        $this->m_MasterDDLObj->dropImproveScoreTable();
      }
      unset($this->m_MasterObj);
      unset($this->m_MasterDDLObj);
    }    
    
    /*
     * Main execute function of task
     */
    protected function execute($arguments = array(), $options = array())
    { 
      if(!sfContext::hasInstance())
	      sfContext::createInstance($this->configuration);
      $st_Time = microtime(TRUE);
      //Init Connection        
      $this->initConnection();

      //Get Profiles from Slave
      $this->getProfiles($arguments);
      
      //Send improve score Mailer
      $this->sendImproveScoreMailer();

      //Get Script Statistics
      $this->endScript($st_Time);
    }

    /*
     * End script 
     * To note statistic of memory and time usages
     * @param : $st_Time [Start Time]
     * @return void
     */
    private function endScript($st_Time='')
    {
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

        if ($var < 1024)
            $mem =  $var." bytes";
        elseif ($var < 1048576)
            $mem =  round($var/1024,2)." kilobytes";
        else
            $mem = round($var/1048576,2)." megabytes";
        
        if($this->m_bDebugInfo)
        {
            $this->logSection('Memory usages : ', $mem);
            $this->logSection('Time taken : ', $end_time - $st_Time);
        }
    }
}
?>
