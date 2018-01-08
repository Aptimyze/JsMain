<?php
/*******************
@author: lakshay
********************/

class ftaProcessTask extends sfBaseTask
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
    $this->name             = 'ftaProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ftaProcess|INFO] task does things.
Call it with:

  [php symfony ftaProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);
		$csvHandler=new csvGenerationHandler();
		$processObj=new PROCESS();
		$processObj->setProcessName("FTA_REGULAR");
		//$processObj->setProcessName("FTA_ONE_TIME");
		$csvHandler->removeOldProfiles($processObj);
		$profiles=$csvHandler->fetchProfiles($processObj);
		//print_r($profiles);
		$filteredProfiles=$csvHandler->filterProfiles($processObj,$profiles);
		//print_r($filteredProfiles);
		$filteredProfiles=$csvHandler->getProfilesFTAScore($filteredProfiles,$processObj);
		//print_r($filteredProfiles);
		$csvHandler->saveProfileSet($processObj,$filteredProfiles);
		echo "Done !!!";
  }
}
