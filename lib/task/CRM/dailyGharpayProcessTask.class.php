<?php

class dailyGharpayProcessTask extends sfBaseTask
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
    $this->name             = 'dailyGharpayProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [dailyGharpayProcess|INFO] task does things.
Call it with:

  [php symfony dailyGharpayProcess|INFO]
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
                $processObj->setProcessName("DAILY_GHARPAY");
		$curDate =date("Y-m-d");
		$processObj->setCurDate($curDate);	

                $profiles =$csvHandler->fetchProfiles($processObj);
		$csvHandler->saveProfileSet($processObj,$profiles);	

  }
}
