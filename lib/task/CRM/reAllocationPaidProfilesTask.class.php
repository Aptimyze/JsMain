<?php

class reAllocationPaidProfilesTask extends sfBaseTask
{
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
	    // 

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'reAllocation';
		$this->name             = 'paidProfiles';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [reAllocation:paidProfiles|INFO] task does things.
Call it with:
[php symfony reAllocation:paidProfiles|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
    	// SET BASIC CONFIGURATION
		sfContext::createInstance($this->configuration);
		$processObj=new PROCESS();
		$agentBucketHandlerObj=new AgentBucketHandler();
		
		// setting dates for picking up paid profiles 
		//$start_dt = date("Y-m-d", time() - 60 * 60 * 24)." 00:00:00";
		//$end_dt = date("Y-m-d", time() - 60 * 60 * 24)." 23:59:59";

                $start_dt = date("Y-m-d H:i:s", time() - 60 * 60 * 27);
		$end_dt = date("Y-m-d H:i:s", time() - 60 * 60 * 2);

		$processObj->setStartDate($start_dt);
		$processObj->setEndDate($end_dt);
		$processObj->setMethod("REALLOCATION");
		$processObj->setSubMethod("CENTRAL_RENEWAL");

		// Using DeAllocation process to deallocate profiles
		$processObj->setProcessName("DeAllocation");
		$agentBucketHandlerObj->deallocate($processObj);
		echo "DeAllocation of Central renewal Profiles having agents LR,ExcFld privilege Completed !!! \n";
	}
}
