<?php

class updateDeAllocationAgentTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'deAllocation';
    $this->name             = 'updateDeAllocationAgent';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateDeAllocationAgent|INFO] task does things.
Call it with:

  [php symfony updateDeAllocationAgent|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
	sfContext::createInstance($this->configuration);
	$deAllocTrackObj=new incentive_DEALLOCATION_TRACK();
	$cdaObj=new CRM_DAILY_ALLOT();
	$profiles=$deAllocTrackObj->getLastDayDeallocatedProfiles();
	for($i=0;$i<count($profiles);$i++)
	{
		$details=$cdaObj->getLastAllocationDetails($profiles[$i]['PROFILEID']);
		$agent=$details['ALLOTED_TO'];
		$deAllocTrackObj->updateDeAllocatedAgent($profiles[$i],$agent);	
	}
  }
}
