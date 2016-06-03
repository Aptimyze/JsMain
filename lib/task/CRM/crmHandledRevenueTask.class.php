<?php

class crmHandledRevenueTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
	new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
     ));

    $this->namespace        = 'MisGeneration';
    $this->name             = 'crmHandledRevenue';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [crmHandledRevenue|INFO] task does things.
Call it with:

  [php symfony crmHandledRevenue|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        $misHandlerObj=new misGenerationhandler();
        $processObj=new PROCESS();
        $processObj->setProcessName("CRM_HANDLED_REVENUE");

	// Handling of Pooled profiles
	$processObj->setIdAllot('1');	
	$misHandlerObj->handleMonthlyIncentivePool($processObj);

	// Handling of Fresh payments profiles based on last receiptid	
	$processObj->setMethod('NEW_PROFILES');
        $profiles =$misHandlerObj->fetchProfiles($processObj);
	$profiles =$misHandlerObj->filterProfiles($profiles,$processObj);	
        $misHandlerObj->saveProfiles($profiles,$processObj);

	// Handling of Manually Back-dated Allocation profiles	
	$processObj->setMethod('MANUAL_ALLOT');
	$processObj->setIdAllot('2');	
        $profiles =$misHandlerObj->fetchProfiles($processObj);
        $profiles =$misHandlerObj->filterProfiles($profiles,$processObj);
        $misHandlerObj->saveProfiles($profiles,$processObj);

        echo "Done !!!";

  }
}
