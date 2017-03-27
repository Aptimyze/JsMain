<?php
/**
 * Description of ProfileImporterJobTask
 * Cron job for Importing Active Users
 * <code>
 * To execute : $ symfony chat:ProfileImporter [-ts|--totalScripts[="..."]] [-cs|--currentScript[="..."]] [-date|--lastLoginWithIn[="..."]]
 * example  $ php symfony chat:ProfileImporter --totalScripts=10
 *          $ php symfony chat:ProfileImporter --lastLoginWithIn="2 days"
 *          $ php symfony chat:ProfileImporter --lastLoginWithIn="3 months"
 * </code>
 * @author Kunal Verma
 * @created 18th July 2016
 */
ini_set('memory_limit','128M');
class ProfileImporterJobTask extends sfBaseTask
{
    /**
	 * Declaration of Member Variables
	 */ 
    /**
     * MySql Object For Slave Connection
     * @access private
     * @var Object of Mysql Store Class
     */
    private $objSlaveJProfile = null;
    
    /**
     * Constant Value of Last Login Since
     * @access Const
     */
    const LAST_LOGIN_SINCE='6 months';
    
    /**
     * Limit Count of Profile
     * If 0 is specified, then no limit on profiles
     * @access Const
     */
    const LIMIT_PROFILES = 0;
    
    /**
     * Array of profiles 
     * @access private
     * @var Array
     */
    private $arrProfiles = null;
    
    /**
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
        //Command line options
        $this->addOptions(array(
            new sfCommandOption('totalScripts', 'ts', sfCommandOption::PARAMETER_OPTIONAL, 'TotalScript', 1),
            new sfCommandOption('currentScript', 'cs', sfCommandOption::PARAMETER_OPTIONAL, 'CurrentScript', 0),
            new sfCommandOption('lastLoginWithIn', 'date', sfCommandOption::PARAMETER_OPTIONAL, 'LastLoginWithIn', self::LAST_LOGIN_SINCE),
        ));
        $this->namespace        = 'chat';
        $this->name             = 'ProfileImporter';
        $this->briefDescription = 'Task for importing all active profiles as per last login date lies with-in 6 months or time specified by optional paramter and ACTIVATED as , check help with ./symfony help chat:ProfileImporter.' ;
        $this->detailedDescription = <<<EOF
        The [ProfileImporter|INFO] task does things.
        Call it with:

        [symfony chat:ProfileImporter [-ts|--totalScripts[="..."]] [-cs|--currentScript[="..."]] [-date|--lastLoginWithIn[="..."]]]
EOF;
    }
    /*
     * InitConnection , initialize all connections
     * @access private
     * @return void
     * @param void
     */
    private function initConnection()
    {
        //NewJs_Slave Connection
        $this->objSlaveJProfile = JPROFILE::getInstance('newjs_slave');
    }

    /**
     * @param $arguments
     * @return bool
     */
    private function getProfiles($arguments)
    {
        $totalScripts = intval($arguments["totalScripts"]); // total no of scripts
		$currentScript = intval($arguments["currentScript"]); // current script number
        $lastLoginWithIn = $arguments["lastLoginWithIn"]; // last login with in [time specified]

        if(null === $this->objSlaveJProfile)
        {
            if($this->m_bDebugInfo)
                $this->logSection('Error: ', 'Not able to establish connection with newjs_slave');
            return false;
        }
        
        /*
         * Where Condition for retrieving set of profiles
         */
        $time = new DateTime();
        $time->sub(date_interval_create_from_date_string($lastLoginWithIn));
        
        $whereCndArray= array('LAST_LOGIN_DT'=> CommonUtility::makeTime($time->format('Y-m-d')));
        if($this->m_bDebugInfo)
        {
            $this->logSection('DebugInfo: Where Cond on last login date', $whereCndArray['LAST_LOGIN_DT']);
        }
        
        /*
         * Get list of all profiles who are active and logged in within specified date
         */
        $this->arrProfiles = $this->objSlaveJProfile->getActiveProfiles($totalScripts,$currentScript,$lastLoginWithIn,self::LIMIT_PROFILES);
        
        if($this->m_bDebugInfo)
        {
            $this->logSection('DebugInfo: Counts of profiles retrieved : ',count($this->arrProfiles));
        }
    }   
    

    /**
     * Function to compute profile Completion score present in arrProfiles array
     * @access private
     * @return void
     */
    private function storeProfiles()
    {
        if(0 === count($this->arrProfiles))
        {
            if($this->m_bDebugInfo)
                $this->logSection('DebugInfo: ', 'No Profile exists');
            return ;
        }
        if ($this->m_bDebugInfo) {
            $this->logSection('DebugInfo: ', 'Num of Profile imported :'. count($this->arrProfiles));
        }
        foreach($this->arrProfiles as $key=>$profileInfo)
        {
            try{
                $producerObj = new Producer();
                if ($producerObj->getRabbitMQServerConnected()) {
                    $chatData = array('process' => 'USERCREATION', 'data' => ($profileInfo["PROFILEID"]), 'redeliveryCount' => 0);
                    $producerObj->sendMessage($chatData);
	                if($this->m_bDebugInfo)
	                {
		                $this->logSection('DebugInfo: ', $profileInfo["PROFILEID"].' Inserted In queue');
	                }
                }
	            else
	            {
		            if($this->m_bDebugInfo)
		            {
			            $this->logSection('DebugInfo: ', 'No rabbitmq connection found');
		            }
	            }
            } catch (Exception $ex) {
                if($this->m_bDebugInfo)
                {
                    $this->logSection('DebugInfo: ', 'Exception occurred');
                    var_dump($ex);
                }
                unset($completionObj);
            }
        }
	    unset($producerObj);

    }

    /**
     * Main execute function of task
     * @param array $arguments
     * @param array $options
     * @return void
     */
    protected function execute($arguments = array(), $options = array())
    {   
        
        $st_Time = microtime(TRUE);
        
        //Init Connection        
        $this->initConnection();
        
        //Get Profiles
        $this->getProfiles($options);
        
        //Store Profile
        $this->storeProfiles();
        
        //Get Script Statistics
        $this->endScript($st_Time);
    }

    /**
     * @param string $st_Time
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