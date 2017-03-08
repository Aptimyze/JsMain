<?php
/**
 * Description of FindZombieProfileTask
 * Cron job for finding those profiles which are marked delete in JPROFILE 
 * but delete process has not been executed for them.
 * <code>
 * To execute : $ [php symfony Delete:FindZombieProfile [--Delete_Before[="..."]] [--application[="..."]] Delete_After]
 * example  
 $php symfony Delete:FindZombieProfile "2016-02-26 00:00:00" 
 $php symfony Delete:FindZombieProfile "2016-02-26 00:00:00" 
 $php symfony Delete:FindZombieProfile "2016-02-26 00:00:00" --Delete_Before="2016-02-28 00:00:00"
 
 * </code>
 * @author Kunal Verma
 * @created 1st March 2017
 */
ini_set('memory_limit','128M');
class FindZombieProfileTask extends sfBaseTask
{
    /**
	 * Declaration of Member Variables
	 */ 
    /*
     * MySql Object For Slave Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $objJProfileSlave =null;
    
    /*
     * Limit Count of Profile
     * If 0 is specifed, then no limit on profiles
     * @access Const
     */
    const LIMIT_PROFILES = 0;
    
    /*
     * Array of profiles 
     * @access private
     * @var Array
     */
    private $arrProfiles = null;
    
    /*
     * Debug Info
     * @access private
     * @var Boolean
     */
    private $bDebugInfo = true;
    
    /**
     *
     * @access private
     * @var DeleteProfile class obhect
     */
    private $objDeleteProfile = null;
    
