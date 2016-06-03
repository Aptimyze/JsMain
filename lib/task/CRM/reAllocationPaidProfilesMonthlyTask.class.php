<?php

class reAllocationPaidProfilesMonthlyTask extends sfBaseTask
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
		$this->name             = 'paidProfilesMonthly';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [reAllocation:paidProfilesMonthly|INFO] task does things.
Call it with:
[php symfony reAllocation:paidProfilesMonthly|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
    		// SET BASIC CONFIGURATION
		sfContext::createInstance($this->configuration);
		$processObj=new PROCESS();
		$agentBucketHandlerObj=new AgentBucketHandler();
                $processObj->setMethod("REALLOCATION");
                $processObj->setSubMethod("CENTRAL_RENEWAL_MONTHLY");
		
		// setting dates for picking up paid profiles 
		$start_dt = date("Y-m-d", strtotime('first day of last month'))." 00:00:00";
		$end_dt = date("Y-m-d", strtotime('last day of last month'))." 23:59:59";
		$processObj->setStartDate($start_dt);
		$processObj->setEndDate($end_dt);

		// Using DeAllocation process to first deallocate profiles
		$processObj->setProcessName("DeAllocation");
		$agentBucketHandlerObj->deallocate($processObj);
		echo "DeAllocation of Paid Field Sales Profiles Completed !!! \n";
	}
}
