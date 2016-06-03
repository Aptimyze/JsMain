<?php

class ftaMisDataTask extends sfBaseTask
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
    $this->name             = 'ftaMisData';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ftaMisData|INFO] task does things.
Call it with:

  [php symfony ftaMisData|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	$misHandlerObj=new misGenerationhandler();
	$processObj=new PROCESS();
	$processObj->setProcessName("FTA_EFFICIENCY_MIS");
	$profiles=$misHandlerObj->fetchProfiles($processObj);
	$misHandlerObj->saveProfiles($profiles,$processObj);
	$misHandlerObj->updateProfiles();
	echo "Done !!!";
  }
}
