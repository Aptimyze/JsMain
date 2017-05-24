<?php

class salesRegistrationProcessTask extends sfBaseTask
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

    $this->namespace        = 'csvGeneration';
    $this->name             = 'salesRegistrationProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [salesRegistrationProcess|INFO] task does things.
Call it with:

  [php symfony salesRegistrationProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        /* Code section for regular Sales process 
           Code section used in case of large data-set          
        */
	if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

                $csvHandler=new csvGenerationHandler();
                $processObj=new PROCESS();
                $processObj->setProcessName("SALES_REGISTRATION");
                $csvHandler->removeOldProfiles($processObj);

		// Section to use temporary table for filtering large data-set
                $profiles=$csvHandler->fetchProfiles($processObj);
		$csvHandler->storeTemporaryProfiles($processObj,$profiles);
                $csvHandler->preFilter($processObj);
		$profiles=$csvHandler->fetchTemporaryProfiles($processObj);
		$eligibleProfiles =$csvHandler->filterProfiles($processObj,$profiles);
		$csvHandler->saveProfileSet($processObj,$eligibleProfiles);	
  }
}
