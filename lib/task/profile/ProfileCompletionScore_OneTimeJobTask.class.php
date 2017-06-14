<?php
/**
 * Description of ProfileCompletionScore_OneTimeTask
 * Cron job for computing profile completeion score and storing in table
 * <code>
 * To execute : $ symfony profile:ProfileCompletionScore  totalScripts currentScript [lastLoginWithIn]
 * example  $ php symfony profile:ProfileCompletionScore  1 0 lastLoginWithIn="1 days"
 *          $ php symfony profile:ProfileCompletionScore  1 0 lastloginWithIn="2 days"
 * </code>
 * @author Kunal Verma
 * @created 1st April 2015
 */
ini_set('memory_limit','128M');
class ProfileCompletionScore_OneTimeJobTask extends sfBaseTask
{
    /**
	 * Declaration of Member Variables
	 */ 
    /*
     * MySql Object For Slave Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $m_objNewJs_Jprofile_Slave =null;
    
    /*
     * Constant Value of Last Login Since
     * @access Const
     */
    const LAST_LOGIN_SINCE='6 months';
    
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
			new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'TotalScript'),
			new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'CurrentScript'),
                        new sfCommandArgument('lastLoginWithIn', sfCommandArgument::OPTIONAL, 'LastLoginWithIn'),
                        new sfCommandArgument('updatePcs', sfCommandArgument::OPTIONAL, 'updateScore'),
		));
        
        $this->namespace        = 'profile';
        $this->name             = 'ProfileCompletionScore';
        $this->briefDescription = 'Task for computing and storing profile completion score of all set those profiles whose last login date lies with-in 6 months or time specfied by optional paramter';
        $this->detailedDescription = <<<EOF
        The [ProfileCompletionScore|INFO] task does things.
        Call it with:

        [php symfony profile:ProfileCompletionScore totalScripts currentScript [lastloginWithIn] [updatePcs]]
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
        $this->m_objNewJs_Jprofile_Slave  =  new PROFILE_PROFILE_COMPLETION_SCORE('newjs_slave');
    }
    
    /*
     * getProfiles
     * Function to get all profiles which are logged in last 6 months 
     * @access private
     * @retrun void
     */
    private function getProfiles($arguments)
    {
        $totalScripts = $arguments["totalScripts"]; // total no of scripts
		$currentScript = $arguments["currentScript"]; // current script number
        $lastLoginWithIn = $arguments["lastLoginWithIn"]; // last login with in [time specified]
        
        if(!$lastLoginWithIn || !strlen($lastLoginWithIn))
        {
            $lastLoginWithIn  = self::LAST_LOGIN_SINCE;
        }
        
        if(null === $this->m_objNewJs_Jprofile_Slave)
        {
            if($this->m_bDebugInfo)
                $this->logSection('Error: ', 'Not able to establish connection with newjs_slave');
            return false;
        }
        
        /*
         * Where Condition for reteriveing set of profiles
         */
        $time = new DateTime();
        $time->sub(date_interval_create_from_date_string($lastLoginWithIn));
        
        $whereCndArray= array('LAST_LOGIN_DT'=>$time->format('Y-m-d'));
        if($this->m_bDebugInfo)
        {
            $this->logSection('DebugInfo: Where Cond on last login date', $whereCndArray['LAST_LOGIN_DT']);
        }
        
        /*
         * Get list of all profiles whose score are not stored on db
         */
        $this->m_arrProfiles = $this->m_objNewJs_Jprofile_Slave->getUncomputedProfiles($totalScripts,$currentScript,$lastLoginWithIn,self::LIMIT_PROFILES);
        
        if($this->m_bDebugInfo)
        {
            $this->logSection('DebugInfo: Counts of profiles reterieved : ',count($this->m_arrProfiles));
        }
    }   
    
    /*
     * getProfiles
     * Function to get all profiles which are logged in last 6 months 
     * @access private
     * @retrun void
     */
    private function getProfilesFromPcs($arguments)
    {
        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        if(!$lastLoginWithIn || !strlen($lastLoginWithIn))
        {
            $lastLoginWithIn  = self::LAST_LOGIN_SINCE;
        }
        
        if(null === $this->m_objNewJs_Jprofile_Slave)
        {
            if($this->m_bDebugInfo)
                $this->logSection('Error: ', 'Not able to establish connection with newjs_slave');
            return false;
        }
        
        $loginDate = date('Y-m-d',strtotime('-12 month'));
        
        /*
         * Get list of all profiles whose score are not stored on db
         */
        $this->m_arrProfiles = $this->m_objNewJs_Jprofile_Slave->getPofilesToBeUpdated($loginDate,$totalScripts,$currentScript);
        
        if($this->m_bDebugInfo)
        {
            $this->logSection('DebugInfo: Counts of profiles reterieved : ',count($this->m_arrProfiles));
        }
    }   
    
    /*
     * Function to compute profile Completion score present in m_arrProfiles array
     * @access private
     * @return void
     */
    private function computeProfileCompletionScore()
    {
        if(0 === count($this->m_arrProfiles))
        {
            if($this->m_bDebugInfo)
                $this->logSection('DebugInfo: ', 'No Profile exists');
            return ;
        }
        
        $itr = count($this->m_arrProfiles);
                
        foreach($this->m_arrProfiles as $key=>$profileInfo)
        {
            try{
                $iProfileId = intval($profileInfo['PROFILEID']);
                $completionObj = ProfileCompletionFactory::getInstance("API",null,$iProfileId);
                $score = $completionObj->updateProfileCompletionScore();
                if($this->m_bDebugInfo)
                {
                    $debugInfo = 'DebugInfo : ProfileId -> '.$iProfileId.' and Score -> '.$score;
                    $this->logSection($debugInfo);
                }
                unset($completionObj); 
            } catch (Exception $ex) {
                if($this->m_bDebugInfo)
                {
                    $this->logSection('DebugInfo: ', 'Exception occurred');
                    var_dump($ex);
                }
                unset($completionObj); 
            }
        }
            
    }    
    
    /*
     * Main execute function of task
     */
    protected function execute($arguments = array(), $options = array())
    {   
        
        $st_Time = microtime(TRUE);
        
        //Init Connection        
        $this->initConnection();
        
        if($arguments["updatePcs"])
        //get profiles which have their score and needs to be updated
            $this->getProfilesFromPcs($arguments);
        else
        //Get Profiles from JPROFILE which do not have score completed
            $this->getProfiles($arguments);
        
        //Compute Score
        $this->computeProfileCompletionScore();
        
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