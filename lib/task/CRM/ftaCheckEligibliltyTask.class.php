<?php
/*******************
@author: lakshay
********************/

class ftaCheckEligibliltyTask extends sfBaseTask
{
  protected function configure()
  {
	$this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
     ));

    $this->namespace        = 'csvGeneration';
    $this->name             = 'ftaCheckEligibility';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
    The [ftaProcess|INFO] task does things.
Call it with:

  [php symfony ftaProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $processObj=new PROCESS();
	$processObj->setProcessName("FTA_CHECK_ELIGIBLE");
	$csvHandler=new csvGenerationHandler();
	$csvHandler->fetchProfiles($processObj);
	echo "Done";
  }
}
