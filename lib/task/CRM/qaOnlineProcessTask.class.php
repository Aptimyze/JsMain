<?php

class qaOnlineProcessTask extends sfBaseTask
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
    $this->name             = 'qaOnlineProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [dailyGharpayProcess|INFO] task does things.
Call it with:

  [php symfony qaOnlineProcess|INFO]
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
                $processObj->setProcessName("QA_ONLINE");

		$curDate =date("Y-m-d");
		$startDate =date('Y-m-d',time()-86400)." 00:00:00";
		$endDate =date('Y-m-d',time()-86400)." 23:59:59";
		$processObj->setCurDate($curDate);	
		$processObj->setStartDate($startDate);
		$processObj->setEndDate($endDate);

                $profiles =$csvHandler->fetchProfiles($processObj);
		if(count($profiles)>0){
			foreach($profiles as $key=>$profileArr){
				$processObj->setState($key);
				$profileDetails =$profileDetails =$csvHandler->fetchProfilesDetail($processObj,$profileArr);		
				$csvHandler->saveProfileSet($processObj,$profileDetails);
			}
		}		
  }
}
