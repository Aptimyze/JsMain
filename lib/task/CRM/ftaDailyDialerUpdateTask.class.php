<?php
/*******************
@author: lakshay
********************/


class ftaDailyDialerUpdateTask extends sfBaseTask
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
    $this->name             = 'ftaDailyDialerUpdate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ftaDailyDialerUpdate|INFO] task does things.
Call it with:

  [php symfony ftaDailyDialerUpdate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {	
	$processObj=new PROCESS();
	$processObj->setProcessName("FTA_DIALER_UPDATE");
	$csvHandler=new csvGenerationHandler();	
	list($ignore_profiles,$eligible_profiles,$campArray)=$csvHandler->fetchProfiles($processObj);
	$csvHandler->removeInEligibleProfiles($ignore_profiles,$campArray);
	$csvHandler->UpdateEligibleProfiles($eligible_profiles,$campArray);	
	echo "Done";	
  }
}
