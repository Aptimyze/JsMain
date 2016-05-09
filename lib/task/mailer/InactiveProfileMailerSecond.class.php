<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the inactive mail to the users
 */
 class InactiveProfileMailerSecondTask extends sfBaseTask
{
	private $profilemail=array();
	private $OneTimeInterval = '360';
	private $Interval = '90';
	protected function configure()
  	{
                $this->addArguments(array(
                    new sfCommandArgument('oneTime', sfCommandArgument::REQUIRED, 'My argument')
		));
                
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'inactive';
	    $this->name             = 'NEWJS_INACTIVE_PROFILES';
	    $this->briefDescription = 'send the mail to alert jeevansathi users for inactive cases';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony inactive:NEWJS_INACTIVE_PROFILES_SHORTLY oneTime] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
				if(!sfContext::hasInstance())
	                sfContext::createInstance($this->configuration);
	            $IncompleteMasterobj = new NEWJS_INACTIVE_PROFILES("newjs_master");
	            $IncompleteSlaveobj = new NEWJS_INACTIVE_PROFILES("newjs_slave");
	            $IncompleteMasterobj->EmptyIncomplete();
	         if($arguments["oneTime"]=="0"){
	           		$profilemail=$IncompleteSlaveobj->ProfilesInactivatedShortly($this->Interval);
	           }
	           else
	           {
	           		$profilemail=$IncompleteSlaveobj->ProfilesInactivatedOneTime($this->Interval,$this->OneTimeInterval);
	           }
	           $profileMailArray=array_chunk($profilemail, 2);
	           
	            foreach ($profileMailArray as $key => $value) {
	            	//print_r($value);die;
	            		$IncompleteMasterobj->InsertStatusAlert($value);
	            	}
	            	
	            }
	        
	}