    /**
     *
     * @access private
     * @var DeleteProfile class object
     */
    private $objProfileDeleteLogs = null;
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
            new sfCommandArgument('Delete_After', sfCommandArgument::OPTIONAL, 'Find Profiles which are deleted after date'),
        ));
        $this->addOptions(array(
            new sfCommandOption('Delete_Before', null, sfCommandOption::PARAMETER_OPTIONAL, 'Find Profiles which are deleted after and deleted before date'),
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
         ));


        $this->namespace        = 'Delete';
        $this->name             = 'FindZombieProfile';
        $this->briefDescription = 'Cron job for finding those profiles which are marked delete in JPROFILE but delete process has not been executed for them.';
        $this->detailedDescription = <<<EOF
        The [Delete:FindZombieProfile|INFO] task does things.
            --Delete_After = "2017-01-01 00:00:00"
            --Delete_Before = "2017-01-01 00:00:00"

        Call it with:

        [php symfony Delete:FindZombieProfile [--Delete_Before[="..."]] [--application[="..."]] Delete_After]
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
        $this->objJProfileSlave  =  JPROFILE::getInstance('newjs_slave');
        
        $this->objDeleteProfile = new DeleteProfile;
        
        $this->objProfileDeleteLogs = new PROFILE_DELETE_LOGS("newjs_slave");
    }
    
    
    /*
     * Main execute function of task
     */
    protected function execute($arguments = array(), $options = array())
    {  
        if(!sfContext::hasInstance()){
          sfContext::createInstance($this->configuration);
        }
        
        $st_Time = microtime(TRUE);
        
        //Init Connection        
        $this->initConnection();
        
        //GetProfiles
        $this->getProfiles($arguments, $options);
        
        //Enqueue in DeleteRetrieveCron
        $this->enqueue();

        //Get Script Statistics
        $this->endScript($st_Time);
    }

    /**
     *
     */
    private function getProfiles($arguments, $options)
    {
        $today = date('Y-m-d H:i:s');

        //$gtDate = strtotime($arguments["Delete_After"]);
        $gtDate = DateTime::createFromFormat('Y-m-d H:i:s', $arguments["Delete_After"]);
        
        if(false === $gtDate) {
            $time = new DateTime();
            $time->sub(date_interval_create_from_date_string("2 days"));
            $gtDate = $time->format('Y-m-d H:i:s');
        }
        else {
            $time = new DateTime($arguments["Delete_After"]);
            $gtDate = $time->format('Y-m-d H:i:s');
        }
        
        if($this->bDebugInfo) {
            $this->logSection('Info: ', "Profile MOD_DT Greater then : $gtDate");
        }

        if(isset($options["Delete_Before"])) {
            
            $ltDate = DateTime::createFromFormat('Y-m-d H:i:s', $options["Delete_Before"]);

            if(false === $ltDate) {
                $time->add(date_interval_create_from_date_string("2 days"));
                $ltDate = $time->format('Y-m-d H:i:s');
            } else {
                $time = new DateTime($options["Delete_Before"]);
                $ltDate = $time->format('Y-m-d H:i:s');
            }

            if($this->bDebugInfo) {
                $this->logSection('Info: ', "Profile MOD_DT Less then : $ltDate");
            }
        }

        $this->arrProfiles = $this->objJProfileSlave->getZombieProfiles($gtDate, self::LIMIT_PROFILES, $ltDate);
        
        if($this->bDebugInfo) {
            $num = count($this->arrProfiles);
            $this->logSection('Info: ', "Number of profiles : $num");
        }
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
        
        if($this->bDebugInfo)
        {
            $this->logSection('Memory usages : ', $mem);
            $this->logSection('Time taken : ', $end_time - $st_Time);
        }
    }

    /**
     *
     */
    private function checkTables()
    {
        if(is_array($this->arrProfiles) && 0 === count($this->arrProfiles)) {
            return;
        }

        //TODO : All Checks in Table
        // newjs.PROFILE_DEL_REASON
        //
    }

    /**
     *   Enqueue
     */
    private function enqueue()
    {

        if(is_array($this->arrProfiles) && 0 === count($this->arrProfiles)) {
            return ;
        }

        foreach($this->arrProfiles as $profiles) {
            $pid = $profiles['PROFILEID'];
            
            //Run Delete Process
            $result = $this->runDeleteProcess($pid);
            
            if($result) {
              //As rest process has been taken care in Class DeleteProfile.class.php
              continue ;
            }
            
            //Remove From Search
            $this->callDeleteCronBasedOnId($pid);

            //Add In Queue
            $producerObj=new Producer();
            if($producerObj->getRabbitMQServerConnected())
            {
                $sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$pid)), 'redeliveryCount'=>0 );
                $producerObj->sendMessage($sendMailData);
            }
            else
            {
                $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
                $cmd = JsConstants::$php5path." -q ".$path;
                passthru($cmd);
            }

        }
    }
    
    /**
     * 
     * @param type $profileid
     * @param type $background
     */
    private function callDeleteCronBasedOnId($profileid,$background='Y')
    {
        if($profileid=='EXPORT')
            $command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing EXPORT";
        elseif($profileid=='DELTA')
            $command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing DELTA";
        else
            $command = JsConstants::$php5path." ".JsConstants::$cronDocRoot."/symfony cron:SearchIndexing PROFILEID ".$profileid;
        //$command.= " >> /var/www/htmlrevamp/ser6/branches/milestoneConfig/cache/l.txt";
        if($background=='Y')
            $command.=" &";
        //echo  $command;echo "\n\n";
        exec($command);
        
    }
    
    /**
     * 
     * @param type $iProfileID
     */
    private function runDeleteProcess($iProfileID)
    {
      $arrData = $this->objProfileDeleteLogs->findRecord($iProfileID);

      if(false === $arrData) {
        return false;
      }
      
      $arrData = $arrData[0];
      
      $delete_reason = $arrData['DELETE_REASON'];
      $specify_reason = $arrData['SPECIFY_REASON'];
      $username = $arrData['USERNAME'];
      $startTime = $arrData['START_TIME'];
      
      try{
        $this->objDeleteProfile->delete_profile($iProfileID, $delete_reason, $specify_reason, $username, $startTime);
        return true;
      } catch (Exception $ex) {
        if($this->bDebugInfo) {
          $this->logSection("Exception : ", $ex->getTrace() );
          return ;
        }
        $subject = "Find Zombie Profile Task: Error while rerunning delete process";
        $szMailBody = "Profileid of user is : ".$iProfileID;
        $szMailBody .= "\n\n'".print_r($ex->getTrace(),true)."'";
            
        SendMail::send_email("kunal.test02@gmail.com",$szMailBody,$subject);
        return false;
      }
    }
}
?>