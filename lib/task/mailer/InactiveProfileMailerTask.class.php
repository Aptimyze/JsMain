<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the inactive mail to the users
 */
 class InactiveProfileMailerTask extends sfBaseTask
{
	private $profilemail=array();
	private $OneTimeInterval = '360';
	private $Interval = '85';
	private $Interval1 = '15';
	private $chunk = '100';
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
	  [php symfony inactive:NEWJS_INACTIVE_PROFILES oneTime] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
			ini_set("memory_limit","512M");
				if(!sfContext::hasInstance())
	                sfContext::createInstance($this->configuration);
	            $IncompleteMasterobj = new NEWJS_INACTIVE_PROFILES("newjs_masterDDL");
	            $IncompleteSlaveobj = new NEWJS_INACTIVE_PROFILES("newjs_slave");
	            $IncompleteMasterobj->EmptyIncomplete();
	         if($arguments["oneTime"]=="0"){
	           		$profilemail1=$IncompleteSlaveobj->ProfilesInactivated($this->Interval);

	           		 $profileMailArray=array_chunk($profilemail1, $this->chunk);
	           		unset($profilemail1);
	           		if($profileMailArray)
	           		{
	           			
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval);
			            	}
			            }
			        /*   		$this->Interval=$this->Interval+30;

			           		$profilemail2=$IncompleteSlaveobj->ProfilesInactivated($this->Interval);
			           		 $profileMailArray=array_chunk($profilemail2, $this->chunk);
			          unset($profilemail2);
			         if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval);
			            	}
			            }
			           		$this->Interval=$this->Interval+25;
			           		$profilemail3=$IncompleteSlaveobj->ProfilesInactivated($this->Interval);
			           		 $profileMailArray=array_chunk($profilemail3, $this->chunk);
			           		unset($profilemail3);
			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval);
			            	}
			           }
			           */
			           		$profilemail4=$IncompleteSlaveobj->ProfilesInactivated($this->Interval1);
			           		 $profileMailArray=array_chunk($profilemail4, $this->chunk);
			           		unset($profilemail4);
			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	//print_r($value);die;
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval1);
			            	}
			            }
			           		$this->Interval1=$this->Interval1+15;
			           		$profilemail5=$IncompleteSlaveobj->ProfilesInactivated($this->Interval1);
			           		 $profileMailArray=array_chunk($profilemail5, $this->chunk);
			           		unset($profilemail5);
			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval1);
			            	}
			            }
			           		$this->Interval1=$this->Interval1+15;
			           		$profilemail6=$IncompleteSlaveobj->ProfilesInactivated($this->Interval1);
			           		 $profileMailArray=array_chunk($profilemail6, $this->chunk);
			           		unset($profilemail6);
			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval1);
			            	}
			        }
			           		$this->Interval1=$this->Interval1+15;
			           		$profilemail7=$IncompleteSlaveobj->ProfilesInactivated($this->Interval1);
			           		 $profileMailArray=array_chunk($profilemail7, $this->chunk);
			           		unset($profilemail7);

			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval1);
			            	}
			        }
			           		$this->Interval1=$this->Interval1+15;
			           		$profilemail8=$IncompleteSlaveobj->ProfilesInactivated($this->Interval1);
			           		 $profileMailArray=array_chunk($profilemail8, $this->chunk);
			           		unset($profilemail8);
			        if($profileMailArray)
	           		{
			            foreach ($profileMailArray as $key => $value) {
			            	
			            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval1);
			            	}
			        }

	           }
	           else
	           {
	           		$profilemail=$IncompleteSlaveobj->ProfilesInactivatedOneTime($this->Interval,$this->OneTimeInterval);
	           		 $profileMailArray=array_chunk($profilemail, $this->chunk);
	           
	            foreach ($profileMailArray as $key => $value) {
	            
	            		$IncompleteMasterobj->InsertStatusAlert($value,$this->Interval);
	            	}
	           }
	           unset($profileMailArray);
	         
		}


}

